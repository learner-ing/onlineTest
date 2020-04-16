<?php


namespace app\index\model;


use think\Model;

class User extends Model
{
    public function register($data)//用户注册
    {
        $user = new User;
        $result = $user->allowField(true)->save($data);
        if ($result) {
            return 1;
        }
        return $result;
    }

    public function login($data)//用户登录
    {
        $user = new User;
        $result = $user->field('id,username,email,role')->where(['email' => $data['email'], 'password' => $data['password']])->find();
        if ($result) {
            $sessionData = [
                'id' => $result['id'],
                'email' => $result['email'],
                'role' => $result['role']
            ];
            session('user', $sessionData);
        }
        return $result;
    }
}