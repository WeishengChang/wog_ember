<?php

class JsonView {
	// 取得樣版並解析(dummy)
	public function fetch()
	{
		return null;
	}
    // 輸出
	public static function render()
	{
		// 因為 View 類別的 render 函式沒有參數
		// 所以 render 要自行取得
		$args = func_get_args();
		$data = $args[0];
		if(!headers_sent())
		{
			header('content-type:text/json;charset=utf-8');
			header('cache-control:no-cache');	
		}
		echo json_encode($data);
	}
}

?>

