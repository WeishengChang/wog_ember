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

class wog_etc_hero{
	function hero_view()
	{
		global $DB_site,$wog_arry,$lang;
		echo charset();
		echo "<script language=JavaScript >\n";
		//##################### hero ######################
		$sql="select b.p_name,b.i_img,b.p_img_set,b.p_img_url,a.hero_type,a.hero_time from wog_player_cp a,wog_player b where a.hero_type > 1 and a.p_id=b.p_id";
		$p=$DB_site->query($sql);
		$s="";
		while($ps=$DB_site->fetch_array($p))
		{
			if($ps["p_img_set"]==1)
			{
				$ps["i_img"]=$ps["p_img_url"];
			}
			$s.=";".$ps["i_img"].",".$ps["p_name"].",".$ps["hero_type"].",".set_date_notime($ps["hero_time"]);
		}
		$s=substr($s,1);
		echo "parent.hero_view('$s');\n";
		$DB_site->free_result($p);
		$DB_site->close();
		echo "</script>\n";
		compress_exit();
	}
}
?>