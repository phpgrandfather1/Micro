<?php
//products控制器
namespace Controller\Admin;
class ProductsController extends \Core\Controller
{
    public function listAction()
    {
        //调用products模型
        $model = new \Model\ProductsModel();
        $list = $model->getList();
        //引入视图
        require __VIEW__ . 'products_list.html';
    }

    //删除
    public function delAction()
    {
        $id = (int)$_GET['proid'];  //获取删除的id
        $model = new \Model\ProductsModel();
        if ($model->del($id))
            $this->success('index.php?p=Admin&c=Products&a=list', '删除成功');
        else
            $this->error('index.php?p=Admin&c=Products&a=list', '删除失败');
    }
}

