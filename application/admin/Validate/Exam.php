<?php


namespace app\admin\Validate;


use think\Validate;

class Exam extends Validate
{
    protected $rule = [
        'title|标题' => 'require',
        'notes|备注' => 'max:60',
        'start_time|开始时间' => 'require|number',
        'end_time|结束时间' => 'require|number',
        'answer_time|答题时间' => 'require|number',
        'radio_count|单选个数' => 'require|number',
        'multi_count|多选个数' => 'require|number',
        'radio_score|单选分数' => 'require|number',
        'multi_score|多选分数' => 'require|number',
        'options|题目选项' => 'require|max:450',
        'answer|题目答案' => 'require|alpha'
    ];

    public function sceneAddExam()
    {
        return $this->only(['title', 'notes', 'start_time', 'end_time', 'answer_time', 'radio_count', 'multi_count', 'radio_score', 'multi_score',])->append('title', 'max:30');
    }
    public function sceneAddQeustion(){
        return $this->only(['title','options','answer'])->append('title','max:100');
    }
}