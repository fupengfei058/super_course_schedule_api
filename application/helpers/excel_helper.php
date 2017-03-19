<?php
/**
 * 导入excel文件helper类
 * @param  int $row 开始行数
 * @param  string $fields     插入数据库字段数组
 * @param  array  $index_arr  index数组
 * @param  string $input_name input名
 * @param  array  $extra 添加的额外字段
 * @return insert_batch 数组
 */
class Excel_helper
{
	private $CI;

	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->library('third_party/PHPExcel');
        $this->CI->load->library('third_party/PHPExcel/IOFactory');
	}

	private function get_date($val) {
		$jd = GregorianToJD(1, 1, 1970);
		echo "==".$jd."==";
		$gregorian = JDToGregorian($jd + intval($val) - 25569);
		return $gregorian;
	}

	public function get_excel_data($row, $fields = array(), $index_arr = array(), $extra = array(), $input_name='Filedata')
	{
		$objPHPExcel = IOFactory::load($_FILES[$input_name]["tmp_name"]);
	    $data = $objPHPExcel->getSheet(0)->toArray();
	    $count = count($data);

	    $insert_batch = array();
	    for ($i = $row; $i < $count; $i++) {
	        $insert = array();
	        for($j = 0; $j < count($fields); $j ++) {
	            $insert += array($fields[$j] => $data[$i][$index_arr[$j]]);
	        }
	        $insert += $extra;
	        array_push($insert_batch, $insert);
	    }

	    return $insert_batch;
	}

	//设置每一列的宽度
	public function set_col_width($obj, $data)
	{
		foreach ($data as $k => $v)
		{
			$obj->getActiveSheet()->getColumnDimension($v['row'])->setWidth($v['width']);
		}

	}
	/**
	 *合并单元格 
	 *@param object $obj	PHPExcel对象
	 *@param array $merge	多维数组：pColumn是列数(0开始); pRow是行数(1开始)
	 * */
	public function export_mergeCells($obj, $merge){
		foreach($merge as $key=>$value){
			foreach($value as $k=>$v){
				$obj->getActiveSheet()->mergeCellsByColumnAndRow($v['pColumn1'], $v['pRow1'], $v['pColumn2'], $v['pRow2']);
			}
		}
	}

	public function export_excel_data($fields, $data, $title = "", $description = "导出列表", $param = "", $merge = "")
	{
// 	    p($data);die;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle($title)->setDescription($description);

		//设置列宽度
		if($param !== "")
		{
			$this->set_col_width($objPHPExcel, $param);
		}

		foreach ($fields as $key => $value) {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($key, 1, $value);
		}

		$row = 2;
		foreach ($data as $key => $value) {
			foreach ($value as $k => $v) {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k, $row, $v);
			}
			$row ++;
		}
		if($merge != ''){
			$this->export_mergeCells($objPHPExcel, $merge);
		}
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="export.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}
}
?>