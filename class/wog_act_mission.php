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

class wog_act_mission{
	function mission_list($user_id)
	{
		global $DB_site,$lang,$_POST;
		$sql="select p_lv,p_sex,ch_id,p_birth from wog_player where p_id=".$user_id;
		$p=$DB_site->query_first($sql);
		if(!$p)
		{
			alertWindowMsg($lang['wog_act_relogin']);
		}
		$DB_site->query('DELETE wog_mission_book FROM wog_mission_book,wog_mission_repeat WHERE wog_mission_repeat.m_id=wog_mission_book.m_id AND wog_mission_repeat.update_time<'.time());
		$m_total=$DB_site->query_first("select count(a.m_id) as m_id from wog_mission_main a LEFT JOIN wog_mission_book b ON  b.p_id=".$user_id." and a.m_id=b.m_id
			LEFT JOIN wog_mission_book c ON  c.p_id=".$user_id." and a.m_need_id=c.m_id and a.m_not_id<>c.m_id
			where  (a.m_job=".$p["ch_id"]." or a.m_job=99) and a.m_lv <= ".$p["p_lv"]." and (a.m_sex=".$p["p_sex"]." or a.m_sex=3)
			and (a.m_birth is null or a.m_birth = ".$p["p_birth"].")
			and ((b.m_id is null and a.m_need_id=0) or ((c.m_status=2 or c.m_status=3 ) and b.m_id is null) ) ");
		if(empty($_POST["page"]) || !is_numeric($_POST["page"]))
		{
			$_POST["page"]="1";
		}
		$spage=((int)$_POST["page"]*8)-8;
		$sql="select a.m_id,a.m_subject,a.m_name,ifnull(d.update_time,0) as update_time,a.m_lv from wog_mission_main a LEFT JOIN wog_mission_book b ON  b.p_id=".$user_id." and a.m_id=b.m_id
			LEFT JOIN wog_mission_book c ON  c.p_id=".$user_id." and a.m_need_id=c.m_id and a.m_not_id<>c.m_id
			LEFT JOIN wog_mission_repeat d ON  d.m_id=a.m_id
			where (a.m_job=".$p["ch_id"]." or a.m_job=99) and a.m_lv <= ".$p["p_lv"]." and (a.m_sex=".$p["p_sex"]." or a.m_sex=3)
			and (a.m_birth is null or a.m_birth = ".$p["p_birth"].")
			and ((b.m_id is null and a.m_need_id=0) or ((c.m_status=2 or c.m_status=3)  and b.m_id is null) ) order by a.m_repeat desc,a.m_lv,a.m_name asc LIMIT ".$spage.",8  ";
		$m=$DB_site->query($sql);
		while($ms=$DB_site->fetch_array($m))
		{
			$update_time="∞";
			if($ms["update_time"] > 0)
			{
				$update_time=set_date($ms["update_time"]);
			}
			$s.=";".$ms["m_id"].",".$ms["m_name"].",".$ms["m_subject"].",".$update_time.",".$ms["m_lv"];
		}
		$DB_site->free_result($m);
		unset($ms,$p,$m);
		$s=substr($s,1);
		showscript("parent.mission_list($m_total[0],".$_POST["page"].",'$s')");
	}
	function mission_detail($user_id)
	{
		global $DB_site,$_POST,$lang,$repeat,$wog_arry;
		if(empty($_POST["temp_id"])){alertWindowMsg($lang['wog_act_mission_error1']);}
		$m_id=$_POST["temp_id"];
		$DB_site->query('DELETE wog_mission_book FROM wog_mission_book,wog_mission_repeat WHERE wog_mission_repeat.m_id=wog_mission_book.m_id AND wog_mission_repeat.update_time<'.time());
		$sql="select a.m_id,a.m_body,a.m_subject,a.m_name,b.repeat_type,b.update_time,b.reward_type
		,a.n_a_id,a.n_d_body_id,a.n_d_foot_id,a.n_d_hand_id,a.n_d_head_id,a.n_d_item_id,a.n_d_stone_id,a.n_d_honor_id,a.n_d_plus_id
		,a.n_skill_id
		,a.g_a_id,a.g_d_body_id,a.g_d_foot_id,a.g_d_hand_id,a.g_d_head_id,a.g_d_item_id,a.g_d_stone_id,a.g_d_honor_id,a.g_d_plus_id
		,a.g_skill_id,a.g_pet_id,a.g_pet_lv,a.ex_id,a.ex_num,a.g_money,a.m_monster_id,a.m_pet_id,a.g_pet_id
		from wog_mission_main a left join wog_mission_repeat b on b.m_id=a.m_id
		where a.m_id=".$m_id;

		$m_item=$DB_site->query_first($sql);

		if(!$m_item){
			alertWindowMsg($lang['wog_act_syn_error1']);
		}
		$m_item[1]=str_replace("\r\n",'&n',$m_item[1]);
		$repeat=null;
		// 取出委託及獎勵內容 begin
		$temp_item=array();
		$temp_item2=array();
		$need_body=array();
		$get_body=array();
		$need_body_sql="";
		$get_body_sql="";
		if(!empty($m_item[n_a_id])){$need_body_sql.=",".$m_item[n_a_id];}
		if(!empty($m_item[n_d_body_id])){$need_body_sql.=",".$m_item[n_d_body_id];}
		if(!empty($m_item[n_d_foot_id])){$need_body_sql.=",".$m_item[n_d_foot_id];}
		if(!empty($m_item[n_d_hand_id])){$need_body_sql.=",".$m_item[n_d_hand_id];}
		if(!empty($m_item[n_d_head_id])){$need_body_sql.=",".$m_item[n_d_head_id];}

		$this->mission_item_tool($m_item[n_d_item_id],$temp_item,$temp_item2);
		$this->mission_item_tool($m_item[n_d_stone_id],$temp_item,$temp_item2);
		$this->mission_item_tool($m_item[n_d_honor_id],$temp_item,$temp_item2);
		$this->mission_item_tool($m_item[n_d_plus_id],$temp_item,$temp_item2);
		if(count($temp_item)>0)
		{
			$need_body_sql.=",".implode(",",$temp_item);
		}

		if(!empty($need_body_sql))
		{
			$need_body_sql=substr($need_body_sql,1);
			$sql="select d_id,d_name from wog_df where d_id in ($need_body_sql)";
			$item=$DB_site->query($sql);
			while($items=$DB_site->fetch_array($item))
			{
				$need_body[]='<a href=javascript:parent.act_click("shop","check_item","'.$items[d_id].'") ondblclick=parent.set_arm_tochat("'.$items[d_name].'","'.$items[d_id].'");>'.$items[d_name].$temp_item2[$items[d_id]].'</a>';
			}
			$DB_site->free_result($item);
			unset($items);
		}
		if(!empty($m_item[m_monster_id]))
		{
			$temp_item=explode(",",$m_item[m_monster_id]);
			$temp_item2=array();
			$temp_item3=array();
			foreach($temp_item as $v)
			{
				$temp=explode("*",$v);
				$temp_item2[$temp[0]]=$temp[1];
				$temp_item3[]=$temp[0];
			}
			$sql="select m_id,m_name,m_place from wog_monster where m_id in (".implode(",",$temp_item3).")";
			$item=$DB_site->query($sql);
			$temp=array();
			while($items=$DB_site->fetch_array($item))
			{
				$temp[]=$items[m_name]."*".$temp_item2[$items[m_id]]."(".$wog_arry["place"][$items[m_place]].")";
			}
			$need_body[]=$lang['wog_act_mission_msg2']." ".implode(" ， ",$temp);
			$DB_site->free_result($item);
			unset($items);
		}
		if(!empty($m_item[m_pet_id]))
		{
			$sql="select m_name,m_place from wog_monster where m_id = $m_item[m_pet_id]";
			$item=$DB_site->query_first($sql);
			$need_body[]=$lang['wog_act_mission_msg3']." ".$item[m_name]."(".$wog_arry["place"][$item[m_place]].")";
			$DB_site->free_result($item);
			unset($items);
		}
		if(!empty($m_item[n_skill_id]))
		{
			$sql="select s_name,s_lv from wog_ch_skill where s_id = $m_item[n_skill_id]";
			$item2=$DB_site->query_first($sql);
			$need_body[]=$item2[s_name]."LV".$item2[s_lv];
			unset($item2);
		}
		$temp_item=array();
		$temp_item2=array();
		if(!empty($m_item[g_a_id])){$get_body_sql.=",".$m_item[g_a_id];}
		if(!empty($m_item[g_d_body_id])){$get_body_sql.=",".$m_item[g_d_body_id];}
		if(!empty($m_item[g_d_foot_id])){$get_body_sql.=",".$m_item[g_d_foot_id];}
		if(!empty($m_item[g_d_hand_id])){$get_body_sql.=",".$m_item[g_d_hand_id];}
		if(!empty($m_item[g_d_head_id])){$get_body_sql.=",".$m_item[g_d_head_id];}
		$this->mission_item_tool($m_item[g_d_item_id],$temp_item,$temp_item2);
		$this->mission_item_tool($m_item[g_d_stone_id],$temp_item,$temp_item2);
		$this->mission_item_tool($m_item[g_d_honor_id],$temp_item,$temp_item2);
		$this->mission_item_tool($m_item[g_d_plus_id],$temp_item,$temp_item2);
		if(count($temp_item)>0)
		{
			$get_body_sql.=",".implode(",",$temp_item);
		}
		if(!empty($get_body_sql))
		{
			$get_body_sql=substr($get_body_sql,1);
			$sql="select d_id,d_name from wog_df where d_id in ($get_body_sql)";
			$item=$DB_site->query($sql);
			while($items=$DB_site->fetch_array($item))
			{
				$get_body[]='<a href=javascript:parent.act_click("shop","check_item","'.$items[d_id].'") ondblclick=parent.set_arm_tochat("'.$items[d_name].'","'.$items[d_id].'");>'.$items[d_name].$temp_item2[$items[d_id]].'</a>';
			}
			$DB_site->free_result($item);
			unset($items);
		}
		if(!empty($m_item[g_pet_id]))
		{
			$sql="select m_name from wog_monster where m_id = $m_item[g_pet_id]";
			$item=$DB_site->query_first($sql);
			$get_body[]=$lang['wog_act_mission_msg4']." ".$item[m_name];
		}
		if(!empty($m_item[g_money]))
		{
			$get_body[]=$lang['wog_act_mission_msg5']." ".$m_item[g_money];
		}
		if(!empty($m_item[g_skill_id]))
		{
			$sql="select s_name,s_lv,need_sid from wog_ch_skill  where s_id = $m_item[g_skill_id]";
			$item=$DB_site->query_first($sql);
			if(!empty($item[need_sid]))
			{
				$sql="select s_name,s_lv from wog_ch_skill  where s_id = $item[need_sid]";
				$item2=$DB_site->query_first($sql);
				$need_body[]=$item2[s_name]."LV".$item2[s_lv];
				unset($item2);
			}
			$get_body[]=$lang['wog_act_mission_msg6']." ".$item[s_name]."LV".$item[s_lv];
		}
		if(!empty($m_item[ex_id]))
		{
			$sql="select ex_name from wog_exchange_main where ex_id = $m_item[ex_id]";
			$item=$DB_site->query_first($sql);
			$get_body[]=$lang['wog_act_mission_msg7']." ".$item[ex_name]."*".$m_item[ex_num];
		}
		if(count($get_body)<=0)
		{
			$get_body[]=$lang['wog_act_mission_msg8'];
		}
		if(count($need_body)<=0)
		{
			$need_body[]=$lang['wog_act_mission_msg8'];
		}
		$m_item[1].=sprintf($lang['wog_act_mission_msg1'],implode("，",$need_body),implode("，",$get_body));
		// 取出委託及獎勵內容 end
		if(!is_null($m_item[5])){
			if(file_exists('./mission/wog_mission_'.$m_id.'.php')){
				include_once('./mission/wog_mission_'.$m_id.'.php');
				$repeat=mission_start();
			}
			if(time()>$m_item[5]){
				$this->updateRepeatMission($m_id,$m_item[4],$m_item[5],$m_item[6]);
				$this->mission_detail($user_id);
			}
			$m_item[4]=set_date($m_item[5]);
		}
		unset($m_item[4],$m_item[6],$m_item[5],$item);

		showscript("parent.wog_message_box('$m_item[1]',0)");
	}
	function mission_get($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		if(empty($_POST["m_id"])){alertWindowMsg($lang['wog_act_mission_error1']);}
		$DB_site->query('DELETE wog_mission_book FROM wog_mission_book,wog_mission_repeat WHERE wog_mission_repeat.m_id=wog_mission_book.m_id AND wog_mission_repeat.update_time<'.time());
		$sql="select p_lv,p_sex,ch_id from wog_player where p_id=".$user_id;
		$p=$DB_site->query_first($sql);
		if(!$p){
			alertWindowMsg($lang['wog_act_relogin']);
		}
		$m_item=$DB_site->query_first("select count(m_id) as num from wog_mission_book where  p_id=".$user_id." and m_status<2");
		if($m_item['num'] >= $wog_arry['mission_get_num']) {
			alertWindowMsg($lang['wog_act_mission_error6']);
		}
		$m_item=$DB_site->query_first("select a.m_id from wog_mission_main a ,wog_mission_book b where  b.p_id=".$user_id." and a.m_id=".$_POST["m_id"]." and a.m_not_id=b.m_id");
		if($m_item){alertWindowMsg($lang['wog_act_mission_error3']);}
		$m_item=$DB_site->query_first("select a.m_id,m_monster_id
			from wog_mission_main a
			where a.m_id=".$_POST["m_id"]." and (a.m_job=".$p["ch_id"]." or a.m_job=99) and a.m_lv <= ".$p["p_lv"]." and (a.m_sex=".$p["p_sex"]." or a.m_sex=3)");
		if(!$m_item){alertWindowMsg($lang['wog_act_mission_error3']);}
		$sql="insert into wog_mission_book (m_id,p_id,m_status,m_kill_num)values(".$_POST['m_id'].",".$user_id.",0,'".$m_item[m_monster_id]."')";
		$DB_site->query($sql);
		setcookie("wog_cookie_mission_id",$_POST["m_id"]);
		unset($m_item,$temp_s);
		//showscript("parent.job_end(20)");
		$this->mission_list($user_id);
	}
	function mission_book($user_id)
	{
		global $DB_site,$_POST,$lang;
		$missions=array();
		$DB_site->query('DELETE wog_mission_book FROM wog_mission_book,wog_mission_repeat WHERE wog_mission_repeat.m_id=wog_mission_book.m_id AND wog_mission_repeat.update_time<'.time());
		$query=$DB_site->query("select a.m_id,a.m_subject,a.m_name,b.m_kill_num,c.update_time,c.repeat_type,c.reward_type from wog_mission_main a,wog_mission_book b LEFT JOIN wog_mission_repeat c on c.m_id=b.m_id where b.p_id=".$user_id." and b.m_status<2 and b.m_id=a.m_id");
		while($d=$DB_site->fetch_row($query)){
//			$d[1]=str_replace("\r\n","&n",$d[1]);
			if($d[4]>0){
				$d[4]-=time();
/*
				if(file_exists('./mission/wog_mission_'.$d[0].'.php')){
					include_once('./mission/wog_mission_'.$d[0].'.php');
					$repeat=mission_start();
					$d[1].=sprintf($lang['wog_act_mission_msg1'],$repeat[0][$d[7]]['request'],$repeat[1][$d[8]]['reward']);
				}
*/
			}else{
				unset($d[4]);
			}
			if(!empty($d[3])){
				$m=explode(",",$d[3]);
				$a=array();
				$b=array();
				foreach($m as $k => $v)
				{
					$t=explode("*",$v);
					$a[]=$t[0];
					$b[$t[0]]=$t[1];
				}
				$monster=array();
				$query2=$DB_site->query('Select m_name,m_id From wog_monster Where m_id in ('.implode(",",$a).')');
				while($i=$DB_site->fetch_row($query2)){
					$monster[]=$i[0]."*".$b[$i[1]];
				}
				$d[3]=implode(' , ',$monster);
				$DB_site->free_result($query2);
			}
			$missions[]=$d;
		}
		if(count($missions)<1){
		//	alertWindowMsg($lang['wog_act_mission_error5']);
		}
		unset($a,$b,$t,$m,$d);
		showscript("parent.mission_book(".toJSON($missions).")");
	}
	function mission_break($user_id)
	{
		global $DB_site,$_POST,$lang;
		$m_item=$DB_site->query_first("select a.m_id from wog_mission_book a where a.p_id=".$user_id." and a.m_status<2 and a.m_id=".$_POST["temp_id"]);
		if(!$m_item){alertWindowMsg($lang['wog_act_mission_error5']);}
		$sql="delete from wog_mission_book where p_id=".$user_id." and m_status<2 and m_id=".$m_item["m_id"];
		$DB_site->query($sql);
		unset($m_item);
		//showscript("parent.job_end(22)");
		$this->mission_book($user_id);
	}
	function mission_paper($user_id)
	{
		global $DB_site,$lang,$_POST;
		$m_total=$DB_site->query_first("select count(a.m_id) as m_id from wog_mission_main a ,wog_mission_book b where b.p_id=".$user_id." and m_status=2 and a.m_id=b.m_id ");
		if(empty($_POST["page"]) || !is_numeric($_POST["page"]))
		{
			$_POST["page"]="1";
		}
		$spage=((int)$_POST["page"]*8)-8;
		$sql="select a.m_subject,a.m_name,a.m_id,a.m_lv from wog_mission_main a ,wog_mission_book b where b.p_id=".$user_id." and m_status=2 and a.m_id=b.m_id LIMIT ".$spage.",8";
		$m=$DB_site->query($sql);
		while($ms=$DB_site->fetch_array($m))
		{
			$s.=";".$ms["m_name"].",".$ms["m_subject"].",".$ms["m_id"].",".$ms["m_lv"];
		}
		$DB_site->free_result($ms);
		unset($ms,$p,$m);
		$s=substr($s,1);
		showscript("parent.mission_paper($m_total[0],".$_POST["page"].",'$s')");
	}
	function mission_end($user_id,$m_id)
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_item_tool,$wog_mission_tool;
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$m_book=$wog_mission_tool->mission_check($user_id,$m_id);
		$item_array=array('a_id','d_body_id','d_foot_id','d_hand_id','d_head_id','d_item_id','d_stone_id','d_honor_id','d_plus_id');
		$item=null;
		if(!empty($m_book[n_a_id]) || !empty($m_book[n_d_body_id]) || !empty($m_book[n_d_foot_id]) || !empty($m_book[n_d_hand_id]) || !empty($m_book[n_d_head_id]) || !empty($m_book[n_d_item_id]) || !empty($m_book[n_d_stone_id]) || !empty($m_book[n_d_honor_id]) || !empty($m_book[n_d_plus_id]))
		{
			$item_date=array(
				'a_id'=>explode(",",$m_book[n_a_id]),
				'd_body_id'=>explode(",",$m_book[n_d_body_id]),
				'd_foot_id'=>explode(",",$m_book[n_d_foot_id]),
				'd_hand_id'=>explode(",",$m_book[n_d_hand_id]),
				'd_head_id'=>explode(",",$m_book[n_d_head_id]),
				'd_item_id'=>explode(",",$m_book[n_d_item_id]),
				'd_stone_id'=>explode(",",$m_book[n_d_stone_id]),
				'd_honor_id'=>explode(",",$m_book[n_d_honor_id]),
				'd_plus_id'=>explode(",",$m_book[n_d_plus_id])
			);
			
			$item=$wog_mission_tool->mission_item($user_id,$item_array,$item_date);			
		}
	
		if(!empty($m_book[g_a_id]) || !empty($m_book[g_d_body_id]) || !empty($m_book[g_d_foot_id]) || !empty($m_book[g_d_hand_id]) || !empty($m_book[g_d_head_id]) || !empty($m_book[g_d_item_id]) || !empty($m_book[g_d_stone_id]) || !empty($m_book[g_d_honor_id]) || !empty($m_book[g_d_plus_id]))
		{
			$item_date=array(
				'a_id'=>explode(",",$m_book[g_a_id]),
				'd_body_id'=>explode(",",$m_book[g_d_body_id]),
				'd_foot_id'=>explode(",",$m_book[g_d_foot_id]),
				'd_hand_id'=>explode(",",$m_book[g_d_hand_id]),
				'd_head_id'=>explode(",",$m_book[g_d_head_id]),
				'd_item_id'=>explode(",",$m_book[g_d_item_id]),
				'd_stone_id'=>explode(",",$m_book[g_d_stone_id]),
				'd_honor_id'=>explode(",",$m_book[g_d_honor_id]),
				'd_plus_id'=>explode(",",$m_book[g_d_plus_id])
			);
			$item=$wog_mission_tool->mission_reward($user_id,$item_array,$item_date,$item);
		}
		if(!empty($m_book[g_pet_id]))
		{
			$wog_mission_tool->mission_pet_get($user_id,$m_book[g_pet_id],$m_book[g_pet_lv]);
		}
		if(!empty($m_book[g_skill_id]))
		{
			$wog_mission_tool->mission_skill($user_id,$m_book[g_skill_id]);
		}
		if(!empty($m_book[g_money]))
		{
			$wog_mission_tool->mission_money($user_id,$m_book[g_money]);
		}
		if(!empty($m_book[ex_id]) && !empty($m_book[ex_num]))
		{
			$wog_mission_tool->mission_ex_get($user_id,$m_book['ex_id'],$m_book['ex_num']);
		}
		$m_end_message=$wog_mission_tool->mission_status_update($user_id,$m_id,$m_book["m_end_message"],$m_book["m_lv"],$item,$item_array);
		$DB_site->query_first("COMMIT");
		showscript("parent.job_end(21,'".$m_end_message."')");
	}
	function updateRepeatMission($mission_id,&$repeat_type,&$end_time,&$reward_type)
	{
		global $DB_site,$repeat;
		$repeat_type=rand(0,count($repeat[0])-1);
		$reward_type=rand(0,count($repeat[1])-1);
		$item_array=array('a_id','d_body_id','d_foot_id','d_hand_id','d_head_id','d_item_id','d_stone_id','d_honor_id','d_plus_id');
		$time=getdate();
		$end_time=mktime(9,0,0,$time['mon'],$time['mday']+1,$time['year']);
		switch($repeat['mission_type']){
			case 1:
				for($i=0;$i<count($item_array);$i++)
				{
					$temp=array();
					$a_id=$item_array[$i];
					for($j=0;$j<count($repeat[1][$reward_type][$a_id]);$j++)
					{
						if(empty($repeat[1][$reward_type][$a_id][$j])){continue;}
						$temp[]=$repeat[1][$reward_type][$a_id][$j];
					}
					$item[$a_id]="g_".$a_id."='".implode(',',$temp)."'";
				}
				$DB_site->query('Update wog_mission_main set m_monster_id=\''.$repeat[0][$repeat_type]['monster_id'].'*'.$repeat[0][$repeat_type]['kill_num'].'\','.implode(',',$item).' Where m_id='.$mission_id);
				break;
			case 2:
				for($i=0;$i<count($item_array);$i++)
				{
					$temp=array();
					$a_id=$item_array[$i];
					for($j=0;$j<count($repeat[1][$reward_type][$a_id]);$j++)
					{
						if(empty($repeat[1][$reward_type][$a_id][$j])){continue;}
						$temp[]=$repeat[1][$reward_type][$a_id][$j];
					}
					$item[$a_id]="g_".$a_id."='".implode(',',$temp)."'";
				}
				$DB_site->query('Update wog_mission_main set m_pet_id=\''.$repeat[0][$repeat_type]['m_pet_id'].'\','.implode(',',$item).' Where m_id='.$mission_id);
				break;
			case 3:
				$DB_site->query('Update wog_mission_main set m_monster_id=\''.$repeat[0]['monster_id'].'*'.$repeat[0]['kill_num'].'\',ex_id='.$repeat[1][ex_id].',ex_num='.$repeat[1][ex_num].' Where m_id='.$mission_id);
				break;
		} // switch
		$DB_site->query('Update wog_mission_repeat set update_time='.$end_time.' Where m_id='.$mission_id);
	}
	function mission_item_tool($str,&$temp_item,&$temp_item2)
	{
		if(!empty($str))
		{
			$packs=explode(",",$str);
			$sum=count($packs);
			for($i=0;$i<$sum;$i++)
			{
				$packss=explode("*",$packs[$i]);
				$temp_item[]=$packss[0];
				$temp_item2[$packss[0]]="*".$packss[1];
			}
		}
	}
}