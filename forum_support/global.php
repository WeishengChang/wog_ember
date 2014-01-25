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
// ###################### Start init #######################


// gzip start

$do_gzip_compress = FALSE;
if ($wog_arry["gzip_compress"])
{

	$phpver = phpversion();
	$useragent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;
	if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
	{
		if ( extension_loaded('zlib') )
		{
			ob_start('ob_gzhandler');
		}
	}
	else if ( $phpver > '4.0' )
	{

		if ( strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') )
		{
			if ( extension_loaded('zlib') )
			{
				$do_gzip_compress = TRUE;
				ob_start();
				ob_implicit_flush(0);
				header('Content-Encoding: gzip');
			}
		}
	}
}
post_check($_POST);
unset($dbservertype);
//load config
require('./forum_support/config/config.php');
// init db **********************
// load db class
$dbservertype = strtolower($dbservertype);
$dbclassname="./forum_support/config/db_$dbservertype.php";
require($dbclassname);

$DB_site=new DB_Sql_vb;
$DB_site->appname='WOG';
$DB_site->appshortname='WOG';
$DB_site->database=$dbname;
$DB_site->server=$servername;
$DB_site->user=$dbusername;
$DB_site->password=$dbpassword;
$DB_site->connect($servername, $dbusername, $dbpassword, $usepconnect);
$DB_site->query_first("SET NAMES utf8;");
$DB_site->query_first("SET CHARACTER_SET_CLIENT=utf8;");
$DB_site->query_first("SET CHARACTER_SET_RESULTS=utf8;");
unset($servername, $dbusername, $dbpassword, $usepconnect);
//$DB_site->connect();
$dbpassword="";
$DB_site->password="";

// end init db
// ###################### Start functions #######################
?>