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
}