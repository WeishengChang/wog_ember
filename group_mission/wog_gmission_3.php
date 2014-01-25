<?php
class gmission_3
{
	function mission_start()
	{
		global $DB_site;
		$r=rand(2,3);
		$need_array=array();
		$reward_array=array();
		$g_item=array(
			1=>'野戰修復',
			2=>'偽報',
			3=>'停戰協議A',
			4=>'緊急徵召A',
			5=>'緊急徵召B',
			6=>'緊急徵召C',
			7=>'停戰協議B',
			8=>'停戰協議C'
		);

		$sql="select g_id,g_name,g_boss from wog_group_main where g_npc=1 and g_boss<5 ORDER BY RAND() limit $r";
		$m=$DB_site->query($sql);
		
		while($ms=$DB_site->fetch_array($m))
		{
			$need_array[]=array(
				'g_id'=>$ms[g_id],
				'g_name'=>$ms[g_name]
			);
			
			$value=0;
			switch($ms[g_boss])
			{
				case 4:
					$value=1;
				break;
				case 0:
					$value=2;
				break;
				case 1:
				case 2:
				case 3:
					$value=3;
				break;
			}
			switch(rand(1,3))
			{
				case 1: //資源獎勵
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
					foreach($ex_t as $v)
					{
						$reward_array[]=array(
							'type'=>'ex',
							'id'=>$v,
							'num'=>rand(1200,2100)*$value
						);			
					}
				break;
				case 2://兵力獎勵
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
					foreach($ex_t as $v)
					{
						$reward_array[]=array(
							'type'=>'wp',
							'id'=>$v,
							'num'=>rand(800,1100)*$value
						);			
					}				
				break;
				case 3://道具獎勵
					$item=rand(1,4);
					$reward_array[]=array(
						'type'=>'item',
						'id'=>$item,
						'num'=>1
					);					
				break;
			}
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