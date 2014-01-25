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

class wog_act_honor{
	function honor_list($user_id)
	{
		global $DB_site,$_POST,$lang;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_syn_error6']);
		}
		if(empty($_POST["page"]) || !is_numeric($_POST["page"]))
		{
			$_POST["page"]="1";
		}
		$type_id=$_POST["temp_id"];
		$temp_type="shop";
		$temp2="";
		$sql="";
		switch($type_id){
			case "mercenary":
				$sql="select b.h_id,b.h_1,b.h_2,b.h_3,b.h_4,b.h_5,a.me_name as item_name,b.item_id,b.item_num from wog_mercenary_main a,wog_honor_main b where a.me_status=1 and b.h_type=1 and b.item_id=a.me_id";
				$sql_total="select count(h_id) as h_id  from wog_honor_main where h_type=1";
				$temp_type="mercenary";
				break;
			case "key":
				$sql="select b.h_id,b.h_1,b.h_2,b.h_3,b.h_4,b.h_5,a.d_name as item_name,b.item_id,b.item_num from wog_df a,wog_honor_main b where b.h_type=2 and b.item_id=a.d_id";
				$sql_total="select count(h_id) as h_id  from wog_honor_main where h_type=2";
				$temp_type="key";
				break;
			case "arm_1":
				$sql="select b.h_id,b.h_1,b.h_2,b.h_3,b.h_4,b.h_5,a.d_name as item_name,b.item_id,b.item_num from wog_df a,wog_honor_main b where b.h_type=3 and b.item_id=a.d_id";
				$sql_total="select count(h_id) as h_id  from wog_honor_main where h_type=3";
				$temp2="0";
				break;
			case "arm_2":
				$sql="select b.h_id,b.h_1,b.h_2,b.h_3,b.h_4,b.h_5,a.d_name as item_name,b.item_id,b.item_num from wog_df a,wog_honor_main b where b.h_type=4 and b.item_id=a.d_id";
				$sql_total="select count(h_id) as h_id  from wog_honor_main where h_type=4";
				$temp2="1";
				break;
			case "arm_3":
				$sql="select b.h_id,b.h_1,b.h_2,b.h_3,b.h_4,b.h_5,a.d_name as item_name,b.item_id,b.item_num from wog_df a,wog_honor_main b where b.h_type=5 and b.item_id=a.d_id";
				$sql_total="select count(h_id) as h_id  from wog_honor_main where h_type=5";
				$temp2="2";
				break;
			case "arm_4":
				$sql="select b.h_id,b.h_1,b.h_2,b.h_3,b.h_4,b.h_5,a.d_name as item_name,b.item_id,b.item_num from wog_df a,wog_honor_main b where b.h_type=6 and b.item_id=a.d_id";
				$sql_total="select count(h_id) as h_id  from wog_honor_main where h_type=6";
				$temp2="3";
				break;
			case "arm_5":
				$sql="select b.h_id,b.h_1,b.h_2,b.h_3,b.h_4,b.h_5,a.d_name as item_name,b.item_id,b.item_num from wog_df a,wog_honor_main b where b.h_type=7 and b.item_id=a.d_id";
				$sql_total="select count(h_id) as h_id  from wog_honor_main where h_type=7";
				$temp2="4";
				break;
			case "item":
				$sql="select b.h_id,b.h_1,b.h_2,b.h_3,b.h_4,b.h_5,a.d_name as item_name,b.item_id,b.item_num from wog_df a,wog_honor_main b where b.h_type=8 and b.item_id=a.d_id";
				$sql_total="select count(h_id) as h_id  from wog_honor_main where h_type=8";
				$temp2="5";
				break;
		} // switch
		$honor_total=$DB_site->query_first($sql_total);
		$spage=((int)$_POST["page"]*8)-8;
		$sql.=" LIMIT ".$spage.",8";
		$honor=$DB_site->query($sql);
		$s="";
		while($honors=$DB_site->fetch_array($honor))
		{
			$temp="";
			for($i=1;$i<6;$i++)
			{
				if($honors["h_".$i]>0){$temp.=":".$i."*".$honors["h_".$i];}
			}
			if(!empty($temp)){$temp=substr($temp,1);}
			$s.=";".$honors[h_id].",".$honors[item_name]."*".$honors[item_num].",".$temp.",".$honors[item_id];
		}
		$DB_site->free_result($honor);
		unset($honors);
		$s=substr($s,1,strlen($s));
		showscript("parent.honor_view('$s','$temp_type','$temp2',$honor_total[0],$_POST[page],'$type_id')");
		unset($s);
	}
	function honor_buy($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_item_tool,$a_id,$wog_arry;
		if(empty($_POST["h_id"]))
		{
			alertWindowMsg($lang['wog_act_syn_error6']);
		}
		$h_id=$_POST["h_id"];

		$sql="select a.item_id,a.h_1,a.h_2,a.h_3,a.h_4,a.h_5,a.h_type,a.item_num from wog_honor_main a where a.h_id=".$h_id;
		$check_item=$DB_site->query_first($sql);
		if(!$check_item)
		{
			alertWindowMsg($lang['wog_act_errwork']);
		}
		if($check_item[h_type]==1)
		{
			$sql="select me_id from wog_mercenary_list where p_id=".$user_id;
			$check_ml=$DB_site->query_first($sql);
			if($check_ml)
			{
				alertWindowMsg($lang['wog_act_mercenary_error1']);
			}
			$sql="select me_count,me_name from wog_mercenary_main where me_id=".$check_item[item_id];
			$mercenary_main=$DB_site->query_first($sql);
		}

		$sql="select d_honor_id from wog_item where p_id=".$user_id;
		$item=$DB_site->query_first($sql);
		$temp_sql="";
		$a_id="d_honor_id";
		$items=array();
		if(!empty($item[$a_id]))
		{
			$items=explode(",",$item[$a_id]);
		}
		for($i=1;$i<6;$i++)
		{
			switch($i){
				case 1:
					$need_id=1304;
					break;
				case 2:
					$need_id=1305;
					break;
				case 3:
					$need_id=1306;
					break;
				case 4:
					$need_id=1307;
					break;
				case 5:
					$need_id=2222;
					break;
			} // switch
			if($check_item["h_".$i]>0){
				$items=$wog_item_tool->item_out($user_id,$need_id,$check_item["h_".$i],$items);
			}
		}
		if($check_item[h_type]==1)
		{
			$temp_sql=$a_id."='".implode(',',$items)."'";
			$DB_site->query("update wog_item set ".$temp_sql." where p_id=".$user_id);
			$sql="insert wog_mercenary_list(p_id,me_id,me_count,me_status,me_name)values($user_id,$check_item[item_id],$mercenary_main[me_count],0,'$mercenary_main[me_name]')";
			$DB_site->query($sql);
		}
		else
		{
			$sql="select p_bag from wog_player where p_id=$user_id";
			$pay_id=$DB_site->query_first($sql);
			if(!$pay_id)
			{
				alertWindowMsg($lang['wog_act_noid']);
			}
			check_type($check_item["item_id"]);
			$bag=$wog_arry["item_limit"]+($a_id=="d_item_id" || $a_id=="d_stone_id" || $a_id=="d_key_id"?$p["p_bag"]:0);
			if((count($temp_pack)+1) > $bag)
				alertWindowMsg($lang['wog_act_bid_full']);
			$temp_sql="d_honor_id='".implode(',',$items)."'";
			$DB_site->query("update wog_item set ".$temp_sql." where p_id=".$user_id);

			$new_blank=$DB_site->query_first("select ".$a_id." from wog_item where p_id=".$user_id);
			$temp_pack=array();
			if(!empty($new_blank[0]))
				$temp_pack=explode(",",$new_blank[0]);
			$temp_pack=$wog_item_tool->item_in($temp_pack,$check_item["item_id"],$check_item["item_num"]);
			$DB_site->query("update wog_item set ".$a_id."='".implode(',',$temp_pack)."' where p_id=".$user_id);
		}
		unset($check_item,$items,$check_ml);
		showscript("parent.job_end(12,'',1)");
	}
}
?>