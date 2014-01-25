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
if(getenv("HTTP_REFERER") !="http://www.2233.idv.tw/wog/wog_foot.htm")
{
//	alertWindowMsg($lang['wog_act_gowhere']);
	exit;
}
*/
//setcookie("wog-name",iconv("BIG5","UTF-8",$_POST["temp_id"]));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="./chat.js"></script>
<script language="JavaScript" src="./wog_msg_box.js"></script>
	<title>Untitled</title>
</head>
	<frameset rows="*,35,0" border="0" resize="no" oncontextmenu="return false">
		<frame src="" name="chat_view"  frameborder="0" scrolling="Auto" noresize marginwidth="0" marginheight="0">
		<frame src="wog_chat_in.php" name="chat_in" frameborder="0" scrolling="no" noresize marginwidth="0" marginheight="0" >"
		<frame src="" name="chat_list"  frameborder="0" scrolling="Auto" noresize marginwidth="0" marginheight="0">
	</frameset>
</html>