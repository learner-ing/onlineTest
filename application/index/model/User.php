<?php


namespace app\index\model;


use think\Model;

class User extends Model
{
    public function register($data)//用户注册
    {
        $user = new User;
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $result = $user->allowField(true)->save($data);
        if ($result) {
            return 1;
        }
        return $result;
    }

    public function login($data)//用户登录
    {
        $user = new User;
        $result = $user->where(['email' => $data['email']])->find();
        if (password_verify($data['password'], $result['password'])) {
            unset($result['password']);
            $sessionData = [
                'id' => $result['id'],
                'email' => $result['email'],
                'role' => $result['role']
            ];
            session('user', $sessionData);
        }
        return $result;
    }

    public function getUser($id)//通过单个id获取改用户信息
    {
        $user = User::get($id);
        unset($user['password']);
        return $user;
    }

//更新用户信息
    public function changeUserInfo($data)
    {
        $id = session('user.id');
        $user = User::get($id);
        $user->username = $data['username'];
        $user->email = $data['email'];
        $result = $user->save();
        if ($result) {
            return 1;
        }
        return "更新失败";
    }

    //更新密码
    public function changePassword($data)
    {
        $id = session('user.id');
        $user = User::get($id);
        if (password_verify($data['oldPassword'], $user['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $result = $user->save();
            if ($result) {
                return 1;
            }
            return "更新失败";
        }
        return "旧密码错误";
    }

}