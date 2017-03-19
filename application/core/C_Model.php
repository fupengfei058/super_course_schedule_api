<?php if( !defined('BASEPATH')) exit('No access!');

class C_Model extends CI_Model {
    //构造函数V
	public function __construct()
    {
        //开启session
        if(!isset($_SESSION))
        {
            session_start();
        }
		parent::__construct();
		$this->load->database();
	}

	/**
     * 综合查询函数
     * @param  array $arr      综合数组
     * @param  string $field   查询字段
     * @param  int $limit    limit
     * @param  int $offset   offset
     * @param  string $order_by 排序
     * @return [type]           [description]
     */
    public function get($arr, $field = '*', $limit = 0, $offset = 0, $order_by = '')
    {
//         p($arr);die;
    	if(!$this->check_get_fields($field)){
    		return array();
    	}

        if($field == '*'){
        	$this->db->select($this->get_all_fields());
        }else{
        	$this->db->select($field);
        }
        
        $order_by = $order_by == '' ? $this->order_by : $order_by;
        $this->splice_where($arr);
        $this->db->order_by($order_by);
        if($limit === 0)
        {
            $q = $this->db->get($this->table_name);
        }
        else
        {
            $q = $this->db->get($this->table_name, $limit, $offset);
        }
       //  echo $this->db->last_query();
        return $q->result_array();
    }
    public function get_row($arr, $field = '*')
    {
    	if(!$this->check_get_fields($field)){
    		return array();
    	}
    
    	if($field == '*'){
    		$this->db->select($this->get_all_fields());
    	}else{
    		$this->db->select($field);
    	}
    	$this->splice_where($arr);
    	$q = $this->db->get($this->table_name);
    	return $q->row_array();
    }
    /**
     * 综合查询函数
     * @param  array $arr      综合数组
     * @param  string $field   查询字段
     * @param  int $limit    limit
     * @param  int $offset   offset
     * @param  string $order_by 排序
     * @return [type]           [description]
     */
    public function special_get($arr, $field = '*', $limit = 0, $offset = 0, $order_by = '')
    {
    	if($field == '*'){
    		$this->db->select($this->get_all_fields(),true);
    	}else{
    		$this->db->select($field,false);
    	}
    	$order_by = $order_by == '' ? $this->order_by : $order_by;
    	$this->splice_where($arr);
    	$this->db->order_by($order_by);
    	if($limit === 0)
    	{
    		$q = $this->db->get($this->table_name);
    	}
    	else
    	{
    		$q = $this->db->get($this->table_name, $limit, $offset);
    	}
    	//         echo $this->db->last_query();die();
    	return $q->result_array();
    }
    //获取该数据表的所有字段
    public function get_columns(){
    	return $this->db->list_fields($this->table_name);
    }
    //通过条件获取该条件下的总数
    public function get_by_where_count_num($arr,$field = ''){
//         print_r($arr);die;
		if($field == ''){
			$this->splice_where($arr);
			$this->db->from($this->table_name);
			return $this->db->count_all_results();
		}else{
			$this->splice_where($arr);
			$this->db->select("count({$field}) total_count");
			$q =$this->db->get($this->table_name);
			$res = $q->row_array();
			if($res && isset($res['total_count'])){
				return $res['total_count'];
			}else{
				return 0;
			}
		}
    	
    }
    public function get_by_where_sum($arr,$field){
    	$this->splice_where($arr);
    	$this->db->select("sum({$field}) total_sum");
    	$q =$this->db->get($this->table_name);
    	$res = $q->row_array();
    	if($res && isset($res['total_sum'])){
    		return $res['total_sum'];
    	}else{
    		return 0;
    	}
    	
    }
    //拼接数据
    public function splice_where($arr){
    	foreach ($arr as $key => $value)
    	{
    		switch ($value[0])
    		{
    			case 'where':
    				$this->db->where($value[1]);
    				break;
    			case 'condition':
    				$this->db->where($value[1]);
    				break;
    			case 'where_string':
    				$this->db->where($value[1]);
    				break;
    			case 'or_where':
    				$this->db->or_where($value[1]);
    				break;
    			case 'where_in':
    				$this->db->where_in($value[1], $value[2]);
    				break;
    			case 'or_where_in':
    				$this->db->or_where_in($value[1], $value[2]);
    				break;
    			case 'where_not_in':
    				$this->db->where_not_in($value[1], $value[2]);
    				break;
    			case 'or_where_not_in':
    				$this->db->or_where_not_in($value[1], $value[2]);
    				break;
    			case 'like':
    				$this->db->like($value[1]);
    				break;
    			case 'or_like':
    				$this->db->or_like($value[1]);
    				break;
    			case 'not_like':
    				$this->db->not_like($value[1]);
    				break;
    			case 'or_not_like':
    				$this->db->or_not_like($value[1]);
    				break;
    			case 'group_by':
    				$this->db->group_by($value[1]);
    				break;
    			default:
    				# code...
    				break;
    		}
    	}
    }
    /**
     * 查询时 * 转换字段
     */
    public function get_all_fields(){
    	$fileds = $this->get_columns();
    	return implode(',', $fileds);
    }
    /**
     * 判断查询的字段是否合法
     */
    public function check_get_fields($fields = ''){
    	if($fields == ''){
//     		show_error("查询字段不能为空");
    		return false;
    	}
    	if($fields == '*'){
    		return true;
    	}
    	$fields = explode(',', $fields);
    	if(!is_array($fields)){
//     		show_error("字段类型出错");
    		return false;
    	}
    	if(count(array_diff($fields, $this->get_columns())) != 0) {
//     		show_error("存在非法字段");
    		return false;
    	}
        return true;
    }
    /**
     * 判断插入数据的字段是否合法
     */
    public function check_insert_data($data){
    	if(!isset($data) || count($data)<=0){
//     		show_error("插入数据字段不能为空");
    		return false;
    	}
    	if(empty($this->insert_noneed_fields)){
    		$insert_fields = array_diff($this->get_columns(), $this->insert_noneed_fields);
    	}
    	//获取插入所需要的字段
    	$insert_fields = array_diff($this->get_columns(), $this->insert_noneed_fields);
    	if(is_array($data[key($data)])){
    		$flag = true;
    		foreach ($data as $key => $value) {
    			if(count(array_diff(array_keys($value), $insert_fields)) > 0){
//     				show_error("存在非法字段");
    				$flag = false;
    				break;
    			}
    		}
    		return $flag;
    	}else{
    		if(count(array_diff(array_keys($data), $insert_fields)) > 0){
//     			show_error("存在非法字段");
    			return false;
    		}
    	}
    	return true;
    }
    /**
     * 判断更新数据的字段是否合法
     */
    public function check_update_data($data){
    	if(!isset($data) || count($data)<=0){
//     		show_error("更新数据字段不能为空");
    		return false;
    	}
    	//获取更新所需要的字段
    	$update_fields = array_diff($this->get_columns(), $this->update_noneed_fields);
    	
    	if(is_array($data[key($data)])){
    		foreach ($data as $key => $value) {
    			if(count(array_diff(array_keys($value), $update_fields)) > 0){
//     				show_error("存在非法字段");
    				return false;
    			}
    		}
    	}else{
    		if(count(array_diff(array_keys($data), $update_fields)) > 0){
//     			show_error("存在非法字段");
    			return false;
    		}
    	}
    	return true;
    }
    /**
     * 过滤更新的字段
     */
    public function filter_update($filter_data = null,$update_column = null){
    	if(!$filter_data || !$update_column)
    		return false;
    	return array_intersect_key($filter_data, array_flip($update_column));
    }
    /**
     * 过滤插入的字段
     */
    public function filter_insert($filter_data = null,$insert_column = null){
    	if(!$filter_data || !$insert_column)
    		return false;
    	if(is_array($filter_data[key($filter_data)])){
    		$flag = true;
    		foreach ($filter_data as $k => $v){
    			$temp = array_intersect_key($v, array_flip($insert_column));
    			if(!$temp){
    				$flag = false;
    				break;
    			}else{
    				$filter_data[$k] = $temp;
    			}
    		}
    		if($flag){
    			return $filter_data;
    		}else{
    			return false;
    		}
    	}else{
    		return array_intersect_key($filter_data, array_flip($insert_column));
    	}
    	
    }
    
    public function insert($data = null){
    	if(empty($data)){
    		return false;
    	}
    	//检测字段
    	if(!$this->check_insert_data($data)){
    		return false;
    	}
    	$data = $this->filter_insert($data,$this->get_columns());
    	if(!$data){
    		return false;
    	}
    	if(is_array($data[key($data)])){
    		$this->db->insert_batch($this->table_name, $data);
    		return $this->db->affected_rows();
    	}else{
    		$this->db->insert($this->table_name, $data);
    		if ($this->db->primary($this->table_name)){
    			return $this->db->insert_id() >0 ?$this->db->insert_id():true;
    		}else {
    			return $this->db->insert_id() >=0?$this->db->insert_id():true;
    		}
    	}
    }
    
    //开启事务
    public function trans_begin()
    {
        $this->db->trans_begin();
    }
    //回滚
    public function trans_rollback()
    {
        $this->db->trans_rollback();
    }
    //提交事务
    public function trans_commit()
    {
        $this->db->trans_commit();
    }

}