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

class wog_act_exchange{
	function exchange_view($user_id)
	{
		global $DB_site,$_POST,$lang;
		$sql="select ex_id,ex_name,ex_money,ex_amount,ex_change,ex_change_time,ex_resupply_time,ex_chg_num from wog_exchange_main ";
		$ex=$DB_site->query($sql);
		while($exs=$DB_site->fetch_array($ex))
		{
			$s.=";".$exs[ex_id].",".$exs[ex_name].",".$exs[ex_money].",".$exs[ex_amount].",".$exs[ex_change].",".date('Y-m-d H:i:s',$exs[ex_change_time]).",".$exs[ex_chg_num];
		}
		$DB_site->free_result($ex);
		unset($exs);
		$s=substr($s,1,strlen($s));
		$s2="";
		$sql="select eb_body,eb_time from wog_exchange_book order by eb_id desc";
		$ex=$DB_site->query($sql);
		while($exs=$DB_site->fetch_array($ex))
		{
			$s2.=";".$exs[eb_body].",".set_date($exs[eb_time]);
		}
		$DB_site->free_result($ex);
		$s2=substr($s2,1,strlen($s2));
		showscript("parent.exchange_view('$s','$s2')");
		unset($s2,$s);
	}

	function exchange_list($user_id)
	{
		global $DB_site,$_POST,$lang;
		$sql="select a.el_id,a.el_amount,a.el_money,b.ex_name from wog_exchange_list a,wog_exchange_main b where a.p_id=".$user_id." and a.ex_id=b.ex_id ";
		$ex=$DB_site->query($sql);
		while($exs=$DB_site->fetch_array($ex))
		{
			$s.=";".$exs[el_id].",".$exs[el_amount].",".$exs[el_money].",".$exs[ex_name];
		}
		$DB_site->free_result($ex);
		unset($exs);
		$s=substr($s,1,strlen($s));
		showscript("parent.exchange_list('$s')");
		unset($s);
	}

	function exchange_buy($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		if(empty($_POST["item_id"]))
		{
			alertWindowMsg($lang['wog_act_syn_error6']);
		}
		if(empty($_POST["item_num"]))
		{
			alertWindowMsg($lang['wog_act_buy_errornum']);
		}
		$item_id=$_POST["item_id"];
		$item_num=$_POST["item_num"];
		if(!is_numeric($item_num) || preg_match("/[^0-9]/",$item_num))
		{
			alertWindowMsg($lang['wog_act_buy_errornum']);
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select ex_id,ex_name,ex_money,ex_amount,ex_change,ex_change_time,ex_resupply_time from wog_exchange_main where ex_id=".$item_id." for update";
		$check_item=$DB_site->query_first($sql);
		if(!$check_item)
		{
			alertWindowMsg($lang['wog_act_errwork']);
		}
		if($check_item[ex_amount] < $item_num)
		{
			alertWindowMsg($lang['wog_act_exchange_error1']);
		}
		$sql="select p_money from wog_player where p_id=".$user_id." for update";
		$check_p=$DB_site->query_first($sql);
		if(!$check_p)
		{
			alertWindowMsg($lang['wog_act_noid']);
		}
		$temp_money1=$item_num*$check_item[ex_money];
		$temp_money3=$temp_money1*$wog_arry["exchange_money"];
		if($temp_money3 < 1)
		{
			$temp_money3=$temp_money1+1;
		}
		else
		{
			$temp_money3=$temp_money1+$temp_money3;
		}
		if($check_p[p_money] < $temp_money3)
		{
			alertWindowMsg($lang['wog_act_nomoney']);
		}
		$sql="update wog_player set p_money=p_money-".$temp_money3." where p_id=".$user_id;
		$DB_site->query($sql);
		$sql="update wog_exchange_main set ex_amount=ex_amount-".$item_num." where ex_id=".$item_id;
		$DB_site->query($sql);
		$sql="select el_id,el_amount,el_money from wog_exchange_list where p_id=".$user_id." and ex_id=".$item_id." for update";
		$check_el=$DB_site->query_first($sql);
		if($check_el)
		{
			$temp_money2=(($check_el[el_amount]*$check_el[el_money])+$temp_money1)/($check_el[el_amount]+$item_num);
			$sql="update wog_exchange_list set el_amount=el_amount+".$item_num.",el_money=".$temp_money2." where el_id=".$check_el[el_id];
			$DB_site->query($sql);
		}
		else
		{
			$sql="insert wog_exchange_list(p_id,ex_id,el_amount,el_money)values($user_id,$item_id,$item_num,$check_item[ex_money])";
			$DB_site->query($sql);
		}
		$DB_site->query_first("COMMIT");
		unset($check_item,$check_el,$check_p);
		showscript("parent.act_click('exchange','list')");
	}

	function exchange_sale($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		if(empty($_POST["item_id"]))
		{
			alertWindowMsg($lang['wog_act_syn_error6']);
		}
		if(empty($_POST["item_num"]))
		{
			alertWindowMsg($lang['wog_act_buy_errornum']);
		}
		$item_id=$_POST["item_id"];
		$item_num=$_POST["item_num"];
		if(!is_numeric($item_num) || preg_match("/[^0-9]/",$item_num))
		{
			alertWindowMsg($lang['wog_act_buy_errornum']);
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select ex_id,el_amount,el_money from wog_exchange_list where p_id=".$user_id." and el_id=".$item_id." for update";
		$check_el=$DB_site->query_first($sql);
		if(!$check_el)
		{
			alertWindowMsg($lang['wog_act_errwork']);
		}
		if($check_el[el_amount] < $item_num)
		{
			alertWindowMsg($lang['wog_act_exchange_error2']);
		}
		$sql="select ex_money from wog_exchange_main where ex_id=".$check_el[ex_id]." for update";
		$check_ex=$DB_site->query_first($sql);
		$temp_money1=floor($item_num*$check_ex[ex_money]);
		$temp_money3=$temp_money1*$wog_arry["exchange_money"];
		if($temp_money3 < 1)
		{
			$temp_money3=$temp_money1-1;
		}
		else
		{
			$temp_money3=$temp_money1-$temp_money3;
		}
		if($item_num==$check_el[el_amount])
		{
			$sql="delete from  wog_exchange_list where el_id=".$item_id;
		}
		else
		{
			$sql="update wog_exchange_list set el_amount=el_amount-".$item_num." where el_id=".$item_id;
		}
		$DB_site->query($sql);
		$sql="update wog_player set p_money=p_money+".$temp_money3." where p_id=".$user_id;
		$DB_site->query($sql);
		$sql="update wog_exchange_main set ex_amount=ex_amount+".$item_num." where ex_id=".$check_el[ex_id];
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		unset($check_ex,$check_el);
		showscript("parent.act_click('exchange','list')");
	}
}
?>