<?php

/**
 * 文件名称:UserModel.php
 * 摘    要:
 * 修改日期: 30/9/17
 */
class UserModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 通过用户名获取用户信息
     * @param $username
     * @return mixed
     */
    public function get_user_by_name($username)
    {
        $this->db->where("username", $username);
        $query = $this->db->get("t_admin");
        return $query->row_array();
    }

    /**
     * 获取管理员列表
     * @return mixed
     */
    public function get_admin_list($offset, $limit)
    {
        $this->db->limit($limit, $offset);
        $query = $this->db->get("t_admin");
        return $query->result_array();
    }

    /**
     * 添加管理员
     * @param $username
     * @param $password
     * @param $salt
     * @return bool
     */
    public function insert_user($username, $password, $salt)
    {
        $admin_info = array(
            "username"    => $username,
            "password"    => $password,
            "salt"        => $salt,
            "create_time" => time()
        );
        $insert_status = $this->db->insert("t_admin", $admin_info);
        $affected_row = $this->db->affected_rows();
        if ($insert_status && $affected_row == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 操作用户
     * @param $id
     * @param $status
     * @return bool
     */
    public function operate_user($id, $status)
    {
        $this->db->where("admin_id", $id);
        $upt_res = $this->db->update("t_admin", array("user_status" => $status));
        $affected_rows = $this->db->affected_rows();
        if ($upt_res && $affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 禁止用户登录
     * @param $id
     * @param $status
     * @return bool
     */
    public function disable_user($id, $status)
    {
        $this->db->where("id", $id);
        $upt_res = $this->db->update("t_user", array("user_status" => $status));
        $affected_rows = $this->db->affected_rows();
        if ($upt_res && $affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取个人注册列表
     * @param $offset
     * @param $limit
     * @return mixed
     */
    public function get_person_user_list($offset, $limit)
    {
        $this->db->limit($limit, $offset);
        $this->db->order_by("create_time", "DESC");
        $query = $this->db->get("t_user");
        return $query->result_array();
    }

    /**
     * 计算个人注册总数
     * @return mixed
     */
    public function count_person_user_list()
    {
        $this->db->from("t_user");
        return $this->db->count_all_results();
    }
    public function count_admin_list()
    {
        $this->db->from("t_admin");
        return $this->db->count_all_results();
    }
}