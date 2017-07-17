<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) php-team@yaochufa <php-team@yaochufa.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Reply_model extends C_Model
{
    const TABLE_NAME                = 'reply';
    protected $table_name           = 'reply';
    protected $order_by             = 'reply desc';
    protected $insert_noneed_fields = [];
    protected $update_noneed_fields = [];

    public function __construct()
    {
        parent::__construct();
    }
}
