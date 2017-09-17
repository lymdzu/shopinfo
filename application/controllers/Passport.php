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

    /**
     * 登录页面
     */
    public function login()
    {
        if ($this->input->method() == "post") {
            $user_name = $this->input->post("loginname");
            $password = $this->input->post("nloginpwd");
        }
        $this->display("passport/login.html");
    }

    /**
     * 注册页面
     */
    public function register()
    {
        if ($this->input->method() == "post") {
            $user_name = $this->input->post("regName", true);
            $pwd = $this->input->post("pwd", true);
            $pwd_repeat = $this->input->post("pwdRepeat", true);
            $phone = $this->input->post("phone");
            $auth_code = $this->input->post("authCode");
            $verify_code = $this->input->post("mobileCode");
            $auth_session = $this->session->userdata("captcha");
            if (!isset($user_name) || empty($user_name)) {
                $this->json_result(DATA_FORMAT_ERROR, "", "请输入用户名");
            }
            if (!isset($pwd) || empty($pwd)) {
                $this->json_result(DATA_FORMAT_ERROR, "", "请输入密码");
            }
            if (!isset($pwd_repeat) || empty($pwd_repeat)) {
                $this->json_result(DATA_FORMAT_ERROR, "", "请再次输入密码");
            }
            if ($pwd != $pwd_repeat) {
                $this->json_result(DATA_FORMAT_ERROR, "", "两次输入密码不一致,请检查");
            }
            if (!isset($phone) || empty($phone)) {
                $this->json_result(DATA_FORMAT_ERROR, "", "请输入注册手机号码");
            }
            if ($auth_session != $auth_code) {
                $this->json_result(DATA_FORMAT_ERROR, "", "图片验证码错误");
            }
        }
        $this->display("passport/register.html");
    }

    /**
     *  生成注册图片二维码
     */
    public function captcha()
    {
        session_start();
        //生成验证码图片
        header("Content-type: image/png");
        header("Cache-Control: no-cache, must-revalidate");
        // 全数字
        $str = "1,2,3,4,5,6,7,8,9,0,A,C,D,E,F,G,H,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Z,Y";      //要显示的字符，可自己进行增删
        $list = explode(",", $str);
        $cmax = count($list) - 1;
        $verifyCode = '';
        for ($i = 0; $i < 4; $i++) {
            $randnum = mt_rand(0, $cmax);
            $verifyCode .= $list[$randnum];           //取出字符，组合成为我们要的验证码字符
        }
        //将字符放入SESSION中
        $this->session->set_userdata("captcha", $verifyCode);
        $im = imagecreate(50, 20);    //生成图片
        //此条及以下三条为设置的颜色
        $white = imagecolorallocate($im, 255, 255, 255);
        $red = imagecolorallocate($im, 255, 0, 0);
        imagefill($im, 0, 0, $white);     //给图片填充颜色

        //将验证码绘入图片
        imagestring($im, 5, 8, 3, $verifyCode, $red);    //将验证码写入到图片中

        $li = ImageColorAllocate($im, 255, 0, 0);
        for ($i = 0; $i < 4; $i++) {    //加入3条干扰线;也可以不要;视情况而定，因为可能影响用户输入;
            imageline($im, rand(0, 50), rand(0, 11), rand(20, 50), rand(0, 11), $li);
        }
        imagepng($im);
        imagedestroy($im);
    }
}