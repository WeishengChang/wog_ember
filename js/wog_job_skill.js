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
//##### skill ######
function job_list()
{
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	w_c(temp_table1);
	w_c('<tr class="head_td"><td colspan="2" align="center">請選擇職業</td></tr>');
	w_c(job_skill_menu);
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function skill_list(s,a2)
{
	w_c(temp_table1);
	w_c(job_skill_menu);
	w_c(temp_table2);
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	w_c(temp_table1);
	w_c('<tr class="head_td"><td></td><td>技能名稱</td><td>技能說明</td><td>回合數</td><td>消費SP</td><td>類型</td><td>機率</td><td>所需技能熟練度</td><td>費用</td></tr>');
	if(s!="")
	{
		var s1=s.split(";");
		for(var i=0;i<s1.length;i++)
		{		
			var s2=s1[i].split(",");
			if(s2[8]=="0")
			{
				s2[8]="主動";
				s2[9]="---";
			}else
			{
				s2[8]="被動";				
			}
			w_c('<tr><td><input type="radio" name="s_id" value="'+s2[0]+'"></td><td>'+s2[1]+'LV'+s2[7]+'</td><td>'+s2[4]+'</td><td>'+s2[6]+'</td><td>'+s2[2]+'</td><td>'+s2[8]+'</td><td>'+s2[9]+'%</td><td>'+job_pro(s2[3])+'%</td><td>'+s2[5]+'</td></tr>');
		}
		w_c('<tr><td colspan="9" ><input type="button" value="學習技能" onClick="parent.foot_turn(\'skill\',\'get\',null,null,document.f1.s_id)"></td></tr>');
	}
	else
	{
		w_c('<tr><td colspan="9">沒有可學習的技能</td></tr>');
	}
	w_c('<tr><td colspan="9">目前擁有 '+job_pro(a2)+'% 技能熟練</td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function skill_view(a1,a2,a)
{
	var i=0;
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	w_c(left_table2);
	w_c('<tr><td colspan="3" class="head_td">主動技能</td></tr>');

	w_c('<tr><td>技能欄</td><td>技能名稱</td><td>發動機率</td></tr>');
	
	var s1=a1.split(";");
	var sid=0;
	if(a1!="")
	{
		for(i=0;i<s1.length;i++)
		{		
			var s2=s1[i].split(",");
			sid=s2[6];
			if(s2[1]!=null)
			{
				var temp_message=s2[1]+","+s2[4]+","+s2[2]+","+s2[3];
				w_c('<tr><td ><input type="radio" name="s_id" value="'+sid+'"></td><td><a class=uline onmouseover=parent.wog_message_box("'+temp_message+'",4,0,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\')>'+s2[1]+'</a></td><td><input type="text" name="p_time_'+s2[6]+'" value="'+s2[5]+'" size="3" maxlength="3">%</td>');
			}else
			{
				w_c('<tr><td ><input type="radio" name="s_id" value="'+s2[0]+'"></td><td></td><td></td>');
			}
			w_c('</tr>');
		}
	}
	w_c('<tr><td colspan="3" class="head_td">被動技能</td></tr>');
	w_c('<tr><td>技能欄</td><td>技能名稱</td><td>發動機率</td></tr>');
	var s1=a2.split(";");
	var sid=0;
	if(a2!="")
	{
		for(i=0;i<s1.length;i++)
		{		
			var s2=s1[i].split(",");
			sid=s2[6];
			if(s2[1]!=null)
			{
				var temp_message=s2[1]+","+s2[4]+","+s2[2]+","+s2[3];
				w_c('<tr><td ><input type="radio" name="s_id" value="'+sid+'"></td><td><a class=uline onmouseover=parent.wog_message_box("'+temp_message+'",4,0,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\')>'+s2[1]+'</a></td><td>'+s2[5]+'%</td>');
			}else
			{
				w_c('<tr><td ><input type="radio" name="s_id" value="'+s2[0]+'"></td><td></td><td></td>');
			}
			w_c('</tr>');
		}
	}
	w_c('<tr><td colspan="3" ><input type="submit" value="安裝技能" onClick="document.f1.act.value=\'setup\'"> <input type="button" value="卸下技能" onClick="parent.foot_turn(\'skill\',\'unset\',null,null,document.f1.s_id)"> <input type="submit" value="變更發動機率" onClick="document.f1.act.value=\'change_use\'" ></td></tr>');
	w_c(temp_table2);
	w_c('<span id=job_view></span>');
	w_c('<input type="hidden" name="f" value="skill">');
	w_c('<input type="hidden" name="act" value="setup">');
	w_c('</form>');
	p_c();
	skill_rview(a);
};
function skill_rview(a)
{
	var temp='';
	temp+=right_table2;
	temp+='<tr class="head_td"><td colspan="7">請選擇要安裝的技能</td></tr>';
	temp+='<tr><td colspan="7" align="center"><table align="center">'+job_skill_menu2+'</table></td></tr>';
	temp+='<tr><td></td><td>技能名稱</td><td>技能說明</td><td>回合數</td><td>消費SP</td><td>類型</td><td>機率</td></tr>';
	if(a!="")
	{
		var s1=a.split(";");
		for(var i=0;i<s1.length;i++)
		{		
			var s2=s1[i].split(",");
			if(s2[5]=="0")
			{
				s2[5]="主動";
				s2[6]="---";
			}else
			{
				s2[5]="被動";				
			}
			temp+='<tr><td><input type="radio" name="s_eq_id" value="'+s2[0]+'"></td><td >'+s2[1]+'</td><td class=b1>'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+s2[2]+'</td><td>'+s2[5]+'</td><td>'+s2[6]+'%</td></tr>';
		}
	}
	temp+='<tr><td colspan="7" ><input type="submit" value="安裝技能" onClick="document.f1.act.value=\'setup\'"></td></tr>';
	temp+=temp_table2;
	f.getElementById("job_view").innerHTML=temp;
};
//##### job ######
function job_view(s)
{
	var f=parent.wog_view.document;
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	w_c(temp_table1);
	w_c('<tr><td></td><td>名稱</td><td>力量</td><td>智慧</td><td>生命</td><td>體質</td><td>敏捷</td><td>魅力</td><td>信仰</td><td>轉職所需職業熟練度</td><td>目前職業熟練度</td></tr>')
	var s1=s.split(";");
	for(var i=0;i<s1.length;i++)
	{		
		var s2=s1[i].split("|");
		for(var j=0;j<(s2.length-1);j++)
		{
			s2[j]=s2[j].replace(",","-");
		}
		var eq_job="";
		if(s2[11]=="1")
		{
			eq_job="<font color=red>E</font>";
		}
		else
		{
			eq_job='<input type="radio" name="job_id" value="'+s2[0]+'">';
		}
		w_c('<tr><td>'+eq_job+'</td><td >'+s2[1]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td >'+s2[4]+'</td><td>'+s2[5]+'</td><td >'+s2[6]+'</td><td>'+s2[7]+'</td><td>'+s2[8]+'</td><td>'+s2[12]+" "+job_pro(s2[10])+'%</td><td>'+job_pro(s2[9])+'%</td></tr>');
	}
	w_c('<tr><td colspan="11" ><input type="button" value="變更職業" onClick="parent.foot_turn(\'job\',\'setup\',null,null,document.f1.job_id)"></td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function job_pro(s)
{
	s=parseInt(s);
	s=Math.floor((s/3500)*100);
	return s;
};

