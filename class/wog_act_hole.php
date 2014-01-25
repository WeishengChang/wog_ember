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
class wog_act_hole{
	function hole_view($user_id)
	{
		global $DB_site,$_POST,$lang;
		$d_id=$_POST["temp_id"];
		if(empty($d_id))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}

		$bag_item_id=$d_id;
		get_arm_id($bag_item_id,$item_id,$hs_id,$ps_id);

		$sql="select d_df,d_mdf,d_name,d_type,d_at,d_mat,d_hole from wog_df where d_id=".$item_id." and d_hole > 0";
		$temp_temp=$DB_site->query_first($sql);
		if(!$temp_temp)
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$a_id=type_name($temp_temp["d_type"]);
		$sql="select ".$a_id.",d_stone_id from wog_item where p_id=".$user_id;
		$item=$DB_site->query_first($sql);
		if($item[0]=='N/A' || $item[0]==''){
			alertWindowMsg($lang['wog_act_errwork']);
		}
		if(!is_array($item[0])){
			$item[0]=explode(',',$item[0]);
		}
		if(!in_array($bag_item_id,$item[0]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$s=$bag_item_id.",".$temp_temp[d_name].",".$temp_temp[d_df].",".$temp_temp[d_mdf].",".$temp_temp[d_at].",".$temp_temp[d_mat].",".$temp_temp[d_hole];
		// 取出寶石袋的魔石 begin
		$s2="";
		if($item[1] <> '')
		{
			$packs=explode(",",$item[1]);
			$item_num=array();
			for($i=0;$i<count($packs);$i++)
			{
				$packss=explode("*",$packs[$i]);
				$temp_item.=",".$packss[0];
				$item_num[$packss[0]]=$packss[1];
			}
			$temp_item=substr($temp_item,1);

			$temp_str=$DB_site->query("select a.d_id,a.d_name from wog_df a where a.d_id in ($temp_item) and d_type=7");

			while($temp_strs=$DB_site->fetch_array($temp_str))
			{
				$s2.=";".$temp_strs[d_id].",".$temp_strs[d_name]."*".$item_num[$temp_strs[d_id]];
			}
			$DB_site->free_result($temp_str);
			unset($temp_strs);
			$s2=substr($s2,1);
		}
		// 取出寶石袋的魔石 end
		// 取出道具所裝備的魔石 begin
		$s3 = "";
		for($i=1;$i<=(int)$temp_temp[d_hole];$i++)
		{
			$temp_string.=","." hole_".$i;
		}
		$temp_string=substr($temp_string,1);
		if(!empty($hs_id))
		{
			$sql="select ".$temp_string." from wog_stone_setup a where hs_id=".$hs_id;
			$item=$DB_site->query_first($sql);
			if($item)
			{
				$temp_hole = array();
				for($i=0;$i<(int)$temp_temp[d_hole];$i++)
				{
					$temp_hole[$i]= $item[$i];
				}
				$sql="select b.d_id,b.d_name from wog_df b where d_id in (".implode(',',$temp_hole).")";
				$temp_str=$DB_site->query($sql);
				while($temp_strs=$DB_site->fetch_array($temp_str))
				{
					$key = array_search($temp_strs["d_id"],$temp_hole);
					$temp_hole[$key]=$temp_strs["d_id"].",".$temp_strs["d_name"];
				}
				$s3 = implode(';',$temp_hole);
			}
		}
		// 取出道具所裝備的魔石 end
		unset($item,$temp_hole,$user_item);
		showscript("parent.hole_view('$s','$s2','$s3','$item[1]','$hs_id')");
	}

	function hole_setup($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_item_tool,$a_id;
		$d_id=$_POST["temp_id"];
		if(empty($d_id))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		if(empty($_POST["hole"]))
		{
			alertWindowMsg($lang['wog_act_hole_error3']);
		}
		$hole_id="hole_".$_POST["hole"];
		$hole_temp_id="hole_temp_".$_POST["hole"];
		$stone_id=$_POST["stone"];
		if(empty($stone_id))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$bag_item_id=$d_id;
		get_arm_id($bag_item_id,$item_id,$hs_id,$ps_id);

		$sql="select d_hole,d_type from wog_df where d_id=".$item_id." and d_hole > 0";
		$temp_temp=$DB_site->query_first($sql);
		if(!$temp_temp)
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		if($_POST["hole"] > $temp_temp["d_hole"])
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$a_id=type_name($temp_temp["d_type"]);
		$sql="select ".$a_id.",d_stone_id from wog_item where p_id=".$user_id;
		$item=$DB_site->query_first($sql);

		if($item[0]=='N/A' || $item[0]==''){
			alertWindowMsg($lang['wog_act_errwork']);
		}
		if(!is_array($item[0])){
			$item[0]=explode(',',$item[0]);
		}
		if(!in_array($bag_item_id,$item[0]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$items=array();
		if(!empty($item[1]))
		{
			$items=explode(",",$item[1]);
		}
		$a_id = "d_stone_id";
		$items2=$wog_item_tool->item_out($user_id,$stone_id,1,$items);
		$sql="select d_class,s_at,s_mat,s_df,s_mdf,s_agl,s_str,s_life,s_vit,s_smart,s_au,s_be,s_hpmax from wog_stone_list where d_id=".$stone_id;
		$stone_class=$DB_site->query_first($sql);		
		if(!empty($hs_id))
		{
			$sql="select p_id,hole_1,hole_2,hole_3,hole_4 from wog_stone_setup where hs_id=".$hs_id;
			$check_item=$DB_site->query_first($sql);
			if($check_item)
			{
				if($check_item[$hole_id] > 0 )
				{
					alertWindowMsg($lang['wog_act_hole_error2']);
				}
				if($check_item[1] == $stone_id || $check_item[2] == $stone_id || $check_item[3] == $stone_id || $check_item[4] == $stone_id)
				{
					alertWindowMsg($lang['wog_act_hole_error1']);
				}
				if($stone_class[d_class]==99)
				{
					$at=0;$mat=0;$df=0;$mdf=0;$agi=0;$str=0;$life=0;$vit=0;$smart=0;$au=0;$be=0;$hp=0;
					if($stone_class[s_at]!=0)
					{
						$t=explode("-",$stone_class[s_at]);
						$at=rand($t[0],$t[1]);
					}
					if($stone_class[s_mat]!=0)
					{
						$t=explode("-",$stone_class[s_mat]);
						$mat=rand($t[0],$t[1]);
					}
					if($stone_class[s_df]!=0)
					{
						$t=explode("-",$stone_class[s_df]);
						$df=rand($t[0],$t[1]);
					}
					if($stone_class[s_mdf]!=0)
					{
						$t=explode("-",$stone_class[s_mdf]);
						$mdf=rand($t[0],$t[1]);
					}
					if($stone_class[s_agl]!=0)
					{
						$t=explode("-",$stone_class[s_agl]);
						$agi=rand($t[0],$t[1]);
					}
					if($stone_class[s_str]!=0)
					{
						$t=explode("-",$stone_class[s_str]);
						$str=rand($t[0],$t[1]);
					}
					if($stone_class[s_life]!=0)
					{
						$t=explode("-",$stone_class[s_life]);
						$life=rand($t[0],$t[1]);
					}					
					if($stone_class[s_vit]!=0)
					{
						$t=explode("-",$stone_class[s_vit]);
						$vit=rand($t[0],$t[1]);
					}
					if($stone_class[s_smart]!=0)
					{
						$t=explode("-",$stone_class[s_smart]);
						$smart=rand($t[0],$t[1]);
					}
					if($stone_class[s_au]!=0)
					{
						$t=explode("-",$stone_class[s_au]);
						$au=rand($t[0],$t[1]);
					}
					if($stone_class[s_be]!=0)
					{
						$t=explode("-",$stone_class[s_be]);
						$be=rand($t[0],$t[1]);
					}
					if($stone_class[s_hpmax]!=0)
					{
						$t=explode("-",$stone_class[s_hpmax]);
						$hp=rand($t[0],$t[1]);
					}
					
					$sql="insert wog_stone_temp (s_at,s_mat,s_df,s_mdf,s_agl,s_str,s_life,s_vit,s_smart,s_au,s_be,s_hpmax,p_id)
						values($at,$mat,$df,$mdf,$agi,$str,$life,$vit,$smart,$au,$be,$hp,$user_id)";
					$DB_site->query($sql);
					$temp_id=$DB_site->insert_id();
					$DB_site->query("update wog_stone_setup set ".$hole_id." = ".$stone_id.",".$hole_temp_id."=".$temp_id." where hs_id=".$hs_id);
				}else
				{
					$DB_site->query("update wog_stone_setup set ".$hole_id." = ".$stone_id." where hs_id=".$hs_id);
				}
			}
			else
			{

				if($stone_class[d_class]==99)
				{
					$at=0;$mat=0;$df=0;$mdf=0;$agi=0;$str=0;$life=0;$vit=0;$smart=0;$au=0;$be=0;$hp=0;
					if($stone_class[s_at]!=0)
					{
						$t=explode("-",$stone_class[s_at]);
						$at=rand($t[0],$t[1]);
					}
					if($stone_class[s_mat]!=0)
					{
						$t=explode("-",$stone_class[s_mat]);
						$mat=rand($t[0],$t[1]);
					}
					if($stone_class[s_df]!=0)
					{
						$t=explode("-",$stone_class[s_df]);
						$df=rand($t[0],$t[1]);
					}
					if($stone_class[s_mdf]!=0)
					{
						$t=explode("-",$stone_class[s_mdf]);
						$mdf=rand($t[0],$t[1]);
					}
					if($stone_class[s_agl]!=0)
					{
						$t=explode("-",$stone_class[s_agl]);
						$agi=rand($t[0],$t[1]);
					}
					if($stone_class[s_str]!=0)
					{
						$t=explode("-",$stone_class[s_str]);
						$str=rand($t[0],$t[1]);
					}
					if($stone_class[s_life]!=0)
					{
						$t=explode("-",$stone_class[s_life]);
						$life=rand($t[0],$t[1]);
					}					
					if($stone_class[s_vit]!=0)
					{
						$t=explode("-",$stone_class[s_vit]);
						$vit=rand($t[0],$t[1]);
					}
					if($stone_class[s_smart]!=0)
					{
						$t=explode("-",$stone_class[s_smart]);
						$smart=rand($t[0],$t[1]);
					}
					if($stone_class[s_au]!=0)
					{
						$t=explode("-",$stone_class[s_au]);
						$au=rand($t[0],$t[1]);
					}
					if($stone_class[s_be]!=0)
					{
						$t=explode("-",$stone_class[s_be]);
						$be=rand($t[0],$t[1]);
					}
					if($stone_class[s_hpmax]!=0)
					{
						$t=explode("-",$stone_class[s_hpmax]);
						$hp=rand($t[0],$t[1]);
					}
					
					$sql="insert wog_stone_temp (s_at,s_mat,s_df,s_mdf,s_agl,s_str,s_life,s_vit,s_smart,s_au,s_be,s_hpmax,p_id)
						values($at,$mat,$df,$mdf,$agi,$str,$life,$vit,$smart,$au,$be,$hp,$user_id)";
					$DB_site->query($sql);
					$temp_id=$DB_site->insert_id();
					$DB_site->query("insert wog_stone_setup(p_id,d_id,".$hole_id.",".$hole_temp_id.")values(".$user_id.",".$item_id.",".$stone_id.",".$temp_id.")");
				}else
				{
					$DB_site->query("insert wog_stone_setup(p_id,d_id,".$hole_id.")values(".$user_id.",".$item_id.",".$stone_id.")");
				}
												
				$hs_id=$DB_site->insert_id();
				$a_id=type_name($temp_temp["d_type"]);
				$items=$wog_item_tool->item_out($user_id,$bag_item_id,1,$item[0]);
				if(!empty($ps_id))
				{
					$item_id.=":".$hs_id."&".$ps_id;
				}else
				{
					$item_id.=":".$hs_id;
				}
				$items=$wog_item_tool->item_in($items,$item_id,1);
				$DB_site->query("update wog_item set ".$a_id."='".implode(',',$items)."' where p_id=".$user_id);
				$bag_item_id=$item_id;				
			}
		}
		else
		{
			if($stone_class[d_class]==99)
			{
				$at=0;$mat=0;$df=0;$mdf=0;$agi=0;$str=0;$life=0;$vit=0;$smart=0;$au=0;$be=0;$hp=0;
				if($stone_class[s_at]!=0)
				{
					$t=explode("-",$stone_class[s_at]);
					$at=rand($t[0],$t[1]);
				}
				if($stone_class[s_mat]!=0)
				{
					$t=explode("-",$stone_class[s_mat]);
					$mat=rand($t[0],$t[1]);
				}
				if($stone_class[s_df]!=0)
				{
					$t=explode("-",$stone_class[s_df]);
					$df=rand($t[0],$t[1]);
				}
				if($stone_class[s_mdf]!=0)
				{
					$t=explode("-",$stone_class[s_mdf]);
					$mdf=rand($t[0],$t[1]);
				}
				if($stone_class[s_agl]!=0)
				{
					$t=explode("-",$stone_class[s_agl]);
					$agi=rand($t[0],$t[1]);
				}
				if($stone_class[s_str]!=0)
				{
					$t=explode("-",$stone_class[s_str]);
					$str=rand($t[0],$t[1]);
				}
				if($stone_class[s_life]!=0)
				{
					$t=explode("-",$stone_class[s_life]);
					$life=rand($t[0],$t[1]);
				}					
				if($stone_class[s_vit]!=0)
				{
					$t=explode("-",$stone_class[s_vit]);
					$vit=rand($t[0],$t[1]);
				}
				if($stone_class[s_smart]!=0)
				{
					$t=explode("-",$stone_class[s_smart]);
					$smart=rand($t[0],$t[1]);
				}
				if($stone_class[s_au]!=0)
				{
					$t=explode("-",$stone_class[s_au]);
					$au=rand($t[0],$t[1]);
				}
				if($stone_class[s_be]!=0)
				{
					$t=explode("-",$stone_class[s_be]);
					$be=rand($t[0],$t[1]);
				}
				if($stone_class[s_hpmax]!=0)
				{
					$t=explode("-",$stone_class[s_hpmax]);
					$hp=rand($t[0],$t[1]);
				}
				
				$sql="insert wog_stone_temp (s_at,s_mat,s_df,s_mdf,s_agl,s_str,s_life,s_vit,s_smart,s_au,s_be,s_hpmax,p_id)
					values($at,$mat,$df,$mdf,$agi,$str,$life,$vit,$smart,$au,$be,$hp,$user_id)";
				$DB_site->query($sql);
				$temp_id=$DB_site->insert_id();
				$DB_site->query("insert wog_stone_setup(p_id,d_id,".$hole_id.",".$hole_temp_id.")values(".$user_id.",".$item_id.",".$stone_id.",".$temp_id.")");
				//$DB_site->query("insert wog_stone_setup(p_id,d_id,".$hole_id.")values(".$user_id.",".$item_id.",'".$stone_id.",".$temp_id."')");
			}else
			{
				$DB_site->query("insert wog_stone_setup(p_id,d_id,".$hole_id.")values(".$user_id.",".$item_id.",'".$stone_id."')");
			}
			$hs_id=$DB_site->insert_id();
			$a_id=type_name($temp_temp["d_type"]);
			$items=$wog_item_tool->item_out($user_id,$bag_item_id,1,$item[0]);
			if(!empty($ps_id))
			{
				$item_id.=":".$hs_id."&".$ps_id;
			}else
			{
				$item_id.=":".$hs_id;
			}
			$items=$wog_item_tool->item_in($items,$item_id,1);
			$DB_site->query("update wog_item set ".$a_id."='".implode(',',$items)."' where p_id=".$user_id);
			$bag_item_id=$item_id;
		}
		$DB_site->query("update wog_item set d_stone_id='".implode(',',$items2)."' where p_id=".$user_id);
		unset($items2,$items,$item,$check_item,$stone_class);
		$_POST["temp_id"]=$bag_item_id;
		$this->hole_view($user_id);
	}
	function hole_remove($user_id)
	{
		global $DB_site,$_POST,$lang,$wog_item_tool,$a_id;
		$temp_id=$_POST["temp_id"];
		$move_id=(int)$_POST["id"];
		if(empty($temp_id))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		if(empty($move_id))
		{
			alertWindowMsg($lang['wog_act_hole_error6']);
		}
		$temp_id=explode(',',$temp_id);
		$bag_item_id=$temp_id[0];
		$item_id=$temp_id[0];
		$hs_id=$temp_id[1];
		$hole_id="hole_".$temp_id[2];
		$hole_temp_id="hole_temp_".$temp_id[2];
		if(empty($hs_id) || empty($temp_id[1]))
		{
			alertWindowMsg($lang['wog_act_arm_noselect']);
		}
		$sql="select p_id,".$hole_id.",".$hole_temp_id." from wog_stone_setup where hs_id=".$hs_id;
		$check_item=$DB_site->query_first($sql);
		if($check_item)
		{
			$DB_site->query_first("set autocommit=0");
			$DB_site->query_first("BEGIN");			
			$a_id="d_stone_id";
			$sql="select d_item_id,".$a_id." from wog_item where p_id=".$user_id." for update";
			$items=$DB_site->query_first($sql);
			$items[0]=trim($items[0]);
			$temp_pack=array();
			if(!empty($items[0]))
			{
				$temp_pack=explode(",",$items[0]);
			}

			$items[1]=trim($items[1]);
			$temp_pack2=array();
			if(!empty($items[1]))
			{
				$temp_pack2=explode(",",$items[1]);
			}
			
			switch($move_id)
			{
				case 1:
					$temp_pack=$wog_item_tool->item_out($user_id,1378,1,$temp_pack);
					break;
				default:
					alertWindowMsg($lang['wog_act_hole_error6']);
					break;				
			}
			if(rand(1,100) < 25)
			{
				$temp_pack2=$wog_item_tool->item_in($temp_pack2,$check_item[$hole_id],1);
				$DB_site->query("update wog_stone_setup set ".$hole_id."=0,".$hole_temp_id."=0 where hs_id=".$hs_id);
				if($check_item[$hole_temp_id]>0)
				{
					$sql="delete from wog_stone_temp where ht_id=".$check_item[$hole_temp_id];
					$DB_site->query($sql);
				}
			}
			else
			{

				$DB_site->query("update wog_stone_setup set ".$hole_id." = 0,".$hole_temp_id."=0 where hs_id=".$hs_id);

				if($check_item[$hole_temp_id]>0)
				{
					$sql="delete from wog_stone_temp where ht_id=".$check_item[$hole_temp_id];
					$DB_site->query($sql);
				}
				alertWindowMsg_continue($lang['wog_act_hole_error4']);
			}
			$DB_site->query("update wog_item set ".$a_id."='".implode(',',$temp_pack2)."',d_item_id='".implode(',',$temp_pack)."' where p_id=".$user_id);
			unset($items);
		}
		$_POST["temp_id"]=$bag_item_id;
		$DB_site->query_first("COMMIT");
		$this->hole_view($user_id);
	}

	function hole_mh($user_id) // 裝備打洞
	{
		global $DB_site,$_POST,$lang,$wog_item_tool,$a_id;
		
		$temp_id=$_POST["temp_id"];
		$id=$_POST["id"];
		if(empty($temp_id)){alertWindowMsg($lang['wog_act_arm_noselect']);}
		if(empty($id)){alertWindowMsg($lang['wog_act_arm_noselect']);}
		$bag_item_id=$temp_id;
		get_arm_id($bag_item_id,$item_id,$hs_id,$ps_id);

		$sql="select d_hole,d_type,d_name from wog_df where d_id=".$item_id;
		$temp_temp=$DB_site->query_first($sql);

		$sql="select d_id from wog_df where d_name='".$temp_temp[d_name]."' and d_hole=".($temp_temp[d_hole]+1);
		$new_arm=$DB_site->query_first($sql);
		if(!$new_arm){alertWindowMsg($lang['wog_act_hole_error5']);}

		$a_id=type_name($temp_temp["d_type"]);
		$temp=$a_id;
		
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");

		$sql="select d_item_id,".$a_id." from wog_item where p_id=".$user_id." for update";
		$item=$DB_site->query_first($sql);

		$items=$wog_item_tool->item_out($user_id,$bag_item_id,1,$item[1]);

		$item[0]=trim($item[0]);
		$temp_pack=array();
		if(!empty($item[0]))
		{
			$temp_pack=explode(",",$item[0]);
		}
		$a_id="d_item_id";
		switch($id)
		{
			case "1":
				$temp_pack=$wog_item_tool->item_out($user_id,3361,1,$temp_pack);
				if(rand(1,100)>8)
				{
					$DB_site->query("update wog_item set d_item_id='".implode(',',$temp_pack)."' where p_id=".$user_id);
					$DB_site->query_first("COMMIT");
					alertWindowMsg($lang['wog_act_hole_error7']);
				}
				break;
		}
		
		
		$item_id=$new_arm[d_id];
		if(!empty($hs_id)){$item_id.=":".$hs_id;}
		if(!empty($ps_id)){$item_id.="&".$ps_id;}
		$a_id=$temp;
		$items=$wog_item_tool->item_in($items,$item_id,1);

		$DB_site->query("update wog_item set ".$a_id."='".implode(',',$items)."',d_item_id='".implode(',',$temp_pack)."' where p_id=".$user_id);
		$DB_site->query_first("COMMIT");
		unset($items2,$items,$item,$temp_pack,$temp_temp,$new_arm);
		$_POST["temp_id"]=$item_id;
		$this->hole_view($user_id);
	}
}
?>