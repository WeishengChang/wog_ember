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

class wog_etc_peo{
	function peo_view($userid)
	{
		global $DB_site,$wog_arry,$forum_check,$root_path;
		eval($forum_check);
		if(!empty($userid))
		{
			$time=time();
			$p_ip=get_ip();
			$DB_site->query("update wog_player set p_online_time=".$time.",p_ipadd='".$p_ip."' where p_id=".$userid." and p_lock=0");
			$p=$DB_site->query_first("select pe_dateline,pe_he,pe_fu from wog_pet where pe_p_id=$userid and pe_st=0");
			if($p)
			{
				if($time-$p["pe_dateline"] > $wog_arry["pet_fu_time"]*60 )
				{
					$p["pe_he"]=$p["pe_he"]-1;
					$p["pe_fu"]=$p["pe_fu"]-rand(8,12);
					if($p["pe_he"] < 0){$p["pe_he"]=0;}
					if($p["pe_fu"] < 0){$p["pe_fu"]=0;}
					$DB_site->query("update wog_pet set pe_dateline=".$time.",pe_he=".$p["pe_he"].",pe_fu=".$p["pe_fu"]." where pe_p_id=".$userid." and pe_st=0 ");
				}
			}
		}
		/* 改使用cache */
		if(rand(1,30)<=1)
		{
			$sql="select sum(ex_amount) as ex_amount from wog_exchange_main";
			$ex=$DB_site->query_first($sql);
			if($ex[ex_amount] < $wog_arry["exchange_up"])
			{
				$ex_id=rand(1,12);
				$ex_amount=rand(5000,35000);
				$sql="update wog_exchange_main set ex_amount=ex_amount+".$ex_amount." where ex_id=$ex_id";
				$DB_site->query($sql);
			}
			else
			{
				$total_ex=$ex[ex_amount];
				$sql="select count(ex_id) as id from wog_exchange_main where ex_amount=0";
				$ex=$DB_site->query_first($sql);
				if($ex[id]>=10)
				{
					$sql="update wog_exchange_main set ex_amount=0 ";
					$DB_site->query($sql);
					for($i=1;$i<13;$i++)
					{
						$ex_id=rand(1,12);
						$ex_amount=rand(3000,300000);
						$total_ex-=$ex_amount;
						$sql="update wog_exchange_main set ex_amount=ex_amount+".$ex_amount." where ex_id=$ex_id";
						$DB_site->query($sql);
						if($total_ex<=0){break;}
					}
					if($total_ex>0)
					{
						$ex_id=rand(1,12);
						$sql="update wog_exchange_main set ex_amount=ex_amount+".$total_ex." where ex_id=$ex_id";
						$DB_site->query($sql);						
					}
				}
			}
		}
		$datecut = time() - $wog_arry["offline_time"];
		$online=$DB_site->query("select p_name,p_sex,p_lv,p_pk_s,p_pk_money,p_place from wog_player where p_online_time >= $datecut and p_lock=0 order by p_lv desc");
		$temp_s="";
		while($onlines=$DB_site->fetch_array($online))
		{
			$temp_s.=";".$onlines[0].",".$onlines[1].",".$onlines[2].",".$onlines[3].",".$onlines[4].",".$onlines[5];
		}
		$DB_site->free_result($online);
		unset($onlines);
		$online=$DB_site->query("select p_name,p_sex,p_pk_s,p_pk_money,p_place from wog_player_cp a,wog_player b where a.hero_time = 0 and a.p_id=b.p_id");
		while($onlines=$DB_site->fetch_array($online))
		{
			$temp_s.=";".$onlines[0].",".$onlines[1].",??,".$onlines[2].",".$onlines[3].",".$onlines[4];
		}
		$DB_site->free_result($online);
		$temp_s=substr($temp_s,1);
		showscript("parent.onlinelist('$temp_s',1)");
		unset($temp_s);
		/*
		showscript("parent.onlinelist('',3)");
		*/
	}
	function peo_view2($userid)
	{
		global $DB_site,$wog_arry,$forum_check,$root_path;
		eval($forum_check);
		if(!empty($userid))
		{
			$time=time();
			$p_ip=get_ip();
			$DB_site->query("update wog_player set p_online_time=".$time.",p_ipadd='".$p_ip."' where p_id=".$userid." and p_lock=0");
		}
		$datecut = time() - $wog_arry["offline_time"];
		$online=$DB_site->query_first("select count(p_id) as id from wog_player where p_online_time >= $datecut and p_lock=0 order by p_lv desc");
		showscript("parent.onlinelist('$online[id]',2)");
	}
	function peo_cache()
	{
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script language='JavaScript' src='./cache1/wog_peo.js'></script>";
	}
}
?>