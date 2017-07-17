<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) php-team@yaochufa <php-team@yaochufa.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class Personal_admin extends CI_Controller
{
    public function __construct()
    {
        header('content-type:text/html; charset=utf-8');
        parent::__construct();
    }

    public function check_user()
    {
        $get = $this->input->get();
        if (empty($get['username']) || empty($get['password'])) {
            $return = [
                'code' => 404,
                'data' => '',
            ];
            exit(json_encode($return));
        }
        $login_post_data = [
            'USERNAME' => $get['username'],
            'PASSWORD' => $get['password'],
        ];
        $login_url = 'http://jwxt.gdufe.edu.cn/jsxsd/xk/LoginToXkLdap';
        $this->load->library('jwxt_lib');
        $result  = $this->jwxt_lib->connect_jwxt($login_url, $login_post_data, true);
        $pattern = '/<div id="Top1_divLoginName" class="Nsb_top_menu_nc" style="color: #000000;">([^<]*?)<\/div\>/';
        $res     = preg_match($pattern, $result);
        if (!(bool) $res) {
            $return = [
                'code' => 404,
                'data' => '',
            ];
            exit(json_encode($return));
        }
        preg_match_all($pattern, $result, $content);
        $return = [
            'code' => 200,
            'data' => $content[1],
        ];
        exit(json_encode($return));
    }
}
