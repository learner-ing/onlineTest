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
            // 如果是服务器未处理的异常，将http状态码设置为500，并记录日志
            if (config('APP_DEBUG')) {
                // 调试状态下需要显示TP默认的异常页面，因为TP的默认页面
                // 很容易看出问题
                return parent::render($e);
            }
            $this->code =  $e->getStatusCode();
            $this->error = '服务器错误!!!';
            $this->status =  $e->getStatusCode();
            $this->data = (object)[];

            //日志记录
            $this->recordErrorLog($e);
        }
        if ($this->code == 404) {
            $this->error = '当前页面不存在';
        } elseif ($this->code==403) {
            $this->error='没有权限访问';
        } else {
            $this->error = '服务器错误!!!';
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