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
require_once('./language/wog_fight_traditional_chinese.php');
/*
if(substr(getenv("HTTP_REFERER"),0,39)!="http://www.2233.idv.tw/wog/wog_foot.htm")
{
	if(substr(getenv("HTTP_REFERER"),0,31)!="http://www.2233.idv.tw/wog/wog_")
	{
		alertWindowMsg("論壇第一入口改成www.2233.idv.tw，請從www.2233.idv.tw登入論壇後再進入遊戲");
	}
}
*/
if($_COOKIE["wog_cookie"]=="")
{
	alertWindowMsg($lang['wog_act_nologin']);
}
//error_reporting(7);

require_once("wog_act_config.php");
require_once("./forum_support/global.php");
//session_register('act_time');
session_start();
session_check($wog_arry["f_time"],3);
if ($_COOKIE["wog_cookie_name"] != Null || $_COOKIE["wog_cookie"] != Null)
{
	if ($_COOKIE["wog_cookie_debug"] != md5($_COOKIE["wog_cookie"].$_COOKIE["wog_bbs_id"].$wog_arry["cookie_debug"]))
	{
		cookie_clean();
		showscript("alert('".$lang['wog_act_check_cookie']."');window.open('../','_top')");
	}
}
include_once("./class/wog_fight_event.php");
$wog_event_class = new wog_fight_event;
if(!isset($_POST["act"]))
{
	$_POST["act"]="";
}
if(!isset($_POST["f"]))
{
	$_POST["f"]="";
}
if(!isset($_POST["temp_id3"]))
{
	$_POST["temp_id3"]="20";
}
//Gen Code SETTING

//########################## switch case begin #######################
/*
if(empty($_COOKIE["wog_bbs_id"]))
{
	alertWindowMsg($lang['wog_act_nofroum_member']);
}

if(!empty($forum_message))
{
	$datecut = time() - $wog_arry["offline_time"];
	$online=$DB_site->query_first("select p_name from wog_player where p_online_time > $datecut and p_id<>".$_COOKIE["wog_cookie"]." and p_bbsid=".$_COOKIE["wog_bbs_id"]."");
	if($online)
	{
		setcookie("wog_cookie","");
		setcookie("wog_cookie_name","");
		setcookie("wog_bbs_id","");
		showscript("alert('".$lang['wog_act_logined']."');window.open('../','_top')");
	}
}
*/
if($_POST["temp_id3"] > 40)
{
	$f_count=$wog_arry["f_count"];
}else
{
	$f_count=(int)$_POST["temp_id3"];
}
if($_POST["temp_id4"] > 100 || $_POST["temp_id4"] < 0)
{
	$f_hp=$wog_arry["f_hp"]/100;
}else
{
	$f_hp=(int)$_POST["temp_id4"]/100;
}
if($_POST["temp_id8"] > 100 || $_POST["temp_id8"] < 0)
{
	$f_sp=$wog_arry["f_sp"]/100;
}else
{
	$f_sp=(int)$_POST["temp_id8"]/100;
}
if($_POST["temp_id5"] < 0 || $_POST["temp_id5"] > 100)
{
	$escape_hp=$wog_arry["f_escape_hp"];
}else
{
	$escape_hp=(int)$_POST["temp_id5"]/100;
}
echo charset();
switch ($_POST["f"])
{
	case "fire":
		include_once("./class/wog_fight_select.php");
		switch ($_POST["temp_id"])
		{
			case "0":
				include_once("./class/wog_item_tool.php");
				$wog_item_tool = new wog_item_tool;
				require_once("./class/wog_fight_m.php");
				$wogclass = new wog;
				$wogclass->win=0;
				$wogclass->lost=0;
				$wogclass->f_count=$f_count;
				$wogclass->f_hp=$f_hp;
				$wogclass->f_sp=$f_sp;
				$wogclass->escape_hp=$escape_hp;
				fire($_COOKIE["wog_cookie"]);
				unset($wog_item_tool);
			break;
			case "2":
				include_once("./class/wog_item_tool.php");
				$wog_item_tool = new wog_item_tool;
				key_check($_COOKIE["wog_cookie"]);
				require_once("./class/wog_fight_m.php");
				$wogclass = new wog;
				$wogclass->win=0;
				$wogclass->lost=0;
				$wogclass->f_count=$f_count;
				$wogclass->f_hp=$f_hp;
				$wogclass->f_sp=$f_sp;
				$wogclass->escape_hp=$escape_hp;
				fire($_COOKIE["wog_cookie"]);
				unset($wog_item_tool);
			break;
			case "1":
				require_once("./class/wog_fight_m.php");
				$wogclass = new wog;
				$wogclass->win=0;
				$wogclass->lost=0;
				$wogclass->f_count=$f_count;
				switch ($_POST["act"])
				{
					case "1000":
						fire_cp($_COOKIE["wog_cookie"]);
					break;
					case "1001":
						if(trim($_POST["towho"])=="")
						{
							alertWindowMsg($lang['wog_fight_cant_pk1']);
						}
						include_once("./class/wog_item_tool.php");
						$wog_item_tool = new wog_item_tool;
						fire_pk($_COOKIE["wog_cookie"]);
						unset($wog_item_tool);
					break;
				}
			break;
		}
	break;
	default:
//		die("不正常操作");
	break;
}
unset($wog_event_class,$wog_act_class,$wogclass,$wog_item_tool);
if(isset($DB_site))
{
	$DB_site->close();
}
//########################## switch case end #######################

function fire($user_id)
{
	global $DB_site,$_POST,$_COOKIE;
	$mission=array();
	$mission_id=array();
	if($_COOKIE["wog_cookie_mission_id"]!=0)
	{
		$result=$DB_site->query("SELECT a.m_id,a.m_monster_id,b.m_kill_num FROM wog_mission_main a,wog_mission_book b WHERE b.p_id=".$user_id." AND b.m_status=0 AND a.m_id=b.m_id ");
		if($result)
		{
			while($m_item=$DB_site->fetch_array($result))
			{
				if(empty($m_item['m_kill_num'])){
					$DB_site->query("update wog_mission_book set m_status=1 where p_id=".$user_id." and m_id=".$m_item['m_id']);
				}else{
					$m_id=explode(',',$m_item['m_monster_id']);
					$a=array();
					foreach($m_id as $value){
						$temp=explode('*',$value);
						$mission_id[$temp[0]]=$m_item['m_id'];
						$a[$temp[0]]=$temp[1];
					}
					$m_id=explode(',',$m_item['m_kill_num']);
					$b=array();
					foreach($m_id as $value){
						$temp=explode('*',$value);
						$b[$temp[0]]=$temp[1];
					}
					$mission[$m_item['m_id']]=array('m_id' => $m_item['m_id'],'m_monster_id' => implode(",",$mission_id),'m_kill_num' => $b,'m_need_num' => $a);
				}
			}
			if(count($mission)<1)
			{
				setcookie("wog_cookie_mission_id",0);
			}
		}
	}
	$wog_act_class = new wog_fight_select;
	$wog_act_class->fire($user_id,$mission,$mission_id);
}
//############  fire cp   ###########

function fire_cp($user_id)
{
	$wog_act_class = new wog_fight_select;
	$wog_act_class->fire_cp($user_id);
}

//############  fire pk   ###########
function fire_pk($user_id)
{
	$wog_act_class = new wog_fight_select;
	$wog_act_class->fire_pk($user_id);
}
function key_check($user_id)
{
	global $DB_site,$_POST,$a_id,$lang,$wog_item_tool;
	$time=time();
	$area_id=$_POST["act"];
	$sql="select area_time from wog_key_list where p_id=$user_id and area_id=$area_id";
	$key_check=$DB_site->query_first($sql);
	if(!$key_check)
	{
		key_use($user_id,$area_id);
	}
	else
	{
		if($key_check[area_time] <=$time)
		{
			$sql="delete from wog_key_list where p_id=$user_id and area_id=$area_id";
			$DB_site->query($sql);
			key_use($user_id,$area_id);
		}
	}
}
function key_use($user_id,$area_id)
{
	global $DB_site,$a_id,$lang,$wog_item_tool;
	$time=time();
	$a_id="d_key_id";
	$sql="select $a_id from wog_item where p_id=$user_id ";
	$item=$DB_site->query_first($sql);
	if(!empty($item[$a_id]))
	{
		$items=explode(",",$item[$a_id]);
	}
	else
	{
		alertWindowMsg($lang['wog_fight_key_error1']);
	}
	$sql="select d_id,area_time from wog_key_main where area_id=$area_id";
	$key_main=$DB_site->query_first($sql);

	$items=$wog_item_tool->item_out($user_id,$key_main[d_id],1,$items);
	$temp_sql=$a_id."='".implode(',',$items)."'";
	$DB_site->query("update wog_item set ".$temp_sql." where p_id=".$user_id);
	$time+=$key_main[area_time];
	$sql="insert into wog_key_list(p_id,area_id,area_time)values($user_id,$area_id,$time)";
	$DB_site->query($sql);
}
//###### this php end ######
echo "</script>\n";
compress_exit();
?>
