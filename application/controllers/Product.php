<?php

/**
 * 文件名称:Product.php
 * 摘    要:
 * 修改日期: 10/1/18
 */
class Product extends AdController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function category_list()
    {
        $this->load->model("GoodsModel", "goods", true);
        $type_list = $this->goods->get_goods_type_list(3);
        $this->page("product/category_list.html");
    }
}