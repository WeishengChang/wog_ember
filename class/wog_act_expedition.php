<?php
require_once 'JsonView.php';
require_once 'wog_expedition.php';
require_once 'wog_team.php';
require_once 'wog_player.php';

class wog_act_expedition {
	//探險隊系統
	public function donate($user_id) {
		$p = wog_player::get($user_id);
		if(is_null($p)) {
			alertWindowMsg("無法辨識的玩家資訊");
		}
		if(!$p->inTeam()) {
			alertWindowMsg("沒有隊伍");
		}
		$money = filter_input(INPUT_POST, 'money', FILTER_VALIDATE_INT);
		if(is_null($money)) {
			alertWindowMsg("不正確的捐獻金額格式");
		}
		$p->donate('expedition', $money);
		//$fund = $team->expedition->donate($user_id, intval($_POST["money"], 10));
		JsonView::render($p->team->expedition->fund());
	}
	
	public function pause($user_id) {
		$p = wog_player::get($user_id);
		if(is_null($p)) {
			alertWindowMsg("無法辨識的玩家資訊");
		}
		if(!$p->inTeam()) {
			alertWindowMsg("沒有隊伍");
		}
		if(!$p->team->isLeader($user_id)) {
			alertWindowMsg("只有隊長有終止探險的權限");
		}
		$p->team->expedition->pause();
		JsonView::render(1);
	}
	
	public function show($user_id) { /** 顯示探險隊狀態 **/
		$p = wog_player::get($user_id);
		if(is_null($p)) {
			alertWindowMsg("無法辨識的玩家資訊");
		}
		if(!$p->inTeam()) {
			alertWindowMsg("沒有隊伍");
		}
		$result = array(
			$p->team->isLeader($user_id), 
			$p->team->expedition->state(), 
			$p->team->expedition->fund()
		);
		if($result[1] != EXPEDITION_PREPARING) {
			$result = array_merge($result, array(
				$p->team->expedition->place_id,
				$p->team->expedition->type,
				$p->team->expedition->delay_timeline,
				$p->team->expedition->return_timeline
			));
		}
		JsonView::render($result);
	}
	
	public function start($user_id) {
		$p = wog_player::get($user_id);
		if(is_null($p)) {
			alertWindowMsg("無法辨識的玩家資訊");
		}
		if(!$p->inTeam()) {
			alertWindowMsg("沒有隊伍");
		}
		if(!$p->team->isLeader($user_id)) {
			alertWindowMsg("只有隊長能派遣探險隊");
		}
		$place = filter_input(INPUT_POST, 'place', FILTER_VALIDATE_INT);
		$delay = filter_input(INPUT_POST, 'delay', FILTER_VALIDATE_INT);
		$time = filter_input(INPUT_POST, 'time', FILTER_VALIDATE_INT);
		$type = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT);
		if(is_null($place) || is_null($delay) || is_null($time) || is_null($type)) {
			alertWindowMsg("未知的錯誤");
		}
		$p->team->expedition->start($place, $type, $time, $delay);
		JsonView::render(!$p->team->expedition->isDelayed());
	}
}
?>
