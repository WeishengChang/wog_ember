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
if($_COOKIE["wog_cookie_name"]=="" || !isset($_COOKIE["wog_cookie_name"]))
{
	exit;
}else
{
	$user=$_COOKIE["wog_cookie_name"];
	if($user=="系統")
	{
		$user=$_COOKIE["wog_cookie_name2"];
		$_POST["chat_type"]="5";
	}
}
require_once('./include/chat_config.php');
if($_COOKIE["wog_chat_cookie_debug"]!= md5($user.$chat_arry["cookie_debug"]))
{
	exit;
}
if(isset($_COOKIE["wog_cookie_group"]))
{
	$group_id=$_COOKIE["wog_cookie_group"];
}
if(isset($_COOKIE["wog_cookie_team"]))
{
	$team_id=$_COOKIE["wog_cookie_team"];
}
if(!empty($_COOKIE["wog_cookie_hero"]))
{
	$hero="1";
}
else
{
	$hero="0";
}
$lastspeed=$_POST["lastspeed"];
$chat_body=$_POST["chat_body"];
$chat_type=$_POST["chat_type"];
$item_id=$_POST["item_id"];
if(!isset($user) || !isset($lastspeed) || !isset($chat_body) || !isset($chat_type))
{
	exit;
}
if(empty($user) || $lastspeed=="" || empty($chat_body) || empty($chat_type))
{
	exit;
}
require_once("./class/chat_class.php");
$chat_class = new chat_class;
if($chat_class->chat_block($user)){exit;}
$towho=$_POST["towhos"];
$ipwhere=$chat_class->get_ip();
if($ipwhere=="125.229.9.110" || $ipwhere=="125.229.4.44")
{
	exit;
}
if($user != "ETERNAL" && $user!="寒風之戀" && $user!="za055104" && $user!="鬼畜王蘭斯" && $user!="uu3")
{
	$chat_body = str_replace("<", "&lt;", $chat_body);
	$chat_body = str_replace(">", "&gt;", $chat_body);
	$chat_body = str_replace("'", "&acute;", $chat_body);
	$chat_body = str_replace(",", "&cedil;", $chat_body);
	$chat_body = str_replace("|", "&brvbar;", $chat_body);
	$chat_body = str_replace("\\", "＼", $chat_body);
}
if(!empty($item_id))
{
	$chat_body = str_replace("[", "[<a href=javascript:parent.parent.act_click(\"shop\",\"check_item\",\"$item_id\") target=mission>", $chat_body);
	$chat_body = str_replace("]", "</a>]", $chat_body);
}
$user = str_replace("<", "&lt;", $user);
$user = str_replace(">", "&gt;", $user);
$user = str_replace("'", "&acute;", $user);
$user = str_replace(",", "&cedil;", $user);
$user = str_replace("|", "&brvbar;", $user);
$user = str_replace("\\", "＼", $user);

$speed=$chat_class->get_speed();
$speed++;
if($speed >= 2147483000)
{
	$speed=0;
}
switch ($chat_type)
{
	case "1":
		$chat_body="parent.goldset('".$user."','0','".$chat_body."','$hero','');";
	break;
	case "2":
		if(empty($group_id))
		{
			unset($chat_class);
			exit;
		}
		$chat_body="parent.goldset('".$user."','1','".$chat_body."','$hero','');";
	break;
	case "3":
		if(empty($team_id))
		{
			unset($chat_class);
			exit;
		}
		$chat_body="parent.goldset('".$user."','2','".$chat_body."','$hero','');";
	break;
	case "4":
		if(empty($towho))
		{
			unset($chat_class);
			exit;
		}
		$chat_body="parent.goldset('".$towho."','3','".$chat_body."','$hero','".$user."');";
	break;
	case "5":
		$chat_body="parent.goldset('".$user."','0','<font color=red>".$chat_body."</font>','0','');";
		$group_id=0;
		$team_id=0;
		$chat_type=1;
	break;
}
$chat_body=$speed."|".$chat_body."|".$group_id."|".$team_id."|".$towho."|".$user."|".$chat_type."|".$ipwhere;
$chat_class->chat_message($chat_body);
$chat_class->set_speed($speed);
$_GET["set_lock1"]=$_POST["set_lock1"];
$_GET["set_lock2"]=$_POST["set_lock2"];
$_GET["set_lock3"]=$_POST["set_lock3"];
$_GET["set_lock4"]=$_POST["set_lock4"];
include_once("wog_chat_list.php");
if(isset($chat_class))
{
	unset($chat_class);
}
?>