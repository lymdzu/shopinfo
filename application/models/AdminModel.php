<?php

/**
 * 文件名称:AdminModel.php
 * 摘    要:
 * 修改日期: 27/8/17
 */
class AdminModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 查询管理员信息
     * @param $username
     * @return mixed
     */
    public function get_admin_by_username($username)
    {
        $this->db->where("username", $username);
        $query = $this->db->get("admin");
        return $query->row_array();
    }
}