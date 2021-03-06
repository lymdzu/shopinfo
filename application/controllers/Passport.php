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
            $username = $this->input->post("username", true);
            $password = $this->input->post("password", true);
            $this->load->model("UserModel", "user", true);
            $user_res = $this->user->get_admin_by_name($username);
            if (empty($user_res)) {
                $this->json_result(USER_NOT_FOUND, "", "不存在此用户");
            }
            $client_pwd = md5(trim($password) . $user_res['salt']);
            if ($client_pwd == $user_res['password']) {
                $this->session->set_userdata("admin_user", $user_res['username']);
                $this->json_result(REQUEST_SUCCESS, base_url('admin/index'));
            } else {
                $this->json_result(PARAMETER_WRONG, "", "密码错误,请重新输入");
            }
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
            $four_error = $this->session->userdata("four_error");
            if ($four_error) {
                $auth_code = $this->session->userdata("");
            }
            $user_name = $this->input->post("user_name", true);
            $password = $this->input->post("pwd", true);
            $this->load->model("UserModel", "user", true);
            $user = $this->user->get_user_by_name($user_name);
            if (empty($user)) {
                $this->json_result(LACK_REQUIRED_PARAMETER, "", "此用户不存在");
            }
            $pwd_md5 = md5($password . $user['salt']);
            if ($pwd_md5 == $user['pwd']) {
                $this->session->set_userdata("person_user", $user['user_name']);
                $this->load->helper('cookie');
                set_cookie("person_user", $user['user_name'], 86400 * 30);
                $this->json_result(REQUEST_SUCCESS, "登录成功");

            } else {
                $this->json_result(PARAMETER_WRONG, "", "账号或密码错误,请检查后重新输入");
            }
        }
        $this->display("passport/login.html");
    }
    public function user_logout()
    {
        $this->session->sess_destroy();
        redirect('/', "auto", 302);
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
            if (strtolower($auth_session) != strtolower(trim($auth_code))) {
                $this->json_result(DATA_FORMAT_ERROR, "", "图片验证码错误");
            }
            $this->load->model("UserModel", "user", true);
            $userinfo = $this->user->get_userinfo_by_name($user_name);
            if (!empty($userinfo['result'])) {
                $this->json_result(DATA_FORMAT_ERROR, "", "已经存在此用户,请换一个用户名");
            }
            $salt = mt_rand(100000, 999999);
            $password = md5($pwd . $salt);
            $reg_status = $this->user->reg_user($user_name, $password, $salt);
            if ($reg_status) {
                $this->json_result(REQUEST_SUCCESS, "注册成功");
            } else {
                $this->json_result(API_ERROR, "", "服务器错误");
            }
        }
        $this->display("passport/register.html");
    }

    /**
     * 发送短信
     */
    public function send_phone_code()
    {
        $phone = $this->input->post("phone", true);
        $auth_code = trim($this->input->post("auth_code", true));
        if (!isset($phone) || empty($phone) || !is_mobile_num($phone)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入正确的手机号码");
        }
        $server_auth = $this->session->userdata("captcha");
        if (strtolower($server_auth) != strtolower($auth_code)) {
            $this->json_result(PARAMETER_WRONG, "", "请输入正确的验证码");
        }
        //        $verify_code = mt_rand(100000, 999999);
        $verify_code = "000000";
        $this->session->set_userdata($verify_code);
        $this->json_result(REQUEST_SUCCESS, "短信发送成功");
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