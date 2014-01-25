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
function pet_break()
{
	w_c(temp_table1);
	w_c('<tr><td>'+d_item2_name+'受到損壞，數量減少1個！！</td></tr>');
	w_c(temp_table2);
	p_nc();
};
function pet_get(s)
{
	w_c(temp_table1);
	w_c('<tr><td>捕捉到 '+s+'，'+d_item2_name+'數量減少1個！！</td></tr>');
	w_c(temp_table2);
	p_nc();
};
function pet_index(name,mname,id,s,pe_list,item_list)
{
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1>');	
	w_c(temp_table1);
	w_c('<tr class="head_td"><td>名稱</td><td>AT</td><td>MT</td><td>DEF</td><td>飽腹</td></tr>');
	var s1=s.split(",");
	var st=s1[9];
	var m_img=s1[10];
	if(m_img=="")
	{
		m_img="no_img.jpg";
	}
	if(s1[11]==0)
	{
		m_img=mimg+m_img;
	}
	w_c('<tr><td rowspan="6">'+name+'-'+mname+' '+s1[6]+'歲<br><img src="'+m_img+'"><br><a href="javascript:parent.pet_leave('+id+');">放生</a></td><td>'+s1[0]+'</td><td>'+s1[1]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td></tr>');
	w_c('<tr class="head_td"><td>個性</td><td>出擊值</td><td>親密度</td></tr>');
	w_c('<tr><td>'+pet_type(s1[5])+'</td><td>'+s1[8]+'</td><td>'+s1[7]+'</td></tr>');
	w_c('<tr><td colspan="4">狀態：<input type="radio" name="pe_st" value="0" onclick="parent.foot_turn(\'pet\',\'chg_st\','+id+',0);">攜帶 <input type="radio" name="pe_st" value="2" onclick="parent.foot_turn(\'pet\',\'chg_st\','+id+',2);">獸欄</td></tr>');
	w_c('<tr><td colspan="4">選擇寵物：');
	var s2=pe_list.split(";");
	for(var i=0;i<s2.length;i++)
	{
		var s3=s2[i].split(",");
		w_c(' <a href=javascript:parent.act_click(\'pet\',\'index\',\''+s3[1]+'\')>'+s3[0]+'</a>');
	}
	w_c('</td></tr>');
	w_c('<tr><td colspan="4"><input type="text" value="'+name+'" size="15" maxlength="30" name=rename> <input type="button" value="改名" onclick="parent.foot_turn(\'pet\',\'rename\','+id+',document.f1.rename.value);"></td></tr>');
	w_c('<tr><td colspan="5"><select name="act1" ><option value="1" SELECTED>初級活力果實</option><option value="2">初級香氣果實</option><option value="3">初級甜味果實</option><option value="4">初級野性果實</option><option value="5">中級活力果實</option><option value="6">中級香氣果實</option><option value="7">中級甜味果實</option><option value="8">中級野性果實</option>');
	if(item_list!="")
	{
		var s1=item_list.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			w_c('<option value="'+s2[0]+'">'+s2[1]+'</option>');
		}
		
	}
	w_c('</select> <input type="button" value="餵食" onclick="parent.foot_turn(\'pet\',\'eat\','+id+',document.f1.act1.value);"> | <input type="text" size="15" name=money> <input type="button" value="拍賣" onclick="parent.foot_turn(\'pet\',\'sale\','+id+',document.f1.money.value);"></td></tr>');
	w_c('<tr><td colspan="5">輸入圖像連結 <input type="text" name="url" value="http://" size="30" maxlength="200"> <input type="button" value="自訂圖像" onclick="parent.foot_turn(\'pet\',\'img\',document.f1.url.value,'+id+');"> <input type="button" value="還原圖像" onclick="parent.act_click(\'pet\',\'unimg\','+id+');"></td></tr>');
	w_c('<tr><td colspan="5" class=b1><ol><li>餵食初級須花費20元,中級須花費50元</li><li>選擇攜帶時,寵物會根據親密值及主人魅力值,隨機出現支援戰鬥或抵擋敵人攻擊</li></ol></td></tr>');
	w_c('<input type="hidden" name="pet_id" value="'+id+'">');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
	if(st=="0")
	{
		f.f1.pe_st[0].checked=true;
	}else
	{
		f.f1.pe_st[1].checked=true;
	}
};
function pet_pact(temp_d,s)
{
	temp_d=' <font color=red><b>'+temp_d+'<b></font>'
	pet_view_fight(temp_d,s,pet_pname,pet_mname);
};
function pet_mact(temp_d,s)
{
	temp_d=' <font color=red><b>'+temp_d+'<b></font>'
	pet_view_fight(temp_d,s,pet_mname,pet_pname);
};
function pet_miss_date(a,d)
{
	(a==pet_pname)?a='<font color="#89C7F3">'+a+'</font>':a='<font color="#81D8A8">'+a+'</font>';
	var s=new Array;
	s[0]=" 沒有擊中目標";
	s[1]=" 發呆";
	s[2]=" 東張西望";
	s[3]=" 失去鬥志";
//	f.getElementById("a2").style.display="none";
//	f.getElementById("a3").style.display="none";
//	f.getElementById("a1").innerHTML=a+s[d];
	fight_message+=a+s[d]+'<br>';
//	anime_atk('g3', 'g2',true);
};
function pet_leave(id)
{
	if(confirm("確定放生?"))
	{
		foot_turn('pet','leave',id);
	}
};
function pet_detail(at,mt,def,fu,he,fi)
{
	
	w_c(temp_table1);
	w_c('<tr><td>AT</td><td>MT</td><td>DEF</td><td>飽腹</td><td>親密度</td><td>出擊值</td></tr>');
	w_c('<tr><td>'+at+'</td><td>'+mt+'</td><td>'+def+'</td><td>'+fu+'</td><td>'+he+'</td><td>'+fi+'</td></tr>');
	w_c(temp_table2);
	p_c();
};
function pet_type(a)
{
	var s=new Array;
	s[0]="";
	s[1]="積極";
	s[2]="冷酷";
	s[3]="鐵壁";
	s[4]="危急";
	return s[a];
};
