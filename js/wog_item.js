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
//##### hole #####
function hole_view(a,b,c,d,e)
{
	p_s_close();
	var i=0;
	var s2 = "";
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	arm_link();
	w_c(left_table1);
	var s1=a.split(",");
	var temp_id= s1[0];
	w_c('<tr class="head_td"><td colspan="4">'+s1[1]+'(<a href=javascript:parent.hole_make("'+temp_id+'")>打洞</a>)</td></tr>');
	w_c('<tr><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td></tr>');
	w_c('<tr><td>'+s1[4]+'</td><td>'+s1[5]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td></tr>');
	w_c('<tr class="head_td"><td colspan="4">請選擇要鑲嵌的洞</font></td></tr>');
	var hole_count=parseInt(s1[6]);
	if(hole_count > 0)
	{
		if(c != "")
		{
			s1 = c.split(";");
			for(i=0;i<s1.length;i++)
			{
				var j=i+1;
				if(s1[i] != "0")
				{
					s2=s1[i].split(",");
					w_c('<tr><td><input type="radio" name="hole" value="'+j+'">洞'+j+'</td><td colspan="2"><a class=uline onclick=parent.arm_show(event.ctrlKey,"'+s2[1]+'","'+s2[0]+'")>'+s2[1]+'</a></td><td><a href=javascript:parent.hole_remove(\''+temp_id+','+e+','+j+'\')>分離</a></td></tr>');
				}
				else
				{
					w_c('<tr><td><input type="radio" name="hole" value="'+j+'">洞'+j+'</td><td colspan="2"></td><td></td></tr>');
				}
			}
		}
		else
		{
			for(i=0;i<hole_count;i++)
			{
				w_c('<tr><td><input type="radio" name="hole" value="'+(i+1)+'">洞'+(i+1)+'</td><td colspan="2"></td><td></td></tr>');
			}
		}
	}
	w_c('<tr><td colspan="4" class=b1><ol><li>分離需要消費一顆分離魔石</li><li>打洞需要消費一顆打孔石</li></ol></td></tr>');
	w_c(temp_table2);
	w_c(right_table1);
	w_c('<tr class="head_td"><td colspan="4">請選擇要鑲入的魔石</td></tr>');
	if(b != '')
	{
		s1=b.split(";");
		vData=d;
		for(i=0;i<s1.length;i++)
		{
			s2=s1[i].split(",");
			w_c('<tr><td><input type="radio" name="stone" value="'+s2[0]+'"></td><td colspan="3"><a class=uline onclick=parent.arm_show(event.ctrlKey,"'+s2[1]+'","'+s2[0]+'")>'+s2[1]+'</a></td></tr>');
		}
		w_c('<tr><td colspan="4"><input type="submit" value="確認鑲入"></td></tr>');
	}
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="hole">');
	w_c('<input type="hidden" name="act" value="setup">');
	w_c('<input type="hidden" name="temp_id" value="'+temp_id+'">');
	w_c('<input type="hidden" name="temp_id_2" value="'+e+'">');
	w_c('</form>');
	p_c();
};
function hole_remove(a1)
{
	w_c(temp_table1);
	w_c('<tr class="head_td"><td></td><td>名稱</td><td>效果</td></tr>');
	w_c('<tr><td><input type="radio" name="id" value="1"></td><td>分離魔石LV1</td><td>分離成功率25%，失敗時會使魔石粉碎</td></tr>');
	w_c('<tr><td><input type="radio" name="id" value="2"></td><td>分離魔石LV2</td><td>分離成功率25%，失敗時魔石不會受到損壞</td></tr>');
	w_c('<tr><td colspan="3"><input type="submit" value="分離" > | <input type="button" value="關閉" onClick="parent.p_s_close();" ></td></tr>');
	w_c('<input type="hidden" name="f" value="hole">');
	w_c('<input type="hidden" name="act" value="remove">');
	w_c('<input type="hidden" name="temp_id" value="'+a1+'">');
	w_c('<input type="hidden" name="temp_id2" value="">');
	var _t="document.f101";
	w_c('<tr><td colspan="3">分離魔石可至 <a class="uline" onClick="'+_t+'.f.value=\'shop\';'+_t+'.act.value=\'view\';parent.th_submit(document.f101,1,\'5\')">【道具店購買】</a>或 <a class="uline" onClick="parent.act_click(\'mall\',\'view\')">【加值商城購買】</a></td></tr>');
	w_c(temp_table2);
	show_item_lay();
};
function hole_make(a1)
{
	w_c(temp_table1);
	w_c('<tr class="head_td"><td></td><td>名稱</td><td>效果</td></tr>');
	w_c('<tr><td><input type="radio" name="id" value="1"></td><td>劣質打孔石</td><td>8%機率打洞成功</td></tr>');
	w_c('<tr><td><input type="radio" name="id" value="2"></td><td>打孔石</td><td>100%打洞成功</td></tr>');
	w_c('<tr><td colspan="3"><input type="submit" value="打洞" > | <input type="button" value="關閉" onClick="parent.p_s_close();" ></td></tr>');
	w_c('<input type="hidden" name="f" value="hole">');
	w_c('<input type="hidden" name="act" value="mh">');
	w_c('<input type="hidden" name="temp_id" value="'+a1+'">');
	var _t="document.f101";
	w_c('<tr><td colspan="3">劣質打孔石可至 <a class="uline" onClick="parent.honor_view(null)">【勳章所購買】</a>或 打孔石至<a class="uline" onClick="parent.act_click(\'mall\',\'view\')">【加值商城購買】</a></td></tr>');
	w_c(temp_table2);
	show_item_lay();
};
//#### arm begin ####
function arm_need_status(s1,s2,s3,s4)
{
	var temp="";
	s1=parseInt(s1);
	s2=parseInt(s2);
	s3=parseInt(s3);
	s4=parseInt(s4);
	if(s1 > 0){
		temp+=" 力:"+s1;
		if(s1 > base_str){temp=noset+temp+buff2;}
	}
	if(s3 > 0){
		temp+=" 智:"+s3;
		if(s3 > base_smart){temp=noset+temp+buff2;}
	}
	if(s2 > 0){
		temp+=" 敏:"+s2;
		if(s2 > base_agi){temp=noset+temp+buff2;}
	}
	if(s4 > 0){
		temp+=" 魅:"+s4;
		if(s4 > base_au){temp=noset+temp+buff2;}
	}
	if(temp!="")
	{
		temp=temp.substring(1,temp.length);
	}
	else
	{
		temp="-------";
	}
	return temp;
};
function arm_view(a,temp_id,b,c1)
{
	// temp_id編號 -> 0武器,1頭,2身體,3手,4腳,5道具,6道具,7魔石,8勳章,9鑰匙,10精練石
	var sum=0;
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	arm_link(temp_id);
	w_c(temp_table1);
	if(temp_id != 7)
	{
		w_c('<tr class="head_td"><td>裝備/轉移</td><td>名稱</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>職業</td><td>屬性</td><td>能力限制</td><td>洞數</td><td>價格</td><td>(<a class="uline" onclick="parent.checkAll(\'item_list[]\');">全</a>)販賣/拍賣</td></tr>');
	}
	else
	{
		w_c('<tr class="head_td"><td>轉移</td><td>名稱</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>敏捷</td><td>力量</td><td>生命</td><td>體質</td><td>智力</td><td>魅力</td><td>信仰</td><td>HP</td><td>價格</td><td>販賣/拍賣</td></tr>');
	}
	if(a != "")
	{
		var temp_form="document.f1";
		var s1=a.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var hole_setup_string="0";
			sum++;
			if(s2[24]!="0"){hole_setup_string='<a href="javascript:parent.act_click(\'hole\',\'view\',\''+s2[1]+'\')">'+s2[24]+'</a>';}

			var arm_view_color ="";
			if(s2[20]=="1"){arm_view_color="bgcolor="+nosend;}
			if (temp_id != 7)
			{
				var temp_sale_str ='';
				if(temp_id<5)
				{
					temp_sale_str ='<input type="checkbox" name="item_list[]" value="'+s2[1]+'">/';
				}else
				{
					s2[1]=s2[0];
				}
				var temp_message=s2[2]+","+s2[7]+","+s2[8]+","+s2[9]+","+s2[10]+","+s2[11]+","+s2[12]+","+s2[13]+","+s2[14]+","+s2[15]+","+s2[24]+","+s2[25]+","+s2[22]+","+s2[26];
				if(temp_id ==5)
				{
					var s3=s2[2].split("*");
					var s4=s3[0]+'*'+'<span id="show_message_'+s2[1]+'">'+s3[1]+'</span>';					
				}else
				{
					var s4=s2[2];
				}
				w_c('<tr><td>'+sum+'. <input type="radio" name="adds" value="'+s2[1]+'"></td><td '+arm_view_color+'><a class="uline" onmouseover=parent.wog_message_box("'+temp_message+'",1,4,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\'); onclick="parent.arm_show(event.ctrlKey,\''+s2[2]+'\',\''+s2[1]+'\');" target="mission">'+s4+'</a></td><td>'+bf_c(s2[3])+'</td><td>'+bf_c(s2[4])+'</td><td>'+bf_c(s2[5])+'</td><td>'+bf_c(s2[6])+'</td><td>'+s2[21]+'</td><td>'+s_status[s2[23]]+'</td><td>'+arm_need_status(s2[16],s2[17],s2[18],s2[19])+'</td><td>'+hole_setup_string+'</td><td>'+s2[22]+'</td><td>'+temp_sale_str+'<input type="radio" name="items" value="'+s2[1]+','+s2[2]+'"></td></tr>');
			}
			else
			{
				if(s2[13]=="1"){arm_view_color="bgcolor="+nosend;}
				w_c('<tr><td>'+sum+'. <input type="radio" name="adds" value="'+s2[0]+'"></td><td '+arm_view_color+'>'+s2[14]+'</td><td>'+s2[1]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+s2[5]+'</td><td>'+s2[6]+'</td><td>'+s2[7]+'</td><td>'+s2[8]+'</td><td>'+s2[9]+'</td><td>'+s2[10]+'</td><td>'+s2[11]+'</td><td>'+s2[16]+'</td><td>'+s2[15]+'</td><td><input type="radio" name="items" value="'+s2[0]+','+s2[14]+'"></td></tr>');
			}
		}
	}
	if(b != "")
	{
		s1=b.split(";");
		for(var i=0;i<s1.length;i++)
		{
			s2=s1[i].split(",");
			var arm_unsetup="";
			if(s2[21]=="11")
			{
				arm_unsetup = '<a href="javascript:parent.act_click(\'arm\',\'unsetup\',\'11\')">卸下</a>';
			}else
			{
				arm_unsetup = '<a href="javascript:parent.act_click(\'arm\',\'unsetup\',\''+temp_id+'\')">卸下</a>';
			}
			var temp_message=s2[0]+","+s2[5]+","+s2[6]+","+s2[7]+","+s2[8]+","+s2[9]+","+s2[10]+","+s2[11]+","+s2[12]+","+s2[13]+","+s2[18]+","+s2[19]+","+s2[16]+","+s2[22];
			w_c('<tr bgcolor="#232323"><td>'+arm_unsetup+'</td><td><font color="#FF0000">E</font> <a class="uline" onmouseover=parent.wog_message_box("'+temp_message+'",1,4,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\'); onclick="parent.arm_show(event.ctrlKey,\''+s2[0]+'\',\''+s2[20]+'\');" target="mission">'+s2[0]+'</a></td><td>'+bf_c(s2[1])+'</td><td>'+bf_c(s2[2])+'</td><td>'+bf_c(s2[3])+'</td><td>'+bf_c(s2[4])+'</td><td>---</td><td>'+s_status[s2[17]]+'</td><td>---</td><td>---</td><td>'+s2[16]+'</td><td></td></tr>');			
		}
	}
	w_c('<tr><td colspan="15" >請選擇數量(1-99):<input type="text" name="item_num" size="2" maxlength="2" value="1"></td></tr>');
	w_c('<tr><td colspan="16" >');
	if(temp_id <=5)
	{
		w_c('<input type="submit" value="裝備" onclick="parent.mouse_xy(event.x||event.pageX,event.y||event.pageY)" onmouseout=parent.hidebox(\'show_message\');>');
	}
	w_c(' <input type="submit" value="轉移至倉庫" onClick="document.f1.act.value=\'depot_add\'"> <input type="submit" value="販賣" onClick="document.f1.act.value=\'sale\'"> <input type="button" value="拍賣" onClick="parent.sale_item(document.f1.items,document.f1.item_num.value)">('+sum+'/'+c1+')</td></tr>');
	w_c('<tr><td colspan="16" >欲轉移需輸入對方遊戲的帳號 <input type="text" name="pay_id" size="16"> <input type="submit" value="轉移給對方" onClick="document.f1.act.value=\'move\'"></td></tr>');
	w_c('<tr><td colspan="16" class=b1><ol><li>(使用轉移,販賣,拍賣記得選擇道具(魔石)數量)</li><li>裝備道具數量不能大於9</li></ol></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="arm">');
	w_c('<input type="hidden" name="act" value="setup">');
	w_c('<input type="hidden" name="temp_type" value="'+temp_id+'">');
	w_c('</form>');
	w_c('<div id="show_message" style="display:none;position: absolute;left:0; top: 0; Z-INDEX: 303;opacity :0.85;filter:alpha(opacity=85);" ></div>');
	p_c();
};
function arm_use(a1)
{
	var temp='<table bgcolor=#000000><tr><td>技能熟練度提昇'+a1+'</td></tr></table>';
	f.getElementById("show_message").innerHTML=temp;
	set_div_x_y(UI.mouse_x+15,UI.mouse_y-20,"show_message");
};
function arm_show(a1,a2,a3)
{
	if(a1)
	{
		set_arm_tochat(a2,a3);
	}else
	{
		act_click("shop","check_item",a3);
	}
}
function arm_setup(a,b,b1)
{
	var temp='<a class=uline onclick=parent.arm_show(event.ctrlKey,"'+b+'","'+b1+'")>'+b+'</a>';
	switch(a)
	{
		case "a_id":
			d_a_name=temp;
			return;
			break;
		case "d_head_id":
			d_head_name=temp;
			return;
			break;
		case "d_body_id":
			d_body_name=temp;
			return;
			break;
		case "d_hand_id":
			d_hand_name=temp;
			return;
			break;
		case "d_foot_id":
			d_foot_name=temp;
			return;
			break;
		case "d_item_id":
			d_item_name=temp;
			return;
			break;
		case "d_item_id2":
			d_item2_name=temp;
			return;
			break;
	}
	return;
};
function depot_list(a,b,a1){
	w_c(temp_table1+bank_depot_menu+temp_table2);
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr class="head_td"><td>物品名稱</td><td>物品名稱</td><td>物品名稱</td></tr>');
	if(a!="")
	{
		var s1=a.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var arm_view_color ="";
			if(s2[3]=="1"){arm_view_color="bgcolor="+nosend;}
			if((i+1)%3==1)
			{
				w_c('<tr>');
			}
			w_c('<td '+arm_view_color+'><input type="radio" name="id" value="'+s2[4]+'" > <a class=uline onclick=parent.arm_show(event.ctrlKey,"'+s2[1]+'","'+s2[0]+'")>'+s2[1]+'</a>*<span id="show_message_'+s2[0]+'">'+s2[2]+'</span></td>');
			if((i+1)%3==0)
			{
				w_c('</tr>');
			}
		}
		w_c('<tr><td colspan="9" >帳號:<input type="text" name="pay_id" size="10" value="'+p_name+'"> , ');
		w_c('請選擇數量(1-99):<input type="text" name="d_num" size="2" maxlength="2" value="1">');
		w_c(' <input id="depot_btn" type="button" value="轉移" onClick="parent.foot_turn(\'arm\',\'depot_move\',this.form.pay_id.value,this.form.d_num.value,this.form.id);parent.btn_disabled(\'depot_btn\');" > ('+a1+')</td></tr>');
	}
	w_c('<tr><td colspan="9" class=b1><ol><li>武器防具類限制1次只能轉移1個</li></ol></td></tr>');
	w_c('<tr ><td colspan="9" ><div id="show_message"></div></td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
//##########----------syn_system_start----------##########
function syn_end(s,end)
{
	w_c(temp_table1);
	w_c('<tr><td>在合成的途中合成爐突然發出閃閃的白光！<BR><p align=center><font size=7><b>轟隆隆隆隆隆隆隆隆隆隆！</b></p></font>');
	var timerID=setTimeout('syn_end_view("'+s+'","'+end+'")',1000);
	w_c('</td></tr>'+temp_table2+"<br>");
	p_c();
};
function syn_end_view(s,end)
{
	w_c(temp_table1+'<tr><td>');
	if(end==1)//合成成功
	{w_c('你的眼前出現了一個閃閃發亮的物品，這次的合成似乎是成功了！<br><br>合成結果： <font color=#ffffaf>'+s+'</font> 入手！');}
	if(end==2)//編號錯誤
	{w_c('你合出了一團無法辨識的東西，或許是一團垃圾....');}
	if(end==3)//合成失敗
	{w_c('你的眼前出現了一團灰燼....。');}
	if(end==4)//裝備過多
	{w_c('裝備欄已滿，無法進行合成。');}
	w_c('</td></tr>'+temp_table2);
	p_nc();
};
function syn_link()
{
	w_c(temp_table1);
	w_c('<tr><td colspan="13"><a href=javascript:parent.sel_type("0");>武器</a> <a href=javascript:parent.sel_type("1");>頭部</a>  <a href=javascript:parent.sel_type("2");>身體</a> <a href=javascript:parent.sel_type("3");>手套</a> <a href=javascript:parent.sel_type("4");>鞋子</a> <a href=javascript:parent.sel_type("5");>道具</a>  <a href=javascript:parent.sel_type("99");>任務品</a> <a href=javascript:parent.sel_type("7");>魔石</a></td></tr>');
	w_c(temp_table2);
	w_c("<br>");
	//p_nc();
};
function syn_view_special(syntotal,page,s,type)
{
	w_c('<form action="wog_act.php" method="post" name="pageform" target="mission">');
	pagesplit(syntotal,page);
	w_c('<input type="hidden" name="page" value="1">');
	w_c('<input type="hidden" name="type" value="'+type+'">');
	w_c('<input type="hidden" name="f" value="syn">');
	w_c('<input type="hidden" name="act" value="list">');
	w_c('</form>');
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1>');
	syn_link();
	w_c(temp_table1);
	w_c('<tr><td colspan="15" class=b1>我是合成大師,專門替冒險者合成一般商店沒有販賣的物品,我的合成是要收費的,每樣物品手續費1000元,但是先提醒合成是有風險的。我的師父愛德華是傳說中的合成天才,能合成稀有物品,目前他四處雲遊冒險,有緣您會遇到他。</td></tr>');
	w_c('<tr><td colspan="15">請選擇想製作的物品</td></tr>');
	if(type!=7)
	{
		w_c('<tr class="head_td"><td></td><td>名稱</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>職業</td><td>屬性</td><td>能力限制</td><td>成功率</td></tr>');
	}
	else
	{
		w_c('<tr class="head_td"><td></td><td>名稱</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>敏捷</td><td>力量</td><td>生命</td><td>體質</td><td>智力</td><td>魅力</td><td>信仰</td><td>HP</td><td>成功率</td></tr>');
	}
	if(s!="")
	{
		var s1=s.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var arm_view_color="";
			if(s2[12]=="1"){arm_view_color="bgcolor="+nosend;}
			if (type != 7)
			{
				w_c('<tr><td><input type="radio" name="syn_id" value="'+s2[9]+'"></td><td '+arm_view_color+'><a href="javascript:parent.act_click(\'syn\',\'detail\','+s2[9]+')">'+s2[0]+'</a></td><td>'+s2[1]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+s2[10]+'</td><td>'+s_status[s2[11]]+'</td><td>'+arm_need_status(s2[5],s2[6],s2[7],s2[8])+'</td><td>'+s2[13]+'%</td></tr>');
			}
			else
			{
				w_c('<tr><td><input type="radio" name="syn_id" value="'+s2[12]+'"></td><td '+arm_view_color+'><a href="javascript:parent.act_click(\'syn\',\'detail\','+s2[12]+')">'+s2[0]+'</a></td><td>'+s2[1]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+s2[5]+'</td><td>'+s2[6]+'</td><td>'+s2[7]+'</td><td>'+s2[8]+'</td><td>'+s2[9]+'</td><td>'+s2[10]+'</td><td>'+s2[11]+'</td><td>'+s2[15]+'</td><td>'+s2[14]+'%</td></tr>');
			}
		}
	}
	w_c('<tr><td colspan="15">數量:<input type="text" value="1" size="5" maxlength="5" name="item_num"> → <input type="submit" value="確定合成"></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="syn">');
	w_c('<input type="hidden" name="act" value="special">');
	w_c('</form>');
	p_c();
};
//##########----------syn_system_end----------##########

function arm_select(a1)
{
	w_c('<form method="post" target="mission">');
	arm_link(a1);
	w_c('</form>');
	w_c('<center>請選擇部位</center>');
	p_c();
};
function arm_link(a1)
{
	w_c(temp_table1);
	w_c('<tr><td><a href="javascript:parent.arm_change_menu(0)" target="mission">裝備類</a> <a href="javascript:parent.arm_change_menu(5)" target="mission">道具類</a> : ');
	var temp=arm_return_menu(a1);
	w_c('<span id="arm_menu">'+temp+'<span>');
	w_c('</td></tr>');
	w_c(temp_table2);
	w_c("<br>");
	//p_nc();
};
function arm_return_menu(a1)
{
	if(a1 < 5)
	{
		return '<a href="javascript:parent.act_click(\'arm\',\'view\',0)" target="mission">武器</a> <a href="javascript:parent.act_click(\'arm\',\'view\',1)" target="mission">頭部</a> <a href="javascript:parent.act_click(\'arm\',\'view\',2)" target="mission">身體</a> <a href="javascript:parent.act_click(\'arm\',\'view\',3)" target="mission">手部</a> <a href="javascript:parent.act_click(\'arm\',\'view\',4)" target="mission">腳部</a>';
	}else
	{
		return '<a href="javascript:parent.act_click(\'arm\',\'view\',5)" target="mission">道具</a> <a href="javascript:parent.act_click(\'arm\',\'view\',7)" target="mission">魔石</a> <a href="javascript:parent.act_click(\'arm\',\'view\',8)" target="mission">勳章</a> <a href="javascript:parent.act_click(\'arm\',\'view\',9)" target="mission">鑰匙</a> <a href="javascript:parent.act_click(\'arm\',\'view\',10)" target="mission">精鍊石</a>';
	}
};
function arm_change_menu(a1)
{
	f.getElementById("arm_menu").innerHTML=arm_return_menu(a1);
};
function bag_up(str,type)
{
	arm_select();
	var temp="";
	switch(type)
	{
		case 1:
			temp="道具欄";
		break;
		case 2:
			temp="倉庫";
		break;
	}
	temp='<b>'+temp+'增加 '+str+' 格</b>';
	wog_message_box(temp,0,2);
};