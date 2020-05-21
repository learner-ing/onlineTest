<?php


namespace app\admin\model;


use think\Model;

class User extends Model
{
    //获取所有用户
    public function getUsers($data)
    {
        $users = User::field('password', true)->limit(($data['page'] - 1) * $data['pre_page'], $data['pre_page'])->order('username', $data['order'])->select();
        $count=User::fieldRaw('count(id)')->find();
        $pages=$count['count(id)']/$data['pre_page'];
        if ($pages<1){
            $pages=$pages+1;
        }
        $result=["users"=>$users,"pages"=>intval($pages),"count"=>$count['count(id)']];
        return $result;
    }

//获取一个用户的信息
    public function getUser($id)
    {
        $user = User::where('id', $id)->field('password', true)->find();
        if ($user) {
            return $user;
        }
        return 0;
    }

//搜索一个用户
    public function searchUser($username)
    {
        $user = User::where('username','like', '%'.$username.'%')->field('password',true)->select();
        if ($user){
            return $user;
        }
        return 0;
    }

    //删除一个用户
    public function deleteUser($id)
    {
        $user = User::destroy($id);
        if ($user) {
            return 1;
        }
        return 0;
    }

    //更改用户信息
    public function changeUser($data)
    {
        $user = User::get($data['id']);
        $user->username = $data['username'];
        $user->role = $data['role'];
        if (!empty($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $result = $user->save();
        if ($result) {
            return 1;
        }
        return $result;
    }
}