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
//####### act tool #########
function act_click(a,b,c)
{
	var foot=parent.foot.document.f1;
	if(c==null){c="";}
	foot.f.value=a;
	foot.act.value=b;
	foot.temp_id.value=c;
	foot.action="wog_act.php";
	foot.submit();
};
function foot_turn(a,b,c,d,e)
{
	var foot=parent.foot.document.f1;
	var temp_s="";
	foot.action="wog_act.php";
	foot.temp_id2.value="";
	foot.act.value="";
	foot.f.value="";
	foot.pay_id.value="";
	foot.temp_id.value="";
	foot.f.value=a;
	foot.act.value=b;
	foot.pay_id.value=c;
	foot.temp_id.value=d;
	var s2=",";
	if(e!=null)
	{
		if(e.length!=undefined)
		{
			for(var i=0;i<e.length;i++)
			{
				if(e[i].checked==true)
				{
					temp_s+=s2+e[i].value;
				}
			}
			temp_s=temp_s.substr(1,temp_s.length);
		}else
		{
			temp_s=e.value;
		}
		foot.temp_id2.value=temp_s;
	}
	foot.submit();
};
function act_gclick(a,b,c)
{
	var foot=parent.foot.document.f1;
	if(c==null){c="";}
	foot.f.value=a;
	foot.act.value=b;
	foot.temp_id.value=c;
	foot.action="wog_group.php";
	foot.submit();
};
function foot_gturn(a,b,c,d,e)
{
	var foot=parent.foot.document.f1;
	var temp_s="";
	foot.action="wog_group.php";
	foot.temp_id2.value="";
	foot.act.value="";
	foot.f.value="";
	foot.pay_id.value="";
	foot.temp_id.value="";
	foot.f.value=a;
	foot.act.value=b;
	foot.pay_id.value=c;
	foot.temp_id.value=d;
	var s2=",";
	if(e!=null)
	{
		if(e.length!=undefined)
		{
			for(var i=0;i<e.length;i++)
			{
				if(e[i].checked==true)
				{
					temp_s+=s2+e[i].value;
				}
			}
			temp_s=temp_s.substr(1,temp_s.length);
		}else
		{
			temp_s=e.value;
		}
		foot.temp_id2.value=temp_s;
	}
	foot.submit();
};
function th_submit(b,s,a)
{
	var thisfrom=b;
	thisfrom.temp_id2.value=s;
	thisfrom.temp_id.value=a;
	thisfrom.submit();
};
function CountDown(){
	var now=new Date();
	var foot=UI.set_target;
	now=Date.parse(now)/1000;
	fight_x=parseInt(counts-(now-start_time),10);
	if(fight_x>-1){
		foot.menu.ats1.disabled=true;
		foot.menu.ats1.value = "離冒險"+fight_x+"秒";
		foot.getElementById("con_fight").innerHTML='<font color="#000000">冒險旅程</font>';
		window.setTimeout("CountDown()",500);
	}else{
		foot.menu.ats1.disabled=false;
		//window.clearInterval(count_fight);
		foot.menu.ats1.value = "開始冒險";
		counts=0;
		fight_x=0;
		foot.getElementById("con_fight").innerHTML='<a href="javascript:parent.fight_fast();" accesskey="x"><font color="#000000">快速冒險</font></a>';
	}
};
function cd(s)
{
	if(counts==0)
	{
		start_time=new Date();
		start_time=Date.parse(start_time)/1000;
	}
	counts+=s;
	window.setTimeout("CountDown()",500);
	//count_fight=window.setInterval('CountDown()',500);
};
function inCountDown(){
	var now=new Date();
	now=Date.parse(now)/1000;
	var x=parseInt(counts-(now-start_time),10);
	var f1=parent.wog_view.document.f1;
	if(f1){
		f1.ppp.value = "請等待"+x+"秒";
	}
	if(x>0){
		timerID=setTimeout("inCountDown()", 1300);
		f1.ppp.disabled=true;
	}else{
		f1.ppp.disabled=false;
		f1.ppp.value = "角色登入";
	}
};
function incd(s)
{
	alert("線上人數額滿,請稍後進入");
	start_time=new Date();
	start_time=Date.parse(start_time)/1000;
	setup_cs(s);
	window.setTimeout('inCountDown()',1300);
};
function hidebox(a){
	if(f.getElementById("DivShim"))
	{
		f.getElementById("DivShim").style.display="none";
	}
	if(f.getElementById(a))
	{
		f.getElementById(a).style.display="none";
	}
};
function wog_message_box(a,b,c,d,x1,x2){
	set_window();
	_docWidth=UI.window_w;
	_docHeight=UI.window_h;
	_width=360;
	_height=10;
	var temp_ex="　";
	var window_name="wog_message_box";
	switch (b)
	{
		case 0: //任務信息,作戰內容
			_width=620;
			switch (c) {
				case 1: 
					_width=260;
					a='<center>'+job_s[a]+'</center>';
				break;
				case 2: 
					_width=280;
					a='<div class=b1>'+a+'</div>';
				break;
				case 3: 
					_width=420;
					var s1=a.split(",");
					var r=s1[2].split(":");
					var r_item='';
					switch(r[0])
					{
						case "wp":
							r_item=wp_name[r[1]]+'：'+r[2];
						break;
						case "ex":
							r_item=ex_name[r[1]]+'：'+r[2];
						break;
						case "item":
							r_item=g_item[r[1]]+'：'+r[2];
						break;
					}
					switch(d)
					{
						case 1:
							var n=s1[1].split(":");
							var n2=n[1].split("\|");
							a='<div class=message_box>'+s1[0]+'<br>完成條件 → 生產'+wp_name[n[0]]+'：'+n2[1]+'/'+n2[0]+'<br>任務獎勵 → '+r_item+'</div>';						
						break;
						case 2:
							var n=s1[1].split(":");				
							a='<div class=message_box>'+s1[0]+'<br>完成條件 → 偵查『'+n[0]+'』<br>任務獎勵 → '+r_item+'</div>';						
						break;
						case 3:
							var n=s1[1].split(":");				
							a='<div class=message_box>'+s1[0]+'<br>完成條件 → 攻打『'+n[0]+'』<br>任務獎勵 → '+r_item+'</div>';						
						break;
						case 4:
							var n=s1[1].split(":");
							var n2=n[3].split("\|");			
							a='<div class=message_box>'+s1[0]+'<br>完成條件 → 運輸『'+wp_name[n[2]]+'：'+n2[1]+'/'+n2[0]+'』，至『'+n[0]+'』<br>任務獎勵 → '+r_item+'</div>';						
						break;
					}
				break;
				default:
					a='<div class=message_box>'+a+'</div>';
				break;
			}
			a=a.replace(/&n/g,"<br>");
			temp_ex+='<table width=100% cellspacing="0" cellpadding="0" bgcolor="#000000">';
			temp_ex+='<tr><td >'+a+'</td></tr>';
			temp_ex+='</table>';
		break;
		case 1: //物品類
			var s1=a.split(",");
			switch (c)
			{
				case 1: //點擊彈出武器防具道具
					window_name="wog_item_box";
					if(a!="")
					{
						var arm_view_color="";
						if(s1[25]=="1"){arm_view_color="bgcolor="+nosend;}
						_width=460;
						_height=100;
						temp_ex+='<table bgcolor="'+wog_m_box_color1+'" width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td colspan="4" '+arm_view_color+'>'+s1[0]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td></tr>';
						temp_ex+='<tr><td>'+bf_c(s1[1])+'</td><td>'+bf_c(s1[2])+'</td><td>'+bf_c(s1[3])+'</td><td>'+bf_c(s1[4])+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>職業</td><td>屬性</td><td>洞數</td><td>精鍊</td></tr>';
						temp_ex+='<tr><td>'+s1[19]+'</td><td>'+s_status[s1[21]]+'</td><td>'+s1[22]+'</td><td>'+s1[23]+'</td></tr>';
						temp_ex+=item_detail(s1[5],s1[6],s1[7],s1[8],s1[9],s1[10],s1[11],s1[24]);
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td colspan="2">技能</td><td colspan="2">價格</td></tr>';
						temp_ex+='<tr><td colspan="2">'+s1[12]+'</td><td colspan="2">'+s1[20]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td colspan="4">能力限制</td></tr>';
						temp_ex+='<tr><td colspan="4">'+arm_need_status(s1[14],s1[15],s1[16],s1[17])+'</td></tr>';
					}
					if(s1[13]!="")
					{
						temp_ex+='<tr><td colspan="4">'+s1[13];
						temp_ex+='</td></tr>';
					}
					temp_ex+='</table></td></tr></table>';
				break;
				case 2: //魔石
					if(a!="")
					{
						temp_ex+='<table bgcolor="'+wog_m_box_color1+'" width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td colspan="4">'+s1[11]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td></tr>';
						temp_ex+='<tr><td>'+s1[0]+'</td><td>'+s1[1]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>敏捷</td><td>力量</td><td colspan="2">HP</td></tr>';
						temp_ex+='<tr><td>'+s1[4]+'</td><td>'+s1[5]+'</td><td colspan="2">'+s1[6]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>體質</td><td>智力</td><td>魅力</td><td>信仰</td></tr>';
						temp_ex+='<tr><td>'+s1[7]+'</td><td>'+s1[8]+'</td><td>'+s1[9]+'</td><td>'+s1[10]+'</td></tr></table></td></tr></table>';
					}
				break;
				case 3: //傭兵
					if(a!="")
					{
						temp_ex+='<table bgcolor="'+wog_m_box_color1+'" width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td colspan="5">'+s1[0]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>戰鬥次數</td><td>金錢</td><td>寶物</td><td>經驗</td><td>死亡消耗</td></tr>';
						temp_ex+='<tr><td>'+s1[1]+'</td><td>'+s1[2]+'%</td><td>'+s1[3]+'%</td><td>'+s1[4]+'%</td><td>'+s1[5]+'</td></tr>';
						temp_ex+='</table></td></tr></table>';
					}
				break;
				case 4: //商店用物品資訊
					if(a!="")
					{
						_width=460;
						_height=120;
						temp_ex+='<table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td colspan="4">'+s1[0]+'</td></tr>';
						temp_ex+=item_detail(s1[1],s1[2],s1[3],s1[4],s1[5],s1[6],s1[7],s1[13]);
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>洞數</td><td>精鍊</td><td>技能</td><td>價格</td></tr>';
						temp_ex+='<tr><td>'+s1[10]+'</td><td>'+s1[11]+'</td><td>'+s1[8]+'</td><td>'+s1[12]+'</td></tr>';
						if(s1[9])
						{
							temp_ex+='<tr><td colspan="4">';
							var s2=s1[9].split(";");
							for(var i=0;i<s2.length;i++)
							{
								temp_ex+=" "+s2[i];
							}
							temp_ex+='</td></tr>';
						}
						temp_ex+='</table>';
					}
				break;
			}
		break;
		case 2: //公會內政軍備
			var s1=a.split(",");
			switch (c)
			{
				case 1: //兵種
					if(a!="")
					{
						temp_ex+='<table bgcolor="'+wog_m_box_color1+'" width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>'+wp_name[1]+'</td><td>'+wp_name[2]+'</td><td>'+wp_name[3]+'</td><td>'+wp_name[4]+'</td></tr>';
						temp_ex+='<tr><td>'+s1[0]+'</td><td>'+s1[1]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>'+wp_name[5]+'</td><td>'+wp_name[6]+'</td><td>'+wp_name[7]+'</td><td>'+wp_name[8]+'</td></tr>';
						temp_ex+='<tr><td>'+s1[4]+'</td><td>'+s1[5]+'</td><td>'+s1[6]+'</td><td>'+s1[7]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>'+wp_name[9]+'</td></tr>';
						temp_ex+='<tr><td>'+s1[8]+'</td></tr></table></td></tr></table>';
					}
				break;
				case 2: //資源
					if(a!="")
					{
						temp_ex+='<table bgcolor="'+wog_m_box_color1+'" width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>煤</td><td>木材</td><td>石塊</td><td>石油</td></tr>';
						temp_ex+='<tr><td>'+s1[0]+'</td><td>'+s1[1]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>黃金</td><td>酒</td><td>大麥</td><td>香菸</td></tr>';
						temp_ex+='<tr><td>'+s1[4]+'</td><td>'+s1[5]+'</td><td>'+s1[6]+'</td><td>'+s1[7]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>鐵</td><td>皮毛</td><td>絲線</td><td>珍珠</td></tr>';
						temp_ex+='<tr><td>'+s1[8]+'</td><td>'+s1[9]+'</td><td>'+s1[10]+'</td><td>'+s1[11]+'</td></tr></table></td></tr></table>';
					}
				break;
				case 3: //資源+兵種
						temp_ex+='<table bgcolor="'+wog_m_box_color1+'" width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td colspan="4">據點防禦力 '+s1[24]+'/'+s1[25]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>'+wp_name[1]+'</td><td>'+wp_name[2]+'</td><td>'+wp_name[3]+'</td><td>'+wp_name[4]+'</td></tr>';
						temp_ex+='<tr><td>'+s1[12]+'</td><td>'+s1[13]+'</td><td>'+s1[14]+'</td><td>'+s1[15]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>'+wp_name[5]+'</td><td>'+wp_name[6]+'</td><td>'+wp_name[7]+'</td><td>'+wp_name[8]+'</td></tr>';
						temp_ex+='<tr><td>'+s1[16]+'</td><td>'+s1[17]+'</td><td>'+s1[18]+'</td><td>'+s1[19]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>'+wp_name[9]+'</td><td>'+wp_name[10]+'</td><td>'+wp_name[11]+'</td><td>'+wp_name[12]+'</td></tr>';
						temp_ex+='<tr><td>'+s1[20]+'</td><td>'+s1[21]+'</td><td>'+s1[22]+'</td><td>'+s1[23]+'</td></tr></table></td></tr></table>';

						temp_ex+='<table bgcolor="'+wog_m_box_color1+'" width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>煤</td><td>木材</td><td>石塊</td><td>石油</td></tr>';
						temp_ex+='<tr><td>'+s1[0]+'</td><td>'+s1[1]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>黃金</td><td>酒</td><td>大麥</td><td>香菸</td></tr>';
						temp_ex+='<tr><td>'+s1[4]+'</td><td>'+s1[5]+'</td><td>'+s1[6]+'</td><td>'+s1[7]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>鐵</td><td>皮毛</td><td>絲線</td><td>珍珠</td></tr>';
						temp_ex+='<tr><td>'+s1[8]+'</td><td>'+s1[9]+'</td><td>'+s1[10]+'</td><td>'+s1[11]+'</td></tr></table></td></tr></table>';
				break;
				case 4:
						temp_ex+='<table bgcolor="'+wog_m_box_color1+'" width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td colspan="4">公會名稱</td></tr>';
						temp_ex+='<tr><td colspan="4">'+s1[0]+'</td></tr>';
						temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>人數</td><td>等級</td><td>氣候</td><td>地形</td></tr>';
						temp_ex+='<tr><td>'+s1[1]+'</td><td>'+s1[2]+'</td><td>'+g_weather[s1[3]]+'</td><td>'+g_area_type[s1[4]]+'</td></tr>';
						temp_ex+='</table></td></tr></table>';
				break;
			}
		break;
		case 3: //合成信息
			_width=460;
			_height=120;
			var s2=c.split(",");
			temp_ex+='<table bgcolor="'+wog_m_box_color1+'" width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
			temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td colspan="4">製作「'+s2[0]+'」需要下列物品</td></tr>';
			temp_ex+='<tr><td colspan="4">'+a+'</td></tr>';
			if(s2[9]!="")
			{
				temp_ex+='<tr bgcolor='+tr_bgcolor2+'><td colspan="4">需完成任務 : '+s2[9]+'</td></tr>';
			}
			temp_ex+=item_detail(s2[1],s2[2],s2[3],s2[6],s2[5],s2[7],s2[4],s2[8]);
			temp_ex+='</table></td></tr></table>';
		break;
		case 4: //技能信息
			_width=460;
			_height=120;
			var s1=a.split(",");
			temp_ex+='<table bgcolor="'+wog_m_box_color1+'" width=100% cellspacing="0" cellpadding="0"><tr><td><table width=100% cellspacing="1" cellpadding="1" bgcolor="#000000">';
			temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td colspan="4">「'+s1[0]+'」</td></tr>';
			temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>回合數</td><td>消費SP</td><td>技能說明</td></tr>';
			temp_ex+='<tr><td>'+s1[1]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td></tr>';
			temp_ex+='</table></td></tr></table>';
		break;
		case 5: //進入遊戲後的提示
			window_name="wog_well_box";
			_width=720;
			_height=180;
			a='<div class=well_box>'+a+'</div>';
			temp_ex+='<table width=100% cellspacing="0" cellpadding="0" bgcolor="#000000">';
			temp_ex+='<tr><td >'+a+'</td></tr>';
			temp_ex+='</table>';
		break;
	}
	_docHeight=(_docHeight/3)-(_height/2);
	_docWidth=(_docWidth/2)-(_width/2);
	if(x1 != null && x2 != null)
	{
		var rootEl=f.body;
		if(rootEl.scrollTop > x2)
		{
			x2+=rootEl.scrollTop;
		}
		_docWidth=x1+35;
		_docHeight=x2-10;
	}
	var wog_message_box_html='';
//	wog_message_box_html+='<div style="position:absolute; left: 100; top: 100; Z-INDEX: 1000;width: 100;height:100;"><table><tr><td bgcolor=red>abc</td></tr></table><iframe id="DivShim" src="javascript:false;" scrolling="no" frameborder="0" width=100% height="100%"></iframe></div>';
	wog_message_box_html+='<table border="0" cellspacing="0" cellpadding="0"><tr><td style="width: '+_width+';">';
	wog_message_box_html+='<div style="position:absolute; left: '+_docWidth+'; top: '+_docHeight+'; Z-INDEX: 1000;filter: Alpha(style=0,opacity=0);width: '+_width+';"><iframe id="DivShim" src="javascript:false;" scrolling="no" frameborder="0" ></iframe></div>';
	wog_message_box_html+='<div style="position:absolute; left: '+_docWidth+'; top: '+_docHeight+'; Z-INDEX: 1002;width: '+_width+';">';
	wog_message_box_html+='<table border="0" bgcolor="'+tr_bgcolor2+'" cellspacing="0" cellpadding="1" width=100%>';
	wog_message_box_html+='<tr><td valign="top"><table width=100% border="0"  cellspacing="0" cellpadding="1" >';
	wog_message_box_html+='<tr><td width=88% height="1">　</td><td valign="top" width="54" style="cursor: hand" height="1">';
	wog_message_box_html+='<a href="javascript:parent.hidebox(\''+window_name+'\');" target="mission"><font size="2" color="#FFFFFF">關閉</font></a></td></tr>';
	wog_message_box_html+='<tr><td width=100% bgcolor="#000000" colspan="2" height="100%" valign="top">'+temp_ex+'</td></tr>';
	wog_message_box_html+='<tr><td width=88% height="1">　</td><td valign="top" width="54" style="cursor: hand" height="1">';
	wog_message_box_html+='<a href="javascript:parent.hidebox(\''+window_name+'\');" target="mission"><font size="2" color="#FFFFFF">關閉</font></a></td></tr>';
	wog_message_box_html+='</table></table></div></td></tr></table>';
	f.getElementById(window_name).innerHTML=wog_message_box_html;
	f.getElementById(window_name).style.display="block";
};
function item_detail(a1,a2,a3,a4,a5,a6,a7,a8)
{
	var temp_ex="";
	temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>力量</td><td>智力</td><td>敏捷</td><td>魅力</td></tr>';
	temp_ex+='<tr><td>'+bf_c(a1)+'</td><td>'+bf_c(a2)+'</td><td>'+bf_c(a3)+'</td><td>'+bf_c(a4)+'</td></tr>';
	temp_ex+='<tr bgcolor="'+wog_m_box_color1+'"><td>體質</td><td>信仰</td><td>生命</td><td>HP</td></tr>';
	temp_ex+='<tr><td>'+bf_c(a5)+'</td><td>'+bf_c(a6)+'</td><td>'+bf_c(a7)+'</td><td>'+bf_c(a8)+'</td></tr>';
	return temp_ex;
};
function foot_fire()
{
	w_m('<form action="wog_act.php" method="post" name="menu" target="mission">'+menu_table1+'<tr>');
	w_m('<td valign="top"><table>');
	w_m('<tr><td align="center" bgcolor="#CFD2DC" height="22"><font color="#000000"><div id="con_fight">冒險旅程</div></font></td></tr>');
	w_m('<tr><td><input type="button" value="冒險開始" name="ats1" onClick="parent.ad_view()" accesskey="a"></td></tr>');
	w_m('<tr><td><input type="button" value="角色狀態" onClick="parent.act_click(\'chara\',\'status_view\')" accesskey="s"></td></tr>');				
	w_m('<tr><td><input type="button" value="冠軍狀態" onClick="parent.act_click(\'chara\',\'cp\')" accesskey="o"></td></tr>');
	w_m('</table></td>');
	w_m('<td valign="top"><table>');
	w_m('<tr><td align="center" bgcolor="#CFD2DC" height="22"><font color="#000000">貿易交流</font></td></tr>');
	w_m('<tr><td><input type="button" value="商店街" onClick="parent.select_store()" accesskey="c"></td></tr>');
	w_m('<tr><td><input type="button" value="裝備欄" onClick="parent.arm_select(1)" accesskey="e"></td></tr>');				
	w_m('<tr><td><input type="button" value="住　宿" onClick="parent.act_click(\'store\',\'hotel\')" accesskey="h"></td></tr>');
	w_m('</table></td>');
	w_m('<td valign="top"><table>');
	w_m('<tr><td align="center" bgcolor="#CFD2DC" height="22"><font color="#000000">對象(<a href="javascript:parent.noname()"><font color="#000000">取消</font></a>)</font></td></tr>');
	w_m('<tr><td><input type="text" name="towho" size="4"></td></tr>');
	w_m('<tr><td><input type="button" value="偵查對手" onClick="parent.act_click(\'chara\',\'view2\',document.menu.towho.value)" accesskey="v"></td></tr>');				
	w_m('<tr><td><input type="button" value="銀行倉庫" onClick="parent.act_click(\'arm\',\'depot_list\')" accesskey="d"></td></tr>');
	w_m('</table></td>');
	w_m('<td valign="top"><table>');
	w_m('<tr><td align="center" bgcolor="#CFD2DC" height="22"><font color="#000000">其他</font></td></tr>');
	w_m('<tr><td><input type="button" value="任務手冊" onClick="parent.mission_ed()" accesskey="b"></td></tr>');
	w_m('<tr><td><input type="button" value="技能手冊" onClick="parent.act_click(\'skill\',\'view\')" accesskey="k"></td></tr>');				
	w_m('<tr><td><input type="button" value="情報中心" onClick="parent.act_click(\'system\',\'view1\')" accesskey="q"></td></tr>');
	w_m('</table></td>');
	w_m('</tr>'+temp_table2+'</form>');
	var a=Gookie("wog_setui");
	setUI(parseInt(a));
	switch (UI.set_frame)
	{
		case 1:
			dfoot=f;
			p_m();
			UI.set_target=f;
			break;
		case 2:
			dfoot=parent.foot.document;
			w_m('<table cellspacing="0" cellpadding="0" align="center"><tr><td valign="top" align="center" ><b><a href="http://www.et99.net/viewforum.php?f=36" target="_blank">WOG</a> V4 Copyright (C) <a href="http://www.et99.net" target="_blank">ETERNAL</a></b></td></tr></table>');
			p_m();
			UI.set_target=dfoot;
			break;
		default:
			setUI(1);
			dfoot=parent.foot.document;
			w_m('<table cellspacing="0" cellpadding="0" align="center"><tr><td valign="top" align="center" ><b><a href="http://www.et99.net/viewforum.php?f=36" target="_blank">WOG</a> V4 Copyright (C) <a href="http://www.et99.net" target="_blank">ETERNAL</a></b></td></tr></table>');
			p_m();
			UI.set_target=dfoot;
			break;
	}
	Drag.init(f.getElementById("wog_menu"));
};
function open_chat(team_id)
{
	if(UI.set_frame==1)
	{
		w_chat(chat_table1+'<tr><td><b>Online FF Battle - <a href="http://www.et99.net/wog4/index.htm" target="_blank">WOG</a> V4 Copyright (C) <a href="http://www.et99.net" target="_blank">ETERNAL</a></b></td><td><table align="right"><tr><td><a href="javascript:parent.chat_resize()" style="text-decoration:none;">↗</a></td></tr></table></td></tr><tr><td width="520" height="150" id="chat_table" colspan="2" ><iframe id="frame_chat" name="frame_chat" src="./wog_chat/wog_chat.php" scrolling="no" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%"></iframe></td></tr>'+temp_table2);
	}else
	{
		w_chat(chat_table1+'<tr><td width="620" height="148" id="chat_table" ><iframe id="frame_chat" name="frame_chat" src="./wog_chat/wog_chat.php" scrolling="no" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%"></iframe></td></tr>'+temp_table2);
	}
	p_chat();
	Drag.init(f.getElementById("wog_chat"));
	if(return_well_box()=="")
	{
		act_click('chara','well');
	}
};
function chat_resize(){
	switch (chat_size)
	{
		case 1:
			f.getElementById("chat_table").width=560;
			f.getElementById("chat_table").height=150;
			chat_size=2;
			break;
		case 2:
			f.getElementById("chat_table").width=620;
			f.getElementById("chat_table").height=180;		
			chat_size=3;
			break;
		case 3:
			f.getElementById("chat_table").width=650;
			f.getElementById("chat_table").height=280;
			chat_size=4;
			break;
		case 4:
			f.getElementById("chat_table").width=520;
			f.getElementById("chat_table").height=100;
			chat_size=1;
			break;
	}
};
function msg_to_chat(a1)
{
	var temp_f=null;
	if (UI.set_frame==1) {
		temp_f=wog_view.frame_chat;
	}
	else
	{
		temp_f=foot.frame_chat;
	}
	temp_f.goldset('系統','0','<font color=red>'+a1+'</font>','','');
};
function Sookie(name, value) {
	document.cookie = name + "=" + value
};
function Gookie(name) {
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while (i < clen) {
		var j = i + alen;
		if (document.cookie.substring(i, j) == arg)
			return GookieVal (j);
		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0) break;
	}
	return null;
};
function GookieVal(offset) {
	var endstr = document.cookie.indexOf (";", offset);
	if (endstr == -1)
		endstr = document.cookie.length;
	return document.cookie.substring(offset, endstr);
};

function srhCount(srhStr)
{
	var tmpData=vData.split(",");
	var tmpResult=0;
	tmpNum="";
	var tempvdata="";
	for(var i=0;i<tmpData.length;i++)
	{
		if(tmpData[i].indexOf("*") > 0 || tmpData[i].indexOf(":") > 0)
		{
			if(tmpData[i].indexOf("*") > 0)
			{
				var s1=tmpData[i].split("*");
			}
			if(tmpData[i].indexOf(":") > 0)
			{
				var s1=tmpData[i].split(":");
			}
			if(s1[0]==srhStr)
			{
				tmpResult=tmpResult+1;
				tmpNum=tmpNum+","+s1[1];
			}else
			{
				tempvdata=tempvdata+","+tmpData[i];
			}
		}else
		{
			if(tmpData[i]==srhStr)
			{
				tmpResult=tmpResult+1;
			}else
			{
				tempvdata=tempvdata+","+tmpData[i];
			}
		}
	}
	vData=tempvdata.substr(1,tempvdata.length);
	if(tmpNum != "")
	{
		tmpNum=tmpNum.substr(1,tmpNum.length);
	}
	return tmpResult;
};
function bf_c(a1)
{
	if(a1.indexOf("+") >-1)
	{
		var s1=a1.split("+");
		a1=s1[0]+buff1+s1[1]+buff2;
	}
	return a1;
}
function yesname(s)
{
	UI.set_target.menu.towho.value=s;
};
function noname() {
	UI.set_target.menu.towho.value='';
};
function setUI(a)
{
	switch(a)
	{
		case 1:
			UI.set_frame=2;
			UI.set_rows="0,39,*,0,0,153,0";
			Sookie("wog_setui",1);
		break;
		case 2:
			Sookie("wog_setui",2);
			UI.set_frame=1;
			UI.set_rows="0,39,*,0,0,0,0";
			UI.set_center_w="850";
			UI.set_fight_w="600";
		break;
		default:
			Sookie("wog_setui",1);
		break;
	}
};
function set_well_box(a1)
{
	var a=Gookie("wog_setwell");
	if(a=="1")
	{
		Sookie("wog_setwell",0);
	}else
	{
		Sookie("wog_setwell",1);
	}
};
function return_well_box()
{
	var a=Gookie("wog_setwell");
	if(a=="1")
	{
		return "checked";
	}else
	{
		return "";
	}
};
function checkAll(name) 
{
	var el = f.getElementsByTagName('input'); 
	var len = el.length;
	for(var i=0; i<len; i++)
	{
		if((el[i].type=="checkbox") && (el[i].name==name))
		{
			if(el[i].checked == true)
			{
				el[i].checked = false;
			}else
			{
				el[i].checked = true;
			}
		}
	}
};
function get_towho()
{
	return UI.set_target.menu.towho.value;
};
function btn_disabled(a1)
{
	f.getElementById(a1).disabled=true;
	window.setTimeout("btn_not_disabled('"+a1+"')",1500);
};
function btn_not_disabled(a1)
{
	f.getElementById(a1).disabled=false;
};
function set_arm_tochat(a1,a2)
{
	var temp_f=null;
	if (UI.set_frame==1) {
		temp_f=parent.wog_view.frame_chat.chat_in.document.f1;
	}
	else
	{
		temp_f=parent.foot.frame_chat.chat_in.document.f1;
	}
	if(!temp_f){return;}
	var temp=temp_f.chat_temp.value;
	var s_num=temp.indexOf("[");
	var e_num=temp.indexOf("]");
	if(s_num>-1 && e_num>0)
	{
		var temp1=temp.substr(0,s_num);
		var temp2=temp.substr(e_num+1,temp.length-e_num);
		temp=temp1+temp2;
	}
	temp_f.chat_temp.value=temp+"["+a1+"]";
	temp_f.item_id_temp.value=a2;
};
function addBookmarkForBrowser(sTitle, sUrl)
{

    if (window.sidebar && window.sidebar.addPanel) {
        addBookmarkForBrowser = function(sTitle, sUrl) {
            window.sidebar.addPanel(sTitle, sUrl, "");
        }
    } else if (window.external) {
        addBookmarkForBrowser = function(sTitle, sUrl) {
            window.external.AddFavorite(sUrl, sTitle);
        }
    } else {
        addBookmarkForBrowser = function() {
            alert("do it yourself");
        }
    }

    return addBookmarkForBrowser(sTitle, sUrl);
};
function set_window()
{
	var rootEl=f.body;
	_docHeight=((rootEl.scrollTop==0)?rootEl.clientHeight:rootEl.scrollHeight);
	_docWidth=((rootEl.scrollLeft==0)?rootEl.clientWidth:rootEl.scrollWidth);
	UI.window_w=_docWidth;
	UI.window_h=_docHeight;
};
function set_div_x_y(x,y,a1)
{
	var rootEl=f.body;
	if(rootEl.scrollTop > y)
	{
		y+=rootEl.scrollTop;
	}
	f.getElementById(a1).style.left=x;
	f.getElementById(a1).style.top=y;
	f.getElementById(a1).style.display="block";
};
function mouse_xy(x,y)
{
	if(x!=undefined){UI.mouse_x=x;}
	if(y!=undefined){UI.mouse_y=y;}
};
function gshow_item_lay()
{
	set_window();
	x=UI.window_w/2-(350/2);
	y=UI.window_h/2;
	p_s(x,y,480);
};
function show_item_lay()
{
	set_window();
	x=UI.window_w/2-(350/2);
	y=UI.window_h/2;
	p_as(x,y,480);
};