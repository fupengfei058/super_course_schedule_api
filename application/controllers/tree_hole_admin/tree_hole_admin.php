<?php

class Tree_hole_admin extends CI_Controller
{

    public function __construct()
    {
        header("content-type:text/html; charset=utf-8");
        parent::__construct();
    }

    /*
     * 获取所有树洞
     */
    public function get_tree_holes(){
        $get = $this->input->get();
        if(empty($get['option']) || $get['offset'] < 0){
            $return = array(
                'code' => '404',
                'data' => array(),
            );
            exit(json_encode($return));
        }
        $this->load->library('hole_lib');
        $limit = 0;
        $tree_holes = array();
        /*$count = count($this->hole_lib->get_tree_holes());//树洞条数
        if($get['offset'] >= $count){
            $return = array(
                'code' => '404',
                'data' => array(),
                'offset' => $count
            );
            exit(json_encode($return));
        }
        if($get['option'] == 'newlist'){
            //首次获取5条
            $limit = $count > 5 ? 5 : $count;
            $tree_holes = $this->hole_lib->get_tree_holes($limit);
        }elseif($get['option'] == 'list'){
            if($get['offset'] < $count){//表示仍有数据没加载
                //下拉获取$limit条
                $limit = $count - $get['offset'] > 3 ? 3 : $count - $get['offset'];
                $tree_holes = $this->hole_lib->get_tree_holes($limit,$get['offset']);
            }
        }*/
        $tree_holes = $this->hole_lib->get_tree_holes();
        if(!empty($tree_holes)){
            foreach ($tree_holes as &$v){
                $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            }
        }
        $return = array(
            'code' => '200',
            'data' => $tree_holes,
            'offset' => $limit+$get['offset']
        );
        exit(json_encode($return));
    }

    /*
     * 赞/踩
     */
    public function support(){
        $return = array(
            'code' => '404',
            'data' => array(),
        );
        $get = $this->input->get();
        if(empty($get['hole_id']) || !$get['type'] || $get['offset'] < 0){
            exit(json_encode($return));
        }
        $this->load->library('hole_lib');
        if($this->hole_lib->support($get['hole_id'],$get['type'])){
//            $count = count($this->hole_lib->get_tree_holes());//树洞条数
//            $limit = $count > 5 ? 5 : $count;
//            $tree_holes = $this->hole_lib->get_tree_holes($get['offset']);
            $tree_holes = $this->hole_lib->get_tree_holes();
            if(!empty($tree_holes)){
                foreach ($tree_holes as &$v){
                    $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
                }
            }
            $return = array(
                'code' => '200',
                'data' => $tree_holes
            );
        }
        exit(json_encode($return));
    }

    /*
     * 发表树洞
     */
    public function publish_tree_hole(){
        $get = $this->input->get();
        $return = array(
            'code' => '404',
            'data' => array()
        );
        if(empty($get['nick_name']) || empty($get['stu_no']) || !$get['content'] || $get['content'] == 'undefined'){
            exit(json_encode($return));
        }
        $nick_name = mb_substr($get['nick_name'],2,1) . '同学';
        $this->load->library('hole_lib');
        $data = array(
            'nick_name' => $nick_name,
            'create_time' => time(),
            'content' => $get['content'],
            'is_delete' => 0,
            'stu_no' => $get['stu_no']
        );
        $res = $this->hole_lib->insert_tree_hole($data);
        if($res){
            $return = array(
                'code' => '200',
                'data' => array()
            );
        }
        exit(json_encode($return));
    }

    /*
     * 发表评论
     */
    public function publish_comment(){
        $get = $this->input->get();
        $return = array(
            'code' => '404',
            'data' => array()
        );
        if(empty($get['nick_name']) || empty($get['stu_no']) || !$get['content'] || $get['content'] == 'undefined' || !$get['hole_id']){
            exit(json_encode($return));
        }
        $this->load->library('comment_lib');
        $nick_name = mb_substr($get['nick_name'],2,1) . '同学';
        $data = array(
            'hole_id' => (int)$get['hole_id'],
            'content' => trim((string)$get['content']),
            'is_delete' => 0,
            'create_time' => time(),
            'nick_name' => $nick_name,
            'stu_no' => $get['stu_no']
        );
        $res = $this->comment_lib->insert_comment($data,$get['hole_id']);
        if($res){
            $return = $this->do_get_hole_and_comments($get['hole_id']);
        }
        exit(json_encode($return));
    }

    /*
     * 获取获取单条树洞以及树洞下的评论
     */
    public function get_hole_and_comments(){
        $get = $this->input->get();
        $return = array(
            'code' => '404',
            'data' => array()
        );
        if(!(int)$get['hole_id']){
            exit(json_encode($return));
        }
        $return = $this->do_get_hole_and_comments($get['hole_id']);
        exit(json_encode($return));
    }

    private function do_get_hole_and_comments($hole_id){
        $return = array(
            'code' => '404',
            'data' => array()
        );
        $this->load->library('hole_lib');
        $hole = (array)$this->hole_lib->get_hole((int)$hole_id);//获取一条树洞
        $this->load->library('comment_lib');
        $comments = (array)$this->comment_lib->get_comments((int)$hole_id);//树洞下的评论
        if(empty($hole)){
            return $return;
        }
        $hole['create_time'] = date('Y-m-d H:i:s',$hole['create_time']);
        if(!empty($comments)){
            foreach ($comments as &$v) {
                $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            }
            unset($v);
        }
        $return = array(
            'code' => '200',
            'data' => array(
                'hole' => $hole,
                'comments' => $comments
            )
        );
        return $return;
    }

    /*
     * 删除树洞
     */
    public function delete_hole(){
        $get = $this->input->get();
        $data = array();
        $return = array(
            'code' => '404',
            'data' => $data
        );
        if(!$get['hole_id'] || $get['hole_id'] <= 0){
            exit(json_encode($return));
        }
        $this->load->library('hole_lib');
        $res = $this->hole_lib->delete_hole($get['hole_id']);
        if($res){
            $data = $this->hole_lib->get_tree_holes();
            $return = array(
                'code' => '200',
                'data' => $data
            );
        }
        exit(json_encode($return));
    }

    /*
     * 删除评论
     */
    public function delete_comment(){
        $get = $this->input->get();
        $return = array(
            'code' => '404',
            'data' => array()
        );
        if(!$get['comment_id'] || $get['comment_id'] <= 0){
            exit(json_encode($return));
        }
        //根据评论id获取树洞id
        $this->load->library('comment_lib');
        $hole_id = $this->comment_lib->get_hole_by_comment($get['comment_id']);
        if((int)$hole_id > 0){
            $res = $this->comment_lib->delete_comment($get['comment_id'],$hole_id);
            if($res){
                $return = $this->do_get_hole_and_comments($hole_id);
            }
        }
        exit(json_encode($return));
    }

}