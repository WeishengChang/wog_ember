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

class wog_act_mercenary{
	function mercenary_view($user_id)
	{
		global $DB_site,$_POST,$lang;
		$sql="select me_id,me_name,me_money,me_count,me_get_money,me_get_item,me_get_exp,me_die from wog_mercenary_main where me_status=0";
		$ex=$DB_site->query($sql);
		while($exs=$DB_site->fetch_array($ex))
		{
			$s.=";".$exs[me_id].",".$exs[me_name].",".$exs[me_money].",".$exs[me_count].",".$exs[me_get_money].",".$exs[me_get_item].",".$exs[me_get_exp].",".$exs[me_die];
		}
		$DB_site->free_result($ex);
		unset($exs);
		$s=substr($s,1,strlen($s));

		$s2="";
		$sql="select a.id,a.me_status,a.me_count,a.me_name,a.me_place from wog_mercenary_list a where a.p_id=$user_id limit 1";
		$ex=$DB_site->query($sql);
		while($exs=$DB_site->fetch_array($ex))
		{
			$s2.=";".$exs[id].",".$exs[me_status].",".$exs[me_count].",".$exs[me_name].",".$exs[me_place];
		}
		$DB_site->free_result($ex);
		$s2=substr($s2,1,strlen($s2));

		$s3="";
		$sql="select a.me_body,a.me_time from wog_mercenary_book a where a.p_id=$user_id  order by a.me_time desc limit 10";
		$ex=$DB_site->query($sql);
		while($exs=$DB_site->fetch_array($ex))
		{
			$s3.=";".$exs[me_body].",".set_date($exs[me_time]);
		}
		$DB_site->free_result($ex);
		$s3=substr($s3,1,strlen($s3));

		showscript("parent.mercenary_view('$s','$s2','$s3')");
		unset($s,$s2,$s3);
	}
	function mercenary_buy($user_id)
	{
		global $DB_site,$_POST,$lang;
		if(empty($_POST["item_id"]))
		{
			alertWindowMsg($lang['wog_act_syn_error6']);
		}
		$item_id=$_POST["item_id"];
		$sql="select me_id,me_money,me_count,me_name from wog_mercenary_main where me_id=".$item_id." and me_status=0";
		$check_item=$DB_site->query_first($sql);
		if(!$check_item)
		{
			alertWindowMsg($lang['wog_act_errwork']);
		}
		$sql="select me_id from wog_mercenary_list where p_id=".$user_id;
		$check_ml=$DB_site->query_first($sql);
		if($check_ml)
		{
			alertWindowMsg($lang['wog_act_mercenary_error1']);
		}
		$sql="select p_money from wog_player where p_id=".$user_id."";
		$check_p=$DB_site->query_first($sql);
		if(!$check_p)
		{
			alertWindowMsg($lang['wog_act_noid']);
		}
		if($check_p[p_money] < $check_item[me_money])
		{
			alertWindowMsg($lang['wog_act_nomoney']);
		}
		$sql="update wog_player set p_money=p_money-".$check_item[me_money]." where p_id=".$user_id;
		$DB_site->query($sql);

		$sql="insert wog_mercenary_list(p_id,me_id,me_count,me_status,me_name)values($user_id,$item_id,$check_item[me_count],0,'$check_item[me_name]')";
		$DB_site->query($sql);
		unset($check_item,$check_el,$check_p);
		showscript("parent.act_click('mercenary','view')");
	}
	function mercenary_set($user_id)
	{
		global $DB_site,$_POST,$lang;
		if(empty($_POST["item_id"]))
		{
			alertWindowMsg($lang['wog_act_syn_error6']);
		}
		if($_POST["status"]=="")
		{
			alertWindowMsg($lang['wog_act_mercenary_error2']);
		}
		if(empty($_POST["me_name"]))
		{
			alertWindowMsg($lang['wog_act_mercenary_error4']);
		}
		if(strlen($_POST["me_name"]) > 24)
		{
			alertWindowMsg($lang['wog_act_mercenary_error5']);
		}
		$me_name=$_POST["me_name"];
		$status=$_POST["status"];
		if($_POST["act1"]=="" && $status =="1")
		{
			alertWindowMsg($lang['wog_act_mercenary_error3']);
		}
		$item_id=$_POST["item_id"];
		$act1=$_POST["act1"];
		switch ($status)
		{
			case "0":
				$sql="update wog_mercenary_list set me_status=$status,me_name='$me_name' where id=$item_id and p_id=$user_id";
			break;
			case "1":
				$sql="update wog_mercenary_list set me_status=$status,me_place=$act1,me_name='$me_name' where id=$item_id and p_id=$user_id";
			break;
			case "2":
				$sql="delete from wog_mercenary_book where p_id=$user_id";
				$DB_site->query($sql);
				$sql="delete from wog_mercenary_list where id=$item_id and p_id=$user_id";
			break;
		}
		$DB_site->query($sql);
		showscript("parent.mercenary_set=$status;parent.act_click('mercenary','view')");
	}
	function check_item($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$item_id=$_POST["temp_id"];
		$show_type=3;
		$sql="select me_name,me_count,me_get_money,me_get_item,me_get_exp,me_die from wog_mercenary_main where me_id=".$item_id;
		$item_main=$DB_site->query_first($sql);
		if($item_main)
		{
			$s=$item_main[me_name].",".$item_main[me_count].",".$item_main[me_get_money].",".$item_main[me_get_item].",".$item_main[me_get_exp].",".$item_main[me_die];
		}
		unset($item_main);
		showscript("parent.wog_message_box('$s',1,$show_type)");
	}
}
?>