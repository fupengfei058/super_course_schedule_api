<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hole_lib
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('hole_model');
    }

    public function get_tree_holes($limit = 0,$offset = 0){
        $arr = array();
        array_push($arr,array('where',array('is_delete'=>0)));
        return $this->CI->hole_model->get($arr,$field = '*', $limit, $offset);
    }

    public function insert_tree_hole($data = null){
        if(!$data) return false;
        return $this->CI->hole_model->insert($data);
    }

    public function support($hole_id,$type = 'support'){
        if(!$hole_id) return false;
        return $this->CI->hole_model->support($hole_id,$type);
    }

    public function update_comment_no($hole_id = 0){
        if($hole_id <= 0) return false;
        return $this->CI->hole_model->update_comment_no($hole_id);
    }

    public function get_hole($hole_id = 0){
        if($hole_id <= 0) return false;
        $arr = array();
        array_push($arr,array('where',array('hole_id'=>$hole_id)));
        return $this->CI->hole_model->get_row($arr);
    }

    public function delete_hole($hole_id = 0){
        if($hole_id <= 0) return false;
        return $this->CI->hole_model->update_hole_state($hole_id);
    }

    public function desc_comment_no($hole_id = 0){
        if($hole_id <= 0) return false;
        return $this->CI->hole_model->desc_comment_no($hole_id);
    }
}