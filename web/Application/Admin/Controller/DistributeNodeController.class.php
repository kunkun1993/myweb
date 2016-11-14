<?php
namespace Admin\Controller;

class DistributeNodeController extends AdminBaseController
{
    public function index()
    {

        // arrar(
        //     'role_id'=>1,
        //     'rolename'=>'rolename';
        //     'node_id'=>array(3,4);
        //     'nodename'=>arrar('quanxian1','quanxian2');
        //     );
        // 角色名 权限 修改权限
        // 获取角色id=>角色名

        // 用$arr存储角色对应权限的信息
        $arr = [];

        // 获取角色信息
        $role = M('role')->select();
        // 将角色的信息方法哦$arr中
        foreach ($role as $k => $v) {
            $arr[($v['id'])]['role_id'] = $v['id'];
            $arr[($v['id'])]['rolename'] = $v['rolename'];
        }
        // 获取中间表的数据
        $rn = M('role_node')->select();
        // 获取权限表的信息 格式为权限id=>权限名称
        $data = M('node')->select();
        foreach ($data as $k => $v) {
            $node[($v['id'])] = $v['nodename'];
        }
        // 将信息合并到数组中
        foreach ($rn as $k => $v) {
            // var_dump($v);
            $arr[($v['role_id'])]['node_id'][] = $v['node_id'];
            $arr[($v['role_id'])]['nodename'][] = $node[($v['node_id'])];
        }
        // 将权限集合转换为字符串
        foreach ($arr as $k => $v) {
            $arr[$k]['node_id'] = implode(',', $v['node_id']);
            $arr[$k]['nodename'] = implode(',', $v['nodename']);
        }
        // var_dump($arr);
        $this->assign('data',$arr);
        $this->display();
    }

    public function editnode()
    {
        $role_id = $_GET['id'];
        $this->assign('roleid',$role_id);
        // var_dump($role_id);
        // 查看该角色的权限
        $data = M('role_node')->field('node_id')->where(['role_id'=>$role_id])->select();
        $nodeid = [];
        foreach ($data as $k => $v) {
            $nodeid[] = $v['node_id'];
        }
        $this->assign('nodeid',$nodeid);
        // var_dump($nodeid);

        // var_dump($data);
        // 获取所有权限信息
        $data = M('node')->order('id asc')->select();
        // var_dump($data);
        $this->assign('data',$data);
        $this->display();

    }

    public function doedit()
    {
        // 表中含有的数据信息
        $map['role_id'] = $_POST['role_id'];
        if (M('role_node')->where($map)->delete() === false) {
            $this->error('修改失败1');
        }


        $arr = [];
        // $_POST['node_id'] = sort($_POST['node_id']);
        for ($i=0; $i < count($_POST['node_id']); $i++) { 
            $arr[$i]['role_id'] = $_POST['role_id'];
            $arr[$i]['node_id'] = $_POST['node_id'][$i];
        }

        // var_dump($arr);

        if(empty($arr)){
            $this->success('设置成功',U('DistributeNode/index'));
            exit;
        }
        M('role_node')->create($arr);
        if (M('role_node')->addAll($arr) !== false) {
            $this->success('设置成功',U('DistributeNode/index'));
        } else {
            $this->error('写入失败');
        }


    }
}