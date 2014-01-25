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

class wog_act_skill{
	function skill_skill_list($user_id,$ch_id)
	{
		global $DB_site,$lang;
		if(empty($ch_id))
		{
			alertWindowMsg($lang['wog_act_job_noselect']);
		}
		$sql="select a.sk_$ch_id
		from wog_ch_exp a where p_id=".$user_id;
		$ch_exp=$DB_site->query_first($sql);

		$skill_bf=array();
		$skill_bf_main=array();
		$skill_main=array();
		
		$sql="select a.s_id,a.s_name,a.s_sp,a.ch_exp,a.s_text,a.s_money,a.s_count,a.s_lv,a.need_sid,b.p_id,a.main_sid,a.type,a.stime
			from wog_ch_skill a left join wog_skill_book b on b.p_id=".$user_id." and a.main_sid=b.main_sid 
			where a.ch_id=".$ch_id." 
			and ((b.p_id is null and (a.need_sid=0 or a.s_lv=1)) or (b.p_id is not null and a.s_lv > b.s_lv)) and a.s_master=0 order by a.s_id,a.s_lv asc";
		$p=$DB_site->query($sql);		
		while($ps=$DB_site->fetch_array($p))
		{
			if($ps[s_lv]==1 && !in_array($ps[need_sid],$skill_bf) && $ps[need_sid]>0)
			{
				$skill_bf[]=$ps[need_sid];
				$skill_bf_main[$ps[need_sid]][s_id]=$ps[s_id];
				$skill_bf_main[$ps[need_sid]][s_name]=$ps[s_name];
				$skill_bf_main[$ps[need_sid]][s_sp]=$ps[s_sp];
				$skill_bf_main[$ps[need_sid]][ch_exp]=$ps[ch_exp];
				$skill_bf_main[$ps[need_sid]][s_text]=$ps[s_text];
				$skill_bf_main[$ps[need_sid]][s_money]=$ps[s_money];
				$skill_bf_main[$ps[need_sid]][s_count]=$ps[s_count];
				$skill_bf_main[$ps[need_sid]][s_lv]=$ps[s_lv];
				$skill_bf_main[$ps[need_sid]][main_sid]=$ps[main_sid];
				$skill_bf_main[$ps[need_sid]][type]=$ps[type];
				$skill_bf_main[$ps[need_sid]][stime]=$ps[stime];
			}
			if(($ps[s_lv]>1 && !isset($skill_main[$ps[s_id]])) || $ps[need_sid]==0)
			{
				if(isset($skill_main[$ps[main_sid]]) && $skill_main[$ps[main_sid]][s_lv] <= $ps[s_lv])
				{
					continue;
				}
				$skill_main[$ps[main_sid]][s_id]=$ps[s_id];
				$skill_main[$ps[main_sid]][s_name]=$ps[s_name];
				$skill_main[$ps[main_sid]][s_sp]=$ps[s_sp];
				$skill_main[$ps[main_sid]][ch_exp]=$ps[ch_exp];
				$skill_main[$ps[main_sid]][s_text]=$ps[s_text];
				$skill_main[$ps[main_sid]][s_money]=$ps[s_money];
				$skill_main[$ps[main_sid]][s_count]=$ps[s_count];
				$skill_main[$ps[main_sid]][s_lv]=$ps[s_lv];
				$skill_main[$ps[main_sid]][type]=$ps[type];
				$skill_main[$ps[main_sid]][stime]=$ps[stime];				
			}			
		}
		if(count($skill_bf)>0)
		{
			$sql="select a.s_id,a.main_sid from wog_ch_skill a,wog_skill_book b where b.p_id=$user_id and a.s_id in (".implode(",",$skill_bf).") and b.main_sid=a.main_sid and b.s_lv>=a.s_lv ";
			$p=$DB_site->query($sql);		
			while($ps=$DB_site->fetch_array($p))
			{
				$skill_main[$skill_bf_main[$ps[s_id]][main_sid]][s_id]=$skill_bf_main[$ps[s_id]][s_id];
				$skill_main[$skill_bf_main[$ps[s_id]][main_sid]][s_name]=$skill_bf_main[$ps[s_id]][s_name];
				$skill_main[$skill_bf_main[$ps[s_id]][main_sid]][s_sp]=$skill_bf_main[$ps[s_id]][s_sp];
				$skill_main[$skill_bf_main[$ps[s_id]][main_sid]][ch_exp]=$skill_bf_main[$ps[s_id]][ch_exp];
				$skill_main[$skill_bf_main[$ps[s_id]][main_sid]][s_text]=$skill_bf_main[$ps[s_id]][s_text];
				$skill_main[$skill_bf_main[$ps[s_id]][main_sid]][s_money]=$skill_bf_main[$ps[s_id]][s_money];
				$skill_main[$skill_bf_main[$ps[s_id]][main_sid]][s_count]=$skill_bf_main[$ps[s_id]][s_count];
				$skill_main[$skill_bf_main[$ps[s_id]][main_sid]][s_lv]=$skill_bf_main[$ps[s_id]][s_lv];
				$skill_main[$skill_bf_main[$ps[s_id]][main_sid]][type]=$skill_bf_main[$ps[s_id]][type];
				$skill_main[$skill_bf_main[$ps[s_id]][main_sid]][stime]=$skill_bf_main[$ps[s_id]][stime];
			}			
		}
		$DB_site->free_result($p);
		unset($ps);
		$temp="";
		if(count($skill_main) > 0)
		{
			$sk=array();
			foreach($skill_main as $val)
			{
				$sk[]=$val[s_id].",".$val[s_name]
					.",".$val[s_sp].",".$val[ch_exp]
					.",".$val[s_text].",".$val[s_money]
					.",".$val[s_count].",".$val[s_lv]
					.",".$val[type].",".$val[stime];
			}
			$temp=implode(";",$sk);
		}
		showscript("parent.skill_list('$temp',$ch_exp[0])");
	}
	function skill_get($user_id,$s_id)
	{
		global $DB_site,$lang;

		$sql="select p_id from wog_skill_book where s_id=".$s_id." and p_id=".$user_id;
		$need=$DB_site->query_first($sql);
		if($need)
		{
			alertWindowMsg($lang['wog_act_skill_er1']);
		}

		$sql="select ch_exp,s_money,ch_id,main_sid,need_sid,s_lv from wog_ch_skill where s_id=".$s_id." and s_master=0 ";
		$need=$DB_site->query_first($sql);
		if(!$need)
		{
			alertWindowMsg($lang['wog_act_skill_er1']);
		}
		if($need["need_sid"] > 0)
		{
			$sql="select s_name,s_lv,main_sid from wog_ch_skill where s_id=".$need["need_sid"];
			$need2=$DB_site->query_first($sql);

			$sql="select s_lv from wog_skill_book where main_sid=".$need2["main_sid"]." and p_id=".$user_id;
			$need3=$DB_site->query_first($sql);
			if(!$need2)
			{
				alertWindowMsg(sprintf($lang['wog_act_skill_er5'],($need2[s_name]."LV".$need2[s_lv])));
			}else
			{
				if($need3[s_lv] < $need2[s_lv])
				{
					alertWindowMsg(sprintf($lang['wog_act_skill_er5'],($need2[s_name]."LV".$need2[s_lv])));				
				}
			}
		}
		$my_chexp=$DB_site->query_first("SELECT sk_".$need["ch_id"]." FROM wog_ch_exp WHERE p_id=".$user_id);
		if($need["ch_exp"] > $my_chexp[0])
		{
			alertWindowMsg($lang['wog_act_skill_er1']);
		}

		$sql="select p_money from wog_player where p_id=".$user_id;
		$my_money=$DB_site->query_first($sql);
		if($need["s_money"] > $my_money[0])
		{
			alertWindowMsg($lang['wog_act_syn_error8']);
		}
		$sql="update wog_player set p_money=p_money-".$need["s_money"]." where p_id=".$user_id;
		$DB_site->query($sql);
		$sql="update wog_ch_exp set sk_".$need["ch_id"]."=sk_".$need["ch_id"]."-".$need["ch_exp"]." where p_id=".$user_id;
		$DB_site->query($sql);
		if($need[main_sid]!=27 && $need[main_sid]!=28 )
		{
			$sql="select p_id from wog_skill_book where p_id=".$user_id." and main_sid=".$need[main_sid];
			$chk=$DB_site->query_first($sql);
		}
		if($chk)
		{
			$sql="update wog_skill_book set s_lv=".$need[s_lv].",s_id=".$s_id." where main_sid=".$need[main_sid]." and p_id=".$user_id;
		}else
		{
			$sql="insert wog_skill_book(p_id,s_id,main_sid,s_lv)values($user_id,$s_id,".$need["main_sid"].",$need[s_lv])" ;
		}
		$DB_site->query($sql);
		//showscript("parent.job_end(13)");
		$this->skill_skill_list($user_id,$need[ch_id]);
		unset($need,$my_money,$my_chexp);
	}
	function skill_view($user_id)
	{
		global $DB_site,$lang,$_POST;
		$s="";
		$s3="";
		$sql="select skill_1,skill_2,skill_3,skill_4,skill_5,time_1,time_2,time_3 from wog_skill_setup where p_id=".$user_id;
		$my_skill=$DB_site->query_first($sql);
		for($i=1;$i<6;$i++)
		{
			if(!empty($my_skill["skill_".$i]))
			{
				$sql="select b.s_name,b.s_text,b.s_sp,b.s_count,b.s_lv,b.type,b.stime from wog_ch_skill b where b.s_id=".$my_skill["skill_".$i];
				$eq_skill=$DB_site->query_first($sql);
				if($eq_skill[type]==0)
				{
					$s.=";".$my_skill["skill_".$i].",".($eq_skill["s_name"]."LV".$eq_skill["s_lv"]).",".$eq_skill["s_sp"].",".$eq_skill["s_text"].",".$eq_skill["s_count"].",".$my_skill["time_".$i].",".$i;
				}else
				{
					$s3.=";".$my_skill["skill_".$i].",".($eq_skill["s_name"]."LV".$eq_skill["s_lv"]).",".$eq_skill["s_sp"].",".$eq_skill["s_text"].",".$eq_skill["s_count"].",".$eq_skill["stime"].",".$i;
				}
			}else
			{
				if($i<4)
				{
					$s.=";".$i;
				}else
				{
					$s3.=";".$i;
				}
			}
		}
		$ch_id=$_POST[temp_id];
		if(!empty($ch_id))
		{
			$sql="select a.s_id,b.s_name,b.s_text,b.s_sp,b.s_count,b.s_lv,b.type,b.stime from wog_skill_book a,wog_ch_skill b where a.p_id=".$user_id." and a.s_id=b.s_id and b.ch_id=".$ch_id." order by b.s_name,b.s_lv";
			$s_book=$DB_site->query($sql);
			$s2="";
			while($s_books=$DB_site->fetch_array($s_book))
			{
				$s2.=";".$s_books[s_id].",".($s_books[s_name]."LV".$s_books[s_lv]).",".$s_books[s_sp].",".$s_books[s_text].",".$s_books[s_count].",".$s_books[type].",".$s_books[stime];
			}
		}
		if(!empty($s)){$s=substr($s,1);}
		if(!empty($s2)){$s2=substr($s2,1);}
		if(!empty($s3)){$s3=substr($s3,1);}
		$DB_site->free_result($s_book);
		unset($my_skill,$eq_skill,$s_books);
		showscript("parent.skill_view('$s','$s3','$s2')");
	}

	function skill_change_use($user_id)
	{
		global $DB_site,$lang,$_POST;
		if($_POST["p_time_1"]==null)
		{
			$_POST["p_time_1"]=0;
		}
		if($_POST["p_time_2"]==null)
		{
			$_POST["p_time_2"]=0;
		}
		if($_POST["p_time_3"]==null)
		{
			$_POST["p_time_3"]=0;
		}
		if(!is_numeric($_POST["p_time_1"]) || !is_numeric($_POST["p_time_2"]) || !is_numeric($_POST["p_time_3"]))
		{
			alertWindowMsg($lang['wog_act_errdate']);
		}
		$sql="update wog_skill_setup set time_1=".$_POST["p_time_1"].",time_2=".$_POST["p_time_2"].",time_3=".$_POST["p_time_3"]." where p_id=".$user_id;
		$DB_site->query($sql);
		showscript("parent.wog_message_box(11,0,1);");
	}
	function skill_setup($user_id)
	{
		global $DB_site,$lang,$_POST;
		$s_id=$_POST[s_eq_id];
		$type=$_POST[s_id];
		if(empty($s_id) || $type > 6)
		{
			alertWindowMsg($lang['wog_act_skill_er2']);
		}
		if(empty($type))
		{
			alertWindowMsg($lang['wog_act_skill_er6']);
		}
		$sql="select s_id from wog_skill_book where p_id=$user_id and s_id =".$s_id;
		$sk_chk=$DB_site->query_first($sql);
		if(!$sk_chk)
		{
			alertWindowMsg($lang['wog_act_skill_er2']);
		}

		$sql="select ch_id,main_sid,s_lv,type from wog_ch_skill where s_id=".$s_id;
		$need=$DB_site->query_first($sql);
		if(!$need)
		{
			alertWindowMsg($lang['wog_act_skill_er2']);
		}
		if($need[type]==1)
		{
			if($type<4)
			{
				alertWindowMsg($lang['wog_act_skill_er8']);
			}
			$p=$DB_site->query_first("SELECT ch_id FROM wog_player WHERE p_id=".$user_id);
			if($p["ch_id"] != $need[ch_id])
			{
				alertWindowMsg($lang['wog_act_skill_er7']);
			}
		}else
		{
			if($type>3)
			{
				alertWindowMsg($lang['wog_act_skill_er8']);
			}
		}
/*
		$my_chexp=$DB_site->query_first("SELECT ch_".$need["ch_id"]." FROM wog_ch_exp WHERE p_id=".$user_id);
		if($need["ch_exp"] > $my_chexp[0])
		{
			alertWindowMsg($lang['wog_act_skill_er2']);
		}
*/
		$s="";
		for($i=1;$i<6;$i++)
		{
			if($i!=$type)
			{
				$s=$s.",skill_".$i;
			}
		}
		$s=substr($s,1);
		$sql="select ".$s." from wog_skill_setup where p_id=".$user_id;
		$p_skill=$DB_site->query_first($sql);

		$sql="select main_sid from wog_ch_skill where s_id in (".$p_skill[0].",".$p_skill[1].",".$p_skill[2].",".$p_skill[3].")";
		$main_sid=$DB_site->query($sql);
		while($main_sids=$DB_site->fetch_array($main_sid))
		{
			if($need["main_sid"]==$main_sids["main_sid"])
			{
				alertWindowMsg($lang['wog_act_skill_er3']);
			}
		}
		$DB_site->free_result($main_sid);

		$sql="update wog_skill_setup set skill_".$type."=".$s_id.",time_".$type."=0 where p_id=".$user_id;
		$DB_site->query($sql);
		//showscript("parent.job_end(23)");
		$this->skill_view($user_id);
		unset($main_sids,$need,$my_chexp);
	}
	function skill_unset($user_id,$eq_id)
	{
		global $DB_site,$lang;
		if(empty($eq_id))
		{
			alertWindowMsg($lang['wog_act_skill_er4']);
		}
		$sql="update wog_skill_setup set skill_".$eq_id."=0,time_".$eq_id."=0 where p_id=".$user_id;
		$DB_site->query($sql);
		$this->skill_view($user_id);
		//showscript("parent.job_end(3)");
	}
	function skill_rview($user_id)
	{
		global $DB_site,$lang,$_POST;
		$ch_id=$_POST[temp_id];
		if(!empty($ch_id))
		{
			$sql="select a.s_id,b.s_name,b.s_text,b.s_sp,b.s_count,b.s_lv,b.type,b.stime from wog_skill_book a,wog_ch_skill b where a.p_id=".$user_id." and a.s_id=b.s_id and b.ch_id=".$ch_id." order by b.s_name,b.s_lv";
			$s_book=$DB_site->query($sql);
			$s2="";
			while($s_books=$DB_site->fetch_array($s_book))
			{
				$s2.=";".$s_books[s_id].",".($s_books[s_name]."LV".$s_books[s_lv]).",".$s_books[s_sp].",".$s_books[s_text].",".$s_books[s_count].",".$s_books[type].",".$s_books[stime];
			}
		}		
		if(!empty($s2)){$s2=substr($s2,1);}
		$DB_site->free_result($s_book);
		unset($s_books);
		showscript("parent.skill_rview('$s2')");
	}
}
?>