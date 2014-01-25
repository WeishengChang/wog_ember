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
require_once('./language/wog_act_traditional_chinese.php');

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
	alertWindowMsg($lang['wog_act_nologin'].$_POST["f"]);
}

require_once("wog_act_config.php");

//session_check($wog_arry["act_dely_time"]);
require_once("./forum_support/global.php");
session_start();
//session_register('act_time');
if ($_COOKIE["wog_cookie_name"] != Null || $_COOKIE["wog_cookie"] != Null)
{
	if ($_COOKIE["wog_cookie_debug"] != md5($_COOKIE["wog_cookie"].$wog_arry["cookie_debug"]))
	{
		//echo $_COOKIE["wog_cookie_debug"]."<br>";
		//echo md5($_COOKIE["wog_cookie"].$_COOKIE["wog_bbs_id"].$wog_arry["cookie_debug"])."<br>";
		//echo $_COOKIE["wog_cookie_name"]."--".$_COOKIE["wog_cookie"]."--".$_COOKIE["wog_bbs_id"]."--".$wog_arry["cookie_debug"]."<br>";
		//exit;
		cookie_clean();
		showscript("alert('".$lang['wog_act_check_cookie']."');window.open('../','_top')");
	}
}
//########################## switch case begin #######################
$a_id="";
$temp_ss="";
switch ($_POST["f"])
{
	case "chara":
		if($_POST["act"]=="login" || $_POST["act"]=="make" || $_POST["act"]=="save")
		{
			$p_ip=get_ip();
			if($p_ip=="125.229.9.110" || $p_ip=="125.229.4.44")
			{
				break;
			}
			eval($forum_check);
/*
			if ($bbs_id<=0) {
				showscript("alert('".$lang['wog_act_nofroum_member']."');window.open('../','_top');");
			}
*/
		}
		include_once("./class/wog_act_chara.php");
		$wog_act_class = new wog_act_chara;
		switch ($_POST["act"])
		{
			case "login":
				$wog_act_class->login($p_ip,0);
			break;
			case "try_login":
				$wog_act_class->login($p_ip,1,$_COOKIE["wog_cookie"]);
			break;
			case "status_view":
				$wog_act_class->show_chara($_COOKIE["wog_cookie"],3);
			break;
			case "well":
				$wog_act_class->start_tips($_COOKIE["wog_cookie"]);
			break;
			case "make":
				$wog_act_class->chara_make();
			break;
			case "save":
				$wog_act_class->chara_chk($bbs_id);
			break;
			case "sns_save":
				header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
				$wog_act_class->sns_save();
			break;
			case "sns_link":
				header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
				$wog_act_class->sns_link();
			break;
/*
			case "revive":
				$wog_act_class->revive($_COOKIE["wog_cookie"]);
			break;
*/
			case "adm":
				$wog_act_class->adm($_COOKIE["wog_cookie"]);
			break;
			case "logout":
				if($_COOKIE["wog_cookie"]=="")
				{
					alertWindowMsg($lang['wog_act_nologin']);
				}
				$wog_act_class->logout($_COOKIE["wog_cookie"]);
			break;
			case "view2":
				$wog_act_class->system_view2($_COOKIE["wog_cookie"]);
			break;
			case "cp":
				$wog_act_class->cp_view();
			break;
			case "chpw":
				$wog_act_class->chpw($_COOKIE["wog_cookie"]);
			break;
			case "kill":
				$wog_act_class->kill($_COOKIE["wog_cookie"]);
			break;
			case "chgid":
				$wog_act_class->chgid($_COOKIE["wog_cookie"]);
			break;
		}
	break;
##########----------一般商店_start----------##########
	case "shop":
		include_once("./class/wog_item_tool.php");
		$wog_item_tool = new wog_item_tool;
		include_once("./class/wog_act_shop.php");
		$wog_act_class = new wog_act_shop;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->shop($_COOKIE["wog_cookie"]);
			break;
			case "buy":
				$wog_act_class->buy($_COOKIE["wog_cookie"]);
			break;
			case "check_item":
				$wog_act_class->check_item($_COOKIE["wog_cookie"]);
			break;
		}
		unset($wog_item_tool);
	break;
##########----------一般商店_end----------##########
##########----------特殊商店_start----------##########
	case "store":
		include_once("./class/wog_act_store.php");
		$wog_act_class = new wog_act_store;
		switch ($_POST["act"])
		{
			case "hotel":
				$wog_act_class->hotel($_COOKIE["wog_cookie"]);
			break;
			case "img":
				$wog_act_class->p_img($_COOKIE["wog_cookie"]);
			break;
			case "img2":
				$wog_act_class->p_img2($_COOKIE["wog_cookie"]);
			break;
			case "sex":
				alertWindowMsg("此功能關閉");
				$wog_act_class->p_sex($_COOKIE["wog_cookie"]);
			break;
		}
	break;
##########----------特殊商店_end----------##########
	case "bank":
		if($_COOKIE["wog_cookie"]=="")
		{
			alertWindowMsg($lang['wog_act_nologin']);
		}
		include_once("./class/wog_act_store.php");
		$wog_act_class = new wog_act_store;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->bank($_COOKIE["wog_cookie"]);
			break;
			case "save":
				$wog_act_class->bank_save($_COOKIE["wog_cookie"],$_COOKIE["wog_cookie"],$_POST["money"]);
			break;
			case "get":
				$wog_act_class->bank_get($_COOKIE["wog_cookie"],$_POST["money"]);
			break;
			case "pay":
				$pay_id=$DB_site->query_first("SELECT p_id FROM wog_player WHERE p_name='".addslashes(htmlspecialchars($_POST["pay_id"]))."'");
				if($pay_id)
				{
					$wog_act_class->bank_save($_COOKIE["wog_cookie"],$pay_id[p_id],$_POST["temp_id"]);
				}else
				{
				    alertWindowMsg($lang['wog_act_noid']);

				}
				unset($pay_id);
			break;
		}
	break;
	case "arm":
		include_once("./class/wog_item_tool.php");
		$wog_item_tool = new wog_item_tool;
		include_once("./class/wog_act_arm.php");
		$wog_act_class = new wog_act_arm;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->arm_view($_COOKIE["wog_cookie"]);
			break;
			case "setup":
				$wog_act_class->arm_setup($_COOKIE["wog_cookie"]);
			break;
			case "move":
				$wog_act_class->arm_move($_COOKIE["wog_cookie"]);
			break;
			case "sale":
				$wog_act_class->arm_sale($_COOKIE["wog_cookie"]);
			break;
			case "unsetup":
				$wog_act_class->arm_unsetup($_COOKIE["wog_cookie"]);
			break;
			case "depot_list":
				$wog_act_class->arm_depot_list($_COOKIE["wog_cookie"]);
			break;
			case "depot_add":
				$wog_act_class->arm_depot_add($_COOKIE["wog_cookie"]);
			break;
			case "depot_move":
				$wog_act_class->arm_depot_move($_COOKIE["wog_cookie"]);
			break;
		}
		unset($wog_item_tool);
	break;
	case "hole":
		include_once("./class/wog_item_tool.php");
		$wog_item_tool = new wog_item_tool;
		include_once("./class/wog_act_hole.php");
		$wog_act_class = new wog_act_hole;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->hole_view($_COOKIE["wog_cookie"]);
			break;
			case "setup":
				$wog_act_class->hole_setup($_COOKIE["wog_cookie"]);
			break;
			case "remove":
				$wog_act_class->hole_remove($_COOKIE["wog_cookie"]);
			break;
			case "mh":
				$wog_act_class->hole_mh($_COOKIE["wog_cookie"]);
			break;
		}
		unset($wog_item_tool);
	break;
##########----------syn_system_start----------##########
	case "syn":
		include_once("./class/wog_item_tool.php");
		$wog_item_tool = new wog_item_tool;
		include_once("./class/wog_act_syn.php");
		$wog_act_class = new wog_act_syn;
		switch ($_POST["act"])
		{
			case "list":
				$wog_act_class->syn_list($_COOKIE["wog_cookie"]);
			break;
			case "detail":
				$wog_act_class->syn_detail($_COOKIE["wog_cookie"]);
			break;
			case "special":
				$wog_act_class->syn_special($_COOKIE["wog_cookie"]);
			break;
		}
		unset($wog_item_tool);
	break;
##########------------syn_system_end-----------##########
##########------------mercenary_begin-----------##########
	case "mercenary":
		include_once("./class/wog_act_mercenary.php");
		$wog_act_class = new wog_act_mercenary;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->mercenary_view($_COOKIE["wog_cookie"]);
			break;
			case "buy":
				$wog_act_class->mercenary_buy($_COOKIE["wog_cookie"]);
			break;
			case "set":
				$wog_act_class->mercenary_set($_COOKIE["wog_cookie"]);
			break;
			case "check_item":
				$wog_act_class->check_item($_COOKIE["wog_cookie"]);
			break;
		}
	break;
##########------------mercenary_end-----------##########
##########------------plus_begin-----------##########
	case "plus":
		include_once("./class/wog_act_plus.php");
		$wog_act_class = new wog_act_plus;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->plus_view($_COOKIE["wog_cookie"]);
			break;
			case "make":
				include_once("./class/wog_item_tool.php");
				$wog_item_tool = new wog_item_tool;
				$wog_act_class->plus_make($_COOKIE["wog_cookie"]);
				unset($wog_item_tool);
			break;
			case "arm_view":
				$wog_act_class->plus_arm_view($_COOKIE["wog_cookie"]);
			break;
			case "make_arm":
				include_once("./class/wog_item_tool.php");
				$wog_item_tool = new wog_item_tool;
				$wog_act_class->plus_make_arm($_COOKIE["wog_cookie"]);
				unset($wog_item_tool);
			break;
		}
	break;
##########------------plus_end-----------##########
##########------------exchange_begin-----------##########
	case "exchange":
		include_once("./class/wog_act_exchange.php");
		$wog_act_class = new wog_act_exchange;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->exchange_view($_COOKIE["wog_cookie"]);
			break;
			case "list":
				$wog_act_class->exchange_list($_COOKIE["wog_cookie"]);
			break;
			case "buy":
				$wog_act_class->exchange_buy($_COOKIE["wog_cookie"]);
			break;
			case "sale":
				$wog_act_class->exchange_sale($_COOKIE["wog_cookie"]);
			break;
		}
	break;
##########------------exchange_end-----------##########
	case "skill":
		include_once("./class/wog_act_skill.php");
		$wog_act_class = new wog_act_skill;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->skill_view($_COOKIE["wog_cookie"]);
			break;
			case "skill_list":
				$wog_act_class->skill_skill_list($_COOKIE["wog_cookie"],$_POST["temp_id"]);
			break;
			case "get":
				$wog_act_class->skill_get($_COOKIE["wog_cookie"],$_POST["temp_id2"]);
			break;
			case "setup":
				$wog_act_class->skill_setup($_COOKIE["wog_cookie"]);
			break;
			case "change_use":
				$wog_act_class->skill_change_use($_COOKIE["wog_cookie"]);
			break;
			case "rview":
				$wog_act_class->skill_rview($_COOKIE["wog_cookie"]);
			break;
			case "unset":
				$wog_act_class->skill_unset($_COOKIE["wog_cookie"],$_POST["temp_id2"]);
			break;
		}
	break;
	case "job":
		include_once("./class/wog_act_job.php");
		$wog_act_class = new wog_act_job;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->job_view($_COOKIE["wog_cookie"]);
			break;
			case "setup":
				include_once("./class/wog_item_tool.php");
				$wog_item_tool = new wog_item_tool;
				$wog_act_class->job_setup($_COOKIE["wog_cookie"],$_POST["temp_id2"]);
				unset($wog_item_tool);
			break;
/*
			case "get_skill":
				$wog_act_class->s_get($_COOKIE["wog_cookie"],$_POST["temp_id2"]);
			break;
*/
		}
	break;
	case "system":
		switch ($_POST["act"])
		{
			case "view1":
				include_once("./class/wog_act_message.php");
				$wog_act_class = new wog_act_message;
				$wog_act_class->system_view1($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "sale":
		include_once("./class/wog_item_tool.php");
		$wog_item_tool = new wog_item_tool;
		include_once("./class/wog_act_bid.php");
		$wog_act_class = new wog_act_bid;
		switch ($_POST["act"])
		{
			case "sale":
				$wog_act_class->bid($_COOKIE["wog_cookie"]);
			break;
			case "buy":
				if($_POST["stype"]=="0")
				{
					$wog_act_class->sale_buy_item($_COOKIE["wog_cookie"]);
				}elseif($_POST["stype"]=="1")
				{
					$wog_act_class->sale_buy_pet($_COOKIE["wog_cookie"]);
				}
			break;
			case "buy3":
				if($_POST["stype"]=="0")
				{
					$wog_act_class->sale_buy_item3($_COOKIE["wog_cookie"]);
				}elseif($_POST["stype"]=="1")
				{
					$wog_act_class->sale_buy_pet($_COOKIE["wog_cookie"]);
				}
			break;
			case "sale2":
				$wog_act_class->bid2($_COOKIE["wog_cookie"]);
			break;
			case "buy2":
				$wog_act_class->sale_buy_item2($_COOKIE["wog_cookie"]);
			break;
			case "buy2_1":
				$wog_act_class->sale_buy_item2_1($_COOKIE["wog_cookie"]);
			break;
		}
		unset($wog_item_tool);
	break;
##########----------組隊_start----------##########
	case "team":
		include_once("./class/wog_act_team.php");
		$wog_act_class = new wog_act_team;
		switch ($_POST["act"])
		{
			case "list":
				$wog_act_class->team_list($_COOKIE["wog_cookie"]);
			break;
			case "creat":
				$wog_act_class->team_creat($_COOKIE["wog_cookie"]);
			break;
			case "status":
				$wog_act_class->team_status($_COOKIE["wog_cookie"]);
			break;
			case "support":
				$wog_act_class->team_support($_COOKIE["wog_cookie"]);
			break;
			case "join":
				$wog_act_class->team_join($_COOKIE["wog_cookie"]);
			break;
			case "del":
				$wog_act_class->team_del($_COOKIE["wog_cookie"]);
			break;
			case "leave":
				$wog_act_class->team_leave($_COOKIE["wog_cookie"]);
			break;
			case "get_member":
				$wog_act_class->team_get_member($_COOKIE["wog_cookie"]);
			break;
			case "get_save_member":
				$wog_act_class->team_get_save_member($_COOKIE["wog_cookie"]);
			break;
			case "nosupport":
				$wog_act_class->team_nosupport($_COOKIE["wog_cookie"]);
			break;
			case "chat":
				$wog_act_class->team_chat($_COOKIE["wog_cookie"]);
			break;
		}
		break;
##########----------組隊_end----------##########

## ---- 探險隊 BEGIN ---- ##
	case "expedition":
		include_once('class/wog_act_expedition.php');
		$cls = new wog_act_expedition;
		switch($_POST["act"]) {
			case "donate":
				$cls->donate($_COOKIE["wog_cookie"]);
				break;
			case "pause":
				$cls->pause($_COOKIE["wog_cookie"]);
				break;
			case "show":
				$cls->show($_COOKIE["wog_cookie"]);
				break;
			case "start":
				$cls->start($_COOKIE["wog_cookie"]);
				break;
		}
		break;
## ---- 探險隊 END ---- ##
	case "honor":
		include_once("./class/wog_act_honor.php");
		$wog_act_class = new wog_act_honor;
		switch ($_POST["act"])
		{
			case "list":
				$wog_act_class->honor_list($_COOKIE["wog_cookie"]);
			break;
			case "buy":
				include_once("./class/wog_item_tool.php");
				$wog_item_tool = new wog_item_tool;
				$wog_act_class->honor_buy($_COOKIE["wog_cookie"]);
				unset($wog_item_tool);
			break;
		}
	break;
	case "pet":
		include_once("./class/wog_act_pet.php");
		$wog_act_class = new wog_act_pet;
		switch ($_POST["act"])
		{
			case "index":
				$wog_act_class->pet_index($_COOKIE["wog_cookie"]);
			break;
			case "ac":
				$wog_act_class->pet_ac($_COOKIE["wog_cookie"]);
			break;
			case "eat":
				$wog_act_class->pet_eat($_COOKIE["wog_cookie"]);
			break;
			case "sale":
				$wog_act_class->pet_sale($_COOKIE["wog_cookie"]);
			break;
			case "chg_st":
				$wog_act_class->pet_chg_st($_COOKIE["wog_cookie"]);
			break;
			case "rename":
				$wog_act_class->pet_rename($_COOKIE["wog_cookie"]);
			break;
			case "img":
				$wog_act_class->pet_img($_COOKIE["wog_cookie"]);
			break;
			case "unimg":
				$wog_act_class->pet_unimg($_COOKIE["wog_cookie"]);
			break;
			case "leave":
				$wog_act_class->pet_leave($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "mission":
		include_once("./class/wog_act_mission.php");
		$wog_act_class = new wog_act_mission;
		switch ($_POST["act"])
		{
			case "list":
				$wog_act_class->mission_list($_COOKIE["wog_cookie"]);
			break;
			case "detail":
				$wog_act_class->mission_detail($_COOKIE["wog_cookie"]);
			break;
			case "book":
				$wog_act_class->mission_book($_COOKIE["wog_cookie"]);
			break;
			case "get":
				$wog_act_class->mission_get($_COOKIE["wog_cookie"]);
			break;
			case "break":
				$wog_act_class->mission_break($_COOKIE["wog_cookie"]);
			break;
			case "end":
				include_once("./class/wog_item_tool.php");
				include_once("./class/wog_mission_tool.php");
				$wog_item_tool= new wog_item_tool;
				$wog_mission_tool= new wog_mission_tool;
				$wog_act_class->mission_end($_COOKIE["wog_cookie"],$_POST["temp_id"]);
				unset($wog_item_tool,$wog_mission_tool);
			break;
			case "paper":
				$wog_act_class->mission_paper($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "mall":
		include_once("./class/wog_act_mall.php");
		$wog_act_class = new wog_act_mall;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->mall_view($_COOKIE["wog_cookie"]);
			break;
			case "buy":
				include_once("./class/wog_item_tool.php");
				$wog_item_tool= new wog_item_tool;
				$wog_act_class->mall_buy($_COOKIE["wog_cookie"]);
				unset($wog_item_tool);
			break;
			case "restup":
				$wog_act_class->mall_restup($_COOKIE["wog_cookie"]);
			break;
			case "chgid":
				$wog_act_class->chgid($_COOKIE["wog_cookie"]);
			break;
		}
	break;

	case "message": // 信箱
		include_once("./class/wog_act_message.php");
		$wog_act_class = new wog_act_message;
		switch ($_POST["act"])
		{
			case "list":
				$wog_act_class->message_box_list($_COOKIE["wog_cookie"]);
			break;
			case "add":
				$wog_act_class->message_box_add($_COOKIE["wog_cookie"]);
			break;
			case "vbody":
				$wog_act_class->message_box_vbody($_COOKIE["wog_cookie"]);
			break;
			case "item_list":
				$wog_act_class->message_item_list($_COOKIE["wog_cookie"]);
			break;
			case "item_get":
				include_once("./class/wog_item_tool.php");
				$wog_item_tool= new wog_item_tool;
				$wog_act_class->message_item_get($_COOKIE["wog_cookie"]);
				unset($wog_item_tool);
			break;
		}
	break;
	case "friend": // 好友
		include_once("./class/wog_act_friend.php");
		$wog_act_class = new wog_act_friend;
		switch ($_POST["act"])
		{
			case "list":
				$wog_act_class->friend_list($_COOKIE["wog_cookie"]);
			break;
			case "add":
				$wog_act_class->friend_add($_COOKIE["wog_cookie"]);
			break;
			case "del":
				$wog_act_class->friend_del($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "recomm":
		include_once("./class/wog_act_recomm.php");
		$wog_act_class = new wog_act_recomm;
		switch ($_POST["act"])
		{
			case "mail":
				$wog_act_class->recomm_send($_COOKIE["wog_cookie"]);
			break;
			case "msn":
				$wog_act_class->recomm_msn($_COOKIE["wog_cookie"]);
				unset($msn);
			break;
			case "qq":
				$wog_act_class->recomm_qq($_COOKIE["wog_cookie"]);
			break;
			case "etcmail":
				$wog_act_class->recomm_etcsend($_COOKIE["wog_cookie"]);
			break;
		}
	break;
	case "event":
		include_once('./forum_support/php-captcha.inc.php');
		event_ans($_COOKIE["wog_cookie"]);
	break;
	case "pk":
		include_once("./class/wog_act_pk.php");
		$wog_act_class = new wog_act_pk;
		switch ($_POST["act"])
		{
			case "view":
				$wog_act_class->pk_view($_COOKIE["wog_cookie"]);
			break;
			case "setup":
				$wog_act_class->pk_setup($_COOKIE["wog_cookie"],$_POST["job_id"]);
			break;
		}
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

//##################### event_ans begin #################
function event_ans($user_id)
{
	global $DB_site,$_POST,$_SESSION,$wog_arry,$lang;
	$p=$DB_site->query_first("select p_attempts,p_lock from wog_player where p_id=".$user_id);
	if(!$p)
	{
		alertWindowMsg($lang['wog_act_relogin']);
	}
	if($p["p_lock"]==1)
	{
		alertWindowMsg(sprintf($lang['wog_act_event_lock'],$wog_arry["unfreeze"]));
	}
	if ($_POST["pay_id"] == ""){
		if($p["p_attempts"]>$wog_arry["attempts"])
		{
			$DB_site->query("update wog_player set p_lock=1,p_lock_time=".time().",p_attempts=0 where p_id=".$user_id);
			cookie_clean();
			alertWindowMsg(sprintf($lang['wog_act_event_lock'],$wog_arry["unfreeze"]));
		}else
		{
			$DB_site->query("update wog_player set p_attempts=p_attempts+1 where p_id=".$user_id);
			alertWindowMsg(sprintf($lang['wog_act_event_err'],($wog_arry["attempts"]-$p["p_attempts"]-1)));
		}
	}
	if (PhpCaptcha::Validate($_POST['pay_id']))
	{
		$DB_site->query("update wog_player set p_attempts=0 where p_id=".$user_id);
		showscript("alert('".$lang['wog_act_event_ok']."');parent.ad_view()");
	} else {
		if($p["p_attempts"]>$wog_arry["attempts"])
		{
			$DB_site->query("update wog_player set p_lock=1,p_lock_time=".time()." where p_id=".$user_id);
			cookie_clean();
			alertWindowMsg(sprintf($lang['wog_act_event_lock'],$wog_arry["unfreeze"]));
		}else
		{
			$DB_site->query("update wog_player set p_attempts=p_attempts+1 where p_id=".$user_id);
			alertWindowMsg(sprintf($lang['wog_act_event_err'],($wog_arry["attempts"]-$p["p_attempts"]-1)));
		}
	}
	unset($s);
	unset($packs);
}
compress_exit();
?>