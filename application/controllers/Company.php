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
    public function save_reg()
    {
        
    }
}