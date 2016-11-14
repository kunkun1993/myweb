<?php
namespace Admin\Controller;

class LandController extends AdminBaseController
{
    public function login()
    {
        $this->display();
    }

    // AJAX判断用户名是否存在
    public function ajaxlogin()
    {
        if (!IS_AJAX){
            echo ' ';
        }

        if (!empty($_GET['name'])) {
            $map['name'] = $_GET['name'];
            $data = M('admin')->where($map)->count();
            // echo $data;
            if ($data != 1) {
                echo "<span style='color:red'>用户名不存在！</sapn>";
            } 
        }
    }

    // 登陆判断
    public function dologin()
    {
        $map['name'] = $_POST['name'];
        $map['password'] = md5($_POST['password']);
        $data = M('admin')->where($map)->count();

        if ($data != 1){
            $this->error('登录失败！');
            exit;
        }
        // var_dump($data);

        // $_SESSION['user'] = $map['name'];

        // var_dump($_SESSION);


        // $this->success('登陆成功',U('Index/index'));
        $this->redirect('Index/index');
    }


    public function loginout()
    {

    }
}