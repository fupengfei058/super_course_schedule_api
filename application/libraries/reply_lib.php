<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reply_lib
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('reply_model');
    }
}