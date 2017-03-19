<?php

class Personal_admin extends CI_Controller {

    public function __construct(){
        header("content-type:text/html; charset=utf-8");
        parent::__construct();
    }

    public function check_user(){
        $get = $this->input->get();
        if(empty($get['username']) || empty($get['password'])){
            $return = array(
                'code' => 404,
                'data' => ''
            );
            exit(json_encode($return));
        }
        $login_post_data = array(
            'USERNAME' => $get['username'],
            'PASSWORD' => $get['password']
        );
        $login_url = 'http://jwxt.gdufe.edu.cn/jsxsd/xk/LoginToXkLdap';
        $this->load->library('jwxt_lib');
        $result = $this->jwxt_lib->connect_jwxt($login_url,$login_post_data,true);
        $pattern = '/<div id="Top1_divLoginName" class="Nsb_top_menu_nc" style="color: #000000;">([^<]*?)<\/div\>/';
        $res = preg_match($pattern,$result);
        if(!(boolean)$res){
            $return = array(
                'code' => 404,
                'data' => ''
            );
            exit(json_encode($return));
        }
        preg_match_all($pattern,$result,$content);
        $return = array(
            'code' => 200,
            'data' => $content[1]
        );
        exit(json_encode($return));
    }
}