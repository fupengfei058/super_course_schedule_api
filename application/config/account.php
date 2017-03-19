<?php 
#结算账号#
$config['account'] = array(
	1 => array(
		'account_id' => 1,
		'account_name' => '现金',
		'bank_name' => '现金',
		'bank_account' => '现金',
		'balance' => 0.00,
		'beginning_balance' => 0.00,
		'cashier_state' => 1,
		'online_state' => 1,
		'is_default' => 1,
		'create_time' => time(),
		'beizhu' => '现金'
	),
	2 => array(
		'account_id' => 2,
		'account_name' => '银联卡',
		'bank_name' => '银联卡',
		'bank_account' => '银联卡',
		'balance' => 0.00,
		'beginning_balance' => 0.00,
		'cashier_state' => 1,
		'online_state' => 0,
		'is_default' => 0,
		'create_time' => time(),
		'beizhu' => '银联卡'
	),
	3 => array(
		'account_id' => 3,
		'account_name' => '储值卡',
		'bank_name' => '储值卡',
		'bank_account' => '储值卡',
		'balance' => 0.00,
		'beginning_balance' => 0.00,
		'cashier_state' => 1,
		'online_state' => 1,
		'is_default' => 0,
		'create_time' => time(),
		'beizhu' => '储值卡'
	),
	4 => array(
		'account_id' => 4,
		'account_name' => '微信',
		'bank_name' => '微信',
		'bank_account' => '微信',
		'balance' => 0.00,
		'beginning_balance' => 0.00,
		'cashier_state' => 1,
		'online_state' => 1,
		'is_default' => 0,
		'create_time' => time(),
		'beizhu' => '微信'
	),
);

#后台不允许使用的账户，如虚拟账户等#
$config['no_use_account'] = array(
    3 => array(
        'account_id' => 3,
        'account_name' => '储值卡',
        'bank_name' => '储值卡',
        'bank_account' => '储值卡',
        'balance' => 0.00,
        'beginning_balance' => 0.00,
        'cashier_state' => 1,
        'online_state' => 1,
        'is_default' => 0,
        'create_time' => time(),
        'beizhu' => '储值卡'
    )
);

?>