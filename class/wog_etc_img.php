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

class wog_etc_img{

	function img_view()
	{
		global $DB_site,$wog_arry;
		echo '<body bgcolor="#000000" text="#EFEFEF" link="#EFEFEF" vlink="#EFEFEF" alink="#EFEFEF">';
		echo '<table width="95%" border="1" cellspacing="0" cellpadding="0" align="center">';
		$img123=$DB_site->query("select * from wog_img ");
		$sum=0;
		while(is_array($img123s=$DB_site->fetch_array($img123)))
		{
			$sum ++;
			if(($sum%3)==1){echo "<tr>";}
			echo "<td align=center ><img src=/ex_img/images/wog/img/".$img123s[i_filename]."><br>no. ".$img123s[i_id]."</td>";
		}
		$DB_site->free_result($img123);
		unset($img123s);
		$DB_site->close();
		echo "</table>";
		echo "</body>";
		compress_exit();
	}
}	
?>