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

class wog_mission_tool{
	function mission_check($user_id,$m_id)
	{
		global $DB_site,$_POST,$lang;
		$sql="select a.m_id,b.m_end_message,a.m_status,b.m_pet_id,a.m_kill_num,b.m_lv
		,b.n_a_id,b.n_d_body_id,b.n_d_foot_id,b.n_d_hand_id,b.n_d_head_id,b.n_d_item_id,b.n_d_stone_id,b.n_d_honor_id,b.n_d_plus_id
		,b.n_skill_id
		,b.g_a_id,b.g_d_body_id,b.g_d_foot_id,b.g_d_hand_id,b.g_d_head_id,b.g_d_item_id,b.g_d_stone_id,b.g_d_honor_id,b.g_d_plus_id
		,b.g_skill_id,b.g_pet_id,b.g_pet_lv,b.ex_id,b.ex_num,b.g_money
		from wog_mission_book a,wog_mission_main b where a.p_id=".$user_id." and a.m_id=".$m_id." and a.m_status<2 and b.m_id=a.m_id";
		$m_book=$DB_site->query_first($sql);
		if(!$m_book)
		{
			alertWindowMsg($lang['wog_act_mission_error4']);
		}
		if($m_book["m_status"]!=1 && $m_book["m_kill_num"]!=0)
		{
			alertWindowMsg($lang['wog_act_mission_error6']);
		}
		if(!empty($m_book["n_skill_id"]))
		{
			$sql="select main_sid,s_lv from wog_ch_skill where s_id=".$m_book["n_skill_id"];
			$s=$DB_site->query_first($sql);
			$sql="select s_lv from wog_skill_book where p_id=".$user_id." and main_sid=".$s[main_sid];
			$s2=$DB_site->query_first($sql);
			if($s[s_lv] > $s2[s_lv])
			{
				alertWindowMsg($lang['wog_act_mission_error7']);
			}
			unset($s,$s2);
		}
		
		if($m_book[m_pet_id] != 0)
		{
			$sql="select pe_id from wog_pet where pe_p_id=".$user_id." and pe_st=0 and pe_m_id=".$m_book[m_pet_id];
			echo $sql;
			$pet=$DB_site->query_first($sql);
			if(!$pet)
			{
				alertWindowMsg($lang['wog_act_mission_error4']);
			}
			$DB_site->query("delete from wog_pet where pe_p_id=".$user_id." and pe_st=0 and pe_m_id=".$m_book[m_pet_id]);
		}
		return $m_book;
	}
	function mission_pet_get($user_id,$m_pet_id,$up)
	{
		global $DB_site,$lang;
		$sql="select m_id,m_name,m_img from wog_monster where m_id=".$m_pet_id." LIMIT 1 ";
		$m=$DB_site->query_first($sql);
		if(!$m)//m date check start
		{
			alertWindowMsg($lang['wog_act_errdate']);
		}
		$pet[pe_at]=rand(1,10)*$up;
		$pet[pe_mt]=rand(1,10)*$up;
		$pet[pe_def]=rand(1,10)*$up;
		$pet[pe_type]=rand(1,4);
		$sql="insert into wog_pet(pe_p_id,pe_name,pe_at,pe_mt,pe_fu,pe_def,pe_hu,pe_type,pe_age,pe_he,pe_fi,pe_dateline,pe_mname,pe_m_id,pe_b_dateline,pe_mimg,p_send)";
		$sql.="values(".$user_id.",'".$m[m_name]."',".$pet[pe_at].",".$pet[pe_mt].",80,".$pet[pe_def].",0,".$pet[pe_type].",1,0,1,".(time()-20).",'".$m[m_name]."',".$m[m_id].",".time().",'".$m[m_img]."',1)";
		$DB_site->query($sql);
		unset($m,$pet);
	}
	function mission_ex_get($user_id,$ex_id,$ex_num)
	{
		global $DB_site,$lang;
		$sql="select el_id,el_amount,el_money from wog_exchange_list where p_id=".$user_id." and ex_id=".$ex_id." for update";
		$check_el=$DB_site->query_first($sql);
		if($check_el)
		{
			$temp_money2=($check_el[el_amount]*$check_el[el_money])/($check_el[el_amount]+$ex_num);
			$sql="update wog_exchange_list set el_amount=el_amount+".$ex_num.",el_money=".$temp_money2." where el_id=".$check_el[el_id];
			$DB_site->query($sql);
		}
		else
		{
			$sql="insert wog_exchange_list(p_id,ex_id,el_amount,el_money)values($user_id,$ex_id,$ex_num,0)";
			$DB_site->query($sql);
		}
		unset($m,$pet);
	}
	function mission_skill($user_id,$s_id)
	{
		global $DB_site,$lang;
		$sql="select p_id from wog_skill_book where s_id=".$s_id." and p_id=".$user_id;
		$need=$DB_site->query_first($sql);
		if($need)
		{
			alertWindowMsg($lang['wog_act_skill_er1']);
		}
		$sql="select need_sid,main_sid,s_lv from wog_ch_skill where s_id=".$s_id;
		$need=$DB_site->query_first($sql);
		if(!$need)
		{
			alertWindowMsg($lang['wog_act_skill_er1']);
		}
		if($need["need_sid"] > 0)
		{
			$sql="select p_id from wog_skill_book where s_id=".$need["need_sid"]." and p_id=".$user_id;
			$need2=$DB_site->query_first($sql);
			if(!$need2)
			{
				$sql="select s_name,s_lv from wog_ch_skill where s_id=".$need["need_sid"];
				$need2=$DB_site->query_first($sql);
				alertWindowMsg(sprintf($lang['wog_act_skill_er5'],($need2[s_name]."LV".$need2[s_lv])));
			}
		}

		if($need[main_sid]!=27 && $need[main_sid]!=28 )
		{
			$sql="select p_id from wog_skill_book where p_id=".$user_id." and main_sid=".$need[main_sid];
			$chk=$DB_site->query_first($sql);
		}
		if($chk)
		{
			$sql="update wog_skill_book set s_lv=".$need[s_lv].",s_id=".$s_id." where main_sid=".$need[main_sid]." and p_id=".$user_id;
		}else
		{
			$sql="insert wog_skill_book(p_id,s_id,main_sid,s_lv)values($user_id,$s_id,".$need["main_sid"].",$need[s_lv])" ;
		}
		$DB_site->query($sql);
	}
	function mission_item($user_id,$item_array,$item_date)
	{
		global $DB_site,$_POST,$lang,$wog_item_tool,$a_id;
		$sql="select a_id,d_body_id,d_foot_id,d_hand_id,d_head_id,d_item_id,d_stone_id,d_honor_id,d_plus_id from wog_item where p_id=".$user_id." for update";
		$item=$DB_site->query_first($sql);
		for($i=0;$i<count($item_array);$i++)
		{
			$a_id=$item_array[$i];
			$items=array();
			if(!empty($item[$a_id]))
			{
				$items=explode(",",$item[$a_id]);
			}
			for($j=0;$j<count($item_date[$a_id]);$j++)
			{
				if(empty($item_date[$a_id][$j])){continue;}
				if($a_id=="d_item_id" || $a_id=="d_stone_id" || $a_id=="d_honor_id" || $a_id=="d_plus_id")
				{
					$need_item=explode("*",$item_date[$a_id][$j]);
					$items=$wog_item_tool->item_out($user_id,$need_item[0],$need_item[1],$items);
				}else
				{
					$items=$wog_item_tool->item_out($user_id,$item_date[$a_id][$j],1,$items,true,true);
				}
			}
			$item[$a_id]=implode(',',$items);
		}
		return $item;
	}
	function mission_reward($user_id,$item_array,$item_date,$item=null)
	{
		global $DB_site,$_POST,$lang,$wog_item_tool,$a_id;
		if($item==null)
		{
			$sql="select a_id,d_body_id,d_foot_id,d_hand_id,d_head_id,d_item_id,d_stone_id,d_honor_id,d_plus_id from wog_item where p_id=".$user_id." for update";
			$item=$DB_site->query_first($sql);
		}
		for($i=0;$i<count($item_array);$i++)
		{
			$a_id=$item_array[$i];
			$items=array();
			if(!empty($item[$a_id]))
			{
				$items=explode(",",$item[$a_id]);
			}
			for($j=0;$j<count($item_date[$a_id]);$j++)
			{
				if(empty($item_date[$a_id][$j])){continue;}
				if($a_id=="d_item_id" || $a_id=="d_stone_id" || $a_id=="d_honor_id" || $a_id=="d_plus_id")
				{
					$need_item=explode("*",$item_date[$a_id][$j]);
					$items=$wog_item_tool->item_in($items,$need_item[0],$need_item[1]);
				}else
				{
					$items=$wog_item_tool->item_in($items,$item_date[$a_id][$j],0);
				}
			}
			$item[$a_id]=implode(',',$items);
		}
		return $item;
	}
	function mission_money($user_id,$money)
	{
		global $DB_site;
		$DB_site->query("update wog_player set p_money=p_money+".$money." where p_id=".$user_id);
	}
	function mission_status_update($user_id,$m_id,$m_end_message,$m_lv,$item=null,$item_array)
	{
		global $DB_site,$_POST,$lang;
		$DB_site->query("update wog_mission_book set m_status=2 where p_id=".$user_id." and m_id=".$m_id);
		$DB_site->query("update wog_player set p_exp=p_exp+".($m_lv*190)." where p_id=".$user_id);
		if($item!=null)
		{
			$temp_sql="";
			for($i=0;$i<count($item_array);$i++)
			{
				$temp_sql.=",".$item_array[$i]."='".$item[$item_array[$i]]."'";
			}
			$temp_sql=substr($temp_sql,1);
			$DB_site->query("update wog_item set ".$temp_sql." where p_id=".$user_id);
		}
		//setcookie("wog_cookie_mission_id",0);
		$m_end_message=str_replace("\r\n","&n",$m_end_message);
		return $m_end_message;
	}
}
