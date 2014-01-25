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

class wog_act_job{

	function job_view($user_id)
	{
		global $DB_site,$lang;
		$sql="select p_lv,ch_id
		from wog_player where  p_id=".$user_id;
		$pm=$DB_site->query_first($sql);
		if(empty($pm))
		{
			alertWindowMsg($lang['wog_act_relogin']);
		}
		$c_exp=$DB_site->query_first("SELECT * FROM wog_ch_exp WHERE p_id=".$user_id." ");
		$sql="select ch_id,ch_name,ch_str,ch_smart,ch_life,ch_vit,ch_agi,ch_au,ch_be,ch_map,ch_main
		from wog_character where ch_mlv <= ".$pm[p_lv]." order by ch_mlv,ch_main asc";
		$p=$DB_site->query($sql);
		$s="";
		$eq_job="0";
		$job_array=array();
		$job_array2=array();
		while($ps=$DB_site->fetch_array($p))
		{
			$job_array[]=$ps;
			$job_array2[$ps[ch_id]]=$ps[ch_name];
		}
		$DB_site->free_result($p);
		for($i=0;$i<count($job_array);$i++)
		{
			if($pm[ch_id]==$job_array[$i][ch_id])
			{
				$eq_job="1";
			}
			else
			{
				$eq_job="0";
			}
			$s=$s.";".$job_array[$i][ch_id]."|".$job_array[$i][ch_name]."|".$job_array[$i][ch_str]."|".$job_array[$i][ch_smart]."|".$job_array[$i][ch_life]."|".$job_array[$i][ch_vit]."|".$job_array[$i][ch_agi]."|".$job_array[$i][ch_au]."|".$job_array[$i][ch_be]."|".$c_exp["ch_".$job_array[$i][ch_id]]."|".$job_array[$i][ch_map]."|".$eq_job."|".$job_array2[$job_array[$i][ch_main]];
		}
		$s=substr($s,1,strlen($s));
		unset($ps);
		unset($job_array);
		if($s != "")
		{
			showscript("parent.job_view('$s')");
		}
		unset($s);
	}
	function job_setup($user_id,$job_id)
	{
		global $DB_site,$lang,$wog_item_tool,$a_id;
		if(empty($job_id))
		{
			alertWindowMsg($lang['wog_act_job_noselect']);
		}
		$sql="select agi,p_lv,str,smart,life,ch_id,be,base_life,base_be,base_smart,hp,sp from wog_player where p_id=".$user_id;
		$p=$DB_site->query_first($sql);

		$sql="select a_id,d_body_id,d_head_id,d_hand_id,d_foot_id,d_item_id,d_item_id2
			,s_a_id,s_body_id,s_head_id,s_hand_id,s_foot_id,s_item_id
			,p_a_id,p_body_id,p_head_id,p_hand_id,p_foot_id,p_item_id,d_item_num,d_item_num2 from wog_player_arm where p_id=".$user_id;
		$p4=$DB_site->query_first($sql);

		$sql="select ch_id,ch_name,ch_map,ch_main
		from wog_character where ch_id=".$job_id;
		$p3=$DB_site->query_first($sql);
		if($p3[ch_main] > 0)
		{
			$c_exp=$DB_site->query_first("SELECT ch_".$p3[ch_main]." FROM wog_ch_exp WHERE p_id=".$user_id);
			if($c_exp[0] < $p3[ch_map]){alertWindowMsg($lang['wog_act_job_expless']);}
		}
		if($p3)
		{
			if(($p4[a_id]+$p4[d_body_id]+$p4[d_head_id]+$p4[d_hand_id]+$p4[d_foot_id]+$p4[d_item_id]) > 0)
			{

					$sql="select a_id,d_body_id,d_head_id,d_hand_id,d_foot_id,d_item_id from wog_item where p_id=".$user_id."";
					$p2=$DB_site->query_first($sql);
					if(!empty($p2[a_id]))
					{
						if($p4[a_id]>0)$p2[a_id].=",".$p4[a_id];
					}else
					{
						if($p4[a_id]>0)$p2[a_id]=$p4[a_id];
					}
					if(!empty($p4[s_a_id]))
					{
						$p2[a_id].=":".$p4[s_a_id];
					}
					if(!empty($p4[p_a_id]))
					{
						$p2[a_id].="&".$p4[p_a_id];
					}
					if(!empty($p2[d_body_id]))
					{
						if($p4[d_body_id]>0) $p2[d_body_id].=",".$p4[d_body_id];
					}else
					{
						if($p4[d_body_id]>0) $p2[d_body_id]=$p4[d_body_id];
					}
					if(!empty($p4[s_body_id]))
					{
						$p2[d_body_id].=":".$p4[s_body_id];
					}
					if(!empty($p4[p_body_id]))
					{
						$p2[d_body_id].="&".$p4[p_body_id];
					}
					if(!empty($p2[d_head_id]))
					{
						if($p4[d_head_id]>0) $p2[d_head_id].=",".$p4[d_head_id];
					}else
					{
						if($p4[d_head_id]>0) $p2[d_head_id]=$p4[d_head_id];
					}
					if(!empty($p4[s_head_id]))
					{
						$p2[d_head_id].=":".$p4[s_head_id];
					}
					if(!empty($p4[p_head_id]))
					{
						$p2[d_head_id].="&".$p4[p_head_id];
					}
					if(!empty($p2[d_hand_id]))
					{
						if($p4[d_hand_id]>0) $p2[d_hand_id].=",".$p4[d_hand_id];
					}else
					{
						if($p4[d_hand_id]>0) $p2[d_hand_id]=$p4[d_hand_id];
					}
					if(!empty($p4[s_hand_id]))
					{
						$p2[d_hand_id].=":".$p4[s_hand_id];
					}
					if(!empty($p4[p_hand_id]))
					{
						$p2[d_hand_id].="&".$p4[p_hand_id];
					}
					if($p2[d_foot_id]!="")
					{
						if($p4[d_foot_id]>0) $p2[d_foot_id].=",".$p4[d_foot_id];
					}else
					{
						if($p4[d_foot_id]>0) $p2[d_foot_id]=$p4[d_foot_id];
					}
					if(!empty($p4[s_foot_id]))
					{
						$p2[d_foot_id].=":".$p4[s_foot_id];
					}
					if(!empty($p4[p_foot_id]))
					{
						$p2[d_foot_id].="&".$p4[p_foot_id];
					}
					if($p2[d_item_id]!="")
					{
						$a_id="d_item_id";
						$p2[d_item_id]=explode(",",$p2[d_item_id]);
						if($p4[d_item_id]>0)
						{
							$p2[d_item_id]=$wog_item_tool->item_in($p2[d_item_id],$p4[d_item_id],$p4[d_item_num]);
						}
						if($p4[d_item_id2]>0)
						{
							$p2[d_item_id]=$wog_item_tool->item_in($p2[d_item_id],$p4[d_item_id2],$p4[d_item_num2]);
						}
					}else
					{
						$p2[d_item_id]=array();
						if($p4[d_item_id]>0) $p2[d_item_id][]=$p4[d_item_id]."*".$p4[d_item_num];
						if($p4[d_item_id2]>0) $p2[d_item_id][]=$p4[d_item_id2]."*".$p4[d_item_num2];
					}

					$sql="update wog_item set a_id='".$p2[a_id]."',d_body_id='".$p2[d_body_id]."',d_head_id='".$p2[d_head_id]."',d_hand_id='".$p2[d_hand_id]."',d_foot_id='".$p2[d_foot_id]."',d_item_id='".implode(',',$p2[d_item_id])."' where p_id=".$user_id;
					$DB_site->query($sql);
			}

			$hpmax=player_hp($p[base_life]);
			$spmax=player_sp($p[base_smart]+$p[base_be]);

			if($p[hp]>$hpmax){$p[hp]=$hpmax;}
			if($p[sp]>$spmax){$p[sp]=$spmax;}
			$DB_site->query("update wog_player set ch_id=".$job_id.",at=base_str,mat=base_smart,df=base_vit,mdf=base_be,agi=base_agi
							,hpmax=$hpmax,au=base_au,be=base_be,vit=base_vit,smart=base_smart,str=base_str
							,life=base_life,spmax=$spmax,hp=$p[hp],sp=$p[sp]
							where p_id=".$user_id);
			$DB_site->query("update wog_player set ch_id=".$job_id." where p_id=".$user_id);
			$DB_site->query("update wog_player_arm set a_id=0,d_body_id=0,d_head_id=0,d_hand_id=0,d_foot_id=0,s_a_id=0,s_body_id=0,s_head_id=0,s_hand_id=0,s_foot_id=0,s_item_id=0
							,d_item_id=0,d_item_id2=0,d_item_num=0,d_item_num2=0
							where p_id=".$user_id);
			$DB_site->query("update wog_skill_setup set skill_4=0,skill_5=0,time_4=0,time_5=0 where p_id=".$user_id);
			showscript("parent.d_ch_name='".$p3[ch_name]."';parent.job_end(2)");
		}else
		{
			alertWindowMsg($lang['wog_act_job_err']);
		}
		unset($p,$p2,$p3);
	}
}
?>