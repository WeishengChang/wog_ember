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

class wog_group_mission{
	var $need_date=array();
	function mission_check($g_id,$type,$p_id,$t_id=0)
	{
		global $DB_site,$lang,$wog_arry;
		$sql="select a.id,a.need,a.reward,a.type,a.title from wog_group_mission_log a where a.g_id=$g_id and a.type=$type and a.status=0 for update";
		$mission=$DB_site->query($sql);
		while($missions=$DB_site->fetch_array($mission))
		{
			$status=0;
			switch($type)
			{
				case 1:
					$n=explode(":",$missions[need]);
					if($this->need_date["weapon_".$n[0]]>0)
					{
						$n2=explode("|",$n[1]);
						$n2[1]+=$this->need_date["weapon_".$n[0]];
						if($n2[1]>=$n2[0])
						{
							$n2[1]=$n2[0];
							$status=1;
							$this->mission_reward($g_id,$missions[reward],$p_id,$missions[title]);
						}
						$need=$n[0].":".$n2[0]."|".$n2[1];
						$sql="update wog_group_mission_log set status=$status,need='$need',p_id=$p_id where id=$missions[id]";
						$DB_site->query($sql);
					}
				break;
				case 2:
					$n=explode(":",$missions[need]);
					if($n[1]==$t_id)
					{
						$status=1;
						$this->mission_reward($g_id,$missions[reward],$p_id,$missions[title]);						
						$sql="update wog_group_mission_log set status=$status,p_id=$p_id where id=$missions[id]";
						$DB_site->query($sql);
					}
				break;
				case 3:
					$n=explode(":",$missions[need]);
					if($n[1]==$t_id)
					{
						$status=1;
						$this->mission_reward($g_id,$missions[reward],$p_id,$missions[title]);						
						$sql="update wog_group_mission_log set status=$status,p_id=$p_id where id=$missions[id]";
						$DB_site->query($sql);
					}
				break;
				case 4:
					$n=explode(":",$missions[need]);
					if($n[1]==$t_id)
					{
						if($this->need_date["weapon_".$n[2]]>0)
						{
							$n2=explode("|",$n[3]);
							$n2[1]+=$this->need_date["weapon_".$n[2]];
							if($n2[1]>=$n2[0])
							{
								$n2[1]=$n2[0];
								$status=1;
								$this->mission_reward($g_id,$missions[reward],$p_id,$missions[title]);
							}
							$need=$n[0].":".$n[1].":".$n[2].":".$n2[0]."|".$n2[1];
							$sql="update wog_group_mission_log set status=$status,need='$need',p_id=$p_id where id=$missions[id]";
							$DB_site->query($sql);
						}
					}
				break;
			}
		}
		//$DB_site->query_first("COMMIT");
	}

	function mission_reward($g_id,$reward,$p_id,$title)
	{
		global $DB_site,$lang,$wog_arry;
		$r=explode(":",$reward);
		switch($r[0])
		{
			case "ex":
				$sql_update="ex_".$r[1]."="."ex_".$r[1]."+".$r[2];
				$sql="update wog_group_exchange set $sql_update where g_id=$g_id";
				$DB_site->query($sql);
				$s=$wog_arry[g_ex][$r[1]].":".$r[2];
			break;
			case "wp":
				$sql_update="weapon_".$r[1]."="."weapon_".$r[1]."+".$r[2];
				$sql="update wog_group_weapon set $sql_update where g_id=$g_id";
				$DB_site->query($sql);
				$s=$wog_arry[g_wp][$r[1]].":".$r[2];
			break;
			case "item":
				$sql="select id from wog_group_item where g_id=$g_id and item_id=$r[1]";
				$item=$DB_site->query_first($sql);
				if($item)
				{
					$sql="update wog_group_item set num=num+".$r[2];
					$DB_site->query($sql);
				}else
				{
					$sql="insert wog_group_item(g_id,item_id,num)values($g_id,$r[1],$r[2])";
					$DB_site->query($sql);
				}
			break;
		}
		$sql="select p_name from wog_player where p_id=$p_id";
		$p=$DB_site->query_first($sql);
		$time=time();

		$temp=sprintf($lang['wog_act_group_msg18'],$p[p_name],$title,$s);
		$sql="insert into wog_group_event(g_b_id,g_b_body,g_b_dateline)values($g_id,'".$temp."',$time)";
		$DB_site->query($sql);
	}

}
