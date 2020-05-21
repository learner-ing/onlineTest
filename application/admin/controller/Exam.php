<?php


namespace app\admin\controller;


use app\common\controller\Base;

class Exam extends Base
{
    public function initialize()
    {
        if (!session('?user')) {
            $this->exception('', '未登录', '401');
        }
        if (session('user.role') != 'admin') {
            $this->exception('', '不是管理员', '401');
        }
    }

    //获取所有题目和单个题目
    public function getExams()
    {
        $eid = input('get.id/d');
        if ($eid) {//如果指定id
            $exam = model('exam')->getExam($eid);
            if ($exam) {
                return $this->returnText($exam, '', 200);
            }
            $this->exception('', '没有这个答题信息', 500);
        }
        $data = [
            'page' => input('get.page/d'),
            'pre_page' => input('get.pre_page/d'),
            'order' => input('get.order')
        ];
        if (!in_array($data['order'], ['asc', 'desc'])) {//如果排序没有选择升序或者降序
            $data['order'] = 'asc';
        }
        if (empty($data['page']) || $data['page'] < 1) {//如果没有选择页数
            $data['page'] = 1;
        }
        if (empty($data['pre_page']) || $data['page'] < 1) {//如果没有指定每页有多少
            $data['pre_page'] = 10;
        }
        $exams = model('exam')->getExams($data);
        return $this->returnText($exams, '', 200);
    }

    public function addExam()//添加答题
    {
        $data = [
            'title' => input('post.title'),
            'notes' => input('post.notes'),
            'start_time' => input('post.start_time'),
            'end_time' => input('post.end_time'),
            'answer_time' => input('post.answer_time'),
            'multi_count' => input('post.multi_count'),//多选个数,前端设置默认值
            'radio_count' => input('post.radio_count'),//单选个数,前端设置默认值
            'multi_score' => input('post.multi_score'),//多选每题分数,前端设置默认值
            'radio_score' => input('post.radio_score')//单选每题分数,前端设置默认值
        ];
        if ($data['start_time'] >= $data['end_time']) {
            return $this->returnText((object)[], '开始时间必须小于结束时间', 406);
        }
        $validate = new \app\admin\Validate\Exam();
        if (!$validate->scene('addExam')->check($data)) {
            return $this->returnText((object)[], $validate->getError(), 406);
        }
        $result = model('exam')->addExam($data);
        if ($result == 1) {
            return $this->returnText('添加成功', '', 200);
        }
        $this->exception('', '添加失败', '500');
    }

    //搜索答题
    public function searchExam()
    {
        $title = input('post.title');
        $exam = model('exam')->searchExam($title);
        if ($exam) {
            return $this->returnText($exam, '', 200);
        }
        return $this->returnText('', '', 200);

    }

    //删除答题
    public function deleteExam()
    {
        $id = input('delete.id');
        $result = model('exam')->deleteExam($id);
        if ($result == 1) {
            return $this->returnText('删除成功', '', 200);
        }
        $this->exception('', '删除失败', '500');
    }

    //更改答题信息
    public function changeExam()
    {
        $id = input('put.id/d');
        if (!$id) {
            $this->exception('', 'id不能为空', 401);
        }
        $data = [
            'start_time' => input('put.start_time/d'),//多选个数,前端设置默认值
            'end_time' => input('put.end_time/d'),//单选个数,前端设置默认值
            'answer_time' => input('put.answer_time/d'),//多选每题分数,前端设置默认值
            'notes' => input('put.notes'),//多选每题分数,前端设置默认值
        ];
        $result = model('exam')->changeExam($id, $data);
        if ($result == 1) {
            return $this->returnText('更新成功', '', 200);
        }
        $this->exception('', '更新失败', '500');
    }

    //分页查询查询所有题目
    public function getQuestions()
    {
        $data = [
            'page' => input('get.page/d'),
            'pre_page' => input('get.pre_page/d'),
            'order' => input('get.order')
        ];
        if (!in_array($data['order'], ['asc', 'desc'])) {//如果排序没有选择升序或者降序
            $data['order'] = 'asc';
        }
        if (empty($data['page']) || $data['page'] < 1) {//如果没有选择页数
            $data['page'] = 1;
        }
        if (empty($data['pre_page']) || $data['page'] < 1) {//如果没有指定每页有多少
            $data['pre_page'] = 10;
        }
        $questions = model('exam')->getQuestions($data);
        return $this->returnText($questions, '', 200);
    }

    //添加题目
    public function addQuestions()
    {
        $data = [
            'title' => trim(input('post.title')),
            'options' => input('post.options'),
            'answer' => trim(input('post.answer'))
        ];
        for ($i = 0; $i < count($data['options']); $i++) {
            $data['options'][$i] = trim($data['options'][$i]);
        }
        $validate = new \app\admin\Validate\Exam();
        if (!$validate->scene('addQeustion')->check($data)) {
            return $this->returnText((object)[], $validate->getError(), 406);
        }
        $str = strtoupper($data['answer']);
        $arr = str_split($str);
        if (count($arr) > 1) {
            $data['flag'] = 1;
        } else {
            $data['flag'] = 0;
        }
        asort($arr);
        $str = implode('', $arr);
        $data['answer'] = $str;
        $result = model('exam')->addQuestions($data);
        if ($result) {
            return $this->returnText('添加成功', '', 200);
        }

        $this->exception('', '添加失败', 500);
    }

    //搜索题目
    public function searchQuestion()
    {
        $title = input('post.title');
        $reslut = model('exam')->searchQuestion($title);
        return $this->returnText($reslut, '', 200);
    }

    //删除题目
    public function deleteQuestions()
    {
        $id = input('delete.id');
        $result = model('exam')->deleteQuestion($id);
        if ($result == 1) {
            return $this->returnText('删除成功', '', 200);
        }
        $this->exception('', '删除失败', 500);
    }

    //排名
    public function rank()
    {
        $eid=input('get.id/d');
        $rank=model('exam')->rank($eid);
        return $this->returnText($rank,'',200);
    }
}