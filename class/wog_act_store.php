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

class wog_act_store{
	var $fight_hotel=0;
	function hotel($user_id)
	{
		global $DB_site,$wog_arry,$lang,$_SESSION;
		$have_price=$DB_site->query_first("select p_money,p_lv,hp,hpmax,p_win,p_lost,au from wog_player where p_id=".$user_id."");
		if($have_price[p_lv] > 100)
		{
			$slv=1;
			$have_price[hpmax]=$have_price[hpmax]-($have_price[p_au]*2);
			if($have_price[hp]>$have_price[hpmax])
			{
				$have_price[hp]=$have_price[hpmax];
			}
			if($have_price[hp]<=0)
			{
				$slv=2.1;
				$hotel_time=$wog_arry["hotel_die_time"];
			}else
			{
				if(($have_price[hpmax]/10) > $have_price[hp])
				{
					$slv=1.8;
				}else
				{
					$slv=1.4*(($have_price[hpmax]-$have_price[hp])/$have_price[hpmax]);
				}
				$hotel_time=$wog_arry["hotel_time"];
			}
			$mmoney=round($wog_arry["hotel_money"]*$have_price[p_lv]*$slv);
			if($mmoney < ($wog_arry["hotel_money"]*$have_price[p_lv]))
			{
				$mmoney=$wog_arry["hotel_money"]*$have_price[p_lv];
			}
			if($have_price[p_money] < $mmoney)
			{
				$DB_site->query("update wog_player set hp=hpmax,p_exp=p_exp*0.8,sp=spmax where p_id=".$user_id);
//				alertWindowMsg(sprintf($lang['wog_act_nomoney_must'], $mmoney));
			}else
			{
				$DB_site->query("update wog_player set p_money=p_money-".$mmoney.",hp=hpmax,sp=spmax where p_id=".$user_id);
			}
		}else
		{
			$DB_site->query("update wog_player set hp=hpmax,sp=spmax where p_id=".$user_id);
			if($have_price[hp]<=0)
			{
				$hotel_time=$wog_arry["hotel_die_time"];
			}else
			{
				$hotel_time=$wog_arry["hotel_time"];
			}
		}
		$_SESSION["act_time"]+=$hotel_time;
		/*
		if(time()-$_SESSION["act_time"] > $wog_arry["f_time"])
		{
			$_SESSION["act_time"]=time()-($wog_arry["f_time"]-$wog_arry["hotel_time"]);
		}else
		{
			$_SESSION["act_time"]+=$wog_arry["hotel_time"];
		}
		*/
		if($this->fight_hotel)
		{
			echo ",\"parent.job_end(5,null,2)\",\"parent.cd(".$hotel_time.")\"";
		}else
		{
			showscript("parent.job_end(5,null,2);parent.cd(".$hotel_time.")");
		}
	}

	function bank($user_id)
	{
		global $DB_site,$_POST,$lang;
		$have_price=$DB_site->query_first("select p_bbsid,p_money from wog_player where p_id=".$user_id);
		$bank_price=$DB_site->query_first("select ".BANK_FIELB." from ".BANK_TABLE." where ".USER_ID."=".$user_id);
		showscript("parent.bank('".$have_price[p_money]."','".$bank_price[0]."')");
	}

	function bank_save($user_id,$pay_id,$money)
	{
		global $DB_site,$lang;
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$have_price=$DB_site->query_first("select p_name,p_money,p_lv,p_bbsid from wog_player where p_id=".$user_id." for update");
		if($have_price[p_money] < $money || $money <=0 || $have_price[p_lv]<15 || !is_numeric($money) || preg_match("/[^0-9]/",$money) )
		{
			alertWindowMsg($lang['wog_act_bank_noues']);

		}else
		{
			if($user_id != $pay_id)
			{
				$DB_site->query("insert into wog_message(p_id,title,dateline)values(".$pay_id.",'".$have_price[p_name]." 匯入 ".$money."元 到你的銀行 ',".time().")");
				//$p=$DB_site->query_first("select p_bbsid from wog_player where p_id=".$pay_id);
				$DB_site->query("update ".BANK_TABLE." set ".BANK_FIELB." = ".BANK_FIELB."+".$money." WHERE ".USER_ID."=".$pay_id);
			}else
			{
				$DB_site->query("update ".BANK_TABLE." set ".BANK_FIELB." = ".BANK_FIELB."+".$money." WHERE ".USER_ID."=".$user_id);
			}
			$DB_site->query("update wog_player set p_money = p_money-".$money." where p_id=".$user_id);
		}
		$DB_site->query_first("COMMIT");
		showscript("parent.job_end(4)");
	}

	function bank_get($user_id,$money)
	{
		global $DB_site,$lang;
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$bank_memey=$DB_site->query_first("select ".BANK_FIELB." from wog_player where p_id=".$user_id."  for update ");
		if($bank_memey[BANK_FIELB] < $money )
		{
			alertWindowMsg($lang['wog_act_nomoney']);
		}
		if($bank_memey[BANK_FIELB] < $money || $money <=0  || !is_numeric($money) || preg_match("/[^0-9]/",$money) )
		{
			alertWindowMsg($lang['wog_act_nomoney']);
		}else
		{
			$DB_site->query("update ".BANK_TABLE." set ".BANK_FIELB." = ".BANK_FIELB."-".$money." WHERE ".USER_ID."=".$user_id);
			$DB_site->query("update wog_player set p_money = p_money+".$money." where p_id=".$user_id);
		}
		$DB_site->query_first("COMMIT");
		showscript("parent.job_end(4)");
	}

	function p_sex($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		if(empty($_POST["num"]))
		{
			alertWindowMsg($lang['wog_act_img_nosexg']);
		}
		if(preg_match("/[12]/",$_POST["num"]))
		{
			$have_price=$DB_site->query_first("select p_money from wog_player where p_id=".$user_id."");
			if($wog_arry["chang_sex"]>$have_price[0]){
				alertWindowMsg($lang['wog_act_nomoney']);
			}
			$DB_site->query("update wog_player set p_sex=".$_POST["num"].",p_money=p_money-".$wog_arry["chang_sex"]." where p_id=".$user_id."");
			showscript("parent.job_end(11)");
		}else
		{
			alertWindowMsg($lang['wog_act_img_errsex']);
		}
		unset($have_price);
	}

	function p_img($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		if(empty($_POST["num"]))
		{
			alertWindowMsg($lang['wog_act_img_noimg']);
		}
		if(!is_numeric($_POST["num"]))
		{
			alertWindowMsg($lang['wog_act_img_errimg']);
		}
		$p=$DB_site->query_first("SELECT i_id FROM wog_img WHERE i_id=".$_POST["num"]." ");
		if($p)
		{
			$have_price=$DB_site->query_first("select p_money from wog_player where p_id=".$user_id."");
			if($wog_arry["chang_face"]>$have_price[0]){
				alertWindowMsg($lang['wog_act_nomoney']);
			}
			$DB_site->query("update wog_player set p_img_set=0,i_img=".$_POST["num"].",p_money=p_money-".$wog_arry["chang_face"]." where p_id=".$user_id."");
			showscript("parent.job_end(11)");
		}else
		{
			alertWindowMsg($lang['wog_act_img_errimg']);
		}
		unset($p,$have_price);
	}

	function p_img2($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		if(empty($_POST["url"]))
		{
			alertWindowMsg($lang['wog_act_img_noimg']);
		}
		$have_price=$DB_site->query_first("select p_money,p_st from wog_player where p_id=".$user_id."");
		if($wog_arry["chang_face"]>$have_price['p_money']){
			alertWindowMsg($lang['wog_act_nomoney']);
		}
/*
		if($have_price['p_st']<2){
			alertWindowMsg($lang['wog_vip_message']);
		}
*/
		$DB_site->query("update wog_player set p_img_set=1,p_img_url='".$_POST["url"]."',p_money=p_money-".$wog_arry["chang_face"]." where p_id=".$user_id."");
		showscript("parent.job_end(11)");
		unset($have_price);
	}
}
?>