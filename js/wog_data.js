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
var p_name="",my_birth="",my_age="",m_name="";
var wog_center_html="",wog_online_list_html="",wog_menu_html="",wog_chat_html="";
var tr_bgcolor2="#333333";
var tr_bgcolor3="#00400A";
var nosend="#8D4B0A";
var wog_m_box_color1="#4B689E";
var online_temp_table1='<table width="130" border="0" cellspacing="0" cellpadding="2" align="center">';
var chat_table1='<table border="1" cellspacing="0" cellpadding="0" bgcolor="#2B4686" bordercolor="#2B4686" >';
var menu_table1='<table width="300" border="0" cellspacing="0" cellpadding="0" bgcolor="#2B4686" >';
var mercenary_table1='<table border="0" cellspacing="0" cellpadding="0" bgcolor="#2B4686" >';
var temp_table1='<table width="98%" border="1" cellspacing="0" cellpadding="2" align="center" bordercolor="#4B689E">';
var left_table1='<table width="48%" border="1" cellspacing="0" cellpadding="1" align="left" bordercolor="#4B689E">';
var left_table2='<table width="35%" border="1" cellspacing="0" cellpadding="1" align="left" bordercolor="#4B689E">';
var right_table1='<table width="50%" border="1" cellspacing="0" cellpadding="1" align="right" bordercolor="#4B689E">';
var right_table2='<table width="64%" border="1" cellspacing="0" cellpadding="1" align="right" bordercolor="#4B689E">';
var fight_temp_table1='<table width="600" border="1" cellspacing="0" cellpadding="2" align="center" bordercolor="#4B689E">';
var group_table1='<table width="95%" border="1" cellspacing="0" cellpadding="1" align="center" bordercolor="#4B689E">';
var form_table1="<table><tr><td>",form_table2="</td></tr></table>";
var build_table1='<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center" >';
var page_table1='<table bgcolor='+tr_bgcolor3+' width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >';
var temp_table2='</table>';
var hr='<hr width="98%" size="1" color="#238CBE">';
var temp_country='<select name="act_area" onChange="parent.change_mission(0,this.form);parent.select_area(this.options[this.options.selectedIndex].value,this.form)"><option value="" SELECTED>選擇場所</option><option value="1" >中央大陸</option><option value="2" >魔法王國</option><option value="3" >熱帶雨林</option><option value="4" >末日王城</option></select>';
var vData="";
var vData2="";
var cp_jmmoney=0;
var img="/ex_img/images/wog/img/";
var mimg="/ex_img/images/wog/img/monster/";
var mid_file="";
var g_img="";
var p_sat_name="",d_a_name="",d_body_name="",d_head_name="",d_hand_name="",d_foot_name="",d_item_name="",d_item2_name="",p_group="",d_ch_name="",d_s_ch_name="";
var d_a_name2="",d_body_name2="",d_head_name2="",d_hand_name2="",d_foot_name2="",d_item_name2="",p_group2="",d_ch_name2="",d_s_ch_name2="";
var p_name2="",my_birth2="",my_age2="",etc_group="";
var pet_pname="",pet_mname="",t_team="",p_support_name="",pet_img="",p_support_img="";
var base_str=0,base_smart=0,base_agi=0,base_vit=0,base_life=0,base_au=0,base_be=0,p_lv2=0;
var fightrow=[];
var fight_num=0,shock_num=0,shock_s="",f_count=40,f_hp=15,f_sp=100,f_escape_hp=0,fight_x=0,hotel_hp=-1,hotel_sp=-1,fight_message="",fight_count_num=0,fight_message_skill="",fight_message_title="";
var temp_obj="";
var gf="",gt="";
var temp_p_hp=0,temp_p_hpmax=0,temp_m_hp=0,temp_m_hpmax=0;
var	tmpNum="";
var mercenary_set=0,mercenary_time=100000;
var peo_chk_time=0;
var f,dfoot;
var fight_message="";
var start_time;
var counts=0;
var chat_size=2;
var bg_img=[];
bg_img[1]="";
bg_img[2]="";
bg_img[3]="";
bg_img[4]="";
bg_img[5]="";
bg_img[6]="";
bg_img[7]="";
bg_img[8]="";
bg_img[9]="";
bg_img[10]="";
bg_img[11]="";
bg_img[12]="";
bg_img[13]="";
bg_img[14]="";
bg_img[15]="";
bg_img[16]="";
bg_img[17]="";
bg_img[18]="";
bg_img[19]="";
bg_img[20]="";
bg_img[21]="";
var sec=[
   ['未知區域'],
   ['中央平原',['香茅綠地',1],['綠風草地',2],['白沙海灣',3],['眠獸洞穴',4]],
   ['試鍊洞窟',['燈影隧道',1],['晶礦源地',2],['狹窄地道',3],['地底空洞',4]],
   ['黑暗沼澤',['巨大沼澤',1],['腐植草原',2],['狂火祭壇',3],['狩者墳場',4]], //3
   ['迷霧森林',['微風樹林',1],['密林古城',2],['幻境花園',3],['濃霧曠野',4]],
   ['古代遺跡',['灰雨祭壇',1],['藤蔓古道',2],['地底通道',3],['地下遺跡',4]],
   ['久遠戰場',['古老戰地',1],['接境戰場',2],['征戰遺址',3],['赭紅荒原',4]], //6
   ['王者之路',['試煉之道',1],['邪靈棧橋',2],['中央高塔',3],['熔岩斷層',4]],
   ['幻獸森林',['沉靜叢林',1],['秘密獸道',2],['光輝草原',3],['精靈幻境',4]],
   ['星河異界',['流星之丘',1],['星絲銀河',2],['三角祕境',3],['次元蟲洞',4]], //9
   ['灼熱荒漠',['幻覺沙丘',1],['海市蜃樓',2],['大漠絲路',3],['炎獸沙漠',4]],
   ['無淵洞窟',['妖魔聚落',1],['毒氣沼澤',2],['大地裂痕',3],['牲祭洞窟',4]],
   ['天空之城',['雲端城堡',1],['輝煌都市',2],['天使之廳',3],['封神殿堂',4]], //12
   ['水晶之間',['幻像步道',1],['晶花水池',2],['結晶礦脈',3],['水晶王座',4]],
   ['失落古船',['星空海岸',1],['古船墳地',2],['潮汐洞穴',3],['大幽靈船',4]],
   ['最果之島',['黃金沙灘',1],['猛獸森林',2],['密林湖泊',3],['荒者山峰',4]], //15
   ['冷峰寒地',['寒氣苔原',1],['覆雪大地',2],['暴風雪山',3],['極寒冰峰',4]],
   ['廢棄洞窟',['幽暗礦坑',1],['濕漉岩壁',2],['魔怪階梯',3],['魔實驗場',4]], //17
   ['日沒碉堡',['刺影高牆',1],['螺旋迴廊',2],['機關巨塔',3],['碉堡中樞',4]],
   ['靜止之城',['凝滯地帶',1],['污泥洞穴',2],['回朔空間',3],['片斷時空',4]],
   ['黑曜神廟'],
   ['血之魔域',['十字墓地',1],['惡魔遺蹟',2],['暗紅神殿',3],['焦池煉獄',4]] //21
];
var sp_sec=[];
sp_sec[17]=['廢棄洞窟'];
sp_sec[18]=['日沒碉堡'];
sp_sec[19]=['靜止之城'];
sp_sec[20]=['黑曜神廟'];
var birth=[];
birth[0]="無";
birth[1]="中央大陸";
birth[2]="魔法王國";
birth[3]="熱帶雨林";
birth[4]="末日王城";
var s_status=[];
s_status[0]="--";
s_status[1]="地";
s_status[2]="水";
s_status[3]="火";
s_status[4]="木";
s_status[5]="風";
s_status[6]="毒";
s_status[7]="";
var wp_name=[];
wp_name[0]="無";
wp_name[1]="步兵";
wp_name[2]="槍兵";
wp_name[3]="騎兵";
wp_name[4]="弓兵";
wp_name[5]="衝車";
wp_name[6]="火槍";
wp_name[7]="火砲";
wp_name[8]="工兵";
wp_name[9]="火槍騎";
wp_name[10]="魔龍";
wp_name[11]="巨獸";
wp_name[12]="邪駭兵";
var ex_name=[];
ex_name[0]="無";
ex_name[1]="煤";
ex_name[2]="木材";
ex_name[3]="石塊";
ex_name[4]="石油";
ex_name[5]="黃金";
ex_name[6]="酒";
ex_name[7]="大麥";
ex_name[8]="香菸";
ex_name[9]="鐵";
ex_name[10]="皮毛";
ex_name[11]="絲線";
ex_name[12]="珍珠";
var group_act_type=[];
group_act_type[0]="兵種生產";
group_act_type[1]="出兵攻打";
group_act_type[2]="運送軍資";
group_act_type[3]="修復據點";
group_act_type[4]="偵查情報";
group_act_type[5]="研究開發";
group_act_type[6]="資源交易";
var g_area_type=[];
g_area_type[1]="平原";
g_area_type[2]="山";
g_area_type[3]="林";
g_area_type[4]="高原";
g_area_type[5]="川";
g_area_type[6]="谷地";
var g_weather=[];
g_weather[1]="晴";
g_weather[2]="雨";
g_weather[3]="霧";
g_weather[4]="雷";
g_weather[5]="雪";
var g_item=[];
g_item[0]="尚未設定";
g_item[1]="野戰修復";
g_item[2]="偽報";
g_item[3]="停戰協議A";
g_item[4]="緊急徵召A";
g_item[5]="緊急徵召B";
g_item[6]="緊急徵召C";
g_item[7]="停戰協議B";
g_item[8]="停戰協議C";
var g_item_text=[];
g_item_text[0]="";
g_item_text[1]="進行公會戰鬥時，當據點防禦力為0，自動修復防禦力30%";
g_item_text[2]="進行公會戰鬥時，隨機使敵方任意一種兵種無法戰鬥，也無法對該兵種進行攻擊";
g_item_text[3]="進行公會戰鬥時，有50%機率使敵方退兵";
g_item_text[4]="進行公會戰鬥時，隨機增加任意一種兵種兵力(增加數3000~5000)";
g_item_text[5]="進行公會戰鬥時，隨機增加任意一種兵種兵力(增加數6000~9000)";
g_item_text[6]="進行公會戰鬥時，隨機增加任意一種兵種兵力(增加數10000~13000)";
g_item_text[7]="進行公會戰鬥時，有60%機率使敵方退兵";
g_item_text[8]="進行公會戰鬥時，有70%機率使敵方退兵";
var honor_name=[];
honor_name[1]="大陸勳章";
honor_name[2]="王國勳章";
honor_name[3]="雨林勳章";
honor_name[4]="王城勳章";
honor_name[5]="白銀勳章";
var build_set=[
	[[0,'步兵'],[1,'槍兵'],[2,'騎兵'],[3,'弓兵'],[4,'衝車'],[5,'火槍'],[6,'火砲'],[7,'工兵'],[8,'火槍騎'],[9,'魔龍'],[10,'巨獸'],[11,'邪駭兵']],
	[[12,'防禦強化'],[13,'資源保護'],[27,'市場'],[28,'行軍技巧'],[29,'要塞中心']],	
	[[14,'偵查'],[15,'炎火陷阱'],	[16,'洪水陷阱'],	[17,'石落陷阱'],	[18,'土穴陷阱']],
	[[19,'雁形陣'],[20,'魚鱗陣'],	[21,'鶴翼陣'],[22,'鋒矢陣'],[23,'衝軛陣'],[24,'長蛇陣'],[25,'車懸陣'],[26,'方圓陣']],
];
var job_s=[];
job_s[0]="";
job_s[1]="刪除成功";
job_s[2]="轉職成功";
job_s[3]="設定成功";
job_s[4]="感謝你使用本銀行";
job_s[5]="休息了一晚後,HP回復SP飽滿<br>(住宿會收取住宿費，住宿費不足會扣經驗值1/5)";
job_s[6]="購買完成";
job_s[7]="訊息成功發出";
job_s[8]="";
job_s[9]="開始拍賣";
job_s[10]="建立成功";
job_s[11]="變更成功";
job_s[12]="兌換成功";
job_s[13]="學習成功";
job_s[14]="手續完成,請等待核可";
job_s[15]="手續完成";
job_s[16]="過度疲勞寵物死亡";
job_s[17]="親密度太低寵物逃跑";
job_s[18]="放生成功";
job_s[19]="";
job_s[20]="任務接受成功";
job_s[21]="恭喜完成任務";
job_s[22]="取消任務成功";
job_s[23]="裝備完成!!";
job_s[24]="轉移完成";
var shop_type_lv=[['<a class="uline" onClick="parent.th_submit(document.f2,1,0)">LV1</a> <a class="uline" onClick="parent.th_submit(document.f2,2,0)">LV2</a> <a class="uline" onClick="parent.th_submit(document.f2,3,0)">LV3</a> <a class="uline" onClick="parent.th_submit(document.f2,4,0)">LV4</a> <a class="uline" onClick="parent.th_submit(document.f2,5,0)">LV5</a> <a class="uline" onClick="parent.th_submit(document.f2,6,0)">LV6</a> <a class="uline" onClick="parent.th_submit(document.f2,7,0)">LV7</a>']
,['<a class="uline" onClick="parent.th_submit(document.f2,1,1)">LV1</a> <a class="uline" onClick="parent.th_submit(document.f2,2,1)">LV2</a> <a class="uline" onClick="parent.th_submit(document.f2,3,1)">LV3</a> <a class="uline" onClick="parent.th_submit(document.f2,4,1)">LV4</a> <a class="uline" onClick="parent.th_submit(document.f2,5,1)">LV5</a> <a class="uline" onClick="parent.th_submit(document.f2,6,1)">LV6</a>']
,['<a class="uline" onClick="parent.th_submit(document.f2,1,2)">LV1</a> <a class="uline" onClick="parent.th_submit(document.f2,2,2)">LV2</a> <a class="uline" onClick="parent.th_submit(document.f2,3,2)">LV3</a> <a class="uline" onClick="parent.th_submit(document.f2,4,2)">LV4</a> <a class="uline" onClick="parent.th_submit(document.f2,5,2)">LV5</a> <a class="uline" onClick="parent.th_submit(document.f2,6,2)">LV6</a>']
,['<a class="uline" onClick="parent.th_submit(document.f2,1,3)">LV1</a> <a class="uline" onClick="parent.th_submit(document.f2,2,3)">LV2</a> <a class="uline" onClick="parent.th_submit(document.f2,3,3)">LV3</a> <a class="uline" onClick="parent.th_submit(document.f2,4,3)">LV4</a> <a class="uline" onClick="parent.th_submit(document.f2,5,3)">LV5</a> <a class="uline" onClick="parent.th_submit(document.f2,6,3)">LV6</a>']
,['<a class="uline" onClick="parent.th_submit(document.f2,1,4)">LV1</a> <a class="uline" onClick="parent.th_submit(document.f2,2,4)">LV2</a> <a class="uline" onClick="parent.th_submit(document.f2,3,4)">LV3</a> <a class="uline" onClick="parent.th_submit(document.f2,4,4)">LV4</a> <a class="uline" onClick="parent.th_submit(document.f2,5,4)">LV5</a> <a class="uline" onClick="parent.th_submit(document.f2,6,4)">LV6</a>']
];
var king_menu='<tr class=head_td><td><a href=wog_etc.php?f=king&type=6 target="mission">等級</a> <a href=wog_etc.php?f=king&type=1 target="mission">勝場</a> <a href=wog_etc.php?f=king&type=2 target="mission">HP</a> <a href=wog_etc.php?f=king&type=3 target="mission">物攻</a> <a href=wog_etc.php?f=king&type=4 target="mission">魔攻</a> <a href=wog_etc.php?f=king&type=5 target="mission">敏捷</a> <a href=wog_etc.php?f=king&type=8 target="mission">魅力</a> <a href=wog_etc.php?f=king&type=7 target="mission">好野人</a></tr>';
var bank_depot_menu='<tr><td><a href=javascript:parent.act_click("bank","view") target="mission">銀行</a> <a href="javascript:parent.act_click(\'arm\',\'depot_list\',\'0\')" target="mission">武器</a> <a href="javascript:parent.act_click(\'arm\',\'depot_list\',\'1\')" target="mission">頭部</a> <a href="javascript:parent.act_click(\'arm\',\'depot_list\',\'2\')" target="mission">身體</a> <a href="javascript:parent.act_click(\'arm\',\'depot_list\',\'3\')" target="mission">手部</a> <a href="javascript:parent.act_click(\'arm\',\'depot_list\',\'4\')" target="mission">腳部</a> <a href="javascript:parent.act_click(\'arm\',\'depot_list\',\'5\')" target="mission">道具</a> <a href="javascript:parent.act_click(\'arm\',\'depot_list\',\'7\')" target="mission">魔石</a> <a href="javascript:parent.act_click(\'arm\',\'depot_list\',\'8\')" target="mission">勳章</a> <a href="javascript:parent.act_click(\'arm\',\'depot_list\',\'9\')" target="mission">鑰匙</a> <a href="javascript:parent.act_click(\'arm\',\'depot_list\',\'10\')" target="mission">精鍊石</a></tr>';
var message_menu='<a href="javascript:parent.act_click(\'message\',\'list\')" target="mission">收信</a> <a href="javascript:parent.message()">發信</a> <a href="javascript:parent.act_click(\'friend\',\'list\')">好友</a> <a href="javascript:parent.act_click(\'message\',\'item_list\')">取物</a>';
var mission_store='<tr><td colspan="2"><a class="uline" onClick="parent.act_click(\'mission\',\'list\')">【任務介紹所】</a> : <a class="uline" onClick="parent.act_click(\'mission\',\'book\')">【查看未完成任務】<a> : <a class="uline" onClick="parent.act_click(\'mission\',\'paper\')">【查看已完成任務】</a></td></tr>';
var item_store_menu='<tr class="head_td"><td>裝備道具商店 》 <a class="uline" onClick="parent.shop_home_view(\'\',\'\',0,\'\',\'\')">【武器】</a> <a class="uline" onClick="parent.shop_home_view(\'\',\'\',1,\'\',\'\')">【頭部】</a> <a class="uline" onClick="parent.shop_home_view(\'\',\'\',2,\'\',\'\')">【身體】</a> <a class="uline" onClick="parent.shop_home_view(\'\',\'\',3,\'\',\'\')">【手部】</a> <a class="uline" onClick="parent.shop_home_view(\'\',\'\',4,\'\',\'\')">【腳部】</a> <a class="uline" onClick="parent.th_submit(document.f2,1,\'5\')">【道具屋】</a> <a class="uline" onClick="parent.honor_view(null)">【勳章所】</a></td></tr>';
var group_menu='<tr><td><input type="button" value="世界地圖" onClick="parent.act_gclick(\'main\',\'view\')"> '
+'<input type="button" value="會員狀態" onClick="parent.act_gclick(\'member\',\'p_list\')"> '
+'<input type="button" value="據點狀態" onClick="parent.act_gclick(\'area_main\',\'area_main\')"> '
+'<input type="button" value="佈告欄" onClick="parent.act_gclick(\'book\',\'book\')"> '
+'<input type="button" value="認領會員" onClick="parent.act_gclick(\'get_member\',\'get_member\')"> '
+'<input type="button" value="榮譽設定" onClick="parent.act_gclick(\'point\',\'set_point\')"></td></tr>'
+'<tr><td>'
+'<input type="button" value="軍備" onClick="parent.act_gclick(\'wp\',\'wp\')"> '
+'<input type="button" value="造兵" onClick="parent.act_gclick(\'ex\',\'ex\')"> '
+'<input type="button" value="研究" onClick="parent.act_gclick(\'build\',\'build_list\')"> '
+'<input type="button" value="戰報" onClick="parent.act_gclick(\'news\',\'news\')"> '
+'<input type="button" value="交易" onClick="parent.act_gclick(\'market\',\'market\')"> '
+'<input type="button" value="任務" onClick="parent.act_gclick(\'mission\',\'list\')"> '
+'<input type="button" value="倉庫" onClick="parent.act_gclick(\'depot\',\'depot_list\')"> '
+'<input type="button" value="作業" onClick="parent.act_gclick(\'job\',\'job_list\')">'
+'</td></tr>';
var group_area_menu='<tr><td><input type="button" value="'+birth[1]+'" onClick="parent.act_gclick(\'main\',\'area\',1)"> <input type="button" value="'+birth[2]+'" onClick="parent.act_gclick(\'main\',\'area\',2)"> <input type="button" value="'+birth[3]+'" onClick="parent.act_gclick(\'main\',\'area\',3)"> <input type="button" value="'+birth[4]+'" onClick="parent.act_gclick(\'main\',\'area\',4)"></td></tr>';
var honor_menu='<tr><td>類別 : <a href="javascript:parent.act_click(\'honor\',\'list\',\'mercenary\')">傭兵</a> <a href="javascript:parent.act_click(\'honor\',\'list\',\'arm_1\')">武器</a> <a href="javascript:parent.act_click(\'honor\',\'list\',\'arm_2\')">頭部</a> <a href="javascript:parent.act_click(\'honor\',\'list\',\'arm_3\')">身體</a> <a href="javascript:parent.act_click(\'honor\',\'list\',\'arm_4\')">手部</a> <a href="javascript:parent.act_click(\'honor\',\'list\',\'arm_5\')">腳部</a> <a href="javascript:parent.act_click(\'honor\',\'list\',\'item\')">道具</a> <a href="javascript:parent.act_click(\'honor\',\'list\',\'key\')">鑰匙</a></td></tr>';
var job_skill_menu='<tr><td><a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'6\')">戰士</a> <a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'7\')">術士</a> <a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'8\')">盜賊</a> <a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'9\')">樂手</a></td></tr>';
job_skill_menu+='<tr><td><a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'10\')">狂戰士</a> <a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'11\')">騎士</a> <a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'12\')">魔導師</a> <a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'13\')">風水師</a> <a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'14\')">刺客</a> <a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'15\')">武術家</a> <a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'16\')">舞者</a> <a href="javascript:parent.act_click(\'skill\',\'skill_list\',\'17\')">獸師</a></td></tr>';
var job_skill_menu2='<tr><td><a href="javascript:parent.act_click(\'skill\',\'rview\',\'6\')">戰士</a> <a href="javascript:parent.act_click(\'skill\',\'rview\',\'7\')">術士</a> <a href="javascript:parent.act_click(\'skill\',\'rview\',\'8\')">盜賊</a> <a href="javascript:parent.act_click(\'skill\',\'rview\',\'9\')">樂手</a></td></tr>';
job_skill_menu2+='<tr><td><a href="javascript:parent.act_click(\'skill\',\'rview\',\'10\')">狂戰士</a> <a href="javascript:parent.act_click(\'skill\',\'rview\',\'11\')">騎士</a> <a href="javascript:parent.act_click(\'skill\',\'rview\',\'12\')">魔導師</a> <a href="javascript:parent.act_click(\'skill\',\'rview\',\'13\')">風水師</a> <a href="javascript:parent.act_click(\'skill\',\'rview\',\'14\')">刺客</a> <a href="javascript:parent.act_click(\'skill\',\'rview\',\'15\')">武術家</a> <a href="javascript:parent.act_click(\'skill\',\'rview\',\'16\')">舞者</a> <a href="javascript:parent.act_click(\'skill\',\'rview\',\'17\')">獸師</a></td></tr>';
var g_area_type_title='<u onmouseover=parent.wog_message_box("平原:騎兵+10%&n山:弓兵+10%,火砲+10%,騎兵-10%&n林:槍兵+10%,弓兵-10%&n高原:火槍+10%,火槍騎+10%&n川:火槍-10%,火槍騎-10%,火砲-10%&n谷地:步兵+10%,工兵-10%",0,2); onmouseout=parent.hidebox(\'wog_message_box\');>地形</u>';
var g_weather_title='<u onmouseover=parent.wog_message_box("晴:火炮+10%,炎火陷阱效果+10%&n雨:火槍-10%,火炮-10%,洪水陷阱效果+10%&n霧:弓兵-10%,土穴陷阱效果+10%&n雷:槍兵-10%,石落陷阱效果+10%&n雪:騎兵-10%,火槍騎-10%",0,2); onmouseout=parent.hidebox(\'wog_message_box\');>氣候</u>';
var plus_text="<ol><li>必須使用『相同等級』精鍊石才能進行精鍊石製作，例：白精鍊石LV1+白精鍊石LV1=白精鍊石LV2</li><li>使用『相同等級』、『相同類別』精鍊石進行製作，可做出同類並提高1級之精鍊石，例：白精鍊石LV1+白精鍊石LV1=白精鍊石LV2</li><li>使用『相同等級』、『不同類別』精鍊石進行製作，可做出『隨機它類』並降1級之精鍊石，例：白精鍊石LV2+黑精鍊石LV2=紅精鍊石LV1</li><li>裝備精鍊必須使用高於裝備1級之精鍊石，例：『木刀+1』+『藍精鍊石LV2』=木刀+2</li><li>所有裝備都有精鍊次數限制</li><li>裝備精鍊級數越高，失敗機率相對提高</li></ol>";
var count_fight;
var buff1="<font color=green>+";
var buff2="</font>";
var debuff1="<font color=red>-";
var debuff2="</font>";
var noset=" <font color=red>";
var UI = {
	set_frame: 2,
	set_rows: "0,39,*,0,0,150",
	set_target:null,
	set_center_w:"91%",
	window_w:0,
	window_h:0,
	mouse_x:0,
	mouse_y:0
};
var Map={
	my_x:0,
	my_y:0,
	group_x:0,
	group_y:0
};
var job_list ={};
var job_work ={};