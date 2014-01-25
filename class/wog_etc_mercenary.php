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

class wog_etc_mercenary{
	function mercenary_view($user_id)
	{
		global $DB_site,$wog_arry,$wogclass,$wog_item_tool,$wog_event_class,$_COOKIE;
		if(!empty($user_id))
		{
			$time=time()+2;
			$datecut=$time-$wog_arry["f_time_mercenary"];
			$get_time=$_COOKIE["wog_cookie_mercenary"];
			if((int)$get_time > $datecut)
			{
				return;
			}			
			//$sql="select a.id,a.me_count,a.me_place,b.me_get_money,b.me_get_item,a.me_name,b.me_get_exp,b.me_die from wog_mercenary_list a,wog_mercenary_main b where a.p_id=".$user_id." and a.me_status=1 and a.me_fight_time <= $datecut and a.me_id=b.me_id";
			$DB_site->query_first("set autocommit=0");
			$DB_site->query_first("BEGIN");
			$sql="select a.id,a.me_count,a.me_place,b.me_get_money,b.me_get_item,a.me_name,b.me_get_exp,b.me_die,a.me_fight_time from wog_mercenary_list a,wog_mercenary_main b where a.p_id=".$user_id." and a.me_status=1 and a.me_id=b.me_id for update";
			$me=$DB_site->query_first($sql);
			if($me)
			{
				if($me["me_fight_time"] > $datecut)
				{
					return;
				}
				include_once("./class/wog_fight_select.php");
				include_once("./class/wog_item_tool.php");
				include_once("./class/wog_fight_m.php");
				include_once("./class/wog_fight_event.php");
				
				//任務載入 begin
				$mission=array();
				$mission_id=array();
				if($_COOKIE["wog_cookie_mission_id"]!=0)
				{

					$result=$DB_site->query("SELECT a.m_id,a.m_monster_id,b.m_kill_num FROM wog_mission_main a,wog_mission_book b WHERE b.p_id=".$user_id." AND b.m_status=0 AND a.m_id=b.m_id");
					if($result)
					{
						while($m_item=$DB_site->fetch_array($result))
						{
							if(empty($m_item['m_kill_num'])){
								$DB_site->query("update wog_mission_book set m_status=1 where p_id=".$user_id." and m_id=".$m_item['m_id']);
							}else{
								$m_id=explode(',',$m_item['m_monster_id']);
								$a=array();
								foreach($m_id as $value){
									$temp=explode('*',$value);
									$mission_id[$temp[0]]=$m_item['m_id'];
									$a[$temp[0]]=$temp[1];
								}
								$m_id=explode(',',$m_item['m_kill_num']);
								$b=array();
								foreach($m_id as $value){
									$temp=explode('*',$value);
									$b[$temp[0]]=$temp[1];
								}
								$mission[$m_item['m_id']]=array('m_id' => $m_item['m_id'],'m_monster_id' => implode(",",$mission_id),'m_kill_num' => $b,'m_need_num' => $a);
							}
						}
						if(count($mission)<1)
						{
							setcookie("wog_cookie_mission_id",0);
						}
					}
					/*
					$result=$DB_site->query("SELECT a.m_id,a.m_monster_id,a.m_kill_num as m_need_num,b.m_kill_num FROM wog_mission_main a,wog_mission_book b WHERE b.p_id=".$user_id." AND b.m_status=0 AND a.m_id=b.m_id");
					if($result)
					{
						while($m_item=$DB_site->fetch_array($result))
						{
							if($m_item['m_kill_num'] == 0){
								$DB_site->query("update wog_mission_book set m_status=1 where p_id=".$user_id." and m_id=".$m_item['m_id']);
							}else{
								$mission[$m_item['m_id']]=array('m_id' => $m_item['m_id'],'m_monster_id' => $m_item['m_monster_id'],'m_kill_num' => $m_item['m_kill_num'],'m_need_num' => $m_item['m_need_num']);
								$m_id=@explode(',',$m_item['m_monster_id']);
								foreach($m_id as $value){
									$mission_id[$value]=$m_item['m_id'];
								}
							}
						}
						if(count($mission)<1)
						{
							setcookie("wog_cookie_mission_id",0);
						}
					}
					*/
				}
				//任務載入 end
				
				$wog_item_tool = new wog_item_tool;
				$wog_event_class = new wog_fight_event;
				$wogclass = new wog;
				$wog_act_class = new wog_fight_select;
				$wogclass->win=0;
				$wogclass->lost=0;
				$wogclass->f_count=$wog_arry["f_count"];
				$wogclass->f_hp=$wog_arry["f_hp"];
				$wog_act_class->fire_mercenary($user_id,$me,$mission,$mission_id);
				unset($wog_act_class,$wogclass,$wog_item_tool);
			}else
			{
				/*
				$sql.=" * ".$time;
				$sql2="insert m_log(p_id,body)values($user_id,'$sql')";
				$DB_site->query($sql2);
				showscript("alert('test - ".$test."');parent.mercenary_set=0");
				*/
				$DB_site->query_first("COMMIT");
				showscript("parent.mercenary_set=0");
			}
			unset($me);
		}
	}
}
?>