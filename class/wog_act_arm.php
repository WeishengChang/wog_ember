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

class wog_act_arm{
	function arm_view($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		$s="";
		$my_item="";
		$item_type=$_POST["temp_id"];
		$limit_item=0;
		if(!isset($item_type)){alertWindowMsg($lang['wog_act_arm_noselect']);}
		$item_type2=type_name($item_type);
		$sql="select ".$item_type2." from wog_item where p_id=".$user_id;

		$pack=$DB_site->query_first($sql);
		if(!empty($pack[0]))
		{
			$temp_item=array(); //裝備id
			$temp_item2=array(); //鑲嵌id,道具類數量
			$temp_item3=array(); //精練id
			$temp_item9=array();

			$packs=explode(",",$pack[0]);
			$sum=count($packs);

			if($item_type>=5)
			{
				for($i=0;$i<$sum;$i++)
				{
					$packss=explode("*",$packs[$i]);
					$temp_item[]=$packss[0];
					$temp_item2[$i]=$packss[1];
					$temp_item9[$i][0]=$packss[0];
					$temp_item9[$i][3]=0;
					$temp_item9[$i][4]=0;
					$temp_item9[$i][5]=0;
				}
			}else
			{
				$arm_array=get_arm_sp($packs,$sum,$temp_item,$temp_item9);
			}
			$item_array=array();
			$item_array2=array();
			switch($item_type)
			{
				case 7:
					$item_array2=array();
					$temp_str=$DB_site->query("select a.d_id,a.s_at,a.s_mat,a.s_df,a.s_mdf,a.s_agl,a.s_str,a.s_life,a.s_vit,a.s_smart,a.s_au,a.s_be,a.s_hpmax,b.d_send,b.d_money,b.d_name
							from wog_stone_list a,wog_df b where a.d_id in (".implode(",",$temp_item).") and a.d_id=b.d_id ");
					while($p2=$DB_site->fetch_array($temp_str))
					{
						//$item_array2[]=$temp_strs[d_id].",".$temp_strs[s_at].",".$temp_strs[s_mat].",".$temp_strs[s_df].",".$temp_strs[s_mdf].",".$temp_strs[s_agl].",".$temp_strs[s_str].",".$temp_strs[s_life].",".$temp_strs[s_vit].",".$temp_strs[s_smart].",".$temp_strs[s_au].",".$temp_strs[s_be].",".$temp_strs[d_send].",".$temp_strs[d_name]."*".$temp_item2[$temp_strs[d_id]]).",".$temp_strs[d_hole].",".$temp_strs[d_money];
						$d_status[$p2[d_id]][s_at]=$p2[s_at];
						$d_status[$p2[d_id]][s_mat]=$p2[s_mat];
						$d_status[$p2[d_id]][s_df]=$p2[s_df];
						$d_status[$p2[d_id]][s_mdf]=$p2[s_mdf];
						$d_status[$p2[d_id]][s_agl]=$p2[s_agl];
						$d_status[$p2[d_id]][s_str]=$p2[s_str];
						$d_status[$p2[d_id]][s_life]=$p2[s_life];
						$d_status[$p2[d_id]][s_vit]=$p2[s_vit];
						$d_status[$p2[d_id]][s_smart]=$p2[s_smart];
						$d_status[$p2[d_id]][s_au]=$p2[s_au];
						$d_status[$p2[d_id]][s_be]=$p2[s_be];
						$d_status[$p2[d_id]][s_hpmax]=$p2[s_hpmax];
						$d_status[$p2[d_id]][d_send]=$p2[d_send];
						$d_status[$p2[d_id]][d_name]=$p2[d_name];
						$d_status[$p2[d_id]][d_money]=$p2[d_money];
					}
					$DB_site->free_result($temp_str);
					for($i=0;$i<$sum;$i++)
					{

						$item_array=$d_status[$temp_item9[$i][0]];
						$item_array[d_name].="*".$temp_item2[$i];
						$item_array2[$i]=$temp_item9[$i][0].","
							.$item_array[s_at].","//1
							.$item_array[s_mat].","
							.$item_array[s_df].","
							.$item_array[s_mdf].","
							.$item_array[s_agl]."," //5
							.$item_array[s_str].","
							.$item_array[s_life].","
							.$item_array[s_vit].","
							.$item_array[s_smart].","
							.$item_array[s_au]."," //10
							.$item_array[s_be].","
							.$item_array[life]."," //12
							.$item_array[d_send]."," //13
							.$item_array[d_name]."," //14
							.$item_array[d_money]."," //15
							.$item_array[s_hpmax]
							;
					}
					break;
				default:

					$temp_str=$DB_site->query("select a.d_id,a.d_df,a.d_mdf,a.d_money,a.d_name,a.d_at,a.d_mat,a.d_mstr,a.d_magl,a.d_msmart,a.d_mau,b.ch_name,a.d_send,a.d_hole,ifnull(a.d_s,0) as d_s
							,a.d_g_str,a.d_g_smart,a.d_g_agi,a.d_g_life,a.d_g_vit,a.d_g_au,a.d_g_be,a.skill,a.d_plus
							from wog_df a left join wog_character b on b.ch_id=a.ch_id  where a.d_id in (".implode(",",$temp_item).")");

					while($p2=$DB_site->fetch_array($temp_str))
					{
						if($p2[skill]>0)
						{
							$sql="select a.s_name
								from wog_ch_skill a where a.s_id=".$p2[skill];
							$skill_main=$DB_site->query_first($sql);
						}
						else
						{
							$skill_main[s_name]="";
						}
						$d_status[$p2[d_id]][d_name]=$p2[d_name];
						$d_status[$p2[d_id]][s_name]=$skill_main[s_name];

						$d_status[$p2[d_id]][at]=$p2[d_at];
						$d_status[$p2[d_id]][mat]=$p2[d_mat];
						$d_status[$p2[d_id]][df]=$p2[d_df];
						$d_status[$p2[d_id]][mdf]=$p2[d_mdf];
						$d_status[$p2[d_id]][agi]=$p2[d_g_agi];
						$d_status[$p2[d_id]][life]=$p2[d_g_life];
						$d_status[$p2[d_id]][au]=$p2[d_g_au];
						$d_status[$p2[d_id]][be]=$p2[d_g_be];
						$d_status[$p2[d_id]][vit]=$p2[d_g_vit];
						$d_status[$p2[d_id]][smart]=$p2[d_g_smart];
						$d_status[$p2[d_id]][str]=$p2[d_g_str];

						$d_status[$p2[d_id]][d_mstr]=$p2[d_mstr];
						$d_status[$p2[d_id]][d_magl]=$p2[d_magl];
						$d_status[$p2[d_id]][d_msmart]=$p2[d_msmart];
						$d_status[$p2[d_id]][d_mau]=$p2[d_mau];
						$d_status[$p2[d_id]][d_send]=$p2[d_send];
						$d_status[$p2[d_id]][stone_name]="";
						$d_status[$p2[d_id]][ch_name]=$p2[ch_name];
						$d_status[$p2[d_id]][d_money]=$p2[d_money];
						$d_status[$p2[d_id]][d_s]=$p2[d_s];
						$d_status[$p2[d_id]][d_hole]=$p2[d_hole];
						$d_status[$p2[d_id]][d_plus]=$p2[d_plus];
					}
					$DB_site->free_result($temp_str);
					unset($p2,$temp_item);

					for($i=0;$i<$sum;$i++)
					{
						$len=$temp_item9[$i][3];
						$len2=$temp_item9[$i][4];
						$len3=$temp_item9[$i][5];
						switch(true)
						{
							case $len==$len2 && $len==$len3: //無鑲嵌,無精練
								$item_array=$d_status[$temp_item9[$i][0]];
							break;
							case $len!=$len2 && $len==$len3: //有鑲嵌,無精練
								$item_array=chk_item_status($d_status[$temp_item9[$i][0]],$arm_array[hs][$temp_item9[$i][1]],null);
							break;
							case $len==$len2 && $len!=$len3: //無鑲嵌,有精練
								$item_array=chk_item_status($d_status[$temp_item9[$i][0]],null,$arm_array[ps][$temp_item9[$i][2]]);
							break;
							case $len!=$len2 && $len!=$len3: //有鑲嵌,有精練
								$item_array=chk_item_status($d_status[$temp_item9[$i][0]],$arm_array[hs][$temp_item9[$i][1]],$arm_array[ps][$temp_item9[$i][2]]);
							break;
						}
						if($item_type>=5){$item_array[d_name].="*".$temp_item2[$i];}
						$item_array2[$i]=$temp_item9[$i][0].",".$packs[$i].","
							.$item_array[d_name].","//2
							.$item_array[at].","
							.$item_array[mat].","
							.$item_array[df].","
							.$item_array[mdf]."," //6
							.$item_array[str].","
							.$item_array[smart].","
							.$item_array[agi].","
							.$item_array[au].","
							.$item_array[vit].","
							.$item_array[be].","
							.$item_array[life]."," //13
							.$item_array[s_name]."," //14
							.$item_array[stone_name]."," //15
							.$item_array[d_mstr].","
							.$item_array[d_magl]."," //17
							.$item_array[d_msmart].","
							.$item_array[d_mau].","
							.$item_array[d_send]."," //20
							.$item_array[ch_name].","
							.$item_array[d_money]."," //22
							.$item_array[d_s].","
							.$item_array[d_hole]."," //24
							.$item_array[d_plus]."," //25
							.$item_array[hp] //26
							;
					}
				break;
			}
			unset($item_array,$d_status,$arm_array,$temp_item9,$packs);
		}
		
		// 取出目前穿在身上的裝備數值 begin
		if($item_type<=5)
		{
			$arm_array=array();
			$item_array=array();
			$my_item_array=array();
			$d_item_num="";
			$s_id="";
			$p_id="";
			$s_id2="";
			$p_id2="";
			get_type_name($item_type,$s_id,$p_id);
			if($item_type==5)
			{
				$d_item_num=",d_item_num,d_item_num2";
				$item_type2.=",d_item_id2";
			}else
			{
				$s_id2=",".$s_id;
				$p_id2=",".$p_id;
			}

			$sql="select ".$item_type2.$d_item_num.$s_id2.$p_id2." from wog_player_arm where p_id=".$user_id;
			$p=$DB_site->query_first($sql);
			if(empty($p))
			{
				alertWindowMsg($lang['wog_act_relogin']);
			}
			$bag_item_id=$p[0];

			if(($item_type<5 && !empty($p[0])) || ($item_type==5 && (!empty($p[0]) || !empty($p[1]))))
			{

				if(!empty($p[$s_id]))
				{
					$bag_item_id.=":".$p[$s_id];
					$temp_str=$DB_site->query("select b.hs_id,a.d_name
							from wog_df a,wog_stone_setup b where b.p_id=$user_id and b.hs_id =".$p[$s_id]." and a.d_id in (b.hole_1,b.hole_2,b.hole_3,b.hole_4)");
					while($p2=$DB_site->fetch_array($temp_str))
					{
						$arm_array[hs][$p[0]][d_name].="　".$p2[d_name];
					}
					$DB_site->free_result($temp_str);

					$sql="select ifnull(sum(a.s_df),0) as s_df,ifnull(sum(a.s_mdf),0) as s_mdf,ifnull(sum(a.s_agl),0) as s_agl,ifnull(sum(a.s_at),0) as s_at,ifnull(sum(a.s_mat),0) as s_mat
						,ifnull(sum(a.s_str),0) as s_str,ifnull(sum(a.s_life),0) as s_life,ifnull(sum(a.s_vit),0) as s_vit,ifnull(sum(a.s_smart),0) as s_smart,ifnull(sum(a.s_au),0) as s_au,ifnull(sum(a.s_be),0) as s_be
						,ifnull(sum(a.s_hpmax),0) as s_hpmax
						from wog_stone_list a,wog_stone_setup b where b.p_id=$user_id and b.hs_id =".$p[$s_id]." and a.d_id in (b.hole_1,b.hole_2,b.hole_3,b.hole_4) and a.d_class <99";
					$p2=$DB_site->query_first($sql);
					$arm_array[hs][$p[0]][at]=$p2[s_at];
					$arm_array[hs][$p[0]][mat]=$p2[s_mat];
					$arm_array[hs][$p[0]][df]=$p2[s_df];
					$arm_array[hs][$p[0]][mdf]=$p2[s_mdf];
					$arm_array[hs][$p[0]][agi]=$p2[s_agl];
					$arm_array[hs][$p[0]][life]=$p2[s_life];
					$arm_array[hs][$p[0]][au]=$p2[s_au];
					$arm_array[hs][$p[0]][be]=$p2[s_be];
					$arm_array[hs][$p[0]][vit]=$p2[s_vit];
					$arm_array[hs][$p[0]][smart]=$p2[s_smart];
					$arm_array[hs][$p[0]][str]=$p2[s_str];
					$arm_array[hs][$p[0]][hp]=$p2[s_hpmax];

					$sql="select ifnull(sum(a.s_df),0) as s_df,ifnull(sum(a.s_mdf),0) as s_mdf,ifnull(sum(a.s_agl),0) as s_agl,ifnull(sum(a.s_at),0) as s_at,ifnull(sum(a.s_mat),0) as s_mat
						,ifnull(sum(a.s_str),0) as s_str,ifnull(sum(a.s_life),0) as s_life,ifnull(sum(a.s_vit),0) as s_vit,ifnull(sum(a.s_smart),0) as s_smart,ifnull(sum(a.s_au),0) as s_au,ifnull(sum(a.s_be),0) as s_be
						,ifnull(sum(a.s_hpmax),0) as s_hpmax
						from wog_stone_temp a,wog_stone_setup b where b.p_id=$user_id and b.hs_id =".$p[$s_id]." and a.ht_id in (b.hole_temp_1,b.hole_temp_2,b.hole_temp_3,b.hole_temp_4)";

					$p2=$DB_site->query_first($sql);
					$arm_array[hs][$p[0]][at]+=$p2[s_at];
					$arm_array[hs][$p[0]][mat]+=$p2[s_mat];
					$arm_array[hs][$p[0]][df]+=$p2[s_df];
					$arm_array[hs][$p[0]][mdf]+=$p2[s_mdf];
					$arm_array[hs][$p[0]][agi]+=$p2[s_agl];
					$arm_array[hs][$p[0]][life]+=$p2[s_life];
					$arm_array[hs][$p[0]][au]+=$p2[s_au];
					$arm_array[hs][$p[0]][be]+=$p2[s_be];
					$arm_array[hs][$p[0]][vit]+=$p2[s_vit];
					$arm_array[hs][$p[0]][smart]+=$p2[s_smart];
					$arm_array[hs][$p[0]][str]+=$p2[s_str];
					$arm_array[hs][$p[0]][hp]+=$p2[s_hpmax];
					
				}
				else
				{
					$arm_array[hs]=null;
				}
				if(!empty($p[$p_id]))
				{
					$bag_item_id.="&".$p[$p_id];
					$p2=$DB_site->query_first("select b.ps_id,b.plus_num,b.d_at,b.d_mat,b.d_df,b.d_mdf,b.d_str,b.d_agi,b.d_smart,b.d_life,b.d_vit,b.d_au,b.d_be from wog_plus_setup b where b.p_id=$user_id and b.ps_id =".$p[$p_id]);
					$arm_array[ps][$p[0]][plus_num]=$p2[plus_num];
					$arm_array[ps][$p[0]][at]=$p2[d_at];
					$arm_array[ps][$p[0]][mat]=$p2[d_mat];
					$arm_array[ps][$p[0]][df]=$p2[d_df];
					$arm_array[ps][$p[0]][mdf]=$p2[d_mdf];
					$arm_array[ps][$p[0]][agi]=$p2[d_agi];
					$arm_array[ps][$p[0]][life]=$p2[d_life];
					$arm_array[ps][$p[0]][au]=$p2[d_au];
					$arm_array[ps][$p[0]][be]=$p2[d_be];
					$arm_array[ps][$p[0]][vit]=$p2[d_vit];
					$arm_array[ps][$p[0]][smart]=$p2[d_smart];
					$arm_array[ps][$p[0]][str]=$p2[d_str];
					$DB_site->free_result($temp_str);
				}
				else
				{
					$arm_array[ps]=null;
				}
				if($item_type==5)
				{
					$where_sql="a.d_id in (".$p[0].",".$p[1].")";
				}else
				{
					$where_sql="a.d_id =".$p[0];
				}
				$sql="select a.d_id,a.d_df,a.d_mdf,a.d_money,a.d_name,a.d_at,a.d_mat,b.ch_name,ifnull(a.d_s,0) as d_s
							,a.d_g_str,a.d_g_smart,a.d_g_agi,a.d_g_life,a.d_g_vit,a.d_g_au,a.d_g_be,a.skill,a.d_hole,a.d_plus
							from wog_df a left join wog_character b on b.ch_id=a.ch_id  where ".$where_sql;
				$temp_str=$DB_site->query($sql);
				while($p2=$DB_site->fetch_array($temp_str))
				{
					if($p2[skill]>0)
					{
						$sql="select a.s_name
							from wog_ch_skill a where a.s_id=".$p2[skill];
						$skill_main=$DB_site->query_first($sql);
					}
					else
					{
						$skill_main[s_name]="";
					}
					$d_status[$p2[d_id]][item_type]=$item_type;
					if($item_type==5)
					{
						switch($p2[d_id])
						{
							case $p[0]:
								$p2[d_name].="*".$p[d_item_num];
								$d_status[$p2[d_id]][item_type]=5;
							break;
							case $p[1]:
								$p2[d_name].="*".$p[d_item_num2];
								$d_status[$p2[d_id]][item_type]=11;
							break;
						}
					}
					$d_status[$p2[d_id]][d_name]=$p2[d_name];
					$d_status[$p2[d_id]][s_name]=$skill_main[s_name];

					$d_status[$p2[d_id]][at]=$p2[d_at];
					$d_status[$p2[d_id]][mat]=$p2[d_mat];
					$d_status[$p2[d_id]][df]=$p2[d_df];
					$d_status[$p2[d_id]][mdf]=$p2[d_mdf];
					$d_status[$p2[d_id]][agi]=$p2[d_g_agi];
					$d_status[$p2[d_id]][life]=$p2[d_g_life];
					$d_status[$p2[d_id]][au]=$p2[d_g_au];
					$d_status[$p2[d_id]][be]=$p2[d_g_be];
					$d_status[$p2[d_id]][vit]=$p2[d_g_vit];
					$d_status[$p2[d_id]][smart]=$p2[d_g_smart];
					$d_status[$p2[d_id]][str]=$p2[d_g_str];

					$d_status[$p2[d_id]][stone_name]="";
					$d_status[$p2[d_id]][ch_name]=$p2[ch_name];
					$d_status[$p2[d_id]][d_money]=$p2[d_money];
					$d_status[$p2[d_id]][d_s]=$p2[d_s];
					$d_status[$p2[d_id]][d_hole]=$p2[d_hole];
					$d_status[$p2[d_id]][d_plus]=$p2[d_plus];

					$item_array=chk_item_status($d_status[$p2[d_id]],$arm_array[hs][$p[0]],$arm_array[ps][$p[0]]);
					$my_item_array[]=$item_array[d_name].","
						.$item_array[at].","
						.$item_array[mat].","
						.$item_array[df].","
						.$item_array[mdf]."," //4
						.$item_array[str].","
						.$item_array[smart].","
						.$item_array[agi].","
						.$item_array[au].","
						.$item_array[vit].","
						.$item_array[be].","
						.$item_array[life]."," //11
						.$item_array[s_name]."," //12
						.$item_array[stone_name]."," //13
						.$item_array[d_send]."," //14
						.$item_array[ch_name].","
						.$item_array[d_money]."," //16
						.$item_array[d_s].","
						.$item_array[d_hole]."," //18
						.$item_array[d_plus]."," //19
						.$bag_item_id."," //20
						.$item_array[item_type]."," //21
						.$item_array[hp] //22
						;
				}
			}
		}
		// 取出目前穿在身上的裝備數值 end
		// 取出背包容量 begin
		if($item_type>=5)
		{
			$sql="select p_bag from wog_player where p_id=$user_id";
			$p=$DB_site->query_first($sql);
			$limit_item=$p[p_bag]+$wog_arry[item_limit];
		}else
		{
			$limit_item=$wog_arry[item_limit];
		}
		// 取出背包容量 end
		$list="";
		if(count($item_array2)>0)
		{
			asort($item_array2);
			$list=implode(";",$item_array2);
		}
		unset($pack,$temp,$item_array,$item_array2);
		$return_val="";
		if(!empty($_POST["return_val"]))
		{
			$return_val="parent.arm_use(".$_POST["return_val"].")";
		}
		if(count($my_item_array)>0)
		{
			$my_item=implode(";",$my_item_array);
		}
		showscript("parent.arm_view('".$list."',".$_POST["temp_id"].",'".$my_item."',$limit_item);".$return_val);
	}

	function arm_setup($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_item_tool;
		if(empty($_POST["adds"]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$bag_item_id=$_POST["adds"];
		$item_sql="";

		$temp_type=$_POST["temp_type"];
		$a_id=type_name($temp_type);
		get_arm_id($bag_item_id,$item_id,$hs_id,$ps_id);

		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");

		$sql="select ".$a_id." from wog_item where p_id=".$user_id." for update";
		$item=$DB_site->query_first($sql);
		if($item[0]=="")
		{
			alertWindowMsg($lang['wog_act_errwork']);
		}else
		{
			$sql="select a.d_df,a.d_mdf,a.d_at,a.d_mat,a.d_name,a.d_type,a.d_g_hp,a.d_g_sp,b.life,b.smart,b.be
				,a.d_g_str,a.d_g_smart,a.d_g_agi,a.d_g_life,a.d_g_vit,a.d_g_au,a.d_g_be,a.d_vip
				 from wog_df a,wog_player b where a.d_id=".$item_id." and b.p_id=".$user_id." and (a.ch_id=b.ch_id or a.ch_id=0) and b.base_agi>=a.d_magl and b.base_str>=a.d_mstr and b.base_smart>=a.d_msmart and b.base_au>=a.d_mau";
			$pack=$DB_site->query_first($sql);
			if($pack)
			{
				if($pack[d_type]==6)
				{
					$items=array();
					if(!empty($item[0]))
					{
						$items=explode(",",$item[0]);
					}
					$items=$wog_item_tool->item_out($user_id,$item_id,1,$items);
					$DB_site->query("update wog_item set ".$a_id."='".implode(',',$items)."' where p_id=".$user_id);

					unset($items);
					$return_val=$this->arm_item_use($user_id,$item_id,$pack);

					$DB_site->query_first("COMMIT");
					check_arm_status($user_id);
					$_POST["temp_id"]=$temp_type;
					$_POST["return_val"]=$return_val;
					$this->arm_view($user_id);
					//showscript("parent.act_click('arm','view',".$temp_type.")");
				}else
				{

					$s_id="";
					$p_id="";
					get_type_name($temp_type,$s_id,$p_id);
					$item_num=$_POST["item_num"];
					if($item_num<1 || $item_num >99){alertWindowMsg($lang['wog_main_error2']);}
					if($pack[d_type]==11)
					{
						$a_id2="d_item_id2";
						$item_sql=",d_item_num2=".$item_num;
						$sql="select a.d_id,b.d_item_num2 as d_item_num,b.".$s_id.",b.".$p_id."
						from wog_df a,wog_player_arm b where b.".$a_id2."=a.d_id and b.p_id=".$user_id;
					}else
					{
						$a_id2=$a_id;
						$item_sql=",d_item_num=".$item_num;
						$sql="select a.d_id,b.d_item_num,b.".$s_id.",b.".$p_id."
						from wog_df a,wog_player_arm b where b.".$a_id2."=a.d_id and b.p_id=".$user_id;
					}

					$pack2=$DB_site->query_first($sql);

					if($a_id=="d_item_id")
					{
//						if(empty($item_num)){alertWindowMsg($lang['wog_act_errwork']);}
//						if($item_num > 99){alertWindowMsg($lang['wog_act_errwork']);}
						$pack[d_name].="*".$item_num;
						$s=$this->arm_item($item[0],$item_id,$pack2,$item_num);
					}else
					{
						$a_id2=$a_id;
						if($pack2[$s_id] > 0)
						{
							$pack2[d_id].=":".$pack2[$s_id];
						}
						if(!empty($ps_id))
						{
							$sql="select b.plus_num from wog_plus_setup b where b.ps_id =$ps_id";
							$p2=$DB_site->query_first($sql);
						}
						if($pack2[$p_id] > 0)
						{
							$pack2[d_id].="&".$pack2[$p_id];
						}
						$s=$this->arm_equit($item[0],$bag_item_id,$pack2);
					}
/*
					if($pack[ch_id]>0)
					{
						$sql="select ch_".$pack[ch_id]." from wog_ch_exp where p_id=".$user_id;
						$ch=$DB_site->query_first($sql);
						if($ch[0] < $pack[ch_pro]){alertWindowMsg($lang['wog_act_arm_nosetup2']);}
						unset($ch);
					}
*/
					$DB_site->query("update wog_item set ".$a_id."='".$s."' where p_id=".$user_id);
					$DB_site->query("update wog_player_arm set ".$a_id2."=".$item_id.",".$s_id."=".$hs_id.",".$p_id."=".$ps_id.$item_sql." where p_id=".$user_id);
					$DB_site->query_first("COMMIT");
					check_arm_status($user_id);
					if($p2){$pack[d_name].="+".$p2[plus_num];}
					showscript("parent.arm_setup('".$a_id2."','".$pack[d_name]."','".$bag_item_id."');parent.act_click('arm','view',".$temp_type.")");
				}
			}else
			{
				$DB_site->query_first("COMMIT");
				alertWindowMsg($lang['wog_act_arm_nosetup']);
			}
		}
		unset($pack,$packs,$pack2,$adds,$s,$item,$a_id);
	}

	function arm_unsetup($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_item_tool;
		if(!isset($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_noid']);
		}
		$temp_type=$_POST["temp_id"];
		$a_id=type_name($temp_type);
		$s_id="";
		$p_id="";
		get_type_name($temp_type,$s_id,$p_id);
		$s_id2=",".$s_id;
		$p_id2=",".$p_id;

		$item_num_name="";
		$a_id2=$a_id;
		if($a_id=="d_item_id"){$item_num_name="d_item_num";}
		if($a_id=="d_item_id2"){$item_num_name="d_item_num2";}

		$sql="select ".$a_id.$s_id2.$p_id2.",d_item_num,d_item_num2 from wog_player_arm where p_id=".$user_id;
		$check_item=$DB_site->query_first($sql);
		if(empty($check_item) || empty($check_item[0]))
		{
			alertWindowMsg($lang['wog_act_relogin']);
		}

		$item_id=$check_item[0];
		$hs_id=$check_item[$s_id];
		$ps_id=$check_item[$p_id];
		$d_item_num=$check_item[$item_num_name];

		if($a_id=="d_item_id" || $a_id=="d_item_id2")
		{
			$sql="update wog_player_arm set ".$a_id."=0,".$s_id."=0,".$p_id."=0,".$item_num_name."=0 where p_id=".$user_id;
		}
		else
		{
			$sql="update wog_player_arm set ".$a_id."=0,".$s_id."=0,".$p_id."=0 where p_id=".$user_id;
		}
		$DB_site->query($sql);

		if(!empty($hs_id))
		{
			$item_id.=":".$hs_id;
		}
		if(!empty($ps_id))
		{
			$item_id.="&".$ps_id;
		}
		if($temp_type=="11"){$a_id="d_item_id";$temp_type=5;}
		$sql="select ".$a_id." from wog_item where p_id=".$user_id;
		$check_item=$DB_site->query_first($sql);
		if(!empty($check_item[0]))
		{
			$check_item=explode(",",$check_item[0]);
			if($a_id=="d_item_id" || $a_id=="d_item_id2")
			{
				$check_item=$wog_item_tool->item_in($check_item,$item_id,$d_item_num);
			}
			else
			{
				$check_item=$wog_item_tool->item_in($check_item,$item_id,1);
			}
		}
		else{
			unset($check_item);
			$check_item = array();
			if($a_id=="d_item_id")
			{
				$check_item[]=$item_id."*".$d_item_num;
			}
			else
			{
				$check_item[]=$item_id;
			}
		}
		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$check_item)."' where p_id=".$user_id);
		unset($p2,$check_item,$p3);
		check_arm_status($user_id);
		showscript("parent.arm_setup('".$a_id2."','');parent.act_click('arm','view',".$temp_type.")");
	}

	function arm_move($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_arry,$temp_ss,$wog_item_tool;
		if(empty($_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_noid']);
		}
		if(preg_match("/[<>'\", ;]/", $_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_errword']);
		}
		$_POST["pay_id"]=trim($_POST["pay_id"]);
		if(empty($_POST["adds"]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}

		$a_id=type_name($_POST["temp_type"]);
		$bag_item_id=$_POST["adds"];
		$item_num=(int)$_POST["item_num"];
		if($item_num <=0 || $item_num >99 || preg_match("/[^0-9]/",$item_num)){alertWindowMsg($lang['wog_act_errnum']);}

		get_arm_id($bag_item_id,$item_id,$hs_id,$ps_id);
		$temp_ss=$wog_item_tool->item_out($user_id,$bag_item_id,$item_num);
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select p_id,p_bag from wog_player where p_name='".htmlspecialchars($_POST["pay_id"])."'";
		$pay_id=$DB_site->query_first($sql);
		if(!$pay_id)
		{
			alertWindowMsg($lang['wog_act_arm_nomove']);
		}
		if($pay_id[0]==$user_id)
		{
			alertWindowMsg($lang['wog_act_arm_nomove_me']);
		}

		$sql="select d_name,d_send,d_fie from wog_df where d_id=".$item_id;
		$pay=$DB_site->query_first($sql);
		$d_fie=$pay[d_fie];
		if($pay[1]==1)
		{
			alertWindowMsg($lang['wog_act_arm_nosend']);
		}

		$d_name=$pay[0];
		$sql="select p_name from wog_player where p_id=".$user_id;
		$pay=$DB_site->query_first($sql);
		$p_name=$pay[0];
		$sql="select ".$a_id." from wog_item where p_id=".$pay_id[0]." for update";
		$pay=$DB_site->query_first($sql);
		$temp_pack=array();
		if(!empty($pay[0]))
		{
			$temp_pack=explode(",",$pay[0]);
		}
		$adds=$wog_item_tool->item_in($temp_pack,$bag_item_id,$item_num);
		if($d_fie>=5)
		{
			$bag=$wog_arry["item_limit"]+$pay_id[1];
		}else
		{
			$bag=$wog_arry["item_limit"];
		}

		if(count($adds) > $bag)
		{
			alertWindowMsg($lang['wog_act_bid_full']);
		}

		if(!empty($hs_id))
		{
			$DB_site->query("update wog_stone_setup set p_id=".$pay_id[0]." where hs_id=".$hs_id);
		}
		if(!empty($ps_id))
		{
			$DB_site->query("update wog_plus_setup set p_id=".$pay_id[0]." where ps_id=".$ps_id);
		}
		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$adds)."' where p_id=".$pay_id[0]);
		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$temp_ss)."' where p_id=".$user_id);
		$DB_site->query("insert into wog_message(p_id,title,from_pid,dateline)values(".$pay_id[0].",'從 ".$p_name." 收到 ".$d_name."*".$item_num." ',".$user_id.",".time().")");
		$DB_site->query_first("COMMIT");
		$_POST["temp_id"]=$d_fie;
		unset($pay,$items,$adds,$s,$s2,$item,$pay_id,$d_name,$p_name,$d_fie);
		$this->arm_view($user_id);
		unset($a_id);
	}
	function arm_sale($user_id)
	{
		global $DB_site,$_POST,$a_id,$p_item,$lang,$wog_item_tool;

		$item_list=$_POST["item_list"];
		$temp_type=$_POST["temp_type"];
		$a_id=type_name($temp_type);
		$items=$_POST["items"];
		if(empty($item_list) && $temp_type<5)
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		if(empty($items) && $temp_type>=5)
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$temp_item_id2=array();
		$temp_hs_id2=array();
		$temp_ps_id2=array();
		$item_num=$_POST["item_num"];
		$temp_item_array=array();
		$sql="select ".$a_id." from wog_item where p_id=".$user_id;
		$p_item=$DB_site->query_first($sql);
		$p_item=explode(",",$p_item[0]);
		if($temp_type>=5)
		{
			$items=explode(",",$items);
			$temp_item_id2[]=$items[0];
			$temp_item_array[$items[0]]=$item_num;
			$p_item=$wog_item_tool->item_out($user_id,$items[0],$item_num,$p_item);
		}
		else
		{
			$item_num=1;
			for($i=0;$i<count($item_list);$i++)
			{
				get_arm_id($item_list[$i],$item_id,$hs_id,$ps_id);
				$temp_item_id2[]=$item_id;
				$temp_hs_id[]=$hs_id;
				$temp_ps_id[]=$ps_id;
				$temp_item_array[$item_id]++;
				$p_item=$wog_item_tool->item_out($user_id,$item_list[$i],$item_num,$p_item);
			}
		}
		$sql="select d_id,d_money  from wog_df where d_id in(".implode(",",$temp_item_id2).")";
		$pay=$DB_site->query($sql);
		$d_money=0;
		while($pays=$DB_site->fetch_array($pay))
		{
			$d_money += $temp_item_array[$pays[d_id]]*$pays[d_money];
		}
		$d_money=$d_money*0.5;
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$DB_site->query("update wog_player set p_money=p_money+".$d_money." where p_id=".$user_id);
		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$p_item)."' where p_id=".$user_id);
		if(count($temp_hs_id)>0)
		{
			$sql="select hole_temp_1,hole_temp_2,hole_temp_3,hole_temp_4 from wog_stone_setup where hs_id in (".implode(",",$temp_hs_id).")";
			$ht=$DB_site->query($sql);
			$temp=array();
			while($hts=$DB_site->fetch_array($ht))
			{
				if($hts[hole_temp_1]>0)
				{
					$temp[]=$hts[hole_temp_1];
				}
				if($hts[hole_temp_2]>0)
				{
					$temp[]=$hts[hole_temp_2];
				}
				if($hts[hole_temp_3]>0)
				{
					$temp[]=$hts[hole_temp_3];
				}
				if($hts[hole_temp_4]>0)
				{
					$temp[]=$hts[hole_temp_4];
				}
			}			
			$DB_site->free_result($ht);
			if(count($temp)>0)
			{
				$DB_site->query("delete from wog_stone_temp where ht_id in (".implode(",",$temp).")");
			}
			unset($temp,$hts);
			$DB_site->query("delete from wog_stone_setup where hs_id in (".implode(",",$temp_hs_id).")");
		}
		if(count($temp_ps_id)>0)
		{
			$DB_site->query("delete from wog_plus_setup where ps_id in (".implode(",",$temp_ps_id).")");
		}
		$DB_site->query_first("COMMIT");
		$_POST["temp_id"]=$temp_type;
		unset($items,$p_item,$d_money,$pay,$s2);
		$this->arm_view($user_id);
	}
	function arm_open_box($user_id,$d_lv)
	{
		global $DB_site,$lang,$wog_item_tool,$a_id,$wog_arry;
		$sql="select p_bag from wog_player where p_id=".$user_id;
		$bag=$DB_site->query_first($sql);
		$r=rand(1,100);
		$d_class=0;
		$limit=1;
		if($d_lv==4){$d_lv=3;$limit=2;}
		if($r<=60)
		{
			$d_class=1;
		}
		if($r>=61 && $r<=90)
		{
			$d_class=2;
		}
		if($r>=91 && $r<=100)
		{
			$d_class=3;
		}
		//$a_id=type_name($present["d_type"]);
		$a_id="d_stone_id";
		$sql="select ".$a_id." from wog_item where p_id=".$user_id;
		$pack=$DB_site->query_first($sql);
		$pack[0]=trim($pack[0]);
		$temp_pack=array();
		if(!empty($pack[0]))
		{
			$temp_pack=explode(",",$pack[0]);
		}
		$sql="select b.d_id,b.d_name,b.d_type from wog_stone_list a,wog_df b where a.d_lv=$d_lv and a.d_class<=$d_class and a.d_id=b.d_id ORDER BY RAND() LIMIT $limit";
		$pack=$DB_site->query($sql);
		$d_name="";
		while($packs=$DB_site->fetch_array($pack))
		{
			if($a_id=="d_item_id" || $a_id=="d_stone_id")
			{
				$temp_pack=$wog_item_tool->item_in($temp_pack,$packs["d_id"],1);
				$bbag=$wog_arry["item_limit"]+$bag[p_bag];
			}else
			{
				$temp_pack=$wog_item_tool->item_in($temp_pack,$packs["d_id"]);
				$bbag=$wog_arry["item_limit"];
			}
			$d_name.=" ".$packs[d_name];
		}
		if(count($temp_pack) > $bbag)
		{
			alertWindowMsg($lang['wog_act_bid_full']);
			unset($temp_pack);
		}
		$DB_site->query("update wog_item set ".$a_id."='".implode(',', $temp_pack)."' where p_id=".$user_id);
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		showscript("parent.xmas_item('".$d_name."')");
		unset($temp_pack,$pack,$present);
	}
	function arm_open_plusbox($user_id,$d_lv)
	{
		global $DB_site,$lang,$wog_item_tool,$a_id,$wog_arry;
		$sql="select p_bag from wog_player where p_id=".$user_id;
		$bag=$DB_site->query_first($sql);
		$r=array(1933,1943,1953,1963,1973,1983,1993,2003,2013,2023,2033);
		$get_id=array_rand($r);
		$get_id=$r[$get_id];
		//$a_id=type_name($present["d_type"]);
		$a_id="d_plus_id";
		$sql="select ".$a_id." from wog_item where p_id=".$user_id;
		$pack=$DB_site->query_first($sql);
		$pack[0]=trim($pack[0]);
		$temp_pack=array();
		if(!empty($pack[0]))
		{
			$temp_pack=explode(",",$pack[0]);
		}
		$sql="select b.d_id,b.d_name,b.d_type from wog_df b where b.d_id=".$get_id." LIMIT 1";
		$pack=$DB_site->query($sql);
		$d_name="";
		while($packs=$DB_site->fetch_array($pack))
		{
			$temp_pack=$wog_item_tool->item_in($temp_pack,$packs["d_id"],1);
			$bbag=$wog_arry["item_limit"]+$bag[p_bag];
			$d_name.=" ".$packs[d_name];
		}
		if(count($temp_pack) > $bbag)
		{
			alertWindowMsg($lang['wog_act_bid_full']);
			unset($temp_pack);
		}
		$DB_site->query("update wog_item set ".$a_id."='".implode(',', $temp_pack)."' where p_id=".$user_id);
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		showscript_continue("parent.xmas_item('".$d_name."')");
		unset($temp_pack,$pack,$bag);
	}
	function arm_open_rice($user_id,$type)
	{
		global $DB_site,$lang,$wog_item_tool,$a_id,$wog_arry;
		switch($type)
		{
			case 1:
				$ex_id=rand(1,12);
				$ex_num=rand(10,40);
				$DB_site->query_first("set autocommit=0");
				$DB_site->query_first("BEGIN");
				$sql="select ex_name from wog_exchange_main where ex_id=".$ex_id;
				$ex_main=$DB_site->query_first($sql);
				$ex_main[ex_name]=$ex_main[ex_name]."*".$ex_num;

				$sql="select el_id,el_amount,el_money from wog_exchange_list where p_id=".$user_id." and ex_id=".$ex_id." for update";
				$check_el=$DB_site->query_first($sql);
				if($check_el)
				{
					$temp_money2=(($check_el[el_amount]*$check_el[el_money]))/($check_el[el_amount]+$ex_num);
					$sql="update wog_exchange_list set el_amount=el_amount+".$ex_num.",el_money=".$temp_money2." where el_id=".$check_el[el_id];
					$DB_site->query($sql);
				}
				else
				{
					$sql="insert wog_exchange_list(p_id,ex_id,el_amount,el_money)values($user_id,$ex_id,$ex_num,0)";
					$DB_site->query($sql);
				}
				$DB_site->query_first("COMMIT");
				showscript("parent.xmas_item('".$ex_main[ex_name]."')");
				unset($check_el,$ex_main);
			break;
			case 2:
				$r=rand(1,200);
				$get_money="";
				if($r>1 && $r<=50){$get_money="500";}
				if($r>50 && $r<=100){$get_money="1000";}
				if($get_money!="")
				{
					$sql="update wog_player set p_money=p_money+".$get_money." where p_id=".$user_id;
					$DB_site->query($sql);
					$DB_site->query_first("COMMIT");
					showscript("parent.xmas_money('".$get_money."')");
				}
				$item_lv="";
				if($r>100 && $r<=130){$item_lv="1";}
				if($r>130 && $r<=160){$item_lv="2";}
				if($r>160 && $r<=190){$item_lv="3";}
				if($item_lv!="")
				{
					$sql="select d_id,d_type,d_name from wog_df where d_dbst=0 and d_lv in (".$item_lv.") ORDER BY RAND() LIMIT 1";
					$present=$DB_site->query_first($sql);
					$a_id=type_name($present["d_type"]);
					$sql="select ".$a_id." from wog_item where p_id=".$user_id;
					$pack=$DB_site->query_first($sql);
					$pack[0]=trim($pack[0]);
					$temp_pack=array();
					if(!empty($pack[0]))
					{
						$temp_pack=explode(",",$pack[0]);
					}

					if($a_id=="d_item_id")
					{
						$temp_pack=$wog_item_tool->item_in($temp_pack,$present["d_id"],1);
						$bbag=$wog_arry["item_limit"]+$bag[0];
					}else
					{
						$temp_pack=$wog_item_tool->item_in($temp_pack,$present["d_id"]);
						$bbag=$wog_arry["item_limit"];
					}

					if(count($temp_pack) > $bbag)
					{
						alertWindowMsg($lang['wog_act_bid_full']);
						unset($temp_pack);
					}
					$DB_site->query("update wog_item set ".$a_id."='".implode(',', $temp_pack)."' where p_id=".$user_id);
					$DB_site->query($sql);
					$DB_site->query_first("COMMIT");
					showscript("parent.xmas_item('".$present["d_name"]."')");
				}
				$d_lv=0;
				if($r>190 && $r<=195){$d_lv=1;}
				if($r>190 && $r<=195){$d_lv=2;}
				if(!empty($d_lv))
				{
					$this->arm_open_box($user_id,$d_lv);
				}
			break;
		}
	}

	function arm_item_use($user_id,$item_id,$pack)
	{
		global $DB_site,$_POST,$lang,$wog_arry,$wog_item_tool;
		$sql="select a.d_lv,a.d_type,a.d_g_exp,a.d_g_bag,a.ch_id,a.use_time,a.exp,a.skill_exp,a.at,a.mat,a.df,a.mdf,a.hp,a.sp,a.skill_id from wog_df_used a where a.d_id=".$item_id;
		$used_item=$DB_site->query_first($sql);
		switch($used_item[d_type]){
			case 0:
			// 使用能力提升藥水 或 exp膠囊
			//	$hpmax=player_hp($pack[life]+$pack[d_g_life]);
			//	$spmax=player_sp($pack[smart]+$pack[be]+$pack[d_g_smart]+$pack[d_g_be]);
				$DB_site->query("update wog_player set df=df+".$pack[d_g_vit]."
				,mdf=mdf+".$pack[d_g_be].",agi=agi+".$pack[d_g_agi].",at=at+".$pack[d_g_str].",mat=mat+".$pack[d_g_smart]."
				,str=str+".$pack[d_g_str].",smart=smart+".$pack[d_g_smart].",life=life+".$pack[d_g_life]."
				,vit=vit+".$pack[d_g_vit].",au=au+".$pack[d_g_au].",be=be+".$pack[d_g_be].",p_exp=p_exp+".$used_item[d_g_exp]."
				,base_str=base_str+".$pack[d_g_str].",base_smart=base_smart+".$pack[d_g_smart].",base_agi=base_agi+".$pack[d_g_agi]."
				,base_life=base_life+".$pack[d_g_life].",base_vit=base_vit+".$pack[d_g_vit].",base_au=base_au+".$pack[d_g_au].",base_be=base_be+".$pack[d_g_be]."
				where p_id=".$user_id);
				$DB_site->query_first("COMMIT");
				check_arm_status($user_id);
				showscript("parent.lv_up2($pack[d_g_str],$pack[d_g_agi],$pack[d_g_smart],$pack[d_g_life],$pack[d_g_vit],$pack[d_g_au],$pack[d_g_be],$used_item[d_g_exp]);parent.job_end(0,null,1,'$_POST[adds]',-1)");
				break;
			case 2:
			// 開寶箱
				$this->arm_open_box($user_id,$used_item[d_lv]);
				break;
			case 6:
			// 使用心得書
				$DB_site->query("update wog_ch_exp set sk_".$used_item[ch_id]."=sk_".$used_item[ch_id]."+".$used_item[d_g_exp]." where p_id=".$user_id);
				return $used_item[d_g_exp];
				break;
			case 7:
			// 行動力藥水
				$sql="select act_num from wog_player where p_id=".$user_id;
				$p=$DB_site->query_first($sql);
				$p["act_num"]+=$used_item[d_g_exp];
				if($p["act_num"]>50){$p["act_num"]=50;}
				$DB_site->query("update wog_player set act_num=".$p["act_num"]." where p_id=".$user_id);
				return;
				break;
			case 1:
			// 使用背包
				$DB_site->query("update wog_player set p_bag=".$used_item[d_g_bag]." where p_id=".$user_id);
				$DB_site->query_first("COMMIT");
				showscript("parent.bag_up($used_item[d_g_bag],1)");
				break;
			case 3:
			// 使用倉庫櫃
				$DB_site->query("update wog_player set p_depot=".$used_item[d_g_bag]." where p_id=".$user_id);
				$DB_site->query_first("COMMIT");
				showscript("parent.bag_up($used_item[d_g_bag],2)");
				break;
			case 4:
			// 使用甜粽
				$this->arm_open_rice($user_id,1);
				break;
			case 5:
			// 使用鹹粽
				$this->arm_open_rice($user_id,2);
				break;
			case 11:
			// 使用紅包
				include_once("./class/wog_act_present.php");
				$wog_act_class = new wog_act_present;
				$wog_act_class->present_xmas($user_id);
				unset($wog_act_class);
				break;
			case 9:
			// 使用密藥 begin
				$time=time()+$used_item[use_time];
				switch($used_item[d_lv]){
					case 1:
						//提昇戰鬥經驗值
						$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and exp >0 for update";
						$buffer=$DB_site->query_first($sql);
						if($buffer)
						{
							$sql="update wog_player_buffer set end_time=".$time.",exp=".$used_item[exp]." where p_id=".$user_id." and exp >0";
						}else
						{
							$sql="insert wog_player_buffer(p_id,end_time,exp)values($user_id,$time,$used_item[exp])";
						}
						$DB_site->query($sql);
						return;
						break;
					case 2:
						//提昇戰鬥熟練度
						$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and skill_exp >0 for update";
						$buffer=$DB_site->query_first($sql);
						if($buffer)
						{
							$sql="update wog_player_buffer set end_time=".$time.",skill_exp=".$used_item[skill_exp]." where p_id=".$user_id." and skill_exp >0";
						}else
						{
							$sql="insert wog_player_buffer(p_id,end_time,skill_exp)values($user_id,$time,$used_item[skill_exp])";
						}
						$DB_site->query($sql);
						return;
						break;
					case 3:
					// 提昇物理攻擊力
						$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and at >0 for update";
						$buffer=$DB_site->query_first($sql);
						if($buffer)
						{
							$sql="update wog_player_buffer set end_time=".$time.",at=".$used_item[at]." where p_id=".$user_id." and at >0";
						}else
						{
							$sql="insert wog_player_buffer(p_id,end_time,at)values($user_id,$time,$used_item[at])";
						}
						$DB_site->query($sql);
						return;
						break;
					case 4:
					// 提昇魔法攻擊力
						$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and mat >0 for update";
						$buffer=$DB_site->query_first($sql);
						if($buffer)
						{
							$sql="update wog_player_buffer set end_time=".$time.",mat=".$used_item[mat]." where p_id=".$user_id." and mat >0";
						}else
						{
							$sql="insert wog_player_buffer(p_id,end_time,mat)values($user_id,$time,$used_item[mat])";
						}
						$DB_site->query($sql);
						return;
						break;
					case 5:
					// 提昇物理防禦
						$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and df >0 for update";
						$buffer=$DB_site->query_first($sql);
						if($buffer)
						{
							$sql="update wog_player_buffer set end_time=".$time.",df=".$used_item[df]." where p_id=".$user_id." and df >0";
						}else
						{
							$sql="insert wog_player_buffer(p_id,end_time,df)values($user_id,$time,$used_item[df])";
						}
						$DB_site->query($sql);
						return;
						break;
					case 6:
					// 提昇魔法防禦
						$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and mdf >0 for update";
						$buffer=$DB_site->query_first($sql);
						if($buffer)
						{
							$sql="update wog_player_buffer set end_time=".$time.",mdf=".$used_item[mdf]." where p_id=".$user_id." and mdf >0";
						}else
						{
							$sql="insert wog_player_buffer(p_id,end_time,mdf)values($user_id,$time,$used_item[mdf])";
						}
						$DB_site->query($sql);
						return;
						break;
					case 7:
					// 提昇HP
						$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and hp >0 for update";
						$buffer=$DB_site->query_first($sql);
						if($buffer)
						{
							$sql="update wog_player_buffer set end_time=".$time.",hp=".$used_item[hp]." where p_id=".$user_id." and hp >0";
						}else
						{
							$sql="insert wog_player_buffer(p_id,end_time,hp)values($user_id,$time,$used_item[hp])";
						}
						$DB_site->query($sql);
						return;
						break;
					case 8:
					// 提昇SP
						$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and sp >0 for update";
						$buffer=$DB_site->query_first($sql);
						if($buffer)
						{
							$sql="update wog_player_buffer set end_time=".$time.",mdf=".$used_item[sp]." where p_id=".$user_id." and sp >0";
						}else
						{
							$sql="insert wog_player_buffer(p_id,end_time,sp)values($user_id,$time,$used_item[sp])";
						}
						$DB_site->query($sql);
						return;
						break;
				} // switch
				// 使用密藥 end
				break;
			case 10:
			//使用寵物蛋
				$sql="select count(pe_id) as num from wog_pet where pe_p_id=".$user_id." and pe_st in (0,2)";
				$pet=$DB_site->query_first($sql);
				if($pet["num"]<3)
				{
					$sql="select m_id,m_name,m_img from wog_monster where m_mission=0 and m_npc=0 ORDER BY RAND() LIMIT 1";
					$m=$DB_site->query_first($sql);
					if(!$m)//m date check start
					{
						alertWindowMsg($lang['wog_act_errdate']);
					}
					$up=280;
					$pet[pe_at]=rand(1,10)*$up;
					$pet[pe_mt]=rand(1,10)*$up;
					$pet[pe_def]=rand(1,10)*$up;
					$pet[pe_type]=rand(1,4);
					$sql="insert into wog_pet(pe_p_id,pe_name,pe_at,pe_mt,pe_fu,pe_def,pe_hu,pe_type,pe_age,pe_he,pe_fi,pe_dateline,pe_mname,pe_m_id,pe_b_dateline,pe_mimg,p_send,pe_st)";
					$sql.="values(".$user_id.",'".$m[m_name]."',".$pet[pe_at].",".$pet[pe_mt].",80,".$pet[pe_def].",0,".$pet[pe_type].",1,0,1,".(time()-20).",'".$m[m_name]."',".$m[m_id].",".time().",'".$m[m_img]."',1,2)";
					$DB_site->query($sql);
					unset($m,$pet);
				}else
				{
					alertWindowMsg($lang['wog_act_pet_error3']);
				}
				break;
			case 12:
			// 開精鍊寶盒
				$this->arm_open_plusbox($user_id,$used_item[d_lv]);
				break;
			case 13:
			//使用技能書
				include_once("./class/wog_mission_tool.php");
				$wog_act_class = new wog_mission_tool;
				$wog_act_class->mission_skill($user_id,$used_item["skill_id"]);
				unset($wog_act_class);
				break;
		} // switch
	}
/*
	function arm_item_use_vip($user_id,$item_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		$sql="select a.d_type,a.use_time,a.exp,a.skill_exp,a.at,a.mat,a.df,a.mdf,a.hp,a.sp from wog_df_vip a where a.d_id=".$item_id;
		$used_item=$DB_site->query_first($sql);
		$time=time()+$used_item[use_time];
		switch($used_item[d_type]){
			case 1:
				//提昇戰鬥經驗值
				$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and exp >0 for update";
				$buffer=$DB_site->query_first($sql);
				if($buffer)
				{
					$sql="update wog_player_buffer set end_time=".$time.",exp=".$used_item[exp]." where p_id=".$user_id." and exp >0";
				}else
				{
					$sql="insert wog_player_buffer(p_id,end_time,exp)values($user_id,$time,$used_item[exp])";
				}
				$DB_site->query($sql);
				return;
				break;
			case 2:
				//提昇戰鬥熟練度
				$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and skill_exp >0 for update";
				$buffer=$DB_site->query_first($sql);
				if($buffer)
				{
					$sql="update wog_player_buffer set end_time=".$time.",skill_exp=".$used_item[skill_exp]." where p_id=".$user_id." and skill_exp >0";
				}else
				{
					$sql="insert wog_player_buffer(p_id,end_time,skill_exp)values($user_id,$time,$used_item[skill_exp])";
				}
				$DB_site->query($sql);
				return;
				break;
			case 3:
			// 提昇物理攻擊力
				$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and at >0 for update";
				$buffer=$DB_site->query_first($sql);
				if($buffer)
				{
					$sql="update wog_player_buffer set end_time=".$time.",at=".$used_item[at]." where p_id=".$user_id." and at >0";
				}else
				{
					$sql="insert wog_player_buffer(p_id,end_time,at)values($user_id,$time,$used_item[at])";
				}
				$DB_site->query($sql);
				return;
				break;
			case 4:
			// 提昇魔法攻擊力
				$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and mat >0 for update";
				$buffer=$DB_site->query_first($sql);
				if($buffer)
				{
					$sql="update wog_player_buffer set end_time=".$time.",mat=".$used_item[mat]." where p_id=".$user_id." and mat >0";
				}else
				{
					$sql="insert wog_player_buffer(p_id,end_time,mat)values($user_id,$time,$used_item[mat])";
				}
				$DB_site->query($sql);
				return;
				break;
			case 5:
			// 提昇物理防禦
				$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and df >0 for update";
				$buffer=$DB_site->query_first($sql);
				if($buffer)
				{
					$sql="update wog_player_buffer set end_time=".$time.",df=".$used_item[df]." where p_id=".$user_id." and df >0";
				}else
				{
					$sql="insert wog_player_buffer(p_id,end_time,df)values($user_id,$time,$used_item[df])";
				}
				$DB_site->query($sql);
				return;
				break;
			case 6:
			// 提昇魔法防禦
				$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and mdf >0 for update";
				$buffer=$DB_site->query_first($sql);
				if($buffer)
				{
					$sql="update wog_player_buffer set end_time=".$time.",mdf=".$used_item[mdf]." where p_id=".$user_id." and mdf >0";
				}else
				{
					$sql="insert wog_player_buffer(p_id,end_time,mdf)values($user_id,$time,$used_item[mdf])";
				}
				$DB_site->query($sql);
				return;
				break;
			case 7:
			// 提昇HP
				$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and hp >0 for update";
				$buffer=$DB_site->query_first($sql);
				if($buffer)
				{
					$sql="update wog_player_buffer set end_time=".$time.",hp=".$used_item[hp]." where p_id=".$user_id." and hp >0";
				}else
				{
					$sql="insert wog_player_buffer(p_id,end_time,hp)values($user_id,$time,$used_item[hp])";
				}
				$DB_site->query($sql);
				return;
				break;
			case 8:
			// 提昇SP
				$sql="select p_id,end_time from wog_player_buffer where p_id=".$user_id." and sp >0 for update";
				$buffer=$DB_site->query_first($sql);
				if($buffer)
				{
					$sql="update wog_player_buffer set end_time=".$time.",mdf=".$used_item[sp]." where p_id=".$user_id." and sp >0";
				}else
				{
					$sql="insert wog_player_buffer(p_id,end_time,sp)values($user_id,$time,$used_item[sp])";
				}
				$DB_site->query($sql);
				return;
				break;
		} // switch
	}
*/
	function arm_item(&$item,$adds,&$pack2,$item_num)
	{
		global $lang,$wog_arry;
		$packs=explode(",",$item);
		$s="";
		$chks=false;
		for($i=count($packs)-1;$i>-1;$i--)
		{
			$packss=explode("*",$packs[$i]);
			if(empty($packss[0])){continue;}
			if(!$chks && ($packss[0] == $pack2[d_id]))
			{
				$packss[1]=$packss[1] + $pack2[d_item_num];
				$chks=true;
			}
			if($packss[0]==$adds)
			{
				if($packss[1] >= $item_num)
				{
					$packss[1]=$packss[1]-$item_num;
					$item_num=0;
				}
				else
				{
					$item_num=$item_num-$packss[1];
				}
			}
			if(($packss[0] == $adds && $item_num == 0) || $packss[0] != $adds)
			{
				if($packss[1] > $wog_arry["item_app_limit"])
				{
					for($j=0;$j<floor($packss[1]/$wog_arry["item_app_limit"]);$j++)
					{
						$s.=','.$packss[0].'*'.$wog_arry["item_app_limit"];
					}
					if($packss[1]%$wog_arry["item_app_limit"] > 0){$s.=','.$packss[0].'*'.($packss[1]%$wog_arry["item_app_limit"]);}
				}
				else
				{

					if($packss[1] > 0){$s.=",".$packss[0]."*".$packss[1];}
				}
			}
		}
		if(!$chks && $pack2)
		{
			$s.=",".$pack2[d_id]."*".$pack2[d_item_num];
		}
		if($item_num > 0){alertWindowMsg($lang['wog_act_arm_noarm']);}
		$s=substr($s,1);
		return $s;
	}
	// 把穿上物品移出裝備欄 & 卸下物品放回裝備欄
	function arm_equit(&$item,$adds,&$pack2)
	{
		global $lang;
		$packs=explode(",",$item);
		$s="";
		$chks=false;
		for($i=0;$i<count($packs);$i++)
		{
			if(($packs[$i]!=$adds || $chks) && !empty($packs[$i]))
			{
				$s.=",".$packs[$i];
			}
			else
			{
				if($packs[$i]==$adds){$chks=true;}
			}
		}
		if(!$chks)
		{
			alertWindowMsg($lang['wog_act_arm_noarm']);
		}
		if($pack2)
		{
			$s.=",".$pack2[d_id];
		}
		$s=substr($s,1);
		return $s;
	}

	//倉庫 begin
	function arm_depot_add($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_arry,$wog_item_tool;
		if(empty($_POST["adds"]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$bag_item_id=$_POST["adds"];
		get_arm_id($bag_item_id,$item_id,$hs_id,$ps_id);
		$item_num=$_POST["item_num"];
		if($item_num <=0 || $item_num >99 ||!is_numeric($item_num) || preg_match("/[^0-9]/",$item_num)){alertWindowMsg($lang['wog_act_errnum']);}

		$sql="select d_fie,d_type from wog_df where d_id=".$item_id;
		$d_type=$DB_site->query_first($sql);
		$a_id=type_name($d_type[d_fie]);

		$temp_ss=$wog_item_tool->item_out($user_id,$bag_item_id,$item_num);
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");

		$sql="select d_id,d_num from wog_player_depot where p_id=$user_id and d_id=$item_id and d_type >4 for update";
		$d_check=$DB_site->query_first($sql);
		if($d_check)
		{
			$d_check[d_num]+=$item_num;
			if($d_check[d_num]>=255)
			{
				alertWindowMsg($lang['wog_act_arm_err1']);
			}
			$DB_site->query("update wog_item set ".$a_id."='".implode(',',$temp_ss)."' where p_id=".$user_id);
			$sql="update wog_player_depot set d_num=$d_check[d_num] where p_id=$user_id and d_id=".$item_id;
			$DB_site->query($sql);
		}
		else
		{
			$sql="select count(d_id) as num  from wog_player_depot where p_id=$user_id";
			$d_check=$DB_site->query_first($sql);
			$sql="select p_depot from wog_player where p_id=$user_id";
			$p=$DB_site->query_first($sql);
			if($d_check[num] < ($wog_arry["depot_limit"]+$p[p_depot]))
			{
				$DB_site->query("update wog_item set ".$a_id."='".implode(',',$temp_ss)."' where p_id=".$user_id);
				$sql="insert into wog_player_depot(p_id,d_id,d_type,d_num,hs_id,ps_id)values($user_id,$item_id,$d_type[0],$item_num,$hs_id,$ps_id)";
				$DB_site->query($sql);
			}
			else
			{
				alertWindowMsg($lang['wog_act_arm_err1']);
			}
		}

		$DB_site->query_first("COMMIT");
		$_POST["temp_id"]=$d_type[d_fie];
		unset($d_check,$d_type);
		$this->arm_view($user_id);
		unset($a_id);
	}
	function arm_depot_list($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		$d_type=$_POST["temp_id"];
		$d_type2=$_POST["temp_id"];
		if(empty($d_type))
		{
			$d_type="0";
			$d_type2="0";
		}
		$item_array=array();
		$item_array2=array();
		$sql="select p_depot from wog_player where p_id=$user_id";
		$p=$DB_site->query_first($sql);
		$p[p_depot]+=$wog_arry["depot_limit"];
		$item=$DB_site->query("select a.id,a.d_id,a.d_num,b.d_name,b.d_hole,b.d_send,c.plus_num,a.hs_id,a.ps_id from wog_player_depot a
			left join wog_plus_setup c on c.ps_id=a.ps_id ,
			wog_df b where a.p_id=$user_id and b.d_fie = ".$d_type." and b.d_id=a.d_id");
		while($items=$DB_site->fetch_array($item))
		{
			if(!empty($items[hs_id])){$items[d_id].=":".$items[hs_id];}
			if(!empty($items[ps_id])){$items[d_id].="&".$items[ps_id];$items[d_name].="+".$items[plus_num];}
			$item_array[]=$items[d_id].",".$items[d_name]."(".$items[d_hole]."),".$items[d_num].",".$items[d_send].",".$items[id];
		}
		$DB_site->free_result($item);
		$sql="select count(a.id) as id from wog_player_depot a where a.p_id=$user_id";
		$depot=$DB_site->query_first($sql);
		$p[p_depot]=$depot[id]."/".$p[p_depot];
		unset($items,$depot);
		showscript("parent.depot_list('".implode(';',$item_array)."',$d_type2,'$p[p_depot]')");
	}
	function arm_depot_move($user_id)
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
		$pay_id=trim($_POST["pay_id"]);
		$id=$_POST["temp_id2"];
		if($item_num <=0 || !is_numeric($item_num) || preg_match("/[^0-9]/",$item_num)){alertWindowMsg($lang['wog_act_errnum']);}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select d_id,d_num,hs_id,ps_id from wog_player_depot where p_id=$user_id and id=$id for update";
		$depot_main=$DB_site->query_first($sql);
		if(!$depot_main)
		{
			alertWindowMsg($lang['wog_act_errnum']);
		}
		if($depot_main[d_num] < $item_num)
		{
			alertWindowMsg($lang['wog_act_errnum']);
		}
		$depot_main[d_num]-=$item_num;

		$d_id=$depot_main["d_id"];
		$sql="select d_name,d_fie,d_send,d_type from wog_df where d_id=".$d_id;
		$pay=$DB_site->query_first($sql);
		$a_id=type_name($pay[d_fie]);
		$d_type=$pay[d_type];
		$d_name=$pay[d_name];
		if(empty($item_num) || $d_type <5)
		{
			$item_num=1;
		}
		$sql="select p_name from wog_player where p_id=$user_id";
		$p_name=$DB_site->query_first($sql);

		$sql="select p_id,p_bag,p_name from wog_player where p_name='".$pay_id."'";
		$pay_id=$DB_site->query_first($sql);
		if(!$pay_id)
		{
			alertWindowMsg($lang['wog_act_arm_nomove']);
		}
		if($pay[d_send]==1 && $user_id!=$pay_id[p_id])
		{
			alertWindowMsg($lang['wog_act_arm_nomove']);
		}
		$sql="select ".$a_id." from wog_item where p_id=".$pay_id[p_id]." for update";
		$pay=$DB_site->query_first($sql);
		$temp_pack=array();
		if(!empty($pay[0]))
		{
			$temp_pack=explode(",",$pay[0]);
		}
		$d_id2=$d_id;
		if(!empty($depot_main[hs_id])){$d_id2.=":".$depot_main[hs_id];}
		if(!empty($depot_main[ps_id])){$d_id2.="&".$depot_main[ps_id];}
		$adds=$wog_item_tool->item_in($temp_pack,$d_id2,$item_num);
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
		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$adds)."' where p_id=".$pay_id[p_id]);
		if($depot_main[d_num]<=0)
		{
			$DB_site->query("delete from wog_player_depot where p_id=$user_id and d_id=".$d_id." limit 1");
		}
		else
		{
			$DB_site->query("update wog_player_depot set d_num=".$depot_main[d_num]." where p_id=$user_id and d_id=".$d_id);
		}
		if($pay_id[p_id]!=$user_id)
		{
			if(!empty($depot_main[hs_id]))
			{
				$sql="update wog_stone_setup set p_id=$pay_id[p_id] where hs_id=$depot_main[hs_id]";
				$DB_site->query($sql);
			}
			if(!empty($depot_main[ps_id]))
			{

				$sql="update wog_plus_setup set p_id=$pay_id[p_id] where ps_id=$depot_main[ps_id]";
				$DB_site->query($sql);
			}
		}


		$DB_site->query("insert into wog_message(p_id,title,from_pid,dateline)values(".$pay_id[p_id].",'從[".$p_name[p_name]."]倉庫  收到 ".$d_name."*$item_num ',".$user_id.",".time().")");
		$DB_site->query_first("COMMIT");
		showscript("parent.job_end(24,null,1,'$d_id2',$depot_main[d_num]);");
		unset($depot_main,$temp_s);
	}
	//倉庫 end
}
?>