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

class wog_fight_select{
	function fire($user_id,$mission=array(),$mission_id=array())
	{
		global $DB_site,$_POST,$wogclass,$wog_arry,$wog_event_class,$lang,$pet,$m,$p,$get_pet;
		$mission_chk=1;
		$mission_str=" and m_mission in (".implode(',',$mission_id).",0)";
		$time=time();
		$datecut=$time-$wog_arry["f_time"];
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select a.p_id,a.p_name,a.at,a.df,a.mat,a.mdf,a.s_property,a.hp,a.hpmax,a.sp,a.spmax
		,a.p_sat_name,a.p_lv,a.p_exp,a.p_nextexp,a.ch_id,b.a_id,a.p_st
		,b.d_item_id2,b.d_item_num2,a.i_img,a.p_img_set,a.p_img_url,a.t_id,a.p_bag,a.p_attempts,c.p_cp_st,c.hero_type
		,d.skill_1,d.skill_2,d.skill_3,d.skill_4,d.skill_5,0 as skill_6,d.time_1,d.time_2,d.time_3,d.time_4,d.time_5
		,a.au,a.be,a.vit,a.life,a.smart,a.agi,a.str,a.act_num,a.act_num_time,a.p_act_time,a.p_recomm
		from wog_player a left join wog_player_arm b on b.p_id=a.p_id left join wog_player_cp c on c.p_id=a.p_id
		left join wog_skill_setup d on d.p_id=a.p_id
		where a.p_id=".$user_id." and a.p_act_time < $datecut and a.p_lock=0 for update";
		if($p=$DB_site->query_first($sql))//check act_timt benig
		{
			$temp_time=($time-$p[act_num_time])/$wog_arry["act_num_time"];
			if($temp_time >= 1)
			{
				$p[act_num]+=$wog_arry["act_num"];
				if($temp_time >= 2)
				{
					$p[act_num]+=$wog_arry["act_num"];
				}
				if($p[act_num]>50){$p[act_num]=50;}
				$sql="update wog_player set act_num_time=".$time." where p_id=".$user_id;
				$DB_site->query($sql);
			}
			$p[act_num]--;
			if($p[act_num]<=0)
			{
				$wog_arry["f_time"]=$wog_arry["f_time1"];
				switch($p[act_num])
				{
					case-1:
						if(($time-$p[p_act_time])<$wog_arry["f_time"])
						{
							alertWindowMsg($wog_arry["f_time"].$lang['wog_fight_cant_fight1']);
						}
					break;
					case 0:
						$wog_arry["f_down"]=1;
					break;						
				}
			}else
			{
				$wog_arry["f_down"]=1;
			}
			if($p[hp]==0)
			{
				alertWindowMsg($lang['wog_fight_no_hp']);
			}
			if($p[p_attempts] && $wog_arry["event_ans"])
			{
				$wog_event_class->event_start($user_id,$p["p_attempts"]);
			}
			if(rand(1,$wog_arry["event_ans_max"])==1 && $wog_arry["event_ans"])
			{
				$wog_event_class->event_creat($user_id);
			}
			if(preg_match("/[^0-9]/",$_POST["act"]))
			{
				alertWindowMsg($lang['wog_fight_select_area']);
			}
			if( count($mission_id) < 1)
			{
				$mission_str="and m_mission=0";
				$mission_chk=0;
			}
			$p[p_sat_name]=$_POST["sat_name"];
			$sql="select d_id,d_g_hp,d_name,d_s,skill,stime,d_g_sp from wog_df where d_id in (".$p[a_id].",".$p[d_item_id2].")";
			$p_check_item=$DB_site->query($sql);
			while($p_check_items=$DB_site->fetch_array($p_check_item))
			{
				if($p_check_items[d_id] == $p[a_id])
				{
					$p[d_s] = $p_check_items[d_s];
					$p[skill_6] = $p_check_items[skill];
					$p[time_6] = $p_check_items[stime];
				}
				if($p_check_items[d_id] == $p[d_item_id2])
				{
					$p[d_g_hp] = $p_check_items[d_g_hp];
					$p[d_name] = $p_check_items[d_name];
					$p[d_g_sp] = $p_check_items[d_g_sp];
				}
			}
			$DB_site->free_result($p_check_item);
			unset($p_check_items);

			$place_str='m_place in ('.$_POST['act'].',0)';
			if($_POST['subplace']){
				$checker=1 << (intval($_POST['subplace'])-1);
				$place_str='(m_place=0 or (m_place='.$_POST['act'].' and m_subplace & '.$checker.'='.$checker.'))';
			}
			$sql="select * from wog_monster where ".$place_str." and m_meet <= ".rand(0,100)." ".$mission_str." ORDER BY RAND() LIMIT 1";

			$m=$DB_site->query_first($sql);
			if($m)//m date check start
			{
				$m[hpmax]=$m[hp];
				$m[spmax]=$m[sp];
				$m[m_job_exp]=floor($m[m_job_exp]*$wog_arry["f_down"]);
				if($p[p_img_set]==1)
				{
					$p[i_img]=$p[p_img_url];
				}
				echo "<script language=JavaScript >\n";
				if($m["m_npc"]==1)
				{
					if($mission_chk==1 && $mission_id[$m['m_id']])
					{
						echo "parent.fire_date('$p[at]','$p[df]','$p[mat]','$p[mdf]','$p[hp]','$p[hpmax]','$p[s_property]',$p[p_img_set],'$p[i_img]','?????','?????','?????','?????','?????','?????','$m[s_property]','$m[m_name]','$m[m_img]',0);\n";
						$mission[$m['m_mission']]['m_kill_num'][$m['m_id']]--;
						if($mission[$m['m_mission']]['m_kill_num'][$m['m_id']]<0)
						{
							$mission[$m['m_mission']]['m_kill_num'][$m['m_id']]=0;
						}
						$m_b=array();
						$t=false;
						foreach($mission[$m['m_mission']]['m_kill_num'] as $k => $v )
						{
							if($v >0){$t=true;}
							$m_b[]=$k."*".$v;
						}
						$str="0";
						if($t)
						{
							$str=implode(",",$m_b);
						}
						
						$sql="update wog_mission_book set m_kill_num='".$str."' where p_id=".$user_id." and m_id=".$mission_id[$m['m_id']];
						$DB_site->query($sql);
						echo "parent.setup_mname('".$m["m_name"]."');\n";
						$m["m_npc_message"]=str_replace("\r\n","&n",$m["m_npc_message"]);
						echo "parent.npc_message('".$m["m_npc_message"]."',".$mission[$m['m_mission']]['m_kill_num'][$m['m_id']].",".$mission[$m['m_mission']]['m_need_num'][$m['m_id']].");\n";
					}
				}else
				{
					if(!empty($p[hero_type]) && rand(10,1) == 1)
					{
						switch($m[hero_type]){
							case 2:
								$p[at]=$p[at]*1.1;
								break;
							case 3:
								$p[mat]=$p[mat]*1.1;
								break;
							case 4:
								$p[agi]=$p[agi]*1.1;
								break;
							case 5:
								;
								break;
						} // switch
					}

					$this->p_set_skill($p); //玩家技能設定
					$this->m_set_skill($m); //怪物技能設定
					if($p["t_id"]>0)
					{
						$datecut=$time-($wog_arry["t_time"]*60);

						$sql="select a.p_name,a.at,a.df,a.mat,a.mdf,a.s_property,a.agi,a.hp,a.hpmax,a.sp,a.spmax
						,a.p_sat_name,a.p_lv,a.i_img,a.p_img_set,a.p_img_url
						from wog_player a
						where a.t_id=".$p["t_id"]." and a.p_id<>".$user_id." and a.p_act_time > $datecut and a.p_place=".$_POST["act"]." and a.p_lock=0 ORDER BY RAND() LIMIT 1";
						$p_support=$DB_site->query_first($sql);
						if(!$p_support)
						{
							$p_support=NULL;
							echo "parent.p_support_name='';";
						}else
						{
							if($p_support[p_img_set]==1)
							{
								$p_support[i_img]=$p_support[p_img_url];
							}
							$p_support["at"]=$p_support["at"]*0.9;
							$p_support["mat"]=$p_support["mat"]*0.9;
							echo "parent.p_support_name='$p_support[p_name]';";
							echo "parent.p_support_img='$p_support[i_img]';";
						}
						$sql="select count(a.p_id) as members,avg(a.p_lv) as members_lv from wog_player a
						where a.t_id=".$p["t_id"]." and a.p_act_time > $datecut and a.p_place=".$_POST["act"]." and a.p_lock=0";
						$my_member=$DB_site->query_first($sql);
					}else
					{
						$p_support=NULL;
						echo "parent.p_support_name='';";
					}
					$sql="select pe_id,pe_name,pe_at,pe_mt,pe_fu,pe_def,pe_hu,pe_type,pe_age,pe_he,pe_fi,pe_b_old,pe_mimg,pe_img_set,pe_img_url from wog_pet where pe_p_id=".$user_id." and pe_st=0";
					$pet=$DB_site->query_first($sql);
					if(!$pet)
					{
						$pet=NULL;
						echo "parent.pet_pname='';";
					}else
					{
						if($pet[pe_img_set]==1)
						{
							$pet[pe_mimg]=$p[pe_img_url];
							echo "parent.pet_img='$pet[pe_mimg]';";
						}else
						{
							echo "parent.pet_img=parent.mimg+'$pet[pe_mimg]';";
						}
						echo "parent.pet_pname='$pet[pe_name]';";
					}
					if(($p[at]*2)<$m[at] && ($p[mat]*2)<$m[mat])
					{
						echo "parent.fire_date('$p[at]','$p[df]','$p[mat]','$p[mdf]','$p[hp]','$p[hpmax]','$p[s_property]',$p[p_img_set],'$p[i_img]','?????','?????','?????','?????','$m[hp]','$m[hp]','$m[s_property]','$m[m_name]','$m[m_img]',0);\n";
					}else
					{
						echo "parent.fire_date('$p[at]','$p[df]','$p[mat]','$p[mdf]','$p[hp]','$p[hpmax]','$p[s_property]',$p[p_img_set],'$p[i_img]','$m[at]','$m[df]','$m[mat]','$m[mdf]','$m[hp]','$m[hp]','$m[s_property]','$m[m_name]','$m[m_img]',0);\n";
					}
					echo "fightrow = new Array(\"戰鬥開始\"";
					$wogclass->p_place=$_POST["act"];
					$wogclass->datecut=$datecut;
					$wogclass->fight_count($user_id,0,$p_support,$my_member);
					echo $wogclass->temp_fight_string;
					$temp_str=date('d',$time); //集中市場週期
					if($wogclass->win > $wogclass->lost)
					{
						$get_pet=0;
						$temp_str=($temp_str%3)?20:-190;
						if(rand(1,(10500-$temp_str))== 1)
						{
							$temp_str=($temp_str%3)?1:-10;
							if(rand(1,100)>25-$temp_str)
							{
								$wog_event_class->exchange_up($p,$m,0);
							}
							else
							{
								$wog_event_class->exchange_down($p,$m,1);
							}
						}
						
						//檢查是否有戰鬥結束技能發動 begin
						$sum=count($wogclass->end_skill_id);
						if($sum>0)
						{
							include_once('./class/wog_fight_end_skill.php');
							for($i=0;$i<$sum;$i++)
							{
								$s_id=$wogclass->end_skill_id[$i];
								$s_lv=$wogclass->end_skill_lv[$i];
								eval("end_skill_".$s_id."(\$p,\$s_lv,\$p[p_id],0,\$dmg);");
								
							}
						}
						//檢查是否有戰鬥結束技能發動 end
						if(($p[d_item_id2]==258||$p[d_item_id2]==1837||$p[d_item_id2]==2236) && !$pet )
						{
							if(rand(1,40)==1)
							{
								$p[d_item_num2]--;
								echo ",\"parent.pet_break()\"";
								if($p[d_item_num2]<=0)
								{
									$sql="update wog_player_arm set d_item_id2=0,d_item_num2=0 where p_id=".$user_id;
									echo ",\"parent.d_item_name=''\"";
								}else
								{
									$sql="update wog_player_arm set d_item_num2=".$p[d_item_num2]." where p_id=".$user_id;
								}
								$DB_site->query($sql);
							}
							if(rand(1,100)<=(12+$get_pet))
							{
								$sql="select count(pe_id) as num from wog_pet where pe_p_id=".$user_id." and pe_st in (0,2) ";
								$pet=$DB_site->query_first($sql);
								if($pet["num"]<3)
								{
									$p[d_item_num2]--;
									$pet=$wog_event_class->pet_stats($p[d_item_id2]);
									$sql="insert into wog_pet(pe_p_id,pe_name,pe_at,pe_mt,pe_fu,pe_def,pe_hu,pe_type,pe_age,pe_he,pe_fi,pe_dateline,pe_mname,pe_m_id,pe_b_dateline,pe_mimg,pe_st)";
									$sql.="values(".$user_id.",'".$m[m_name]."',".$pet[pe_at].",".$pet[pe_mt].",80,".$pet[pe_def].",0,".$pet[pe_type].",1,0,1,".($time-20).",'".$m[m_name]."',".$m[m_id].",".$time.",'".$m[m_img]."',2)";
									$DB_site->query($sql);
									if($p[d_item_num2]<=0)
									{
										$sql="update wog_player_arm set d_item_id2=0,d_item_num2=0 where p_id=".$user_id;
										echo ",\"parent.d_item_name=''\"";
									}else
									{
										$sql="update wog_player_arm set d_item_num2=".$p[d_item_num2]." where p_id=".$user_id;
									}
									$DB_site->query($sql);
									echo ",\"parent.pet_get('".$m[m_name]."')\"";
								}
							}
						}
						
						if($mission_chk==1 && $mission_id[$m['m_id']])
						{
							$m['m_mission']=$mission_id[$m['m_id']];
							$mission[$m['m_mission']]['m_kill_num'][$m['m_id']]--;
							if($mission[$m['m_mission']]['m_kill_num'][$m['m_id']]<0)
							{
								$mission[$m['m_mission']]['m_kill_num'][$m['m_id']]=0;
							}
							$m_b=array();
							$t=false;
							
							foreach($mission[$m['m_mission']]['m_kill_num'] as $k => $v )
							{
								if($v >0){$t=true;}
								$m_b[]=$k."*".$v;
							}
							$str="0";
							if($t)
							{
								$str=implode(",",$m_b);
							}
							if(empty($str))
							{
								$sql="update wog_mission_book set m_kill_num='".$str."',m_status=1 where p_id=".$user_id." and m_id=".$m['m_mission'];
								$DB_site->query($sql);
							}
							else
							{
								$sql="update wog_mission_book set m_kill_num='".$str."' where p_id=".$user_id." and m_id=".$m['m_mission'];
								$DB_site->query($sql);
							}
							echo ",\"parent.mission_achieve(".$mission[$m['m_mission']]['m_kill_num'][$m['m_id']].",".$mission[$m['m_mission']]['m_need_num'][$m['m_id']].")\"";
						}
						if(!empty($m[d_id]))
						{
							if($wog_arry["f_down"]<1){$m[m_topr]*=($wog_arry["f_down"]+1);}
							$wog_event_class->get_item($user_id,$m[d_id],$m[m_topr],$p[p_st],$p[p_bag]);
							if(!empty($wog_event_class->temp_fight_string))
							{
								echo $wog_event_class->temp_fight_string;
							}
						}						
					}else
					{
						$temp_str=($temp_str%3)?-10:60;
						if(rand(1,(310-$temp_str))== 1)
						{
							//$temp_str=($temp_str%3)?-5:5;
							if(rand(1,100)>25)
							{
								$wog_event_class->exchange_down($p,$m,0);
							}
							else
							{
								$wog_event_class->exchange_up($p,$m,1);
							}
						}
					}
					$temp_id6=(int)$_POST["temp_id6"];
					$temp_id7=(int)$_POST["temp_id7"];
					if($temp_id6 > -1 || $temp_id7 > -1)
					{
						if(($p[hp] <= $p[hpmax]*($temp_id6/100)) || ($p[sp] <= $p[spmax]*($temp_id7/100)))
						{
							require_once("./class/wog_act_store.php");
							$hotelclass = new wog_act_store;
							$hotelclass->fight_hotel=1;
							$hotelclass->hotel($user_id);
						}
					}else
					{
						if($p[hp] <= 0)
						{
							require_once("./class/wog_act_store.php");
							$hotelclass = new wog_act_store;
							$hotelclass->fight_hotel=1;
							$hotelclass->hotel($user_id);
						}
					}
					echo ");\n parent.set_fight(fightrow);\n";
				}
			}else
			{
				alertWindowMsg($lang['wog_fight_no_date']);
			}//m date check end
		}else
		{
			alertWindowMsg($wog_arry["f_time"].$lang['wog_fight_cant_fight1']);
		}//check act_time end
		$DB_site->query_first("COMMIT");
		unset($hotelclass,$m,$p,$pet,$time,$my_member,$p_support);
		echo "parent.cd(".$wog_arry["f_time"].")\n";
	}
	function fire_cp($user_id)
	{
		global $DB_site,$_POST,$wogclass,$wog_arry,$lang,$m,$p,$temp_p_st;
		$win=0;
		$lost=0;
		$f_cp=0;
		$time=time();
   		$datecut=$time-$wog_arry["f_time"];
		$sql="select a.p_id,a.p_name,a.at,a.df,a.mat,a.mdf,a.s_property,a.hp,a.hpmax,a.sp,a.spmax,a.p_sat_name,a.p_lv,a.p_birth
		,a.p_exp,a.p_nextexp,a.str,a.smart,a.agi,a.life,a.vit,a.au,a.be,a.ch_id
		,a.p_sex,a.p_money,a.p_win,a.p_lost,a.i_img,a.p_place,a.p_cdate
		,a.base_str,a.base_smart,a.base_agi,a.base_life,a.base_vit,a.base_au,a.base_be
		,0 as d_g_hp,a.p_img_set,a.p_img_url,a.p_cp_time
		,d.skill_1,d.skill_2,d.skill_3,d.skill_4,d.skill_5,0 as skill_6,d.time_1,d.time_2,d.time_3,d.time_4,d.time_5
		,a.act_num
		from wog_player a
		left join wog_skill_setup d on d.p_id=a.p_id
		where a.p_id=".$user_id." AND a.p_act_time < $datecut and a.p_lock=0
		";
		if($p=$DB_site->query_first($sql))//check act_timt benig
		{
			
			if($p['p_cp_time']>$time)
			{
				alertWindowMsg(sprintf($lang['wog_fight_cant_f_cp'],set_date($p['p_cp_time'])));
			}
			if($p[p_money]<$wog_arry["cp_mmoney"])
			{
				alertWindowMsg($lang['wog_fight_cp_money'].$wog_arry["cp_mmoney"]);
			}
			if($p[hp]==0)
			{
				alertWindowMsg($lang['wog_fight_no_hp']);
			}
			$sql="select a.p_name as m_name,a.at,a.df,a.mat
			 ,a.mdf ,a.agi ,a.p_lv as m_lv,a.s_property ,a.p_sat_name as m_sat_name
			 ,a.hp,a.hpmax,a.p_pid as m_id,a.i_img as m_img
			 ,a.p_img_set as m_img_set,a.p_img_url as m_img_url
			 ,a.sp,a.spmax
			 ,a.str,a.smart,a.agi,a.life,a.vit,a.au,a.be
			 ,d.skill_1,d.skill_2,d.skill_3,d.skill_4,d.skill_5,0 as skill_6,d.time_1,d.time_2,d.time_3,d.time_4,d.time_5
			 from wog_cp a left join wog_skill_setup d on d.p_id=a.p_pid LIMIT 1 ";
			$m=$DB_site->query_first($sql);
			if($m)//m date check start
			{
				$wog_arry["f_time"]=$wog_arry["f_time1"];
				if($m[m_name]==$p[p_name])
				{
					alertWindowMsg($lang['wog_fight_cant_fight_me']);
				}
				if($p[p_img_set]==1)
				{
					$i_img=$p[p_img_url];
				}else
				{
					$i_img=$p[i_img];
				}
				if($m[m_img_set]==1)
				{
					$m[m_img]=$m[m_img_url];
				}
				$this->p_set_skill($p); //玩家技能設定
				$this->m_set_skill($m); //怪物技能設定
				echo "<script language=JavaScript >\n";
				echo "parent.fire_date('$p[at]','$p[df]','$p[mat]','$p[mdf]','$p[hp]','$p[hpmax]','$p[s_property]',$p[p_img_set],'$i_img','$m[at]','$m[df]','$m[mat]','$m[mdf]','$m[hp]','$m[hpmax]','$m[s_property]','$m[m_name]','$m[m_img]',1);\n";
				//$wogclass->cp_m_hp=0;
				$wogclass->p_place=$p[p_place];
				echo "fightrow = new Array(\"戰鬥開始\"";
				$wogclass->datecut=$time;
				$cp=$wogclass->fight_count($user_id,-$wog_arry["cp_mmoney"],NULL,NULL,"");
				echo $wogclass->temp_fight_string.");\n";
				echo "parent.set_fight(fightrow);\n";
				$cp_m_hp=$wogclass->cp_m_hp;
				$cp_m_sp=$wogclass->cp_m_sp;
				if($wogclass->win > $wogclass->lost)
				{
					//還原玩家所有能力,讓冠軍狀態正常 begin
					$temp_p_st[hp]=$p[hp];
					$temp_p_st[sp]=$p[sp];
					$p=$temp_p_st;
					//還原玩家所有能力,讓冠軍狀態正常 end

					$f_cp=$time+$wog_arry["f_cp"];
					$sql="update wog_cp set p_name='$p[p_name]',at=$p[at]
					,mat=$p[mat],df=$p[df],mdf=$p[mdf],s_property=$p[s_property]
					,str=$p[str],life=$p[life],agi=$p[agi],au=$p[au],be=$p[be],vit=$p[vit],smart=$p[smart]
					,base_str=$p[base_str],base_life=$p[base_life],base_agi=$p[base_agi],base_au=$p[base_au],base_be=$p[base_be],base_vit=$p[base_vit],base_smart=$p[base_smart]
					,hp=$p[hpmax],p_sat_name='$cp[p_sat_name]'
					,hpmax=$p[hpmax],p_win_total=1,p_lv=$p[p_lv]
					,p_sex=$p[p_sex],p_money=$p[p_money],p_exp=$p[p_exp],p_nextexp=$p[p_nextexp]
					,p_win=$p[p_win]+1,p_lost=$p[p_lost],i_img=$p[i_img],ch_id=$p[ch_id],p_pid=$user_id
					,p_place=$p[p_place],p_birth=$p[p_birth]
					,p_cdate=$p[p_cdate],p_img_set=$p[p_img_set],p_img_url='$p[p_img_url]'
					,sp=$p[spmax],spmax=$p[spmax]
					";
					$DB_site->query($sql);

					echo "parent.cp_end('$p[p_name]')\n";
					$DB_site->query("update wog_player set p_cp_time=$f_cp where p_id=$m[m_id]");
//					$DB_site->query("update wog_player_cp set p_cp_st=1 where p_id=$user_id");
				}else{
					$DB_site->query("update wog_cp set hp=$cp_m_hp,sp=$cp_m_sp,p_win_total=p_win_total+1");
					$DB_site->query("update wog_player set p_money=p_money+".$wog_arry["cp_mmoney"]*0.7." where p_id=$m[m_id]");
				}
			}else
			{
				$sql="insert into wog_cp(p_name,at,mat,df,mdf,s_property
				,str,life,vit,agi,smart,au,be,hp,p_sat_name,hpmax,p_win_total,p_lv,
				p_sex,p_money,p_exp,p_nextexp,p_win,p_lost,ch_id,i_img,p_pid
				,p_place,p_birth,p_cdate,p_img_set,p_img_url
				,sp,spmax,base_str,base_life,base_vit,base_agi,base_smart,base_au,base_be
				)values('$p[p_name]',$p[at],$p[mat],$p[df],$p[mdf],$p[s_property]
				,$p[str],$p[life],$p[vit],$p[agi],$p[smart],$p[au],$p[be],$p[hpmax]
				,'$p[p_sat_name]',$p[hpmax],1,$p[p_lv],$p[p_sex],$p[p_money],$p[p_exp],$p[p_nextexp]
				,$p[p_win]+1,$p[p_lost],$p[ch_id],$p[i_img],$user_id,$p[p_place]
				,$p[p_birth],$p[p_cdate],$p[p_img_set],'$p[p_img_url]'
				,$p[sp],$p[spmax]
				,$p[base_str],$p[base_life],$p[base_vit],$p[base_agi],$p[base_smart],$p[base_au],$p[base_be]
				)";
				$DB_site->query($sql);
				$DB_site->query("update wog_player_cp set p_cp_st=1 where p_id=$user_id");
				echo "<script language=JavaScript >\n";
				echo "parent.cp_end('$p[p_name]')\n";
			}//m date check end
		}else
		{
			alertWindowMsg($wog_arry["f_time"].$lang['wog_fight_cant_fight1']);
		}//check act_time end
		unset($m,$p,$cp,$time);
		echo "parent.cd(".$wog_arry["f_time"].")\n";
	}
	function fire_pk($user_id)
	{
		global $DB_site,$_POST,$wogclass,$wog_arry,$lang,$wog_event_class,$m,$p;
		$win=0;
		$lost=0;
		$time=time();
   		$datecut=$time-$wog_arry["f_time"];
		$sql="select a.p_id,a.p_name,a.at,a.df,a.mat,a.mdf,a.s_property,a.hp,a.hpmax,a.sp,a.spmax,a.p_sat_name,a.p_lv
		,a.p_exp,a.p_nextexp,a.ch_id,a.p_money,a.p_win,a.p_lost,a.i_img
		,0 as d_g_hp,a.p_img_set,a.p_img_url,p_bag,p_st
		,a.au,a.be,a.vit,a.life,a.smart,a.agi,a.str
		,c.p_cp_st,c.hero_type
		,d.skill_1,d.skill_2,d.skill_3,d.skill_4,d.skill_5,0 as skill_6,d.time_1,d.time_2,d.time_3,d.time_4,d.time_5
		,b.d_item_id,b.d_item_num,a.act_num
		from wog_player a left join wog_player_cp c on c.p_id=a.p_id left join wog_player_arm b on b.p_id=a.p_id
		left join wog_skill_setup d on d.p_id=a.p_id
		where a.p_id=".$user_id." AND a.p_act_time < $datecut and a.p_lock=0
		";
		if($p=$DB_site->query_first($sql))//check act_timt benig
		{
			if($p[hp]==0)
			{
				alertWindowMsg($lang['wog_fight_no_hp']);
			}
			$sql="select a.p_id as m_id,a.p_name as m_name,a.at,a.df,a.mat
			,a.mdf,a.agi,a.p_lv as m_lv,a.s_property,a.p_sat_name as m_sat_name
			,a.hpmax as hp,a.hpmax,a.p_pk_money,a.p_money,a.i_img as m_img
			,a.spmax as sp,a.spmax
			,a.p_img_set as m_img_set,a.p_img_url as m_img_url,a.p_npc
			,a.au,a.be,a.vit,a.life,a.smart,a.agi,a.str
			,d.skill_1,d.skill_2,d.skill_3,d.skill_4,d.skill_5,0 as skill_6,d.time_1,d.time_2,d.time_3,d.time_4,d.time_5
			from wog_player a
			left join wog_skill_setup d on d.p_id=a.p_id
			where a.p_name='".trim($_POST["towho"])."' and a.p_pk_s=1 ";
			$m=$DB_site->query_first($sql);
			if($m)//m date check start
			{
				$wog_arry["f_time"]=$wog_arry["f_time1"];
				if($m[m_name]==$p[p_name])
				{
					alertWindowMsg($lang['wog_fight_cant_fight_me']."-".$m[m_name]."-".$p[p_name]);
				}
				if($p[p_money]<$m[p_pk_money])
				{
					alertWindowMsg($lang['wog_fight_pk_money'].$m[p_pk_money]);
				}
				if($m[p_npc] > 1)
				{
					if(!empty($p[hero_type])){alertWindowMsg($lang['wog_fight_cant_pk5']);}
					if($p[p_lv] < 200){alertWindowMsg($lang['wog_fight_cant_pk4']);}
					$m[skill_1]=$p[skill_1];
					$m[skill_2]=$p[skill_2];
					$m[skill_3]=$p[skill_3];
					$m[skill_4]=$p[skill_4];
					$m[skill_5]=$p[skill_5];
					$m[time_1]=$p[time_1];
					$m[time_2]=$p[time_2];
					$m[time_3]=$p[time_3];
					$m[time_4]=$p[time_4];
					$m[time_5]=$p[time_5];

					$m[m_topr]="1,2";
					$m[at]=round(($p[at]+$p[mat])/2);
					$m[mat]=round(($p[at]+$p[mat])/2);
					$m[df]=round(($p[df]+$p[mdf])/2);
					$m[mdf]=round(($p[df]+$p[mdf])/2);
					$m[agi]=$p[agi];
					$m[au]=$p[au];
					$m[s_property]=$p[s_property];
					$m[hp]=$p[hpmax];
					$m[hpmax]=$m[hp];
					$m[sp]=$p[spmax];
					$m[spmax]=$m[sp];

					switch($m[p_npc]){
						case 2:
							$m[d_id]="283,1842";
							$m[at]=$m[at]+$p[str];
							break;
						case 3:
							$m[d_id]="285,1844";
							$m[mat]=$m[mat]+$p[smart];
							break;
						case 4:
							$m[d_id]="284,1845";
							$m[agi]=$m[agi]*1.3;
							break;
						case 5:
							$m[d_id]="287,1843";
							$m[df]=$m[df]+$p[vit];
							break;
						case 6:
							$m[d_id]="288,1846";
							$m[mdf]=$m[mdf]+$p[be];
							break;
						case 7:
							$m[d_id]="289,1848";
							$m[au]=$m[au]*1.3;
							$m[at]=$m[at]+($p[str]/2.5);
							$m[mat]=$m[mat]+($p[smart]/2.5);
							break;
						case 8:
							$m[d_id]="284,1847";
							$m[agi]=$m[agi]*1.2;
							$m[at]=$m[at]+($p[str]/2);
							break;
						case 9:
							$m[d_id]="289,1849";
							$m[au]=$m[au]*1.2;
							$m[at]=$m[at]+($p[str]/1.8);
							$m[mat]=$m[mat]+($p[smart]/1.8);
							break;
					} // switch
				}else
				{
					if($m[p_money]<$m[p_pk_money])
					{
						$DB_site->query("update wog_player set p_pk_s=0 where p_id=".$m[p_id]);
						alertWindowMsg($lang['wog_fight_cant_pk2']);
					}
				}
				if($p[p_img_set]==1)
				{
					$p[i_img]=$p[p_img_url];
				}
				if($m[m_img_set]==1)
				{
					$m[m_img]=$m[m_img_url];
				}
				$this->p_set_skill($p); //玩家技能設定
				$this->m_set_skill($m); //怪物技能設定
				echo "<script language=JavaScript >\n";
				echo "parent.fire_date('$p[at]','$p[df]','$p[mat]','$p[mdf]','$p[hp]','$p[hpmax]','$p[s_property]',$p[p_img_set],'$p[i_img]','$m[at]','$m[df]','$m[mat]','$m[mdf]','$m[hp]','$m[hpmax]','$m[s_property]','$m[m_name]','$m[m_img]',1);\n";
				echo "fightrow = new Array(\"戰鬥開始\"";
				$cp=$wogclass->fight_count($user_id);
				echo $wogclass->temp_fight_string;
				if($wogclass->win > $wogclass->lost)
				{
					$DB_site->query("update wog_player set p_money=".($p[p_money]+$m[p_pk_money])." where p_id=".$user_id);
					if($m[p_npc] > 1)
					{
						$DB_site->query("update wog_player_cp set p_id=$user_id,hero_type=$m[p_npc],hero_time=".($time+rand($wog_arry["f_hero"][0],$wog_arry["f_hero"][1]))." where hero_npc=".$m[m_id]);
						$DB_site->query("update wog_player set p_pk_s=0 where p_id=$m[m_id]");
						$wog_event_class->get_item($user_id,$m[d_id],$m[m_topr],$p[p_st],$p[p_bag]);
						if(!empty($wog_event_class->temp_fight_string))
						{
							echo $wog_event_class->temp_fight_string;
						}
					}
					else
					{
						$DB_site->query("update wog_player set p_money=p_money-".$m[p_pk_money]." where p_id=".$m[m_id]);
					}
				}else{
					$DB_site->query("update wog_player set p_money=".($p[p_money]-$m[p_pk_money])." where p_id=".$user_id);
					if($m[p_npc] == 0)
					{
						$DB_site->query("update wog_player set p_money=p_money+".$m[p_pk_money]." where p_id=".$m[m_id]);
					}
				}
				echo ");\n parent.set_fight(fightrow);\n";
			}else
			{
				alertWindowMsg($lang['wog_fight_cant_pk3']);
			}//m date check end
		}else
		{
			alertWindowMsg($wog_arry["f_time"].$lang['wog_fight_cant_fight1']);

		}//check act_time end
		unset($m,$p,$time);
		echo "parent.cd(".$wog_arry["f_time"].")\n";
	}
	function fire_mercenary($user_id,$me,$mission=array(),$mission_id=array())
	{
		global $DB_site,$_POST,$wogclass,$wog_arry,$wog_event_class,$lang,$m,$p,$_COOKIE;
		$time=time();
		setcookie("wog_cookie_mercenary",$time);
		$sql="select a.p_id,a.p_name,a.at,a.df,a.mat,a.mdf,a.s_property,a.agi,a.hp,a.hpmax
		,a.sp,a.spmax
		,a.p_sat_name,a.p_lv,a.p_exp,a.p_nextexp,a.life,a.smart,a.ch_id,a.p_st
		,a.au,a.t_id,a.p_bag,a.p_place
		,c.p_cp_st,c.hero_type
		,d.skill_1,d.skill_2,d.skill_3,d.skill_4,d.skill_5,0 as skill_6,d.time_1,d.time_2,d.time_3,d.time_4,d.time_5
		,a.au,a.be,a.vit,a.life,a.smart,a.agi,a.str,a.p_recomm
		from wog_player a
		left join wog_player_cp c on c.p_id=a.p_id
		left join wog_skill_setup d on d.p_id=a.p_id
		where a.p_id=".$user_id." and a.p_lock=0";
		$p=$DB_site->query_first($sql);
		if($p)//check act_timt benig
		{
			$p[hp]=$p[hpmax];
			$p[sp]=$p[spmax];
			if(!empty($p[hero_type]) && rand(10,1) == 1)
			{
				switch($m[hero_type]){
					case 2:
						$p[at]=$p[at]*1.1;
						break;
					case 3:
						$p[mat]=$p[mat]*1.1;
						break;
					case 4:
						$p[agi]=$p[agi]*1.1;
						break;
					case 5:
						;
						break;
				} // switch
			}
			
			$mission_chk=1;
			$mission_str=" and m_mission in (".implode(',',$mission_id).",0)";
			if( count($mission_id) < 1)
			{
				$mission_str="and m_mission=0";
				$mission_chk=0;
			}			
			$sql="select * from wog_monster where m_place in ($me[me_place]) and m_meet <= ".rand(0,100)." ".$mission_str." ORDER BY RAND() LIMIT 1 ";
			//$sql="select * from wog_monster where m_id=19 ORDER BY RAND() LIMIT 1 ";
			$m=$DB_site->query_first($sql);
			if($m)//m date check start
			{

				if($m["m_npc"]==1)
				{
					if($mission_chk==1 && $mission_id[$m['m_id']])
					{
						$mission[$m['m_mission']]['m_kill_num'][$m['m_id']]--;
						if($mission[$m['m_mission']]['m_kill_num'][$m['m_id']]<0)
						{
							$mission[$m['m_mission']]['m_kill_num'][$m['m_id']]=0;
						}
						$m_b=array();
						$t=false;
						foreach($mission[$m['m_mission']]['m_kill_num'] as $k => $v )
						{
							if($v >0){$t=true;}
							$m_b[]=$k."*".$v;
						}
						$str="0";
						if($t)
						{
							$str=implode(",",$m_b);
						}
						$sql="update wog_mission_book set m_kill_num='".$str."' where p_id=".$user_id." and m_id=".$mission_id[$m['m_id']];
						$DB_site->query($sql);
					}
				}else
				{
					$wog_arry["f_down"]=1;
					$m[hpmax]=$m[hp];
					$m[spmax]=$m[sp];
					$this->p_set_skill($p); //玩家技能設定
					$this->m_set_skill($m); //怪物技能設定
					
					$sql="select pe_id,pe_name,pe_at,pe_mt,pe_fu,pe_def,pe_hu,pe_type,pe_age,pe_he,pe_fi,pe_b_old,pe_mimg,pe_img_set,pe_img_url from wog_pet where pe_p_id=".$user_id." and pe_st=0";
					$pet=$DB_site->query_first($sql);
					if(!$pet)
					{
						$pet=NULL;
					}
					
					$wogclass->mercenary=1;
					$wogclass->mercenary_money=$me[me_get_money]/100;
					$wogclass->mercenary_setexp=$me[me_get_exp]/100;
					$wogclass->fight_count($user_id);
					if($wogclass->win > $wogclass->lost)
					{
						$me[me_count]-=1;
						if($mission_chk==1 && $mission_id[$m['m_id']])
						{

							$m['m_mission']=$mission_id[$m['m_id']];
							$mission[$m['m_mission']]['m_kill_num'][$m['m_id']]--;
							if($mission[$m['m_mission']]['m_kill_num'][$m['m_id']]<0)
							{
								$mission[$m['m_mission']]['m_kill_num'][$m['m_id']]=0;
							}
							$m_b=array();
							$t=false;
							foreach($mission[$m['m_mission']]['m_kill_num'] as $k => $v )
							{
								if($v >0){$t=true;}
								$m_b[]=$k."*".$v;
							}
							$str="0";
							if($t)
							{
								$str=implode(",",$m_b);
							}
							if(empty($str))
							{
								$sql="update wog_mission_book set m_kill_num='".$str."',m_status=1 where p_id=".$user_id." and m_id=".$m['m_mission'];
								$DB_site->query($sql);
							}
							else
							{
								$sql="update wog_mission_book set m_kill_num='".$str."' where p_id=".$user_id." and m_id=".$m['m_mission'];
								$DB_site->query($sql);
							}
						}						
						
						if(!empty($m[d_id]))
						{
							$wog_event_class->mercenary_topr=$me[me_get_item];
							$wog_event_class->get_item($user_id,$m[d_id],$m[m_topr],$p[p_st],$p[p_bag]);
						}
						$temp_str=sprintf($lang['wog_etc_mercenary_mg1'],$me[me_name],$m[m_name],$wogclass->money,$wogclass->mercenary_exp.$wog_event_class->mercenary_get);
						$sql="insert wog_mercenary_book(p_id,me_body,me_time)values($user_id,'".$temp_str."',".$time.")";						
					}
					else
					{
						$me[me_count]-=$me[me_die];
						$temp_str=sprintf($lang['wog_etc_mercenary_mg2'],$me[me_name],$m[m_name],$wogclass->money,$wogclass->mercenary_exp);
						$sql="insert wog_mercenary_book(p_id,me_body,me_time)values($user_id,'".$temp_str."',".$time.")";
					}
					$DB_site->query($sql);				
				}				

				$sql="delete from wog_mercenary_book where p_id=$user_id and me_time < ".($time-3600);
				$DB_site->query($sql);
				
				$mercenary_set="";
				if($me[me_count]<=0)
				{
					$sql="insert wog_message(p_id,title,dateline)values($user_id,'".sprintf($lang['wog_etc_mercenary_mg3'],$me[me_name])."',$time)";
					$DB_site->query($sql);
					$sql="delete from wog_mercenary_list where p_id=$user_id";
					$DB_site->query($sql);
					$sql="delete from wog_mercenary_book where p_id=$user_id";
					$DB_site->query($sql);
					$mercenary_set="parent.mercenary_set=0;";
				}
				else
				{
					$sql="update wog_mercenary_list set me_count=$me[me_count],me_fight_time=$time where id=$me[id]";
					$DB_site->query($sql);
				}
				$DB_site->query_first("COMMIT");
				showscript($mercenary_set."parent.mercenary_job_view('".$temp_str."')");
			}
		}//check act_time end
		unset($m,$p,$me,$time);
	}
	function p_set_skill($p)
	{
		global $DB_site,$wogclass;
		//## 人物技能設定 begin
		$p_skill_event=array();
		$p_skill_count=array();
		$p_skill_start=array();
		$p_skill_main=array();
		$sql="select main_sid,s_st,s_count,s_name,s_lv,s_sp,s_id,stime from wog_ch_skill where s_id in (".$p["skill_1"].",".$p["skill_2"].",".$p["skill_3"].",".$p["skill_4"].",".$p["skill_5"].",".$p["skill_6"].") ORDER BY RAND()";
		$temp=$DB_site->query($sql);
		while($temps=$DB_site->fetch_array($temp))
		{
			$p_skill_event[$temps["s_st"]][]=$temps["main_sid"];
			$p_skill_count[$temps["main_sid"]]=$temps["s_count"];
			$p_skill_start[$temps["main_sid"]]=0;
			$p_skill_main[$temps["main_sid"]]["name"]=$temps["s_name"]."LV".$temps["s_lv"];
			$p_skill_main[$temps["main_sid"]]["lv"]=$temps["s_lv"];
			$p_skill_main[$temps["main_sid"]]["sp"]=$temps["s_sp"];
			//$p_skill_main[$temps["main_sid"]]["type"]=$temps["type"];
			if($temps["s_id"]==$p["skill_1"]){$p_skill_main[$temps["main_sid"]]["stime"]=$p["time_1"];}
			if($temps["s_id"]==$p["skill_2"]){$p_skill_main[$temps["main_sid"]]["stime"]=$p["time_2"];}
			if($temps["s_id"]==$p["skill_3"]){$p_skill_main[$temps["main_sid"]]["stime"]=$p["time_3"];}
			if($temps["s_id"]==$p["skill_4"]){$p_skill_main[$temps["main_sid"]]["stime"]=$temps["stime"];}
			if($temps["s_id"]==$p["skill_5"]){$p_skill_main[$temps["main_sid"]]["stime"]=$temps["stime"];}
			if($temps["s_id"]==$p["skill_6"]){$p_skill_main[$temps["main_sid"]]["stime"]=$p["time_6"];$p_skill_main[$temps["main_sid"]]["sp"]=0;}
		}
		$DB_site->free_result($temps);
		//## 人物技能設定 end
		$wogclass->p_skill_event=$p_skill_event;
		$wogclass->p_skill_count=$p_skill_count;
		$wogclass->p_skill_start=$p_skill_start;
		$wogclass->p_skill_main=$p_skill_main;
		unset($temps,$temp,$p_skill_event,$p_skill_count,$p_skill_start,$p_skill_main);
	}
	function m_set_skill($m)
	{
		global $DB_site,$wogclass;
		//## 怪物技能設定 begin
		if(!empty($m["skill_1"]) || !empty($m["skill_2"]) || !empty($m["skill_3"]) || !empty($m["skill_4"]) || !empty($m["skill_5"]))
		{
			$m_skill_event=array();
			$m_skill_count=array();
			$m_skill_start=array();
			$m_skill_main=array();
			$sql="select main_sid,s_st,s_count,s_name,s_lv,s_sp,s_id,stime from wog_ch_skill where s_id in (".$m["skill_1"].",".$m["skill_2"].",".$m["skill_3"].",".$m["skill_4"].",".$m["skill_5"].") ORDER BY RAND()";
			$temp=$DB_site->query($sql);
			while($temps=$DB_site->fetch_array($temp))
			{
				$m_skill_event[$temps["s_st"]][]=$temps["main_sid"];
				$m_skill_count[$temps["main_sid"]]=$temps["s_count"];
				$m_skill_start[$temps["main_sid"]]=0;
				$m_skill_main[$temps["main_sid"]]["name"]=$temps["s_name"];
				$m_skill_main[$temps["main_sid"]]["lv"]=$temps["s_lv"]."LV".$temps["s_lv"];
				$m_skill_main[$temps["main_sid"]]["sp"]=$temps["s_sp"];
				//$m_skill_main[$temps["main_sid"]]["type"]=$temps["type"];
				if($temps["s_id"]==$m["skill_1"]){$m_skill_main[$temps["main_sid"]]["stime"]=$m["time_1"];}
				if($temps["s_id"]==$m["skill_2"]){$m_skill_main[$temps["main_sid"]]["stime"]=$m["time_2"];}
				if($temps["s_id"]==$m["skill_3"]){$m_skill_main[$temps["main_sid"]]["stime"]=$m["time_3"];}
				if($temps["s_id"]==$p["skill_4"]){$p_skill_main[$temps["main_sid"]]["stime"]=$temps["stime"];}
				if($temps["s_id"]==$p["skill_5"]){$p_skill_main[$temps["main_sid"]]["stime"]=$temps["stime"];}
			}
			$DB_site->free_result($temps);
			//## 怪物技能設定 end
			$wogclass->m_skill_event=$m_skill_event;
			$wogclass->m_skill_count=$m_skill_count;
			$wogclass->m_skill_start=$m_skill_start;
			$wogclass->m_skill_main=$m_skill_main;
			unset($temps,$temp,$m_skill_event,$m_skill_count,$m_skill_start,$m_skill_main);
		}
	}
}
?>