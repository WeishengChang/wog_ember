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
//##### shop begin #####
function select_store()
{	
	w_c('<form action="wog_act.php" method="post" target="mission" name="f1">');
	w_c(temp_table1);
	w_c('<tr><td colspan="2" class="head_td">請選擇要進入的商店</td></tr>')
	w_c('<tr><td>裝備道具商店 》</td><td><a class="uline" onClick="parent.shop_home_view(\'\',\'\',0,\'\',\'\')">【武器】</a> <a class="uline" onClick="parent.shop_home_view(\'\',\'\',1,\'\',\'\')">【頭部】</a> <a class="uline" onClick="parent.shop_home_view(\'\',\'\',2,\'\',\'\')">【身體】</a> <a class="uline" onClick="parent.shop_home_view(\'\',\'\',3,\'\',\'\')">【手部】</a> <a class="uline" onClick="parent.shop_home_view(\'\',\'\',4,\'\',\'\')">【腳部】</a> <a class="uline" onClick="parent.th_submit(document.f1,1,\'5\')">【道具屋】</a> <a class="uline" onClick="parent.honor_view(null)">【勳章所】</a></td></tr>');
	w_c('<tr class=head_td3><td >其他商店 》</td><td><a class="uline" onClick="parent.act_click(\'plus\',\'view\')">【精鍊房】</a> <a class="uline" onClick="parent.act_click(\'mercenary\',\'view\')">【傭兵所】</a> <a class="uline" onClick="parent.black_shop()">【換膚室】</a></td></tr>');
	w_c('<tr><td >其他商店  》</td><td><a class="uline" onClick="parent.act_click(\'syn\',\'list\')">【合成大師】</a> <a class="uline" onClick="parent.act_click(\'job\',\'view\')">【職業中心】</a> <a class="uline" onClick="parent.job_list();">【技能大師】</a></td></tr>');
	w_c('<tr class=head_td3><td >其他商店  》</td><td><a class="uline" onClick="parent.act_click(\'exchange\',\'view\')">【資源市場】</a> <a class="uline" onClick="parent.act_click(\'mall\',\'view\')">【加值商城】</a></td></tr>');
	w_c('<tr><td >其他設施 》</td><td><a class="uline" onClick="parent.act_gclick(\'main\',\'view\')">【公會】</a> <a class="uline" onClick="parent.act_click(\'pet\',\'index\')">【牧場】</a> <a class="uline" onClick="parent.team_view()">【團隊】</a> <a class="uline" onClick="parent.act_click(\'message\',\'list\')">【信箱】</a></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="shop">');
	w_c('<input type="hidden" name="act" value="view">');
	w_c('<input type="hidden" name="temp_id" value="">');
	w_c('<input type="hidden" name="temp_id2" value="">');
	w_c('</form>');
	p_c();
};
function mall_view(a1,a2,a3,a4)
{
	w_c('<form action="wog_act.php" method="post" name="pageform" target="mission">');
	pagesplit(a3, a4);
	w_c('<input type="hidden" name="page" value="1">');
	w_c('<input type="hidden" name="f" value="mall">');
	w_c('<input type="hidden" name="act" value="view">');
	w_c('</form>');
	
	w_c('<form action="wog_act.php" method="post" target="mission" name="f1">');
	w_c(temp_table1);
	w_c('<tr class="head_td"><td></td><td>名稱</td><td>說明</td><td>需求點數</td><td>庫存數量</td></tr>');
	if(a1!="")
	{
		var s1=a1.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			w_c('<tr><td><input type="radio" name="temp_id" value="'+s2[0]+'"></td><td>'+s2[1]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'</td></tr>');
		}		
	}
	w_c('<tr><td colspan="5">請填入數量(1-99) <input type="text" name="buy_num" size="2" maxlength="2" value=1> <input type="submit" value="確定兌換"></td></tr>');
	w_c('<tr><td colspan="5">剩餘遊戲點 : <span id="show_message">'+a2+'</span></td></tr>');
	w_c('<input type="hidden" name="f" value="mall">');
	w_c('<input type="hidden" name="act" value="buy">');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function restup(a1,a2)
{
	w_c(group_table1);
	var s1=a1.split(",");
	w_c('<tr class="head_td2"><td class=b1 colspan="7">LV '+s1[0]+' 重新配點結果</td></tr>');
	w_c('<tr class="head_td"><td>力量</td><td>敏捷</td><td>智力</td><td>生命</td><td>體質</td><td>魅力</td><td>信仰</td></tr>');
	w_c('<tr><td>+'+s1[1]+'</td><td>+'+s1[2]+'</td><td>+'+s1[5]+'</td><td>+'+s1[3]+'</td><td>+'+s1[4]+'</td><td>+'+s1[7]+'</td><td>+'+s1[6]+'</td></tr>');
	w_c('<tr><td colspan="7">'+hr+'</td></tr>');
	var s1=a2.split(",");
	w_c('<tr class="head_td2"><td colspan="7">現在基礎狀態</td></tr>');
	w_c('<tr class="head_td"><td>力量</td><td>敏捷</td><td>智力</td><td>生命</td><td>體質</td><td>魅力</td><td>信仰</td></tr>');
	w_c('<tr><td>'+s1[0]+'</td><td>'+s1[1]+'</td><td>'+s1[4]+'</td><td>'+s1[2]+'</td><td>'+s1[3]+'</td><td>'+s1[6]+'</td><td>'+s1[5]+'</td></tr>');
	w_c('<tr class="head_td2"><td colspan="7"><a href=javascript:parent.act_click(\'mall\',\'restup\');>重新配點</a> <a href=javascript:parent.act_click(\'chara\',\'status_view\');>角色狀態</a></td></tr>');
	w_c('<tr ><td colspan="7">重新分配升級能力點數，需消耗 3 遊戲點</td></tr>');
	w_c(temp_table2);
	p_c();
};
function honor_view(a,b,c,d,e,g)
{
	w_c(temp_table1);
	w_c(item_store_menu);
	w_c(honor_menu);
	w_c(temp_table2);
	if(a != null) {
		w_c('<form action="wog_act.php" method="post" name="pageform" target="mission">');
		pagesplit(d, e);
		w_c('<input type="hidden" name="page" value="1">');
		w_c('<input type="hidden" name="temp_id" value="' + g + '">');
		w_c('<input type="hidden" name="f" value="honor">');
		w_c('<input type="hidden" name="act" value="list">');
		w_c('</form>');
	}
	w_c('<form action="wog_act.php" method="post" target="mission" name="f1">');
	w_c(temp_table1);
	if(a==null)
	{
		w_c('<tr><td>請選擇種類</td></tr>');
		w_c(temp_table2);
		p_c();
		return;
	}
	w_c('<tr class="head_td"><td></td><td>名稱</td><td>需求</td></tr>');
	var s1=a.split(";");
	for(var i=0;i<s1.length;i++)
	{
		var s2=s1[i].split(",");
		var s3=s2[2].split(":");
		var temp="";
		for(var j=0;j<s3.length;j++)
		{
			var s4=s3[j].split("*");
			temp+=","+honor_name[s4[0]]+"*"+s4[1];
		}
		temp=temp.substr(1,temp.length);
		w_c('<tr><td><input type="radio" name="h_id" value="'+s2[0]+'"></td><td><a href="javascript:parent.foot_turn(\''+b+'\',\'check_item\',\''+c+'\','+s2[3]+')">'+s2[1]+'</a></td><td>'+temp+'</td></tr>');
	}
	w_c('<tr><td colspan="3" ><input type="submit" value="確定兌換"></td></tr>');
	w_c('<tr><td colspan="3" ><div id="show_message"></div></td></tr>');
	w_c('<input type="hidden" name="f" value="honor">');
	w_c('<input type="hidden" name="act" value="buy">');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function shop_home_view(s,a,temp_id,ss,temp_id2,df_total,page,money)
{
	w_c('<form action="wog_act.php" method="post" name="f2" target="mission">');
	w_c(temp_table1+item_store_menu);
	if(temp_id!="5")
	{
		w_c('<tr><td>物品等級 : '+shop_type_lv[temp_id]+'</td></tr>');
		w_c('<input type="hidden" name="f" value="shop">');
		w_c('<input type="hidden" name="act" value="view">');
		w_c('<input type="hidden" name="temp_id" value="">');
	}
	w_c(temp_table2+'<input type="hidden" name="temp_id2" value=""></form>');
	if(temp_id2=="" && temp_id!="5")
	{
		w_c(temp_table1+'<tr class="head_td2"><td>請選擇物品等級</td></tr>'+temp_table2);
		p_c();
		return;
	}
	w_c('<form action="wog_act.php" method="post" name="pageform" target="mission">');
	pagesplit(df_total,page);
	w_c('<input type="hidden" name="page" value="">');
	w_c('<input type="hidden" name="f" value="shop">');
	w_c('<input type="hidden" name="act" value="view">');
	w_c('<input type="hidden" name="temp_id" value="'+temp_id+'">');
	w_c('<input type="hidden" name="temp_id2" value="'+temp_id2+'">');
	w_c('</form>');
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr class="head_td"><td></td><td>名稱</td><td><u title="物理攻擊力">物攻</u></td><td><u title="魔法攻擊力">魔攻</u></td><td><u title="物理防禦力">物防</u></td><td><u title="魔法防禦力">魔防</u></td><td>職業</td><td>能力限制</td><td>價格</td></tr>');
	if(s!="")
	{
		var s1=s.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var temp_message=s2[4]+","+s2[12]+","+s2[13]+","+s2[14]+","+s2[17]+","+s2[16]+","+s2[18]+","+s2[15]+",,,"+s2[19]+","+s2[20]+","+s2[3]+",0";
			w_c('<tr><td ><input type="radio" name="adds" value="'+s2[0]+'"></td><td><a href="javascript:return false" onmouseover=parent.wog_message_box("'+temp_message+'",1,4,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\')>'+s2[4]+'</a></td><td>'+s2[5]+'</td><td>'+s2[6]+'</td><td>'+s2[1]+'</td><td>'+s2[2]+'</td><td>'+s2[11]+'</td><td>'+arm_need_status(s2[7],s2[8],s2[9],s2[10])+'</td><td>'+s2[3]+'</td></tr>');
		}
	}else
	{
		w_c('<tr><td colspan="10" >沒有可以使用的裝具</td></tr>');
	}
	if(ss != "")
	{
		s1=ss.split(";");
		for(var i=0;i<s1.length;i++)
		{
			s2=s1[i].split(",");
			var temp_message=s2[0]+","+s2[5]+","+s2[6]+","+s2[7]+","+s2[8]+","+s2[9]+","+s2[10]+","+s2[11]+","+s2[12]+","+s2[13]+","+s2[18]+","+s2[19]+","+s2[16]+","+s2[22];
			w_c('<tr bgcolor="#777779"><td><font color="#FF0000">E</font></td><td><a href="#" onmouseover=parent.wog_message_box("'+temp_message+'",1,4,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\'); ondblclick="parent.set_arm_tochat(\''+s2[0]+'\',\''+s2[20]+'\');" target="mission">'+s2[0]+'</a></td><td>'+bf_c(s2[1])+'</td><td>'+bf_c(s2[2])+'</td><td>'+bf_c(s2[3])+'</td><td>'+bf_c(s2[4])+'</td><td>---</td><td>---</td><td>'+s2[16]+'</td></tr>');			
		}
	}
	if(temp_id=="5")
	{
		w_c('<tr><td colspan="10">請填入數量(1-99) <input type="text" name="buy_num" size="2" maxlength="2"></td></tr>');
	}
	w_c('<tr><td colspan="10" ><input type="submit" value="確定購買"></td></tr>');
	w_c('<tr><td colspan="2">身上金額</td><td colspan="8" ><div id="show_message"> '+money+'</div></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="shop">');
	w_c('<input type="hidden" name="act" value="buy">');
	w_c('<input type="hidden" name="temp_id" value="'+temp_id+'">');
	w_c('</form>');
	p_c();
};
function black_shop()
{
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c('<input type="hidden" name="f" value="store">');
	w_c('<input type="hidden" name="act" value="img">');
	w_c(temp_table1);
	w_c('<tr><td>變更角色圖像</td></tr>');
	w_c('<tr><td>輸入圖像編號 <input type="text" name="num" size="2"> <a href="javascript://" onClick="window.open(\'wog_etc.php?f=img\',\'\',\'menubar=no,status=no,scrollbars=yes,top=50,left=50,toolbar=no,width=400,height=400\')">圖像預覽</a></td></tr>');
	w_c('<tr><td><input type="submit" value="使用系統圖像"></td></tr>');
	w_c('<tr><td>變臉手術需花1000</td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	w_c('<p>');
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c('<input type="hidden" name="f" value="store">');
	w_c('<input type="hidden" name="act" value="img2">');
	w_c(temp_table1);
	w_c('<tr><td>輸入圖像連結 <input type="text" name="url" value="http://" size="45" maxlength="150"> </td></tr>');
	w_c('<tr><td>(最佳size請勿超過 120*120)</td></tr>');
	w_c('<tr><td><input type="submit" value="使用自訂圖像"></td></tr>');
	w_c('<tr><td>變臉手術需花3000</td></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function sale_item(s,b)
{
	var temp_s="";
	if(s!=null)
	{
		if(s.length!=undefined)
		{
			for(var i=0;i<s.length;i++)
			{
				if(s[i].checked==true)
				{
					temp_s=s[i].value;
					break;
				}
			}
		}else
		{
			temp_s=s.value;
		}
	}
	var s1=temp_s.split(",");
	if(temp_s != "")
	{	
		arm_link();
		
		if(s1[1].indexOf("*")>-1)
		{
			var temp=s1[1].split("*");
			var d_name=temp[0];
		}else
		{
			var d_name=s1[1];
		}
		w_c('<form action="wog_act.php" method="post" target="mission">');
		w_c(temp_table1);
		w_c('<tr><td colspan="3">拍賣『'+d_name+'*'+b+'』</td></tr>');
		w_c('<tr><td>起標價格:<input type="text" name="money" size="9" maxlength="9" value="1"></td><td>結標價格:<input type="text" name="e_money" size="9" maxlength="9" value="0"></td><td>拍賣時間:<input type="text" name="day" size="2" maxlength="2" value="1">天</td></tr>');
		w_c('<tr><td colspan="3"><input type="submit" value="開始拍賣"></td></tr>');
		w_c('<tr><td colspan="3" class=b1><ol><li>拍賣時間不能超過10天,一人拍賣的商品不能超過5種</li><li>拍賣時間單位為天數,時間到期自動下架</li><li>拍賣會收取起標價手續費</li><li>拍賣過期物品會自動放入玩家背包，當背包無法放入時會自動放入信箱(<a href=javascript:parent.act_click(\'message\',\'item_list\')>取物</a>)</li></ol></td></tr>');
		w_c(temp_table2);
		w_c('<input type="hidden" name="f" value="sale">');
		w_c('<input type="hidden" name="act" value="sale">');
		w_c('<input type="hidden" name="item_id" value="'+s1[0]+'">');
		w_c('<input type="hidden" name="item_num" value="'+b+'">');
		w_c('</form>');
		p_c();
	}
	else
	{
		alert("請選擇拍賣物品");
	}
};
function sale_view(saletotal,page,s,type,key)//拍賣區
{
	w_c('<form action="wog_etc.php" method="get" name="pageform" target="mission">');
	pagesplit(saletotal,page);
	w_c('<input type="hidden" name="page" value="1">');
	w_c('<input type="hidden" name="type" value="'+type+'">');
	w_c('<input type="hidden" name="f" value="sale">');
	w_c('<input type="hidden" name="act" value="view">');
	w_c('<input type="hidden" name="key" value="'+key+'">');
	w_c('</form>');
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1>');
	w_c(temp_table1);
	w_c('<tr><td colspan="18"><a href=javascript:parent.sel_type("0");>武器</a> <a href=javascript:parent.sel_type("1");>頭部</a>  <a href=javascript:parent.sel_type("2");>身體</a> <a href=javascript:parent.sel_type("3");>手套</a> <a href=javascript:parent.sel_type("4");>鞋子</a> <a href=javascript:parent.sel_type("5");>道具</a> <a href=javascript:parent.sel_type("6");>寵物</a> <a href=javascript:parent.sel_type("7");>魔石</a> <a href=javascript:parent.sel_type("10");>精鍊石</a></td></tr>');
	w_c('<tr><td colspan="18">拍賣商品</td></tr>');
	switch(type)
	{
		case 6:
			w_c('<tr class="head_td"><td></td><td>拍賣者</td><td>商品</td><td>AT</td><td>MT</td><td>DEF</td><td>個性</td><td>年紀</td><td>出擊值</td><td>底價</td><td>剩餘日</td></tr>');
		break;
		case 7:
			w_c('<tr class="head_td"><td></td><td>拍賣者</td><td>商品</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>敏捷</td><td>力量</td><td>生命</td><td>體質</td><td>智力</td><td>魅力</td><td>信仰</td><td>HP</td><td>底價</td><td>結標價</td><td>剩餘日</td></tr>');
		break;
		default:
			w_c('<tr class="head_td"><td></td><td>拍賣者</td><td>商品</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>洞數</td><td>職業</td><td>能力限制</td><td>底價</td><td>結標價</td><td>剩餘日</td></tr>');
		break;
	}
	if(s != "")
	{
		var s1=s.split(";");
		var temp_form="document.f1";
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var now_date=new Date();
			var sale_day=null;
			var temp_stone_list="";
			switch(type)
			{
				case 6:
					sale_day=Math.ceil((s2[11]-(now_date.getTime()/1000))/(60*60*24));
					w_c('<tr><td><input type="radio" name="sp_id" value="'+s2[1]+'"></td><td>'+s2[0]+'</td><td>'+s2[2]+'-'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+s2[5]+'</td><td>'+s2[6]+'</td><td>'+pet_type(s2[7])+'</td><td>'+s2[8]+'</td><td>'+s2[9]+'</td><td>'+s2[10]+'</td><td>'+sale_day+'</td></tr>');
				break;
				case 7:
					if(s2[13]=="0"){s2[13]="∞";}
					sale_day=Math.ceil((s2[16]-(now_date.getTime()/1000))/(60*60*24));
					w_c('<tr><td><a class="uline" onclick="'+temp_form+'.act.value=\'buy\';'+temp_form+'.s_id.value=\''+s2[14]+'\';parent.sale_mbuy(event.x||event.pageX,event.y||event.pageY);">競標</a> <a class="uline" onclick="if(confirm(\'確認是否購買\')){'+temp_form+'.s_id.value=\''+s2[14]+'\';'+temp_form+'.act.value=\'buy3\';'+temp_form+'.submit();}">直購</a></td><td>'+s2[0]+'</td><td><a class=uline onclick=parent.arm_show(event.ctrlKey,"'+s2[1]+'","'+s2[19]+'") >'+s2[1]+'</a>*'+s2[17]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+s2[5]+'</td><td>'+s2[6]+'</td><td>'+s2[7]+'</td><td>'+s2[8]+'</td><td>'+s2[9]+'</td><td>'+s2[10]+'</td><td>'+s2[11]+'</td><td>'+s2[12]+'</td><td>'+s2[20]+'</td><td>'+s2[15]+'</td><td>'+s2[13]+'</td><td>'+sale_day+'</td></tr>');
				break;
				default:
					if(s2[25]=="0"){s2[25]="∞";}
					sale_day=Math.ceil((s2[27]-(now_date.getTime()/1000))/(60*60*24));
					var temp_message=s2[2]+","+s2[7]+","+s2[8]+","+s2[9]+","+s2[10]+","+s2[11]+","+s2[12]+","+s2[13]+","+s2[14]+","+s2[15]+","+s2[24]+","+s2[29]+",---,"+s2[30];
					w_c('<tr><td><a class="uline" onclick="'+temp_form+'.act.value=\'buy\';'+temp_form+'.s_id.value=\''+s2[0]+'\';parent.sale_mbuy(event.x||event.pageX,event.y||event.pageY);">競標</a> <a class="uline" onclick="if(confirm(\'確認是否購買\')){'+temp_form+'.s_id.value=\''+s2[0]+'\';'+temp_form+'.act.value=\'buy3\';'+temp_form+'.submit();}">直購</a></td><td>'+s2[28]+'</td><td><a class=uline onclick=parent.arm_show(event.ctrlKey,"'+s2[2]+'","'+s2[1]+'") onmouseover=parent.wog_message_box("'+temp_message+'",1,4,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\')>'+s2[2]+'</a></td><td>'+bf_c(s2[3])+'</td><td>'+bf_c(s2[4])+'</td><td>'+bf_c(s2[5])+'</td><td>'+bf_c(s2[6])+'</td><td>'+s2[24]+'</td><td>'+s2[21]+'</td><td>'+arm_need_status(s2[16],s2[17],s2[18],s2[19])+'</td><td>'+s2[26]+'</td><td>'+s2[25]+'</td><td>'+sale_day+'</td></tr>');
				break;
			}
		}
	}
	if (type == 6) {
		w_c('<input type="hidden" name="stype" value="1">');
		w_c('<tr><td colspan="17" ><input type="submit" value="確定購買"></td></tr>');
	}else{
		w_c('<input type="hidden" name="stype" value="0">');
	}
	w_c('<input type="hidden" name="f" value="sale">');
	w_c('<input type="hidden" name="act" value="buy">');
	w_c('<input type="hidden" name="type" value="'+type+'">');
	w_c('<input type="hidden" name="money" value=""><input type="hidden" name="s_id" value="">');
	w_c(temp_table2);
	w_c('</form>');
	w_c('<form action="wog_etc.php" method="get" name="serchform" target="mission" onsubmit="if(this.key.value==\'\'){alert(\'請輸入搜尋字\');return false;}">');
	w_c(temp_table1);
	w_c('<tr><td colspan="17"><input type="text" name="key" size="16"> <input type="submit" value="搜尋" ></td></tr>');
	w_c('<input type="hidden" name="type" value="'+type+'">');
	w_c('<input type="hidden" name="f" value="sale">');
	w_c('<input type="hidden" name="act" value="view">');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function sale_mbuy(x,y)
{
	w_c(build_table1);
	w_c('<tr><td>請輸入競標金額:<input type="text" name="money" value="" size="10" ></td></tr>');
	w_c('<tr><td colspan="2"><input type="button" value="確定送出" onclick="document.f1.money.value=this.form.money.value;document.f1.submit();parent.p_s_close();"> <input type="button" value="取消" onClick="parent.p_s_close();"></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="sale">');
	w_c('<input type="hidden" name="act" value="sale2">');
	p_as(x,y);
}
function sale2_view(saletotal,page,s,type,key)//收購區
{
	w_c('<form action="wog_etc.php" method="get" name="pageform" target="mission">');
	pagesplit(saletotal,page);
	w_c('<input type="hidden" name="page" value="1">');
	w_c('<input type="hidden" name="type" value="'+type+'">');
	w_c('<input type="hidden" name="f" value="sale">');
	w_c('<input type="hidden" name="act" value="view2">');
	w_c('<input type="hidden" name="key" value="'+key+'">');
	w_c('</form>');
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1>');
	w_c(temp_table1);
	w_c('<tr><td colspan="17"><a href=javascript:parent.sel_type("0");>武器</a> <a href=javascript:parent.sel_type("1");>頭部</a>  <a href=javascript:parent.sel_type("2");>身體</a> <a href=javascript:parent.sel_type("3");>手套</a> <a href=javascript:parent.sel_type("4");>鞋子</a> <a href=javascript:parent.sel_type("5");>道具</a> <a href=javascript:parent.sel_type("7");>魔石</a> <a href=javascript:parent.sel_type("10");>精鍊石</a></td></tr>');
	w_c('<tr><td colspan="17">收購商品</td></tr>');
	switch(type)
	{
		case 7:
			w_c('<tr class="head_td"><td></td><td>收購者</td><td>商品</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>敏捷</td><td>力量</td><td>生命</td><td>體質</td><td>智力</td><td>魅力</td><td>信仰</td><td>金額</td><td>原價</td><td>剩餘日</td></tr>');
		break;
		default:
			w_c('<tr class="head_td"><td></td><td>收購者</td><td>商品</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>洞數</td><td>職業</td><td>能力限制</td><td>金額</td><td>原價</td><td>剩餘日</td></tr>');
		break;
	}
	var s1=s.split(";");
	if(s != "")
	{
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var now_date=new Date();
			var sale_day=Math.ceil((s2[13]-(now_date.getTime()/1000))/(60*60*24));
			switch(type)
			{
				case 7:
					sale_day=Math.ceil((s2[16]-(now_date.getTime()/1000))/(60*60*24));
					w_c('<tr><td><input type="radio" name="s_id" value="'+s2[14]+'"></td><td>'+s2[0]+'</td><td>'+s2[1]+'*'+s2[17]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+s2[5]+'</td><td>'+s2[6]+'</td><td>'+s2[7]+'</td><td>'+s2[8]+'</td><td>'+s2[9]+'</td><td>'+s2[10]+'</td><td>'+s2[11]+'</td><td>'+s2[12]+'</td><td>'+s2[15]+'</td><td>'+s2[13]+'</td><td>'+sale_day+'</td></tr>');
				break;
				default:
					var temp_message=s2[1]+","+s2[18]+","+s2[19]+","+s2[20]+","+s2[23]+","+s2[22]+","+s2[24]+","+s2[21]+","+s2[25]+","+","+s2[17]+","+s2[26]+","+s2[10];
					w_c('<tr><td><input type="radio" name="s_id" value="'+s2[11]+'"></td><td>'+s2[0]+'</td><td><a class=uline onmouseover=parent.wog_message_box("'+temp_message+'",1,4,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\')>'+s2[1]+'</a>*'+s2[15]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+s2[5]+'</td><td>'+s2[17]+'</td><td>'+s2[14]+'</td><td>'+arm_need_status(s2[6],s2[7],s2[8],s2[9])+'</td><td>'+s2[12]+'</td><td>'+s2[10]+'</td><td>'+sale_day+'</td></tr>');
				break;
			}
		}
	}
	w_c('<tr><td colspan="17"><input type="submit" value="確定收購者"> <input type="button" value="張貼收購" onclick="parent.sale2_post(event.x||event.pageX,event.y||event.pageY);"></td></tr>');
	w_c('<input type="hidden" name="f" value="sale">');
	w_c('<input type="hidden" name="act" value="buy2">');
	w_c('<input type="hidden" name="type" value="'+type+'">');
	w_c(temp_table2);
	w_c('</form>');
	w_c('<div id="sel_item"></div>');
	w_c('<form action="wog_etc.php" method="get" name="serchform" target="mission" onsubmit="if(this.key.value==\'\'){alert(\'請輸入搜尋字\');return false;}">');
	w_c(temp_table1);
	w_c('<tr><td colspan="17"><input type="submit" value="搜尋" > <input type="text" name="key" size="16"></td></tr>');
	w_c('<input type="hidden" name="type" value="'+type+'">');
	w_c('<input type="hidden" name="f" value="sale">');
	w_c('<input type="hidden" name="act" value="view2">');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function arm2_view(a1,a4,a5)
{
	var temp_str="";
	var sum=0;
	temp_str+='<form action="wog_act.php" method="post" target="mission" name=f1>'+temp_table1;
	if(a4 != 7)
	{
		temp_str+='<tr class="head_td"><td></td><td>名稱</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>職業</td><td>屬性</td></tr>';
	}
	else
	{
		temp_str+='<tr class="head_td"><td></td><td>名稱</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>敏捷</td><td>力量</td><td>生命</td><td>體質</td><td>智力</td><td>魅力</td><td>信仰</td></tr>';
	}
	if(a1!="")
	{
		var s1=a1.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var arm_view_color ="";
			var s2=s1[i].split(",");
			sum++;
			if (a4 != 7)
			{
				if(s2[15]=="1"){arm_view_color="bgcolor="+nosend;}
				var temp_message=s2[1]+","+s2[6]+","+s2[7]+","+s2[8]+","+s2[9]+","+s2[10]+","+s2[11]+","+s2[12]+","+s2[13]+","+s2[14]+","+s2[19]+","+s2[20]+","+s2[17];
				temp_str+='<tr><td>'+sum+'. <input type="radio" name="adds" value="'+s2[0]+'"></td><td '+arm_view_color+'><a href="#" onmouseover=parent.wog_message_box("'+temp_message+'",1,4,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\');>'+s2[1]+'</a></td><td>'+bf_c(s2[2])+'</td><td>'+bf_c(s2[3])+'</td><td>'+bf_c(s2[4])+'</td><td>'+bf_c(s2[5])+'</td><td>'+s2[20]+'</td><td>'+s_status[s2[18]]+'</td></tr>';
			}
			else
			{
				temp_str+='<tr><td>'+sum+'. <input type="radio" name="adds" value="'+s2[0]+'"></td><td >'+s2[13]+temp_num+'</td><td>'+s2[1]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+s2[5]+'</td><td>'+s2[6]+'</td><td>'+s2[7]+'</td><td>'+s2[8]+'</td><td>'+s2[9]+'</td><td>'+s2[10]+'</td><td>'+s2[11]+'</td></tr>';
			}
			
		}
		temp_str+='<tr><td colspan="8"><input type="submit" value="確定賣出"></td></tr>';
	}
	temp_str+=temp_table2;
	temp_str+='<input type="hidden" name="f" value="sale">';
	temp_str+='<input type="hidden" name="act" value="buy2_1">';
	temp_str+='<input type="hidden" name="s_id" value="'+a5+'">';
	temp_str+='<input type="hidden" name="type" value='+a4+'>';
	temp_str+="</form>";
	f.getElementById("sel_item").innerHTML=temp_str;
}
function sale2_post(x,y){
	w_c(build_table1);
	w_c('<tr><td>收購價格:</td><td><input type="text" name="money" value="" size="10" ></td><td>收購時間:</td><td><input type="text" name="day" value="" size="10" ></td></tr>');
	w_c('<tr><td>收購物品:</td><td><input type="text" name="d_name" value="" size="10" ></td><td>物品洞數:</td><td><input type="text" name="d_hole" value="" size="10" ></td></tr>');
	w_c('<tr><td>收購數量:</td><td><input type="text" name="item_num" value="1" size="10" ></td></tr>');
	w_c('<tr><td colspan="4"><input type="submit" value="確定送出" > <input type="button" value="取消" onClick="parent.p_s_close();"></td></tr>');
	w_c('<tr><td colspan="4" class=b1><ol><li>收購時間不能超過10天</li><li>武器防具類收購數量強制為1</li><li>道具魔石類洞數強制為0</li><li>張貼收購會預收出價金額</li><li>收購到期且無人進行交易時金額會返回玩家身上</li><li>收購最低金額為1000元</li></ol></td></tr>')
	w_c(temp_table2);
	w_c('<input type="hidden" name="f" value="sale">');
	w_c('<input type="hidden" name="act" value="sale2">');
	p_as(x,y);
};
//##### bank #####
function bank(a,b)
{
	w_c(temp_table1+bank_depot_menu+temp_table2);
	w_c('<form action="wog_act.php" method="post" target="mission">');	
	w_c(temp_table1);
	w_c('<tr class="head_td"><td colspan="2" >身上現金 : '+a+' , 銀行存款 : '+b+'</td></tr>');
	w_c('<tr><td  width="70%">金額 <input type="text" name="money" size="5"></td><td ><input type="submit" value="存款"></td></tr>');
	w_c('<input type="hidden" name="f" value="bank">');
	w_c('<input type="hidden" name="act" value="save">');
	w_c(temp_table2);
	w_c('</form>');
	w_c('<form action="wog_act.php" method="post" target="mission">');
	w_c(temp_table1);
	w_c('<tr><td width="70%">金額 <input type="text" name="money" size="5"></td><td ><input type="submit" value="提款"></td></tr>')
	w_c('<input type="hidden" name="f" value="bank">');
	w_c('<input type="hidden" name="act" value="get">');
	w_c(temp_table2);
	w_c('</form>');
	w_c('<form name=f1>');
	w_c(temp_table1);
	w_c('<tr><td width="70%">金額 <input type="text" name="money" size="5"> ,對象帳號 <input type="text" name="pay_id" size="6"></td><td ><input type="button" value="轉帳" onclick="parent.foot_turn(\'bank\',\'pay\',document.f1.pay_id.value,document.f1.money.value,null)"></td></form></tr>');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function mercenary_view(a,b,c){
	var temp_status="";
	var area_set="";
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	if(job_work.mercenary_start==null || job_work.mercenary_start==undefined)
	{
		mercenary_f();
	}
	w_c(left_table1);
	w_c('<tr><td colspan="8" class=b1>歡迎來到傭兵所,請選擇欲雇用的傭兵,傭兵屬性能力及技能與玩家相同,不同等級傭兵戰鬥獲得的金錢跟寶物的比例不同,例F級傭兵戰鬥獲得的金錢為原來的30%,打寶率為原來的30%,經驗值為原來的65%。死亡消耗為傭兵戰敗時,需要扣減多少戰鬥次數</td></tr>');
	w_c('<tr class="head_td"><td></td><td>名稱</td><td>價格</td><td>戰鬥次數</td><td>金錢</td><td>寶物</td><td>經驗</td><td>死亡消耗</td></tr>');
	if (a != "") {
		var s1=a.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2=s1[i].split(",");
			w_c('<tr><td><input type="radio" name="item_id" value="'+s2[0]+'"></td><td>'+s2[1]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'%</td><td>'+s2[5]+'%</td><td>'+s2[6]+'%</td><td>'+s2[7]+'</td></tr>');
		}
	}
	w_c('<tr><td colspan="8"><input type="submit" value="確認僱用"></td></tr>');
	w_c('<input type="hidden" name="f" value="mercenary">');
	w_c('<input type="hidden" name="act" value="buy">');
	w_c(temp_table2);
	w_c('</form>');
	w_c('<form action="wog_act.php" method="post" target="mission" name=f2 >');
	w_c(right_table1);
	if (b != "") {
		w_c('<tr class="head_td"><td>名稱</td><td>狀態</td><td>設定</td><td>剩餘次數</td></tr>');
		var s1 = b.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2 = s1[i].split(",");
			if (s2[1] == '0') {
				temp_status = "休息中";
			}
			if (s2[1] == '1') {
				temp_status = "戰鬥中";
			}
			w_c('<tr><td><input type="text" name="me_name" value="'+s2[3]+'" maxlength="8" size="8"></td><td>' + temp_status + '</td><td><select name="status" ><option value="0" >停止戰鬥</option><option value="1" >開始戰鬥</option><option value="2" >解除僱用</option></select></td><td>' + s2[2] + '</td></tr>');
			w_c('<tr><td>選擇冒險地  :</td><td>'+sec[s2[4]][0]+'</td><td colspan="2" class=b1>'+temp_country);
			w_c(' <select name="act1" ><option value="" SELECTED>選擇場所</option></select></td></tr>');
		}
		area_set=s2[1];
		w_c('<tr><td colspan="4"><input type="submit" value="確認設定"></td></tr>');
		w_c('<input type="hidden" name="f" value="mercenary">');
		w_c('<input type="hidden" name="act" value="set">');
		w_c('<input type="hidden" name="item_id" value="' + s2[0] + '">');
		w_c('<tr><td colspan="4">'+hr+'</td></tr>');
		if (c != "") {
			var s1 = c.split(";");
			for (var i = 0; i < s1.length; i++) {
				var s2 = s1[i].split(",");
				w_c('<tr><td colspan="4" class=b1>' + s2[0] + ' at ' + s2[1] + '</td></tr>');
			}
		}
		w_c(temp_table2);
		w_c('</form>');
		w_c('<script language="JavaScript">document.f2.status.value=' + area_set + ';</script>');
	}
	p_c();
};
function mercenary_job_view(a1,a2)
{
	if(f.getElementById("wog_mercenary_job"))
	{
		set_window();
		f.getElementById("wog_mercenary_job").style.top=UI.window_h-22;
		f.getElementById("wog_mercenary_job").style.display="block";
		f.getElementById("wog_mercenary_job").innerHTML=mercenary_table1+'<tr><td>'+a1+' </td><td width="15" onClick="parent.mercenary_job_close();" bgcolor="#4e4e4e" class="click">X</td></tr>'+temp_table2;
		//window.setTimeout("mercenary_job_close()",5000);
		if(a2==null)
		{
			window.setTimeout("mercenary_job_close(1)",5000);			
		}
	}
};
function mercenary_job_close(a1)
{
	if(f.getElementById("wog_mercenary_job"))
	{
		f.getElementById("wog_mercenary_job").style.display="none";
		f.getElementById("wog_mercenary_job").innerHTML="";
		if(mercenary_set==0 && a1!=null)
		{
			mercenary_job_view("傭兵戰鬥次數為0，[<a href=javascript:parent.act_click(\'mercenary\',\'view\')>繼續雇用</a>]",1);
		}
	}
};
function exchange_view(a, b){
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	w_c(left_table1);
	w_c('<tr><td colspan="6"><input type="button" value="交易市場" onClick="parent.act_click(\'exchange\',\'view\')"> <input type="button" value="庫存明細" onClick="parent.act_click(\'exchange\',\'list\')"></td></tr>');
	w_c('<tr><td></td><td>名稱</td><td>價格</td><td>市場剩餘量</td><td>漲幅</td><td>漲幅變動時間</td></tr>');
	if (a != "") {
		var s1=a.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2=s1[i].split(",");
			if(s2[4]=="0")
			{
				s2[4]="";
			}
			if(s2[4]=="1")
			{
				s2[4]="<font color=red>↑"+s2[6]+"%</font>";
			}
			if(s2[4]=="2")
			{
				s2[4]="<font color=green>↓"+s2[6]+"%</font>";
			}
			w_c('<tr><td><input type="radio" name="item_id" value="'+s2[0]+'"></td><td>'+s2[1]+'</td><td>'+s2[2]+'</td><td>'+s2[3]+'</td><td>'+s2[4]+'</td><td>'+s2[5]+'</td></tr>');
		}
	}
	w_c('<tr><td colspan="6">請輸入數量:<input type="text" name="item_num" size="16"> <input type="submit" value="確認購入"></td></tr>');
	w_c('<input type="hidden" name="f" value="exchange">');
	w_c('<input type="hidden" name="act" value="buy">');
	w_c(temp_table2);
	w_c('</form>');
	w_c(right_table1);
	w_c('<tr><td>內容</td><td>變動時間</td></tr>');
	if (b != "") {
		var s1 = b.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2=s1[i].split(",");
			w_c('<tr><td>'+s2[0]+'</td><td>'+s2[1]+'</td></tr>');
		}
	}
	w_c(temp_table2);
	p_c();
};
function exchange_list(a){
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	w_c(temp_table1);
	w_c('<tr><td colspan="6"><input type="button" value="交易市場" onClick="parent.act_click(\'exchange\',\'view\')"> <input type="button" value="庫存明細" onClick="parent.act_click(\'exchange\',\'list\')"></td></tr>');
	w_c('<tr><td></td><td>名稱</td><td>庫存數量</td><td>平均成本</td></tr>');
	if (a != "") {
		var s1=a.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2=s1[i].split(",");
			w_c('<tr><td><input type="radio" name="item_id" value="'+s2[0]+'"></td><td>'+s2[3]+'</td><td>'+s2[1]+'</td><td>'+s2[2]+'</td></tr>');
		}
	}
	w_c('<tr><td colspan="6">請輸入數量:<input type="text" name="item_num" size="16"></td></tr>');
	w_c('<tr><td colspan="6"><input type="submit" value="確認賣出"> <input type="button" value="貢獻給公會" onClick="parent.foot_gturn(\'ex\',\'exup\',null,document.f1.item_num.value,document.f1.item_id)"></td></tr>');
	w_c('<input type="hidden" name="f" value="exchange">');
	w_c('<input type="hidden" name="act" value="sale">');
	w_c(temp_table2);
	w_c('</form>');
	p_c();
};
function plus_view(a1,a2,a3){
	//a1:精鍊石,a2:精鍊石數量,a3:結果,a4:裝備數值,a5:裝備列表,a6:魔石
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	w_c(left_table1);
	w_c('<tr><td colspan=3><a href="javascript:parent.act_click(\'plus\',\'view\')" >精鍊石製作</a> <a href="javascript:parent.act_click(\'plus\',\'arm_view\')" >裝備精鍊</a></td></tr>');
	w_c('<tr class="head_td"><td></td><td>名稱</td><td>效果</td></tr>');
	if (a1 != "") {
		vData=a2;
		var s1=a1.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2=s1[i].split(":");
			var temps=srhCount(s2[0]);
			var s3=tmpNum.split(",");
			var temp=plus_buffer_status(s2[2]+":"+s2[3]+":"+s2[4]+":"+s2[5]+":"+s2[6]+":"+s2[7]+":"+s2[8]+":"+s2[9]+":"+s2[10]+":"+s2[11]+":"+s2[12]);
			for(var j=0;j<temps;j++)
			{
				var p_id=i+'_'+j;
				w_c('<tr><td><a href="javascript:parent.plus_add('+s2[0]+',\''+p_id+'\');">添加製作</a></td><td><span id="p_'+p_id+'">'+s2[1]+'*'+s3[j]+'</span></td><td>'+temp+'</td></tr>');
			}
		}
	}
	w_c('<input type="hidden" name="f" value="plus">');
	w_c('<input type="hidden" name="act" value="make">');
	w_c('<tr><td colspan=3>數量:<input type="text" value="1" size="5" maxlength="5" name="item_num"> → <input type="submit" value="開始製作"> <input type="button" value="重置設定" onclick="parent.plus_view(\''+a1+'\',\''+a2+'\',\'\');"></td></tr>');
	w_c('<tr><td colspan=3 class=b1>'+plus_text+'</td></tr>');
	w_c(temp_table2);
	w_c(right_table1);
	w_c('<tr class="head_td"><td>精鍊石1</td><td>精鍊石2</td></tr>');
	w_c('<tr><td><span id="t1">　</span></td><td><span id="t2">　</span></td></tr>');
	w_c('<tr><td colspan=2>合成結果→<span id="t3">'+a3+'</span></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="temp1" value="">');
	w_c('<input type="hidden" name="temp2" value="">');
	w_c('<input type="hidden" name="temp3" value="">');
	w_c('</form>');
	p_c();
};
function plus_view2(a1,a2,a3,a4){
	//a1:精鍊石,a2:精鍊石數量,a3:結果,a4:裝備數值
	p_s_close();
	w_c('<form action="wog_act.php" method="post" target="mission" name=f1 >');
	w_c(left_table1);
	w_c('<tr><td colspan=3><a href="javascript:parent.act_click(\'plus\',\'view\')" >精鍊石製作</a> <a href="javascript:parent.act_click(\'plus\',\'arm_view\')" >裝備精鍊</a></td></tr>');
	w_c('<tr class="head_td"><td></td><td>名稱</td><td>效果</td></tr>');
	if (a1 != "") {
		vData=a2;
		var s1=a1.split(";");
		for (var i = 0; i < s1.length; i++) {
			var s2=s1[i].split(":");
			var temps=srhCount(s2[0]);
			var s3=tmpNum.split(",");
			var temp=plus_buffer_status(s2[2]+":"+s2[3]+":"+s2[4]+":"+s2[5]+":"+s2[6]+":"+s2[7]+":"+s2[8]+":"+s2[9]+":"+s2[10]+":"+s2[11]+":"+s2[12]);
			for(var j=0;j<temps;j++)
			{
				var p_id=i+'_'+j;
				w_c('<tr><td><a href="javascript:parent.plus_add('+s2[0]+',\''+p_id+'\');">添加製作</a></td><td><span id="p_'+p_id+'">'+s2[1]+'*'+s3[j]+'</span></td><td>'+temp+'</td></tr>');
			}
		}
	}
	w_c('<input type="hidden" name="f" value="plus">');
	w_c('<input type="hidden" name="act" value="make_arm">');
	w_c('<tr><td colspan=3><input type="button" value="開始製作" onclick="parent.plus_make();"> <input type="button" value="重置設定" onclick="parent.plus_view(\''+a1+'\',\''+a2+'\',\'\');"></td></tr>');
	w_c('<tr><td colspan=3 class=b1>'+plus_text+'</td></tr>');
	w_c(temp_table2);
	w_c(right_table1);
	w_c('<tr><td colspan="7"><a href="javascript:parent.act_click(\'plus\',\'arm_view\',\'a_id\')" target="mission">武器</a> <a href="javascript:parent.act_click(\'plus\',\'arm_view\',\'d_head_id\')" target="mission">頭部</a> <a href="javascript:parent.act_click(\'plus\',\'arm_view\',\'d_body_id\')" target="mission">身體</a> <a href="javascript:parent.act_click(\'plus\',\'arm_view\',\'d_hand_id\')" target="mission">手部</a> <a href="javascript:parent.act_click(\'plus\',\'arm_view\',\'d_foot_id\')" target="mission">腳部</a></td></tr>');
	w_c('<tr class="head_td"><td></td><td>名稱</td><td>物攻</td><td>魔攻</td><td>物防</td><td>魔防</td><td>能力限制</td></tr>');

	if(a4 != "")
	{
		var s1=a4.split(";");
		for(var i=0;i<s1.length;i++)
		{
			var s2=s1[i].split(",");
			var arm_view_color ="";
			if(s2[20]=="1"){arm_view_color="bgcolor="+nosend;}
			var temp_message=s2[2]+","+s2[7]+","+s2[8]+","+s2[9]+","+s2[10]+","+s2[11]+","+s2[12]+","+s2[13]+","+s2[14]+","+s2[15]+","+s2[22]+","+s2[23]+","+s2[21]+","+s2[24];
			w_c('<tr><td><a href="javascript:parent.plus_add1(\''+s2[1]+'\');">添加製作</a></td><td '+arm_view_color+'><a class="uline" onmouseover=parent.wog_message_box("'+temp_message+'",1,4,null,event.x||event.pageX,event.y||event.pageY); onmouseout=parent.hidebox(\'wog_message_box\'); onclick="parent.arm_show(event.ctrlKey,\''+s2[2]+'\',\''+s2[1]+'\');" target="mission"><span id="a_'+s2[1]+'">'+s2[2]+'</span></a></td><td>'+bf_c(s2[3])+'</td><td>'+bf_c(s2[4])+'</td><td>'+bf_c(s2[5])+'</td><td>'+bf_c(s2[6])+'</td><td>'+arm_need_status(s2[16],s2[17],s2[18],s2[19])+'</td></tr>');
		}
	}
	w_c('<tr><td colspan=7><span id="t1">　</span> + <span id="t2">　</span></td></tr>');
	w_c('<tr><td colspan=7>合成結果→<span id="t3">'+a3+'</span></td></tr>');
	w_c(temp_table2);
	w_c('<input type="hidden" name="temp1" value="">');
	w_c('<input type="hidden" name="temp2" value="">');
	w_c('<input type="hidden" name="temp3" value="">');
	w_c('<input type="hidden" name="set" value="">');
	w_c('</form>');
	p_c();
};
function plus_make()
{
	w_c(temp_table1);
	w_c('<tr class="head_td"><td>使否使用安全精鍊石</td></tr>');
	w_c('<tr><td><input type="radio" name="set" value="1">是 <input type="radio" name="set" value="2" checked>否</td></tr>');
	w_c('<tr><td>使用安全精鍊石，可以確保失敗時精鍊石不會損壞。使用前請確認背包裡面是否有該物品，若無請至<a class="uline" onclick="parent.p_s_close();parent.act_click(\'mall\',\'view\');">商城購買</a></td></tr>');
	w_c('<tr><td colspan="3"><input type="button" value="確認" onclick="parent.plus_send();"> | <input type="button" value="關閉" onClick="parent.p_s_close();" ></td></tr>');
	w_c(temp_table2);
	set_window();
	x=UI.window_w/2-(350/2);
	y=UI.window_h/2;
	p_as(x,y,480);
};
function plus_send()
{
	if(f.f101.set[0].checked==true)
	{
		f.f1.set.value="1";
	}else
	{
		f.f1.set.value="2";
	}
	f.f1.submit();
};
function plus_add(a1,a2)
{
	var s1=f.getElementById("p_"+a2).innerHTML.split("*");
	var t_num=parseInt(s1[1]);
	var temp_a3=f.f1.temp3.value;
	if(t_num<=0){return;}
	s1[2]=s1[0];
	s1[0]="『"+s1[0]+"』";
	if(f.f1.temp1.value!="" && f.f1.act.value=="make")
	{
		f.f1.temp2.value=a1;
		f.f1.temp3.value=a2;
		f.getElementById("t2").innerHTML=s1[0];		
	}
	else
	{
		if(f.f1.act.value=="make_arm")
		{
			f.f1.temp3.value=a2;
		}
		f.f1.temp1.value=a1;
		f.getElementById("t1").innerHTML=s1[0];
	}
	t_num-=1;
	if(temp_a3!="" && temp_a3!=a2)
	{
		var s2=f.getElementById("p_"+temp_a3).innerHTML.split("*");
		var t_num2=parseInt(s2[1]);
		t_num2++;
		f.getElementById("p_"+temp_a3).innerHTML=s2[0]+"*"+t_num2;
	}
	if(temp_a3!=a2)
	{
		f.getElementById("p_"+a2).innerHTML=s1[2]+"*"+t_num;
	}
	f.getElementById("t3").innerHTML="";
};
function plus_add1(a1)
{
	f.f1.temp2.value=a1;
	f.getElementById("t2").innerHTML="『"+f.getElementById("a_"+a1).innerHTML+"』";
	f.getElementById("t3").innerHTML="";
};
function plus_buffer_status(a1)
{
	var temp="";
	var s1=a1.split(":");
	for(var i=0;i < s1.length; i++)
	{
		if(s1[i]!="")
		{
			var s2=s1[i].split(",");
			switch(i)
			{
				case 0:
					temp+=" 物攻:"+s2[0]+"-"+s2[1];
				break;
				case 1:
					temp+=" 魔攻:"+s2[0]+"-"+s2[1];
				break;
				case 2:
					temp+=" 物防:"+s2[0]+"-"+s2[1];
				break;
				case 3:
					temp+=" 魔防:"+s2[0]+"-"+s2[1];
				break;
				case 4:
					temp+=" 力量:"+s2[0]+"-"+s2[1];
				break;
				case 5:
					temp+=" 敏捷:"+s2[0]+"-"+s2[1];
				break;
				case 6:
					temp+=" 智力:"+s2[0]+"-"+s2[1];
				break;
				case 7:
					temp+=" 生命:"+s2[0]+"-"+s2[1];
				break;
				case 8:
					temp+=" 體質:"+s2[0]+"-"+s2[1];
				break;
				case 9:
					temp+=" 魅力:"+s2[0]+"-"+s2[1];
				break;
				case 10:
					temp+=" 信仰:"+s2[0]+"-"+s2[1];
				break;				
			}			
		}
	}
	return temp;
};
