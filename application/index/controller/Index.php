<?php

namespace app\index\controller;

use app\common\controller\Base;
class Index extends Base
{
    public function login()//登录
    {
        $data = [
            "email" => input("post.email"),
            "password" => md5(input("post.password")),
            "rememberMe" => input("remember_me")
        ];
        $validate = new \app\index\Validate\User;
        if (!$validate->scene('login')->check($data)) {
            return $this->returnText((object)[],$validate->getError(),406);
        }
        $result = model('user')->login($data);
        if ($result) {
            return $this->returnText($result, false,200);
        }
        return $this->returnText((object)[],'用户名或密码错误', 200);
    }

    public function register()//注册
    {
        $data = [
            "username" => input('post.username'),
            "email" => input("post.email"),
            "password" => md5(input("post.password")),
            "passwordConfirm" => md5(input("post.password_confirm")),
            "role" => input("post.role")
        ];
        //判断是否选的是学生和教师
        if (!in_array($data['role'], ['student', 'teacher'])) {
            return $this->returnText((object)[],'角色选择错误', 406);
        }
        $validate = new \app\index\Validate\User;
        if (!$validate->scene('register')->check($data)) {
            return $this->returnText($validate->getError());
        }
        $result = model('user')->register($data);
        if ($result == 1) {
            return $this->returnText('注册成功');
        }
        return $this->exception((object)[],'注册失败',500);
    }
}
