<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) fupengfei <183860913@qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Reply_lib
{
    protected $CI;

    public function __construct()
    {
        $this->CI =&get_instance();
        $this->CI->load->model('reply_model');
    }
}
