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

class wog{
	var $f_count=0;
	var $f_hp=0.15;
	var $f_sp=0.15;
	var $escape_hp=0;
	var	$win=0;
	var	$lost=0;
	var	$temp_lost=0;
	var	$cp_m_hp=0;
	var	$cp_m_sp=0;
	var $sum=0;
	var $petact=1; //原始寵物出擊率
//	var $pe_def=0;
	var $pet_act_type=0; //指定寵物攻擊方式

	var $money=0;
	var $fight_time=0;
	var $skill_money_up=1;

	var $mercenary=0;
	var $mercenary_money=1;
	var $mercenary_setexp=1;
	var $mercenary_exp=0;

	var $act_type=1;
	var $temp_fight_string="";
	var $datecut=0;
	var $p_place=0;
	var $p_skill_event=array();
	var $p_skill_count=array();
	var $p_skill_start=array();
	var $p_skill_main=array();
	
	var $m_skill_event=array();
	var $m_skill_count=array();
	var $m_skill_start=array();
	var $m_skill_main=array();
	
	var $p_status_buffer=array();
	var $m_status_buffer=array();
	var $p_dot_buffer=array();
	var $m_dot_buffer=array();
	var $reload_list=array();
	var $pskill_event0=0;
	var $pskill_event1=0;
	var $pskill_event2=0;
	var $mskill_event0=0;
	var $mskill_event1=0;
	var $mskill_event2=0;
	/*
	var $p_fight_break=0;
	var $m_fight_break=0;
	var $p_skill_break=0;
	var $m_skill_break=0;
	*/
	var $end_skill_id=array();
	var $end_skill_lv=array();
	
	function fight($f_a,$d_d,$temp_s,$def_dmg=1,$get_dmg=0) //屬性傷害比例function
	{
		if($d_d == 0){$d_d=1;}
		if($f_a == 0){$f_a=1;}
		$temp_at=($f_a/$d_d);
		//$temp_lv=$f_lv-$d_lv;
		if($temp_at < 0){$temp_at=1;}
		//if($temp_lv < 0){$temp_lv=1;}
		$temp_d=(($f_a*$temp_at)-$d_d)*$temp_s;
		if($temp_d<=0){$temp_d=1;}
		$temp_rd=$temp_d/$f_a;
		$temp_d+=rand($temp_rd/3,$temp_rd/2);
		return round(($temp_d*$def_dmg)+$get_dmg);
	}
	function m_type($m_at,$m_mat)//判斷敵攻擊模式function
	{
		$temp_mtype=rand(1,10);
		$m_type=0;
		if(($m_at>=$m_mat) && ($temp_mtype <= 8))
		{
			$m_type=1;
		}elseif(($m_at<$m_mat) && ($temp_mtype <= 8))
		{
			$m_type=2;
		}else
		{
			if($m_type==0)
			{
				if($temp_mtype >=5 )
				{
					$m_type=1;
				}else
				{
					$m_type=2;
				}
			}
		}
		return $m_type;
	}
	function get_exp($m_hp,$m_hpmax,$m_at,$m_mat,$m_lv,$p_lv,$s)//get經驗直function
	{
		global $wog_arry;
		$exp_rand=($m_at+$m_mat)/2;
		$exp_at=rand($exp_rand,$exp_rand+$m_lv);
		if($p_lv<=100)
		{
			$exp_at=$exp_at*1.3;
		}
		if($m_lv >= 450)
		{
			$exp_at=$exp_at*0.6;
		}
		$exp_at=$m_lv*$exp_at*($m_lv/$p_lv);
		$exp=$exp_at*($m_hp/$m_hpmax)*0.18;
		if($s==0)
		{
			$exp=$exp*($m_hp/$m_hpmax);
		}
		if($p_lv<=15)
		{
			$exp=$exp*2.5;
		}
		$temp_lv=2;
		return round($exp*$temp_lv*$this->mercenary_setexp*$wog_arry["f_down"]);
	}
	function get_money($m_hp,$m_at,$m_mat,$m_lv)//get金錢function
	{
		global $wog_arry;
		$at_total=($m_at+$m_mat)/$m_lv;
		$at_total2=($m_hp/($m_at+$m_mat))*0.4;
		return rand(($m_lv/2.5),$m_lv)*(rand($at_total,$at_total2)*0.4)*$this->mercenary_money*$wog_arry["f_down"];
	}
	function lv_up($user_id,$p,$money)//升級function
	{
		global $DB_site;
		$p_str=0;
		$p_agl=0;
		$p_smart=0;
		$p_life=0;
		$p_be=0;
		$p_au=0;
		$p_vit=0;
		$o_lv=$p[p_lv];
		$lv=$DB_site->query_first("select ch_str,ch_agi,ch_life,ch_vit,ch_smart,ch_au,ch_be from wog_character where ch_id=".$p[ch_id]."");
		while($p[p_exp]>$p[p_nextexp])
		{
			$p[p_lv]+=1;
			$p[p_exp]-=$p[p_nextexp];
			//$temp_lv=($p[p_lv]/50)*250+900;
			//$p[p_nextexp]=round(($p[p_lv]+(($p[p_lv]/6)*1.2))*$temp_lv);
			$p[p_nextexp]=6*pow($p[p_lv],2)+(1080*$p[p_lv]);
			$m=explode(",",$lv[ch_str]);
			$p_str+=rand($m[0],$m[1]);
			$m=explode(",",$lv[ch_agi]);
			$p_agl+=rand($m[0],$m[1]);
			$m=explode(",",$lv[ch_life]);
			$p_life+=rand($m[0],$m[1]);

			$m=explode(",",$lv[ch_vit]);
			$p_vit+=rand($m[0],$m[1]);
			$m=explode(",",$lv[ch_smart]);
			$p_smart+=rand($m[0],$m[1]);
			$m=explode(",",$lv[ch_be]);
			$p_be+=rand($m[0],$m[1]);
			$m=explode(",",$lv[ch_au]);
			$p_au+=rand($m[0],$m[1]);

		}

		$this->temp_fight_string.=",\"parent.lv_up('$p_str','$p_agl','$p_smart','$p_life','$p_vit','$p_au','$p_be')\"";
		if($this->mercenary==0)
		{
			$sql="update wog_player set at=at+".$p_str.",df=df+".$p_vit.",mat=mat+".$p_smart."
			,mdf=mdf+".$p_be.",str=str+".$p_str.",smart=smart+".$p_smart.",agi=agi+".$p_agl."
			,life=life+".$p_life.",vit=vit+".$p_vit.",au=au+".$p_au.",be=be+".$p_be.",p_lv=".$p[p_lv].",p_exp=".$p[p_exp].",p_nextexp=".$p[p_nextexp].",hp=".$p[hp]."
			,sp=".$p[sp].",p_money=p_money+".$money.",p_win=p_win+".$this->win.",p_lost=p_lost+".$this->temp_lost."
			,base_str=base_str+".$p_str.",base_smart=base_smart+".$p_smart.",base_agi=base_agi+".$p_agl."
			,base_life=base_life+".$p_life.",base_vit=base_vit+".$p_vit.",base_au=base_au+".$p_au.",base_be=base_be+".$p_be."
			,p_act_time =".$this->fight_time.",p_place=$this->p_place,act_num=".$p[act_num]."
			,p_online_time=".$this->fight_time." where p_id=".$user_id;
		}
		else
		{
			$sql="update wog_player set at=at+".$p_str.",df=df+".$p_vit.",mat=mat+".$p_smart."
			,mdf=mdf+".$p_be.",str=str+".$p_str.",smart=smart+".$p_smart.",agi=agi+".$p_agl."
			,life=life+".$p_life.",vit=vit+".$p_vit.",au=au+".$p_au.",be=be+".$p_be.",p_lv=".$p[p_lv].",p_exp=".$p[p_exp].",p_nextexp=".$p[p_nextexp]."
			,p_money=p_money+".$money."
			,base_str=base_str+".$p_str.",base_smart=base_smart+".$p_smart.",base_agi=base_agi+".$p_agl."
			,base_life=base_life+".$p_life.",base_vit=base_vit+".$p_vit.",base_au=base_au+".$p_au.",base_be=base_be+".$p_be."
			,p_online_time=".$this->fight_time." where p_id=".$user_id;
		}
		$DB_site->query($sql);
		check_arm_status($user_id);
		unset($lv);
	}
	function fight_p_dm(&$p,&$m,$ss,$temp_s,$support=0,$agi_p_rag)//玩家攻擊function
	{
		global $show_fight_message;
		$fight_message="";
		$temp_d=0;
		if($p[fight_break]==1){return 0;}
		if($p[act_type]=="1")
		{
			$agi_p_rag+=20;
			for($i=0;$i<$p[at_count];$i++)
			{
				$s="";
				if($agi_p_rag > rand(1,100))
				{
					$cri=10;
					$cri_dmg=1.2;
					if($p[agi] > $m[agi])
					{
/*
						if($m[agi]<1){
							$fp=fopen("temp/m_log.txt","a+");
							fputs($fp,"m_id=".$m[m_id].", m_agi=".$m[agi]." , p_id=".$p[p_id]." , p_agi=".$p[agi]." \r\n");
							fclose($fp);
							$m[agi]=1;
						}
*/
						$cri=($p[agi]/$m[agi])*10;
						$cri_dmg=($p[agi]/$m[agi])*1.2;
						$cri_dmg_max=2*$p[acrtdmg];
						if($cri>20){$cri=20;}
						if($cri_dmg>$cri_dmg_max){$cri_dmg=$cri_dmg_max;}
					}
					if(rand(1,100) < ($cri+$p[act]))
					{

						$temp_d2=$this->fight($p[at]*$cri_dmg,$m[df],$temp_s,$m[ddef_dmg],$p[d_dmg]);
						$s=$p[p_sat_name];
					}else
					{
						$temp_d2=$this->fight($p[at],$m[df],$temp_s,$m[ddef_dmg],$p[d_dmg]);
					}
					$temp_d+=$temp_d2;
					$fight_message.=",\"parent.pact_date('$temp_d2','$s','$ss',$support)\"";
				}else
				{
					$fight_message.=",\"parent.miss_date('$p[p_name]','$m[m_name]')\"";
				}
			}
		}elseif($p[act_type]=="2")
		{
			for($i=0;$i<$p[mat_count];$i++)
			{
				$s="";
				$cri=10;
				$cri_dmg=1.2;
				if($p[au] > $m[au])
				{
					
					if($m[au]==0){
						$fp=fopen("temp/m_log.txt","a+");
						fputs($fp,"m_id=".$m[m_id].", au=".$m[au]." , p_id=".$p[p_id]." , au=".$p[au]."\r\n");
						fclose($fp);
						//$m[au]=1;
					}
					
					$temp_cri=$p[au]/$m[au];
					$cri=$temp_cri*10;
					$cri_dmg=$temp_cri*1.2;
					$cri_dmg_max=2*$p[mcrtdmg];
					if($cri>20){$cri=20;}
					if($cri_dmg>$cri_dmg_max){$cri_dmg=$cri_dmg_max;}
				}
				if(rand(1,100) < ($cri+$p[mct]))
				{
					$temp_d2=$this->fight($p[mat]*$cri_dmg,$m[mdf],$temp_s,$m[mdef_dmg],$p[m_dmg]);
					$s=$p[p_sat_name];
				}else
				{
					$temp_d2=$this->fight($p[mat],$m[mdf],$temp_s,$m[mdef_dmg],$p[m_dmg]);
				}
				$temp_d+=$temp_d2;
				$fight_message.=",\"parent.pact_date('$temp_d2','$s','$ss',$support)\"";
			}
		}
		if($show_fight_message)//a_mode判斷
		{
			$this->temp_fight_string.=$fight_message;
		}
		$this->skill_use(1,2,$temp_d);
		return $temp_d;
	}
	function fight_m_dm(&$m,&$p,$temp_s,$agi_m_rag)//敵人攻擊function
	{
		global $pet,$show_fight_message;
		$fight_message="";
		$temp_d=0;
		if($m[fight_break]==1){return 0;}
		if($m[act_type]=="1")
		{
			$agi_m_rag+=20;
			for($i=0;$i<$m[at_count];$i++)
			{
				$s="";
				if($agi_m_rag > rand(1,100))
				{
					$cri=10;
					$cri_dmg=1.2;
					if($m[agi] > $p[agi])
					{
/*
						if($p[agi]==0){
							$fp=fopen("temp/p_log.txt","a+");
							fputs($fp,"p_id=".$p[p_id].", agi=".$p[agi]." , m_id=".$m[m_id]." , agi=".$m[agi]."\r\n");
							fclose($fp);
							$p[agi]=1;
						}
*/
						$cri=($m[agi]/$p[agi])*10;
						$cri_dmg=($p[agi]/$m[agi])*1.2;
						$cri_dmg_max=2*$m[acrtdmg];
						if($cri>20){$cri=20;}
						if($cri_dmg>$cri_dmg_max){$cri_dmg=$cri_dmg_max;}
					}
					if(rand(1,100) < ($cri+$m[act]))
					{
						$temp_d2=$this->fight($m[at]*$cri_dmg,$p[df],$temp_s,$p[ddef_dmg],$m[d_dmg]);
						$s.=$m[m_sat_name];
					}else
					{
						$temp_d2=$this->fight($m[at],$p[df],$temp_s,$p[ddef_dmg],$m[d_dmg]);
					}
					if($pet!=null)//寵物抵擋敵人攻擊開始
					{
						$fight_message.=$this->guard($temp_d2,$s);
						//continue;
					}
					else
					{
						$fight_message.=",\"parent.mact_date('$temp_d2','$s','')\"";
					}
					$temp_d+=$temp_d2;
				}else
				{
					$fight_message.=",\"parent.miss_date('$m[m_name]','$p[p_name]')\"";
				}
			}
		}else
		{
			for($i=0;$i<$m[mat_count];$i++)
			{
				$s="";
				$cri=10;
				$cri_dmg=1.2;
				if($m[au] > $p[au])
				{
/*
					if($p[au]==0){
						$fp=fopen("temp/p_log.txt","a+");
						fputs($fp,"p_id=".$p[p_id].", au=".$p[au]." , m_id=".$m[m_id]." , au=".$m[au]."\r\n");
						fclose($fp);
						$p[au]=1;
					}
*/
					$temp_cri=$m[au]/$p[au];
					$cri=$temp_cri*10;
					$cri_dmg=$temp_cri*1.2;
					$cri_dmg_max=2*$m[mcrtdmg];
					if($cri>20){$cri=20;}
					if($cri_dmg>$cri_dmg_max){$cri_dmg=$cri_dmg_max;}
				}
				if(rand(1,100)<(10+$m[mct]))
				{
					$temp_d2=$this->fight($m[mat]*$cri_dmg,$p[mdf],$temp_s,$p[mdef_dmg],$m[m_dmg]);
				}else
				{
					$temp_d2=$this->fight($m[mat],$p[mdf],$temp_s,$p[mdef_dmg],$m[m_dmg]);
				}
				if($pet!=null)//寵物抵擋敵人攻擊開始
				{
					$fight_message.=$this->guard($temp_d2,$s);
					//continue;
				}
				else
				{
					$fight_message.=",\"parent.mact_date('$temp_d2','$s','')\"";
				}
				$temp_d+=$temp_d2;
			}
		}
		if($show_fight_message)//a_mode判斷
		{
			$this->temp_fight_string.=$fight_message;
		}
		$this->skill_use(2,2,$temp_d);
		return $temp_d;
	}
	function fight_pet_dm($pet,$p,$m,$s)//玩家寵物攻擊function
	{
		global $_POST,$show_fight_message;
		if(rand(1,3)<3) //玩家寵物攻擊
		{
			$temp_cri=($p[au]+$p[agi])/($m[au]+$m[agi]);
			$cri=$temp_cri*10;
			$cri_dmg=1;
			if($cri>20){$cri=20;}
			
			if(rand(1,100) < ($cri+$pet[pet_act]))
			{
				$cri_dmg=$temp_cri*1.2;
				if($cri_dmg>2){$cri_dmg=2;}
			}
			switch($s)
			{
				case 1:
					$temp_d=$this->fight($pet[pe_at]*$cri_dmg,$m[df],1,1,1);
				break;
				case 2:
					$temp_d=$this->fight($pet[pe_mt]*$cri_dmg,$m[mdf],1,1,1);
				break;
			}
		}
		else
		{
			$temp_d=0;
		}
		$temp_d=0;
		if($show_fight_message)//a_mode判斷
		{
			if($temp_d==0)
			{
				$this->temp_fight_string.=",\"parent.pet_miss_date('$pet[pe_name]',".rand(0,3).")\"";
			}else
			{
				$this->temp_fight_string.=",\"parent.pet_act_date('$temp_d','$s')\"";
			}
		}
		return $temp_d;
	}

	function fight_count($user_id,$cp=0,$p_support=null,$my_member=null)
	{
		global $DB_site,$_POST,$pet,$p,$m,$temp_pd,$temp_m_st,$temp_p_st,$temp_pet,$agi_p_rag,$agi_m_rag,$p_fast,$show_fight_message;
		if(count($this->p_skill_main) > 0 || count($this->m_skill_main) > 0){
			include_once('./class/wog_fight_skill.php');
		}
		if($_POST["temp_id2"]=="2")//a_mode判斷
		{
			$show_fight_message=true;
		}else
		{
			$show_fight_message=false;
		}
		$temp_pd=0;
		$exp=0;
		$s="";
		$this->sum=0;
		$s_start=0;
		//$m[hp]=999000000;
		//$p[hp]=999000000;
		//$p[hpmax]=999000000;
		$p[name]=$p[p_name];
		$m[name]=$m[m_name];
		$fight_end=0;
		$check_item=0;
		$p[at_count]=1;$m[at_count]=1;$p[mat_count]=1;$m[mat_count]=1;
		$p[act]=0;$m[act]=0;$p[mct]=0;$m[mct]=0; //爆擊率
		$p[acrtdmg]=1;$m[acrtdmg]=1;$p[mcrtdmg]=1;$m[mcrtdmg]=1; //爆擊傷害加成
		$p[rag]=0;$m[rag]=0; //迴避率
		$p[escape]=0;$m[escape]=0; //逃跑率
		$p[ddef_dmg]=1;$m[ddef_dmg]=1; //物理減傷率
		$p[mdef_dmg]=1;$m[mdef_dmg]=1; //魔法減傷率
		$p[d_dmg]=0;$p[m_dmg]=0;$m[d_dmg]=0;$m[m_dmg]=0; //附加傷害

		$p[fight_break]=0;
		$m[fight_break]=0;
		$p[skill_break]=0;
		$m[skill_break]=0;
	
		$p[pet_act_type]=0; //指定寵物攻擊方式
		$p[petact_m]=0; //計能修正後的寵物出擊率
		if($pet)
		{
			$pet[pet_act]=0;//寵物爆擊率
		}
		$this->fight_time=time();
		$p[act_type]=1;$m[act_type]=1;
		if(!empty($_POST["at_type"]))
		{
			$p[act_type]=$_POST["at_type"];
		}
		else
		{
			$p[act_type]=$this->m_type($p[at],$p[mat]);
		}
		if($pet!=null)//寵物個性判斷
		{
			switch($pet[pe_type])
			{
				case 1:
					$pet[pe_at]=$pet[pe_at]*1.2;
				break;
				case 2:
					$pet[pe_mt]=$pet[pe_mt]*1.2;
				break;
				case 3:
					$pet[pe_def]=$pet[pe_def]*1.2;
				break;
				case 4:
					$pet[pe_fi]=$pet[pe_fi]*1.5;
				break;
			}
			$petact=(($pet[pe_he]/255)*10)+(($pet[pe_fi]/255)*10)+round($p[au]/$m[au]);
			if($petact>35)
			{
				$petact=35;
			}
			$this->petact=$petact;
		}
		if($p[d_s]!=NULL){$p[s_property]=$p[d_s];}
		$p[temp_s]=$this->s_check($p[s_property]-$m[s_property]); //屬性判斷
		$m[temp_s]=$this->s_check($m[s_property]-$p[s_property]); //屬性判斷
		$m[id]="m".$m[m_id];
		$f_hp=$p[hpmax]*$this->f_hp;
		$f_sp=$p[spmax]*$this->f_sp;

		$escape_hp=$p[hpmax]*$this->escape_hp;
		//隊友攻擊命中率計算
		if($p_support!=null)
		{
			$agi_p_support_total=$p_support[agi]+$m[agi];
			$agi_p_support_rag=($p_support[agi]/$agi_p_support_total)*100;
			$p_support[at_count]=1;$p_support[mat_count]=1;
			$p_support[fight_break]=0;$p_support[skill_break]=0;
		}
		//if($p[d_g_hp]==0){$p[d_item_num]=0;}
		$this->pskill_event0=count($this->p_skill_event[0]);
		$this->pskill_event1=count($this->p_skill_event[1]);
		$this->pskill_event2=count($this->p_skill_event[2]);
		$this->mskill_event0=count($this->m_skill_event[0]);
		$this->mskill_event1=count($this->m_skill_event[1]);
		$this->mskill_event2=count($this->m_skill_event[2]);
		$temp_p_st=$p;
		$temp_m_st=$m;
		$temp_pet=$pet;
		while($p[hp]!=0 || $m[hp]!=0)
		{
			$ss="";
			$temp_p_st[hp]=$p[hp];
			$temp_p_st[sp]=$p[sp];
			$temp_m_st[hp]=$m[hp];
			$temp_m_st[sp]=$m[sp];
			$temp_pet[pe_def]=$pet[pe_def];

			$this->sum+=1;
			if($show_fight_message){$this->temp_fight_string.= ",\"parent.vc($this->sum)\"";}
			
			$p_fast=50;
			$agi_total=$p[agi]+$m[agi];
			$agi_p_rag=($p[agi]/$agi_total)*100 + $p[rag];
			$agi_m_rag=($m[agi]/$agi_total)*100 + $m[rag];
			if($p[agi] > $m[agi])
			{
				$p_fast=$p_fast+20;
			}else
			{
				$p_fast=$p_fast-20;
			}
			if(!empty($p[p_cp_st]))
			{
				if( rand(1,100) < 3 )
				{
					//######### pact ##########(己方攻擊開始)
					$temp_d=$this->fight_p_dm($p,$m,$ss,$p[temp_s],0,$agi_p_rag);//傷害計算
					//$temp_d=$this->fight_p_dm($p,$m,$me_skill,$this->act_type,$ss,$temp_ps);//傷害計算
					$m[hp]-=$temp_d;
					if($m[hp]<=0)//判斷是否戰勝
					{
						$exp=$this->win_check($user_id,$p,$m,$my_member);
						break;
					}
					$temp_pd+=$temp_d;
					//(己方攻擊結束)
				}
			}
			if(rand(1,100) < $p_fast) //己先攻
			{
				//######### pact ##########(己方攻擊開始)
				$ss=$this->skill_use(1,0);
				$temp_d=$this->fight_p_dm($p,$m,$ss,$p[temp_s],0,$agi_p_rag);//傷害計算
				$m[hp]-=$temp_d;
				if($m[hp]<=0)//判斷是否戰勝
				{
					$exp=$this->win_check($user_id,$p,$m,$my_member);
					break;
				}
				$temp_pd+=$temp_d;
				//(己方攻擊結束)
				
				//######### mact ##########(敵方攻擊開始)
				$ss=$this->skill_use(2,0);
				$temp_d=0;
				$m[act_type]=$this->m_type($m[at],$m[mat]);//判斷攻擊模式
				$temp_d=$this->fight_m_dm($m,$p,$m[temp_s],$agi_m_rag);//傷害計算
				$p[hp]-=$temp_d;
				if($p[hp]<=0)//判斷是否戰敗
				{
					$p[hp]=0;
					$money=0;
					$exp=$this->lost_check($user_id,$p,$m,$my_member,$temp_pd);
					break;
				}
			//(敵方攻擊結束)
			}
			else //怪先攻
			{
				//######### mact ##########(敵方攻擊開始)
				$ss=$this->skill_use(2,0);
				$temp_d=0;
				$m[act_type]=$this->m_type($m[at],$m[mat]);//判斷攻擊模式
				$temp_d=$this->fight_m_dm($m,$p,$m[temp_s],$agi_m_rag);//傷害計算

				$p[hp]-=$temp_d;
				if($p[hp]<=0)//判斷是否戰敗
				{
					$p[hp]=0;
					$money=0;
					$exp=$this->lost_check($user_id,$p,$m,$my_member,$temp_pd);
					break;
				}
				//(敵方攻擊結束)

				//######### pact ##########(己方攻擊開始)
				$ss=$this->skill_use(1,0);
				$temp_d=$this->fight_p_dm($p,$m,$ss,$p[temp_s],0,$agi_p_rag);//傷害計算

				$m[hp]-=$temp_d;
				if($m[hp]<=0)//判斷是否戰勝
				{
					$exp=$this->win_check($user_id,$p,$m,$my_member);
					break;
				}
				$temp_pd+=$temp_d;
				//(己方攻擊結束)
			}

			//######### p_support ##########(隊友攻擊開始)
			$temp_d=0;
			if($p_support!=null)
			{
				if(rand(1,100) < (10+$p[p_support_m]) && $p_support["hp"] > ($p_support["hpmax"]*0.1))
				{
					$p_support[act_type]=$this->m_type($p_support[at],$p_support[mat]);//判斷攻擊模式
					$temp_d=$this->fight_p_dm($p_support,$m,$ss,$p[temp_s],1,$agi_p_support_rag);//傷害計算

					$m[hp]-=$temp_d;
					if($m[hp]<=0)//判斷是否戰勝
					{
						$exp=$this->win_check($user_id,$p,$m,$my_member);
						break;
					}
					$temp_pd+=$temp_d;
				}
			}
			//(隊友攻擊結束)

			//######### petact ##########(寵物攻擊開始)
			$temp_d=0;
			if($pet!=null)
			{
				if(rand(1,100) < ($this->petact+ $p[petact_m]) && $pet[pe_def] > 0)
				{
					$fight_type=rand(1,2);
					if($p[pet_act_type]==1){$fight_type=1;}
					if($p[pet_act_type]==2){$fight_type=2;}
					$temp_d=$this->fight_pet_dm($pet,$p,$m,$fight_type);//傷害計算
					/*
					if($fight_type==1)
					{
						$temp_d=$this->fight_pet_dm($pet[pe_at],$pet[pe_name],$m,"1");//傷害計算
					}elseif($fight_type==2)
					{
						$temp_d=$this->fight_pet_dm($pet[pe_mt],$pet[pe_name],$m,"2");//傷害計算
					}elseif($fight_type==3)
					{
						$temp_d=$this->fight_pet_dm(($pet[pe_at]+$pet[pe_mt])*1.5,$pet[pe_name],$m,"3");//傷害計算
					}
					*/
				}
				$m[hp]-=$temp_d;
				if($m[hp]<=0)//判斷是否戰勝
				{
					$exp=$this->win_check($user_id,$p,$m,$my_member);
					break;
				}
				$temp_pd+=$temp_d;
			}
			//(寵物攻擊結束)
			if($this->sum >= $this->f_count)//判斷戰鬥是否超過回合數設定
			{
				if((int)$_POST["act"]>=1000)
				{
//					$exp=$this->get_exp($temp_pd,$m[m_hpmax],$m[m_at],$m[m_mat],$m[m_lv]*0.2,$p[p_lv],0);
					$exp=0;
				}else
				{
					if($temp_pd > $m[hpmax]){$temp_pd=$m[hpmax];}
					$temp_pd=round($temp_pd/1.5);
					$exp=$this->get_exp($temp_pd,$m[hpmax],$m[at],$m[mat],$m[m_lv]*0.6,$p[p_lv],0);
				}
				$money=0;
				$this->temp_fight_string.=",\"parent.end_date('$p[p_name] 戰鬥超過".$this->f_count."回合 ','0','$exp',0,'$p[hp]','$this->sum','$p[d_item_num2]')\"";
				$this->win=0;
				$this->lost=1;
				break;
			}

			//喝補hp,sp藥水
			if($f_hp > $p[hp] && $f_sp > $p[sp] && ($p[d_item_id2]>0 && ($p[d_g_hp]>0 || $p[d_g_sp]>0) && $p[d_item_num2]>0) && $check_item < 9 && $this->mercenary==0)
			{
//				$bhp=$p[hp];
				$p[hp]=$p[hp]+$p[d_g_hp];
				$p[sp]=$p[sp]+$p[d_g_sp];
				if($p[hp]>$p[hpmax]){$p[hp]=$p[hpmax];}
				if($p[sp]>$p[spmax]){$p[sp]=$p[spmax];}
				$p[d_item_num2]--;
				if($p[d_item_num2]<=0)
				{
					$p[d_item_id2]=0;
					$this->temp_fight_string.=",\"parent.arm_setup('d_item_id2','','')\"";
				}
				$check_item++;
				if($show_fight_message)//a_mode判斷
				{
					$this->temp_fight_string.=",\"parent.fight_event3('$p[name]','$p[d_name]')\"";
					$this->temp_fight_string.=",\"parent.fight_event2('$p[name]',$p[d_g_hp],$p[d_g_sp])\"";
				}
				$temp_p_st[d_item_id2]=$p[d_item_id2];
				$temp_p_st[d_item_num2]=$p[d_item_num2];
			}
			//戰鬥逃跑
			if($escape_hp > $p[hp])
			{
				if($this->escape($p[agi],$m[agi]))
				{
					$exp=0;
					$money=0;
					$this->temp_fight_string.=",\"parent.end_date('$p[p_name] 逃跑成功','0','$exp',0,'$p[hp]','$this->sum','$p[d_item_num2]')\"";
					$this->win=0;
					$this->lost=1;
					break;
				}
			}
		}
		$this->cp_m_hp=$m[hp];//冠軍的hp,若在冠軍挑戰模式下,冠軍hp將會被寫入db
		$this->cp_m_sp=$m[sp];//冠軍的sp
		unset($m,$pet,$my_member);
		if($check_item > 0)
		{
			$DB_site->query("update wog_player_arm set d_item_id2=".$p[d_item_id2].",d_item_num2=".$p[d_item_num2]." where p_id=".$user_id);
		}
		$p[p_exp]+=$exp;
		$this->mercenary_exp=$exp;
		$money=$this->money;

		if($p[p_exp]>$p[p_nextexp])//判斷是否升級
		{
			$this->lv_up($user_id,$p,$money);
		}else
		{
			if($this->mercenary==0)
			{
				$sql="update wog_player set p_exp=".$p[p_exp].",p_money=p_money+".($money+$cp).",hp=".$p[hp].",sp=".$p[sp].",p_win=p_win+".$this->win.",p_lost=p_lost+".$this->temp_lost."
				 ,p_act_time =".$this->fight_time.",p_online_time=".$this->fight_time.",act_num=".$p[act_num]."
				,p_sat_name='".htmlspecialchars(stripslashes(trim($p[p_sat_name])))."',p_place=$this->p_place
				where p_id=".$user_id;
			}
			else
			{
				$sql="update wog_player set p_exp=".$p[p_exp].",p_money=p_money+".($money+$cp).",p_online_time=".$this->fight_time."
				where p_id=".$user_id;
			}
			$DB_site->query($sql);
		}
		check_buffer($user_id);
//		return $p;
//		unset($p);
	}
	function win_check($user_id,$p,$m,$my_member)
	{
		global $DB_site,$_POST,$temp_m_st;
		if((int)$_POST["act"]>=1000)
		{
//			$exp=$this->get_exp($m[m_hpmax],$m[m_hpmax],$m[m_at],$m[m_mat],$m[m_lv]*0.2,$p[p_lv],1);
			$exp=0;
			$money=0;
		}else
		{
			$m=$temp_m_st;
			$exp=$this->get_exp($m[hpmax],$m[hpmax],$m[at],$m[mat],$m[m_lv],$p[p_lv],1);
			$money=round($this->get_money($m[hpmax],$m[at],$m[mat],$m[m_lv])*$this->skill_money_up);
			// 載入玩家商城效果 begin
			$time=time();
			$sql="select p_id,end_time,exp,skill_exp from wog_player_buffer where p_id=$user_id and end_time>".$time;
			$buffer=$DB_site->query($sql);
			while($buffers=$DB_site->fetch_array($buffer))
			{
				if(!empty($buffers[exp]))
				{
					$exp=round($exp*$buffers[exp]);
				}
				if(!empty($buffers[skill_exp]))
				{
					$m[m_job_exp]=round($m[m_job_exp]*$buffers[skill_exp]);
				}
			}
			$DB_site->free_result($buffer);
			unset($buffers);
			// 載入玩家商城效果 end
			$DB_site->query("update wog_ch_exp set ch_".$p[ch_id]."=ch_".$p[ch_id]."+".$m[m_job_exp].",sk_".$p[ch_id]."=sk_".$p[ch_id]."+".$m[m_job_exp]." where p_id=".$user_id);
/*			
			if($this->mercenary==0)
			{
				$DB_site->query("update wog_ch_exp set ch_".$p[ch_id]."=ch_".$p[ch_id]."+".$m[m_job_exp].",sk_".$p[ch_id]."=sk_".$p[ch_id]."+".$m[m_job_exp]." where p_id=".$user_id);
			}
*/
		}
		$this->win=1;
		$this->lost=0;
		$this->money=round($money);
		/*
		if(!empty($my_member["members"]) && $exp > 0)
		{
			if($my_member["members"] > 1)
			{
				$exp=$exp+($exp*0.009*$my_member["members"]);
				$exp=round($exp/$my_member["members"]);
				
				$get_group_min1=$my_member["members_lv"]*0.7;
				$get_group_max1=$my_member["members_lv"]*1.3;
				$get_group_min2=$my_member["members_lv"]*0.5;
				$get_group_max2=$my_member["members_lv"]*1.5;
				
				$DB_site->query("update wog_player set p_exp=p_exp+".$exp."	where  t_id=".$p["t_id"]." and p_id<>".$user_id." and p_lv>=".$get_group_min1." and p_lv<=".$get_group_max1." and p_act_time > $this->datecut and p_place=".$this->p_place." and p_lock=0");
				$DB_site->query("update wog_player set p_exp=p_exp+".($exp/2)."	where t_id=".$p["t_id"]." and p_id<>".$user_id." and (p_lv<".$get_group_min1." and p_lv>=".$get_group_min2.") and (p_lv>".$get_group_max1." and p_lv<=".$get_group_max2.") and p_act_time > $this->datecut and p_place=".$this->p_place." and p_lock=0");
				$DB_site->query_first("COMMIT");				
			}
		}
		*/
		$this->temp_fight_string.=",\"parent.end_date('$p[p_name]','1','$exp','$this->money','$p[hp]','$this->sum','$p[d_item_num2]')\"";
		return $exp;
	}
	function lost_check($user_id,$p,$m,$my_member,$temp_pd)
	{
		global $DB_site,$_POST;
		if((int)$_POST["act"]>=1000)
		{
			$exp=0;
		}else
		{
			//if($temp_pd > $m[hpmax]){$temp_pd=$m[hpmax];}
			//$temp_pd=round($temp_pd/10);
			//$exp=$this->get_exp($temp_pd,$m[hpmax],$m[at],$m[mat],$m[m_lv],$p[p_lv],0);
			$exp=0;
			//$DB_site->query("update wog_ch_exp set ch_".$p[ch_id]."=ch_".$p[ch_id]."+1 where p_id=".$user_id);
			$this->temp_lost=1;
		}
		$this->temp_fight_string.=",\"parent.end_date('$p[p_name]','0','$exp',0,'$p[hp]','$this->sum','$p[d_item_num2]')\"";
		$this->win=0;
		$this->lost=1;
		/*
		if(!empty($my_member["members"]) && $exp > 0)
		{
			if($my_member["members"] > 1)
			{
				$exp=$exp+($exp*0.009*$my_member["members"]);
				$exp=round($exp/$my_member["members"]);
				
				$get_group_min1=$my_member["members_lv"]*0.7;
				$get_group_max1=$my_member["members_lv"]*1.3;
				$get_group_min2=$my_member["members_lv"]*0.5;
				$get_group_max2=$my_member["members_lv"]*1.5;
				
				$DB_site->query("update wog_player set p_exp=p_exp+".$exp."	where  t_id=".$p["t_id"]." and p_id<>".$user_id." and p_lv>=".$get_group_min1." and p_lv<=".$get_group_max1." and p_act_time > $this->datecut and p_place=".$this->p_place." and p_lock=0");
				$DB_site->query("update wog_player set p_exp=p_exp+".($exp/2)."	where t_id=".$p["t_id"]." and p_id<>".$user_id." and (p_lv<".$get_group_min1." and p_lv>=".$get_group_min2.") and (p_lv>".$get_group_max1." and p_lv<=".$get_group_max2.") and p_act_time > $this->datecut and p_place=".$this->p_place." and p_lock=0");
				$DB_site->query_first("COMMIT");
			}
		}
		*/
		return $exp;
	}
	function s_check($temp_s)
	{
		switch($temp_s)
		{
			case -1:
				$temp_s=1.2;
			break;
			case 1:
				$temp_s=0.8;
			break;
			case 5:
				$temp_s=1.2;
			break;
			case -5:
				$temp_s=0.8;
			break;
			default:
				$temp_s=1;
			break;
		}
		return $temp_s;
	}
	function escape($a,$b){
		$run=0;
		if($a[agi] > $b[agi])
		{
			$run=30;
		}
		else
		{
			$run=10;
		}
		if($a[agi]/3 > $b[agi]){$run+=10;}
		if($a[agi]/2 > $b[agi]){$run+=10;}
		if(rand(1,100) <= $run)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function guard(&$temp_d,$s)
	{
		global $DB_site,$pet;
		if(rand(1,100) < $this->petact && $pet[pe_def] > 0)
		{
			//$s="";
			//$this->pe_def=1;
			//echo "######-".$pet[pe_def]."-######";
			$pet[pe_def]-=$temp_d;
			if($pet[pe_def]<=0)
			{
				$a= ",\"parent.pet_def_date('$temp_d','$s','1')\"";
				$pet=null;
				$temp_d=0;
			}else
			{
				//unset($pet);
				$a= ",\"parent.pet_def_date('$temp_d','$s','')\"";
				$temp_d=0;
			}
		}
		else
		{
			$a= ",\"parent.mact_date('$temp_d','$s','')\"";
		}
		return $a;
	}
	function skill_use($type,$type2=0,$dmg=0)
	{
		global $p,$m,$agi_p_rag,$agi_m_rag,$p_fast,$show_fight_message,$temp_p_st,$temp_m_st,$temp_pet;
		$ss="";
		$reload_list=array();
		$s_id=0;
		switch($type2)
		{
			case 0:
				$count=$this->pskill_event0;
				$count2=$this->mskill_event0;
			break;
			case 2:
				$count=$this->pskill_event2;
				$count2=$this->mskill_event2;
			break;
		}
		switch($type)
		{
			case 1:
				//玩家使用技能
				for($i=$count-1;$i>=0;$i--)
				{
					$s_id=$this->p_skill_event[$type2][$i];
					if($this->p_skill_start[$s_id] > 0)
					{
						if($this->p_skill_start[$s_id] >= $this->p_skill_count[$s_id])
						{
							$this->p_skill_start[$s_id]=0;
							//eval("skill_undo_".$s_id."(&\$p,\$p[p_id]);");
							if(isset($this->m_dot_buffer[$s_id]))
							{
								if(!in_array($this->m_dot_buffer[$s_id][0],$reload_list))
								{
									$reload_list[]=array(1,$this->m_dot_buffer[$s_id][0]);
								}								
							}
							if(!in_array($p[p_id],$reload_list))
							{
								$reload_list[]=array(0,$p[p_id]);
							}
							unset($this->m_dot_buffer[$s_id],$this->p_status_buffer[$s_id]);
						}else
						{
							$this->p_skill_start[$s_id]++;
						}
					}
				}
				$this->reload_status($reload_list);
				for($i=0;$i<$count;$i++)
				{
					if($p[fight_break]==1 || $p[skill_break]==1){break;}
					$s_id=$this->p_skill_event[$type2][$i];
					if($this->p_skill_main[$s_id][sp] <= $p[sp] && rand(1,100) <= $this->p_skill_main[$s_id][stime] && $this->p_skill_start[$s_id]==0)//判斷奧義是否發動
					{
						$p[sp]-=$this->p_skill_main[$s_id][sp];
						if($this->p_skill_count[$s_id] >1)
						{
							$this->p_status_buffer[$s_id][]=array($user_id,$this->p_skill_main[$s_id][lv]);
						}
						eval("\$use_skill=skill_".$s_id."(\$p,\$this->p_skill_main[\$s_id][lv],\$p[p_id],0,\$dmg);");
						if($use_skill==false)
						{
							if($show_fight_message)//a_mode判斷
							{
								$this->temp_fight_string.= ",\"parent.fight_event('$p[name]','".$this->p_skill_main[$s_id][name]."')\"";
							}
							$this->p_skill_start[$s_id]=1;
							$ss="1";
						}
					}
				}
				
			break;
			case 2:
				//怪物使用技能
				for($i=$count2-1;$i>=0;$i--)
				{
					$s_id=$this->m_skill_event[$type2][$i];
					if($this->m_skill_start[$s_id] > 0)
					{
						if($this->m_skill_start[$s_id] >= $this->m_skill_count[$s_id])
						{
							$this->m_skill_start[$s_id]=0;
							//eval("skill_undo_".$s_id."(&\$m,\$m[id]);");
							if(isset($this->p_dot_buffer[$s_id]))
							{
								if(!in_array($this->p_dot_buffer[$s_id][0],$reload_list))
								{
									$reload_list[]=array(0,$this->p_dot_buffer[$s_id][0]);
								}								
							}
							if(!in_array($m[id],$reload_list))
							{
								$reload_list[]=array(1,$m[id]);
							}
							unset($this->p_dot_buffer[$s_id],$this->m_status_buffer[$s_id]);
						}else
						{
							$this->m_skill_start[$s_id]++;
						}
					}
				}
				$this->reload_status($reload_list);
				for($i=0;$i<$count2;$i++)
				{
					if($m[fight_break]==1 || $m[skill_break]==1){break;}
					$s_id=$this->m_skill_event[$type2][$i];
					if($this->m_skill_main[$s_id][sp] <= $m[sp] && rand(1,100) <= $this->m_skill_main[$s_id][stime] && $this->m_skill_start[$s_id]==0)//判斷奧義是否發動
					{
						$m[sp]-=$this->m_skill_main[$s_id][sp];
						if($show_fight_message)//a_mode判斷
						{
							$this->temp_fight_string.= ",\"parent.fight_event('$m[name]','".$this->m_skill_main[$s_id][name]."')\"";
						}
						if($this->m_skill_count[$s_id] >1)
						{
							$this->m_status_buffer[$s_id][]=array($m[id],$this->m_skill_main[$s_id][lv]);
						}
						eval("skill_".$s_id."(\$m,\$this->m_skill_main[\$s_id][lv],\$m[id],0,\$dmg);");
						$this->m_skill_start[$s_id]=1;
						$ss="1";
					}
				}
			break;
		}
		$p_fast=50;
		$agi_total=$p[agi]+$m[agi];
		$agi_p_rag=($p[agi]/$agi_total)*100 + $p[rag];
		$agi_m_rag=($m[agi]/$agi_total)*100 + $m[rag];
		if($p[agi] > $m[agi])
		{
			$p_fast=$p_fast+20;
		}else
		{
			$p_fast=$p_fast-20;
		}
		$temp_p_st[hp]=$p[hp];
		$temp_p_st[sp]=$p[sp];
		$temp_m_st[hp]=$m[hp];
		$temp_m_st[sp]=$m[sp];
		$temp_pet[pe_def]=$pet[pe_def];
		return $ss;
	}
	function reload_status($list)
	{
		global $p,$m,$temp_m_st,$temp_p_st,$temp_pet;
		foreach($list as $value)
		{
			switch($value[0])
			{
				case 0:
					$p=$temp_p_st;
					$pet=$temp_pet;
					foreach($this->p_status_buffer as $key=>$value1) //玩家增益buffer
					{
						eval("\$use_skill=skill_".$key."(\$p,\$this->p_status_buffer[\$key][1],\$p[p_id],2,0);");
					}
					foreach($this->p_dot_buffer as $key=>$value1) //玩家有害buffer
					{
						eval("\$use_skill=skill_".$key."(\$p,\$this->p_dot_buffer[\$key][1],\$m[id],1,0);");
					}
				break;
				case 1:
					$m=$temp_m_st;
					foreach($this->m_status_buffer as $key=>$value1)//對方增益buffer
					{
						eval("\$use_skill=skill_".$key."(\$m,\$this->m_status_buffer[\$key][1],\$m[id],2,0);");
					}
					foreach($this->m_dot_buffer as $key=>$value1)//對方有害buffer
					{
						eval("\$use_skill=skill_".$key."(\$m,\$this->m_dot_buffer[\$key][1],\$p[p_id],1,0);");
					}
				break;
			}
		}
	}
	function event_recomm($recommid,$o_lv,$p_lv,$p_name)
	{
		global $DB_site,$lang,$wog_arry,$a_id,$wog_item_tool;
		$honor_id=array(1304,1305,1306,1307,2222);
		$w_id=array(283,284,285,286,287,288,289);
		$get_honor=array();
		$get_ex=array();
		$get_w=array();
		$act_num=0;
		$time=time();
		for($i=$o_lv;$i<$p_lv;$i++)
		{
			switch(true)
			{
				case $i%100==0:
					if($i==300 || $i==500)
					{
						$rand_keys = array_rand($w_id);
						if(isset($get_w[$rand_keys]))
						{
							$get_w[$rand_keys]["num"]++;
						}
						else
						{
							$get_w[$rand_keys]["id"]=$w_id[$rand_keys];
							$get_w[$rand_keys]["num"]=1;
						}
						break;
					}
					if($i<500)
					{
						$rand_keys = array_rand($honor_id);
						if(isset($get_honor[$rand_keys]))
						{
							$get_honor[$rand_keys]["num"]++;
						}
						else
						{
							$get_honor[$rand_keys]["id"]=$honor_id[$rand_keys];
							$get_honor[$rand_keys]["num"]=1;
						}				
					}
				break;
				default:
					$id=rand(1,13);
					if($id==13)
					{
						$act_num++;
					}else
					{
						if(isset($get_ex[$id]))
						{
							$get_ex[$id]+=rand(10,40);
						}else
						{
							$get_ex[$id]=rand(10,40);
						}
					}
				break;
			}
		}
		if($act_num>0)
		{
			$sql="select act_num from wog_player where p_id=".$recommid." for update";
			$p=$DB_site->query_first($sql);
			$p[act_num]+=$act_num;
			if($p[act_num]>50){$p[act_num]=50;}
			$sql="update wog_player set act_num=".$p[act_num]." where p_id=".$recommid;
			$DB_site->query($sql);
			$time=time();
			$DB_site->query("insert into wog_message(p_id,title,from_pid,dateline)values(".$recommid.",'".sprintf($lang['wog_act_friend_msg3'],$p_name,$p_lv,$act_num)."',".$recommid.",".$time.")");
		}
		if(count($get_ex)>0)
		{
			foreach($get_ex as $k=>$v)
			{
				$sql="select ex_name from wog_exchange_main where ex_id=".$k;
				$p=$DB_site->query_first($sql);
				$item_num=$v;
				$sql="select el_id,el_amount,el_money from wog_exchange_list where p_id=".$recommid." and ex_id=".$k." for update";
				$check_el=$DB_site->query_first($sql);
				if($check_el)
				{
					$temp_money2=($check_el[el_amount]*$check_el[el_money])/($check_el[el_amount]+$item_num);
					$sql="update wog_exchange_list set el_amount=el_amount+".$item_num.",el_money=".$temp_money2." where el_id=".$check_el[el_id];
					$DB_site->query($sql);
				}
				else
				{
					$sql="insert wog_exchange_list(p_id,ex_id,el_amount,el_money)values($recommid,$k,$item_num,0)";
					$DB_site->query($sql);
				}
				$p[ex_name]=$p[ex_name]."*".$item_num;
				$DB_site->query("insert into wog_message(p_id,title,from_pid,dateline)values(".$recommid.",'".sprintf($lang['wog_act_friend_msg2'],$p_name,$p_lv,$p[ex_name])."',".$recommid.",".$time.")");
			}
		}
		if(count($get_honor)>0)
		{
			$sql="select d_honor_id from wog_item where p_id=".$recommid." for update";
			$p=$DB_site->query_first($sql);
			$a_id="d_honor_id";
			$temp=explode(",",$p[d_honor_id]);
			$s=array();
			foreach($get_honor as $k=>$v)
			{
				$sql="select d_name from wog_df where d_id=".$v["id"];
				$p=$DB_site->query_first($sql);
				$temp=$wog_item_tool->item_in($temp,$v["id"],$v["num"]);
				$s[]=$p[d_name]."*".$v["num"];
			}
			$DB_site->query("update wog_item set d_honor_id='".implode(",",$temp)."' where p_id=".$recommid);
			$DB_site->query("insert into wog_message(p_id,title,from_pid,dateline)values(".$recommid.",'".sprintf($lang['wog_act_friend_msg2'],$p_name,$p_lv,implode("，",$s))."',".$recommid.",".$time.")");
		}
		if(count($get_w)>0)
		{
			$sql="select d_item_id from wog_item where p_id=".$recommid." for update";
			$p=$DB_site->query_first($sql);
			$a_id="d_item_id";
			$temp=explode(",",$p[d_item_id]);
			$s=array();
			foreach($get_w as $k=>$v)
			{
				$sql="select d_name from wog_df where d_id=".$v["id"];
				$p=$DB_site->query_first($sql);
				$temp=$wog_item_tool->item_in($temp,$v["id"],$v["num"]);
				$s[]=$p[d_name]."*".$v["num"];
			}
			$DB_site->query("update wog_item set d_item_id='".implode(",",$temp)."' where p_id=".$recommid);
			$DB_site->query("insert into wog_message(p_id,title,from_pid,dateline)values(".$recommid.",'".sprintf($lang['wog_act_friend_msg2'],$p_name,$p_lv,implode("，",$s))."',".$recommid.",".$time.")");
		}
	}
}
?>