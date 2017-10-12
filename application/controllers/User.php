<?php
/**
 * 文件名称:User.php
 * 摘    要:
 * 修改日期: 10/10/17
 */
class User extends AdController
{
    public function __construct() {
        parent::__construct();
        $this->vars['row'] = "user";
        $this->load->model('UserModel', "user", true);
    }

    /**
     * 分页显示个人注册用户列表
     */
    public function person_list()
    {
        $page = $this->input->get("page");
        $offset = empty($page) ? 0 : (intval($page) - 1) * PAGESIZE;
        $total = $this->user->count_person_user_list();
        $person_list = $this->user->get_person_user_list($offset, PAGESIZE);
        $this->load->library("tgpage", array('total' => $total, 'pagesize' => PAGESIZE));
        $this->vars['person_list'] = $person_list;
        $this->vars['pagelist'] = $this->tgpage->showpage();
        $this->page("user/person_list.html");
    }
    public function disable_login()
    {
        $id = $this->input->post("id", true);
        $status = $this->input->post("status", true);
        $reverse_status = $status == 1 ? 0 : 1;
        $this->load->model("UserModel", "user", true);
        $opt_res = $this->user->disable_user(trim($id), $reverse_status);
        if ($opt_res) {
            $this->json_result(REQUEST_SUCCESS, "操作成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器错误");
        }
    }
}