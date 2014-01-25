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

/*
 * $trun說明:
 * 0:技能初發動
 * 1:持續性發動(有害)
 * 2:持續性發動(增益)
 */
function end_skill_79(&$a,$lv,$uid,$trun=0,$dmg) //神頌曲
{
	global $skill_value,$DB_site,$lang;
	if(empty($skill_value[$uid][79][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.1;
				break;
			case 2:
				$value=0.2;
				break;
			case 3:
				$value=0.3;
				break;
			case 4:
				$value=0.4;
				break;
			case 5:
				$value=0.5;
				break;
			case 6:
				$value=0.6;
				break;
			case 7:
				$value=0.7;
				break;
			case 8:
				$value=0.8;
				break;
			case 9:
				$value=0.9;
				break;
			case 10:
				$value=1;
				break;
		} // switch
		$skill_value[$uid][79][0]=$value;
	}
	if(empty($a["t_id"]))
	{
		return;
	}
	$hp=round($a[au]*$skill_value[$uid][79][0]);
	$sql="select p_id,p_name,hp,hpmax from wog_player where t_id=".$a["t_id"]."  and p_id <> ".$a[p_id]." ORDER BY RAND() LIMIT 1";
	$p=$DB_site->query_first($sql);
	if($p)
	{
		$p[hp]+=$hp;
		if($p[hp]>$p[hpmax])
		{
			$p[hp]=$p[hpmax];
		}
		$sql="update wog_player set hp=".$p[hp]." where p_id=".$p[p_id];
		$DB_site->query($sql);
		$msg=sprintf($lang['wog_fight_msg1'],$p[p_name],$hp);
		echo ",\"parent.fight_event4('".$msg."')\"";
	}
}
function end_skill_80(&$a,$lv,$uid,$trun=0,$dmg) //地獄禮讚
{
	global $skill_value,$DB_site,$lang;
	if(empty($skill_value[$uid][80][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.08;
				break;
			case 2:
				$value=0.16;
				break;
			case 3:
				$value=0.24;
				break;
			case 4:
				$value=0.32;
				break;
			case 5:
				$value=0.4;
				break;
			case 6:
				$value=0.48;
				break;
			case 7:
				$value=0.56;
				break;
			case 8:
				$value=0.64;
				break;
			case 9:
				$value=0.72;
				break;
			case 10:
				$value=0.8;
				break;
		} // switch
		$skill_value[$uid][80][0]=$value;
	}
	if(empty($a["t_id"]))
	{
		return;
	}
	$hp=round($a[smart]*$skill_value[$uid][80][0]);
	$sql="select p_id,p_name,hp,hpmax from wog_player where t_id=".$a["t_id"]." and hp > 0  and p_id <> ".$a[p_id]." ORDER BY RAND() LIMIT 1";
	$p=$DB_site->query_first($sql);
	if($p)
	{
		$p[hp]-=$hp;
		if($p[hp]<0)
		{
			$hp+=$p[hp];
			$p[hp]=1;
		}
		$sql="update wog_player set hp=".$p[hp]." where p_id=".$p[p_id];
		$DB_site->query($sql);
		$msg=sprintf($lang['wog_fight_msg2'],$p[p_name],$hp);
		echo ",\"parent.fight_event4('".$msg."')\"";
		
//		$sql="select hp,hpmax from wog_player where p_id=".$a["p_id"];
//		$p=$DB_site->query_first($sql);
		$p[hp]+=$hp;
		if($p[hp]>$p[hpmax])
		{
			$p[hp]=$p[hpmax];
		}
		$sql="update wog_player set hp=".$p[hp]." where p_id=".$a[p_id];
		$DB_site->query($sql);
	}
}
function end_skill_81(&$a,$lv,$uid,$trun=0,$dmg) //詐騙
{
	global $skill_value,$DB_site,$lang;
	if(empty($skill_value[$uid][81][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.05;
				break;
			case 2:
				$value=0.136;
				break;
			case 3:
				$value=0.204;
				break;
			case 4:
				$value=0.272;
				break;
			case 5:
				$value=0.34;
				break;
			case 6:
				$value=0.408;
				break;
			case 7:
				$value=0.476;
				break;
			case 8:
				$value=0.544;
				break;
			case 9:
				$value=0.612;
				break;
			case 10:
				$value=0.68;
				break;
		} // switch
		$skill_value[$uid][81][0]=$value;
	}
	if(empty($a["t_id"]))
	{
		return;
	}
	$money=round($a[p_lv]*$skill_value[$uid][81][0]);
	$sql="select p_id,p_name,p_money from wog_player where t_id=".$a["t_id"]." and p_id <> ".$a[p_id]." ORDER BY RAND() LIMIT 1";
	$p=$DB_site->query_first($sql);
	if($p)
	{
		$p[p_money]-=$money;
		if($p[p_money]<0)
		{
			$p_money+=$p[p_money];
			$p[p_money]=0;
		}
		$sql="update wog_player set p_money=".$p[p_money]." where p_id=".$p[p_id];
		$DB_site->query($sql);
		$msg=sprintf($lang['wog_fight_msg3'],$p[p_name],$money);
		echo ",\"parent.fight_event4('".$msg."')\"";
		
		$sql="select p_money from wog_player where p_id=".$a["p_id"];
		$p=$DB_site->query_first($sql);
		$p[p_money]+=$money;
		$sql="update wog_player set p_money=".$p[p_money]." where p_id=".$a[p_id];
		$DB_site->query($sql);
	}
}
function end_skill_84(&$a,$lv,$uid,$trun=0,$dmg) //精密陷阱
{
	global $skill_value,$DB_site,$lang,$get_pet;
	if(empty($skill_value[$uid][84][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=7;
				break;
			case 2:
				$value=14;
				break;
			case 3:
				$value=21;
				break;
			case 4:
				$value=28;
				break;
			case 5:
				$value=35;
				break;
			case 6:
				$value=42;
				break;
			case 7:
				$value=49;
				break;
			case 8:
				$value=56;
				break;
		} // switch
		$skill_value[$uid][84][0]=$value;
	}
	$get_pet=$skill_value[$uid][84][0];
}
?>