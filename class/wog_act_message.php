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

class wog_act_message{
	function system_view1($user_id)
	{
		global $DB_site,$lang;
		$DB_site->query("delete from wog_message where dateline < ".(time()-(10*24*60*60)));
		$sql="select title,dateline from wog_message where p_id=".$user_id." order by m_id desc LIMIT 10";
		$pack=$DB_site->query($sql);
		$s1="";
		$s2="";
		while($packs=$DB_site->fetch_array($pack))
		{
			$s1=$s1.";".$packs[title];
			$s2=$s2.";".set_date($packs[dateline]);
		}
		$s1=substr($s1,1);
		$s2=substr($s2,1);
		$DB_site->free_result($pack);
		showscript("parent.system_view('$s1','$s2')");
		unset($s1,$s2,$packs);
	}
	function message_box_list($user_id)
	{
		global $DB_site,$lang,$wog_arry,$_POST;
		$DB_site->query("delete from wog_message_box where m_time <= ".(time()-$wog_arry["message_box"]));
		if(empty($_POST["page"]) || !is_numeric($_POST["page"]))
		{
			$_POST["page"]="1";
		}
		$page=(int)$_POST["page"];
		$spage=($page*8)-8;
		

		$sql="select count(id) from wog_message_box where p_id=".$user_id;
		$m=$DB_site->query_first($sql);

		$sql="select id,p_name,m_subject,m_read,m_time from wog_message_box where p_id=".$user_id." order by id desc LIMIT ".$spage.",8";
		$pack=$DB_site->query($sql);
		$s="";
		while($packs=$DB_site->fetch_array($pack))
		{
			$s.=";".$packs[id].",".$packs[p_name].",".$packs[m_subject].",".$packs[m_read].",".set_date($packs[m_time]);
		}
		$s=substr($s,1);
		$DB_site->free_result($pack);
		showscript("parent.message_box_list('$s',$page,$m[0])");
		unset($s,$pack);
	}
	function message_box_vbody($user_id)
	{
		global $DB_site,$_POST;
		$id=$_POST["temp_id"];
		$sql="update wog_message_box set m_read=1 where id=".$id;
		$DB_site->query($sql);
		$sql="select p_name,m_subject,m_body from wog_message_box where id=".$id;
		$m=$DB_site->query_first($sql);
		$temp=str_replace("\r\n","[n]",$m[m_body]);
		$s=$m[p_name].",".$m[m_subject].",".$temp;
		showscript("parent.message_vbody('$s')");
		unset($s,$m);
	}
	function message_box_add($user_id)
	{
		global $DB_site,$lang;
		$name=addslashes(htmlspecialchars($_POST["name"]));
		$subject=$_POST["subject"];
		$body=$_POST["body"];
		$time=time();
		if(empty($subject) || empty($name) || empty($body))
		{
			alertWindowMsg($lang['wog_act_message_er1']);
		}
		$to_p=$DB_site->query_first("SELECT p_id FROM wog_player WHERE p_name='".$name."'");
		$from_p=$DB_site->query_first("SELECT p_name FROM wog_player WHERE p_id=$user_id");

		$sql="insert into wog_message_box(p_id,p_name,m_subject,m_body,m_time)values($to_p[p_id],'$from_p[p_name]','$subject','$body',$time)";
		$DB_site->query($sql);
		$sql="insert wog_message(p_id,title,dateline)values($to_p[p_id],'".sprintf($lang['wog_act_message_msg1'],$from_p[p_name])."',$time)";
		$DB_site->query($sql);
		showscript("parent.wog_message_box(7,0,1)");
	}
	function message_item_list($user_id)
	{
		global $DB_site,$lang,$_POST;
		$DB_site->query("delete from wog_player_reitem where end_time <= ".time());
		$item_array=array();
		$item_array2=array();
		$temp_s=array();
		$item=$DB_site->query("select a.id,a.d_id,a.d_num,b.d_name,b.d_hole,b.d_send,a.d_type,a.hs_id,a.ps_id from wog_player_reitem a,wog_df b where a.p_id=$user_id and b.d_id=a.d_id");
		while($items=$DB_site->fetch_array($item))
		{
			if(!empty($items[hs_id])){$items[d_id].=":".$items[hs_id];}
			if(!empty($items[ps_id]))
			{
				$sql="select c.plus_num,c.ps_id from wog_plus_setup c where c.ps_id=".$items[ps_id];
				$plus=$DB_site->query_first($sql);
				$items[d_id].="&".$items[ps_id];$items[d_name].="+".$plus[plus_num];
			}
			$temp_s[]=$items[id].",".$items[d_id].",".$items[d_name]."(".$items[d_hole]."),".$items[d_num].",".$items[d_send].",".$items[d_type].",".$items[hs_id];
		}
		$DB_site->free_result($item);
		unset($items,$plus);
		showscript("parent.message_item_list('".implode(";",$temp_s)."')");
		unset($temp_s);
	}
	function message_item_get($user_id)
	{
		global $DB_site,$lang,$_POST,$wog_item_tool,$wog_arry,$a_id;
		$id=$_POST["temp_id"];
		if(empty($id)){alertWindowMsg($lang['wog_act_arm_noselect']);}
		
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$item=$DB_site->query_first("select a.d_id,a.d_num,a.d_type,a.hs_id,a.ps_id from wog_player_reitem a where a.id=$id and a.p_id=$user_id for update");
		$a_id=type_name($item[d_type]);	
		$sql="select p_bag from wog_player where p_id=$user_id";
		$pay_id=$DB_site->query_first($sql);
		if(!$pay_id)
		{
			alertWindowMsg($lang['wog_act_arm_nomove']);
		}
		$sql="select ".$a_id." from wog_item where p_id=".$user_id." for update";
		$pay=$DB_site->query_first($sql);
		$temp_pack=array();
		if(!empty($pay[0]))
		{
			$temp_pack=explode(",",$pay[0]);
		}
		$d_id2=$item[d_id];
		if($item[hs_id]!=0){$d_id2.=":".$item[hs_id];}
		if($item[ps_id]!=0){$d_id2.="&".$item[ps_id];}
		$temp_pack=$wog_item_tool->item_in($temp_pack,$d_id2,$item[d_num]);
		if($a_id=="d_item_id" || $a_id=="d_stone_id" || $a_id=="d_plus_id")
		{
			$bag=$wog_arry["item_limit"]+$pay_id[p_bag];
		}else
		{
			$bag=$wog_arry["item_limit"];
		}
		if(count($temp_pack) > $bag)
		{
			alertWindowMsg($lang['wog_act_bid_full']);
		}
		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$temp_pack)."' where p_id=".$user_id);
		$DB_site->query("delete from wog_player_reitem where id=$id");
		$DB_site->query_first("COMMIT");
		$DB_site->query_first("set autocommit=1");
		unset($adds,$temp_pack,$item,$pay_id);
		$this->message_item_list($user_id);
	}
	function system_chat($message)
	{
		global $lang,$wog_arry;
		require_once($wog_arry["chat_path"]."/class/chat_class.php");
		$chat_class = new chat_class;
		$chat_class->file_path=$wog_arry["chat_path"]."/data/";
		$speed=$chat_class->get_speed();
		$speed++;
		if($speed >= 2147483000)
		{
			$speed=0;
		}
		$chat_body="parent.goldset('".$lang['wog_chat_msg1']."','0','<font color=red>".$message."</font>','');";
		$chat_body=$speed."|".$chat_body."|0|0|0|".$lang['wog_chat_msg1']."|1";
		$chat_class->chat_message($chat_body);
		$chat_class->set_speed($speed);
		unset($chat_class,$speed);
	}
	function phpbb3_message($user_id,$user_name,$bbs_id)
	{
		global $DB_site,$_POST,$lang;
		$_POST["pay_id"]=addslashes(htmlspecialchars($_POST["pay_id"]));
		$p=$DB_site->query_first("SELECT p_bbsid FROM wog_player WHERE p_name='".$_POST["pay_id"]."'");
		if($p)
		{
			if($p["p_bbsid"]==0)
			{
				alertWindowMsg($lang['wog_act_nofroum_member']);
			}else
			{
				$time=time();
				$sql = "INSERT INTO phpbb_privmsgs(author_id,author_ip,message_time,message_subject,message_text,to_address,bcc_address)
				VALUES ($bbs_id,'".get_ip()."',$time,'WOG訊息通知-".$user_name."', '" . str_replace("\'", "''", addslashes($_POST["temp_id"])) . "','','')";
				$DB_site->query($sql);
				$privmsg_sent_id=$DB_site->insert_id();
				$sql = "INSERT INTO phpbb_privmsgs_to(msg_id, user_id, author_id,folder_id)
				VALUES ($privmsg_sent_id, $bbs_id, $bbs_id, -2)";
				$DB_site->query($sql);
				$sql = "INSERT INTO phpbb_privmsgs_to(msg_id, user_id, author_id,folder_id)
				VALUES ($privmsg_sent_id, ".$p["p_bbsid"].", $bbs_id, -3)";
				$DB_site->query($sql);
				showscript("parent.job_end(7)");
			}
		}else
		{
			alertWindowMsg($lang['wog_act_noid']);
		}
		unset($p);
	}
	function phpbb_message($user_id,$user_name,$bbs_id)
	{
		global $DB_site,$_POST,$root_path,$lang,$db, $board_config;
		define('IN_PHPBB', true);
		$phpbb_root_path=$root_path;
		include_once($root_path . 'extension.inc');
		include_once($root_path . 'includes/bbcode.'.$phpEx);
		include_once($root_path . 'includes/functions.'.$phpEx);
		include_once($root_path . 'config.'.$phpEx);
		include_once($root_path . 'includes/constants.'.$phpEx);
		include_once($root_path . 'includes/db.'.$phpEx);
		$_POST["pay_id"]=addslashes(htmlspecialchars($_POST["pay_id"]));
		$board_config = array();
		$userdata = array();
		$sql = "SELECT *
			FROM " . CONFIG_TABLE;
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(CRITICAL_ERROR, "Could not query config information", "", __LINE__, __FILE__, $sql);
		}
		while ( $row = $db->sql_fetchrow($result) )
		{
			$board_config[$row['config_name']] = $row['config_value'];
		}
		$p=$DB_site->query_first("SELECT p_bbsid FROM wog_player  WHERE p_name='".$_POST["pay_id"]."' ");
		if($p)
		{
			if($p["p_bbsid"]==0)
			{
				$db->sql_close();
				alertWindowMsg($lang['wog_act_nofroum_member']);

			}else
			{
				$sql = "INSERT  INTO phpbb_privmsgs(privmsgs_type,privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip)
				VALUES (5,'WOG訊息通知-".$user_name."', ".$bbs_id.",".$p["p_bbsid"].",".time(). ",'".get_ip(). "')";
				$DB_site->query($sql);
				$privmsg_sent_id=$DB_site->insert_id();
				$sql = "INSERT  INTO phpbb_privmsgs_text(privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text)
				VALUES ($privmsg_sent_id, '".make_bbcode_uid()."', '" . str_replace("\'", "''", addslashes($_POST["temp_id"])) . "')";
				$DB_site->query($sql);
				$sql = "update phpbb_users set user_new_privmsg=1 where user_id=".$p["p_bbsid"]."";
				$DB_site->query($sql);
				$db->sql_close();
				showscript("parent.job_end(7)");
			}
		}else
		{
			$db->sql_close();
			alertWindowMsg($lang['wog_act_noid']);

		}
		unset($p);
	}

	function vbb_message($user_id,$user_name,$bbs_id)
	{
		global $DB_site,$_POST,$lang;
		$_POST["pay_id"]=addslashes(htmlspecialchars($_POST["pay_id"]));
		$p=$DB_site->query_first("SELECT p_bbsid FROM wog_player WHERE p_name='".$_POST["pay_id"]."'");
		if($p)
		{
			$DB_site->query("INSERT INTO privatemessage (privatemessageid,userid,touserid,fromuserid,title,message,dateline)VALUES(NULL,$p[p_bbsid],$p[p_bbsid],$bbs_id,'".$user_name." 傳來WOG訊息','".addslashes($_POST["temp_id"])."',".time().")");
			showscript("parent.job_end(7)");
		}else
		{
			alertWindowMsg($lang['wog_act_noid']);
		}
		unset($p);
	}

	function dz_message($user_id,$user_name,$bbs_id)
	{
		global $DB_site,$_POST,$lang;
		$_POST["pay_id"]=addslashes(htmlspecialchars($_POST["pay_id"]));
		$p=$DB_site->query_first("SELECT p_bbsid FROM wog_player WHERE p_name='".$_POST["pay_id"]."'");
		if($p)
		{
			$DB_site->query("INSERT INTO cdb_pms (msgfrom, msgfromid, msgtoid, folder, new, subject, dateline, message)
				VALUES('$user_name', '$bbs_id', '$p[p_bbsid]', 'inbox', '1','".$user_name." 傳來WOG訊息', '".time()."', '".addslashes($_POST["temp_id"])."')");
			showscript("parent.job_end(7)");
		}else
		{
			alertWindowMsg($lang['wog_act_noid']);
		}
		unset($p);
	}

	function vbb3_message($user_id,$user_name,$bbs_id)
	{
		global $DB_site,$_POST,$lang,$root_path;
//		$tostring = array(); // the array of users who will appear in the pmtext record
//		$tostring["$user[userid]"] = $user['username'];
		$_POST["pay_id"]=addslashes(htmlspecialchars($_POST["pay_id"]));
		$p=$DB_site->query_first("SELECT p_bbsid FROM wog_player WHERE p_name='".$_POST["pay_id"]."'");
		$p2=$DB_site->query_first("SELECT username  FROM user WHERE userid =".$bbs_id."");
		if($p && $p2)
		{
			// insert private message text
			require_once($root_path.'includes/functions.php');
			$message = addslashes(fetch_censored_text($_POST["temp_id"]));
			$DB_site->query("INSERT INTO pmtext\n\t(fromuserid, fromusername, title, message, touserarray, iconid, dateline, showsignature, allowsmilie)\nVALUES\n\t($bbs_id, '" . addslashes($p2[username]) . "', '".$user_name." 傳來WOG訊息', '".$message."', '', 0, " . time() . ", 0, 1)");
			// get the inserted private message id
			$pmtextid = $DB_site->insert_id();
			// save a copy into $bbuserinfo's sent items folder
			$DB_site->query("INSERT INTO pm (pmtextid, userid, folderid, messageread) VALUES ($pmtextid, $bbs_id, 0, 0)");
			$DB_site->shutdown_query("UPDATE user SET pmtotal=pmtotal+1,pmunread=pmunread+1 WHERE userid=$bbs_id");
			showscript("parent.job_end(7)");
		}else
		{
			alertWindowMsg($lang['wog_act_noid']);
		}
		unset($p);
		unset($p2);
	}

}
?>