<?php

/**
 * 文件名称:Brand.php
 * 摘    要:
 * 修改日期: 19/10/17
 */
class Brand extends AdController
{
    public function __construct()
    {
        parent::__construct();
        $this->vars['row'] = "brand";
    }

    public function brand_list()
    {
        $this->load->model('GoodsModel', "goods", true);
        $this->load->model("UserModel", "user", true);
        $admin = $this->admin_company();
        if ($admin['auth'] == "all") {
            $company = false;
        } else {
            $company = $admin['company'];
        }
        //todo 权限角色管理
        $page = $this->input->get("page");
        $offset = empty($page) ? 0 : (intval($page) - 1) * PAGESIZE;
        $total = $this->goods->count_brand_list($company);
        $admin_list = $this->goods->get_brand_list($offset, PAGESIZE, $company);
        $this->load->library("tgpage", array('total' => $total, 'pagesize' => PAGESIZE));
        $this->vars['pagelist'] = $this->tgpage->showpage();
        $this->vars['brand_list'] = $admin_list;
        $this->vars['page'] = "brand_list";
        $this->page("brand/brand_list.html");
    }

    /**
     * 添加商品类目
     */
    public function add_brand()
    {
        $name = $this->input->post("name", true);
        $admin = $this->admin_company();
        if (empty($name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入商品类别名称");
        }
        $this->load->model("GoodsModel", "goods", true);
        $add_status = $this->goods->add_brand($name, $admin['company'], $admin['username']);
        if ($add_status) {
            $this->json_result(REQUEST_SUCCESS, "品牌添加成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器出错");
        }
    }
    /**
     * 修改商品类目
     */
    public function edit_brand()
    {
        $name = $this->input->post("name", true);
        $id = $this->input->post("id", true);
        if (empty($name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入商品类别名称");
        }
        if (empty($id)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少需要修改的类目id");
        }
        $this->load->model("GoodsModel", "goods", true);
        $edit_status = $this->goods->edit_brand($id, $name);
        if ($edit_status) {
            $this->json_result(REQUEST_SUCCESS, "品牌修改成功");
        } else {
            $this->json_result(API_ERROR, "", "修改失败请检查输入");
        }
    }

    /**
     * 删除当前及其下属类目
     */
    public function delete_brand()
    {
        $id = $this->input->post("id", true);
        if (empty($id)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少需要修改的类目id");
        }
        $this->load->model("GoodsModel", "goods", true);
        $edit_status = $this->goods->delete_goods_type($id);
        if ($edit_status) {
            $this->json_result(REQUEST_SUCCESS, "商品类目删除成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器出错");
        }
    }
}