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

class wog_etc_king{
	function king_view()
	{
		global $DB_site,$wog_arry,$lang,$_GET;
//        $fp=fopen("./cache/wog_king.js","w");
		$type=$_GET["type"];
		if(empty($type)){$type="1";}
		switch($type)
		{
			case "1":
				// WIN begin
				$title=$lang['wog_etc_king_win'];
				$sql="select p_name,p_win,i_img,p_img_set,p_img_url from wog_player order by p_win desc LIMIT 5";
				$p=$DB_site->query($sql);
				$s="";
				while($ps=$DB_site->fetch_array($p))
				{
					if($ps["p_img_set"]==1)
					{
						$ps["i_img"]=$ps["p_img_url"];
					}
					$s.=";".$ps["i_img"].",".$ps["p_name"].",".$ps["p_win"]." WIN";
				}
				// WIN end
				break;
			case "2":
				// HP begin
				$title=$lang['wog_etc_king_hp'];
				$sql="select p_name,hpmax,i_img,p_img_set,p_img_url from wog_player order by hpmax desc LIMIT 5";
				$p=$DB_site->query($sql);
				$s="";
				while($ps=$DB_site->fetch_array($p))
				{
					if($ps["p_img_set"]==1)
					{
						$ps["i_img"]=$ps["p_img_url"];
					}
					$s.=";".$ps["i_img"].",".$ps["p_name"].",".$lang['wog_etc_king_hp']." ".$ps["hpmax"];
				}
				// HP end
				break;
			case "3":
				// AT begin
				$title=$lang['wog_etc_king_ac'];
				$sql="select p_name,at,i_img,p_img_set,p_img_url from wog_player order by at desc LIMIT 5";
				$p=$DB_site->query($sql);
				$s="";
				while($ps=$DB_site->fetch_array($p))
				{
					if($ps["p_img_set"]==1)
					{
						$ps["i_img"]=$ps["p_img_url"];
					}
					$s.=";".$ps["i_img"].",".$ps["p_name"].",".$lang['wog_etc_king_ac']." ".$ps["at"];
				}
				// AT end
				break;
			case "4":
				// MT begin
				$title=$lang['wog_etc_king_mc'];
				$sql="select p_name,mat,i_img,p_img_set,p_img_url from wog_player order by mat desc LIMIT 5";
				$p=$DB_site->query($sql);
				$s="";
				while($ps=$DB_site->fetch_array($p))
				{
					if($ps["p_img_set"]==1)
					{
						$ps["i_img"]=$ps["p_img_url"];
					}
					$s.=";".$ps["i_img"].",".$ps["p_name"].",".$lang['wog_etc_king_mc']." ".$ps["mat"];
				}
				// MT end
				break;				
			case "5":
				// AGI begin
				$title=$lang['wog_etc_king_agl'];
				$sql="select p_name,agi,i_img,p_img_set,p_img_url from wog_player order by agi desc LIMIT 5";
				$p=$DB_site->query($sql);
				$s="";
				while($ps=$DB_site->fetch_array($p))
				{
					if($ps["p_img_set"]==1)
					{
						$ps["i_img"]=$ps["p_img_url"];
					}
					$s.=";".$ps["i_img"].",".$ps["p_name"].",".$lang['wog_etc_king_agl']." ".$ps["agi"];
				}
				// AGI end
				break;
			case "6":
				// LV begin
				$title="LV";
				$sql="select p_name,p_lv,i_img,p_img_set,p_img_url from wog_player  order by p_lv desc LIMIT 5";
				$p=$DB_site->query($sql);
				$s="";
				while($ps=$DB_site->fetch_array($p))
				{
					if($ps["p_img_set"]==1)
					{
						$ps["i_img"]=$ps["p_img_url"];
					}
					$s.=";".$ps["i_img"].",".$ps["p_name"].", LV ".$ps["p_lv"];
				}
				// LV end
				break;
			case "7":
				// money begin
				$title=$lang['wog_etc_king_money'];
				$sql="select p_name,p_money,i_img,p_img_set,p_img_url from wog_player  order by p_money desc LIMIT 5";
				$p=$DB_site->query($sql);
				$s="";
				while($ps=$DB_site->fetch_array($p))
				{
					if($ps["p_img_set"]==1)
					{
						$ps["i_img"]=$ps["p_img_url"];
					}
					$s.=";".$ps["i_img"].",".$ps["p_name"].", ".$ps["p_money"]." money";
				}
				// money end
				break;
			case "8":
				// au begin
				$title=$lang['wog_etc_king_au'];
				$sql="select p_name,au,i_img,p_img_set,p_img_url from wog_player order by au desc LIMIT 5";
				$p=$DB_site->query($sql);
				$s="";
				while($ps=$DB_site->fetch_array($p))
				{
					if($ps["p_img_set"]==1)
					{
						$ps["i_img"]=$ps["p_img_url"];
					}
					$s.=";".$ps["i_img"].",".$ps["p_name"].",".$lang['wog_etc_king_au']." ".$ps["au"];
				}
				// au end
				break;
		}
		
		$s=substr($s,1);
		$temp_str.="parent.king_view('".$title." TOP','$s');\n";
		$DB_site->free_result($p);
//		$DB_site->close();
		//$temp_str.="parent.wog_view.document.write('<hr size=1 color=#A2A9B8>');\n";
//		fputs($fp,$temp_str);
//		fclose($fp);
//		compress_exit();
		showscript($temp_str);
	}
}
?>