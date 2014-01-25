<?php
/*===================================================== 
 Copyright (C) ETERNAL<iqstar@ms24.hinet.net>
 URL : http://www.et99.net

請仔細閱讀以下許可協議。當您使用本軟體，您將自動成為本協議的一方並受到本協議的約束。

軟體和文檔受到臺灣及中國大陸著作權法及國際條約的保護。您不得：
a)營利、出租或者出借軟體或文檔的任何部分。
b)反向工程、分解、反編譯或者企圖察看軟體的源代碼作為商業用途。
c)修改或者改變軟體，或者與其他程式結合。

許可人保留軟體及文檔的所有權利和權益。您不能通過本許可協定獲得軟體的任何所有權和知識產權。
===================================================== */

class wog_act_pk{

	function pk_view($user_id)
	{
		global $DB_site,$lang;
		$sql="select p_pk_s,p_pk_money
		from wog_player where  p_id=".$user_id." 
		";
		$p=$DB_site->query_first($sql);
		showscript("parent.pk_view($p[p_pk_s],'$p[p_pk_money]')");
		unset($p);
	}

	function pk_setup($user_id)
	{
		global $DB_site,$_POST,$lang;
		if(empty($_POST["pk_money"]))
		{
			alertWindowMsg($lang['wog_act_nomoney']);
		}
		if(!is_numeric($_POST["pk_money"]))
		{
			alertWindowMsg($lang['wog_act_errmoney']);
		}
		$money=(int)$_POST["pk_money"];
		$sql="select p_money from wog_player where p_id=".$user_id;
		$p=$DB_site->query_first($sql);
		if($money>$p[p_money] || ($money<1000 and $_POST["pk_setup"]=="1") || ($money>100000 and $_POST["pk_setup"]=="1"))
		{
			alertWindowMsg($lang['wog_act_nomoney']);
		}else
		{
			$DB_site->query("update wog_player set p_pk_s=".$_POST["pk_setup"].",p_pk_money=".$money." where p_id=".$user_id."");
			showscript("parent.job_end(3,'',3)");
		}
		unset($p);
	}

}
?>