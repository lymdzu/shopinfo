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

    /**
     * 商品属性类目
     */
    public function category_list()
    {
        $this->load->model("ProductModel", "product", true);
        $cate_list = $this->product->get_product_category();
        $this->vars['category_list'] = $cate_list;
        $this->page("product/category_list.html");
    }
}