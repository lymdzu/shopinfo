<?php

/**
 * 文件名称:Lists.php
 * 摘    要:
 * 修改日期: 26/8/17
 */
class Lists extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->page("front/second.html");
    }
}