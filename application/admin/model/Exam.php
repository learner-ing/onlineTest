<?php


namespace app\admin\model;


use think\Db;
use think\Exception;
use think\Model;

class Exam extends Model
{
    //分页获取所有答题
    public function getExams($data)
    {
        $exams = db('exam')->alias('e')->join('exam_info i', 'e.id=i.eid')->field('id,title,notes,start_time,end_time,answer_time,radio_count,multi_count,radio_score,multi_score')
            ->limit(($data['page'] - 1) * $data['pre_page'], $data['pre_page'])->order('start_time', $data['order'])->select();
        $count = Exam::fieldRaw('count(id)')->find();
        $pages = $count['count(id)'] / $data['pre_page'];
        if ($pages < 1) {
            $pages = $pages + 1;
        }
        $result = ["exams" => $exams, "pages" => intval($pages), "count" => $count['count(id)']];
        return $result;
    }

    //获取单个答题
    public function getExam($id)
    {
        $exam = Exam::field('id,notes,start_time,end_time,answer_time')->where('id', $id)->find();
        return $exam;
    }

    //搜索一个答题
    public function searchExam($title)
    {
        $exam = db('exam')->alias('e')->join('exam_info i', 'e.id=i.eid')->
        field('id,title,notes,start_time,end_time,answer_time,radio_count,multi_count,radio_score,multi_score')->where('title', 'like', '%' . $title . '%')->select();
        if ($exam) {
            return $exam;
        }
        return 0;
    }

    public function addExam($data)
    {//添加答题
        $exam = new Exam;
        $info['radio_count'] = $data['radio_count'];
        $info['multi_count'] = $data['multi_count'];
        $info['multi_score'] = $data['multi_score'];
        $info['radio_score'] = $data['radio_score'];
        $exam->allowField(true)->save($data);
        $info['eid'] = $exam->id;
        try {
            $exam_info = db('exam_info')->insert($info);
        } catch (Exception $e) {
            Exam::destroy($exam->id);
            return 0;
        }
        if ($exam && $exam_info) {
            return 1;
        }
        return 0;
    }

    //删除题目
    public function deleteExam($id)
    {
        $deleteExam = Exam::destroy($id);//删除答题
        db('exam_info')->delete($id);//删除答题信息
        db('answers')->where('eid', $id)->delete();//删除答题信息
        if ($deleteExam) {
            return 1;
        }
        return 0;
    }

    //更改答题信息
    public function changeExam($id, $data)
    {
        $exam = Exam::where('id', $id)->find();
        $exam->start_time = $data['start_time'];
        $exam->end_time = $data['end_time'];
        $exam->answer_time = $data['answer_time'];
        $exam->notes = $data['notes'];
        $result = $exam->save();
        if ($result) {
            return 1;
        }
        return 0;
    }

    //获取所有题目信息
    public function getQuestions($data)
    {
        //根据id排序
        $questions = db('questions')->limit(($data['page'] - 1) * $data['pre_page'], $data['pre_page'])->order('id', $data['order'])->select();
        $count = db('questions')->fieldRaw('count(id)')->find();
        $pages = $count['count(id)'] / $data['pre_page'];
        if ($pages < 1) {
            $pages = $pages + 1;
        }
        $result = ["questions" => $questions, "pages" => intval($pages), "count" => $count['count(id)']];
        return $result;
    }

    //添加题目
    public function addQuestions($data)
    {
        $data['options'] = "['" . implode("','", $data['options']) . "']";
        $result = db('questions')->insert($data);
        return $result;
    }

    //搜索题目
    public function searchQuestion($title)
    {
        $questions = db('questions')->where('title', 'like', '%' . $title . '%')->select();
        return $questions;
    }

    //删除题目
    public function deleteQuestion($id)
    {
        $result = db('questions')->delete($id);
        if ($result) {
            return 1;
        }
        return 0;
    }

//获取某一次答题所有的排名
    public function rank($eid)
    {
        $sql = 'SELECT b.* FROM (SELECT t.*, @rownum := @rownum + 1 AS rank FROM (SELECT @rownum := 0) r,(SELECT answers.uid,answers.score,answers.use_time,user.username FROM answers INNER JOIN user ON answers.uid=user.id WHERE answers.eid=? ORDER BY score DESC,use_time) AS t) AS b WHERE 1;';
        $rank = Db::query($sql, [$eid]);
        return $rank;
    }
}
