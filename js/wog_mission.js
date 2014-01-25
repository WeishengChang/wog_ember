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
//###### mission #######
function mission_list(saletotal,page,s)
{
	w_c(temp_table1+mission_store+temp_table2);
	w_c('<form action="wog_act.php" method="post" name=pageform target="mission">');
	pagesplit(saletotal,page);
	w_c('<input type="hidden" name="page" value="">');
	w_c('<input type="hidden" name="f" value="mission">');
	w_c('<input type="hidden" name="act" value="list">');
	w_c('</form>');
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr class="head_td"><td></td><td>委託者</td><td>任務主題</td><td>限制等級</td><td>截止時間</td></tr>');
	if(s!="")
	{
		var s1=s.split(";");
		var temp_s="";
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			w_c('<tr><td><input type="radio" name="m_id" value="'+s2[0]+'"></td><td>'+s2[1]+'</td><td><a href="javascript:parent.act_click(\'mission\',\'detail\','+s2[0]+')">'+s2[2]+'</a></td><td>'+s2[4]+'</td><td>'+s2[3]+'</td></tr>');
		}
		w_c('<tr><td colspan="5"><input type="submit" value="接受任務"></td></tr>');
		w_c(temp_table2);
		w_c('<input type="hidden" name="f" value="mission">');
		w_c('<input type="hidden" name="act" value="get">');
	}else
	{
		w_c('<tr><td colspan="4" align="center">目前無任務</td></tr>');
		w_c(temp_table2);
	}
	w_c('</form>');
	p_c();
};
function mission_book(json)
{
	w_c(temp_table1+mission_store+temp_table2);
	if(json.length>0){
		for(var i=0,mission;mission=json[i];i++){
			w_c('<form action="wog_act.php" method="post" target="mission" name=f1>');
			w_c(temp_table1);
			w_c('<tr><td>委託者：'+mission[2]+'</td></tr>');
			w_c('<tr><td><a href="javascript:parent.act_click(\'mission\',\'detail\','+mission[0]+')">'+mission[1]+'</a></td></tr>');
			if(mission[3]){
				w_c('<tr><td>剩餘：'+mission[3]+' 隻</td></tr>');
			}
			if(mission[5]){
				w_c('<tr><td>※這是限時任務，剩餘時間：'+Math.ceil(parseInt(mission[4],10)/3600)+' 小時</td></tr>');
			}
			w_c('<tr><td ><input type="button" value="完成任務" onClick="parent.foot_turn(\'mission\',\'end\',\'\','+mission[0]+',\'\')"> <input type="button" value="放棄任務" onClick="parent.foot_turn(\'mission\',\'break\',\'\','+mission[0]+',\'\')"></td></tr>');
			w_c(temp_table2);
			w_c('</form><br/>');
			}
	}
	p_c();
};
function mission_achieve(a,b)
{
	w_c(hr);
	w_c(temp_table1+'<tr><td ><b>任務進度：'+m_name+' '+a+'/'+b+'</b></td></tr>');
	w_c(temp_table2);
	p_nc();
};
function npc_message(message,a,b)
{
	w_c(hr);
	while(message.indexOf("&n") > 0)
	{
		message=message.replace("&n","<br>");
	}
	w_c(temp_table1+'<tr><td ><b>發現：'+m_name+' '+a+'/'+b+'</b></td></tr><tr><td class=b1>'+message+'</td></tr>');
	w_c(temp_table2);
	p_nc();
};
function mission_paper(saletotal,page,s)
{
	w_c(temp_table1+mission_store+temp_table2);
	w_c('<form action="wog_act.php" method="post" name=pageform target="mission">');
	pagesplit(saletotal,page);
	w_c('<input type="hidden" name="page" value="">');
	w_c('<input type="hidden" name="f" value="mission">');
	w_c('<input type="hidden" name="act" value="paper">');
	w_c('</form>');
	w_c(temp_table1);
	w_c(temp_table1);
	w_c('<tr><td>委託者</td><td>任務主題</td><td>限制等級</td></tr>');
	if(s!="")
	{
		var s1=s.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			w_c('<tr><td>'+s2[0]+'</td><td><a href="javascript:parent.act_click(\'mission\',\'detail\','+s2[2]+')">'+s2[1]+'</a></td><td>'+s2[3]+'</td></tr>');
		}
	}
	w_c(temp_table2);
	p_c();
};
function mission_ed()
{
	w_c(temp_table1+mission_store+temp_table2);
	p_c();
};