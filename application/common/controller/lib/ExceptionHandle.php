<?php


namespace app\common\controller\lib;


use \think\exception\Handle;
use Exception;

class ExceptionHandle extends Handle
{
    private $code;
    private $error;//错误信息
    private $status;
    private $data;

    public function render(Exception $e)
    {
        if ($e instanceof BaseException) {
            //如果是自定义异常，则控制http状态码，不需要记录日志
            //因为这些通常是因为客户端传递参数错误或者是用户请求造成的异常
            $this->code = $e->code;
            $this->error = $e->error;
            $this->status = $e->code;
            $this->data = $e->data;

        } else {
            //debug完成需要注释下面这行代码
            return parent::render($e);
            $this->code =  500;
            $this->error = '服务器错误!!!';
            $this->status =  500;
            $this->data = (object)[];

            //日志记录
            $this->recordErrorLog($e);
        }
        $result = [
            'status' => $this->status,
            'data' => $this->data,
            'error' => $this->error,
        ];
        return json($result, $this->code);
    }

    /*
   * 将异常写入日志
   */
    private function recordErrorLog(Exception $e)
    {

    }
}
