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

class wog_act_bid{
	function bid($user_id)
	{
		global $DB_site,$_POST,$a_id,$temp_ss,$wog_arry,$lang,$wog_item_tool;
		$money=(int)$_POST["money"];
		$e_money=(int)$_POST["e_money"];
		$day=(int)$_POST["day"];
		$item_num=(int)$_POST["item_num"];
		if($money>=$e_money){alertWindowMsg($lang['wog_act_bid_err5']);}
		if(empty($_POST["item_id"]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		if(empty($item_num) || $item_num > 99 || $item_num < 1 )
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		if(empty($day) || empty($money))
		{
			alertWindowMsg($lang['wog_act_bid_nodate']);
		}
		if($money<0 || $day<0 || !is_numeric($money) || !is_numeric($day) || !is_numeric($e_money))
		{
			alertWindowMsg($lang['wog_act_bid_nodate']);
		}
		if($day>$wog_arry["sale_day"])
		{
			alertWindowMsg(sprintf($lang['wog_act_bid_limitday'],$wog_arry["sale_day"]));
		}
		$total=$DB_site->query_first("select count(s_id) as s_id from wog_sale where p_id=".$user_id." and s_type=1");
		if($total[0]>=5)
		{
			alertWindowMsg($lang['wog_act_bid_limit_item']);
		}

//		$temp=explode(",",$_POST["item_id"]);
//		$item_id=$temp[0];
//		$item_hs_id=$temp[0];
		$bag_item_id=$_POST["item_id"];
		get_arm_id($bag_item_id,$item_id,$hs_id,$ps_id);
		$sql="select d_send,d_fie from wog_df where d_id=".$item_id;
		$pay=$DB_site->query_first($sql);
		if($pay[0]==1 || $pay[0]==2)
		{
			alertWindowMsg($lang['wog_act_arm_nosend']);
		}
		$a_id=type_name($pay[d_fie]);
//		check_type($item_id);
/*
		$hs_id=0;
		if(!empty($temp[1]) && $a_id!='d_item_id' && $a_id!='d_stone_id')
		{
			$hs_id=$temp[1];
			$item_hs_id.=":".$temp[1];
		}
*/
		$temp_ss=$wog_item_tool->item_out($user_id,$bag_item_id,$item_num);
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$have_price=$DB_site->query_first("select p_money from wog_player where p_id=".$user_id." and p_lock=0");
		if($have_price)
		{
			if($have_price["p_money"]<$money*0.01)
			{
				alertWindowMsg(sprintf($lang['wog_act_bid_procedures'],$money*0.01));
			}
			$DB_site->query("update wog_player set p_money=p_money-".$money*0.01." where p_id=".$user_id);
		}else
		{
			alertWindowMsg($lang['wog_act_nologin']);
		}
		$DB_site->query("select p_id from wog_item where p_id=".$user_id." for update");
		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$temp_ss)."' where p_id=".$user_id);
		$DB_site->query("insert wog_sale(p_id,d_id,hs_id,ps_id,item_num,s_money,e_money,dateline,d_type,s_type)values(".$user_id.",".$item_id.",".$hs_id.",".$ps_id.",".$item_num.",".$money.",".$e_money.",".(time()+($day*24*60*60)).",".$pay[d_fie].",1)");
		$DB_site->query_first("COMMIT");
		showscript("parent.arm_select()");
		unset($d_money,$pay,$s2,$a_id);
	}
	function sale_buy_item3($user_id) //直標模式
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_arry,$wog_item_tool,$_GET;
		$s_id=$_POST["s_id"];
		if(empty($s_id)){alertWindowMsg($lang['wog_act_arm_noselect']);}
		$sql="select e_money from wog_sale where s_id=".$s_id;
		$pack=$DB_site->query_first($sql);
		if(empty($pack[e_money])){alertWindowMsg($lang['wog_act_bid_err4']);}
		$_POST["money"]=$pack[e_money];
		$this->sale_buy_item($user_id);
	}
	function sale_buy_item($user_id) //競標模式
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_arry,$wog_item_tool,$_GET;
		$money=(int)$_POST["money"];
		$s_id=$_POST["s_id"];
		if(!isset($s_id))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		if(empty($money) || !is_numeric($money) || preg_match("/[^0-9]/",$_POST["money"]))
		{
			alertWindowMsg($lang['wog_act_bid_nodate']);
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$pack=$DB_site->query_first("select a.d_type,a.d_id,a.p_id,a.hs_id,a.ps_id,a.s_money,a.e_money,a.item_num,a.e_p_id from wog_sale a where a.s_id=".$s_id." and a.s_type=1 for update");
		if(!$pack)
		{
			alertWindowMsg($lang['wog_act_bid_buyed']);
		}
		if($pack[s_money]>=$money)
		{
			alertWindowMsg($lang['wog_act_bid_err2']);
		}
		if($pack[p_id]==$user_id)
		{
			alertWindowMsg($lang['wog_act_bid_err3']);
		}
		$have_price=$DB_site->query_first("select p_money,p_name,p_lv from wog_player where p_id=".$user_id." for update");
		if($money > $have_price[0]){
			alertWindowMsg($lang['wog_act_nomoney']);
		}
		if($pack[e_money] > 0 && $money > $have_price[0]){alertWindowMsg($lang['wog_act_nomoney']);}
		if($have_price[2] < 15 )
		{
			alertWindowMsg(sprintf($lang['wog_act_cant_bid'],15));
		}
		if($pack[e_p_id] > 0  )
		{
			$sql="select d_name from wog_df where d_id=".$pack[d_id];
			$d_main=$DB_site->query_first($sql);
			$sql="update wog_player set p_money=p_money+".$pack[s_money]." where p_id=".$pack[e_p_id];
			$DB_site->query($sql);
			$sql="insert into wog_message(p_id,title,dateline)values($pack[e_p_id],'".sprintf($lang['wog_act_bid_msg2'],$d_main[d_name],$pack[s_money])."',".time().")";
			$DB_site->query($sql);
		}
		if($pack[e_money] > 0 && $pack[e_money]<=$money)
		{
			$money=$pack[e_money];
			
			check_type($pack[d_type],1);
			$sql="select ".$a_id." from wog_item where p_id=".$user_id;
			$pack2=$DB_site->query_first($sql);
			$temp_pack=array();
			if(!empty($pack2[0]))
			{
				$temp_pack=explode(",",$pack2[0]);
			}
			$d_id=$pack["d_id"];
			if($pack[d_type]>4)
			{
				$temp_pack=$wog_item_tool->item_in($temp_pack,$pack["d_id"],$pack["item_num"]);
				$sql="select p_bag from wog_player where p_id=".$user_id;
				$bag=$DB_site->query_first($sql);
				$bbag=$wog_arry["item_limit"]+$bag[0];
			}else
			{
				if(!empty($pack["hs_id"])){$pack["d_id"].=":".$pack["hs_id"];}
				if(!empty($pack["ps_id"])){$pack["d_id"].="&".$pack["ps_id"];}
				$temp_pack=$wog_item_tool->item_in($temp_pack,$pack["d_id"]);
				$bbag=$wog_arry["item_limit"];
			}
			if(count($temp_pack) <= $bbag)
			{
				$DB_site->query("update wog_item set ".$a_id."='".implode(',',$temp_pack)."' where p_id=".$user_id);
			}
			else
			{
				$e_time=time()+$wog_arry["retime"];
				$sql="insert into wog_player_reitem(p_id,d_id,d_type,d_num,hs_id,ps_id,end_time)values($user_id,$d_id,$pack[d_type],$pack[item_num],$pack[hs_id],$pack[ps_id],$e_time)";
				$DB_site->query($sql);
			}
			if(!empty($pack["hs_id"]))
			{
				$DB_site->query("update wog_stone_setup set p_id=$user_id where hs_id=".$pack["hs_id"]);
			}
			if(!empty($pack["ps_id"]))
			{
				$DB_site->query("update wog_plus_setup set p_id=$user_id where ps_id=".$pack["ps_id"]);
			}
			$DB_site->query("update wog_player set p_money=p_money+".$money." where p_id=".$pack[p_id]);
			$DB_site->query("delete from wog_sale where s_id=".$s_id);
			$sql="select d_name from wog_df where d_id=".$d_id;
			$d_main=$DB_site->query_first($sql);
			$sql="insert into wog_message(p_id,title,dateline)values($pack[p_id],'".sprintf($lang['wog_act_bid_msg3'],$d_main[d_name],$have_price[p_name],$money)."',".time().")";
			$DB_site->query($sql);
		}
		else
		{
			$DB_site->query("update wog_sale set s_money=".$money.",e_p_id=$user_id where s_id=".$s_id);
		}

		$DB_site->query("update wog_player set p_money=p_money-".$money." where p_id=".$user_id);

		$DB_site->query_first("COMMIT");
		unset($pack,$temp_add,$must_price,$have_price,$temp,$a_id,$d_id,$d_main);
		$_GET["type"]=$_POST["type"];
		$this->bid_view($user_id);
	}
	function bid_get_item() //處理到期的拍賣物品
	{
		global $DB_site,$_GET,$a_id,$wog_arry,$wog_item_tool,$lang;
		$time=time();
		$e_time=$time+$wog_arry["retime"];
		$s3="";
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$pack=$DB_site->query("select a.d_type,a.d_id,a.p_id,a.hs_id,a.ps_id,a.s_money,a.e_p_id,a.item_num from wog_sale a where a.dateline < ".$time." and s_type=1 for update");
		while($packs=$DB_site->fetch_array($pack))
		{
			$chk_sale=1;
			check_type($packs[0],1);
			if(empty($packs[e_p_id]))
			{
				$packs[e_p_id]=$packs[p_id];
				$chk_sale=0;
			}
			$sql="select ".$a_id." from wog_item where p_id=".$packs[e_p_id]." for update";
			$pack2=$DB_site->query_first($sql);
			$temp_pack=array();
			if(!empty($pack2[0]))
			{
				$temp_pack=explode(",",$pack2[0]);
			}
			$d_id=$packs["d_id"];
			$sql="select p_bag,p_name from wog_player where p_id=".$packs[e_p_id];
			$bag=$DB_site->query_first($sql);
			if($packs[0] >4)
			{
				$temp_pack=$wog_item_tool->item_in($temp_pack,$packs["d_id"],$packs["item_num"]);
				$bbag=$wog_arry["item_limit"]+$bag[0];
			}else
			{
				if(!empty($packs["hs_id"])){$packs["d_id"].=":".$packs["hs_id"];}
				if(!empty($packs["ps_id"])){$packs["d_id"].="&".$packs["ps_id"];}
				$temp_pack=$wog_item_tool->item_in($temp_pack,$packs["d_id"]);
				$bbag=$wog_arry["item_limit"];
			}
			if(count($temp_pack) <= $bbag)
			{
				$DB_site->query("update wog_item set ".$a_id."='".implode(',',$temp_pack)."' where p_id=".$packs[e_p_id]);
			}
			else
			{
				$sql="insert into wog_player_reitem(p_id,d_id,d_type,d_num,hs_id,ps_id,end_time)values($packs[e_p_id],$d_id,$packs[d_type],$packs[item_num],$packs[hs_id],$packs[ps_id],$e_time)";
				$DB_site->query($sql);
			}
			if(!empty($packs["hs_id"]))
			{
				$DB_site->query("update wog_stone_setup set p_id=$packs[e_p_id] where hs_id=".$packs["hs_id"]);
			}
			if(!empty($packs["ps_id"]))
			{
				$DB_site->query("update wog_plus_setup set p_id=$packs[e_p_id] where ps_id=".$packs["ps_id"]);
			}
			$sql="select d_name from wog_df where d_id=$d_id";
			$d_main=$DB_site->query_first($sql);
			if($chk_sale==1)
			{
				$sql="update wog_player set p_money=p_money+".$packs[s_money]." where p_id=".$packs[p_id];
				$DB_site->query($sql);
				$sql="insert into wog_message(p_id,title,dateline)values($packs[p_id],'".sprintf($lang['wog_act_bid_msg3'],$d_main[d_name],$bag[p_name],$packs[s_money])."',".$time.")";
				$DB_site->query($sql);				
			}
			else
			{
				$sql="insert into wog_message(p_id,title,dateline)values($packs[p_id],'".sprintf($lang['wog_act_bid_msg4'],$d_main[d_name])."',".$time.")";
				$DB_site->query($sql);				
			}
		}
		$DB_site->free_result($pack);
		unset($temp_pack,$pack,$packs,$pack2);
		$DB_site->query("delete from wog_sale where dateline < ".$time." and s_type=1");
		$DB_site->query("delete from wog_pet where pe_st=1 and pe_s_dateline < ".$time);
		$DB_site->query_first("COMMIT");
	}
	function sale_buy_pet($user_id)
	{
		global $DB_site,$_POST,$lang;
		if(!isset($_POST["sp_id"]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$pack=$DB_site->query_first("select pe_money,pe_p_id,pe_name from wog_pet where pe_id=".$_POST["sp_id"]." and pe_st=1 for update");
		if(!$pack)
		{
			alertWindowMsg($lang['wog_act_bid_buyed']);
		}
		$temp["money"]=$pack["pe_money"];
		$temp["user_id"]=$pack["pe_p_id"];
		$temp["d_name"]=$pack["pe_name"];
		$sql="select count(pe_id) as num from wog_pet where pe_p_id=".$user_id." and pe_st in (0,2) ";
		$pack=$DB_site->query_first($sql);
		if($pack["num"]>2)
		{
			alertWindowMsg($lang['wog_act_bid_one']);
		}
		$have_price=$DB_site->query_first("select p_money,p_name,p_lv from wog_player where p_id=".$user_id." for update");
		if($temp["money"]>$have_price[0]){
			alertWindowMsg($lang['wog_act_nomoney']);
		}
		if($have_price[p_lv] < 15 )
		{
			alertWindowMsg(sprintf($lang['wog_act_cant_bid'],15));
		}
		$DB_site->query("update wog_player set p_money=p_money-".$temp["money"]." where p_id=".$user_id);
		$DB_site->query("update wog_player set p_money=p_money+".$temp["money"]." where p_id=".$temp["user_id"]);
		$DB_site->query("update wog_pet set pe_p_id=".$user_id.",pe_st=2,pe_he=0 where pe_id=".$_POST["sp_id"]);
		$DB_site->query("insert into wog_message(p_id,title,dateline)values(".$temp["user_id"].",'您拍賣的 ".$temp["d_name"]." 被 ".$have_price["p_name"]." 買走',".time().")");
		$DB_site->query_first("COMMIT");
		unset($pack,$have_price,$temp,$a_id);
		showscript("parent.job_end(6)");
	}

	function bid_view($user_id)
	{
		global $DB_site,$_GET,$a_id,$wog_arry,$wog_item_tool,$lang;
		$this->bid_get_item();
		if(empty($user_id)){alertWindowMsg($lang['wog_act_relogin']);}
		$serch_key="";
		if(empty($_GET["type"]) || !is_numeric($_GET["type"]))
		{
			$_GET["type"]="0";
		}
		$d_type=$_GET["type"];
		if($d_type == "6")
		{
			if(!empty($_GET["key"]))
			{
				$serch_key=" and a.pe_name like '%".$_GET["key"]."%'";
			}
			$sale_total=$DB_site->query_first("select count(a.pe_id) as pe_id from wog_pet a,wog_player b where a.pe_p_id=b.p_id and a.pe_st=1 $serch_key");
		}
		else{
			if(!empty($_GET["key"]))
			{
				$serch_key=" and c.d_name like '%".$_GET["key"]."%'";
			}
			$sale_total=$DB_site->query_first("select count(a.s_id) as s_id from wog_sale a,wog_player b,wog_df c where a.s_type=1 and a.p_id=b.p_id and a.d_id=c.d_id and c.d_fie =".$d_type." $serch_key");
		}
		if(empty($_GET["page"]) || !is_numeric($_GET["page"]))
		{
			$_GET["page"]="1";
		}
		$spage=((int)$_GET["page"]*8)-8;
		$item_array2=array();
		if($d_type == "6")
		{
			$pet_age=3600*24*10;
			$sale=$DB_site->query("select b.p_name,a.pe_id,a.pe_name,a.pe_mname,a.pe_at,a.pe_mt,a.pe_def,a.pe_type,a.pe_b_dateline,a.pe_fi,a.pe_money,a.pe_s_dateline from wog_pet a,wog_player b where a.pe_p_id=b.p_id and a.pe_st=1 $serch_key ORDER BY a.pe_money,a.pe_s_dateline desc LIMIT ".$spage.",8 ");
			while($sales=$DB_site->fetch_array($sale))
			{
				$item_array2[]=$sales[0].",".$sales[1].",".$sales[2].",".$sales[3].",".$sales[4].",".$sales[5].",".$sales[6].",".$sales[7].",".round((time()-$sales[8])/$pet_age).",".$sales[9].",".$sales[10].",".$sales[11];
			}
		}
		else
		{
			if($d_type == "7")
			{
				$sale=$DB_site->query("select b.p_name,c.d_name,d.s_at,d.s_mat,d.s_df,d.s_mdf,d.s_agl,d.s_str,d.s_life,d.s_vit,d.s_smart,d.s_au,d.s_be,a.e_money,a.s_id,a.s_money,a.dateline,a.item_num,a.hs_id,c.d_id,d.s_hpmax from wog_sale a,wog_player b,wog_df c,wog_stone_list d  where a.s_type=1 and a.p_id=b.p_id and a.d_id=c.d_id and d.d_id=a.d_id and c.d_type in (".$d_type.") $serch_key ORDER BY a.s_money,a.dateline LIMIT ".$spage.",8 ");
				while($sales=$DB_site->fetch_array($sale))
				{
					$item_array2[]=$sales[0].",".$sales[1].",".$sales[2].",".$sales[3].",".$sales[4].",".$sales[5].",".$sales[6].",".$sales[7].",".$sales[8].",".$sales[9].",".$sales[10].",".$sales[11].",".$sales[12].",".$sales[13].",".$sales[14].",".$sales[15].",".$sales[16].",".$sales[17].",".$sales[18].",".$sales[d_id].",".$sales[s_hpmax];
				}
			}else
			{
				$packs=array();
				$sum=0;
				$temp_item=array(); //裝備id
				$temp_item2=array(); //紀錄拍賣數量
				$temp_item9=array();
				$sale=$DB_site->query("select b.p_name,c.d_name,c.d_at,c.d_mat,c.d_df,c.d_mdf,c.d_mstr,c.d_magl,c.d_msmart,c.d_mau,a.e_money,a.s_id,a.s_money,a.dateline,d.ch_name,a.item_num,a.hs_id,c.d_hole
									,c.d_g_str,c.d_g_smart,c.d_g_agi,c.d_g_life,c.d_g_vit,c.d_g_au,c.d_g_be,c.skill,a.ps_id,a.d_id,c.d_plus
									 from wog_sale a,wog_player b,wog_df c left join wog_character d on d.ch_id=c.ch_id where a.s_type=1 and  a.p_id=b.p_id and a.d_id=c.d_id and c.d_fie =".$d_type." $serch_key ORDER BY a.s_money,a.dateline LIMIT ".$spage.",8 ");
				while($p2=$DB_site->fetch_array($sale))
				{
					$temp_item2[$sum][item_num]=$p2[item_num];
					$temp_item2[$sum][e_money]=$p2[e_money];
					$temp_item2[$sum][s_money]=$p2[s_money];
					$temp_item2[$sum][dateline]=$p2[dateline];
					$temp_item2[$sum][p_name]=$p2[p_name];
					$temp_item2[$sum][s_id]=$p2[s_id];
					$sum++;
					if($p2[skill]>0)
					{
						$sql="select a.s_name
							from wog_ch_skill a where a.s_id=".$p2[skill];
						$skill_main=$DB_site->query_first($sql);
					}
					else
					{
						$skill_main[s_name]="";
					}
					$temp_id=$p2[d_id];
					if(!empty($p2[hs_id])){$temp_id.=":".$p2[hs_id];}
					if(!empty($p2[ps_id])){$temp_id.="&".$p2[ps_id];}
					$packs[]=$temp_id;
					
					$d_status[$p2[d_id]][d_name]=$p2[d_name];
					$d_status[$p2[d_id]][s_name]=$skill_main[s_name];
	
					$d_status[$p2[d_id]][at]=$p2[d_at];
					$d_status[$p2[d_id]][mat]=$p2[d_mat];
					$d_status[$p2[d_id]][df]=$p2[d_df];
					$d_status[$p2[d_id]][mdf]=$p2[d_mdf];
					$d_status[$p2[d_id]][agi]=$p2[d_g_agi];
					$d_status[$p2[d_id]][life]=$p2[d_g_life];
					$d_status[$p2[d_id]][au]=$p2[d_g_au];
					$d_status[$p2[d_id]][be]=$p2[d_g_be];
					$d_status[$p2[d_id]][vit]=$p2[d_g_vit];
					$d_status[$p2[d_id]][smart]=$p2[d_g_smart];
					$d_status[$p2[d_id]][str]=$p2[d_g_str];
					
					$d_status[$p2[d_id]][d_mstr]=$p2[d_mstr];
					$d_status[$p2[d_id]][d_magl]=$p2[d_magl];
					$d_status[$p2[d_id]][d_msmart]=$p2[d_msmart];
					$d_status[$p2[d_id]][d_mau]=$p2[d_mau];
					$d_status[$p2[d_id]][d_send]=$p2[d_send];
					$d_status[$p2[d_id]][stone_name]="";
					$d_status[$p2[d_id]][ch_name]=$p2[ch_name];
					$d_status[$p2[d_id]][d_money]=$p2[d_money];
					$d_status[$p2[d_id]][d_s]=$p2[d_s];
					$d_status[$p2[d_id]][d_hole]=$p2[d_hole];
//					$d_status[$p2[d_id]][s_id]=$p2[s_id];
//					$d_status[$p2[d_id]][e_money]=$p2[e_money];
//					$d_status[$p2[d_id]][s_money]=$p2[s_money];
//					$d_status[$p2[d_id]][dateline]=$p2[dateline];
//					$d_status[$p2[d_id]][p_name]=$p2[p_name];
					$d_status[$p2[d_id]][d_plus]=$p2[d_plus];
				}
				$arm_array=get_arm_sp($packs,$sum,$temp_item,$temp_item9);

				$item_array=array();
				for($i=0;$i<$sum;$i++)
				{
					$len=$temp_item9[$i][3];
					$len2=$temp_item9[$i][4];
					$len3=$temp_item9[$i][5];
					switch(true)
					{
						case $len==$len2 && $len==$len3: //無鑲嵌,無精練
							$item_array=$d_status[$temp_item9[$i][0]];
						break;
						case $len!=$len2 && $len==$len3: //有鑲嵌,無精練
							$item_array=chk_item_status($d_status[$temp_item9[$i][0]],$arm_array[hs][$temp_item9[$i][1]],null);
						break;
						case $len==$len2 && $len!=$len3: //無鑲嵌,有精練
							$item_array=chk_item_status($d_status[$temp_item9[$i][0]],null,$arm_array[ps][$temp_item9[$i][2]]);
						break;
						case $len!=$len2 && $len!=$len3: //有鑲嵌,有精練
							$item_array=chk_item_status($d_status[$temp_item9[$i][0]],$arm_array[hs][$temp_item9[$i][1]],$arm_array[ps][$temp_item9[$i][2]]);
						break;
					}
					if($d_type>=5){$item_array[d_name].="*".$temp_item2[$i][item_num];}
					$item_array2[$i]=$temp_item2[$i][s_id].",".$packs[$i].","
						.$item_array[d_name].","//2
						.$item_array[at].","
						.$item_array[mat].","
						.$item_array[df].","
						.$item_array[mdf]."," //6
						.$item_array[str].","
						.$item_array[smart].","
						.$item_array[agi].","
						.$item_array[au].","
						.$item_array[vit].","
						.$item_array[be].","
						.$item_array[life]."," //13
						.$item_array[s_name]."," //14
						.$item_array[stone_name]."," //15
						.$item_array[d_mstr].","
						.$item_array[d_magl]."," //17
						.$item_array[d_msmart].","
						.$item_array[d_mau].","
						.$item_array[d_send]."," //20
						.$item_array[ch_name].","
						.$item_array[d_money]."," //22
						.$item_array[d_s].","
						.$item_array[d_hole]."," //24
//						.$item_array[s_id].","
						.$temp_item2[$i][e_money].","
						.$temp_item2[$i][s_money]."," //26
						.$temp_item2[$i][dateline]."," 
						.$temp_item2[$i][p_name]."," 
						.$item_array[d_plus]."," //29
						.$item_array[hp] //30
						;
				}
			}
		}
		$DB_site->free_result($sale);
		unset($sales,$temp_item2,$item_array,$d_status,$temp_item9,$arm_array);
		showscript("parent.sale_view($sale_total[0],".$_GET["page"].",'".implode(";",$item_array2)."',".$d_type.",'".$_GET["key"]."')");
		unset($sale_total,$item_array2);
	}
	function bid2_view()//顯示收購區資料
	{
		global $DB_site,$_GET,$a_id,$wog_arry;
		$ttime=time();
		$s3="";
		//回收收購區過期信息 begin
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$pack=$DB_site->query("select a.p_id,a.s_money from wog_sale a where a.dateline < ".$ttime." and a.s_type=2 for update");
		while($packs=$DB_site->fetch_array($pack))
		{
			$sql="update wog_player set p_money=p_money+".$packs[s_money]." where p_id=".$packs[p_id];
			$DB_site->query($sql);
		}
		$DB_site->free_result($pack);
		unset($temp_pack,$pack,$packs,$pack2);
		$DB_site->query("delete from wog_sale where dateline < ".$ttime." and s_type=2");
		$DB_site->query("delete from wog_pet where pe_st=1 and pe_s_dateline < ".$ttime);
		$DB_site->query_first("COMMIT");
		//回收收購區過期信息 end
		$serch_key="";
		if(empty($_GET["type"]) || !is_numeric($_GET["type"]))
		{
			$_GET["type"]="0";
		}
		$d_type=$_GET["type"];
		//if($d_type=="5"){$d_type="5,6";}
		if(!empty($_GET["key"]))
		{
			$serch_key=" and c.d_name like '%".$_GET["key"]."%'";
		}
		$sale_total=$DB_site->query_first("select count(a.s_id) as s_id from wog_sale a,wog_df c where a.s_type=2 and  a.d_id=c.d_id and c.d_fie =".$d_type." $serch_key");

		if(empty($_GET["page"]) || !is_numeric($_GET["page"]))
		{
			$_GET["page"]="1";
		}
		$spage=((int)$_GET["page"]*8)-8;
		$temp_s="";

		if($_GET["type"] == "7")
		{
			$sale=$DB_site->query("select b.p_name,c.d_name,d.s_at,d.s_mat,d.s_df,d.s_mdf,d.s_agl,d.s_str,d.s_life,d.s_vit,d.s_smart,d.s_au,d.s_be,c.d_money,a.s_id,a.s_money,a.dateline,a.item_num,a.hs_id from wog_sale a,wog_player b,wog_df c,wog_stone_list d  where a.p_id=b.p_id and a.d_id=c.d_id and d.d_id=a.d_id and c.d_type in (".$d_type.") and s_type=2 $serch_key ORDER BY a.s_money,a.dateline LIMIT ".$spage.",8 ");
			while($sales=$DB_site->fetch_array($sale))
			{
				$temp_s.=";".$sales[0].",".$sales[1].",".$sales[2].",".$sales[3].",".$sales[4].",".$sales[5].",".$sales[6].",".$sales[7].",".$sales[8].",".$sales[9].",".$sales[10].",".$sales[11].",".$sales[12].",".$sales[13].",".$sales[14].",".$sales[15].",".$sales[16].",".$sales[17].",".$sales[18];
			}
		}else
		{
			$sale=$DB_site->query("select b.p_name,c.d_name,c.d_at,c.d_mat,c.d_df,c.d_mdf,c.d_mstr,c.d_magl,c.d_msmart,c.d_mau,c.d_money,a.s_id,a.s_money,a.dateline,d.ch_name,a.item_num,a.hs_id,c.d_hole
								,c.d_g_str,c.d_g_smart,c.d_g_agi,c.d_g_life,c.d_g_vit,c.d_g_au,c.d_g_be,c.skill,c.d_plus
								 from wog_sale a,wog_player b,wog_df c left join wog_character d on d.ch_id=c.ch_id where a.p_id=b.p_id and a.d_id=c.d_id and c.d_fie =".$d_type." and s_type=2 $serch_key ORDER BY a.s_money,a.dateline LIMIT ".$spage.",8 ");
			while($sales=$DB_site->fetch_array($sale))
			{
				if($sales[25]>0)
				{
					$sql="select a.s_name
						from wog_ch_skill a where a.s_id=".$sales[25];
					$skill_main=$DB_site->query_first($sql);
				}
				else
				{
					$skill_main[s_name]="";
				}
				$temp_s.=";".$sales[0].",".$sales[1].",".$sales[2].",".$sales[3].",".$sales[4].",".$sales[5].",".$sales[6].",".$sales[7].",".$sales[8].",".$sales[9].",".$sales[10].",".$sales[11].",".$sales[12].",".$sales[13].",".$sales[14].",".$sales[15].",".$sales[16].",".$sales[17]
							.",".$sales[18].",".$sales[19].",".$sales[20].",".$sales[21].",".$sales[22].",".$sales[23].",".$sales[24].",".$skill_main[s_name].",".$sales[26];
			}
		}
		$DB_site->free_result($sale);
		unset($sales);
		$temp_s=substr($temp_s,1);
		showscript("parent.sale2_view($sale_total[0],".$_GET["page"].",'$temp_s',".$_GET["type"].",'".$_GET["key"]."')");
		unset($temp_s,$sale_total);
	}
	function bid2($user_id)
	{
		global $DB_site,$_POST,$wog_arry,$lang,$_GET;
		$money=(int)$_POST["money"];
		$day=(int)$_POST["day"];
		$item_num=(int)$_POST["item_num"];
		$d_name=$_POST["d_name"];
		$d_hole=$_POST["d_hole"];
		if(empty($d_hole)){$d_hole=0;}

		if(empty($item_num) || $item_num > 99 || $item_num < 1 )
		{
			alertWindowMsg($lang['wog_act_buy_errornum']);
		}
		if(empty($day) || empty($money) || empty($d_name))
		{
			alertWindowMsg($lang['wog_act_bid_nodate']);
		}
		if($money<$wog_arry["bid2_money"] || $day<0 || !is_numeric($_POST["money"]) || !is_numeric($_POST["day"]) )
		{
			alertWindowMsg($lang['wog_act_bid_nodate']);
		}
		if($day>$wog_arry["sale_day"])
		{
			alertWindowMsg(sprintf($lang['wog_act_bid_limitday'],$wog_arry["sale_day"]));
		}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select p_money from wog_player where p_id=$user_id for update";
		$p=$DB_site->query_first($sql);
		if($p[p_money] < $money){alertWindowMsg($lang['wog_act_nomoney']);}
		
		$sql="select d_id,d_fie,d_hole from wog_df where d_name='".$d_name."' and d_hole=$d_hole";
		$df=$DB_site->query_first($sql);
		if(!$df){alertWindowMsg($lang['wog_act_bid_err1']);}
		if($df[d_fie]<5){$item_num=1;}
		$sql="update wog_player set p_money=p_money-".$money." where p_id=$user_id";
		$DB_site->query($sql);
		$sql="insert into wog_sale(p_id,d_id,d_type,hs_id,item_num,s_money,s_type,dateline)values
			($user_id,$df[d_id],$df[d_fie],0,$item_num,$money,2,".(time()+($day*24*60*60)).")";
		$DB_site->query($sql);
		$DB_site->query_first("COMMIT");
		$_GET["type"]=$df[d_fie];
		$this->bid2_view();
		unset($d_money,$pay,$s2,$a_id);
	}
	function sale_buy_item2($user_id) //顯示身上有哪些物品可以給收購者
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_arry,$wog_item_tool;
		$s_id=$_POST["s_id"];
		if(empty($s_id))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$sql="select b.d_fie,b.d_id,a.p_id,a.s_money,b.d_name,a.hs_id,a.item_num from wog_sale a,wog_df b where a.s_id=$s_id and a.d_id=b.d_id and a.s_type=2";
		$pack=$DB_site->query_first($sql);
		if(!$pack)
		{
			alertWindowMsg($lang['wog_act_bid_buyed']);
		}
		$msg="";
		if($pack[d_fie]<5)//裝備類物品
		{
			check_type($pack[0],1);
			$sql="select ".$a_id." from wog_item where p_id=".$user_id;
			$eq=$DB_site->query_first($sql);
			$eq[0]=trim($eq[0]);
			$temp_eq=array();
			if(!empty($eq[0]))
			{
				$temp_eq=explode(",",$eq[0]);
				$packs=array();
				$sum=0;
				foreach($temp_eq as $value)
				{
					get_arm_id($value,$item_id,$hs_id,$ps_id);
					if($pack[d_id]==$item_id)
					{
						$sum++;
						$temp_item9=array();
						$packs[]=$value;
						if(!isset($d_status))//取得物品資料
						{
							$d_status=array();
							$sql="select a.d_id,a.d_df,a.d_mdf,a.d_money,a.d_name,a.d_at,a.d_mat,b.ch_name,a.d_send,a.d_hole,ifnull(a.d_s,0) as d_s
								,a.d_g_str,a.d_g_smart,a.d_g_agi,a.d_g_life,a.d_g_vit,a.d_g_au,a.d_g_be,a.skill,a.d_plus
								from wog_df a left join wog_character b on b.ch_id=a.ch_id  where a.d_id =$pack[d_id]";
							$p2=$DB_site->query_first($sql);
							if($df[skill]>0)
							{
								$sql="select a.s_name
									from wog_ch_skill a where a.s_id=".$p2[skill];
								$skill_main=$DB_site->query_first($sql);
							}
							else
							{
								$skill_main[s_name]="";
							}
							$d_status[d_name]=$p2[d_name];
							$d_status[s_name]=$skill_main[s_name];
							$d_status[at]=$p2[d_at];
							$d_status[mat]=$p2[d_mat];
							$d_status[df]=$p2[d_df];
							$d_status[mdf]=$p2[d_mdf];
							$d_status[agi]=$p2[d_g_agi];
							$d_status[life]=$p2[d_g_life];
							$d_status[au]=$p2[d_g_au];
							$d_status[be]=$p2[d_g_be];
							$d_status[vit]=$p2[d_g_vit];
							$d_status[smart]=$p2[d_g_smart];
							$d_status[str]=$p2[d_g_str];
							$d_status[d_send]=$p2[d_send];
							$d_status[stone_name]="";
							$d_status[ch_name]=$p2[ch_name];
							$d_status[d_money]=$p2[d_money];
							$d_status[d_s]=$p2[d_s];
							$d_status[d_hole]=$p2[d_hole];
							$d_status[d_plus]=$p2[d_plus];
						}
					}
				}
				$arm_array=get_arm_sp($packs,$sum,$temp_item,$temp_item9);
				
				$item_array=array();
				for($i=0;$i<$sum;$i++)
				{
					$len=$temp_item9[$i][3];
					$len2=$temp_item9[$i][4];
					$len3=$temp_item9[$i][5];
					switch(true)
					{
						case $len==$len2 && $len==$len3: //無鑲嵌,無精練
							$item_array=$d_status;
						break;
						case $len!=$len2 && $len==$len3: //有鑲嵌,無精練
							$item_array=chk_item_status($d_status,$arm_array[hs][$temp_item9[$i][1]],null);
						break;
						case $len==$len2 && $len!=$len3: //無鑲嵌,有精練
							$item_array=chk_item_status($d_status,null,$arm_array[ps][$temp_item9[$i][2]]);
						break;
						case $len!=$len2 && $len!=$len3: //有鑲嵌,有精練
							$item_array=chk_item_status($d_status,$arm_array[hs][$temp_item9[$i][1]],$arm_array[ps][$temp_item9[$i][2]]);
						break;
					}
					$item_array2[$i]=$packs[$i].","
						.$item_array[d_name].","//1
						.$item_array[at].","
						.$item_array[mat].","
						.$item_array[df].","
						.$item_array[mdf]."," //5
						.$item_array[str].","
						.$item_array[smart].","
						.$item_array[agi].","
						.$item_array[au].","
						.$item_array[vit]."," //10
						.$item_array[be].","
						.$item_array[life]."," //12
						.$item_array[s_name]."," //13
						.$item_array[stone_name]."," //14
						.$item_array[d_send]."," //15
						.$item_array[ch_name].","
						.$item_array[d_money]."," //17
						.$item_array[d_s].","
						.$item_array[d_hole]."," //19
						.$item_array[d_plus] //20
						;
				}
				
				if(count($item_array2)>0)
				{
					$msg=implode(";",$item_array2);
				}
			}
			showscript("parent.arm2_view('".$msg."','$a_id',$s_id)");			
		}
		else//道具類物品
		{
			//$_POST["type"]=$a_id;
			$this->sale_buy_item2_1($user_id);
		}

	}
	function sale_buy_item2_1($user_id) //把身上物品給收購者
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_arry,$wog_item_tool,$_GET;
		$s_id=$_POST["s_id"];
		$adds=$_POST["adds"];
		$hs_id=0;
		if(empty($s_id))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$sql="select b.d_fie,b.d_id,a.p_id,a.s_money,b.d_name,a.hs_id,a.ps_id,a.item_num,b.d_send from wog_sale a,wog_df b where a.s_id=$s_id and a.d_id=b.d_id and a.s_type=2 for update";
		$pack=$DB_site->query_first($sql);
		if(!$pack)
		{
			alertWindowMsg($lang['wog_act_bid_buyed']);
		}

		$sql="select p_bag from wog_player where p_id=$pack[p_id] for update";
		$pay_id=$DB_site->query_first($sql);
		if(!$pay_id)
		{
			alertWindowMsg($lang['wog_act_arm_nomove']);
		}
		if($pack[d_send]==1)
		{
			alertWindowMsg($lang['wog_act_arm_nosend']);
		}
		$d_fie=$pack[0];
		check_type($pack[0],1);
		$sql="select ".$a_id." from wog_item where p_id=".$user_id." for update";
		$df=$DB_site->query_first($sql);
		if($pack[d_fie]<5)
		{
			if(empty($adds)){alertWindowMsg($lang['wog_act_arm_noselect']);}
			get_arm_id($adds,$item_id,$hs_id,$ps_id);
			if($item_id!=$pack[d_id]){alertWindowMsg($lang['wog_act_arm_noselect']);}
		}
		else
		{
			$_POST["type"]=$pack[d_fie];
			$adds=$pack[d_id];
		}
		$item=$wog_item_tool->item_out($user_id,$adds,$pack[item_num],$df[0]);
		
		$sql="select p_name from wog_player where p_id=".$user_id;
		$pay=$DB_site->query_first($sql);
		$p_name=$pay[0];

		$temp_pack=array();
		if($user_id!=$pack[p_id])
		{
			$sql="select ".$a_id." from wog_item where p_id=$pack[p_id] for update";
			$pay=$DB_site->query_first($sql);
			if(!empty($pay[0]))
			{
				$temp_pack=explode(",",$pay[0]);
			}
		}
		else
		{
			$temp_pack=$item;
		}
		$adds=$wog_item_tool->item_in($temp_pack,$adds,$pack[item_num]);
		if($d_fie>4)
		{
			$bag=$wog_arry["item_limit"]+$pay_id[p_bag];
		}else
		{
			$bag=$wog_arry["item_limit"];
		}

		if(count($adds) > $bag)
		{
			alertWindowMsg($lang['wog_act_bid_full']);
		}
		if(!empty($hs_id))
		{
			$DB_site->query("update wog_stone_setup set p_id=".$pack[p_id]." where hs_id=".$hs_id);
		}
		if(!empty($ps_id))
		{
			$DB_site->query("update wog_plus_setup set p_id=".$pack[p_id]." where ps_id=".$hs_id);
		}
		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$item)."' where p_id=".$user_id);
		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$adds)."' where p_id=$pack[p_id]");
		$DB_site->query("update wog_player set p_money=p_money+".$pack[s_money]." where p_id=".$user_id);
		$DB_site->query("insert into wog_message(p_id,title,dateline)values(".$pack[p_id].",'".sprintf($lang['wog_act_bid_msg1'],$p_name,$pack[d_name]."*".$pack[item_num],$pack[s_money])." ',".time().")");
		$DB_site->query("delete from wog_sale where s_id=".$s_id);
		$DB_site->query_first("COMMIT");
		unset($pack,$adds,$item,$a_id,$temp_pack,$pay_id,$temp);
		$_GET["type"]=$_POST["type"];
		$this->bid2_view();
	}
}
?>