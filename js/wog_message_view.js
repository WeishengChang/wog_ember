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
//###### message #######
function message(a1)
{
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	if(!a1)
	{
		a1="";
	}
	w_c('<tr><td colspan=2 class=b1>'+message_menu+'</td></tr>');
	w_c('<tr><td>遊戲中的帳號</td><td class="b1"><input type="text" name="name" size="12" value="'+a1+'"></td></tr>');
	w_c('<tr><td>主旨</td><td class="b1"><input type="text" name="subject" size="15"></td></tr>');
	w_c('<tr><td>內容</td><td class="b1"><textarea cols="30" rows="7" name="body"></textarea><br><input type="submit" value="送出" onClick="parent.group_chang_book(this.form.subject);parent.group_chang_book(this.form.body);"></td></tr>');
//	w_c('<tr><td colspan="2">訊息是發到論壇上對方需到論壇上才能收到訊息</td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="message">');
	w_c('<input type="hidden" name="act" value="add">');
	w_c('</form>');
	p_c();
};
function message_box_list(a1,a2,a3)
{
	w_c('<form action="wog_act.php" method="post" name="pageform" target="mission">');
	pagesplit(a3,a2);
	w_c('<input type="hidden" name="page" value="1">');
	w_c('<input type="hidden" name="f" value="message">');
	w_c('<input type="hidden" name="act" value="list">');
	w_c('</form>');
	w_c(temp_table1);
	w_c('<tr><td colspan=3 class=b1>'+message_menu+'</td></tr>');
	w_c('<tr><td>帳號</td><td>主旨</td><td>時間</td></tr>');
	if(a1)
	{
		var s1=a1.split(";");
		var css_style="";
		var open_style="";
		for(var i=0;i<s1.length;i++)
		{
			
			var s2=s1[i].split(",");
			if(s2[3]==0)
			{
				css_style='style="font-weight: bold;"';
				open_style="<font color=red>+</font>";
			}else
			{
				
				css_style='',open_style='';
			}
			w_c('<tr '+css_style+'><td><a href="javascript:parent.message(\''+s2[1]+'\')">'+s2[1]+'</a></td><td class=b1>'+open_style+' <a href="javascript:parent.act_click(\'message\',\'vbody\','+s2[0]+')" target="mission">'+s2[2]+'</a></td><td>'+s2[4]+'</td></tr>');
		}		
	}
	w_c(temp_table2);
	p_c();
};
function message_vbody(a1)
{
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td colspan=2 class=b1>'+message_menu+'</td></tr>');
	if (a1) {
		var s1 = a1.split(",");
		var temp=s1[2];
		while(temp.indexOf("[n]") > 0)
		{
			temp=temp.replace("[n]","<br>");
		}
		w_c('<tr><td width=120>遊戲中的帳號</td><td class="b1">'+s1[0]+'</td></tr>');
		w_c('<tr><td>主旨</td><td class="b1">'+s1[1]+'</td></tr>');
		w_c('<tr><td>內容</td><td class="b1">'+temp+'</td></tr>');
	}
	w_c(temp_table2);
	w_c("<br>"+temp_table1);
	if(s1[1].indexOf("RE") <= 0)
	{
		s1[1]="RE:"+s1[1];
	}
	w_c('<tr><td width=120>遊戲中的帳號</td><td class="b1"><input type="text" name="name" size="12" value="'+s1[0]+'"></td></tr>');
	w_c('<tr><td>主旨</td><td class="b1"><input type="text" name="subject" size="15" value="'+s1[1]+'"></td></tr>');
	w_c('<tr><td>內容</td><td class="b1"><textarea cols="30" rows="7" name="body"></textarea><br><input type="submit" value="回覆" onClick="parent.group_chang_book(this.form.subject);parent.group_chang_book(this.form.body);"></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="message">');
	w_c('<input type="hidden" name="act" value="add">');
	w_c('</form>');
	p_c();
};
function message_item_list(a1){
	w_c(temp_table1);
	w_c('<tr><td class=b1>'+message_menu+'</td></tr>');
	w_c(temp_table2);
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr class="head_td"><td>物品名稱</td><td>物品名稱</td><td>物品名稱</td></tr>');
	if(a1!="")
	{
		var s1=a1.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var arm_view_color ="";
			if(s2[4]=="1"){arm_view_color="bgcolor="+nosend;}
			if((i+1)%3==1)
			{
				w_c('<tr>');
			}
			w_c('<td '+arm_view_color+'><a href="javascript:parent.act_click(\'message\',\'item_get\','+s2[0]+');" target="mission">領取</a>→<a class=uline onclick=parent.arm_show(event.ctrlKey,"'+s2[2]+'","'+s2[1]+'") >'+s2[2]+'</a>*<span id="show_message_'+s2[0]+'">'+s2[3]+'</span></td>');
			if((i+1)%3==0)
			{
				w_c('</tr>');
			}
		}
	}
	w_c('<tr><td colspan="3" class=b1><ol><li>信箱內物品只能存放10天，超過時間將會自動刪除</li></li></ol></td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function friend_list(a1,a2,a3)
{
	w_c('<form action="wog_act.php" method="post" name="pageform" target="mission">');
	pagesplit(a3,a2);
	w_c('<input type="hidden" name="page" value="1">');
	w_c('<input type="hidden" name="f" value="friend">');
	w_c('<input type="hidden" name="act" value="list">');
	w_c('</form>');
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td colspan=4 class=b1>'+message_menu+'</td></tr>');
	w_c('<tr><td></td><td>帳號</td><td>等級</td><td>冒險地</td></tr>');
	if(a1)
	{
		var s1=a1.split(";");
		var css_style="";
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			if(s2[4]==0)
			{
				css_style='style="color:#808080;"';
			}else
			{
				
				css_style='';
			}
			w_c('<tr '+css_style+'><td><a href="javascript:parent.message(\''+s2[1]+'\')">發信</a> <a href="javascript:parent.act_click(\'friend\',\'del\','+s2[0]+')">刪除</a></td><td>'+s2[1]+'</td><td>'+s2[2]+'</td><td>'+sec[s2[3]][0]+'</td></tr>');
		}		
	}
	w_c('<tr><td colspan=4 class=b1>遊戲中的帳號:<input type="text" name="name" size="12" ><input type="submit" value="加入好友" ></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="friend">');
	w_c('<input type="hidden" name="act" value="add">');
	w_c('</form>');
	p_c();
};