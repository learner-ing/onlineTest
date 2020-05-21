<?php


namespace app\common\controller\lib;

use think\Exception;

class BaseException extends Exception
{

    public $code = '400';
    public $error='服务器出现错误了';
    public $data = '';

    public $shouldToClient = true;

    /**
     * 构造函数，接收一个关联数组
     * @param array $params 关联数组只应包含code、msg和errorCode，且不应该是空值
     */
    public function __construct($params = [])
    {
        $this->data = (object)[];
        if (!is_array($params)) {
            return;
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('error', $params)) {
            $this->error = $params['error'];
        }
        if (array_key_exists('data', $params)) {
            $this->data = $params['data'];
        }
    }
}