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
function trap_15($lv,$base=1)
{
	global $trap_value1,$trap_value2;
	$trap_main=array();
	if(empty($trap_value1) && empty($trap_value2))
	{
		$trap_value=trap_dmg($lv);
		$trap_value1=$trap_value[0];
		$trap_value2=$trap_value[1];
	}
	$trap_main[1][min]=$trap_value1*$base;
	$trap_main[1][max]=$trap_value2*$base;
	$trap_main[8][min]=$trap_value1*$base;
	$trap_main[8][max]=$trap_value2*$base;
	$trap_main[12][min]=$trap_value1*$base;
	$trap_main[12][max]=$trap_value2*$base;
	return $trap_main;
}
function trap_16($lv,$base=1)
{
	global $trap_value1,$trap_value2;
	$trap_main=array();
	if(empty($trap_value1) && empty($trap_value2))
	{
		$trap_value=trap_dmg($lv);
		$trap_value1=$trap_value[0];
		$trap_value2=$trap_value[1];
	}
	$trap_main[6][min]=$trap_value1*$base;
	$trap_main[6][max]=$trap_value2*$base;
	$trap_main[7][min]=$trap_value1*$base;
	$trap_main[7][max]=$trap_value2*$base;
	$trap_main[11][min]=$trap_value1*$base;
	$trap_main[11][max]=$trap_value2*$base;
	return $trap_main;
}
function trap_17($lv,$base=1)
{
	global $trap_value1,$trap_value2;
	$trap_main=array();
	if(empty($trap_value1) && empty($trap_value2))
	{
		$trap_value=trap_dmg($lv);
		$trap_value1=$trap_value[0];
		$trap_value2=$trap_value[1];
	}
	$trap_main[2][min]=$trap_value1*$base;
	$trap_main[2][max]=$trap_value2*$base;
	$trap_main[5][min]=$trap_value1*$base;
	$trap_main[5][max]=$trap_value2*$base;
	$trap_main[10][min]=$trap_value1*$base;
	$trap_main[10][max]=$trap_value2*$base;
	return $trap_main;
}
function trap_18($lv,$base=1)
{
	global $trap_value1,$trap_value2;
	$trap_main=array();
	if(empty($trap_value1) && empty($trap_value2))
	{
		$trap_value=trap_dmg($lv);
		$trap_value1=$trap_value[0];
		$trap_value2=$trap_value[1];
	}
	$trap_main[3][min]=$trap_value1*$base;
	$trap_main[3][max]=$trap_value2*$base;
	$trap_main[4][min]=$trap_value1*$base;
	$trap_main[4][max]=$trap_value2*$base;
	$trap_main[9][min]=$trap_value1*$base;
	$trap_main[9][max]=$trap_value2*$base;
	return $trap_main;
}
function formation_19($group,$key,$wp_act,$lv,$def_formation,$g_area_type)
{
	global $formation_value;
	if(empty($formation_value[$group[g_id]][0]))
	{
		switch($lv)
		{
			case 1:
				$value1=1.01;
			break;
			case 2:
				$value1=1.02;
			break;
			case 3:
				$value1=1.03;
			break;
			case 4:
				$value1=1.04;
			break;
			case 5:
				$value1=1.05;
			break;
			case 6:
				$value1=1.06;
			break;
			case 7:
				$value1=1.07;
			break;
			case 8:
				$value1=1.08;
			break;
			case 9:
				$value1=1.09;
			break;
			case 10:
				$value1=1.1;
			break;
		}
		$formation_value[$group[g_id]][0]=$value1;
	}
	switch(true)
	{
		case ($key==4):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
	}
	return $wp_act;
}
function formation_20($group,$key,$wp_act,$lv,$def_formation,$g_area_type)
{
	global $formation_value;
	if(empty($formation_value[$group[g_id]][0]))
	{
		switch ($lv)
		{
			case 1:
				$value1=1.01;
			break;
			case 2:
				$value1=1.02;
			break;
			case 3:
				$value1=1.03;
			break;
			case 4:
				$value1=1.04;
			break;
			case 5:
				$value1=1.05;
			break;
			case 6:
				$value1=1.06;
			break;
			case 7:
				$value1=1.07;
			break;
			case 8:
				$value1=1.08;
			break;
			case 9:
				$value1=1.09;
			break;
			case 10:
				$value1=1.1;
			break;
		}
		$formation_value[$group[g_id]][0]=$value1;
	}
	switch(true)
	{
		case ($key==1):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
	}
	return $wp_act;
}
function formation_21($group,$key,$wp_act,$lv,$def_formation,$g_area_type)
{
	global $formation_value;
	if(empty($formation_value[$group[g_id]][0]) || empty($formation_value[$group[g_id]][1]))
	{
		switch ($lv)
		{
			case 1:
				$value1=1.01;
				$value2=1.005;
			break;
			case 2:
				$value1=1.02;
				$value2=1.01;
			break;
			case 3:
				$value1=1.03;
				$value2=1.015;
			break;
			case 4:
				$value1=1.04;
				$value2=1.02;
			break;
			case 5:
				$value1=1.05;
				$value2=1.025;
			break;
			case 6:
				$value1=1.06;
				$value2=1.03;
			break;
			case 7:
				$value1=1.07;
				$value2=1.035;
			break;
			case 8:
				$value1=1.08;
				$value2=1.04;
			break;
			case 9:
				$value1=1.09;
				$value2=1.045;
			break;
			case 10:
				$value1=1.1;
				$value2=1.05;
			break;
		}
		$formation_value[$group[g_id]][0]=$value1;
		$formation_value[$group[g_id]][1]=$value2;
	}
	switch(true)
	{
		case ($key==6):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
		case ($key==7):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
	}
	switch($def_formation)
	{
		case 20:
			$wp_act*=$formation_value[$group[g_id]][1];
		break;
	}
	return $wp_act;
}
function formation_22($group,$key,$wp_act,$lv,$def_formation,$g_area_type)
{
	global $formation_value;
	if(empty($formation_value[$group[g_id]][0]))
	{
		switch ($lv)
		{
			case 1:
				$value1=1.005;
			break;
			case 2:
				$value1=1.01;
			break;
			case 3:
				$value1=1.015;
			break;
			case 4:
				$value1=1.02;
			break;
			case 5:
				$value1=1.025;
			break;
			case 6:
				$value1=1.03;
			break;
			case 7:
				$value1=1.035;
			break;
			case 8:
				$value1=1.04;
			break;
			case 9:
				$value1=1.045;
			break;
			case 10:
				$value1=1.05;
			break;
		}
		$formation_value[$group[g_id]][0]=$value1;
	}
	switch($def_formation)
	{
		case 21:
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
		case 19:
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
	}
	return $wp_act;
}
function formation_23($group,$key,$wp_act,$lv,$def_formation,$g_area_type)
{
	global $formation_value;
	if(empty($formation_value[$group[g_id]][0]) || empty($formation_value[$group[g_id]][1]))
	{
		switch ($lv)
		{
			case 1:
				$value1=1.01;
				$value2=1.005;
			break;
			case 2:
				$value1=1.02;
				$value2=1.01;
			break;
			case 3:
				$value1=1.03;
				$value2=1.015;
			break;
			case 4:
				$value1=1.04;
				$value2=1.02;
			break;
			case 5:
				$value1=1.05;
				$value2=1.025;
			break;
			case 6:
				$value1=1.06;
				$value2=1.03;
			break;
			case 7:
				$value1=1.07;
				$value2=1.035;
			break;
			case 8:
				$value1=1.08;
				$value2=1.04;
			break;
			case 9:
				$value1=1.09;
				$value2=1.045;
			break;
			case 10:
				$value1=1.1;
				$value2=1.05;
			break;
		}
		$formation_value[$group[g_id]][0]=$value1;
		$formation_value[$group[g_id]][1]=$value2;
	}
	switch(true)
	{
		case ($key==2):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
	}
	switch($def_formation)
	{
		case 24:
			$wp_act*=$formation_value[$group[g_id]][1];
		break;
	}
	return $wp_act;
}
function formation_24($group,$key,$wp_act,$lv,$def_formation,$g_area_type)
{
	global $formation_value;
	if(empty($formation_value[$group[g_id]][0]))
	{
		switch ($lv)
		{
			case 1:
				$value1=1.005;
			break;
			case 2:
				$value1=1.01;
			break;
			case 3:
				$value1=1.015;
			break;
			case 4:
				$value1=1.02;
			break;
			case 5:
				$value1=1.025;
			break;
			case 6:
				$value1=1.03;
			break;
			case 7:
				$value1=1.035;
			break;
			case 8:
				$value1=1.04;
			break;
			case 9:
				$value1=1.045;
			break;
			case 10:
				$value1=1.05;
			break;
		}
		$formation_value[$group[g_id]][0]=$value1;
	}
	switch(true)
	{
		case ($g_area_type==2):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
		case ($g_area_type==6):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
	}
	return $wp_act;
}
function formation_25($group,$key,$wp_act,$lv,$def_formation,$g_area_type)
{
	global $formation_value;
	if(empty($formation_value[$group[g_id]][0]))
	{
		switch ($lv)
		{
			case 1:
				$value1=1.01;
			break;
			case 2:
				$value1=1.02;
			break;
			case 3:
				$value1=1.03;
			break;
			case 4:
				$value1=1.04;
			break;
			case 5:
				$value1=1.05;
			break;
			case 6:
				$value1=1.06;
			break;
			case 7:
				$value1=1.07;
			break;
			case 8:
				$value1=1.08;
			break;
			case 9:
				$value1=1.09;
			break;
			case 10:
				$value1=1.1;
			break;
		}
		$formation_value[$group[g_id]][0]=$value1;
	}
	switch(true)
	{
		case ($key==3):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
		case ($key==9):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
	}
	return $wp_act;
}
function formation_26($group,$key,$wp_act,$lv,$def_formation,$g_area_type)
{
	global $formation_value;
	if(empty($formation_value[$group[g_id]][0]))
	{
		switch ($lv)
		{
			case 1:
				$value1=1.005;
			break;
			case 2:
				$value1=1.01;
			break;
			case 3:
				$value1=1.015;
			break;
			case 4:
				$value1=1.02;
			break;
			case 5:
				$value1=1.025;
			break;
			case 6:
				$value1=1.03;
			break;
			case 7:
				$value1=1.035;
			break;
			case 8:
				$value1=1.04;
			break;
			case 9:
				$value1=1.045;
			break;
			case 10:
				$value1=1.05;
			break;
		}
		$formation_value[$group[g_id]][0]=$value1;
	}
	switch(true)
	{
		case ($def_formation==22):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
		case ($def_formation==25):
			$wp_act*=$formation_value[$group[g_id]][0];
		break;
	}
	return $wp_act;
}
function def_ex_13($lv)
{
	switch ($lv)
	{
		case 1:
			$value1=0.975;
		break;
		case 2:
			$value1=0.95;
		break;
		case 3:
			$value1=0.925;
		break;
		case 4:
			$value1=0.9;
		break;
		case 5:
			$value1=0.875;
		break;
		case 6:
			$value1=0.85;
		break;
		case 7:
			$value1=0.825;
		break;
		case 8:
			$value1=0.8;
		break;
		case 9:
			$value1=0.775;
		break;
		case 10:
			$value1=0.75;
		break;
		default:
			$value1=1;
		break;
	}
	return($value1);
}
function trap_dmg($lv)
{
	$trap_value=array();
	switch ($lv)
	{
		case 1:
			$trap_value[0]=50;
			$trap_value[1]=100;
		break;
		case 2:
			$trap_value[0]=100;
			$trap_value[1]=200;
		break;
		case 3:
			$trap_value[0]=150;
			$trap_value[1]=300;
		break;
		case 4:
			$trap_value[0]=200;
			$trap_value[1]=400;
		break;
		case 5:
			$trap_value[0]=250;
			$trap_value[1]=500;
		break;
		case 6:
			$trap_value[0]=300;
			$trap_value[1]=600;
		break;
		case 7:
			$trap_value[0]=350;
			$trap_value[1]=700;
		break;
		case 8:
			$trap_value[0]=400;
			$trap_value[1]=800;
		break;
		case 9:
			$trap_value[0]=450;
			$trap_value[1]=900;
		break;
		case 10:
			$trap_value[0]=500;
			$trap_value[1]=1000;
		break;
		case 11:
			$trap_value[0]=550;
			$trap_value[1]=1100;
		break;
		case 12:
			$trap_value[0]=600;
			$trap_value[1]=1200;
		break;
		case 13:
			$trap_value[0]=650;
			$trap_value[1]=1300;
		break;
		case 14:
			$trap_value[0]=700;
			$trap_value[1]=1400;
		break;
		case 15:
			$trap_value[0]=750;
			$trap_value[1]=1500;
		break;
		case 16:
			$trap_value[0]=800;
			$trap_value[1]=1600;
		break;
		case 17:
			$trap_value[0]=850;
			$trap_value[1]=1700;
		break;
		case 18:
			$trap_value[0]=900;
			$trap_value[1]=1800;
		break;
		case 19:
			$trap_value[0]=950;
			$trap_value[1]=1900;
		break;
		case 20:
			$trap_value[0]=1000;
			$trap_value[1]=2000;
		break;
		case 21:
			$trap_value[0]=1050;
			$trap_value[1]=2100;
		break;
		case 22:
			$trap_value[0]=1100;
			$trap_value[1]=2200;
		break;
		case 23:
			$trap_value[0]=1150;
			$trap_value[1]=2300;
		break;
		case 24:
			$trap_value[0]=1200;
			$trap_value[1]=2400;
		break;
		case 25:
			$trap_value[0]=1250;
			$trap_value[1]=2500;
		break;
	}
	return $trap_value;
}
?>