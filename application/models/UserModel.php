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
     * 注册用户
     * @param $username
     * @param $pwd
     * @param $salt
     * @return bool
     */
    public function reg_user($username, $pwd, $salt)
    {
        $userinfo = array(
            "user_name"   => $username,
            "pwd"         => $pwd,
            "salt"        => $salt,
            "create_time" => time()
        );
        $status = $this->db->insert("t_user", $userinfo);
        $affect_row = $this->db->affected_rows();
        if ($status && $affect_row == 1) {
            return true;
        } else {
            return false;
        }
    }
}