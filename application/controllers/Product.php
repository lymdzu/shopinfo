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

    public function list_cate()
    {
        $cate_id = $this->input->get("cate_id");
        $cate_type = $this->input->get("cate_type");
        $this->load->model("ProductModel", "product", true);
        $cate_info = $this->product->get_cate_info($cate_id);
        $pro_attr = $this->product->get_pro_cate_by_cateid($cate_id);
        $cate_list = $this->product->get_cate_list($cate_id, $cate_type);
        $this->vars['cate_list'] = $cate_list;
        $this->vars['cate_info'] = $cate_info;
        $this->vars['pro_attr'] = $pro_attr;
        $this->vars['cate_id'] = $cate_id;
        $this->vars['cate_type'] = $cate_type;
        $this->page("product/list_cate.html");
    }

    public function add_cate()
    {
        $cate_id = $this->input->post("cate_id");
        $cate_type = $this->input->post("cate_type");
        $cate_name = $this->input->post("cate_name");
        $this->load->model("ProductModel", "product", true);
        $insert_status = $this->product->add_cate($cate_id, $cate_type, $cate_name);
        if ($insert_status) {
            $this->json_result(REQUEST_SUCCESS, "类目添加成功");
        } else {
            $this->json_result(API_ERROR, "", "类目添加失败");
        }

    }
    public function edit_cate()
    {
        $id = $this->input->post("id");
        $cate_name = $this->input->post("name");
        $this->load->model("ProductModel", "product", true);
        $update_status = $this->product->edit_cate($id, $cate_name);
        if ($update_status) {
            $this->json_result(REQUEST_SUCCESS, "类目修改成功");
        } else {
            $this->json_result(API_ERROR, "", "类目修改失败");
        }
    }
    public function delete_cate()
    {
        $id = $this->input->post("id");
        $this->load->model("ProductModel", "product", true);
        $update_status = $this->product->delete_cate($id);
        if ($update_status) {
            $this->json_result(REQUEST_SUCCESS, "类目删除成功");
        } else {
            $this->json_result(API_ERROR, "", "类目删除失败");
        }
    }


    /**
     * 保存产品属性
     * @auther lymdzu@hotmail.com
     */
    public function save_product_cate()
    {
        $cate_id = $this->input->post("cate_id");
        $id = $this->input->post("id");
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
        if (empty($id)) {
            $save_status = $this->product->save_prodcut_cate($cate_id, $cate_name, $product_cate1, $product_cate2, $product_cate3, $product_cate4);
        } else {
            $save_status = $this->product->update_prodcut_cate($id, $cate_id, $cate_name, $product_cate1, $product_cate2, $product_cate3, $product_cate4);
        }

        if ($save_status) {
            $this->json_result(REQUEST_SUCCESS, "商品属性添加成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器出错");
        }
    }
    public function add_product()
    {
        $this->vars['page'] = "add_product";
        $this->page("product/add_product.html");
    }
}