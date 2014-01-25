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
//###### 分頁 begin #####
function pagesplit(saletotal,page,type)
{
	var temp_html="";
	temp_html+=page_table1+'<tr><td>分頁:';
	var totalpage=0;
	if(saletotal%8==0)
	{
		totalpage=saletotal/8;
	}else
	{
		totalpage=(saletotal/8)+1;
	}
	for(var i=1;i<=totalpage;i++)
	{
		if(i==page)
		{
			temp_html+=' '+i+' ';
		}else
		{
			temp_html+=' <a href="javascript:parent.page_jump(\''+i+'\')" >'+i+'</a> ';
		}
	}
	temp_html+='</td></tr>'+temp_table2;
	if(type==null)
	{
		w_c(temp_html);
	}
	else
	{
		return temp_html;
	}
};
function page_jump(s)
{
	parent.wog_view.document.pageform.page.value=s;
	parent.wog_view.document.pageform.submit();
};
function sel_type(s)
{
	var fp=parent.wog_view.document.pageform;
	if(fp.key != null){fp.key.value='';}
	fp.type.value=s;
	fp.submit();
};
function system_view(s1,s2,type)
{
	if(type!=null)
	{
		w_c(temp_table1+group_menu+temp_table2);
		w_c(hr);
	}
	w_c(temp_table1);
	w_c('<tr class="head_td"><td>內容</td><td>發生時間</td></tr>');
	if(s1!="")
	{
		var a1=s1.split(";");
		var a2=s2.split(";");
		for(var i=0;i<a1.length;i++)
		{
			w_c('<tr><td class=b1>'+a1[i]+'</td><td>'+a2[i]+'</td></tr>');
		}
	}else
	{
		w_c('<tr><td colspan="8" >尚未發生任何事件</td></tr>');
	}
	w_c(temp_table2);
	p_c();
};
function pk_view(s,money)
{
	if(s==1)
	{
		var pk_yes="checked";
		var pk_no="";
	}else
	{
		var pk_yes="";
		var pk_no="checked";
	}
	w_c(build_table1);
	w_c('<tr><td >參加PK</td><td >PK金額</td></tr>');
	w_c('<tr><td ><input type="radio" name="pk_setup" value="1" '+pk_yes+'>YES <input type="radio" name="pk_setup" value="0" '+pk_no+'>NO </td><td ><input type="text" name="pk_money" value="'+money+'" size="7" maxlength="7"></td></tr>');
	w_c('<tr><td colspan="2" ><input type="submit" value="確定"> <input type="button" value="取消" onClick="parent.p_s_close();"></td></tr>');
	w_c('<tr><td colspan="2" >PK金額設定最高100000最低1000,身上現金低於最低金額不能參加PK,系統會自動設定不參加PK賽</td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="pk">');
	w_c('<input type="hidden" name="act" value="setup">');
	p_as();
};
function id_admin(a1,a2)
{
	var te="document.f1";
	w_c('<form action="wog_act.php" method="post" target="mission" name="f1">');
	w_c(left_table1);
	w_c('<tr class="head_td"><td>帳號</td><td>密碼</td><td>操作</td></tr>');
	w_c('<tr><td><input type="text" size="12" maxlength="12" name="id"></td><td ><input type="password" size="12" name="password"></td><td><input type="submit" value="角色自殺" onclick="'+te+'.f.value=\'chara\';'+te+'.act.value=\'kill\';"></td></tr>');
	w_c('<tr class="head_td"><td>新密碼</td><td>舊密碼</td><td>操作</td></tr>');
	w_c('<tr><td><input type="text" size="12" maxlength="12" name="new_pw"></td><td ><input type="password" size="12" name="old_pw"></td><td><input type="submit" value="改變密碼" onclick="'+te+'.f.value=\'chara\';'+te+'.act.value=\'chpw\';"></td></tr>');
	w_c('<tr class="head_td"><td colspan="3">其他</td></tr>');
	w_c('<tr><td colspan="3" >補發密碼 <input type="text" size="25"  name="email"> (請輸入您創立角色的EMAIL) <input type="button" value="送出" onclick="document.f3.email.value='+te+'.email.value;document.f3.submit();"></td></tr>');
	w_c('<tr><td colspan="3" >更改帳號名稱 <input type="text" size="20"  name="p_name"> (請輸入新名稱,此功能需消耗6點數) <input type="submit" value="送出" onclick="'+te+'.f.value=\'mall\';'+te+'.act.value=\'chgid\';"></td></tr>');
	w_c('<tr><td colspan="3"><input type="checkbox" onclick="parent.set_well_box()" '+return_well_box()+'>不再顯示友善消息</td></tr>');;
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="">');
	w_c('<input type="hidden" name="act" value="">');
	w_c('</form>');
	if(a1!="" && a2!="")
	{
		var te="document.f2";
		w_c('<form action="wog_act.php" method="post" target="msn" name="f2">');
		w_c(right_table1);
		w_c('<tr class="head_td"><td colspan="2">推薦好友</td></tr>');
		w_c('<tr><td colspan="2"><input type="text" size="60" onclick="parent.oCopy(this)" value="http://www.et99.net/wog4/index.php?recomm='+Gookie("wog_cookie")+'"><br>您可以複製上面的連結，轉發給您的好友註冊，透過您的連結註冊進來的玩家，每次升級時會您會獲得系統獎勵回饋</td></tr>');
		w_c('<tr class="head_td"><td colspan="2" colspan="2">邀請函</td></tr>');
		w_c('<tr><td colspan="2">您也可以透過系統，替您發邀請函及註冊連結給好友<br><input type="text" size="30" name="email[]"> (請輸入EMAIL)<br><input type="text" size="30" name="email[]"> (請輸入EMAIL)<br><input type="text" size="30" name="email[]"> (請輸入EMAIL)<br><input type="text" size="30" name="email[]"> (請輸入EMAIL)<br><input type="text" size="30" name="email[]"> (請輸入EMAIL)<br><input type="submit" value="寄出邀請函" onclick="'+te+'.f.value=\'recomm\';'+te+'.act.value=\'mail\';"></td></tr>');
		w_c('<tr class="head_td"><td>MSN、QQ邀請函</td><td>郵箱聯絡人</td></tr>');
		w_c('<tr><td class=b1>您也可以透過系統，替您MSN上的好友發出邀請<br>MSN帳號: <input type="text" size="15" name="msn_id"><br>MSN密碼: <input type="password" size="15" name="msn_password"><br><input type="submit" value="MSN邀請函" onclick="'+te+'.f.value=\'recomm\';'+te+'.act.value=\'msn\';'+te+'.action=\'http://bbs.et99.org/wog4/wog_etc.php\'">(若您msn在線，系統將會暫時將您登出msn幾秒鐘發送邀請函)'+hr);
		w_c('您也可以透過系統，替您QQ上的好友發出邀請<br>QQ帳號: <input type="text" size="15" name="qq_id"><br>QQ密碼: <input type="password" size="15" name="qqpwd"><br><input type="submit" value="QQ邀請函" onclick="'+te+'.f.value=\'recomm\';'+te+'.act.value=\'qq\';'+te+'.action=\'http://bbs.et99.org/wog4/wog_etc.php\';parent.md5_3(this.form.qqpwd.value);"></td>');
		w_c('<td class=b1 align="top">您也可以透過系統，替您<a href="./wog_gmail.php" target="_blank">GMAL郵箱</a> , <a href="./wog_yahoo.php" target="_blank">Yahoo郵箱</a>的聯絡人發出邀請<br>↓或其他郵箱↓<br>郵箱帳號: <input type="text" size="15" name="mail_id"> ');
		w_c('<select id="mailtype" name="mailtype">');
		w_c('<option value="sina">@sina.com</option>');
		w_c('<option value="tom">@tom.com</option>');
		w_c('</select><br>郵箱密碼: <input type="password" size="15" name="mail_password"><br><input type="submit" value="寄出邀請函" onclick="'+te+'.f.value=\'recomm\';'+te+'.act.value=\'etcmail\';'+te+'.action=\'wog_act.php\'"></td></tr>');
		w_c('<tr class="head_td2"><td colspan="2"><b>系統不會保存您的帳號密碼，也不會保存您好友的郵箱</b></td></tr>');
		w_c(temp_table2);
		w_c('<input type="hidden" name="f" value="">');
		w_c('<input type="hidden" name="act" value="">');
		w_c('<input type="hidden" name="uid" value="'+Gookie("wog_cookie")+'">');
		w_c('<input type="hidden" name="email" value="'+a2+'">');
		w_c('<input type="hidden" name="pname" value="'+a1+'">');
		w_c('<input type="hidden" name="qq_password" value="">');
		w_c('</form>');
	}
		w_c('<form action="wog_etc.php" method="get" target="mission" name="f3">');
		w_c('<input type="hidden" name="email" value="">');
		w_c('<input type="hidden" name="f" value="password">');
		w_c('</form>');
	p_c();
};
function oCopy(obj){ 
	obj.select(); 
	js=obj.createTextRange(); 
	js.execCommand("Copy");
}
//###### event begin ######
function event()
{
	var now_time=new Date();
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td>站長要考驗大家是否有認真在玩</td></tr>');
	w_c('<tr><td colspan="2"><img id="enent_code" src="wog_etc.php?f=confirm&time='+now_time.getTime()+'"><br><a href=javascript:parent.recode();>看不清楚，重新獲取驗證碼</a></td></tr>');
	w_c('<tr><td>請輸入安全驗證碼:<input type="text" name="sec_code" size="8">(沒有數字，不分大小寫)</td></tr>');
	w_c('<tr><td colspan="2"><input class="text" type="button" value="填好答案了,放我過關吧!!" onClick="parent.foot_turn(\'event\',\'\',this.form.sec_code.value,\'\')"></td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function recode()
{
	var now_time=new Date();
	f.getElementById("enent_code").src='wog_etc.php?f=confirm&time='+now_time.getTime();
}
//###### xmas_happy ######
function xmas_happy(s)
{	
	var temp='<center>繼續努力<br>';
	if(s==1)
	{
		temp+='<img border="0" src=http://k53.pbase.com/o5/33/110733/1/69740040.TiqK7YfY.LeeCooperLeeCooper2006_021.jpg>';
	}
	if(s==2)
	{
		temp+='<img border="0" src=http://i.pbase.com/o4/33/110733/1/65075517.aljuTijO.chiling_artdeco_018.jpg>';
	}
	if(s==3)
	{
		temp+='<img border="0" src=http://k43.pbase.com/o4/33/110733/1/52936435.chiling_129copy.jpg>';
	}
	if(s==4)
	{
		temp+='<img border="0" src=http://i.pbase.com/o4/33/110733/1/65075633.1W7My1Cw.chiling_88_023.jpg>';
	}
	temp+='</center>';
	wog_message_box(temp,0);
};
//###### xmas_money ######
function xmas_money(s)
{
	var temp='<center>';
	temp+='恭喜抽到金錢 '+s;
	temp+='</center>';
	wog_message_box(temp,0);
};
//###### xmas_money ######
function xmas_item(s)
{
	var temp='<center>';
	temp+='恭喜抽到 '+s;
	temp+='</center>';
	wog_message_box(temp,0);

};
//########## online peo begin ###########
function onlinelist(peo,type)
{
	var boy=0;
	var girl=0;
	var area_total=0;
	var temp_s=Gookie("wog_set_onlinelist");
	var temp_t=Gookie("wog_set_online_title");
	var display_status="";
	switch(type)
	{
		case 1:
			w_o(online_temp_table1+'<tr bgcolor="#2B4686"><td colspan="2"><a href=javascript:parent.online_title_show() target="foot">在線名單</a></td></tr>'+temp_table2);
			if(peo!="")
			{
				display_status=(temp_t=="1")?"block":"none";
				w_o('<div id="area_-1" style="display:'+display_status+'">');
				var s1=peo.split(";");
				for(var temp_p=0;temp_p<sec.length;temp_p++)
				{
					area_total=0;
					w_o('<div id="area_'+temp_p+'" style="display:none">');
					w_o(online_temp_table1+'<tr bgcolor="#2B4686"><td>名稱</td><td>LV</td></tr>');
					for(var i=0;i<s1.length;i++)
					{
						var s2=s1[i].split(",");
						if(temp_p==s2[5])
						{
							var fcolor=psex(s2[1]);
							(s2[1]=="1")?boy++:girl++;
							(s2[3]==1)?s2[3]="Y":s2[3]="N";
							w_o('<tr bgcolor="#040051"><td ><a href=javascript:parent.yesname("'+s2[0]+'") target="foot" title="PK:'+s2[3]+',報酬:'+s2[4]+'"><b><font color="'+fcolor+'">'+s2[0]+'</font></b></a></td><td >'+s2[2]+'</td></tr>');
							area_total++;
						}
					}
					w_o(temp_table2+'</div>');
					w_o(online_temp_table1+'<tr><td  bgcolor="#2B4686" colspan="3">↑<a href=javascript:parent.onlinelist_show("'+temp_p+'") target="foot">'+sec[temp_p][0]+'('+area_total+')</a>↑</td></tr>'+temp_table2);
				}
				w_o(online_temp_table1+'<tr><td  bgcolor="#2B4686" colspan="3"><font color="#66ccff">男生</font> '+boy+' 人</td></tr>');
				w_o('<tr><td  bgcolor="#2B4686" colspan="3"><font color="#ff99cc">女生</font> '+girl+' 人</td></tr>');
				w_o('<tr><td  bgcolor="#2B4686" colspan="3">線上人數 '+s1.length+' 人</td></tr>'+temp_table2);
				w_o('</div>');
			}
		break;
		case 2:
			temp_s=null;
			w_o(online_temp_table1+'<tr><td colspan="3" bgcolor="#2B4686">線上人數 '+peo+' 人</td></tr>'+temp_table2);
		break;
		case 3:
			w_o(online_temp_table1+'<tr bgcolor="#2B4686"><td colspan="2"><a href=javascript:parent.online_title_show() target="foot">在線名單</a></td></tr>'+temp_table2);
		break;
	}
	p_o();
	Drag.init(f.getElementById("wog_online"));
	if(type==3)
	{
		return;
	}
	if(temp_s!=null)
	{
		onlinelist_show(temp_s);
	}
};
function onlinelist_show(a)
{
	var temp_s=Gookie("wog_set_onlinelist");
	for(var temp_p=0;temp_p<sec.length;temp_p++)
	{
		f.getElementById("area_"+temp_p).style.display="none";
	}
	f.getElementById("area_"+a).style.display="block";
	Sookie("wog_set_onlinelist", a);
};
function online_title_show()
{
	var temp_s=Gookie("wog_set_online_title");
	if (temp_s == "1") {
		if(f.getElementById("area_-1")){f.getElementById("area_-1").style.display = "none";}
		Sookie("wog_set_online_title", "2");
	}else
	{
		/*
		var today = new Date();
		if(peo_chk_time+1200000 < today.getTime())
		{
			parent.peolist.document.location="./wog_etc.php?f=peo_cache";
			peo_chk_time=today.getTime();
		}
		*/
		if(f.getElementById("area_-1")){f.getElementById("area_-1").style.display="block";}
		Sookie("wog_set_online_title", "1");
	}
};
function psex(s)
{
	if(s=="1")
	{
		return "#66ccff";
	}else
	{
		return "#ff99cc";
	}
};