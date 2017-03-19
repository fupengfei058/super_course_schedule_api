<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reply_model extends C_Model
{

    const TABLE_NAME = 'reply';
    protected $table_name = 'reply';
    protected $order_by = 'reply desc';
    protected $insert_noneed_fields = array();
    protected $update_noneed_fields = array();

    function __construct()
    {
        parent::__construct();
    }
}