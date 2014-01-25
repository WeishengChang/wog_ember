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

class wog_act_syn{
	function syn_view($user_id)
	{
		global $DB_site,$_POST,$lang;
		$sql="select ".$_POST["temp_id"]." from wog_item where p_id=".$user_id;
		$pack=$DB_site->query_first($sql);
		if($pack[0]=="N/A" || empty($pack[0]))
		{
			alertWindowMsg($lang['wog_act_arm_view']);
		}else
		{
			$temp_item="";
			if($_POST["temp_id"]=="d_item_id")
			{
				$item_1=array();
				$item_2=array();
				$packs=explode(",",$pack[0]);
				for($i=0;$i<count($packs);$i++)
				{
					$packss=explode("*",$packs[$i]);
					$item_1[]=$packss[0];
					$item_2[]=$packss[1];
					$temp_item.=",".$packss[0];

				}
				$temp_item=substr($temp_item,1,strlen($temp_item));
			}else
			{
				$temp_item=$pack[0];
			}

			$temp_str=$DB_site->query("select d_id,d_df,d_mdf,d_agl,d_mstr,d_magl,d_msmart,d_money,d_name,d_at,d_mat from wog_df where d_id in ($temp_item)");
			$s="";
			while($temp_strs=$DB_site->fetch_array($temp_str))
			{
				$s.=";".$temp_strs[d_id].",".$temp_strs[d_df].",".$temp_strs[d_mdf].",".$temp_strs[d_agl].",".$temp_strs[d_money].",".$temp_strs[d_name].",".$temp_strs[d_at].",".$temp_strs[d_mat].",".$temp_strs[d_mstr].",".$temp_strs[d_magl].",".$temp_strs[d_msmart];
			}
			$DB_site->free_result($temp_str);
			unset($temp_strs);
			$s=substr($s,1,strlen($s));
			showscript("parent.syn_view('$s','$pack[0]')");
		}
		unset($pack,$temp);
	}

	function syn_purify($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_item_tool,$wog_arry;
		if(empty($_POST["syn_way"])){alertWindowMsg($lang['wog_act_syn_noway']);}
		if(count($_POST["syn"])<=1){alertWindowMsg($lang['wog_act_syn_error']);}
		$syn=$_POST["syn"];
		$syn_debug=0;
		check_type($_POST["syn"][0]);
		$sql="select ".$a_id." from wog_item where p_id=".$user_id."";
		$item=$DB_site->query_first($sql);
		if($item[0]=="N/A" || $item[0]=="")
		{alertWindowMsg($lang['wog_act_syn_error2']);}
		else
		{
			$items=explode(",",$item[0]);
			for($a=0;$a<count($_POST["syn"]);$a++)
			{
				$syn_debug++;
				$items=$wog_item_tool->item_out($user_id,$syn[$a],1,$items);
			}
			if($syn_debug!=count($_POST["syn"])){alertWindowMsg($lang['wog_act_syn_error3']);}
			$chang_d_type=$a_id;
			switch($_POST["syn_way"])
			{
				case "1":##########normal synthetic##########
					$syn_tatal=0;
					$sql="select sum(d_money) as id,sum(d_lv) as id2 from wog_df where d_id in (".implode(',',$_POST["syn"]).")";
					$item_money=$DB_site->query_first($sql);
//					if(rand(1,500) > 1)
//					{
						$item_money[0]=$item_money[0]+(rand(-80,80)*$item_money[1]);
						$sql="select d_id,d_name from wog_df where d_money<".$item_money[0]." and d_dbst=0 ORDER BY d_money desc limit 1";
						$new_arm=$DB_site->query_first($sql);
/*					}else
//					{
						$item_money[0]=$item_money[0]+(rand(1000,1500)*$item_money[1]);
						$sql="select d_id,d_name from wog_df where d_money<".$item_money[0]." and d_lv > 0 and d_lv <= ".(($item_money[1]/$syn_debug)+1)." ORDER BY RAND() limit 1";
						$new_arm=$DB_site->query_first($sql);
					}*/
				break;

				case "2":##########random synthetic##########
					$syn_num=0;
					for($a=0;$a<count($_POST["syn"]);$a++)
					{$syn_num+=$_POST["syn"][$a];}
					$get_type=$DB_site->query_first("select d_type,d_lv from wog_df where d_id=".$_POST["syn"][0]."");
					$syn_id=floor(($syn_num/3)*1.1);
					$sql="select d_id,d_name from wog_df where d_id<".$syn_id." and d_dbst=0 and d_type=".$get_type[0]." and d_lv <= ".$get_type[1]." ORDER BY RAND() LIMIT 1";
					$new_arm=$DB_site->query_first($sql);
				break;

/*
				case "3":##########special synthetic##########
					if(count($_POST["syn"])>5){alertWindowMsg($lang['wog_act_syn_error4']);}
					for($ch=0;$ch<5;$ch++)
					{if(!$_POST["syn"][$ch] || empty($_POST["syn"])){$_POST["syn"][$ch]=0;}}
					$new_arm=$DB_site->query_first("select a.syn_result,b.d_name from wog_syn a,wog_df b where a.syn_ele1=".$_POST["syn"][0]." and a.syn_ele2=".$_POST["syn"][1]." and a.syn_ele3=".$_POST["syn"][2]." and a.syn_ele4=".$_POST["syn"][3]." and a.syn_ele5=".$_POST["syn"][4]." and b.d_id=a.syn_result");
				break;
*/
				default:##########error##########
					alertWindowMsg($lang['wog_act_syn_error5']);
				break;
			}
			if(rand(1,4)==1)
			{
				$DB_site->query("update wog_item set ".$chang_d_type."='".implode(',',$items)."' where p_id=".$user_id." ");
				showscript("parent.syn_end('no',3)");
			}elseif($new_arm[0])
			{
				$p=$DB_site->query_first("select p_bag from wog_player where p_id=".$user_id."");
				check_type($new_arm[0]);
				if($a_id==$chang_d_type)
				{
					if($a_id=="d_item_id")
					{
						$bag=$wog_arry["item_limit"]+$p["p_bag"];
					}else
					{
						$bag=$wog_arry["item_limit"];
					}
					if((count($items)) > $bag)
					{
						showscript("parent.syn_end('no',4)");
						unset($temp_pack);
					}
					$items=$wog_item_tool->item_in($items,$new_arm[0],1);
				}else
				{
					$new_blank=$DB_site->query_first("select ".$a_id." from wog_item where p_id=".$user_id."");
					$temp_pack=array();
					if(!empty($new_blank[0]))
					{
						$temp_pack=explode(",",$new_blank[0]);
					}
					$temp_pack=$wog_item_tool->item_in($temp_pack,$new_arm[0],1);
					if($a_id=="d_item_id")
					{
						$bag=$wog_arry["item_limit"]+$p["p_bag"];
					}else
					{
						$bag=$wog_arry["item_limit"];
					}
					if((count($temp_pack)) > $bag)
					{
						showscript("parent.syn_end('no',4)");
						unset($temp_pack);
					}
					$DB_site->query("update wog_item set ".$a_id."='".implode(',',$temp_pack)."' where p_id=".$user_id." ");
				}
				$DB_site->query("update wog_item set ".$chang_d_type."='".implode(',',$items)."' where p_id=".$user_id." ");
				showscript("parent.syn_end('$new_arm[1]',1)");
			}else
			{
				$DB_site->query("update wog_item set ".$chang_d_type."='".implode(',',$items)."' where p_id=".$user_id." ");
				showscript("parent.syn_end('no',2)");
			}
		}
	}

	function syn_list($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang;
		if(empty($_POST["type"]) || !is_numeric($_POST["type"]))
		{
			$_POST["type"]="0";
		}
		if(empty($_POST["page"]) || !is_numeric($_POST["page"]))
		{
			$_POST["page"]="1";
		}
		$d_type=$_POST["type"];
		$syn_mission=0;
		if($d_type == "99"){$syn_mission=1;$d_type=5;}
		if($d_type=="5"){$d_type="5,6";}
		$syn_total=$DB_site->query_first("select count(a.syn_id) as syn_id  from wog_syn a,wog_df c where a.syn_result=c.d_id and c.d_type in (".$d_type.") and a.syn_mission=$syn_mission order by syn_id ");
		$spage=((int)$_POST["page"]*8)-8;
		$temp_s="";
		if($d_type == "7")
		{
			$syn=$DB_site->query("select c.d_name,d.s_at,d.s_mat,d.s_df,d.s_mdf,d.s_agl,d.s_str,d.s_life,d.s_vit,d.s_smart,d.s_au,d.s_be,a.syn_id,c.d_send,a.syn_topr,a.syn_num,d.s_hpmax  from wog_syn a,wog_df c,wog_stone_list d where a.syn_result=c.d_id and c.d_type in (".$d_type.") and d.d_id=a.syn_result LIMIT ".$spage.",8 ");
			while($syns=$DB_site->fetch_array($syn))
			{
				$temp_s.=";".$syns[0]."*".$syns[15].",".$syns[1].",".$syns[2].",".$syns[3].",".$syns[4].",".$syns[5].",".$syns[6].",".$syns[7].",".$syns[8].",".$syns[9].",".$syns[10].",".$syns[11].",".$syns[12].",".$syns[13].",".$syns[14].",".$syns[16];
			}
			$DB_site->free_result($syn);
		}
		else
		{
			$syn=$DB_site->query("select c.d_name,c.d_at,c.d_mat,c.d_df,c.d_mdf,c.d_mstr,c.d_magl,c.d_msmart,c.d_mau,a.syn_id,d.ch_name,ifnull(c.d_s,0) as d_s,c.d_send,a.syn_topr,a.syn_num,c.d_hole
			from wog_syn a,wog_df c left join wog_character d on d.ch_id=c.ch_id where a.syn_result=c.d_id and c.d_type in (".$d_type.") and a.syn_mission=$syn_mission LIMIT ".$spage.",8 ");
			while($syns=$DB_site->fetch_array($syn))
			{
				if($syns[15]>0)
				{
					$syns[0]=$syns[0]."(".$syns[15].")*".$syns[14];
				}
				else
				{
					$syns[0]=$syns[0]."*".$syns[14];
				}

				$temp_s.=";".$syns[0].",".$syns[1].",".$syns[2].",".$syns[3].",".$syns[4].",".$syns[5].",".$syns[6].",".$syns[7].",".$syns[8].",".$syns[9].",".$syns[10].",".$syns[11].",".$syns[12].",".$syns[13];
			}
			$DB_site->free_result($syn);
		}
		unset($syns);
		$temp_s=substr($temp_s,1,strlen($temp_s));
		showscript("parent.syn_view_special($syn_total[0],".$_POST["page"].",'$temp_s',".$_POST["type"].")");
		unset($temp_s);
		unset($syn_total);
	}

	function syn_detail($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang;
		if(empty($_POST['temp_id']))
			alertWindowMsg($lang['wog_act_syn_error6']);
		$syn_item=$DB_site->query_first("select syn_element,syn_result,syn_need_mission from wog_syn where syn_id=".$_POST["temp_id"]);
		if(!$syn_item)
			alertWindowMsg($lang['wog_act_syn_error6']);
		$eleArr=$this->elementReform($syn_item['syn_element']);
		$eleList=implode(',',array_keys($eleArr));
		$syn=$DB_site->query("select d_id,d_name,d_hole from wog_df where d_id in (".$eleList.") ");
		$temp_s='';
		while($syns=$DB_site->fetch_array($syn))
			$temp_s.=','.$syns['d_name'].'('.$syns['d_hole'].')*'.$eleArr[$syns['d_id']];
		$DB_site->free_result($syn);
		unset($syns);
		$temp_s=substr($temp_s,1);
		$syn_item2=$DB_site->query_first("select d_name,d_g_str,d_g_smart,d_g_agi,d_g_life,d_g_vit,d_g_au,d_g_be,skill from wog_df where d_id=".$syn_item['syn_result']);
		if($temp_strs[skill]>0)
		{
			$sql="select a.s_name
				from wog_ch_skill a where a.s_id=".$syn_item2[skill];
			$skill_main=$DB_site->query_first($sql);
		}
		else
		{
			$skill_main[s_name]="";
		}
		if($syn_item[syn_need_mission] > 0)
		{
			$sql="select m_subject from wog_mission_main where m_id=".$syn_item[syn_need_mission];
			$mission_main=$DB_site->query_first($sql);
		}
		else
		{
			$mission_main[m_subject]="";
		}
		$temp_s2=$syn_item2['d_name'].",".$syn_item2[d_g_str].",".$syn_item2[d_g_smart].",".$syn_item2[d_g_agi].",".$syn_item2[d_g_life].",".$syn_item2[d_g_vit].",".$syn_item2[d_g_au].",".$syn_item2[d_g_be].",".$skill_main[s_name].",".$mission_main[m_subject];
		showscript("parent.wog_message_box('$temp_s',3,'".$temp_s2."')");
	}
	function syn_special($user_id)
	{
		global $DB_site,$_POST,$a_id,$lang,$wog_arry,$syn_debug,$syn_item,$wog_item_tool,$stone_key,$plus_key;
		if(empty($_POST['syn_id']))
			alertWindowMsg($lang['wog_act_syn_error6']);
		$item_num=(int)$_POST["item_num"];
		if($item_num <=0 || preg_match("/[^0-9]/",$item_num)){alertWindowMsg($lang['wog_act_errnum']);}
		$DB_site->query_first("set autocommit=0");
		$DB_site->query_first("BEGIN");
		$p=$DB_site->query_first("select p_money,p_bag from wog_player where p_id=".$user_id);
		$need_money=1000*$item_num;
		if($p['p_money']<$need_money)
			alertWindowMsg($lang['wog_act_syn_error8']);
		
		$syn_item=$DB_site->query_first("select a.syn_element,a.syn_result,b.d_name,a.syn_topr,a.syn_num,a.syn_need_mission from wog_syn a left join wog_df b on b.d_id=a.syn_result where a.syn_id=".$_POST['syn_id']);
		if(!$syn_item)
			alertWindowMsg($lang['wog_act_syn_error6']);
		// 計算背包容量 begin
		check_type($syn_item["syn_result"]);
		$syn_aid=$a_id;
		$bag=$wog_arry["item_limit"];
		if($syn_aid=="d_item_id" || $syn_aid=="d_stone_id")
		{
			$bag+=$p["p_bag"];
		}else
		{
			$item_num=1;
		}
		$new_blank=$DB_site->query_first("select ".$syn_aid." from wog_item where p_id=".$user_id);
		$temp_pack=array();
		if(!empty($new_blank[0]))
			$temp_pack=explode(",",$new_blank[0]);
		$temp_bag=count($temp_pack);
		$temp_bag+=floor($value/$wog_arry["item_app_limit"]);
		if($value%$wog_arry["item_app_limit"] > 0){$temp_bag++;}
		$temp_bag++;
		if($temp_bag > $bag)
		{
			showscript("parent.syn_end('no',4)");
		}
		// 計算背包容量 end
		if($syn_item[syn_need_mission] >0)
		{
			$sql="select m_id from wog_mission_book where p_id=".$user_id." and m_id=".$syn_item[syn_need_mission];
			$mission=$DB_site->query_first($sql);
			if(!$mission)
			{
				alertWindowMsg($lang['wog_act_syn_error10']);
			}
		}
		$eleArr=$this->elementReform($syn_item['syn_element'],$item_num);
		$eleList=implode(',',array_keys($eleArr));
		$syn_debug2=array_sum($eleArr);
		$itemArr=$this->getItemArray($user_id,$eleList);
		$itemSql='';
		foreach($itemArr as $key=>$value)
		{
			if(count($value) < 1)
				alertWindowMsg($lang['wog_act_syn_error7']);
			$a_id=$key;
			$tempStr='';
			$wog_item_tool->item_syn_special_out($value,$eleArr);
			if(count($value) > 0)
			{
				if($a_id == 'd_item_id' || $a_id == 'd_stone_id' || $a_id == 'd_plus_id')
				{
					foreach($value as $key=>$value)
					{
						if($value > $wog_arry["item_app_limit"])
						{
							for($i=0;$i<floor($value/$wog_arry["item_app_limit"]);$i++)
							{
								$tempStr.=','.$key.'*'.$wog_arry["item_app_limit"];
							}
							if($value%$wog_arry["item_app_limit"] > 0){$tempStr.=','.$key.'*'.($value%$wog_arry["item_app_limit"]);}
						}
						else
						{
							$tempStr.=','.$key.'*'.$value;
						}
					}
					$tempStr=substr($tempStr,1);
				}else{
					foreach($value as $key=>$value){
						$temp_num_stone=count($stone_key[$key]);
						$temp_num_plus=count($plus_key[$key]);
						if($temp_num_stone || $temp_num_plus)
						{
							for($j=0;$j<$temp_num_stone;$j++)
							{
								$temp_key=$key.":".$stone_key[$key][$j];
								if(isset($plus_key[$key][$j]))
								{
									$temp_key=$key.":".$stone_key[$key][$j]."&".$plus_key[$key][$j];
									unset($plus_key[$key][$j]);
								}
								unset($stone_key[$key][$j]);
								$tempStr.=','.$temp_key;
								$value--;
							}
							for($k=$j;$k<$temp_num_plus;$k++)
							{
								$temp_key=$key."&".$plus_key[$key][$k];
								unset($plus_key[$key][$k]);
								$tempStr.=','.$temp_key;
								$value--;
							}
						}
						if($value > 0)
						{
							$tempStr.=str_repeat(','.$key,$value);
						}
					}
					$tempStr=substr($tempStr,1);
				}
				$itemSql.=','.$a_id.'=\''.$tempStr.'\'';
			}else{
				$itemSql.=','.$a_id.'=\'\'';
			}
		}
		if(array_sum($eleArr) > 0){
			alertWindowMsg($lang['wog_act_syn_error7']);
		}
		$itemSql=substr($itemSql,1);
		$stoneSql="";
		foreach($stone_key as $value)
		{
			foreach($value as $value)
			{
				$stoneSql.=",".$value;
			}
		}
		if(!empty($stoneSql))
		{
			$stoneSql=substr($stoneSql,1);
			$DB_site->query("delete from wog_stone_setup where hs_id in (".$stoneSql.")");
		}
		$plusSql="";
		foreach($plus_key as $value)
		{
			foreach($value as $value)
			{
				$plusSql.=",".$value;
			}
		}
		if(!empty($plusSql))
		{
			$plusSql=substr($plusSql,1);
			$DB_site->query("delete from wog_plus_setup where ps_id in (".$plusSql.")");
		}
		$DB_site->query("update wog_item set ".$itemSql." where p_id=".$user_id);
		$DB_site->query_first("COMMIT");
		$get_item_num=0;
		for($i=1;$i<=$item_num;$i++)
		{
			if(rand(1,100) <= $syn_item["syn_topr"])
			{
				$get_item_num++;
			}
		}
		if(empty($get_item_num))
		{
			showscript("parent.syn_end('no',3)");
		}else
		{
			$DB_site->query("update wog_player set p_money=p_money-".$need_money." where p_id=".$user_id);
//			check_type($syn_item["syn_result"]);
//			$bag=$wog_arry["item_limit"]+($a_id=="d_item_id" || $a_id=="d_stone_id"?$p["p_bag"]:0);
			$a_id=$syn_aid;
			$new_blank=$DB_site->query_first("select ".$syn_aid." from wog_item where p_id=".$user_id." for update");
			$temp_pack=array();
			if(!empty($new_blank[0]))
				$temp_pack=explode(",",$new_blank[0]);
			$temp_pack=$wog_item_tool->item_in($temp_pack,$syn_item["syn_result"],$syn_item["syn_num"]*$get_item_num);
			$syn_item["d_name"].="*".($syn_item["syn_num"]*$get_item_num);
/*
			if((count($temp_pack)) > $bag)
			{
				showscript("parent.syn_end('no',4)");
			}
*/
			$DB_site->query("update wog_item set ".$syn_aid."='".implode(',',$temp_pack)."' where p_id=".$user_id);
			$DB_site->query_first("COMMIT");
			unset($syn_aid,$new_blank,$temp_pack);
			showscript("parent.syn_end('".$syn_item["d_name"]."',1)");
		}
		unset($syn_item);
	}
	function elementReform(&$eleStr,$item_num=1)
	{
		$arr=explode(',',$eleStr);
		$newArr=array();
		$num=count($arr);
		for($i=0;$i<$num;$i++)
		{
			$tempArr=explode("*",$arr[$i]);
			$newArr[$tempArr[0]]?$newArr[$tempArr[0]]+=$tempArr[1]*$item_num:$newArr[$tempArr[0]]=$tempArr[1]*$item_num;
		}
		unset($arr,$tempArr,$num);
		return $newArr;
	}
	function getItemArray($user_id,&$eleList)
	{
		global $DB_site,$lang,$stone_key,$plus_key;
		$query=$DB_site->query("select DISTINCT d_type from wog_df where d_id in (".$eleList.") ");
		$typeStr='';
		while($typeArr=$DB_site->fetch_array($query))
			$typeStr.=','.type_name($typeArr['d_type']);
		$DB_site->free_result($query);
		unset($typeArr);
		$typeStr=substr($typeStr,1);
		$itemArr=$DB_site->query_first('SELECT '.$typeStr.' FROM wog_item WHERE p_id='.$user_id);
		if(!$itemArr)
			alertWindowMsg($lang['wog_act_syn_error5']);
		$_itemArr=array();
		$stone_key=array();
		$plus_key=array();
		foreach($itemArr as $key=>$value)
		{
			if(!is_numeric($key))
			{
				$valArr=@explode(',',$value);
				$valNum=count($valArr);
				$tempArr=&$_itemArr[$key];

				if($key=='d_item_id' || $key=='d_stone_id' || $key=='d_plus_id')
				{
					for($i=0;$i<$valNum;$i++)
					{
						$temp=explode("*",$valArr[$i]);
						if($tempArr[$temp[0]]>0)
							$tempArr[$temp[0]]+=$temp[1];
						else
							$tempArr[$temp[0]]=$temp[1];
					}
				}else
				{
					for($i=0;$i<$valNum;$i++)
					{
						get_arm_id($valArr[$i],$temp_key,$hs_id,$ps_id);
						if(!empty($hs_id)){$stone_key[$temp_key][]=$hs_id;}
						if(!empty($ps_id)){$plus_key[$temp_key][]=$ps_id;}
						if($tempArr[$temp_key]>0)
							$tempArr[$temp_key]+=1;
						else
							$tempArr[$temp_key]=1;
					}
				}
			}
		}
		unset($itemArr);
		return $_itemArr;
	}
}
?>