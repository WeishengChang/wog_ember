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
//Expedition
var wog = {
	"postAJAX": function(f, act, data, callback) {
		if($.isFunction(data)) {
			callback = data;
			data = {"f": f, "act": act};
		} else {
			data.f = f;
			data.act = act;
		}
		if(!$.isFunction(callback)) callback = $.noop;
		$.ajax({
			"url": "wog_act.php",
			"type": "POST",
			"data": data,
			"dataType": "JSON",
			"dataFilter": (function() {
				var expr = [
					/^[\],:{}\s]*$/,
					/\\["\\\/bfnrtu]/g,
					/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
					/(?:^|:|,)(?:\s*\[)+/g
				];
				return function(data) {
					//檢查內容是否符合JSON規則
					if(!expr[0].test(data.replace(expr[1], '@').replace(expr[2], ']').replace(expr[3], ''))) {
						$(parent.mission.document.body).html(data);
						data = "";
					}
					return data;
				}
			})()
		}).done(callback);
	}
};
function expedition() {}
expedition.donate = function() {
	var $body = $(f.getElementById("wog_center"));
	var $donation = $body.find("#donation");
	if($donation.length > 0) {
		wog.postAJAX("expedition", "donate", {"money": $donation.val()}, function(json) {
			var $fund = $body.find("#expedition_money");
			if($fund.length > 0) {
				$fund.text(""+json);
			}
			alert("捐獻成功");
		});
	}
};
expedition.pauseSuccess = function() {
	var $body = $(f.getElementById("wog_center"));
	$body.load("template/wog_expedition.html #expedition_pause_success", function() {
		$body.find("a.back").on("click", parent.team_view);
	});
};
expedition.setup = function(type, $target, value) {
	switch(type) {
		case "place":
			$target.text(sec[+value][0]);
			break;
		case 'type':
			switch(+value) {
				case 2:	$target.text("討伐");		break;
				case 3:	$target.text("開採資源");	break;
				case 4:	$target.text("調查");		break;
				default:$target.text("探險");		break;
			}
			break;
	}
	return this;
};
expedition.show = function() {
	wog.postAJAX("expedition", "show", function(json) {
		var $body = $(f.getElementById("wog_center"));
		if(json[0] == false && json[1] == 0) {
			$body.load("template/wog_expedition.html #expedition_none", function() {
					$(this).find('a.back').on("click", parent.team_view);
				});
			return;
		}
		switch(json[1]) {
			case 0:	//preparing
				$body.load("template/wog_expedition.html #expedition_prepare", function() {
					$(this)
						.find("#expedition_money").text(json[2]).end()
						.find('a.back').on("click", parent.team_view).end()
						.find("#donate").on("click", expedition.donate).end()
						.find("#start").on("click", function() {
							var data = {
								"place": $body.find('#expedition_place').val(),
								"delay": $body.find("#expedition_delay_timeline").val(),
								"time": $body.find("#expedition_return_timeline").val(),
								"type": $body.find("#expedition_type").val()
							};
							wog.postAJAX("expedition", "start", data, expedition.startSuccess);
					});
				});
				break;
			case 1:	//delay
				$body.load("template/wog_expedition.html #expedition_delay", function() {
					if(!json[0]) {
						$body.find("#pause").hide();
					}
					$(this)
						.find("#expedition_money").text(json[2]).end()
						.find("#start_time").text(function() {
							var $text = $body.find("#start_time");
							var now = new Date().getTime()/1000;
							var start = Math.floor(+json[5] - now);
							switch(true) {
								case start < 60:
									return "即將出發";
								case start < 180:
									return "3分鐘內";
								case start < 300:
									return "5分鐘內";
								case start < 600:
									return "10分鐘內";
								case start < 900:
									return "15分鐘內";
								case start < 1800:
									return "30分鐘內";
								default:
									return "超過30分鐘";
							}
						}).end()
						.find("#place").text(function() {
							return sec[+json[3]][0];
						}).end()
						.find('a.back').on("click", parent.team_view).end()
						.find("#donate").on("click", expedition.donate).end()
						.find("#pause").on("click", function() {
							if(!confirm("確定終止探險？")) return;
							wog.postAJAX("expedition", "pause", expedition.pauseSuccess);
					});
					expedition.setup("type", $body.find("#type"), json[4]);
				});
				break;
			case 2:	//exploring
				$body.load("template/wog_expedition.html #expedition_exploring", function() {
					expedition.setup("type", $body.find("#type"), json[4])
							.setup("place", $body.find("#place"), json[3]);
					$body.find("a.back").on("click", parent.team_view);
				});
				
				break;
			case 3:	//resulting
				
				break;
		}
	});
};
expedition.startSuccess = function(json) {
	var $body = $(f.getElementById("wog_center"));
	switch(json) {
		case true:
			$body.load("template/wog_expedition.html #expedition_start_success", function() {
				$body.find("a.back").on("click", parent.team_view);
			});
			break;
		case false:
			$body.load("template/wog_expedition.html #expedition_start_delay", function() {
				$body.find("a.back").on("click", parent.expedition.show);
			});
			break;
	}
	
}
//##### team  #######
function team_view()
{
	w_c('<form action="wog_act.php" method="post" target="mission">');	
	w_c(temp_table1);
	w_c('<tr><td><input type="button" value="隊伍列表" onClick="parent.act_click(\'team\',\'list\')"></td></tr>');
	w_c('<tr><td><input type="button" value="隊伍狀態" onClick="parent.act_click(\'team\',\'status\')"></td></tr>');
	w_c('<tr><td><input type="button" value="創立隊伍" onClick="parent.team_creat();"></td></tr>');
	w_c('<tr><td><input type="button" value="離開隊伍" onClick="parent.act_click(\'team\',\'leave\')"></td></tr>');
	w_c('<tr><td><input type="button" value="認領隊員" onClick="parent.act_click(\'team\',\'get_member\')"></td></tr>');
	w_c('<tr><td><input type="button" value="編列探險隊" onClick="parent.expedition.show()"></td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function team_list(saletotal,page,s)
{
	
	w_c('<form action="wog_act.php" method="post" name=pageform target="mission">');
	pagesplit(saletotal,page);
	w_c('<input type="hidden" name="page" value="">');
	w_c('<input type="hidden" name="f" value="team">');
	w_c('<input type="hidden" name="act" value="list">');
	w_c('</form>');
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	if(s!="")
	{
		w_c('<tr><td></td><td>隊長暱稱</td><td>隊長等級</td><td>隊伍名稱</td><td>人數</td></tr>');
		var s1=s.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			w_c('<tr><td ><input type="radio" name="t_id" value="'+s2[0]+'" ></td><td >'+s2[3]+'</td><td >'+s2[4]+'</td><td >'+s2[1]+'</td><td >'+s2[2]+'</td></tr>');
		}
		var dbsts_join="";
		var dbsts_leave="";
		if(t_team!="")
		{
			dbsts_join="disabled";
		}else
		{
			w_c('<input type="hidden" name="f" value="team"><input type="hidden" name="act" value="join">');
		}
		w_c('<tr><td colspan="5" align="center"><input type="submit" value="加入隊伍"  '+dbsts_join+' ></td></tr>');
	}else
	{
		w_c('<tr><td colspan="4" align="center">目前無隊伍</td></tr>');
	}
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function team_creat()
{
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td>隊伍名稱 <input type="text" name="t_name" size="16" maxlength="20"> <input type="button" value="決定" onClick="parent.act_click(\'team\',\'creat\',this.form.t_name.value)"> </td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function team_status(s,time_line,support,my_id,adm)
{
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td>選擇</td><td>名稱</td><td>等級</td><td>狀態</td></tr>');
	var s1=s.split(";");
	if(s!="")
	{
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var chk="";
			var dis="";
			var dis2="";
			if(s2[0]==support)
			{
				chk="checked";
			}
			if(s2[0]==my_id)
			{
				dis="disabled";
			}
			if(adm=="0")
			{
				dis2="disabled";
			}
			if( (time_line-parseInt(s2[3])) < 600 )
			{
				w_c('<tr><td ><input type="radio" name="p_id" value="'+s2[0]+'" '+chk+' '+dis+'></td><td >'+s2[1]+'</td><td >'+s2[2]+'</td><td >在線</td></tr>');
			}else
			{
				w_c('<tr bgcolor="#474747"><td ><input type="radio" name="p_id" value="'+s2[0]+'" '+chk+' '+dis+'></td><td >'+s2[1]+'</td><td >'+s2[2]+'</td><td >離線</td></tr>');
			}
		}
		w_c('<tr><td colspan="4" align="center"><input type="button" value="加入隊頻" onClick="parent.act_click(\'team\',\'chat\')">  <input type="button" value="踢除隊員" onClick="parent.foot_turn(\'team\',\'del\',null,null,this.form.p_id)" '+dis2+'></td></tr>');
	}
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function team_join_list(s)
{
	w_c('<form action="wog_act.php" method="post" target="mission">');	
	w_c(temp_table1);
	w_c('<tr><td></td><td>內容</td><td>等級</td><td>職業</td></tr>')
	if(s!="")
	{
		var s1=s.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			w_c('<tr><td><input type="radio" name="t_j_id" value="'+s2[0]+'" ></td><td>'+s2[1]+' 提出加入隊伍申請</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td></tr>');
		}
		w_c('<tr><td colspan="4" align="center"><input type="button" value="邀請加入" onClick="parent.foot_turn(\'team\',\'get_save_member\',null,null,this.form.t_j_id)" ></td></tr>');
	}else
	{
		w_c('<tr><td colspan="5" align="center">沒有玩家申請加入隊伍</td></tr>');
	}
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
