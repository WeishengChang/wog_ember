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

require('./forum_support/function.php');
//alertWindowMsg("資料維修中");
require_once('./language/wog_main_traditional_chinese.php');
require_once('./language/wog_etc_traditional_chinese.php');

//session_register('act_time');
require("wog_act_config.php");
//session_check($wog_arry["etc_dely_time"]);
require("./forum_support/global.php");
if ($_COOKIE["wog_cookie_name"] != Null || $_COOKIE["wog_cookie"] != Null)
{
	if ($_COOKIE["wog_cookie_debug"] != md5($_COOKIE["wog_cookie"].$wog_arry["cookie_debug"]))
	{
		cookie_clean();
		showscript("alert('".$lang['wog_act_check_cookie']."');window.open('../','_top')");
	}
}
$a_id="";
//###### switch case begin ######

switch ($_GET["f"])
{
	case "peo":
		include("./class/wog_etc_peo.php");
		$wog_act_class = new wog_etc_peo;
		$wog_act_class->peo_view($_COOKIE["wog_cookie"]);
	break;
	case "peo_count":
		include("./class/wog_etc_peo.php");
		$wog_act_class = new wog_etc_peo;
		$wog_act_class->peo_count($_COOKIE["wog_cookie"]);
	break;
	case "mercenary":
		require_once('./language/wog_fight_traditional_chinese.php');
		include("./class/wog_etc_mercenary.php");
		$wog_act_class = new wog_etc_mercenary;
		$wog_act_class->mercenary_view($_COOKIE["wog_cookie"]);
	break;
	case "well":
		include("./class/wog_etc_well.php");
		$wog_act_class = new wog_etc_well;
		$wog_act_class->well_view();
	break;
	case "peo_cache":
		include("./class/wog_etc_peo.php");
		$wog_act_class = new wog_etc_peo;
		$wog_act_class->peo_cache();
	break;
	case "sale":
		include_once('./language/wog_act_traditional_chinese.php');
		include_once("./class/wog_item_tool.php");
		$wog_item_tool = new wog_item_tool;
		include("./class/wog_act_bid.php");
		$wog_act_class = new wog_act_bid;
		switch ($_GET["act"])
		{
			case "view":
				$wog_act_class->bid_view($_COOKIE["wog_cookie"]);
			break;
			case "view2":
				$wog_act_class->bid2_view($_COOKIE["wog_cookie"]);
			break;
		}

		unset($wog_item_tool);
	break;
	case "img":
		include("./class/wog_etc_img.php");
		$wog_act_class = new wog_etc_img;
		$wog_act_class->img_view();
	break;
	case "recomm":
		include_once("./class/wog_act_chara.php");
		$wog_act_class = new wog_act_chara;
		$wog_act_class->chara_make($_GET["recommid"]);
	break;
	case "password":
		include("./class/wog_act_chara.php");
		$wog_act_class = new wog_act_chara;
		$wog_act_class->get_password($_GET["email"]);
	break;
	case "peo2":
		include("./class/wog_etc_peo.php");
		$wog_act_class = new wog_etc_peo;
		$wog_act_class->peo_view2($_COOKIE["wog_cookie"]);
	break;
/*
	case "king_cache":
		include("./class/wog_etc_king_cache.php");
		$wog_act_class = new wog_etc_king_cache;
		$wog_act_class->king_view_cache();
	break;
	case "shop_cache":
		include("./class/wog_act_shop_cache.php");
		$wog_act_class = new wog_act_shop_cache;
		$wog_act_class->shop_cache();
	break;
*/
	case "confirm":
		include_once('./forum_support/php-captcha.inc.php');
		include("./class/wog_etc_confirm.php");
		$wog_act_class = new wog_etc_confirm;
		$wog_act_class->confirm_view($_COOKIE["wog_cookie"]);
	break;
	case "hero":
		include("./class/wog_etc_hero.php");
		$wog_act_class = new wog_etc_hero;
		$wog_act_class->hero_view();
	break;
	case "king":
		include("./class/wog_etc_king.php");
		$wog_act_class = new wog_etc_king;
		$wog_act_class->king_view();
	break;
	case "code":
		include("./class/wog_act_chara.php");
		$wog_act_class = new wog_act_chara;
		$wog_act_class->check_code();
	break;

	default:
//		die("不正常操作");
	break;
}
//$_SESSION['act_time']=time();
unset($wog_act_class);
if(isset($DB_site))
{
	$DB_site->close();
}
//##### switch case end #####

compress_exit();
?>
