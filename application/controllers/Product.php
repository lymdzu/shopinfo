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
        $this->vars['row'] = "goods";
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

    /**
     * 保存产品属性
     * @auther lymdzu@hotmail.com
     */
    public function save_product_cate()
    {
        $cate_id = $this->input->post("cate_id");
        $cate_name = $this->input->post("name");
        $product_cate1 = $this->input->post("product_cate1");
        $product_cate2 = $this->input->post("product_cate2");
        $product_cate3 = $this->input->post("product_cate3");
        $product_cate4 = $this->input->post("product_cate4");
        if (empty($cate_id)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少类目id");
        }
        if (empty($product_cate1)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少商品属性一");
        }
        if (empty($product_cate2)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少商品属性二");
        }
        if (empty($product_cate3)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少商品属性三");
        }
        if (empty($product_cate4)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少商品属性四");
        }
        $this->load->model('ProductModel', "product", true);
        $save_status = $this->product->save_prodcut_cate($cate_id, $cate_name, $product_cate1, $product_cate2, $product_cate3, $product_cate4);
        if ($save_status) {
            $this->json_result(REQUEST_SUCCESS, "商品属性添加成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器出错");
        }
    }
}