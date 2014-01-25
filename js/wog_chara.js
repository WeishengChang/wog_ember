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
//#### view begin ####
//,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be
function login_view(p_win,p_lost,p_img_set,i_img,name,p_sex,ch_name,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,a_name,body_name,head_name,hand_name,foot_name,item_name,item_name2,sat_name,p_place,p_birth,p_cdate,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be,act_num)
{
	message_cls();
	foot_fire();
	parent.document.getElementById("set_mainframe").rows=UI.set_rows;
	p_name=name;
	p_sat_name=sat_name;
	d_ch_name=ch_name;
	d_a_name=a_name;
	d_body_name=body_name;
	d_head_name=head_name;
	d_hand_name=hand_name;
	d_foot_name=foot_name;
	d_item_name=item_name;
	d_item2_name=item_name2;
	my_birth=birth[p_birth];
	my_age=p_cdate;
	p_lv2=p_lv;
	show_status(p_win,p_lost,p_img_set,i_img,p_sex,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,p_place,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be,act_num);
	job_work.p1=setInterval("p1()",1200000);
};
function show_status(p_win,p_lost,p_img_set,i_img,p_sex,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,p_place,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be,act_num)
{
	p_name2=p_name;
	d_ch_name2=d_ch_name;
	d_a_name2=d_a_name;
	d_body_name2=d_body_name;
	d_head_name2=d_head_name;
	d_hand_name2=d_hand_name;
	d_foot_name2=d_foot_name;
	d_item_name2=d_item_name;
	d_item2_name2=d_item2_name;
	my_birth2=my_birth;
	my_age2=my_age;
	status_template(p_win,p_lost,p_img_set,i_img,p_sex,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,p_place,0,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be,act_num);
	p_c();
};
function well_view(p_win,p_lost,p_img_set,i_img,p_name,p_sex,ch_name,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,a_name,d_name,e_name,dd_name,c_name,item_name,item_name2,p_place,p_birth,p_cdate,del_day,f_time,win_num,cp_mmoney,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be)
{
	message_cls();
	w_c('<table width="100%" border="0" cellspacing="0" cellpadding="0">');
	w_c('<tr>');
	w_c('<td  width="63%" valign="top">');
	status_view2(p_win,p_lost,p_img_set,i_img,p_name,p_sex,ch_name,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,a_name,d_name,e_name,dd_name,c_name,item_name,item_name2,p_place,p_birth,p_cdate,win_num,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be,"---");
	w_c('</td>');
	w_c('<td valign="top" class="b1">');
	chara_make(del_day,f_time);
	w_c('</td>');
	w_c('</tr>');
	w_c('</table>');
	p_c();
	setup_jmmoney(cp_mmoney);
};
function cp_view(p_win,p_lost,p_img_set,i_img,p_name,p_sex,ch_name,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,a_name,d_name,e_name,dd_name,c_name,item_name,item_name2,p_place,p_birth,p_cdate,win_num,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be,act_num)
{
	status_view2(p_win,p_lost,p_img_set,i_img,p_name,p_sex,ch_name,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,a_name,d_name,e_name,dd_name,c_name,item_name,item_name2,p_place,p_birth,p_cdate,win_num,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be,act_num);
	p_c();
};
function status_view2(p_win,p_lost,p_img_set,i_img,name,p_sex,ch_name,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,a_name,body_name,head_name,hand_name,foot_name,item_name,item_name2,p_place,p_birth,p_cdate,win_num,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be,act_num)
{
	p_name2=name;
	d_ch_name2=ch_name;
	d_a_name2=a_name;
	d_body_name2=body_name;
	d_head_name2=head_name;
	d_hand_name2=hand_name;
	d_foot_name2=foot_name;
	d_item_name2=item_name;
	d_item2_name2=item_name2;
	my_birth2=birth[p_birth];
	my_age2=p_cdate;
	status_template(p_win,p_lost,p_img_set,i_img,p_sex,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,p_place,win_num,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be,act_num);
};
function status_template(p_win,p_lost,p_img_set,i_img,p_sex,p_s,p_lv,p_exp,p_nextexp,p_money,p_hp,p_hpmax,p_str,p_smart,p_agl,p_life,p_vit,p_au,p_be,p_at,p_mat,p_df,p_mdf,p_place,win_num,sp,spmax,etc_str,etc_smart,etc_agi,etc_vit,etc_life,etc_au,etc_be,act_num)
{
	var p_img="";
	var group_name=etc_group;
	var args = arguments;
	if(p_name2==p_name){base_str=p_str,base_smart=p_smart,base_agi=p_agl,base_vit=p_vit,base_life=p_life,base_au=p_au,base_be=p_be,group_name=p_group;}
	[27,28,29,30,31,32,33].forEach(function(val) {
		if(args[val] > 0) args[val] = buff1+args[val]+buff2;
		if(args[val] < 0) args[val] = debuff1+args[val]+debuff2;
		if(args[val] == 0) args[val] = "";
	});
	p_s=s_status[p_s];
	if(p_img_set==1)
	{
		p_img=i_img;
	}else
	{
		p_img=img+i_img+".gif";
	}
	w_c('<table class="wog-table-1">');
	w_c('<tr class="head-1"><td>英雄檔案</td><td colspan="4">WIN '+p_win+' / LOST '+p_lost+'</td></tr>');
	w_c('<tr><td rowspan="9" class="status-image">'+group_name+'<br><img src="'+p_img+'" border="0" ><br>');
	if((p_win+p_lost)==0 || p_win==0)
	{
		w_c('獲勝率:<b>0</b>')
	}else
	{
		w_c('獲勝率:<b>'+Math.floor((p_win/(p_win+p_lost))*100)+'%</b>');
	}
	w_c('</td>');
	var sex="";
	(p_sex=="1")?sex="男":sex="女";
	w_c('<td width="40%">'+CColumn.make('暱稱', '<b>'+p_name2+'('+p_s+')</b>', {headWidth: '4em'})+'</td><td width="40%">'+CColumn.make('性別', '<b>'+sex+' '+my_age2+'歲</b>', {headWidth: '4em'})+'</td></tr>')
	var ratio = {
		str: [p_str, 4500], 
		smart: [p_smart, 4500],
		agi: [p_agl, 4500],
		life: [p_life, 4500],
		au: [p_au, 4500],
		be: [p_be, 4500],
		vit: [p_vit, 4500],
		at: [p_at, 7500],
		mat: [p_mat, 7500],
		df: [p_df, 7500],
		mdf: [p_mdf, 7500],
		hp: [p_hp, p_hpmax],
		sp: [sp, spmax],
		exp: [p_exp, p_nextexp],
		actnum: [act_num, 50]
	};
	for(var i in ratio) {
		ratio[i] = get_f(ratio[i][0], ratio[i][1]);
	}
	var rest_up="";
	if((win_num == undefined || win_num == 0) && p_name2==p_name)
	{
		rest_up='(<a class="uline" onclick="if(confirm(\'重新分配升級能力點數，需消耗 3 遊戲點，確定是否使用?\')){parent.act_click(\'mall\',\'restup\');}">配點</a>)';
	}
	w_c('<tr><td>'+CColumn.make('職業', '<b>'+d_ch_name2+'</b>', {headWidth: '4em'})+'</td><td>'+CColumn.make('金錢', '<b>'+p_money+'</b>', {headWidth: '4em'})+'</td></tr>');
	w_c('<tr><td >'+CColumn.make('等級', '<b>'+p_lv+rest_up+'</b>', {headWidth: '4em'})+'</td><td>'+CBar.makeColumn('經驗值', 0, ratio.exp, '<b>'+p_exp+'/'+p_nextexp+'</b>', {headWidth: '4em', class: 'column-box-2'})+'</td></tr>');
	w_c('<tr><td >'+CBar.makeColumn('HP', 2, ratio.hp, '<b>'+p_hp+'/'+p_hpmax+'</b>', {headWidth: '4em', class: 'column-box-2'})+'</td><td >'+CBar.makeColumn('SP', 1, ratio.sp, '<b>'+sp+'/'+spmax+'</b>', {headWidth: '4em', class: 'column-box-2'})+'</td></tr>');
	w_c('<tr><td >'+CColumn.make('冒險地', '<b>'+sec[p_place][0]+'</b>', {headWidth: '4em'})+'</td><td >'+CColumn.make('出生地', '<b>'+my_birth2+'</b>', {headWidth: '4em'})+'</td></tr>');
	w_c('<tr><td >'+CBar.makeColumn('行動力', 0, ratio.actnum, '<b>'+act_num+'</b>', {headWidth: '4em', class: 'column-box-2'})+'</td><td >'+CColumn.make('武器', '<b>'+re_arm_name(d_a_name2)+'</b>', {headWidth: '4em'})+'</td></tr>');
	w_c('<tr><td >'+CColumn.make('頭部', '<b>'+re_arm_name(d_head_name2)+'</b>', {headWidth: '4em'})+'</td><td >'+CColumn.make('身體', '<b>'+re_arm_name(d_body_name2)+'</b>', {headWidth: '4em'})+'</td></tr>');
	w_c('<tr><td >'+CColumn.make('手部', '<b>'+re_arm_name(d_hand_name2)+'</b>', {headWidth: '4em'})+'</td><td >'+CColumn.make('腳部', '<b>'+re_arm_name(d_foot_name2)+'</b>', {headWidth: '4em'})+'</td></tr>');
	w_c('<tr><td >'+CColumn.make('飾品', '<b>'+re_arm_name(d_item_name2)+'</b>', {headWidth: '4em'})+'</td><td >'+CColumn.make('道具', '<b>'+re_arm_name(d_item2_name2)+'</b>', {headWidth: '4em'})+'</td></tr>');
	if(win_num != undefined && win_num > 0)
	{
		w_c('<tr><td  >連勝紀錄</td><td colspan="4"  >'+win_num+' 連勝中</td></tr>')
	}
	w_c(temp_table2);
	w_c('<table class="wog-table-1">');
	//w_c('<tr><td >力量</td><td class="b1" ><img src="'+img+'bar/bxg.gif" width="'+p_strf+'%" height="9"><b>'+p_str+etc_str+'</b></td><td>智力</td><td class="b1" ><img src="'+img+'bar/bxg.gif" width="'+p_smartf+'%" height="9"><b>'+p_smart+etc_smart+'</b></td></tr>');
	w_c('<tr><td class="b1"  width="50%">'+CBar.makeColumn('力量', 0, ratio.str, p_str+etc_str)+'</td><td class="b1"  width="50%">'+CBar.makeColumn('智力', 0, ratio.smart, p_smart+etc_smart)+'</td></tr>');
	w_c('<tr><td class="b1" >'+CBar.makeColumn('速度', 0, ratio.agi, p_agl+etc_agi)+'</td><td class="b1" >'+CBar.makeColumn('生命', 0, ratio.life, p_life+etc_life)+'</b></td></tr>');
	w_c('<tr><td class="b1" >'+CBar.makeColumn('魅力', 0, ratio.au, p_au+etc_au)+'</td><td class="b1" >'+CBar.makeColumn('信仰', 0, ratio.be, p_be+etc_be)+'</td></tr>');
	w_c('<tr><td class="b1" >'+CBar.makeColumn('體質', 0, ratio.vit, p_vit+etc_vit)+'</td><td align="center" class="b1" >---</td></tr>');
	w_c('<tr><td class="b1" >'+CBar.makeColumn('物攻', 1, ratio.at, p_at)+'</td><td class="b1" >'+CBar.makeColumn('魔攻', 1, ratio.mat, p_mat)+'</td></tr>');
	w_c('<tr><td class="b1" >'+CBar.makeColumn('物防', 1, ratio.df, p_df)+'</td><td class="b1" >'+CBar.makeColumn('魔防', 1, ratio.mdf, p_mdf)+'</td></tr>');
	w_c(temp_table2);
};
function CColumn() {}
CColumn.make = function(name, text, options) {
	if(!options) options = {};
	var opts = {
		headWidth: options.headWidth ? 'width: '+options.headWidth+';' : '',
		class: options.class ? options.class : 'column-box'
	};
	return '<span class="'+opts.class+'"><span class="column-head" style="'+opts.headWidth+'">'+name+'</span>'+text+'</span>';
};
function CBar() {}
CBar.make = function(type, percent, options) {
	if(!options) options = {};
	var mapType = ['blue', 'green', 'red'];
	var percentText = '';
	type = mapType[type] || mapType[0];
	if(percent == 100) type += ' max';
	percent = Math.floor(percent*100)/100;
	return '<span class="status-bar-box" title="'+percent+'%">'+percentText+'<span style="width: '+(percent)+'%" class="status-bar-'+type+'"></span></span>';
};
CBar.makeColumn = function(name, type, percent, text, options) {
	return CColumn.make(name, ''+CBar.make(type, percent, options)+'<span class="status-text"><b>'+text+'</b></span>', options);
};

function re_arm_name(a1)
{
	return (a1=="")?"　":a1;
};
function no_cp(del_day,f_time)
{
	w_c('<table border=0 width="100%">');
	w_c('<tr>');
	w_c('<td valign="top">');
	chara_make(del_day,f_time);
	w_c('</td>');
	w_c('</tr>');
	w_c('</table>');
	p_c();
};
function get_f(p,s)
{
	p=(p/s)*100;
	if(p>100){p=100;}
	return p;
};
function chara_make(del_day,f_time)
{
	w_c('<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/80x15.png" /></a><br /><span xmlns:dc="http://purl.org/dc/elements/1.1/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dc:title" rel="dc:type">WOG</span>&#30001;<a xmlns:cc="http://creativecommons.org/ns#" href="http://www.et99.net" property="cc:attributionName" rel="cc:attributionURL" target="_blank">ETERNAL</a>&#35069;&#20316;&#65292;&#20197;<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank">&#21109;&#29992;CC &#22995;&#21517;&#27161;&#31034;-&#38750;&#21830;&#26989;&#24615;-&#30456;&#21516;&#26041;&#24335;&#20998;&#20139; 3.0 &#36890;&#29992;&#29256; &#25480;&#27402;&#26781;&#27454;</a>&#37323;&#20986;&#12290;&#27492;&#20316;&#21697;&#34893;&#29983;&#33258;<a href="http://www.et99.net" target="_blank">www.et99.net</a>&#12290;');
	w_c('<form action="wog_act.php" method="post" name="f1" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td>帳號:<input type="text" size="10" name="id" value="" > 密碼:<input type="password" size="10" name="pass" value="" ></td></tr>');
	w_c('<tr><td><input type="radio" name="set_frame" value="2" onclick="parent.setUI(1)" checked >固定介面 <input type="radio" name="set_frame" value="1" onclick="parent.setUI(2)">浮動介面</td></tr>');
	w_c('<tr><td><input type="submit" name="ppp" value="角色登入"> <input type="button" value="創造新角色" onclick="parent.act_click(\'chara\',\'make\')"></td></tr>');
	w_c('<tr class=head_td3><td>如果您有FaceBook帳號,可以使用您的<a href="http://apps.facebook.com/wog_game/" target="_blank">【FaceBook帳號登入遊戲】</a></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="chara"><input type="hidden" name="act" value="login"></form>');
	w_c('<OL>');
	w_c('<center><FONT COLOR="#FF0000"><b>WOG-遊戲方式</b></FONT></center>');
	w_c('<LI>點選「<a onclick="parent.act_click(\'chara\',\'make\')" class="uline">創造新角色</a>」按鈕，創立新角色。');
	w_c('<LI>角色創立之後，便可利用設定的帳號和密碼進入遊戲。');
	w_c('<LI>在個人專屬舞台裡，你可以進行動作選擇。');
	w_c('<LI>每場戰役結束後，必須經過<FONT COLOR="#FF0000"><b>'+f_time+'</b></FONT>秒才可以再進行下一場戰役!!');
	w_c('</OL>');
	setUI(1);
};
function king_view(sname,s)
{
	w_c(temp_table1);
	w_c(king_menu);
	w_c(temp_table2);
	w_c(hr);
	w_c(temp_table1);
	w_c('<tr class=head_td><td height="0" colspan="3" rowspan="0" >'+sname+'</td></tr>');
	var s1=s.split(";");
	for(var i=0;i<s1.length;i++)
	{
		var s2=s1[i].split(",");
		var p_img_url=s2[0];
		if(p_img_url.indexOf("http") == -1 && p_img_url.indexOf("ftp") == -1)
		{
			p_img_url='<img src="'+img+p_img_url+'.gif" border="0">';
		}else
		{
			p_img_url='<img src="'+p_img_url+'" border="0">';
		}
		w_c('<tr><td  width="110">'+p_img_url+'</td><td >'+s2[1]+'</td><td >'+s2[2]+'</td></tr>');
	}
	w_c(temp_table2);
	p_c();
};
function hero_view(a)
{
	w_c(temp_table1);
	var s1=a.split(";");
	for(var i=0;i<s1.length;i++)
	{
		var s2=s1[i].split(",");
		var p_img_url=s2[0];
		var hero_title="";
		switch (s2[2])
		{
			case '2':
				hero_title="不敗英雄";
			break;
			case '3':
				hero_title="幻魔英雄";
			break;
			case '4':
				hero_title="神風英雄";
			break;
			case '5':
				hero_title="鋼鐵英雄";
			break;
			case '6':
				hero_title="結界英雄";
			break;
			case '7':
				hero_title="魅心英雄";
			break;
			case '8':
				hero_title="武術英雄";
			break;
			case '9':
				hero_title="獸王英雄";
			break;
		}
		w_c('<tr class="head_td"><td height="0" colspan="2" >'+hero_title+'(重置日期：'+s2[3]+')</td></tr>');
		if(p_img_url.indexOf("http") == -1)
		{
			p_img_url='<img src="'+img+p_img_url+'.gif" border="0">';
		}else
		{
			p_img_url='<img src="'+p_img_url+'" border="0">';
		}
		w_c('<tr><td width="110">'+p_img_url+'</td><td>'+s2[1]+'</td></tr>');
	}
	w_c(temp_table2);
	p_c();
};
function chara_make_view(total_point,s1,s2,s3)
{
	message_cls();
	var temp_i_id=new Array;
	var temp_ch=new Array;
	temp_st=new Array("str","smart","life","agi");
	temp_i_id=s1.split(",");
	temp_ch=s2.split(";");
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(parent.temp_table1);
	w_c('<tr><td class="b1">玩家帳號：<input type="text" name="id" size="10" maxlength="12" class="style1"> 請勿輸入{ } ; &lt; &gt; , " \' \\  等符號</td></tr>');
	w_c('<tr class=head_td3><td class="b1">玩家密碼：<input type="password" name="pass" size="10" class=style1> 請輸入4～8字內的半形英數。</td></tr>');
	w_c('<tr><td class="b1">玩家email：<input type="text" name="email" size="50" class=style1> 補發密碼使用</td></tr>');
	w_c('<tr class=head_td3><td class="b1">角色性別：<input type="radio" name="sex" value="2">女　<input type="radio" name="sex" value="1">男　★請選擇角色的性別。</td></tr>');
	w_c('<tr><td class="b1">角色屬性：<input type="radio" name="s" value="1">地 <input type="radio" name="s" value="2">水 <input type="radio" name="s" value="3">火 <input type="radio" name="s" value="4">木 <input type="radio" name="s" value="5">風 <input type="radio" name="s" value="6">毒  ★請選擇角色的屬性</td></tr>');
	w_c('<tr class=head_td3><td class="b1">出生地點：<input type="radio" name="birth" value="1">中央大陸 <input type="radio" name="birth" value="2">魔法王國 <input type="radio" name="birth" value="3">熱帶雨林 <input type="radio" name="birth" value="4">末日王城</td></tr>');
	w_c('<tr><td class="b1">角色圖像：<select name="i_id">');
	for(var i=0;i<temp_i_id.length;i++)
	{
		w_c('<option value="'+temp_i_id[i]+'" >'+temp_i_id[i]);
	}
	w_c('</select>　<a href="javascript://" onClick="window.open(\'wog_etc.php?f=img\',\'\',\'menubar=no,status=no,scrollbars=yes,top=50,left=50,toolbar=no,width=400,height=400\')"><font size="2">★圖像預覽★</font></a><BR>　　　　★請選擇角色的圖片。</td></tr>');
	w_c('<tr class=head_td3><td class="b1">角色職業：<select name=ch >');
	for(var i=0;i<temp_ch.length;i++)
	{
		temp_c=temp_ch[i].split(",");
		w_c('<option value="'+temp_c[0]+'" >'+temp_c[1]);
	}
	w_c('</select></td></tr>');
	w_c('<tr><td class="b1">角色能力： 基本值 10<table border="1" cellspacing="0" cellpadding="0"><tr><td class="b2" width="70">力量 10</td><td class="b2" width="70">智力 10</td><td class="b2" width="70">生命 10</td><td class="b2" width="70">敏捷 10</td></tr>');
	w_c('<tr class=head_td3>');
	for(var j=0;j<4;j++)
	{
		w_c('<td class="b1"><select name='+temp_st[j]+'>');
		for(var i=1;i<11;i++)
		{
			w_c('<option value="'+i+'">+'+i);
		}
		w_c('</select></td>');
	}
	w_c('</tr>');
	w_c('<tr><td colspan="4" class="b1">★你共有「<font color="#FF0000"><b>'+total_point+'</b></font>」點的加分點數可增加能力值，請自行分配。分配總和超出<font color="#FF0000"><b>'+total_point+'</b></font>以上則無法註冊。</td></tr></table>');
	w_c('</td></tr>');
	w_c('<tr class=head_td3><td class="b1">必殺技名稱：<input type="text" name="sat_name" size="40" maxlength="40" class="style1"> 請輸入你必殺技的名稱。 請勿輸入{ } ; &lt; &gt; , " \' \\  等符號</td></tr>');
	w_c('<tr><td align="center"><input type="submit" value="角色作成" > 已經有帳號→[<a href="wog_etc.php?f=well" target="mission">返回登入頁</a>] ></td></tr>');
	w_c(parent.temp_table2);
	w_c('<input type="hidden" name="f" value="chara">');
	w_c('<input type="hidden" name="act" value="save">');
	w_c('<input type="hidden" name="recommid" value="'+s3+'">');
	w_c('</form>');
	p_c();
	setUI(1);
};
function check_creat(a1){
	w_c(temp_table1);
	switch(a1)
	{
		case 1:
			w_c('<tr><td>角色建立成功,系統已發送驗證信到你的信箱,請查收認證信通過驗證才能登入角色(收不到驗證信時請查看是否在垃圾郵件裡面，建議使用Google或Hotmail)</td></tr>');
		break;
	}
	w_c(temp_table2);
	p_c();
};
function start_tips(a1,a2,a3,a4,a5){
	var t='';
	t+='<form>';
	t+=temp_table1;
	t+='<tr><td><table border="0" width="100%" height="100%"><tr><td>找最果之島 拿1000點<a href="http://www.et99.net/topic-t46833.html" target="_blank">【請看詳情】</a>'+hr+'WOG推薦活動<a href="http://www.et99.net/post3992580.html#p3992580" target="_blank">【請看詳情】</a></td></tr>';
	var s1=a5.split(",");
	t+='</table></td>';
	//右邊窗口 begin
	t+='<td><table align="center" width="100%">';
	t+='<tr class="head_td2"><td colspan="2">推薦冒險地點 : '+a4+'</td></tr>';
	if(p_lv2>1)
	{
		if(a1!="")
		{
			t+='<tr class="head_td"><td colspan="2">推薦可接任務</td></tr>';
			var s1=a1.split(";");
			t+='<tr class="head_td"><td>委託者</td><td>主題</td></tr>';
			for(var i=0;i<s1.length;i++)
			{
				var s2=s1[i].split(",");
				t+='<tr onmouseover="this.className=\'on_td\';" onmouseout="this.className=\'un_td\';" class="un_td"><td>'+s2[1]+'</td><td><a href="javascript:parent.act_click(\'mission\',\'detail\','+s2[0]+')">'+s2[2]+'</a></td></tr>';
			}
			t+='<tr class="head_td2"><td colspan="2"><a class="uline" onClick="parent.act_click(\'mission\',\'list\')">【進入任務介紹所】</a></td></tr>';
		}		
	}else
	{
		t+='<tr class="head_td"><td colspan="2">新手提示</td></tr>';
		t+='<tr><td colspan="2" class=b1>這裡是四個國家共同建立的WOG世界，新手要在這世界生存請善用下面功能<ol><li>可從<a href=parent.mission_ed()>【任務手冊】</a>裡面的任務介紹所接任務，新手推薦先接『新手入門』任務</li>';
		t+='<li>可從<a href=parent.select_store()>【商店街】</a>進入商店購買裝備、公會、團隊、傭兵所、牧場等</li>';
		t+='<li>在<a href=parent.act_click(\'mercenary\',\'view\')>【傭兵所】</a>可以購買傭兵替您自動戰鬥</li>';
		t+='<li>在<a href=parent.act_click(\'job\',\'view\')>【職業中心】</a>可以隨時改變您的職業，並在<a href=parent.job_list();>【技能大師】</a>學習你想使用的技能</li>';
		t+='<li>利用<a href=parent.act_click(\'skill\',\'view\')>【技能手冊】</a>可以隨時改變您戰鬥中使用的技能</li>';
		t+='</ol></td></tr>';		
	}
	t+='<tr class="head_td"><td colspan="2">現有榮譽:<b>'+a2+'</b> (榮譽必須加入公會才能獲得)</td></tr>';
	if(a3!="")
	{
		t+='<tr class="head_td"><td colspan="2">↓榮譽可兌換物品↓</td></tr>';
		t+='<tr><td colspan="2"><table align="center">';
		var s1=a3.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			if((i+1)%3==1)
			{
				t+='<tr>';
			}
			t+='<td width="150"><a class=uline onclick=parent.arm_show(event.ctrlKey,"'+s2[1]+'","'+s2[0]+'")>'+s2[1]+'</a></td>';
			if((i+1)%3==0)
			{
				t+='</tr>';
			}
		}
		t+='</td></tr></table><tr class="head_td2"><td colspan="2"><a class="uline" onClick="parent.act_gclick(\'depot\',\'depot_list\')">【進入公會倉庫】</a></td></tr>';
	}
	t+='<tr class="head_td"><td colspan="2"><input type="checkbox" onclick="parent.set_well_box()" '+return_well_box()+'>不再顯示友善消息</td></tr>';
	t+='</table></td></tr>';
	//右邊窗口 end
	t+=temp_table2;
	t+="</form>";
	wog_message_box(t,5);
};
