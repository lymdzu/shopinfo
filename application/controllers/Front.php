<?php

/**
 * 文件名称:Front.php
 * 摘    要:
 * 修改日期: 26/8/17
 */
class Front extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->page("front/front.html");
    }
}