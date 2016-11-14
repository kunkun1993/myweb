<?php
namespace Admin\Controller;

class DistributeRoleController extends AdminBaseController
{
    // 为用户分配角色
    public function index()
    {
        // 'userid'=>array(
        //             'user_id'=>
        //             'user_name'=>
        //             'role_id'=>字串
        //             'role_name'=>字串
        //             )
        $user = M('admin')->order('id asc')->select();

        $data = M('role')->order('id asc')->select();
        foreach ($data as $k => $v) {
            $role[$v['id']] = $v['rolename'];
        }

        $ar = M('admin_role')->order('user_id asc')->select();

        $arr = [];
        foreach ($user as $k => $v) {
            $arr[($v['id'])]['user_id'] = $v['id']; 
            $arr[($v['id'])]['user_name'] = $v['name']; 
        }

        foreach ($ar as $k => $v) {
            $arr[($v['user_id'])]['role_id'][] = $v['role_id'];
            $arr[($v['user_id'])]['role_name'][] = $role[($v['role_id'])];
        }

        foreach ($arr as $k => $v) {
            $arr[$k]['role_id'] = implode(',', $v['role_id']);
            $arr[$k]['role_name'] = implode(',', $v['role_name']);
        }

        $this->assign('data',$arr);
        $this->display();
    }

    public function editrole()
    {
        // user_id role_id
        $user_id = $_GET['id'];
        $this->assign('userid',$user_id);
        // var_dump($user_id);
        // 查看该角色的权限
        $data = M('admin_role')->field('role_id')->where(['user_id'=>$user_id])->select();
        // var_dump($data);
        $roleid = [];
        foreach ($data as $k => $v) {
            $roleid[] = $v['role_id'];
        }
        // var_dump($roleid);
        $this->assign('roleid',$roleid);
        // var_dump($nodeid);

        // var_dump($data);
        // 获取所有权限信息
        $data = M('role')->order('id asc')->select();
        // var_dump($data);
        $this->assign('data',$data);
        $this->display();

    }

     public function doedit()
    {
        // role = > node
        // admin = > role
        // 表中含有的数据信息
        $map['user_id'] = $_POST['user_id'];
        // var_dump($map);
        if (M('admin_role')->where($map)->delete() === false) {
            $this->error('修改失败1');
        }

        // var_dump($_POST);
        $arr = [];
        // $_POST['node_id'] = sort($_POST['node_id']);
        for ($i=0; $i < count($_POST['role_id']); $i++) { 
            $arr[$i]['user_id'] = $_POST['user_id'];
            $arr[$i]['role_id'] = $_POST['role_id'][$i];
        }

        // var_dump($arr);

        if(empty($arr)){
            $this->success('设置成功',U('DistributeRole/index'));
            exit;
        }
        M('admin_role')->create($arr);
        if (M('admin_role')->addAll($arr) !== false) {
            $this->success('设置成功',U('DistributeRole/index'));
        } else {
            $this->error('写入失败');
        }


    }
}