<?php

class ProductModel extends CI_Model
{
    /**
     *
     * @return mixed
     * @author liuyongming@shopex.cn
     */
    public function get_product_category()
    {
        $query = $this->db->query("SELECT t.id as cate_id, c.id, t.name, t.level, c.cate_name as catename, c.product_cate1,  c.product_cate2,  c.product_cate3,  c.product_cate4 FROM `t_type` AS t LEFT JOIN `t_product_category` AS c ON c.cate_id=t.id WHERE t.level=3 ");
        return $query->result_array();
    }

    public function get_cate_info($cate_id)
    {
        $query = $this->db->query("SELECT * FROM t_type WHERE id=? ", $cate_id);
        return $query->row_array();
    }
    public function get_pro_cate_by_cateid($cate_id)
    {
        $query = $this->db->query("SELECT * FROM t_product_category WHERE cate_id=? ", $cate_id);
        return $query->row_array();
    }

    /**
     *
     * @param $cate_id
     * @param $cate_name
     * @param $product_cate1
     * @param $product_cate2
     * @param $product_cate3
     * @param $product_cate4
     * @return bool
     * @author liuyongming@shopex.cn
     */
    public function save_prodcut_cate($cate_id, $cate_name, $product_cate1, $product_cate2, $product_cate3, $product_cate4)
    {
        $this->db->query("INSERT INTO `t_product_category` (cate_id, cate_name, product_cate1, product_cate2, product_cate3, product_cate4, status) VALUES ('{$cate_id}', '{$cate_name}', '{$product_cate1}', '{$product_cate2}', '{$product_cate3}', '{$product_cate4}', 1) ON DUPLICATE KEY UPDATE product_cate1 = VALUES (product_cate1), product_cate2 = VALUES (product_cate2), product_cate3 = VALUES (product_cate3), product_cate4 = VALUES (product_cate4)");
        if ($this->db->affected_rows() >= 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param $id
     * @param $cate_id
     * @param $cate_name
     * @param $product_cate1
     * @param $product_cate2
     * @param $product_cate3
     * @param $product_cate4
     * @return bool
     * @author liuyongming@shopex.cn
     */
    public function update_prodcut_cate($id, $cate_id, $cate_name, $product_cate1, $product_cate2, $product_cate3, $product_cate4)
    {
        log_message("info", "update product cate, id:{$id},cate_id:{$cate_id}, cate_name:{$cate_name}, product_cate1:{$product_cate1}, product_cate2:{$product_cate2}, product_cate3:{$product_cate3}, product_cate4:{$product_cate4}");
        $this->db->query("UPDATE `t_product_category`  SET cate_id = {$cate_id}, cate_name = '{$cate_name}', product_cate1 = '{$product_cate1}', product_cate2 = '{$product_cate2}', product_cate3 = '{$product_cate3}', product_cate4 = '{$product_cate4}' WHERE id={$id}");
        if ($this->db->affected_rows() >= 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取商品属性可选列表
     * @param $cate_id
     * @param $cate_type
     * @return mixed
     * @auther lymdzu@hotmail.com
     */
    public function get_cate_list($cate_id, $cate_type)
    {
        $query = $this->db->query("SELECT p.id, p.cate_name, p.create_time, t.name as pro_name FROM t_pro_cate as p LEFT JOIN t_type as t ON p.cate_id = t.id WHERE cate_id = {$cate_id} and cate_type = {$cate_type}");
        return $query->result_array();
    }

    public function add_cate($cate_id, $cate_type, $cate_name)
    {
        $data = array("cate_id" => $cate_id, "cate_type" => $cate_type, "cate_name" => $cate_name, "create_time" => time());
        $insert_status = $this->db->insert("t_pro_cate", $data);
        $affect_rows = $this->db->affected_rows();
        if ($insert_status && $affect_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function edit_cate($id, $name)
    {
        $this->db->where("id", $id);
        $update_status = $this->db->update("t_pro_cate", array("cate_name" => $name));
        $affect_rows = $this->db->affected_rows();
        if ($update_status && $affect_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function delete_cate($id)
    {
        $this->db->where("id", $id);
        $update_status = $this->db->delete("t_pro_cate");
        $affect_rows = $this->db->affected_rows();
        if ($update_status && $affect_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}