<?php


namespace app\admin\controller;


use app\common\controller\Base;

class User extends Base
{
    public function initialize()
    {
        if (!session('?user')) {
            $this->exception('', '未登录', '401');
        }
        if (session('user.role') != 'admin') {
            $this->exception('', '不是管理员', '401');
        }
    }

//获取所有用户
    public function getUsers()
    {
        $id = input('get.id/d');
        if ($id) {//如果指定id
            $user = model('user')->getUser($id);
            if ($user) {
                return $this->returnText($user, '', 200);
            }
            $this->exception('', '没有这个用户', 500);
        }
        $data = [
            'page' => input('get.page/d'),
            'pre_page' => input('get.pre_page/d'),
            'order' => input('get.order')
        ];
        if (!in_array($data['order'], ['asc', 'desc'])) {//如果排序没有选择升序或者降序
            $data['order'] = 'asc';
        }
        if (empty($data['page']) || $data['page'] < 1) {//如果没有选择页数
            $data['page'] = 1;
        }
        if (empty($data['pre_page']) || $data['page'] < 1) {//如果没有指定每页有多少
            $data['pre_page'] = 10;
        }
        $users = model("user")->getUsers($data);
        if ($users) {
            return $this->returnText($users, '', 200);
        }
        $this->exception('', '未获得用户信息', 500);
    }

//搜索用户
    public function searchUser()
    {
        $username=input('post.username');
        $user=model('user')->searchUser($username);
        if ($user){
            return $this->returnText($user,'',200);
        }
        return $this->returnText('','',200);
    }

//删除一个用户
    public function deleteUser()
    {
        $id = input('delete.id');
        $result = model('user')->deleteUser($id);
        if ($result == 1) {
            return $this->returnText('删除成功', '', 200);
        }
        $this->exception('', '删除失败', 500);
    }

    //更改用户信息
    public function changeUser()
    {
        $data = [
            "id" => input("put.id"),
            "username" => input("put.username"),
            "role" => input("put.role"),
            "password" => input("put.password")
        ];
        $validate = new \app\admin\Validate\User;
        if (!$validate->scene('change')->check($data)) {
            return $this->returnText((object)[], $validate->getError(), 406);
        }
        $result = model("user")->changeUser($data);
        if ($result == 1) {
            return $this->returnText('更改成功', '', 200);
        }
        $this->exception('', '更改失败', 500);
    }

}