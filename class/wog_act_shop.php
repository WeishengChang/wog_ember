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

class wog_act_shop{
	function shop($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang;
		check_type($_POST["temp_id"],1);
		$sql="select agi,p_lv,str,smart,life,ch_id,p_money from wog_player where p_id=".$user_id;
		$p=$DB_site->query_first($sql);
		if(empty($p))
		{
			alertWindowMsg($lang['wog_act_relogin']);
		}
		$money=$p[p_money];
		$sql="select ".$a_id." from wog_player_arm where  p_id=".$user_id;
		$p_arm=$DB_site->query_first($sql);
		if(empty($p_arm))
		{
			alertWindowMsg($lang['wog_act_relogin']);
		}
		switch($_POST["temp_id2"])
		{
			case "1":
				$dlv=1;
			break;
			case "2":
				$dlv=2;
			break;
			case "3":
				$dlv=3;
			break;
			case "4":
				$dlv=4;
			break;
			case "5":
				$dlv=5;
			break;
			case "6":
				$dlv=6;
			break;
			case "7":
				$dlv=7;
			break;
			default:
				$dlv=1;
			break;
		}

		//$temp_where="((a.d_mstr >= $slv or a.d_msmart >= $slv or a.d_magl >= $slv) and (a.d_mstr <= $hlv and a.d_msmart <= $hlv and a.d_magl <= $hlv)) and a.d_msmart<=".$p[smart]." and a.d_magl<=".$p[agi]." and a.d_mstr<=".$p[str]." ";
		$temp_where=" a.d_lv=$dlv";

		$df_total=$DB_site->query_first("select count(a.d_id) as d_id from wog_df a where d_fie = ".$_POST["temp_id"]." and ".$temp_where." and a.d_dbst=0");
		if(empty($_POST["page"]))
		{
			$_POST["page"]="1";
		}
		$spage=((int)$_POST["page"]*8)-8;

		$sql="select a.d_id,a.d_df,a.d_mdf,a.d_money,a.d_name,a.d_at,a.d_mat,a.d_mstr,a.d_magl,a.d_msmart,a.d_mau,b.ch_name
			,a.d_g_str,a.d_g_smart,a.d_g_agi,a.d_g_life,a.d_g_vit,a.d_g_au,a.d_g_be,a.d_hole,a.d_plus
			from wog_df a left join wog_character b on b.ch_id=a.ch_id
			where d_fie = ".$_POST["temp_id"]." and ".$temp_where." and a.d_dbst=0 order by d_money LIMIT ".$spage.",8 ";
		$pack=$DB_site->query($sql);
		$s="";
		while($packs=$DB_site->fetch_array($pack))
		{
			if($packs[ch_name]==null){$packs[ch_name]="";}
			$s=$s.";".$packs[d_id].",".$packs[d_df].",".$packs[d_mdf].",".$packs[d_money].",".$packs[d_name].",".$packs[d_at].",".$packs[d_mat].",".$packs[d_mstr].",".$packs[d_magl].",".$packs[d_msmart].",".$packs[d_mau].",".$packs[ch_name].",".$packs[d_g_str].",".$packs[d_g_smart].",".$packs[d_g_agi].",".$packs[d_g_life].",".$packs[d_g_vit].",".$packs[d_g_au].",".$packs[d_g_be].",".$packs[d_hole].",".$packs[d_plus];
		}
		$s=substr($s,1,strlen($s));
		$DB_site->free_result($pack);
/*
		$packs=$DB_site->query_first("select d_df,d_mdf,d_name,d_at,d_mat,d_g_str,d_g_smart,d_g_agi,d_g_life,d_g_vit,d_g_au,d_g_be from wog_df where d_id=$p_arm[0]");
		$ss=$packs[d_df].",".$packs[d_mdf].",".$packs[d_name].",".$packs[d_at].",".$packs[d_mat].",".$p[p_money].",".$packs[d_g_str].",".$packs[d_g_smart].",".$packs[d_g_agi].",".$packs[d_g_life].",".$packs[d_g_vit].",".$packs[d_g_au].",".$packs[d_g_be];
*/
		// 取出目前穿在身上的裝備數值 begin
		$item_type=$_POST["temp_id"];
		$item_type2=type_name($item_type);
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
						$arm_array[hs][d_name].="　".$p2[d_name];
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
					$arm_array[hs][$p[0]][mdf]=$p2[s_mdf];
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
					if($temp_strs[skill]>0)
					{
						$sql="select a.s_name
							from wog_ch_skill a where a.s_id=".$temp_strs[skill];
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
		if(count($my_item_array)>0)
		{
			$my_item=implode(";",$my_item_array);
		}
		unset($packs,$temp_where,$temp,$my_item_array,$d_status,$arm_array,$p2);
//		echo "<script language='JavaScript' src='http://www.acer.net/back/ming/wog_shop_cache_".$_POST["temp_id"]."_".$_POST["temp_id2"].".js'></script>";
//		showscript("parent.shop_home_view(s,'".$_POST["act"]."','".$_POST["temp_id"]."','".$ss."','".$_POST["temp_id2"]."')");
		showscript("parent.shop_home_view('$s','".$_POST["act"]."','".$_POST["temp_id"]."','".$my_item."','".$_POST["temp_id2"]."',".$df_total[0].",".$_POST["page"].",$money)");
		unset($s,$ss,$p,$a_id,$df_total);
	}

	function buy($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_arry,$wog_item_tool;
		$temp["money"]="d_money";
		$temp["table"]="wog_df";
		$buy_num=$_POST["buy_num"];
		if(!isset($_POST["adds"]))
		{
			alertWindowMsg($lang['wog_act_buy_noitem']);
		}
		if(empty($buy_num)){$buy_num=1;}
		if($buy_num > 99 || $buy_num < 1 || !is_numeric($buy_num) || preg_match("/[^0-9]/",$buy_num) || empty($buy_num))
		{
			alertWindowMsg($lang['wog_act_buy_errornum']);
		}
		check_type($_POST["temp_id"],1);
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		
		$sql="select ".$a_id." from wog_item where p_id=".$user_id." for update";
		$pack=$DB_site->query_first($sql);
		$pack[0]=trim($pack[0]);
		$temp_pack=array();
		if(!empty($pack[0]))
		{
			$temp_pack=explode(",",$pack[0]);
		}
		$must_price=$DB_site->query_first("select ".$temp["money"].",d_id from ".$temp["table"]." where d_id=".$_POST["adds"]." and d_dbst=0");
		if(!$must_price)
		{
			alertWindowMsg($lang['wog_act_errwork']);
		}
		$have_price=$DB_site->query_first("select p_money,p_bag from wog_player where p_id=".$user_id." for update");
		if($a_id=="d_item_id")
		{
			$temp_pack=$wog_item_tool->item_in($temp_pack,$_POST["adds"],$buy_num);
			$must_price[0]=$must_price[0]*$buy_num;
			$bag=$wog_arry["item_limit"]+$have_price["p_bag"];
		}else
		{
			$temp_pack=$wog_item_tool->item_in($temp_pack,$_POST["adds"]);
			$bag=$wog_arry["item_limit"];
		}
		if($must_price[0]>$have_price[0]){
			alertWindowMsg($lang['wog_act_nomoney']);
		}
		if(count($temp_pack) > $bag)
		{
			alertWindowMsg(sprintf($lang['wog_act_buy_tenitem'],$bag));
			unset($temp_pack);
		}

		$DB_site->query("update wog_player set p_money=p_money-".$must_price[0]." where p_id=".$user_id);
		if($pack)
		{
			$pack[0]=implode(',', $temp_pack);
			$DB_site->query("update wog_item set ".$a_id."='".$pack[0]."' where p_id=".$user_id);
		}else
		{
			$pack[0]=implode(',', $temp_pack);
			$DB_site->query("insert into wog_item(".$a_id.",p_id)values('".$pack[0]."',".$user_id.")");
		}
		$DB_site->query_first("COMMIT");
		showscript("parent.job_end(6,'".($have_price[0]-$must_price[0])."',1)");
		unset($pack,$temp_add,$must_price,$have_price,$temp,$a_id);

	}
	function check_item($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang;
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$bag_item_id=$_POST["temp_id"];
		get_arm_id($bag_item_id,$item_id,$hs_id,$ps_id);

		/*
		if($_POST["pay_id"]=="")
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		*/
		$item_type=$_POST["pay_id"];
		$s="";
		$show_type=0;
		$arm_array=array();
		$item_array=array();
		$d_status=array();
		if($item_type < 7)
		{

			$sql="select a.d_name,a.d_df,a.d_mdf,a.d_money,a.d_name,a.d_at,a.d_mat,a.d_mstr,a.d_magl,a.d_msmart,a.d_mau,b.ch_name,ifnull(a.d_s,0) as d_s,a.d_hole
				,a.d_g_str,a.d_g_smart,a.d_g_agi,a.d_g_life,a.d_g_vit,a.d_g_au,a.d_g_be,a.skill,a.d_plus,a.d_send
				from wog_df a left join wog_character b on b.ch_id=a.ch_id
				where a.d_id=".$item_id;
			$p2=$DB_site->query_first($sql);
			if($p2[skill]>0)
			{
				$sql="select a.s_name,a.s_lv
					from wog_ch_skill a where a.s_id=".$p2[skill];
				$skill_main=$DB_site->query_first($sql);
				$skill_main[s_name]=$skill_main[s_name]."LV".$skill_main[s_lv];
			}
			else
			{
				$skill_main[s_name]="";
			}
			if($p2)
			{
				if($p2[ch_name]==null){$p2[ch_name]="";}
				$d_status[d_name]=$p2[d_name];
				$d_status[s_name]=$skill_main[s_name];

				$d_status[at]=$p2[d_at];
				$d_status[mat]=$p2[d_mat];
				$d_status[df]=$p2[d_df];
				$d_status[mdf]=$p2[d_mdf];
				$d_status[agi]=$p2[d_g_agi];
				$d_status[life]=$p2[d_g_life];
				$d_status[au]=$p2[d_g_au];
				$d_status[be]=$p2[d_g_be];
				$d_status[vit]=$p2[d_g_vit];
				$d_status[smart]=$p2[d_g_smart];
				$d_status[str]=$p2[d_g_str];
				
				$d_status[d_mstr]=$p2[d_mstr];
				$d_status[d_magl]=$p2[d_magl];
				$d_status[d_msmart]=$p2[d_msmart];
				$d_status[d_mau]=$p2[d_mau];
						
				$d_status[stone_name]="";
				$d_status[ch_name]=$p2[ch_name];
				$d_status[d_money]=$p2[d_money];
				$d_status[d_s]=$p2[d_s];
				$d_status[d_hole]=$p2[d_hole];
				$d_status[d_plus]=$p2[d_plus];
				$d_status[d_send]=$p2[d_send];
			}

			if(!empty($hs_id))
			{
				$temp_str=$DB_site->query("select b.hs_id,a.d_name
						from wog_df a,wog_stone_setup b where  b.hs_id =".$hs_id." and a.d_id in (b.hole_1,b.hole_2,b.hole_3,b.hole_4)");
				while($p2=$DB_site->fetch_array($temp_str))
				{
					$arm_array[hs][d_name].="　".$p2[d_name];
				}
				$DB_site->free_result($temp_str);
				
				if(!empty($arm_array[hs][d_name]))
				{
					
					$sql="select ifnull(sum(a.s_df),0) as s_df,ifnull(sum(a.s_mdf),0) as s_mdf,ifnull(sum(a.s_agl),0) as s_agl,ifnull(sum(a.s_at),0) as s_at,ifnull(sum(a.s_mat),0) as s_mat
						,ifnull(sum(a.s_str),0) as s_str,ifnull(sum(a.s_life),0) as s_life,ifnull(sum(a.s_vit),0) as s_vit,ifnull(sum(a.s_smart),0) as s_smart,ifnull(sum(a.s_au),0) as s_au,ifnull(sum(a.s_be),0) as s_be
						,ifnull(sum(a.s_hpmax),0) as s_hpmax
						from wog_stone_list a,wog_stone_setup b where  b.hs_id =".$hs_id." and a.d_id in (b.hole_1,b.hole_2,b.hole_3,b.hole_4) and a.d_class < 99";
					$p2=$DB_site->query_first($sql);
					$arm_array[hs][at]=$p2[s_at];
					$arm_array[hs][mat]=$p2[s_mat];
					$arm_array[hs][df]=$p2[s_df];
					$arm_array[hs][mdf]=$p2[s_mdf];
					$arm_array[hs][agi]=$p2[s_agl];
					$arm_array[hs][life]=$p2[s_life];
					$arm_array[hs][au]=$p2[s_au];
					$arm_array[hs][be]=$p2[s_be];
					$arm_array[hs][vit]=$p2[s_vit];
					$arm_array[hs][smart]=$p2[s_smart];
					$arm_array[hs][str]=$p2[s_str];
					$arm_array[hs][hp]=$p2[s_hpmax];
	
					$sql="select ifnull(sum(a.s_df),0) as s_df,ifnull(sum(a.s_mdf),0) as s_mdf,ifnull(sum(a.s_agl),0) as s_agl,ifnull(sum(a.s_at),0) as s_at,ifnull(sum(a.s_mat),0) as s_mat
						,ifnull(sum(a.s_str),0) as s_str,ifnull(sum(a.s_life),0) as s_life,ifnull(sum(a.s_vit),0) as s_vit,ifnull(sum(a.s_smart),0) as s_smart,ifnull(sum(a.s_au),0) as s_au,ifnull(sum(a.s_be),0) as s_be
						,ifnull(sum(a.s_hpmax),0) as s_hpmax
						from wog_stone_temp a,wog_stone_setup b where  b.hs_id =".$hs_id." and a.ht_id in (b.hole_temp_1,b.hole_temp_2,b.hole_temp_3,b.hole_temp_4)";

					$p2=$DB_site->query_first($sql);
					$arm_array[hs][at]+=$p2[s_at];
					$arm_array[hs][mat]+=$p2[s_mat];
					$arm_array[hs][df]+=$p2[s_df];
					$arm_array[hs][mdf]+=$p2[s_mdf];
					$arm_array[hs][agi]+=$p2[s_agl];
					$arm_array[hs][life]+=$p2[s_life];
					$arm_array[hs][au]+=$p2[s_au];
					$arm_array[hs][be]+=$p2[s_be];
					$arm_array[hs][vit]+=$p2[s_vit];
					$arm_array[hs][smart]+=$p2[s_smart];
					$arm_array[hs][str]+=$p2[s_str];
					$arm_array[hs][hp]+=$p2[s_hpmax];
				}
				
			}
			else
			{
				$arm_array[hs]=null;
			}
			if(!empty($ps_id))
			{
				$p2=$DB_site->query_first("select b.ps_id,b.plus_num,b.d_at,b.d_mat,b.d_df,b.d_mdf,b.d_str,b.d_agi,b.d_smart,b.d_life,b.d_vit,b.d_au,b.d_be from wog_plus_setup b where  b.ps_id =".$ps_id);
				$arm_array[ps][plus_num]=$p2[plus_num];
				$arm_array[ps][at]=$p2[d_at];
				$arm_array[ps][mat]=$p2[d_mat];
				$arm_array[ps][df]=$p2[d_df];
				$arm_array[ps][mdf]=$p2[d_mdf];
				$arm_array[ps][agi]=$p2[d_agi];
				$arm_array[ps][life]=$p2[d_life];
				$arm_array[ps][au]=$p2[d_au];
				$arm_array[ps][be]=$p2[d_be];
				$arm_array[ps][vit]=$p2[d_vit];
				$arm_array[ps][smart]=$p2[d_smart];
				$arm_array[ps][str]=$p2[d_str];
				$DB_site->free_result($temp_str);
			}
			else
			{
				$arm_array[ps]=null;
			}
			$item_array=chk_item_status($d_status,$arm_array[hs],$arm_array[ps]);
			$my_item=$item_array[d_name].","
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
				.$item_array[d_mstr].","
				.$item_array[d_magl]."," //15
				.$item_array[d_msmart].","
				.$item_array[d_mau].","
				.$item_array[d_send]."," //18
				.$item_array[ch_name].","
				.$item_array[d_money]."," //20
				.$item_array[d_s]."," //21
				.$item_array[d_hole]."," //22
				.$item_array[d_plus].","//23
				.$item_array[hp].","//24
				.$item_array[d_send]//25
				;
			$show_type=1;
		}
		if($item_type == 7)
		{
			$sql="select a.d_id,a.s_at,a.s_mat,a.s_df,a.s_mdf,a.s_agl,a.s_str,a.s_life,a.s_vit,a.s_smart,a.s_au,a.s_be,b.d_name
					from wog_stone_list a,wog_df b where a.d_id=$item_id and a.d_id=b.d_id";
			$item_main=$DB_site->query_first($sql);
			if($item_main)
			{
				$my_item=$item_main[s_at].",".$item_main[s_mat].",".$item_main[s_df].",".$item_main[s_mdf].",".$item_main[s_agl].",".$item_main[s_str].",".$item_main[s_life].",".$item_main[s_vit].",".$item_main[s_smart].",".$item_main[s_au].",".$item_main[s_be].",".$item_main[d_name];
			}
			$show_type=2;
		}
		unset($item_main,$temp_str,$arm_array,$p2,$item_array);
		showscript("parent.wog_message_box('".$my_item."',1,$show_type)");
	}
}
?>