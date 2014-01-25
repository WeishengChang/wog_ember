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
function setup_mname(name) {m_name=name;};
function setup_jmmoney(money) {cp_jmmoney=money;};
function get_name() {return p_name;};
function get_mname() {return m_name;};
//##### page head begin #####
function message_cls(a,bline) {
	f=parent.wog_view.document;
	if(bline==null)
	{
		bline=2;
	}
	if(a!=null)
	{
		a.close();
	    a.write('<html>');
	    a.write('<head>');
	    a.write('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
	    a.write('<meta http-equiv=Cache-Control content="no-cache">');
		a.write('<style type=text/css>');
	    a.write('td {font-family : verdana,Arial,Helvetica ;font-size : 12px;text-align : center;}');
	    a.write('.b1 {text-align : left;}');
	    a.write('.a1 {text-align : center;}');
	    a.write('</style>');
	    a.write('</head>');
	    a.write('<body bgcolor="#000000" text="#EFEFEF" link="#EFEFEF" vlink="#EFEFEF" alink="#EFEFEF" style="border: '+bline+' inset;" >');
	}
	else{
		set_window();
		_docWidth=UI.window_w;
		_docHeight=UI.window_h;
		var online_top=0;
		var online_left=_docWidth-155;
		var menu_top=_docHeight-118;
		var menu_left=0;
		var chat_top=_docHeight-182;
		var chat_left=330;
		f.close();
	    f.write('<html>');
	    f.write('<head>');
	    f.write('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
	    f.write('<meta http-equiv=Cache-Control content="no-cache">');
		f.write('<link href="./css/wog.css" rel="stylesheet" type=text/css>');
	    f.write('</head>');
	    f.write('<body bgcolor="#000000" text="#EFEFEF" link="#EFEFEF" vlink="#EFEFEF" alink="#EFEFEF" style="border: '+bline+' inset;" >');
		f.write('<div id="wog_center_all" onclick="parent.lay_show(\'wog_center_all\')" style="position:absolute; left:1px; top:1px;Z-INDEX: 1;opacity :0.94;filter:alpha(opacity=94);width:'+UI.set_center_w+'"><table  border="0" style="width: 100%" align="left" bgcolor="#000000"><tr><td align="left"><div id="wog_center" ></div></td></tr></table></div>');
		f.write('<div id="wog_menu" onclick="parent.lay_show(\'wog_menu\')" style="position:absolute; left:'+menu_left+'px; top:'+menu_top+'px;Z-INDEX: 202"></div>');
		f.write('<div id="wog_chat" onclick="parent.lay_show(\'wog_chat\')" style="position:absolute; left:'+chat_left+'px; top:'+chat_top+'px;Z-INDEX: 198"></div>');
		f.write('<div id="wog_online" onclick="parent.lay_show(\'wog_online\')" style="position:absolute; left:'+online_left+'px; top:'+online_top+'px;Z-INDEX: 200"></div>');
		f.write('<div id="wog_select" style="position: absolute;left: '+((_docWidth/2)-175)+'; top: '+((_docHeight/2)-50)+'; Z-INDEX: 300;opacity :0.9;filter:alpha(opacity=90);"></div>');
		f.write('<div id="wog_well_box" style="position:absolute; Z-INDEX: 1000"></div>');
		f.write('<div id="wog_message_box" style="position:absolute; Z-INDEX: 1001"></div>');
		f.write('<div id="wog_item_box" style="position:absolute; Z-INDEX: 1002"></div>');
		f.write('<div id="wog_chat_set" style="position: absolute;left: '+((_docWidth/2)-150)+'; top: '+((_docHeight/2)-50)+'; Z-INDEX: 302;opacity :0.8;filter:alpha(opacity=80);"></div>');
		f.write('<div id="wog_mercenary_job" style="position: absolute;left:1; top: 10; Z-INDEX: 303;opacity :0.9;filter:alpha(opacity=90);display:none"></div>');
	}
};
function p_s(x,y,z){
	var w=350;
	if(z)
	{
		w=z;
	}
	f.getElementById("wog_select").innerHTML='<form action="wog_group.php" method="post" name="f102" target="mission"><table width="'+w+'" border="0" cellspacing="0" cellpadding="1" bgcolor='+tr_bgcolor2+'><tr><td width="100%">'+wog_center_html+'</td></tr></table></form>';
	wog_center_html="";
	if(!x){x=UI.mouse_x;}
	if(!y){y=UI.mouse_y;}
	set_div_x_y(x+15,y-20,"wog_select");
};
function p_as(x,y,z){
	var w=350;
	if(z)
	{
		w=z;
	}
	f.getElementById("wog_select").innerHTML='<form action="wog_act.php" method="post" name="f101" target="mission"><table width="'+w+'" height="" border="0" cellspacing="0" cellpadding="1" bgcolor='+tr_bgcolor2+' id="wog_as"><tr><td width="100%">'+wog_center_html+'</td></tr></table></form>';
	wog_center_html="";
	if(!x){x=UI.mouse_x;}
	if(!y){y=UI.mouse_y;}
	set_div_x_y(x+15,y-20,"wog_select");
};
function w_c(a){
	wog_center_html+=a;
};
function w_o(a){
	wog_online_list_html+=a;
};
function w_m(a){
	wog_menu_html+=a;
};
function w_chat(a){
	wog_chat_html+=a;
};
function p_c(){
	f.getElementById("wog_center").innerHTML=wog_center_html;
	wog_center_html="";
};
function p_nc(){
	if(f.getElementById("a4"))
	{
		f.getElementById("a4").innerHTML+=wog_center_html;
	}
	else
	{
		f.getElementById("wog_center").innerHTML+=wog_center_html;		
	}
	wog_center_html="";
};
function p_o(){
	f=parent.wog_view.document;
	if(f.getElementById("wog_online"))
	{
		f.getElementById("wog_online").innerHTML=wog_online_list_html;
	}
	wog_online_list_html="";
};
function p_m(){
	if(UI.set_frame==1)
	{
		f.getElementById("wog_menu").innerHTML=wog_menu_html;
	}
	else
	{
		dfoot.getElementById("wog_menu").innerHTML=wog_menu_html;
	}
	wog_menu_html="";
};
function p_chat(){
	if (UI.set_frame==1) {
		f.getElementById("wog_chat").innerHTML=wog_chat_html;
	}
	else
	{
		dfoot.getElementById("wog_chat").innerHTML=wog_chat_html;
	}
	wog_chat_html="";
};
function p_s_close(){
	f.getElementById("wog_select").innerHTML='';
};
function lay_show(a)
{
	f.getElementById("wog_center_all").style.zIndex="1";
	f.getElementById("wog_menu").style.zIndex="202";
	f.getElementById("wog_chat").style.zIndex="198";
	f.getElementById("wog_online").style.zIndex="200";
	if(a!='')
	{
		f.getElementById(a).style.zIndex="300";
	}
};
//###### tip message ######
function job_end(a,message,type,item_id,item_num)
{
	if(a==2)
	{
		d_a_name="";d_body_name="";d_head_name="";d_hand_name="";d_foot_name="";d_item_name="";d_item2_name="";
	}
	var temp = '';
	temp = '<br>'+temp_table1+'<tr bgcolor='+tr_bgcolor2+'><td>'+job_s[a]+'!!</td></tr>';
	if(message)
	{
		while(message.indexOf("&n") > 0)
		{
			message=message.replace("&n","<br>");
		}
		temp += '<tr><td>'+message+'</td></tr>';
	}
	temp +=temp_table2;
	if(type)
	{
		switch (type)
		{
			case 1:
				if(item_id!=null)
				{
					if(item_num==-1)
					{
						item_num=f.getElementById("show_message_"+item_id).innerHTML;
						item_num=parseInt(item_num)-1;
					}
					f.getElementById("show_message_"+item_id).innerHTML=item_num;
				}
				if(a>0){
					f.getElementById("show_message").innerHTML=temp;
				}
			break;
			case 2:
				w_c(temp);
				p_nc();
			break;		
			case 3:
				temp=temp_table1+'<tr bgcolor='+tr_bgcolor2+'><td>'+job_s[a]+'</td></tr>'+temp_table2+'<a href="javascript:parent.p_s_close();" target="mission">關閉</a>';
				w_c(temp);
				p_as();
			break;	
		}
	}
	else
	{
		w_c(temp);
		p_c();
	}
};
function try_login()
{
	if(parent.foot.document.f1)
	{
		var a=parent.foot.document.f1;
		a.f.value="chara";
		a.act.value="try_login";
		a.submit();
	}else
	{
		window.setTimeout("try_login()",1000);
	}
};
function p1()
{
	parent.peolist.document.location="wog_etc.php?f=peo";
};
function mercenary_start()
{
	if(mercenary_set==1)
	{
		parent.peolist.document.location="wog_etc.php?f=mercenary";
	}
};
function mercenary_f()
{
	job_work.mercenary_start=setInterval("mercenary_start()",mercenary_time);
};
