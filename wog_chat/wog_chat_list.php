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
}
//if($user=="ht93080"){exit;}
require_once('include/chat_config.php');
if ($_COOKIE["wog_chat_cookie_debug"] != md5($user.$chat_arry["cookie_debug"]))
{
	exit;
}
if(!isset($speed))
{
	require_once("./class/chat_class.php");
	$chat_class = new chat_class;
	$speed=$chat_class->get_speed();
}
if($chat_class->chat_block($user)){exit;}
if(isset($_GET["lastspeed"]) && !isset($lastspeed))
{
	$lastspeed=(int)$_GET["lastspeed"];
}
if(isset($_COOKIE["wog_cookie_group"]) && !isset($group_id))
{
	$group_id=$_COOKIE["wog_cookie_group"];
}
if(isset($_COOKIE["wog_cookie_team"]) && !isset($team_id))
{
	$team_id=$_COOKIE["wog_cookie_team"];
}

$set_lock1=(int)$_GET["set_lock1"];
$set_lock2=(int)$_GET["set_lock2"];
$set_lock3=(int)$_GET["set_lock3"];
$set_lock4=(int)$_GET["set_lock4"];
?>
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript">
<?php

echo "parent.lastspeed=".$speed.";\n";
$message_val=$speed - $lastspeed;
if($message_val<0)
{
	$message_val=$speed+(2147483000-$lastspeed);
}
message($message_val,$group_id,$team_id,$user);
unset($chat_class);
function message($message_val,$group_id,$team_id,$user)
{
	global $chat_class,$set_lock1,$set_lock2,$set_lock3,$set_lock4;
	if($chat_class->show==NULL)
	{
		$chat_class->get_chat_message();
	}
	$message_val=$chat_class->maxnum-$message_val;
	if($message_val < 0)
	{
		$message_val=0;
	}
	for($i=$message_val;$i<$chat_class->maxnum;$i++)
	{
		$temp_message=explode("|",$chat_class->show[$i]);
		$temp_message[6]=str_replace("\r\n","",$temp_message[6]);
		switch ($temp_message[6])
		{
			case "1":
				if($set_lock1==0)
				{
					echo $temp_message[1]."";
				}
			break;
			case "2":
				if($temp_message[2]==$group_id && $set_lock3==0)
				{
					echo $temp_message[1]."";
				}
			break;
			case "3":
				if($temp_message[3]==$team_id && $set_lock4==0)
				{
					echo $temp_message[1]."";
				}
			break;
			case "4":
				if(($temp_message[4]==$user || $temp_message[5]==$user) && $set_lock2==0)
				{
					echo $temp_message[1]."";
				}
			break;
		}
	}
}
?>
</script>
</head>
</HTML>
