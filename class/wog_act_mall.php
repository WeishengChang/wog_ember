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

class wog_act_mall{
	function mall_view($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		alertWindowMsg($lang['wog_main_error4']);
	}
	function mall_buy($user_id)
	{
		global $DB_site,$_POST,$lang,$a_id,$wog_item_tool,$wog_arry;
		alertWindowMsg($lang['wog_main_error4']);
	}
	function mall_restup($user_id)
	{
		global $DB_site,$_POST,$lang;
		alertWindowMsg($lang['wog_main_error4']);
	}
	function chgid($user_id)
	{
		global $DB_site,$lang,$_POST;
		alertWindowMsg($lang['wog_main_error4']);
	}
}
?>