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

class wog_etc_well{

	function well_view()
	{
		global $DB_site,$wog_arry,$_COOKIE;
		
		if ($_COOKIE["wog_cookie_name"] != Null || $_COOKIE["wog_cookie"] != Null)
		{
			if ($_COOKIE["wog_cookie_debug"] == md5($_COOKIE["wog_cookie"].$wog_arry["cookie_debug"]))
			{
				showscript("parent.try_login()");
				return;
			}
		}	
		$p=$DB_site->query_first("select cp.p_name,cp.at,cp.df,cp.mat
		,cp.mdf,cp.s_property,cp.p_birth,cp.p_img_set,cp.p_img_url
		,cp.str,cp.life,cp.vit,cp.smart,cp.agi,cp.au,cp.be
		,cp.hp,cp.hpmax,cp.p_money,cp.p_lv,cp.p_exp
		,cp.p_nextexp,cp.p_win,cp.p_lost,cp.p_sex,cp.i_img,cp.p_win_total,cp.p_place,cp.p_cdate
		,wog_character.ch_name,a.d_name as a_name ,b.d_name,e.d_name as e_name,d.d_name as dd_name
		,c.d_name as c_name,f.d_name as item_name,g.d_name as item_name2
		,cp.base_str,cp.base_life,cp.base_smart,cp.base_agi,cp.base_au,cp.base_be,cp.base_vit
		,cp.sp,cp.spmax
		from wog_cp cp left join wog_character on cp.ch_id=wog_character.ch_id
		left join wog_player_arm h on h.p_id=cp.p_pid
		left join wog_df a on h.a_id=a.d_id
		left join wog_df b on h.d_body_id=b.d_id
		left join wog_df e on h.d_head_id=e.d_id
		left join wog_df d on h.d_hand_id=d.d_id
		left join wog_df c on h.d_foot_id=c.d_id
		left join wog_df f on h.d_item_id=f.d_id
		left join wog_df g on h.d_item_id2=g.d_id
		LIMIT 1 ");
		if($p[p_img_set]==1)
		{
			$p[i_img]=$p[p_img_url];
		}
		if($p)
		{
			$etc_str=$p[str]-$p[base_str];
			$etc_smart=$p[smart]-$p[base_smart];
			$etc_agi=$p[agi]-$p[base_agi];
			$etc_life=$p[life]-$p[base_life];
			$etc_vit=$p[vit]-$p[base_vit];
			$etc_au=$p[au]-$p[base_au];
			$etc_be=$p[be]-$p[base_be];
			$p[p_cdate]=player_age($p[p_cdate],$wog_arry["player_age"]);
			showscript("parent.well_view($p[p_win],$p[p_lost],$p[p_img_set],'$p[i_img]','$p[p_name]','$p[p_sex]','$p[ch_name]','$p[s_property]','$p[p_lv]','$p[p_exp]','$p[p_nextexp]','$p[p_money]','$p[hp]','$p[hpmax]',$p[str],$p[smart],$p[agi],$p[life],$p[vit],$p[au],$p[be],'$p[at]','$p[mat]','$p[df]','$p[mdf]','$p[a_name]','$p[d_name]','$p[e_name]','$p[dd_name]','$p[c_name]','$p[item_name]','$p[item_name2]',$p[p_place],$p[p_birth],$p[p_cdate],".$wog_arry["del_day"].",".$wog_arry["f_time"].",'$p[p_win_total]',".$wog_arry["cp_mmoney"].",$p[sp],$p[spmax],$etc_str,$etc_smart,$etc_agi,$etc_vit,$etc_life,$etc_au,$etc_be);parent.f_count='".$wog_arry["f_count"]."'");
		}else
		{
			showscript("parent.no_cp(".$wog_arry["del_day"].",".$wog_arry["f_time"].",".$wog_arry["cp_mmoney"].");parent.f_count='".$wog_arry["f_count"]."'");
		}
		unset($p,$temp_s);
	}
}
?>