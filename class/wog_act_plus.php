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

class wog_act_plus{
	function plus_view($user_id,$d_name="")
	{
		global $DB_site,$_POST,$lang;
		$sql="select d_plus_id from wog_item where p_id=$user_id";
		$item_list=$DB_site->query_first($sql);

		$packs=explode(",",$item_list[0]);
		for($i=0;$i<count($packs);$i++)
		{
			$packss=explode("*",$packs[$i]);
			$temp_item.=",".$packss[0];
		}
		$temp_item=substr($temp_item,1);
		$temp="";
		if(!empty($temp_item))
		{
			$sql="select a.d_id,a.d_name,b.d_at,b.d_mat,b.d_df,b.d_mdf,b.d_str,b.d_agi,b.d_smart,b.d_life,b.d_vit,b.d_au,b.d_be
					 from wog_df a,wog_plus_list b where a.d_type=10 and a.d_id in ($temp_item) and b.d_id=a.d_id";
			$plus=$DB_site->query($sql);
			$s2=array();
			while($pluss=$DB_site->fetch_array($plus))
			{
				$s2[]=$pluss[d_id].":".$pluss[d_name].":".$pluss[d_at].":".$pluss[d_mat].":".$pluss[d_df].":".$pluss[d_mdf].":".$pluss[d_str].":".$pluss[d_agi].":".$pluss[d_smart].":".$pluss[d_life].":".$pluss[d_vit].":".$pluss[d_au].":".$pluss[d_be];
			}
					
			$DB_site->free_result($plus);
			unset($pluss);
			$temp=implode(";",$s2);
		}
		showscript("parent.plus_view('$temp','".$item_list[0]."','$d_name')");
		unset($s2,$item_list);
	}
	function plus_make($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_item_tool,$wog_arry,$a_id;
		if(empty($_POST["temp1"]) || empty($_POST["temp2"]))
		{
			alertWindowMsg($lang['wog_act_plus_er1']);
		}
		$item_num=(int)$_POST["item_num"];
		if($item_num <=0 || $item_num >49 || preg_match("/[^0-9]/",$item_num)){alertWindowMsg($lang['wog_act_errnum']);}
		$d_id1=$_POST["temp1"];
		$d_id2=$_POST["temp2"];
		$sql="select d_lv,d_type from wog_plus_list where d_id =$d_id1";
		$plus=$DB_site->query_first($sql);
		$s[0][d_lv]=$plus[d_lv];
		$s[0][d_type]=$plus[d_type];
		
		$sql="select d_lv,d_type from wog_plus_list where d_id =$d_id2";
		$plus=$DB_site->query_first($sql);
		$s[1][d_lv]=$plus[d_lv];
		$s[1][d_type]=$plus[d_type];
		unset($plus);
		if($s[0][d_lv] != $s[1][d_lv])
		{
			alertWindowMsg($lang['wog_act_plus_er2']);
		}
		$sql="select p_bag from wog_player where p_id=".$user_id;
		$bag=$DB_site->query_first($sql);

		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		
		$sql="select d_plus_id from wog_item where p_id=".$user_id." for update";
		$item=$DB_site->query_first($sql);
		$a_id="d_plus_id";
		$items=array();
		if(!empty($item[$a_id]))
		{
			$items=explode(",",$item[$a_id]);
		}
		$name_str=array();
		if($s[0][d_type] == $s[1][d_type]) //同類精練石合成
		{
			$s[0][d_lv]++;
			if($s[0][d_lv]>10){alertWindowMsg($lang['wog_act_plus_er5']);}
			$sql="select d_id from wog_plus_list where d_lv=".$s[0][d_lv]." and d_type=".$s[0][d_type];
			$get_item=$DB_site->query_first($sql);
			$items=$wog_item_tool->item_in($items,$get_item["d_id"],$item_num);
						
			$sql="select d_name from wog_df where d_id=".$get_item[d_id];
			$get_name=$DB_site->query_first($sql);
			$name_str[$get_name[d_name]]+=$item_num;
		}
		else //不同類精練石合成
		{
			$s[0][d_lv]--;
			if($s[0][d_lv] <=0)
			{
				alertWindowMsg($lang['wog_act_plus_er3']);
			}
			for($i=1;$i<=$item_num;$i++)
			{
				$r=rand(1,100);
				switch(true)
				{
					case $r <=50:
					 	$sql="select d_id from wog_plus_list where d_lv=".$s[0][d_lv]." and d_type <= 7 and d_type<>".$s[0][d_type]." and d_type<>".$s[1][d_type]." ORDER BY RAND() LIMIT 1";
					break;
					case $r >50 && $r <=80:
					 	$sql="select d_id from wog_plus_list where d_lv=".$s[0][d_lv]." and d_type >= 8 and d_type <= 11 and d_type<>".$s[0][d_type]." and d_type<>".$s[1][d_type]." ORDER BY RAND() LIMIT 1";
					break;
					case $r >80 && $r <=95:
					 	$sql="select d_id from wog_plus_list where d_lv=".$s[0][d_lv]." and d_type >= 12 and d_type <= 15 and d_type<>".$s[0][d_type]." and d_type<>".$s[1][d_type]." ORDER BY RAND() LIMIT 1";
					break;
					case $r >95 && $r <=100:
					 	$sql="select d_id from wog_plus_list where d_lv=".$s[0][d_lv]." and d_type >= 16 and d_type <= 18 and d_type<>".$s[0][d_type]." and d_type<>".$s[1][d_type]." ORDER BY RAND() LIMIT 1";
					break;
				}
				$get_item=$DB_site->query_first($sql);
				$items=$wog_item_tool->item_in($items,$get_item["d_id"],1);
				
				$sql="select d_name from wog_df where d_id=".$get_item[d_id];
				$get_name=$DB_site->query_first($sql);
				$name_str[$get_name[d_name]]+=1;
			}
		}
		$bbag=$wog_arry["item_limit"]+$bag[0];
		if(count($items) > $bbag)
		{
			unset($items);
			alertWindowMsg($lang['wog_act_bid_full']);
		}
		$items=$wog_item_tool->item_out($user_id,$d_id1,$item_num,$items);
		$items=$wog_item_tool->item_out($user_id,$d_id2,$item_num,$items);
/*
		if($s[0][d_type] == $s[1][d_type]) //同類精練石合成
		{
			$s[0][d_lv]++;
			if($s[0][d_lv]>10){alertWindowMsg($lang['wog_act_plus_er5']);}
			$sql="select d_id from wog_plus_list where d_lv=".$s[0][d_lv]." and d_type=".$s[0][d_type];
			
		}
		else //不同類精練石合成
		{
			$s[0][d_lv]--;
			if($s[0][d_lv] <=0)
			{
				alertWindowMsg($lang['wog_act_plus_er3']);
			}
			$r=rand(1,100);
			switch(true)
			{
				case $r <=50:
				 	$sql="select d_id from wog_plus_list where d_lv=".$s[0][d_lv]." and d_type <= 7 and d_type<>".$s[0][d_type]." and d_type<>".$s[1][d_type]." ORDER BY RAND() LIMIT 1";
				break;
				case $r >50 && $r <=80:
				 	$sql="select d_id from wog_plus_list where d_lv=".$s[0][d_lv]." and d_type >= 8 and d_type <= 11 and d_type<>".$s[0][d_type]." and d_type<>".$s[1][d_type]." ORDER BY RAND() LIMIT 1";
				break;
				case $r >80 && $r <=95:
				 	$sql="select d_id from wog_plus_list where d_lv=".$s[0][d_lv]." and d_type >= 12 and d_type <= 15 and d_type<>".$s[0][d_type]." and d_type<>".$s[1][d_type]." ORDER BY RAND() LIMIT 1";
				break;
				case $r >95 && $r <=100:
				 	$sql="select d_id from wog_plus_list where d_lv=".$s[0][d_lv]." and d_type >= 16 and d_type <= 18 and d_type<>".$s[0][d_type]." and d_type<>".$s[1][d_type]." ORDER BY RAND() LIMIT 1";
				break;
			}
		}
		$get_item=$DB_site->query_first($sql);
		$sql="select d_name from wog_df where d_id=".$get_item[d_id];
		$get_name=$DB_site->query_first($sql);
				
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");

		$sql="select d_plus_id from wog_item where p_id=".$user_id." for update";
		$item=$DB_site->query_first($sql);
		$a_id="d_plus_id";
		$items=array();
		if(!empty($item[$a_id]))
		{
			$items=explode(",",$item[$a_id]);
		}
		$items=$wog_item_tool->item_out($user_id,$d_id1,1,$items);
		$items=$wog_item_tool->item_out($user_id,$d_id2,1,$items);

		$items=$wog_item_tool->item_in($items,$get_item["d_id"],1);
		$bbag=$wog_arry["item_limit"]+$bag[0];		
		if(count($items) > $bbag)
		{
			unset($items);
			alertWindowMsg($lang['wog_act_bid_full']);
		}
*/
		$plus_name="";
		foreach($name_str as $k=>$v)
		{
			$plus_name.=",".$k."*".$v;
		}
		$plus_name=substr($plus_name,1);
		$DB_site->query("update wog_item set d_plus_id='".implode(',',$items)."' where p_id=".$user_id);
		$DB_site->query_first("COMMIT");
		$this->plus_view($user_id,$plus_name);
	}
	function plus_arm_view($user_id,$d_name="")
	{
		global $DB_site,$_POST,$lang;
		$sql="select d_plus_id from wog_item where p_id=$user_id";
		$item_list=$DB_site->query_first($sql);

		$packs=explode(",",$item_list[0]);
		for($i=0;$i<count($packs);$i++)
		{
			$packss=explode("*",$packs[$i]);
			$temp_item.=",".$packss[0];
		}
		$temp_item=substr($temp_item,1);
		$temp="";
		if(!empty($temp_item))
		{
			$sql="select a.d_id,a.d_name,b.d_at,b.d_mat,b.d_df,b.d_mdf,b.d_str,b.d_agi,b.d_smart,b.d_life,b.d_vit,b.d_au,b.d_be
					 from wog_df a,wog_plus_list b where a.d_type=10 and a.d_id in ($temp_item) and b.d_id=a.d_id";
			$plus=$DB_site->query($sql);
			$plus_item=array();
			while($pluss=$DB_site->fetch_array($plus))
			{
				$plus_item[]=$pluss[d_id].":".$pluss[d_name].":".$pluss[d_at].":".$pluss[d_mat].":".$pluss[d_df].":".$pluss[d_mdf].":".$pluss[d_str].":".$pluss[d_agi].":".$pluss[d_smart].":".$pluss[d_life].":".$pluss[d_vit].":".$pluss[d_au].":".$pluss[d_be];
			}
					
			$DB_site->free_result($plus);
			unset($pluss);
			$temp=implode(";",$plus_item);
		}		
		
		$item_type=$_POST["temp_id"];
		if(empty($item_type)){$item_type="a_id";}
		$sql="select $item_type from wog_item where p_id=".$user_id;
		$pack=$DB_site->query_first($sql);
		if(!empty($pack[0]))
		{
			$temp_item=array(); //裝備id
//			$temp_item9=array();

			$packs=explode(",",$pack[0]);
			$sum=count($packs);
			$arm_array=get_arm_sp($packs,$sum,$temp_item,$temp_item9);
			$d_status=array(); //取出物品數值
			$temp_str=$DB_site->query("select a.d_id,a.d_df,a.d_mdf,a.d_money,a.d_name,a.d_at,a.d_mat,a.d_mstr,a.d_magl,a.d_msmart,a.d_mau,b.ch_name,a.d_send,a.d_hole,ifnull(a.d_s,0) as d_s
					,a.d_g_str,a.d_g_smart,a.d_g_agi,a.d_g_life,a.d_g_vit,a.d_g_au,a.d_g_be,a.skill,a.d_plus
					from wog_df a left join wog_character b on b.ch_id=a.ch_id  where a.d_id in (".implode(",",$temp_item).")");
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
				$d_status[$p2[d_id]][d_hole]=$p2[d_hole];
				$d_status[$p2[d_id]][d_plus]=$p2[d_plus];
				$d_status[$p2[d_id]][d_money]=$p2[d_money];
			}
			$DB_site->free_result($temp_str);
			unset($p2,$temp_item);
		}
		$item_array=array();
		$item_array2=array();
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
			$item_array2[$i]=$temp_item9[$i][0].",".$packs[$i].","
				.$item_array[d_name].","
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
				.$item_array[s_name].","
				.$item_array[stone_name]."," //15
				.$item_array[d_mstr].","
				.$item_array[d_magl].","
				.$item_array[d_msmart].","
				.$item_array[d_mau].","
				.$item_array[d_send]."," //20
				.$item_array[d_money].","
				.$item_array[d_hole].","
				.$item_array[d_plus].","//23
				.$item_array[hp]
				;
		}
		unset($item_array,$d_status,$arm_array,$temp_item9,$packs);
		showscript("parent.plus_view2('$temp','".$item_list[0]."','$d_name','".implode(";",$item_array2)."')");
		unset($plus_item,$item_list);
	}
	function plus_make_arm($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_item_tool,$wog_arry,$a_id;
		//物品字串格式{x1:x2&x3} x1物品id,x2鑲嵌id,x3精練id
		if(empty($_POST["temp1"]) || empty($_POST["temp2"]))
		{
			alertWindowMsg($lang['wog_act_plus_er1']);
		}
		if(empty($_POST["set"]))
		{
			alertWindowMsg($lang['wog_act_plus_er6']);
		}
		$set=(int)$_POST["set"];
		$d_id1=$_POST["temp1"];
		$d_id2=$_POST["temp2"];
		$bag_item_id=$d_id2;
		$len=strlen($d_id2);
		$len2=strcspn($d_id2,":");
		$len3=strcspn($d_id2,"&");
		switch(true)
		{
			case $len==$len2 && $len==$len3: //無鑲嵌,無精練
				$item_id=$d_id2;
				$ps_id=0;
			break;
			case $len!=$len2 && $len==$len3: //有鑲嵌,無精練
				$adds=explode(":",$d_id2);
				$item_id=$adds[0];
				$ps_id=0;
			break;
			case $len==$len2 && $len!=$len3: //無鑲嵌,有精練
				$adds=explode("&",$d_id2);
				$item_id=$adds[0];
				$ps_id=$adds[1];
			break;
			case $len!=$len2 && $len!=$len3: //有鑲嵌,有精練
				$adds=explode(":",$d_id2);
				$item_id=$adds[0];
				$adds=explode("&",$d_id2);
				$ps_id=$adds[1];
			break;
		}
		
		$sql="select b.d_lv,b.d_at,b.d_mat,b.d_df,b.d_mdf,b.d_str,b.d_agi,b.d_smart,b.d_life,b.d_vit,b.d_au,b.d_be from wog_plus_list b where b.d_id =$d_id1";
		$plus=$DB_site->query_first($sql);
		if(!empty($ps_id))
		{
			$sql="select b.plus_num,b.d_at,b.d_mat,b.d_df,b.d_mdf,b.d_str,b.d_agi,b.d_smart,b.d_life,b.d_vit,b.d_au,b.d_be from wog_plus_setup b where b.ps_id =$ps_id";
			$plus_setup=$DB_site->query_first($sql);			
		}
		else
		{
			$plus_setup[plus_num]=0;
			$plus_setup[d_at]=0;
			$plus_setup[d_mat]=0;
			$plus_setup[d_df]=0;
			$plus_setup[d_mdf]=0;
			$plus_setup[d_str]=0;
			$plus_setup[d_agi]=0;
			$plus_setup[d_smart]=0;
			$plus_setup[d_life]=0;
			$plus_setup[d_vit]=0;
			$plus_setup[d_au]=0;
			$plus_setup[d_be]=0;
		}
		$plus_setup[plus_num]++;
		if($plus_setup[plus_num]!=$plus[d_lv])
		{
			alertWindowMsg(sprintf($lang['wog_act_plus_er4'],$plus_setup[plus_num]));
		}
		
		$sql="select d_fie,d_plus from wog_df where d_id=$item_id";
		$item=$DB_site->query_first($sql);
		$a_id2=type_name($item[d_fie]);
		if($item[d_plus] < $plus_setup[plus_num])
		{
			alertWindowMsg($lang['wog_act_plus_er7']);
		}
		$r=rand(1,100);
		if(10*$plus_setup[plus_num] > $r)
		{
			$DB_site->query_first("set autocommit=0");
			$DB_site->query_first("BEGIN");
			switch($set)
			{
				case 1:
					$sql="select d_item_id from wog_item where p_id=".$user_id." for update";
					$item=$DB_site->query_first($sql);
					$a_id="d_item_id";
					$items=array();
					if(!empty($item[$a_id]))
					{
						$items=explode(",",$item[$a_id]);
					}
					$items=$wog_item_tool->item_out($user_id,2284,1,$items);
					$DB_site->query("update wog_item set d_item_id='".implode(',',$items)."' where p_id=".$user_id);
					$time=time();
					$sql="insert wog_vip_log(p_id,d_id,datetime)values($user_id,2284,$time)";
					$DB_site->query($sql);
					break;
				case 2:
					$sql="select d_plus_id from wog_item where p_id=".$user_id." for update";
					$item=$DB_site->query_first($sql);
					$a_id="d_plus_id";
					$items=array();
					if(!empty($item[$a_id]))
					{
						$items=explode(",",$item[$a_id]);
					}
					$items=$wog_item_tool->item_out($user_id,$d_id1,1,$items);
					$DB_site->query("update wog_item set d_plus_id='".implode(',',$items)."' where p_id=".$user_id);
					break;
				default:
					alertWindowMsg($lang['wog_act_plus_er6']);
					break;				
			}
			$DB_site->query_first("COMMIT");			
			$this->plus_arm_view($user_id,$lang['wog_act_plus_msg1']);
		}
		if(!empty($plus[d_at]))
		{
			$m=explode(",",$plus[d_at]);
			$plus_setup[d_at]+=rand($m[0],$m[1]);			
		}
		if(!empty($plus[d_mat]))
		{
			$m=explode(",",$plus[d_mat]);
			$plus_setup[d_mat]+=rand($m[0],$m[1]);			
		}
		if(!empty($plus[d_df]))
		{
			$m=explode(",",$plus[d_df]);
			$plus_setup[d_df]+=rand($m[0],$m[1]);			
		}
		if(!empty($plus[d_mdf]))
		{
			$m=explode(",",$plus[d_mdf]);
			$plus_setup[d_mdf]+=rand($m[0],$m[1]);			
		}
		if(!empty($plus[d_str]))
		{
			$m=explode(",",$plus[d_str]);
			$plus_setup[d_str]+=rand($m[0],$m[1]);			
		}
		if(!empty($plus[d_agi]))
		{
			$m=explode(",",$plus[d_agi]);
			$plus_setup[d_agi]+=rand($m[0],$m[1]);			
		}
		if(!empty($plus[d_smart]))
		{
			$m=explode(",",$plus[d_smart]);
			$plus_setup[d_smart]+=rand($m[0],$m[1]);			
		}
		if(!empty($plus[d_life]))
		{
			$m=explode(",",$plus[d_life]);
			$plus_setup[d_life]+=rand($m[0],$m[1]);			
		}
		if(!empty($plus[d_vit]))
		{
			$m=explode(",",$plus[d_vit]);
			$plus_setup[d_vit]+=rand($m[0],$m[1]);			
		}
		if(!empty($plus[d_au]))
		{
			$m=explode(",",$plus[d_au]);
			$plus_setup[d_au]+=rand($m[0],$m[1]);			
		}
		if(!empty($plus[d_be]))
		{
			$m=explode(",",$plus[d_be]);
			$plus_setup[d_be]+=rand($m[0],$m[1]);			
		}
		
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select d_plus_id,$a_id2 from wog_item where p_id=".$user_id." for update";
		$item=$DB_site->query_first($sql);
		$a_id="d_plus_id";
		$items=array();
		if(!empty($item[$a_id]))
		{
			$items=explode(",",$item[$a_id]);
		}
		$items=$wog_item_tool->item_out($user_id,$d_id1,1,$items);
		
		$a_id=$a_id2;
		$items2=array();
		if(!empty($item[$a_id]))
		{
			$items2=explode(",",$item[$a_id]);
		}
		$items2=$wog_item_tool->item_out($user_id,$bag_item_id,1,$items2);
		if(!empty($ps_id))
		{
			$sql="update wog_plus_setup set plus_num=".$plus_setup[plus_num].
			",d_at=".$plus_setup[d_at].
			",d_mat=".$plus_setup[d_mat].
			",d_df=".$plus_setup[d_df].
			",d_mdf=".$plus_setup[d_mdf].
			",d_str=".$plus_setup[d_str].
			",d_agi=".$plus_setup[d_agi].
			",d_smart=".$plus_setup[d_smart].
			",d_life=".$plus_setup[d_life].
			",d_vit=".$plus_setup[d_vit].
			",d_au=".$plus_setup[d_au].
			",d_be=".$plus_setup[d_be]." where ps_id=".$ps_id;
			$DB_site->query($sql);
		}else
		{
			$sql="insert wog_plus_setup(p_id,d_id,plus_num,d_at,d_mat,d_df,d_mdf,d_str,d_agi,d_smart,d_life,d_vit,d_au,d_be)values(
			$user_id,
			$item_id,
			$plus_setup[plus_num],
			$plus_setup[d_at],
			$plus_setup[d_mat],
			$plus_setup[d_df],
			$plus_setup[d_mdf],
			$plus_setup[d_str],
			$plus_setup[d_agi],
			$plus_setup[d_smart],
			$plus_setup[d_life],
			$plus_setup[d_vit],
			$plus_setup[d_au],
			$plus_setup[d_be]
			)";
			$DB_site->query($sql);
			$ps_id=$DB_site->insert_id();
			$bag_item_id.="&".$ps_id;
		}
		
		$items2=$wog_item_tool->item_in($items2,$bag_item_id,1);
		$DB_site->query("update wog_item set d_plus_id='".implode(',',$items)."',$a_id2='".implode(',',$items2)."' where p_id=".$user_id);
		$DB_site->query_first("COMMIT");
		$sql="select d_name from wog_df where d_id=".$item_id;
		$get_name=$DB_site->query_first($sql);
		$this->plus_arm_view($user_id,$get_name[d_name]."+".$plus_setup[plus_num]);
	}
}
?>