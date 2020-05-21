<?php

namespace app\common\controller;

use think\Controller;

class Base extends Controller
{
    public $code = 200;
    public $error = false;
    public $data = [];

    public function _empty()
    {
        var_dump(11);
        $message = '请求异常#' ;
        $this->returnText($message, [], 404);
    }

    public function returnText($data = [],$error = null, $code = null)
    {
        $array = [
            'status' => empty($code) ? $this->code : $code,
            'error' => empty($error) ? $this->error : $error,
            'data' => $data ? $data : $this->data
        ];
        return json($array)->code($code);
    }

    public function exception($data = [],$error = null, $code = null)
    {
        $array = [
            'code' => empty($code) ? $this->code : $code,
            'error' => empty($error) ? $this->error : $error,
            'data' => $data ? $data : $this->data,
        ];
        throw new \app\common\controller\lib\BaseException($array);
    }

}