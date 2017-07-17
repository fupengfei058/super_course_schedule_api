<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) php-team@yaochufa <php-team@yaochufa.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class Grade_admin extends CI_Controller
{
    public function __construct()
    {
        header('content-type:text/html; charset=utf-8');
        parent::__construct();
    }

    public function get_all_grade()
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
        //根据学号获取入学年份
        $admit_time = substr($get['username'], 0, 2);
        $admit_time = 2000 + intval($admit_time); //2013
        //$year_arr 在读年份数组
        $year_arr = [
            $admit_time . '-' . ($admit_time + 1) . '-1',
            $admit_time . '-' . ($admit_time + 1) . '-2',
            ($admit_time + 1) . '-' . ($admit_time + 2) . '-1',
            ($admit_time + 1) . '-' . ($admit_time + 2) . '-2',
            ($admit_time + 2) . '-' . ($admit_time + 3) . '-1',
            ($admit_time + 2) . '-' . ($admit_time + 3) . '-2',
            ($admit_time + 3) . '-' . ($admit_time + 4) . '-1',
            ($admit_time + 3) . '-' . ($admit_time + 4) . '-2',
        ];
        //$grade_arr 四年成绩数组
        $grade_arr = [];
        foreach ($year_arr as $k => $v) {
            $grade_arr[$k]['id']    = $k;
            $grade_arr[$k]['years'] = substr($v, 0, 9); //2013-2014
            $grade_arr[$k]['term']  = substr($v, -1);
            $grade_arr[$k]['open']  = false;
            $grade_arr[$k]['table'] = $this->get_grade($login_post_data, $v);
        }
//        p($grade_arr);exit;
        $return = [
            'code' => 200,
            'data' => $grade_arr,
        ];
        exit(json_encode($return));
    }

    public function get_grade($login_post_data, $time)
    {
        $login_url = 'http://jwxt.gdufe.edu.cn/jsxsd/xk/LoginToXkLdap';
        $this->load->library('jwxt_lib');
        $this->jwxt_lib->connect_jwxt($login_url, $login_post_data);
        $grade_post_data = [
            'kksj' => $time,
        ];
        $grade_url = 'http://jwxt.gdufe.edu.cn/jsxsd/kscj/cjcx_list';
        $content   = $this->jwxt_lib->get_grade($grade_url, $grade_post_data);
        $arr       = [];
        array_shift($content);
        foreach ($content as $k => $v) {
            $arr[$k][] = $v[3]; //课程
            $arr[$k][] = $v[4]; //成绩
            $arr[$k][] = $this->get_grade_no($v[4]); //绩点
        }
//        p($arr);exit;
        return $arr;
    }

    /*
     * return 绩点
     */
    protected function get_grade_no($grade)
    {
        if (!is_numeric($grade)) {
            return $grade;
        }
        if ($grade < 60) {
            return 0;
        }

        return number_format(($grade - 60) / 10 + 1, 1);
    }
}
