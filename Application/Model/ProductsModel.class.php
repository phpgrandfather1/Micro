<?php
namespace Model;
//products表的模型
class ProductsModel extends \Core\Model {
    //获取表数据
    public function getList() {		
            return $this->mypdo->fetchAll('select * from products');
    }
    //删除商品
    public function del($id) {
            return $this->mypdo->exec("delete from products where proid=$id");
    }
}