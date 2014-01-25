<?php
require_once 'wog_expedition.php';
class wog_team {
	private $exist = false;
	private $leader_p_id = null;
	private $member = array();
	public $expedition = null;
	static public function get($id) {	/** 取得既存隊伍 **/
		global $DB_site;
		$id = intval($id, 10);
		$data = $DB_site->query_first("
			SELECT t_id, p_id
			FROM wog_team_main
			WHERE t_id=$id
		");
		if(!!$data) {
			return new wog_team($id, $data['p_id']);
		} else {
			return null;
		}
	}
	private function __construct($id, $leader_p_id) {
		global $DB_site;
		$this->exist = true;
		$this->t_id = $id;
		$this->leader_p_id = intval($leader_p_id, 10);
		//抓出成員清單
		$query = $DB_site->query("
			SELECT p_id
			FROM wog_player
			WHERE t_id=$id
		");
		while($d = $DB_site->fetch_assoc($query)) {
			$this->member[] = intval($d['p_id'], 10);
		}
		//抓出探險隊資料
		$this->expedition = wog_expedition::get($id);
	}
	public function isExist() {
		return $this->exist;
	}
	
	public function isLeader($p_id) {
		return intval($p_id, 10) === $this->leader_p_id;
	}
	
	public function isMember($p_id) {
		return in_array(intval($p_id, 10), $this->member);
	}
}
?>