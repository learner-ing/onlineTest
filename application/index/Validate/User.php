<?php


namespace app\index\Validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username|用户名' => 'require|max:10',
        'email|邮箱'=>'require|email|max:30',
        'password|密码' => 'require',
        'passwordConfirm|确认密码'=>'require|confirm:password',
        'rememberMe' => 'boolean',
        'role|角色'=>'require'
    ];
    public function sceneLogin()
    {
        return $this->only(['email','password','rememberMe']);
    }
    public function sceneRegister()
    {
        return $this->only(['username','password','passwordConfirm','rememberMe','role','email'])->append('email', 'unique:user');
    }
    public function sceneChangeInfo()//验证更新的用户信息
    {
        return $this->only(['email','username']);
    }
}