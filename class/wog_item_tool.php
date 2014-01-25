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

class wog_item_tool{
function dataCheck(&$arr,&$user_id)
{
	global $DB_site,$lang,$a_id;
	if($arr!=null){
		$item[0]=$arr;
	}else{
		$sql="select ".$a_id." from wog_item where p_id=".$user_id;
		$item=$DB_site->query_first($sql);
	}
	if($item[0]=='N/A' || $item[0]==''){
		alertWindowMsg($lang['wog_act_errwork']);
	}
	if(!is_array($item[0])){
		$item[0]=explode(',',$item[0]);
	}
	return $item[0];
}
//################## item_out ################
function item_out($user_id,$item_id,$item_num=1,$item_now=null,$del_stone=false,$mission=false)
{
	global $lang,$a_id,$wog_arry,$DB_site;
	$temp_pack=$this->dataCheck($item_now,$user_id);
	$temp_pack2 = array();
	if($a_id=='d_item_id' || $a_id=='d_stone_id' || $a_id=='d_honor_id' || $a_id=='d_key_id' || $a_id=='d_plus_id'){
		$adds=$item_id;
		$buy_num=$item_num;
		if($item_num < 0){
			alertWindowMsg($lang['wog_act_errdate']);
		}
		for($i=0;$i<count($temp_pack);$i++){
			$temp_packs=explode("*",$temp_pack[$i]);
			if(empty($temp_packs[0])){continue;}
			if($temp_packs[0] == $adds){
				$remain_num = $temp_packs[1]-$buy_num;
				if($remain_num < 1){
					$buy_num -= $temp_packs[1];
				}else{
					$temp_pack2[]=$temp_packs[0].'*'.$remain_num;
					$buy_num=0;
				}
			}else{
				$temp_pack2[]=$temp_packs[0].'*'.$temp_packs[1];
			}
		}
		if($buy_num > 0){

			alertWindowMsg($lang['wog_act_errnum']."--".$buy_num);
		}
		return $temp_pack2;
	}else{
		if($item_num < 0 || $item_num > 1){
			alertWindowMsg($lang['wog_act_errdate']);
		}
		$adds=explode(",",$item_id);
		for($i=0;$i<count($adds);$i++){
			$chks=false;
			for($j=0;$j<count($temp_pack);$j++){
				if(empty($temp_pack[$j])){continue;}
				
				if($del_stone)
				{
					get_arm_id($temp_pack[$j],$item_id2,$hs_id,$ps_id);
					if($adds[$i]!=$item_id2 || $chks){
						$temp_pack2[]=$temp_pack[$j];
					}else{
						if(!empty($hs_id)){$this->del_stone($hs_id);}
						if(!empty($ps_id)){$this->del_plus($ps_id);}
						$chks=true;
					}
				}
				else
				{
					if($adds[$i]!=$temp_pack[$j] || $chks){
						$temp_pack2[]=$temp_pack[$j];
					}else{
						$chks=true;
					}					
				}
			}
			if(!$chks){
				if($mission) // 檢查打洞後的武器
				{
					$sql=" select d_name,d_hole from wog_df where d_id=".$item_id;
					$d_name=$DB_site->query_first($sql);
					if($d_name)
					{
						$sql="select d_id from wog_df where d_name='".$d_name[d_name]."' and d_hole > ".$d_name[d_hole]." order by d_hole limit 1";
						$d_chk=$DB_site->query_first($sql);
						if($d_chk)
						{
							unset($d_name);
							$temp_pack2=$this->item_out($user_id,$d_chk[d_id],1,$item_now,true,true);
						}else
						{
							alertWindowMsg($lang['wog_act_errnum']);
						}
					}else
					{
						alertWindowMsg($lang['wog_act_errnum']);
					}
				}else
				{
					alertWindowMsg($lang['wog_act_errnum']);
				}
			}
		}
		return $temp_pack2;
	}
	unset($temp_pack,$temp_pack2,$adds,$item);
}
//################## item_syn_special_out ################
function item_syn_special_out(&$itemArr,&$eleArr)
{
	global $lang,$a_id,$DB_site;
	foreach($eleArr as $key=>$value)
	{
		if($itemArr[$key] && $itemArr[$key] > 0)
		{			
			$itemArr[$key]-=$value;
			$eleArr[$key]=0;
			if($itemArr[$key] < 0){
				alertWindowMsg($lang['wog_act_syn_error9']);
			}
			elseif($itemArr[$key] == 0){
				unset($itemArr[$key]);
			}
		}else
		{
			// 檢查打洞後的武器 begin
			$d_type=0;
			switch($a_id)
			{
				case 'a_id':
					$d_type=0;
					break;
				case 'd_head_id':
					$d_type=1;
					break;
				case 'd_body_id':
					$d_type=2;
					break;
				case 'd_hand_id':
					$d_type=3;
					break;
				case 'd_foot_id':
					$d_type=4;
					break;
				default:
					$d_type=99;
					break;
			}
			if($d_type<99)
			{
				$sql=" select d_name,d_hole from wog_df where d_id=".$key." and d_type= ".$d_type;
				$d_name=$DB_site->query_first($sql);
				if($d_name)
				{
					$sql="select d_id from wog_df where d_name='".$d_name[d_name]."' and d_hole > ".$d_name[d_hole]." order by d_hole limit 1";
					$d_chk=$DB_site->query_first($sql);
					if($d_chk)
					{
						$eleArr[$d_chk[d_id]]=$eleArr[$key];					
						unset($eleArr[$key],$d_name,$d_chk);
						$this->item_syn_special_out($itemArr,$eleArr);
					}
				}				
			}
			// 檢查打洞後的武器 end
		}
		
	}
}
//################## item_in ################
function item_in($temp_pack,$adds,$buy_num=0)
{
	global $a_id,$wog_arry;
	if($a_id=="d_item_id" || $a_id=="d_stone_id" || $a_id=="d_honor_id" || $a_id=='d_key_id' || $a_id=='d_plus_id'){
		$sum=count($temp_pack);
		for($i=0;$i<$sum;$i++){
			$temp_packs=explode("*",$temp_pack[$i]);
			if(empty($temp_pack[0])){continue;}
			if($temp_packs[0]==$adds){
				$temp_packs[1]=$temp_packs[1]+$buy_num;
				if($temp_packs[1] > $wog_arry["item_app_limit"]){
					$buy_num=$temp_packs[1]-$wog_arry["item_app_limit"];
					$temp_packs[1]=$wog_arry["item_app_limit"];
				}else{
					$buy_num=0;
				}
			}
			$temp_pack[$i]=$temp_packs[0].'*'.$temp_packs[1];
		}
		if($buy_num > 0){
			$temp_pack[]=$adds.'*'.$buy_num;
		}
		return $temp_pack;
	}else{
		$temp_pack[]=$adds;
		return $temp_pack;
	}
}
function del_stone($id)
{
	global $DB_site;
	$sql="select hole_temp_1,hole_temp_2,hole_temp_3,hole_temp_4 from wog_stone_setup where hs_id=$id";
	$ht=$DB_site->query_first($sql);
	$temp=array();
	if($ht[hole_temp_1]>0)
	{
		$temp[]=$ht[hole_temp_1];
	}
	if($ht[hole_temp_2]>0)
	{
		$temp[]=$ht[hole_temp_2];
	}
	if($ht[hole_temp_3]>0)
	{
		$temp[]=$ht[hole_temp_3];
	}
	if($ht[hole_temp_4]>0)
	{
		$temp[]=$ht[hole_temp_4];
	}
	unset($ht);
	if(count($temp)>0)
	{
		$DB_site->query("delete from wog_stone_temp where ht_id in (".implode(",",$temp).")");
	}
	$DB_site->query("delete from wog_stone_setup where hs_id=$id");
}
function del_plus($id)
{
	global $DB_site;
	$DB_site->query("delete from wog_plus_setup where ps_id=$id");
}
}