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

class wog_act_pet{
	function pet_leave($user_id)
	{
		global $DB_site,$_POST,$lang;
		if(!isset($_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		$DB_site->query("delete from wog_pet where pe_p_id=".$user_id." and pe_id=".$_POST["pay_id"]);
		//showscript("parent.job_end(18)");
		$this->pet_index($user_id);
	}

	function pet_sale($user_id)
	{
		global $DB_site,$_POST,$lang;
		if(!isset($_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		$money=$_POST["temp_id"];
		if(empty($money) || (int)$money <=0 || !is_numeric($money))
		{
			alertWindowMsg($lang['wog_act_nomoney']);
		}
		$p=$DB_site->query_first("select p_send from wog_pet where pe_id=".$_POST["pay_id"]);
		if($p[p_send]==1)
		{
			alertWindowMsg($lang['wog_act_pet_error1']);
		}
		$p=$DB_site->query_first("select count(pe_id) as id from wog_pet where pe_p_id=".$user_id." and pe_st=1");
		if($p[id]>=5)
		{
			alertWindowMsg($lang['wog_act_pet_error4']);
		}
		$have_price=$DB_site->query_first("select p_money from wog_player where p_id=".$user_id." and p_lock=0");
		if($have_price)
		{
			$need_money=$money*0.01;
			if($need_money < 1){$need_money=1;}
			if($have_price["p_money"]<$need_money)
			{
				alertWindowMsg(sprintf($lang['wog_act_bid_procedures'],$need_money));
			}
			$DB_site->query("update wog_player set p_money=p_money-".$need_money." where p_id=".$user_id);
		}else
		{
			alertWindowMsg($lang['wog_act_nologin']);
		}
		$sale_time=3600*24*5;
		$DB_site->query("update wog_pet set pe_money=".$money.",pe_st=1,pe_s_dateline=".(time()+$sale_time)." where pe_p_id=".$user_id." and pe_id=".$_POST["pay_id"]);
		showscript("parent.job_end(9)");
	}

	function pet_chg_st($user_id)
	{
		global $DB_site,$_POST,$lang;
		if(!isset($_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_pet_nopet'].$_POST["pay_id"]);
		}
		if($_POST["temp_id"]=="0")
		{
			$p=$DB_site->query_first("select pe_id from wog_pet where pe_p_id=".$user_id." and pe_st=0");
			if($p)
			{
				alertWindowMsg($lang['wog_act_pet_noget']);
			}
		}
		$DB_site->query("update wog_pet set pe_st=".$_POST["temp_id"]." where pe_p_id=".$user_id." and pe_id=".$_POST["pay_id"]);
		//showscript("parent.job_end(3)");
		showscript("parent.wog_message_box(11,0,1);");
	}

	function pet_rename($user_id)
	{
		global $DB_site,$_POST,$lang;
		if(!isset($_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_pet_noname']);
		}
		$DB_site->query("update wog_pet set pe_name='".trim($_POST["temp_id"])."' where pe_p_id=".$user_id." and pe_id=".$_POST["pay_id"]);
		$_POST["temp_id"]=$_POST["pay_id"];
		$this->pet_index($user_id);
	}

	function pet_eat($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang,$a_id;
		if(!isset($_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_pet_noselect']);
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");			
		
		$p=$DB_site->query_first("select pe_fu,pe_dateline,pe_hu,pe_at,pe_mt,pe_def,pe_fi from wog_pet where pe_p_id=".$user_id." and pe_id=".$_POST["pay_id"]." for update");
		$player=$DB_site->query_first("select p_money,at,mat,df,mdf from wog_player where p_id=".$user_id." for update");
		if($p)
		{
			if((time()-$p["pe_dateline"]) < $wog_arry["pet_ac_time"])
			{
				alertWindowMsg($wog_arry["pet_ac_time"].$lang['wog_act_pet_noeat']);
			}
			if($p["pe_fu"] >= 100)
			{
				alertWindowMsg($lang['wog_act_pet_eatmax']);
			}
		}else
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		$at="+0";$mt="+0";$def="+0";$fi="+0";
		$money=0;
		switch ($_POST["temp_id"])
		{
			case "1":
				$p["pe_at"]+=2;
				if($p["pe_at"] > $player[at])
				{
					alertWindowMsg($lang['wog_act_pet_error2']);
				}
				$at="+2";
				$fu=rand(10,20);
				$money=20;
			break;
			case "2":
				$p["pe_mt"]+=2;
				if($p["pe_mt"] > $player[mat])
				{
					alertWindowMsg($lang['wog_act_pet_error2']);
				}				
				$mt="+2";
				$fu=rand(10,20);
				$money=20;
			break;
			case "3":
				$p["pe_def"]+=2;
				if($p["pe_def"] > $player[df] && $p["pe_def"] > $player[mdf])
				{
					alertWindowMsg($lang['wog_act_pet_error2']);
				}
				$def="+2";
				$fu=rand(10,20);
				$money=20;
			break;
			case "4":
				$p["pe_fi"]+=2;
				$fi="+2";
				$fu=rand(10,20);
				$money=20;
			break;
			case "5":
				$p["pe_at"]+=4;
				if($p["pe_at"] > $player[at])
				{
					alertWindowMsg($lang['wog_act_pet_error2']);
				}
				$at="+4";
				$fu=rand(18,28);
				$money=50;
			break;
			case "6":
				$p["pe_mt"]+=4;
				if($p["pe_mt"] > $player[mat])
				{
					alertWindowMsg($lang['wog_act_pet_error2']);
				}
				$mt="+4";
				$fu=rand(18,28);
				$money=50;
			break;
			case "7":
				$p["pe_def"]+=4;
				if($p["pe_def"] > $player[df] && $p["pe_def"] > $player[mdf])
				{
					alertWindowMsg($lang['wog_act_pet_error2']);
				}
				$def="+4";
				$fu=rand(18,28);
				$money=50;
			break;
			case "8":
				$p["pe_fi"]+=4;
				$fi="+4";
				$fu=rand(18,28);
				$money=50;
			break;
			case "2319":
				$p["pe_at"]+=8;
				if($p["pe_at"] > $player[at])
				{
					alertWindowMsg($lang['wog_act_pet_error2']);
				}
				$at="+8";
				$fu=rand(18,28);
			break;
			case "2320":
				$p["pe_mt"]+=8;
				if($p["pe_mt"] > $player[mat])
				{
					alertWindowMsg($lang['wog_act_pet_error2']);
				}
				$mt="+8";
				$fu=rand(18,28);
			break;
			case "2321":
				$p["pe_def"]+=8;
				if($p["pe_def"] > $player[df] && $p["pe_def"] > $player[mdf])
				{
					alertWindowMsg($lang['wog_act_pet_error2']);
				}
				$def="+8";
				$fu=rand(18,28);
			break;
			case "2322":
				$p["pe_fi"]+=8;
				$fi="+8";
				$fu=rand(18,28);
			break;
			default:
				alertWindowMsg($lang['wog_act_pet_noselect']);
			break;
		}
		$he=rand(2,6);
		if((int)$_POST["temp_id"]>2000)
		{
			include_once("./class/wog_item_tool.php");
			$wog_item_tool = new wog_item_tool;
			$sql="select d_item_id from wog_item where p_id=".$user_id." for update";
			$items=$DB_site->query_first($sql);
			$a_id="d_item_id";
			$items[0]=trim($items[0]);
			$temp_pack=array();
			if(!empty($items[0]))
			{
				$temp_pack=explode(",",$items[0]);
			}
			$temp_pack=$wog_item_tool->item_out($user_id,$_POST["temp_id"],1,$temp_pack);
			unset($wog_item_tool);
			$DB_site->query("update wog_item set d_item_id='".implode(',',$temp_pack)."' where p_id=".$user_id);
			$time=time();
			$sql="insert wog_vip_log(p_id,d_id,datetime)values($user_id,".$_POST["temp_id"].",$time)";
			$DB_site->query($sql);
		}else
		{
			if($player[p_money]<$money)
			{
				alertWindowMsg($lang['wog_act_nomoney']);
			}
		}

//		$hu=rand(5,20);
//		$p["pe_hu"]=$p["pe_hu"]-$hu;
//		if($p["pe_hu"] < 0){$p["pe_hu"]=0;}
		$DB_site->query("update wog_pet set pe_at=pe_at".$at.",pe_mt=pe_mt".$mt.",pe_def=pe_def".$def.",pe_fi=pe_fi".$fi.",pe_he=pe_he+".$he.",pe_fu=pe_fu+".$fu.",pe_dateline=".time()." where pe_p_id=".$user_id." and pe_id=".$_POST["pay_id"]);
		$DB_site->query("update wog_player set p_money=p_money-".$money." where p_id=".$user_id);
		$DB_site->query_first("COMMIT");
		unset($p,$player);
		$_POST["temp_id"]=$_POST["pay_id"];
		$this->pet_index($user_id);
		
	}
	function pet_index($user_id)
	{
		global $DB_site,$wog_arry,$lang,$_POST;
		if(!empty($_POST["temp_id"]) && is_numeric($_POST["temp_id"]))
		{
			$sql=" and pe_id=".$_POST["temp_id"];
		}else
		{
			$sql="";
		}
		$p=$DB_site->query_first("select * from wog_pet where pe_p_id=".$user_id." ".$sql." and pe_st in (0,2) LIMIT 1");
		if($p)
		{
			$old=round((time()-$p[pe_b_dateline])/$wog_arry["pet_age"]);
/*
			if($old >= 15 && $old < 20 && rand(1,800) <= 1)
			{
				$DB_site->query("delete from wog_pet where pe_p_id=".$user_id." and pe_st=0");
				alertWindowMsg($lang['wog_act_pet_die']);
			}
			if($old >= 20 && rand(1,600) <= 1)
			{
				$DB_site->query("delete from wog_pet where pe_p_id=".$user_id." and pe_st=0");
				alertWindowMsg($lang['wog_act_pet_die']);
			}
*/
			if($old >= 5 && $p["p_send"]==0)
			{
				$DB_site->query("update wog_pet set p_send=1 where pe_id=".$p["pe_id"]);
			}
			
			$DB_site->query("update wog_pet set pe_b_old=".$old." where pe_id=".$p["pe_id"]);

			$pe_list=$DB_site->query("select pe_name,pe_id from wog_pet where pe_p_id=".$user_id." and pe_st in (0,2)");
			$temp_s="";
			while($pe_lists=$DB_site->fetch_array($pe_list))
			{
				$temp_s.=";".$pe_lists[0].",".$pe_lists[1];
			}
			$DB_site->free_result($pe_list);
			unset($pe_lists);
			if($p[pe_img_set]==1)
			{
				$p[pe_mimg]=$p[pe_img_url];
			}
			$sql="select d_item_id from wog_item where p_id=".$user_id;
			$items=$DB_site->query_first($sql);
			$items[d_item_id]=trim($items[d_item_id]);
			$temp_pack=array();
			if(!empty($items[d_item_id]))
			{
				$temp_pack=explode(",",$items[d_item_id]);
			}
			$temp_s2=array();
			if(count($temp_pack)>0 && (strpos($items[d_item_id],"2319") || strpos($items[d_item_id],"2320") || strpos($items[d_item_id],"2321") || strpos($items[d_item_id],"2322")))
			{
				$item_chk=array();
				$sql="select d_id,d_name from wog_df where d_id in (2319,2320,2321,2322)";
				$item=$DB_site->query($sql);
				while($items=$DB_site->fetch_array($item))
				{
					$item_chk[$items[d_id]]=$items[d_name];
				}
				$DB_site->free_result($item);
				foreach($temp_pack as $v)
				{
					$chk=explode("*",$v);
					if($chk[0]=="2319" || $chk[0]=="2320" || $chk[0]=="2321" || $chk[0]=="2322")
					{
						$temp_s2[$chk[0]][num]+=(int)$chk[1];
						$temp_s2[$chk[0]][name]=$item_chk[$chk[0]];
						$temp_s2[$chk[0]][id]=$chk[0];
					}
				}
			}
			$str2="";
			if(count($temp_s2)>0)
			{
				foreach($temp_s2 as $k=>$v)
				{
					$str2.=";".$k.",".$v[name]."*".$v[num];
				}
				$str2=substr($str2,1);
			}
			unset($items,$temp_s2,$item_chk,$temp_pack);
			showscript("parent.pet_index('$p[pe_name]','$p[pe_mname]','$p[pe_id]','$p[pe_at],$p[pe_mt],$p[pe_def],$p[pe_fu],0,$p[pe_type],$old,$p[pe_he],$p[pe_fi],$p[pe_st],$p[pe_mimg],$p[pe_img_set]','$temp_s','$str2')");
		}else
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		unset($p);
	}
	function pet_img($user_id)
	{
		global $DB_site,$wog_arry,$lang,$_POST;
		if(!empty($_POST["temp_id"]) && is_numeric($_POST["temp_id"]))
		{
			$sql="update wog_pet set pe_img_url='".$_POST["pay_id"]."',pe_img_set=1 where pe_id=".$_POST["temp_id"];
			$DB_site->query($sql);
		}else
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		$this->pet_index($user_id);
	}
	function pet_unimg($user_id)
	{
		global $DB_site,$wog_arry,$lang,$_POST;
		if(!empty($_POST["temp_id"]) && is_numeric($_POST["temp_id"]))
		{
			$sql="update wog_pet set pe_img_url='',pe_img_set=0 where pe_id=".$_POST["temp_id"];
			$DB_site->query($sql);
		}else
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		$this->pet_index($user_id);
	}
/*
	function pet_ac($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang;
		if(!isset($_POST["pay_id"]))
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		if(empty($_POST["temp_id"]))
		{
			alertWindowMsg($lang['wog_act_pet_noselect']);
		}
		$p=$DB_site->query_first("select pe_at,pe_mt,pe_def,pe_fi,pe_hu,pe_he,pe_dateline from wog_pet where pe_p_id=".$user_id." and pe_id=".$_POST["pay_id"]);
		if($p)
		{
			if((time()-$p["pe_dateline"]) < $wog_arry["pet_ac_time"])
			{
				alertWindowMsg($wog_arry["pet_ac_time"].$lang['wog_act_pet_noac']);
			}
			if($p["pe_hu"] >= 100)
			{
				alertWindowMsg($lang['wog_act_pet_humax']);
			}
			if($p["pe_hu"] >= 80 )
			{
				if(rand(1,80) <= 1)
				{
					$DB_site->query("delete from wog_pet where pe_p_id=".$user_id." and pe_id=".$_POST["pay_id"]);
					showscript("parent.job_end(16)");
				}
			}
		}else
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		$at="+0";$mt="+0";$def="+0";$fi="+0";$fu="+0";
		switch ($_POST["temp_id"])
		{
			case "1":
				$at="+3";$mt="-1";$def="-1";
				$p[pe_at]=$p[pe_at]+3;$p[pe_mt]=$p[pe_mt]-1;$p[pe_def]=$p[pe_def]-1; 
			break;
			case "2":
				$at="-1";$mt="+3";$fi="-1";
				$p[pe_at]=$p[pe_at]-1;$p[pe_mt]=$p[pe_mt]+3;$p[pe_fi]=$p[pe_fi]-1;
			break;
			case "3":
				$at="-1";$mt="-1";$def="+3";
				$p[pe_at]=$p[pe_at]-1;$p[pe_mt]=$p[pe_mt]-1;$p[pe_def]=$p[pe_def]+3;
			break;
			case "4":
				$fi="+2";$def="-2";
				$p[pe_fi]=$p[pe_fi]+2;$p[pe_def]=$p[pe_def]-2;
			break;
		}
		$hu=rand(5,9);
		$he=rand(0,3);
		$p["pe_he"]=$p["pe_he"]-$he;
		if($p["pe_he"] < 0){$p["pe_he"]=0;}
		if($p["pe_at"] < 0){$p["pe_at"]=0;} 
		if($p["pe_mt"] < 0){$p["pe_mt"]=0;} 
		if($p["pe_def"] < 0){$p["pe_def"]=0;} 
		if($p["pe_fi"] < 0){$p["pe_fi"]=0;}
		$DB_site->query("update wog_pet set pe_at=".$p["pe_at"].",pe_mt=".$p["pe_mt"].",pe_def=".$p["pe_def"].",pe_fi=".$p["pe_fi"].",pe_he=".$p["pe_he"].",pe_dateline=".time()." where pe_p_id=".$user_id." and pe_id=".$_POST["pay_id"]);
		if($p)
		{
			//showscript("parent.pet_detail('$at','$mt','$def','+$hu','$fu','-$he','$fi')");
			$this->pet_index($user_id);
		}else
		{
			alertWindowMsg($lang['wog_act_pet_nopet']);
		}
		unset($p);
	}
*/
}
?>