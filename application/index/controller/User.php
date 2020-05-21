<?php


namespace app\index\controller;


use app\common\controller\Base;

class User extends Base
{
    protected function initialize()
    {
        if (!session('?user.id')) {
            $this->exception('', '未登录', '401');
        }
    }

//退出登录
    public function logout()
    {
        session('user', null);
        return $this->returnText('退出登录成功', '', '200');
    }

    //获取用户信息
    public function getUser()
    {
        $id = session('user.id');
        $info = model('user')->getUser($id);
        return $this->returnText($info, '', 200);
    }

    //更改用户信息
    public function changeInfo()
    {
        $data = [
            "username" => input("put.username"),
            "email" => input("put.email"),
        ];
        $validate = new \app\index\Validate\User;
        if (!$validate->scene('changeinfo')->check($data)) {
            return $this->returnText((object)[], $validate->getError(), 406);
        }
        $result = model('user')->changeUserInfo($data);
        if ($result == 1) {
            return $this->returnText('更新成功', '', 200);
        }
        return $this->returnText((object)[], $result, 200);
    }

    //更改密码
    public function changePassword()
    {
        $data = [
            "oldPassword" => input('put.old_password'),
            "password" => input('put.password'),
            "passwordConfirm" => input('put.password_confirm')
        ];
        if ($data['password'] !== $data['passwordConfirm']) {
            return $this->returnText((object)[], '密码和确认密码不一致', '406');
        }
        $result = model('user')->changePassword($data);
        if ($result == 1) {
            return $this->returnText('更新成功', '', 200);
        }
        return $this->returnText((object)[], $result, 200);

    }

}