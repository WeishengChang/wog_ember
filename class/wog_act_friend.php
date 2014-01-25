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
class wog_act_friend{
	function friend_list($user_id)
	{
		global $DB_site,$lang,$wog_arry,$_POST;
		if(empty($_POST["page"]) || !is_numeric($_POST["page"]))
		{
			$_POST["page"]="1";
		}
		$page=(int)$_POST["page"];
		$spage=($page*8)-8;
		$time=time()-$wog_arry["offline_time"];

		$sql="select count(f_id) from wog_friend_list where p_id=".$user_id;
		$m=$DB_site->query_first($sql);

		$sql="select a.p_id,a.p_name,a.p_lv,a.p_place,a.p_online_time from wog_player a,wog_friend_list b where b.p_id=".$user_id." and a.p_id=b.f_id order by a.p_online_time desc LIMIT ".$spage.",8";
		$pack=$DB_site->query($sql);
		$s="";
		$online_set=0;
		while($packs=$DB_site->fetch_array($pack))
		{
			$online_set=($packs[p_online_time]>=$time)?1:0;
			$s.=";".$packs[p_id].",".$packs[p_name].",".$packs[p_lv].",".$packs[p_place].",".$online_set;
		}
		$s=substr($s,1);
		$DB_site->free_result($pack);
		showscript("parent.friend_list('$s',$page,$m[0])");
		unset($s,$pack);
	}
	function friend_del($user_id)
	{
		global $DB_site,$_POST;
		$id=$_POST["temp_id"];
		$sql="delete from  wog_friend_list where p_id=$user_id and f_id=$id";
		$DB_site->query($sql);
		$this->friend_list($user_id);
	}
	function friend_add($user_id)
	{
		global $DB_site,$lang,$_POST;
		$name=addslashes(htmlspecialchars($_POST["name"]));
		if(empty($name))
		{
			alertWindowMsg($lang['wog_act_message_er1']);
		}
		$to_p=$DB_site->query_first("SELECT p_id FROM wog_player WHERE p_name='".$name."'");
		if($to_p[p_id]==$user_id)
		{
			alertWindowMsg($lang['wog_act_friend_er2']);
		}
		$sql="select p_id from wog_friend_list where p_id=$user_id and f_id=$to_p[p_id]";
		$check=$DB_site->query_first($sql);
		if($check)
		{
			alertWindowMsg($lang['wog_act_friend_er1']);
		}
		else
		{
			$sql="insert into wog_friend_list(p_id,f_id)values($user_id,$to_p[p_id])";
		}
		$from_p=$DB_site->query_first("SELECT p_name FROM wog_player WHERE p_id=$user_id");
		$DB_site->query($sql);
		$this->friend_list($user_id);
	}
}
?>