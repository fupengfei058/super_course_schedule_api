<?php

class Course_admin extends CI_Controller {

    public function __construct(){
        header("content-type:text/html; charset=utf-8");
        parent::__construct();
    }

    public function get_course(){
        $get = $this->input->get();
        if(empty($get['username']) || empty($get['password']) || empty($get['time'])){
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
        $this->jwxt_lib->connect_jwxt($login_url,$login_post_data);
        $course_post_data = array(
            'xnxq01id' => $get['time'],
        );
        $course_url = 'http://jwxt.gdufe.edu.cn/jsxsd/xskb/xskb_list.do';
        $content = $this->jwxt_lib->get_course($course_url,$course_post_data);
        array_shift($content);
        array_pop($content);
        $arr = array();
        foreach($content as $key => $val){
            array_pop($val);
            array_pop($val);
            foreach($val as $v){
                //取出@左边的字符串
                //取出[左边的字符串，即不要[01-02]节
                if(strstr($v,'[',true)){
                    $arr[$key][] = strstr($v,'[',true);
                }elseif(strstr($v,'@',true)){
                    $arr[$key][] = strstr($v,'@',true);
                }else{
                    $arr[$key][] = $v;
                }
            }
        }
        if(count($arr) > 0 && count($arr) < 6){
            array_push($arr,array('第十一十二节','','','','',''));
        }
//        p($arr);exit;
        $return = array(
            'code' => 200,
            'data' => $arr
        );
        exit(json_encode($return));
    }
}