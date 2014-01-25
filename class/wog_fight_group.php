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

class wog_fight_group{
	var $wp_array=array();
//	var $ex_array=array();
	var $weather_array=array(1=>"晴",2=>"雨",3=>"霧",4=>"雷",5=>"雪");
	function group_fight($user_id) //增加出戰job
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		if(empty($_POST["g_id"]))
		{
			alertWindowMsg($lang['wog_act_group_nosel']);
		}
		if(empty($_POST["fight_type"]))
		{
			alertWindowMsg($lang['wog_act_errwork']);
		}
		$fight_type=$_POST["fight_type"];
		$per="";
		switch($fight_type)
		{
			case "1":
				$per="p1";
				break;
			case "2":
				$per="p3";
				break;
			case "4":
				$per="p2";
				break;
		}
		$p=$DB_site->query_first("select b.p_name,b.p_g_id,a.g_id,a.lv,a.".$per." from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and a.id=c.p_permissions");
		if(!$p)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$this->group_permissions_chk($p,$per);
		
		$fight_message=$_POST["fight_message"];
		$sql="select j_id from wog_group_job where p_id=$user_id limit 1";
		$job_count=$DB_site->query_first($sql);
		if($job_count)
		{
			alertWindowMsg($lang['wog_act_group_error7']);
		}
		
		$g_id=$_POST["g_id"];
		if($fight_type==1 || $fight_type==2)
		{
			if(empty($_POST["wp"]))
			{
				alertWindowMsg($lang['wog_act_group_error1']);
			}
			if(count($_POST["wp"])>$wog_arry["g_fight_max"])
			{
				alertWindowMsg(sprintf($lang['wog_act_group_error8'],$wog_arry["g_fight_max"]));
			}
			$wp_num=array();
			$wp_id=array();
			$wp_num_id=array();
			
			$sql_update="";
			$sql_check="";
			$sql_insert="";
			$sql_insert2="";
			$temp_fight_msg="";
			if($p[p_g_id]==$g_id)
			{
				alertWindowMsg($lang['wog_act_group_error9']);
			}
			$wp_main=$this->group_wp_main();
			foreach($_POST["wp"] as $value)
			{
				if(empty($value))
				{
					alertWindowMsg($lang['wog_main_error1']);
				}
				if(empty($_POST["wp_".$value]))
				{
					alertWindowMsg($lang['wog_main_error1']);
				}
				$wp_num[$value]=$_POST["wp_".$value];
				$wp_id[]=$value;
				$wp_num_id[]="b_".($value-1);
				$temp_fight_msg.=" ".$wp_main[$value][wp_name].":".$_POST["wp_".$value];
				if(!is_numeric($wp_num[$value]) || preg_match("/[^0-9]/",$wp_num[$value]))
				{
					alertWindowMsg($lang['wog_act_buy_errornum']);
				}
				$sql_update.=",weapon_".$value."=weapon_".$value."-".$_POST["wp_".$value];
				$sql_check.=",weapon_".$value;
				$sql_insert2.=",".$_POST["wp_".$value];
			}
			unset($wp_main);
			$sql="select ".implode(",",$wp_num_id)." from wog_group_build where g_id=".$p[p_g_id];
			$wp_count=count($wp_num_id);
			$wp_num_id=$DB_site->query_first($sql);
			$wp_temp=array();
			for($i=0;$i<$wp_count;$i++)
			{
				if($wp_num_id[$i]==0){alertWindowMsg($lang['wog_act_group_error18']);}
				$wp_temp[]=$wp_num_id[$i];
			}
			$sql="select type_class,wp_num from wog_build_main where b_id in(".implode(",",$wp_temp).")";
			$wp_max=$DB_site->query($sql);
			while($wp_maxs=$DB_site->fetch_array($wp_max))
			{
				if($_POST["wp_".($wp_maxs["type_class"]+1)] > $wp_maxs["wp_num"]){alertWindowMsg($lang['wog_act_group_error18']);}
			}
			unset($wp_max,$wp_temp,$wp_count,$wp_num_id);
			
			$time=time();
			$sql_update=substr($sql_update,1);
			$sql_check=substr($sql_check,1);
			$sql_insert=$sql_check;
			$sql_insert2=substr($sql_insert2,1);
			$temp_fight_msg=substr($temp_fight_msg,1);
			$DB_site->query_first("set autocommit=0");
			$DB_site->query_first("BEGIN");
			$sql="select $sql_check,g_durable from wog_group_weapon where g_id=$p[p_g_id] for update";
			$group_main=$DB_site->query_first($sql);
			if($group_main[g_durable]<=0){alertWindowMsg($lang['wog_act_group_error22']);}
			foreach($_POST["wp"] as $value)
			{
				if($group_main["weapon_".$value] < $wp_num[$value])
				{
					alertWindowMsg($lang['wog_act_group_error2']);
				}
			}
			$sql="select g_name from wog_group_main where g_id=$g_id";
			$t_g=$DB_site->query_first($sql);
			$sql="select g_name from wog_group_main where g_id=$p[p_g_id]";
			$p_g=$DB_site->query_first($sql);
			switch($fight_type)
			{
				case 1: // 攻打
					$time=$time+$this->group_move_time($wp_id,$g_id,$p[p_g_id]);
					$sql_insert.=",j_formation";
					$sql_insert2.=",".$_POST["formation"];
					$temp=sprintf($lang['wog_act_group_msg8'],$p_g[g_name],$t_g[g_name],set_date($time));
					$temp_fight_msg=sprintf($lang['wog_act_group_msg9'],$p[p_name],$temp_fight_msg,$t_g[g_name]);
				break;
				case 2: // 輸送
					$line=$this->group_xy_line($g_id,$p[p_g_id]);
					$time+=round($wog_arry["g_conveyance_time"]*$line);
					$temp=sprintf($lang['wog_act_group_msg21'],$p_g[g_name],$t_g[g_name],set_date($time));
					$temp_fight_msg=sprintf($lang['wog_act_group_msg22'],$p[p_name],$temp_fight_msg,$t_g[g_name]);
				break;
			}
			$sql="update wog_group_weapon set $sql_update where g_id=$p[p_g_id]";
			$DB_site->query($sql);

/*
			if(!empty($fight_message))
			{
				$fight_message=sprintf($lang['wog_act_group_msg11'],$fight_message);
			}
			else
			{
				$fight_message="";
			}
			$temp_chat=sprintf($lang['wog_act_group_msg7'],$p_g[g_name],$fight_message,$t_g[g_name]);
*/
			$now_time=time();
			$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($g_id,' ".$temp." ',$now_time)";
			$DB_site->query($sql);
	
			$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($p[p_g_id],' ".$temp_fight_msg." ',$now_time)";
			$DB_site->query($sql);
			$sql="insert into wog_group_job(g_id,p_id,j_type,j_time,j_target,$sql_insert)values($p[p_g_id],$user_id,$fight_type,$time,$g_id,$sql_insert2)";
			
		}
		else if($fight_type==4) //偵查
		{
			$time=time();
			$line=$this->group_xy_line($g_id,$p[p_g_id]);
			$time+=round($wog_arry["g_detect_time"]*$line);
			$sql="insert into wog_group_job(g_id,p_id,j_type,j_time,j_target)values($p[p_g_id],$user_id,$fight_type,$time,$g_id)";
		}		
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
/*
		require_once("./class/wog_act_message.php");
		$wog_act_class = new wog_act_message;
		$wog_act_class->system_chat($temp_chat);
		unset($wog_act_class);
*/
		showscript("parent.act_gclick('wp','wp')");
	}
	function group_check_job($user_id)
	{
		global $DB_site,$check_target,$lang;
		$time=time();
		$check_target=array();
		$temp="";
		include_once('./class/wog_group_skill.php');
		include_once("./class/wog_group_mission.php");
		$wog_group_mission = new wog_group_mission;

		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select a.g_id,a.j_target,a.j_type,sum(a.weapon_1) as weapon_1,sum(a.weapon_2) as weapon_2
		,sum(a.weapon_3) as weapon_3,sum(a.weapon_4) as weapon_4,sum(a.weapon_5) as weapon_5,sum(a.weapon_6) as weapon_6
		,sum(a.weapon_7) as weapon_7,sum(a.weapon_8) as weapon_8,sum(a.weapon_9) as weapon_9,sum(a.weapon_10) as weapon_10
		,sum(a.weapon_11) as weapon_11,sum(a.weapon_12) as weapon_12,sum(a.wp_num) as wp_num
		,a.j_formation,a.p_id
		 from wog_group_job a
		 where a.j_time <= $time and j_type in (1,2,3) group by a.g_id,a.j_target,a.j_type order by a.j_time asc for update ";
		$job_main=$DB_site->query($sql);
		
		// 檢查據點是否為自己,false自動回城
		if($job_mains[j_target]<=18 && $job_mains[j_target]>=9 && $job_mains[j_type]==2)
		{
			$sql="select set_gid from wog_group_point_temp where g_id=".$job_mains[j_target];
			$chk_point_area=$DB_site->query_first($sql);
			if($job_mains[g_id]!=$chk_point_area[set_gid])
			{
				for($i=1;$i<13;$i++)
				{
					if($job_mains["weapon_".$i]>0)
					{
						$item_message.=" ".$wp[$i]["wp_name"].":".$job_mains["weapon_".$i];
					}
				}
				$item_message=$lang['wog_act_group_msg34'].$item_message;
				$job_mains[j_target]=$job_mains[g_id];
				$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($job_mains[g_id],' ".$item_message." ',$time)";
				$DB_site->query($sql);
			}
		}
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			switch($job_mains[j_type]){
				case 3: //修護城防禦
					$sql="select g_durable,g_durable_max from wog_group_weapon where g_id=".$job_mains[g_id];
					$g_durable=$DB_site->query_first($sql);
					$g_durable[g_durable]+=$job_mains[wp_num];
					if($g_durable[g_durable]>$g_durable[g_durable_max]){$g_durable[g_durable]=$g_durable[g_durable_max];}
					$sql="update wog_group_weapon set g_durable=".$g_durable[g_durable]." where g_id=".$job_mains[g_id];
					$DB_site->query($sql);
					unset($g_durable);
					break;
				case 2: //運輸兵
					$temp=",weapon_1=weapon_1+".$job_mains[weapon_1].",weapon_2=weapon_2+".$job_mains[weapon_2]
					.",weapon_3=weapon_3+".$job_mains[weapon_3].",weapon_4=weapon_4+".$job_mains[weapon_4].",weapon_5=weapon_5+".$job_mains[weapon_5]
					.",weapon_6=weapon_6+".$job_mains[weapon_6].",weapon_7=weapon_7+".$job_mains[weapon_7].",weapon_8=weapon_8+".$job_mains[weapon_8]
					.",weapon_9=weapon_9+".$job_mains[weapon_9].",weapon_10=weapon_10+".$job_mains[weapon_10].",weapon_11=weapon_11+".$job_mains[weapon_11]
					.",weapon_12=weapon_12+".$job_mains[weapon_12];
					$temp=substr($temp,1);
					$sql="update wog_group_weapon set ".$temp." where g_id=".$job_mains[j_target];
					$DB_site->query($sql);
					// 處理運輸任務 begin
					$wog_group_mission->need_date=$job_mains;
					$wog_group_mission->mission_check($job_mains[g_id],4,$job_mains[p_id],$job_mains[j_target]);
					// 處理運輸任務 end
					break;
/*
				case 4: //偵查
					$this->group_detect($job_mains[g_id],$job_mains[j_target],$job_mains[p_id]);
					// 處理偵查任務 begin
					$wog_group_mission->need_date=$job_mains;
					$wog_group_mission->mission_check($job_mains[g_id],2,$job_mains[p_id],$job_mains[j_target]);
					// 處理偵查任務 end
					break;
*/
				case 1: //戰鬥
					if(!$check_target[$job_mains[j_target]])
					{
						$wp=$this->group_wp_main();
						if($this->group_fight_job($job_mains,$wp))
						{
							// 處理戰鬥任務 begin
							$wog_group_mission->need_date=$job_mains;
							$wog_group_mission->mission_check($job_mains[g_id],3,$job_mains[p_id],$job_mains[j_target]);
							// 處理戰鬥任務 end
						}						
					}else
					{
						$sql="select g_name from wog_group_main  where g_id=".$job_mains[j_target];
						$tempg=$DB_site->query_first($sql);
						$item_message=sprintf($lang['wog_act_group_msg14'],$tempg["g_name"]);
						unset($tempg);
						for($i=1;$i<13;$i++)
						{
							if($job_mains["weapon_".$i]>0)
							{
								$item_message.=" ".$wp[$i]["wp_name"].":".$job_mains["weapon_".$i];
							}
						}
						
						$temp=",weapon_1=weapon_1+".$job_mains[weapon_1].",weapon_2=weapon_2+".$job_mains[weapon_2]
						.",weapon_3=weapon_3+".$job_mains[weapon_3].",weapon_4=weapon_4+".$job_mains[weapon_4].",weapon_5=weapon_5+".$job_mains[weapon_5]
						.",weapon_6=weapon_6+".$job_mains[weapon_6].",weapon_7=weapon_7+".$job_mains[weapon_7].",weapon_8=weapon_8+".$job_mains[weapon_8]
						.",weapon_9=weapon_9+".$job_mains[weapon_9].",weapon_10=weapon_10+".$job_mains[weapon_10].",weapon_11=weapon_11+".$job_mains[weapon_11]
						.",weapon_12=weapon_12+".$job_mains[weapon_12];
						$temp=substr($temp,1);
						$sql="update wog_group_weapon set ".$temp." where g_id=".$job_mains[g_id];
						$DB_site->query($sql);
						$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($job_mains[g_id],' ".$item_message." ',$time)";
						$DB_site->query($sql);						
					}
					break;
			} // switch
			//$DB_site->query_first("COMMIT");
		}
		$sql="delete from wog_group_job where j_time <= $time and j_type in (1,2,3)";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		//$DB_site->query_first("set autocommit=1");
		
		//$DB_site->query_first("set autocommit=0");
		//$DB_site->query_first("BEGIN");
		$sql="select a.g_id,a.j_target,a.p_id,a.j_type,sum(a.weapon_1) as weapon_1,sum(a.weapon_2) as weapon_2
		,sum(a.weapon_3) as weapon_3,sum(a.weapon_4) as weapon_4,sum(a.weapon_5) as weapon_5,sum(a.weapon_6) as weapon_6
		,sum(a.weapon_7) as weapon_7,sum(a.weapon_8) as weapon_8,sum(a.weapon_9) as weapon_9,b.wp_1,b.wp_2,b.wp_3,b.wp_4,b.wp_5,b.wp_6,b.wp_7,b.wp_8,b.wp_9 
		from wog_group_job a,wog_group_point b
		 where a.j_time <= $time and j_type in (0,4) and b.g_id=a.g_id group by a.g_id,a.p_id order by a.j_time asc for update";
		$job_main=$DB_site->query($sql);
//		echo $sql;
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			$point=0;
			switch($job_mains[j_type]){
				case 0: //生兵
					$temp=",weapon_1=weapon_1+".$job_mains[weapon_1].",weapon_2=weapon_2+".$job_mains[weapon_2]
					.",weapon_3=weapon_3+".$job_mains[weapon_3].",weapon_4=weapon_4+".$job_mains[weapon_4].",weapon_5=weapon_5+".$job_mains[weapon_5]
					.",weapon_6=weapon_6+".$job_mains[weapon_6].",weapon_7=weapon_7+".$job_mains[weapon_7].",weapon_8=weapon_8+".$job_mains[weapon_8]
					.",weapon_9=weapon_9+".$job_mains[weapon_9];
					$temp=substr($temp,1);
					$sql="update wog_group_weapon set ".$temp." where g_id=".$job_mains[g_id];
					$DB_site->query($sql);
					for($i=1;$i<10;$i++)
					{
						if($job_mains["weapon_".$i] >0)
						{
							$point += $job_mains["wp_".$i]*$job_mains["weapon_".$i];
							//break;
						}
					}
					if($point > 0)
					{
						$sql="update wog_group_member_point set p_point=p_point+".$point." where p_id=$job_mains[p_id] and g_id=$job_mains[g_id]";
						$DB_site->query($sql);
					}
					// 處理生兵任務 begin
					$wog_group_mission->need_date=$job_mains;
					$wog_group_mission->mission_check($job_mains[g_id],1,$job_mains[p_id]);
					// 處理生兵任務 end
					break;
				case 4: //偵查
					$this->group_detect($job_mains[g_id],$job_mains[j_target],$job_mains[p_id]);
					// 處理偵查任務 begin
					$wog_group_mission->need_date=$job_mains;
					$wog_group_mission->mission_check($job_mains[g_id],2,$job_mains[p_id],$job_mains[j_target]);
					// 處理偵查任務 end
					break;
			} // switch
		}
		$DB_site->free_result($job_main);
		unset($job_mains,$wp);
		$sql="delete from wog_group_job where j_time <= $time and j_type in (0,4)";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		$DB_site->query_first("set autocommit=1");
	}
	function group_detect($g_id,$j_target,$user_id) //偵查
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		$sql="select b.lv from wog_group_build a,wog_build_main b where a.g_id=".$g_id." and b.b_id=a.b_14";
		$build_main=$DB_site->query_first($sql);
		if(!$build_main){$build_main[lv]=1;}
		$num=$build_main[lv];
		$ex_array=array();
		$wp_array=array();
		for($i=0;$i<$num;$i++)
		{
			$temp="ex_".rand(1,12);
			while(in_array($temp,$ex_array))
			{
				$temp="ex_".rand(1,12);
			}
			$ex_array[$i]=$temp;
		}

		$sql_ex1=implode(",",$ex_array);
		$sql_ex2="";
		$ex=$DB_site->query_first("select ".$sql_ex1." from wog_group_exchange where g_id=".$j_target);
		if($ex)
		{
			for($i=0;$i<$num;$i++)
			{
				$sql_ex2.=",'".$ex[$i]."'";
			}
		}
		unset($ex);

		for($i=0;$i<$num;$i++)
		{
			$temp="weapon_".rand(1,12);
			while(in_array($temp,$wp_array))
			{
				$temp="weapon_".rand(1,12);
			}
			$wp_array[$i]=$temp;
		}
		$sql_wp1=implode(",",$wp_array);
		$sql_wp2="";		
		$sql="select ".$sql_wp1.",g_durable,g_durable_max
		 from wog_group_weapon where g_id=$j_target";
		$wp=$DB_site->query_first($sql);
		if($wp)
		{
			for($i=0;$i<$num;$i++)
			{
				$sql_wp2.=",'".$wp[$i]."'";
			}
		}
		$sql_wp2.=",'".$wp[g_durable]."','".$wp[g_durable_max]."'";
		unset($wp);
		$time=time();
		$sql="insert wog_group_detect(g_id,t_id,detect_time,$sql_ex1,$sql_wp1,g_durable,g_durable_max)values($g_id,$j_target,$time".$sql_ex2.$sql_wp2.")";
		$DB_site->query($sql);
		$detect_id=$DB_site->insert_id();
		
		$p_name=$DB_site->query_first("select p_name from wog_player where p_id=$user_id");
		$t_name=$DB_site->query_first("select g_name from wog_group_main where g_id=$j_target");
		$temp=sprintf($lang['wog_act_group_msg15'],$p_name[p_name],$t_name[g_name],$detect_id);
		$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($g_id,' ".$temp." ',$time)";
		$DB_site->query($sql);

	}
	function group_fight_job(&$job_mains,$wp)
	{
		global $DB_site,$lang,$wog_arry,$trap_value1,$trap_value2,$wog_act_class,$check_target,$formation_value;
		//$target[g_boss]==9 為據點爭奪戰
		$formation_value=array();
		$time=time();
		$trap_value1=0;
		$trap_value2=0;
		$fight_end=0;
		$sql="select b.g_id,b.g_name,b.g_npc,b.g_boss,b.g_area_type,b.g_weather,b.g_formation,b.g_trap,b.g_item,a.g_durable_max,a.g_durable,a.weapon_1,a.weapon_2,a.weapon_3,a.weapon_4,a.weapon_5,a.weapon_6,a.weapon_7,a.weapon_8,a.weapon_9,a.weapon_10,a.weapon_11,a.weapon_12,b.g_lost_time,b.g_restart_time from wog_group_weapon a,wog_group_main b where a.g_id=".$job_mains[j_target]." and a.g_id=b.g_id and g_break=0";
		$target=$DB_site->query_first($sql);
		$check_fight=false;
		$item_status=0;
		$item_message='';
		$fight_book='';
		if(!$target)
		{
			$check_fight=true;
			$sql="select g_name from wog_group_main  where g_id=".$job_mains[j_target];
			$tempg=$DB_site->query_first($sql);
			$item_message=sprintf($lang['wog_act_group_msg14'],$tempg["g_name"]);
			unset($tempg);
		}
		if($time < $target[g_lost_time])
		{
			$check_fight=true;
			$sql="select g_name from wog_group_main  where g_id=".$job_mains[j_target];
			$tempg=$DB_site->query_first($sql);
			$item_message=sprintf($lang['wog_act_group_msg14'],$tempg["g_name"]);
			unset($tempg);
		}
		if($target[g_boss]==9)
		{
			// 檢查據點是否屬於攻方,true自動轉成輸送
			$sql="select set_gid from wog_group_point_temp where g_id=".$job_mains[j_target];
			$chk_point_area=$DB_site->query_first($sql);
			if($job_mains[g_id]==$chk_point_area[set_gid])
			{
				for($i=1;$i<13;$i++)
				{
					if($job_mains["weapon_".$i]>0)
					{
						$item_message.=" ".$wp[$i]["wp_name"].":".$job_mains["weapon_".$i];
					}
				}
				$item_message=sprintf($lang['wog_act_group_msg33'],$item_message,$target[g_name]);
				$temp=",weapon_1=weapon_1+".$job_mains[weapon_1].",weapon_2=weapon_2+".$job_mains[weapon_2]
				.",weapon_3=weapon_3+".$job_mains[weapon_3].",weapon_4=weapon_4+".$job_mains[weapon_4].",weapon_5=weapon_5+".$job_mains[weapon_5]
				.",weapon_6=weapon_6+".$job_mains[weapon_6].",weapon_7=weapon_7+".$job_mains[weapon_7].",weapon_8=weapon_8+".$job_mains[weapon_8]
				.",weapon_9=weapon_9+".$job_mains[weapon_9].",weapon_10=weapon_10+".$job_mains[weapon_10].",weapon_11=weapon_11+".$job_mains[weapon_11]
				.",weapon_12=weapon_12+".$job_mains[weapon_12];
				$temp=substr($temp,1);
				$sql="update wog_group_weapon set ".$temp." where g_id=".$job_mains[j_target];
				$DB_site->query($sql);
				unset($target);
				$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($job_mains[g_id],' ".$item_message." ',$time)";
				$DB_site->query($sql);
				return;
			}
		}
		if(($target[g_item]==3 || $target[g_item]==7 || $target[g_item]==8) && $check_fight==false)
		{
			switch($target[g_item])
			{
				case 3://發動停戰協議A
					$item_value=50;
				break;		
				case 7://發動停戰協議B
					$item_value=60;
				break;	
				case 8://發動停戰協議C
					$item_value=70;
				break;	
			}
			if(rand(1,100)<=$item_value)
			{
				$check_fight=true;
				$item_message=sprintf($lang['wog_act_group_msg19'],$target[g_name]);
			}
			else
			{
				$item_message=sprintf($lang['wog_act_group_msg20'],$target[g_name]);
			}
			$sql="update wog_group_main set g_item=0 where g_id=$job_mains[j_target]";
			$DB_site->query($sql);
			$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($job_mains[j_target],' ".$item_message." ',$time)";
			$DB_site->query($sql);
		}
		
		if($check_fight)
		{
			for($i=1;$i<13;$i++)
			{
				if($job_mains["weapon_".$i]>0)
				{
					$item_message.=" ".$wp[$i]["wp_name"].":".$job_mains["weapon_".$i];
				}
			}
			
			$temp=",weapon_1=weapon_1+".$job_mains[weapon_1].",weapon_2=weapon_2+".$job_mains[weapon_2]
			.",weapon_3=weapon_3+".$job_mains[weapon_3].",weapon_4=weapon_4+".$job_mains[weapon_4].",weapon_5=weapon_5+".$job_mains[weapon_5]
			.",weapon_6=weapon_6+".$job_mains[weapon_6].",weapon_7=weapon_7+".$job_mains[weapon_7].",weapon_8=weapon_8+".$job_mains[weapon_8]
			.",weapon_9=weapon_9+".$job_mains[weapon_9].",weapon_10=weapon_10+".$job_mains[weapon_10].",weapon_11=weapon_11+".$job_mains[weapon_11]
			.",weapon_12=weapon_12+".$job_mains[weapon_12];
			$temp=substr($temp,1);
			$sql="update wog_group_weapon set ".$temp." where g_id=".$job_mains[g_id];
			$DB_site->query($sql);
			unset($target);
			$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($job_mains[g_id],' ".$item_message." ',$time)";
			$DB_site->query($sql);
			return;
		}
		$tar_main_key=array();
		for($i=1;$i<13;$i++)
		{
			if($target["weapon_".$i]>0){$tar_main_key["weapon_".$i]=$i;}
		}
		$act_main=array();
		$act_main_key=array();
		for($i=1;$i<13;$i++)
		{
			if($job_mains["weapon_".$i]>0)
			{
				$act_main["weapon_".$i]=$job_mains["weapon_".$i];
				$act_main_key["weapon_".$i]=$i;
			}
		}
		switch($target[g_item])
		{
			case 2://發動偽報
				$tar_key=array_rand($act_main_key);
				$fight_book.="攻擊方[".$wp[$act_main_key[$tar_key]][wp_name]."]中了偽報<br>";
				unset($act_main_key[$tar_key]);
				$target[g_item]=0;
				$item_status=2;
				$wp_key=$tar_key;
				$wp_num=$act_main[$tar_key];
			break;
			case 4://發動緊急徵召A
				$tar_key=array_rand($tar_main_key);
				$item_status=4;
				$wp_num=rand(3000,5000);
				$wp_key=$tar_key;
				$target[$tar_key]+=$wp_num;
				$fight_book.="防守方使用緊急徵召A [".$wp[$tar_main_key[$tar_key]][wp_name]."]增加".$wp_num."兵力<br>";
				$target[g_item]=0;
			break;
			case 5://發動緊急徵召B
				$tar_key=array_rand($tar_main_key);
				$item_status=5;
				$wp_num=rand(6000,9000);
				$wp_key=$tar_key;
				$target[$tar_key]+=$wp_num;
				$fight_book.="防守方使用緊急徵召B [".$wp[$tar_main_key[$tar_key]][wp_name]."]增加".$wp_num."兵力<br>";
				$target[g_item]=0;
			break;
			case 6://發動緊急徵召C
				$tar_key=array_rand($tar_main_key);
				echo $tar_key;
				$item_status=6;
				$wp_num=rand(10000,13000);
				$wp_key=$tar_key;
				$target[$tar_key]+=$wp_num;
				$fight_book.="防守方使用緊急徵召C [".$wp[$tar_main_key[$tar_key]][wp_name]."]增加".$wp_num."兵力<br>";
				$target[g_item]=0;
			break;
		}

		//載入地形效果 begin3
		switch($target[g_area_type])
		{
			case 1:
				for($i=1;$i<13;$i++)
				{
					$wp[3]["weapon_".$i]*=1.1;
				}
			break;
			case 2:
				for($i=1;$i<13;$i++)
				{
					$wp[4]["weapon_".$i]*=1.1;
					$wp[7]["weapon_".$i]*=1.1;
					$wp[3]["weapon_".$i]*=0.9;
				}
			break;
			case 3:
				for($i=1;$i<13;$i++)
				{
					$wp[2]["weapon_".$i]*=1.1;
					$wp[4]["weapon_".$i]*=0.9;
				}
			break;
			case 4:
				for($i=1;$i<13;$i++)
				{
					$wp[6]["weapon_".$i]*=1.1;
					$wp[9]["weapon_".$i]*=1.1;
				}
			break;
			case 5:
				for($i=1;$i<13;$i++)
				{
					$wp[3]["weapon_".$i]*=0.9;
					$wp[6]["weapon_".$i]*=0.9;
					$wp[9]["weapon_".$i]*=0.9;
				}
			break;
			case 6:
				for($i=1;$i<13;$i++)
				{
					$wp[1]["weapon_".$i]*=1.1;
					$wp[8]["weapon_".$i]*=0.9;
				}
			break;
		}
		//載入地形效果 end
		//載入陷阱效果 begin
		if(!empty($target[g_trap]))
		{
			$sql="select main_id,lv from wog_build_main where b_id=".$target[g_trap];
			$trap=$DB_site->query_first($sql);
			eval("\$wp_trap=trap_".$trap[main_id]."(\$trap[lv]);");
		}
		//載入陷阱效果 end
		//讀取雙方陣形效果 begin
		$sql="select main_id,lv from wog_build_main where b_id=".$target[g_formation];
		$temp=$DB_site->query_first($sql);
		$target[g_formation_lv]=$temp[lv];
		$target[g_formation_main_id]=$temp[main_id];
		$sql="select main_id,lv from wog_build_main where b_id=".$job_mains[j_formation];
		$temp=$DB_site->query_first($sql);
		$job_mains[g_formation_lv]=$temp[lv];
		$job_mains[g_formation_main_id]=$temp[main_id];
		//讀取雙方陣形效果 end
		//讀取資源保護效果 begin
		$sql="select a.lv from wog_build_main a,wog_group_build b where b.g_id=$job_mains[j_target] and b.b_13=a.b_id";
		$temp=$DB_site->query_first($sql);
		$target[g_def_lv]=$temp[lv];
		//讀取資源保護效果 end
		unset($temp);
		$sum=0;
		$act_report=array();
		$tag_report=array();
		$dmg=0;
		
		$temp_weathe=$target[g_weather];
		$target[g_area_type]=1;
		$target[g_formation_lv]=10;
		//戰鬥 begin
		while($target[g_durable] > 0 && isset($act_main_key) && $sum<$wog_arry["g_count"])
		{
			if($target[g_boss]==9 && !isset($tar_main_key))
			{
				$target[g_durable]=0;
				break;
			}
			$sum++;
			$sum2=0;
			$fight_status=false;
			//氣候影響載入 begin
			if(rand(1,5)==1){$temp_weathe=rand(1,5);}
			if($sum==1 || $temp_weathe!=$target[g_weather])
			{
				if($sum >1){$target[g_weather]=$temp_weathe;}
				switch($target[g_weather])
				{
					case 1:
						for($i=1;$i<13;$i++)
						{
							$wp[7]["weapon_".$i]*=1.1;
						}
						if($trap[main_id]==15){$wp_trap=trap_15($trap[lv],1.1);}
					break;				
					case 2:
						for($i=1;$i<13;$i++)
						{
							$wp[7]["weapon_".$i]*=0.9;
							$wp[6]["weapon_".$i]*=0.9;
						}
						if($trap[main_id]==16){$wp_trap=trap_16($trap[lv],1.1);}
					break;				
					case 3:
						for($i=1;$i<13;$i++)
						{
							$wp[4]["weapon_".$i]*=0.9;
						}
						if($trap[main_id]==18){$wp_trap=trap_18($trap[lv],1.1);}
					break;	
					case 4:
						for($i=1;$i<13;$i++)
						{
							$wp[2]["weapon_".$i]*=0.9;
						}
						if($trap[main_id]==17){$wp_trap=trap_17($trap[lv],1.1);}
					break;
					case 5:
						for($i=1;$i<13;$i++)
						{
							$wp[3]["weapon_".$i]*=0.9;
							$wp[9]["weapon_".$i]*=0.9;
						}
					break;
				}
			}
			//氣候影響載入 end
			$fight_book.="氣候[".$this->weather_array[$target[g_weather]]."]<br>";
			foreach($wp as $key=>$value)
			{
				if($sum % $value["wp_fs"]==0)
				{
					$fight_status=true;
					if(isset($act_main["weapon_".$key]) && isset($act_main_key["weapon_".$key]))
					{
						if(isset($tar_main_key))
						{
							$tar_key=array_rand($tar_main_key);
							eval("\$act_value=formation_".$job_mains[g_formation_main_id]."(\$job_mains,\$key,\$value[\$tar_key],\$job_mains[g_formation_lv],\$target[g_formation_main_id],\$target[g_area_type]);");
							$dmg=floor($act_main["weapon_".$key]*$act_value);
							if($target[$tar_key] < $dmg)
							{
								$tag_report[$tar_main_key[$tar_key]]+=$target[$tar_key];
								$dmg=$target[$tar_key];
							}
							else
							{
								$tag_report[$tar_main_key[$tar_key]]+=$dmg;
							}
							$target[$tar_key]-=$dmg;
							//echo "攻擊方".$key."給予".$tar_key." - ".$dmg."(".$target[$tar_key].")<br>";
							$fight_book.="攻擊方[".$wp[$key][wp_name]."] 給予 防守方[".$wp[$tar_main_key[$tar_key]][wp_name]."] <font color=red>".$dmg."</font>傷害(剩餘兵力".$target[$tar_key].")<br>";
							if($target[$tar_key] <= 0)
							{
								$target[$tar_key]=0;
								unset($tar_main_key[$tar_key]);
								if(count($tar_main_key) <= 0)
								{
									unset($tar_main_key);
									break;
								}
							}
						}
					}
					if(isset($tar_main_key["weapon_".$key]))
					{
						$tar_key=array_rand($act_main_key);
						$chk_formation="formation_".$target[g_formation_main_id]."(".$target.",".$key.",".$value[$tar_key].",".$target[g_formation_lv].",".$job_mains[g_formation_main_id].",".$target[g_area_type].");";
						eval("\$act_value=formation_".$target[g_formation_main_id]."(\$target,\$key,\$value[\$tar_key],\$target[g_formation_lv],\$job_mains[g_formation_main_id],\$target[g_area_type]);");
						$debug_str="[攻擊力檢查 :".$target["weapon_".$key]." - ".$act_value."]"."[陣型檢查:".$chk_formation." ## ".$target[g_formation_main_id]." - ".$target[g_id]."]";
						$dmg=floor($target["weapon_".$key]*$act_value);
						if( $act_main[$tar_key] < $dmg)
						{
							$act_report[$act_main_key[$tar_key]]+=$act_main[$tar_key];
							$dmg=$act_main[$tar_key];
						}
						else
						{
							$act_report[$act_main_key[$tar_key]]+=$dmg;
						}
						$act_main[$tar_key]-=$dmg;
						//echo "防守方".$key."給予".$tar_key." - ".$dmg."(".$act_main[$tar_key].")<br>";
						$fight_book.="防守方[".$wp[$key][wp_name]."] 給予  攻擊方[".$wp[$act_main_key[$tar_key]][wp_name]."] <font color=red>".$dmg."</font>傷害(剩餘兵力".$act_main[$tar_key].") ## ".$debug_str."<br>";
						if($act_main[$tar_key] <= 0)
						{
							$act_main[$tar_key]=0;
							unset($act_main[$tar_key],$act_main_key[$tar_key]);
						}
						if(count($act_main_key) <= 0)
						{
							unset($act_main_key,$act_main);
							break;
						}
					}
					if(!empty($act_main["weapon_".$key]) && $target[g_boss]!=9)
					{
						$dmg=floor($act_main["weapon_".$key]*$value["wp_durable"]);
						$target[g_durable]-=$dmg;
						//echo "攻擊方給予城 - ".$dmg."(".$target[g_durable].")"."<br>";
						$fight_book.="攻擊方[".$wp[$key][wp_name]."] 給予 據點 <font color=red>".$dmg."</font>傷害(據點剩餘".$target[g_durable]."防禦值)<br>";
						if($target[g_durable]<=0)
						{
							if($target[g_item]==1) // 發動野戰修復
							{
								$target[g_durable]=$target[g_durable_max]*0.3;
								$target[g_item]=0;
								$fight_book.="據點使用野戰修復，恢復防禦力(".$target[g_durable].")<br>";
							}else
							{
								$target[g_durable]=0;
								break;								
							}
						}
						//陷阱攻擊 begin
						if(isset($wp_trap[$key]) && rand(1,3)==1)
						{
							$tar_key="weapon_".$key;
							$dmg=rand($wp_trap[$key][min],$wp_trap[$key][max]);
							if( $act_main[$tar_key] < $dmg)
							{
								$act_report[$act_main_key[$tar_key]]+=$act_main[$tar_key];
								$dmg=$act_main[$tar_key];
							}
							else
							{
								$act_report[$act_main_key[$tar_key]]+=$dmg;
							}
							$act_main[$tar_key]-=$dmg;
							//echo "防守方的陷阱".$key."給予".$tar_key." - ".$dmg."(".$act_main[$key].")<br>";
							$fight_book.="防守方[陷阱] 給予 攻擊方[".$wp[$key][wp_name]."] <font color=red>".$dmg."</font>傷害(剩餘兵力".$act_main[$tar_key].")<br>";
							if($act_main[$tar_key] <= 0)
							{
								$act_main[$tar_key]=0;
								unset($act_main[$tar_key],$act_main_key[$tar_key]);
							}
							if(count($act_main_key) <= 0)
							{
								unset($act_main_key,$act_main);
								break;
							}
						}
						//陷阱攻擊 end
					}
				}
			}
			if($fight_status){$fight_book.="<br>";}
		}
		//戰鬥 end
		if($item_status==2){$act_main[$wp_key]=$wp_num;}
		$g_main=$DB_site->query_first("select g_name,g_npc from wog_group_main where g_id=".$job_mains[g_id]);
		if(isset($act_main) && $g_main[g_npc]==0 && $target[g_boss]!=9)
		{
			//剩餘兵力補回
			$temp="";
			foreach($act_main as $key=>$value)
			{
				$temp.=",".$key."=".$key."+".$value;
			}
			if(!empty($temp))
			{
				$temp=substr($temp,1);
				$sql="update wog_group_weapon set ".$temp." where g_id=".$job_mains[g_id];
				$DB_site->query($sql);
			}
		}
		$temp="";
		if(count($act_report) > 0 )
		{
			foreach($act_report as $key=>$value)
			{
				$temp.=" ".$wp[$key]["wp_name"].":".$value;
			}
		}
		if(empty($temp)){$temp="0";}
		$temp_tag="";
		if(count($tag_report) > 0)
		{
			foreach($tag_report as $key=>$value)
			{
				$temp_tag.=" ".$wp[$key]["wp_name"].":".$value;
			}
		}
		if(empty($temp_tag)){$temp_tag="0";}
		$j_g_array=array();
		$j_g_array[g_name]=$g_main[g_name];
		$j_g_array[g_id]=$job_mains[g_id];
		$j_g_array[g_npc]=$g_main[g_npc];

		$sql="insert into wog_group_fight_book (g_id,g_fight_book,datetime)values($job_mains[j_target],'".$fight_book."',$time)";
		$DB_site->query($sql);
		$fbook_id=$DB_site->insert_id();
		if($target[g_durable] <= 0)
		{
			$fight_end=1; //返回勝負
			$check_target[$job_mains[j_target]]=1; //記錄勝負
			//獲得資源
			if($target[g_boss]!=9)
			{
				$ex_str=$this->group_mod_ex($j_g_array,$job_mains[j_target],$target[g_def_lv],$fbook_id);
			}else
			{
				$ex_str="0";
			}
			$temp=sprintf($lang['wog_act_group_msg3'],$j_g_array[g_name],$target[g_name],$ex_str,$temp);
			//$temp_chat=sprintf($lang['wog_act_group_msg5'],$j_g_array[g_name],$target[g_name]);
			if($target[g_npc]==1)
			{
				if($target[g_boss]!=9)
				{
					//紀錄被攻破時間
					$g_lost_time=$time+$target["g_restart_time"];
					$sql="update wog_group_main set g_lost_time=$g_lost_time,g_break=1 where g_id=".$job_mains[j_target];
					$this->group_get_item($job_mains[g_id],$job_mains[j_target],$target[g_boss]);					
				}
			}
			else
			{
				//$this->group_down_build($job_mains[j_target]);
				$g_lost_time=$time+$wog_arry["g_lost_time"];
				$target[g_durable]=$target[g_durable_max]*0.2;
				$sql="update wog_group_main set g_lost_time=$g_lost_time where g_id=".$job_mains[j_target];
			}
			$DB_site->query($sql);
			$sql="delete from wog_group_job where g_id=".$job_mains[j_target]." and j_type in (0,3)";
			$DB_site->query($sql);
		}
		else
		{
			$temp2=sprintf($lang['wog_act_group_msg4'],$j_g_array[g_name],$target[g_durable],$temp_tag).sprintf($lang['wog_act_group_msg16'],$fbook_id);
			$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($job_mains[j_target],' ".$temp2." ',$time)";
			$DB_site->query($sql);
			$temp=sprintf($lang['wog_act_group_msg1'],$j_g_array[g_name],$target[g_name],$temp,$temp_tag);
			//$temp_chat=sprintf($lang['wog_act_group_msg6'],$j_g_array[g_name],$target[g_name]);
		}

		$sql="insert into wog_group_fight_book (g_id,g_fight_book,datetime)values($job_mains[g_id],'".$fight_book."',$time)";
		$DB_site->query($sql);
		$fbook_id=$DB_site->insert_id();

		$temp.=sprintf($lang['wog_act_group_msg16'],$fbook_id);
		if($j_g_array[g_npc]==0)
		{
			$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($job_mains[g_id],' ".$temp." ',$time)";
			$DB_site->query($sql);			
		}

		//require_once("./class/wog_act_message.php");
		//$wog_act_class = new wog_act_message;
		//$wog_act_class->system_chat($temp_chat);
		//unset($wog_act_class);
		switch($item_status)
		{
			case 4:
			case 5:
			case 6:
				$target[$wp_key]-=$wp_num;
				if($target[$wp_key]<0){$target[$wp_key]=0;}
			break;
		}
		if($target[g_boss]==9 && $fight_end)
		{
			//剩餘兵力戰領據點
			$target[g_durable]=$target[g_durable_max];
			$temp_gboss9="";
			foreach($act_main as $key=>$value)
			{
				$temp_gboss9.=",".$key."=".$value;
			}
			if(!empty($temp_gboss9))
			{
				$temp_gboss9=substr($temp_gboss9,1);
				$sql="update wog_group_weapon set ".$temp_gboss9." where g_id=".$job_mains[j_target];
				$DB_site->query($sql);
			}
			$sql="update wog_group_main set g_weather=$target[g_weather],g_formation=$job_mains[j_formation],p_name='[".$j_g_array[g_name]."]' where g_id=".$job_mains[j_target];
			$DB_site->query($sql);
			$sql="update wog_group_point_temp set set_gid=".$j_g_array[g_id].",set_datetime=".$time." where g_id=".$job_mains[j_target];
			$DB_site->query($sql);
		}else
		{
			$temp=",weapon_1=".$target[weapon_1].",weapon_2=".$target[weapon_2]
			.",weapon_3=".$target[weapon_3].",weapon_4=".$target[weapon_4].",weapon_5=".$target[weapon_5]
			.",weapon_6=".$target[weapon_6].",weapon_7=".$target[weapon_7].",weapon_8=".$target[weapon_8]
			.",weapon_9=".$target[weapon_9].",weapon_10=".$target[weapon_10].",weapon_11=".$target[weapon_11]
			.",weapon_12=".$target[weapon_12].",g_durable=".$target[g_durable];
			$temp=substr($temp,1);
			$sql="update wog_group_weapon set ".$temp." where g_id=".$job_mains[j_target];
			$DB_site->query($sql);
			$sql="update wog_group_main set g_weather=$target[g_weather],g_item=$target[g_item] where g_id=".$job_mains[j_target];
			$DB_site->query($sql);		
		}
		unset($g_main,$act_main_key,$act_main,$tar_main_key);
		if($target[g_npc]==1 && $target[g_durable]<($target[g_durable_max]*0.3) && rand(1,100)<30)
		{
			$wog_act_class->group_durable(0,$job_mains[j_target]);
		}
		unset($target,$j_g_array);
		return $fight_end;
	}
	//獲得獎勵
	function group_get_item($g_id,$t_id,$g_boss)
	{
		global $DB_site,$wog_arry;
		$sql="select d_id,d_num,d_topr from wog_group_npc_item where g_id=$t_id";
		$d_main=$DB_site->query_first($sql);
		if(!$d_main){return;}
		$d_id_array=explode(",",$d_main[d_id]);
		$d_num_array=explode(",",$d_main[d_num]);
		$d_topr_array=explode(",",$d_main[d_topr]);
		$temp_get_item="";
		$top_get=0;
		$topr_vip = 1;//掉寶率參數
		for($i=0;$i<count($d_id_array);$i++)
		{
			$top_get=$d_topr_array[$i]*$topr_vip;
			if(rand(1,$top_get) <=1)
			{
				$item_num=rand(1,$d_num_array[$i]);
				$sql="select d_id from wog_group_depot where g_id=$g_id and d_id=".$d_id_array[$i];
				$d_check=$DB_site->query_first($sql);
				if($d_check)
				{
					$sql="update wog_group_depot set d_num=d_num+".$item_num." where g_id=$g_id and d_id=".$d_id_array[$i];
					$DB_site->query($sql);
				}
				else
				{
					$sql="select count(d_id) as num  from wog_group_depot where g_id=$g_id";
					$d_check=$DB_site->query_first($sql);
					if($d_check[num] < $wog_arry["g_depot_num"])
					{
						$sql="select d_fie from wog_df where d_id=".$d_id_array[$i];
						$d_type=$DB_site->query_first($sql);
						$sql="insert into wog_group_depot(g_id,d_id,d_type,d_num)values($g_id,".$d_id_array[$i].",".$d_type[d_fie].",$item_num)";
						$DB_site->query($sql);
					}
				}
			}
		}
		switch($g_boss){
			case 0:
				$item_num=5;
				break;
			case 1:
				$item_num=9;
				break;
			case 2:
				$item_num=9;
				break;
			case 3:
				$item_num=9;
				break;
			case 4:
				$item_num=3;
				break;
			case 7:
				$item_num=7;
				break;
			case 8:
				$item_num=4;
				break;
			case 5:
				$item_num=12;
				break;
		} // switch
		$sql="select d_id,d_fie from wog_df where d_lv>0 and d_type<>6 and d_hole < 4 ORDER BY RAND() limit $item_num";
		$d_main=$DB_site->query($sql);
		while($d_mains=$DB_site->fetch_array($d_main))
		{
			$sql="select d_id from wog_group_depot where g_id=$g_id and d_id=".$d_mains[d_id];
			$d_check=$DB_site->query_first($sql);
			if($d_check)
			{
				$sql="update wog_group_depot set d_num=d_num+1 where g_id=$g_id and d_id=".$d_mains[d_id];
				$DB_site->query($sql);
			}
			else
			{
				$sql="select count(d_id) as num  from wog_group_depot where g_id=$g_id";
				$d_check=$DB_site->query_first($sql);
				if($d_check[num] < $wog_arry["g_depot_num"])
				{
					$sql="insert into wog_group_depot(g_id,d_id,d_type,d_num)values($g_id,".$d_mains[d_id].",".$d_mains[d_fie].",1)";
					$DB_site->query($sql);
				}
			}
		}
	}
	//改變資源
	function group_mod_ex($g_id,$target_id,$def_lv,$fbook_id)
	{
		global $DB_site,$lang,$wog_arry;
		$ex=$DB_site->query_first("select ex_1,ex_2,ex_3,ex_4,ex_5,ex_6,ex_7,ex_8,ex_9,ex_10,ex_11,ex_12 from wog_group_exchange where g_id=".$target_id);
		$ex_get=array();
		$temp="";
		$time=time();
		if($ex)
		{
			$sql_update="";
			$def_ex=def_ex_13($def_lv);
			for($i=1;$i<13;$i++)
			{
				$temp_ex=0;
				if($ex["ex_".$i] > 0)
				{
					$temp_ex=round($ex["ex_".$i]*(rand(2,4)/10)*$def_ex);
					$temp_lost=$ex["ex_".$i]-$temp_ex;
					$ex_get["ex_".$i]="ex_".$i."=ex_".$i."+".$temp_ex;
					$temp.=" ".$wog_arry[g_ex][$i].":".$temp_ex;
					$sql_update.=",ex_".$i."=".$temp_lost;
				}
			}
			if(!empty($sql_update))
			{
				$sql_update=substr($sql_update,1);
				$sql="update wog_group_exchange set $sql_update where g_id=$target_id";
				$DB_site->query($sql);

				if(!empty($g_id))
				{
					$sql="update wog_group_exchange set ".implode(',',$ex_get)." where g_id=$g_id[g_id]";
					$DB_site->query($sql);
				}
				$temp=substr($temp,1);
				$temp2=sprintf($lang['wog_act_group_msg2'],$g_id[g_name],$temp).sprintf($lang['wog_act_group_msg16'],$fbook_id);
				$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($target_id,' ".$temp2." ',$time)";
				$DB_site->query($sql);
			}
		}
		return $temp=(empty($temp))?"0":$temp;
	}
	//獲取兵種資料
	function group_wp_main()
	{
		global $DB_site;
		if(!$this->wp_array)
		{
			$sql="select wp_id,wp_name,wp_fs,wp_durable,weapon_1,weapon_2,weapon_3,weapon_4,weapon_5,weapon_6,weapon_7,weapon_8,weapon_9,weapon_10,weapon_11,weapon_12 from wog_weapon_main ";
			$wp_main=$DB_site->query($sql);
			$wp=array();
			while($wp_mains=$DB_site->fetch_array($wp_main))
			{
				$wp[$wp_mains[wp_id]]["wp_fs"]=$wp_mains[wp_fs];
				$wp[$wp_mains[wp_id]]["wp_name"]=$wp_mains[wp_name];
				$wp[$wp_mains[wp_id]]["wp_durable"]=$wp_mains[wp_durable];
				$wp[$wp_mains[wp_id]]["weapon_1"]=$wp_mains[weapon_1];
				$wp[$wp_mains[wp_id]]["weapon_2"]=$wp_mains[weapon_2];
				$wp[$wp_mains[wp_id]]["weapon_3"]=$wp_mains[weapon_3];
				$wp[$wp_mains[wp_id]]["weapon_4"]=$wp_mains[weapon_4];
				$wp[$wp_mains[wp_id]]["weapon_5"]=$wp_mains[weapon_5];
				$wp[$wp_mains[wp_id]]["weapon_6"]=$wp_mains[weapon_6];
				$wp[$wp_mains[wp_id]]["weapon_7"]=$wp_mains[weapon_7];
				$wp[$wp_mains[wp_id]]["weapon_8"]=$wp_mains[weapon_8];
				$wp[$wp_mains[wp_id]]["weapon_9"]=$wp_mains[weapon_9];
				$wp[$wp_mains[wp_id]]["weapon_10"]=$wp_mains[weapon_10];
				$wp[$wp_mains[wp_id]]["weapon_11"]=$wp_mains[weapon_11];
				$wp[$wp_mains[wp_id]]["weapon_12"]=$wp_mains[weapon_12];
			}
			$this->wp_array=$wp;
			unset($wp);
		}
		return $this->wp_array;
	}
	function group_move_time(&$wp_id,$tg_id,$pg_id)
	{
		global $DB_site;
		$sql="select wp_id,wp_seed from wog_weapon_main where wp_id in (".implode(',',$wp_id).")";
		$wp_main=$DB_site->query($sql);
		$time=0;
		while($wp_mains=$DB_site->fetch_array($wp_main))
		{
			if($wp_mains[wp_seed] > $time){$time=$wp_mains[wp_seed];}
		}
		$time=$time*$this->group_xy_line($tg_id,$pg_id)*12;
		$sql="select b_28 from wog_group_build where g_id=".$pg_id;
		$build=$DB_site->query_first($sql);
		if($build[b_28]>0)
		{
			$sql="select lv from wog_build_main where b_id =".$build[b_28];
			$build=$DB_site->query_first($sql);
			$time=$time*(1-(($build[lv]*0.2)/100));
		}
		return round($time);
	}
	//獲取座標直線長度
	function group_xy_line($tg_id,$pg_id){
		global $DB_site,$wog_arry,$lang;
		$time=time();
		$sql="select g_name,g_area_x,g_area_y,g_create_time,g_lost_time,g_break,g_npc,g_boss from wog_group_main where g_id=".$tg_id;
		$t_g=$DB_site->query_first($sql);
		/*
		if($t_g[g_break]==1 and $t_g[g_npc]==1)
		{
			alertWindowMsg($lang['wog_act_group_msg12']);
		}
		*/
		if($time-$t_g[g_create_time] < $wog_arry["g_not_fight"])
		{
			alertWindowMsg($lang['wog_act_group_msg10']);
		}
		if($time<$t_g[g_lost_time] && $t_g[g_boss]!=9)
		{
			alertWindowMsg($lang['wog_act_group_msg10']);
		}
		$sql="select g_name,g_area_x,g_area_y,g_create_time,g_lost_time from wog_group_main where g_id=".$pg_id;
		$p_g=$DB_site->query_first($sql);
		if($time-$p_g[g_create_time] < $wog_arry["g_not_fight"])
		{
			alertWindowMsg($lang['wog_act_group_msg10']);
		}
		if($time<$p_g[g_lost_time])
		{
			alertWindowMsg($lang['wog_act_group_msg10']);
		}

		$x=$t_g[g_area_x]-$p_g[g_area_x];
		$y=$t_g[g_area_y]-$p_g[g_area_y];
		if($x<0){$x*=-1;}
		if($y<0){$y*=-1;}
		$line=sqrt(pow($x,2)+pow($y,2));
		return $line;
	}
	//npc公會發動攻擊
	function group_npc_fight($t_g=array())
	{
		global $DB_site,$wog_arry,$lang;
		$g_id=0;
		$time=time();
		if(count($t_g)<1)
		{
			$sql="select count(g_id) as id from wog_group_job where j_type=1";
			$g_j=$DB_site->query_first($sql);
			if($g_j[id] < 7)
			{
				$sql="select b.g_id,b.g_create_time,b.g_lost_time,b.g_name from wog_group_weapon a,wog_group_main b  where a.g_id=b.g_id and b.g_npc=0 and 10000 < (a.weapon_1+a.weapon_2+a.weapon_3+a.weapon_4+a.weapon_5+a.weapon_6+a.weapon_7+a.weapon_8+a.weapon_9+a.weapon_10+a.weapon_11+a.weapon_12) ORDER BY RAND() limit 1";
				$t_g=$DB_site->query_first($sql);
				if(!$t_g)
				{
					return false;
				}
			}
		}
		if($time-$t_g[g_create_time] < $wog_arry["g_not_fight"])
		{
			return false;
		}
		if($time<$t_g[g_lost_time])
		{
			return false;
		}
		$g_id=$t_g[g_id];
		if($g_id>0)
		{
			$sql="select g_id,g_name,g_boss from wog_group_main where g_npc=1 and g_boss <>5 and g_boss <>9 and g_lost_time < $time ORDER BY RAND() limit 1";
			$p_g=$DB_site->query_first($sql);
			switch($p_g[g_boss])
			{
				case 1:
				case 2:
				case 3:
					$r=rand(1,3);
					$sum=0;
					$wp=array();
					while($sum <$r)
					{
						$wp_id=rand(1,12);
						if(!in_array($wp_id,$wp))
						{
							$wp[$wp_id]=$wp_id;
							$sum++;
						}
					}
					$sql_insert="";
					$sql_insert2="";
					foreach($wp as $kay)
					{
						$sql_insert.=",weapon_".$kay;
						$sql_insert2.=",".rand(650,900);
					}
					$sql_insert.=",j_formation";
					$sql="select b_id from wog_build_main where type=3 and lv between 3 and 4 ORDER BY RAND() limit 1";
					$g_formation=$DB_site->query_first($sql);
					$sql_insert2.=",".$g_formation["b_id"];
				break;
				case 0:
				case 4:
				case 7:
				default:
					$r=rand(1,3);
					$sum=0;
					$wp=array();
					while($sum <$r)
					{
						$wp_id=rand(1,9);
						if(!in_array($wp_id,$wp))
						{
							$wp[$wp_id]=$wp_id;
							$sum++;
						}
					}
					$sql_insert="";
					$sql_insert2="";
					foreach($wp as $kay)
					{
						$sql_insert.=",weapon_".$kay;
						$sql_insert2.=",".rand(150,450);
					}
					$sql_insert.=",j_formation";
					$sql="select b_id from wog_build_main where type=3 and lv between 1 and 2 ORDER BY RAND() limit 1";
					$g_formation=$DB_site->query_first($sql);
					$sql_insert2.=",".$g_formation["b_id"];
				break;
			}
			$sql_insert=substr($sql_insert,1);
			$sql_insert2=substr($sql_insert2,1);
			$time=$time+$this->group_move_time($wp,$g_id,$p_g[g_id]);

			$temp=sprintf($lang['wog_act_group_msg8'],$p_g[g_name],$t_g[g_name],set_date($time));

			$now_time=time();
			$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($g_id,' ".$temp." ',$now_time)";
			$DB_site->query($sql);

			$sql="insert into wog_group_job(g_id,p_id,j_type,j_time,j_target,$sql_insert)values($p_g[g_id],0,1,$time,$g_id,$sql_insert2)";
			$DB_site->query($sql);

			require_once($wog_arry["chat_path"]."/class/chat_class.php");
			$chat_class = new chat_class;
			$chat_class->file_path=$wog_arry["chat_path"]."/data/";
			$speed=$chat_class->get_speed();
			unset($chat_class);

			$user=$p_g[g_name];
			$cookie="wog_cookie_name=".urlencode("系統")."; wog_cookie_name2=".urlencode($user)."; wog_chat_cookie_debug=".urlencode(md5($user.$wog_arry["cookie_debug"]));

			$url=$wog_arry["main_url"];
			$lastspeed=$speed;
			$lastspeed=urlencode($lastspeed);
			$r=rand(23,32);
			$chat_body=sprintf($lang["wog_act_group_msg".$r],$t_g[g_name]);
			$chat_body=urlencode($chat_body);
			$chat_type="1";
			$chat_type=urlencode($chat_type);

			$params="lastspeed=".$lastspeed."&chat_body=".$chat_body."&chat_type=".$chat_type."&item_id=&towhos=&set_lock1=&set_lock2=&set_lock3=&set_lock4=";
			$length = strlen($params);
			/*
			$fp = fsockopen($url,80,$errno,$errstr,5);

			$header = "POST /wog/wog_chat/wog_chat_hidden.php HTTP/1.1\r\n";
			$header.= "Content-Length: ".$length."\r\n";
			$header.= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header.="Referer: http://127.0.0.1/wog/wog_chat/wog_chat.php\r\n";
			$header.="Accept-Language: zh-cn,zh-tw;q=0.5\r\n";
			$header.="Accept-Encoding: gzip, deflate\r\n";
			$header.="User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; GTB6; .NET CLR 2.0.50727)\r\n";
			$header.= "Host: $url\r\n";
			$header.= "Cookie: ".$cookie."\r\n";
			$header.= "Coonnction: Close\r\n\r\n";

			$header.= $params."\r\n";
			fputs($fp,$header);
			while(!feof($fp))
			{
				fgets($fp,1024);
			}
			fclose($fp);
			*/
			return true;
		}else
		{
			return false;
		}

	}
	// 降低研究等級
	function group_down_build($g_id)
	{
		global $DB_site,$wog_arry,$lang;
		$chk=array(0,201,211,219,227,235,243,253,263,273,283,293,303,313);
		$chk2=array();
		$b_id=rand(0,26);
		$chk2[]=$b_id;
		$sql="select b_".$b_id." from wog_group_build where g_id=$g_id";
		$b_main=$DB_site->query_first($sql);
		$sum=0;
		while(in_array($b_main[0],$chk) && $sum <24)
		{
			$b_id=rand(0,26);
			if(!in_array($b_id,$chk2))
			{
				$sql="select b_".$b_id." from wog_group_build where g_id=$g_id";
				$b_main=$DB_site->query_first($sql);
				if(!in_array($b_main[0],$chk)){break;}
				$chk2[]=$b_id;
			}
			$sum++;
		}
		$sql="select a.b_id from wog_build_main a,wog_build_main b where a.main_id=$b_id and b.b_id=$b_main[0] and a.lv < b.lv ORDER BY a.lv DESC LIMIT 1";
		$b_main=$DB_site->query_first($sql);
		$sql="update wog_group_build set b_".$b_id."=".$b_main[0]." where g_id=$g_id";
		$DB_site->query($sql);
	}
	function group_permissions_chk($a1,$a2) //檢查權限
	{
		global $lang;
		if($a1["lv"]!=1 && $a1[$a2]!=1)
		{
			alertWindowMsg($lang['wog_act_group_error31']);
		}		
	}
}
?>