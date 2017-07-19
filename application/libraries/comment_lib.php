<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) fupengfei <183860913@qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Comment_lib
{
    protected $CI;

    public function __construct()
    {
        $this->CI =&get_instance();
        $this->CI->load->model('comment_model');
    }

    public function insert_comment($data = null, $hole_id = 0)
    {
        if (!$data || !$hole_id) {
            return false;
        }
        $this->CI->load->library('hole_lib');
        if ($this->CI->hole_lib->update_comment_no($hole_id)) {
            return $this->CI->comment_model->insert($data);
        }

        return false;
    }

    public function get_comments($hole_id = 0)
    {
        if ($hole_id <= 0) {
            return false;
        }
        $arr = [];
        array_push($arr, ['where', ['hole_id'=>$hole_id, 'is_delete'=>0]]);

        return $this->CI->comment_model->get($arr);
    }

    public function delete_comment($comment_id = 0, $hole_id = 0)
    {
        if ($comment_id <= 0 || $hole_id <= 0) {
            return false;
        }
        $this->CI->load->library('hole_lib');
        $res = $this->CI->hole_lib->desc_comment_no($hole_id); //评论数量减一
        if ($res) {
            return $this->CI->comment_model->update_comment_state($comment_id);
        }

        return false;
    }

    public function get_hole_by_comment($comment_id = 0)
    {
        if ($comment_id <= 0) {
            return false;
        }
        $arr = [];
        array_push($arr, ['where', ['comment_id'=>$comment_id]]);
        $comment = $this->CI->comment_model->get_row($arr);
        if (!empty($comment)) {
            $hole_id = $comment['hole_id'];

            return $hole_id;
        }

        return false;
    }
}
