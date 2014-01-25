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
require_once('./forum_support/function.php');
//alertWindowMsg("資料維修中");
require_once('./language/wog_main_traditional_chinese.php');
require_once('./language/wog_group_traditional_chinese.php');
//error_reporting(7);
if(!isset($_POST["act"]))
{
	$_POST["act"]="";
}
if(!isset($_POST["f"]))
{
	$_POST["f"]="";
}

if( $_POST["f"] != "chara"  && empty($_COOKIE["wog_cookie"]))
{
	alertWindowMsg($lang['wog_act_nologin']);
}

require_once("wog_act_config.php");

require_once("./forum_support/global.php");
//session_start();
if ($_COOKIE["wog_cookie_name"] != Null || $_COOKIE["wog_cookie"] != Null)
{
	if ($_COOKIE["wog_cookie_debug"] != md5($_COOKIE["wog_cookie"].$_COOKIE["wog_bbs_id"].$wog_arry["cookie_debug"]))
	{
		cookie_clean();
		showscript("alert('".$lang['wog_act_check_cookie']."');window.open('../','_top')");
	}
}
//########################## switch case begin #######################
$a_id="";
//$temp_ss="";
include_once("./class/wog_act_group.php");
$wog_act_class = new wog_act_group;
switch ($_POST["f"])
{
	case "member":
		switch ($_POST["act"])
		{
			case "p_list": //會員列表
				$wog_act_class->group_p_list($_COOKIE["wog_cookie"]);
			break;
			case "mod_member": //修改會員資格
				$wog_act_class->group_mod_member($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "area_main":
		switch ($_POST["act"])
		{
			case "area_main": //據點狀態
				include_once("./class/wog_fight_group.php");
				$wog_fight_group = new wog_fight_group;
				$wog_act_class->group_area_main($_COOKIE["wog_cookie"]);
				unset($wog_fight_group);
			break;
			case "durable":
				$wog_act_class->group_durable($_COOKIE["wog_cookie"]);
			break;
			case "ch_main": //變更公會圖誌
				$wog_act_class->group_ch_main($_COOKIE["wog_cookie"]);
			break;
			case "item": //變更公會圖誌
				$wog_act_class->group_item($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "book":
		switch ($_POST["act"])
		{
			case "book": //佈告欄
				$wog_act_class->group_book_view($_COOKIE["wog_cookie"]);
			break;
			case "fight_book": //戰報細節過程
				$wog_act_class->group_fight_book($_COOKIE["wog_cookie"]);
			break;
			case "save_book":
				$wog_act_class->group_book_save($_COOKIE["wog_cookie"]);
			break;
			case "save_memberbook":
				$wog_act_class->group_memberbook_save($_COOKIE["wog_cookie"]);
			break;
			case "check_ex": //查看對方資源
				$wog_act_class->group_check_ex($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "get_member":
		switch ($_POST["act"])
		{
			case "get_member": //認領會員
				$wog_act_class->group_get_member($_COOKIE["wog_cookie"]);
			break;
			case "get_save_member": //通過認證
				$wog_act_class->group_get_save_member($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "point":
		switch ($_POST["act"])
		{
			case "set_point": //榮譽設定
				$wog_act_class->group_set_point($_COOKIE["wog_cookie"]);
			break;
			case "dep_point":
				$wog_act_class->group_dep_point($_COOKIE["wog_cookie"]);
			break;
			case "mod_point":
				$wog_act_class->group_mod_point($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "wp":
		include_once("./class/wog_fight_group.php");
		$wog_fight_group = new wog_fight_group;
		switch ($_POST["act"])
		{
			case "wp":
				$wog_act_class->group_wp($_COOKIE["wog_cookie"]);
			break;
			case "fight":
				$wog_fight_group->group_fight($_COOKIE["wog_cookie"]);
			break;
		}
		unset($wog_fight_group);
	break;
	case "ex":
		switch ($_POST["act"])
		{
			case "ex": //造兵所
				include_once("./class/wog_fight_group.php");
				$wog_fight_group = new wog_fight_group;
				$wog_act_class->group_ex($_COOKIE["wog_cookie"]);
				unset($wog_fight_group);
			break;
			case "wpup": //造兵
				$wog_act_class->group_wpup($_COOKIE["wog_cookie"]);
			break;
			case "exup":
				$wog_act_class->group_exup($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "build":
		switch ($_POST["act"])
		{
			case "build_list":
				$wog_act_class->group_build_list($_COOKIE["wog_cookie"]);
			break;
			case "build_show":
				$wog_act_class->group_build_show($_COOKIE["wog_cookie"]);
			break;
			case "build_make":
				$wog_act_class->group_build_make($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "news":
		switch ($_POST["act"])
		{
			case "news":
				$wog_act_class->group_news($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "market":
		switch ($_POST["act"])
		{
			case "market":
				$wog_act_class->group_market_list($_COOKIE["wog_cookie"]);
			break;
			case "post":
				$wog_act_class->group_market_post($_COOKIE["wog_cookie"]);
			break;
			case "get1":
				$wog_act_class->group_market_get1($_COOKIE["wog_cookie"]);
			break;
			case "get2":
				$wog_act_class->group_market_get2($_COOKIE["wog_cookie"]);
			break;
			case "jump":
				$wog_act_class->group_market_jump($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "depot":
		switch ($_POST["act"])
		{
			case "depot_list":
				$wog_act_class->group_depot_list($_COOKIE["wog_cookie"]);
			break;
			case "depot_move":
				include_once("./class/wog_item_tool.php");
				$wog_item_tool = new wog_item_tool;
				$wog_act_class->group_depot_move($_COOKIE["wog_cookie"]);
				unset($wog_item_tool);
			break;
		}
	break;
	case "main":
		switch ($_POST["act"])
		{
			case "view":
				include_once("./class/wog_fight_group.php");
				$wog_fight_group = new wog_fight_group;
				$wog_act_class->group_view($_COOKIE["wog_cookie"]);
				unset($wog_fight_group);
			break;
			case "map":
				$wog_act_class->group_map();
			break;
			case "list":
				$wog_act_class->group_list($_COOKIE["wog_cookie"]);
			break;
			case "area":
				$wog_act_class->group_area($_COOKIE["wog_cookie"]);
			break;
			case "add": //申請加入會員
				$wog_act_class->group_add($_COOKIE["wog_cookie"]);
			break;
			case "creat":
				$wog_act_class->group_creat($_COOKIE["wog_cookie"]);
			break;
			case "del":
				$wog_act_class->group_del($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "job":
		switch ($_POST["act"])
		{
			case "job_wp":
				$wog_act_class->group_job_wp($_COOKIE["wog_cookie"]);
			break;
			case "job_break":
				$wog_act_class->group_job_break($_COOKIE["wog_cookie"]);
			break;
			case "job_list":
				$wog_act_class->group_job_list($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "mission":
		switch ($_POST["act"])
		{
			case "list":
				$wog_act_class->group_mission_list($_COOKIE["wog_cookie"]);
			break;
			case "view":
				$wog_act_class->group_mission_view($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "vip":
		switch ($_POST["act"])
		{
			case "item":
				include_once("./class/wog_fight_group.php");
				$wog_fight_group = new wog_fight_group;
				$wog_act_class->group_vip_item($_COOKIE["wog_cookie"]);
				unset($wog_fight_group);
			break;
		}
	break;
	case "permissions":
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->group_permissions_view($_COOKIE["wog_cookie"]);
			break;
			case "save":
				$wog_act_class->group_permissions_save($_COOKIE["wog_cookie"]);
			break;
			case "add":
				$wog_act_class->group_permissions_add($_COOKIE["wog_cookie"]);
			break;
			case "del":
				$wog_act_class->group_permissions_del($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "g_admin":
		$wog_act_class->group_admin($_COOKIE["wog_cookie"]);
	break;
	default:
//		die("不正常操作");
	break;
}
unset($wog_act_class);
if(isset($DB_site))
{
	$DB_site->close();
}
//##################### switch case end #################
compress_exit();
?>