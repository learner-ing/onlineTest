<?php


namespace app\index\controller;


use app\common\controller\Base;
use think\Db;

class Exam extends Base
{
    protected function initialize()
    {
        if (!session('?user.id')) {
            $this->exception('', '未登录', '401');
        } elseif (session("user.role") == 'admin') {
            $this->exception('', '管理员不能答题', '401');
        }
    }

    //获取答题列表
    public function getExams()
    {
        $id = input('get.id/d');
        if ($id) {
            $questions = model('exam')->getQuestions($id);
            if (is_object($questions)) {
                return $this->returnText($questions, '', 200);
            }
            return $this->returnText('', $questions, 401);
        }
        $exams = model('exam')->getExams();
        return $this->returnText($exams, '', 200);
    }

    //提交题目答案
    public function examSubmit()
    {
        $eid = input('post.id');
        $answer = json_decode(input('post.answers'));
        $result = model('exam')->examSubmit($eid, $answer);
        if (is_numeric($result)) {
            return $this->returnText($result . '分', '', 200);
        }
        return $this->returnText('', $result, 401);
    }

    public function rank()
    {
        $rank=model('exam')->rank();
        return $this->returnText($rank,'',200);
    }
}