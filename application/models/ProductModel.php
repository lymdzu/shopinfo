<?php

/**
 * �ļ�����:ProductModel.php
 * ժ    Ҫ:
 * �޸�����: 2018/1/10
 * ��    ��:liuyongming@shopex.cn
 */
class ProductModel extends CI_Model
{
    public function get_product_category()
    {
        $query = $this->db->query("SELECT t.id, t.name, t.level, c.cate_name as catename, c.product_cate1,  c.product_cate2,  c.product_cate3,  c.product_cate4 FROM `t_type` AS t LEFT JOIN `t_product_category` AS c ON c.cate_id=t.id WHERE t.level=3 ");
        return $query->result_array();
    }

    public function save_prodcut_cate($cate_id, $cate_name, $product_cate1, $product_cate2, $product_cate3, $product_cate4)
    {
        $this->db->query("INSERT INTO `t_product_category` (cate_id, cate_name, product_cate1, product_cate2, product_cate3, product_cate4, status) VALUES ('{$cate_id}', '{$cate_name}', '{$product_cate1}', '{$product_cate2}', '{$product_cate3}', '{$product_cate4}', 1) ON DUPLICATE KEY UPDATE product_cate1 = VALUES (product_cate1), product_cate2 = VALUES (product_cate2), product_cate3 = VALUES (product_cate3), product_cate4 = VALUES (product_cate4)");
        if ($this->db->affected_rows() >= 1) {
            return true;
        } else {
            return false;
        }
    }
}