<?php
namespace Admin\Controller;

class RoleController extends AdminBaseController
{
    public function index()
    {
        $data = M('Role')->select();
        $this->assign('data',$data);
        $this->display();
    }

    public function roleadd()
    {
        $this->display();
    }

    public function doadd()
    {
        M('role')->create();
        if (M('role')->add()){
            $this->success('添加角色成功',U('Role/index'));
        } else {
            $this->error('添加角色失败');
        }
    }

    public function del()
    {
        $id = $_GET['id'];
        // 删除角色权限表中对应的数据 
        // 删除用户角色表中对象的数据
        if (M('role')->where(['id'=>$id])->delete()) {
            M('role_node')->where(['role_id'=>$id])->delete();
            M('admin_role')->where(['role_id'=>$id])->delete();
            $this->success('删除成功');
        } else{
            $this->error('删除失败');
        }
    }

    public function edit()
    {
        $id = $_GET['id'];
        $data = M('role')->where(['id'=>$id])->find();
        $this->assign('data',$data);
        $this->display();
    }

    public function doedit()
    {
        $id = $_POST['id'];
        // var_dump($_POST);
        M('role')->create();
        if (M('role')->save()) {
            $this->success('修改成功',U('Role/index'));
        } else {
            $this->error('修改失败');
        }
    }
}