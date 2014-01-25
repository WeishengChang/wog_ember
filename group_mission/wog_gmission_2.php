<?php
class gmission_2
{
	function mission_start()
	{
		global $DB_site;
		$r=rand(2,3);
		$need_array=array();
		$reward_array=array();
		$et=0;
		$ex_t=array();
		for($i=0;$i<$r;$i++)
		{			
			$et=rand(1,12);
			while(in_array($et,$ex_t))
			{
				$et=rand(1,12);
			}
			$ex_t[]=$et;	
		}
		$sql="select g_id,g_name from wog_group_main where g_npc=1 and g_boss<5 ORDER BY RAND() limit $r";
		$m=$DB_site->query($sql);
		
		while($ms=$DB_site->fetch_array($m))
		{
			$need_array[]=array(
				'g_id'=>$ms[g_id],
				'g_name'=>$ms[g_name]
			);
		}

		foreach($ex_t as $v)
		{
			$reward_array[]=array(
				'type'=>'ex',
				'id'=>$v,
				'num'=>rand(300,500)
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