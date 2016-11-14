<?php
namespace Admin\Controller;
class AdminController extends AdminBaseController
{
    // 用户头像管理
    public function photo()
    {
        $this->display();
    }

    public function index()
    {
        $data = M('admin')->limit(5)->order('id desc')->select();
        $this->assign('list',$data);
        $this->display();
    }

    public function adminAdd()
    {
        $this->display();
    }

    public function doAdd()
    {
        $_POST['mtime'] = time();
        $_POST['rtime'] = time();
        $_POST['password'] = md5($_POST['password']);
        M('admin')->create();
        // M('admin')->add();
        // var_dump(M('admin'));
        // var_dump($_POST);
        // exit;
        if( M('admin')->add() >0 ) {
            $this->success('添加成功', U('Admin/index'));
        } else {
            $this->error('添加失败');
            exit;
        }
    }

    // 删除操作
    public function del()
    {
        $id = $_GET['id'];

        if (M('admin')->delete($id) > 0) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    // 修改页面
    public function edit()
    {
        $id = $_GET['id'];
        $map['id'] = $id;
        $data = M('admin')->where($map)->find();
        $this->assign('data',$data);
        $this->display();
    }

    public function doEdit()
    {
        $id = $_POST['id'];
        $_POST['mtime'] = time();
        var_dump($_POST);
        M('admin')->create();
        if (M('admin')->save()) {
            $this->success('修改成功',U('admin/index'));
        } else {
            $this->error('修改失败');
        }
    }

    public function password()
    {
        $id = $_GET['id'];
        $this->assign('id',$id);
        $this->display();
    }

    public function checkPwd()
    {
        if(!IS_AJAX){
            $this->error('访问失败，请原路返回');
            exit;
        }
        $id = $_GET['id'];
        $pwd = md5($_GET['password']);
        $map['id'] = $id;
        $data = M('admin')->field('password')->where($map)->find();
        if( $pwd != $data['password']){
            echo "<span style='color:red;'>密码输入错误</span>";
        } else {
            echo "<span style='color:green;'>密码无误，请点击下一步</span>";
        }
    }

    public function newPass()
    {
        $id = $_POST['id'];
        $this->assign('id',$id);
        $this->display();
    }

    public function checked()
    {
        $id = $_POST['id'];
        $_POST['password'] = md5($_POST['password']);
        M('admin')->create();
        if (M('admin')->save()) {
            $this->success('密码修改成功！',U('admin/index'));
        } else {
            $this->error('修改失败');
        }
    }
}