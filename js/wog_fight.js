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
//####### fight begin #######
function datechk(s,b)
{
	var foot=parent.foot.document.f1;
	var error="";
	if(b.f_count.value > 40)
	{
		error="戰鬥回合數不能大於"+f_count;
	}
	foot.subplace.value="";
	switch(true){
		case b.a_type[0].checked:
			foot.act.value=b.act1.value;
			foot.temp_id.value=0;
			foot.subplace.value=b.subplace.value || "";
		break;
		case b.a_type[1].checked:
			foot.act.value=b.act2.value;
			foot.temp_id.value=1;
			if(b.act2.value=="1000")
			{
				if(!confirm("挑戰冠軍需付500元費用"))
				{
					error="結束挑戰";
				}
			}
			if(b.act2.value=="1001")
			{
				if(dfoot.menu.towho.value=="")
				{
					error="沒有選擇對象不能PK";
				}
			}
		break;
		case b.a_type[2].checked:
			foot.act.value=b.act3.value;
			foot.temp_id.value=2;
//			foot.subplace.value=b.sp_subplace.value || "";
		break;
	}
	if(foot.act.value=="")
	{
		error="請選擇場地";
	}
	if(error != "")
	{
		alert(error);
	}else
	{
		if(b.a_mode[0].checked==true)
		{
			foot.temp_id2.value=1;
		}
		if(b.a_mode[1].checked==true)
		{
			foot.temp_id2.value=2;
		}
		p_sat_name=b.sat_name.value;
		Sookie("wog_set_cookie", foot.temp_id.value+","+foot.act.value+","+foot.temp_id2.value+","+b.act_area.value+","+foot.subplace.value);
		f_count=b.f_count.value;
		f_hp=b.f_hp.value;
		f_sp=b.f_sp.value;
		f_escape_hp=b.escape_hp.value;
		hotel_hp=b.hotel_hp.value;
		hotel_sp=b.hotel_sp.value;
		foot.f.value="fire";
		foot.action="wog_fight.php";
		foot.at_type.value=s;
		foot.sat_name.value=b.sat_name.value;
		foot.towho.value=dfoot.menu.towho.value;
		foot.temp_id3.value=b.f_count.value;
		foot.temp_id4.value=b.f_hp.value;
		foot.temp_id8.value=b.f_sp.value;
		foot.temp_id5.value=b.escape_hp.value;
		foot.temp_id6.value=b.hotel_hp.value;
		foot.temp_id7.value=b.hotel_sp.value;
		from_copy(foot,parent.foot.document.f2);
		foot.submit();
	}
};
function fight_fast(){
	var foot=parent.foot.document.f1;
	f=parent.wog_view.document;
	from_copy(parent.foot.document.f2,foot);
	foot.action="wog_fight.php";
	foot.submit();
	UI.set_target.getElementById("con_fight").innerHTML='<font color="#000000">冒險旅程</font>';
};
function from_copy(f1,f2)
{
	f2.f.value=f1.f.value;
	f2.act.value=f1.act.value;
	f2.pay_id.value=f1.pay_id.value;
	f2.temp_id.value=f1.temp_id.value;
	f2.temp_id2.value=f1.temp_id2.value;
	f2.temp_id3.value=f1.temp_id3.value;
	f2.temp_id4.value=f1.temp_id4.value;
	f2.temp_id5.value=f1.temp_id5.value;
	f2.temp_id6.value=f1.temp_id6.value;
	f2.temp_id7.value=f1.temp_id7.value;
	f2.temp_id8.value=f1.temp_id8.value;
	f2.at_type.value=f1.at_type.value;
	f2.sat_name.value=f1.sat_name.value;
	f2.subplace.value=f1.subplace.value;
};
function change_mission(s,b)
{
	if(b.a_type){b.a_type[s].checked=true;}
};
function select_area(num,s)
{
	var place=s.act1;
	var first=null;
	place.options.length=0;
	place.selectedIndex=0;
	switch(parseInt(num,10)){
		case 1:
			place.options[0]=new Option("中央平原","1");
			place.options[1]=new Option("黑暗沼澤","3");
			place.options[2]=new Option("王者之路","7");
			place.options[3]=new Option("水晶之間","13");
			first=1;
		break;
		case 2:
			place.options[0]=new Option("試鍊洞窟","2");
			place.options[1]=new Option("幻獸森林","8");
			place.options[2]=new Option("天空之城","12");
			place.options[3]=new Option("最果之島","15");
			first=2;
		break;
		case 3:
			place.options[0]=new Option("迷霧森林","4");
			place.options[1]=new Option("無淵洞窟","11");
			place.options[2]=new Option("冷峰寒地","16");
			place.options[3]=new Option("星河異界","9");
			first=4;
		break;
		case 4:
			place.options[0]=new Option("灼熱荒漠","10");
			place.options[1]=new Option("古代遺跡","5");
			place.options[2]=new Option("久遠戰場","6");
			place.options[3]=new Option("失落古船","14");
			place.options[4]=new Option("血之魔域","21");
			first=10;
		break;
		default:
			place.options[0]=new Option("請選擇場所","");
		break;
	}
	place.options[0].selected=true;
	setSubplace(first,s.subplace);
};
function setSubplace(id,subplace){
	if(subplace){
		subplace.options.length=0;
		subplace.options[0]=new Option("不限定","");
		if(!isNaN(id) && sec[id]){
			var list=sec[id];
			for(var i=1,item;item=list[i];i++){
				subplace.options[i]=new Option(item[0],item[1]);
			}
		}
		subplace.selectedIndex=0;
	}
};
function ad_view()
{
	w_c('<form name=f1>');	
	w_c(temp_table1);
	w_c('<tr><td><input type="radio" name="a_type" value="1" checked>冒險修行 '+temp_country);
	w_c(' <select name="act1" onchange="parent.change_mission(0,this.form);parent.setSubplace(this.options[this.selectedIndex].value,this.form.subplace);"><option value="" SELECTED>選擇場所</option></select>');
	w_c(' <select name="subplace" onchange="parent.change_mission(0,this.form);"><option value="" selected="selected">不限定</option></select></td></tr>');
	w_c('<tr><td><input type="radio" name="a_type" value="2" >武鬥競技 <select name="act2" onChange="parent.change_mission(1,this.form);"><option value="" SELECTED>選擇模式</option><option value="1000" >挑戰冠軍</option><option value="1001" > PK 挑戰</option></select> <a href="#" onclick="parent.mouse_xy(event.x||event.pageX,event.y||event.pageY);parent.act_click(\'pk\',\'view\');" target="mission">PK設定</a></td></tr>');
	w_c('<tr><td><input type="radio" name="a_type" value="3" >禁區挑戰 <select name="act3" onChange="parent.change_mission(2,this.form);"><option value="" SELECTED>選擇地點</option>');
	for(var temp_p in sp_sec)
	{
		w_c('<option value="'+temp_p+'">'+sp_sec[temp_p][0]+'</option>');
	}
//	w_c('</select> <select name="sp_subplace" onchange="parent.change_mission(2,this.form)"><option value="" selected="selected">不限定</option></select></td></tr>');
	w_c('<tr><td ><input type="radio" name="a_mode" value="1" checked>快速模式  <input type="radio" name="a_mode" value="2" >一般模式</td></tr>');
	w_c('<tr><td ><input type="button" value="物理攻擊" onClick="parent.datechk(1,document.forms[0])"> <input type="button" value="魔法攻擊" onClick="parent.datechk(2,document.forms[0])"></td></tr>');
	w_c(temp_table2);
	w_c('<br>'+hr+'<br>');
	w_c(temp_table1);
	w_c('<tr><td >戰鬥回合數 <input type="text" name="f_count" value="'+f_count+'" size="2" maxlength="2"> (最大'+f_count+')，HP低於 <input type="text" name="escape_hp" value="'+f_escape_hp+'" size="2" maxlength="2"> %逃離戰鬥</td></tr>');
	w_c('<tr><td >HP低於 <input type="text" name="f_hp" value="'+f_hp+'" size="3" maxlength="3"> %，SP低於 <input type="text" name="f_sp" value="'+f_sp+'" size="3" maxlength="3"> %自動使用恢復道具 </td></tr>');
	w_c('<tr><td >HP低於 <input type="text" name="hotel_hp" value="'+hotel_hp+'" size="2" maxlength="2"> % ， SP低於 <input type="text" name="hotel_sp" value="'+hotel_sp+'" size="2" maxlength="2"> %自動住宿(-1表示略過條件)</td></tr>');
	w_c('<tr><td>必殺技名稱 <input type="text" name="sat_name" size="40" maxlength="60" value="'+p_sat_name+'"></td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
	var temp_s=Gookie("wog_set_cookie");
	if(temp_s!=null)
	{
		var s1=temp_s.split(",");
		var form=parent.wog_view.document.forms[0];
		switch(s1[0])
		{
			case '0':
				fire_set_type(0);
				parent.select_area(s1[3],form);
				form.act_area.value=s1[3];
				form.act1.value=s1[1];
				setSubplace(form.act1.value,form.subplace);
				form.subplace.value=s1[4];
			break;
			case '1':
				fire_set_type(1);
				form.act2.value=s1[1];
			break;
			case '2':
				fire_set_type(2);
				form.act3.value=s1[1];
//				setSubplace(form.act3.value,form.sp_subplace);
//				form.sp_subplace.value=s1[4];
			break;
		}
		if(s1[2]==1)
		{
			form.a_mode[0].checked=true;
			form.a_mode[1].checked=false;
		}else
		{
			form.a_mode[0].checked=false;
			form.a_mode[1].checked=true;
		}
	}
};
function fire_set_type(a)
{
	f.f1.a_type[0].checked=false;
	f.f1.a_type[1].checked=false;
	f.f1.a_type[2].checked=false;
	f.f1.a_type[a].checked=true;
};
function fire_date(p_at,p_df,p_mat,p_mdf,p_hp,p_hpmax,p_s,p_img_set,i_img,m_at,m_df,m_mat,m_mdf,m_hp,m_hpmax,m_s,m_name,m_img,f_status)
{
	var temp_s=Gookie("wog_set_cookie");
	var bg_imgs=temp_s.split(",");
	var fight_background='background="'+bg_img[bg_imgs[1]]+'"';
	var p_name=get_name();
	temp_p_hp=p_hp;
	temp_p_hpmax=p_hpmax;
	temp_m_hp=m_hp;
	temp_m_hpmax=m_hpmax;
	var temp_php_img=(temp_p_hp/temp_p_hpmax)*260;
	var temp_mhp_img=(temp_m_hp/temp_m_hpmax)*260;
	var p_img="";
	var php_message=temp_p_hp+"/"+temp_p_hpmax;
	var mhp_message=temp_m_hp+"/"+temp_m_hpmax;
	setup_mname(m_name);
	
	p_s=s_status[p_s];
	m_s=s_status[m_s];
	if(m_img=="")
	{
		m_img="no_img.jpg";
	}
	if(f_status==1)
	{
		if(m_img.indexOf("http") == -1)
		{
			m_img=img+m_img+".gif";
		}
		m_img='<img id=g2 src="'+m_img+'" border="0" style="position: absolute;left: 400;top: 160;Z-INDEX: 1;visibility: visible">';
	}else
	{
		m_img=mimg+m_img;
		m_img='<img id=g2 src="'+m_img+'" border="0" style="position: absolute;left: 400;top: 135;Z-INDEX: 1;visibility: visible">';
	}
	if(p_img_set==1)
	{
		p_img=i_img;
	}else
	{
		p_img=img+i_img+".gif";
	}
	w_c('<table align="left" id="fight_table" style="backgroundColor:#000000;"><tr><td>'+fight_temp_table1+'<tr><td colspan="4">'+p_name+'('+p_s+')</td><td colspan="4" >'+m_name+'('+m_s+')</td></tr>');
	w_c('<tr><td width="40">HP</td><td class=b1 width="260" colspan="3"><img src='+img+'bar/bhg.gif border="0" width="'+temp_php_img+'" id="p_img"  height="9" alt="'+php_message+'"></td><td width="40">HP</td><td class=b1 width="260" colspan="4"><img src='+img+'bar/bhg.gif border="0" width="260" id="m_img"  height="9" alt="'+mhp_message+'"></td></tr>');
	w_c('<tr><td>物攻</td><td>'+p_at+'</td><td>魔攻</td><td>'+p_mat+'</td><td>物攻</td><td>'+m_at+'</td><td>魔攻</td><td>'+m_mat+'</td></tr>');
	w_c('<tr><td>物防</td><td>'+p_df+'</td><td>魔防</td><td>'+p_mdf+'</td><td>物防</td><td>'+m_df+'</td><td>魔防</td><td>'+m_mdf+'</td></tr>');
	w_c(temp_table2);
	w_c('<table  border="0" cellspacing="1" cellpadding="0" bgcolor="#000000" align="center"><tr><td><table width="600" border="0" cellspacing="0" cellpadding="0" align="center" '+fight_background+'>');
	w_c('<tr><td width="50%" height="212" align="center"><img id=g1 src="'+p_img+'" border="0" style="position: absolute;left: 80;top: 160;Z-INDEX: 1;visibility: visible"></td><td align="center" width="50%" height="210">'+m_img+'</td></tr>');
	w_c(temp_table2+"</td></tr></table>");
	w_c('<table width="97%" border="0" cellspacing="0" cellpadding="0" align="center" ><tr><td colspan="2" align="center"><div align="center" id="a1"></div><div align="center" id="a4"></div></td></tr>');
	w_c(temp_table2+'</td></tr></table>');
	w_c('<img id=g3 border="0" style="position: absolute;left: 80;top: 160;Z-INDEX: 1;visibility: hidden">');
	if(p_support_name!="")
	{
		if(p_support_img.indexOf("http") == -1)
		{
			p_support_img=img+p_support_img+'.gif';
		}
		w_c('<img id=g4 src="'+p_support_img+'" border="0" style="position: absolute;left: 80;top: 160;Z-INDEX: 1;visibility: hidden">');
	}
	p_c();
};
function set_fight(temp_fightrow)
{
	fight_num=0;
	fight_count_num=0;
	fight_message_skill="";
	fight_message_title="";
	fight_message="";
	fightrow=temp_fightrow;
	parent.show_fightrow();
};
function show_fightrow()
{
	if(f.getElementById("a1"))
	{
		for(var i=fight_num;i< fightrow.length;i++)
		{
			if(i==0)
			{
				f.getElementById("a1").innerHTML=fightrow[i];
			}else
			{
				if(fightrow[i].substring(0,9)=="parent.vc")
				{
					fight_num++;
					eval(fightrow[i]);
					window.setTimeout("show_fightrow()",1500);
					break;
				}
				eval(fightrow[i]);
			}
			fight_num++;
		}
	}
};
function pact_date(temp_d,s,ss,support)
{
	m_hp_status(-temp_d);
	if(support==0)
	{
		f.getElementById("g1").style.visibility="visible";
		if(f.getElementById("g3")!=null)
		{
			f.getElementById("g3").style.visibility="hidden";
		}
		if(f.getElementById("g4")!=null)
		{
			f.getElementById("g4").style.visibility="hidden";
		}
		view_fight(temp_d,s,ss,p_name,m_name);
	}else
	{
		f.getElementById("g1").style.visibility="hidden";
		f.getElementById("g3").style.visibility="hidden";
		f.getElementById("g4").style.visibility="visible";
		view_fight(temp_d,s,ss,p_support_name,m_name);
	}
	gf=f.getElementById("g2").style.left;
	gf=gf.replace("%","");
	gt=f.getElementById("g2").style.top;
	shock_num=0;
	temp_obj=f.getElementById("g2").style;
	shock_s=s;
	shock_fight();
};
function mact_date(temp_d,s,ss)
{
	p_hp_status(-temp_d);
	view_fight(temp_d,s,ss,m_name,p_name);
	gf=f.getElementById("g1").style.left;
	gf=gf.replace("%","");
	gt=f.getElementById("g1").style.top;
	shock_num=0;
	temp_obj=f.getElementById("g1").style;
	shock_s=s;
	shock_fight();
};
function pet_act_date(temp_d,s)
{
	f.getElementById("g3").src=mimg+pet_img;
	f.getElementById("g1").style.visibility="hidden";
	if(f.getElementById("g3")!=null)
	{
		f.getElementById("g3").style.visibility="visible";
	}
	if(f.getElementById("g4")!=null)
	{
		f.getElementById("g4").style.visibility="hidden";
	}
	m_hp_status(-temp_d);
	view_fight(temp_d,s,"",pet_pname,m_name);
	gf=f.getElementById("g2").style.left;
	gf=gf.replace("%","");
	gt=f.getElementById("g2").style.top;
	shock_num=0;
	temp_obj=f.getElementById("g2").style;
	shock_s=s;
	shock_fight();
};
function pet_def_date(temp_d,s,ss)
{
	f.getElementById("g3").src=mimg+pet_img;
	f.getElementById("g1").style.visibility="hidden";
	f.getElementById("g3").style.visibility="visible";
	view_fight(temp_d,s,ss,m_name,pet_pname);
	gf=f.getElementById("g3").style.left;
	gf=gf.replace("%","");
	gt=f.getElementById("g3").style.top;
	shock_num=0;
	temp_obj=f.getElementById("g3").style;
	if(ss=="1")
	{
		fight_message="<font color=#89C7F3>"+pet_pname+"</font> 戰敗";
	}
	shock_s=s;
	shock_fight();
};
function shock_fight()
{
	var shock_ss=1;
	if(f.getElementById("fight_table"))
	{
		if(shock_s!="")
		{
			shock_ss=3;
			f.getElementById("fight_table").style.backgroundColor="#4d0002";
		}else
		{
			f.getElementById("fight_table").style.backgroundColor="#000000";
		}
		temp_obj.left=(parseInt(gf)+(Math.floor(Math.random()*7*shock_ss)));
		temp_obj.top=(parseInt(gt)+(Math.floor(Math.random()*13*shock_ss)));
		shock_num++;
		if(shock_num<15)
		{
			window.setTimeout("shock_fight()",30);
		}else
		{
			temp_obj.left=gf;
			temp_obj.top=gt;
		}
	}
};
function vc(a1){
	if(fight_count_num>0)
	{
		fight_show_message();
	}
	fight_count_num=a1;
	fight_message_skill="";
	fight_message_title=' 第 '+a1+' 回'+'<br>';
	fight_message="";
};
function view_fight(temp_d,s,ss,e,d)
{
	temp_d=' <font color=red><b>'+temp_d+'<b></font>';
	(e==p_name)?e='<font color="#89C7F3">'+e+'</font>':e='<font color="#81D8A8">'+e+'</font>';
	(d==p_name)?d='<font color="#89C7F3">'+d+'</font>':d='<font color="#81D8A8">'+d+'</font>';
	if(ss!="")
	{
		ss='<img src="'+img+'ss.gif" border="0">';
	}
	if(s!="")
	{
		fight_message+=ss+e+' 發動 <b><font color="#FF0000" size="+2">'+s+'</b></font> 給予 '+d+temp_d+' 點傷害<br>';
	}else
	{
		fight_message+=ss+e+' 給予 '+d+temp_d+' 傷害<br>';
	}
};
function miss_date(a,d)
{
	(a==p_name)?a='<font color="#89C7F3">'+a+'</font>':a='<font color="#81D8A8">'+a+'</font>';
	(d==p_name)?d='<font color="#89C7F3">'+d+'</font>':d='<font color="#81D8A8">'+d+'</font>';
	fight_message+=a+' 展開攻擊 '+d+' 快速閃開攻擊<br>';
};
function end_date(s,en,getexp,getmoney,p_hp,sum,d_item_num)
{
	var enstr="";
	if(en=="0")
	{
		enstr="戰敗了!!!";
	}else if(en=="1")
	{
		enstr="獲得了勝利！！";
	}
	var temp_str=hr;
	temp_str+=temp_table1+'<tr><td ><b>'+s+' '+enstr+'</b></td></tr>';
	temp_str+='<tr><td><b>HP剩下 '+p_hp+',獲得經驗值 '+getexp+',獲得金錢 '+getmoney+',費時 '+sum+' 回合</b></td></tr>';
	if(d_item_num > 0){
		var s_num=d_item2_name.lastIndexOf("*");
		if (s_num > -1) 
		{d_item2_name=d_item2_name.substr(0,s_num)+"*"+d_item_num+'</a>';}
		else
		{d_item2_name+="*"+d_item_num+'</a>';}
		temp_str+='<tr><td><b>殘餘 '+d_item2_name+'</b></td></tr>';
	}
	temp_str+=temp_table2;
	fight_show_message();
	f.getElementById("a4").innerHTML=temp_str;
};
function fight_show_message()
{
	if(fight_message_skill.length>0)
	{
		fight_message_skill=fight_message_skill.substring(0, fight_message_skill.length-1)
	}
	f.getElementById("a1").innerHTML=fight_message_title+fight_message_skill+'<br>'+fight_message;	
};
function get_item(i,s,s1)
{
	var enstr="";
	if(s==0)
	{
		enstr="裝備欄超過"+s1+"樣物品無法裝入";
	}else if(s==1)
	{
		enstr="幸運撿到";
	}
	var temp_str=hr;
	temp_str+=temp_table1+'<tr><td ><b>'+enstr+' '+i+'</b></td></tr>';
	temp_str+=temp_table2;
	f.getElementById("a4").innerHTML+=temp_str;
};
function fight_event(name,s)
{
	//f.getElementById("a2").style.display="block";
	//f.getElementById("a2").innerHTML=' <b>'+name+' 使用  '+s+'</b>';
	fight_message_skill+=' <b>'+name+' 使用  '+s+'</b> ，';
};
function fight_event2(name,temp_d,sp){
	var temp="";
	var temp2="";
	if(temp_d <0)
	{
		temp="減少";
	}
	else
	{
		temp="增加";
	}
	if(!sp){sp=0;}
	if(sp <0)
	{
		temp2="減少";
	}
	else
	{
		temp2="增加";
	}
	if(p_name==name)
	{
		p_hp_status(temp_d);
	}
	else
	{
		m_hp_status(temp_d);
	}
	//f.getElementById("a3").style.display="block";
	//f.getElementById("a3").innerHTML='<b>'+name+' HP'+temp+' '+temp_d+', SP'+temp2+' '+sp+'</b>';
	fight_message+='<b>'+name+' HP'+temp+' '+temp_d+', SP'+temp2+' '+sp+'</b><br>';
};
function fight_event3(name,s)
{
	fight_message+='<b>'+name+' 使用  '+s+'</b><br>';
};
function fight_event4(a1)
{
	f.getElementById("a4").innerHTML+='<tr><td><b>'+a1+'</b></td></tr>';
	//fight_message+='<b>'+a1+'</b><br>';
};
function p_hp_status(temp_d)
{
	temp_p_hp = parseInt(temp_p_hp) + parseInt(temp_d);
	if(temp_p_hp<0){temp_p_hp=0;}
	if (temp_p_hp > temp_p_hpmax) {
		temp_p_hp = temp_p_hpmax;
	}
	var temp_php_img = (temp_p_hp / temp_p_hpmax) * 260;
	if(f.getElementById("p_img"))
	{
		f.getElementById("p_img").width = temp_php_img;
		f.getElementById("p_img").alt = temp_p_hp + "/" + temp_p_hpmax;
	}
};
function m_hp_status(temp_d)
{
	temp_m_hp = parseInt(temp_m_hp) + parseInt(temp_d);
	if(temp_m_hp<0){temp_m_hp=0;}
	if (temp_m_hp > temp_m_hpmax) {
		temp_m_hp = temp_m_hpmax;
	}
	var temp_mhp_img=(temp_m_hp/temp_m_hpmax)*260;
	if(f.getElementById("m_img"))
	{
		f.getElementById("m_img").width=temp_mhp_img;
		f.getElementById("m_img").alt=temp_m_hp+"/"+temp_m_hpmax;
	}
};
function lv_up(str,agl,smart,life,vit,au,be,up_type)
{
	if(up_type==null)
	{
		w_c(hr);
		w_c(temp_table1+'<tr><td><b>'+p_name+' 等級上升 </b></td></tr>');
		str=parseInt(str);
		agl=parseInt(agl);
		smart=parseInt(smart);
		life=parseInt(life);
		vit=parseInt(vit);
		au=parseInt(au);
		be=parseInt(be);
		base_str=str,base_smart=smart,base_agi=agl,base_vit=vit,base_life=life,base_au=au,base_be=be;		
		parent.lv_up2(str,agl,smart,life,vit,au,be,null);
	}
};
function lv_up2(str,agl,smart,life,vit,au,be,p_exp)
{
	if(p_exp!=null)
	{
		w_c(temp_table1+'<tr><td class="b1"><b>'+p_name+' 能力上升 </b></td></tr>');
		w_c('<tr><td class="b1"><b>經驗值上升 '+p_exp+'</b></td></tr>');
	}
	w_c('<tr><td><b>力量上升 '+str+',敏捷上升 '+agl+',智力上升 '+smart+',生命上升 '+life+',體質上升 '+vit+',魅力上升 '+au+',信仰上升 '+be+'</b></td></tr>');
	w_c(temp_table2);
	p_nc();
};
function cp_end(p_name)
{
	w_c(temp_table1);
	w_c('<tr><td  >'+p_name+' 順利當上冠軍 </td></tr>')
	w_c(temp_table2);
	p_nc();
};