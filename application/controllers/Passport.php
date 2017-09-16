<?php

/**
 * 文件名称:Passport.php
 * 摘    要:
 * 修改日期: 27/8/17
 */
class Passport extends PublicController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 管理员后台登录
     */
    public function admin_login()
    {
        if ($this->input->method() == "post") {
            $username = trim($this->input->post("username", true));
            $password = trim($this->input->post("password", true));
            $this->load->model("AdminModel", "admin", true);
            $user_info = $this->admin->get_admin_by_username($username);
            if (empty($user_info)) {
                $this->json_result(404, "", "该用户不存在");
            }
            $pwd_check = md5($password . $user_info['salt']);
            if ($user_info['password'] !== $pwd_check) {
                $this->json_result(403, "", "密码错误");
            }
            $this->session->set_userdata("admin_id", $user_info['admin_id']);
            $this->json_result(200, "登录成功");
        } else {
            $this->display("passport/admin_login.html");
        }
    }
    public function admin_logout()
    {
        $this->session->sess_destroy();
        redirect('passport/admin_login', "auto", 302);
    }
    public function login()
    {
        $this->display("passport/login.html");
    }
    public function register()
    {
        $this->display("passport/register.html");
    }
}