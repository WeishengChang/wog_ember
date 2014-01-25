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

// wog show java script
function alertWindowMsg($theMsg,$theURL = "default",$s_save=1) {
	global $DB_site,$_SESSION;
	if(isset($DB_site))
	{
		$DB_site->close();
	}
	echo "<html>\n<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"JavaScript\">\n";
	echo "alert(\"$theMsg\")\n";
	if ($theURL != "default") {
		echo "document.location = '" . $theURL . "'\n";
	}
	echo "</script>\n</head>\n<body></body>\n</html>\n";
/*	if($s_save)
	{
		$_SESSION['act_time']=time();
	}
*/
	compress_exit();		// EXIT here!
}
function alertWindowMsg_sns($theMsg) {
	global $DB_site;
	if(isset($DB_site))
	{
		$DB_site->close();
	}
	echo "<html>\n<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"JavaScript\">\n";
	echo "alert(\"$theMsg\")\n";
	echo "history.back()\n";
	echo "</script>\n</head>\n<body></body>\n</html>\n";
/*	if($s_save)
	{
		$_SESSION['act_time']=time();
	}
*/
	compress_exit();		// EXIT here!
}
function alertWindowMsg_continue($theMsg) {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"JavaScript\">\n";
	echo "alert(\"$theMsg\")\n";
	echo "</script>\n";
}
function alertTo_chat($theMsg) {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"JavaScript\">\n";
	echo "parent.msg_to_chat(\"$theMsg\")\n";
	echo "</script>\n";
}

// wog show java script
function showscript($temp_s) {
	global $DB_site,$_SESSION,$wog_arry,$_SERVER,$HTTP_USER_AGENT;
	if(isset($DB_site))
	{
		$DB_site->close();
	}
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"JavaScript\">\n";
	echo $temp_s.";\n";
	echo "</script>\n";
//	$_SESSION['act_time']=time();
	compress_exit();		// EXIT here!
}
function showscript_continue($a) {
	echo "<script language=\"JavaScript\">\n";
	echo $a.";\n";
	echo "</script>\n";
}
function charset() {
	return "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
}
function set_date($time)
{
	global $wog_arry;
	$time+=$wog_arry["timezone"]*3600;
	return date("Y/m/d  g:i a",$time);
}
function set_date_notime($time)
{
	global $wog_arry;
	$time+=$wog_arry["timezone"]*3600;
	return date("Y/m/d",$time);
}
//################## check_type begin ################
function check_type($temp_id,$type=-1)
{
	global $a_id,$DB_site;
	if($type==-1)
	{
		$arm_main=$DB_site->query_first("select d_fie from wog_df where d_id=".$temp_id);
		if(!$arm_main)
		{
			alertWindowMsg("無此物品");
		}
		$temp_id=$arm_main[0];
	}
	$a_id=type_name($temp_id);
	return $temp_id;
	unset($temp);
}

function type_name($temp_id)
{
	global $s_id;
	$s_id="";
	switch($temp_id)
	{
		case "0":
			$s_id = "s_id";
			return "a_id";
		break;
		case "1":
			$s_id = "s_head_id";
			return "d_head_id";
		break;
		case "2":
			$s_id = "s_body_id";
			return "d_body_id";
		break;
		case "3":
			$s_id = "s_hand_id";
			return "d_hand_id";
		break;
		case "4":
			$s_id = "s_foot_id";
			return "d_foot_id";
		break;
		case "5":
			return "d_item_id";
		break;
		case "5,6":
			return "d_item_id";
		break;
		case "6":
			return "d_item_id";
		break;
		case "11":
			return "d_item_id2";
		break;		
		case "7":
			return "d_stone_id";
		break;
		case "8":
			return "d_honor_id";
		break;
		case "9":
			return "d_key_id";
		break;
		case "10":
			return "d_plus_id";
		break;
		default:
			alertWindowMsg("無此物品");
		break;
	}
}

//######## post_check begin #########
function post_check($post)
{
	global $_COOKIE;
	$invalidCharacter="<>;'\"";
	foreach ($post as $value){
		if(is_array($value)){
			post_check($value);
			}else{
			$a=strlen($value);
			$b=strcspn($value,$invalidCharacter);
			if($a-$b){
				if($b > $a){$a=$a*3;}
				if($a-$b){
				alertWindowMsg('有不正常符號(2)--'.$value);
				}
			}
		}
	}
	if(isset($_COOKIE['wog_cookie'])){
		if(!is_numeric($_COOKIE['wog_cookie'])){
			alertWindowMsg('有不正常符號(3)');
		}
	}
	/*
	if(isset($_COOKIE['wog_bbs_id'])){
		if(!is_numeric($_COOKIE["wog_bbs_id"])){
			alertWindowMsg('有不正常符號(4)');
		}
	}
	*/
}

function check_buffer($user_id)
{
	global $DB_site;
	$time=time();
	$sql="select p_id from wog_player_buffer where p_id=".$user_id." and end_time<".$time." LIMIT 1";
	$buffer=$DB_site->query_first($sql);
	if($buffer)
	{
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="delete from wog_player_buffer where p_id=".$user_id." and end_time<".$time;
		$DB_site->query($sql);
		check_status($user_id);
		$DB_site->query_first("COMMIT");
	}
}
function check_arm_status($user_id)
{
	global $DB_site;
	$DB_site->query_first("set autocommit=0");
	$DB_site->query_first("BEGIN");
	check_status($user_id);
	$DB_site->query_first("COMMIT");
}
function check_status($user_id)
{
	global $DB_site;
	$sql="select 
	a_id,d_body_id,d_head_id,d_hand_id,d_foot_id,d_item_id
	,s_a_id,s_body_id,s_head_id,s_hand_id,s_foot_id
	,p_a_id,p_body_id,p_head_id,p_hand_id,p_foot_id
	 from wog_player_arm where p_id=".$user_id." for update";
	$arm=$DB_site->query_first($sql);
	if($arm) 
	{
		
		$arm_array=array();
		$temp_item2=array(); // 鑲嵌
		$temp_item3=array(); // 精鍊
		$temp_item4=array(); // 裝備
		if(!empty($arm[s_a_id])){$temp_item2[]=$arm[s_a_id];}
		if(!empty($arm[s_body_id])){$temp_item2[]=$arm[s_body_id];}
		if(!empty($arm[s_head_id])){$temp_item2[]=$arm[s_head_id];}
		if(!empty($arm[s_hand_id])){$temp_item2[]=$arm[s_hand_id];}
		if(!empty($arm[s_foot_id])){$temp_item2[]=$arm[s_foot_id];}
		
		if(!empty($arm[p_a_id])){$temp_item3[]=$arm[p_a_id];}
		if(!empty($arm[p_body_id])){$temp_item3[]=$arm[p_body_id];}
		if(!empty($arm[p_head_id])){$temp_item3[]=$arm[p_head_id];}
		if(!empty($arm[p_hand_id])){$temp_item3[]=$arm[p_hand_id];}
		if(!empty($arm[p_foot_id])){$temp_item3[]=$arm[p_foot_id];}
		
		if(!empty($arm[a_id])){$temp_item4[]=$arm[a_id];}
		if(!empty($arm[d_body_id])){$temp_item4[]=$arm[d_body_id];}
		if(!empty($arm[d_head_id])){$temp_item4[]=$arm[d_head_id];}
		if(!empty($arm[d_hand_id])){$temp_item4[]=$arm[d_hand_id];}
		if(!empty($arm[d_foot_id])){$temp_item4[]=$arm[d_foot_id];}
		if(!empty($arm[d_item_id])){$temp_item4[]=$arm[d_item_id];}

		//取出鑲嵌數值 begin
		if(count($temp_item2)>0)
		{
			$sql="select ifnull(sum(a.s_df),0) as s_df,ifnull(sum(a.s_mdf),0) as s_mdf,ifnull(sum(a.s_agl),0) as s_agl,ifnull(sum(a.s_at),0) as s_at,ifnull(sum(a.s_mat),0) as s_mat
				,ifnull(sum(a.s_str),0) as s_str,ifnull(sum(a.s_life),0) as s_life,ifnull(sum(a.s_vit),0) as s_vit,ifnull(sum(a.s_smart),0) as s_smart,ifnull(sum(a.s_au),0) as s_au,ifnull(sum(a.s_be),0) as s_be
				,ifnull(sum(a.s_hpmax),0) as s_hpmax
				from wog_stone_list a,wog_stone_setup b where b.hs_id in (".implode(",",$temp_item2).") and a.d_id in (b.hole_1,b.hole_2,b.hole_3,b.hole_4) and a.d_class <99";
			$temp_str=$DB_site->query($sql);
			while($p2=$DB_site->fetch_array($temp_str))
			{
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
			}

			$sql="select ifnull(sum(a.s_df),0) as s_df,ifnull(sum(a.s_mdf),0) as s_mdf,ifnull(sum(a.s_agl),0) as s_agl,ifnull(sum(a.s_at),0) as s_at,ifnull(sum(a.s_mat),0) as s_mat
				,ifnull(sum(a.s_str),0) as s_str,ifnull(sum(a.s_life),0) as s_life,ifnull(sum(a.s_vit),0) as s_vit,ifnull(sum(a.s_smart),0) as s_smart,ifnull(sum(a.s_au),0) as s_au,ifnull(sum(a.s_be),0) as s_be
				,ifnull(sum(a.s_hpmax),0) as s_hpmax
				from wog_stone_temp a,wog_stone_setup b where b.hs_id in (".implode(",",$temp_item2).") and a.ht_id in (b.hole_temp_1,b.hole_temp_2,b.hole_temp_3,b.hole_temp_4)";
			
			$temp_str=$DB_site->query($sql);
			while($p2=$DB_site->fetch_array($temp_str))
			{
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
		//取出鑲嵌數值 end

		//取出精練數值  begin
		if(count($temp_item3)>0)
		{
			$sql="select ifnull(sum(b.d_at),0) as d_at
			,ifnull(sum(b.d_mat),0) as d_mat
			,ifnull(sum(b.d_df),0) as d_df 
			,ifnull(sum(b.d_mdf),0) as d_mdf
			,ifnull(sum(b.d_str),0) as d_str
			,ifnull(sum(b.d_agi),0) as d_agi
			,ifnull(sum(b.d_smart),0) as d_smart
			,ifnull(sum(b.d_life),0) as d_life
			,ifnull(sum(b.d_vit),0) as d_vit
			,ifnull(sum(b.d_au),0) as d_au
			,ifnull(sum(b.d_be),0) as d_be 
			from wog_plus_setup b where b.ps_id in (".implode(",",$temp_item3).")";
			$temp_str=$DB_site->query($sql);
			while($p2=$DB_site->fetch_array($temp_str))
			{
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
			}
		}
		//取出精練數值  end

		//取出裝備數值  begin
		if(count($temp_item4)>0)
		{
			$sql="select ifnull(sum(a.d_df),0) as df,ifnull(sum(a.d_mdf),0) as mdf,ifnull(sum(a.d_g_agi),0) as agi,ifnull(sum(a.d_at),0) as at,ifnull(sum(a.d_mat),0) as mat
				,ifnull(sum(a.d_g_str),0) as str,ifnull(sum(a.d_g_life),0) as life,ifnull(sum(a.d_g_vit),0) as vit,ifnull(sum(a.d_g_smart),0) as smart,ifnull(sum(a.d_g_au),0) as au,ifnull(sum(a.d_g_be),0) as be
				from wog_df a where a.d_id in (".implode(",",$temp_item4).") ";
			$temp_str=$DB_site->query($sql);
			while($p2=$DB_site->fetch_array($temp_str))
			{
				$arm_array[ds][at]=$p2[at];
				$arm_array[ds][mat]=$p2[mat];
				$arm_array[ds][df]=$p2[df];
				$arm_array[ds][mdf]=$p2[mdf];
				$arm_array[ds][agi]=$p2[agi];
				$arm_array[ds][life]=$p2[life];
				$arm_array[ds][au]=$p2[au];
				$arm_array[ds][be]=$p2[be];
				$arm_array[ds][vit]=$p2[vit];
				$arm_array[ds][smart]=$p2[smart];
				$arm_array[ds][str]=$p2[str];
			}
		}
		//取出裝備數值  end
		$sql="select base_life,base_be,base_smart,base_vit,base_str,base_agi,base_au,base_vit from wog_player where p_id=".$user_id;
		$p=$DB_site->query_first($sql);
		
		$at=$arm_array[hs][at]+$arm_array[ps][at]+$arm_array[ds][at];
		$mat=$arm_array[hs][mat]+$arm_array[ps][mat]+$arm_array[ds][mat];
		$df=$arm_array[hs][df]+$arm_array[ps][df]+$arm_array[ds][df];
		$mdf=$arm_array[hs][mdf]+$arm_array[ps][mdf]+$arm_array[ds][mdf];
		
		$agi=$arm_array[hs][agi]+$arm_array[ps][agi]+$arm_array[ds][agi]+$p[base_agi];
		$life=$arm_array[hs][life]+$arm_array[ps][life]+$arm_array[ds][life]+$p[base_life];
		$au=$arm_array[hs][au]+$arm_array[ps][au]+$arm_array[ds][au]+$p[base_au];
		$be=$arm_array[hs][be]+$arm_array[ps][be]+$arm_array[ds][be]+$p[base_be];
		$vit=$arm_array[hs][vit]+$arm_array[ps][vit]+$arm_array[ds][vit]+$p[base_vit];
		$smart=$arm_array[hs][smart]+$arm_array[ps][smart]+$arm_array[ds][smart]+$p[base_smart];
		$str=$arm_array[hs][str]+$arm_array[ps][str]+$arm_array[ds][str]+$p[base_str];

		$at+=$str;
		$mat+=$smart;
		$df+=$vit;
		$mdf+=$be;
				
		$hpmax=player_hp($life)+$arm_array[hs][hp];
		$spmax=player_sp($smart+$be);
						
		//取出商城道具增益屬性 begin
		$time=time();
		$sql="select p_id,at,mat,df,mdf,hp,sp from wog_player_buffer where p_id=$user_id and end_time>".$time;
		$temp_str=$DB_site->query($sql);
		while($p2=$DB_site->fetch_array($temp_str))
		{
			if(!empty($p2[at])){$at*=$p2[at];}
			if(!empty($p2[mat])){$mat*=$p2[mat];}
			if(!empty($p2[df])){$df*=$p2[df];}
			if(!empty($p2[mdf])){$mdf*=$p2[mdf];}
			if(!empty($p2[hp])){$hpmax*=$p2[hp];}
			if(!empty($p2[sp])){$spmax*=$p2[sp];}
		}		
		//取出商城道具增益屬性 end
		$DB_site->free_result($temp_str);
		unset($p2);
		$sql="delete from wog_player_buffer where p_id=".$user_id." and end_time<".$time;
		$DB_site->query($sql);
		$sql="update wog_player set df=".$df.",at=".$at.",mat=".$mat.",mdf=".$mdf.
			",agi=".$agi.",life=".$life.",au=".$au.",be=".$be.",vit=".$vit.
			",smart=".$smart.",str=".$str.",hpmax=$hpmax,spmax=$spmax where p_id=".$user_id;
		$DB_site->query($sql);
	}
}

//######### player_hp begin ############

function player_hp($life)
{
	$hpmax=round($life*25)+50;
	return $hpmax;
}

//######### player_sp begin ############

function player_sp($smart)
{
	$spmax=round($smart*3)+25;
	return $spmax;
}

//######### player_age begin ############

function player_age($p_cdate,$day)
{
	return floor((time()-$p_cdate)/($day*24*60*60));
}

//######### chk_item_status begin ############
// 放入裝備增益數值
function chk_item_status($a1,$a2=null,$a3=null)
{
	$temp=array(
		at=>0,
		mat=>0,
		df=>0,
		mdf=>0,
		agi=>0,
		life=>0,
		au=>0,
		be=>0,
		vit=>0,
		smart=>0,
		str=>0,
		hp=>0
	);
	if($a2!=null)
	{
		$temp[at]+=$a2[at];
		$temp[mat]+=$a2[mat];
		$temp[df]+=$a2[df];
		$temp[mdf]+=$a2[mdf];
		$temp[agi]+=$a2[agi];
		$temp[life]+=$a2[life];
		$temp[au]+=$a2[au];
		$temp[be]+=$a2[be];
		$temp[vit]+=$a2[vit];
		$temp[smart]+=$a2[smart];
		$temp[str]+=$a2[str];
		$temp[hp]+=$a2[hp];
		$a1[stone_name]=$a2[d_name];
	}
	if($a3!=null)
	{
		$temp[at]+=$a3[at];
		$temp[mat]+=$a3[mat];
		$temp[df]+=$a3[df];
		$temp[mdf]+=$a3[mdf];
		$temp[agi]+=$a3[agi];
		$temp[life]+=$a3[life];
		$temp[au]+=$a3[au];
		$temp[be]+=$a3[be];
		$temp[vit]+=$a3[vit];
		$temp[smart]+=$a3[smart];
		$temp[str]+=$a3[str];
		$a1[d_name].="+".$a3[plus_num];
	}
	foreach($temp as $key=>$val)
	{
		if(!empty($val))
		{
			$temp[$key]="+".$val;
		}else
		{
			$temp[$key]="";
		}
	}
	$a1[at].=$temp[at];
	$a1[mat].=$temp[mat];
	$a1[df].=$temp[df];
	$a1[mdf].=$temp[mdf];
	$a1[agi].=$temp[agi];
	$a1[life].=$temp[life];
	$a1[au].=$temp[au];
	$a1[be].=$temp[be];
	$a1[vit].=$temp[vit];
	$a1[smart].=$temp[smart];
	$a1[str].=$temp[str];
	$a1[hp].=$temp[hp];
	unset($temp);
	return $a1;
}
// 取出裝備增益數值
function get_arm_sp($packs,$sum,&$temp_item,&$temp_item9)
{
	global $DB_site;
	$temp_item=array(); //裝備id
	$temp_item2=array(); //鑲嵌id
	$temp_item3=array(); //精練id
	$temp_item9=array();
	$arm_array=array();
	for($i=0;$i<$sum;$i++)
	{
		$len=strlen($packs[$i]);
		$len2=strcspn($packs[$i],":"); //鑲嵌
		$len3=strcspn($packs[$i],"&"); //精練
		$temp_item9[$i][3]=$len;
		$temp_item9[$i][4]=$len2;
		$temp_item9[$i][5]=$len3;
		switch(true)
		{
			case $len==$len2 && $len==$len3: //無鑲嵌,無精練
				$temp_item[]=$packs[$i];
				$temp_item9[$i][0]=$packs[$i];
			break;
			case $len!=$len2 && $len==$len3: //有鑲嵌,無精練
				$adds=explode(":",$packs[$i]);
				$temp_item[]=$adds[0];
				$temp_item2[]=$adds[1];
				$temp_item9[$i][0]=$adds[0];
				$temp_item9[$i][1]=$adds[1];
			break;
			case $len==$len2 && $len!=$len3: //無鑲嵌,有精練
				$adds=explode("&",$packs[$i]);
				$temp_item[]=$adds[0];
				$temp_item3[]=$adds[1];
				$temp_item9[$i][0]=$adds[0];
				$temp_item9[$i][2]=$adds[1];
			break;
			case $len!=$len2 && $len!=$len3: //有鑲嵌,有精練
				$adds=explode(":",$packs[$i]);
				$temp_item[]=$adds[0];
				$temp_item9[$i][0]=$adds[0];
				$adds=explode("&",$packs[$i]);

				$temp_item3[]=$adds[1];						
				$temp_item9[$i][2]=$adds[1];

				$adds=explode(":",$adds[0]);
				$temp_item2[]=$adds[1];
				$temp_item9[$i][1]=$adds[1];
			break;
		}
	}

	if(count($temp_item2) > 0) //取出鑲嵌數值
	{
		$temp_str=$DB_site->query("select b.hs_id,a.d_name
				from wog_df a,wog_stone_setup b where b.hs_id in (".implode(",",$temp_item2).") and a.d_id in (b.hole_1,b.hole_2,b.hole_3,b.hole_4)");
		while($p2=$DB_site->fetch_array($temp_str))
		{
			$arm_array[hs][$p2[hs_id]][d_name].="　".$p2[d_name];
		}
		$DB_site->free_result($temp_str);
		
		$sql="select b.hs_id,ifnull(sum(a.s_df),0) as s_df,ifnull(sum(a.s_mdf),0) as s_mdf,ifnull(sum(a.s_agl),0) as s_agl,ifnull(sum(a.s_at),0) as s_at,ifnull(sum(a.s_mat),0) as s_mat
			,ifnull(sum(a.s_str),0) as s_str,ifnull(sum(a.s_life),0) as s_life,ifnull(sum(a.s_vit),0) as s_vit,ifnull(sum(a.s_smart),0) as s_smart,ifnull(sum(a.s_au),0) as s_au,ifnull(sum(a.s_be),0) as s_be
			,ifnull(sum(a.s_hpmax),0) as s_hpmax
			from wog_stone_list a,wog_stone_setup b where b.hs_id in (".implode(",",$temp_item2).") and a.d_id in (b.hole_1,b.hole_2,b.hole_3,b.hole_4) and a.d_class < 99 group by b.hs_id";
		$temp_str=$DB_site->query($sql);
		while($p2=$DB_site->fetch_array($temp_str))
		{
			$arm_array[hs][$p2[hs_id]][at]=$p2[s_at];
			$arm_array[hs][$p2[hs_id]][mat]=$p2[s_mat];
			$arm_array[hs][$p2[hs_id]][df]=$p2[s_df];
			$arm_array[hs][$p2[hs_id]][mdf]=$p2[s_mdf];
			$arm_array[hs][$p2[hs_id]][agi]=$p2[s_agl];
			$arm_array[hs][$p2[hs_id]][life]=$p2[s_life];
			$arm_array[hs][$p2[hs_id]][au]=$p2[s_au];
			$arm_array[hs][$p2[hs_id]][be]=$p2[s_be];
			$arm_array[hs][$p2[hs_id]][vit]=$p2[s_vit];
			$arm_array[hs][$p2[hs_id]][smart]=$p2[s_smart];
			$arm_array[hs][$p2[hs_id]][str]=$p2[s_str];
			$arm_array[hs][$p2[hs_id]][hp]=$p2[s_hpmax];
		}
	
		$sql="select b.hs_id,ifnull(sum(a.s_df),0) as s_df,ifnull(sum(a.s_mdf),0) as s_mdf,ifnull(sum(a.s_agl),0) as s_agl,ifnull(sum(a.s_at),0) as s_at,ifnull(sum(a.s_mat),0) as s_mat
			,ifnull(sum(a.s_str),0) as s_str,ifnull(sum(a.s_life),0) as s_life,ifnull(sum(a.s_vit),0) as s_vit,ifnull(sum(a.s_smart),0) as s_smart,ifnull(sum(a.s_au),0) as s_au,ifnull(sum(a.s_be),0) as s_be
			,ifnull(sum(a.s_hpmax),0) as s_hpmax
			from wog_stone_temp a,wog_stone_setup b where b.hs_id in (".implode(",",$temp_item2).") and a.ht_id in (b.hole_temp_1,b.hole_temp_2,b.hole_temp_3,b.hole_temp_4) group by b.hs_id";
		$temp_str=$DB_site->query($sql);
		while($p2=$DB_site->fetch_array($temp_str))
		{
			$arm_array[hs][$p2[hs_id]][at]+=$p2[s_at];
			$arm_array[hs][$p2[hs_id]][mat]+=$p2[s_mat];
			$arm_array[hs][$p2[hs_id]][df]+=$p2[s_df];
			$arm_array[hs][$p2[hs_id]][mdf]+=$p2[s_mdf];
			$arm_array[hs][$p2[hs_id]][agi]+=$p2[s_agl];
			$arm_array[hs][$p2[hs_id]][life]+=$p2[s_life];
			$arm_array[hs][$p2[hs_id]][au]+=$p2[s_au];
			$arm_array[hs][$p2[hs_id]][be]+=$p2[s_be];
			$arm_array[hs][$p2[hs_id]][vit]+=$p2[s_vit];
			$arm_array[hs][$p2[hs_id]][smart]+=$p2[s_smart];
			$arm_array[hs][$p2[hs_id]][str]+=$p2[s_str];
			$arm_array[hs][$p2[hs_id]][hp]=$p2[s_hpmax];
		}
	}
	unset($temp_item2);
	if(count($temp_item3) > 0) //取出精練數值
	{
		$temp_str=$DB_site->query("select b.ps_id,b.plus_num,b.d_at,b.d_mat,b.d_df,b.d_mdf,b.d_str,b.d_agi,b.d_smart,b.d_life,b.d_vit,b.d_au,b.d_be from wog_plus_setup b where b.ps_id in (".implode(",",$temp_item3).")");
		while($p2=$DB_site->fetch_array($temp_str))
		{
			$arm_array[ps][$p2[ps_id]][plus_num]=$p2[plus_num];
			$arm_array[ps][$p2[ps_id]][at]=$p2[d_at];
			$arm_array[ps][$p2[ps_id]][mat]=$p2[d_mat];
			$arm_array[ps][$p2[ps_id]][df]=$p2[d_df];
			$arm_array[ps][$p2[ps_id]][mdf]=$p2[d_mdf];
			$arm_array[ps][$p2[ps_id]][agi]=$p2[d_agi];
			$arm_array[ps][$p2[ps_id]][life]=$p2[d_life];
			$arm_array[ps][$p2[ps_id]][au]=$p2[d_au];
			$arm_array[ps][$p2[ps_id]][be]=$p2[d_be];
			$arm_array[ps][$p2[ps_id]][vit]=$p2[d_vit];
			$arm_array[ps][$p2[ps_id]][smart]=$p2[d_smart];
			$arm_array[ps][$p2[ps_id]][str]=$p2[d_str];
		}
		$DB_site->free_result($temp_str);
	}
	unset($temp_item3,$p2);
	return $arm_array;
}
// 取出裝備增益數值的對應id
function get_arm_id($d_id,&$item_id,&$hs_id,&$ps_id)
{
	//$d_id:在背包中的id , $item_id:裝備id , $hs_id:鑲嵌id , $ps_id:精練id
	$len=strlen($d_id);
	$len2=strcspn($d_id,":");
	$len3=strcspn($d_id,"&");
	switch(true)
	{
		case $len==$len2 && $len==$len3: //無鑲嵌,無精練
			$item_id=$d_id;
			$hs_id=0;
			$ps_id=0;
		break;
		case $len!=$len2 && $len==$len3: //有鑲嵌,無精練
			$adds=explode(":",$d_id);
			$item_id=$adds[0];
			$hs_id=$adds[1];
			$ps_id=0;
		break;
		case $len==$len2 && $len!=$len3: //無鑲嵌,有精練
			$adds=explode("&",$d_id);
			$item_id=$adds[0];
			$ps_id=$adds[1];
			$hs_id=0;
		break;
		case $len!=$len2 && $len!=$len3: //有鑲嵌,有精練
			$adds=explode(":",$d_id);
			$item_id=$adds[0];
			$adds=explode("&",$adds[1]);
			$hs_id=$adds[0];
			$ps_id=$adds[1];
		break;
	}

}
function get_type_name($temp_id,&$s_id,&$p_id)
{
	switch($temp_id)
	{
		case 0:
			$s_id= "s_a_id";
			$p_id= "p_a_id";
		break;
		case 1:
			$s_id= "s_head_id";
			$p_id= "p_head_id";
		break;
		case 2:
			$s_id= "s_body_id";
			$p_id= "p_body_id";
		break;
		case 3:
			$s_id= "s_hand_id";
			$p_id= "p_hand_id";
		break;
		case 4:
			$s_id= "s_foot_id";
			$p_id= "p_foot_id";
		break;
		case 5:
			$s_id= "s_item_id";
			$p_id= "p_item_id";
		break;
		case 11:
			$s_id= "s_item_id";
			$p_id= "p_item_id";
		break;
		default:
			alertWindowMsg("無此物品");
		break;
	}
}
function chk_monster_kill($a)
{
	foreach($a as $v)
	{
		if($v > 0)
		{
			return false;
		}
	}
	return true;
}
//######### cookie_clean begin ############
function cookie_clean()
{
	global $_COOKIE;
	//$time=time()-60;
	setcookie("wog_cookie","");
	setcookie("wog_cookie_name","");
	setcookie("wog_bbs_id","");
	setcookie("wog_cookie_debug","");
	setcookie("wog_chat_cookie_debug","");
/*
	$time=time()-60;
	setcookie("wog_cookie","",$time);
	setcookie("wog_cookie_name","",$time);
	setcookie("wog_bbs_id","",$time);
	setcookie("wog_cookie_debug","",$time);
	setcookie("wog_cookie_mission_id",0,$time);
*/
}

//######### get_ip begin ############ phpbb論壇專用

function get_ip()
{
	global $_SERVER,$_ENV,$REMOTE_ADDR;
	$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
	return $client_ip;
}

//########## session_check begin ##########

function session_check($time,$check_type)
{
	global $_SESSION,$_GET,$_POST;
	if(isset($_SESSION["act_time"]))
	{
		$check_time=$_SESSION["act_time"];
		if((time()-$check_time)<$time)
		{
			if($_POST["act"]!="22")
			{
				alertWindowMsg("請等完 : ".$time."秒","default",0);
			}else
			{
				alertWindowMsg("請等完 : 3秒","default",0);
			}
		}else
		{
			$_SESSION["act_time"]=time();
		}
	}else
	{
		$_SESSION["act_time"]=time();
	}
}

function compress_exit($str="")
{
	global $do_gzip_compress;
	if ( $do_gzip_compress )
	{
//
// Borrowed from php.net!
//
		$gzip_contents = ob_get_contents();
		ob_end_clean();
		$gzip_size = strlen($gzip_contents);
		$gzip_crc = crc32($gzip_contents);
		$gzip_contents = gzcompress($gzip_contents, 9);
		$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);
		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $gzip_contents;
		echo pack('V', $gzip_crc);
		echo pack('V', $gzip_size);
	}
	exit;
}
function toJSON(&$obj){
	$s='';
	switch(true){
		case is_array($obj) && isset($obj[0]):
			$str.=parseJSONOfArray($obj);
		break;
		case is_object($obj):
			$str.=parseJSON($obj);
		break;
		case empty($obj):
			$str.='[]';
		break;
		default:
			$str.='{error:\'Unkown type of variables.\'}';
		break;
	}
	return $str;
}
function parseJSON(&$obj){
	$str='';
	$n=0;
	foreach($obj as $key=>$value){
		$str.=','.$key.':';
		switch(gettype($value)){
			case 'string':
				if(is_numeric($value)){
					$str.=$value;
				}else{
					$str.='\''.str_replace("\r\n",'',str_replace('\'','\\\'',str_replace("'",'\'',$value))).'\'';
				}
			break;
			case 'integer':
			case 'double':
				$str.=$value;
			break;
			case 'boolean':
				$str.=$value? 'true' : 'false' ;
			break;
			case 'array':
				if($value[0]){
					$str.=parseJSONOfArray($value);
				}else{
					$str.=parseJSON($value);
				}
			break;
			case 'object':
				$str.=parseJSON($value);
			break;
			default:
			case 'NULL':
			$str.='null';
			break;
		}
		$n++;
	}
	$str.=',length:'.$n;
	return '{'.substr($str,1).'}';
}
function parseJSONOfArray(&$obj){
	$str='';
	for($i=0;isset($obj[$i]);$i++){
		$value=$obj[$i];
		switch(true){
			case is_string($value):
				$str.=',\''.str_replace("\r\n",'',str_replace('\'','\\\'',str_replace("'",'\'',$value))).'\'';
			break;
			case is_numeric($value):
				$str.=','.$value;
			break;
			case is_bool($value):
				$str.=','.($value? 'true' : 'false' );
			break;
			case is_array($value):
				if($value[0]){
					$str.=','.parseJSONOfArray($value);
				}else{
					$str.=','.parseJSON($value);
				}
			break;
			case is_object($value):
				$str.=','.parseJSON($value);
			break;
			default:
				$str.=',null';
			break;
		}
	}
	return '['.substr($str,1).']';
}
?>
