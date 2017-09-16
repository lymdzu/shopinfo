<?php

/**
 * 文件名称:goods.php
 * 摘    要:
 * 修改日期: 27/8/17
 */
class Goods extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add()
    {
        $this->display("admin/goods_add.html");
    }
}