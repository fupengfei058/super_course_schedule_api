<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends C_Model
{

    const TABLE_NAME = 'comment';
    protected $table_name = 'comment';
    protected $order_by = 'comment_id desc';
    protected $insert_noneed_fields = array();
    protected $update_noneed_fields = array();

    function __construct()
    {
        parent::__construct();
    }

    public function update_comment_state($comment_id){
        $this->db->where('comment_id', $comment_id);
        $this->db->set('is_delete','1',false);
        $this->db->update($this->table_name,array());
        return $this->db->affected_rows() > 0? true : false;
    }
}