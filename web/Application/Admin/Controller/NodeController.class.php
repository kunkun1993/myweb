<?php
namespace Admin\Controller;

class NodeController extends AdminBaseController
{
    public function index()
    {
        $data = M('node')->select();
        $this->assign('data',$data);
        $this->display();
    }

    public function nodeadd()
    {
        $this->display();
    }

    public function doadd()
    {
        // 将控制器名和方法名变成首字母大写
        $_POST['controller'] = strtolower($_POST['controller']);
        $_POST['controller'] = ucfirst($_POST['controller']);
        $_POST['action'] = strtolower($_POST['action']);
        M('node')->create();

        if(M('node')->add() > 0){
            $this->success('添加成功',U('Node/index'));
        } else {
            $this->error('添加失败');
        }
    }

    public function del()
    {
        // 删除该条目时需删除对应角色权限表对应的条目
        $id = $_GET['id'];
        if( M('node')->where(['id'=>$id])->delete()) {
            M('role_node')->where(['node_id'=>$id])->delete();
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    public function edit()
    {
        $id = $_GET['id'];
        $data = M('node')->where(['id'=>$id])->find();
        // var_dump($data);
        $this->assign('data',$data);
        $this->display();
    }

    public function doedit()
    {
        $id = $_POST['id'];

        if (M('node')->where(['id'=>$id])->save($_POST)){
            $this->success('修改成功',U('Node/index'));
        } else{
            $this->error('修改失败');
        }
    }


}