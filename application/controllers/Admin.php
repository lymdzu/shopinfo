<?php

/**
 * 文件名称:Admin.php
 * 摘    要:
 * 修改日期: 27/8/17
 */
class Admin extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->display("admin/data_chart.html");
    }
}