<?php
function mission_start()
{
	global $DB_site;
	$sql="select m_id from wog_monster where m_mission=0 and m_npc=0 and m_meet=0 and m_place in (1,2,3,4) ORDER BY RAND() LIMIT 1";
	$m=$DB_site->query_first($sql);
	return array(
	array(
		array(
			'm_pet_id'=>$m[m_id]
		)
	),
	array(
		array(
		'a_id'=>array(),
		'd_body_id'=>array(),
		'd_foot_id'=>array(),
		'd_hand_id'=>array(),
		'd_head_id'=>array(),
		'd_item_id'=>array('1422*1'),
		'd_stone_id'=>array(),
		'd_honor_id'=>array('1304*1'),
		'd_plus_id'=>array()
		),
		array(
		'a_id'=>array(),
		'd_body_id'=>array(),
		'd_foot_id'=>array(),
		'd_hand_id'=>array(),
		'd_head_id'=>array(),
		'd_item_id'=>array('1422*1'),
		'd_stone_id'=>array(),
		'd_honor_id'=>array('1305*1'),
		'd_plus_id'=>array()
		),
		array(
		'a_id'=>array(),
		'd_body_id'=>array(),
		'd_foot_id'=>array(),
		'd_hand_id'=>array(),
		'd_head_id'=>array(),
		'd_item_id'=>array('1422*1'),
		'd_stone_id'=>array(),
		'd_honor_id'=>array('1306*1'),
		'd_plus_id'=>array()
		),
		array(
		'a_id'=>array(),
		'd_body_id'=>array(),
		'd_foot_id'=>array(),
		'd_hand_id'=>array(),
		'd_head_id'=>array(),
		'd_item_id'=>array(),
		'd_stone_id'=>array(),
		'd_honor_id'=>array('2222*1'),
		'd_plus_id'=>array()
		),
		array(
		'a_id'=>array(),
		'd_body_id'=>array(),
		'd_foot_id'=>array(),
		'd_hand_id'=>array(),
		'd_head_id'=>array(),
		'd_item_id'=>array('1422*1'),
		'd_stone_id'=>array(),
		'd_honor_id'=>array('1307*1'),
		'd_plus_id'=>array()
		)
	),
	'mission_type'=>'2'
	);
}
function mission_body($user_id,$m_id)
{
}
function mission_end($user_id,$m_id)
{
}
?>