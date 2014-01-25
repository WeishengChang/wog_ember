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
function group_view(a1,a2,a3,a4)
{
	w_c('<form action="wog_group.php" method="post" name="f1" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td colspan="2"><input type="button" value="公會列表" onClick="parent.act_gclick(\'main\',\'list\')" title="觀看所有公會狀態,加入公會,離開公會"></td></tr>');
	if(a2!="")
	{
		Map.group_x=a3;Map.group_y=a4;
		w_c('<tr><td colspan="2" ><div style="position:relative;">');
		w_c('<table cellspacing="0" cellpadding="0" width="100%" ><tr><td >');
		w_c('<div id="group_map" align="center">'+group_map_make(a2)+'</div></td></tr></table>');

		var temp="document.f1";
		w_c('<div id="wog_group_job" style="position: absolute;left:0; top: 0; Z-INDEX: 302;">');
		w_c('<table>');
		w_c('<tr><td align="center">X:<input type="text" name="x" size="3"> , Y:<input type="text" name="y" size="3"> <input type="submit" name="send" value="設定座標">');
		w_c('</td></tr></table><input type="hidden" name="temp_id" value=","><input type="hidden" name="act" value="map"><input type="hidden" name="f" value="main">');
		w_c('</div>');

		if(a1!="")
		{
			w_c('<div id="wog_group_job" style="position: absolute;left:0; top: 40; Z-INDEX: 302;">');
			w_c('<table ><tr><td colspan="2" class=b1>');
			var s1=a1.split(";");
			for(var i=0;i<s1.length;i++)
			{
				var s2=s1[i].split(",");
				switch(s2[2])
				{
					case "2":
						check_link=s2[0]+' '+group_act_type[1]+' → '+p_group;
					break;
					case "1":
						check_link='<a href="javascript:parent.act_gclick(\'job\',\'job_wp\','+s2[3]+')" target="mission" title="查看軍報">'+p_group+' '+group_act_type[1]+'</a> → '+s2[0];
					break;
				}
	
				w_c(check_link+' <span id="count_s'+i+'"></span><br>');
				eval("parent.window.clearInterval(job_list.count_s"+i+"_2)");
				creat_job_list("count_s"+i,s2[1]);
			}
			w_c('</td></tr></table>');
			w_c('</div>');
		}
		w_c('<div id="wog_group_map" style="position: absolute;left:0; top: 250; Z-INDEX: 303;">');
		temp="";
		temp+='<table><tr><td align="center"><a href="javascript:parent.group_map_move(1)" target="mission" ><img src="'+img+'arrow_2.gif" border="0"></a></td></tr>';
		temp+='<tr><td align="center"><a href="javascript:parent.group_map_move(4)" target="mission" ><img src="'+img+'arrow_3.gif" border="0"></a>　<a href="javascript:parent.group_map_move(5)" target="mission" title="">本陣</a>　<a href="javascript:parent.group_map_move(2)" target="mission" ><img src="'+img+'arrow_1.gif" border="0"></a></td></tr>';
		temp+='<tr><td align="center"><a href="javascript:parent.group_map_move(3)" target="mission" ><img src="'+img+'arrow_4.gif" border="0"></a></td></tr>';
		temp+='<tr><td align="center"><a href="javascript:parent.act_gclick(\'wp\',\'wp\')" target="mission" title="觀看軍備狀態">軍備</a>　<a href="javascript:parent.act_gclick(\'ex\',\'ex\')" target="mission" title="觀看造兵狀態">造兵</a>　<a href="javascript:parent.act_gclick(\'news\',\'news\')" target="mission" title="觀看10天內前10次會戰狀況">戰報</a>　<a href="javascript:parent.act_gclick(\'build\',\'build_list\')" target="mission" title="觀看研究狀態">研究</a>　<a href="javascript:parent.group_map_menu()" target="mission" >其他</a></td></tr>';
		temp+='<tr id="group_map_menu" style="display:none;"><td align="center"><input type="button" value="會員" onClick="parent.act_gclick(\'member\',\'p_list\')" title="觀看會員狀態,會員管理"> <input type="button" value="據點" onClick="parent.act_gclick(\'area_main\',\'area_main\')" title="觀看據點狀況,設定陷阱及陣形"> <input type="button" value="作業" onClick="parent.act_gclick(\'job\',\'job_list\')" title="查看會員目前進行中的工作"><br><input type="button" value="任務" onClick="parent.act_gclick(\'mission\',\'list\')" title="查看目前任務內容"> <input type="button" value="倉庫" onClick="parent.act_gclick(\'depot\',\'depot_list\')" title="倉庫管理"> <input type="button" value="交易" onClick="parent.act_gclick(\'market\',\'market\')" title="觀看交易市場"><br><input type="button" value="佈告欄" onClick="parent.act_gclick(\'book\',\'book\')" title="公會佈告欄">  <input type="button" value="榮譽設定" onClick="parent.act_gclick(\'point\',\'set_point\')" title="設定公會榮譽<會長專用>"></td></tr>';		
		temp+='</table>';
		w_c(temp+'</div>');
		w_c('</div></td></tr>');
		Drag.init(f.getElementById("wog_group_map"));
	}
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function group_map_menu()
{
	var temp=f.getElementById("group_map_menu").style.display;
	if(temp=="none")
	{
		f.getElementById("group_map_menu").style.display="block";
	}else
	{
		f.getElementById("group_map_menu").style.display="none";
	}
}
function group_map_move(a1){
	var map_area="";
	switch(a1)
	{
		case 1:
			if(Map.my_x-7 < 0){alert("地圖到底了");return;}
			map_area=(Map.my_x-7)+","+Map.my_y;
		break;
		case 2:
			if(Map.my_y+7 > 300){alert("地圖到底了");return;}
			map_area=Map.my_x+","+(Map.my_y+9);
		break;
		case 3:
			if(Map.my_x+7 > 300){alert("地圖到底了");return;}
			map_area=(Map.my_x+7)+","+Map.my_y;
		break;
		case 4:
			if(Map.my_y-7 < 0){alert("地圖到底了");return;}
			map_area=Map.my_x+","+(Map.my_y-9);
		break;
		case 5:
			map_area=Map.group_x+","+Map.group_y;
		break;
	}
	act_gclick('main','map',map_area);
};
function group_map(a1){
	f.getElementById("group_map").innerHTML=group_map_make(a1);
};
function group_map_make(a1)
{
	var s1=a1.split(";");
	var temp='<table background="'+img+'map/7.jpg" cellspacing="1" cellpadding="1">';
	for(var i=0;i<s1.length;i++)
	{
		var s2=s1[i].split(",");
		if(i==24)
		{
			Map.my_x=parseInt(s2[1]);
			Map.my_y=parseInt(s2[2]);
		}
		if(i%7==0)
		{
			temp+='<tr>';
		}
		var g_t="";
		if(s2[4]!="")
		{
			var temp_message=s2[4]+","+s2[5]+","+s2[6]+","+s2[7]+","+s2[3];
			g_t='<a onmouseover=parent.wog_message_box("'+temp_message+'",2,4,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\');><b><font color=#000000>'+s2[4]+'</font><b></a>';
		}
		temp+='<td background="'+img+'map/'+s2[3]+'.jpg" class=b2 width="62" height="56" title="座標:'+s2[1]+','+s2[2]+'('+g_area_type[s2[3]]+')">'+g_t+'</td>';
		if(i%7==6)
		{
			temp+='</tr>';
		}
	}
	temp+='</table>';
	return temp;
};
function group_mission_list(a1)
{
	w_c(temp_table1+group_menu+temp_table2+"<br>");
	w_c(temp_table1);
	w_c('<tr class="head_td"><td>任務標題</td><td>任務狀態</td><td>完成玩家</td></tr>');
	if(a1!="")
	{
		var s1=a1.split(";");
		var status="未完成";
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			switch(s2[2])
			{
				case '0':
					status="未完成";
				break;
				case '1':
					status="已完成";
				break;
			}
			w_c('<tr><td><a target="mission" href="javascript:parent.act_gclick(\'mission\',\'view\',\''+s2[0]+'\')" >'+s2[1]+'</a></td><td>'+status+'</td><td>'+s2[3]+'</td></tr>');
		}
	}
	w_c(temp_table2);
	p_c();
}
function group_depot_list(a,b,c,a1,a2){
	w_c(temp_table1+group_menu+temp_table2+"<br>");
	w_c(temp_table1);
	w_c('<tr><td><a href="javascript:parent.act_gclick(\'depot\',\'depot_list\',\'0\')" target="mission">武器</a> <a href="javascript:parent.act_gclick(\'depot\',\'depot_list\',\'1\')" target="mission">頭部</a> <a href="javascript:parent.act_gclick(\'depot\',\'depot_list\',\'2\')" target="mission">身體</a> <a href="javascript:parent.act_gclick(\'depot\',\'depot_list\',\'3\')" target="mission">手部</a> <a href="javascript:parent.act_gclick(\'depot\',\'depot_list\',\'4\')" target="mission">腳部</a> <a href="javascript:parent.act_gclick(\'depot\',\'depot_list\',\'5\')" target="mission">道具</a> <a href="javascript:parent.act_gclick(\'depot\',\'depot_list\',\'7\')" target="mission">魔石</a> <a href="javascript:parent.act_gclick(\'depot\',\'depot_list\',\'8\')" target="mission">勳章</a> <a href="javascript:parent.act_gclick(\'depot\',\'depot_list\',\'10\')" target="mission">精鍊石</a></tr>');
	w_c(temp_table2);
	w_c('<form action="wog_group.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr class="head_td"><td>物品名稱</td><td>需求榮譽</td><td>物品名稱</td><td>需求榮譽</td><td>物品名稱</td><td>需求榮譽</td></tr>');
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
			w_c('<td '+arm_view_color+'><input type="radio" name="d_id" value="'+s2[0]+'" > <a class=uline onclick=parent.arm_show(event.ctrlKey,"'+s2[1]+'","'+s2[0]+'")>'+s2[1]+'</a>*<span id="show_message_'+s2[0]+'">'+s2[2]+'</span></td><td>'+s2[4]+'</td>');
			if((i+1)%3==0)
			{
				w_c('</tr>');
			}
		}
		w_c('<tr><td colspan="9" >帳號:<input type="text" name="pay_id" size="10"> , ');
		w_c('請選擇數量:<select name="d_num">');
		for(var j=1;j<10;j++)
		{
			w_c('<option value="'+j+'" >'+j+'</option>');
		}
		w_c('</select> <input type="button" value="轉移" onClick="parent.foot_gturn(\'depot\',\'depot_move\',this.form.pay_id.value,this.form.d_num.value,this.form.d_id)" ></td></tr>');
		w_c('<tr><td colspan="9" align="center">『'+p_name+'目前榮譽』:<span id="point">'+c+'</span></td></tr>');
	}
	w_c('<tr><td colspan="9" class=b1><ol><li>會長及副會長轉移物品沒有榮譽限制</li><li>武器防具類限制1次只能轉移1個</li></ol></td></tr>');
	var tpl="";
	if (a1 != "") {
		var s1=a1.split(";");
		var s2=a2.split(";");
		for(i=0;i<s1.length;i++)
		{
			tpl+=s1[i]+" at "+s2[i]+"<br>";
		}
	}
	w_c('<tr><td colspan="9" class="b1"><div id="show_message" align="center"></div>'+tpl+'</td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function group_list(saletotal,page,a,area_id,key){
	var temp_html="";
	w_c('<form action="wog_group.php" method="post" name="pageform" id="pageform" target="mission">');
	w_c(temp_table1+'<tr><td><input type="button" value="'+birth[1]+'" onClick="parent.act_gclick(\'main\',\'list\',1)"> <input type="button" value="'+birth[2]+'" onClick="parent.act_gclick(\'main\',\'list\',2)"> <input type="button" value="'+birth[3]+'" onClick="parent.act_gclick(\'main\',\'list\',3)"> <input type="button" value="'+birth[4]+'" onClick="parent.act_gclick(\'main\',\'list\',4)"></td></tr>'+temp_table2);
	w_c('<input type="hidden" name="page" value=""><input type="hidden" name="f" value="main"><input type="hidden" name="act" value="list"><input type="hidden" name="temp_id" value="'+area_id+'"><input type="hidden" name="key" value="'+key+'"></form>');
	temp_html+=pagesplit(saletotal,page,1);
	temp_html+='<form action="wog_group.php" method="post" target="mission" name="f1">';
	temp_html+=temp_table1;
	if(a!="")
	{
		temp_html+='<tr><td></td><td>名稱</td><td>總人數</td><td>會長</td><td>座標</td><td>'+g_area_type_title+'</td><td>'+g_weather_title+'</td></tr>';
		var s1=a.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var temp_new_str="";
			if(s2[5]==1)
			{
				temp_new_str='<font color="red">NEW</font> ';
			}
			temp_html+='<tr><td ><input type="radio" name="g_id" value="'+s2[0]+'" ></td><td>'+temp_new_str+s2[1]+'</td><td >'+s2[2]+'</td><td >'+s2[3]+'</td><td >'+s2[4]+'</td><td>'+g_area_type[s2[6]]+'</td><td>'+g_weather[s2[7]]+'</td></tr>';
		}
		var dbsts_join="";
		var dbsts_leave="";
		if(p_group!="")
		{
			dbsts_join="disabled";
		}
	}else
	{
		dbsts_join="disabled";
		if(area_id==0)
		{
			temp_html+='<tr><td colspan="7" align="center">請選擇地區</td></tr>';
		}
		else
		{
			temp_html+='<tr><td colspan="7" align="center">目前無公會</td></tr>';
		}

	}
	var temp="document.f1";
	temp_html+='<tr><td colspan="7" align="center"><input type="submit" value="加入公會"  '+dbsts_join+' > <input type="button" value="創立公會" onClick="parent.group_creat();" title="創立公會"></td></tr>';
	temp_html+='<tr><td colspan="7" align="center"><input type="text" name="key" size="16"> <input type="button" value="搜尋" onclick="'+temp+'.f.value=\'main\';'+temp+'.act.value=\'list\';'+temp+'.submit();"></td></tr>';
	temp_html+=temp_table2+'<input type="hidden" name="f" value="main"><input type="hidden" name="act" value="add"><input type="hidden" name="temp_id" value="1"></form>';
	w_c(temp_html);
	p_c();
};
function group_book_view(temp,temp2,saletotal,page)
{
	w_c(temp_table1+group_menu+temp_table2+"<br>");
	w_c('<form action="wog_group.php" method="post" target="mission">');
	w_c(temp_table1);
	temp_html='<div id="msg_box2"><input type="button" value="修改佈告(會長專用)" onClick="document.getElementById(\'msg_box\').style.display=\'block\';document.getElementById(\'msg_box2\').style.display=\'none\';"></div>';
	temp_html+='<div id="msg_box" style="display:none;"><textarea cols="30" rows="5" name="g_book"></textarea><br><input type="button" value="確定送出" onClick="parent.act_gclick(\'book\',\'save_book\',this.form.g_book.value)">';
	temp_html+=' <input type="button" value="取消" onClick="document.getElementById(\'msg_box\').style.display=\'none\';document.getElementById(\'msg_box2\').style.display=\'block\';"></div>';
	if(temp.length<=0)
	{
		w_c('<tr><td>沒有資料');
	}else
	{
		while(temp.indexOf("[n]") > 0)
		{
			temp=temp.replace("[n]","<br>");
		}
		w_c('<tr><td>'+temp);
	}
	w_c('<br>'+temp_html+'</td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	w_c('<form action="wog_group.php" method="post" name="pageform" id="pageform" target="mission">');
	w_c('<input type="hidden" name="page" value=""><input type="hidden" name="f" value="book"><input type="hidden" name="act" value="book"></form>');
	pagesplit(saletotal,page);
	w_c('<form action="wog_group.php" method="post" target="mission">');
	w_c('<br>'+temp_table1);
	if(temp2.length<=0)
	{
		w_c('<tr><td>沒有資料</td></tr>');
	}else
	{
		w_c('<tr><td class=b1 >');
		var s1=temp2.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2 = s1[i].split(",");
			{
				if(s2[1])
				{
					while(s2[1].indexOf("[n]") > 0)
					{
						s2[1]=s2[1].replace("[n]","<br>");
					}					
				}
				var p_img_url=s2[3];
				if(p_img_url.indexOf("http") == -1)
				{
					p_img_url='<img src="'+img+p_img_url+'.gif" border="0">';
				}else
				{
					p_img_url='<img src="'+p_img_url+'" border="0">';
				}
				w_c('<table  ><tr><td width="180">'+s2[0]+'<br>'+p_img_url+'<br>at '+s2[2]+'</td><td valign="top" class=b1 >'+s2[1]+'</td></tr></table><center>'+hr+'</center>');
				//w_c(s2[1]+'<br><br>&nbsp;&nbsp;'+s2[0]+' at '+s2[2]+'<table width="100%"><tr><td width="100%">'+hr+'</td></tr></table>');
			}		
		}
		w_c('</td></tr>');
	}
	w_c('<tr><td><textarea cols="30" rows="5" name="g_book"></textarea><br><input type="button" value="會員留言" onClick="parent.group_chang_book(this.form.g_book);parent.act_gclick(\'book\',\'save_memberbook\',this.form.g_book.value)"></td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function group_chang_book(s)
{
	var book=s.value;
	var chang_word=[[",","，"],[";","；"],["<","＜"],[">","＞"],["'","、"]];
	for(var i=0;i<chang_word.length;i++)
	{
		while(book.indexOf(chang_word[i][0])>=0)
		{
			book=book.replace(chang_word[i][0],chang_word[i][1]);
		}
	}
	s.value=book;
};
function group_join_list(s)
{
	w_c(temp_table1+group_menu+temp_table2);
	w_c('<form action="wog_group.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td></td><td>內容</td><td>等級</td><td>職業</td></tr>')
	if(s!="")
	{
		var s1=s.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			w_c('<tr><td><input type="radio" name="g_j_id" value="'+s2[0]+'" ></td><td>'+s2[1]+' 提出加入公會申請</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td></tr>');
		}
		w_c('<tr><td colspan="4" align="center"><input type="button" value="邀請加入" onClick="parent.foot_gturn(\'get_member\',\'get_save_member\',null,null,this.form.g_j_id)" ></td></tr>');
	}else
	{
		w_c('<tr><td colspan="5" align="center">沒有玩家申請加入會員</td></tr>');
	}
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function group_p_list(a1,a2,a3)
{
	w_c(temp_table1+group_menu+temp_table2);
	w_c('<form action="wog_group.php" method="post" target="mission">');
	w_c(temp_table1);
	var s1=a2.split(";");
	var pa=[];
	pa["0"]="";
	for(var i=0;i<s1.length;i++)
	{
		var s2=s1[i].split(",");
		pa[s2[0]]=s2[1];
	}
	w_c('<tr><td colspan="6">'+a3+'</td></tr>');
	w_c('<tr class="head_td"><td></td><td>名稱</td><td>榮譽</td><td>等級</td></tr>');
	if(a1!="")
	{
		var s1=a1.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			w_c('<tr><td><input type="radio" name="p_id" value="'+s2[0]+'" ></td><td >'+s2[1]+'('+pa[s2[4]]+')</td><td >'+s2[3]+'</td><td >'+s2[2]+'</td></tr>');
		}
		pa["0"]="請選擇";
		w_c('<tr><td colspan="6"><input type="radio" name="mod_type" value="1">取消會員資格  <input type="radio" name="mod_type" value="2">設定職務<select name="permissions" onChange="this.form.mod_type[1].checked=true">');
		for(var key in pa)
		{
			w_c('<option value="'+key+'">'+pa[key]+'</option>');
		}
		w_c('</select>，<input type="submit" value="確定"></td></tr>');
		w_c('<tr><td colspan="6">只有會長才能取消會員資格<br>若會長取消自身的會員資格視同解散公會</td></tr>');
	}
	w_c('<tr ><td colspan="4"><input type="button" value="階級設定" onClick="parent.act_gclick(\'permissions\',\'view\')" title="核准入會申請<會長專用>"> <input type="button" value="認領會員" onClick="parent.act_gclick(\'get_member\',\'get_member\')" title="核准入會申請<會長專用>"> | <input type="button" value="退出公會" onClick="if(confirm(\'確認您是否退出公會，若會長退出公會視同解散公會\')){parent.act_gclick(\'main\',\'del\');}" title="退出公會<會員專用>"></td></tr>');
	w_c('<input type="hidden" name="f" value="member">');
	w_c('<input type="hidden" name="act" value="mod_member">');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function group_permissions(a1)
{
	p_s_close();
	w_c(temp_table1+group_menu+temp_table2);
	w_c('<form action="wog_group.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr class="head_td"><td rowspan=2></td><td rowspan=2>名稱</td><td colspan=3>軍事</td><td colspan=4>內政</td></tr>');
	w_c('<tr class="head_td"><td>出戰</td><td>偵查</td><td>輸送</td><td>造兵</td><td>研究</td><td>交易</td><td>會員</td></tr>');
	if(a1!="")
	{
		var s1=a1.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			if(parseInt(s2[2])<=2)
			{
				w_c('<tr><td></td>');
			}else
			{
				w_c('<tr><td><a href=javascript:parent.act_gclick(\'permissions\',\'del\','+s2[0]+')>刪除</a></td>');				
			}
			w_c('<td><input type="text" name="p_1_'+i+'" size="10" maxlength="30" value="'+s2[1]+'"><input type="hidden" name="p_0_'+i+'" value="'+s2[0]+'"></td>');
			w_c('<td><input type="checkbox" name="p_2_'+i+'" value=1 '+group_permissions_return(s2[3])+'></td>');
			w_c('<td><input type="checkbox" name="p_3_'+i+'" value=1 '+group_permissions_return(s2[4])+'></td>');
			w_c('<td><input type="checkbox" name="p_4_'+i+'" value=1 '+group_permissions_return(s2[5])+'></td>');
			w_c('<td><input type="checkbox" name="p_5_'+i+'" value=1 '+group_permissions_return(s2[6])+'></td>');
			w_c('<td><input type="checkbox" name="p_6_'+i+'" value=1 '+group_permissions_return(s2[7])+'></td>');
			w_c('<td><input type="checkbox" name="p_7_'+i+'" value=1 '+group_permissions_return(s2[8])+'></td>');
			w_c('<td><input type="checkbox" name="p_8_'+i+'" value=1 '+group_permissions_return(s2[9])+'></td></tr>');
		}
	}
	w_c('<tr><td colspan="10"><input type="submit" value="儲存" > | <input type="button" value="新增" onClick="if(this.form.num.value>=10){alert(\'上限只能設定10個職務\')}else{parent.group_permission_add();}" ></td></tr>');
	w_c('<input type="hidden" name="f" value="permissions">');
	w_c('<input type="hidden" name="act" value="save">');
	w_c('<input type="hidden" name="num" value="'+i+'">');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function group_permissions_return(a1)
{
	return (a1=="1")?"checked":"";
};
function group_permission_add()
{
	w_c(temp_table1);
	w_c('<tr class="head_td"><td rowspan=2>名稱</td><td colspan=3>軍事</td><td colspan=4>內政</td></tr>');
	w_c('<tr class="head_td"><td>出戰</td><td>偵查</td><td>輸送</td><td>造兵</td><td>研究</td><td>交易</td><td>會員</td></tr>');
	w_c('<tr><td><input type="text" name="p_1" size="10" maxlength="30" value=""></td>');
	w_c('<td><input type="checkbox" name="p_2" value=1></td>');
	w_c('<td><input type="checkbox" name="p_3" value=1></td>');
	w_c('<td><input type="checkbox" name="p_4" value=1></td>');
	w_c('<td><input type="checkbox" name="p_5" value=1></td>');
	w_c('<td><input type="checkbox" name="p_6" value=1></td>');
	w_c('<td><input type="checkbox" name="p_7" value=1></td>');
	w_c('<td><input type="checkbox" name="p_8" value=1></td></tr>');
	w_c('<tr><td colspan="10"><input type="submit" value="儲存" > | <input type="button" value="關閉" onClick="parent.p_s_close();" ></td></tr>');
	w_c('<input type="hidden" name="f" value="permissions">');
	w_c('<input type="hidden" name="act" value="add">');
	w_c(temp_table2);
	set_window();
	x=UI.window_w/2-(350/2);
	y=UI.window_h/2;
	p_s(x,y,480);
};
function group_wp_select(a1,a2,s1)
{
	var temp='';
	var sel='';
	if(a2=="item")
	{
		temp='onchange="parent.group_item_text(this.options[this.selectedIndex].value);"';
	}
	temp='<select name="'+a2+'" '+temp+'>';
	if(a1[0]!="")
	{
		if(a2=="item")
		{
			temp+='<option value="0" >請選擇</option>';
		}
		for(var i=0;i<a1.length;i++)
		{
			var ar=a1[i].split(";");
			if(a2=="item")
			{
				ar[1]=g_item[ar[0]]+"*"+ar[1];
			}
			if(ar[0]==s1)
			{
				sel='SELECTED';
			}else
			{
				sel='';
			}
			temp+='<option value="'+ar[0]+'" '+sel+'>'+ar[1]+'</option>';
		}
	}
	else
	{
		temp+='<option value="0" >請選擇</option>';
	}
	temp+='</select>';
	return temp;
};
function group_area_main(a1,a2,a3,a4,a5,a6)
{
	p_s_close();
	w_c(temp_table1+group_menu+temp_table2);
	w_c('<form action="wog_group.php" method="post" name="f1" target="mission">');
	w_c(temp_table1);
	if(a1[5]!=""){g_img="<img src="+a1[5]+">";}
	w_c('<tr class="head_td"><td colspan="3">'+a1[0]+'('+a1[1]+'-'+a1[2]+')</td></tr>');
	w_c('<tr><td rowspan="4" colspan="2">勝利點:'+a6+'<br>'+g_img+'<br>輸入圖像連結 <input type="text" name="url" value="http://" size="20" maxlength="200"><input type="submit" value="變更圖誌" onClick="document.f1.act.value=\'ch_main\';document.f1.type.value=\'1\';"></td>');
	w_c('<td>防禦力 : '+a1[10]+' <input type="submit" value="修復據點" onClick="document.f1.act.value=\'durable\'"></td></tr>');
	w_c('<tr><td>防禦陣形 : '+group_wp_select(a3.split(","),"formation",a1[8])+' <input type="submit" value="變更陣形" onClick="document.f1.act.value=\'ch_main\';document.f1.type.value=\'2\';"></td></tr>');
	w_c('<tr><td>防禦陷阱 : '+group_wp_select(a2.split(","),"trap",a1[9])+' <input type="submit" value="變更陷阱" onClick="document.f1.act.value=\'ch_main\';document.f1.type.value=\'3\';"></td></tr>');
	w_c('<tr><td>戰略指令 : '+g_item[a1[11]]+' <input type="button" value="變更指令" onClick="parent.mouse_xy(event.x||event.pageX,event.y||event.pageY);parent.act_gclick(\'area_main\',\'item\')"><div>'+g_item_text[a1[11]]+'</div></td></tr>');
	w_c('<tr><td>公會等級 : '+a1[7]+'</td><td>會員數 : '+a1[6]+'</td><td rowspan="2" class=b1><ol><li>據點防禦力為0時,資源會被掠奪</li><li>選擇防禦陣形,可對抗敵方攻擊</li><li>使用防禦陷阱,可使敵方攻城時造成傷害</li></ol></td></tr>');
	w_c('<tr><td>地形 : '+g_area_type[a1[3]]+'</td><td>氣候 : '+g_weather[a1[4]]+'</td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="area_main">');
	w_c('<input type="hidden" name="act" value="ch_main">');
	w_c('<input type="hidden" name="type" value="1">');
	w_c('</form>');
	p_c();
};
function group_master(a1)
{
	w_c(temp_table1);
	w_c('<tr class="head_td"><td>請填入座標</td></tr>');
	switch(a1)
	{
		case 1:
			w_c('<tr><td>X:<input type="text" name="x" size="3"> , Y:<input type="text" name="y" size="3"> <input type="submit" name="send" value="設定座標" onclick="document.f102.act.value=1;"></td></tr>');
			break;
		case 2:
			w_c('<tr><td>公會:<input type="text" name="g_name" size="20"> <input type="submit" name="send" value="進行攻擊" onclick="document.f102.act.value=2"></td></tr>');
			break;
	}
	w_c('<tr><td><input type="button" value="關閉" onClick="parent.p_s_close();" ></td></tr>');
	w_c('<input type="hidden" name="f" value="g_admin">');
	w_c('<input type="hidden" name="act" value="">');
	w_c(temp_table2);
	gshow_item_lay();
};
function group_item(a1)
{
	w_c(build_table1);
	w_c('<tr><td>戰略指令 : '+group_wp_select(a1.split(","),"item",'')+' </td></tr>');
	w_c('<tr><td class=b1><font color=#ff8000><b><div id="g_item_text"></div></b></font></td></tr>');
	w_c('<tr><td><input type="submit" value="確定送出"> <input type="button" value="取消" onClick="parent.p_s_close();"></td></tr>');
	w_c('<tr><td class=b1><ol><li>按下確定後，將會消費一次使用次數</li><li>戰略指令將會在戰鬥中生效</li></ol></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="area_main">');
	w_c('<input type="hidden" name="act" value="ch_main">');
	w_c('<input type="hidden" name="type" value="4">');
	p_s();
};
function group_item_text(a1)
{
	if (f.getElementById("g_item_text")) {
	
		if (g_item_text[a1]) {
			f.getElementById("g_item_text").style.display = "block";
			f.getElementById("g_item_text").innerHTML = g_item_text[a1];
		}
		else {
			f.getElementById("g_item_text").style.display = "none";
		}
	}
}
function group_wp(a,b,d,a1){
	p_s_close();
	w_c(temp_table1+group_menu+temp_table2);
	w_c('<form action="wog_group.php" method="post" name="f1" target="mission">');
	w_c(left_table1);
	w_c('<tr><td colspan="5">軍備情況</td></tr>');
	w_c('<tr class="head_td"><td></td><td>名稱</td><td>數量</td><td>出擊上限</td><td>出擊數量</td></tr>');
	if (a != "") {
		var s1 = a.split(",");
		var s2 = d.split(",");
		for (var i = 0; i < s1.length; i++) {
			w_c('<tr><td><input type="checkbox" name="wp[]" value="' + (i+1) + '" ></td><td>' + wp_name[i+1] + '</td><td>' + s1[i] + '</td><td>'+s2[i]+'</td><td><input type="text" name="wp_' + (i+1) + '" maxlength="6" size="6"></td></tr>');
		}
	}
	var temp="document.f1";
	w_c(temp_table2);
	w_c(right_table1);
	w_c('<tr><td colspan="4">請選擇地區</td></tr>');
	w_c(group_area_menu);
	w_c('<tr><td colspan="4"><div id="show_message_area"></div></td></tr>');
	w_c('<tr><td colspan="4"><input type="submit" value="出戰" onClick="'+temp+'.act.value=\'fight\';'+temp+'.f.value=\'wp\';"> <input type="submit" value="偵查" onClick="'+temp+'.act.value=\'fight\';'+temp+'.f.value=\'wp\';'+temp+'.fight_type.value=\'4\'"> <input type="submit" value="輸送" onClick="'+temp+'.act.value=\'fight\';'+temp+'.f.value=\'wp\';'+temp+'.fight_type.value=\'2\'"></td></tr>');
	w_c('<tr><td colspan="4">選擇作戰陣形:'+group_wp_select(a1.split(","),"formation",'')+'(作戰時附加陣形效果)</td></tr>');
	w_c('<tr><td colspan="4"><input type="text" name="key" size="16"> <input type="button" value="搜尋" onclick="'+temp+'.f.value=\'main\';'+temp+'.act.value=\'area\';'+temp+'.submit();"></td></tr>');
	//w_c('<tr><td colspan="4">宣戰佈告:<input type="text" name="fight_message" size="16" maxlength="20" value="'+fight_message+'"></td></tr>');
	w_c('<input type="hidden" name="f" value="wp">');
	w_c('<input type="hidden" name="act" value="fight">');
	w_c('<input type="hidden" name="fight_type" value="1">');
	w_c('<input type="hidden" name="temp_id" value="1">');
	if (b!= "") {
		var s1 = b.split(";");
		var item_str="";
		for (var i = 0; i < s1.length; i++) {
			var s2 = s1[i].split(",");
			var check_link="";
			switch(s2[2])
			{
				case '1':
					item_str=' <a href="javascript:parent.group_vip_item('+s2[0]+',2)" target="mission">加速</a>';
				case '2':
					check_link='<a href="javascript:parent.act_gclick(\'job\',\'job_wp\','+s2[0]+')" target="mission" title="查看軍報">'+group_act_type[s2[2]]+'</a> → '+s2[3];
				break;
				case '4':
					check_link=group_act_type[s2[2]]+' → '+s2[3];
				break;
				case '3':
					check_link=group_act_type[s2[2]];
				break;
			}
			w_c('<tr><td colspan="4"><table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width=50%>'+check_link+'</td><td width=35%><div id="count_s'+i+'"></div></td><td><div id="count_s'+i+'_cs"><a href="javascript:parent.foot_gturn(\'job\',\'job_break\','+s2[0]+',1,null)" target="mission">中斷</a>'+item_str+'</div></td></tr></table></td></tr>');
			eval("parent.window.clearInterval(job_list.count_s"+i+"_2)");
			creat_job_list("count_s"+i,s2[1]);
		}
	}
	w_c(temp_table2);
	w_c('</form><div id="page_jump"></div>');
	p_c();
};
function group_ex(a,b,c)
{
	p_s_close();
	w_c(temp_table1+group_menu+temp_table2);
	w_c('<form action="wog_group.php" method="post" name="f2" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td colspan="12">資源情況</td></tr>');
	w_c('<tr class="head_td"><td>煤</td><td>木材</td><td>石塊</td><td>石油</td><td>黃金</td><td>酒</td><td>大麥</td><td>香菸</td><td>鐵</td><td>皮毛</td><td>絲線</td><td>珍珠</td></tr>');
	if(a!="")
	{
		var s1=a.split(",");
		w_c('<tr><td>'+s1[0]+'</td><td>'+s1[1]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td><td>'+s1[4]+'</td><td>'+s1[5]+'</td><td>'+s1[6]+'</td><td>'+s1[7]+'</td><td>'+s1[8]+'</td><td>'+s1[9]+'</td><td>'+s1[10]+'</td><td>'+s1[11]+'</td></tr>');
	}
	w_c(temp_table2);
	w_c(hr);
	w_c(left_table1);
	w_c('<tr class="head_td"><td></td><td>兵種</td><td>每單位生產需求</td><td>數量</td></tr>');
	if (b != "") {
		var s1 = b.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2 = s1[i].split(",");
			w_c('<tr><td><input type="radio" name="wp" value="' + s2[0] + '" ></td><td>' + s2[1] + '</td><td>' + s2[2] + '</td><td><input type="text" name="wp_' + s2[0] + '" maxlength="6" size="6"></td></tr>');
		}
	}
	w_c('<tr><td colspan="4"><input type="submit" value="確認生產"></td></tr>');
	w_c('<input type="hidden" name="f" value="ex">');
	w_c('<input type="hidden" name="act" value="wpup">');
	w_c(temp_table2);
	if (c != "") {
		w_c(right_table1);
		var s1 = c.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2 = s1[i].split(",");
			w_c('<tr><td><table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width=50%>'+group_act_type[0]+' '+wp_name[s2[2]]+':'+s2[3]+'</td><td><div id="count_s'+i+'"></div></td><td><div id="count_s'+i+'_cs"><a href="javascript:parent.foot_gturn(\'job\',\'job_break\','+s2[0]+',1,null)" target="mission">中斷</a> <a href="javascript:parent.group_vip_item('+s2[0]+',3)" target="mission">加速</a></div></td></tr></table></td></tr>');
			eval("parent.window.clearInterval(job_list.count_s"+i+"_2)");
			creat_job_list("count_s"+i,s2[1]);
		}
		w_c(temp_table2);
	}
	w_c('</form>');
	p_c();
};
function group_area_list(saletotal,page,a,temp_id,key){
	var temp_html="";
	var page_jump_html="";
	page_jump_html+='<form action="wog_group.php" method="post" name="pageform" id="pageform" target="mission">';
	page_jump_html+='<input type="hidden" name="page" value=""><input type="hidden" name="f" value="main"><input type="hidden" name="act" value="area"><input type="hidden" name="temp_id" value="'+temp_id+'"><input type="hidden" name="key" value="'+key+'"></form>';
	temp_html+=pagesplit(saletotal,page,1);
	temp_html+=group_table1;
	if(a!="")
	{
		temp_html+='<tr><td></td><td>名稱</td><td>總人數</td><td>會長</td><td>座標</td><td>'+g_area_type_title+'</td><td>'+g_weather_title+'</td></tr>';
		var s1=a.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var temp_new_str="";
			if(s2[5]==1)
			{
				temp_new_str='<font color="red">NEW</font> ';
			}
			temp_html+='<tr><td ><input type="radio" name="g_id" value="'+s2[0]+'" ></td><td>'+temp_new_str+''+s2[1]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+g_area_type[s2[6]]+'</td><td>'+g_weather[s2[7]]+'</td></tr>';
		}
	}else
	{
		temp_html+='<tr><td colspan="7" align="center">目前無公會</td></tr>';
	}
	temp_html+=temp_table2;
	f.getElementById("show_message_area").innerHTML=temp_html;
	f.getElementById("page_jump").innerHTML=page_jump_html;
};
function group_creat()
{
	w_c('<form action="wog_group.php" method="post" target="mission">');	
	w_c(temp_table1);
	w_c('<tr><td>公會名稱 <input type="text" name="g_name" size="16" maxlength="20"> <input type="button" value="決定" onClick="parent.act_gclick(\'main\',\'creat\',this.form.g_name.value)"> </td></tr>');
	w_c('<tr><td>創立公會需要30000元</td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function group_set_point(a,b,c,d)
{
	w_c(temp_table1+group_menu+temp_table2+'<br>');
	w_c(temp_table1+'<tr class="head_td"><td>獲得榮譽設定</td></tr>');
	w_c('<tr><td>');
	w_c('<form action="wog_group.php" method="post" target="mission" onSubmit="this.set_type.value=1;">');
	w_c(temp_table1);
	var s1=c.split(",");
	w_c('<tr><td colspan="6">每貢獻1點資源可獲得榮譽</td></tr>');
	for (var i = 0; i < s1.length; i++) {
		if((i+1)%6==1)
		{
			w_c('<tr>');
		}
		w_c('<td>'+ex_name[i+1]+':<input type="text" name="ex_point_'+(i+1)+'" value="'+s1[i]+'" size="2" maxlength="5"></td>');
		if((i+1)%6==0)
		{
			w_c('</tr>');
		}
	}
	w_c('<tr class="head_td"><td colspan="6"><input type="submit" value="設定資源榮譽" ></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="set_type" value="">');
	w_c('<input type="hidden" name="f" value="point">');
	w_c('<input type="hidden" name="act" value="mod_point">');
	w_c('</form>');

	w_c('<form action="wog_group.php" method="post" target="mission" onSubmit="this.set_type.value=2;">');
	w_c(temp_table1);
	w_c('<tr><td colspan="6">每生產1點兵力可獲得榮譽 </td></tr>');
	var s1=d.split(",");
	for (var i = 0; i < s1.length; i++) {
		if((i+1)%6==1)
		{
			w_c('<tr>');
		}
		w_c('<td>'+wp_name[i+1]+':<input type="text" name="wp_point_'+(i+1)+'" value="'+s1[i]+'" size="2" maxlength="5"></td>');
		if((i+1)%6==0)
		{
			w_c('</tr>');
		}
	}
	w_c('<tr class="head_td"><td colspan="6"><input type="submit" value="設定兵種榮譽" ></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="set_type" value="">');
	w_c('<input type="hidden" name="f" value="point">');
	w_c('<input type="hidden" name="act" value="mod_point">');
	w_c('</form></td></tr>');

	w_c('<tr class="head_td"><td>消費榮譽設定</td></tr><tr><td>');
	w_c('<form action="wog_group.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td><a href="javascript:parent.act_gclick(\'point\',\'set_point\',\'0\')" target="mission">武器</a> <a href="javascript:parent.act_gclick(\'point\',\'set_point\',\'1\')" target="mission">頭部</a> <a href="javascript:parent.act_gclick(\'point\',\'set_point\',\'2\')" target="mission">身體</a> <a href="javascript:parent.act_gclick(\'point\',\'set_point\',\'3\')" target="mission">手部</a> <a href="javascript:parent.act_gclick(\'point\',\'set_point\',\'4\')" target="mission">腳部</a> <a href="javascript:parent.act_gclick(\'point\',\'set_point\',\'5\')" target="mission">道具</a> <a href="javascript:parent.act_gclick(\'point\',\'set_point\',\'7\')" target="mission">魔石</a> <a href="javascript:parent.act_gclick(\'point\',\'set_point\',\'8\')" target="mission">勳章</a> <a href="javascript:parent.act_gclick(\'point\',\'set_point\',\'10\')" target="mission">精鍊石</a></tr>');
	w_c(temp_table2);
	w_c(temp_table1);
	w_c('<tr><td>物品名稱</td><td>需求榮譽</td><td>物品名稱</td><td>需求榮譽</td><td>物品名稱</td><td>需求榮譽</td></tr>');
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
			w_c('<td '+arm_view_color+'><a href="javascript:parent.foot_gturn(\'shop\',\'check_item\','+b+','+s2[0]+',null)" >'+s2[1]+'</a>*'+s2[2]+'</td><td><input type="text" name="ex_point_'+s2[0]+'" value="'+s2[4]+'" size="2" maxlength="5"> <input type="button" value="設定" onClick="parent.foot_gturn(\'point\',\'dep_point\','+s2[0]+',this.form.ex_point_'+s2[0]+'.value)" ></td>');
			if((i+1)%3==0)
			{
				w_c('</tr>');
			}
		}
	}
	w_c(temp_table2);
	w_c('</form>');
	w_c('</td></tr>'+temp_table2);
	p_c();
};
function group_build(a,b)
{
	p_s_close();
	w_c(temp_table1+group_menu+temp_table2);
	w_c('<form action="wog_group.php" method="post" name="f2" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td colspan="12">資源情況</td></tr>');
	w_c('<tr class="head_td"><td>煤</td><td>木材</td><td>石塊</td><td>石油</td><td>黃金</td><td>酒</td><td>大麥</td><td>香菸</td><td>鐵</td><td>皮毛</td><td>絲線</td><td>珍珠</td></tr>');
	if(a!="")
	{
		var s1=a.split(",");
		w_c('<tr><td>'+s1[0]+'</td><td>'+s1[1]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td><td>'+s1[4]+'</td><td>'+s1[5]+'</td><td>'+s1[6]+'</td><td>'+s1[7]+'</td><td>'+s1[8]+'</td><td>'+s1[9]+'</td><td>'+s1[10]+'</td><td>'+s1[11]+'</td></tr>');
	}
	w_c(temp_table2);
	w_c(hr);
	w_c(left_table1);
	w_c('<tr><td><input type="button" value="兵舍" onClick="parent.foot_gturn(\'build\',\'build_show\',0,null,null)" ></td><td><input type="button" value="建築技術" onClick="parent.foot_gturn(\'build\',\'build_show\',1,null,null)" ></td></tr>');
	w_c('<tr><td><input type="button" value="謀策" onClick="parent.foot_gturn(\'build\',\'build_show\',2,null,null)" ></td><td><input type="button" value="戰術研究" onClick="parent.foot_gturn(\'build\',\'build_show\',3,null,null)" ></td></tr>');
	w_c('<input type="hidden" name="f" value="build">');
	w_c('<input type="hidden" name="act" value="build_make">');
	w_c(temp_table2);
	w_c(right_table1);
	if (b.length >0) {
		var i=0;
//		for(var i=0;i<b.length;i++)
//		{
			w_c('<tr><td><table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width=50%>'+group_act_type[5]+' '+b[2]+'LV'+b[3]+'</td><td><div id="count_s'+i+'"></div></td><td><div id="count_s'+i+'_cs"><a href="javascript:parent.foot_gturn(\'job\',\'job_break\','+b[0]+',2,null)" target="mission">中斷</a> <a href="javascript:parent.group_vip_item('+b[0]+',1)" target="mission">加速</a></div></td></tr></table></td></tr>');
			eval("parent.window.clearInterval(job_list.count_s"+i+"_2)");
			creat_job_list("count_s"+i,b[1]);	
//		}
	}
	w_c('<tr id="build_show_tr" style="display:none"><td><div id="build_show" ></div></td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function group_market(a1,a3,a4)
{
	p_s_close();
	w_c(temp_table1+group_menu+temp_table2);
	w_c('<form action="wog_group.php" method="post" name="f1" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td colspan="12">資源情況</td></tr>');
	w_c('<tr class="head_td"><td>煤</td><td>木材</td><td>石塊</td><td>石油</td><td>黃金</td><td>酒</td><td>大麥</td><td>香菸</td><td>鐵</td><td>皮毛</td><td>絲線</td><td>珍珠</td></tr>');
	if(a1!="")
	{
		var s1=a1.split(",");
		w_c('<tr><td>'+s1[0]+'</td><td>'+s1[1]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td><td>'+s1[4]+'</td><td>'+s1[5]+'</td><td>'+s1[6]+'</td><td>'+s1[7]+'</td><td>'+s1[8]+'</td><td>'+s1[9]+'</td><td>'+s1[10]+'</td><td>'+s1[11]+'</td></tr>');
	}
	w_c(temp_table2);
	w_c(hr);
	w_c('<div id="mark_show"></div>');
	w_c(right_table1);
	w_c('<tr class="head_td"><td>與他方公會交易狀況</td></tr>');
	if (a3.length >0) {
		var s1 = a3.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2 = s1[i].split(",");
			w_c('<tr><td><table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width=50%>'+group_act_type[6]+' 取得'+ex_name[s2[1]]+':'+s2[2]+'</td><td><span id="count_s1'+i+'"></span><span id="count_s1'+i+'_cs"></span></td></tr></table></td></tr>');
			eval("parent.window.clearInterval(job_list.count_s1"+i+"_2)");
			creat_job_list("count_s1"+i,s2[0]);
		}
	}
	w_c('<tr class="head_td"><td>委託市場交易狀況</td></tr>');
	if (a4.length >0) {
		var s1 = a4.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2 = s1[i].split(",");
			w_c('<tr><td><table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width=50%>'+group_act_type[6]+' 取得'+ex_name[s2[1]]+':'+s2[2]+'</td><td><span id="count_s2'+i+'"></span><span id="count_s2'+i+'_cs"></span></td></tr></table></td></tr>');
			eval("parent.window.clearInterval(job_list.count_s2"+i+"_2)");
			creat_job_list("count_s2"+i,s2[0]);
		}
	}
	w_c(temp_table2);
	w_c('</form>');
	w_c('<form action="wog_group.php" method="post" name="pageform" id="pageform" target="mission"><input type="hidden" name="page" value=""><input type="hidden" name="f" value="market"><input type="hidden" name="act" value="jump"><input type="hidden" name="sh1" value=""><input type="hidden" name="sh2" value=""></form>');
	p_c();
};
function group_market_show(a1,saletotal,page,a5,a6,sh1,sh2)
{
	var str='';
	str+=left_table1;
	var temp1='<select name="f_exid" >';
	var temp2='<select name="t_exid" >';
	var temp3='<option value="" >請選擇</option>';
	for(var i=1;i<13;i++)
	{
		temp3+='<option value="'+i+'" >'+ex_name[i]+'</option>';
	}
	temp1+=temp3+'</select>';
	temp2+=temp3+'</select>';
	str+='<tr><td colspan="7">交易資源:'+temp1+' , 需求資源:'+temp2+' <input type="button" value="搜尋" onClick="parent.group_ex_sh(document.f1.f_exid.value,document.f1.t_exid.value);document.pageform.page.value=1;document.pageform.submit();"></td></tr>';
	str+='<tr><td colspan="7">'+pagesplit(saletotal,page,1)+'</td></tr>';
	str+='<tr><td colspan="7"><input type="button" value="委託資源交易('+a5+')" onClick="parent.group_market_post(event.x||event.pageX,event.y||event.pageY);"></td></tr>';
	str+='<tr><td></td><td>公會名稱</td><td>交易資源</td><td>交易數量</td><td>需求資源</td><td>需求數量</td><td>截止時間</td></tr>';
	if (a1 != "") {
		var s1 = a1.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2 = s1[i].split(",");
			str+='<tr><td ><input type="radio" name="mk_id" value="' + s2[0] + '" ></td><td >' + s2[1] + '</td><td >' + ex_name[s2[2]] + '</td><td >' + s2[3] + '</td><td >' + ex_name[s2[4]] + '</td><td >' + s2[5] + '</td><td >' + s2[6] + '</td></tr>';
		}
	}
	str+='<tr><td colspan="7"><input type="submit" value="進行運輸交易('+a6+')" onclick="parent.mouse_xy(event.x||event.pageX,event.y||event.pageY);"></td></tr>';
	str+='<input type="hidden" name="f" value="market">';
	str+='<input type="hidden" name="act" value="get1">';
	str+=temp_table2;
	group_ex_sh(sh1,sh2);
	f.getElementById("mark_show").innerHTML=str;
};
function group_ex_sh(a1,a2)
{
	f.pageform.sh1.value=a1;
	f.pageform.sh2.value=a2;
}
function group_market_post(x,y){
	w_c(build_table1);
	var temp1='<select name="f_exid" >';
	var temp2='<select name="t_exid" >';
	var temp3='';
	for(var i=1;i<13;i++)
	{
		temp3+='<option value="'+i+'" >'+ex_name[i]+'</option>';
	}
	temp1+=temp3+'</select>';
	temp2+=temp3+'</select>';
	w_c('<tr><td>提供資源:</td><td>'+temp1+'</td><td>提供數量:</td><td><input type="text" name="f_num" value="" size="10" ></td></tr>');
	w_c('<tr><td>需求資源:</td><td>'+temp2+'</td><td>需求數量:</td><td><input type="text" name="t_num" value="" size="10" ></td></tr>');
	w_c('<tr><td colspan="4"><input type="submit" value="確定送出" > <input type="button" value="取消" onClick="parent.p_s_close();"></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="market">');
	w_c('<input type="hidden" name="act" value="post">');
	p_s(x,y);
};
function group_market_get1(a1){
	w_c(build_table1);
	var s1=a1.split(",");
	w_c('<tr class="head_td"><td class=b1 colspan="4">交易目標:'+s1[1]+'</td></tr>');
	w_c('<tr><td class=b1>您將輸送資源:<font color=red>'+ex_name[s1[4]]+'</font>，數量:<font color=red>'+s1[5]+'</font></td></tr>');
	w_c('<tr><td class=b1>換取對方資源:<font color=red>'+ex_name[s1[2]]+'</font>，數量:<font color=red>'+s1[3]+'</font></td></tr>');
	w_c('<tr><td class=b1 colspan="4">此次交易將費時:'+make_time(s1[7])+'</td></tr>');
	w_c('<tr><td class=b1 colspan="4">預計交易完成日:'+s1[6]+'</td></tr>');
	w_c('<tr><td colspan="4"><input type="submit" value="確定交易" > <input type="button" value="取消交易" onClick="parent.p_s_close();"></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="market">');
	w_c('<input type="hidden" name="act" value="get2">');
	w_c('<input type="hidden" name="mk_id" value="'+s1[0]+'">');
	p_s();
};
function group_build_show(a,b,c,d){
	var temp="";
	temp+=build_table1+"<tr><td >";
	for(var i=0;i<build_set[a].length;i++)
	{
		temp+=" <a href=javascript:parent.foot_gturn(\'build\',\'build_show\',"+a+","+build_set[a][i][0]+");>"+build_set[a][i][1]+"</a>";
	}
	temp+="</td></tr>";

	temp+="<tr class='head_td'><td>目前狀態</td></tr>";
	if (b.length > 0) {
		temp+="<tr><td class=b1>"+b[0]+" LV"+b[1]+" : "+b[2]+"</td></tr>";
	}
	if (c.length > 0) {
		temp+="<tr class='head_td'><td>下一級</td></tr>";
		temp+="<tr><td class=b1><a href=javascript:parent.act_gclick(\'build\',\'build_make\',"+c[0]+");>"+c[2]+" LV"+c[3]+"</a> : "+c[4]+"<br>限制公會等級: "+c[6]+"<br>需求資源 → "+d+"<br>需求時間 → "+make_time(c[5])+"</td></tr>";
	}
	temp+=temp_table2;
	f.getElementById("build_show_tr").style.display="block";
	f.getElementById("build_show").innerHTML=temp;
};
function group_job_list(a1){
	w_c(temp_table1+group_menu+temp_table2);
	w_c('<br>'+temp_table1);
	w_c('<tr class="head_td"><td>會員名稱</td><td>作業內容</td><td>消耗時間</td></tr>');
	var temp_str1="";
	if (a1.length >0) {
		var s1 = a1.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2 = s1[i].split(",");
			temp_str1+='<tr><td>'+s2[4]+'</td>';
			switch(s2[0])
			{
				case "1":
					temp_str1+='<td>'+group_act_type[5]+' '+s2[2]+'LV'+s2[3]+'</td><td><span id="count_s'+i+'"></span><span id="count_s'+i+'_cs"></span></td>';
				break;
				case "2":
					var check_link="";
					switch(s2[2])
					{
						case '1':
						case '2':
							check_link='<a href="javascript:parent.act_gclick(\'job\',\'job_wp\','+s2[5]+')" target="mission" title="查看軍報">'+group_act_type[s2[2]]+'</a> → '+s2[3];
						break;
						case '4':
							check_link=group_act_type[s2[2]]+' → '+s2[3];
						break;
						case '3':
							check_link=group_act_type[s2[2]];
						break;
					}
					temp_str1+='<td>'+check_link+'</td><td ><span id="count_s'+i+'"></span><span id="count_s'+i+'_cs"></span></td>';
				break;
				case "3":
					temp_str1+='<td>'+group_act_type[0]+' '+wp_name[s2[2]]+':'+s2[3]+'</td><td><span id="count_s'+i+'"></span><span id="count_s'+i+'_cs"></span></td>';
				break;
				case "4":
					temp_str1+='<td>'+group_act_type[6]+' 取得'+ex_name[s2[2]]+':'+s2[3]+'</td><td><span id="count_s'+i+'"></span><span id="count_s'+i+'_cs"></span></td>';
				break;
			}
			temp_str1+='</tr>';
			eval("parent.window.clearInterval(job_list.count_s"+i+"_2)");
			creat_job_list("count_s"+i,s2[1]);	
		}
	}
	w_c(temp_str1+temp_table2);
	p_c();
};
function group_vip_item(a1,a2)
{
	w_c(temp_table1);
	w_c('<tr class="head_td"><td>選項</td><td>名稱</td><td>效果</td></tr>');
	switch(a2)
	{
		case 1:
			w_c('<tr><td><input type="radio" name="id" value="2297"></td><td>1小時加速研究</td><td>縮短公會1小時研究時間</td></tr>');
			w_c('<tr><td><input type="radio" name="id" value="2298"></td><td>3小時加速研究</td><td>縮短公會3小時研究時間</td></tr>');
			w_c('<tr><td><input type="radio" name="id" value="2299"></td><td>5小時加速研究</td><td>縮短公會5小時研究時間</td></tr>');
			break;
		case 2:
			w_c('<tr><td><input type="radio" name="id" value="2300"></td><td>1小時加速行軍</td><td>縮短公會1小時研究行軍</td></tr>');
			w_c('<tr><td><input type="radio" name="id" value="2301"></td><td>3小時加速行軍</td><td>縮短公會3小時研究行軍</td></tr>');
			w_c('<tr><td><input type="radio" name="id" value="2302"></td><td>5小時加速行軍</td><td>縮短公會5小時研究行軍</td></tr>');
			break;
		case 3:
			w_c('<tr><td><input type="radio" name="id" value="2303"></td><td>1小時加速造兵</td><td>縮短公會1小時研究造兵</td></tr>');
			w_c('<tr><td><input type="radio" name="id" value="2304"></td><td>3小時加速造兵</td><td>縮短公會3小時研究造兵</td></tr>');
			w_c('<tr><td><input type="radio" name="id" value="2305"></td><td>5小時加速造兵</td><td>縮短公會5小時研究造兵</td></tr>');
			break;
	}
	w_c('<tr><td colspan="3"><input type="submit" value="使用" > | <input type="button" value="關閉" onClick="parent.p_s_close();" ></td></tr>');
	w_c('<tr><td colspan="3">使用前請確認背包裡面是否有該物品，若無請至<a class="uline" onclick="parent.p_s_close();parent.act_click(\'mall\',\'view\');">加值商城購買</a></td></tr>');
	w_c('<input type="hidden" name="f" value="vip">');
	w_c('<input type="hidden" name="act" value="item">');
	w_c('<input type="hidden" name="temp_id" value="'+a1+'">');
	w_c(temp_table2);
	gshow_item_lay();
};
function show_make_time(time)
{
	var temp="剩餘"+time;
	return temp;
};
function make_time(time)
{
	var m=Math.floor(time/60);
	var h=0;
	if(m>=60)
	{
		h=Math.floor(m/60);
		m=m%60;
	}
	var se=Math.floor(time%60);
	return 	h+":"+m+":"+se;
};
function creat_job_list(a1,a2)
{
	eval('job_list.'+a1+'_1='+a2);
	eval('job_list.'+a1+'_2=setInterval("j_count_time(\''+a1+'\')",1000)');
};
function j_count_time(a1){
	var str=''
	+'if(job_list.'+a1+'_1<=0){'
	+'	window.clearInterval(job_list.'+a1+'_2);'
	+'	f.getElementById("'+a1+'").innerHTML ="";'
	+'	f.getElementById("'+a1+'_cs").innerHTML ="完成";'
	+'	job_list.'+a1+'_1=null;'
	+'	job_list.'+a1+'_2=null;'
	+'}'
	+'else{'
	+'job_list.'+a1+'_1--;'
	+'if (f.getElementById("'+a1+'")) {'
	+'	f.getElementById("'+a1+'").innerHTML = show_make_time(make_time(job_list.'+a1+'_1));'
	+'}else{'
	+' window.clearInterval(job_list.'+a1+'_2);'
	+'	job_list.'+a1+'_1=null;'
	+'	job_list.'+a1+'_2=null;}'
	+'}';
	eval(str);
};
