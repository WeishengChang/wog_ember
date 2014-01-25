<?php
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

//-------------group---------
$lang['wog_act_group_nogroup'] = '沒有所屬公會';
$lang['wog_act_group_nolyadmin'] = '只有會長才能設定';
$lang['wog_act_group_nosel'] = '沒有選擇對象';
$lang['wog_act_group_nolv'] = '沒有管理權限';
$lang['wog_act_group_cancel'] = '入會申請已經被取消了';
$lang['wog_act_group_notset'] = '不能核准別人公會的會員';
$lang['wog_act_group_havgroup'] = '已經有所屬公會';
$lang['wog_act_group_ful'] = '公會已經額滿,無法入會';
$lang['wog_act_group_post'] = '已經提出申請';
$lang['wog_act_group_ad'] = '(會長)';
$lang['wog_act_group_ad2'] = '(副會長)';
$lang['wog_act_group_noname'] = '請輸入名稱';
$lang['wog_act_group_used'] = '此名稱已經被使用了';
$lang['wog_act_group_notcreat'] = '已經有所屬公會,無法創立公會';
$lang['wog_act_group_long'] = '內容太長';
$lang['wog_act_group_error1'] = '沒有選擇兵種';
$lang['wog_act_group_error2'] = '公會兵種數量不足';
$lang['wog_act_group_error3'] = '庫存數量不足';
$lang['wog_act_group_error4'] = '執行工作不能超過 %s 項';
$lang['wog_act_group_error5'] = '執行軍備工作中，不能執行其他工作';
$lang['wog_act_group_error6'] = '公會已滿，不能創立新公會';
$lang['wog_act_group_error7'] = '執行其他工作中，不能執行作戰';
$lang['wog_act_group_error8'] = '一次最多只能攜帶 %s 兵種';
$lang['wog_act_group_error9'] = '不能攻打自己公會';
$lang['wog_act_group_error10'] = '出兵數量不能超過 %s';
$lang['wog_act_group_error11'] = '沒有選擇物品';
$lang['wog_act_group_error12'] = '不能小於0';
$lang['wog_act_group_error13'] = '榮譽不足';
$lang['wog_act_group_error14'] = '沒選擇兵種';
$lang['wog_act_group_error15'] = '沒有選擇升級項目';
$lang['wog_act_group_error16'] = '已有執行其他研究項目';
$lang['wog_act_group_error17'] = '已有其他執行中工作，無法進行研究';
$lang['wog_act_group_error18'] = '出擊兵數超過上限，在「兵舍」提升兵種等級可提高上限';
$lang['wog_act_group_error19'] = '已有其他會員進行研究';
$lang['wog_act_group_error20'] = '需求條件不符';
$lang['wog_act_group_error21'] = '不能拿取榮譽0物品';
$lang['wog_act_group_error22'] = '防禦值為0不能出兵';
$lang['wog_act_group_error23'] = '提供資源數量不能為0';
$lang['wog_act_group_error24'] = '需求資源數量不能為0';
$lang['wog_act_group_error25'] = '提供資源數量不能大於庫存量';
$lang['wog_act_group_error26'] = '沒有選擇交易項目';
$lang['wog_act_group_error27'] = '交易數量已達上限請提高市場等級';
$lang['wog_act_group_error28'] = '不能對自己進行交易';
$lang['wog_act_group_error29'] = '職務上限為 %s';
$lang['wog_act_group_error30'] = '職務名稱沒有填寫';
$lang['wog_act_group_error31'] = '權限不足';
$lang['wog_act_group_error32'] = '不能變更自己職務';
$lang['wog_act_group_error33'] = '請選擇使用物品，及欲加速的工作';
$lang['wog_act_group_error34'] = '需求資源不足';
$lang['wog_act_group_error35'] = '請輸入座標';
$lang['wog_act_group_error36'] = '無法操作';
$lang['wog_act_group_error37'] = '無此公會';
$lang['wog_act_group_msg1'] = '%s 襲擊 %s 失敗，損失兵力 %s 。對方損失兵力 %s';
$lang['wog_act_group_msg2'] = '遭受 %s 襲擊，損失 %s';
$lang['wog_act_group_msg3'] = '%s 襲擊 %s 成功，獲得資源 %s ，損失兵力 %s';
$lang['wog_act_group_msg4'] = '遭受 %s 襲擊，防守成功，城耐久度 %s，損失兵力 %s';
$lang['wog_act_group_msg5'] = '<font color="red">公會 [%s] 攻破 [%s] 成功!!</font>';
$lang['wog_act_group_msg6'] = '<font color="red">公會 [%s] 襲擊 [%s] 失敗!!</font>';
$lang['wog_act_group_msg7'] = '<font color="red">公會 [%s]%s 出兵攻打 [%s]</font>';
$lang['wog_act_group_msg8'] = '%s 出兵攻打 %s，預計抵達時間:%s';
$lang['wog_act_group_msg9'] = '會員 %s 帶領 %s 攻打 %s';
$lang['wog_act_group_msg10'] = '受到冒險者公會保護，無法進行攻擊及被攻擊';
$lang['wog_act_group_msg11'] = ' 大喊:「%s」';
$lang['wog_act_group_msg12'] = 'NPC公會已被消滅';
$lang['wog_act_group_msg13'] = '<font color="red">%s 在 %s 出沒作亂，冒險者公會請求協助</font>';
$lang['wog_act_group_msg14'] = '%s 公會已被消滅，兵隊自動回城 -';
$lang['wog_act_group_msg15'] = '會員 %s 偵查  %s <a href=javascript:parent.act_gclick("book","check_ex","%s")>回報</a>';
$lang['wog_act_group_msg16'] = '<br><a href=javascript:parent.act_gclick("book","fight_book","%s")>作戰詳情</a>';
$lang['wog_act_group_msg17'] = '會員 %s 在交易市場提供  %s 需求 %s';
$lang['wog_act_group_msg18'] = '會員 %s 完成任務『 %s』，獲得 %s';
$lang['wog_act_group_msg19'] = '『%s』 公會使用停戰協議成功，攻擊方兵隊自動回城';
$lang['wog_act_group_msg20'] = '『%s』 公會使用停戰協議失敗';
$lang['wog_act_group_msg21'] = '%s 運輸 %s，預計抵達時間:%s';
$lang['wog_act_group_msg22'] = '會員 %s 運輸 %s 至 %s';
$lang['wog_act_group_msg23'] = ' 大喊:「很久沒交保護費」 下令攻打 公會『%s』';
$lang['wog_act_group_msg24'] = ' 大喊:「沒奉上美女給ET，天地不容」 懲罰 公會『%s』';
$lang['wog_act_group_msg25'] = ' 怒道:「沒有敬老尊賢，人神共憤」 討閥 公會『%s』';
$lang['wog_act_group_msg26'] = ' 「一直有句話要說，你是我 今生今世的守候」 出兵關愛 公會『%s』';
$lang['wog_act_group_msg27'] = ' 「為你寫詩，為你靜止，為你做不可能的事」 出兵探望 公會『%s』';
$lang['wog_act_group_msg28'] = ' 「不管你醒著，睡著，夢著，我在你左右」 出兵陪伴 公會『%s』';
$lang['wog_act_group_msg29'] = ' 公會『%s』玩弄少女心靈，出兵給予懲罰';
$lang['wog_act_group_msg30'] = ' 大喊:「奉天承運，ET詔曰，公會『%s』太久沒上線，出兵給予制裁」';
$lang['wog_act_group_msg31'] = ' 大喊:「讓我代替月亮懲罰你」 出兵懲罰 公會『%s』';
$lang['wog_act_group_msg32'] = ' 公會『%s』散播流感病毒，出兵給予制裁';
$lang['wog_act_group_msg33'] = '運輸 %s 至 %s';
$lang['wog_act_group_msg34'] = '據點已被搶走，兵隊自動回城 -';
?>