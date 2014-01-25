<?php

define("EXPEDITION_PREPARING", 0);	//未出發
define("EXPEDITION_DELAY", 1);		//推遲出發
define("EXPEDITION_EXPLORING", 2);	//已出發
define("EXPEDITION_RESULTING", 3);	//結算中
class wog_expedition {
	private $data = array();
	static public function get($id, $debug = false) {	/** 取得探險隊資料 **/
		global $DB_site;
		$id = intval($id, 10);
		$data = $DB_site->query_first("
			SELECT *
			FROM wog_expedition
			WHERE team_id=$id
		");
		if(!!$data) {
			return new wog_expedition($data);
		} elseif($debug === true) {
			return null;
		}else {
			$DB_site->query("
				INSERT INTO wog_expedition(team_id)VALUES($id)
			");
			return wog_expedition::get($id, true);
		}
	}
	
	private function __construct($data) {
		$this->data = $data;
	}
	
	public function __set($name, $value) {
		if(isset($this->data[$name])) $this->data[$name] = $value;
	}
	
	public function __get($name) {
		if(isset($this->data[$name])) return $this->data[$name];
		else return null;
	}
	
	public function donate($p_id, $money) {
		global $DB_site;
		if($money <= 0) {
			alertWindowMsg ("請提供正確的捐獻金額");
		}
		if($this->return_timeline > 0) {
			alertWindowMsg("探險期間無法捐獻");
		}
		$result = $DB_site->query_first("
			SELECT result_money
			FROM wog_expedition_result
			WHERE team_id=$this->team_id AND p_id=$p_id
		");
		if(+$result["result_money"] > 0) {
			alertWindowMsg("未領取結算金額前無法捐獻探險基金");
		}
		$DB_site->query("
			INSERT INTO wog_expedition_result(team_id, p_id, donate_money)
			VALUES($this->team_id, $p_id, $money)
			ON DUPLICATE KEY UPDATE donate_money = donate_money + $money
		");
	}
	
	public function fund() {
		global $DB_site;
		$donate = $DB_site->query_first("
			SELECT SUM(donate_money) AS fund
			FROM wog_expedition_result
			GROUP BY team_id
			HAVING team_id=$this->team_id
			ORDER BY NULL
		");
		return +$donate["fund"];
	}
	
	public function isDelayed() {
		return $this->delay_timeline > time();
	}
	
	public function pause() {
		global $DB_site;
		$DB_site->query("
			UPDATE wog_expedition
			SET delay_timeline=0, return_timeline=0
			WHERE team_id=$this->team_id
		");
		$this->delay_timeline = 0;
		$this->return_timeline = 0;
	}
	
	public function start($place, $type, $time, $delay) {
		global $DB_site, $wog_arry;
		if(!isset($wog_arry["place"][$place])) {
			alertWindowMsg ("未知的探險地點");
		}
		if($delay < 0 || $delay > 30) {
			alertWindowMsg ("推遲時間必須在0至30分鐘之間");
		}
		$delayed = $delay > 0 ?true:false;
		$delay = $delay*60 + time();
		switch($time) {
			case 1: //30分鐘
				$time = 1800;
				break;
			case 2:	//1小時
				$time = 3600;
				break;
			case 3:	//2小時
				$time = 7200;
				break;
			case 4:	//4小時
				$time = 14400;
				break;
			case 5:	//24小時
				$time = 86400;
				break;
			default:
				alertWindowMsg("未知的探險時間");
				break;
		}
		$time = $time + $delay;
		if(!in_array($type, array(1,2,3,4))) {
			alertWindowMsg ("未知的探險方針");
		}
		//TODO: 檢查成員的結算品是否都處理完畢
		//TODO: 檢查同IP隊友無法出發探險
		$DB_site->query("
			UPDATE wog_expedition
			SET place_id=$place, type=$type, delay_timeline=$delay,
				return_timeline=$time
			WHERE team_id=$this->team_id
		");
		$this->place_id = $place;
		$this->type = $type;
		$this->delay_timeline = $delay;
		$this->return_timeline = $time;
		//JsonView::render(!$delayed);
	}
	public function state() {
		$state = EXPEDITION_PREPARING; 
		if(time() < $this->delay_timeline) {
			$state = EXPEDITION_DELAY;
		} elseif(time() < $this->return_timeline) {
			$state = EXPEDITION_EXPLORING;
		}
		return $state;
	}
}

?>