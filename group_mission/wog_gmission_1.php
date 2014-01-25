<?php
class gmission_1
{
	function mission_start()
	{
		$r=rand(2,4);
		$need_array=array();
		$reward_array=array();
		$wt=0;
		$et=0;
		$wp_t=array();
		$ex_t=array();
		for($i=0;$i<$r;$i++)
		{
			$wt=rand(1,9);
			while(in_array($wt,$wp_t))
			{
				$wt=rand(1,9);
			}
			$wp_t[]=$wt;
			
			$et=rand(1,12);
			while(in_array($et,$ex_t))
			{
				$et=rand(1,12);
			}
			$ex_t[]=$et;	
		}
		
		foreach($wp_t as $v)
		{
			$need_array[]=array(
				'wp_id'=>$v,
				'wp_num'=>rand(300,500)
			);			
		}
		foreach($ex_t as $v)
		{
			$reward_array[]=array(
				'type'=>'ex',
				'id'=>$v,
				'num'=>rand(900,1200)
			);			
		}
		return array($need_array,$reward_array);
	}
	function mission_body($user_id,$m_id)
	{
	}
	function mission_end($user_id,$m_id)
	{
	}
}
?>