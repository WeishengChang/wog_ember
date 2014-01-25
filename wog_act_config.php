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


//no forum
define('USER_TABLE','wog_player');
define('BANK_TABLE','wog_player');
define('MONEY_FIELB','p_money');
define('BANK_FIELB','p_bank');
define('USER_ID','p_id');
$forum_check="\$bbs_id=1;";
$forum_message="";
/*
//vbb2.X support
define('USER_TABLE','user');
define('MONEY_FIELB','money');
define('BANK_FIELB','bank');
define('USER_ID','userid');
$forum_check="include_once('./forum_support/function_vbb2.php');\$bbuserinfo=vbb_check();\$bbs_id=\$bbuserinfo[userid];";
$forum_message="\$wog_act_class = new wog_act_message;";
$forum_message.="\$wog_act_class->vbb_message(\$_COOKIE[\"wog_cookie\"],\$_COOKIE[\"wog_cookie_name\"],\$_COOKIE[\"wog_bbs_id\"]);";

//vbb3.0.X support
define('USER_TABLE','user');
define('MONEY_FIELB','money');
define('BANK_FIELB','bank');
define('USER_ID','userid');
$forum_check="include_once('./forum_support/function_vbb3.php');\$bbuserinfo=vbb3_check();\$bbs_id=\$bbuserinfo[userid];";
$forum_message="\$wog_act_class = new wog_act_message;";
$forum_message.="\$wog_act_class->vbb3_message(\$_COOKIE[\"wog_cookie\"],\$_COOKIE[\"wog_cookie_name\"],\$_COOKIE[\"wog_bbs_id\"]);";

//DISCUZ! 2.5 support
define('USER_TABLE','cdb_members');
define('MONEY_FIELB','money');
define('BANK_FIELB','bank');
define('USER_ID','uid');
$forum_check="include_once('./forum_support/function_dz2.php');\$bbs_id=dz_check();";
$forum_message="\$wog_act_class = new wog_act_message;";
$forum_message.="\$wog_act_class->dz_message(\$_COOKIE[\"wog_cookie\"],\$_COOKIE[\"wog_cookie_name\"],\$_COOKIE[\"wog_bbs_id\"]);";

//DISCUZ! 4.1.0 support
define('USER_TABLE','cdb_members');
define('MONEY_FIELB','money');
define('BANK_FIELB','bank');
define('USER_ID','uid');
$forum_check="include_once('./forum_support/function_dz4.php');\$bbs_id=dz_check();";
$forum_message="\$wog_act_class = new wog_act_message;";
$forum_message.="\$wog_act_class->dz_message(\$_COOKIE[\"wog_cookie\"],\$_COOKIE[\"wog_cookie_name\"],\$_COOKIE[\"wog_bbs_id\"]);";

//vbb3.5.X support
define('USER_TABLE','user');
define('MONEY_FIELB','money');
define('BANK_FIELB','bank');
define('USER_ID','userid');
$forum_check="include_once('./forum_support/function_vbb35.php');\$bbs_id=vbb35_check();";
$forum_message="\$wog_act_class = new wog_act_message;";
$forum_message.="\$wog_act_class->vbb3_message(\$_COOKIE[\"wog_cookie\"],\$_COOKIE[\"wog_cookie_name\"],\$_COOKIE[\"wog_bbs_id\"]);";

//phpbb2 support
define('USER_TABLE','phpbb_users');
define('BANK_TABLE','phpbb_bank');
define('MONEY_FIELB','user_money');
define('BANK_FIELB','user_bank');
define('USER_ID','user_id');
$forum_check="include_once('./forum_support/function_phpbb.php');\$bbs_id=phpbb_check(\$p_ip);";
$forum_message="\$wog_act_class = new wog_act_message;";
$forum_message.="\$wog_act_class->phpbb_message(\$_COOKIE[\"wog_cookie\"],\$_COOKIE[\"wog_cookie_name\"],\$_COOKIE[\"wog_bbs_id\"]);";


//phpbb3 support
define('USER_TABLE','phpbb_users');
define('BANK_TABLE','phpbb_bank');
define('MONEY_FIELB','user_money');
define('BANK_FIELB','user_bank');
define('USER_ID','user_id');
$forum_check="include_once('./forum_support/function_phpbb3.php');\$bbs_id=phpbb_check(\$p_ip);";
$forum_message="\$wog_act_class = new wog_act_message;";
$forum_message.="\$wog_act_class->phpbb3_message(\$_COOKIE[\"wog_cookie\"],\$_COOKIE[\"wog_cookie_name\"],\$_COOKIE[\"wog_bbs_id\"]);";
*/

$root_path = './../';//論壇主程式路徑
$wog_arry["cookie_debug"]="vr#dq2^HR1@#cv4i"; //驗證碼,請修改內容,請勿使用預設值
$wog_arry["del_day"]=62208000; //幾秒沒玩刪除角色
$wog_arry["del_confirm"]=604800; //幾天沒玩刪除角色
$wog_arry["total_point"]=10; //創造角色所給予的點數
$wog_arry["offline_time"]=1200;//幾秒後沒執行判斷為離線
$wog_arry["player_num"]=1;//創造角色數目限制
$wog_arry["sale_day"]=10;//限制能拍賣幾天
$wog_arry["view2_money"]=10;//查看對手費用
$wog_arry["chang_face"]=1000;//改變圖像費用
$wog_arry["chang_sex"]=1000000;//變性費用
$wog_arry["depot_limit"]=20;//倉庫上限,建議值20
$wog_arry["item_limit"]=99;//道具數量上限,建議值15
$wog_arry["item_app_limit"]=99;//道具堆疊上限,預設值9
$wog_arry["item_setapp_limit"]=9;//裝備道具堆疊上限,預設值9
$wog_arry["player_age"]=30;//多少天一歲
$wog_arry["chat_path"]="./wog_chat";//聊天室class位子
$wog_arry["exchange_up"]=300000;//資源低於多少自動補貨
$wog_arry["exchange_money"]=0.001;//交易手續費
$wog_arry["message_box"]=2592000;//信箱保存時間，預設30天，單位秒
$wog_arry["retime"]=864000;//信箱備份物品保存時間，預設10天，單位秒
$wog_arry["act_num_time"]=18;//補充行動力週期時間,單位秒
$wog_arry["act_num"]=6;//補充行動力數量
$wog_arry["bid2_money"]=1000;//收購最低金額
$wog_arry["mall_time"]=86400;//商城限量物品更新時間,預設1天
$wog_arry["timezone"]=15;//時差設定

$wog_arry["hotel_money"]=10;//住宿所需要的金額
$wog_arry["hotel_time"]=-1;//住宿所需要等待秒數
$wog_arry["hotel_die_time"]=-1;//hp=0 住宿所需要等待秒數

$wog_arry["etc_dely_time"]=0;//wog_etc.php延遲時間
$wog_arry["act_dely_time"]=0;//wog_act.php延遲時間
//$wog_arry["fight_dely_time"]=15;//wog_fight.php延遲時間
$wog_arry["online_limit"]=900;//線上人數
$wog_arry["login_time"]=18;//等待登入時間
$wog_arry["gzip_compress"]=1;//gzip設定 1開 0關

$wog_arry["cp_mmoney"]=500;//挑冠軍需要多少金額
$wog_arry["f_time"]=-1;//戰鬥間隔時間
$wog_arry["f_time1"]=-1;//行動力不足時的戰鬥間隔時間
$wog_arry["f_down"]=1;//行動力不足時報酬降低比例
$wog_arry["f_count"]=40;//戰鬥回合限制
$wog_arry["f_hp"]=15;//戰鬥低於多少HP自動使用恢復劑
$wog_arry["f_sp"]=100;//戰鬥低於多少SP自動使用恢復劑
$wog_arry["f_cp"]=604800;//無法挑戰冠軍限制時間(預設7天)
$wog_arry["f_time_mercenary"]=5;//傭兵戰鬥時間 單位秒
$wog_arry["f_hero"][0]=259200;//英雄重置時間(預設3-4天)
$wog_arry["f_hero"][1]=345600;//英雄重置時間(預設3-4天)
$wog_arry["f_escape_hp"]=0;//低於多少HP逃離戰鬥

$wog_arry["creat_group"]=30000;//創造工會費用
$wog_arry["g_permissions_max"]=10;//職務數量上限
$wog_arry["g_maxpeo"]=10;//駐守人數上限
$wog_arry["g_make_time"]=12;//兵種生產時間(每單位秒數)
$wog_arry["g_job_max"]=3;//最大作業數量
$wog_arry["g_conveyance_time"]=5;//運輸兵力所需秒數
$wog_arry["g_ex_conveyanc_time"]=6;//運輸資源所需秒數
$wog_arry["g_detect_time"]=5.5;//偵查所需秒數
$wog_arry["g_detect_save_time"]=864000;//偵查保存秒數,預設10天
$wog_arry["g_durable_time"]=0.5;//修復據點每單位秒數
$wog_arry["g_count"]=30;//公會戰場戰鬥回合限制
$wog_arry["g_durable"]=8000;//據點預設耐久度
$wog_arry["g_not_fight"]=432000;//新公會保護期,預設5天 432000
$wog_arry["g_fight_max"]=3;//作戰最大兵總攜帶量 預設3
$wog_arry["g_lost_time"]=86400;//公會被攻破的保護時間
$wog_arry["g_boss_npc_day"]=6;//BOSS NPC被攻破的重置時間
$wog_arry["g_boss_npc_noday"]=0;//BOSS NPC結束時間
$wog_arry["g_depot_num"]=100;//倉庫最大數量
$wog_arry["g_bulid_make_time"]=1200;//研究時間
$wog_arry["g_lv"]=array(1=>10,2=>20,3=>30,4=>40,5=>50);//公會等級
$wog_arry["g_sale"]=604800;//拍賣時間秒數,預設7天
$wog_arry["g_restart_mission"]=604800;//重置任務時間秒數,預設7天
$wog_arry["g_news"]=604800;//公會戰報信息保存時間(秒數),預設10天
$wog_arry["g_wp"]=array(
			1=>'步兵',
			2=>'槍兵',
			3=>'騎兵',
			4=>'弓兵',
			5=>'衝車',
			6=>'火槍',
			7=>'火炮',
			8=>'工兵',
			9=>'火槍騎',
			10=>'魔龍',
			11=>'巨獸',
			12=>'邪駭'
		);
$wog_arry["g_ex"]=array(
			1=>'煤',
			2=>'木材',
			3=>'石塊',
			4=>'石油',
			5=>'黃金',
			6=>'酒',
			7=>'大麥',
			8=>'香菸',
			9=>'鐵',
			10=>'皮毛',
			11=>'絲線',
			12=>'珍珠'
		);
$wog_arry["place"]=array(
			1=>'中央平原',2=>'試鍊洞窟',3=>'黑暗沼澤',4=>'迷霧森林',
			5=>'古代遺跡',6=>'久遠戰場',7=>'王者之路',8=>'幻獸森林',
			9=>'星河異界',10=>'灼熱荒漠',11=>'無淵洞窟',12=>'天空之城',
			13=>'水晶之間',14=>'失落古船',15=>'最果之島',16=>'冷峰寒地',
			17=>'廢棄洞窟',18=>'日沒碉堡',19=>'靜止之城',20=>'黑曜神廟',
			21=>'血之魔域'
		);

$wog_arry["t_peo"]=5;//隊伍人數上限
//$wog_arry["t_lv_limit"]=50;//組隊等級限制
$wog_arry["t_time"]=3;//支援及經驗值分享有效時間(單位分鐘)

$wog_arry["pet_ac_time"]=10;//寵物特訓餵食間隔時間
$wog_arry["pet_f_time"]=28800;//寵物競技間隔時間(預設8小時)
$wog_arry["pet_fu_time"]=15;//寵物飢餓間隔時間(單位分鐘)
$wog_arry["pet_age"]=864000;//寵物幾秒一歲(預設10天)
$wog_arry["pet_eat_money"]=20;//寵物餵食金額

$wog_arry["mission_get_num"]=3;//同時進行任務上限

$wog_arry["event_ans"]=0;//隨機問答 1開 0關
$wog_arry["event_ans_max"]=310;//隨機問答出現機率 預設值350,表示機率1/150
$wog_arry["keylen"]=5;//驗證碼長度
$wog_arry["attempts"]=6;//允許回答次數
$wog_arry["unfreeze"]=48;//多久解開帳號，預設48小時
$wog_arry["main_url"]="127.0.0.1";//遊戲網址，不帶http,用於fsockopen
$wog_arry["logout_url"]="http://www.et99.net/wog4/black.htm";//登出後連結的網址
$wog_arry["code_url"]="http://127.0.0.1/wog/wog_etc.php?f=code&code=";//開通驗證網址
?>