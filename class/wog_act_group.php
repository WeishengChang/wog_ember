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

class wog_act_group{
	function group_view($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry,$wog_fight_group;
		
		$this->group_npc_restart();
		
		$wog_fight_group->group_check_job($user_id);
//		npc攻擊改用crontab
		$wog_fight_group->group_npc_fight();

		$group_main=$DB_site->query_first("select b.p_g_id,a.g_name,a.g_area_x,a.g_area_y from wog_player b,wog_group_main a where b.p_id=".$user_id." and a.g_id=b.p_g_id");
		$s="";
		$x_y="0,0";
		if(!empty($group_main[p_g_id]))
		{
//			alertWindowMsg($lang['wog_act_group_nogroup']);

			$g_id=$group_main[p_g_id];

			$sql="select j_target,j_time,j_id from wog_group_job where g_id=$g_id and j_type=1";
			$job=$DB_site->query($sql);
			$s1=array();
			$s2=array();
			$time=time();
			while($jobs=$DB_site->fetch_array($job))
			{
				$s2[]=$jobs[j_target];
				$s1[$jobs[j_target]][j_time]=$jobs[j_time]-$time;
				$s1[$jobs[j_target]][type]=1;
				$s1[$jobs[j_target]][j_id]=$jobs[j_id];
			}
			$sql="select g_id,j_time from wog_group_job where j_target=$g_id and j_type=1";
			$job=$DB_site->query($sql);
			while($jobs=$DB_site->fetch_array($job))
			{
				$s2[]=$jobs[g_id];
				$s1[$jobs[g_id]][j_time]=$jobs[j_time]-$time;
				$s1[$jobs[g_id]][type]=2;
			}

			if(count($s2) >0)
			{
				$sql="select g_id,g_name from wog_group_main where g_id in (".implode(",",$s2).")";
				$list=$DB_site->query($sql);
				while($lists=$DB_site->fetch_array($list))
				{
					$s1[$lists[g_id]][g_name]=$lists[g_name];
				}

				foreach($s1 as $v)
				{
					$s.=";".$v[g_name].",".$v[j_time].",".$v[type].",".$v[j_id];
				}
				if(!empty($s))
				{
					$s=substr($s,1);
				}
			}
			$map="";
			$map=$this->group_map_make($group_main[g_area_x],$group_main[g_area_y]);
			$x_y=$group_main[g_area_x].",".$group_main[g_area_y];
		}
		showscript("parent.group_view('".$s."','".$map."',$x_y)");
	}
	function group_map()
	{
		global $DB_site,$_POST;
		if((int)$_POST["x"]<0 || (int)$_POST["y"]<0){return;}
		//$x_y=explode(",",$_POST["temp_id"]);
		$x=$_POST["x"];
		$y=$_POST["y"];
		$map=$this->group_map_make($x,$y);
		showscript("parent.group_map('".$map."')");
	}
	function group_map_make($x,$y)
	{
		global $DB_site;

		$start_x=$x-3;
		if($start_x<0){$start_x=0;}
		if($start_x>294){$start_x=294;}

		$start_y=$y-3;
		if($start_y<0){$start_y=0;}
		if($start_y>294){$start_y=294;}

		$end_x=$x+3;
		if($end_x>300){$end_x=300;}
		if($end_x<6){$end_x=6;}

		$end_y=$y+3;
		if($end_y>300){$end_y=300;}
		if($end_y<6){$end_y=6;}

		$sql="select x,y,area_type,g_id from wog_group_map where x>=$start_x and y>=$start_y and x<=$end_x and y<=$end_y order by id";
		$job=$DB_site->query($sql);
		$map="";
		while($jobs=$DB_site->fetch_array($job))
		{
			$map.=";".$jobs[g_id].",".$jobs[x].",".$jobs[y].",".$jobs[area_type].",";
			if(!empty($jobs[g_id]))
			{
				$sql="select g_name,g_peo,g_lv,g_weather from wog_group_main where g_id=".$jobs[g_id];
				$g=$DB_site->query_first($sql);
				$map.=$g[g_name].",".$g[g_peo].",".$g[g_lv].",".$g[g_weather];
			}
		}
		$DB_site->free_result($job);
		unset($jobs);
		if(!empty($map))
		{
			$map=substr($map,1);
		}
		return $map;
	}
	function group_mission_list($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$g_id=$group_main[p_g_id];
		$sql="select g_restart_mission from wog_group_main  where g_id=$g_id";
		$group_main=$DB_site->query_first($sql);
		$time=time();
		if($group_main[g_restart_mission] < $time)
		{
			//重置公會任務 begin
			$sql="delete from wog_group_mission_log where g_id=$g_id";
			$DB_site->query($sql);
			$sql="select id,type,title from wog_group_mission limit 10";
			$mission=$DB_site->query($sql);
			while($missions=$DB_site->fetch_array($mission))
			{
				include_once("./group_mission/wog_gmission_".$missions[id].".php");
				eval("\$gm=new gmission_".$missions[id].";");
				$m_main=$gm->mission_start();
				$m_sum=count($m_main[0]);
				for($i=0;$i<$m_sum;$i++)
				{

					$reward=$m_main[1][$i][type].":".$m_main[1][$i][id].":".$m_main[1][$i][num];
					switch($missions[type])
					{
						case 1:
							$need=$m_main[0][$i][wp_id].":".$m_main[0][$i][wp_num]."|0";
							$title=$missions[title].$wog_arry[g_wp][$m_main[0][$i][wp_id]];
						break;
						case 2:
						case 3:
							$need=$m_main[0][$i][g_name].":".$m_main[0][$i][g_id];
							$title=$missions[title].$m_main[0][$i][g_name];
						break;
						case 4:
							$need=$m_main[0][$i][g_name].":".$m_main[0][$i][g_id].":".$m_main[0][$i][wp_id].":".$m_main[0][$i][wp_num]."|0";;
							$title=$missions[title].$m_main[0][$i][g_name];
						break;
					}
					$sql="insert wog_group_mission_log(m_id,g_id,type,title,need,reward,status)values($missions[id],$g_id,$missions[type],'$title','$need','$reward',0)";
					$DB_site->query($sql);
				}
			}
			$sql="update wog_group_main set g_restart_mission=".($time+$wog_arry["g_restart_mission"])." where g_id=$g_id";
			$DB_site->query($sql);
			//重置公會任務 end
		}
		$sql="select a.id,a.title,a.status,b.p_name from wog_group_mission_log a left join wog_player b on b.p_id=a.p_id where a.g_id=$g_id";
		$mission=$DB_site->query($sql);
		$s=array();
		while($missions=$DB_site->fetch_array($mission))
		{
			$s[]=$missions[id].",".$missions[title].",".$missions[status].",".$missions[p_name];
		}
		$DB_site->free_result($mission);
		unset($missions);
		showscript("parent.group_mission_list('".implode(";",$s)."')");
	}
	function group_mission_view($user_id)
	{
		global $DB_site,$_POST,$lang;
		$id=$_POST["temp_id"];
		if(empty($id)){alertWindowMsg($lang['wog_act_errwork']);}
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$g_id=$group_main[p_g_id];
		$sql="select b.body,a.need,a.reward,a.type from wog_group_mission_log a,wog_group_mission b where a.id=$id and a.g_id=$g_id and b.id=a.m_id";
		$mission=$DB_site->query_first($sql);
		$s="";
		if($mission)
		{
			$s=$mission[body].",".$mission[need].",".$mission[reward];
		}
		showscript("parent.wog_message_box('$s',0,3,$mission[type])");
		unset($mission);
	}
	function group_depot_list($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		$d_type=$_POST["temp_id"];
		if(empty($d_type))
		{
			$d_type=0;
		}
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$item_array=array();
		$item_array2=array();
		$item=$DB_site->query("select d_id,d_num,d_point from wog_group_depot a where a.g_id=".$group_main[p_g_id]." and d_type =$d_type");
		while($items=$DB_site->fetch_array($item))
		{
			$item_array[]=$items[d_id];
			$item_array2[$items[d_id]][0]=$items[d_num];
			$item_array2[$items[d_id]][1]=$items[d_point];
		}
		$DB_site->free_result($item);
		unset($items);
		$point=$DB_site->query_first("select p_point from wog_group_member_point where g_id=$group_main[p_g_id] and p_id=".$user_id);
		if($item_array)
		{
			$item_main=$DB_site->query("select d_id,d_name,d_hole,d_send from wog_df where d_id in (".implode(',',$item_array).")");
			$temp_s="";
			while($item_mains=$DB_site->fetch_array($item_main))
			{
				$temp_s.=";".$item_mains[d_id].",".$item_mains[d_name]."(".$item_mains[d_hole]."),".$item_array2[$item_mains[d_id]][0].",".$item_mains[d_send].",".$item_array2[$item_mains[d_id]][1];
			}
			$DB_site->free_result($item_main);
			unset($item_mains);
			$temp_s=substr($temp_s,1);
		}

		$time=time()-$wog_arry["g_news"];
		$DB_site->query("delete from wog_group_depot_msg where dateline < $time");
		$sql="select depot_msg,dateline from wog_group_depot_msg where g_id=".$group_main[p_g_id]." order by dateline desc LIMIT 5";
		$pack=$DB_site->query($sql);
		$s1="";
		$s2="";
		while($packs=$DB_site->fetch_array($pack))
		{
			$s1=$s1.";".$packs[depot_msg];
			$s2=$s2.";".set_date($packs[dateline]);
		}
		$s1=substr($s1,1);
		$s2=substr($s2,1);
		$DB_site->free_result($pack);

		showscript("parent.group_depot_list('$temp_s','$d_type',$point[p_point],'$s1','$s2')");
		unset($temp_s,$s1,$s2,$packs);
	}
	function group_depot_move($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_item_tool,$wog_arry,$a_id;
		if(empty($_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_noid']);
		}
		if(preg_match("/[<>'\", ;]/", $_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_errword']);
		}
		if(empty($_POST["temp_id2"]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$item_num=$_POST["temp_id"];
		if($item_num <=0 || !is_numeric($item_num) ||  preg_match("/[^0-9]/",$item_num)){alertWindowMsg($lang['wog_act_group_error3']);}
		$pay_id=trim($_POST["pay_id"]);
		$d_id=$_POST["temp_id2"];
		
		$group_main=$DB_site->query_first("select b.p_g_id,a.lv,d.g_name from wog_group_permissions a,wog_player b,wog_group_member_point c,wog_group_main d where b.p_id=".$user_id." and c.p_id=".$user_id." and d.g_id = b.p_g_id and a.id=c.p_permissions");
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		//$this->group_permissions_chk($p,"p2");
		/*
		$group_main=$DB_site->query_first("select b.p_g_id,a.g_name,a.g_adm_id1,a.g_adm_id2 from wog_player b,wog_group_main a where b.p_id=".$user_id." and b.p_g_id=a.g_id");
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		*/
		
		$sql="select d_name,d_fie from wog_df where d_id=".$d_id;
		$pay=$DB_site->query_first($sql);
		$a_id=type_name($pay[d_fie]);
		$d_type=$pay[d_fie];
		$d_name=$pay[d_name];
		if(empty($item_num) || $d_type <5)
		{
			$item_num=1;
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		if($group_main["lv"]!=1 && $group_main["lv"]!=2)
		{
			$point=$DB_site->query_first("select p_point from wog_group_member_point where g_id=$group_main[p_g_id] and p_id=".$user_id." for update");
			if(!$point){alertWindowMsg($lang['wog_act_group_nolv']);}
		}
		$sql="select d_num,d_point from wog_group_depot where g_id=".$group_main[p_g_id]." and d_id=$d_id for update ";
		$depot_main=$DB_site->query_first($sql);
		if($depot_main[d_num] < $item_num)
		{
			alertWindowMsg($lang['wog_act_group_error3']);
		}
		if($point)
		{
			if($point[p_point] < $depot_main[d_point])
			{
				alertWindowMsg($lang['wog_act_group_error13']);
			}
			if($depot_main[d_point] == 0)
			{
				alertWindowMsg($lang['wog_act_group_error3']);
			}
			$point[p_point]-=$depot_main[d_point];
		}else
		{
			$depot_main[d_point] = 0;
		}
		$depot_main[d_num]-=$item_num;
		$sql="select p_id,p_bag,p_name from wog_player where p_name='".$pay_id."'";
		$pay_id=$DB_site->query_first($sql);
		if(!$pay_id)
		{
			alertWindowMsg($lang['wog_act_arm_nomove']);
		}
		$sql="select ".$a_id." from wog_item where p_id=".$pay_id[p_id]." for update ";
		$pay=$DB_site->query_first($sql);
		$temp_pack=array();
		if(!empty($pay[0]))
		{
			$temp_pack=explode(",",$pay[0]);
		}
		$adds=$wog_item_tool->item_in($temp_pack,$d_id,$item_num);
		if($a_id=="d_item_id" || $a_id=="d_stone_id")
		{
			$bag=$wog_arry["item_limit"]+$pay_id[p_bag];
		}else
		{
			$bag=$wog_arry["item_limit"];
		}
		if(count($adds) > $bag)
		{
			alertWindowMsg($lang['wog_act_bid_full']);
		}
		if($group_main["lv"]!=1 && $group_main["lv"]!=2)
		{
			$DB_site->query("update wog_group_member_point set p_point=".$point[p_point]." where g_id=$group_main[p_g_id] and p_id=".$user_id);
			$temp="parent.f.getElementById('point').innerHTML=".$point[p_point].";";
		}
		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$adds)."' where p_id=".$pay_id[p_id]);
		if($depot_main[d_num]<=0)
		{
			$DB_site->query("delete from wog_group_depot where g_id=".$group_main[p_g_id]." and d_id=".$d_id);
		}
		else
		{
			$DB_site->query("update wog_group_depot set d_num=".$depot_main[d_num]." where g_id=".$group_main[p_g_id]." and d_id=".$d_id);
		}
		$time=time();
		$DB_site->query("insert into wog_message(p_id,title,dateline)values(".$pay_id[p_id].",'從 公會[".$group_main[g_name]."] 收到 ".$d_name."*".$item_num." ',".$time.")");
		$DB_site->query("insert into wog_group_depot_msg(g_id,depot_msg,dateline)values(".$group_main[p_g_id].",' ".$d_name."*".$item_num." to ".$pay_id[p_name]." ',".$time.")");
		//$DB_site->query("insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values(".$group_main[p_g_id].",' ".$d_name." to ".$pay_id[p_name]." ',".time().")");
		$temp="";
		$DB_site->query_first("COMMIT");
		showscript("parent.job_end(24,null,1,$d_id,$depot_main[d_num]);$temp");
		unset($temp_s,$depot_main,$group_main,$depot_main);
	}
	function group_wp($user_id)
	{
		global $DB_site,$lang,$wog_fight_group;
//		$this->group_npc_restart();
		$wog_fight_group->group_check_job($user_id);
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$s="";
		$wp=$DB_site->query_first("select weapon_1,weapon_2,weapon_3,weapon_4,weapon_5,weapon_6,weapon_7,weapon_8,weapon_9,weapon_10,weapon_11,weapon_12 from wog_group_weapon where g_id=".$group_main[0]);
		if($wp)
		{
			$s.=$wp["weapon_1"].",".$wp["weapon_2"].",".$wp["weapon_3"].",".$wp["weapon_4"].",".$wp["weapon_5"].",".$wp["weapon_6"].",".$wp["weapon_7"].",".$wp["weapon_8"].",".$wp["weapon_9"].",".$wp["weapon_10"].",".$wp["weapon_11"].",".$wp["weapon_12"];
		}
//		$s3=$wp["g_durable"]."/".$wp["g_durable_max"];
		unset($wp);
		$wp_max=$DB_site->query_first("select b_0,b_1,b_2,b_3,b_4,b_5,b_6,b_7,b_8,b_9,b_10,b_11 from wog_group_build where g_id=".$group_main[0]);
		$wp_array=array();
		$wp_array2=array();
		for($i=0;$i<12;$i++)
		{
			if($wp_max[$i]>0){$wp_array[$i]=$wp_max[$i];}
			$wp_array2[$i]=0;
		}
		if(!empty($wp_array))
		{
			$sql="select type_class,wp_num from wog_build_main where b_id in(".implode(",",$wp_array).")";
			$wp_max=$DB_site->query($sql);
			while($wp_maxs=$DB_site->fetch_array($wp_max))
			{
				$wp_array2[$wp_maxs["type_class"]]=$wp_maxs["wp_num"];
			}
		}
		$time=time();
		$s2="";
		$job_main=$DB_site->query("select a.j_id,a.j_time,a.j_type,b.g_name from wog_group_job a left join wog_group_main b on  a.j_target=b.g_id  where a.p_id=$user_id and a.j_type in (1,2,3,4)");
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			$s2.=";".$job_mains[j_id].",".($job_mains[j_time]-$time).",".$job_mains[j_type].",".$job_mains[g_name];
		}
		unset($job_mains);
		$DB_site->free_result($job_main);
		if(!empty($s2)){$s2=substr($s2,1);}

		$sql="select b_19,b_20,b_21,b_22,b_23,b_24,b_25,b_26 from wog_group_build where g_id=$group_main[p_g_id]";
		$temp=$DB_site->query_first($sql);
		$sql="select b_id,type,name from wog_build_main where b_id in ($temp[b_19],$temp[b_20],$temp[b_21],$temp[b_22],$temp[b_23],$temp[b_24],$temp[b_25],$temp[b_26])";
		$temp=$DB_site->query($sql);
		$a2=array();
		while($temps=$DB_site->fetch_array($temp))
		{
			$a2[]=$temps[b_id].";".$temps[name];
		}
		$DB_site->free_result($temp);
		unset($temps);
		showscript("parent.group_wp('".$s."','".$s2."','".implode(",",$wp_array2)."','".implode(",",$a2)."')");
	}
	function group_ex($user_id)
	{
		global $DB_site,$lang,$wog_fight_group,$wog_arry;
//		$this->group_npc_restart();
		$wog_fight_group->group_check_job($user_id);
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$s=$this->group_ex_data($group_main[0]);

		$s2="";
		$temp_old1="";
		$temp_old2="";
		$temp="";
		$wp_main=$DB_site->query("select wp_id,ex_id,ex_num from wog_weapon_list order by wp_id");
		while($wp_mains=$DB_site->fetch_array($wp_main))
		{
			if(empty($temp_old1))
			{
				$temp_old1=$wp_mains[wp_id];
				$temp_old2=$wog_arry[g_wp][$wp_mains[wp_id]];
			}
			if($temp_old1 != $wp_mains[wp_id])
			{
				$s2.=";".$temp_old1.",".$temp_old2.",".$temp;
				$temp="";
				$temp_old1=$wp_mains[wp_id];
				$temp_old2=$wog_arry[g_wp][$wp_mains[wp_id]];
			}
			if($temp_old1 == $wp_mains[wp_id])
			{
				$temp.=" ".$wog_arry[g_ex][$wp_mains[ex_id]].":".$wp_mains[ex_num];
			}
		}
		$s2.=";".$temp_old1.",".$temp_old2.",".$temp;
		unset($wp_mains);
		$DB_site->free_result($wp_main);
		$s2=substr($s2,1);
		$time=time();
		$s3="";
		$job_main=$DB_site->query("select j_id,j_time,j_class,weapon_1,weapon_2,weapon_3,weapon_4,weapon_5,weapon_6,weapon_7,weapon_8,weapon_9 from wog_group_job where p_id=$user_id and j_type=0");
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			$s3.=";".$job_mains[j_id].",".($job_mains[j_time]-$time).",".$job_mains[j_class].",".$job_mains["weapon_".$job_mains[j_class]];
		}
		unset($job_mains);
		$DB_site->free_result($job_main);
		if(!empty($s3)){$s3=substr($s3,1);}
		showscript("parent.group_ex('$s','$s2','$s3')");
	}
	function group_build_list($user_id)
	{
		global $DB_site,$lang;
		$this->group_build_check_job();
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$s=$this->group_ex_data($group_main[0]);
		$time=time();
//		$s2="";
		$job_main=$DB_site->query_first("select a.j_id,a.j_time-".$time.",b.name,b.lv from wog_group_build_job a ,wog_build_main b where a.p_id=$user_id and a.g_id=".$group_main[0]." and b.b_id=a.b_id");

		showscript("parent.group_build('$s',".toJSON($job_main).")");
	}
	function group_build_check_job()
	{
		global $DB_site;
		$time=time();
		$temp="";
		$check_gid=0;
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select a.g_id,a.b_id,a.b_main_id,b.lv
		 from wog_group_build_job a,wog_build_main b
		 where a.j_time <= $time and b.b_id=a.b_id order by a.j_time asc for update";
		$job_main=$DB_site->query($sql);
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			if($check_gid==0){$check_gid=$job_mains[g_id];}
			if($check_gid==$job_mains[g_id])
			{
				$temp.=",b_".$job_mains[b_main_id]."=".$job_mains[b_id];
			}else
			{
				$temp=substr($temp,1);
				$sql="update wog_group_build set $temp where g_id=".$check_gid;
				$DB_site->query($sql);
				$check_gid=$job_mains[g_id];
				$temp=",b_".$job_mains[b_main_id]."=".$job_mains[b_id];
			}
			switch($job_mains[b_main_id])
			{
				case 12:
					$sql="update wog_group_weapon set g_durable_max=8000+".(1500*$job_mains[lv])." where g_id=".$job_mains[g_id];
					$DB_site->query($sql);
				break;
			}
		}
		if(!empty($temp))
		{
			$temp=substr($temp,1);
			$sql="update wog_group_build set $temp where g_id=".$check_gid;
		}
		$DB_site->query($sql);
		$sql="delete from wog_group_build_job where j_time <= $time ";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
	}
	function group_build_show($user_id)
	{
		global $DB_site,$lang,$wog_arry,$_POST;
		$main_type=$_POST["pay_id"];
		$type=$_POST["temp_id"];
		if(isset($type) && $type !="null" && $type !="")
		{
			$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);

			$build_main=$DB_site->query_first("select b_".$type." from wog_group_build where g_id=$group_main[p_g_id]");
			$build_now_array=array();
			if($build_main[0] > 0)
			{
				$sql="select b_id,main_id,name,lv,content,need_time from wog_build_main where b_id=".$build_main[0];
				$build_main=$DB_site->query_first($sql);
				$build_now_array=array($build_main[name],$build_main[lv],$build_main[content]);
			}
			else
			{
				$build_main[lv]=0;
				$build_main[main_id]=$type;
			}
			$build_up_array=array();

			$s="";
			if($build_main[lv]<30)
			{
				/*
				$ex_main=$DB_site->query("select ex_id,ex_name from wog_exchange_main");
				$ex_array=array();
				while($ex_mains=$DB_site->fetch_array($ex_main))
				{
					$ex_array[$ex_mains[ex_id]]=$ex_mains[ex_name];
				}
				unset($ex_mains);
				$DB_site->free_result($ex_main);
				*/
				$sql="select b_id,main_id,name,lv,content,need_time*".$wog_arry["g_bulid_make_time"].",need_lv from wog_build_main where main_id=$build_main[main_id] and lv=".($build_main[lv]+1);
				$build_up_array=$DB_site->query_first($sql);
				$temp="";
				if($build_up_array)
				{
					$wp_main=$DB_site->query("select ex_id,ex_num from wog_build_list where b_id=".$build_up_array[b_id]);
					while($wp_mains=$DB_site->fetch_array($wp_main))
					{
						$s.=" ".$wog_arry[g_ex][$wp_mains[ex_id]].":".$wp_mains[ex_num];
					}
					unset($wp_mains);
					$DB_site->free_result($wp_main);
				}
			}
		}
		showscript("parent.group_build_show($main_type,".toJSON($build_now_array).",".toJSON($build_up_array).",'$s')");

	}
	function group_build_make($user_id)
	{
		global $DB_site,$lang,$_POST,$wog_arry;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_group_error15']);
		}
		$b_id=$_POST["temp_id"];
		$group_main=$DB_site->query_first("select b.p_g_id,a.lv,a.p5 from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$this->group_permissions_chk($group_main,"p5");

		$need_time=$DB_site->query_first("select need_time,main_id,type_class,need_lv,need_id from wog_build_main where b_id=$b_id");
		if(!$need_time)
		{
			alertWindowMsg($lang['wog_act_group_error15']);
		}
		$sql="select j_id from wog_group_job where p_id=$user_id limit 1";
		$job_count=$DB_site->query_first($sql);
		if($job_count)
		{
			alertWindowMsg($lang['wog_act_group_error17']);
		}
		$sql="select j_id from wog_group_build_job where p_id=$user_id limit 1";
		$job_count=$DB_site->query_first($sql);
		if($job_count)
		{
			alertWindowMsg($lang['wog_act_group_error16']);
		}
		$sql="select j_id from wog_group_build_job where b_id=$b_id and g_id=$group_main[p_g_id] limit 1";
		$job_count=$DB_site->query_first($sql);
		if($job_count)
		{
			alertWindowMsg($lang['wog_act_group_error19']);
		}
		$sql="select b_".$need_time[main_id]." from wog_group_build where g_id=".$group_main[p_g_id];
		$chk_data=$DB_site->query_first($sql);
		if($chk_data[0]!=$need_time[need_id])
		{
			alertWindowMsg($lang['wog_act_group_error20']."99");
		}
		$sql="select g_lv from wog_group_main where g_id=".$group_main[p_g_id];
		$chk_data=$DB_site->query_first($sql);
		if($chk_data[0]<$need_time[need_lv])
		{
			alertWindowMsg($lang['wog_act_group_error20']."888");
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$time=time();
		$group_ex=$DB_site->query_first("select ex_1,ex_2,ex_3,ex_4,ex_5,ex_6,ex_7,ex_8,ex_9,ex_10,ex_11,ex_12 from wog_group_exchange where g_id=".$group_main[p_g_id]." for update");
		$bulid_main=$DB_site->query("select ex_id,ex_num from wog_build_list where b_id=$b_id");
		$ex_need=0;
		$sql_need="";
		while($bulid_mains=$DB_site->fetch_array($bulid_main))
		{
			$ex_need=$bulid_mains[ex_num];
			if($group_ex["ex_".$bulid_mains[ex_id]] < $ex_need){alertWindowMsg($lang['wog_act_group_error34']);}
			$ex_need=$group_ex["ex_".$bulid_mains[ex_id]]-$ex_need;
			$sql_need.=",ex_".$bulid_mains[ex_id]."=".$ex_need;
		}
		unset($bulid_mains);
		$DB_site->free_result($bulid_main);
		$sql_need=substr($sql_need,1);
		$sql="update wog_group_exchange set $sql_need where g_id=".$group_main[p_g_id];
		$DB_site->query($sql);
		$make_speed=$need_time[need_time]*$wog_arry["g_bulid_make_time"];
		$time+=$make_speed;
		$sql="insert into wog_group_build_job(g_id,p_id,b_id,b_main_id,j_time)values($group_main[p_g_id],$user_id,$b_id,$need_time[main_id],$time)";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		unset($group_ex,$group_main,$need_time);
		$this->group_build_list($user_id);
	}
	function group_market_list($user_id) // 貿易
	{
		global $DB_site,$lang;
		$this->group_build_check_job();
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$g_id=$group_main[0];
		unset($group_main);
		$s=$this->group_ex_data($g_id);
		$time=time();
		$s3="";
		$job_main=$DB_site->query("select a.j_time,t_type,t_num from wog_group_market_job a where a.p_id=$user_id and a.t_id=".$g_id);
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			$s3.=";".($job_mains[j_time]-$time).",".$job_mains[t_type].",".$job_mains[t_num];
		}
		unset($job_mains);
		$DB_site->free_result($job_main);
		if(!empty($s3)){$s3=substr($s3,1);}

		$s4="";
		$job_main=$DB_site->query("select a.j_time,t_type,t_num from wog_group_market_job a where a.g_id<>$g_id and a.t_id=".$g_id);
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			$s4.=";".($job_mains[j_time]-$time).",".$job_mains[t_type].",".$job_mains[t_num];
		}
		unset($job_mains);
		$DB_site->free_result($job_main);
		if(!empty($s4)){$s4=substr($s4,1);}
		$this->group_market_show($g_id,$s2,$saletotal,$page,$s5,$s6);
		showscript("parent.group_market('$s','$s3','$s4');parent.group_market_show('$s2',$saletotal,$page,'$s5','$s6','".$_POST["sh1"]."','".$_POST["sh2"]."')");
	}
	function group_market_jump($user_id)
	{
		global $DB_site,$_POST;
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$g_id=$group_main[0];
		$this->group_market_show($g_id,$s2,$saletotal,$page,$s5,$s6);
		showscript("parent.group_market_show('$s2',$saletotal,$page,'$s5','$s6','".$_POST["sh1"]."','".$_POST["sh2"]."')");
	}
	function group_market_show($g_id,&$s2,&$saletotal,&$page,&$s5,&$s6)
	{
		global $DB_site,$_POST;
		if(empty($_POST["page"]))
		{
			$_POST["page"]="1";
		}
		$page=(int)$_POST["page"];
		$spage=($page*8)-8;
		$sh=array();
		$ssh1="";$ssh2="";
		if(!empty($_POST["sh1"]))
		{
			$sh[]="a.f_type=".$_POST["sh1"];
		}
		if(!empty($_POST["sh2"]))
		{
			$sh[]="a.t_type=".$_POST["sh2"];
		}
		if(!empty($sh))
		{
			$ssh1=" where ".implode(' and ',$sh);
			$ssh2="and ".implode(' and ',$sh);
		}
		$total=$DB_site->query_first("select count(a.id) as id from wog_group_market a $ssh1");
		$saletotal=$total[0];
		unset($total);
		$sql="select a.id,b.g_name,a.f_type,a.f_num,a.t_type,a.t_num,a.end_time from wog_group_market a,wog_group_main b where b.g_id=a.g_id $ssh2 LIMIT ".$spage.",8";
		$market=$DB_site->query($sql);
		$s2="";
		while($markets=$DB_site->fetch_array($market))
		{
			$markets[end_time]=date('Y-m-d H:i:s',$markets[end_time]);
			$s2.=";".$markets[id].",".$markets[g_name].",".$markets[f_type].",".$markets[f_num].",".$markets[t_type].",".$markets[t_num].",".$markets[end_time];
		}
		unset($markets,$ssh1,$ssh2,$sh);
		$DB_site->free_result($market);
		if(!empty($s2)){$s2=substr($s2,1);}
		$sql="select b.lv from wog_group_build a,wog_build_main b where a.g_id=$g_id and b.b_id=a.b_27";
		$build=$DB_site->query_first($sql);
		if(!$build){$build[0]=0;}
		$sql="select count(g_id) as id from wog_group_market where g_id=$g_id";
		$market=$DB_site->query_first($sql);
		$s5=$market[0]."/".$build[0];
		$sql="select count(g_id) as id from wog_group_market_job where g_id=$g_id and t_id=$g_id";
		$market=$DB_site->query_first($sql);
		$s6=$market[0]."/".$build[0];
		unset($build,$market);
	}
	function group_market_post($user_id) // 增加交易品
	{
		global $DB_site,$lang,$wog_arry,$_POST;
		$f_exid=$_POST["f_exid"];
		$t_exid=$_POST["t_exid"];
		$f_num=$_POST["f_num"];
		$t_num=$_POST["t_num"];
		if(empty($f_num))
		{
			alertWindowMsg($lang['wog_act_group_error23']);
		}
		if(empty($t_num))
		{
			alertWindowMsg($lang['wog_act_group_error24']);
		}
		$group_main=$DB_site->query_first("select b.p_g_id,b.p_name from wog_player b where b.p_id=".$user_id);
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$g_id=$group_main[p_g_id];
		$sql="select b.lv from wog_group_build a,wog_build_main b where a.g_id=$g_id and b.b_id=a.b_27";
		$build=$DB_site->query_first($sql);
		$sql="select count(g_id) as id from wog_group_market where g_id=$g_id";
		$market=$DB_site->query_first($sql);
		if($market[id]>=$build[0]){alertWindowMsg($lang['wog_act_group_error27']);}

		$f_ex=$DB_site->query_first("select ex_name from wog_exchange_main where ex_id=".$f_exid);
		$t_ex=$DB_site->query_first("select ex_name from wog_exchange_main where ex_id=".$t_exid);

		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select ex_".$f_exid." from wog_group_exchange where g_id=".$g_id." for update";
		$ex_main=$DB_site->query_first($sql);
		if(empty($ex_main[0]))
		{
			alertWindowMsg($lang['wog_act_group_error23']);
		}
		if($ex_main[0] < $f_num)
		{
			alertWindowMsg($lang['wog_act_group_error25']);
		}
		$sql="update wog_group_exchange set ex_".$f_exid."=".($ex_main[0]-$f_num)." where g_id=".$g_id;
		$DB_site->query($sql);
		$time=time();
		$sql="insert into wog_group_market(g_id,f_type,f_num,t_type,t_num,end_time)values($g_id,$f_exid,$f_num,$t_exid,$t_num,".($wog_arry["g_sale"]+$time).")";
		$DB_site->query($sql);
		$f_ex[ex_name].=":".$f_num;
		$t_ex[ex_name].=":".$t_num;
		$temp=sprintf($lang['wog_act_group_msg17'],$group_main[p_name],$f_ex[ex_name],$t_ex[ex_name]);
		$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($g_id,' ".$temp." ',$time)";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		$DB_site->query_first("set autocommit=1");
		$this->group_market_list($user_id);
	}
	function group_market_get1($user_id) // 確認交易品
	{
		global $DB_site,$lang,$wog_arry,$_POST;
		$mk_id=$_POST["mk_id"];
		if(empty($mk_id))
		{
			alertWindowMsg($lang['wog_act_group_error26']);
		}
		$sql="select g_id,f_type,f_num,t_type,t_num from wog_group_market where id=$mk_id";
		$mark=$DB_site->query_first($sql);
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$g_id=$group_main[p_g_id];
		$line=$this->group_xy_line($mark[g_id],$group_main[p_g_id])*$wog_arry["g_ex_conveyanc_time"];
		$time=time()+$line;
		$sql="select g_name from wog_group_main where g_id=".$mark[g_id];
		$f_g=$DB_site->query_first($sql);
		$time=date('Y-m-d H:i:s',$time);
		$s=$mk_id.",".$f_g[g_name].",".$mark[f_type].",".$mark[f_num].",".$mark[t_type].",".$mark[t_num].",".$time.",".$line;
		showscript("parent.group_market_get1('$s')");
	}
	function group_market_get2($user_id) // 進行輸送
	{
		global $DB_site,$lang,$wog_arry,$_POST;
		$mk_id=$_POST["mk_id"];
		if(empty($mk_id))
		{
			alertWindowMsg($lang['wog_act_group_error26']);
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select g_id,f_type,f_num,t_type,t_num from wog_group_market where id=$mk_id for update";
		$mark=$DB_site->query_first($sql);
		
		$group_main=$DB_site->query_first("select b.p_g_id,a.lv,a.p6 from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$this->group_permissions_chk($group_main,"p6");

		$g_id=$group_main[p_g_id];
		if($mark[g_id]==$g_id){alertWindowMsg($lang['wog_act_group_error28']);}

		$sql="select b.lv from wog_group_build a,wog_build_main b where a.g_id=$g_id and b.b_id=a.b_27";
		$build=$DB_site->query_first($sql);
		$sql="select count(g_id) as id from wog_group_market_job where g_id=$g_id";
		$market=$DB_site->query_first($sql);
		if($market[id]>=$build[0]){alertWindowMsg($lang['wog_act_group_error27']);}

		$sql="select ex_".$mark[t_type]." from wog_group_exchange where g_id=".$g_id." for update";
		$ex=$DB_site->query_first($sql);
		if($ex[0]<$mark[t_num])
		{
			alertWindowMsg($lang['wog_act_group_error25']);
		}
		$sql="update wog_group_exchange set ex_".$mark[t_type]."=".($ex[0]-$mark[t_num])." where g_id=".$g_id;
		$DB_site->query($sql);

		$line=$this->group_xy_line($mark[g_id],$group_main[p_g_id])*$wog_arry["g_ex_conveyanc_time"];
		$time=time()+$line;
		$sql="insert into wog_group_market_job(g_id,p_id,t_id,t_type,t_num,j_time)values($g_id,$user_id,$mark[g_id],$mark[t_type],$mark[t_num],$time)";
		$DB_site->query($sql);
		$sql="insert into wog_group_market_job(g_id,p_id,t_id,t_type,t_num,j_time)values($g_id,$user_id,$g_id,$mark[f_type],$mark[f_num],$time)";
		$DB_site->query($sql);
		$sql="delete from wog_group_market where id=$mk_id";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		$DB_site->query_first("set autocommit=1");
		$this->group_market_list($user_id);
	}
	function group_ex_conveyanc_job() // 處理運輸排程
	{
		global $DB_site;
		$time=time();
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select a.t_id,a.t_type,sum(a.t_num) as ex_num from wog_group_market_job a
		 where a.j_time <= $time group by a.t_id,a.t_type order by a.t_id asc for update";
		$job_main=$DB_site->query($sql);
		$t_id=0;
		$ex_get=array();
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			if(empty($t_id)){$t_id=$job_mains[t_id];}
			if($t_id!=$job_mains[t_id])
			{
				$sql="update wog_group_exchange set ".implode(',',$ex_get)." where g_id=$t_id";
				$DB_site->query($sql);
				$t_id=$job_mains[t_id];
				$ex_get=array();
				$ex_get[]="ex_".$job_mains[t_type]."=ex_".$job_mains[t_type]."+".$job_mains[ex_num];
			}else
			{
				$ex_get[]="ex_".$job_mains[t_type]."=ex_".$job_mains[t_type]."+".$job_mains[ex_num];
			}
		}
		unset($job_mains);
		$DB_site->free_result($job_main);
		if(!empty($ex_get))
		{
				$sql="update wog_group_exchange set ".implode(',',$ex_get)." where g_id=$t_id";
				$DB_site->query($sql);
		}
		$sql="delete from wog_group_market_job where j_time <= $time";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		$DB_site->query_first("set autocommit=1");
	}
	function group_durable($user_id,$npc_id=0)
	{
		global $DB_site,$lang,$_POST,$wog_arry;
		$group_main=array();
		if($npc_id > 0)
		{
			$group_main[p_g_id]=$npc_id;
			$sql="select j_id from wog_group_job where g_id=$npc_id and j_type = 3 limit 1";
			$job_count=$DB_site->query_first($sql);
			if($job_count)
			{
				return;
			}
		}
		else
		{
			$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
			if(!$group_main)
			{
				alertWindowMsg($lang['wog_act_group_nogroup']);
			}
			$sql="select j_id from wog_group_job where p_id=$user_id and j_type > 0 limit 1";
			$job_count=$DB_site->query_first($sql);
			if($job_count)
			{
				alertWindowMsg($lang['wog_act_group_error5']);
			}
		}
		$g=$DB_site->query_first("select g_durable,g_durable_max from wog_group_weapon where g_id=".$group_main[p_g_id]);
		if(!$g)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$time=time();
		$wp_num=$g[g_durable_max]-$g[g_durable];
		$make_speed=round($wp_num*$wog_arry["g_durable_time"]);
		$time+=$make_speed;
		$sql="insert into wog_group_job(g_id,p_id,j_type,wp_num,j_time)values($group_main[p_g_id],$user_id,3,$wp_num,$time)";
		$DB_site->query($sql);
		unset($group_ex,$group_main);
		if($npc_id == 0)
		{
			showscript("parent.act_gclick('wp','wp')");
		}
	}
	function group_wpup($user_id) // 造兵
	{
		global $DB_site,$lang,$_POST,$wog_arry;
		if(empty($_POST["wp"]))
		{
			alertWindowMsg($lang['wog_act_group_error1']);
		}
		if(empty($_POST["wp_".$_POST["wp"]]))
		{
			alertWindowMsg($lang['wog_main_error2']);
		}
		$wp_id=$_POST["wp"];
		$wp_num=$_POST["wp_".$_POST["wp"]];
		if(!is_numeric($wp_num) || preg_match("/[^0-9]/",$wp_num))
		{
			alertWindowMsg($lang['wog_main_error2']);
		}
		$group_main=$DB_site->query_first("select b.p_g_id,a.lv,a.p4 from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$this->group_permissions_chk($group_main,"p4");

		$wp_make=$DB_site->query_first("select wp_make from wog_weapon_main where wp_id=$wp_id and wp_npc=0");
		if(!$wp_make)
		{
			alertWindowMsg($lang['wog_act_group_error1']);
		}
		$sql="select j_id from wog_group_build_job where p_id=$user_id limit 1";
		$job_count=$DB_site->query_first($sql);
		if($job_count)
		{
			alertWindowMsg($lang['wog_act_group_error16']);
		}
		$sql="select j_id from  wog_group_job where p_id=$user_id and j_type > 0 limit 1";
		$job_count=$DB_site->query_first($sql);
		if($job_count)
		{
			alertWindowMsg($lang['wog_act_group_error5']);
		}
		$sql="select count(j_id) as j_id from  wog_group_job where p_id=$user_id and j_type=0";
		$job_count=$DB_site->query_first($sql);
		if($job_count)
		{
			if($job_count[j_id] >= $wog_arry['g_job_max'])
			{
				alertWindowMsg(sprintf($lang['wog_act_group_error4'],$wog_arry['g_job_max']));
			}
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$time=time();
		$group_ex=$DB_site->query_first("select ex_1,ex_2,ex_3,ex_4,ex_5,ex_6,ex_7,ex_8,ex_9,ex_10,ex_11,ex_12 from wog_group_exchange where g_id=".$group_main[p_g_id]." for update");
		$wp_main=$DB_site->query("select ex_id,ex_num from wog_weapon_list where wp_id=$wp_id");
		$ex_need=0;
		$sql_need="";
		while($wp_mains=$DB_site->fetch_array($wp_main))
		{
			$ex_need=$wp_mains[ex_num]*$wp_num;
			if($group_ex["ex_".$wp_mains[ex_id]] < $ex_need){alertWindowMsg($lang['wog_act_group_error34']);}
			$ex_need=$group_ex["ex_".$wp_mains[ex_id]]-$ex_need;
			$sql_need.=",ex_".$wp_mains[ex_id]."=".$ex_need;
		}
		unset($wp_mains);
		$DB_site->free_result($wp_main);
		$sql_need=substr($sql_need,1,strlen($sql_need));
		$sql="update wog_group_exchange set $sql_need where g_id=".$group_main[p_g_id];
		$DB_site->query($sql);
		$make_speed=$wp_make[wp_make]*$wp_num*$wog_arry["g_make_time"];
		$time+=$make_speed;
		$sql="insert into wog_group_job(g_id,p_id,j_type,j_class,weapon_".$wp_id.",j_time)values($group_main[p_g_id],$user_id,0,$wp_id,$wp_num,$time)";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		unset($group_ex,$group_main);
		showscript("parent.act_gclick('ex','ex')");
	}
	function group_exup($user_id)
	{
		global $DB_site,$lang,$_POST;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_main_error2']);
		}
		if(empty($_POST["temp_id2"]))
		{
			alertWindowMsg($lang['wog_act_syn_error6']);
		}
		$item_id=$_POST["temp_id2"];
		$item_num=$_POST["temp_id"];
		if(!is_numeric($item_num) || preg_match("/[^0-9]/",$item_num))
		{
			alertWindowMsg($lang['wog_main_error2']);
		}
		$group_main=$DB_site->query_first("select a.p_g_id,b.ex_1,b.ex_2,b.ex_3,b.ex_4,b.ex_5,b.ex_6,b.ex_7,b.ex_8,b.ex_9,b.ex_10,b.ex_11,b.ex_12 from wog_player a,wog_group_point b where a.p_id=".$user_id." and b.g_id=a.p_g_id");
		if(!$group_main[0])
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$ex_main=$DB_site->query_first("select ex_id,el_amount from wog_exchange_list where p_id=$user_id and el_id=$item_id for update");
		if($ex_main[el_amount] < $item_num)
		{
			alertWindowMsg($lang['wog_act_group_error34']);
		}
		if($item_num==$ex_main[el_amount])
		{
			$sql="delete from wog_exchange_list where el_id=".$item_id;
		}
		else
		{
			$sql="update wog_exchange_list set el_amount=el_amount-".$item_num." where el_id=".$item_id;
		}
		$DB_site->query($sql);
		$sql="update wog_group_exchange set ex_".$ex_main[ex_id]."=ex_".$ex_main[ex_id]."+".$item_num." where g_id=".$group_main[p_g_id];
		$DB_site->query($sql);
		$point = $group_main["ex_".$ex_main[ex_id]]*$item_num;
		if($point > 0)
		{
			$sql="update wog_group_member_point set p_point=p_point+".$point." where p_id=$user_id and g_id=$group_main[p_g_id]";
			$DB_site->query($sql);
		}
		$DB_site->query_first("COMMIT");
		unset($ex_main,$group_main);
		showscript("parent.act_click('exchange','list')");
	}
	function group_creat($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_group_noname']);
		}
		if(preg_match("/[<>'\", ;]/", $_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_errword']);
		}
		$_POST["temp_id"]=addslashes(htmlspecialchars($_POST["temp_id"]));
		$len=strlen($_POST["temp_id"]);
		if($len>24)
		{
			alertWindowMsg(sprintf($lang['wog_act_group_error29'],24));
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$group=$DB_site->query_first("select g_name from wog_group_main where g_name='".$_POST["temp_id"]."' for update");
		if($group)
		{
			alertWindowMsg($lang['wog_act_group_used']);
		}
		$have_price=$DB_site->query_first("select p_money,p_g_id,p_birth,p_name from wog_player where p_id=".$user_id);
		if($have_price[0]<$wog_arry["creat_group"])
		{
			alertWindowMsg($lang['wog_act_nomoney']);
		}
		if($have_price[1]>0)
		{
			alertWindowMsg($lang['wog_act_group_notcreat']);
		}
		$sum=0;
		while($sum < 20)
		{
			$sum++;
			$area_x=rand(0,300);
			$area_y=rand(0,300);
			$area=$DB_site->query_first("select g_area_x,g_area_y from wog_group_main where g_area_x=$area_x and g_area_y=$area_y");
			if(!$area){break;}
		}
		if($sum >= 20)
		{
			alertWindowMsg($lang['wog_act_group_error6']);
		}
		$time=time();
		$g_area_type=rand(1,6);
		$g_weather=rand(1,5);
		$DB_site->query("insert into wog_group_main(g_name,g_peo,g_area,g_area_x,g_area_y,g_area_type,g_weather,g_create_time,p_name)values('".$_POST["temp_id"]."',1,".$have_price[2].",$area_x,$area_y,$g_area_type,$g_weather,$time,'".$have_price[p_name]."')");
		$g_id=$DB_site->insert_id();
		$DB_site->query("insert into wog_group_weapon(g_id,g_durable,g_durable_max)values($g_id,$wog_arry[g_durable],$wog_arry[g_durable])");
		//$DB_site->query("insert into wog_group_exchange(g_id)values($g_id)");
		$DB_site->query("insert into wog_group_exchange(g_id,ex_1,ex_2,ex_3,ex_4,ex_5,ex_6,ex_7,ex_8,ex_9,ex_10,ex_11,ex_12)values($g_id,100,100,100,100,100,100,100,100,100,100,100,100)");
		$DB_site->query("update wog_player set p_g_id=".$g_id.",p_money=p_money-".$wog_arry["creat_group"]." where p_id=".$user_id);
		$DB_site->query("update wog_group_map set g_id=".$g_id." where x=$area_x and y=$area_y");
		$DB_site->query("insert wog_group_point(g_id)values($g_id)");
		$DB_site->query("insert wog_group_build (g_id)values($g_id)");
		$sql="insert wog_group_permissions(g_id,name,p1,p2,p3,p4,p5,p6,p7,lv)values(".$g_id.",'會長',1,1,1,1,1,1,1,1)";
		$DB_site->query($sql);
		$per_id=$DB_site->insert_id();
		$DB_site->query("insert wog_group_member_point(g_id,p_id,p_permissions)values($g_id,$user_id,$per_id)");
		$sql="insert wog_group_permissions(g_id,name,p1,p2,p3,p4,p5,p6,p7,lv)values(".$g_id.",'副會長',1,1,1,1,1,1,1,2)";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		showscript("parent.job_end(10);parent.p_group='".$_POST["temp_id"]."'");
	}
	function group_get_save_member($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		if(empty($_POST["temp_id2"]))
		{
			alertWindowMsg($lang['wog_act_group_nosel']);
		}
		$_POST["temp_id2"]=htmlspecialchars($_POST["temp_id2"]);
		
		$group_main=$DB_site->query_first("select a.g_id,a.lv,a.p7 from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$this->group_permissions_chk($group_main,"p7");
		$group=$DB_site->query_first("select g_id,p_id from wog_group_join where g_j_id=".$_POST["temp_id2"]);
		if(!$group)
		{
			alertWindowMsg($lang['wog_act_group_cancel']);
		}
		if($group_main["g_id"]!=$group["g_id"])
		{
			alertWindowMsg($lang['wog_act_group_notset']);
		}
		$p=$DB_site->query_first("select p_g_id from wog_player where p_id=".$group["p_id"]);
		if($p["p_g_id"]>0)
		{
			alertWindowMsg($lang['wog_act_group_havgroup']);
		}
		$p_count=$DB_site->query_first("select g_peo from wog_group_main  where g_id=".$group["g_id"]);
		$sql="select b_29 from wog_group_build where g_id=".$group["g_id"];
		$build=$DB_site->query_first($sql);
		$maxpeo=$wog_arry["g_maxpeo"];
		if($build[b_29]>0)
		{
			$sql="select lv from wog_build_main where b_id =".$build[b_29];
			$build=$DB_site->query_first($sql);
			$maxpeo+=($build[lv]*2);
		}
		if($p_count[0]>=$maxpeo)
		{
			alertWindowMsg($lang['wog_act_group_ful']);
		}
		$p_count=$DB_site->query_first("select count(p_id) as id from wog_player where p_g_id=".$group["g_id"]);
		$p_count[id]+=1;
		$g_lv=1;
		foreach($wog_arry["g_lv"] as $key=>$value)
		{
			if($value >= $p_count[id])
			{
				$g_lv=$key;
				break;
			}
		}
		$DB_site->query("update wog_player set p_g_id=".$group["g_id"]." where p_id=".$group["p_id"]);
		$DB_site->query("update wog_group_main set g_peo=".$p_count[id].",g_lv=$g_lv where g_id=".$group["g_id"]);
		$DB_site->query("insert wog_group_member_point(g_id,p_id)values($group[g_id],$group[p_id])");
		$DB_site->query("delete from wog_group_join where p_id=".$group["p_id"]);
		unset($group,$p_count,$p,$group_main);
		$this->group_get_member($user_id);
	}
	function group_get_member($user_id)
	{
		global $DB_site,$_POST,$lang;
		$DB_site->query("delete from wog_group_join where g_j_dateline < ".(time()-(7*24*60*60)));
		$group_main=$DB_site->query_first("select a.g_id,a.lv,a.p7 from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$this->group_permissions_chk($group_main,"p7");
		$group=$DB_site->query("select a.g_j_id,b.p_name,b.p_lv,c.ch_name from wog_group_join a,wog_player b,wog_character c where a.g_id=".$group_main["g_id"]." and a.p_id=b.p_id and b.ch_id=c.ch_id order by a.g_j_dateline asc");
		$temp_s="";
		while($groups=$DB_site->fetch_array($group))
		{
			$temp_s.=";".$groups[0].",".$groups[1].",".$groups[2].",".$groups[3];
		}
		$DB_site->free_result($group);
		unset($groups);
		$temp_s=substr($temp_s,1);
		showscript("parent.group_join_list('$temp_s')");
		unset($temp_s);
	}
	//會員狀態
	function group_p_list($user_id)
	{
		global $DB_site,$lang;
		$group_main=$DB_site->query_first("select a.g_id,a.g_name,a.g_area_x,a.g_area_y from wog_group_main a,wog_player b where a.g_id=b.p_g_id and b.p_id=".$user_id);
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$ma=array();
		$u_g_a=$DB_site->query("select a.p_id,a.p_name,a.p_lv,b.p_point,b.p_permissions from wog_player a,wog_group_member_point b where a.p_g_id=".$group_main[0]." and b.p_id=a.p_id");
		while($u_g_as=$DB_site->fetch_array($u_g_a))
		{

			$ma[]=$u_g_as["p_id"].",".$u_g_as["p_name"].",".$u_g_as["p_lv"].",".$u_g_as["p_point"].",".$u_g_as["p_permissions"];
		}
		$pa=array();
		$u_g_a=$DB_site->query("select a.id,a.name from wog_group_permissions a where a.g_id=".$group_main[0]);
		while($u_g_as=$DB_site->fetch_array($u_g_a))
		{

			$pa[]=$u_g_as["id"].",".$u_g_as["name"];
		}
		$DB_site->free_result($u_g_a);
		unset($u_g_as);
		showscript("parent.group_p_list('".implode(";",$ma)."','".implode(";",$pa)."','".$group_main[g_name]."(".$group_main[g_area_x]."-".$group_main[g_area_y].")')");
		unset($ma,$pa,$group_main);
	}
	function group_mod_member($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		if(empty($_POST["p_id"]))
		{
			alertWindowMsg($lang['wog_act_group_nosel']);
		}
		$p_id=$_POST["p_id"];
		$group_main=$DB_site->query_first("select a.g_id,a.lv,a.p7 from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$this->group_permissions_chk($group_main,"p7");
		switch($_POST["mod_type"]){
			case 1:
				if($group_main["lv"]==1 && $p_id==$user_id)
				{
					$DB_site->query("delete from wog_group_main where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_event where g_b_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_book where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_weapon where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_join where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_job where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_exchange where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_depot where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_build where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_build_job where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_detect where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_fight_book where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_member_book where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_member_point where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_point where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_mission_log where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_depot_msg where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_market where g_id=".$group_main["g_id"]);
					$DB_site->query("delete from wog_group_permissions where g_id=".$group_main["g_id"]);
					$DB_site->query("update wog_group_map set g_id =0 where g_id =".$group_main["g_id"]);
					$DB_site->query("update wog_player set p_g_id=0 where p_g_id=".$group_main["g_id"]);
				}else
				{
					$check_p=$DB_site->query_first("select p_g_id from wog_player where p_g_id=".$group_main["g_id"]." and p_id=".$p_id);
					if(!$check_p)
					{
						alertWindowMsg($lang['wog_act_group_nosel']);
					}
					$DB_site->query("update wog_group_main set g_peo=g_peo-1 where g_id=".$group_main["g_id"]);
					$DB_site->query("update wog_player set p_g_id=0 where p_id=".$p_id);
					$DB_site->query("delete from wog_group_job where p_id=".$p_id);
					$DB_site->query("delete from wog_group_member_point where p_id=".$p_id);
					$DB_site->query("delete from wog_group_build_job where p_id=".$p_id);
				}
				break;
			case 2:
				if($p_id==$user_id)
				{
					alertWindowMsg($lang['wog_act_group_error32']);
				}
				$permissions=$_POST["permissions"];
				$sql="select lv from wog_group_permissions where id=".$permissions;
				$per=$DB_site->query_first($sql);
				if($per[lv]<$group_main["lv"])
				{
					alertWindowMsg($lang['wog_act_group_error31']);
				}
				if($per[lv]==1)
				{
					$sql="select p_name from wog_player where p_id=".$p_id;
					$p_name=$DB_site->query_first($sql);
					$sql="update wog_group_member_point set p_permissions=0 where p_id=".$user_id;
					$DB_site->query($sql);
					$sql="update wog_group_member_point set p_permissions=".$permissions." where p_id=".$p_id;
					$DB_site->query($sql);
					$sql="update wog_group_main set p_name='".$p_name["p_name"]."' where g_id=".$group_main["g_id"];
					$DB_site->query($sql);
				}else
				{
					$sql="update wog_group_member_point set p_permissions=".$permissions." where p_id=".$p_id;
					$DB_site->query($sql);					
				}
				break;
			default:
				;
		} // switch
		unset($temp_s,$group_main,$u_g_a,$p_name);
		$this->group_p_list($user_id);
	}
	function group_add($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		if(empty($_POST["g_id"]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$_POST["g_id"]=htmlspecialchars($_POST["g_id"]);
		$DB_site->query("delete from wog_group_join where g_j_dateline < ".(time()-(7*24*60*60)));
		$group=$DB_site->query_first("select p_g_id from wog_player where p_id=".$user_id);
		if($group[0]!=0)
		{
			$group_chk=$DB_site->query_first("select g_id from wog_group_main where g_id=".$group[0]);
			if($group_chk)
			{
				alertWindowMsg($lang['wog_act_group_havgroup']);
			}else
			{
				$DB_site->query("update wog_player set p_g_id=0 where p_id=".$user_id);
			}
			unset($group_chk);
		}
		$group=$DB_site->query_first("select g_peo from wog_group_main  where g_id=".$_POST["g_id"]);
		$sql="select b_29 from wog_group_build where g_id=".$_POST["g_id"];
		$build=$DB_site->query_first($sql);
		$maxpeo=$wog_arry["g_maxpeo"];
		if($build[b_29]>0)
		{
			$sql="select lv from wog_build_main where b_id =".$build[b_29];
			$build=$DB_site->query_first($sql);
			$maxpeo+=($build[lv]*2);
		}
		if($group[0]>=$maxpeo)
		{
			alertWindowMsg($lang['wog_act_group_ful']);
		}
		$group=$DB_site->query_first("select g_j_id from wog_group_join where p_id=".$user_id." and g_id=".$_POST["g_id"]);
		if($group)
		{
			alertWindowMsg($lang['wog_act_group_post']);
		}
		$DB_site->query("insert into wog_group_join(g_id,p_id,g_j_dateline)values(".$_POST["g_id"].",".$user_id.",".time().")");
		showscript("parent.job_end(14)");
		unset($group);
	}
	function group_permissions_view($user_id)
	{
		global $DB_site,$lang;
		$group_main=$DB_site->query_first("select a.g_id,a.lv from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nolv']);
		}
		/*
		$group_main=$DB_site->query_first("select a.g_id,a.p_permissions from wog_group_member_point a,wog_player b where b.p_id=".$user_id." and b.p_id=".$user_id." and a.g_id=b.p_g_id");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$group_permissions=$DB_site->query_first("select a.lv from wog_group_permissions a where a.id=".$group_main[p_permissions]);
		if(!$group_permissions)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		*/
		if($group_main[lv]<1 || $group_main[lv]>2)
		{
			alertWindowMsg($lang['wog_act_group_nolv']);
		}
		$permissions=$DB_site->query("select a.id,a.lv,a.name,a.p1,a.p2,a.p3,a.p4,a.p5,a.p6,a.p7 from wog_group_permissions a where a.g_id=".$group_main[g_id]);
		$psa=array();
		while($ps=$DB_site->fetch_array($permissions))
		{
			$psa[]=$ps[id].",".$ps[name].",".$ps[lv].",".$ps[p1].",".$ps[p2].",".$ps[p3].",".$ps[p4].",".$ps[p5].",".$ps[p6].",".$ps[p7];
		}
		$DB_site->free_result($permissions);
		unset($ps);
		showscript("parent.group_permissions('".implode(';',$psa)."')");
		unset($temp_s,$group_main);
	}
	function group_permissions_save($user_id)
	{
		global $DB_site,$lang,$_POST;
		$group_main=$DB_site->query_first("select a.g_id,a.lv,a.p7 from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$this->group_permissions_chk($group_main,"p7");
		$num=$_POST["num"];
		for($i=0;$i<$num;$i++)
		{
			$p2=empty($_POST["p_2_".$i])?"0":"1";
			$p3=empty($_POST["p_3_".$i])?"0":"1";
			$p4=empty($_POST["p_4_".$i])?"0":"1";
			$p5=empty($_POST["p_5_".$i])?"0":"1";
			$p6=empty($_POST["p_6_".$i])?"0":"1";
			$p7=empty($_POST["p_7_".$i])?"0":"1";
			$p8=empty($_POST["p_8_".$i])?"0":"1";
			$sql="update wog_group_permissions set name='".$_POST["p_1_".$i]."'
				,p1=".$p2.",p2=".$p3.",p3=".$p4.",p4=".$p5.",p5=".$p6."
				,p6=".$p7.",p7=".$p8." where id=".$_POST["p_0_".$i];
			$DB_site->query($sql);
		}
		unset($num,$group_main,$group_permissions);
		showscript("parent.wog_message_box(11,0,1);");
		//$this->group_permissions_view($user_id);
	}
	function group_permissions_add($user_id)
	{
		global $DB_site,$lang,$_POST,$wog_arry;
		if(empty($_POST["p_1"]))
		{
			alertWindowMsg($lang['wog_act_group_error29']);
		}
		$p1=$_POST["p_1"];
		$p2=empty($_POST["p_2"])?"0":"1";
		$p3=empty($_POST["p_3"])?"0":"1";
		$p4=empty($_POST["p_4"])?"0":"1";
		$p5=empty($_POST["p_5"])?"0":"1";
		$p6=empty($_POST["p_6"])?"0":"1";
		$p7=empty($_POST["p_7"])?"0":"1";
		$p8=empty($_POST["p_8"])?"0":"1";
		
		$group_main=$DB_site->query_first("select a.g_id,a.lv,a.p7 from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$this->group_permissions_chk($group_main,"p7");
		$sql="select count(id) as id from wog_group_permissions where g_id=".$group_main[g_id];
		$group_permissions=$DB_site->query_first($sql);
		if($wog_arry["g_permissions_max"] <= $group_permissions[id])
		{
			alertWindowMsg(sprintf($lang['wog_act_group_error29'],$wog_arry["g_permissions_max"]));
		}
		$sql="insert wog_group_permissions(g_id,name,p1,p2,p3,p4,p5,p6,p7)values(".$group_main[g_id].",'".$p1."',".$p2.",".$p3.",".$p4.",".$p5.",".$p6.",".$p7.",".$p8.")";
		$DB_site->query($sql);
		unset($group_permissions,$group_main);
		$this->group_permissions_view($user_id);
	}
	function group_permissions_del($user_id)
	{
		global $DB_site,$lang,$_POST,$wog_arry;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_group_nosel']);
		}
		$id=$_POST["temp_id"];
		$group_main=$DB_site->query_first("select a.g_id,a.lv,a.p7 from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$this->group_permissions_chk($group_main,"p7");
		$sql="delete from wog_group_permissions where id=".$id;
		$DB_site->query($sql);
		$sql="update wog_group_member_point set p_permissions=0 where p_permissions=".$id;
		$DB_site->query($sql);
		$this->group_permissions_view($user_id);
	}
	function group_permissions_chk($a1,$a2)
	{
		global $lang;
		if($a1["lv"]!=1 && $a1[$a2]!=1)
		{
			alertWindowMsg($lang['wog_act_group_error31']);
		}		
	}
	function group_area_main($user_id)
	{
		global $DB_site,$lang;
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$g_id=$group_main[p_g_id];
		if(rand(1,50)==1)
		{
			$g_weather=rand(1,5);
			$sql="update wog_group_main set g_weather=$g_weather where g_id=$group_main[p_g_id]";
			$DB_site->query($sql);
		}
		$sql="select g_name,g_area_x,g_area_y,g_area_type,g_weather,IFNULL(g_img,''),g_peo,g_lv,g_formation,g_trap,0,g_item from wog_group_main where g_id=$g_id";
		$a1=$DB_site->query_first($sql);
		$sql="select g_durable,g_durable_max from wog_group_weapon where g_id=$g_id";
		$temp=$DB_site->query_first($sql);
		$a1[10]=$temp[g_durable]."/".$temp[g_durable_max];
		$a1[0]=$a1[g_name];

		$sql="select b_15,b_16,b_17,b_18,b_19,b_20,b_21,b_22,b_23,b_24,b_25,b_26 from wog_group_build where g_id=$g_id";
		$temp=$DB_site->query_first($sql);
		$sql="select b_id,type,name from wog_build_main where b_id in ($temp[b_15],$temp[b_16],$temp[b_17],$temp[b_18],$temp[b_19],$temp[b_20],$temp[b_21],$temp[b_22],$temp[b_23],$temp[b_24],$temp[b_25],$temp[b_26])";
		$temp=$DB_site->query($sql);
		$a2=array();
		$a3=array();
		while($temps=$DB_site->fetch_array($temp))
		{
			if($temps[type]==2)
			{
				$a2[]=$temps[b_id].";".$temps[name];
			}else
			{
				$a3[]=$temps[b_id].";".$temps[name];
			}
		}
		$DB_site->free_result($temp);
		unset($temps);
		$g_cp=0;
		$sql="select s1_update,s2_update,s3_update from wog_group_cp where g_id=".$group_main[p_g_id];
		$cp_main=$DB_site->query_first($sql);
		$time=time();
		$a4=array();
		if($cp_main)
		{
			$g_cp=1;
			$temp=$cp_main[s1_update]-$time;
			if($temp<0){$temp=0;}
			$a4[0]=$temp;
			$temp=$cp_main[s2_update]-$time;
			if($temp<0){$temp=0;}
			$a4[1]=$temp;
			$temp=$cp_main[s3_update]-$time;
			if($temp<0){$temp=0;}
			$a4[2]=$temp;
		}
		$temp_h=date("H",$time);
		$temp_w=date("w",$time);
		$point=0;
		//if($temp_h >=20 && $temp_h <=22 && $temp_w==6){
		if(true){
			$sql="select g_point from wog_group_point_list where g_id=".$group_main[p_g_id];
			$cp_main=$DB_site->query_first($sql);
			if($cp_main)
			{
				$point=$cp_main[g_point];
			}
		}
		showscript("parent.group_area_main(".toJSON($a1).",'".implode(",",$a2)."','".implode(",",$a3)."',$g_cp,'".implode(",",$a4)."',$point)");
	}
	function group_ch_main($user_id)
	{
		global $DB_site,$lang,$_POST;
		$group_main=$DB_site->query_first("select a.g_id,a.lv from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		//$this->group_permissions_chk($group_main,"p7");
		if($group_main["lv"]==1 || $group_main["lv"]==2)
		{
			switch($_POST["type"])
			{
				case "1":
					$DB_site->query("update wog_group_main set g_img='".$_POST["url"]."' where g_id=".$group_main["g_id"]);
				break;
				case "2":
					if(!empty($_POST["formation"]))
					{
						$sql="select b_id,main_id from wog_build_main where b_id=".$_POST["formation"];
						$b_main=$DB_site->query_first($sql);
						$sql="select b_".$b_main[main_id]." from wog_group_build where g_id=".$group_main["g_id"];
						$b_main=$DB_site->query_first($sql);
						if($_POST["formation"]==$b_main[0])
						{
							$DB_site->query("update wog_group_main set g_formation=".$_POST["formation"]." where g_id=".$group_main["g_id"]);
						}
					}
				break;
				case "3":
					if(!empty($_POST["trap"]))
					{
						$sql="select b_id,main_id from wog_build_main where b_id=".$_POST["trap"];
						$b_main=$DB_site->query_first($sql);
						$sql="select b_".$b_main[main_id]." from wog_group_build where g_id=".$group_main["g_id"];
						$b_main=$DB_site->query_first($sql);
						if($_POST["trap"]==$b_main[0])
						{
							$DB_site->query("update wog_group_main set g_trap=".$_POST["trap"]." where g_id=".$group_main["g_id"]);
						}
					}
				break;
				case "4":
					//if(!empty($_POST["item"]))
					//{
						$sql="select item_id,num from wog_group_item where g_id=".$group_main["g_id"]." and item_id=".$_POST["item"];
						$item=$DB_site->query_first($sql);
						if($item)
						{
							$DB_site->query("update wog_group_main set g_item=".$item[item_id]." where g_id=".$group_main["g_id"]);

							if($item[num]<=1)
							{
								$DB_site->query("delete from wog_group_item where g_id=".$group_main["g_id"]." and item_id=".$_POST["item"]);
							}
							else
							{
								$DB_site->query("update wog_group_item set num=num-1 where g_id=".$group_main["g_id"]." and item_id=".$_POST["item"]);
							}

						}
						else
						{
							$DB_site->query("update wog_group_main set g_item=0 where g_id=".$group_main["g_id"]);
						}
					//}
				break;
			}
		}
		else
		{
			unset($group_main);
			alertWindowMsg($lang['wog_act_group_nolv']);
		}
		unset($group_main);
		echo "<script language=\"JavaScript\">parent.p_s_close();parent.wog_message_box(11,0,1);</script>";
		$this->group_area_main($user_id);
		//showscript("parent.p_s_close();parent.wog_message_box(11,0,1)");
	}
	function group_item($user_id)
	{
		global $DB_site,$lang;
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$sql="select item_id,num from wog_group_item where g_id=$group_main[p_g_id]";
		$temp=$DB_site->query($sql);
		$a4=array();
		while($temps=$DB_site->fetch_array($temp))
		{
			$a4[]=$temps[item_id].";".$temps[num];
		}
		$DB_site->free_result($temp);
		unset($temps);
		showscript("parent.group_item('".implode(",",$a4)."')");
	}
	function group_area($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_group_nosel']);
		}
		$area_id=$_POST["temp_id"];
		$key=$_POST["key"];
		$time=time();
		$g_boss="0,1,2,3,4,6,7,8,9";
		$temp_h=date("H",$time);
		$temp_w=date("w",$time);
		/*
		if($temp_h >=20 && $temp_h <=22 && $temp_w==6){
			$g_boss.=",9";
		}
		*/
		if($temp_w==$wog_arry["g_boss_npc_day"])
		{
			//$npc_group=$DB_site->query("select g_id,g_name,g_boss from wog_group_main where g_npc=1 and g_break=1 and g_lost_time < $time limit 1");
			$g_boss.=",5";
		}
		if(!empty($key))
		{
			$sql_key=" a.g_name like '%".$key."%'";
		}else
		{
			$key="";
			$sql_key=" a.g_area=".$area_id;
		}
		$group_total=$DB_site->query_first("select count(a.g_id) as g_id from wog_group_main a where $sql_key and a.g_break =0 and g_boss in ($g_boss)");
		if(empty($_POST["page"]))
		{
			$_POST["page"]="1";
		}
		$spage=((int)$_POST["page"]*8)-8;

		$sql="select a.g_id,a.g_name,a.g_peo,a.g_area_x,a.g_area_y,a.g_area_type,a.p_name,a.g_create_time,g_weather 
		from wog_group_main a where $sql_key 
		and a.g_break =0 and a.g_boss in ($g_boss) ORDER BY a.g_id desc LIMIT ".$spage.",8";
		$group=$DB_site->query($sql);
		$temp_s="";
		while($groups=$DB_site->fetch_array($group))
		{
			$check_new_group=0;
			if($time-$groups[g_create_time] < $wog_arry["g_not_fight"])
			{
				$check_new_group=1;
			}
			$temp_s.=";".$groups[0].",".$groups[1].",".$groups[2].",".$groups[p_name].",".$groups[g_area_x]."-".$groups[g_area_y].",".$check_new_group.",".$groups[g_area_type].",".$groups[g_weather];
		}
		$DB_site->free_result($group);
		unset($groups,$p);
		$temp_s=substr($temp_s,1);
		showscript("parent.group_area_list($group_total[0],".$_POST["page"].",'$temp_s',$area_id,'$key')");
		unset($temp_s,$group_total);
	}
	function group_list($user_id)//公會列表
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		if(!empty($_POST["temp_id"]))
		{
			$area_id=$_POST["temp_id"];
			$key=$_POST["key"];
			$time=time();
			$g_boss="0,1,2,3,4,6,7,8,9";
			$temp_h=date("H",$time);
			$temp_w=date("w",$time);
			/*
			if($temp_h >=21 && $temp_h <=22 && $temp_w==6){
				$g_boss.=",9";
			}
			*/
			if(date("w",$time)==$wog_arry["g_boss_npc_day"])
			{
				//$npc_group=$DB_site->query("select g_id,g_name,g_boss from wog_group_main where g_npc=1 and g_break=1 and g_lost_time < $time limit 1");
				$g_boss.=",5";
			}
			if(!empty($key))
			{
				$sql_key=" a.g_name like '%".$key."%'";
			}else
			{
				$key="";
				$sql_key=" a.g_area=".$area_id;
			}
			$group_total=$DB_site->query_first("select count(a.g_id) as g_id from wog_group_main a where $sql_key and a.g_break =0 and g_boss in ($g_boss)");
			if(empty($_POST["page"]))
			{
				$_POST["page"]="1";
			}
			$spage=((int)$_POST["page"]*8)-8;
			$sql="select a.g_id,a.g_name,a.g_peo,a.g_area_x,a.g_area_y,a.g_area_type,a.p_name,a.g_create_time,g_weather 
			from wog_group_main a where $sql_key 
			and a.g_break =0 and a.g_boss in ($g_boss) ORDER BY a.g_id desc LIMIT ".$spage.",8";
			$group=$DB_site->query($sql); 
			$temp_s="";
			while($groups=$DB_site->fetch_array($group))
			{
				$check_new_group=0;
				if($time-$groups[g_create_time] < $wog_arry["g_not_fight"])
				{
					$check_new_group=1;
				}
				$temp_s.=";".$groups[g_id].",".$groups[g_name].",".$groups[g_peo].",".$groups[p_name].",".$groups[g_area_x]."-".$groups[g_area_y].",".$check_new_group.",".$groups[g_area_type].",".$groups[g_weather];
			}
			$DB_site->free_result($group);
			unset($groups,$p);
			$temp_s=substr($temp_s,1);
			showscript("parent.group_list($group_total[0],".$_POST["page"].",'$temp_s',$area_id,'$key')");
			unset($temp_s,$group_total);
		}
		else
		{
			showscript("parent.group_list(0,0,'',0,'$key')");
		}
	}
	function group_job_break($user_id) //移除作業
	{
		global $DB_site,$_POST,$lang;
		if(empty($_POST["temp_id"]) || empty($_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_group_nosel']);
		}
		$type=$_POST["temp_id"];
		$job_id=$_POST["pay_id"];
		switch($type)
		{
			case "1":
				$sql="select g_id,j_type,weapon_1,weapon_2,weapon_3,weapon_4,weapon_5,weapon_6,weapon_7,weapon_8,weapon_9,weapon_10,weapon_11,weapon_12 from wog_group_job where j_id=$job_id and p_id=$user_id";
				$job_main=$DB_site->query_first($sql);
				$return_page="";
				if($job_main)
				{
					switch($job_main[j_type])
					{
						case 0:
							$sql="delete from wog_group_job where j_id=$job_id and p_id=$user_id";
							$DB_site->query($sql);
							$return_page="ex";
							$return_page2="ex";
							break;
						case 4:
						case 3:
							$sql="delete from wog_group_job where j_id=$job_id and p_id=$user_id";
							$DB_site->query($sql);
							$return_page="wp";
							$return_page2="wp";
							break;
						case 1:
						case 2:
							$temp=",weapon_1=weapon_1+".$job_main[weapon_1].",weapon_2=weapon_2+".$job_main[weapon_2]
							.",weapon_3=weapon_3+".$job_main[weapon_3].",weapon_4=weapon_4+".$job_main[weapon_4].",weapon_5=weapon_5+".$job_main[weapon_5]
							.",weapon_6=weapon_6+".$job_main[weapon_6].",weapon_7=weapon_7+".$job_main[weapon_7].",weapon_8=weapon_8+".$job_main[weapon_8]
							.",weapon_9=weapon_9+".$job_main[weapon_9].",weapon_10=weapon_10+".$job_main[weapon_10].",weapon_11=weapon_11+".$job_main[weapon_11]
							.",weapon_12=weapon_12+".$job_main[weapon_12];
							$temp=substr($temp,1);
							$sql="update wog_group_weapon set ".$temp." where g_id=".$job_main[g_id];
							$DB_site->query($sql);
							$sql="delete from wog_group_job where j_id=$job_id and p_id=$user_id";
							$DB_site->query($sql);
							$return_page="wp";
							$return_page2="wp";
							break;
					}
				}
			break;
			case "2":
				$sql="delete from wog_group_build_job where j_id=$job_id and p_id=$user_id";
				$DB_site->query($sql);
				$return_page="build_list";
				$return_page2="build";
			break;
		}
		showscript("parent.act_gclick('$return_page2','$return_page')");
	}
	function group_job_wp($user_id) //查看job兵種內容
	{
		global $DB_site,$_POST,$lang;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_group_nosel']);
		}
		$job_id=$_POST["temp_id"];
		$sql="select weapon_1,weapon_2,weapon_3,weapon_4,weapon_5,weapon_6,weapon_7,weapon_8,weapon_9
		 from wog_group_job where j_id=$job_id";
		$wp=$DB_site->query_first($sql);
		$s="";
		if($wp)
		{
			$s=$wp["weapon_1"].",".$wp["weapon_2"].",".$wp["weapon_3"].",".$wp["weapon_4"].",".$wp["weapon_5"].",".$wp["weapon_6"].",".$wp["weapon_7"].",".$wp["weapon_8"].",".$wp["weapon_9"];
		}
		unset($wp);
		showscript("parent.wog_message_box('$s',2,1)");
	}
	function group_check_ex($user_id) //查看對方資源
	{
		global $DB_site,$_POST,$lang;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_group_nosel']);
		}
		$detect_id=$_POST["temp_id"];

		$group=$DB_site->query_first("select p_g_id from wog_player where p_id=".$user_id);
		if(empty($group[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$s="";
		$sql="select ex_1,ex_2,ex_3,ex_4,ex_5,ex_6,ex_7,ex_8,ex_9,ex_10,ex_11,ex_12,weapon_1,weapon_2,weapon_3,weapon_4,weapon_5,weapon_6,weapon_7,weapon_8,weapon_9,weapon_10,weapon_11,weapon_12,g_durable,g_durable_max from wog_group_detect where id=$detect_id and g_id=$group[p_g_id]";
		$detect=$DB_site->query_first($sql);
		if($detect)
		{
			$s.=$detect["ex_1"].",".$detect["ex_2"].",".$detect["ex_3"].",".$detect["ex_4"].",".$detect["ex_5"].",".$detect["ex_6"].",".$detect["ex_7"].",".$detect["ex_8"].",".$detect["ex_9"].",".$detect["ex_10"].",".$detect["ex_11"].",".$detect["ex_12"];
			$s.=",".$detect["weapon_1"].",".$detect["weapon_2"].",".$detect["weapon_3"].",".$detect["weapon_4"].",".$detect["weapon_5"].",".$detect["weapon_6"].",".$detect["weapon_7"].",".$detect["weapon_8"].",".$detect["weapon_9"].",".$detect["weapon_10"].",".$detect["weapon_11"].",".$detect["weapon_12"].",".$detect["g_durable"].",".$detect["g_durable_max"];
		}
		unset($detect);

		showscript("parent.wog_message_box('$s',2,3)");
	}
	function group_news($user_id)
	{
		global $DB_site,$lang,$wog_arry;
		$group_main=$DB_site->query_first("select a.g_id from wog_group_main a,wog_player b where a.g_id=b.p_g_id and b.p_id=".$user_id);
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$time=time()-$wog_arry["g_news"];
		$DB_site->query("delete from wog_group_event where g_b_dateline < $time");
		$DB_site->query("delete from wog_group_detect where detect_time < $time");
		$DB_site->query("delete from wog_group_fight_book where datetime < $time");
		$sql="select g_b_body,g_b_dateline from wog_group_event where g_b_id=".$group_main[g_id]." order by g_b_inid desc LIMIT 30";
		$pack=$DB_site->query($sql);
		$s1="";
		$s2="";
		while($packs=$DB_site->fetch_array($pack))
		{
			$s1=$s1.";".$packs[g_b_body];
			$s2=$s2.";".set_date($packs[g_b_dateline]);
		}
		$s1=substr($s1,1);
		$s2=substr($s2,1);
		$DB_site->free_result($pack);
		showscript("parent.system_view('$s1','$s2',1)");
		unset($s1,$s2,$packs);
	}
	function group_fight_book($user_id)
	{
		global $DB_site,$lang,$_POST;
		$group_main=$DB_site->query_first("select a.g_id from wog_group_main a,wog_player b where a.g_id=b.p_g_id and b.p_id=".$user_id);
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$sql="select g_fight_book from wog_group_fight_book where g_id=".$group_main[g_id]." and id=".$_POST["temp_id"];
		$pack=$DB_site->query_first($sql);
		showscript("parent.wog_message_box('$pack[0]',0)");
		unset($pack);
	}
	function group_book_view($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		$group=$DB_site->query_first("select p_g_id from wog_player where p_id=".$user_id);
		if($group['p_g_id']==0)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$g_id=$group['p_g_id'];
		$group=$DB_site->query_first("select g_book from wog_group_book where g_id=".$g_id);
		$temp=str_replace("\r\n","[n]",$group[0]);

		$book_total=$DB_site->query_first("select count(a.id) as id from wog_group_member_book a where a.g_id=".$g_id);
		if(empty($_POST["page"]))
		{
			$_POST["page"]="1";
		}
		$spage=((int)$_POST["page"]*8)-8;

		$DB_site->query("delete from wog_group_member_book where g_id=".$g_id." and datetime < ".(time()-(15*24*60*60)));
		$group=$DB_site->query("select p_name,g_book,datetime,i_img from wog_group_member_book where g_id=".$g_id." order by id desc LIMIT ".$spage.",8");
		while($groups=$DB_site->fetch_array($group))
		{
			$groups[g_book]=str_replace("\r\n","[n]",$groups[g_book]);
			$temp2.=";".$groups[p_name].",".$groups[g_book].",".set_date($groups[datetime]).",".$groups[i_img];
		}
		if(!empty($temp2)){$temp2=substr($temp2,1);}
		showscript("parent.group_book_view('$temp','$temp2',$book_total[0],".$_POST["page"].")");
		unset($group,$book_total);
	}
	function group_book_save($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_nodata']);
		}
		$temp=htmlspecialchars($_POST["temp_id"]);
		if(strlen($temp) > 800)
		{
			alertWindowMsg($lang['wog_act_group_long']);
		}
		$group_main=$DB_site->query_first("select a.g_id,a.lv from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		if($group_main["lv"]!=1 && $group_main["lv"]!=2)
		{
			alertWindowMsg($lang['wog_act_group_nolyadmin']);
		}
		$g_id=$group_main['g_id'];
		$group_main=$DB_site->query_first("select g_id from wog_group_book where g_id=".$g_id);
		$temp=str_replace(";","；",$temp);
		$temp=str_replace(",","，",$temp);
		if($group_main)
		{
			$DB_site->query("update wog_group_book set g_book='".$temp."' where g_id=".$g_id);
		}else
		{
			$DB_site->query("insert wog_group_book(g_id,g_book)values(".$g_id.",'".$temp."')");
		}
		unset($group_main,$temp);
		$this->group_book_view($user_id);
	}
	function group_memberbook_save($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_nodata']);
		}
		$temp=htmlspecialchars($_POST["temp_id"]);
		if(strlen($temp) > 3000)
		{
			alertWindowMsg($lang['wog_act_group_long']);
		}
		$group_main=$DB_site->query_first("select a.g_id,b.p_name,b.i_img,b.p_img_url,b.p_img_set from wog_group_main a,wog_player b where a.g_id=b.p_g_id and b.p_id=".$user_id);
		if(!$group_main)
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$g_id=$group_main['g_id'];
		$p_name=$group_main['p_name'];
		if($group_main["p_img_set"]==1)
		{
			$group_main["i_img"]=$group_main["p_img_url"];
		}
		$i_img=$group_main['i_img'];
		/*
		$temp = str_replace("<", "&lt;", $temp);
		$temp = str_replace(">", "&gt;", $temp);
		$temp = str_replace("'", "&acute;", $temp);
		$temp = str_replace(",", "&cedil;", $temp);
		*/
		$temp=str_replace(";","；",$temp);
		$temp=str_replace(",","，",$temp);
		$DB_site->query("insert wog_group_member_book(g_id,g_book,p_name,i_img,datetime)values(".$g_id.",'".$temp."','".$p_name."','$i_img',".time().")");
		unset($group_main,$temp);
		$this->group_book_view($user_id);
	}
	function group_del($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		
		$group_main=$DB_site->query_first("select a.g_id,a.lv from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions"); 
		if(!$group_main)
		{
			$group_main["lv"]=0;
		}
		if($group_main["lv"]==1)
		{
			$DB_site->query("delete from wog_group_main where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_event where g_b_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_book where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_weapon where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_join where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_job where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_exchange where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_depot where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_build where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_build_job where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_detect where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_fight_book where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_member_book where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_member_point where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_point where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_mission_log where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_depot_msg where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_market where g_id=".$group_main["g_id"]);
			$DB_site->query("delete from wog_group_permissions where g_id=".$group_main["g_id"]);
			$DB_site->query("update wog_group_map set g_id =0 where g_id =".$group_main["g_id"]);
			$DB_site->query("update wog_player set p_g_id=0 where p_g_id=".$group_main["g_id"]);
		}else
		{
			if(empty($group_main["g_id"]))
			{
				$sql="select p_g_id from wog_player where p_id=".$user_id;
				$group_main=$DB_site->query_first($sql);
				$group_main["g_id"]=$group_main[p_g_id];
			}
			$p_count=$DB_site->query_first("select count(p_id) as id from wog_player where p_g_id=".$group_main["g_id"]);
			$p_count[id]-=1;
			$g_lv=1;
			foreach($wog_arry["g_lv"] as $key=>$value)
			{
				if($value >= $p_count[id])
				{
					$g_lv=$key;
					break;
				}
			}
			$DB_site->query("update wog_group_main set g_peo=".$p_count[id].",g_lv=$g_lv where g_id=".$group_main["g_id"]);
			$DB_site->query("update wog_player set p_g_id=0 where p_id=".$user_id);
			$DB_site->query("delete from wog_group_job where p_id=".$user_id);
			$DB_site->query("delete from wog_group_build_job where p_id=".$user_id);
			$DB_site->query("delete from wog_group_member_point where p_id=".$user_id);
		}
		showscript("parent.p_group='';parent.job_end(15)");
		unset($group);
	}
	function group_npc_restart()
	{
		global $DB_site,$wog_arry;
		$time=time();
		$g_boss="0,1,2,3,4,6,7,8";
		if(date("w",$time)==$wog_arry["g_boss_npc_day"])
		{
			$g_boss.=",5";
		}
		$npc_group=$DB_site->query("select g_id,g_name,g_boss from wog_group_main where g_npc=1 and g_break=1 and g_lost_time < $time and g_boss in ($g_boss) limit 1");
		while($npc_groups=$DB_site->fetch_array($npc_group))
		{
			$this->group_creat_npc($npc_groups);
		}
		$DB_site->free_result($npc_group);
		unset($npc_groups);
	}
	function group_creat_npc($npc_groups)
	{
		global $DB_site,$lang,$wog_arry;
		$area_x=rand(0,300);
		$area_y=rand(0,300);
		while($sum < 50)
		{
			$sum++;
			$area_x=rand(0,300);
			$area_y=rand(0,300);
			$area=$DB_site->query_first("select g_area_x,g_area_y from wog_group_main where g_area_x=$area_x and g_area_y=$area_y");
			if(!$area){break;}
		}
		$wp=array();
		$ex=array();
		switch($npc_groups[g_boss]){
			case 8: //活動用npc
				for($i=1;$i<10;$i++)
				{
					$wp["weapon_".$i]=rand(8000,15000);
				}
				for($i=1;$i<13;$i++)
				{
					$ex["ex_".$i]=rand(4000,8000);
				}
				$sql="select b_id from wog_build_main where lv between 4 and 7 and type=2 and type_class >0 ORDER BY RAND() limit 1";
				$g_trap=$DB_site->query_first($sql);
				$sql="select b_id from wog_build_main where lv between 4 and 7 and type=3 ORDER BY RAND() limit 1";
				$g_formation=$DB_site->query_first($sql);
				break;
			case 0://普通NPC
				for($i=1;$i<10;$i++)
				{
					$wp["weapon_".$i]=rand(0,6000);
				}
				for($i=1;$i<13;$i++)
				{
					$ex["ex_".$i]=rand(0,20000);
				}
				$sql="select b_id from wog_build_main where lv between 3 and 5 and type=2 and type_class >0 ORDER BY RAND() limit 1";
				$g_trap=$DB_site->query_first($sql);
				$sql="select b_id from wog_build_main where lv between 3 and 5 and type=3 ORDER BY RAND() limit 1";
				$g_formation=$DB_site->query_first($sql);
				break;
			case 1:
				for($i=1;$i<10;$i++)
				{
					$wp["weapon_".$i]=rand(0,5000);
				}
				$wp["weapon_10"]=50000;
				for($i=1;$i<13;$i++)
				{
					$ex["ex_".$i]=rand(3000,90000);
				}
				$sql="select b_id from wog_build_main where lv between 6 and 8 and type=2 and type_class >0 ORDER BY RAND() limit 1";
				$g_trap=$DB_site->query_first($sql);
				$sql="select b_id from wog_build_main where lv between 6 and 8 and type=3 ORDER BY RAND() limit 1";
				$g_formation=$DB_site->query_first($sql);
				break;
			case 2:
				for($i=1;$i<10;$i++)
				{
					$wp["weapon_".$i]=rand(0,5000);
				}
				$wp["weapon_11"]=50000;
				for($i=1;$i<13;$i++)
				{
					$ex["ex_".$i]=rand(3000,70000);
				}
				$sql="select b_id from wog_build_main where lv between 6 and 8 and type=2 and type_class >0 ORDER BY RAND() limit 1";
				$g_trap=$DB_site->query_first($sql);
				$sql="select b_id from wog_build_main where lv between 6 and 8 and type=3 ORDER BY RAND() limit 1";
				$g_formation=$DB_site->query_first($sql);
				break;
			case 3:
				for($i=1;$i<10;$i++)
				{
					$wp["weapon_".$i]=rand(0,5000);
				}
				$wp["weapon_12"]=50000;
				for($i=1;$i<13;$i++)
				{
					$ex["ex_".$i]=rand(3000,80000);
				}
				$sql="select b_id from wog_build_main where lv between 6 and 8 and type=2 and type_class >0 ORDER BY RAND() limit 1";
				$g_trap=$DB_site->query_first($sql);
				$sql="select b_id from wog_build_main where lv between 6 and 8 and type=3 ORDER BY RAND() limit 1";
				$g_formation=$DB_site->query_first($sql);
				break;
			case 4: //較弱NPC
				for($i=1;$i<10;$i++)
				{
					$wp["weapon_".$i]=rand(0,1500);
				}
				for($i=1;$i<13;$i++)
				{
					$ex["ex_".$i]=rand(0,3000);
				}
				$sql="select b_id from wog_build_main where lv between 1 and 2 and type=2 and type_class >0 ORDER BY RAND() limit 1";
				$g_trap=$DB_site->query_first($sql);
				$sql="select b_id from wog_build_main where lv between 1 and 2 and type=3 ORDER BY RAND() limit 1";
				$g_formation=$DB_site->query_first($sql);
				break;
			case 7: //帶精鍊石npc
				for($i=1;$i<10;$i++)
				{
					$wp["weapon_".$i]=rand(3000,9000);
				}
				for($i=1;$i<13;$i++)
				{
					$ex["ex_".$i]=rand(2000,20000);
				}
				$sql="select b_id from wog_build_main where lv between 4 and 7 and type=2 and type_class >0 ORDER BY RAND() limit 1";
				$g_trap=$DB_site->query_first($sql);
				$sql="select b_id from wog_build_main where lv between 4 and 7 and type=3 ORDER BY RAND() limit 1";
				$g_formation=$DB_site->query_first($sql);
				break;
			case 5: //破壞神
				for($i=1;$i<10;$i++)
				{
					$wp["weapon_".$i]=rand(30000,70000);
				}
				for($i=10;$i<13;$i++)
				{
					$wp["weapon_".$i]=rand(200000,300000);
				}
				for($i=1;$i<13;$i++)
				{
					$ex["ex_".$i]=rand(10000,100000);
				}
				$sql="select b_id from wog_build_main where lv = 8 and type=2 and type_class >0 ORDER BY RAND() limit 1";
				$g_trap=$DB_site->query_first($sql);
				$sql="select b_id from wog_build_main where lv = 8 and type=3 ORDER BY RAND() limit 1";
				$g_formation=$DB_site->query_first($sql);
				break;
			case 6://友善npc
				for($i=1;$i<12;$i++)
				{
					$wp["weapon_".$i]=500000;
				}
				/*
				for($i=10;$i<13;$i++)
				{
					$wp["weapon_".$i]=rand(200000,300000);
				}

				for($i=1;$i<13;$i++)
				{
					$ex["ex_".$i]=rand(10000,100000);
				}
				*/
				$sql="select b_id from wog_build_main where lv = 8 and type=2 and type_class >0 ORDER BY RAND() limit 1";
				$g_trap=$DB_site->query_first($sql);
				$sql="select b_id from wog_build_main where lv = 8 and type=3 ORDER BY RAND() limit 1";
				$g_formation=$DB_site->query_first($sql);
				break;
		} // switch
		$temp_wp="";
		foreach($wp as $key=>$value)
		{
			$temp_wp.=",".$key."=".$value;
		}
		$temp_ex="";
		foreach($ex as $key=>$value)
		{
			$temp_ex.=",".$key."=".$value;
		}
		if(!empty($temp_wp))
		{
			$temp_wp=substr($temp_wp,1);
			$temp_ex=substr($temp_ex,1);
			$place=rand(1,4);
			$sql="update wog_group_weapon set ".$temp_wp." , g_durable=g_durable_max where g_id=".$npc_groups[g_id];
			$DB_site->query($sql);
			$g_area_type=rand(1,6);
			$g_weather=rand(1,5);
			$sql="update wog_group_main set g_area_type=$g_area_type,g_formation=$g_formation[b_id],g_trap=$g_trap[b_id],g_weather=$g_weather,g_area=$place,g_area_x=$area_x,g_area_y=$area_y,g_break=0 where g_id=".$npc_groups[g_id];
			$DB_site->query($sql);
			$sql="update wog_group_map set g_id=".$npc_groups[g_id]." where x=".$area_x." and y=".$area_y;
			$DB_site->query($sql);
			
			$sql="update wog_group_exchange set ".$temp_ex." where g_id=".$npc_groups[g_id];
			$DB_site->query($sql);
			$temp_chat=sprintf($lang['wog_act_group_msg13'],$npc_groups[g_name],$lang['wog_act_place_'.$place]);
			//使用cache 重新設定聊天室路徑
			//require_once("./class/wog_act_message.php");
			require_once("../class/wog_act_message.php");
			$wog_arry["chat_path"]="../wog_chat";
			$wog_act_class = new wog_act_message;
			$wog_act_class->system_chat($temp_chat);
			unset($wog_act_class);
		}
	}
	function group_set_point($user_id)
	{
		global $DB_site,$_POST,$lang;

		$group_main=$DB_site->query_first("select b.p_g_id,a.lv from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions"); 
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		if($group_main["lv"]!=1 && $group_main["lv"]!=2)
		{
			alertWindowMsg($lang['wog_act_group_nolv']);
		}
		$d_type=$_POST["temp_id"];
		if(empty($d_type))
		{
			$d_type=0;
		}
		if($d_type==5)
		{
			$d_type="5,6";
		}
		$item_array=array();
		$item_array2=array();
		$item=$DB_site->query("select d_id,d_num,d_point from wog_group_depot a where a.g_id=".$group_main[p_g_id]." and d_type in ($d_type)");
		while($items=$DB_site->fetch_array($item))
		{
			$item_array[]=$items[d_id];
			$item_array2[$items[d_id]][0]=$items[d_num];
			$item_array2[$items[d_id]][1]=$items[d_point];
		}
		$DB_site->free_result($item);
		unset($items);
		if($item_array)
		{
			$item_main=$DB_site->query("select d_id,d_name,d_hole,d_send from wog_df where d_id in (".implode(',',$item_array).")");
			$temp_s="";
			while($item_mains=$DB_site->fetch_array($item_main))
			{
				$temp_s.=";".$item_mains[d_id].",".$item_mains[d_name]."(".$item_mains[d_hole]."),".$item_array2[$item_mains[d_id]][0].",".$item_mains[d_send].",".$item_array2[$item_mains[d_id]][1];
			}
			$DB_site->free_result($item_main);
			unset($item_mains);
			$temp_s=substr($temp_s,1,strlen($temp_s));
		}
		$point_main=$DB_site->query_first("select wp_1,wp_2,wp_3,wp_4,wp_5,wp_6,wp_7,wp_8,wp_9,wp_10,wp_11,wp_12,ex_1,ex_2,ex_3,ex_4,ex_5,ex_6,ex_7,ex_8,ex_9,ex_10,ex_11,ex_12 from wog_group_point where g_id=".$group_main[p_g_id]);
		$ex_str=$point_main[ex_1].",".$point_main[ex_2].",".$point_main[ex_3].",".$point_main[ex_4].",".$point_main[ex_5].",".$point_main[ex_6].",".$point_main[ex_7].",".$point_main[ex_8].",".$point_main[ex_9].",".$point_main[ex_10].",".$point_main[ex_11].",".$point_main[ex_12];
		$wp_str=$point_main[wp_1].",".$point_main[wp_2].",".$point_main[wp_3].",".$point_main[wp_4].",".$point_main[wp_5].",".$point_main[wp_6].",".$point_main[wp_7].",".$point_main[wp_8].",".$point_main[wp_9].",".$point_main[wp_10].",".$point_main[wp_11].",".$point_main[wp_12];
		showscript("parent.group_set_point('$temp_s','$d_type','$ex_str','$wp_str')");
		unset($temp_s);
	}
	function group_dep_point($user_id)
	{
		global $DB_site,$_POST,$lang;
		$group_main=$DB_site->query_first("select b.p_g_id,a.lv from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		if($group_main["lv"]!=1 && $group_main["lv"]!=2)
		{
			alertWindowMsg($lang['wog_act_group_nolv']);
		}
		$d_id=$_POST["pay_id"];
		$point=$_POST["temp_id"];
		if(empty($d_id))
		{
			alertWindowMsg($lang['wog_act_group_error11']);
		}
		if($point <0)
		{
			alertWindowMsg($lang['wog_act_group_error12']);
		}
		$DB_site->query("update wog_group_depot set d_point=$point where g_id=".$group_main[p_g_id]." and d_id =$d_id");
		showscript("parent.wog_message_box(11,0,1);");
		unset($temp_s);
	}
	function group_mod_point($user_id)
	{
		global $DB_site,$_POST,$lang;
		$group_main=$DB_site->query_first("select b.p_g_id,a.lv from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$user_id." and c.p_id=".$user_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		if($group_main["lv"]!=1 && $group_main["lv"]!=2)
		{
			alertWindowMsg($lang['wog_act_group_nolv']);
		}
		$update_str="";
		for($i=1;$i<13;$i++)
		{
			switch($_POST["set_type"])
			{
				case 1:
					$point=$_POST["ex_point_".$i];
					if(isset($point) && $point > -1)
					{
						$update_str.=",ex_".$i."=".$point;
					}
				break;
				case 2:
					$point=$_POST["wp_point_".$i];
					if(isset($point) && $point > -1)
					{
						$update_str.=",wp_".$i."=".$point;
					}
				break;
			}
		}
		if(!empty($update_str))
		{
			$update_str=substr($update_str,1);
			$DB_site->query("update wog_group_point set $update_str where g_id=".$group_main[p_g_id]);
		}
		unset($update_str,$group_main);
		showscript("parent.wog_message_box(11,0,1)");
	}
	function group_xy_line($tg_id,$pg_id){ // 計算兩點距離
		global $DB_site;
		$sql="select g_area_x,g_area_y from wog_group_main where g_id=".$tg_id;
		$t_g=$DB_site->query_first($sql);
		$sql="select g_area_x,g_area_y from wog_group_main where g_id=".$pg_id;
		$p_g=$DB_site->query_first($sql);
		$x=$t_g[g_area_x]-$p_g[g_area_x];
		$y=$t_g[g_area_y]-$p_g[g_area_y];
		if($x<0){$x*=-1;}
		if($y<0){$y*=-1;}
		$line=sqrt(pow($x,2)+pow($y,2));
		return $line;
	}
	function group_ex_data($g_id) //傳回公會目前資源
	{
		global $DB_site;
		$this->group_ex_conveyanc_job(); //先處理資源運輸
		$this->group_reback_market(); //處理過期資源
		$s="";
		$ex=$DB_site->query_first("select ex_1,ex_2,ex_3,ex_4,ex_5,ex_6,ex_7,ex_8,ex_9,ex_10,ex_11,ex_12 from wog_group_exchange where g_id=$g_id");
		if($ex)
		{
			$s.=$ex["ex_1"].",".$ex["ex_2"].",".$ex["ex_3"].",".$ex["ex_4"].",".$ex["ex_5"].",".$ex["ex_6"].",".$ex["ex_7"].",".$ex["ex_8"].",".$ex["ex_9"].",".$ex["ex_10"].",".$ex["ex_11"].",".$ex["ex_12"];
		}
		unset($ex);
		return $s;
	}
	function group_reback_market()
	{
		global $DB_site;
		$time=time();
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select g_id,f_type,sum(f_num) as ex_num from wog_group_market where end_time <=$time group by g_id,f_type for update";
		$job_main=$DB_site->query($sql);
		$g_id=0;
		$ex_get=array();
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			if(empty($g_id)){$g_id=$job_mains[g_id];}
			if($g_id!=$job_mains[g_id])
			{
				$sql="update wog_group_exchange set ".implode(',',$ex_get)." where g_id=$g_id";
				$DB_site->query($sql);
				$g_id=$job_mains[g_id];
				$ex_get=array();
				$ex_get[]="ex_".$job_mains[f_type]."=ex_".$job_mains[f_type]."+".$job_mains[ex_num];
			}else
			{
				$ex_get[]="ex_".$job_mains[f_type]."=ex_".$job_mains[f_type]."+".$job_mains[ex_num];
			}
		}
		unset($job_mains);
		$DB_site->free_result($job_main);
		if(!empty($ex_get))
		{
				$sql="update wog_group_exchange set ".implode(',',$ex_get)." where g_id=$g_id";
				$DB_site->query($sql);
		}
		$sql="delete from wog_group_market where end_time <= $time";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		$DB_site->query_first("set autocommit=1");
	}
	function group_job_list($user_id)
	{
		global $DB_site;
		$group_main=$DB_site->query_first("select b.p_g_id from wog_player b where b.p_id=".$user_id);
		if(empty($group_main[p_g_id]))
		{
			alertWindowMsg($lang['wog_act_group_nogroup']);
		}
		$g_id=$group_main[0];
		$time=time();
		$s2=array();
		//研究
		$job_main=$DB_site->query("select a.j_time,b.name,b.lv,c.p_name from wog_group_build_job a ,wog_build_main b,wog_player c where a.g_id=$g_id and b.b_id=a.b_id and c.p_id=a.p_id ");
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			$s2[]="1,".($job_mains[j_time]-$time).",".$job_mains[name].",".$job_mains[lv].",".$job_mains[p_name];
		}
		//軍備
		$job_main=$DB_site->query("select a.j_time,a.j_type,b.g_name,c.p_name,a.j_id from wog_group_job a left join wog_group_main b on  a.j_target=b.g_id ,wog_player c where a.g_id=$g_id and a.j_type in (1,2,3,4) and c.p_id=a.p_id");
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			$s2[]="2,".($job_mains[j_time]-$time).",".$job_mains[j_type].",".$job_mains[g_name].",".$job_mains[p_name].",".$job_mains[j_id];;
		}
		//造兵
		$job_main=$DB_site->query("select a.j_time,a.j_class,a.weapon_1,a.weapon_2,a.weapon_3,a.weapon_4,a.weapon_5,a.weapon_6,a.weapon_7,a.weapon_8,a.weapon_9,c.p_name from wog_group_job a,wog_player c where a.g_id=$g_id and a.j_type=0 and c.p_id=a.p_id");
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			$s2[]="3,".($job_mains[j_time]-$time).",".$job_mains[j_class].",".$job_mains["weapon_".$job_mains[j_class]].",".$job_mains[p_name];
		}
		//交易
		$job_main=$DB_site->query("select a.j_time,a.t_type,a.t_num,c.p_name from wog_group_market_job a,wog_player c where a.t_id=".$g_id." and c.p_id=a.p_id");
		while($job_mains=$DB_site->fetch_array($job_main))
		{
			$s2[]="4,".($job_mains[j_time]-$time).",".$job_mains[t_type].",".$job_mains[t_num].",".$job_mains[p_name];
		}
		unset($job_mains);
		$DB_site->free_result($job_main);
		$s1="";
		$s1=implode(';',$s2);
		showscript("parent.group_job_list('$s1')");
	}
	function group_vip_item($user_id)
	{
		global $DB_site,$_POST,$lang,$a_id;
		alertWindowMsg($lang['wog_main_error4']);
	}

}
?>