<?php


namespace app\index\model;


use think\Db;
use think\Model;

class Exam extends Model
{
    //获取答题列表
    public function getExams()
    {
        //获取还未开始和正在进行中的答题
        $exams = Exam::field('id,title,notes,answer_time,start_time,end_time')->where('end_time', '>', time())->select();
        return $exams;
    }

    //获取单个答题的具体题目
    public function getQuestions($id)
    {
        //获取用户id
        $uid = session('user.id');
        //先要获得这个答题的结束时间
        $exam = Exam::where('id', $id)->find();
        //没有这个题目或者答题时间结束
        if (!$exam || $exam['end_time'] < time()) {
            return "答题不存在或已过期";
        }
        //判断用户是否已经做过这个题
        $is_submit = db('answers')->where(['eid' => $id, 'uid' => $uid])->find();
        //用户已经答过这个答题
        if ($is_submit && $is_submit['use_time']) {
            return "你已经提交过答案";
        }
        //记录各个答题的开始时间
        if (session('?begin')) {
            $sessionData = session('begin');
            if (empty($sessionData[$id])) {
                $sessionData[$id] = time();
            }
        } else {
            $sessionData = [
                $id => time(),
            ];
        }
        //判断用户剩余答题时间
        session('begin', $sessionData);
        if (session("begin.$id") + ($exam['answer_time'] * 60) > $exam['end_time']) {//如果用户开始时间+答题时间大于结束时间
            $time = $exam['end_time'] - time();
        } else {
            $time = $exam['answer_time'] * 60 - time() + session("begin.$id");
        }
        $exam_info = db('exam_info')->field('radio_count,multi_count,radio_score,multi_score')->where('eid', $id)->find();//获取单选个数和分数
        //获取题目
        if ($is_submit && $is_submit['qids']) {//已经有题目
            $questions = json_decode($is_submit['qids']);
            $radio = db('questions')->field("flag", true)->where(['flag' => 0, 'id' => $questions])->select();
            $multi = db('questions')->field("flag", true)->where(['flag' => 1, 'id' => $questions])->select();
        } else {//还没有题目
            //线随机获取id，再利用in 获取具体题目
            if ($exam_info['radio_count'] != 0) {
                $ids = db('questions')->field("id")->where('flag', 0)->limit($exam_info['radio_count'])->orderRaw('rand()')->select();//从question表中随机获取题目，0表示单选，1表示多选
                for ($i = 0; $i < count($ids); $i++) {
                    $radioId[$i] = $ids[$i]['id'];
                }
            } else {
                $radioId = [];
            }
            if ($exam_info['multi_count'] != 0) {
                $ids = db('questions')->field("id")->where('flag', 1)->limit($exam_info['multi_count'])->orderRaw('rand()')->select();
                for ($i = 0; $i < count($ids); $i++) {
                    $multiId[$i] = $ids[$i]['id'];
                }
            } else {
                $multiId = [];
            }
            $radio = db('questions')->field("flag", true)->where(['id' => $radioId])->select();
            $multi = db('questions')->field("flag", true)->where(['id' => $multiId])->select();
        }
        //给每个单选赋分数
        for ($i = 0; $i < count($radio); $i++) {
            if (!$radio[$i]['id']) {
                break;
            }
            $radio[$i]['score'] = $exam_info['radio_score'];
            $qids[$i] = $radio[$i]['id'];
        }
        $j = $i;
        //给每个多选赋分数
        for ($i = 0; $i < count($multi); $i++) {
            if (!$multi[$i]['id']) {
                break;
            }
            $multi[$i]['score'] = $exam_info['multi_score'];
            $qids[$i + $j] = $multi[$i]['id'];
        }
        //如果是第一次答题就记录用户答的那些题目
        if (!$is_submit) {
            $qids = json_encode($qids);
            db('answers')->insert(['eid' => $id, 'uid' => $uid, 'qids' => $qids]);
        }
        $question = ["radio" => $radio, "multi" => $multi];
        //返回questions
        $exam['questions'] = $question;
        $exam["time"] = $time;
        return $exam;
    }

    public function examSubmit($eid, $userAnswers)
    {
        $uid = session('user.id');
        //先要获得这个答题的结束时间
        $exam = Exam::where('id', $eid)->find();
        //没有这个题目或者答题结束
        if (!$exam || $exam['end_time'] < time()) {
            return "题目不存在或已过期";
        }
        //判断用户剩余答题时间
        if (session("begin.$eid") + ($exam['answer_time'] * 60) > $exam['end_time']) {//如果用户开始时间+答题时间大于结束时间
            $time = $exam['end_time'] - time();
        } else {
            $time = $exam['answer_time'] * 60 - time() + session('begin.' . $eid);
        }
        //判断用户是否已经做过这个题
        $is_submit = db('answers')->where(['eid' => $eid, 'uid' => $uid])->find();
        //已经结束或者用户已经答过这个答题
        if ($is_submit && $is_submit['use_time']) {
            return "你已经提交过";
        }
        //剩余时间小于0
        if ($time <= 0) {
            if ($is_submit) {
                db('answers')->update(['eid' => $eid, 'uid' => $uid, 'score' => 1, "use_time" => time() - session("begin.$eid")]);
            } else {
                db('answers')->insert(['eid' => $eid, 'uid' => $uid, 'score' => 1, "use_time" => time() - session("begin.$eid")]);
            }
            session("begin.$eid", null);
            return "时间已过期";
        }
        //获取用户题目
        $ids = json_decode($is_submit['qids']);
        if (count($ids) != count($userAnswers)) {
            return "提交的答案有问题";
        }

        //获取正确答案
        $rightRadioAnswers = db('questions')->field('answer')->where(['flag' => 0, 'id' => $ids])->select();
        $rightMultiAnswers = db('questions')->field('answer')->where(['flag' => 1, 'id' => $ids])->select();
        //获取单选和多选分数
        $optionScore = db('exam_info')->field('radio_score,multi_score')->where('eid', $eid)->find();
        $score = 0;
        //计算单选分数
        for ($i = 0; $i < count($rightRadioAnswers); $i++) {
            if ($rightRadioAnswers[$i]['answer'] == $userAnswers[$i]) {
                $score = $score + $optionScore['radio_score'];
            }
        }
        $j = $i;
        //计算多选分数
        for ($i = 0; $i < count($rightMultiAnswers); $i++) {
            if ($rightMultiAnswers[$i]['answer'] == $userAnswers[$i + $j]) {
                $score = $score + $optionScore['multi_score'];
            }
        }
        db('answers')->where('eid',$eid)->update(['answers' => "['" . implode("','", $userAnswers) . "']", 'score' => $score, "use_time" => time() - session("begin.$eid")]);
        session("begin.$eid", null);
        return $score;
    }

    public function rank()
    {
        $uid = session('user.id');
        $eids = db('answers')->field('eid')->where('uid', $uid)->select();
        $rank=[];
        foreach ($eids as $eid) {
            $sql = 'SELECT b.* FROM (SELECT t.*, @rownum := @rownum + 1 AS rank FROM (SELECT @rownum := 0) r,(SELECT answers.uid,answers.score,answers.use_time,exam.title FROM answers INNER JOIN exam ON answers.eid=exam.id WHERE answers.eid=? ORDER BY score DESC,use_time) AS t) AS b WHERE b.uid = ?;';
            array_push($rank,Db::query($sql, [$eid['eid'], $uid])[0]);
        }
        return $rank;
    }
}
