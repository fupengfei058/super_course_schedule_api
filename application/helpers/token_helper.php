<?php
//token 生成与验证

//token设置
//$param为参与token生成的数据，包括时间t，但是不包括密钥
function set_token($param,$secr='')
{
    #默认token
    if( empty($secr) ){
		$secr = 'FEFBCE77FD0EBD57711E545BF63D9A47';
	}

	$token = $secr;
	$token .= loop_array_token($param);
	$token .= $secr;
	if(isset($_REQUEST['show_token']) && $_REQUEST['show_token'] == 'yes'){
		echo "<br/>";
		echo "参数链接值:";
		echo "<br/>";
		echo $token;
		echo "<br/>";
	}
	$token = strtoupper(md5($token));
	if(isset($_REQUEST['show_token']) && $_REQUEST['show_token'] == 'yes'){
		echo "<br/>";
		echo "服务器生成的token的值:";
		echo "<br/>";
		echo $token;
		echo "<br/>";
	}
	return $token;
}
function loop_array_token($param){
	$token = "";
	ksort($param);
	foreach($param as $k=>$v){
		if(is_array($v)){
			$token .="{$k}";
			$token .= loop_array_token($v);
		}else{
			$token .= "{$k}{$v}";
		}
	}
	//处理特殊转义字符。
	return stripslashes($token);
}

//token验证
//$param为参与token生成的数据，包括时间t，但是不包括密钥
function check_token($tokenval,$param = array(),$time_limit=3600,$secr=''){
	$param = $param ? $param : array_merge((array)$_POST, (array)$_GET);
	if(isset($_REQUEST['show_token']) && $_REQUEST['show_token'] == 'yes'){
		echo "接受的全部参数：";
		print_r($param);
	}
	date_default_timezone_set("PRC");
	$currtime = time();
	if( isset($param['t']) && abs($param['t']-$currtime)<$time_limit ){
		$token = strtoupper($tokenval);
		if(isset($_REQUEST['show_token']) && $_REQUEST['show_token'] == 'yes'){
			echo "<br/>";
			echo "服务器接受到的token的值:";
			echo "<br/>";
			echo $token;
			echo "<br/>";
			echo "参加token加密的参数:<br/>";
			print_r($param); 
		}
		$gettoken = set_token($param,$secr);
		return $gettoken;
		if($gettoken === $token){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}