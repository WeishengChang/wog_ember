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

class wog_act_chara{
	function chara_make($recommid=0) //創造新增頁面
	{
/*
		alertWindowMsg("遊戲維護中,暫時關閉註冊");
*/
		global $DB_site,$_POST,$wog_arry,$lang;
		$temp_i_id="";
		$img=$DB_site->query("select i_id from wog_img order by i_id asc");
		while($imgs=$DB_site->fetch_array($img))
		{
		  $temp_i_id.=",".$imgs["i_id"];
		}
		$DB_site->free_result($img);
		unset($imgs);
		$temp_i_id=substr($temp_i_id,1);
		$temp_ch="";
		$ch=$DB_site->query("select ch_id,ch_name from wog_character where ch_mlv=1");
		while($chs=$DB_site->fetch_array($ch))
		{
			$temp_ch.=";".$chs["ch_id"].",".$chs["ch_name"];
		}
		$DB_site->free_result($ch);
		unset($chs);
		$temp_ch=substr($temp_ch,1,strlen($temp_ch));
		if(!empty($recommid))
		{
			$sql="select p_id from wog_player where p_id=".$recommid." and p_npc=0";
			$p=$DB_site->query_first($sql);
			if(!$p){$recommid=0;}
		}
		showscript("parent.chara_make_view('$wog_arry[total_point]','$temp_i_id','$temp_ch',$recommid);");
	}
	function chara_save($recommid=0)
	{
		global $DB_site,$_POST,$wog_arry,$lang,$forum_message;
		$time=time();
		$str=$_POST["str"]+10;
		$life=$_POST["life"]+10;
		$smart=$_POST["smart"]+10;
		$agi=$_POST["agi"]+10;
		$luck=rand(1,10);
		$p_name=$_POST["id"];
		$email=htmlspecialchars(trim($_POST["email"]));
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$DB_site->query("insert into wog_player(p_name,at,df,mat,mdf,s_property
		,str,life,vit,smart,agi,au,be,hp,p_sat_name
		,hpmax,ch_id,p_money,p_lv,p_exp,p_nextexp,p_sex,base_str,base_life,base_vit,base_smart,base_agi,base_au,base_be
		,p_password,i_img,p_cdate,p_email,p_online_time,p_birth,sp,spmax,p_lock,p_recomm
		)values('".$p_name."',".$str.",".$life.
		",".$smart.",10,".$_POST["s"]."
		,".$str.",".$life.",10,".$smart.",".$agi.",10,10
		,50,'".htmlspecialchars(trim($_POST["sat_name"]))."',50,".$_POST["ch"].",2000,1
		,0,1000,'".$_POST["sex"]."'
		,".$str.",".$life.",10,".$smart.",".$agi.",10,10
		,'".$_POST["pass"]."'
		,".$_POST["i_id"].",".$time.",'".$email."',".$time.",".$_POST["birth"]."
		,25,25,0,".$recommid."
		)");
		$user_id=$DB_site->insert_id();
		$DB_site->query("insert into wog_item(p_id,a_id,d_body_id,d_foot_id,d_hand_id,d_head_id,d_item_id,d_stone_id,d_honor_id,d_key_id,d_plus_id
		)values(".$user_id.",'','','','','','','','','','')");
		$DB_site->query("insert into wog_ch_exp(p_id)values(".$user_id.")");
		$DB_site->query("insert into wog_player_arm(p_id)values(".$user_id.")");
		$DB_site->query("insert into wog_skill_setup(p_id)values(".$user_id.")");
		if($recommid>0)
		{
			$sql="insert into wog_friend_list(p_id,f_id)values($user_id,$recommid)";
			$DB_site->query($sql);
			$DB_site->query("insert into wog_message(p_id,title,from_pid,dateline)values(".$recommid.",'".sprintf($lang['wog_act_friend_msg1'],$p_name)."',".$user_id.",".$time.")");
			$sql="insert into wog_friend_list(p_id,f_id)values($recommid,$user_id)";
			$DB_site->query($sql);
		}
		$DB_site->query_first("COMMIT");
		return $user_id;
	}
	function chara_chk() //儲存新增角色
	{
		global $DB_site,$_POST,$wog_arry,$lang,$forum_message;
		$errormessage="";
		$_POST["id"]=htmlspecialchars(trim($_POST["id"]));
		if(!isset($_POST["id"]) || !isset($_POST["pass"])  || !isset($_POST["sex"]) || !isset($_POST["s"]) || !isset($_POST["email"]) || !isset($_POST["birth"]))
		{
			$errormessage.=$lang['wog_act_chara_errdata'];
		}
		if(empty($_POST["id"]) || empty($_POST["pass"])  || empty($_POST["sex"]) || empty($_POST["s"]) || empty($_POST["email"]) )
		{
			$errormessage.=$lang['wog_act_chara_errdata'];
		}
		if (preg_match("/[<>'\", ;]/", $_POST["id"]) || preg_match("/[<>'\", ]/", $_POST["pass"]) || preg_match("/[<>'\", ;]/", $_POST["sat_name"]))
		{
			$errormessage.=$lang['wog_act_errword'];
		}
		if((int)$_POST["str"]<=0 || (int)$_POST["smart"]<=0 || (int)$_POST["agi"]<=0 || (int)$_POST["life"]<=0 )
		{
			$errormessage.=$lang['wog_act_chara_errpoint'];
		}
		if( ((int)$_POST["str"]+(int)$_POST["smart"]+(int)$_POST["agi"]+(int)$_POST["life"]) > $wog_arry["total_point"] )
		{
			$errormessage.=$lang['wog_act_chara_fulpoint'].$wog_arry["total_point"].",";
		}
		if((int)$_POST["ch"] > 9)
		{
			$errormessage.=$lang['wog_act_chara_errjob'];
		}
		if((int)$_POST["birth"] > 5)
		{
			$errormessage.=$lang['wog_act_chara_errdata'];
		}

		if($p=$DB_site->query_first("select p_id from wog_player where p_name='".trim($_POST["id"])."'"))
		{
			$errormessage.=$lang['wog_act_chara_usedid'];
		}
		
		if(empty($forum_message))
		{
			if($p=$DB_site->query_first("select count(p_id) as p_id from wog_player where p_email='".trim($_POST["email"])."'"))
			{
				if($wog_arry["player_num"] <= $p[0])
				{
					$errormessage.=$lang['wog_act_chara_fulnum'];
				}
			}
		}else
		{
			if($p=$DB_site->query_first("select count(p_bbsid) as p_bbsid from wog_player where p_bbsid=".$bbs_id))
			{
				if($wog_arry["player_num"] <= $p[0])
				{
					$errormessage.=$lang['wog_act_chara_fulnum'];
				}
			}
		}
		$time=time();
		if(!empty($errormessage))
		{
			alertWindowMsg($errormessage);
		}
		unset($errormessage);
		if(empty($_POST["recommid"])){$_POST["recommid"]=0;}
		$user_id=$this->chara_save($_POST["recommid"]);
/*
		$code=$this->creat_code();
		$sql="insert wog_player_confirm(p_id,code,datetime)values($user_id,'".$code."',$time)";
		$DB_site->query($sql);
		$this->send_code($email,$code);
		showscript("parent.check_creat(1)");
*/
		//$time=mktime(0,0,0,12,31,2020);
		$p_name=$_POST["id"];
		setcookie("wog_cookie",$user_id);
//		setcookie("wog_key",$key);
		setcookie("wog_cookie_name",$p_name);
//		setcookie("wog_bbs_id",$bbs_id);
		setcookie("wog_cookie_group","0");
		setcookie("wog_cookie_team","0");
		setcookie("wog_cookie_debug",md5($user_id.$wog_arry[cookie_debug]));
		setcookie("wog_chat_cookie_debug",md5($p_name.$wog_arry[cookie_debug]));
		//setcookie("wog_cookie_ver","4");
		$this->show_chara($user_id,1);
		showscript("parent.open_chat(null);parent.peolist.document.location='wog_etc.php?f=peo';parent.mercenary_set=0;parent.mercenary_time=".$wog_arry["f_time_mercenary"]."000");
	}
	function sns_save()
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		$errormessage="";
		if(!isset($_POST["id"]) || !isset($_POST["sex"]) || !isset($_POST["user_id"]) || !isset($_POST["s"]) || !isset($_POST["birth"]))
		{
			$errormessage.=$lang['wog_act_chara_errdata'];
		}
		if(empty($_POST["id"]) || empty($_POST["sex"]) || empty($_POST["s"]) || empty($_POST["user_id"]))
		{
			$errormessage.=$lang['wog_act_chara_errdata'];
		}
		if (preg_match("/[<>'\", ;]/", $_POST["id"]) || preg_match("/[<>'\", ]/", $_POST["pass"]) || preg_match("/[<>'\", ;]/", $_POST["sat_name"]))
		{
			$errormessage.=$lang['wog_act_errword'];
		}
		if((int)$_POST["str"]<=0 || (int)$_POST["smart"]<=0 || (int)$_POST["agi"]<=0 || (int)$_POST["life"]<=0 )
		{
			$errormessage.=$lang['wog_act_chara_errpoint'];
		}
		if( ((int)$_POST["str"]+(int)$_POST["smart"]+(int)$_POST["agi"]+(int)$_POST["life"]) > $wog_arry["total_point"] )
		{
			$errormessage.=$lang['wog_act_chara_fulpoint'].$wog_arry["total_point"].",";
		}
		if((int)$_POST["ch"] > 9)
		{
			$errormessage.=$lang['wog_act_chara_errjob'];
		}
		if((int)$_POST["birth"] > 5)
		{
			$errormessage.=$lang['wog_act_chara_errdata'];
		}
		if($p=$DB_site->query_first("select p_id from wog_player where p_name='".trim($_POST["id"])."'"))
		{
			$errormessage.=$lang['wog_act_chara_usedid'];
		}
		$time=time();
		if(!empty($errormessage))
		{
			alertWindowMsg_sns($errormessage);
		}
		unset($errormessage);
		$time=time();
		$user_id=$this->chara_save();
		$p_name=$_POST["id"];
		$sns_id=$_POST["user_id"];
		$type=$_POST["type"];
		
		$sql="select id from wog_sns_player where sns_id='".$sns_id."'";
		$p=$DB_site->query_first($sql);
		if($p)
		{
			$sql="update wog_sns_player set p_id=".$user_id.",p_name='".$p_name."' where sns_id='".$sns_id."'";
		}else
		{
			$sql="insert wog_sns_player (sns_id,p_id,p_name,type,datetime)values('".$sns_id."',$user_id,'".$p_name."',$type,$time)";
		}
		$DB_site->query($sql);
		setcookie("wog_cookie",$user_id);
		setcookie("wog_cookie_name",$p_name);
		setcookie("wog_cookie_group","0");
		setcookie("wog_cookie_team","0");
		setcookie("wog_cookie_debug",md5($user_id.$wog_arry[cookie_debug]));
		setcookie("wog_chat_cookie_debug",md5($p_name.$wog_arry[cookie_debug]));
		$this->sns_login($user_id,$p_name);
	}
	function sns_link()
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		if(empty($_POST["oid"]) || empty($_POST["pass"]) || empty($_POST["user_id"]))
		{
			alertWindowMsg_sns($lang['wog_act_chara_err2']);
		}
		$id=trim($_POST["oid"]);
		$password=$_POST["pass"];
		$sns_id=$_POST["user_id"];
		$type=$_POST["type"];
		
		$sql="select p_id from wog_player where p_name='".$id."' and p_password='".$password."' and p_npc=0";
		$p2=$DB_site->query_first($sql);
		if(!$p2)
		{
			alertWindowMsg_sns($lang['wog_act_chara_err3']);
		}
		$sql="select id from wog_sns_player where p_id=".$p2[p_id];
		$p=$DB_site->query_first($sql);
		if($p)
		{
			alertWindowMsg_sns($lang['wog_act_chara_err4']);
		}
		$time=time();
		$sql="select id from wog_sns_player where sns_id='".$sns_id."'";
		$p=$DB_site->query_first($sql);
		if($p)
		{
			$sql="update wog_sns_player set p_id=".$p2[p_id].",p_name='".$id."' where sns_id='".$sns_id."'";
		}else
		{
			$sql="insert wog_sns_player (sns_id,p_id,p_name,type,datetime)values('".$sns_id."',$p2[p_id],'".$id."',$type,$time)";
		}
		$DB_site->query($sql);
		setcookie("wog_cookie",$p2[p_id]);
		setcookie("wog_cookie_name",$id);
		setcookie("wog_cookie_group","0");
		setcookie("wog_cookie_team","0");
		setcookie("wog_cookie_debug",md5($p2[p_id].$wog_arry[cookie_debug]));
		setcookie("wog_chat_cookie_debug",md5($id.$wog_arry[cookie_debug]));
		unset($p);
		$this->sns_login($p2[p_id],$id);
	}
	function sns_login($user_id,$p_name)
	{
		global $DB_site;
		$sql="select i_img,p_img_url,p_img_set from wog_player where p_id=".$user_id;
		$p=$DB_site->query_first($sql);
		if($p["p_img_set"]==1)
		{
			$p["i_img"]=$p["p_img_url"];
		}else
		{
			$p["i_img"]="/ex_img/images/wog/img/".$p["i_img"].".gif";
		}
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
		echo '<html>';
		echo '<head>';
		echo '<META NAME="keywords" CONTENT="遊戲,線上遊戲,水色論壇,ffa,wog,online game,web game,網頁遊戲,webgame">';
		echo '<META NAME="description" CONTENT="水色論壇,線上遊戲-WOG 論壇,水色論壇,Web Game,WebGame">';
		echo '<META NAME="author" CONTENT="ETERNAL">';
		echo '<META NAME="copyright" CONTENT="Copyright (C) ETERNAL">';
		echo '<title>網頁遊戲WebGame,FFA線上遊戲-Online FF Battle - WOG V4 Copyright (C) ETERNAL</title>';
		echo '<link href="./css/wog.css" rel="stylesheet" type=text/css>';
		echo '<meta content="text/html; charset=utf-8" http-equiv=content-type>';
		echo '<html>';
		echo '<body bgcolor="#000000" text="#EFEFEF" link="#EFEFEF" vlink="#EFEFEF" alink="#EFEFEF">';
		
		echo '<form action="./index.htm" method="post" target="_blank">';
		echo '<table align="center">';
		echo '<tr><td align="center">玩家暱稱：'.$p_name.'</td></tr>';
		echo '<tr><td align="center"><img border="0" src="'.$p["i_img"].'"></td></tr>';
		echo '<tr><td align="center"><input type="submit" value="進入遊戲" ></td></tr>';
		echo '</table>';
		echo '<input type="hidden" name="f" value="chara">';
		echo '<input type="hidden" name="act" value="sns_save">';
		echo '<input type="hidden" name="user_id" value="'.$user_id.'">';
		echo '<input type="hidden" name="type" value="'.$type.'">';
		echo '</form>';
		echo '</body>';
		echo '</html>';
	}
	function login($p_ip,$nopass=0,$user_id=null)
	{
		global $DB_site,$_POST,$wog_arry,$lang,$forum_message;
		if($nopass==0)
		{
			if (preg_match("/[<>'\", ]/", $_POST["id"]) || preg_match("/[<>'\", ]/", $_POST["pass"]))
			{
				alertWindowMsg($lang['wog_act_errword']);
			}
			if(empty($_POST["id"]) || empty($_POST["pass"]))
			{
				alertWindowMsg($lang['wog_act_chara_err2']);
			}
			$user_id=$_POST["id"];
		}
		$time=time();
		$p=$DB_site->query("select p_id from wog_player where p_online_time < ".($time-$wog_arry["del_day"])." and p_st <= 0 and p_npc=0"); //刪除角色
		//$p=$DB_site->query("select p_id from wog_player where p_npc=0"); //刪除角色
		while($ps=$DB_site->fetch_array($p))
		{
			$this->kill_sub($ps["p_id"]);
		}
		//刪除驗證 begin
		$p=$DB_site->query("select p_id from wog_player_confirm where datetime < ".($time-$wog_arry["del_confirm"]));
		while($ps=$DB_site->fetch_array($p))
		{
			$this->kill_sub($ps["p_id"]);
		}
		$sql="delete from wog_player_confirm where datetime < ".($time-$wog_arry["del_confirm"]);
		$DB_site->query($sql);
		//刪除驗證 end
		$p=$DB_site->query("select p_id,hero_type,hero_npc from wog_player_cp where hero_time > 0 and hero_time <= $time");
		while($ps=$DB_site->fetch_array($p))
		{
			$p_place=rand(1,20);
			$sql="update wog_player set p_place=".$p_place.",p_pk_s=1 where p_id=".$ps["hero_npc"];
			$DB_site->query($sql); //重置npc出現地點
		}
		$sql="update wog_player_cp set p_id=hero_npc,hero_time=0 where hero_time <= $time";
		$DB_site->query($sql); //重置英雄榜npc
		if($nopass==0)
		{
			$sql="select p_id,p_name,p_lock,p_st,p_lock_time,p_g_id,t_id from wog_player where p_name='".$user_id."' and p_password='".$_POST["pass"]."' and p_npc=0";
		}else
		{
			$sql="select p_id,p_name,p_lock,p_st,p_lock_time,p_g_id,t_id from wog_player where p_id=".$user_id." and p_npc=0";
		}
		$p=$DB_site->query_first($sql);
		if($p)
		{
			if($p[p_lock]==1)
			{
				$lock_time=$p[p_lock_time]+($wog_arry["unfreeze"]*60*60);
				if($lock_time>$time)
				{
					alertWindowMsg($lang['wog_act_chara_nologin']);
				}else
				{
					$sql="update wog_player set p_lock=0,p_attempts=0 where p_id=".$p[p_id];
					$DB_site->query($sql);
				}
			}
			if($p[p_lock]==2)
			{
				alertWindowMsg($lang['wog_act_chara_msg2']);
			}
			
/*
			if(!empty($forum_message))
			{
				if($p[p_bbsid]!=$bbs_id)
				{
					alertWindowMsg($lang['wog_act_chara_error_creatid']);
				}
				$datecut = $time - $wog_arry["offline_time"];
				$check_time=$DB_site->query_first("select p_online_time from wog_player where p_bbsid=".$bbs_id." and p_online_time > $datecut and p_id<>".$p[p_id]);
				if($check_time)
				{
					//alertWindowMsg($lang['wog_act_chara_sameid']);
				}
			}
*/
			if($p[p_st]==0)
			{
				$datecut = $time - $wog_arry["offline_time"];
				$online=$DB_site->query_first("select count(p_name) as num from wog_player where p_online_time > $datecut");
				if($online[num]>=$wog_arry["online_limit"])
				{
					showscript("parent.incd(".$wog_arry["login_time"].")");
				}
			}
			$m_item=$DB_site->query_first("select a.m_id from wog_mission_main a,wog_mission_book b where b.p_id=".$p[p_id]." and b.m_status=0 and a.m_id=b.m_id LIMIT 1");
			//$time=mktime(0,0,0,12,31,2020);
			$this->show_chara($p[p_id],1);
			setcookie("wog_cookie",$p[p_id]);
			setcookie("wog_cookie_name",$p[p_name]);
			//setcookie("wog_bbs_id",$bbs_id);
			setcookie("wog_cookie_group",$p["p_g_id"]);
			setcookie("wog_cookie_team",$p["t_id"]);
			setcookie("wog_cookie_debug",md5($p[p_id].$wog_arry[cookie_debug]));
			setcookie("wog_chat_cookie_debug",md5($p[p_name].$wog_arry[cookie_debug]));
			setcookie("wog_cookie_mission_id",$m_item["m_id"]);
			//setcookie("wog_cookie_ver","4");
			$p_hero=$DB_site->query_first("select p_id from wog_player_cp where p_id=".$p[p_id]);
			if($p_hero){setcookie("wog_cookie_hero",1);}else{setcookie("wog_cookie_hero",0);}
			$sql="update wog_player set p_ipadd='".$p_ip."' where p_id=".$p[p_id];
			$DB_site->query($sql);
			$sql="select me_status from wog_mercenary_list where p_id=".$p[p_id];
			$p=$DB_site->query_first($sql);
			$me_status=0;
			if($p)
			{
				if($p[me_status]==1)
				{
					$me_status=1;
				}
			}
			showscript("parent.open_chat(null);parent.peolist.document.location='wog_etc.php?f=peo';parent.mercenary_set=$me_status;parent.mercenary_time=".$wog_arry["f_time_mercenary"]."000;parent.mercenary_f();");
		}else
		{
			alertWindowMsg($lang['wog_act_chara_errlogin']);
		}
		unset($p);
	}
/*
	function revive($user_id)
	{
		global $DB_site,$wog_arry,$lang;
		$DB_site->query("update wog_player set hp=hpmax,p_exp=p_exp*0.8 where p_id=".$user_id);
		showscript("parent.job_end(19)");
	}
*/
	function logout($user_id)
	{
		global $DB_site,$wog_arry,$lang;
		$DB_site->query("update wog_player set p_online_time=".(time() - $wog_arry["offline_time"])." where p_id=".$user_id);
		cookie_clean();
		showscript("top.location.href='".$wog_arry["logout_url"]."';");
	}

	function show_chara($user_id,$s)
	{
		global $DB_site,$lang,$wog_arry;
		if(empty($user_id))
		{
			alertWindowMsg($lang['wog_act_noid']."---");
		}
		check_buffer($user_id);
		if($s<3)
		{
			$sql="select w.p_name,w.at,w.df,w.mat
			,w.mdf,w.s_property,w.str,w.life,w.smart,w.agi
			,w.au,w.be,w.p_place,w.vit,w.p_birth,w.p_img_set,w.p_img_url
			,w.hp,w.hpmax,w.p_money,w.p_lv,w.p_exp,w.p_cdate
			,w.p_nextexp,w.p_win,w.p_lost
			,w.p_sex,w.i_img,w.p_sat_name,wog_character.ch_name,g.g_name,g.g_id,g.g_img
			,h.a_id,h.d_body_id,h.d_head_id,h.d_hand_id,h.d_foot_id,h.d_item_id,h.d_item_num,h.d_item_id2,h.d_item_num2
			,h.s_a_id,h.s_body_id,h.s_head_id,h.s_hand_id,h.s_foot_id
			,h.p_a_id,h.p_body_id,h.p_head_id,h.p_hand_id,h.p_foot_id
			,w.base_str,w.base_life,w.base_smart,w.base_agi,w.base_au,w.base_be,w.base_vit
			,w.sp,w.spmax,w.act_num,w.act_num_time
			from wog_player w left join wog_character on w.ch_id=wog_character.ch_id
			left join wog_group_main g on w.p_g_id=g.g_id
			,wog_player_arm h
			where w.p_id=".$user_id." and h.p_id=w.p_id and w.p_npc=0";
		}else
		{
			$sql="select w.p_name,w.at,w.df,w.mat
			,w.mdf,w.s_property,w.str,w.life,w.smart,w.agi
			,w.au,w.be,w.p_place,w.vit,w.p_img_set,w.p_img_url
			,w.hp,w.hpmax,w.p_money,w.p_lv,w.p_exp
			,w.p_nextexp,w.p_win,w.p_lost
			,w.p_sex,w.i_img,w.p_sat_name,g.g_name,g.g_id,g.g_img
			,h.d_item_id,h.d_item_num,h.d_item_id2,h.d_item_num2
			,w.base_str,w.base_life,w.base_smart,w.base_agi,w.base_au,w.base_be,w.base_vit
			,w.sp,w.spmax,w.act_num,w.act_num_time
			from wog_player w left join wog_group_main g on w.p_g_id=g.g_id
			,wog_player_arm h
			where w.p_id=".$user_id." and h.p_id=w.p_id";
		}
		$p=$DB_site->query_first($sql);
		if(!$p){alertWindowMsg($lang['wog_act_noid']);}
		echo charset();
		$script="";
		if(!empty($p["g_img"]) && $s==1){$script.="parent.g_img='<img src=".$p["g_img"].">';\n";}
		if($s==1){$script.="parent.p_group='".$p["g_name"]."';\n";}
		if($s==2){$script.="parent.etc_group='".$p["g_name"]."';\n";}
		$p[p_cdate]=player_age($p[p_cdate],$wog_arry["player_age"]);
		if($p[p_img_set]==1)
		{
			$p[i_img]=$p[p_img_url];
		}
		$time=time();
		$temp_time=($time-$p[act_num_time])/$wog_arry["act_num_time"];
		if($temp_time >= 1)
		{
			$p[act_num]+=$wog_arry["act_num"];
			if($temp_time >= 2)
			{
				$p[act_num]+=$wog_arry["act_num"];
			}
			if($p[act_num]>50){$p[act_num]=50;}
			$sql="update wog_player set act_num=".$p[act_num].",act_num_time=".$time." where p_id=".$user_id;
			$DB_site->query($sql);
		}
		$etc_str=$p[str]-$p[base_str];
		$etc_smart=$p[smart]-$p[base_smart];
		$etc_agi=$p[agi]-$p[base_agi];
		$etc_life=$p[life]-$p[base_life];
		$etc_vit=$p[vit]-$p[base_vit];
		$etc_au=$p[au]-$p[base_au];
		$etc_be=$p[be]-$p[base_be];
		if($s==1)
		{
			$plus_item=array();
			$sql="select ps_id,plus_num from wog_plus_setup where ps_id in(".$p[p_a_id].",".$p[p_body_id].",".$p[p_head_id].",".$p[p_hand_id].",".$p[p_foot_id].")";
			$plus=$DB_site->query($sql);
			while($pluss=$DB_site->fetch_array($plus))
			{
				$plus_item[$pluss[ps_id]]=$pluss[plus_num];
			}
			$DB_site->free_result($plus);
			unset($pluss);			
			$sql="select d_id,d_name from wog_df where d_id in (".$p[a_id].",".$p[d_body_id].",".$p[d_head_id].",".$p[d_hand_id].",".$p[d_foot_id].",".$p[d_item_id].",".$p[d_item_id2].")";
			$p_item=$DB_site->query($sql);
			$a_name="";$body_name="";$head_name="";$hand_name="";$foot_name="";$item_name="";$item_name2="";
			while($p_items=$DB_site->fetch_array($p_item))
			{
				if($p[a_id] == $p_items[d_id])
				{
					if(!empty($p[s_a_id])){$p_items[d_id].=":".$p[s_a_id];}
					if(!empty($p[p_a_id])){
						$p_items[d_id].="&".$p[p_a_id];
						$p_items[d_name].="+".$plus_item[$p[p_a_id]];
					}
					$a_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_body_id] == $p_items[d_id])
				{
					if(!empty($p[s_body_id])){$p_items[d_id].=":".$p[s_body_id];}
					if(!empty($p[p_body_id])){
						$p_items[d_id].="&".$p[p_body_id];
						$p_items[d_name].="+".$plus_item[$p[p_body_id]];
					}
					$body_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_head_id] == $p_items[d_id])
				{
					if(!empty($p[s_head_id])){$p_items[d_id].=":".$p[s_head_id];}
					if(!empty($p[p_head_id])){
						$p_items[d_id].="&".$p[p_head_id];
						$p_items[d_name].="+".$plus_item[$p[p_head_id]];
					}
					$head_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_hand_id] == $p_items[d_id])
				{
					if(!empty($p[s_hand_id])){$p_items[d_id].=":".$p[s_hand_id];}
					if(!empty($p[p_hand_id])){
						$p_items[d_id].="&".$p[p_hand_id];
						$p_items[d_name].="+".$plus_item[$p[p_hand_id]];
					}
					$hand_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_foot_id] == $p_items[d_id])
				{
					if(!empty($p[s_foot_id])){$p_items[d_id].=":".$p[s_foot_id];}
					if(!empty($p[p_foot_id])){
						$p_items[d_id].="&".$p[p_foot_id];
						$p_items[d_name].="+".$plus_item[$p[p_foot_id]];
					}
					$foot_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_item_id] == $p_items[d_id])
				{
					$p_items[d_name].="*".$p[d_item_num];
					$item_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_item_id2] == $p_items[d_id])
				{
					$p_items[d_name].="*".$p[d_item_num2];
					$item_name2 = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
			}
			$DB_site->free_result($p_item);
			unset($p_items);
			showscript_continue($script."parent.login_view($p[p_win],$p[p_lost],$p[p_img_set],'$p[i_img]','$p[p_name]','$p[p_sex]','$p[ch_name]','$p[s_property]','$p[p_lv]','$p[p_exp]','$p[p_nextexp]','$p[p_money]','$p[hp]','$p[hpmax]','$p[base_str]','$p[base_smart]','$p[base_agi]','$p[base_life]','$p[base_vit]','$p[base_au]','$p[base_be]','$p[at]','$p[mat]','$p[df]','$p[mdf]','$a_name','$body_name','$head_name','$hand_name','$foot_name','$item_name','$item_name2','$p[p_sat_name]',$p[p_place],$p[p_birth],$p[p_cdate],$p[sp],$p[spmax],$etc_str,$etc_smart,$etc_agi,$etc_vit,$etc_life,$etc_au,$etc_be,$p[act_num]);\n");
		}elseif($s==2)
		{
			$plus_item=array();
			$sql="select ps_id,plus_num from wog_plus_setup where ps_id in(".$p[p_a_id].",".$p[p_body_id].",".$p[p_head_id].",".$p[p_hand_id].",".$p[p_foot_id].")";
			$plus=$DB_site->query($sql);
			while($pluss=$DB_site->fetch_array($plus))
			{
				$plus_item[$pluss[ps_id]]=$pluss[plus_num];
			}
			$DB_site->free_result($plus);
			unset($pluss);	
			$sql="select d_id,d_name from wog_df where d_id in (".$p[a_id].",".$p[d_body_id].",".$p[d_head_id].",".$p[d_hand_id].",".$p[d_foot_id].",".$p[d_item_id].",".$p[d_item_id2].")";
			$p_item=$DB_site->query($sql);
			$a_name="";$body_name="";$head_name="";$hand_name="";$foot_name="";$item_name="";$item_name2="";
			while($p_items=$DB_site->fetch_array($p_item))
			{
				if($p[a_id] == $p_items[d_id])
				{
					if(!empty($p[s_a_id])){$p_items[d_id].=":".$p[s_a_id];}
					if(!empty($p[p_a_id])){
						$p_items[d_id].="&".$p[p_a_id];
						$p_items[d_name].="+".$plus_item[$p[p_a_id]];
					}
					$a_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_body_id] == $p_items[d_id])
				{
					if(!empty($p[s_body_id])){$p_items[d_id].=":".$p[s_body_id];}
					if(!empty($p[p_body_id])){
						$p_items[d_id].="&".$p[p_body_id];
						$p_items[d_name].="+".$plus_item[$p[p_body_id]];
					}
					$body_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_head_id] == $p_items[d_id])
				{
					if(!empty($p[s_head_id])){$p_items[d_id].=":".$p[s_head_id];}
					if(!empty($p[p_head_id])){
						$p_items[d_id].="&".$p[p_head_id];
						$p_items[d_name].="+".$plus_item[$p[p_head_id]];
					}
					$head_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_hand_id] == $p_items[d_id])
				{
					if(!empty($p[s_hand_id])){$p_items[d_id].=":".$p[s_hand_id];}
					if(!empty($p[p_hand_id])){
						$p_items[d_id].="&".$p[p_hand_id];
						$p_items[d_name].="+".$plus_item[$p[p_hand_id]];
					}
					$hand_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_foot_id] == $p_items[d_id])
				{
					if(!empty($p[s_foot_id])){$p_items[d_id].=":".$p[s_foot_id];}
					if(!empty($p[p_foot_id])){
						$p_items[d_id].="&".$p[p_foot_id];
						$p_items[d_name].="+".$plus_item[$p[p_foot_id]];
					}
					$foot_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_item_id] == $p_items[d_id])
				{
					$p_items[d_name].="*".$p[d_item_num];
					$item_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
				if($p[d_item_id2] == $p_items[d_id])
				{
					$p_items[d_name].="*".$p[d_item_num2];
					$item_name2 = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
				}
			}
			$DB_site->free_result($p_item);
			unset($p_items);
			showscript_continue($script."parent.cp_view($p[p_win],$p[p_lost],$p[p_img_set],'$p[i_img]','$p[p_name]','$p[p_sex]','$p[ch_name]','$p[s_property]','$p[p_lv]','$p[p_exp]','$p[p_nextexp]','$p[p_money]','$p[hp]','$p[hpmax]','$p[base_str]','$p[base_smart]','$p[base_agi]','$p[base_life]','$p[base_vit]','$p[base_au]','$p[base_be]','$p[at]','$p[mat]','$p[df]','$p[mdf]','$a_name','$body_name','$head_name','$hand_name','$foot_name','$item_name','$item_name2',$p[p_place],$p[p_birth],$p[p_cdate],0,$p[sp],$p[spmax],$etc_str,$etc_smart,$etc_agi,$etc_vit,$etc_life,$etc_au,$etc_be,$p[act_num]);\n");
		}elseif($s==3)
		{
			$sql="select d_id,d_name from wog_df where d_id in (".$p[d_item_id2].")";
			$p_item=$DB_site->query_first($sql);
			if($p_item)
			{
				echo "parent.arm_setup('d_item_id2','".$p_item[d_name]."*".$p[d_item_num2]."','".$p_item[d_id]."');\n";
			}
			showscript_continue($script."parent.show_status($p[p_win],$p[p_lost],$p[p_img_set],'$p[i_img]','$p[p_sex]','$p[s_property]','$p[p_lv]','$p[p_exp]','$p[p_nextexp]','$p[p_money]','$p[hp]','$p[hpmax]','$p[base_str]','$p[base_smart]','$p[base_agi]','$p[base_life]','$p[base_vit]','$p[base_au]','$p[base_be]','$p[at]','$p[mat]','$p[df]','$p[mdf]',$p[p_place],$p[sp],$p[spmax],$etc_str,$etc_smart,$etc_agi,$etc_vit,$etc_life,$etc_au,$etc_be,$p[act_num]);\n");
		}
		unset($p);
	}
	function start_tips($user_id,$p=null)
	{
		global $DB_site,$lang;
		$s1="";
		$s="";
		$pt="0";
		if($p==null)
		{
			$sql="select p_lv,ch_id,p_g_id,p_birth,p_sex from wog_player where p_id=".$user_id;
			$p=$DB_site->query_first($sql);
		}
		$sql="select a.m_id,a.m_subject,a.m_name from wog_mission_main a LEFT JOIN wog_mission_book b ON  b.p_id=".$user_id." and a.m_id=b.m_id
			LEFT JOIN wog_mission_book c ON  c.p_id=".$user_id." and a.m_need_id=c.m_id and a.m_not_id<>c.m_id
			LEFT JOIN wog_mission_repeat d ON  d.m_id=a.m_id
			where  (a.m_job=".$p["ch_id"]." or a.m_job=99) and a.m_lv <= ".$p["p_lv"]." and (a.m_sex=".$p["p_sex"]." or a.m_sex=3)
			and (a.m_birth is null or a.m_birth = ".$p["p_birth"].")
			and ((b.m_id is null and a.m_need_id=0) or ((c.m_status=2 or c.m_status=3)  and b.m_id is null) ) ORDER BY RAND() LIMIT 5";
		$m=$DB_site->query($sql);
		while($ms=$DB_site->fetch_array($m))
		{
			$s.=";".$ms["m_id"].",".$ms["m_name"].",".$ms["m_subject"];
		}
		$DB_site->free_result($m);
		if($p[p_g_id]>0)
		{
			$sql="select p_point from wog_group_member_point where p_id=".$user_id." and g_id=".$p[p_g_id];
			$point=$DB_site->query_first($sql);
			$pt=$point[p_point];
			$sql="select a.d_name,a.d_id from wog_df a,wog_group_depot b where b.g_id=".$p[p_g_id]." and b.d_point>0 and b.d_point <=".$point[p_point]." and b.d_id=a.d_id ORDER BY RAND() LIMIT 6";
			$d=$DB_site->query($sql);
			while($ds=$DB_site->fetch_array($d))
			{
				$s1.=";".$ds["d_id"].",".$ds["d_name"];
			}
			$DB_site->free_result($d);
			if(!empty($s1))
			{
				$s1=substr($s1,1);
			}
		}
		$sql="select area_name from wog_area_lv where min_lv <= ".$p["p_lv"]." and max_lv >= ".$p["p_lv"]." order by rand() limit 1";
		$p=$DB_site->query_first($sql);
		//$sql="select b.d_name,a.d_text from wog_df_vip a,wog_df b where a.d_id=b.d_id and b.d_vip=1 order by rand() LIMIT 1";
		//$d=$DB_site->query_first($sql);
		unset($m);
		$s=substr($s,1);
		showscript("parent.start_tips('".$s."',".$pt.",'".$s1."','".$p[area_name]."','')");
		unset($d,$p);
	}
	function cp_view()
	{
		global $DB_site,$lang,$wog_arry;
		$p=$DB_site->query_first("select cp.p_name,cp.at,cp.df,cp.mat
		,cp.mdf,cp.s_property,cp.p_birth,cp.p_cdate
		,cp.str,cp.life,cp.vit,cp.smart,cp.agi,cp.au,cp.be
		,cp.hp,cp.hpmax,cp.p_money,cp.p_lv,cp.p_exp,cp.p_img_set,cp.p_img_url
		,cp.p_nextexp,cp.p_win,cp.p_lost,cp.p_sex,cp.i_img,cp.p_win_total,cp.p_place
		,wog_character.ch_name,g.g_name
		,h.a_id,h.d_body_id,h.d_head_id,h.d_hand_id,h.d_foot_id,h.d_item_id,h.d_item_num,h.d_item_id2,h.d_item_num2
		,h.s_a_id,h.s_body_id,h.s_head_id,h.s_hand_id,h.s_foot_id
		,h.p_a_id,h.p_body_id,h.p_head_id,h.p_hand_id,h.p_foot_id
		,cp.base_str,cp.base_life,cp.base_smart,cp.base_agi,cp.base_au,cp.base_be,cp.base_vit
		,cp.sp,cp.spmax
		from wog_cp cp left join wog_character on cp.ch_id=wog_character.ch_id
		left join wog_group_main g on cp.p_g_id=g.g_id
		LEFT JOIN wog_player_arm h on h.p_id=cp.p_pid
		LIMIT 1 ");
		if($p[p_img_set]==1)
		{
			$p[i_img]=$p[p_img_url];
		}
		$plus_item=array();
		$sql="select ps_id,plus_num from wog_plus_setup where ps_id in(".$p[p_a_id].",".$p[p_body_id].",".$p[p_head_id].",".$p[p_hand_id].",".$p[p_foot_id].")";
		$plus=$DB_site->query($sql);
		while($pluss=$DB_site->fetch_array($plus))
		{
			$plus_item[$pluss[ps_id]]=$pluss[plus_num];
		}
		$DB_site->free_result($plus);
		unset($pluss);
		$sql="select d_id,d_name from wog_df where d_id in (".$p[a_id].",".$p[d_body_id].",".$p[d_head_id].",".$p[d_hand_id].",".$p[d_foot_id].",".$p[d_item_id].$p[d_item_id2].")";
		$p_item=$DB_site->query($sql);
		$a_name="";$body_name="";$head_name="";$hand_name="";$foot_name="";$item_name="";$item_name2="";
		while($p_items=$DB_site->fetch_array($p_item))
		{
			if($p[a_id] == $p_items[d_id])
			{
				if(!empty($p[s_a_id])){$p_items[d_id].=":".$p[s_a_id];}
				if(!empty($p[p_a_id])){
					$p_items[d_id].="&".$p[p_a_id];
					$p_items[d_name].="+".$plus_item[$p[p_a_id]];
				}
				$a_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
			}
			if($p[d_body_id] == $p_items[d_id])
			{
				if(!empty($p[s_body_id])){$p_items[d_id].=":".$p[s_body_id];}
				if(!empty($p[p_body_id])){
					$p_items[d_id].="&".$p[p_body_id];
					$p_items[d_name].="+".$plus_item[$p[p_body_id]];
				}
				$body_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
			}
			if($p[d_head_id] == $p_items[d_id])
			{
				if(!empty($p[s_head_id])){$p_items[d_id].=":".$p[s_head_id];}
				if(!empty($p[p_head_id])){
					$p_items[d_id].="&".$p[p_head_id];
					$p_items[d_name].="+".$plus_item[$p[p_head_id]];
				}
				$head_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
			}
			if($p[d_hand_id] == $p_items[d_id])
			{
				if(!empty($p[s_hand_id])){$p_items[d_id].=":".$p[s_hand_id];}
				if(!empty($p[p_hand_id])){
					$p_items[d_id].="&".$p[p_hand_id];
					$p_items[d_name].="+".$plus_item[$p[p_hand_id]];
				}
				$hand_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
			}
			if($p[d_foot_id] == $p_items[d_id])
			{
				if(!empty($p[s_foot_id])){$p_items[d_id].=":".$p[s_foot_id];}
				if(!empty($p[p_foot_id])){
					$p_items[d_id].="&".$p[p_foot_id];
					$p_items[d_name].="+".$plus_item[$p[p_foot_id]];
				}
				$foot_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
			}
			if($p[d_item_id] == $p_items[d_id])
			{
				$p_items[d_name].="*".$p[d_item_num];
				$item_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
			}
			if($p[d_item_id2] == $p_items[d_id])
			{
				$p_items[d_name].="*".$p[d_item_num2];
				$item_name = '<a class=uline onclick=parent.arm_show(event.ctrlKey,"'.$p_items[d_name].'","'.$p_items[d_id].'") >'.$p_items[d_name].'</a>';
			}
		}
		$DB_site->free_result($p_item);
		unset($p_items);
		$etc_str=$p[str]-$p[base_str];
		$etc_smart=$p[smart]-$p[base_smart];
		$etc_agi=$p[agi]-$p[base_agi];
		$etc_life=$p[life]-$p[base_life];
		$etc_vit=$p[vit]-$p[base_vit];
		$etc_au=$p[au]-$p[base_au];
		$etc_be=$p[be]-$p[base_be];
		$p[p_cdate]=player_age($p[p_cdate],$wog_arry["player_age"]);
		showscript("parent.etc_group='".$p["g_name"]."';parent.cp_view($p[p_win],$p[p_lost],$p[p_img_set],'$p[i_img]','$p[p_name]','$p[p_sex]','$p[ch_name]','$p[s_property]','$p[p_lv]','$p[p_exp]','$p[p_nextexp]','$p[p_money]','$p[hp]','$p[hpmax]','$p[base_str]','$p[base_smart]','$p[base_agi]','$p[base_life]','$p[base_vit]','$p[base_au]','$p[base_be]','$p[at]','$p[mat]','$p[df]','$p[mdf]','$a_name','$body_name','$head_name','$hand_name','$foot_name','$item_name','$item_name2',$p[p_place],$p[p_birth],$p[p_cdate],'$p[p_win_total]',$p[sp],$p[spmax],$etc_str,$etc_smart,$etc_agi,$etc_vit,$etc_life,$etc_au,$etc_be,'---')");
		unset($p);
	}
	function kill($user_id)
	{
		global $DB_site,$_POST,$lang;
		$sql="select p_id from wog_player where p_name='".trim($_POST["id"])."' and p_password='".$_POST["password"]."' and p_npc=0";
		$p=$DB_site->query_first($sql);
		if($p)
		{
			/*
			if($p["p_id"]!=$user_id)
			{
				alertWindowMsg($lang['wog_act_kill']);
			}
			*/
			$this->kill_sub($p["p_id"]);
			//cookie_clean();
			showscript("parent.job_end(1)");
		}else
		{
			alertWindowMsg($lang['wog_act_kill']);
		}
		unset($p);
	}
	function lock()
	{
		global $DB_site,$_POST,$lang,$wog_arry;
		$id=trim($_GET["id"]);
		$sql="update wog_player set p_lock=1,p_lock_time=".time()." where p_name='".$id."'";
		$DB_site->query($sql);
		require_once($wog_arry["chat_path"]."/class/chat_class.php");
		$chat_class = new chat_class;
		$chat_class->file_path=$wog_arry["chat_path"]."/data/";
		$chat_class->set_block($id);
		unset($chat_class);
		//alertWindowMsg("lock ok!!!");
	}
	function kill_sub($p_id)
	{
		global $DB_site,$lang;
		$group_main=$DB_site->query_first("select a.g_id,a.lv from wog_group_permissions a,wog_player b,wog_group_member_point c where b.p_id=".$p_id." and c.p_id=".$p_id." and c.g_id=b.p_g_id and a.id=c.p_permissions");
		if($group_main)
		{
			if($group_main["lv"]==1)
			{
				$DB_site->query("delete from wog_group_main where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_event where g_b_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_book where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_weapon where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_join where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_job where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_exchange where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_depot where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_build where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_build_job where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_detect where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_fight_book where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_member_book where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_member_point where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_point where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_mission_log where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_depot_msg where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_market where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_permissions where g_id=".$group_main["g_id"]);
				$DB_site->query("update wog_group_map set g_id =0 where g_id =".$group_main["g_id"]);
				$DB_site->query("update wog_player set p_g_id=0 where p_g_id=".$group_main["g_id"]);
			}else
			{
				$p_count=$DB_site->query_first("select count(p_id) as id from wog_player where p_g_id=".$group_main["g_id"]);
				$p_count[id]-=1;
				$g_lv=1;
				foreach($wog_arry["g_lv"] as $key=>$value)
				{
					if($value > $p_count[id])
					{
						$g_lv=$key;
						break;
					}
				}
				$DB_site->query("update wog_group_main set g_peo=".$p_count[id].",g_lv=$g_lv where g_id=".$group_main["g_id"]);
				$DB_site->query("delete from wog_group_job where p_id=".$p_id);
				$DB_site->query("delete from wog_group_build_job where p_id=".$p_id);
				$DB_site->query("delete from wog_group_member_point where p_id=".$p_id);
			}
		}
		$DB_site->query("delete from wog_player where p_id=".$p_id);
		$DB_site->query("delete from wog_item where p_id=".$p_id);
		$DB_site->query("delete from wog_ch_exp where p_id=".$p_id);
		$DB_site->query("delete from wog_sale where p_id=".$p_id);
		$DB_site->query("delete from wog_pet where pe_p_id=".$p_id);
		$DB_site->query("delete from wog_mission_book where p_id=".$p_id);
		$DB_site->query("delete from wog_player_arm where p_id=".$p_id);
		$DB_site->query("delete from wog_stone_setup where p_id=".$p_id);
		$DB_site->query("delete from wog_exchange_list where p_id=".$p_id);
		$DB_site->query("delete from wog_mercenary_list where p_id=".$p_id);
		$DB_site->query("delete from wog_mercenary_book where p_id=".$p_id);
		$DB_site->query("delete from wog_key_list where p_id=".$p_id);
		$DB_site->query("delete from wog_skill_setup where p_id=".$p_id);
		$DB_site->query("delete from wog_skill_book where p_id=".$p_id);
		$DB_site->query("delete from wog_player_depot where p_id=".$p_id);
		$DB_site->query("delete from wog_friend_list where p_id=".$p_id);
		$DB_site->query("delete from wog_message_box where p_id=".$p_id);
		$DB_site->query("delete from wog_player_reitem where p_id=".$p_id);
		$DB_site->query("delete from wog_plus_setup where p_id=".$p_id);
		$DB_site->query("delete from wog_stone_temp where p_id=".$p_id);
		$DB_site->query("delete from wog_player_up where p_id=".$p_id);
		unset($group_main);
	}

	function system_view2($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		$towho=trim($_POST["temp_id"]);
		if($towho=="")
		{
			alertWindowMsg($lang['wog_act_sysview_noselect']);

		}
		$have_price=$DB_site->query_first("select p_money from wog_player where p_id=".$user_id);
		if($have_price[p_money] < $wog_arry["view2_money"])
		{
			alertWindowMsg(sprintf($lang['wog_act_nomoney_must'], $wog_arry["view2_money"]));

		}else
		{
			$DB_site->query("update wog_player set p_money=p_money-".$wog_arry["view2_money"]." where p_id=".$user_id);
		}
		$p=$DB_site->query_first("select p_id from wog_player where p_name='".$towho."'");
		if($p)
		{
			$this->show_chara($p["p_id"],2);
		}else
		{
			alertWindowMsg($lang['wog_act_noid']);

		}
		unset($have_price,$p);
	}
	function get_password($email)
	{
		global $DB_site,$lang;
		$p=$DB_site->query_first("SELECT p_email FROM wog_player WHERE p_email='".addslashes(htmlspecialchars($email))."'");
		if($p){
			$temp_s=$lang['wog_etc_email_body1']."\r\n";
			$get_p=$DB_site->query("SELECT p_name,p_password from wog_player where p_email='".addslashes(htmlspecialchars($email))."'");
			while($get_ps=$DB_site->fetch_array($get_p))
			{
				$temp_s.=sprintf($lang['wog_etc_email_body2'],$get_ps[p_name])."\r\n";
				$temp_s.=sprintf($lang['wog_etc_email_body3'],$get_ps[p_password])."\r\n";
			}
			mail($p[p_email],$lang['wog_etc_email_subject'],$temp_s,"From: \"Online FF Battle - WOG\" <iqstar@pchome.com.tw>\r\n"."Content-type: text/html; charset=UTF-8\r\n"."Reply-To: iqstar@pchome.com.tw\r\n"."X-Mailer: PHP/" . phpversion());
			alertWindowMsg($lang['wog_etc_email_send']);
		}
		else
		{
			alertWindowMsg($lang['wog_etc_email_error']);
		}
	}
	function chpw($user_id)
	{
		global $DB_site,$lang,$_POST;
		if(empty($user_id)){alertWindowMsg($lang['wog_act_chara_err1']);}
		$p=$DB_site->query_first("SELECT p_id FROM wog_player WHERE p_id=".$user_id." and p_password='".$_POST["old_pw"]."'");
		if($p){
			$sql="update wog_player set p_password='".$_POST["new_pw"]."' where p_id=".$user_id;
			$DB_site->query($sql);
			showscript("parent.job_end(3)");
		}
		else
		{
			alertWindowMsg($lang['wog_act_chara_errlogin']);
		}
	}
	function creat_code()
	{
		$confirm_chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',  'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',  'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		list($usec, $sec) = explode(' ', microtime());
		mt_srand($sec * $usec);
		$max_chars = count($confirm_chars) - 1;
		$key = '';
		for ($i = 0; $i < 5; $i++)
		{
			$key .= $confirm_chars[mt_rand(0, $max_chars)];
		}
		return md5($key);
	}
	function send_code($email,$code)
	{
		global $lang,$wog_arry;
		$url=$wog_arry["code_url"].$code;
		$temp=sprintf($lang['wog_act_chara_msg1'],$url);
		mail($email,'Online FF Battle - WOG',$temp,"From: \"Online FF Battle - WOG\" <iqstar@pchome.com.tw>\r\n"."Content-type: text/html; charset=UTF-8\r\n"."Reply-To: iqstar@pchome.com.tw\r\n"."X-Mailer: PHP/" . phpversion());
	}
	function check_code()
	{
		global $DB_site,$lang,$_GET;
		$sql="select p_id from wog_player_confirm where code='".$_GET["code"]."'";
		$p=$DB_site->query_first($sql);
		if($p)
		{
			$sql="update wog_player set p_lock=0 where p_id=".$p[p_id];
			$DB_site->query($sql);
			$sql="delete from wog_player_confirm where code='".$_GET["code"]."'";
			$DB_site->query($sql);
			alertWindowMsg($lang['wog_etc_chara_msg1'],"./");
		}
		else
		{
			alertWindowMsg($lang['wog_etc_chara_err1']);
		}
	}
	function adm($user_id)
	{
		global $DB_site,$lang,$_GET;
		if(empty($user_id))
		{
			showscript("parent.id_admin('','')");
		}
		$sql="select p_name,p_email from wog_player where p_id=".$user_id;
		$p=$DB_site->query_first($sql);
		if($p)
		{
			showscript("parent.id_admin('".$p["p_name"]."','".$p["p_email"]."')");			
		}else
		{
			showscript("parent.id_admin('','')");
		}
		
	}
}
?>