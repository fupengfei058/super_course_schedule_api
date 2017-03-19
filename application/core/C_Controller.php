<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*CI_Controller扩展类*/
class C_Controller extends CI_Controller {

	public function __construct()
    {
        //继承父类构造函数
        parent::__construct();
        //开启session
        if (!isset($_SESSION)) {
            session_start();
        }
    }
}
/* End of file MY_Controller.php */
/* Location: ../application/core/MY_Controller.php */