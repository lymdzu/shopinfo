<?php

/**
 * 文件名称:goods.php
 * 摘    要:
 * 修改日期: 27/8/17
 */
class Goods extends AdController
{
    public function __construct()
    {
        parent::__construct();
        $this->vars['row'] = "goods";
    }
    public function goods_list()
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
        $total = $this->goods->count_goods_list($company);
        $goods_list = $this->goods->get_goods_list($offset, PAGESIZE, $company);
        $this->load->library("tgpage", array('total' => $total, 'pagesize' => PAGESIZE));
        $this->vars['pagelist'] = $this->tgpage->showpage();
        $this->vars['goods_list'] = $goods_list;
        $this->vars['page'] = "goods_list";
        $this->page("goods/goods_list.html");
    }

    public function level_list()
    {
        $level = $this->input->get("level", true);
        $parent = $this->input->get("parent", true);
        $this->vars['parent'] = empty($parent) ? null : intval($parent);
        $level = empty($level) ? 1 : (intval($level) > 3 ? 3 : intval($level));
        $this->load->model("GoodsModel", "goods", true);
        $type_list = $this->goods->get_goods_type_list($level);
        $this->vars['level_list'] = $type_list;
        $this->vars['level'] = $level;
        $this->vars['next_level'] = $level + 1;
        $this->vars['page'] = "level_list";
        $this->page("goods/level_list.html");
    }

    /**
     * 添加商品类目
     */
    public function add_level()
    {
        $name = $this->input->post("name", true);
        $level = $this->input->post("level", true);
        $parent = $this->input->post("parent", true);
        if (empty($name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入商品类别名称");
        }
        if (empty($level)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请选择商品分类级别");
        }
        if ($level != 1 && empty($parent)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入分类上级");
        }
        $this->load->model("GoodsModel", "goods", true);
        $operate = $this->session->userdata("admin_user");
        $add_status = $this->goods->add_goods_type($name, $level, $parent, $operate);
        if ($add_status) {
            $this->json_result(REQUEST_SUCCESS, "商品类目添加成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器出错");
        }
    }

    /**
     * 修改商品类目
     */
    public function edit_level()
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
        $edit_status = $this->goods->edit_goods_type($id, $name);
        if ($edit_status) {
            $this->json_result(REQUEST_SUCCESS, "商品类目修改成功");
        } else {
            $this->json_result(API_ERROR, "", "修改失败请检查输入");
        }
    }

    /**
     * 删除当前及其下属类目
     */
    public function delete_level()
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

    /**
     * 重新生成商品类目
     */
    public function make_category()
    {
        $this->load->model("GoodsModel", "goods", true);
        $first_level = $this->goods->get_category_by_level(1);
        $category = array();
        if (!empty($first_level)) {
            foreach ($first_level as $first_key => $list) {
                $category[$first_key]["text"] = $list['name'];
                $category[$first_key]['state'] = array("opened" => true);
                $second_level = $this->goods->get_category_by_level(2, $list['id']);
                $second = array();
                if (!empty($second_level)) {
                    foreach ($second_level as $second_key => $second_list) {
                        $second[$second_key]["text"] = $second_list['name'];
                        $second[$second_key]["state"] = array("opened" => true);
                        $third_level = $this->goods->get_category_by_level(3, $second_list['id']);
                        $third = array();
                        if (!empty($third_level)) {
                            foreach ($third_level as $third_key => $third_list) {
                                $third[$third_key]["text"] = $third_list['name'];
                                $third[$third_key]["icon"] = 'none';
                            }
                        }
                        $second[$second_key]['children'] = $third;
                    }
                }
                $category[$first_key]['children'] = $second;
            }
        }
        $cat_json = "var json =" . json_encode($category);
        file_put_contents(dirname(APPPATH) . "/public/file/category.js", $cat_json);
        $this->json_result(REQUEST_SUCCESS, "生成成功");
    }
}