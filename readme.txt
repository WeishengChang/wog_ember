********************************************************************
此軟件為個人興趣所開發,在台灣及大陸地區擁有著作權 
僅供技術交流用途,沒經過同意禁止商業化運營 

The program is developed by personal interests, only for the purpose of campus and technology improvement. The copyright in Taiwan and Great China Area belongs to the original writer. 

All rights reserved, reproduction or commercial use is forbidden unless authorized. 

計算機軟件著作權 
編號：軟著登字第092114號 
登記號：2008SR04935 
著作權人：簡彥名 
********************************************************************

//===================================================== 
// WOG v4.5.0 
// Copyright by ETERNAL<2233.tw@gmail.com> 
// URL : http://www.et99.net
//===================================================== 

釋出版本移除「加值商城相關功能及推廣相關功能」

Demo站台: 
http://www.et99.net/wog4/index.htm

下載點(繁體中文UTF-8版) 
主程式 http://www.et99.net/tool/WOG4.5_Releas_CT.rar
資料庫SQL語句 http://www.et99.net/tool/WOG4.5_sql_CT.rar
圖檔及字型檔 http://cid-5a8a41785cbfa760.office.live.com/self.aspx/WOG%5E_CT/WOG4.5%5E_img%5E_font.rar

下載點(簡體中文UTF-8版) 
主程式 http://www.et99.net/tool/WOG4.5_Releas_CS.rar
資料庫SQL語句 http://www.et99.net/tool/WOG4.5_sql_CS.rar
圖檔及字型檔 http://cid-5a8a41785cbfa760.office.live.com/self.aspx/WOG%5E_CT/WOG4.5%5E_img%5E_font.rar

文件說明：
主程式 WOG4.5_Releas
圖檔及字型檔 WOG4.5_img_font
資料庫SQL語句 WOG4.5_sql_utf-8

環境需求： 
MySQL 版本 5.0以上 
PHP 版本 4.3以上 
空間必須支援GD(http://tw.php.net/manual/tw/function.gd-info.php) 

安裝說明: 
1.解壓縮 WOG4_Releas.rar 
2.請修改./forum_support/config/config.php，並設定MySQL資料庫的帳號密碼
3.請修改./wog_act_config.php 及 ./wog_chat/include/chat_config.php 驗證碼內容，避免被偽造封包竄改數據資料
4.解壓縮 WOG4_img_font.rar
5.解壓縮 WOG4_sql_utf-8.rar，將sql檔匯入資料庫裡 
6.安裝結束，目錄結構如下：
/
../admin
../cache
../class
../css
../ex_img
../forum_support
../js
../language
../mission
../security
../wog_chat

注意事項: 
1.遊戲參數設定在wog_act_config.php 
2.MySQL建議開啟innodb

感謝事項： 
v1一直到v4.5經過許多年，感謝多位網友的支持以及不斷提供意見 
特別感謝涅魂、唐沁、New-TypeChobits、~木林森~、小剎、cku100、dustseal、Adol25、bandcoach、HIUHA 長期以來不斷的支持整理意見 
還有很多沒提到的朋友，謝謝您們的支持 