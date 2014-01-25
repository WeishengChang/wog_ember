<?php 
header('content-type:text/html;charset=utf-8');
?>
<!--
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
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<META NAME="keywords" CONTENT="Online FF Battle - WOG">
<META NAME="description" CONTENT="Online FF Battle - WOG">
<META NAME="author" CONTENT="ETERNAL">
<META NAME="copyright" CONTENT="Copyright (C) ETERNAL">
<title>網頁遊戲WebGame,FFA線上遊戲-Online FF Battle - WOG V4 Copyright (C) ETERNAL</title>
<meta content="text/html; charset=utf-8" http-equiv=content-type>
<script language="JavaScript" src="./js/jquery-2.0.3.min.js"></script>
<script language="JavaScript" src="./js/libs/handlebars-1.1.2.js"></script>
<script language="JavaScript" src="./js/libs/ember-1.2.0.js"></script>
<script language="javascript" src="./js/dom-drag.js"></script>
<script language="JavaScript" src="./js/wog_data.js"></script>
<script language="JavaScript" src="./js/wog_group.js"></script>
<script language="JavaScript" src="./js/wog_pet.js"></script>
<script language="JavaScript" src="./js/wog_shop.js"></script>
<script language="JavaScript" src="./js/wog_job_skill.js"></script>
<script language="JavaScript" src="./js/wog_item.js"></script>
<script language="JavaScript" src="./js/wog_team.js"></script>
<script language="JavaScript" src="./js/wog_mission.js"></script>
<script language="JavaScript" src="./js/wog_fight.js"></script>
<script language="JavaScript" src="./js/wog_chara.js"></script>
<script language="JavaScript" src="./js/wog_tool.js"></script>
<script language="JavaScript" src="./js/wog_etc_view.js"></script>
<script language="JavaScript" src="./js/wog_function.js"></script>
<script language="JavaScript" src="./js/wog_message_view.js"></script>
</head>
<!-- frames -->
<frameset rows="0,39,*,0,0,0,0" border="0" resize="no" id="set_mainframe">
    <frame src="" name="news" id="news" frameborder="0" scrolling="No" noresize marginwidth="0" marginheight="0">
    <frame src="wog_top.htm" name="top_view" id="top_view" frameborder="0" scrolling="No" noresize marginwidth="0" marginheight="0">
    <frame name="wog_view" id="wog_view" frameborder="0" scrolling="Auto" noresize marginwidth="10" marginheight="10">
<?php
	if(!empty($_GET["recomm"]))
	{
		echo "<frame name=\"mission\" src=\"wog_etc.php?f=recomm&recommid=".$_GET["recomm"]."\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" frameborder=\"0\" noresize>";
	}else
	{
		echo "<frame name=\"mission\" src=\"wog_etc.php?f=well\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" frameborder=\"0\" noresize>";
	}
?>
    <frame name="peolist" src="wog_etc.php?f=peo2" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" noresize>
    <frame src="wog_foot.htm" name="foot" id="foot" frameborder="0" scrolling="Auto" noresize marginwidth="0" marginheight="0">
    <frame name="msn" src="" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" noresize>
</frameset>
<body>
<script type="text/x-handlebars" data-template-name="application">
</script>
</body>
</html>