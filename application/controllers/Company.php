<?php

/**
 * 文件名称:Company.php
 * 摘    要:
 * 修改日期: 21/10/17
 */
class Company extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function reg()
    {
        $this->display("company/reg.html");
    }

    /**
     *
     * @auther liuyongming@shopex.cn
     */
    public function save_reg()
    {
        $reg_name = $this->input->post("regName");
        $pwd = $this->input->post("pwd");
        $pwd_repeat = $this->input->post("pwdRepeat");
        $company_name = $this->input->post("companyname");
        $district = $this->input->post("companyprovince");
        $employees = $this->input->post("employees");
        $industry = $this->input->post("industry");
        $real_name = $this->input->post("realname");
        $phone = $this->input->post("phone");
        if (empty($reg_name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入用户名");
        }
        if ($pwd != $pwd_repeat) {
            $this->json_result(PARAMETER_WRONG, "", "两次输入密码不一致");
        }
        if (empty($company_name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入公司名称");
        }
        if (empty($district)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入公司所在地");
        }
        if (empty($real_name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入联系人姓名");
        }
        if (empty($phone)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入联系人手机号");
        }
        if (!is_phone_no($phone)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入正确的手机号码");
        }
        $this->load->model("UserModel", "user", true);
        $insert_res = $this->user->insert_company($reg_name, $pwd, $company_name, $district, $employees, $industry, $reg_name, $phone);
        if ($insert_res['status']) {
            $this->json_result(REQUEST_SUCCESS, "");
        } else {
            $this->json_result(API_ERROR, "", "添加");
        }
    }
}