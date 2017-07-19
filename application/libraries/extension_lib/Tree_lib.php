<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) fupengfei <183860913@qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class Tree_lib
{
    //静态
    public static $list = [];
    //父分类id的字段名
    private $parent_id_str = 'parent_id';
    //自增长主键id
    private $id_str = 'id';

    public function __construct()
    {
        self::$list = [];
    }

    //设置父分类id的字段名
    public function set_parent_id_str($str = '')
    {
        if ($str != '') {
            $this->parent_id_str = $str;
        }
    }

    //设置自增长主键id
    public function set_id_str($str = '')
    {
        if ($str != '') {
            $this->id_str = $str;
        }
    }

    //无限极分类根据层级排列成一维数组
    public function getlist($data, $parent_id=0)
    {
        foreach ($data as $k => $v) {
            if ($v["{$this->parent_id_str}"] == $parent_id) {
                self::$list[$v["{$this->id_str}"]]=$v;
                // 把这个类从原数组中删除，已经找好，以后不用再找这个类了
                unset($data[$k]);
                // 从剩下的分类中继续找当前类的子类
                // 第二个参数传当前这条记录的id，意思：找parent_id=这个id
                $this->getlist($data, $v["{$this->id_str}"]);
            }
        }

        return self::$list;
    }

    //无限极分类根据层级排列成一维数组 , 携带level等级
    public function getlist_level($data, $parent_id=0, $level = 1)
    {
        foreach ($data as $k => $v) {
            if ($v["{$this->parent_id_str}"] == $parent_id) {
                $v['level']                       = $level;
                self::$list[$v["{$this->id_str}"]]=$v;
                // 把这个类从原数组中删除，已经找好，以后不用再找这个类了
                unset($data[$k]);
                // 从剩下的分类中继续找当前类的子类
                // 第二个参数传当前这条记录的id，意思：找parent_id=这个id
                $this->getlist_level($data, $v["{$this->id_str}"], $level + 1);
            }
        }

        return self::$list;
    }

    //将一维数组转化成多维数组
    public function generateTree($arr)
    {
        $tree = [];
        foreach ($arr as $arrs) {
            if (isset($arr[$arrs["{$this->parent_id_str}"]])) {
                $arr[$arrs["{$this->parent_id_str}"]]['son'][] = &$arr[$arrs["{$this->id_str}"]];
            } else {
                $tree[] = &$arr[$arrs["{$this->id_str}"]];
            }
        }
        $this->sort($tree);
        unset($arr);

        return $tree;
    }

    //对数组进行sort字段的排序
    public function sort($arr)
    {
        if (count($arr) > 1) {
            usort($arr, function ($a, $b) {
                return $a['sort'] - $b['sort'];
            });
        }
        foreach ($arr as $k => &$v) {
            if ($k == 2) {
                break;
            }
            if (isset($v['son'])) {
                self::sort($v['son']);
            }
        }
    }

    //通过分类id获取对应的父分类数组
    public function get_parent_ids_by_id_list($tree, $id_list)
    {
        $arr = [];
        foreach ($id_list as $v) {
            $temp = $this->get_parents($tree, $v);
            if (!empty($temp)) {
                if (isset($arr[$temp[0]["{$this->id_str}"]])) {
                    if (count($temp) > count($arr[$temp[0]["{$this->id_str}"]])) {
                        $arr[$temp[0]["{$this->id_str}"]] = $temp;
                    }
                } else {
                    $arr[$temp[0]["{$this->id_str}"]] = $temp;
                }
            }
        }
        unset($v);
        foreach ($arr as $k => &$v) {
            $v = array_keys(key_exchange($v, $this->id_str));
        }

        return $arr;
    }

    //获取传入id的所有父级分类
    public function get_parents($tree, $id)
    {
        $arr = [];
        foreach ($tree as $v) {
            if ($v["{$this->id_str}"] == $id) {
                $arr[] = $v;
                $arr   = array_merge($this->get_parents($tree, $v["{$this->parent_id_str}"]), $arr);
            }
        }

        return $arr;
    }

    //获取传入父级id的所有子集id
    public function get_childs($tree, $parent_id)
    {
        $arr = [];
        foreach ($tree as $v) {
            if ($v["{$this->parent_id_str}"] == $parent_id) {
                $arr[] = $v["{$this->id_str}"];
                $arr   = array_merge($arr, $this->get_child_id($tree, $v["{$this->id_str}"]));
            }
        }

        return $arr;
    }
}
