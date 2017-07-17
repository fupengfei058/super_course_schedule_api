<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) php-team@yaochufa <php-team@yaochufa.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Hole_model extends C_Model
{
    const TABLE_NAME                = 'hole';
    protected $table_name           = 'hole';
    protected $order_by             = 'hole_id desc';
    protected $insert_noneed_fields = [];
    protected $update_noneed_fields = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function support($hole_id, $type)
    {
        $this->db->where('hole_id', (int) $hole_id);
        if ($type == 'support') {
            $this->db->set('support', 'support + 1', false);
        } else {
            $this->db->set('unsupport', 'unsupport + 1', false);
        }
        $this->db->update($this->table_name, []);

        return $this->db->affected_rows() > 0 ? true : false;
    }

    public function update_comment_no($hole_id)
    {
        $this->db->where('hole_id', $hole_id);
        $this->db->set('comment_no', 'comment_no + 1', false);
        $this->db->update($this->table_name, []);

        return $this->db->affected_rows() > 0 ? true : false;
    }

    public function desc_comment_no($hole_id)
    {
        $this->db->where('hole_id', $hole_id);
        $this->db->set('comment_no', 'comment_no - 1', false);
        $this->db->update($this->table_name, []);

        return $this->db->affected_rows() > 0 ? true : false;
    }

    public function update_hole_state($hole_id)
    {
        $this->db->where('hole_id', $hole_id);
        $this->db->set('is_delete', '1', false);
        $this->db->update($this->table_name, []);

        return $this->db->affected_rows() > 0 ? true : false;
    }
}
