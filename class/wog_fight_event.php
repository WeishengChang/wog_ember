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

class wog_fight_event{
	var $temp_fight_string="";
	var $mercenary_topr=1;
	var $mercenary_get="";
	//var $topr_vip = 1;//掉寶率參數
//########## get_item begin   ##########
	function get_item($user_id,$m_id,$m_topr,$p_st,$p_bag)
	{
		global $DB_site,$a_id,$wog_arry,$wog_item_tool;
		$temp_m_id=explode(",",$m_id);
		$temp_m_topr=explode(",",$m_topr);
		$temp_get_item="";
		$top_get=0;
		$topr_vip = 0.9;//掉寶率參數
		switch($p_st){
			case 1:
				$topr_vip=0.8;
				break;
			case 3:
				$topr_vip=0.7;
				break;
		} // switch
		for($i=0;$i<count($temp_m_topr);$i++)
		{
			if($this->mercenary_topr > 1)
			{
				$top_get=((($temp_m_topr[$i]*(100-$this->mercenary_topr))/100)+$temp_m_topr[$i])*$topr_vip;
			}
			else
			{
				$top_get=$temp_m_topr[$i]*$topr_vip;
			}
			if(rand(1,$top_get) <=1 && $temp_m_id[$i])
			{
				$temp_get_item.=",".$temp_m_id[$i];
			}
		}
		if(empty($temp_get_item))
		{
			return;
		}
		else
		{
			$temp_get_item=substr($temp_get_item,1);
		}
		//$DB_site->query_first("set autocommit=0");
		//$DB_site->query_first("BEGIN");
		$sql="select a_id,d_body_id,d_foot_id,d_hand_id,d_head_id,d_item_id,d_stone_id,d_plus_id from wog_item where p_id=".$user_id." for update";
		$p_item=$DB_site->query_first($sql);
		$sql="select d_id,d_name,d_fie,d_vip from wog_df where d_id in (".$temp_get_item.")";
		$item=$DB_site->query($sql);
		$temp_sql="";
		$temp_pack=array();
		$temp_pack2=array();
		while($items=$DB_site->fetch_array($item))
		{
			//if($items[d_vip]==1 && $p_st==0)
			//{
				//break;
			//	continue;
			//}
			$a_id=type_name($items[d_fie]);
			$p_item[$a_id]=trim($p_item[$a_id]);
			if(!empty($p_item[$a_id]))
			{
				$temp_pack[$a_id]=explode(",",$p_item[$a_id]);
			}
			if($items[d_fie] > 4)
			{
				$bag=$wog_arry["item_limit"]+$p_bag;
				$temp_pack2[$a_id]=$temp_pack[$a_id];
				$temp_pack[$a_id]=$wog_item_tool->item_in($temp_pack[$a_id],$items[d_id],1);
				if(count($temp_pack[$a_id]) <= $bag)
				{
					$this->temp_fight_string.=",\"parent.get_item('$items[d_name]',1)\"";
					$this->mercenary_get.="，".$items[d_name];
				}
				else
				{
					$temp_pack[$a_id]=$temp_pack2[$a_id];
					$this->temp_fight_string.=",\"parent.get_item('$items[d_name]',0,$bag)\"";
				}
			}else
			{
				$bag=$wog_arry["item_limit"];
				if(count($temp_pack[$a_id])+1 <= $bag)
				{
					$temp_pack[$a_id]=$wog_item_tool->item_in($temp_pack[$a_id],$items[d_id]);
					$this->temp_fight_string.=",\"parent.get_item('$items[d_name]',1)\"";
					$this->mercenary_get.="，".$items[d_name];
				}
				else
				{
					$this->temp_fight_string.=",\"parent.get_item('$items[d_name]',0,$bag)\"";
				}
			}
			$p_item[$a_id]=implode(',',$temp_pack[$a_id]);
		}
		unset($p_item,$a_id,$item);
		$DB_site->free_result($items);
		foreach($temp_pack as $key=>$value)
		{
			$temp_sql.=",".$key."='".implode(',',$value)."'";
		}
		if(!empty($temp_sql))
		{
			$temp_sql=substr($temp_sql,1);
			$DB_site->query("update wog_item set ".$temp_sql." where p_id=".$user_id);
		}
		//$DB_site->query_first("COMMIT");
		//$DB_site->query_first("set autocommit=1");
		unset($temp_pack);
	}
	function exchange_up(&$p,&$m,$type)
	{
		global $DB_site,$lang;
		$sql="select ex_id,ex_name,ex_money from wog_exchange_main ORDER BY RAND() LIMIT 1";
		$exchange_check=$DB_site->query_first($sql);
		$time=time();
		if($m[m_meet] >= 20)
		{
			$temp_ex1=rand(1,5);
		}
		else
		{
			$temp_ex1=rand(0,2);
		}
		if($type==1)
		{
			$temp_ex4=rand(100,101);
		}
		else
		{
			$temp_ex4=rand(1,6);
		}
		$temp_ex2=floatval($temp_ex1.".".rand(1,99));
		$exchange_check[ex_money]=$exchange_check[ex_money]+(($exchange_check[ex_money]*$temp_ex2)/100);
		$sql="update wog_exchange_main set ex_money=".$exchange_check[ex_money].",ex_change=1,ex_chg_num=".$temp_ex2.",ex_change_time=".$time." where ex_id=".$exchange_check[ex_id];
		$DB_site->query($sql);
		$temp_str=sprintf($lang['wog_fight_change_mg'.$temp_ex4],$p[p_name],$m[m_name],$exchange_check[ex_name],$exchange_check[ex_name],"$temp_ex2%");
		$temp_chat=$temp_str;
		$sql="insert wog_exchange_book(ex_id,eb_body,eb_time)values($exchange_check[ex_id],'$temp_str',$time)";
		$DB_site->query($sql);
		$temp_str=$DB_site->insert_id();
		$sql="delete from wog_exchange_book where eb_id <= ".($temp_str-8);
		$DB_site->query($sql);
/*
		require_once("./class/wog_act_message.php");
		$wog_act_class = new wog_act_message;
		$wog_act_class->system_chat("<font color=\"red\">".$temp_chat."</font>");
		unset($wog_act_class);
*/
		unset($temp_ex1,$temp_ex2,$temp_ex4);
	}
	function exchange_down(&$p,&$m,$type)
	{
		global $DB_site,$lang;
		$sql="select ex_id,ex_name,ex_money from wog_exchange_main ORDER BY RAND() LIMIT 1";
		$exchange_check=$DB_site->query_first($sql);
		$time=time();
		if($m[m_meet] >= 10)
		{
			$temp_ex1=rand(1,5);
		}
		else
		{
			$temp_ex1=rand(0,2);
		}
		$temp_ex2=floatval($temp_ex1.".".rand(1,99));
		$exchange_check[ex_money]=$exchange_check[ex_money]-(($exchange_check[ex_money]*$temp_ex2)/100);
		if($type==1)
		{
			$temp_ex4=rand(50,52);
			$temp_str=sprintf($lang['wog_fight_change_mg'.$temp_ex4],$p[p_name],$m[m_name],$exchange_check[ex_name],$exchange_check[ex_name],"$temp_ex2%");
		}
		else
		{
			$temp_ex4=rand(53,61);
			$temp_str=sprintf($lang['wog_fight_change_mg'.$temp_ex4],$m[m_name],$p[p_name],$exchange_check[ex_name],$exchange_check[ex_name],"$temp_ex2%");
		}
		$sql="";
		$sql="update wog_exchange_main set ex_money=".$exchange_check[ex_money].",ex_change=2,ex_chg_num=".$temp_ex2.",ex_change_time=".$time." where ex_id=".$exchange_check[ex_id];
		$DB_site->query($sql);
		$temp_chat=$temp_str;
		$sql="insert wog_exchange_book(ex_id,eb_body,eb_time)values($exchange_check[ex_id],'$temp_str',$time)";
		$DB_site->query($sql);
		$temp_str=$DB_site->insert_id();
		$sql="delete from wog_exchange_book where eb_id <= ".($temp_str-8);
		$DB_site->query($sql);
/*
		require_once("./class/wog_act_message.php");
		$wog_act_class = new wog_act_message;
		$wog_act_class->system_chat("<font color=\"red\">".$temp_chat."</font>");
		unset($wog_act_class);
*/
		unset($temp_ex1,$temp_ex2,$temp_ex4);
	}
//########## event_start  ##########
	function event_start($user_id,$p_attempts)
	{
		global $DB_site,$wog_arry,$lang;
		if($p_attempts>$wog_arry["attempts"])
		{
			$DB_site->query("update wog_player set p_lock=1,p_lock_time=".time()." where p_id=".$user_id);
			$DB_site->query_first("COMMIT");
			cookie_clean();
			alertWindowMsg(sprintf($lang['wog_fight_event_lock'],$wog_arry["unfreeze"]));
		}else
		{
//			$get_key=$this->gen_key();
			$DB_site->query("update wog_player set p_attempts=p_attempts+1 where p_id=".$user_id);
			$DB_site->query_first("COMMIT");
		}
		showscript("parent.event();");
	}
//########## event_creat  ##########
	function event_creat($user_id)
	{
		global $DB_site,$wog_arry;
//		$get_key=$this->gen_key();
		$DB_site->query("update wog_player set p_attempts=1  where p_id=".$user_id);
		$DB_site->query_first("COMMIT");
		showscript("parent.event();");
	}
	//Gen Key.
	function gen_key()
	{
		global $wog_arry;
//		$key = "";
		//u can add your own image below here.
		$confirm_chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',  'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',  'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9');

		list($usec, $sec) = explode(' ', microtime());
		mt_srand($sec * $usec);

		$max_chars = count($confirm_chars) - 1;
		$key = '';
		for ($i = 0; $i < $wog_arry["keylen"]; $i++)
		{
			$key .= $confirm_chars[mt_rand(0, $max_chars)];
		}
/*
		$count = count($chars) - 1;
		srand((double)microtime()*1000000);
		for($i = 0; $i < $wog_arry["keylen"]; $i++)
		{
			$key .= $chars[rand(0, $count)];
		}
*/
		return($key);
	}


//########## pet_stats  ##########

	function pet_stats($d_id)
	{
		switch($d_id)
		{
			case 258:
				$lv=1;
			break;
			case 1837:
				$lv=8;
			break;
			case 2236:
				$lv=13;
			break;
		}
		$pet[pe_at]=rand(1,10)*$lv;
		$pet[pe_mt]=rand(1,10)*$lv;
		$pet[pe_def]=rand(1,10)*$lv;
		$pet[pe_type]=rand(1,4);
		return $pet;
	}
/*
//####################   Gen Image with GD   ####################
	//####################   GD JPEG   ####################
	function img_gd_jpeg($name,$words){
		$words_size=strlen($words);
		for($i=0;$i<$words_size;$i++)
		{
			$this_number[$i]=substr($words,$i,1);
			$this_size_temp=getimagesize("anti-bot/validation_".$this_number[$i].".jpg");
			$this_width[$i]=$this_size_temp[0];
			$this_height[$i]=$this_size_temp[1];
			$image_width+=$this_size_temp[0];
		}
		$image_height=max($this_height);

		$base=@imagecreate($image_width,$image_height);
		if(!$base){header("location:anti-bot/error2.jpg");exit;}

		for($i=0;$i<$words_size;$i++)
		{
			$image=imagecreatefromjpeg("anti-bot/validation_".$this_number[$i].".jpg") or die("false 2");
			imagecopyresized($base,$image,0+$sum_width,0,0,0,$this_width[$i],$this_height[$i],$this_width[$i],$this_height[$i]) or die("false 3");
			$sum_width+=$this_width[$i];
			imagedestroy($image);
		}
		imagejpeg($base,"${name}.jpg");
		imagedestroy($base);
	}

//####################   GD GIF   ####################
	function img_gd_gif($name,$words){
		$words_size=strlen($words);

		for($i=0;$i<$words_size;$i++)
		{
			$this_number[$i]=substr($words,$i,1);
			$this_size_temp=getimagesize("anti-bot/validation_".$this_number[$i].".gif");
			$this_width[$i]=$this_size_temp[0];
			$this_height[$i]=$this_size_temp[1];
			$image_width+=$this_size_temp[0];
		}
		$image_height=max($this_height);

		$base=@imagecreate($image_width,$image_height);
		if(!$base){header("location:anti-bot/error2.gif");exit;}

		for($i=0;$i<$words_size;$i++)
		{
			$image=imagecreatefromgif("anti-bot/validation_".$this_number[$i].".gif") or die("false 2");
			imagecopyresized($base,$image,0+$sum_width,0,0,0,$this_width[$i],$this_height[$i],$this_width[$i],$this_height[$i]) or die("false 3");
			$sum_width+=$this_width[$i];
			imagedestroy($image);
		}
		imagegif($base,"${name}.gif");
		imagedestroy($base);
	}
//####################   Gen Image without GD   ####################
	function img_no_gd($words)
	{
		global $antibot;
		$words_size=strlen($words);
		for($i = 0; $i < $words_size; $i++)
		{
		   $validation_images .= "<img src=http://www.233.idv.tw/wog/" . 'anti-bot/validation_' . $words{$i} .".". $wog_arry["file_type"] . '>';
		}
		return $validation_images;
	}
*/
}

?>