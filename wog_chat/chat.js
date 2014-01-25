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
var messput = new Array;
var sum=0,lastspeed=0,set_lock1=0,set_lock2=0,set_lock3=0,set_lock4=0,chat_time=15000;
function goldset(fman,cman,talkms,hero,a1) {
	//for (var line=19;line>=0;line--) {messput[line+1]=messput[line];}
	//alert(talkms);
	var now = new Date();
	var bg="";
	var table_bg="";
	var temp_tag="";
	var temp_tag1="";
	if(cman=="1")
	{
		bg="#66ccff";
	}
	if(cman=="0")
	{
		bg="#ffffff"
	}
	if(cman=="2")
	{
		bg="#ffcc00"
	}
	if(sum==0)
	{
		message_cls(chat_view);	
	}
	if(cman=="3")
	{
		table_bg="bgcolor=\"#00400A\"";
		temp_tag="(私) <a href=\"javascript:parent.parent.parent.yesname('"+a1+"');parent.chat_in.document.f1.chat_temp.focus()\" target=chat_in>"+a1+"</a> to ";
	}
	if(hero=="1")
	{
		temp_tag1="<font color=#FFFF33>[英雄]</font>";
	}
	messput[0]='<div align=left><table width="100%" cellspacing="0" cellpadding="0" '+table_bg+'><tr><td class="b1"><font color='+bg+'><B>'+temp_tag1+temp_tag+"<a href=\"javascript:parent.parent.parent.yesname('"+fman+"');parent.chat_in.document.f1.chat_temp.focus()\" target=chat_in>"+fman+'</a> ：'+talkms+'</B></font>('+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds()+')</td></tr><tr><td width="100%" height="1" bgcolor="#A2A9B8"></td></tr></table></div>';
	chat_view.document.write(messput[0]);
	if(sum==495)
	{		
		chat_view.document.write('<div align=left><table width="100%" cellspacing="0" cellpadding="0" '+table_bg+'><tr><td class="b1"><B>離更新頁面還有5行</B>('+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds()+')</td></tr><tr><td width="100%" height="1" bgcolor="#A2A9B8"></td></tr></table></div>');
	}
	sum++;
	chat_view.scroll(0,65535);
	if(sum==500)
	{
		sum=0;
	}
};
function message_cls(a,bline) {
	var s="";
	if(a==null)
	{
		s=parent.wog_view;
	}else
	{
		s=a;
	}
	if(bline==null)
	{
		bline=2;
	}
	s.document.close();
    s.document.write('<html>');
    s.document.write('<head>');
    s.document.write('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
	s.document.write('<style type=text/css>');
    s.document.write('td {font-family : verdana,Arial,Helvetica ;font-size : 12px;	text-align : center;}');
    s.document.write('.b1 {text-align : left;font-size : 12px;}');
    s.document.write('</style>');
    s.document.write('</head>');
    s.document.write('<body bgcolor="#000000" text="#EFEFEF" link="#EFEFEF" vlink="#EFEFEF" alink="#EFEFEF" style="border: '+bline+' inset;">');
};
function chat_lock(type)
{
	var f=chat_in.document.f1;
	switch(type)
	{
		case 1:
			if(set_lock1==0)
			{
				set_lock1=1;
				f.set_lock1.value="1";
			}else
			{
				set_lock1=0;
				f.set_lock1.value="0";				
			}
			break;
		case 2:
			if(set_lock2==0)
			{
				set_lock2=1;
				f.set_lock2.value="1";
			}else
			{
				set_lock2=0;
				f.set_lock2.value="0";				
			}
			break;
		case 3:
			if(set_lock3==0)
			{
				set_lock3=1;
				f.set_lock3.value="1";
			}else
			{
				set_lock3=0;
				f.set_lock3.value="0";				
			}
			break;
		case 4:
			if(set_lock4==0)
			{
				set_lock4=1;
				f.set_lock4.value="1";
			}else
			{
				set_lock4=0;
				f.set_lock4.value="0";				
			}
			break;
	}
	var temp_message="";
	if(set_lock1==1)
	{
		temp_message+="『<font color=#80ff80>一般頻關閉中</font>』";
	}
	if(set_lock2==1)
	{
		temp_message+="『<font color=#80ff80>私頻關閉中</font>』";
	}
	if(set_lock3==1)
	{
		temp_message+="『<font color=#80ff80>公會頻關閉中</font>』";
	}
	if(set_lock4==1)
	{
		temp_message+="『<font color=#80ff80>隊頻關閉中</font>』";
	}
	if(temp_message!="")
	{
		goldset("系統","2","<font color=red>"+temp_message+"，請在頻道設定中設定頻道開啟聊天</font>","0");
	}
};
function chat_set()
{
	var str='';
	var lock_ch1="",lock_ch2="",lock_ch3="",lock_ch4="";
	var temp_f="";
	if (parent.parent.UI.set_frame==1) {
		temp_f="parent.wog_view.frame_chat";
	}
	else
	{
		temp_f="parent.foot.frame_chat";
	}
	if(set_lock1==1){lock_ch1="checked";}
	if(set_lock2==1){lock_ch2="checked";}
	if(set_lock3==1){lock_ch3="checked";}
	if(set_lock4==1){lock_ch4="checked";}
	str+='<table bgcolor=#000000 width="300">';
	str+='<tr><td><input type="checkbox" name="lock_chat" onclick="'+temp_f+'.chat_lock(1);" '+lock_ch1+'>關閉一般頻</td>';
	str+='<td><input type="checkbox" name="lock_chat" onclick="'+temp_f+'.chat_lock(2);" '+lock_ch2+'>關閉私頻</td></tr>';
	str+='<tr><td><input type="checkbox" name="lock_chat" onclick="'+temp_f+'.chat_lock(3);" '+lock_ch3+'>關閉公會頻</td>';
	str+='<td><input type="checkbox" name="lock_chat" onclick="'+temp_f+'.chat_lock(4);" '+lock_ch4+'>關閉隊頻</td></tr>';
	str+='<tr><td colspan="2"><a href="javascript:'+temp_f+'.chat_set_close();" target="mission">關閉</a></td></tr>';
	str+='</table>';
	parent.parent.parent.f.getElementById("wog_chat_set").innerHTML=str;
};
function chat_set_close()
{
	parent.parent.f.getElementById("wog_chat_set").innerHTML="";
};
function chat_refresh()
{
	if(!set_lock1 || !set_lock2 || !set_lock3 || !set_lock4)
	{
		chat_list.document.location="./wog_chat_list.php?lastspeed="+lastspeed+"&set_lock1="+set_lock1+"&set_lock2="+set_lock2+"&set_lock3="+set_lock3+"&set_lock4="+set_lock4;
	}
	window.setTimeout("chat_refresh()",chat_time);
};
window.setTimeout("chat_refresh()",chat_time);