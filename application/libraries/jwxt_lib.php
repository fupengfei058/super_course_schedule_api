<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) php-team@yaochufa <php-team@yaochufa.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Jwxt_lib
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('snoopy_lib', '', 'snoopy');
    }

    /*
     * 模拟登陆操作
     */
    public function connect_jwxt($login_url, $post_data, $is_check_user = false)
    {
        $this->CI->snoopy->expandlinks = true;
        $this->CI->snoopy->host        = 'jwxt.gdufe.edu.cn';
        $this->CI->snoopy->agent       = 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:43.0) Gecko/20100101 Firefox/43.0';
        $this->CI->snoopy->referer     = 'http://jwxt.gdufe.edu.cn/jsxsd/';
        $this->CI->snoopy->setcookies();
        $this->CI->snoopy->submit($login_url, $post_data);
        if ($is_check_user) {
            return $this->CI->snoopy->results;
        }
    }

    /*
     * return array 课程表
     */
    public function get_course($course_url, $post_data)
    {
        $this->CI->snoopy->host    = 'jwxt.gdufe.edu.cn';
        $this->CI->snoopy->agent   = 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:43.0) Gecko/20100101 Firefox/43.0';
        $this->CI->snoopy->referer = 'http://jwxt.gdufe.edu.cn/jsxsd/xskb/xskb_list.do';
        $this->CI->snoopy->submit($course_url, $post_data);
        $content = $this->CI->snoopy->results;
        if ($content) {
            preg_match_all('/<table id="kbtable"[\w\W]*?>([\w\W]*?)<\/table>/', $content, $out);
            $table = $out[0][0]; //获取整个课表
            return $this->get_td_array($table);
        }
    }

    /*
     * return array 成绩
     */
    public function get_grade($grade_url, $post_data)
    {
        $this->CI->snoopy->host    = 'jwxt.gdufe.edu.cn';
        $this->CI->snoopy->agent   = 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:43.0) Gecko/20100101 Firefox/43.0';
        $this->CI->snoopy->referer = 'http://jwxt.gdufe.edu.cn/jsxsd/kscj/cjcx_query?Ves632DSdyV=NEW_XSD_XJCJ';
        $this->CI->snoopy->submit($grade_url, $post_data);
        $content = $this->CI->snoopy->results;
        if ($content) {
            preg_match_all('/<table id="dataList"[\w\W]*?>([\w\W]*?)<\/table>/', $content, $out);
            $table = $out[0][0]; //获取整个课表
            return $this->get_td_array($table);
        }
    }

    /*
     * 把table转换成array
     */
    protected function get_td_array($table)
    {
        $table = preg_replace("'<div[^>]*?display: none;[^>].*</font><br/></div>'", '', $table);
        $table = preg_replace("'<table[^>]*?>'si", '', $table);
        $table = preg_replace("'<input[^>]*?>'si", '', $table);
        $table = preg_replace("'<div[^>]*?>'si", '', $table);
        $table = preg_replace("'th'", 'td', $table);
        $table = preg_replace("'<tr[^>]*?>'si", '', $table);
        $table = preg_replace("'<td[^>]*?>'si", '', $table);
        $table = str_replace('</tr>', '{tr}', $table);
        $table = str_replace('</td>', '{td}', $table);
        //去掉 HTML 标记
        $table = preg_replace("'<[/!]*?[^<>]*?>'si", '', $table);
        //去掉空白字符
        $table = preg_replace("/[\t\n\r]+/", '', $table);
        //$table = preg_replace("'([rn])[s]+'","",$table);
        $table = preg_replace('/&nbsp;/', '', $table);
        $table = str_replace(' ', '', $table);
        $table = preg_replace("'----------------------'", '@', $table);
        $table = explode('{tr}', $table);
        array_pop($table);
        $td_array = [];
        foreach ($table as $key=>$tr) {
            $td = explode('{td}', $tr);
            array_pop($td);
            $td_array[] = $td;
        }

        return $td_array;
    }
}
