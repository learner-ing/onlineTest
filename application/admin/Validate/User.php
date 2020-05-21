<?php


namespace app\admin\Validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username|用户名' => 'require|max:10',
        'email|邮箱' => 'require|email|max:30',
        'password|密码' => 'require',
        'passwordConfirm|确认密码' => 'require|confirm:password',
        'rememberMe' => 'boolean',
        'role|角色' => 'require',
    ];

    public function sceneChange()
    {
        return $this->only(['username', 'role']);
    }
}