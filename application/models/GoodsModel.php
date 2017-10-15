<?php

/**
 * 文件名称:GoodsModel.php
 * 摘    要:
 * 修改日期: 16/10/17
 */
class GoodsModel extends CI_Model
{
    /**
     * 获取商品分类列表
     * @param $level
     * @return mixed
     */
    public function get_goods_type_list($level)
    {
        $this->db->where("level", $level);
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
}