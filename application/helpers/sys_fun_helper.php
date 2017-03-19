<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 取数组$arr的键与$limits的值的交集
 * @param $arr
 * @param $limits
 * @return array 返回$arr数组中的键在$limits的键值对数组
 */
if ( ! function_exists('array_key_intersect'))
{
	function array_key_intersect($arr, $limits)
	{
		// 将values 转为 keys
		$limits = array_flip($limits);
		return array_intersect_key($arr, $limits);
	}
}
/**
 * 打印数组函数
 * @param $arr
 */
if( ! function_exists('p'))
{
	function p($arr)
	{
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
/**
 * 一维数组键值转换
 * @param $arr
 */
if( ! function_exists('key_exchange'))
{
	function key_exchange($arr,$key = '')
	{
		$res = array();
		if($key == '')
			foreach ($arr as $k => $v){
				$value = array_values($v);
				$res[$value[0]]= $v;
			}
		else
			foreach ($arr as $k => $v){
				$value = array_values($v);
				$res[$v[$key]]= $v;
			}
		return $res;
	}
	
}
/**
 * 一维数组数据提取某个键值到数组，并以数组返回
 * @param $arr
 */
if( ! function_exists('array_value_one'))
{
	function array_value_one($arr,$key = '')
	{
		if($key == '')
			return $arr;
		$res = array();
		unset($v);
		foreach ($arr as $k => $v){
			array_push($res, $v[$key]);
		}
		return $res;
	}
}
/**
 * 对象转为数组
 * @param object
 */
if( ! function_exists('object_to_array'))
{
	function object_to_array($obj) {
		$ret = array();
		foreach ($obj as $key => $value) {
			if (gettype($value) == "array" || gettype($value) == "object"){
				$ret[$key] =  object_to_array($value);
			}else{
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}
/**
 * 模板输出数组和字符串检查函数
 * @param $data 被检测的数据
 * @param $flag 检测类型（str、arr）str字符串，arr数组，默认为数组
 * @param $default 默认值，该参数只对字符串有效
 */
if ( ! function_exists('echo_on_temp'))
{
	function echo_on_temp($data, $flag = 'str', $default = '')
	{
		switch ($flag)
		{
			case 'arr':
				if(isset($data) && is_array($data) && !empty($data))
				{
					return true;
				}
				else
				{
					return false;
				}
				break;

			case 'str':
			default :
				if(isset($data) && is_string($data) && $data != '')
				{
					return $data;
				}
				else
				{
					return $default;
				}
				break;
		}
	}
}
/**
 * 保存上次的跳转路径
 * @author: dong
 * */
/* if ( ! function_exists('redirect_url'))
{
    function redirect_url($url){
        if(!isset($_SESSION['prev_url'])){
        
            $_SESSION['prev_url'] = $url;
        
        }else{
            	
            if($_SESSION['prev_url'] != $url){
        
                $_SESSION['url'] = $_SESSION['prev_url'];
            }
        
        }
    }
} */
/**
 * post验证客户端登陆
 * @author eason
 */
if ( ! function_exists('validate_client'))
{
	function validate_client($post = null){
		if(!$post){
			return false;
		}
		$flag = false;
		if(isset($post['client']) && $post['client'] == true){
			//判断时间是否超时
			if(isset($post['time']) && $post['time'] + $this->config->item('client_expire') < time()){
				$flag = true;
			}else{
				//判断session_id 并获取session
				if(isset($post['session_id'])){
					session_start();
					$_SESSION = session_id($post['session_id']);
				}else
					$flag = true;
			}
		}else
			$flag = true;
		return $flag;
	}
}
?>