<?php
require_once 'wog_team.php';
class wog_player {
	private $p = null;
	private $t_id = 0;
	public $team = null;
	static public function get($id) {
		global $DB_site;
		$id = intval($id, 10);
		$p = $DB_site->query_first("
			SELECT p_id, p_money, t_id
			FROM wog_player
			WHERE p_id=$id
		");
		if(!!$p) {
			return new wog_player($p);
		} else {
			return null;
		}
	}
	public function __construct($p) {
		$this->p = $p;
		$this->p_money = +$p["p_money"];
		if($p["t_id"] > 0) {
			$this->team = wog_team::get($p["t_id"]);
		}
	}
	
	public function __set($name, $value) {
		if(isset($this->p[$name])) {
			$this->p[$name] = $value;
		}
	}
	public function __get($name) {
		if(isset($this->p[$name])) {
			return $this->p[$name];
		} else {
			return null;
		}
	}
	
	public function donate($target, $money) {
		global $DB_site;
		if($this->p_money < $money) {
			alertWindowMsg ("現金不足");
		}
		switch($target) {
			case 'expedition':
				$this->team->expedition->donate($this->p_id, $money);
				$this->p_money -= $money;
				break;
			case 'guild':
				
				break;
			default:
				alertWindowMsg("未知的捐獻對象");
				break;
		}
		
		$DB_site->query("
			UPDATE wog_player
			SET p_money = $this->p_money
			WHERE p_id=$this->p_id
		");
	}
	public function inTeam() { return !is_null($this->team);}
}

?>