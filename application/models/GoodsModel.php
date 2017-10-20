<?php

/**
 * 文件名称:GoodsModel.php
 * 摘    要:
 * 修改日期: 16/10/17
 */
class GoodsModel extends CI_Model
{
    /**
     * 获取公司旗下商品列表
     * @param $offset
     * @param $limit
     * @param $company
     * @return mixed
     */
    public function get_goods_list($offset, $limit, $company)
    {
        $where_company = "";
        if ($company) {
            $where_company = "WHERE g.company={$company}";
        }
        $query = $this->db->query("select x.id, x.product, x.brand, t.name category, x.company, x.operater, x.create_time from (select g.id, g.product, b.brand, g.category, g.company, g.operater, g.create_time from t_goods g LEFT JOIN t_brand b ON g.brand = b.id" . $where_company . ") as x LEFT JOIN t_type t on x.category = t.id limit {$offset}, {$limit}");
        return $query->result_array();
    }

    /**
     * 添加商品
     * @param $product
     * @param $brand
     * @param $catogory
     * @param $company
     * @param $operater
     * @return bool
     */
    public function add_goods($product, $brand, $catogory, $company, $operater)
    {
        $insert_goods = array(
            "product"     => $product,
            "brand"       => $brand,
            "category"    => $catogory,
            "company"     => $company,
            "operater"    => $operater,
            "create_time" => time(),
            "update_time" => "",
            "status"      => 0
        );
        $insert_status = $this->db->insert("t_goods", $insert_goods);
        $affected_rows = $this->db->affected_rows();
        if ($insert_status && $affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param $company
     * @return mixed
     */
    public function count_goods_list($company)
    {
        if ($company) {
            $this->db->where("company", $company);
        }
        $this->db->from("t_goods");
        return $this->db->count_all_results();
    }

    /**
     * 获取商品分类列表
     * @param $level
     * @param bool $parent
     * @return mixed
     */
    public function get_goods_type_list($level, $parent = false)
    {
        $this->db->where("level", $level);
        if ($parent) {
            $this->db->where("parent", $parent);
        }
        $query = $this->db->get("t_type");
        return $query->result_array();
    }

    /**
     * 获取特定等级类目列表
     * @param $level
     * @param bool $parent
     * @return mixed
     */
    public function get_category_by_level($level, $parent = false)
    {
        $this->db->where("level", $level);
        if ($parent) {
            $this->db->where("parent", $parent);
        }
        $query = $this->db->get("t_type");
        return $query->result_array();
    }

    /**
     * 添加商品分类
     * @param $name
     * @param $level
     * @param $parent
     * @param $operater
     * @return bool
     */
    public function add_goods_type($name, $level, $parent, $operater)
    {
        $insert_status = $this->db->insert("t_type", array("name" => $name, "level" => $level, "parent" => $parent, "create_time" => time(), "operater" => $operater));
        $affect_rows = $this->db->affected_rows();
        if ($insert_status && $affect_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 修改商户分类名称
     * @param $id
     * @param $name
     * @return bool
     */
    public function edit_goods_type($id, $name)
    {
        $this->db->where("id", $id);
        $update_status = $this->db->update("t_type", array("name" => $name));
        $affect_rows = $this->db->affected_rows();
        if ($update_status && $affect_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_goods_type($id)
    {
        $this->db->trans_start();
        $this->db->where("id", $id);
        $this->db->delete("t_type");
        $this->db->where("parent", $id);
        $query = $this->db->get("t_type");
        $children = $query->result_array();
        $this->db->where("parent", $id);
        $this->db->delete("t_type");
        if (!empty($children)) {
            $child_where = array();
            foreach ($children as $child) {
                $child_where[] = $child['id'];
            }
            $this->db->where_in("parent", $child_where);
            $this->db->delete("t_type");
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 查询公司旗下所有品牌
     * @param $offset
     * @param $limit
     * @param $company
     * @return mixed
     */
    public function get_brand_list($offset, $limit, $company)
    {
        $this->db->limit($limit, $offset);
        if ($company) {
            $this->db->where("company", $company);
        }
        $query = $this->db->get("t_brand");
        return $query->result_array();
    }

    /**
     * 查询此公司旗下所有品牌数量
     * @param $company
     * @return mixed
     */
    public function count_brand_list($company)
    {
        if ($company) {
            $this->db->where("company", $company);
        }
        $this->db->from("t_brand");
        return $this->db->count_all_results();
    }

    /**
     * 添加品牌
     * @param $name
     * @param $company
     * @param $operater
     * @return bool
     */
    public function add_brand($name, $company, $operater)
    {
        $brand_info = array(
            "brand"       => $name,
            "company"     => $company,
            "operater"    => $operater,
            "create_time" => time(),
        );
        $insert_status = $this->db->insert("t_brand", $brand_info);
        $affected_row = $this->db->affected_rows();
        if ($insert_status && $affected_row == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 修改品牌名称
     * @param $id
     * @param $brand
     * @return bool
     */
    public function edit_brand($id, $brand)
    {
        $this->db->where("id", $id);
        $update_status = $this->db->update("t_brand", array("brand" => $brand));
        $affect_rows = $this->db->affected_rows();
        if ($update_status && $affect_rows == 1) {
            return true;
        } else {
            return false;
        }
    }
}