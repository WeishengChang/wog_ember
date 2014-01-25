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
if(getenv("HTTP_REFERER") !="http://www.2233.idv.tw/wog/wog_chat.php")
{
	exit;
}
*/
if($_COOKIE["wog_cookie_name"]=="" || !isset($_COOKIE["wog_cookie_name"]))
{
	exit;
}else
{
	$user=$_COOKIE["wog_cookie_name"];
}

require_once("./class/chat_class.php");
$chat_class = new chat_class;
//setcookie("wog-name",$_GET["user_id"]);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Untitled</title>
<script language="JavaScript">
<?php
echo "parent.lastspeed=".$chat_class->get_speed().";\n";
unset($chat_class);
?>
function stype() {return parent.lastspeed;}
function chatdate()
{
	var towho=parent.parent.parent.get_towho();
	var f_chat=document.f1;
	if(f_chat.chat_type.value == "4" && towho=="")
	{
		return false;
	}
	if(parent.set_lock1==1 && f_chat.chat_type.value == "1")
	{
		return false;
	}
	if(parent.set_lock2==1 && f_chat.chat_type.value == "4")
	{
		return false;
	}
	if(parent.set_lock3==1 && f_chat.chat_type.value == "2")
	{
		return false;
	}
	if(parent.set_lock4==1 && f_chat.chat_type.value == "3")
	{
		return false;
	}
	if(f_chat.chat_temp.value !="")
	{
		f_chat.lastspeed.value=stype();
		f_chat.towhos.value=towho;
		f_chat.chat_body.value=document.f1.chat_temp.value;
		f_chat.item_id.value=document.f1.item_id_temp.value;
		f_chat.chat_temp.value="";
		f_chat.item_id_temp.value="";
		return true;
	}
	return false;
}
</script>
<STYLE type="text/css">
<!--
body,tr,td,th,input,select { font-size: 13px; font-family: verdana }
-->
</STYLE>
</head>

<body bgcolor="#000000" text="#EFEFEF" >
<form action="wog_chat_hidden.php" method="post" name="f1" target="chat_list" onSubmit="return(chatdate())">
<input type="submit" name="send" value="送出" >
<input type="text" size="30" maxlength="250" name="chat_temp">
<select name="chat_type" onchange="document.f1.chat_temp.focus()">
<option value="1" SELECTED>一般頻</option>
<option value="4" >私頻</option>
<option value="2" >公會頻</option>
<option value="3" >隊頻</option>
</select>
<input type="button" value="頻道設定" onclick="parent.chat_set();">
<input type="hidden" name="lastspeed" value="">
<input type="hidden" name="towhos" value="">
<input type="hidden" name="chat_body" value="">
<input type="hidden" name="set_lock1" value="">
<input type="hidden" name="set_lock2" value="">
<input type="hidden" name="set_lock3" value="">
<input type="hidden" name="set_lock4" value="">
<input type="hidden" name="item_id_temp" value="">
<input type="hidden" name="item_id" value="">
</form>
</body>
<script language="JavaScript">
	parent.message_cls(parent.chat_view);
	parent.goldset('系統','0','<font color=red>'+parent.well_box[Math.floor(Math.random()*parent.well_box.length)]+'</font>','','');
<?php
	echo "parent.chat_list.document.location=\"wog_chat_list.php?lastspeed=\"+parent.lastspeed;";
?>
</script>
</html>
