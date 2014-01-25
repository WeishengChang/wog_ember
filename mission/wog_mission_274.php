<?php
function mission_start()
{
	global $DB_site;
	$sql="select m_id,m_name,m_place from wog_monster where m_mission=0 and m_npc=0 and m_meet=0 and m_place in (8,16) ORDER BY RAND() LIMIT 1";
	$m=$DB_site->query_first($sql);
	$m_num=rand(5,7);
	$ex_id=rand(1,12);
	$ex_num=rand(160,180);
	return array(
	array(
		'monster_id'=>$m[m_id],
		'kill_num'=>$m_num
	),
	array(
		'ex_id'=>$ex_id,
		'ex_num'=>$ex_num
	),
	'mission_type'=>'3'
	);
}
function mission_body($user_id,$m_id)
{
}
function mission_end($user_id,$m_id)
{
}
?>