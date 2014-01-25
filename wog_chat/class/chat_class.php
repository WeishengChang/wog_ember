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
class chat_class{
	var $show=NULL;
	var $block=NULL;
	var $maxnum=0;
	var $block_maxnum=0;
	var $message=NULL;
	var $file_path="data";
	function chat_message($message)
	{
		global $chat_arry;
		$this->show=file($this->file_path."/www_2233_talk_list.txt");
		$this->maxnum=sizeof($this->show);
		$message_list="";
		if($this->maxnum >= $chat_arry["m_limit"])
		{
			$this->maxnum=$chat_arry["m_limit"];
			for($i=1;$i<$this->maxnum;$i++)
			{
				$message_list.=$this->show[$i];
				$this->show[$i-1]=$this->show[$i];
			}
			$this->show[$i-1]=$message;
		}else
		{
			for($i=0;$i<$this->maxnum;$i++)
			{
				$message_list.=$this->show[$i];
			}
			$this->show[$i]=$message;
			$this->maxnum++;
		}
		$message_list.=$message."\r\n";
		$fp=fopen($this->file_path."/www_2233_talk_list.txt","w");
		fputs($fp,$message_list);
		fclose($fp);
		unset($message_list);
		unset($people_list);
		unset($show);
	}
	function get_speed()
	{
		$show=file($this->file_path."/www_2233_talkspeed.txt");
		$maxnum=sizeof($show);
		for($i=0;$i<$maxnum;$i++)
		{
			$speed=$show[$i];
		}
		if($maxnum==0)
		{
			$speed=0;
		}
		unset($show);
		return $speed;
	}
	function set_speed($speed)
	{
		$fp=fopen($this->file_path."/www_2233_talkspeed.txt","w");
		fputs($fp,$speed);
		fclose($fp);
	}
	function get_chat_message()
	{
		if($this->show==NULL)
		{
			$this->show=file($this->file_path."/www_2233_talk_list.txt");
			$this->maxnum=sizeof($this->show);
		}
	}
	function chat_block($user)
	{
		global $lang;
		if($this->block == NULL)
		{
			$this->block=file($this->file_path."/www_2233_block.txt");
			$this->block_maxnum=sizeof($this->block);
		}
//		$ip=get_ip()."\r\n";
		$time=time();
		$block_list="";
		$update=false;
		for($i=0;$i<$this->block_maxnum;$i++)
		{
			$temp_block=explode(",",$this->block[$i]);
			if($user==$temp_block[0])
			{
				return true;
			}
			$temp_block[1]=str_replace("\r\n","",$temp_block[1]);
			if($temp_block[1] < $time)
			{
				$update=true;
			}else
			{
				$block_list.=$this->block[$i];
			}
		}
		if($update){

			$fp=fopen($this->file_path."/www_2233_block.txt","w");
			fputs($fp,$block_list);
			fclose($fp);
			$this->block=NULL;
		}
		unset($block_list);
		return false;
	}
	function set_block($user)
	{
		$fp=fopen($this->file_path."/www_2233_block.txt","a+");
		fputs($fp,$user.",".(time()+86400)."\r\n");
		fclose($fp);
	}

	function get_ip()
	{
		global $_SERVER,$_ENV,$REMOTE_ADDR;
		if( getenv('HTTP_X_FORWARDED_FOR') != '' )
		{
			$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
			if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip_list) )
			{
				$private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10.\.*/', '/^224.\.*/', '/^240.\.*/');
				$client_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
			}
		}
		else
		{
			$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
		}
		return $client_ip;
	}
}
?>