<?php
/**
 * 文件名称:Dashboard.php
 * 摘    要:
 * 修改日期: 17/9/17
 */
class Dashboard extends DashboardController
{
    public function index()
    {
        $this->vars['footer'] = true;
        $this->vars['fix_footer'] = true;
        $this->page('front/front.html');
    }
}