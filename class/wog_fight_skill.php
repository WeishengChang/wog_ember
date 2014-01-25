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

/*
 * $trun說明:
 * 0:技能初發動
 * 1:持續性發動(有害)
 * 2:持續性發動(增益)
 */
function skill_0(&$a,$lv,$uid,$trun=0,$dmg) //爆氣
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][0][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1.01;
				break;
			case 2:
				$value=1.02;
				break;
			case 3:
				$value=1.03;
				break;
			case 4:
				$value=1.04;
				break;
			case 5:
				$value=1.05;
				break;
			case 6:
				$value=1.06;
				break;
			case 7:
				$value=1.07;
				break;
			case 8:
				$value=1.08;
				break;
			case 9:
				$value=1.09;
				break;
			case 10:
				$value=1.1;
				break;
		} // switch
		$skill_value[$uid][0][0]=$value;
	}
	$a[at]=$a[at]*$skill_value[$uid][0][0];
}
function skill_1(&$a,$lv,$uid,$trun=0,$dmg) //魔力增幅
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][1][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1.01;
				break;
			case 2:
				$value=1.02;
				break;
			case 3:
				$value=1.03;
				break;
			case 4:
				$value=1.04;
				break;
			case 5:
				$value=1.05;
				break;
			case 6:
				$value=1.06;
				break;
			case 7:
				$value=1.07;
				break;
			case 8:
				$value=1.08;
				break;
			case 9:
				$value=1.09;
				break;
			case 10:
				$value=1.1;
				break;
		} // switch
		$skill_value[$uid][1][0]=$value;
	}
	$a[mat]=$a[mat]*$skill_value[$uid][1][0];
}
function skill_2(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][2][0]))
	{
		$value_1=0;
		$value_2=0;
		$value_3=0;
		switch($lv){
			case 1:
				$value_1=2;
				$value_2=0.99;
				$value_3=-1;
				break;
			case 2:
				$value_1=3;
				$value_2=0.98;
				$value_3=-2;
				break;
			case 3:
				$value_1=4;
				$value_2=0.97;
				$value_3=-3;
				break;
			case 4:
				$value_1=5;
				$value_2=0.96;
				$value_3=-4;
				break;
			case 5:
				$value_1=6;
				$value_2=0.95;
				$value_3=-5;
				break;
			case 6:
				$value_1=7;
				$value_2=0.94;
				$value_3=-6;
				break;
			case 7:
				$value_1=8;
				$value_2=0.93;
				$value_3=-7;
				break;
			case 8:
				$value_1=9;
				$value_2=0.92;
				$value_3=-8;
				break;
			case 9:
				$value_1=10;
				$value_2=0.91;
				$value_3=-9;
				break;
			case 10:
				$value_1=11;
				$value_2=0.9;
				$value_3=-10;
				break;
		} // switch
		$skill_value[$uid][2][0]=$value_1;
		$skill_value[$uid][2][1]=$value_2;
		$skill_value[$uid][2][2]=$value_3;
	}
	$a[at_count]=$skill_value[$uid][2][0];
	$a[at]=$a[at]*$skill_value[$uid][2][1];
	$a[rag]+=$skill_value[$uid][2][2];
}
function skill_3(&$a,$lv,$uid,$trun=0,$dmg) //急影
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][3][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1.01;
				break;
			case 2:
				$value=1.02;
				break;
			case 3:
				$value=1.03;
				break;
			case 4:
				$value=1.04;
				break;
			case 5:
				$value=1.05;
				break;
			case 6:
				$value=1.06;
				break;
			case 7:
				$value=1.07;
				break;
			case 8:
				$value=1.08;
				break;
			case 9:
				$value=1.09;
				break;
			case 10:
				$value=1.1;
				break;
		} // switch
		$skill_value[$uid][3][0]=$value;
	}
	$a[agi]=$a[agi]*$skill_value[$uid][3][0];
}
function skill_4(&$a,$lv,$uid,$trun=0,$dmg)//硬甲之樂
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][4][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1.01;
				break;
			case 2:
				$value=1.02;
				break;
			case 3:
				$value=1.03;
				break;
			case 4:
				$value=1.04;
				break;
			case 5:
				$value=1.05;
				break;
			case 6:
				$value=1.06;
				break;
			case 7:
				$value=1.07;
				break;
			case 8:
				$value=1.08;
				break;
			case 9:
				$value=1.09;
				break;
			case 10:
				$value=1.1;
				break;
		} // switch
		$skill_value[$uid][4][0]=$value;
	}
	$a[df]=$a[df]*$skill_value[$uid][4][0];
}
function skill_5(&$a,$lv,$uid,$trun=0,$dmg)//魔甲之樂
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][5][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1.01;
				break;
			case 2:
				$value=1.02;
				break;
			case 3:
				$value=1.03;
				break;
			case 4:
				$value=1.04;
				break;
			case 5:
				$value=1.05;
				break;
			case 6:
				$value=1.06;
				break;
			case 7:
				$value=1.07;
				break;
			case 8:
				$value=1.08;
				break;
			case 9:
				$value=1.09;
				break;
			case 10:
				$value=1.1;
				break;
		} // switch
		$skill_value[$uid][5][0]=$value;
	}
	$a[mdf]=$a[mdf]*$skill_value[$uid][5][0];
}
function skill_6(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][6][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.98;
				break;
			case 2:
				$value=0.96;
				break;
			case 3:
				$value=0.94;
				break;
			case 4:
				$value=0.92;
				break;
			case 5:
				$value=0.9;
				break;
		} // switch
		$skill_value[$uid][6][0]=$value;
	}
	$a[ddef_dmg]=$skill_value[$uid][6][0];
}
function skill_7(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][7][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.98;
				break;
			case 2:
				$value=0.96;
				break;
			case 3:
				$value=0.94;
				break;
			case 4:
				$value=0.92;
				break;
			case 5:
				$value=0.9;
				break;
		} // switch
		$skill_value[$uid][7][0]=$value;
	}
	$a[mdef_dmg]=$skill_value[$uid][7][0];
}
function skill_8(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][8][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1.05;
				break;
			case 2:
				$value=1.1;
				break;
			case 3:
				$value=1.15;
				break;
			case 4:
				$value=1.2;
				break;
			case 5:
				$value=1.25;
				break;
			case 6:
				$value=1.3;
				break;
		} // switch
		$skill_value[$uid][8][0]=$value;
	}
	$a[at]=$a[at]*$skill_value[$uid][8][0];
}
function skill_9(&$a,$lv,$uid,$trun=0,$dmg) //嗜血
{
	global $skill_value,$skill_undo_value,$p,$m,$wogclass;
	if(empty($skill_value[$uid][9][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.02;
				break;
			case 2:
				$value=0.04;
				break;
			case 3:
				$value=0.06;
				break;
			case 4:
				$value=0.08;
				break;
			case 5:
				$value=0.1;
				break;
		} // switch
		$skill_value[$uid][9][0]=$value;
	}
	if($a[act_type]=="1")
	{
		if(empty($a[p_id]))
		{
			if($dmg > $p[hp]){$dmg=$p[hp];}
		}
		else
		{
			if($dmg > $m[hp]){$dmg=$m[hp];}
		}
		$temp_hp=round($dmg*$skill_value[$uid][9][0]);
		$wogclass->temp_fight_string.=",\"parent.fight_event2('$a[name]',$temp_hp)\"";
		$a[hp]+=$temp_hp;
		if($a[hp]>$a[hpmax]){$a[hp]=$a[hpmax];}
	}
}
function skill_10(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value,$wogclass;
	if(empty($skill_value[$uid][10][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1;
				break;
			case 2:
				$value=1.3;
				break;
			case 3:
				$value=1.6;
				break;
			case 4:
				$value=1.9;
				break;
			case 5:
				$value=2.2;
				break;
			case 6:
				$value=2.5;
				break;
		} // switch
		$skill_value[$uid][10][0]=$value;
	}
	$temp_hp=round($a[smart]*$skill_value[$uid][10][0]);
	$wogclass->temp_fight_string.=",\"parent.fight_event2('$a[name]',$temp_hp)\"";
	$a[hp]=$a[hp]+$temp_hp;
	if($a[hp]>$a[hpmax]){$a[hp]=$a[hpmax];}
}
function skill_11(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][11][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=2;
				break;
			case 2:
				$value=4;
				break;
			case 3:
				$value=6;
				break;
			case 4:
				$value=8;
				break;
			case 5:
				$value=10;
				break;
			case 6:
				$value=12;
				break;
			case 7:
				$value=14;
				break;
		} // switch
		$skill_value[$uid][11][0]=$value;
	}
	$a[act]+=$skill_value[$uid][11][0];
}
function skill_12(&$a,$lv,$uid,$trun=0,$dmg)//煙遁
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][12][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=5;
				break;
			case 2:
				$value=10;
				break;
			case 3:
				$value=15;
				break;
			case 4:
				$value=20;
				break;
			case 5:
				$value=25;
				break;
		} // switch
		$skill_value[$uid][12][0]=$value;
	}
	$a[escape]=$skill_value[$uid][12][0];
}
function skill_13(&$a,$lv,$uid,$trun=0,$dmg)//靡靡之音
{
	global $skill_value,$skill_undo_value,$p,$m,$wogclass;
	if(empty($skill_value[$uid][13][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.99;
				break;
			case 2:
				$value=0.98;
				break;
			case 3:
				$value=0.97;
				break;
			case 4:
				$value=0.96;
				break;
			case 5:
				$value=0.95;
				break;
			case 6:
				$value=0.94;
				break;
			case 7:
				$value=0.93;
				break;
		} // switch
		$skill_value[$uid][13][0]=$value;
	}
	switch($trun)
	{
		case 0:
			if(!empty($a[p_id]))
			{
				$m[agi]=$m[agi]*$skill_value[$uid][13][0];
				skill_set_dot(13,$m[id],$lv,2);
			}
			else
			{
				$p[agi]=$p[agi]*$skill_value[$uid][13][0];
				skill_set_dot(13,$p[p_id],$lv,1);
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[agi]=$p[agi]*$skill_value[$uid][13][0];
			}
			else
			{
				$m[agi]=$m[agi]*$skill_value[$uid][13][0];
			}
		break;
	}
}

function skill_14(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][14][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.08;
				break;
			case 2:
				$value=0.16;
				break;
			case 3:
				$value=0.24;
				break;
			case 4:
				$value=0.32;
				break;
			case 5:
				$value=0.4;
				break;
			case 6:
				$value=0.48;
				break;
		} // switch
		$skill_value[$uid][14][0]=$value;
	}
	$a[d_dmg]+=$a[au]*$skill_value[$uid][14][0];
	$a[m_dmg]+=$a[au]*$skill_value[$uid][14][0];
}

function skill_15(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$wogclass;
	if(empty($skill_value[$uid][15][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.6;
				break;
			case 2:
				$value=0.8;
				break;
			case 3:
				$value=1;
				break;
			case 4:
				$value=1.2;
				break;
			case 5:
				$value=1.4;
				break;
			case 6:
				$value=1.6;
				break;
		} // switch
		$skill_value[$uid][15][0]=$value;
	}
	$temp_hp=round($a[au]*$skill_value[$uid][15][0]);
	$wogclass->temp_fight_string.=",\"parent.fight_event2('$a[name]',$temp_hp)\"";
	$a[hp]+=$temp_hp;
	if($a[hp]>$a[hpmax]){$a[hp]=$a[hpmax];}
}

function skill_49(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][49][0]))
	{
		$value_1=0;
		$value_2=0;
		switch($lv){
			case 1:
				$value_1=2;
				$value_2=0.97;
				break;
			case 2:
				$value_1=4;
				$value_2=0.94;
				break;
			case 3:
				$value_1=6;
				$value_2=0.91;
				break;
			case 4:
				$value_1=8;
				$value_2=0.88;
				break;
			case 5:
				$value_1=10;
				$value_2=0.85;
				break;
			case 6:
				$value_1=12;
				$value_2=0.82;
				break;
			case 7:
				$value_1=14;
				$value_2=0.79;
				break;
		} // switch
		$skill_value[$uid][49][0]=$value_1;
		$skill_value[$uid][49][1]=$value_2;
	}
	$a[act]+=$skill_value[$uid][49][0];
	$a[df]=$a[df]*$skill_value[$uid][49][1];
}

function skill_16(&$a,$lv,$uid,$trun=0,$dmg) //戰神之怒
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][16][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=-70;
				break;
			case 2:
				$value=-65;
				break;
			case 3:
				$value=-60;
				break;
			case 4:
				$value=-55;
				break;
			case 5:
				$value=-50;
				break;
			case 6:
				$value=-45;
				break;
		} // switch
		$skill_value[$uid][16][0]=$value;
	}
	$a[rag]+=$skill_value[$uid][16][0];
	$a[at]+=$a[str]*0.45;
}

function skill_17(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][17][0]))
	{
		$value_1=0;
		$value_2=0;
		switch($lv){
			case 1:
				$value_1=1.05;
				$value_2=1.05;
				break;
			case 2:
				$value_1=1.1;
				$value_2=1.1;
				break;
			case 3:
				$value_1=1.15;
				$value_2=1.15;
				break;
			case 4:
				$value_1=1.2;
				$value_2=1.2;
				break;
			case 5:
				$value_1=1.25;
				$value_2=1.25;
				break;
			case 6:
				$value_1=1.3;
				$value_2=1.3;
				break;
		} // switch
		$skill_value[$uid][17][0]=$value_1;
		$skill_value[$uid][17][1]=$value_2;
	}
	$a[at]=$a[at]*$skill_value[$uid][17][0];
	$a[agi]=$a[agi]*$skill_value[$uid][17][1];
	$a[at_count]=2;
}

function skill_18(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value,$wogclass;
	if(empty($skill_value[$uid][18][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.55;
				break;
			case 2:
				$value=0.6;
				break;
			case 3:
				$value=0.65;
				break;
			case 4:
				$value=0.7;
				break;
			case 5:
				$value=0.75;
				break;
			case 6:
				$value=0.8;
				break;
		} // switch
		$skill_value[$uid][18][0]=$value;
	}
	$a[at]=$a[at]*1.5;
	$temp_hp2=round($a[hp]*$skill_value[$uid][18][0]);
	$temp_hp=$a[hp]-$temp_hp2;
	$a[hp]=$temp_hp2;
	$wogclass->temp_fight_string.=",\"parent.fight_event2('$a[name]',-$temp_hp)\"";
	$a[rag]+=30;
}

function skill_19(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$p,$m;
	if(empty($skill_value[$uid][19][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=3;
				break;
			case 2:
				$value=6;
				break;
			case 3:
				$value=9;
				break;
			case 4:
				$value=12;
				break;
			case 5:
				$value=15;
				break;
		} // switch
		$skill_value[$uid][19][0]=$value;
	}
	switch($trun)
	{
		case 0:
			if(rand(1,100)<$skill_value[$uid][19][0])
			{
				if(!empty($a[p_id]))
				{
					$m[at_count]=0;
					$m[mat_count]=0;
					$m[fight_break]=1;
					skill_set_dot(19,$m[id],$lv,2);
				}
				else
				{
					$p[at_count]=0;
					$p[mat_count]=0;
					$p[fight_break]=1;
					skill_set_dot(19,$p[p_id],$lv,1);
				}
			}
		break;
		case 1:
			if(empty($a[p_id]))
			{
				$m[at_count]=0;
				$m[mat_count]=0;
				$m[fight_break]=1;
			}
			else
			{
				$p[at_count]=0;
				$p[mat_count]=0;
				$p[fight_break]=1;
			}
		break;
	}
}

function skill_20(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value;
	if(empty($skill_value[$uid][20][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.02;
				break;
			case 2:
				$value=0.04;
				break;
			case 3:
				$value=0.06;
				break;
			case 4:
				$value=0.08;
				break;
			case 5:
				$value=0.1;
				break;
			case 6:
				$value=0.12;
				break;
		} // switch
		$skill_value[$uid][20][0]=$value;
	}
	$temp=$a[df]*$skill_value[$uid][20][0];
	$a[d_dmg]+=$temp;
	$a[df]=$a[df]-$temp;
}

function skill_21(&$a,$lv,$uid,$trun=0,$dmg)//反擊
{
	global $skill_value;
	if(empty($skill_value[$uid][21][0]))
	{
		$value_1=0;
		$value_2=0;
		switch($lv){
			case 1:
				$value_1=0.9;
				$value_2=1.01;
				break;
			case 2:
				$value_1=0.88;
				$value_2=1.02;
				break;
			case 3:
				$value_1=0.86;
				$value_2=1.03;
				break;
			case 4:
				$value_1=0.84;
				$value_2=1.04;
				break;
			case 5:
				$value_1=0.82;
				$value_2=1.05;
				break;
			case 6:
				$value_1=0.8;
				$value_2=1.06;
				break;
			case 7:
				$value_1=0.78;
				$value_2=1.07;
				break;
		} // switch
		$skill_value[$uid][21][0]=$value_1;
		$skill_value[$uid][21][1]=$value_2;
	}
	$a[ddef_dmg]=$skill_value[$uid][21][0];
	$a[at]=$a[at]*$skill_value[$uid][21][1];
}

function skill_22(&$a,$lv,$uid,$trun=0,$dmg)//仁義
{
	global $skill_value,$wogclass;
	if(empty($skill_value[$uid][22][0]))
	{
		$value_1=0;
		$value_2=0;
		switch($lv){
			case 1:
				$value_1=0.03;
				$value_2=0.02;
				break;
			case 2:
				$value_1=0.06;
				$value_2=0.02;
				break;
			case 3:
				$value_1=0.09;
				$value_2=0.02;
				break;
			case 4:
				$value_1=0.12;
				$value_2=0.05;
				break;
			case 5:
				$value_1=0.15;
				$value_2=0.05;
				break;
			case 6:
				$value_1=0.18;
				$value_2=0.05;
				break;
			case 7:
				$value_1=0.2;
				$value_2=0.05;
				break;
		} // switch
		$skill_value[$uid][22][0]=$value_1;
		$skill_value[$uid][22][1]=$value_2;
	}
	$hp=round($a[hpmax]*$skill_value[$uid][22][1]);
	if($a[hp]>$hp)
	{
		$sp=round($a[sp]*$skill_value[$uid][22][0]);
		$a[sp]+=$sp;
		if($a[sp] > $a[spmax]){$a[sp] = $a[spmax];}
		$a[hp]=$a[hp]-$hp;
		$wogclass->temp_fight_string.=",\"parent.fight_event2('$a[name]',-$hp,$sp)\"";
	}
}

function skill_23(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value;
	if(empty($skill_value[$uid][23][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=2;
				break;
			case 2:
				$value=3;
				break;
			case 3:
				$value=4;
				break;
			case 4:
				$value=5;
				break;
			case 5:
				$value=6;
				break;
			case 6:
				$value=7;
				break;
		} // switch
		$skill_value[$uid][23][0]=$value;
	}
	$a[mat_count]=$skill_value[$uid][23][0];
}

function skill_24(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$p,$m;
	if(empty($skill_value[$uid][24][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.02;
				break;
			case 2:
				$value=0.04;
				break;
			case 3:
				$value=0.06;
				break;
			case 4:
				$value=0.08;
				break;
			case 5:
				$value=0.1;
				break;
		} // switch
		$skill_value[$uid][24][0]=$value;
	}
	switch($trun)
	{
		case 0:
			$a[mat_count]=0;
			$a[at_count]=0;
			$a[sp]=$a[sp]+($a[spmax]*$skill_value[$uid][24][0]);
			if($a[sp] > $a[spmax]){$a[sp] = $a[spmax];}
			if(empty($a[p_id]))
			{
				$m[fight_break]=1;
			}
			else
			{
				$p[fight_break]=1;
			}
		break;
/*
		case 1:
			if(!empty($a[p_id]))
			{
				$p[fight_break]=1;
			}
			else
			{
				$m[fight_break]=1;
			}
		break;
*/
		case 2:
			$a[mat_count]=0;
			$a[at_count]=0;
			$a[sp]=$a[sp]+($a[spmax]*$skill_value[$uid][24][0]);
			if($a[sp] > $a[spmax]){$a[sp] = $a[spmax];}
		break;
	}
}

function skill_25(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value,$p,$m;
	if(empty($skill_value[$uid][25][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.98;
				break;
			case 2:
				$value=0.96;
				break;
			case 3:
				$value=0.94;
				break;
			case 4:
				$value=0.92;
				break;
			case 5:
				$value=0.9;
				break;
			case 6:
				$value=0.88;
				break;
			case 7:
				$value=0.86;
				break;
			case 8:
				$value=0.84;
				break;
		} // switch
		$skill_value[$uid][25][0]=$value;
		$skill_value[$uid][25][1]=$value;
	}
	
	switch($trun)
	{
		case 0:
			if(empty($a[p_id]))
			{
				$p[mat]=$p[mat]*$skill_value[$uid][25][0];
				$p[at]=$p[at]*$skill_value[$uid][25][1];
				skill_set_dot(25,$p[p_id],$lv,1);
			}
			else
			{
				$m[mat]=$m[mat]*$skill_value[$uid][25][0];
				$m[at]=$m[at]*$skill_value[$uid][25][1];
				skill_set_dot(25,$m[id],$lv,2);
			}
		break;
		case 1:
			if(empty($a[p_id]))
			{
				$m[mat]*=$skill_value[$uid][25][0];
				$m[at]*=$skill_value[$uid][25][1];
			}
			else
			{
				$p[mat]*=$skill_value[$uid][25][0];
				$p[at]*=$skill_value[$uid][25][1];
			}
		break;
	}
}

function skill_26(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][26][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.1;
				break;
			case 2:
				$value=0.2;
				break;
			case 3:
				$value=0.3;
				break;
			case 4:
				$value=0.4;
				break;
			case 5:
				$value=0.5;
				break;
			case 6:
				$value=0.6;
				break;
			case 7:
				$value=0.7;
				break;
			case 8:
				$value=0.8;
				break;
		} // switch
		$skill_value[$uid][26][0]=$value;
	}
	$skill_undo_value[$uid][26][0]=$a[mat];
	if($a[hp]<=($a[hpmax]*0.3))
	{
		$a[mat]+=$a[sp]*$skill_value[$uid][26][0];
		$a[sp]=0;
		return false;
	}
	else
	{
		return true;
	}
}

function skill_27(&$a,$lv,$uid,$trun=0,$dmg)//魔劍
{
	global $skill_value,$p,$m,$wogclass;
	skill_f_property($a,$lv,$uid,$trun=0,27);
}
function skill_58(&$a,$lv,$uid,$trun=0,$dmg)//魔劍
{
	global $skill_value,$p,$m,$wogclass;
	skill_f_property($a,$lv,$uid,$trun=0,58);
}
function skill_59(&$a,$lv,$uid,$trun=0,$dmg)//魔劍
{
	global $skill_value,$p,$m,$wogclass;
	skill_f_property($a,$lv,$uid,$trun=0,59);
}
function skill_60(&$a,$lv,$uid,$trun=0,$dmg)//魔劍
{
	global $skill_value,$p,$m,$wogclass;
	skill_f_property($a,$lv,$uid,$trun=0,60);
}
function skill_61(&$a,$lv,$uid,$trun=0,$dmg)//魔劍
{
	global $skill_value,$p,$m,$wogclass;
	skill_f_property($a,$lv,$uid,$trun=0,61);
}
function skill_62(&$a,$lv,$uid,$trun=0,$dmg)//魔劍
{
	global $skill_value,$p,$m,$wogclass;
	skill_f_property($a,$lv,$uid,$trun=0,62);
}

function skill_28(&$a,$lv,$uid,$trun=0,$dmg)//魔盾
{
	global $skill_value,$p,$m,$wogclass;
	skill_d_property($a,$lv,$uid,$trun=0,28);
}
function skill_63(&$a,$lv,$uid,$trun=0,$dmg)//魔盾
{
	global $skill_value,$p,$m,$wogclass;
	skill_d_property($a,$lv,$uid,$trun=0,63);
}
function skill_64(&$a,$lv,$uid,$trun=0,$dmg)//魔盾
{
	global $skill_value,$p,$m,$wogclass;
	skill_d_property($a,$lv,$uid,$trun=0,64);
}
function skill_65(&$a,$lv,$uid,$trun=0,$dmg)//魔盾
{
	global $skill_value,$p,$m,$wogclass;
	skill_d_property($a,$lv,$uid,$trun=0,65);
}
function skill_66(&$a,$lv,$uid,$trun=0,$dmg)//魔盾
{
	global $skill_value,$p,$m,$wogclass;
	skill_d_property($a,$lv,$uid,$trun=0,66);
}
function skill_67(&$a,$lv,$uid,$trun=0,$dmg)//魔盾
{
	global $skill_value,$p,$m,$wogclass;
	skill_d_property($a,$lv,$uid,$trun=0,67);
}

function skill_29(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][29][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1;
				break;
			case 2:
				$value=2;
				break;
			case 3:
				$value=3;
				break;
			case 4:
				$value=4;
				break;
			case 5:
				$value=5;
				break;
			case 6:
				$value=6;
				break;
		} // switch
		$skill_value[$uid][29][0]=$value;
	}
	$a[mct]+=$skill_value[$uid][29][0];
}

function skill_30(&$a,$lv,$uid,$trun=0,$dmg)//劍雨
{
	global $skill_value,$skill_undo_value,$m,$p;
	if(empty($skill_value[$uid][30][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.02;
				break;
			case 2:
				$value=0.04;
				break;
			case 3:
				$value=0.06;
				break;
			case 4:
				$value=0.08;
				break;
			case 5:
				$value=0.1;
				break;
			case 6:
				$value=0.12;
				break;
			case 7:
				$value=0.14;
				break;
		} // switch
		$skill_value[$uid][30][0]=$value;
	}
	if($trun==0)
	{
		$dmg=($a[str]*$skill_value[$uid][30][0])+($a[smart]*$skill_value[$uid][30][0]);
		if(empty($a[p_id]))
		{
			if($m[mat] > $p[mat])
			{
//				$skill_undo_value[$uid][30][0]=$a[m_dmg];
//				$skill_undo_value[$uid][30][1]=1;
				$a[m_dmg]+=$dmg;
			}
		}
		else
		{
			if($p[mat] > $m[mat])
			{
//				$skill_undo_value[$uid][30][0]=$a[m_dmg];
//				$skill_undo_value[$uid][30][1]=1;
				$a[m_dmg]+=$dmg;
			}
		}
	}
}

function skill_51(&$a,$lv,$uid,$trun=0,$dmg)//暴雪
{
	global $skill_value,$p,$m;
	if(empty($skill_value[$uid][51][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.01;
				break;
			case 2:
				$value=0.02;
				break;
			case 3:
				$value=0.03;
				break;
			case 4:
				$value=0.04;
				break;
			case 5:
				$value=0.05;
				break;
			case 6:
				$value=0.06;
				break;
		} // switch
		$skill_value[$uid][51][0]=$value;
	}
	switch($trun)
	{
		case 2:
			$a[m_dmg]+=($a[smart]*$skill_value[$uid][51][0]);
		break;
		case 0:
			$a[m_dmg]+=($a[smart]*$skill_value[$uid][51][0]);
			if(empty($a[p_id]))
			{
				$p[agi]=$p[agi]*(1-$skill_value[$uid][51][0]);
				skill_set_dot(51,$p[p_id],$lv,1);
			}
			else
			{
				$m[agi]=$m[agi]*(1-$skill_value[$uid][51][0]);
				skill_set_dot(51,$m[id],$lv,2);
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[agi]=$p[agi]*(1-$skill_value[$uid][51][0]);
			}
			else
			{
				$m[agi]=$m[agi]*(1-$skill_value[$uid][51][0]);
			}
		break;
	}
}

function skill_50(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value,$m,$p,$wogclass,$temp_pd;
	if(empty($skill_value[$uid][50][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.9;
				break;
			case 2:
				$value=0.89;
				break;
			case 3:
				$value=0.88;
				break;
			case 4:
				$value=0.87;
				break;
			case 5:
				$value=0.86;
				break;
			case 6:
				$value=0.85;
				break;
			case 7:
				$value=0.84;
				break;
		} // switch
		$skill_value[$uid][50][0]=$value;
	}
	if(empty($a[p_id]))
	{
		$temp_hp2=round($p[hp]*$skill_value[$uid][50][0]);
		$temp_hp=$p[hp]-$temp_hp2;
		$p[hp]=$temp_hp2;
		$wogclass->temp_fight_string.=",\"parent.fight_event2('$p[name]',-$temp_hp)\"";
	}
	else
	{
		$temp_hp2=round($m[hp]*$skill_value[$uid][50][0]);
		$temp_hp=$m[hp]-$temp_hp2;
		$m[hp]=$temp_hp2;
		$temp_pd+=$temp_hp;
		$wogclass->temp_fight_string.=",\"parent.fight_event2('$m[name]',-$temp_hp)\"";
	}
	$a[df]=$a[df]*0.98;
	$a[mdf]=$a[mdf]*0.98;
}

function skill_31(&$a,$lv,$uid,$trun=0,$dmg)//心眼
{
	global $skill_value,$skill_undo_value,$p,$m;
	if(empty($skill_value[$uid][31][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=-1;
				break;
			case 2:
				$value=-2;
				break;
			case 3:
				$value=-3;
				break;
			case 4:
				$value=-4;
				break;
			case 5:
				$value=-5;
				break;
			case 6:
				$value=-6;
				break;
			case 7:
				$value=-7;
				break;
			case 8:
				$value=-8;
				break;
		} // switch
		$skill_value[$uid][31][0]=$value;
	}
	switch($trun)
	{
		case 0:
			if(empty($a[p_id]))
			{
				$p[rag]+=$skill_value[$uid][31][0];
				skill_set_dot(31,$p[p_id],$lv,1);
			}
			else
			{
				$m[rag]+=$skill_value[$uid][31][0];
				skill_set_dot(31,$m[id],$lv,2);
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[rag]+=$skill_value[$uid][31][0];
			}
			else
			{
				$m[rag]+=$skill_value[$uid][31][0];
			}
		break;
	}
}

function skill_32(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value,$p,$m,$wogclass;
	if(empty($skill_value[$uid][32][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=2;
				break;
			case 2:
				$value=4;
				break;
			case 3:
				$value=6;
				break;
			case 4:
				$value=8;
				break;
			case 5:
				$value=10;
				break;
			case 6:
				$value=12;
				break;
			case 7:
				$value=14;
				break;
		} // switch
		$skill_value[$uid][32][0]=$value;
	}
	switch($trun)
	{
		case 0:
			if(empty($a[p_id]))
			{
				$temp_val = 0;
				$temp_val=($p[smart] < $m[smart])?5:-5;
				if(rand(1,100) <= $skill_value[$uid][32][0]+$temp_val )
				{
					$p[at_count]=0;
					$p[mat_count]=0;
					$p[fight_break]=1;
					skill_set_dot(32,$p[p_id],$lv,1);
				}
			}
			else
			{
				$temp_val = 0;
				$temp_val=($p[smart] > $m[smart])?5:-5;
				if(rand(1,100) <= $skill_value[$uid][32][0]+$temp_val )
				{
					$m[at_count]=0;
					$m[mat_count]=0;
					$m[fight_break]=1;
					skill_set_dot(32,$m[id],$lv,2);
				}
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[at_count]=0;
				$p[mat_count]=0;
				$p[fight_break]=1;
			}
			else
			{
				$m[at_count]=0;
				$m[mat_count]=0;
				$m[fight_break]=1;
			}
		break;
	}

}

function skill_33(&$a,$lv,$uid,$trun=0,$dmg)//背襲
{
	global $skill_value,$skill_undo_value,$p,$m;
	if(empty($skill_value[$uid][33][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=2;
				break;
			case 2:
				$value=4;
				break;
			case 3:
				$value=6;
				break;
			case 4:
				$value=8;
				break;
			case 5:
				$value=10;
				break;
			case 6:
				$value=12;
				break;
			case 7:
				$value=14;
				break;
		} // switch
		$skill_value[$uid][33][0]=$value;
	}
	switch($trun)
	{
		case 0:
			if(empty($a[p_id]))
			{
				$m[rag]+=$skill_value[$uid][33][0];
				skill_set_dot(33,$p[p_id],$lv,1);
			}
			else
			{
				$m[rag]+=$skill_value[$uid][33][0];
				skill_set_dot(33,$m[id],$lv,2);
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$m[rag]+=$skill_value[$uid][33][0];
			}
			else
			{
				$m[rag]+=$skill_value[$uid][33][0];
			}
		break;
	}
}

function skill_34(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value,$p,$m;
	if(empty($skill_value[$uid][34][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=80;
				break;
			case 2:
				$value=72;
				break;
			case 3:
				$value=64;
				break;
			case 4:
				$value=56;
				break;
			case 5:
				$value=48;
				break;
			case 6:
				$value=40;
				break;
			case 7:
				$value=32;
				break;
		} // switch
		$skill_value[$uid][34][0]=$value;
	}
	switch($trun)
	{
		case 2:
			if(rand(1,100)<=20){$a[d_dmg]+=$a[agi]*0.15;}
			$a[act]+=40;
		break;
		case 0:
			if(rand(1,100)<=20){$a[d_dmg]+=$a[agi]*0.15;}
			$a[act]+=40;
			if(empty($a[p_id]))
			{
				$p[rag]+=$skill_value[$uid][34][0];
				skill_set_dot(34,$p[p_id],$lv,1);
			}
			else
			{
				$m[rag]+=$skill_value[$uid][34][0];
				skill_set_dot(34,$m[id],$lv,2);
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[rag]+=$skill_value[$uid][34][0];
			}
			else
			{
				$m[rag]+=$skill_value[$uid][34][0];
			}
		break;
	}
}

function skill_35(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][35][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.02;
				break;
			case 2:
				$value=0.04;
				break;
			case 3:
				$value=0.06;
				break;
			case 4:
				$value=0.08;
				break;
			case 5:
				$value=0.1;
				break;
			case 6:
				$value=0.12;
				break;
			case 7:
				$value=0.14;
				break;
		} // switch
		$skill_value[$uid][35][0]=$value;
	}
	$a[d_dmg]+=$a[at]*$skill_value[$uid][35][0];
}

function skill_36(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][36][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=-70;
				break;
			case 2:
				$value=-60;
				break;
			case 3:
				$value=-50;
				break;
			case 4:
				$value=-40;
				break;
			case 5:
				$value=-30;
				break;
			case 6:
				$value=-20;
				break;
		} // switch
		$skill_value[$uid][36][0]=$value;
	}
	$a[at_count]=5;
	$a[rag]+=$skill_value[$uid][36][0];
}

function skill_37(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][37][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.03;
				break;
			case 2:
				$value=0.06;
				break;
			case 3:
				$value=0.09;
				break;
			case 4:
				$value=0.12;
				break;
			case 5:
				$value=0.15;
				break;
			case 6:
				$value=0.18;
				break;
			case 7:
				$value=0.21;
				break;
		} // switch
		$skill_value[$uid][37][0]=$value;
	}
	$a[at]+=$a[vit]*$skill_value[$uid][37][0];
}

function skill_38(&$a,$lv,$uid,$trun=0,$dmg)//犧牲
{
	global $skill_value,$skill_undo_value,$wogclass;
	if(empty($skill_value[$uid][38][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=rand(15,25)/100;
				break;
			case 2:
				$value=rand(25,35)/100;
				break;
			case 3:
				$value=rand(35,45)/100;
				break;
			case 4:
				$value=rand(45,55)/100;
				break;
			case 5:
				$value=rand(55,65)/100;
				break;
			case 6:
				$value=rand(65,75)/100;
				break;
		} // switch
		$skill_value[$uid][38][0]=$value;
	}
	switch($trun)
	{
		case 0:
			$hp=round($a[hpmax]*0.1);
			if($a[hp]>$hp)
			{
				$a[hp]-=$hp;
				$a[d_dmg]+=$a[at]*$skill_value[$uid][38][0];
				$a[rag]+=60;
				$a[act]+=10;
				$wogclass->temp_fight_string.=",\"parent.fight_event2('$a[name]',-$hp)\"";
			}
		break;
		case 2:
			$a[d_dmg]+=$a[at]*$skill_value[$uid][38][0];
			$a[rag]+=60;
			$a[act]+=10;
		break;
	}
}

function skill_39(&$a,$lv,$uid,$trun=0,$dmg)//力量之舞
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][39][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.05;
				break;
			case 2:
				$value=0.1;
				break;
			case 3:
				$value=0.15;
				break;
			case 4:
				$value=0.2;
				break;
			case 5:
				$value=0.25;
				break;
			case 6:
				$value=0.3;
				break;
			case 7:
				$value=0.35;
				break;
			case 8:
				$value=0.4;
				break;
		} // switch
		$skill_value[$uid][39][0]=$value;
	}
	$a[at]+=$a[au]*$skill_value[$uid][39][0];
}

function skill_40(&$a,$lv,$uid,$trun=0,$dmg)//魔力之舞
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][40][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.05;
				break;
			case 2:
				$value=0.1;
				break;
			case 3:
				$value=0.15;
				break;
			case 4:
				$value=0.2;
				break;
			case 5:
				$value=0.25;
				break;
			case 6:
				$value=0.3;
				break;
			case 7:
				$value=0.35;
				break;
			case 8:
				$value=0.4;
				break;
		} // switch
		$skill_value[$uid][40][0]=$value;
	}
	$a[mat]+=$a[au]*$skill_value[$uid][40][0];
}

function skill_41(&$a,$lv,$uid,$trun=0,$dmg)//敏捷之舞
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][41][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.05;
				break;
			case 2:
				$value=0.1;
				break;
			case 3:
				$value=0.15;
				break;
			case 4:
				$value=0.2;
				break;
			case 5:
				$value=0.25;
				break;
			case 6:
				$value=0.3;
				break;
			case 7:
				$value=0.35;
				break;
			case 8:
				$value=0.4;
				break;
		} // switch
		$skill_value[$uid][41][0]=$value;
	}
	$a[agi]+=$a[au]*$skill_value[$uid][41][0];
}

function skill_42(&$a,$lv,$uid,$trun=0,$dmg)//毀滅之舞
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][42][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1;
				break;
			case 2:
				$value=2;
				break;
			case 3:
				$value=3;
				break;
			case 4:
				$value=4;
				break;
			case 5:
				$value=5;
				break;
			case 6:
				$value=6;
				break;
			case 7:
				$value=7;
				break;
		} // switch
		$skill_value[$uid][42][0]=$value;
	}
	$a[act]+=$skill_value[$uid][42][0];
	$a[mct]+=$skill_value[$uid][42][0];
}

function skill_43(&$a,$lv,$uid,$trun=0,$dmg)//友情之舞
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][43][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=2;
				break;
			case 2:
				$value=4;
				break;
			case 3:
				$value=6;
				break;
			case 4:
				$value=8;
				break;
			case 5:
				$value=10;
				break;
			case 6:
				$value=12;
				break;
			case 7:
				$value=14;
				break;
		} // switch
		$skill_value[$uid][43][0]=$value;
	}
	$a[p_support_m]+=$skill_value[$uid][43][0];
}

function skill_44(&$a,$lv,$uid,$trun=0,$dmg)//鋒針陷阱
{
	global $skill_value,$skill_undo_value,$p,$m,$wogclass;
	if(empty($skill_value[$uid][44][0]))
	{
		$value_1=0;
		$value_2=0;
		switch($lv){
			case 1:
				$value_1=15;
				$value_2=0.03;
				break;
			case 2:
				$value_1=18;
				$value_2=0.06;
				break;
			case 3:
				$value_1=21;
				$value_2=0.09;
				break;
			case 4:
				$value_1=24;
				$value_2=0.12;
				break;
			case 5:
				$value_1=27;
				$value_2=0.15;
				break;
			case 6:
				$value_1=30;
				$value_2=0.18;
				break;
		} // switch
		$skill_value[$uid][44][0]=$value_1;
		$skill_value[$uid][44][1]=$value_2;
	}
	switch($trun)
	{
		case 2:
			$a[d_dmg]+=$a[au]*$skill_value[$uid][44][1];
			$a[m_dmg]+=$a[au]*$skill_value[$uid][44][1];
		break;
		case 0:
			if(rand(1,100)<=$skill_value[$uid][44][0])
			{
				$a[d_dmg]+=$a[au]*$skill_value[$uid][44][1];
				$a[m_dmg]+=$a[au]*$skill_value[$uid][44][1];
				if(empty($a[p_id]))
				{
					$p[mat_count]=0;
					$p[at_count]=0;
					$p[fight_break]=1;
					skill_set_dot(44,$p[p_id],$lv,1);
				}
				else
				{
					$m[mat_count]=0;
					$m[at_count]=0;
					$m[fight_break]=1;
					skill_set_dot(44,$m[id],$lv,2);
				}
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[mat_count]=0;
				$p[at_count]=0;
				$p[fight_break]=1;
			}
			else
			{
				$m[mat_count]=0;
				$m[at_count]=0;
				$m[fight_break]=1;
			}
		break;
	}
}

function skill_45(&$a,$lv,$uid,$trun=0,$dmg)//物攻命令/魔攻命令
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][45][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1;
				break;
			case 2:
				$value=2;
				break;
		} // switch
		$skill_value[$uid][45][0]=$value;
	}
	$a[pet_act_type]=$skill_value[$uid][45][0];
}

function skill_46(&$a,$lv,$uid,$trun=0,$dmg)//戰意驅使
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][46][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=2;
				break;
			case 2:
				$value=4;
				break;
			case 3:
				$value=6;
				break;
			case 4:
				$value=8;
				break;
			case 5:
				$value=10;
				break;
			case 6:
				$value=12;
				break;
			case 7:
				$value=14;
				break;
		} // switch
		$skill_value[$uid][46][0]=$value;
	}
	$a[petact_m]=$skill_value[$uid][46][0];
}

function skill_47(&$a,$lv,$uid,$trun=0,$dmg)//狂性連結
{
	global $skill_value,$skill_undo_value,$pet;
	if(empty($skill_value[$uid][47][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1.03;
				break;
			case 2:
				$value=1.06;
				break;
			case 3:
				$value=1.09;
				break;
			case 4:
				$value=1.12;
				break;
			case 5:
				$value=1.15;
				break;
			case 6:
				$value=1.18;
				break;
			case 7:
				$value=1.21;
				break;
		} // switch
		$skill_value[$uid][47][0]=$value;
	}
	$pet[pe_at]=$pet[pe_at]*$skill_value[$uid][47][0];
}

function skill_48(&$a,$lv,$uid,$trun=0,$dmg)//魔性連結
{
	global $skill_value,$skill_undo_value,$pet;
	if(empty($skill_value[$uid][48][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1.03;
				break;
			case 2:
				$value=1.06;
				break;
			case 3:
				$value=1.09;
				break;
			case 4:
				$value=1.12;
				break;
			case 5:
				$value=1.15;
				break;
			case 6:
				$value=1.18;
				break;
			case 7:
				$value=1.21;
				break;
		} // switch
		$skill_value[$uid][48][0]=$value;
	}
	$pet[pe_mt]=$pet[pe_mt]*$skill_value[$uid][48][0];
}

function skill_52(&$a,$lv,$uid,$trun=0,$dmg)//精靈之力
{
	global $skill_value,$skill_undo_value;
	if(empty($skill_value[$uid][52][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1.03;
				break;
			case 2:
				$value=1.06;
				break;
			case 3:
				$value=1.09;
				break;
			case 4:
				$value=1.12;
				break;
			case 5:
				$value=1.15;
				break;
			case 6:
				$value=1.18;
				break;
			case 7:
				$value=1.21;
				break;
			case 8:
				$value=1.24;
				break;
			case 9:
				$value=1.26;
				break;
		} // switch
		$skill_value[$uid][52][0]=$value;
	}
	$a[mat]=$a[mat]*$skill_value[$uid][52][0];
}
function skill_53(&$a,$lv,$uid,$trun=0,$dmg)//屬性迫害
{
	global $skill_value,$skill_undo_value,$p,$m;
	if(empty($skill_value[$uid][53][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1.05;
				break;
			case 2:
				$value=1.1;
				break;
			case 3:
				$value=1.15;
				break;
			case 4:
				$value=1.2;
				break;
			case 5:
				$value=1.25;
				break;
			case 6:
				$value=1.3;
				break;
			case 7:
				$value=1.35;
				break;
			case 8:
				$value=1.4;
				break;
			case 9:
				$value=1.45;
				break;
		} // switch
		$skill_value[$uid][53][0]=$value;
	}
	if(empty($a[p_id]))
	{
		$temp=$m[s_property]-$p[s_property];
		if($temp==-1 || $temp==5)
		$m[temp_s]=1.2*$skill_value[$uid][53][0];
	}
	else
	{
		$temp=$p[s_property]-$m[s_property];
		if($temp==-1 || $temp==5)
		$p[temp_s]=1.2*$skill_value[$uid][53][0];
	}
}

function skill_54(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$skill_undo_value,$p,$m;
	if(empty($skill_value[$uid][54][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=20;
				break;
			case 2:
				$value=80;
				break;
			case 3:
				$value=180;
				break;
			case 4:
				$value=320;
				break;
			case 5:
				$value=500;
				break;
			case 6:
				$value=720;
				break;
			case 7:
				$value=980;
				break;
			case 8:
				$value=1280;
				break;
			case 9:
				$value=1620;
				break;
			case 9:
				$value=2000;
				break;
		} // switch
		$skill_value[$uid][54][0]=$value;
	}
	if(empty($a[p_id]))
	{
		$m[sp]-=$skill_value[$uid][54][0];
		if($m[sp]<0){$m[sp]=0;}
	}
	else
	{
		$p[sp]-=$skill_value[$uid][54][0];
		if($p[sp]<0){$p[sp]=0;}
	}
}

function skill_55(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$p,$m;
	if(empty($skill_value[$uid][55][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=5;
				break;
			case 2:
				$value=10;
				break;
			case 3:
				$value=15;
				break;
			case 4:
				$value=20;
				break;
			case 5:
				$value=25;
				break;
			case 6:
				$value=30;
				break;
			case 7:
				$value=35;
				break;
			case 8:
				$value=40;
				break;
			case 9:
				$value=45;
				break;
			case 10:
				$value=50;
				break;
		} // switch
		$skill_value[$uid][55][0]=$value;
	}
	switch($trun)
	{
		case 0:
			if(rand(1,100)<$skill_value[$uid][55][0])
			{
				if(!empty($a[p_id]))
				{
					$m[skill_break]=1;
					skill_set_dot(55,$m[id],$lv,2);
				}
				else
				{
					$p[skill_break]=1;
					skill_set_dot(55,$p[p_id],$lv,1);
				}
			}
		break;
		case 1:
			if(empty($a[p_id]))
			{
				$m[skill_break]=1;
			}
			else
			{
				$p[skill_break]=1;
			}
		break;
	}
}

function skill_56(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][56][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=0.04;
				break;
			case 2:
				$value=0.08;
				break;
			case 3:
				$value=0.12;
				break;
			case 4:
				$value=0.16;
				break;
			case 5:
				$value=0.2;
				break;
			case 6:
				$value=0.24;
				break;
			case 7:
				$value=0.28;
				break;
			case 8:
				$value=0.32;
				break;
			case 9:
				$value=0.36;
				break;
			case 10:
				$value=0.4;
				break;
		} // switch
		$skill_value[$uid][56][0]=$value;
	}
	if(!empty($a[p_id]))
	{
		if($pet[pe_def]>0)
		{
			$temp=$pet[pe_def]*$skill_value[$uid][56][0];
			$pet[pe_def]-=$temp;
			$p[hp]+=$temp;
			if($p[hp]>$p[hpmax]){$p[hp]=$p[hpmax];}
			$wogclass->temp_fight_string.=",\"parent.fight_event2('$p[name]',$temp)\"";
		}
	}
	else
	{
	}
}

function skill_57(&$a,$lv,$uid,$trun=0,$dmg)
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][57][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=10;
				break;
			case 2:
				$value=15;
				break;
			case 3:
				$value=20;
				break;
			case 4:
				$value=25;
				break;
			case 5:
				$value=30;
				break;
			case 6:
				$value=35;
				break;
			case 7:
				$value=40;
				break;
			case 8:
				$value=45;
				break;
			case 9:
				$value=50;
				break;
			case 10:
				$value=55;
				break;
		} // switch
		$skill_value[$uid][57][0]=$value;
	}
	switch($trun)
	{
		case 0:
			$dmg=$a[smart]*0.05;
			$a[m_dmg]+=$dmg;
			if(empty($a[p_id]))
			{
				if($p[smart] < $m[smart])
				{
					if(rand(1,100) <= $skill_value[$uid][57][0])
					{
						$p[at_count]=0;
						$p[mat_count]=0;
						$p[fight_break]=1;
						skill_set_dot(57,$p[p_id],$lv,1);
					}	
				}
			}
			else
			{
				if($m[smart] < $p[smart])
				{
					if(rand(1,100) <= $skill_value[$uid][57][0])
					{
						$m[at_count]=0;
						$m[mat_count]=0;
						$m[fight_break]=1;
						skill_set_dot(57,$m[id],$lv,2);
					}
				}
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[at_count]=0;
				$p[mat_count]=0;
				$p[fight_break]=1;
			}
			else
			{
				$m[at_count]=0;
				$m[mat_count]=0;
				$m[fight_break]=1;
			}
		break;
	}
}
function skill_68(&$a,$lv,$uid,$trun=0,$dmg)//斯巴達
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][68][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=12;
				break;
			case 2:
				$value=15;
				break;
			case 3:
				$value=18;
				break;
			case 4:
				$value=21;
				break;
			case 5:
				$value=24;
				break;
			case 6:
				$value=27;
				break;
			case 7:
				$value=30;
				break;
			case 8:
				$value=33;
				break;
			case 9:
				$value=36;
				break;
			case 10:
				$value=39;
				break;
		} // switch
		$skill_value[$uid][68][0]=$value;
	}
	switch($trun)
	{
		case 0:
			if(empty($a[p_id]))
			{
				if($p[str] < $m[str])
				{
					$a[act]+=20;
					if(rand(1,100) <= $skill_value[$uid][68][0])
					{
						$p[at_count]=0;
						$p[mat_count]=0;
						$p[fight_break]=1;
						skill_set_dot(68,$p[p_id],$lv,1);
					}	
				}
			}
			else
			{
				if($m[str] < $p[str])
				{
					$a[act]+=20;
					if(rand(1,100) <= $skill_value[$uid][68][0])
					{
						$m[at_count]=0;
						$m[mat_count]=0;
						$m[fight_break]=1;
						skill_set_dot(68,$m[id],$lv,2);
					}
				}
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[at_count]=0;
				$p[mat_count]=0;
				$p[fight_break]=1;
			}
			else
			{
				$m[at_count]=0;
				$m[mat_count]=0;
				$m[fight_break]=1;
			}
		break;
		case 2:
			if(empty($a[p_id]))
			{
				if($p[str] < $m[str])
				{
					$a[act]+=20;
				}
			}
			else
			{
				if($m[str] < $p[str])
				{
					$a[act]+=20;
				}
			}
		break;
	}
}
function skill_69(&$a,$lv,$uid,$trun=0,$dmg)//壓制
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][69][0]))
	{
		$value1=0;
		$value2=0;
		switch($lv){
			case 1:
				$value1=0.9;
				$value2=0.9;
				break;
			case 2:
				$value1=0.88;
				$value2=0.885;
				break;
			case 3:
				$value1=0.86;
				$value2=0.87;
				break;
			case 4:
				$value1=0.84;
				$value2=0.855;
				break;
			case 5:
				$value1=0.82;
				$value2=0.84;
				break;
			case 6:
				$value1=0.8;
				$value2=0.825;
				break;
			case 7:
				$value1=0.78;
				$value2=0.81;
				break;
			case 8:
				$value1=0.76;
				$value2=0.795;
				break;
			case 9:
				$value1=0.74;
				$value2=0.78;
				break;
			case 10:
				$value1=0.72;
				$value2=0.765;
				break;
		} // switch
		$skill_value[$uid][69][0]=$value1;
		$skill_value[$uid][69][1]=$value2;
	}
	switch($trun)
	{
		case 0:
			if(empty($a[p_id]))
			{
				if($p[vit] < $m[vit])
				{
					$p[agi]*=$skill_value[$uid][69][0];
					$p[df]*=$skill_value[$uid][69][1];
					if($p[agi]<1){$p[agi]=1;}
					if($p[df]<1){$p[df]=1;}
					skill_set_dot(69,$p[p_id],$lv,1);
				}
			}
			else
			{
				if($m[vit] < $p[vit])
				{
					$m[agi]*=$skill_value[$uid][69][0];
					$m[df]*=$skill_value[$uid][69][1];
					if($m[agi]<1){$m[agi]=1;}
					if($m[df]<1){$m[df]=1;}
					skill_set_dot(69,$m[id],$lv,2);
				}
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[agi]*=$skill_value[$uid][69][0];
				$p[df]*=$skill_value[$uid][69][1];
				if($p[agi]<1){$p[agi]=1;}
				if($p[df]<1){$p[df]=1;}
			}
			else
			{
				$m[agi]*=$skill_value[$uid][69][0];
				$m[df]*=$skill_value[$uid][69][1];
				if($m[agi]<1){$m[agi]=1;}
				if($m[df]<1){$m[df]=1;}
			}
		break;
	}
}
function skill_70(&$a,$lv,$uid,$trun=0,$dmg)//施毒
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][70][0]))
	{
		$value1=0;
		$value2=0;
		switch($lv){
			case 1:
				$value1=25;
				$value2=0.92;
				break;
			case 2:
				$value1=29;
				$value2=0.905;
				break;
			case 3:
				$value1=33;
				$value2=0.89;
				break;
			case 4:
				$value1=37;
				$value2=0.875;
				break;
			case 5:
				$value1=41;
				$value2=0.86;
				break;
			case 6:
				$value1=45;
				$value2=0.845;
				break;
			case 7:
				$value1=49;
				$value2=0.83;
				break;
			case 8:
				$value1=53;
				$value2=0.815;
				break;
			case 9:
				$value1=57;
				$value2=0.8;
				break;
			case 10:
				$value1=61;
				$value2=0.785;
				break;
		} // switch
		$skill_value[$uid][70][0]=$value1;
		$skill_value[$uid][70][1]=$value2;
	}
	switch($trun)
	{
		case 0:
			$a[d_dmg]+=$a[smart]*0.1;
			if(empty($a[p_id]))
			{
				if(rand(1,100) <= $skill_value[$uid][70][0])
				{
					$p[at]*=$skill_value[$uid][70][1];
					skill_set_dot(70,$p[p_id],$lv,1);
				}
			}
			else
			{
				if(rand(1,100) <= $skill_value[$uid][70][0])
				{
					$m[at]*=$skill_value[$uid][70][1];
					skill_set_dot(70,$m[id],$lv,2);
				}
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[at]*=$skill_value[$uid][70][1];
			}
			else
			{
				$m[at]*=$skill_value[$uid][70][1];
			}
		break;
		case 2:
			$a[d_dmg]+=$a[smart]*0.1;
		break;
	}
}
function skill_71(&$a,$lv,$uid,$trun=0,$dmg)//明王
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][71][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=10;
				$value2=0.94;
				break;
			case 2:
				$value1=14;
				$value2=0.92;
				break;
			case 3:
				$value1=18;
				$value2=0.9;
				break;
			case 4:
				$value1=22;
				$value2=0.88;
				break;
			case 5:
				$value1=26;
				$value2=0.86;
				break;
			case 6:
				$value1=30;
				$value2=0.84;
				break;
			case 7:
				$value1=34;
				$value2=0.82;
				break;
			case 8:
				$value1=38;
				$value2=0.8;
				break;
			case 9:
				$value1=42;
				$value2=0.78;
				break;
			case 10:
				$value1=46;
				$value2=0.76;
				break;
		} // switch
		$skill_value[$uid][71][0]=$value1;
		$skill_value[$uid][71][1]=$value2;
	}
	switch($trun)
	{
		case 0:			
			$a[act]+=17;
			if(empty($a[p_id]))
			{
				if(rand(1,100) <= $skill_value[$uid][71][0])
				{
					$p[df]*=$skill_value[$uid][71][1];
					skill_set_dot(71,$p[p_id],$lv,1);
				}
			}
			else
			{
				if(rand(1,100) <= $skill_value[$uid][71][0])
				{
					$m[df]*=$skill_value[$uid][71][1];
					skill_set_dot(71,$m[id],$lv,2);
				}
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[df]*=$skill_value[$uid][71][1];
			}
			else
			{
				$m[df]*=$skill_value[$uid][71][1];
			}
		break;
		case 2:
			$a[act]+=17;
		break;
	}
}
function skill_72(&$a,$lv,$uid,$trun=0,$dmg)//復仇
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][72][0]))
	{
		$value1=0;
		$value2=0;
		$value3=0;
		switch($lv){
			case 1:
				$value1=0.03;
				$value2=1.05;
				$value3=34;
				break;
			case 2:
				$value1=0.06;
				$value2=1.05;
				$value3=38;
				break;
			case 3:
				$value1=0.09;
				$value2=1.05;
				$value3=42;
				break;
			case 4:
				$value1=0.12;
				$value2=1.05;
				$value3=46;
				break;
			case 5:
				$value1=0.15;
				$value2=1.05;
				$value3=50;
				break;
			case 6:
				$value1=0.18;
				$value2=1.08;
				$value3=54;
				break;
			case 7:
				$value1=0.21;
				$value2=1.08;
				$value3=58;
				break;
			case 8:
				$value1=0.24;
				$value2=1.08;
				$value3=62;
				break;
			case 9:
				$value1=0.27;
				$value2=1.08;
				$value3=66;
				break;
			case 10:
				$value1=0.3;
				$value2=1.08;
				$value3=70;
				break;
		} // switch
		$skill_value[$uid][72][0]=$value1;
		$skill_value[$uid][72][1]=$value2;
		$skill_value[$uid][72][2]=$value3;
	}
	if(empty($a[p_id]))
	{
		if($m[hp] < $p[hp])
		{
			$temp_hp=round($m[vit]*$skill_value[$uid][72][0]);
			$a[hp]+=$temp_hp;
			$a[rag]+=$skill_value[$uid][72][2];
			$a[at]*=$skill_value[$uid][72][1];
			$wogclass->temp_fight_string.=",\"parent.fight_event2('$a[name]',$temp_hp)\"";
		}
	}
	else
	{
		if($p[hp] < $m[hp])
		{
			$temp_hp=round($p[vit]*$skill_value[$uid][72][0]);
			$a[hp]+=$temp_hp;
			$wogclass->temp_fight_string.=",\"parent.fight_event2('$a[name]',$temp_hp)\"";
			$a[rag]+=$skill_value[$uid][72][2];
			$a[at]*=$skill_value[$uid][72][1];
		}
	}
	if($a[hp]>$a[hpmax]){$a[hp]=$a[hpmax];}
}
function skill_73(&$a,$lv,$uid,$trun=0,$dmg)//豪鬼
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][73][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=0.02;
				break;
			case 2:
				$value1=0.04;
				break;
			case 3:
				$value1=0.06;
				break;
			case 4:
				$value1=0.08;
				break;
			case 5:
				$value1=0.1;
				break;
			case 6:
				$value1=0.12;
				break;
			case 7:
				$value1=0.14;
				break;
			case 8:
				$value1=0.16;
				break;
			case 9:
				$value1=0.18;
				break;
			case 10:
				$value1=0.2;
				break;
		} // switch
		$skill_value[$uid][73][0]=$value1;
	}
	if(rand(1,100)<=10)
	{
		if(empty($a[p_id]))
		{
			$p[skill_break]=1;
		}
		else
		{
			$m[skill_break]=1;
		}		
	}
	$a[acrtdmg]+=$skill_value[$uid][73][0];
}
function skill_74(&$a,$lv,$uid,$trun=0,$dmg)//煉獄
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][74][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=2;
				break;
			case 2:
				$value1=4;
				break;
			case 3:
				$value1=6;
				break;
			case 4:
				$value1=8;
				break;
			case 5:
				$value1=10;
				break;
			case 6:
				$value1=12;
				break;
			case 7:
				$value1=14;
				break;
			case 8:
				$value1=16;
				break;
		} // switch
		$skill_value[$uid][74][0]=$value1;
	}
	if(empty($a[p_id]))
	{
		if($m[smart] < $p[smart])
		{
			$a[mct]+=$skill_value[$uid][74][0];
		}
	}
	else
	{
		if($p[smart] < $m[smart])
		{
			$a[mct]+=$skill_value[$uid][74][0];
		}
	}
}
function skill_75(&$a,$lv,$uid,$trun=0,$dmg)//熱情律動
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][75][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=0.025;
				break;
			case 2:
				$value1=0.05;
				break;
			case 3:
				$value1=0.075;
				break;
			case 4:
				$value1=0.1;
				break;
			case 5:
				$value1=0.125;
				break;
			case 6:
				$value1=0.15;
				break;
			case 7:
				$value1=0.175;
				break;
			case 8:
				$value1=0.2;
				break;
			case 9:
				$value1=0.225;
				break;
			case 10:
				$value1=0.25;
				break;
		} // switch
		$skill_value[$uid][75][0]=$value1;
	}
	if(empty($a[p_id]))
	{
		if($m[au] > $p[au])
		{
			$a[mcrtdmg]+=$skill_value[$uid][75][0];
		}
	}
	else
	{
		if($p[au] > $m[au])
		{
			$a[mcrtdmg]+=$skill_value[$uid][75][0];
		}
	}
}
function skill_76(&$a,$lv,$uid,$trun=0,$dmg)//華爾滋
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][76][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=8;
				break;
			case 2:
				$value1=10;
				break;
			case 3:
				$value1=12;
				break;
			case 4:
				$value1=14;
				break;
			case 5:
				$value1=16;
				break;
			case 6:
				$value1=18;
				break;
			case 7:
				$value1=20;
				break;
			case 8:
				$value1=22;
				break;
		} // switch
		$skill_value[$uid][76][0]=$value1;
	}
	$a[mct]+=$skill_value[$uid][76][0];
}
function skill_77(&$a,$lv,$uid,$trun=0,$dmg)//毒傷陷阱
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][77][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=0.02;
				break;
			case 2:
				$value1=0.03;
				break;
			case 3:
				$value1=0.04;
				break;
			case 4:
				$value1=0.05;
				break;
			case 5:
				$value1=0.06;
				break;
			case 6:
				$value1=0.07;
				break;
			case 7:
				$value1=0.08;
				break;
			case 8:
				$value1=0.09;
				break;
			case 9:
				$value1=0.1;
				break;
			case 10:
				$value1=0.12;
				break;
		} // switch
		$skill_value[$uid][77][0]=$value1;
	}
	$a[d_dmg]+=($a[smart]*$skill_value[$uid][77][0])+($a[str]*$skill_value[$uid][77][0]);
	$a[m_dmg]+=($a[smart]*$skill_value[$uid][77][0])+($a[str]*$skill_value[$uid][77][0]);
}
function skill_78(&$a,$lv,$uid,$trun=0,$dmg)//正義火雷
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][78][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=200;
				break;
			case 2:
				$value1=350;
				break;
			case 3:
				$value1=550;
				break;
			case 4:
				$value1=750;
				break;
			case 5:
				$value1=980;
				break;
			case 6:
				$value1=1300;
				break;
			case 7:
				$value1=1800;
				break;
			case 8:
				$value1=2600;
				break;
			case 9:
				$value1=3600;
				break;
			case 10:
				$value1=4900;
				break;
		} // switch
		$skill_value[$uid][78][0]=$value1;
	}
	switch($trun)
	{
		case 0:
			if(empty($a[p_id]))
			{
				$p[agi]-=$skill_value[$uid][78][0];
				if($p[agi]<1){$p[agi]=1;}
				skill_set_dot(78,$p[p_id],$lv,1);
			}
			else
			{
				$m[agi]-=$skill_value[$uid][78][0];
				if($m[agi]<1){$m[agi]=1;}
				skill_set_dot(78,$m[id],$lv,2);
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[agi]-=$skill_value[$uid][78][0];
				if($p[agi]<1){$p[agi]=1;}
			}
			else
			{
				$m[agi]-=$skill_value[$uid][78][0];
				if($m[agi]<1){$m[agi]=1;}
			}
		break;
	}
}
function skill_79(&$a,$lv,$uid,$trun=0,$dmg)//神頌曲
{
	global $skill_value,$pet,$p,$m,$wogclass;
	$wogclass->end_skill_id[]=79;
	$wogclass->end_skill_lv[]=$lv;
	unset($wogclass->p_skill_count[79],$wogclass->p_skill_start[79],$wogclass->p_skill_event[0][array_search(79,$wogclass->p_skill_event[0])]);
	$wogclass->pskill_event0=count($wogclass->p_skill_event[0]);
}
function skill_80(&$a,$lv,$uid,$trun=0,$dmg)//地獄禮讚
{
	global $skill_value,$pet,$p,$m,$wogclass;
	$wogclass->end_skill_id[]=80;
	$wogclass->end_skill_lv[]=$lv;
	unset($wogclass->p_skill_count[80],$wogclass->p_skill_start[80],$wogclass->p_skill_event[0][array_search(80,$wogclass->p_skill_event[0])]);
	$wogclass->pskill_event0=count($wogclass->p_skill_event[0]);
}
function skill_81(&$a,$lv,$uid,$trun=0,$dmg)//詐騙
{
	global $skill_value,$pet,$p,$m,$wogclass;
	$wogclass->end_skill_id[]=81;
	$wogclass->end_skill_lv[]=$lv;
	unset($wogclass->p_skill_count[81],$wogclass->p_skill_start[81],$wogclass->p_skill_event[0][array_search(81,$wogclass->p_skill_event[0])]);
	$wogclass->pskill_event0=count($wogclass->p_skill_event[0]);
}
function skill_82(&$a,$lv,$uid,$trun=0,$dmg)//石人陣
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][82][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=150;
				break;
			case 2:
				$value1=280;
				break;
			case 3:
				$value1=420;
				break;
			case 4:
				$value1=600;
				break;
			case 5:
				$value1=820;
				break;
			case 6:
				$value1=1070;
				break;
			case 7:
				$value1=1350;
				break;
			case 8:
				$value1=1670;
				break;
			case 9:
				$value1=2010;
				break;
			case 10:
				$value1=2430;
				break;
		} // switch
		$skill_value[$uid][82][0]=$value1;
	}
	$a[df]+=$skill_value[$uid][82][0];
}
function skill_83(&$a,$lv,$uid,$trun=0,$dmg)//黑渦
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][83][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=200;
				break;
			case 2:
				$value1=350;
				break;
			case 3:
				$value1=550;
				break;
			case 4:
				$value1=750;
				break;
			case 5:
				$value1=980;
				break;
			case 6:
				$value1=1300;
				break;
			case 7:
				$value1=1800;
				break;
			case 8:
				$value1=2600;
				break;
			case 9:
				$value1=3600;
				break;
			case 10:
				$value1=4900;
				break;
		} // switch
		$skill_value[$uid][83][0]=$value1;
	}
	switch($trun)
	{
		case 0:
			if(empty($a[p_id]))
			{
				$p[au]-=$skill_value[$uid][83][0];
				if($p[au]<1){$p[au]=1;}
				skill_set_dot(83,$p[p_id],$lv,1);
			}
			else
			{
				$m[au]-=$skill_value[$uid][83][0];
				if($m[au]<1){$m[au]=1;}
				skill_set_dot(83,$m[id],$lv,2);
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[au]-=$skill_value[$uid][83][0];
				if($p[au]<1){$p[au]=1;}
			}
			else
			{
				$m[au]-=$skill_value[$uid][83][0];
				if($m[au]<1){$m[au]=1;}
			}
		break;
	}
}
function skill_84(&$a,$lv,$uid,$trun=0,$dmg)//精密陷阱
{
	global $skill_value,$pet,$p,$m,$wogclass;
	$wogclass->end_skill_id[]=84;
	$wogclass->end_skill_lv[]=$lv;
	unset($wogclass->p_skill_count[84],$wogclass->p_skill_start[84],$wogclass->p_skill_event[0][array_search(84,$wogclass->p_skill_event[0])]);
	$wogclass->pskill_event0=count($wogclass->p_skill_event[0]);
}
function skill_85(&$a,$lv,$uid,$trun=0,$dmg)//閃電痛擊
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][85][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=10;
				break;
			case 2:
				$value1=15;
				break;
			case 3:
				$value1=20;
				break;
			case 4:
				$value1=25;
				break;
			case 5:
				$value1=30;
				break;
			case 6:
				$value1=35;
				break;
			case 7:
				$value1=40;
				break;
			case 8:
				$value1=45;
				break;
			case 9:
				$value1=50;
				break;
			case 10:
				$value1=55;
				break;
		} // switch
		$skill_value[$uid][85][0]=$value1;
	}
	$pet[pet_act]+=$skill_value[$uid][85][0];
}
function skill_86(&$a,$lv,$uid,$trun=0,$dmg)//獸騎
{
	global $skill_value,$pet,$p,$m,$wogclass;
	if(empty($skill_value[$uid][86][0]))
	{
		$value1=0;
		switch($lv){
			case 1:
				$value1=150;
				break;
			case 2:
				$value1=280;
				break;
			case 3:
				$value1=420;
				break;
			case 4:
				$value1=600;
				break;
			case 5:
				$value1=820;
				break;
			case 6:
				$value1=1070;
				break;
			case 7:
				$value1=1350;
				break;
			case 8:
				$value1=1670;
				break;
			case 9:
				$value1=2010;
				break;
			case 10:
				$value1=2430;
				break;
		} // switch
		$skill_value[$uid][86][0]=$value1;
	}
	$a[agi]+=$skill_value[$uid][86][0];
}
function skill_f_property(&$a,$lv,$uid,$trun=0,$s_id)
{
	global $skill_value,$p,$m,$wogclass;
	if(empty($skill_value[$uid][$s_id][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1;
				break;
			case 2:
				$value=2;
				break;
			case 3:
				$value=3;
				break;
			case 4:
				$value=4;
				break;
			case 5:
				$value=5;
				break;
			case 6:
				$value=6;
				break;
		} // switch
		$skill_value[$uid][$s_id][0]=$value;
	}
	switch($trun)
	{
		case 2:
		case 0:
			if(empty($a[p_id]))
			{
				$m[temp_s]=$wogclass->s_check($skill_value[$uid][$s_id][0]-$p[s_property]);
			}
			else
			{
				$p[temp_s]=$wogclass->s_check($skill_value[$uid][$s_id][0]-$m[s_property]);
			}
		break;
	}
}
function skill_d_property(&$a,$lv,$uid,$trun=0,$s_id)
{
	global $skill_value,$p,$m,$wogclass;
	if(empty($skill_value[$uid][28][0]))
	{
		$value=0;
		switch($lv){
			case 1:
				$value=1;
				break;
			case 2:
				$value=2;
				break;
			case 3:
				$value=3;
				break;
			case 4:
				$value=4;
				break;
			case 5:
				$value=5;
				break;
			case 6:
				$value=6;
				break;
		} // switch
		$skill_value[$uid][28][0]=$value;
	}
	switch($trun)
	{
		case 0:
			if(empty($a[p_id]))
			{
				$p[temp_s]=$wogclass->s_check($p[s_property]-$skill_value[$uid][28][0]);
				skill_set_dot(28,$m[id],$lv,1);
			}
			else
			{
				$m[temp_s]=$wogclass->s_check($m[s_property]-$skill_value[$uid][28][0]);
				skill_set_dot(28,$m[id],$lv,2);
			}
		break;
		case 1:
			if(!empty($a[p_id]))
			{
				$p[temp_s]=$wogclass->s_check($p[s_property]-$skill_value[$uid][28][0]);
			}
			else
			{
				$m[temp_s]=$wogclass->s_check($m[s_property]-$skill_value[$uid][28][0]);
			}
		break;
	}
}
function skill_undo(&$a,$temp)
{
	$a=$temp;
}
function skill_set_dot($sid,$id,$lv,$type)
{
	global $wogclass;
	switch($type)
	{
		case 1:
			if(!isset($wogclass->p_dot_buffer[$sid]))
			{
				$wogclass->p_dot_buffer[$sid]=array($id,$lv);
			}
		break;
		case 2:
			if(!isset($wogclass->m_dot_buffer[$sid]))
			{
				$wogclass->m_dot_buffer[$sid]=array($id,$lv);
			}
		break;
	}
}
?>