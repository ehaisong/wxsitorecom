-- MySQL database dump
-- Created by MySQLReback. Power by Hintay.
--
-- 主机: 
-- 生成日期: 2016 年  06 月 13 日 13:57
-- MySQL版本: 5.6.29
-- PHP 版本: 5.4.27

--
-- 数据库: `wx_vifnn`
--
-- -------------------------------------------------------


 /* 创建表结构 `vifnn_aaactivity` */
 DROP TABLE IF EXISTS `vifnn_aaactivity`;/* MySQLReback Separation */ CREATE TABLE `vifnn_aaactivity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `joinnum` int(11) NOT NULL COMMENT '已人数',
  `wecha_id` varchar(100) NOT NULL DEFAULT '' COMMENT '发起人ID',
  `usename` varchar(100) NOT NULL DEFAULT '' COMMENT '发起人名字',
  `tel` char(20) NOT NULL DEFAULT '' COMMENT '发起者TEl',
  `token` varchar(30) NOT NULL DEFAULT '',
  `keyword` varchar(10) NOT NULL,
  `top_pic` varchar(200) NOT NULL DEFAULT '' COMMENT '活动顶部图片',
  `reply_pic` varchar(200) NOT NULL DEFAULT '' COMMENT '回复图片',
  `share_pic` varchar(200) NOT NULL DEFAULT '' COMMENT '分享小图',
  `title` varchar(60) NOT NULL COMMENT '活动名称',
  `statdate` int(11) NOT NULL COMMENT '活动开始时间',
  `enddate` int(11) NOT NULL COMMENT '活动结束时间',
  `intro` varchar(200) NOT NULL DEFAULT '' COMMENT '活动说明',
  `info` varchar(255) NOT NULL DEFAULT '' COMMENT '活动规则',
  `txtaudit` varchar(250) NOT NULL DEFAULT '' COMMENT '提交说明',
  `type` tinyint(1) NOT NULL,
  `score` char(100) NOT NULL DEFAULT '' COMMENT '扣除积分',
  `feiyong` char(100) NOT NULL DEFAULT '' COMMENT 'AA费用',
  `backscore` char(100) NOT NULL DEFAULT '' COMMENT '参与活动赠送积分',
  `backfeiyong` char(100) NOT NULL DEFAULT '' COMMENT '商家赞助多少',
  `share_score` int(11) NOT NULL COMMENT '分享每拉人参与进来获得积分',
  `share_feiyong` int(11) NOT NULL COMMENT '分享每拉人参与进来获得金额',
  `minnums` int(11) NOT NULL COMMENT '最小报名人数',
  `maxnums` int(11) NOT NULL COMMENT '最大参与人数',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `zjpic` varchar(150) NOT NULL DEFAULT '' COMMENT '活动图片',
  `daynums` mediumint(4) NOT NULL DEFAULT '0' COMMENT '活动时长',
  `is_user` int(11) NOT NULL DEFAULT '0' COMMENT '是否是用户发起的',
  `is_audit` int(11) NOT NULL DEFAULT '0' COMMENT '是否审通过',
  `is_sub` int(11) NOT NULL DEFAULT '0' COMMENT '是否关注',
  `is_reg` int(11) NOT NULL DEFAULT '0' COMMENT '是否注册',
  `is_share_score` int(11) NOT NULL DEFAULT '0' COMMENT '是否开启拉人送积分',
  `is_open` int(11) NOT NULL DEFAULT '0',
  `is_score` int(11) NOT NULL DEFAULT '0' COMMENT '是否开启扣除积分',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_aaactivity_record` */
 DROP TABLE IF EXISTS `vifnn_aaactivity_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_aaactivity_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL COMMENT '参与活动ID',
  `wecha_id` varchar(100) NOT NULL DEFAULT '' COMMENT '报名者ID',
  `token` varchar(30) NOT NULL DEFAULT '',
  `score` char(100) NOT NULL DEFAULT '' COMMENT '扣除积分',
  `feiyong` char(100) NOT NULL DEFAULT '',
  `share_key` varchar(100) NOT NULL DEFAULT '' COMMENT '分享KEY',
  `add_time` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_aaactivity_user` */
 DROP TABLE IF EXISTS `vifnn_aaactivity_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_aaactivity_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL COMMENT '参与活动ID',
  `wecha_id` varchar(100) NOT NULL DEFAULT '' COMMENT '报名者ID',
  `token` varchar(30) NOT NULL DEFAULT '',
  `score` char(100) NOT NULL DEFAULT '' COMMENT '扣除积分',
  `feiyong` char(100) NOT NULL DEFAULT '',
  `share_key` varchar(100) NOT NULL DEFAULT '' COMMENT '分享KEY',
  `usename` varchar(100) NOT NULL DEFAULT '',
  `tel` char(20) NOT NULL DEFAULT '',
  `sex` int(11) NOT NULL,
  `note` varchar(100) NOT NULL DEFAULT '',
  `add_time` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_access` */
 DROP TABLE IF EXISTS `vifnn_access`;/* MySQLReback Separation */ CREATE TABLE `vifnn_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_access_count` */
 DROP TABLE IF EXISTS `vifnn_access_count`;/* MySQLReback Separation */ CREATE TABLE `vifnn_access_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL DEFAULT 'alltoken',
  `module` varchar(50) NOT NULL,
  `controller` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '1',
  `update_time` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `module` (`module`) USING BTREE,
  KEY `controller` (`controller`) USING BTREE,
  KEY `action` (`action`) USING BTREE,
  KEY `count` (`count`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1315 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('1287','ywxgfh1465574691','user','livingcircle','index','1','1465614422','1465614422'),('1251','admin','user','upyun','upload','2','1465574790','1465574763'),('1252','alltoken','user','upyun','upload','12','1465613459','1465574763'),('1253','ywxgfh1465574691','user','index','upsave','3','1465613593','1465574825'),('12','alltoken','user','attachment','index','1','1444572264','1444572264'),('1005','alltoken','user','index','upsave','1','1460366239','1460366239'),('22','alltoken','user','collectword','index','4','1444572366','1444572303'),('24','alltoken','user','frontpage','index','5','1444572374','1444572308'),('26','alltoken','user','cointree','index','2','1444572317','1444572311'),('28','alltoken','user','lottery','index','1','1444572315','1444572315'),('30','alltoken','user','cointree','add_action','1','1444572319','1444572319'),('827','alltoken','user','areply','index','1','1459066224','1459066224'),('34','alltoken','user','frontpage','listconfigure','2','1444572370','1444572350'),('36','alltoken','user','frontpage','addconfigure','1','1444572353','1444572353'),('38','alltoken','user','test','index','1','1444572377','1444572377'),('40','alltoken','user','test','add','1','1444572380','1444572380'),('42','alltoken','user','card','index','3','1444577118','1444572398'),('1058','alltoken','user','other','index','1','1462929934','1462929934'),('1060','alltoken','user','api','index','1','1462929938','1462929938'),('1062','alltoken','user','api','add','1','1462929943','1462929943'),('1064','alltoken','user','auth','index','1','1462929968','1462929968'),('1066','alltoken','user','conditionalmenu','index','1','1462930010','1462930010'),('56','alltoken','user','yjgz','index','4','1444574556','1444573239'),('58','alltoken','user','daohang','index','7','1444576906','1444573262'),('1231','alltoken','user','taobao','index','1','1465567592','1465567592'),('67','alltoken','user','map','pano','1','1444574462','1444574462'),('1079','alltoken','user','flash','index','1','1462932830','1462932830'),('1080','alltoken','user','company','index','1','1462932875','1462932875'),('1082','alltoken','user','index','frame','2','1463379585','1463366618'),('1084','alltoken','user','function','welcome','2','1463379585','1463366618'),('1158','alltoken','user','api','index','1','1464261508','1464261508'),('1160','alltoken','user','api','add','1','1464261528','1464261528'),('1162','alltoken','user','quick','cpshortlink','1','1464269313','1464269313'),('678','alltoken','user','live','add','2','1458796703','1458794792'),('85','alltoken','user','templatemsg','add','1','1444574629','1444574629'),('91','alltoken','user','assistente','add','1','1444574783','1444574783'),('93','alltoken','user','service','service_fans','1','1444574798','1444574798'),('97','alltoken','user','index','add','1','1444574865','1444574865'),('99','alltoken','user','shake','index','1','1444574889','1444574889'),('101','alltoken','user','scene','index','1','1444574892','1444574892'),('1278','alltoken','user','store','setting','3','1465614392','1465613705'),('1279','ywxgfh1465574691','user','classify','choosetpl','2','1465614273','1465613718'),('1283','ywxgfh1465574691','user','store','catadd','1','1465614354','1465614354'),('1286','alltoken','user','taobao','index','1','1465614400','1465614400'),('111','alltoken','user','catemenu','index','1','1444576955','1444576955'),('113','alltoken','user','home','plugmenu','1','1444576956','1444576956'),('115','alltoken','user','vifnn','index','1','1444576958','1444576958'),('119','alltoken','user','seniorscene','index','1','1444576991','1444576991'),('121','alltoken','user','seniorscene','add','1','1444576994','1444576994'),('1214','alltoken','user','unitary','index','1','1465224875','1465224875'),('125','alltoken','user','link','commondetail','1','1444577005','1444577005'),('1192','alltoken','user','function','welcome','1','1464661916','1464661916'),('131','alltoken','user','wall','index','2','1444577060','1444577058'),('135','alltoken','user','greeting_card','index','1','1444577078','1444577078'),('137','alltoken','user','person_card','index','1','1444577101','1444577101'),('139','alltoken','user','person_card','design','1','1444577113','1444577113'),('143','alltoken','user','invite','add','3','1444577137','1444577127'),('145','alltoken','user','research','index','1','1444577128','1444577128'),('728','alltoken','user','host','add','1','1458797004','1458797004'),('1188','alltoken','user','message','index','1','1464272459','1464272459'),('1164','alltoken','user','index','info','1','1464270505','1464270505'),('659','alltoken','user','quick','index','1','1458789571','1458789571'),('1196','alltoken','user','text','index','1','1464861314','1464861314'),('817','alltoken','user','api','add','1','1458990561','1458990561'),('1244','alltoken','user','company','index','12','1465613556','1465574424'),('171','alltoken','user','attachment','index','2','1444632083','1444632072'),('173','alltoken','user','attachment','delete','1','1444632078','1444632078'),('1224','alltoken','user','micrstore','withdraw','1','1465225435','1465225435'),('1230','alltoken','user','alipay_config','index','1','1465567576','1465567576');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('181','alltoken','user','index','add','7','1444657216','1444636005');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('183','alltoken','user','index','insert','2','1444656821','1444636017'),('1306','alltoken','user','store','orders','1','1465705956','1465705956'),('625','alltoken','user','member_card','custom','1','1444998311','1444998311'),('624','alltoken','user','helping','set','2','1444998052','1444997888'),('622','alltoken','user','helping','index','2','1444997900','1444997885'),('190','alltoken','user','frontpage','index','2','1444656258','1444638570'),('192','alltoken','user','guajiang','index','1','1444638574','1444638574'),('194','alltoken','user','goldenegg','index','1','1444638576','1444638576'),('196','alltoken','user','hongbao','index','1','1444638579','1444638579'),('199','alltoken','user','daohang','index','2','1444660800','1444638586'),('831','alltoken','user','api','index','1','1459066240','1459066240'),('206','alltoken','user','assistente','add','1','1444639240','1444639240'),('610','alltoken','user','alipay','recharge','1','1444760510','1444760510'),('742','alltoken','user','member_card','index','1','1458797047','1458797047'),('212','alltoken','user','member_card','design','2','1444642736','1444639426'),('607','alltoken','user','index','add','1','1444760501','1444760501'),('1277','ywxgfh1465574691','user','store','setting','3','1465614392','1465613705'),('222','alltoken','user','research','index','1','1444639560','1444639560'),('604','alltoken','user','member_card','coupons','1','1444760354','1444760354'),('224','alltoken','user','invite','add','4','1444642661','1444639563'),('226','alltoken','user','invite','index','1','1444639571','1444639571'),('228','alltoken','user','invite','enroll','1','1444639574','1444639574'),('602','alltoken','user','member_card','consume_use','1','1444760341','1444760341'),('1104','alltoken','user','vote','index','1','1463380052','1463380052'),('1228','alltoken','user','upyun','upload','7','1465573477','1465566460'),('1229','alltoken','user','map','setlatlng_amap','3','1465573506','1465566496'),('243','alltoken','user','invites','index','4','1444640906','1444640654'),('245','alltoken','user','invites','add','1','1444640659','1444640659'),('600','alltoken','user','member_card','consume_record','1','1444760337','1444760337'),('686','alltoken','user','shakearound','page_index','1','1458796791','1458796791'),('1170','alltoken','user','sms','buy','1','1464270528','1464270528'),('253','alltoken','user','game','gamelibrary','1','1444640928','1444640928'),('1086','alltoken','user','aaactivity','index','1','1463366635','1463366635'),('1088','alltoken','user','service','preferentiallist','1','1463366667','1463366667'),('259','alltoken','user','jingcai','index','3','1444642705','1444641456'),('263','alltoken','user','router','index','1','1444641483','1444641483'),('265','alltoken','user','router','config','1','1444641490','1444641490'),('268','alltoken','user','hardware','orderprintset','4','1444642264','1444641692'),('690','alltoken','user','store','twitter','6','1458819672','1458796803'),('839','alltoken','user','repast','index','1','1459067097','1459067097'),('280','alltoken','user','fangchan','index','2','1444642608','1444642596'),('282','alltoken','user','zhaopin','index','2','1444642722','1444642599'),('284','alltoken','user','zhaopin','jianli','1','1444642602','1444642602'),('286','alltoken','user','zhaopin','reply','1','1444642605','1444642605'),('288','alltoken','user','fangchan','exportforms','1','1444642610','1444642610'),('290','alltoken','user','signin','index','1','1444642654','1444642654'),('1107','alltoken','user','index','frame','1','1463636330','1463636330'),('1108','alltoken','user','function','welcome','1','1463636331','1463636331'),('796','alltoken','user','reply','index','1','1458804655','1458804655'),('297','alltoken','user','baoming','index','2','1444642703','1444642697'),('299','alltoken','user','baoming','company','1','1444642700','1444642700'),('303','alltoken','user','member_card','center','1','1444642741','1444642741'),('305','alltoken','user','member_card','consume_record','1','1444642743','1444642743'),('307','alltoken','user','member_card','custom','1','1444642745','1444642745'),('309','alltoken','user','czz','index','4','1444664176','1444642928'),('311','alltoken','user','fanyan','index','3','1444664178','1444642932'),('1268','alltoken','user','index','edit','2','1465613578','1465613343'),('1269','ywxgfh1465574691','user','store','index','5','1465705951','1465613691'),('1299','ywxgfh1465574691','user','seckill','index','1','1465614472','1465614472'),('1300','alltoken','user','seckill','index','1','1465614472','1465614472'),('1132','alltoken','user','unitary','index','1','1463645005','1463645005'),('325','alltoken','user','shake','index','1','1444643063','1444643063'),('327','alltoken','user','wall','index','1','1444643065','1444643065'),('329','alltoken','user','scene','index','1','1444643067','1444643067'),('334','alltoken','user','customtmpls','mydynamic','3','1444643666','1444643379'),('336','alltoken','user','tmpls','index','4','1444654299','1444643487'),('338','alltoken','user','tmpls','qrcode','4','1444654300','1444643489'),('676','alltoken','user','live','index','2','1458796701','1458794790');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('342','alltoken','user','seniorscene','highlive','1','1444643507','1444643507');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('344','alltoken','user','vifnn','index','4','1444655452','1444654319'),('1178','alltoken','user','yulan','index','1','1464270950','1464270950'),('1180','alltoken','wap','index','index','1','1464270951','1464270951'),('350','alltoken','user','home','plugmenu','2','1444661833','1444654582'),('355','alltoken','wap','index','lists','1','1444655278','1444655278'),('682','alltoken','user','zhida','index','1','1458796776','1458796776'),('363','alltoken','user','voiceresponse','add','1','1444655911','1444655911'),('365','alltoken','user','templatemsg','add','2','1444655983','1444655919'),('372','alltoken','user','collectword','index','2','1444656279','1444656261'),('374','alltoken','user','lottery','index','1','1444656263','1444656263'),('377','alltoken','user','index','useredit','4','1444656527','1444656307'),('1176','alltoken','user','home','set','1','1464270921','1464270921'),('1168','alltoken','user','sms','index','2','1464270550','1464270520'),('385','alltoken','user','index','invite','2','1444656518','1444656397'),('387','alltoken','user','index','qiye','8','1444657200','1444656861'),('1148','alltoken','user','text','index','1','1464249233','1464249233'),('1218','alltoken','user','company','branches','1','1465224937','1465224937'),('393','alltoken','user','hotels','index','7','1444664165','1444660314'),('842','alltoken','user','host','index','1','1459067107','1459067107'),('398','alltoken','user','img','add','2','1444661875','1444661001'),('400','alltoken','user','img','diytool','1','1444661007','1444661007'),('406','alltoken','user','classify','edit','1','1444661682','1444661682'),('408','alltoken','user','yjgz','index','1','1444663220','1444663220'),('726','alltoken','user','host','index','1','1458796999','1458796999'),('1190','alltoken','user','index','frame','1','1464661915','1464661915'),('864','alltoken','user','estate','index','1','1459155085','1459155085'),('1098','alltoken','user','car','index','1','1463369348','1463369348'),('418','alltoken','user','medical','index','1','1444664163','1444664163'),('1220','alltoken','user','upyun','upload','3','1465225070','1465225045'),('1186','alltoken','user','member_card','focus','1','1464272453','1464272453'),('680','alltoken','user','phone','downloadfile','1','1458796737','1458796737'),('657','alltoken','user','api','add','1','1458789555','1458789555'),('425','alltoken','user','game','config','2','1444729283','1444669365'),('1077','alltoken','user','customtmpls','dynamic','1','1462932789','1462932789'),('835','alltoken','user','numqueue','index','1','1459067071','1459067071'),('837','alltoken','user','numqueue','add_action','1','1459067074','1459067074'),('841','alltoken','user','dishout','index','1','1459067099','1459067099'),('655','alltoken','user','api','index','1','1458789551','1458789551'),('1184','alltoken','user','member_card','replyinfoset','1','1464272435','1464272435'),('816','alltoken','user','api','index','1','1458990556','1458990556'),('756','alltoken','user','link','insert','1','1458797134','1458797134'),('833','alltoken','user','vifnnemail','index','1','1459066903','1459066903'),('439','alltoken','user','yjgz','index','1','1444671423','1444671423'),('1182','alltoken','user','company','index','2','1464271676','1464271019'),('1243','alltoken','user','attachment','my','1','1465573481','1465573481'),('442','alltoken','user','attachment','index','14','1444747490','1444671441'),('1206','alltoken','user','micrstore','api','6','1465226291','1465223802'),('1204','alltoken','user','micrstore','index','9','1465226288','1465223796'),('1241','alltoken','user','bargain','index','1','1465568780','1465568780'),('448','alltoken','user','bargain','add','1','1444728947','1444728947'),('1235','alltoken','user','market','index','1','1465567640','1465567640'),('1233','alltoken','user','livingcircle','index','1','1465567622','1465567622'),('1290','alltoken','user','livingcircle','type','1','1465614433','1465614433'),('1291','ywxgfh1465574691','user','livingcircle','typeadd','1','1465614436','1465614436'),('778','alltoken','user','livingcircle','sellcircle','1','1458804279','1458804279'),('780','alltoken','user','livingcircle','seller','1','1458804281','1458804281'),('1298','alltoken','user','cutprice','index','1','1465614469','1465614469'),('1130','alltoken','user','seckill','index','1','1463637450','1463637450'),('1131','7a965969c8c38a32','user','unitary','index','1','1463645005','1463645005'),('460','alltoken','user','seckill','action_add','1','1444729109','1444729109'),('461','alltoken','user','jingcai','index','2','1444729154','1444729146'),('718','alltoken','user','jikedati','index','1','1458796956','1458796956'),('464','alltoken','user','invites','index','2','1444729310','1444729151'),('748','alltoken','user','serviceuser','wechatservice','1','1458797098','1458797098'),('758','alltoken','user','requerydata','index','1','1458797147','1458797147'),('1172','alltoken','user','sms','buy_post','1','1464270534','1464270534'),('868','alltoken','user','wechat_group','index','2','1459155951','1459155936'),('872','alltoken','user','message','index','1','1459155964','1459155964'),('473','alltoken','user','share','index','1','1444729240','1444729240');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('870','alltoken','user','fuwu','index','1','1459155940','1459155940');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('720','alltoken','user','hardware','wifi','1','1458796963','1458796963'),('710','alltoken','user','photo','index','1','1458796921','1458796921'),('480','alltoken','user','invite','add','1','1444729312','1444729312'),('482','alltoken','user','research','index','1','1444729314','1444729314'),('712','alltoken','user','custom','index','3','1458796941','1458796931'),('485','alltoken','user','baoming','index','1','1444729319','1444729319'),('486','alltoken','user','shake','index','1','1444729322','1444729322'),('1106','alltoken','user','forum','index','1','1463380055','1463380055'),('488','alltoken','user','wall','index','1','1444729362','1444729362'),('489','alltoken','user','scene','index','1','1444729364','1444729364'),('491','alltoken','user','applegame','index','1','1444729395','1444729395'),('493','alltoken','user','goldenegg','index','1','1444729397','1444729397'),('495','alltoken','user','luckyfruit','index','1','1444729399','1444729399'),('497','alltoken','user','guajiang','index','2','1444729456','1444729401'),('499','alltoken','user','guajiang','add','1','1444729403','1444729403'),('500','alltoken','user','collectword','index','7','1444747555','1444729411'),('502','alltoken','user','jiugong','index','1','1444729434','1444729434'),('504','alltoken','user','coupon','index','1','1444729436','1444729436'),('506','alltoken','user','problem','index','1','1444729458','1444729458'),('508','alltoken','user','problem','game_set','1','1444729462','1444729462'),('1210','alltoken','user','taobao','index','2','1465224902','1465224661'),('1166','alltoken','user','index','index','2','1464270563','1464270506'),('1237','alltoken','user','platform','index','1','1465567657','1465567657'),('517','alltoken','user','img','add','5','1444739755','1444739596'),('519','alltoken','user','message','img','2','1444739752','1444739731'),('1198','alltoken','user','classify','index','1','1464861321','1464861321'),('522','alltoken','user','img','diytool','1','1444740063','1444740063'),('523','alltoken','user','zhaopin','index','3','1444740375','1444740326'),('525','alltoken','user','zhaopin','add','2','1444740377','1444740330'),('526','alltoken','user','fangchan','index','1','1444740341','1444740341'),('527','alltoken','user','fangchan','exportforms','1','1444740343','1444740343'),('529','alltoken','user','fangchan','add','1','1444740346','1444740346'),('530','alltoken','user','zhaopin','jianli','1','1444740361','1444740361'),('532','alltoken','user','zhaopin','jladd','1','1444740364','1444740364'),('533','alltoken','user','zhaopin','reply','1','1444740369','1444740369'),('1018','alltoken','user','micrstore','api','1','1460432248','1460432248'),('1222','alltoken','user','map','setlatlng_amap','3','1465225220','1465225077'),('706','alltoken','user','red_packet','index','1','1458796893','1458796893'),('538','alltoken','user','frontpage','index','4','1444747002','1444744681'),('1016','alltoken','user','micrstore','index','1','1460432243','1460432243'),('540','alltoken','user','frontpage','addaction','3','1444747004','1444744684'),('1150','alltoken','user','img','index','1','1464249236','1464249236'),('542','alltoken','user','cointree','index','2','1444745452','1444745147'),('1014','alltoken','user','function','welcome','1','1460432228','1460432228'),('544','alltoken','user','cointree','add_action','2','1444745454','1444745150'),('1013','alltoken','user','index','frame','1','1460432227','1460432227'),('546','alltoken','user','collectword','set','2','1444747155','1444745507'),('1012','alltoken','user','micrstore','api','3','1460366257','1460366247'),('1292','alltoken','user','livingcircle','typeadd','1','1465614436','1465614436'),('1011','eclmus1460363554','user','micrstore','api','3','1460366257','1460366247'),('550','alltoken','user','game','gamelibrary','1','1444747547','1444747547'),('551','alltoken','user','czz','index','1','1444747549','1444747549'),('552','alltoken','user','lottery','index','1','1444747558','1444747558'),('1010','eclmus1460363554','user','function','welcome','1','1460366245','1460366245'),('555','alltoken','user','assistente','add','1','1444748252','1444748252'),('1009','eclmus1460363554','user','index','frame','1','1460366244','1460366244'),('557','alltoken','user','function','welcomedy','18','1444751826','1444748305'),('1008','alltoken','user','index','apiinfo','1','1460366242','1460366242'),('684','alltoken','user','shakearound','index','1','1458796786','1458796786'),('669','alltoken','user','phone','baseset','3','1458796726','1458794413'),('562','alltoken','user','function','welcomedy','13','1444758567','1444752890'),('645','alltoken','user','areply','index','1','1458744325','1458744325'),('931','alltoken','user','classify','index','1','1459402797','1459402797'),('661','alltoken','user','img','index','1','1458790748','1458790748'),('1007','eclmus1460363554','user','index','apiinfo','1','1460366242','1460366242'),('567','alltoken','user','wechat_group','groups','2','1444760372','1444752937'),('1006','eclmus1460363554','user','index','index','1','1460366241','1460366241'),('1156','alltoken','user','message','sendhistory','1','1464249251','1464249251');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('815','alltoken','user','diymen','index','2','1458990538','1458990523');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('1194','alltoken','user','function','welcome','1','1464859243','1464859243'),('825','alltoken','user','auth','index','2','1459066241','1459011831'),('1193','alltoken','user','index','frame','1','1464859243','1464859243'),('760','alltoken','user','wechat_behavior','statistics','1','1458797150','1458797150'),('722','alltoken','user','hardware','orderprint','1','1458796975','1458796975'),('724','alltoken','user','hardware','photoprint','1','1458796980','1458796980'),('665','alltoken','user','assistente','index','1','1458794164','1458794164'),('578','alltoken','user','assistente','add','2','1444754047','1444753992'),('579','alltoken','user','lottery','index','2','1444758596','1444754085'),('936','alltoken','user','templatemsg','index','1','1459402829','1459402829'),('581','alltoken','user','templatemsg','add','1','1444754115','1444754115'),('784','alltoken','user','alipay_cert','index','1','1458804395','1458804395'),('1004','eclmus1460363554','user','index','upsave','1','1460366239','1460366239'),('1216','alltoken','user','alipay_config','index','1','1465224888','1465224888'),('1208','alltoken','user','company','index','10','1465225325','1465224649'),('764','alltoken','user','weixinbill','index','1','1458804068','1458804068'),('647','alltoken','user','img','multi','1','1458744327','1458744327'),('589','alltoken','user','lottery','add','2','1444758605','1444758599'),('1154','alltoken','user','voiceresponse','index','1','1464249247','1464249247'),('667','alltoken','user','home','set','2','1458794432','1458794350'),('829','alltoken','user','text','index','1','1459066227','1459066227'),('671','alltoken','user','jiejing','index','1','1458794458','1458794458'),('628','alltoken','user','index','add','3','1444998683','1444998344'),('1174','alltoken','user','alipay','index','1','1464270537','1464270537'),('630','alltoken','user','alipay','recharge','3','1444998754','1444998688'),('997','alltoken','user','alipay','vip','1','1460338004','1460338004'),('730','alltoken','user','business','index','1','1458797020','1458797020'),('1003','alltoken','user','index','bindhelp','1','1460366175','1460366175'),('1114','alltoken','user','business','index_add','1','1463637068','1463637068'),('635','alltoken','user','medical','index','2','1445001596','1445001550'),('808','alltoken','user','microbroker','index','1','1458866479','1458866479'),('1096','alltoken','user','wedding','index','1','1463369347','1463369347'),('1002','eclmus1460363554','user','index','bindhelp','1','1460366175','1460366175'),('639','alltoken','user','wedding','add','1','1445001823','1445001823'),('1146','alltoken','user','areply','index','1','1464249230','1464249230'),('688','alltoken','user','shakearound','statistics','1','1458796793','1458796793'),('1144','alltoken','user','function','welcome','5','1464270828','1464249220'),('692','alltoken','user','store','twitterset','2','1458804377','1458796808'),('1143','alltoken','user','index','frame','4','1464270827','1464249220'),('694','alltoken','user','store','removesearch','1','1458796817','1458796817'),('1142','alltoken','user','function','welcome','2','1463992348','1463986310'),('1273','ywxgfh1465574691','user','reply_info','set','2','1465614335','1465613697'),('1276','alltoken','user','store','flash','2','1465614331','1465613702'),('1140','alltoken','user','index','frame','2','1463992347','1463986309'),('1138','alltoken','user','micrstore','withdraw','2','1463889111','1463888036'),('708','alltoken','user','red_packet','set','1','1458796895','1458796895'),('714','alltoken','user','custom','record','1','1458796935','1458796935'),('1136','alltoken','user','micrstore','index','4','1463889105','1463887924'),('716','alltoken','user','custom','set','1','1458796943','1458796943'),('1135','alltoken','user','micrstore','api','11','1463889109','1463885376'),('1134','alltoken','user','function','welcome','2','1463887918','1463885370'),('732','alltoken','user','business','classify','1','1458797028','1458797028'),('1133','alltoken','user','index','frame','2','1463887918','1463885369'),('734','alltoken','user','business','project','1','1458797030','1458797030'),('1129','7a965969c8c38a32','user','seckill','index','1','1463637450','1463637450'),('736','alltoken','user','business','poster','1','1458797031','1458797031'),('1128','alltoken','user','adma','index','1','1463637113','1463637113'),('738','alltoken','user','business','comments','1','1458797033','1458797033'),('740','alltoken','user','business','orders','1','1458797035','1458797035'),('1126','alltoken','user','business','poster_add','1','1463637084','1463637084'),('744','alltoken','user','recognition','index','1','1458797056','1458797056'),('1124','alltoken','user','business','orders','1','1463637081','1463637081'),('750','alltoken','user','service','servicelist','3','1458797124','1458797116'),('752','alltoken','user','service','preferentiallist','1','1458797127','1458797127'),('1122','alltoken','user','business','comments','1','1463637078','1463637078'),('754','alltoken','user','service','preferentialadd','1','1458797129','1458797129'),('1120','alltoken','user','business','poster','3','1463637083','1463637075');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('1313','ywxgfh1465574691','user','adma','index','1','1465782538','1465782538');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('1239','alltoken','user','crowdfunding','index','1','1465568776','1465568776'),('1312','alltoken','user','recognitiondata','index','3','1465782536','1465782529'),('770','alltoken','user','crowdfunding','basis_set','1','1458804225','1458804225'),('1118','alltoken','user','business','project','1','1463637074','1463637074'),('1311','ywxgfh1465574691','user','recognitiondata','index','3','1465782536','1465782529'),('1116','alltoken','user','business','classify','1','1463637073','1463637073'),('1310','alltoken','user','function','welcome','1','1465782463','1465782463'),('1309','ywxgfh1465574691','user','function','welcome','1','1465782463','1465782463'),('1152','alltoken','user','img','multi','1','1464249240','1464249240'),('1212','alltoken','user','groupon','index','1','1465224871','1465224871'),('788','alltoken','user','groupon','products','2','1458804590','1458804576'),('1308','alltoken','user','index','frame','1','1465782462','1465782462'),('1112','alltoken','user','business','index','1','1463637058','1463637058'),('790','alltoken','user','groupon','config','1','1458804578','1458804578'),('792','alltoken','user','groupon','add','1','1458804593','1458804593'),('1110','alltoken','user','company','index','1','1463637047','1463637047'),('798','alltoken','user','reply','config','2','1458804670','1458804657'),('1280','alltoken','user','classify','choosetpl','2','1465614273','1465613718'),('1281','ywxgfh1465574691','user','catemenu','choosemenu','1','1465614297','1465614297'),('1282','alltoken','user','catemenu','choosemenu','1','1465614297','1465614297'),('1102','alltoken','user','micrstore','api','1','1463379599','1463379599'),('1284','alltoken','user','store','catadd','1','1465614354','1465614354'),('1285','ywxgfh1465574691','user','taobao','index','1','1465614400','1465614400'),('810','alltoken','user','microbroker','mbkadd','1','1458866492','1458866492'),('1307','ywxgfh1465574691','user','index','frame','1','1465782462','1465782462'),('1100','alltoken','user','micrstore','index','1','1463379596','1463379596'),('813','alltoken','user','conditionalmenu','index','1','1458990488','1458990488'),('1305','alltoken','user','store','index','1','1465705951','1465705951'),('1304','alltoken','user','function','welcome','1','1465705946','1465705946'),('1094','alltoken','user','host','add','1','1463368793','1463368793'),('843','alltoken','user','host','add','1','1459067110','1459067110'),('844','alltoken','user','service','preferentiallist','1','1459067177','1459067177'),('845','alltoken','user','service','preferentialadd','1','1459067179','1459067179'),('846','alltoken','user','link','insert','2','1459067197','1459067182'),('1303','alltoken','user','index','frame','1','1465705945','1465705945'),('848','alltoken','user','link','research','1','1459067190','1459067190'),('1092','alltoken','user','host','index','1','1463368787','1463368787'),('1270','alltoken','user','store','index','4','1465614391','1465613691'),('1271','ywxgfh1465574691','user','store','orders','3','1465705956','1465613695'),('1272','alltoken','user','store','orders','2','1465614385','1465613695'),('1090','alltoken','user','serviceuser','wechatservice','1','1463366669','1463366669'),('1274','alltoken','user','reply_info','set','2','1465614335','1465613697'),('1275','ywxgfh1465574691','user','store','flash','2','1465614331','1465613702'),('856','alltoken','user','store','twitter','2','1459157191','1459155043'),('1302','alltoken','user','unitary','index','1','1465614479','1465614479'),('858','alltoken','user','store','twitterset','1','1459155045','1459155045'),('1301','ywxgfh1465574691','user','unitary','index','1','1465614479','1465614479'),('860','alltoken','user','store','removesearch','1','1459155053','1459155053'),('1075','alltoken','user','img','index','2','1462932786','1462932779'),('862','alltoken','user','microbroker','index','3','1459157455','1459155084'),('866','alltoken','user','microbroker','mbkadd','2','1459157460','1459155089'),('1297','ywxgfh1465574691','user','cutprice','index','1','1465614469','1465614469'),('1073','alltoken','user','classify','index','1','1462932766','1462932766'),('1202','alltoken','user','function','welcome','1','1465223790','1465223790'),('1071','alltoken','user','home','set','1','1462932756','1462932756'),('1200','alltoken','user','index','frame','1','1465223790','1465223790'),('1296','alltoken','user','auction','index','1','1465614467','1465614467'),('1069','alltoken','user','index','index','1','1462930044','1462930044'),('880','alltoken','user','index','bindhelp','1','1459157412','1459157412'),('1068','alltoken','user','index','info','1','1462930044','1462930044'),('882','alltoken','user','link','insert','1','1459157470','1459157470'),('1295','ywxgfh1465574691','user','auction','index','1','1465614467','1465614467'),('884','alltoken','user','link','problem','1','1459157484','1459157484'),('1293','ywxgfh1465574691','user','platform','index','1','1465614442','1465614442'),('886','alltoken','user','conditionalmenu','index','1','1459157501','1459157501'),('1294','alltoken','user','platform','index','1','1465614442','1465614442'),('888','alltoken','user','api','index','1','1459157506','1459157506');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('1056','alltoken','user','vifnnemail','index','1','1462929916','1462929916');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('890','alltoken','user','api','add','1','1459157510','1459157510'),('891','alltoken','user','microbroker','index','1','1459204453','1459204453'),('892','alltoken','user','microbroker','mbkadd','1','1459204455','1459204455'),('894','alltoken','user','recognition','index','3','1459241137','1459221255'),('895','alltoken','user','link','insert','2','1459221277','1459221268'),('897','alltoken','user','link','autumn','1','1459221273','1459221273'),('1054','alltoken','user','quick','index','1','1462929897','1462929897'),('1314','alltoken','user','adma','index','1','1465782538','1465782538'),('1227','alltoken','user','company','index','15','1465573890','1465565800'),('1289','ywxgfh1465574691','user','livingcircle','type','1','1465614433','1465614433'),('903','alltoken','user','text','index','1','1459240773','1459240773'),('1225','alltoken','user','index','frame','3','1465573415','1465565786'),('1226','alltoken','user','function','welcome','3','1465573420','1465565787'),('1052','alltoken','user','quick','cpshortlink','1','1462929881','1462929881'),('907','alltoken','user','business','index','1','1459323659','1459323659'),('1288','alltoken','user','livingcircle','index','1','1465614422','1465614422'),('909','alltoken','user','business','classify','2','1459323691','1459323670'),('1050','alltoken','user','assistente','index','1','1462929845','1462929845'),('911','alltoken','user','business','project','2','1459323694','1459323675'),('913','alltoken','user','business','poster','2','1459323696','1459323677'),('1048','alltoken','user','micrstore','api','2','1462929604','1462929447'),('915','alltoken','user','business','comments','2','1459323698','1459323679'),('1047','alltoken','user','micrstore','index','1','1462929440','1462929440'),('917','alltoken','user','business','orders','1','1459323681','1459323681'),('1046','alltoken','user','function','welcome','1','1462929422','1462929422'),('1246','alltoken','user','index','info','3','1465613569','1465574612'),('1045','alltoken','user','index','frame','1','1462929421','1462929421'),('1248','alltoken','user','index','index','6','1465613596','1465574613'),('1250','alltoken','user','index','apiinfo','1','1465574617','1465574617'),('924','alltoken','user','auth','index','1','1459352610','1459352610'),('1044','alltoken','user','wechat_behavior','statistics','1','1462892176','1462892176'),('926','alltoken','user','quick','index','1','1459393810','1459393810'),('1266','ywxgfh1465574691','user','index','info','2','1465613569','1465613338'),('928','alltoken','user','areply','index','4','1459403557','1459402782'),('929','alltoken','user','text','index','2','1459403522','1459402787'),('1267','ywxgfh1465574691','user','index','edit','2','1465613578','1465613343'),('933','alltoken','user','classify','add','1','1459402805','1459402805'),('934','alltoken','user','message','index','1','1459402827','1459402827'),('1042','alltoken','user','requerydata','index','1','1462892174','1462892174'),('939','alltoken','user','img','index','3','1459403564','1459403523'),('940','alltoken','user','conditionalmenu','index','1','1459403571','1459403571'),('1265','alltoken','user','attachment','my','1','1465602705','1465602705'),('942','alltoken','user','conditionalmenu','matchrule_set','1','1459403597','1459403597'),('1040','alltoken','user','company','index','1','1462892160','1462892160'),('944','alltoken','user','diymen','index','1','1459403626','1459403626'),('945','alltoken','user','auth','index','1','1459408642','1459408642'),('1264','ywxgfh1465574691','user','attachment','my','1','1465602705','1465602705'),('947','alltoken','user','index','frame','1','1459950633','1459950633'),('1038','alltoken','user','micrstore','api','2','1462892138','1462892080'),('949','alltoken','user','function','welcome','1','1459950633','1459950633'),('1263','alltoken','user','map','setlatlng_amap','4','1465613464','1465574913'),('951','alltoken','user','taobao','index','1','1459950645','1459950645'),('1036','alltoken','user','micrstore','index','4','1462892133','1462892076'),('953','alltoken','user','store','index','1','1459953942','1459953942'),('1262','ywxgfh1465574691','user','map','setlatlng_amap','4','1465613464','1465574913'),('955','alltoken','user','store','catadd','1','1459953952','1459953952'),('956','alltoken','user','index','frame','1','1459997664','1459997664'),('957','alltoken','user','function','welcome','1','1459997665','1459997665'),('958','alltoken','user','index','frame','1','1460215186','1460215186'),('959','alltoken','user','function','welcome','1','1460215186','1460215186'),('960','alltoken','user','store','index','3','1460215229','1460215194'),('962','alltoken','user','store','orders','2','1460215226','1460215219'),('1034','alltoken','user','business','index','1','1462892067','1462892067'),('964','alltoken','user','reply_info','set','1','1460215230','1460215230'),('1260','ywxgfh1465574691','user','company','index','8','1465613556','1465574836'),('966','alltoken','user','store','setting','1','1460215243','1460215243'),('1261','ywxgfh1465574691','user','upyun','upload','10','1465613459','1465574886'),('968','alltoken','user','store','twitter','3','1460215360','1460215252');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('1032','alltoken','user','index','bindhelp','1','1462892023','1462892023');/* MySQLReback Separation */
 /* 插入数据 `vifnn_access_count` */
 INSERT INTO `vifnn_access_count` VALUES ('970','alltoken','user','store','flash','1','1460215258','1460215258'),('1259','alltoken','user','function','welcome','5','1465613688','1465574831'),('972','alltoken','user','alipay_config','index','2','1460215299','1460215267'),('973','alltoken','user','taobao','index','2','1460215289','1460215269'),('1030','alltoken','user','index','apiinfo','1','1462892019','1462892019'),('975','alltoken','user','platform','index','1','1460215280','1460215280'),('1258','ywxgfh1465574691','user','function','welcome','6','1465705946','1465574831'),('977','alltoken','user','livingcircle','index','1','1460215290','1460215290'),('1028','alltoken','user','function','welcome','2','1462892035','1462892016'),('979','alltoken','user','market','index','1','1460215293','1460215293'),('1257','alltoken','user','index','frame','5','1465613688','1465574831'),('981','alltoken','user','alipay_cert','index','1','1460215296','1460215296'),('1026','alltoken','user','index','frame','2','1462892035','1462892015'),('983','alltoken','user','store','twitterset','1','1460215363','1460215363'),('1256','ywxgfh1465574691','user','index','frame','6','1465705945','1465574831'),('985','alltoken','user','micrstore','index','3','1460217407','1460215391'),('1024','alltoken','user','index','index','1','1462892012','1462892012'),('987','alltoken','user','micrstore','api','4','1460217429','1460215393'),('1255','ywxgfh1465574691','user','index','index','5','1465613596','1465574828'),('989','alltoken','user','micrstore','withdraw','1','1460215400','1460215400'),('990','alltoken','user','micrstore','index','1','1460218464','1460218464'),('991','alltoken','user','micrstore','api','2','1460218484','1460218466'),('1022','alltoken','user','index','upsave','1','1462892010','1462892010'),('993','alltoken','user','index','frame','5','1460367533','1460337593'),('1254','alltoken','user','index','upsave','3','1465613593','1465574825'),('995','alltoken','user','function','welcome','5','1460367534','1460337595'),('1020','alltoken','user','upyun','upload','2','1462891962','1462891955'),('999','alltoken','user','index','index','4','1460366241','1460338018'),('1019','admin','user','upyun','upload','2','1462891962','1462891955'),('1001','alltoken','user','index','info','1','1460340527','1460340527');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_activity` */
 DROP TABLE IF EXISTS `vifnn_activity`;/* MySQLReback Separation */ CREATE TABLE `vifnn_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `joinnum` int(11) NOT NULL COMMENT '参与人数',
  `click` int(11) NOT NULL,
  `token` varchar(30) NOT NULL,
  `keyword` varchar(10) NOT NULL,
  `starpicurl` varchar(100) NOT NULL COMMENT '活动开始图片',
  `title` varchar(60) NOT NULL COMMENT '活动名称',
  `txt` varchar(60) NOT NULL COMMENT '兑奖信息',
  `sttxt` varchar(200) NOT NULL COMMENT '活动主题',
  `statdate` int(11) NOT NULL COMMENT '活动开始时间',
  `enddate` int(11) NOT NULL COMMENT '活动结束时间',
  `info` varchar(200) NOT NULL COMMENT '活动说明',
  `aginfo` varchar(200) NOT NULL COMMENT '活动规则',
  `endtite` varchar(60) NOT NULL COMMENT '结束公告',
  `endpicurl` varchar(100) NOT NULL COMMENT '结束图片地址',
  `endinfo` varchar(60) NOT NULL,
  `fist` varchar(50) NOT NULL COMMENT '一等奖奖品',
  `fistnums` int(4) NOT NULL COMMENT '一等奖奖品数量',
  `fistlucknums` int(1) NOT NULL COMMENT '一等奖中奖人数',
  `second` varchar(50) NOT NULL COMMENT '二等奖奖品',
  `type` tinyint(1) NOT NULL,
  `secondnums` int(4) NOT NULL COMMENT '二等奖奖品数量',
  `secondlucknums` int(1) NOT NULL COMMENT '三等奖中奖人数',
  `third` varchar(50) NOT NULL,
  `thirdnums` int(4) NOT NULL,
  `thirdlucknums` int(1) NOT NULL,
  `allpeople` int(11) NOT NULL COMMENT '预计参与人数',
  `canrqnums` int(2) NOT NULL COMMENT '个人抽奖次数限制',
  `parssword` int(15) NOT NULL,
  `renamesn` varchar(50) NOT NULL DEFAULT '',
  `renametel` varchar(60) NOT NULL,
  `displayjpnums` int(1) NOT NULL,
  `createtime` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `four` varchar(50) NOT NULL COMMENT '四等奖奖品',
  `fournums` int(11) NOT NULL COMMENT '四等奖奖品数量',
  `fourlucknums` int(11) NOT NULL COMMENT '四等奖中奖人数',
  `five` varchar(50) NOT NULL COMMENT '五等奖奖品',
  `fivenums` int(11) NOT NULL COMMENT '5奖品数量',
  `fivelucknums` int(11) NOT NULL COMMENT '5中间人数',
  `six` varchar(50) NOT NULL COMMENT '六等奖奖品',
  `sixnums` int(11) NOT NULL COMMENT '6奖品数量',
  `sixlucknums` int(11) NOT NULL COMMENT '6中奖人数',
  `zjpic` varchar(150) NOT NULL DEFAULT '',
  `daynums` mediumint(4) NOT NULL DEFAULT '0',
  `maxgetprizenum` mediumint(4) NOT NULL DEFAULT '1',
  `needreg` tinyint(1) NOT NULL DEFAULT '0',
  `backmp3` varchar(100) NOT NULL COMMENT '背景音乐',
  `hpic` varchar(150) NOT NULL COMMENT '首页背景图片',
  `xpic` varchar(150) NOT NULL COMMENT '兑奖页面宣传图片',
  `mpic` varchar(150) NOT NULL COMMENT '我的盒子背景图片',
  `optime` int(11) NOT NULL COMMENT '需要分享的次数',
  `focus` int(11) NOT NULL,
  `awarding` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_adma` */
 DROP TABLE IF EXISTS `vifnn_adma`;/* MySQLReback Separation */ CREATE TABLE `vifnn_adma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `url` varchar(100) NOT NULL,
  `copyright` varchar(50) NOT NULL,
  `info` varchar(120) NOT NULL,
  `title` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_adma` */
 INSERT INTO `vifnn_adma` VALUES ('1','2','zrtmca1421056092','/tpl/Home/vifnn/common/images/ewm2.jpg','© 2001-2013 某某微信版权所有','微信营销管理平台为个人和企业提供基于微信公众平台的一系列功能，包括智能回复、微信3G网站、互动营销活动，会员管理，在线订单，数据统计等系统功能,带给你全新的微信互动营销体验。','微风VIFNN');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_agent` */
 DROP TABLE IF EXISTS `vifnn_agent`;/* MySQLReback Separation */ CREATE TABLE `vifnn_agent` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `intro` varchar(800) NOT NULL DEFAULT '',
  `mp` varchar(11) NOT NULL DEFAULT '',
  `usercount` int(10) NOT NULL DEFAULT '0',
  `wxusercount` int(10) NOT NULL DEFAULT '0',
  `sitename` varchar(50) NOT NULL DEFAULT '',
  `sitelogo` varchar(200) NOT NULL DEFAULT '',
  `qrcode` varchar(100) NOT NULL DEFAULT '',
  `sitetitle` varchar(60) NOT NULL DEFAULT '',
  `siteurl` varchar(100) NOT NULL DEFAULT '',
  `robotname` varchar(40) NOT NULL DEFAULT '',
  `connectouttip` varchar(50) NOT NULL DEFAULT '',
  `needcheckuser` tinyint(1) NOT NULL DEFAULT '0',
  `regneedmp` tinyint(1) NOT NULL DEFAULT '1',
  `reggid` int(10) NOT NULL DEFAULT '0',
  `regvaliddays` mediumint(4) NOT NULL DEFAULT '30',
  `qq` varchar(12) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `metades` varchar(300) NOT NULL DEFAULT '',
  `metakeywords` varchar(200) NOT NULL DEFAULT '',
  `statisticcode` varchar(300) NOT NULL DEFAULT '',
  `copyright` varchar(100) NOT NULL DEFAULT '',
  `alipayaccount` varchar(50) NOT NULL DEFAULT '',
  `alipaypid` varchar(100) NOT NULL DEFAULT '',
  `alipaykey` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `salt` varchar(6) NOT NULL DEFAULT '',
  `money` int(10) NOT NULL DEFAULT '0',
  `moneybalance` int(10) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL DEFAULT '0',
  `endtime` int(11) NOT NULL DEFAULT '0',
  `lastloginip` varchar(26) NOT NULL DEFAULT '',
  `lastlogintime` int(11) NOT NULL DEFAULT '0',
  `wxacountprice` mediumint(4) NOT NULL DEFAULT '0',
  `monthprice` mediumint(4) NOT NULL DEFAULT '0',
  `appid` varchar(50) NOT NULL DEFAULT '',
  `appsecret` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(40) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `isnav` int(11) NOT NULL DEFAULT '0',
  `agenturl` varchar(50) NOT NULL,
  `is_package` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许自定义套餐1允许0不允许',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_agent_expenserecords` */
 DROP TABLE IF EXISTS `vifnn_agent_expenserecords`;/* MySQLReback Separation */ CREATE TABLE `vifnn_agent_expenserecords` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `agentid` int(10) NOT NULL DEFAULT '0',
  `amount` int(10) NOT NULL DEFAULT '0',
  `orderid` varchar(60) NOT NULL DEFAULT '',
  `des` varchar(200) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `agentid` (`agentid`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_agent_function` */
 DROP TABLE IF EXISTS `vifnn_agent_function`;/* MySQLReback Separation */ CREATE TABLE `vifnn_agent_function` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `usenum` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `funname` varchar(100) NOT NULL,
  `info` varchar(100) NOT NULL,
  `isserve` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `agentid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`),
  KEY `agentid` (`agentid`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_agent_function` */
 INSERT INTO `vifnn_agent_function` VALUES ('1','1','0','天气查询','tianqi','天气查询服务:例  城市名+天气','1','1','1'),('2','1','0','糗事','qiushi','糗事　直接发送糗事','1','1','1'),('3','1','0','计算器','jishuan','计算器使用方法　例：计算50-50　，计算100*100','1','1','1'),('4','4','0','朗读','langdu','朗读＋关键词　例：朗读pigcms多用户营销系统','1','1','1'),('5','3','0','健康指数查询','jiankang','健康指数查询　健康＋高，＋重　例：健康170,65','1','1','1'),('6','1','0','快递查询','kuaidi','快递＋快递名＋快递号　例：快递顺丰117215889174','1','1','1'),('7','1','0','笑话','xiaohua','笑话　直接发送笑话','1','1','1'),('8','2','0','藏头诗','changtoushi',' 藏头诗+关键词　例：藏头诗我爱你','1','1','1'),('9','1','0','陪聊','peiliao','聊天　直接输入聊天关键词即可','1','1','1'),('10','1','0','聊天','liaotian','聊天　直接输入聊天关键词即可','1','1','1'),('11','3','0','周公解梦','mengjian','周公解梦　梦见+关键词　例如:梦见父母','1','1','1'),('12','2','0','语音翻译','yuyinfanyi','翻译＋关键词 例：翻译你好','1','1','1'),('13','2','0','火车查询','huoche','火车查询　火车＋城市＋目的地　例火车上海南京','1','1','1'),('14','2','0','公交查询','gongjiao','公交＋城市＋公交编号　例：上海公交774','1','1','1'),('15','2','0','身份证查询','shenfenzheng','身份证＋号码　　例：身份证342423198803015568','1','1','1'),('16','1','0','手机归属地查询','shouji','手机归属地查询(吉凶 运势) 手机＋手机号码　例：手机13856017160','1','1','1'),('17','3','0','音乐查询','yinle','音乐＋音乐名  例：音乐爱你一万年','1','1','1'),('18','1','0','附近周边信息查询','fujin','附近周边信息查询(ＬＢＳ） 回复:附近+关键词  例:附近酒店','1','1','1'),('19','4','0','公众小秘书','Paper','公众小秘书','2','1','1'),('20','3','0','淘宝店铺','taobao','输入淘宝＋关键词　即可访问淘宝3g手机店铺','2','1','1'),('21','4','0','会员资料管理','userinfo','会员资料管理　输入会员　即可参与','2','1','1'),('22','1','0','翻译','fanyi','翻译＋关键词 例：翻译你好','1','1','1'),('23','4','0','第三方接口','api','第三方接口整合模块，请在管理平台下载接口文件并配置接口信息，','1','1','1'),('24','1','0','姓名算命','suanming','姓名算命 算命＋关键词　例：算命李白','1','1','1'),('25','3','0','百度百科','baike','百度百科　使用方法：百科＋关键词　例：百科北京','2','1','1'),('26','2','0','彩票查询','caipiao','回复内容:彩票+彩种即可查询彩票中奖信息,例：彩票双色球','1','1','1'),('27','4','0','照片墙','Zhaopianwall','活动开启后，在聊天窗口中直接发送图片，即可图片上墙！','2','1','1'),('28','4','0','RippleOS','RippleOS','RippleOS路由器','1','1','1'),('29','1','0','3G首页','shouye','输入首页,访问微3g 网站','1','1','1'),('30','1','0','DIY宣传页','adma','DIY宣传页,用于创建二维码宣传页权限开启','1','1','1'),('31','4','0','会员卡','huiyuanka','尊贵享受vip会员卡,回复会员卡即可领取会员卡','1','1','1'),('32','4','0','微名片','Vcard','微名片','2','1','1'),('33','4','0','账号审核','usernameCheck','正确的审核帐号方式是：审核+帐号','2','1','1'),('34','1','0','歌词查询','geci','歌词查询 回复歌词＋歌名即可查询一首歌曲的歌词,例：歌词醉清风','1','1','1'),('35','2','0','域名whois查询','whois','域名whois查询　回复域名＋域名 可查询网站备案信息,域名whois注册时间等等
 例：域名pigcms.com','1','1','1'),('36','1','0','通用预订系统','host_kev','通用预订系统 可用于酒店预订，ktv订房，旅游预订等。','2','1','1'),('37','4','0','自定义表单','diyform','自定义表单(用于报名，预约,留言)等','1','1','1'),('38','2','0','无线网络订餐','dx','无线网络订餐','1','1','1'),('39','2','0','在线商城','shop','在线商城,购买系统','1','1','1'),('40','2','0','在线团购系统','etuan','在线团购系统','1','1','1'),('41','4','0','自定义菜单','diymen_set','自定义菜单,一键生成轻app','1','1','1'),('42','4','0','幸运大转盘','choujiang','输入抽奖　即可参加幸运大转盘抽奖活动','2','1','1'),('43','4','0','抽奖','lottery','抽奖,输入抽奖即可参加幸运大转盘','1','1','1'),('44','4','0','刮刮卡','gua2','刮刮卡抽奖活动','1','1','1'),('45','4','0','全景','panorama','','1','1','1'),('46','4','0','婚庆喜帖','Wedding','','2','1','1'),('47','4','0','投票','Vote','','2','1','1'),('48','4','0','房产','estate','','2','1','1'),('49','4','0','3G相册','album','','1','1','1'),('50','4','0','砸金蛋','GoldenEgg','','2','1','1'),('51','4','0','水果机','LuckyFruit','','2','1','1'),('52','4','0','留言板','messageboard','','2','1','1'),('53','4','0','微汽车','car','','2','1','1'),('54','4','0','微信墙','wall','','1','1','1');/* MySQLReback Separation */
 /* 插入数据 `vifnn_agent_function` */
 INSERT INTO `vifnn_agent_function` VALUES ('55','4','0','摇一摇','shake','','1','1','1');/* MySQLReback Separation */
 /* 插入数据 `vifnn_agent_function` */
 INSERT INTO `vifnn_agent_function` VALUES ('56','4','0','微论坛','forum','','1','1','1'),('57','4','0','微医疗','medical','','1','1','1'),('58','4','0','群发消息','groupmessage','','1','1','1'),('59','4','0','分享统计','share','','1','1','1'),('60','4','0','酒店宾馆','hotel','','1','1','1'),('61','4','0','微教育','school','','1','1','1'),('62','4','0','微场景','Live','','2','1','1'),('63','1','0','微街景','Jiejing','','1','1','1'),('64','1','0','中秋吃月饼','Autumn','','1','1','1'),('65','1','0','摁死小情侣游戏','Lovers','回复 “小情侣” 即可参加','1','1','1'),('66','1','0','七夕走鹊桥','AppleGame','回复 “走鹊桥” 即可参与','1','1','1'),('67','1','0','微调研','Research','','1','1','1'),('68','1','0','一战到底','Problem','','1','1','1'),('69','1','0','微信签到','Signin','','1','1','1'),('70','1','0','现场活动','Scene','','1','1','1'),('71','1','0','微商圈','Market','','1','1','1'),('72','1','0','微预约','Custom','','1','1','1'),('73','1','0','祝福贺卡','Greeting_card','','1','1','1'),('74','1','0','微美容','beauty','','1','1','1'),('75','1','0','微健身','fitness','','1','1','1'),('76','1','0','微政务','gover','','1','1','1'),('77','1','0','微食品','food','','1','1','1'),('78','1','0','微旅游','travel','','1','1','1'),('79','1','0','微花店','flower','','1','1','1'),('80','1','0','微物业','property','','1','1','1'),('81','1','0','微KTV','ktv','','1','1','1'),('82','1','0','微酒吧','bar','','1','1','1'),('83','1','0','微装修','fitment','','1','1','1'),('84','1','0','微婚庆','buswedd','','1','1','1'),('85','1','0','微宠物','affections','','1','1','1'),('86','1','0','微家政','housekeeper','','1','1','1'),('87','1','0','微租赁','lease','','1','1','1'),('88','1','0','微游戏','Gamecenter','','1','1','1'),('89','1','0','百度直达号','Zhida','','1','1','1'),('90','1','0','微信红包','Red_packet','','1','1','1'),('91','1','0','惩罚台','Punish','','1','1','1'),('92','1','0','邀请函','Invite','','1','1','1'),('93','1','0','拆礼盒','Autumns','','1','1','1'),('94','1','0','活动报名','Baoming','主题活动报名','1','1','1'),('95','1','0','微众场景','Scenes','','1','1','1'),('96','1','0','微商盟','Fenlei','微商盟','1','1','1'),('97','1','0','极客答题','Jikedati','','1','1','1'),('98','1','0','微招聘','Zhaopin','','1','1','1'),('99','1','0','微房产','Fangchan','','1','1','1'),('100','1','0','欢乐摇奖','Shakeprize','','1','1','1'),('101','1','0','转发有礼','Hforward','','1','1','1'),('102','1','0','分享达人','Sharetalent','','1','1','1'),('103','1','0','群发消息','Message','','1','1','1'),('104','1','0','高级模版','AdvanceTpl','','1','1','1'),('105','1','0','卡娃贺卡','Hcar','','1','1','1'),('106','1','0','微秀贺卡','Knwx','','1','1','1'),('107','1','0','微竞猜','Jingcai','','1','1','1'),('108','1','0','邮件提醒','Wzqemail','','1','1','1'),('109','1','0','第三方客服','Kefu','第三方客服，接入美洽、百度商桥等','1','1','1'),('110','1','0','微助力','Weizhuli','微助力','1','1','1'),('111','1','0','拉票营销','Lapiao','','1','1','1'),('112','1','0','微杂志','Wzz','微杂志','1','1','1'),('113','1','0','微空场景','Yingyong','','1','1','1'),('114','1','0','方言考试','Fangyan','','1','1','1');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_agent_price` */
 DROP TABLE IF EXISTS `vifnn_agent_price`;/* MySQLReback Separation */ CREATE TABLE `vifnn_agent_price` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `agentid` int(10) NOT NULL DEFAULT '0',
  `minaccount` int(10) NOT NULL DEFAULT '0',
  `maxaccount` int(10) NOT NULL DEFAULT '0',
  `price` int(10) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `agentid` (`agentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_alipay_config` */
 DROP TABLE IF EXISTS `vifnn_alipay_config`;/* MySQLReback Separation */ CREATE TABLE `vifnn_alipay_config` (
  `token` varchar(60) NOT NULL,
  `paytype` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `pid` varchar(40) NOT NULL DEFAULT '',
  `key` varchar(200) NOT NULL DEFAULT '',
  `partnerkey` varchar(100) NOT NULL DEFAULT '',
  `appsecret` varchar(200) NOT NULL DEFAULT '',
  `appid` varchar(60) NOT NULL DEFAULT '',
  `paysignkey` varchar(200) NOT NULL DEFAULT '',
  `partnerid` varchar(200) NOT NULL DEFAULT '',
  `mchid` varchar(100) NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '0',
  `info` text NOT NULL,
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_alipay_config` */
 INSERT INTO `vifnn_alipay_config` VALUES ('7a965969c8c38a32','','','','','','','','','','','1','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_api` */
 DROP TABLE IF EXISTS `vifnn_api`;/* MySQLReback Separation */ CREATE TABLE `vifnn_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `token` varchar(60) NOT NULL,
  `url` varchar(100) NOT NULL,
  `apitoken` varchar(100) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL,
  `is_colation` tinyint(1) NOT NULL,
  `colation_keyword` varchar(100) NOT NULL,
  `number` tinyint(1) NOT NULL,
  `order` tinyint(1) NOT NULL,
  `time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `noanswer` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`token`),
  KEY `IDX_TOKEN` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_appdabao` */
 DROP TABLE IF EXISTS `vifnn_appdabao`;/* MySQLReback Separation */ CREATE TABLE `vifnn_appdabao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(200) NOT NULL,
  `domain` varchar(200) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `app_key` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_appdabao_list` */
 DROP TABLE IF EXISTS `vifnn_appdabao_list`;/* MySQLReback Separation */ CREATE TABLE `vifnn_appdabao_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(200) NOT NULL,
  `web_app_id` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `oktime` int(10) unsigned DEFAULT NULL,
  `err_result` varchar(200) DEFAULT NULL,
  `addtime` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `intro` text,
  `weburl` varchar(500) NOT NULL,
  `apptype` int(11) NOT NULL DEFAULT '0',
  `icopic` varchar(200) NOT NULL,
  `hellopic` varchar(200) NOT NULL,
  `hidetop` int(11) NOT NULL DEFAULT '0',
  `screen` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_areply` */
 DROP TABLE IF EXISTS `vifnn_areply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_areply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `uid` int(11) NOT NULL,
  `uname` varchar(90) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `updatetime` varchar(13) NOT NULL,
  `token` char(30) NOT NULL,
  `home` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_art` */
 DROP TABLE IF EXISTS `vifnn_art`;/* MySQLReback Separation */ CREATE TABLE `vifnn_art` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(90) NOT NULL,
  `key` varchar(120) NOT NULL,
  `description` char(60) NOT NULL,
  `category` int(1) NOT NULL COMMENT '1:动态，2：公告，3：其他',
  `content` text NOT NULL,
  `imgs` char(120) NOT NULL,
  `showtime` int(11) NOT NULL,
  `status` varchar(1) NOT NULL,
  `agentid` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_artical` */
 DROP TABLE IF EXISTS `vifnn_artical`;/* MySQLReback Separation */ CREATE TABLE `vifnn_artical` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `info` varchar(5000) NOT NULL,
  `img` char(200) NOT NULL,
  `status` varchar(1) NOT NULL,
  `agentid` int(10) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `type` varchar(200) NOT NULL,
  `jianjie` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_article` */
 DROP TABLE IF EXISTS `vifnn_article`;/* MySQLReback Separation */ CREATE TABLE `vifnn_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(90) NOT NULL,
  `description` char(255) NOT NULL,
  `author` varchar(15) NOT NULL,
  `form` varchar(30) NOT NULL,
  `updatetime` varchar(13) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `content` text NOT NULL,
  `imgs` char(40) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_attribute` */
 DROP TABLE IF EXISTS `vifnn_attribute`;/* MySQLReback Separation */ CREATE TABLE `vifnn_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(50) NOT NULL,
  `catid` int(10) unsigned NOT NULL COMMENT '分类ID',
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`,`catid`),
  KEY `catid` (`catid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_auction` */
 DROP TABLE IF EXISTS `vifnn_auction`;/* MySQLReback Separation */ CREATE TABLE `vifnn_auction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `wxpic` varchar(200) NOT NULL,
  `wxtitle` varchar(100) NOT NULL,
  `wxinfo` text,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `info` text NOT NULL,
  `logo` varchar(200) NOT NULL,
  `startprice` int(11) NOT NULL DEFAULT '0',
  `addprice` int(11) NOT NULL DEFAULT '0',
  `fixedprice` int(11) NOT NULL DEFAULT '0',
  `referenceprice` int(11) NOT NULL DEFAULT '0',
  `is_attention` int(11) NOT NULL DEFAULT '0',
  `is_reg` int(11) NOT NULL DEFAULT '0',
  `is_open` int(11) NOT NULL DEFAULT '0',
  `is_del` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `pv` int(11) NOT NULL DEFAULT '0',
  `like_num` int(11) NOT NULL DEFAULT '0',
  `share_num` int(11) NOT NULL DEFAULT '0',
  `postage` int(11) NOT NULL DEFAULT '0',
  `settime` int(11) NOT NULL,
  `nobuytime` int(11) NOT NULL DEFAULT '48',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `keyword` (`keyword`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_auction_order` */
 DROP TABLE IF EXISTS `vifnn_auction_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_auction_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `orderid` varchar(30) NOT NULL DEFAULT '0',
  `paid` int(11) NOT NULL DEFAULT '0',
  `transactionid` varchar(150) DEFAULT NULL,
  `paytype` varchar(30) DEFAULT NULL,
  `price` varchar(100) NOT NULL,
  `third_id` varchar(100) DEFAULT NULL,
  `auctionid` int(11) NOT NULL,
  `topriceid` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `address` varchar(300) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `expressnum` varchar(100) DEFAULT NULL,
  `expressname` varchar(100) DEFAULT NULL,
  `thirdpay` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `auctionid` (`auctionid`) USING BTREE,
  KEY `topriceid` (`topriceid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_auction_pic` */
 DROP TABLE IF EXISTS `vifnn_auction_pic`;/* MySQLReback Separation */ CREATE TABLE `vifnn_auction_pic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `pic` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_auction_toprice` */
 DROP TABLE IF EXISTS `vifnn_auction_toprice`;/* MySQLReback Separation */ CREATE TABLE `vifnn_auction_toprice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_autumns_box` */
 DROP TABLE IF EXISTS `vifnn_autumns_box`;/* MySQLReback Separation */ CREATE TABLE `vifnn_autumns_box` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `bid` int(11) NOT NULL COMMENT '活动ID',
  `invite` int(11) NOT NULL,
  `boxdate` int(11) NOT NULL COMMENT '领取盒子的时间',
  `box` int(11) NOT NULL COMMENT '盒子样式',
  `wecha_id` varchar(60) NOT NULL,
  `prize` varchar(100) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `isprize` int(11) NOT NULL COMMENT '是否中奖',
  `isprizes` int(11) NOT NULL COMMENT '是否领奖',
  `prizedate` varchar(20) NOT NULL COMMENT '兑奖起始时间',
  `prizedates` varchar(20) NOT NULL COMMENT '兑奖结束时间',
  `lvprize` varchar(30) NOT NULL,
  `sn` varchar(13) NOT NULL,
  `sntime` int(11) NOT NULL COMMENT '奖品发放时间',
  `sendstutas` int(11) NOT NULL DEFAULT '0' COMMENT '是否发奖',
  `prtime` int(11) NOT NULL COMMENT '领奖时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_autumns_ip` */
 DROP TABLE IF EXISTS `vifnn_autumns_ip`;/* MySQLReback Separation */ CREATE TABLE `vifnn_autumns_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL,
  `bid` int(11) NOT NULL COMMENT '盒子ID',
  `lid` int(11) NOT NULL COMMENT '活动ID',
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_autumns_open` */
 DROP TABLE IF EXISTS `vifnn_autumns_open`;/* MySQLReback Separation */ CREATE TABLE `vifnn_autumns_open` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `bid` int(11) NOT NULL COMMENT '盒子ID',
  `lid` int(11) NOT NULL COMMENT '活动ID',
  `time` int(11) NOT NULL COMMENT '分享次数',
  `isopen` int(11) NOT NULL COMMENT '是否打开',
  `wecha_id` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_banners` */
 DROP TABLE IF EXISTS `vifnn_banners`;/* MySQLReback Separation */ CREATE TABLE `vifnn_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` char(200) NOT NULL,
  `url` char(255) NOT NULL,
  `status` varchar(1) NOT NULL,
  `agentid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_baoming` */
 DROP TABLE IF EXISTS `vifnn_baoming`;/* MySQLReback Separation */ CREATE TABLE `vifnn_baoming` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `info` varchar(500) DEFAULT NULL COMMENT '公司简介',
  `title` text NOT NULL,
  `jianjie` text NOT NULL,
  `tp` char(255) NOT NULL,
  `logo` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_baoming_list` */
 DROP TABLE IF EXISTS `vifnn_baoming_list`;/* MySQLReback Separation */ CREATE TABLE `vifnn_baoming_list` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `zhuti` varchar(100) NOT NULL,
  `feiyong` text,
  `time` text,
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `banner` varchar(200) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_baoming_order` */
 DROP TABLE IF EXISTS `vifnn_baoming_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_baoming_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(64) NOT NULL,
  `wecha_id` varchar(64) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `weixin` varchar(100) NOT NULL,
  `beizhu` text NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_bargain` */
 DROP TABLE IF EXISTS `vifnn_bargain`;/* MySQLReback Separation */ CREATE TABLE `vifnn_bargain` (
  `vifnn_id` int(100) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '商品名称',
  `keyword` varchar(100) NOT NULL COMMENT '关键词',
  `wxtitle` varchar(100) NOT NULL COMMENT '图文回复标题',
  `wxpic` varchar(100) NOT NULL COMMENT '图文回复图片',
  `wxinfo` varchar(200) DEFAULT NULL COMMENT '图文回复简单描述',
  `logoimg1` varchar(100) NOT NULL COMMENT '商品图片1',
  `logourl1` varchar(200) DEFAULT NULL COMMENT '商品图片链接1',
  `logoimg2` varchar(100) DEFAULT NULL COMMENT '商品图片2',
  `logourl2` varchar(200) DEFAULT NULL COMMENT '商品图片链接2',
  `logoimg3` varchar(100) DEFAULT NULL COMMENT '商品图片3',
  `logourl3` varchar(200) DEFAULT NULL COMMENT '商品图片链接3',
  `info` mediumtext COMMENT '商品描述',
  `guize` mediumtext,
  `original` int(20) NOT NULL COMMENT '原价',
  `minimum` int(20) NOT NULL COMMENT '底价',
  `starttime` int(20) NOT NULL COMMENT '开始时间',
  `inventory` int(20) NOT NULL COMMENT '库存',
  `qdao` int(11) DEFAULT NULL COMMENT '前n刀',
  `qprice` int(20) DEFAULT NULL COMMENT '前n刀砍去多少钱',
  `dao` int(11) DEFAULT NULL COMMENT '总共需要n刀',
  `pv` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '开始-关闭',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `is_new` int(11) NOT NULL DEFAULT '1',
  `kan_min` int(20) NOT NULL DEFAULT '0',
  `kan_max` int(20) NOT NULL DEFAULT '0',
  `rank_num` int(11) NOT NULL DEFAULT '10',
  `logotitle1` varchar(50) DEFAULT NULL,
  `logotitle2` varchar(50) DEFAULT NULL,
  `logotitle3` varchar(50) DEFAULT NULL,
  `is_attention` int(10) unsigned NOT NULL DEFAULT '1',
  `is_reg` int(10) unsigned NOT NULL DEFAULT '1',
  `is_subhelp` int(10) unsigned NOT NULL DEFAULT '1',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sku_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`vifnn_id`),
  KEY `token` (`token`) USING BTREE,
  KEY `name` (`name`) USING BTREE,
  KEY `state` (`state`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_bargain_kanuser` */
 DROP TABLE IF EXISTS `vifnn_bargain_kanuser`;/* MySQLReback Separation */ CREATE TABLE `vifnn_bargain_kanuser` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `bargain_id` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `friend` varchar(100) NOT NULL,
  `dao` int(20) NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`vifnn_id`),
  KEY `token` (`token`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `bargain_id` (`bargain_id`) USING BTREE,
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `friend` (`friend`) USING BTREE,
  KEY `token_2` (`token`,`bargain_id`,`orderid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_bargain_order` */
 DROP TABLE IF EXISTS `vifnn_bargain_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_bargain_order` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `paytype` varchar(50) DEFAULT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `third_id` varchar(100) DEFAULT NULL,
  `bargain_id` int(11) NOT NULL,
  `bargain_name` varchar(100) DEFAULT NULL,
  `bargain_logoimg` varchar(100) DEFAULT NULL,
  `bargain_original` varchar(20) DEFAULT NULL,
  `bargain_minimum` varchar(20) DEFAULT NULL,
  `bargain_nowprice` int(20) DEFAULT NULL,
  `price` varchar(20) DEFAULT NULL,
  `endtime` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) DEFAULT NULL,
  `orderid` varchar(255) NOT NULL,
  `state2` int(1) NOT NULL DEFAULT '0',
  `logintime` int(11) NOT NULL DEFAULT '0',
  `changetime` int(11) NOT NULL DEFAULT '0',
  `expressnum` varchar(100) DEFAULT NULL,
  `expressname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`vifnn_id`),
  KEY `token` (`token`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `paid` (`paid`) USING BTREE,
  KEY `bargain_id` (`bargain_id`) USING BTREE,
  KEY `token_2` (`token`,`wecha_id`,`bargain_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_behavior` */
 DROP TABLE IF EXISTS `vifnn_behavior`;/* MySQLReback Separation */ CREATE TABLE `vifnn_behavior` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `openid` varchar(60) NOT NULL,
  `date` varchar(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `model` varchar(60) NOT NULL,
  `num` int(11) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`),
  KEY `IDX_TOKEN_NUM` (`token`,`num`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_behavior` */
 INSERT INTO `vifnn_behavior` VALUES ('1','0','ezrehr1458789061','opJiSw6wdBEB5QzJ-lgRixw50p5o','2016-03-30','1459333609','home','1','首页','1'),('2','2','ezrehr1458789061','opJiSw6wdBEB5QzJ-lgRixw50p5o','2016-03-30','1459338385','Micrstore','7','用户关注','0'),('3','0','ezrehr1458789061','opJiSw6wdBEB5QzJ-lgRixw50p5o','2016-03-30','1459342332','chat','4','我的海报','0'),('4','0','ezrehr1458789061','opJiSw6wdBEB5QzJ-lgRixw50p5o','2016-03-30','1459342400','home','1',' 首页','1'),('5','0','ezrehr1458789061','opJiSw6wdBEB5QzJ-lgRixw50p5o','2016-03-30','1459343690','chat','1','qrcode','0'),('6','2','ezrehr1458789061','opJiSw6wdBEB5QzJ-lgRixw50p5o','2016-03-31','1459395866','Micrstore','6','用户关注','0'),('7','0','ezrehr1458789061','opJiSw_cuETnHqum0vB0_1tg8F1Q','2016-03-31','1459403724','chat','1','我的海报','0'),('8','2','ezrehr1458789061','opJiSw_cuETnHqum0vB0_1tg8F1Q','2016-03-31','1459403729','Micrstore','2','用户关注','0'),('9','0','ezrehr1458789061','opJiSw6wdBEB5QzJ-lgRixw50p5o','2016-03-31','1459406965','chat','2','我的海报','0'),('10','0','zbemii1462891866','o4xIKt6khyG50BfQy8QuZ3iAsRTY','2016-05-10','1462895119','help','1','帮助','1'),('11','3','zbemii1462891866','o4xIKt1UdTsU6VAl_O12XjHcsIj0','2016-05-11','1462912200','Micrstore','1','用户关注','0'),('12','3','zbemii1462891866','o4xIKt_XtAoToHq1DOBj5hlC3MDM','2016-05-13','1463076607','Micrstore','1','用户关注','0'),('13','3','zbemii1462891866','o4xIKt9CxvF0GgDcC_dgLdULeWFQ','2016-05-14','1463206124','Micrstore','1','用户关注','0'),('14','3','zbemii1462891866','o4xIKt9E88SQR1uKlC_ZSrpyDP7c','2016-05-14','1463224212','Micrstore','1','用户关注','0'),('15','3','zbemii1462891866','o4xIKt5OA0yRNqATZHYFEBuFkcug','2016-05-16','1463376212','Micrstore','1','用户关注','0'),('16','3','zbemii1462891866','o4xIKt9EK8N26tW_is7jsb48AYPM','2016-05-17','1463443353','Micrstore','1','用户关注','0'),('17','3','zbemii1462891866','o4xIKt3HUWJ3N5r8rqkZY7yK1kyA','2016-05-17','1463451789','Micrstore','1','用户关注','0'),('18','3','zbemii1462891866','o4xIKt_KSjRLRKosyZC81Ztpl6mY','2016-05-17','1463471930','Micrstore','1','用户关注','0'),('19','3','zbemii1462891866','o4xIKt4SRUCVIKyw5wLRJXU-6RFc','2016-05-17','1463498192','Micrstore','1','用户关注','0'),('20','3','zbemii1462891866','o4xIKt2iLt9xORh7cocUSn3TeDzw','2016-05-18','1463582239','Micrstore','1','用户关注','0'),('21','3','zbemii1462891866','o4xIKt409CN7IYEdQfocohe5eYQQ','2016-05-19','1463619536','Micrstore','1','用户关注','0'),('22','3','zbemii1462891866','o4xIKt4mJ76am75xIuAPEIN830Fs','2016-05-19','1463620021','Micrstore','1','用户关注','0'),('23','3','zbemii1462891866','o4xIKt-iRgPmZBGYzZzqnH-03_2I','2016-05-19','1463638019','Micrstore','1','用户关注','0'),('24','3','zbemii1462891866','o4xIKt-yVbuLaH_wFNVZd7dJAf1U','2016-05-19','1463664987','Micrstore','1','用户关注','0'),('25','4','ywxgfh1465574691','','2016-06-11','1465646163','LivingCircle','1','用户关注','0'),('26','4','ywxgfh1465574691','','2016-06-12','1465712747','LivingCircle','1','用户关注','0'),('27','4','ywxgfh1465574691','','2016-06-13','1465785502','LivingCircle','1','用户关注','0'),('28','4','ywxgfh1465574691','','2016-06-13','1465788440','LivingCircle','1','用户关注','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_blacklist` */
 DROP TABLE IF EXISTS `vifnn_blacklist`;/* MySQLReback Separation */ CREATE TABLE `vifnn_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(90) NOT NULL,
  `ips` text NOT NULL COMMENT 'ip列表逗号分隔',
  `post` varchar(300) NOT NULL DEFAULT '' COMMENT 'post参数',
  `url` varchar(300) NOT NULL DEFAULT '' COMMENT '限制数据',
  `remark` varchar(300) NOT NULL DEFAULT '' COMMENT '备注',
  `limit_start` int(11) DEFAULT NULL COMMENT '开始时间',
  `limit_end` int(11) DEFAULT NULL COMMENT '结束时间',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_boost_convert` */
 DROP TABLE IF EXISTS `vifnn_boost_convert`;/* MySQLReback Separation */ CREATE TABLE `vifnn_boost_convert` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `boost_id` int(11) NOT NULL,
  `u_boost_id` int(11) NOT NULL,
  `fans_id` int(11) DEFAULT NULL,
  `convert_code` varchar(255) NOT NULL,
  `awards_level` tinyint(9) NOT NULL COMMENT '奖品级别',
  `is_use` tinyint(2) DEFAULT '0' COMMENT '0：未使用；1：已使用',
  `has_send` varchar(255) DEFAULT '0' COMMENT '微信红包已经发送的金额',
  `convert_time` timestamp NULL DEFAULT NULL COMMENT '兑换时间',
  `create_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `UID` (`uid`) USING BTREE,
  KEY `GAME_ID` (`boost_id`) USING BTREE,
  KEY `UGAME_ID` (`u_boost_id`) USING BTREE,
  KEY `FANS_ID` (`fans_id`) USING BTREE,
  KEY `IDX_GAME_CONVERT` (`uid`,`boost_id`,`u_boost_id`,`fans_id`) USING BTREE,
  KEY `CONVERT_CODE` (`convert_code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_boost_records` */
 DROP TABLE IF EXISTS `vifnn_boost_records`;/* MySQLReback Separation */ CREATE TABLE `vifnn_boost_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boostid` int(11) NOT NULL DEFAULT '0',
  `token` varchar(30) NOT NULL DEFAULT '',
  `wecha_id` varchar(50) NOT NULL DEFAULT '',
  `score` float(11,2) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL,
  `u_boost_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gameid` (`boostid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_boosts` */
 DROP TABLE IF EXISTS `vifnn_boosts`;/* MySQLReback Separation */ CREATE TABLE `vifnn_boosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(40) NOT NULL DEFAULT '',
  `boostid` int(11) NOT NULL DEFAULT '0',
  `picurl` varchar(160) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(60) NOT NULL DEFAULT '',
  `intro` varchar(500) NOT NULL DEFAULT '',
  `selfinfo` varchar(5000) NOT NULL DEFAULT '',
  `token` varchar(20) NOT NULL DEFAULT '',
  `playcount` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `limit_num` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `is_share` tinyint(1) NOT NULL,
  `share_value` varchar(255) NOT NULL COMMENT '序列化',
  `share_callback` tinyint(2) NOT NULL DEFAULT '0' COMMENT '分享回调类型0:再玩一次,1:红包,2:卡券,3:积分',
  `is_release` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0：未发布；1： 已发布；',
  PRIMARY KEY (`id`),
  KEY `TOKEN` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_broker` */
 DROP TABLE IF EXISTS `vifnn_broker`;/* MySQLReback Separation */ CREATE TABLE `vifnn_broker` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `keyword` varchar(20) NOT NULL,
  `token` varchar(50) NOT NULL,
  `picurl` varchar(250) NOT NULL,
  `imgreply` varchar(250) NOT NULL COMMENT '消息回复图片',
  `invitecode` char(16) NOT NULL,
  `statdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `ruledesc` text NOT NULL,
  `registration` text NOT NULL,
  `isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1表示已删除',
  `addtime` int(11) NOT NULL,
  `uptime` int(11) NOT NULL COMMENT '更新时间',
  `bgimg` varchar(250) NOT NULL COMMENT '背景图片',
  `rinfo` varchar(500) NOT NULL COMMENT '消息回复简介',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_broker_client` */
 DROP TABLE IF EXISTS `vifnn_broker_client`;/* MySQLReback Separation */ CREATE TABLE `vifnn_broker_client` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `bid` int(10) unsigned NOT NULL,
  `tjuid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推挤人id 推挤人idbroker_user表id',
  `verifyuid` int(11) NOT NULL DEFAULT '0' COMMENT '顾问id 推挤人idbroker_user表id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `cname` varchar(90) NOT NULL COMMENT '客户名称',
  `ctel` varchar(15) NOT NULL COMMENT '客户手机号',
  `proid` int(11) NOT NULL DEFAULT '0' COMMENT 'broker_item表id',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `uptime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `wecha_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_broker_commission` */
 DROP TABLE IF EXISTS `vifnn_broker_commission`;/* MySQLReback Separation */ CREATE TABLE `vifnn_broker_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned NOT NULL,
  `tjuid` int(11) unsigned NOT NULL,
  `tjname` varchar(100) NOT NULL,
  `clientid` int(11) unsigned NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_tel` varchar(20) NOT NULL,
  `client_status` tinyint(1) unsigned NOT NULL COMMENT '客户目前状态',
  `proid` int(11) unsigned NOT NULL,
  `proname` varchar(255) NOT NULL,
  `verifyname` varchar(100) NOT NULL COMMENT '置业顾问名字',
  `verifytel` varchar(20) NOT NULL COMMENT '置业顾问电话',
  `money` decimal(10,2) unsigned NOT NULL COMMENT '金额',
  `status` tinyint(1) unsigned NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_broker_item` */
 DROP TABLE IF EXISTS `vifnn_broker_item`;/* MySQLReback Separation */ CREATE TABLE `vifnn_broker_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL COMMENT 'broker表id',
  `xmname` varchar(100) NOT NULL,
  `xmtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1现金2百分比',
  `xmnum` varchar(20) NOT NULL COMMENT '佣金额度',
  `xmimg` varchar(300) NOT NULL COMMENT '图片url',
  `tourl` varchar(300) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL COMMENT '跳转地址url',
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_broker_optionlog` */
 DROP TABLE IF EXISTS `vifnn_broker_optionlog`;/* MySQLReback Separation */ CREATE TABLE `vifnn_broker_optionlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `bid` int(10) unsigned NOT NULL,
  `tjuid` int(11) NOT NULL COMMENT '推荐人',
  `logstr` varchar(100) NOT NULL,
  `addtime` int(11) NOT NULL,
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_broker_translation` */
 DROP TABLE IF EXISTS `vifnn_broker_translation`;/* MySQLReback Separation */ CREATE TABLE `vifnn_broker_translation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(30) NOT NULL COMMENT '身份介绍',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0普通1经纪人2其他',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_broker_translation` */
 INSERT INTO `vifnn_broker_translation` VALUES ('1','公司员工','0'),('2','大众经纪人','0'),('3','中介公司','0'),('4','代理公司','0'),('5','合作伙伴','0'),('6','老业主','1'),('7','产品顾问','2');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_broker_user` */
 DROP TABLE IF EXISTS `vifnn_broker_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_broker_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `bid` int(11) NOT NULL,
  `tel` varchar(12) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pwd` varchar(100) NOT NULL,
  `identity` tinyint(1) unsigned NOT NULL COMMENT 'broker_translation表id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0正常1禁用',
  `is_verify` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否置业顾问',
  `identitylog` varchar(255) NOT NULL COMMENT '身份变更记录',
  `identitycode` varchar(20) NOT NULL COMMENT '身份证号',
  `company` varchar(255) NOT NULL COMMENT '公司名称',
  `recommendnum` int(10) unsigned NOT NULL COMMENT '推荐人数',
  `totalcash` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可提取佣金',
  `extractcash` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '以提取出的佣金',
  `bank_truename` varchar(30) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL COMMENT '银行开户姓名',
  `bank_cardnum` varchar(20) NOT NULL COMMENT '银行卡号',
  `bank_name` varchar(60) NOT NULL COMMENT '银行名称',
  `wecha_id` varchar(100) NOT NULL COMMENT 'openid',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_busines` */
 DROP TABLE IF EXISTS `vifnn_busines`;/* MySQLReback Separation */ CREATE TABLE `vifnn_busines` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL DEFAULT '',
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `mtitle` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `picurl` varchar(200) NOT NULL DEFAULT '',
  `album_id` int(11) NOT NULL,
  `toppicurl` varchar(200) NOT NULL DEFAULT '',
  `roompicurl` varchar(200) NOT NULL DEFAULT '',
  `address` varchar(50) NOT NULL DEFAULT '',
  `longitude` char(11) NOT NULL DEFAULT '',
  `latitude` char(11) NOT NULL DEFAULT '',
  `business_desc` text NOT NULL,
  `type` char(15) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL,
  `blogo` varchar(200) NOT NULL,
  `businesphone` char(13) NOT NULL DEFAULT '',
  `orderInfo` varchar(800) NOT NULL DEFAULT '',
  `compyphone` char(50) NOT NULL DEFAULT '',
  `path` varchar(3000) DEFAULT '0',
  `tpid` int(11) DEFAULT '36',
  `conttpid` int(11) DEFAULT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_busines_comment` */
 DROP TABLE IF EXISTS `vifnn_busines_comment`;/* MySQLReback Separation */ CREATE TABLE `vifnn_busines_comment` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `type` char(15) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `position` varchar(50) NOT NULL DEFAULT '',
  `face_picurl` varchar(200) NOT NULL,
  `face_desc` varchar(1000) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL,
  `bid_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_busines_main` */
 DROP TABLE IF EXISTS `vifnn_busines_main`;/* MySQLReback Separation */ CREATE TABLE `vifnn_busines_main` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `bid_id` int(11) NOT NULL,
  `name` char(50) NOT NULL,
  `sort` int(11) NOT NULL,
  `main_desc` text NOT NULL,
  `type` char(15) NOT NULL,
  `telphone` char(12) NOT NULL DEFAULT '',
  `maddress` varchar(50) NOT NULL DEFAULT '',
  `desc_pic` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_busines_pic` */
 DROP TABLE IF EXISTS `vifnn_busines_pic`;/* MySQLReback Separation */ CREATE TABLE `vifnn_busines_pic` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `bid_id` int(11) NOT NULL,
  `picurl_1` varchar(200) NOT NULL DEFAULT '',
  `picurl_2` varchar(200) NOT NULL DEFAULT '',
  `picurl_3` varchar(200) NOT NULL DEFAULT '',
  `picurl_4` varchar(200) NOT NULL DEFAULT '',
  `picurl_5` varchar(200) NOT NULL DEFAULT '',
  `token` varchar(50) NOT NULL,
  `type` char(15) NOT NULL,
  `ablum_id` int(11) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_busines_second` */
 DROP TABLE IF EXISTS `vifnn_busines_second`;/* MySQLReback Separation */ CREATE TABLE `vifnn_busines_second` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `type` char(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mid_id` int(11) NOT NULL,
  `picurl` varchar(200) NOT NULL DEFAULT '',
  `learntime` varchar(100) NOT NULL,
  `datatype` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL,
  `second_desc` text NOT NULL,
  `oneprice` decimal(10,2) DEFAULT '0.00',
  `googsnumber` bigint(20) NOT NULL DEFAULT '0',
  `havenumber` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_car` */
 DROP TABLE IF EXISTS `vifnn_car`;/* MySQLReback Separation */ CREATE TABLE `vifnn_car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `www` varchar(50) NOT NULL DEFAULT '',
  `logo` varchar(200) NOT NULL DEFAULT '',
  `sort` int(11) DEFAULT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_car_utility` */
 DROP TABLE IF EXISTS `vifnn_car_utility`;/* MySQLReback Separation */ CREATE TABLE `vifnn_car_utility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `token` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_carcat` */
 DROP TABLE IF EXISTS `vifnn_carcat`;/* MySQLReback Separation */ CREATE TABLE `vifnn_carcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `name` char(50) NOT NULL,
  `img` char(150) NOT NULL,
  `icon` char(150) NOT NULL,
  `sort` int(11) NOT NULL,
  `is_show` int(11) NOT NULL,
  `url` varchar(300) NOT NULL,
  `system` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cards` */
 DROP TABLE IF EXISTS `vifnn_cards`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cardid` int(11) NOT NULL DEFAULT '0',
  `picurl` varchar(160) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(60) NOT NULL DEFAULT '',
  `intro` varchar(500) NOT NULL DEFAULT '',
  `selfinfo` varchar(5000) NOT NULL DEFAULT '',
  `token` varchar(20) NOT NULL DEFAULT '',
  `viewcount` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_carmodel` */
 DROP TABLE IF EXISTS `vifnn_carmodel`;/* MySQLReback Separation */ CREATE TABLE `vifnn_carmodel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `brand_serise` varchar(50) NOT NULL,
  `model_year` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `panorama_id` int(11) DEFAULT '0',
  `guide_price` varchar(50) NOT NULL DEFAULT '',
  `dealer_price` varchar(50) NOT NULL DEFAULT '',
  `emission` double NOT NULL,
  `stalls` tinyint(4) DEFAULT NULL,
  `box` tinyint(4) NOT NULL,
  `pic_url` varchar(200) NOT NULL,
  `s_id` int(11) NOT NULL,
  `type` tinyint(3) NOT NULL DEFAULT '1',
  `b_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_carnews` */
 DROP TABLE IF EXISTS `vifnn_carnews`;/* MySQLReback Separation */ CREATE TABLE `vifnn_carnews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `news_id` int(11) NOT NULL,
  `pre_id` int(11) NOT NULL,
  `usedcar_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_carowner` */
 DROP TABLE IF EXISTS `vifnn_carowner`;/* MySQLReback Separation */ CREATE TABLE `vifnn_carowner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '''''',
  `head_url` varchar(200) NOT NULL DEFAULT '''''',
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_carsaler` */
 DROP TABLE IF EXISTS `vifnn_carsaler`;/* MySQLReback Separation */ CREATE TABLE `vifnn_carsaler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `picture` varchar(200) NOT NULL,
  `mobile` char(13) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `salestype` tinyint(4) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_carseries` */
 DROP TABLE IF EXISTS `vifnn_carseries`;/* MySQLReback Separation */ CREATE TABLE `vifnn_carseries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `token` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `shortname` varchar(50) NOT NULL,
  `picture` varchar(200) NOT NULL,
  `sort` int(11) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_carset` */
 DROP TABLE IF EXISTS `vifnn_carset`;/* MySQLReback Separation */ CREATE TABLE `vifnn_carset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `head_url` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL,
  `title1` varchar(50) NOT NULL DEFAULT '',
  `title2` varchar(50) NOT NULL DEFAULT '',
  `title3` varchar(50) NOT NULL DEFAULT '',
  `title4` varchar(50) NOT NULL DEFAULT '',
  `title5` varchar(50) NOT NULL DEFAULT '',
  `title6` varchar(50) NOT NULL DEFAULT '',
  `head_url_1` varchar(200) NOT NULL DEFAULT '',
  `head_url_2` varchar(200) NOT NULL DEFAULT '',
  `head_url_3` varchar(200) NOT NULL DEFAULT '',
  `head_url_4` varchar(200) NOT NULL DEFAULT '',
  `head_url_5` varchar(200) NOT NULL DEFAULT '',
  `head_url_6` varchar(200) NOT NULL DEFAULT '',
  `url1` varchar(200) NOT NULL DEFAULT '',
  `url2` varchar(200) NOT NULL DEFAULT '',
  `url3` varchar(200) NOT NULL DEFAULT '',
  `url4` varchar(200) NOT NULL DEFAULT '',
  `url5` varchar(200) NOT NULL DEFAULT '',
  `url6` varchar(200) NOT NULL DEFAULT '',
  `path` varchar(3000) DEFAULT '0',
  `tpid` int(11) DEFAULT '23',
  `conttpid` int(11) DEFAULT NULL,
  `title7` varchar(50) DEFAULT NULL,
  `title8` varchar(50) DEFAULT NULL,
  `title9` varchar(50) DEFAULT NULL,
  `title10` varchar(50) DEFAULT NULL,
  `title11` varchar(50) DEFAULT NULL,
  `head_url_7` varchar(200) DEFAULT NULL,
  `head_url_8` varchar(200) DEFAULT NULL,
  `head_url_9` varchar(200) DEFAULT NULL,
  `head_url_10` varchar(200) DEFAULT NULL,
  `head_url_11` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_caruser` */
 DROP TABLE IF EXISTS `vifnn_caruser`;/* MySQLReback Separation */ CREATE TABLE `vifnn_caruser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  `brand_serise` varchar(50) NOT NULL,
  `car_no` varchar(20) NOT NULL,
  `car_userName` varchar(50) NOT NULL,
  `car_startTime` varchar(50) NOT NULL,
  `car_insurance_lastDate` varchar(50) NOT NULL,
  `car_insurance_lastCost` decimal(10,2) NOT NULL,
  `car_care_mileage` int(11) NOT NULL,
  `user_tel` char(11) NOT NULL,
  `car_care_lastDate` varchar(50) NOT NULL,
  `car_care_lastCost` decimal(10,2) NOT NULL,
  `kfinfo` varchar(200) NOT NULL DEFAULT '',
  `insurance_Date` varchar(50) DEFAULT NULL,
  `insurance_Cost` decimal(10,2) DEFAULT NULL,
  `care_mileage` int(11) DEFAULT NULL,
  `car_care_Date` varchar(50) DEFAULT NULL,
  `car_care_Cost` decimal(10,2) DEFAULT NULL,
  `car_buyTime` varchar(50) NOT NULL DEFAULT '',
  `car_care_inspection` varchar(50) NOT NULL DEFAULT '',
  `care_next_mileage` int(11) NOT NULL DEFAULT '0',
  `next_care_inspection` varchar(50) NOT NULL DEFAULT '',
  `next_insurance_Date` varchar(50) NOT NULL DEFAULT '',
  `carmodel` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_case` */
 DROP TABLE IF EXISTS `vifnn_case`;/* MySQLReback Separation */ CREATE TABLE `vifnn_case` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `url` char(255) NOT NULL,
  `img` char(200) NOT NULL,
  `status` varchar(1) NOT NULL,
  `agentid` int(10) NOT NULL DEFAULT '0',
  `timg` char(200) NOT NULL,
  `classid` varchar(200) NOT NULL,
  `class` int(11) NOT NULL,
  `img_two` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agentid` (`agentid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_caseclass` */
 DROP TABLE IF EXISTS `vifnn_caseclass`;/* MySQLReback Separation */ CREATE TABLE `vifnn_caseclass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `counts` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashbonus` */
 DROP TABLE IF EXISTS `vifnn_cashbonus`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashbonus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `title` char(40) NOT NULL,
  `keyword` char(30) NOT NULL,
  `msg_pic` char(120) NOT NULL,
  `desc` varchar(200) NOT NULL,
  `info` text NOT NULL,
  `wishing` text NOT NULL,
  `act_name` text NOT NULL,
  `remark` text NOT NULL,
  `send_name` text NOT NULL,
  `start_time` char(11) NOT NULL,
  `end_time` char(11) NOT NULL,
  `ext_total` mediumint(8) unsigned NOT NULL,
  `get_number` smallint(5) unsigned NOT NULL,
  `is_open` enum('0','1') NOT NULL,
  `item_num` mediumint(9) NOT NULL,
  `item_sum` mediumint(9) NOT NULL,
  `item_max` mediumint(9) NOT NULL,
  `item_unit` varchar(30) NOT NULL,
  `type` enum('1','2') NOT NULL,
  `is_sub` int(11) NOT NULL,
  `deci` smallint(6) NOT NULL,
  `people` mediumint(9) NOT NULL,
  `model` char(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashbonus_log` */
 DROP TABLE IF EXISTS `vifnn_cashbonus_log`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashbonus_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `price` char(100) NOT NULL,
  `add_time` char(11) NOT NULL,
  `status` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_activity` */
 DROP TABLE IF EXISTS `vifnn_cashier_activity`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '活动名称',
  `pic` varchar(300) NOT NULL COMMENT '活动图片',
  `table_name` varchar(50) NOT NULL COMMENT '活动来自的表名',
  `activity_id` int(10) unsigned NOT NULL,
  `price` varchar(50) NOT NULL,
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '抽奖类型',
  `original_price` varchar(50) NOT NULL COMMENT '原价',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `activity_id` (`activity_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_adminuser` */
 DROP TABLE IF EXISTS `vifnn_cashier_adminuser`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_adminuser` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `account` varchar(100) NOT NULL,
  `pwd` varchar(35) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `lastlogintime` int(10) unsigned NOT NULL,
  `phone` varchar(15) NOT NULL,
  `isproxy` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0总帐户1代理商2其他账户',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1启用',
  `lxname` varchar(30) NOT NULL COMMENT '联系人',
  `lxcompany` varchar(150) NOT NULL COMMENT '公司',
  `lxaddress` varchar(250) NOT NULL COMMENT '联系地址',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `mid` (`mid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_attention` */
 DROP TABLE IF EXISTS `vifnn_cashier_attention`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_attention` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ucid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ucenter表id',
  `openid` varchar(100) NOT NULL,
  `mid` int(10) unsigned NOT NULL DEFAULT '0',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_bank` */
 DROP TABLE IF EXISTS `vifnn_cashier_bank`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_bank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `phone` varchar(20) NOT NULL,
  `banktruename` varchar(30) NOT NULL,
  `bankname` varchar(100) NOT NULL,
  `identitycode` varchar(30) NOT NULL,
  `bankcardnum` varchar(30) NOT NULL,
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_banner` */
 DROP TABLE IF EXISTS `vifnn_cashier_banner`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `pic` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `sort` smallint(3) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_card_bonus` */
 DROP TABLE IF EXISTS `vifnn_cashier_card_bonus`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_card_bonus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `card_id` varchar(100) NOT NULL,
  `add_bonus` int(11) NOT NULL DEFAULT '0',
  `record_bonus` varchar(200) NOT NULL,
  `openid` varchar(80) NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `fromid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '来源（0：本地，1：营销系统）',
  PRIMARY KEY (`id`),
  KEY `code` (`code`) USING BTREE,
  KEY `card_id` (`card_id`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_employee` */
 DROP TABLE IF EXISTS `vifnn_cashier_employee`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_employee` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `username` char(50) NOT NULL,
  `account` char(100) NOT NULL,
  `password` char(32) NOT NULL,
  `email` char(200) NOT NULL,
  `salt` char(20) NOT NULL,
  `authority` text,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `phone` varchar(15) NOT NULL,
  `thirdstoreid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '第三方门店id',
  PRIMARY KEY (`eid`),
  KEY `mid` (`mid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_fans` */
 DROP TABLE IF EXISTS `vifnn_cashier_fans`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `appid` varchar(200) NOT NULL COMMENT '公众号id',
  `openid` varchar(250) NOT NULL COMMENT '公众号对应的公众号openid',
  `cf` varchar(10) NOT NULL DEFAULT 'local' COMMENT '来源',
  `totalfee` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付总额(分)',
  `refund` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '退款金额分',
  `is_subscribe` tinyint(4) NOT NULL COMMENT '1关注',
  `nickname` varchar(250) NOT NULL COMMENT '昵称',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1男2女0未知',
  `province` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `headimgurl` varchar(500) NOT NULL COMMENT '头像',
  `groupid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '微信粉丝分组id',
  `ucid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ucenter表id',
  `buyeraccount` varchar(150) NOT NULL COMMENT '买家账号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_locmbcard` */
 DROP TABLE IF EXISTS `vifnn_cashier_locmbcard`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_locmbcard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `cardname` varchar(60) NOT NULL,
  `miniscore` int(10) NOT NULL DEFAULT '0' COMMENT '卡券领取最低积分',
  `mclogo` varchar(200) NOT NULL,
  `bgstyle` varchar(100) NOT NULL,
  `diybg` varchar(200) NOT NULL,
  `cardinfo` text NOT NULL COMMENT '会员卡说明',
  `tipmsg` varchar(100) NOT NULL,
  `numbercolor` varchar(10) NOT NULL,
  `vipnamecolor` varchar(10) NOT NULL,
  `everyday` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '签到送积分',
  `xreward` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消费送积分',
  `integralrule` text NOT NULL COMMENT '积分规则',
  `welfaretitle` varchar(100) NOT NULL COMMENT '特权福利标题',
  `welfareinfo` text NOT NULL COMMENT '特权福利内容',
  `ischeck` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1短信验证',
  `isdonate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1开启充值赠送',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1已删除',
  PRIMARY KEY (`id`),
  KEY `token` (`mid`) USING BTREE,
  KEY `miniscore` (`miniscore`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_locmbdonate` */
 DROP TABLE IF EXISTS `vifnn_cashier_locmbdonate`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_locmbdonate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL,
  `cdid` int(11) unsigned NOT NULL,
  `minmoney` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `maxmoney` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `donatemoney` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `isopen` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1开启',
  `donateinfo` text NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_locmbgifts` */
 DROP TABLE IF EXISTS `vifnn_cashier_locmbgifts`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_locmbgifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gtitle` varchar(100) NOT NULL COMMENT '标题',
  `gtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0积分1卡券',
  `itemvalue` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '相关值',
  `itemtype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '卡券类型',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `mid` int(10) unsigned NOT NULL,
  `cdid` int(10) unsigned NOT NULL,
  `isopen` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_locmbnotice` */
 DROP TABLE IF EXISTS `vifnn_cashier_locmbnotice`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_locmbnotice` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `cdid` int(10) unsigned NOT NULL DEFAULT '0',
  `ntitle` varchar(100) NOT NULL DEFAULT '',
  `ncontent` text NOT NULL,
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cardid` (`cdid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_locmbnumber` */
 DROP TABLE IF EXISTS `vifnn_cashier_locmbnumber`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_locmbnumber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cdid` int(10) NOT NULL DEFAULT '0',
  `mid` int(10) NOT NULL DEFAULT '0',
  `numstr` varchar(30) NOT NULL COMMENT '卡号序号',
  `openid` varchar(60) NOT NULL,
  `is_bind` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `offNumStr` varchar(30) NOT NULL COMMENT '绑定线下卡号',
  PRIMARY KEY (`id`),
  KEY `cdid` (`cdid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_locmbpayrecord` */
 DROP TABLE IF EXISTS `vifnn_cashier_locmbpayrecord`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_locmbpayrecord` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ordid` int(10) unsigned NOT NULL COMMENT '订单表id',
  `orderdesc` varchar(250) NOT NULL,
  `paytype` varchar(20) NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `paytime` int(10) unsigned NOT NULL DEFAULT '0',
  `paid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `mid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  `eid` int(10) unsigned NOT NULL DEFAULT '0',
  `cdid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_locmbsign` */
 DROP TABLE IF EXISTS `vifnn_cashier_locmbsign`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_locmbsign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `signtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '签到时间',
  `issign` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否签到',
  `scoretype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `expense` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '得到的积分',
  `sellexpense` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消费的积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_locmbsync` */
 DROP TABLE IF EXISTS `vifnn_cashier_locmbsync`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_locmbsync` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `issync` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1同步',
  `wxcdid` varchar(100) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL COMMENT '微信会员卡id',
  `cdid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '本站会员卡id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_locmbuserecord` */
 DROP TABLE IF EXISTS `vifnn_cashier_locmbuserecord`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_locmbuserecord` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  `cat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `expense` decimal(12,2) NOT NULL DEFAULT '0.00',
  `score` int(10) NOT NULL DEFAULT '0',
  `usecount` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '使用次数',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `notes` varchar(300) NOT NULL,
  `eid` int(10) unsigned NOT NULL DEFAULT '0',
  `cdid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_menu` */
 DROP TABLE IF EXISTS `vifnn_cashier_menu`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `icon` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `sort` mediumint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏（0：否，1：是）',
  `ishd` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是不是幻灯片',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE,
  KEY `sort` (`sort`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_merchants` */
 DROP TABLE IF EXISTS `vifnn_cashier_merchants`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_merchants` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(50) DEFAULT NULL,
  `thirduserid` varchar(100) NOT NULL COMMENT '第三方唯一身份ID',
  `password` char(32) DEFAULT NULL,
  `salt` char(50) NOT NULL,
  `wxname` char(210) NOT NULL,
  `weixin` varchar(150) NOT NULL COMMENT '微信号',
  `email` char(100) DEFAULT NULL,
  `logo` char(200) NOT NULL,
  `regTime` int(11) DEFAULT NULL,
  `regIp` char(20) DEFAULT NULL,
  `lastLoginTime` int(11) DEFAULT '0',
  `lastLoginIp` char(20) DEFAULT NULL,
  `source` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `mfypwd` tinyint(1) unsigned NOT NULL COMMENT '1修改过密码',
  `aeskey` varchar(50) NOT NULL COMMENT 'EncodingAESKey',
  `wxtoken` varchar(40) NOT NULL COMMENT 'wxToken',
  `encodetype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '消息加解密方式',
  `isadmin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1是总后台生成账号',
  `phone` varchar(15) NOT NULL,
  `thirdmid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '第三方商家id对接用',
  `agentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '代理商id',
  PRIMARY KEY (`mid`),
  KEY `thirduserid` (`thirduserid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_order` */
 DROP TABLE IF EXISTS `vifnn_cashier_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(100) NOT NULL,
  `mid` int(11) NOT NULL,
  `pay_way` char(50) NOT NULL,
  `pay_type` char(50) NOT NULL,
  `goods_type` char(50) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `goods_name` char(200) NOT NULL,
  `goods_describe` varchar(500) NOT NULL,
  `goods_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `add_time` int(11) NOT NULL,
  `paytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `ispay` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1已支付',
  `truename` varchar(250) NOT NULL,
  `openid` varchar(250) NOT NULL,
  `transaction_id` varchar(250) NOT NULL COMMENT '第三方支付订单号',
  `refund` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1退款中2已退款3失败',
  `refundtext` text NOT NULL COMMENT '退款结果数据',
  `comefrom` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0本地1微信营销 2微店 3 o2o系统',
  `mchtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1特约商户2平台代收商户',
  `pmid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '代理者mid',
  `p_openid` varchar(250) NOT NULL COMMENT 'p_mid对应openid',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `eid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '员工id',
  `ucid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ucenter表id',
  `extrainfo` text NOT NULL COMMENT '额外信息',
  `mprice` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '第三方订单价格',
  `agentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '代理商id',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_pay_cell` */
 DROP TABLE IF EXISTS `vifnn_cashier_pay_cell`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_pay_cell` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(80) NOT NULL,
  `card_id` varchar(250) NOT NULL,
  `code` varchar(50) NOT NULL,
  `orderid` varchar(50) NOT NULL COMMENT '微信订单号',
  `location_name` varchar(100) NOT NULL COMMENT '门店名称',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `fee` decimal(10,2) NOT NULL COMMENT '实付金额',
  `original_fee` decimal(10,2) NOT NULL COMMENT '应付金额',
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `code` (`code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_payconfig` */
 DROP TABLE IF EXISTS `vifnn_cashier_payconfig`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_payconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `isOpen` tinyint(1) NOT NULL DEFAULT '0',
  `configData` varchar(2000) DEFAULT NULL,
  `pfpaymid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '平台代付mid',
  `proxymid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '代理者的mid',
  `wxsubmchid` varchar(30) NOT NULL COMMENT '分配到的子商户号',
  `pfalipaymid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付宝平台待支付',
  `agentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '代理商id',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_payreduce` */
 DROP TABLE IF EXISTS `vifnn_cashier_payreduce`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_payreduce` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `ntitle` varchar(250) NOT NULL COMMENT '标题',
  `fullprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `minprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `maxprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `isopen` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1开启',
  `ptypeali` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1支持',
  `ptypewx` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1支持',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_pfapplyrecord` */
 DROP TABLE IF EXISTS `vifnn_cashier_pfapplyrecord`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_pfapplyrecord` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL DEFAULT '0',
  `atitle` varchar(250) NOT NULL,
  `starttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '申请开始时间',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '申请结束时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `remark` text NOT NULL COMMENT '备注',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_printcfg` */
 DROP TABLE IF EXISTS `vifnn_cashier_printcfg`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_printcfg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cellphone` varchar(15) NOT NULL COMMENT '绑定手机号',
  `terminalnum` varchar(35) NOT NULL COMMENT '终端号',
  `mkey` varchar(80) NOT NULL COMMENT '设备秘钥',
  `pnum` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '打印份数',
  `pformat` text NOT NULL COMMENT '打印格式',
  `isopen` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1开启打印',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_remittance` */
 DROP TABLE IF EXISTS `vifnn_cashier_remittance`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_remittance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `meraccount` varchar(50) NOT NULL,
  `money` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `paytime` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_stores` */
 DROP TABLE IF EXISTS `vifnn_cashier_stores`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_stores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `poi_id` varchar(50) NOT NULL COMMENT '微信门店id',
  `business_name` varchar(250) NOT NULL COMMENT '微信商家名',
  `branch_name` varchar(250) NOT NULL COMMENT '微信门店名',
  `telephone` varchar(25) NOT NULL COMMENT '电话',
  `longitude` varchar(25) NOT NULL,
  `latitude` varchar(25) NOT NULL,
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `avg_price` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(250) NOT NULL COMMENT '地址',
  `photo_list` text NOT NULL COMMENT '图片存储',
  `fsortid` int(10) unsigned NOT NULL DEFAULT '0',
  `sortid` int(10) unsigned NOT NULL DEFAULT '0',
  `circleid` int(10) unsigned NOT NULL DEFAULT '0',
  `cityid` int(10) unsigned NOT NULL DEFAULT '0',
  `provinceid` int(10) unsigned NOT NULL DEFAULT '0',
  `cityname` varchar(100) NOT NULL COMMENT '城市名',
  `provincename` varchar(100) NOT NULL COMMENT '省份名',
  `introduction` text NOT NULL COMMENT '简介',
  `recommend` text NOT NULL COMMENT '推荐说明',
  `special` text NOT NULL COMMENT '特色服务',
  `districtid` int(10) unsigned NOT NULL COMMENT '区id',
  `districtname` varchar(100) NOT NULL COMMENT '区名字',
  `available_state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '门店是否可用状态',
  `offset_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '坐标类型',
  `comefrom` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1微信同步来数据',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `appid` varchar(200) NOT NULL,
  `discount` decimal(3,1) NOT NULL COMMENT '折扣率',
  `least_cost` decimal(8,2) NOT NULL COMMENT '满足的金额',
  `reduce_cost` decimal(8,2) NOT NULL COMMENT '减免的金额',
  `thirdmid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '第三方商家id对接用',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1显示',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE,
  KEY `discount` (`discount`,`reduce_cost`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_ucenter` */
 DROP TABLE IF EXISTS `vifnn_cashier_ucenter`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_ucenter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(50) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1男2女',
  `headimgurl` varchar(250) NOT NULL COMMENT '头像',
  `province` varchar(90) NOT NULL,
  `city` varchar(90) NOT NULL,
  `unionid` varchar(100) NOT NULL COMMENT '微信唯一标识',
  `paymoney` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '消费金额',
  `paycount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付次数',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_userinfo` */
 DROP TABLE IF EXISTS `vifnn_cashier_userinfo`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_userinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `headimgurl` varchar(200) NOT NULL DEFAULT '',
  `total_score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总积分',
  `locwxscore` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '本地卡微信卡积分一致',
  `expensetotal` int(10) unsigned NOT NULL DEFAULT '0',
  `mid` int(10) unsigned NOT NULL,
  `openid` varchar(60) NOT NULL,
  `nickname` varchar(60) NOT NULL,
  `truename` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(11) NOT NULL,
  `bornyear` varchar(4) NOT NULL DEFAULT '',
  `bornmonth` varchar(4) NOT NULL DEFAULT '',
  `bornday` varchar(4) NOT NULL DEFAULT '',
  `qq` varchar(11) NOT NULL DEFAULT '',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1男2女3其他',
  `address` varchar(250) NOT NULL,
  `sign_score` int(11) unsigned NOT NULL,
  `getcardtime` int(10) unsigned NOT NULL,
  `balance` double(10,2) unsigned NOT NULL DEFAULT '0.00',
  `paypass` varchar(32) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `province` varchar(40) DEFAULT NULL,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `regtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `comefrom` varchar(200) DEFAULT NULL,
  `isverify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `continuous` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '连续签到',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_wxcoupon` */
 DROP TABLE IF EXISTS `vifnn_cashier_wxcoupon`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_wxcoupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `card_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `card_title` varchar(250) NOT NULL,
  `card_id` varchar(250) NOT NULL COMMENT '微信卡券ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '卡券状态',
  `isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1删除',
  `begin_timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `end_timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `receivenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取数',
  `consumenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '核销数量',
  `get_limit` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '每人可领几张',
  `kqcontent` text NOT NULL COMMENT '卡券内容',
  `kqexpand` text NOT NULL COMMENT '卡券扩展内容',
  `checktime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核通过时间',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `cardticket` varchar(250) NOT NULL,
  `cardurl` varchar(250) NOT NULL COMMENT ' 二维码图片解析后的地址',
  `is_open_cell` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启买单功能（0：否，1：开启）',
  `activate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '会员卡激活方式（0:字段激活，1：一键激活，2：手动激活）',
  `activate_user_form` text NOT NULL COMMENT '一键激活的表单填写项',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `eid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '员工id',
  `poi_ids` text NOT NULL COMMENT '微信门店ids',
  `appid` varchar(200) NOT NULL,
  `ticket` varchar(200) NOT NULL,
  `store_ids` varchar(250) NOT NULL,
  `istowx` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_wxcoupon_common` */
 DROP TABLE IF EXISTS `vifnn_cashier_wxcoupon_common`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_wxcoupon_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `logurl` varchar(250) NOT NULL,
  `mname` varchar(100) NOT NULL COMMENT '商户名字',
  `wxlogurl` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cashier_wxcoupon_receive` */
 DROP TABLE IF EXISTS `vifnn_cashier_wxcoupon_receive`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cashier_wxcoupon_receive` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(250) NOT NULL COMMENT '领取人openid',
  `give_openId` varchar(250) NOT NULL COMMENT '转赠送方账号openid',
  `cardid` varchar(250) NOT NULL,
  `cardtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '卡券类型',
  `cardtitle` varchar(250) NOT NULL COMMENT '卡券标题',
  `isgivebyfriend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为转赠',
  `cardcode` varchar(100) NOT NULL COMMENT 'code序列号',
  `oldcardcode` varchar(100) NOT NULL COMMENT '转赠前的code序列号',
  `outerid` int(10) unsigned NOT NULL COMMENT 'mid值',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0领取1核销',
  `addtime` int(10) unsigned NOT NULL COMMENT '添加时间',
  `deltime` int(10) unsigned NOT NULL COMMENT '用户删除时间',
  `consumetime` int(10) unsigned NOT NULL COMMENT '消费时间',
  `consumesource` varchar(100) NOT NULL COMMENT '核销来源',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `eid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '员工id',
  `isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1删除',
  `ucid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ucenter表id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_catemenu` */
 DROP TABLE IF EXISTS `vifnn_catemenu`;/* MySQLReback Separation */ CREATE TABLE `vifnn_catemenu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fid` int(10) NOT NULL DEFAULT '0',
  `token` varchar(60) NOT NULL,
  `name` varchar(120) NOT NULL,
  `orderss` varchar(10) NOT NULL DEFAULT '0',
  `picurl` varchar(120) NOT NULL,
  `url` varchar(200) NOT NULL DEFAULT '0',
  `status` varchar(10) NOT NULL,
  `RadioGroup1` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`,`orderss`,`status`) USING BTREE,
  KEY `token_2` (`token`,`orderss`,`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_classify` */
 DROP TABLE IF EXISTS `vifnn_classify`;/* MySQLReback Separation */ CREATE TABLE `vifnn_classify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL,
  `info` varchar(90) NOT NULL COMMENT '分类描述',
  `sorts` int(6) NOT NULL COMMENT '显示顺序',
  `img` char(255) NOT NULL,
  `url` char(255) NOT NULL,
  `status` varchar(1) NOT NULL,
  `token` varchar(30) NOT NULL,
  `path` varchar(3000) DEFAULT '0',
  `tpid` int(10) DEFAULT '1',
  `conttpid` int(10) DEFAULT '1',
  `pc_cat_id` int(11) NOT NULL,
  `pc_web_id` int(11) NOT NULL,
  `sonpagesize` int(2) NOT NULL DEFAULT '5' COMMENT '子类手机每页显示数',
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`),
  KEY `IDX_TO_FI_ID` (`token`,`fid`,`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_classify` */
 INSERT INTO `vifnn_classify` VALUES ('11','0','1','1','1','http://800.vifnn.com/tpl/static/attachment/icon/canyin/canyin_red/1.png','','1','zrtmca1421056092','0','1','1','0','0','5');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cointree` */
 DROP TABLE IF EXISTS `vifnn_cointree`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cointree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_name` varchar(50) NOT NULL DEFAULT '',
  `reply_title` varchar(50) NOT NULL DEFAULT '',
  `reply_content` varchar(200) NOT NULL DEFAULT '',
  `reply_pic` varchar(255) NOT NULL DEFAULT '',
  `action_desc` text NOT NULL,
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `remind_word` varchar(255) NOT NULL DEFAULT '',
  `remind_link` varchar(255) NOT NULL DEFAULT '',
  `totaltimes` int(11) NOT NULL DEFAULT '1',
  `join_number` int(11) NOT NULL,
  `actual_join_number` int(11) NOT NULL,
  `everydaytimes` int(11) NOT NULL DEFAULT '0',
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `usedup_conins` int(11) NOT NULL,
  `gain_conins` int(11) NOT NULL DEFAULT '1',
  `timespan` int(11) NOT NULL,
  `record_nums` int(11) NOT NULL,
  `coinrecord_nums` int(11) NOT NULL,
  `is_limitwin` tinyint(1) NOT NULL DEFAULT '1',
  `is_follow` tinyint(1) NOT NULL DEFAULT '1',
  `is_register` tinyint(1) NOT NULL DEFAULT '1',
  `token` varchar(30) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `fistlucknums` int(11) NOT NULL,
  `secondlucknums` int(11) NOT NULL,
  `thirdlucknums` int(11) NOT NULL,
  `fourlucknums` int(11) NOT NULL,
  `fivelucknums` int(11) NOT NULL,
  `sixlucknums` int(11) NOT NULL,
  `is_amount` tinyint(1) NOT NULL,
  `share_count` int(11) NOT NULL,
  `custom_sharetitle` varchar(255) NOT NULL DEFAULT '',
  `custom_sharedsc` varchar(500) NOT NULL DEFAULT '',
  `sms_verify` tinyint(1) NOT NULL,
  `follow_msg` varchar(255) NOT NULL,
  `follow_btn_msg` varchar(255) NOT NULL,
  `register_msg` varchar(255) NOT NULL,
  `custom_follow_url` varchar(255) NOT NULL,
  `is_once` enum('0','1') NOT NULL DEFAULT '1' COMMENT '首次是否默认赠送金币',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cointree_prize` */
 DROP TABLE IF EXISTS `vifnn_cointree_prize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cointree_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `first_prize` varchar(50) NOT NULL DEFAULT '',
  `first_img` varchar(255) NOT NULL DEFAULT '',
  `first_nums` int(11) NOT NULL,
  `second_prize` varchar(50) NOT NULL DEFAULT '',
  `second_img` varchar(255) NOT NULL DEFAULT '',
  `second_nums` int(11) NOT NULL,
  `third_prize` varchar(50) NOT NULL DEFAULT '',
  `third_img` varchar(255) NOT NULL DEFAULT '',
  `third_nums` int(11) NOT NULL,
  `fourth_prize` varchar(50) NOT NULL DEFAULT '',
  `fourth_img` varchar(255) NOT NULL DEFAULT '',
  `fourth_nums` int(11) NOT NULL,
  `fifth_prize` varchar(50) NOT NULL DEFAULT '',
  `fifth_img` varchar(255) NOT NULL DEFAULT '',
  `fifth_nums` int(11) NOT NULL,
  `sixth_prize` varchar(50) NOT NULL DEFAULT '',
  `sixth_img` varchar(255) NOT NULL DEFAULT '',
  `sixth_nums` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cointree_record` */
 DROP TABLE IF EXISTS `vifnn_cointree_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cointree_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `serialnumber` varchar(15) NOT NULL DEFAULT '',
  `prize` int(11) NOT NULL,
  `iswin` tinyint(1) NOT NULL DEFAULT '0',
  `shaketime` int(11) NOT NULL,
  `sendstutas` tinyint(1) NOT NULL DEFAULT '0',
  `sendtime` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `wecha_name` char(50) NOT NULL,
  `wecha_pic` varchar(255) NOT NULL,
  `token` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cointree_shares` */
 DROP TABLE IF EXISTS `vifnn_cointree_shares`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cointree_shares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `share_wechaid` varchar(50) NOT NULL DEFAULT '',
  `share_wechaname` varchar(50) NOT NULL DEFAULT '',
  `share_wechapic` varchar(255) NOT NULL DEFAULT '',
  `share_key` varchar(100) NOT NULL DEFAULT '',
  `addcoins` varchar(15) NOT NULL,
  `opentime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cointree_users` */
 DROP TABLE IF EXISTS `vifnn_cointree_users`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cointree_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `total_shakes` int(11) NOT NULL,
  `today_shakes` int(11) NOT NULL,
  `wecha_id` varchar(50) NOT NULL DEFAULT '',
  `wecha_name` varchar(50) NOT NULL DEFAULT '',
  `wecha_pic` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(15) NOT NULL DEFAULT '',
  `token` varchar(30) NOT NULL DEFAULT '',
  `share_key` varchar(100) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL,
  `coins_count` int(11) NOT NULL,
  `isverify` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE,
  KEY `coinuser` (`cid`,`wecha_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_collectword` */
 DROP TABLE IF EXISTS `vifnn_collectword`;/* MySQLReback Separation */ CREATE TABLE `vifnn_collectword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `word` varchar(20) NOT NULL,
  `intro` varchar(200) NOT NULL DEFAULT '',
  `info` text,
  `reply_pic` varchar(200) NOT NULL,
  `start` int(11) NOT NULL DEFAULT '0',
  `end` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_open` int(11) NOT NULL DEFAULT '0',
  `is_reg` int(11) NOT NULL DEFAULT '0',
  `is_attention` int(11) NOT NULL DEFAULT '0',
  `is_sms` int(11) NOT NULL DEFAULT '0',
  `fxtitle` varchar(200) NOT NULL DEFAULT '',
  `fxinfo` varchar(200) NOT NULL DEFAULT '',
  `rank_num` int(11) NOT NULL DEFAULT '10',
  `count` int(10) unsigned NOT NULL,
  `help_count` int(10) unsigned NOT NULL,
  `prize_display` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `prize_fxtitle` varchar(200) NOT NULL DEFAULT '',
  `prize_fxinfo` varchar(200) NOT NULL DEFAULT '',
  `day_count` int(10) unsigned NOT NULL DEFAULT '0',
  `expect` int(10) unsigned NOT NULL DEFAULT '0',
  `fxpic` varchar(200) NOT NULL DEFAULT '',
  `is_help` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `title` (`title`) USING BTREE,
  KEY `is_open` (`is_open`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_collectword_news` */
 DROP TABLE IF EXISTS `vifnn_collectword_news`;/* MySQLReback Separation */ CREATE TABLE `vifnn_collectword_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `imgurl` varchar(200) NOT NULL,
  `url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_collectword_prize` */
 DROP TABLE IF EXISTS `vifnn_collectword_prize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_collectword_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `imgurl` varchar(200) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_collectword_record` */
 DROP TABLE IF EXISTS `vifnn_collectword_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_collectword_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `word` tinyint(3) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_collectword_share` */
 DROP TABLE IF EXISTS `vifnn_collectword_share`;/* MySQLReback Separation */ CREATE TABLE `vifnn_collectword_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `share_key` varchar(100) NOT NULL,
  `addtime` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `share_key` (`share_key`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_collectword_user` */
 DROP TABLE IF EXISTS `vifnn_collectword_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_collectword_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_prize` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `share_key` varchar(100) NOT NULL DEFAULT '0',
  `share_num` int(11) NOT NULL DEFAULT '0',
  `tel` varchar(50) NOT NULL DEFAULT '0',
  `is_join` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `word_count` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `share_key` (`share_key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_company` */
 DROP TABLE IF EXISTS `vifnn_company`;/* MySQLReback Separation */ CREATE TABLE `vifnn_company` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `amapid` varchar(50) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `token` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(60) NOT NULL,
  `password` varchar(32) NOT NULL,
  `shortname` varchar(50) NOT NULL DEFAULT '',
  `mp` varchar(11) NOT NULL DEFAULT '',
  `tel` varchar(20) NOT NULL DEFAULT '',
  `address` varchar(200) NOT NULL DEFAULT '',
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `intro` text NOT NULL,
  `catid` mediumint(3) NOT NULL DEFAULT '0',
  `taxis` int(10) NOT NULL DEFAULT '0',
  `isbranch` tinyint(1) NOT NULL DEFAULT '0',
  `logourl` varchar(180) NOT NULL DEFAULT '',
  `area_id` int(11) NOT NULL DEFAULT '0',
  `cate_id` int(11) NOT NULL DEFAULT '0',
  `market_id` int(11) NOT NULL DEFAULT '0',
  `mark_url` varchar(200) NOT NULL DEFAULT '',
  `add_time` char(10) NOT NULL DEFAULT '0',
  `province` char(30) NOT NULL,
  `city` char(30) NOT NULL,
  `district` char(30) NOT NULL,
  `location_id` int(11) NOT NULL,
  `cat_name` char(50) NOT NULL,
  `business_type` varchar(100) NOT NULL,
  `recommend` varchar(500) NOT NULL,
  `special` varchar(500) NOT NULL,
  `introduction` varchar(800) NOT NULL,
  `open_time` varchar(50) NOT NULL,
  `avg_price` int(11) NOT NULL,
  `opentime` varchar(20) NOT NULL,
  `closetime` varchar(20) NOT NULL,
  `available_state` tinyint(1) NOT NULL,
  `categories` varchar(100) NOT NULL,
  `sid` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_company` */
 INSERT INTO `vifnn_company` VALUES ('7','','1','zrtmca1421056092','1','','','1','','13322221111','天安门','39.908776','116.407746','','0','0','0','http://800.vifnn.com/tpl/static/attachment/icon/canyin/canyin_orange/6.png','0','0','0','','0','','','','0','','','','','','','0','','','0','',''),('8','155353','1','zrtmca1421056092','2','111','698d51a19d8a121ce581499d7b701668','2','13800138000','2','211221','-0.0314835','0.02973369','2','0','0','1','http://800.vifnn.com/tpl/static/attachment/icon/canyin/canyin_red/1.png','0','0','0','','0','湖北省','黄冈市','黄梅县','0','生活','Store,Hotels,Repast,DishOut','','','','','0','','','0','',''),('9','','1','ezrehr1458789061','海口淘志网络科技有限公司','','','','','0898-66746960','的挖的挖的我单位党委','0','0','&nbsp;查询现在现在现在现在现在xz','0','0','0','http://wx.sitore.com/uploads/e/ezrehr1458789061/0/8/5/4/thumb_56f370cbdc059.jpg','0','0','0','','0','海南省','海口市','新华区','0','IT','','','','','','0','','','0','',''),('22','','1','ywxgfh1465574691','思拓演示商城','','','','','13807674808','国贸大道帝国大厦A座1615','20.027648','110.322988','用于演示思拓旅游商城设置','0','0','0','http://wx.sitore.com/uploads/y/ywxgfh1465574691/3/1/f/2/thumb_575b7c92ace20.jpg','0','0','0','','1465613554','海南省','海口市','新华区','0','IT','','','','','','0','09:00','19:00','0','','575b7cf24b835');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_company_staff` */
 DROP TABLE IF EXISTS `vifnn_company_staff`;/* MySQLReback Separation */ CREATE TABLE `vifnn_company_staff` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `companyid` int(10) NOT NULL,
  `token` varchar(30) NOT NULL DEFAULT '',
  `name` varchar(30) NOT NULL DEFAULT '',
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `tel` varchar(11) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL,
  `func` varchar(1000) NOT NULL,
  `pcorwap` enum('pc','wap') NOT NULL,
  `wecha_id` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `companyid` (`companyid`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_company_staff` */
 INSERT INTO `vifnn_company_staff` VALUES ('2','0','qcjnep1444636005','店员','dianyuan','dianyuan','','1444639262','lottery,choujiang,shouye,adma,huiyuanka,host_kev,diyform,dx,shop,etuan,diymen_set,gua2,panorama,wedding,vote,estate,album,GoldenEgg,LuckyFruit,messageboard,car,wall,shake,forum,medical,groupmessage,share,hotel,school,Autumn,Lovers,AppleGame,Live,Research,Problem,Signin,Scene,Market,Custom,Greeting_card,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Gamecenter,Red_packet,Punish,Invite,Autumns,Jiugong,MicroBroker,Helping,Popularity,Unitary,Phone,website,DishOut,LivingCircle,Test,Bargain,Crowdfunding,SeniorScene,Seckill,Service,Hongbao,Weixin,Card,Fuwu,Voteimg,Micrstore,Shakearound,Cutprice,Sentiment,Person_card,ServiceUser,Numqueue,RecognitionData,CustomTmpls,Yundabao,usernameCheck,CoinTree,FrontPage,Collectword,RippleOS,Vifnnemail,Jiejing','',''),('3','0','zrtmca1421056092','店员','vifnn','vifnn','18900009999','1444748282','lottery','','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_conditionalmenu` */
 DROP TABLE IF EXISTS `vifnn_conditionalmenu`;/* MySQLReback Separation */ CREATE TABLE `vifnn_conditionalmenu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '-1',
  `sex` int(11) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `client_platform_type` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `addtime` int(11) NOT NULL,
  `menuid` varchar(100) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_conditionalmenu_class` */
 DROP TABLE IF EXISTS `vifnn_conditionalmenu_class`;/* MySQLReback Separation */ CREATE TABLE `vifnn_conditionalmenu_class` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `cid` int(11) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `keyword` varchar(30) NOT NULL,
  `url` varchar(300) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) NOT NULL DEFAULT '0',
  `wxsys` varchar(40) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `nav` varchar(200) NOT NULL,
  `text` varchar(500) NOT NULL,
  `test` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cosmetology` */
 DROP TABLE IF EXISTS `vifnn_cosmetology`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cosmetology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `piccover` varchar(500) NOT NULL,
  `registrationtoppic` varchar(500) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `lat` varchar(30) NOT NULL,
  `lng` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sms` varchar(100) NOT NULL,
  `open_email` varchar(50) NOT NULL,
  `open_sms` varchar(50) NOT NULL,
  `checked` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cosmetology_departments` */
 DROP TABLE IF EXISTS `vifnn_cosmetology_departments`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cosmetology_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cosmetology_setup` */
 DROP TABLE IF EXISTS `vifnn_cosmetology_setup`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cosmetology_setup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `age` varchar(20) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `scheduled_date` datetime NOT NULL,
  `address` varchar(500) NOT NULL,
  `departments` varchar(200) NOT NULL,
  `expert` varchar(200) NOT NULL,
  `disease` varchar(500) NOT NULL,
  `process` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cosmetology_setup_control` */
 DROP TABLE IF EXISTS `vifnn_cosmetology_setup_control`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cosmetology_setup_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `age` varchar(20) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `scheduled_date` varchar(200) NOT NULL,
  `address` varchar(500) NOT NULL,
  `departments` varchar(200) NOT NULL,
  `expert` varchar(200) NOT NULL,
  `disease` varchar(500) NOT NULL,
  `process` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_coupon_pay_record` */
 DROP TABLE IF EXISTS `vifnn_coupon_pay_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_coupon_pay_record` (
  `vifnn_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` varchar(60) NOT NULL COMMENT '订单ID',
  `coupon_id` int(10) unsigned NOT NULL COMMENT '优惠券ID',
  `wechat_id` varchar(100) NOT NULL,
  `token` varchar(64) NOT NULL,
  `from` varchar(50) NOT NULL,
  `least_cost` decimal(10,2) NOT NULL COMMENT '订单至少要满足的金额',
  `reduce_cost` decimal(10,2) NOT NULL COMMENT '订单优惠的金额',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`vifnn_id`),
  KEY `orderid` (`orderid`,`coupon_id`) USING BTREE,
  KEY `wechat_id` (`wechat_id`,`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_crowdfunding` */
 DROP TABLE IF EXISTS `vifnn_crowdfunding`;/* MySQLReback Separation */ CREATE TABLE `vifnn_crowdfunding` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` char(30) NOT NULL,
  `keyword` char(30) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `pic` varchar(250) NOT NULL,
  `intro` text NOT NULL,
  `fund` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  `day` smallint(5) unsigned NOT NULL,
  `start` char(15) NOT NULL,
  `detail` text NOT NULL,
  `is_attention` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `praise` int(11) NOT NULL,
  `focus` int(11) NOT NULL,
  `supports` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_crowdfunding_focus` */
 DROP TABLE IF EXISTS `vifnn_crowdfunding_focus`;/* MySQLReback Separation */ CREATE TABLE `vifnn_crowdfunding_focus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `wecha_id` char(40) NOT NULL,
  `token` char(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`cid`),
  KEY `wecha_id` (`wecha_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_crowdfunding_order` */
 DROP TABLE IF EXISTS `vifnn_crowdfunding_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_crowdfunding_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_name` char(50) NOT NULL,
  `orderid` char(50) NOT NULL,
  `token` char(40) NOT NULL,
  `pid` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `wecha_id` char(40) NOT NULL,
  `address` varchar(300) NOT NULL,
  `add_time` char(15) NOT NULL,
  `pay_time` char(15) NOT NULL,
  `price` double(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `username` char(20) NOT NULL,
  `tel` char(20) NOT NULL,
  `remark` varchar(500) NOT NULL,
  `paytype` varchar(50) NOT NULL,
  `paid` tinyint(4) NOT NULL,
  `third_id` varchar(100) NOT NULL,
  `is_delete` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_crowdfunding_reward` */
 DROP TABLE IF EXISTS `vifnn_crowdfunding_reward`;/* MySQLReback Separation */ CREATE TABLE `vifnn_crowdfunding_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(30) NOT NULL,
  `pid` int(11) NOT NULL,
  `money` float NOT NULL,
  `content` text NOT NULL,
  `img` varchar(250) NOT NULL,
  `people` int(11) NOT NULL,
  `back_day` smallint(6) NOT NULL,
  `carriage` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_custom_field` */
 DROP TABLE IF EXISTS `vifnn_custom_field`;/* MySQLReback Separation */ CREATE TABLE `vifnn_custom_field` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` char(15) NOT NULL,
  `filed_option` varchar(500) NOT NULL,
  `field_type` char(10) NOT NULL,
  `item_name` char(15) NOT NULL,
  `field_match` char(80) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `is_empty` enum('0','1') NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `err_info` char(35) NOT NULL,
  `set_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  `file_type` varchar(120) NOT NULL DEFAULT '' COMMENT '上传文件类型',
  `file_size` int(11) NOT NULL DEFAULT '2' COMMENT '上传文件大小(M)',
  `file_num` int(11) NOT NULL DEFAULT '1' COMMENT '文件数量限制',
  PRIMARY KEY (`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_custom_info` */
 DROP TABLE IF EXISTS `vifnn_custom_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_custom_info` (
  `info_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` char(20) NOT NULL,
  `wecha_id` char(30) NOT NULL,
  `set_id` int(11) NOT NULL,
  `add_time` char(11) NOT NULL,
  `user_name` char(35) NOT NULL,
  `phone` char(11) NOT NULL,
  `sub_info` text NOT NULL,
  PRIMARY KEY (`info_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_custom_limit` */
 DROP TABLE IF EXISTS `vifnn_custom_limit`;/* MySQLReback Separation */ CREATE TABLE `vifnn_custom_limit` (
  `limit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `enddate` char(10) NOT NULL,
  `sub_total` smallint(6) NOT NULL,
  `today_total` smallint(6) NOT NULL,
  `type` int(11) NOT NULL,
  `apiece_total` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`limit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_custom_order` */
 DROP TABLE IF EXISTS `vifnn_custom_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_custom_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `set_id` int(11) NOT NULL,
  `orderid` varchar(30) DEFAULT NULL,
  `paid` int(11) NOT NULL DEFAULT '0',
  `paytype` varchar(50) DEFAULT NULL,
  `third_id` varchar(100) DEFAULT NULL,
  `price` varchar(50) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_custom_reply` */
 DROP TABLE IF EXISTS `vifnn_custom_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_custom_reply` (
  `id` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  `reply_content` varchar(300) NOT NULL DEFAULT '' COMMENT '回复内容',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_custom_set` */
 DROP TABLE IF EXISTS `vifnn_custom_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_custom_set` (
  `set_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(30) NOT NULL,
  `keyword` char(25) NOT NULL,
  `intro` varchar(100) NOT NULL,
  `addtime` char(10) NOT NULL,
  `top_pic` char(100) NOT NULL,
  `succ_info` char(35) NOT NULL,
  `err_info` char(35) NOT NULL,
  `detail` text NOT NULL,
  `limit_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  `tel` char(20) NOT NULL,
  `address` char(80) NOT NULL,
  `longitude` char(20) NOT NULL,
  `latitude` char(20) NOT NULL,
  `is_pay` int(10) unsigned NOT NULL DEFAULT '0',
  `pay_name` varchar(100) DEFAULT NULL,
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `follow` enum('0','1') NOT NULL DEFAULT '0' COMMENT '关注后提交 0不限 1粉丝',
  `sms` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否需要短信提醒',
  PRIMARY KEY (`set_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_customer` */
 DROP TABLE IF EXISTS `vifnn_customer`;/* MySQLReback Separation */ CREATE TABLE `vifnn_customer` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT '',
  `sex` varchar(4) DEFAULT '',
  `mobile` varchar(200) DEFAULT '',
  `tel` varchar(200) DEFAULT '',
  `email` varchar(200) DEFAULT '',
  `company` varchar(100) DEFAULT '',
  `job` varchar(20) DEFAULT '',
  `address` varchar(120) DEFAULT '',
  `website` varchar(200) DEFAULT '',
  `qq` varchar(16) DEFAULT '',
  `weixin` varchar(50) DEFAULT '',
  `yixin` varchar(50) DEFAULT '',
  `weibo` varchar(50) DEFAULT '',
  `laiwang` varchar(50) DEFAULT '',
  `remark` varchar(255) DEFAULT '',
  `origin` bigint(20) unsigned NOT NULL DEFAULT '0',
  `originName` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `createUser` varchar(32) NOT NULL DEFAULT '',
  `createTime` int(10) unsigned NOT NULL DEFAULT '0',
  `groupId` varchar(20) NOT NULL DEFAULT '',
  `groupName` varchar(200) DEFAULT '',
  `group` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_customs` */
 DROP TABLE IF EXISTS `vifnn_customs`;/* MySQLReback Separation */ CREATE TABLE `vifnn_customs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fc` char(150) NOT NULL,
  `price` char(150) NOT NULL,
  `about` char(150) NOT NULL,
  `common` char(150) NOT NULL,
  `login` char(150) NOT NULL,
  `help` char(150) NOT NULL,
  `fcname` varchar(80) NOT NULL,
  `pricename` varchar(80) NOT NULL,
  `loginname` varchar(80) NOT NULL,
  `helpname` varchar(80) NOT NULL,
  `aboutname` varchar(80) NOT NULL,
  `commonname` varchar(80) NOT NULL,
  `fc_open` int(11) NOT NULL DEFAULT '0',
  `about_open` int(11) NOT NULL DEFAULT '0',
  `common_open` int(11) NOT NULL DEFAULT '0',
  `help_open` int(11) NOT NULL DEFAULT '0',
  `login_open` int(11) NOT NULL DEFAULT '0',
  `price_open` int(11) NOT NULL DEFAULT '0',
  `fc_dspl` int(11) NOT NULL DEFAULT '0',
  `common_dspl` int(11) NOT NULL DEFAULT '0',
  `about_dspl` int(11) NOT NULL DEFAULT '0',
  `login_dspl` int(11) NOT NULL DEFAULT '0',
  `price_dspl` int(11) NOT NULL DEFAULT '0',
  `help_dspl` int(11) NOT NULL DEFAULT '0',
  `agentid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  `open` int(11) NOT NULL,
  `dspl` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_TYPE` (`type`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cutprice` */
 DROP TABLE IF EXISTS `vifnn_cutprice`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cutprice` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `wxtitle` varchar(100) NOT NULL,
  `wxpic` varchar(100) NOT NULL,
  `wxinfo` varchar(500) DEFAULT NULL,
  `starttime` int(11) NOT NULL,
  `original` varchar(100) NOT NULL,
  `startprice` varchar(100) NOT NULL,
  `stopprice` varchar(100) NOT NULL,
  `cuttime` int(11) NOT NULL,
  `cutprice` varchar(100) NOT NULL,
  `inventory` int(11) NOT NULL,
  `logoimg1` varchar(100) NOT NULL,
  `logourl1` varchar(200) DEFAULT NULL,
  `logoimg2` varchar(100) DEFAULT NULL,
  `logourl2` varchar(200) DEFAULT NULL,
  `logoimg3` varchar(100) DEFAULT NULL,
  `logourl3` varchar(200) DEFAULT NULL,
  `info` text,
  `guize` text,
  `state` int(11) NOT NULL DEFAULT '0',
  `state_subscribe` int(11) NOT NULL DEFAULT '0',
  `state_userinfo` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `onebuynum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_cutprice_order` */
 DROP TABLE IF EXISTS `vifnn_cutprice_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_cutprice_order` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `cid` int(11) NOT NULL,
  `orderid` varchar(200) NOT NULL,
  `num` int(11) NOT NULL,
  `nowprice` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `address` varchar(500) NOT NULL,
  `endtime` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `wecha_id` varchar(200) NOT NULL,
  `transactionid` varchar(200) DEFAULT NULL,
  `paytype` varchar(100) DEFAULT NULL,
  `third_id` varchar(100) DEFAULT NULL,
  `paid` int(11) NOT NULL DEFAULT '0',
  `paystate` int(11) NOT NULL DEFAULT '0',
  `fahuo` int(11) NOT NULL DEFAULT '0',
  `fahuoid` varchar(100) DEFAULT NULL,
  `fahuoname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_czzreply_info` */
 DROP TABLE IF EXISTS `vifnn_czzreply_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_czzreply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `picurl` varchar(120) NOT NULL DEFAULT '',
  `picurls1` varchar(120) NOT NULL DEFAULT '',
  `info` varchar(120) NOT NULL DEFAULT '',
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `copyright` text NOT NULL,
  `bg` varchar(120) NOT NULL,
  `wx` varchar(120) NOT NULL,
  `zz` varchar(120) NOT NULL,
  `url` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dajiangsai` */
 DROP TABLE IF EXISTS `vifnn_dajiangsai`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dajiangsai` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(40) NOT NULL,
  `title` char(40) NOT NULL,
  `keyword` char(20) NOT NULL,
  `intro` varchar(250) NOT NULL,
  `info` text NOT NULL,
  `reply_pic` varchar(250) NOT NULL,
  `top_pic` varchar(250) NOT NULL,
  `share_pic` varchar(250) NOT NULL DEFAULT '',
  `start` char(15) NOT NULL,
  `end` char(15) NOT NULL,
  `add_time` char(15) NOT NULL DEFAULT '',
  `is_open` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  `is_score` tinyint(4) NOT NULL,
  `is_attention` tinyint(4) NOT NULL,
  `wecha_id` varchar(100) NOT NULL DEFAULT '',
  `minscore` char(10) NOT NULL DEFAULT '1',
  `maxscore` char(10) NOT NULL DEFAULT '',
  `frist` varchar(20) NOT NULL DEFAULT '',
  `fristpic` varchar(200) NOT NULL DEFAULT '',
  `fristnums` varchar(10) NOT NULL DEFAULT '',
  `fristdoc` varchar(20) NOT NULL DEFAULT '',
  `second` varchar(20) NOT NULL DEFAULT '',
  `secondpic` varchar(200) NOT NULL DEFAULT '',
  `secondnums` varchar(10) NOT NULL DEFAULT '',
  `seconddoc` varchar(20) NOT NULL DEFAULT '',
  `third` varchar(20) NOT NULL DEFAULT '',
  `thirdpic` varchar(200) NOT NULL DEFAULT '',
  `thirdnums` varchar(10) NOT NULL DEFAULT '',
  `thirddoc` varchar(20) NOT NULL DEFAULT '',
  `four` varchar(20) NOT NULL DEFAULT '',
  `fourpic` varchar(200) NOT NULL DEFAULT '',
  `fournums` varchar(10) NOT NULL DEFAULT '',
  `fourdoc` varchar(20) NOT NULL DEFAULT '',
  `five` varchar(20) NOT NULL DEFAULT '',
  `fivepic` varchar(200) NOT NULL DEFAULT '',
  `fivenums` varchar(10) NOT NULL DEFAULT '',
  `fivedoc` varchar(20) NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dajiangsai_record` */
 DROP TABLE IF EXISTS `vifnn_dajiangsai_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dajiangsai_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(35) NOT NULL,
  `pid` int(11) NOT NULL,
  `share_key` char(40) NOT NULL,
  `addtime` char(35) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `score` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dajiangsai_user` */
 DROP TABLE IF EXISTS `vifnn_dajiangsai_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dajiangsai_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(40) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `help_count` int(11) NOT NULL,
  `add_time` char(15) NOT NULL,
  `share_key` char(40) NOT NULL,
  `help_score` int(10) NOT NULL,
  `help_num` int(100) NOT NULL,
  `help_rank` int(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `tel` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dati` */
 DROP TABLE IF EXISTS `vifnn_dati`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dati` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL COMMENT '????',
  `picurl` varchar(200) DEFAULT NULL COMMENT '????',
  `info` varchar(300) DEFAULT NULL COMMENT '????',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 ??? 1 ??? 2 ???',
  `daan` varchar(200) NOT NULL COMMENT '??',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '????????',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '??',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dati_record` */
 DROP TABLE IF EXISTS `vifnn_dati_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dati_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(200) NOT NULL COMMENT 'token',
  `wecha_id` varchar(200) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '??',
  `playtime` int(15) DEFAULT NULL COMMENT '???????',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_deliemail` */
 DROP TABLE IF EXISTS `vifnn_deliemail`;/* MySQLReback Separation */ CREATE TABLE `vifnn_deliemail` (
  `token` varchar(60) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `smtpserver` varchar(40) NOT NULL DEFAULT '',
  `port` varchar(40) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `receive` varchar(60) NOT NULL DEFAULT '',
  `shangcheng` tinyint(1) NOT NULL DEFAULT '0',
  `yuyue` tinyint(1) NOT NULL DEFAULT '0',
  `baom` tinyint(1) NOT NULL DEFAULT '0',
  `zxyy` tinyint(1) NOT NULL DEFAULT '0',
  `toupiao` tinyint(1) NOT NULL DEFAULT '0',
  `dingcan` tinyint(1) NOT NULL,
  `car` tinyint(1) NOT NULL,
  `yiliao` tinyint(1) NOT NULL,
  `jdbg` tinyint(1) NOT NULL,
  `beauty` tinyint(1) NOT NULL,
  `fitness` tinyint(1) NOT NULL,
  `gover` tinyint(1) NOT NULL,
  `zhaopin` tinyint(1) NOT NULL,
  `jianli` tinyint(1) NOT NULL,
  `fangchan` tinyint(1) NOT NULL,
  `food` tinyint(1) NOT NULL,
  `travel` tinyint(1) NOT NULL,
  `flower` tinyint(1) NOT NULL,
  `property` tinyint(1) NOT NULL,
  `bar` tinyint(1) NOT NULL,
  `fitment` tinyint(1) NOT NULL,
  `wedding` tinyint(1) NOT NULL,
  `affections` tinyint(1) NOT NULL,
  `housekeeper` tinyint(1) NOT NULL,
  `lease` tinyint(1) NOT NULL,
  `wn` tinyint(1) NOT NULL,
  PRIMARY KEY (`token`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_deliemail` */
 INSERT INTO `vifnn_deliemail` VALUES ('zrtmca1421056092','0','','','','','dd@qq.com','0','0','0','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','0','0','0','0','0','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_delisms` */
 DROP TABLE IF EXISTS `vifnn_delisms`;/* MySQLReback Separation */ CREATE TABLE `vifnn_delisms` (
  `token` varchar(60) NOT NULL,
  `phone` varchar(40) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `shangcheng` tinyint(1) NOT NULL DEFAULT '0',
  `yuyue` tinyint(1) NOT NULL DEFAULT '0',
  `baom` tinyint(1) NOT NULL DEFAULT '0',
  `zxyy` tinyint(1) NOT NULL DEFAULT '0',
  `toupiao` tinyint(1) NOT NULL DEFAULT '0',
  `dingcan` tinyint(1) NOT NULL,
  `car` tinyint(1) NOT NULL,
  `yiliao` tinyint(1) NOT NULL,
  `jdbg` tinyint(1) NOT NULL,
  `ktv` tinyint(1) NOT NULL,
  `huisuo` tinyint(1) NOT NULL,
  `jiuba` tinyint(1) NOT NULL,
  PRIMARY KEY (`token`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_diaoyan` */
 DROP TABLE IF EXISTS `vifnn_diaoyan`;/* MySQLReback Separation */ CREATE TABLE `vifnn_diaoyan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `stime` date DEFAULT NULL,
  `etime` date DEFAULT NULL,
  `stat` tinyint(2) DEFAULT '0',
  `pic` varchar(200) DEFAULT NULL,
  `sinfo` varchar(500) DEFAULT NULL,
  `einfo` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_diaoyan_timu` */
 DROP TABLE IF EXISTS `vifnn_diaoyan_timu`;/* MySQLReback Separation */ CREATE TABLE `vifnn_diaoyan_timu` (
  `tid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `optia` varchar(200) DEFAULT NULL,
  `optib` varchar(200) DEFAULT NULL,
  `optic` varchar(200) DEFAULT NULL,
  `optid` varchar(200) DEFAULT NULL,
  `optie` varchar(200) DEFAULT NULL,
  `max` smallint(2) DEFAULT NULL,
  `pid` int(11) NOT NULL,
  `perca` int(11) DEFAULT '0',
  `percb` int(11) DEFAULT '0',
  `percc` int(11) DEFAULT '0',
  `percd` int(11) DEFAULT '0',
  `perce` int(11) DEFAULT '0',
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_diaoyan_user` */
 DROP TABLE IF EXISTS `vifnn_diaoyan_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_diaoyan_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `diaoyan_id` int(11) DEFAULT NULL,
  `wecha_id` varchar(100) DEFAULT NULL,
  `qid` int(11) DEFAULT NULL,
  `ans` varchar(20) DEFAULT NULL,
  `jianyi` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dining_table` */
 DROP TABLE IF EXISTS `vifnn_dining_table`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dining_table` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `image` varchar(200) NOT NULL,
  `isbox` tinyint(1) unsigned NOT NULL,
  `isorder` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `catid` int(8) NOT NULL COMMENT '店铺id',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `isbox` (`isbox`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_dining_table` */
 INSERT INTO `vifnn_dining_table` VALUES ('4','7','434','11','','0','0','0','7');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_directhongbao` */
 DROP TABLE IF EXISTS `vifnn_directhongbao`;/* MySQLReback Separation */ CREATE TABLE `vifnn_directhongbao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `min_money` float(6,2) NOT NULL,
  `max_money` float(6,2) NOT NULL,
  `total_money` float(9,2) NOT NULL,
  `send_name` varchar(50) NOT NULL,
  `wishing` varchar(150) NOT NULL,
  `act_name` varchar(50) NOT NULL,
  `remark` varchar(300) NOT NULL,
  `hb_type` tinyint(1) NOT NULL DEFAULT '1',
  `group_nums` int(10) NOT NULL,
  `send_type` tinyint(1) NOT NULL DEFAULT '1',
  `gid` int(10) NOT NULL,
  `fans_id` text NOT NULL,
  `fans_name` text NOT NULL,
  `lastsendtime` int(10) NOT NULL,
  `totalnums` int(10) NOT NULL,
  `token` char(25) NOT NULL,
  `send_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_directhongbao_record` */
 DROP TABLE IF EXISTS `vifnn_directhongbao_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_directhongbao_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(10) unsigned NOT NULL,
  `mch_billno` char(50) NOT NULL DEFAULT '',
  `fans_id` varchar(60) NOT NULL DEFAULT '',
  `fans_nickname` varchar(60) NOT NULL DEFAULT '',
  `money` float(6,2) DEFAULT NULL,
  `status` char(20) NOT NULL DEFAULT '',
  `hb_type` tinyint(1) NOT NULL,
  `token` char(25) NOT NULL,
  `send_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hid` (`hid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dish` */
 DROP TABLE IF EXISTS `vifnn_dish`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit` varchar(3) NOT NULL,
  `price` float NOT NULL,
  `ishot` tinyint(1) unsigned NOT NULL,
  `isopen` tinyint(1) unsigned NOT NULL,
  `image` varchar(200) NOT NULL COMMENT '片',
  `des` varchar(500) NOT NULL,
  `creattime` int(10) unsigned NOT NULL,
  `catid` int(8) NOT NULL COMMENT '店铺id',
  `sort` int(10) unsigned NOT NULL COMMENT '排序，数字越小排的越前',
  `istakeout` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否支持外卖',
  `isdiscount` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否支持会员折扣',
  `instock` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `refreshstock` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '刷新库存',
  `kitchen_id` int(10) unsigned NOT NULL COMMENT '厨房ID',
  `tmpinstock` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '临时库存',
  `locktime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存锁定时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `sid` (`sid`),
  KEY `isopen` (`isopen`),
  KEY `sort` (`sort`),
  KEY `kitchen_id` (`kitchen_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dish_company` */
 DROP TABLE IF EXISTS `vifnn_dish_company`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dish_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `istakeaway` tinyint(1) unsigned NOT NULL,
  `price` float NOT NULL,
  `payonline` tinyint(1) unsigned NOT NULL,
  `subscription` float NOT NULL,
  `discount` decimal(10,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '折扣',
  `kconoff` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启库存',
  `autoref` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '营业开始时间',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '营业结束时间',
  `imgs` varchar(250) NOT NULL COMMENT '餐厅图片',
  `bookingtime` varchar(255) NOT NULL COMMENT '餐桌选择时间段',
  `nowpay` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否立刻支付',
  `advancepay` int(10) unsigned NOT NULL COMMENT '预付定金',
  `dishsame` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分店统一菜品',
  `offtable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '关闭选择餐桌',
  `starttime2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '营业开始时间',
  `endtime2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '营业结束时间',
  `token` varchar(50) NOT NULL DEFAULT '',
  `catid` mediumint(3) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL COMMENT '店铺状态',
  `category` varchar(10) NOT NULL COMMENT '店铺分类',
  `time` varchar(12) NOT NULL COMMENT '营业时间',
  `money` double(10,2) NOT NULL COMMENT '起送价格',
  `radius` varchar(10) NOT NULL COMMENT '服务半径',
  `scope` varchar(100) NOT NULL COMMENT '配送范围',
  `bulletin` text NOT NULL COMMENT '店铺公告',
  `memberCode` varchar(50) NOT NULL,
  `feiyin_key` varchar(50) NOT NULL,
  `deviceNo` varchar(20) NOT NULL,
  `print_status` int(1) NOT NULL,
  `print_total` int(4) NOT NULL COMMENT '打印份数',
  `phone_authorize` int(1) NOT NULL COMMENT '手机订单短信验证开关',
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_status` int(1) NOT NULL,
  `email_status` int(1) NOT NULL,
  `printer` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_dish_company` */
 INSERT INTO `vifnn_dish_company` VALUES ('3','7','1','200','0','0','0.0','0','0','0','0','','','1','0','0','0','0','0','','7','0','','','0.00','','','','','','','0','0','0','','','0','0',''),('4','8','0','0','0','0','0.0','0','0','0','0','','','1','0','0','0','0','0','','8','0','','','0.00','','','','','','','0','0','0','','','0','0','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dish_kitchen` */
 DROP TABLE IF EXISTS `vifnn_dish_kitchen`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dish_kitchen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dish_like` */
 DROP TABLE IF EXISTS `vifnn_dish_like`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dish_like` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `did` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `wecha_id` (`wecha_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dish_name` */
 DROP TABLE IF EXISTS `vifnn_dish_name`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dish_name` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `token` varchar(250) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dish_order` */
 DROP TABLE IF EXISTS `vifnn_dish_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dish_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `total` int(11) NOT NULL,
  `price` float NOT NULL,
  `nums` smallint(3) unsigned NOT NULL,
  `info` text NOT NULL,
  `name` varchar(100) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `tel` varchar(13) NOT NULL DEFAULT '',
  `address` varchar(200) NOT NULL,
  `tableid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `reservetime` int(11) NOT NULL,
  `isuse` tinyint(1) NOT NULL,
  `paid` tinyint(1) unsigned NOT NULL,
  `orderid` varchar(100) NOT NULL,
  `printed` tinyint(1) unsigned NOT NULL,
  `des` varchar(500) NOT NULL,
  `takeaway` tinyint(1) unsigned NOT NULL,
  `paytype` varchar(50) NOT NULL DEFAULT '',
  `third_id` varchar(100) NOT NULL DEFAULT '',
  `comefrom` varchar(50) NOT NULL DEFAULT 'dish' COMMENT '表明来源字母全小写',
  `stype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '外送方式类型',
  `isdel` tinyint(1) unsigned DEFAULT '0',
  `allmark` text NOT NULL COMMENT '购物车备注',
  `havepaid` float unsigned NOT NULL DEFAULT '0' COMMENT '二次支付时记录已经支付的金额',
  `paycount` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付的次数',
  `advancepay` float unsigned NOT NULL DEFAULT '0' COMMENT '预付金额',
  `isover` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单支付是否结束1进行2结束',
  `tmporderid` varchar(100) NOT NULL COMMENT '临时订单id支持多次付款',
  `catid` int(8) NOT NULL COMMENT '店铺id',
  `send_email` char(1) NOT NULL DEFAULT '0' COMMENT '1已发0失败',
  `isyes` tinyint(1) DEFAULT NULL,
  `deliverymanid` int(20) DEFAULT NULL,
  `addcan` text NOT NULL COMMENT '记录加菜',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`wecha_id`),
  KEY `token` (`token`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dish_reply` */
 DROP TABLE IF EXISTS `vifnn_dish_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dish_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL COMMENT 'company表id',
  `token` varchar(250) NOT NULL,
  `tableid` int(10) unsigned NOT NULL COMMENT 'dining_table表id',
  `keyword` varchar(100) NOT NULL COMMENT '关键词',
  `cf` varchar(20) NOT NULL COMMENT '来源',
  `addtime` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1餐厅2餐桌后台0餐桌',
  `reg_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'recognition表id',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE,
  KEY `reg_id` (`reg_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dish_sort` */
 DROP TABLE IF EXISTS `vifnn_dish_sort`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dish_sort` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `des` varchar(500) NOT NULL,
  `image` varchar(200) NOT NULL,
  `num` smallint(3) unsigned NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `catid` int(8) NOT NULL COMMENT '店铺id',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dish_table` */
 DROP TABLE IF EXISTS `vifnn_dish_table`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dish_table` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `tableid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  `reservetime` int(10) unsigned NOT NULL,
  `creattime` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `isuse` tinyint(1) unsigned NOT NULL,
  `dn_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'dish_name表id',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `wecha_id` (`wecha_id`,`reservetime`),
  KEY `tableid` (`tableid`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dishout_deliveryman` */
 DROP TABLE IF EXISTS `vifnn_dishout_deliveryman`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dishout_deliveryman` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL COMMENT 'company表id',
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tel` varchar(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dishout_manage` */
 DROP TABLE IF EXISTS `vifnn_dishout_manage`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dishout_manage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL COMMENT 'company表id',
  `token` varchar(255) NOT NULL,
  `zc_sdate` int(10) unsigned NOT NULL DEFAULT '0',
  `zc_edate` int(10) unsigned NOT NULL DEFAULT '0',
  `wc_sdate` int(10) unsigned NOT NULL DEFAULT '0',
  `wc_edate` int(10) unsigned NOT NULL DEFAULT '0',
  `permin` tinyint(3) unsigned NOT NULL DEFAULT '15',
  `removing` varchar(50) NOT NULL DEFAULT '0' COMMENT '服务半径',
  `area` varchar(255) NOT NULL COMMENT '配送区域',
  `sendtime` tinyint(3) NOT NULL COMMENT '送达时间(分)',
  `shopimg` text NOT NULL COMMENT '门店幻灯图片',
  `stype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '外送方式类型',
  `pricing` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外送相关金额设定',
  `keyword` varchar(255) NOT NULL COMMENT '关键词',
  `rtitle` varchar(200) NOT NULL COMMENT '回复标题',
  `rinfo` varchar(255) NOT NULL COMMENT '回复描述',
  `picurl` varchar(255) NOT NULL COMMENT '回复封面图片',
  `overflow_radius` int(5) DEFAULT NULL,
  `priceup` int(5) DEFAULT NULL,
  `delivery_fee` int(5) DEFAULT NULL,
  `discount` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dishout_salelog` */
 DROP TABLE IF EXISTS `vifnn_dishout_salelog`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dishout_salelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(250) NOT NULL,
  `cid` int(10) unsigned NOT NULL COMMENT '商店id',
  `did` int(10) unsigned NOT NULL COMMENT 'dish表id',
  `order_id` int(10) unsigned NOT NULL COMMENT 'dish_order表id',
  `money` decimal(10,2) unsigned NOT NULL COMMENT '下单时此菜总金额',
  `nums` int(10) unsigned NOT NULL COMMENT '下单时份数',
  `unitprice` decimal(10,2) unsigned NOT NULL COMMENT '下单时此菜单价',
  `dname` varchar(200) NOT NULL COMMENT '下单时菜名',
  `paytype` varchar(20) NOT NULL COMMENT '支付方式',
  `addtime` int(10) unsigned NOT NULL COMMENT '下单时间',
  `addtimestr` varchar(30) NOT NULL COMMENT '下单时间',
  `comefrom` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0外卖1微餐饮',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `did` (`did`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_distribution_setting` */
 DROP TABLE IF EXISTS `vifnn_distribution_setting`;/* MySQLReback Separation */ CREATE TABLE `vifnn_distribution_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `commission_type` varchar(10) NOT NULL DEFAULT '' COMMENT '佣金类型 fixed 固定 float 百分比',
  `commission` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `multi_distribution` char(1) NOT NULL DEFAULT '0' COMMENT '多级分销 0, 1',
  `upgrade_channel_commission` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '升级渠道商奖励',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT '主站唯一标识',
  `agreement` text NOT NULL COMMENT '加盟协议',
  `is_check` char(1) NOT NULL DEFAULT '0' COMMENT '分销申请是否要审核',
  `level_max` int(3) NOT NULL DEFAULT '0' COMMENT '最大分销级别',
  `paid_day` tinyint(3) DEFAULT '2' COMMENT '佣金支付处理（工作日）',
  `ad_img` varchar(200) NOT NULL DEFAULT '' COMMENT '分销引导广告（图片）',
  `home_banner_img` varchar(200) NOT NULL DEFAULT '' COMMENT '分销店铺首页banner图片',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '分销店铺logo图片',
  `allow_distribution` char(1) NOT NULL DEFAULT '0' COMMENT '是否允许分销 0不允许 0允许',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_distributor` */
 DROP TABLE IF EXISTS `vifnn_distributor`;/* MySQLReback Separation */ CREATE TABLE `vifnn_distributor` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '分销商id',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '电话',
  `addr` varchar(500) NOT NULL DEFAULT '' COMMENT '地址',
  `latitude` double NOT NULL DEFAULT '0' COMMENT '经度',
  `longitude` double NOT NULL DEFAULT '0' COMMENT '纬度',
  `intro` text NOT NULL COMMENT '简介',
  `ischannel` char(1) NOT NULL DEFAULT '0' COMMENT '渠道商 0,1',
  `status` char(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `balance` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '未提现金额',
  `paid` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '已提现金额',
  `checked` char(1) NOT NULL DEFAULT '0' COMMENT '审核 0,1',
  `regtime` varchar(20) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `wecha_id` varchar(60) NOT NULL DEFAULT '0' COMMENT '粉丝识别码',
  `token` varchar(50) NOT NULL DEFAULT '0' COMMENT '主商城识别码',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `token` (`token`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_distributor_commission_record` */
 DROP TABLE IF EXISTS `vifnn_distributor_commission_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_distributor_commission_record` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `did` int(10) NOT NULL DEFAULT '0' COMMENT '分销商id',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '买家id 0为游客结算',
  `product_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品id 0为升级渠道商奖励',
  `amount` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `bak` text NOT NULL COMMENT '备注',
  `addtime` varchar(20) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `did` (`did`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `product_id` (`product_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_distributor_order` */
 DROP TABLE IF EXISTS `vifnn_distributor_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_distributor_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '订单id',
  `did` int(10) NOT NULL DEFAULT '0' COMMENT '分销商id',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_distributor_product` */
 DROP TABLE IF EXISTS `vifnn_distributor_product`;/* MySQLReback Separation */ CREATE TABLE `vifnn_distributor_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '商品id',
  `did` int(10) NOT NULL DEFAULT '0' COMMENT '分销商id',
  `soldout` char(1) NOT NULL DEFAULT '0' COMMENT '商品下架 0，1',
  `salesnum` int(5) NOT NULL DEFAULT '0' COMMENT '销售量',
  `salestotal` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '销售额',
  `time` varchar(20) NOT NULL DEFAULT '' COMMENT '操作时间(上架、下架)',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `did` (`did`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_distributor_receipt` */
 DROP TABLE IF EXISTS `vifnn_distributor_receipt`;/* MySQLReback Separation */ CREATE TABLE `vifnn_distributor_receipt` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `did` int(10) NOT NULL DEFAULT '0' COMMENT '分销商id',
  `amount` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `paidtime` varchar(20) NOT NULL DEFAULT '0' COMMENT '打款时间',
  `status` char(1) NOT NULL DEFAULT '0' COMMENT '状态 0 已打款 , 1 已收款',
  `receipttime` varchar(20) NOT NULL DEFAULT '0' COMMENT '收款时间',
  `time` varchar(20) NOT NULL DEFAULT '0' COMMENT '申请提现时间',
  PRIMARY KEY (`id`),
  KEY `did` (`did`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_distributor_relation` */
 DROP TABLE IF EXISTS `vifnn_distributor_relation`;/* MySQLReback Separation */ CREATE TABLE `vifnn_distributor_relation` (
  `did` int(10) NOT NULL DEFAULT '0' COMMENT '分销商id',
  `supdid` int(10) NOT NULL DEFAULT '0' COMMENT '所属分销商id',
  `supdids` varchar(3000) NOT NULL DEFAULT '0' COMMENT '上级分销商id列表',
  `level` int(5) NOT NULL DEFAULT '1' COMMENT '级别',
  KEY `did` (`did`) USING BTREE,
  KEY `supdid` (`supdid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_distributor_store` */
 DROP TABLE IF EXISTS `vifnn_distributor_store`;/* MySQLReback Separation */ CREATE TABLE `vifnn_distributor_store` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `did` int(10) NOT NULL DEFAULT '0' COMMENT '分销商id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '店铺名称',
  `tpid` int(10) NOT NULL DEFAULT '0' COMMENT '首页模板',
  `footerid` int(10) NOT NULL DEFAULT '0' COMMENT '底部导航',
  `headerid` int(10) NOT NULL DEFAULT '0' COMMENT '顶部导航',
  `payee` varchar(50) NOT NULL DEFAULT '' COMMENT '收款人',
  `bankcard` varchar(50) NOT NULL DEFAULT '' COMMENT '银行卡',
  `logourl` varchar(200) NOT NULL DEFAULT '' COMMENT '店铺logo',
  `intro` text NOT NULL COMMENT '图文详细页内容',
  `bankname` varchar(50) NOT NULL DEFAULT '' COMMENT '开户银行',
  `notice` varchar(200) NOT NULL DEFAULT '' COMMENT '店铺公告',
  `noticetime` varchar(20) NOT NULL DEFAULT '' COMMENT '公告时间',
  `banner` varchar(200) NOT NULL DEFAULT '' COMMENT '首页banner',
  `allow_distribution` char(1) NOT NULL DEFAULT '0' COMMENT '是否允许分销加盟 0,1',
  `commission_rate` float NOT NULL DEFAULT '0' COMMENT '佣金分成',
  `product_source` char(1) NOT NULL DEFAULT '1' COMMENT '分销商品来源 0, 1 不限, 本店铺',
  `ad_img` varchar(200) NOT NULL DEFAULT '' COMMENT '分销引导广告（图片）',
  PRIMARY KEY (`id`),
  KEY `did` (`did`) USING BTREE,
  KEY `tpid` (`tpid`) USING BTREE,
  KEY `footerid` (`footerid`) USING BTREE,
  KEY `headerid` (`headerid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_diyform` */
 DROP TABLE IF EXISTS `vifnn_diyform`;/* MySQLReback Separation */ CREATE TABLE `vifnn_diyform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `age` tinyint(2) NOT NULL,
  `qq` int(11) NOT NULL,
  `photo` int(11) NOT NULL,
  `tel` int(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_diyform_set` */
 DROP TABLE IF EXISTS `vifnn_diyform_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_diyform_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(30) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `info` varchar(100) NOT NULL,
  `jion_num` int(5) NOT NULL,
  `select_name` varchar(200) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_diymen_class` */
 DROP TABLE IF EXISTS `vifnn_diymen_class`;/* MySQLReback Separation */ CREATE TABLE `vifnn_diymen_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `pid` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `keyword` varchar(300) NOT NULL DEFAULT '',
  `url` varchar(300) NOT NULL DEFAULT '',
  `is_show` tinyint(1) NOT NULL,
  `sort` tinyint(3) NOT NULL,
  `wxsys` char(40) NOT NULL,
  `text` varchar(500) NOT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `nav` varchar(200) DEFAULT NULL,
  `emoji_code` varchar(16) NOT NULL COMMENT '图标码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_diymen_set` */
 DROP TABLE IF EXISTS `vifnn_diymen_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_diymen_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `appid` varchar(18) NOT NULL,
  `appsecret` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_diymen_set` */
 INSERT INTO `vifnn_diymen_set` VALUES ('26','zrtmca1421056092','1','1'),('27','qcjnep1444636005','1','1'),('28','zcbtlh1444656817','',''),('29','ezrehr1458789061','wxfd7387d022ed6f94','11699dbda9e8bb7c1ed7997b2bff735c'),('30','eclmus1460363554','wx84f0e5b056e6b4b2','1'),('31','bzwmqd1462841786','wx45f3c62ce7a9c4b7',''),('32','zbemii1462891866','wx4658d70c5473c824','b7c9075990e6788bb0bc2ca891ba6ec6'),('33','ywxgfh1465574691','wx4658d70c5473c824','b7c9075990e6788bb0bc2ca891ba6ec6');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dizwifi_device` */
 DROP TABLE IF EXISTS `vifnn_dizwifi_device`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dizwifi_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `ssid` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `bssid` varchar(30) NOT NULL DEFAULT '',
  `token` varchar(30) NOT NULL DEFAULT '',
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dizwifi_homepage` */
 DROP TABLE IF EXISTS `vifnn_dizwifi_homepage`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dizwifi_homepage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL,
  `template_id` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `bar_type` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_domain_verify_code` */
 DROP TABLE IF EXISTS `vifnn_domain_verify_code`;/* MySQLReback Separation */ CREATE TABLE `vifnn_domain_verify_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `top_domain` varchar(125) DEFAULT NULL,
  `server_name` varchar(125) DEFAULT NULL,
  `verify_code` varchar(60) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `create_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `TOP_DOMAIN` (`top_domain`) USING BTREE,
  KEY `SERVER_NAME` (`server_name`) USING BTREE,
  KEY `GET_VERIFY_CODE` (`top_domain`,`server_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_donation` */
 DROP TABLE IF EXISTS `vifnn_donation`;/* MySQLReback Separation */ CREATE TABLE `vifnn_donation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL,
  `name` varchar(30) NOT NULL,
  `note` varchar(300) NOT NULL COMMENT '介绍',
  `content` text NOT NULL COMMENT '详情',
  `company` varchar(200) NOT NULL COMMENT '捐款接受机构',
  `fixed_money1` smallint(5) unsigned NOT NULL DEFAULT '20' COMMENT '固定捐款金额',
  `share_content1` varchar(90) NOT NULL COMMENT '众筹宣传语范例1',
  `share_content2` varchar(90) NOT NULL COMMENT '众筹宣传语范例2',
  `creattime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `share_num` int(10) unsigned NOT NULL COMMENT '分享次数',
  `keyword` varchar(20) NOT NULL COMMENT '关键词',
  `reply_title` varchar(50) NOT NULL COMMENT '回复标题',
  `reply_content` varchar(200) NOT NULL COMMENT '回复内容',
  `reply_pic` varchar(260) NOT NULL,
  `pic` varchar(260) NOT NULL,
  `fixed_money2` smallint(5) unsigned NOT NULL DEFAULT '50',
  `fixed_money3` smallint(5) unsigned NOT NULL DEFAULT '100',
  `fixed_money4` smallint(5) unsigned NOT NULL DEFAULT '200',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态（0：关闭，1：正常）',
  `starttime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '结束时间',
  `logo` varchar(300) NOT NULL,
  `tip` varchar(15) NOT NULL COMMENT '募捐提示语',
  `share_pic` varchar(200) NOT NULL COMMENT '分享图片',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可以删除（0：可以，1：不可以）',
  `account` varchar(30) NOT NULL COMMENT '款项去向',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_donation_dynamic` */
 DROP TABLE IF EXISTS `vifnn_donation_dynamic`;/* MySQLReback Separation */ CREATE TABLE `vifnn_donation_dynamic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL,
  `did` int(10) unsigned NOT NULL COMMENT '活动ID',
  `image_id` int(10) unsigned NOT NULL COMMENT '图文ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `did` (`did`) USING BTREE,
  KEY `image_id` (`image_id`) USING BTREE,
  KEY `sort` (`sort`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_donation_gift` */
 DROP TABLE IF EXISTS `vifnn_donation_gift`;/* MySQLReback Separation */ CREATE TABLE `vifnn_donation_gift` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL,
  `did` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(8,2) NOT NULL,
  `pic` varchar(200) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `did` (`did`,`money`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_donation_medal` */
 DROP TABLE IF EXISTS `vifnn_donation_medal`;/* MySQLReback Separation */ CREATE TABLE `vifnn_donation_medal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL,
  `did` int(10) unsigned NOT NULL,
  `num` smallint(3) unsigned NOT NULL COMMENT '奖牌数',
  `money` decimal(8,2) NOT NULL COMMENT '捐款的金额',
  `pic` varchar(200) NOT NULL COMMENT '奖牌图片',
  `dateline` int(10) unsigned NOT NULL,
  `note` varchar(15) NOT NULL COMMENT '奖牌说明',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `did` (`did`) USING BTREE,
  KEY `money` (`money`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_donation_order` */
 DROP TABLE IF EXISTS `vifnn_donation_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_donation_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `did` int(10) unsigned NOT NULL,
  `orderid` varchar(20) NOT NULL,
  `token` varchar(80) NOT NULL,
  `wecha_id` varchar(80) NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `paid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `isanonymous` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否匿名（0:否，1：是）',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `token` (`token`,`wecha_id`,`sid`) USING BTREE,
  KEY `did` (`did`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_donation_set` */
 DROP TABLE IF EXISTS `vifnn_donation_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_donation_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `did` int(10) unsigned NOT NULL,
  `token` varchar(80) NOT NULL,
  `circle_name` varchar(10) NOT NULL COMMENT '圈子名称',
  `money` decimal(8,2) NOT NULL COMMENT '积分获得基数',
  `one` decimal(8,2) NOT NULL COMMENT '获得1个奖牌的条件',
  `two` decimal(8,2) NOT NULL COMMENT '获得2个奖牌的条件',
  `three` decimal(8,2) NOT NULL COMMENT '获得3个奖牌的条件',
  `four` decimal(8,2) NOT NULL COMMENT '获得4个奖牌的条件',
  `five` decimal(8,2) NOT NULL COMMENT '获得5个奖牌的条件',
  `dateline` int(10) unsigned NOT NULL,
  `agreement` text NOT NULL COMMENT '协议',
  `tip` varchar(15) NOT NULL COMMENT '感谢语',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `did` (`did`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_donation_share` */
 DROP TABLE IF EXISTS `vifnn_donation_share`;/* MySQLReback Separation */ CREATE TABLE `vifnn_donation_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `did` int(10) unsigned NOT NULL,
  `wecha_id` varchar(70) NOT NULL,
  `token` varchar(70) NOT NULL,
  `content` varchar(100) NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `did` (`did`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_donation_value` */
 DROP TABLE IF EXISTS `vifnn_donation_value`;/* MySQLReback Separation */ CREATE TABLE `vifnn_donation_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(10) unsigned NOT NULL,
  `token` varchar(80) NOT NULL,
  `wecha_id` varchar(80) NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `did` (`did`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dream` */
 DROP TABLE IF EXISTS `vifnn_dream`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dream` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `content` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('1','梦见父母','梦见自己成了幼儿与父母相处  幸运的事情即将发生。可以尝到美昧可口的咖啡、得到电影招待券等等。你将不觉莞尔一笑。梦见和父母快乐地有说有笑  家中将起波澜。对父母将感觉厌烦，会有离家出走的行为。你还未成年，不要因轻率急躁而遗恨终身，要多多自重。梦见父母离婚  朋友运不如意。你无心的一句话，将引起误解。如果置之不理，朋友将离你而去，要设法解释清楚才好。梦见受母亲疼爱  在爱情方面将有乐事。约会的地方最好尽量选择幽静的地方，诸如图书馆、博物馆、黄昏的公园等等。梦见被父亲大骂一顿  健康方面有不良征兆。尤其要注意的是意外事故，在上下车、横穿车道时要特别小心哪。梦见离开父母而成孤苦伶仃  爱情方面将有很大的幸运。如果有意中人，不必迟疑可以进攻，绝对不会被封杀出局。因为这是梦中注定的'),('2','梦见护士',' 已婚女子梦见护士  不久会怀孕，得贵子。 少女梦见有一群美貌的护士  很快要出嫁。 少女梦见和护士争吵  婚事不顺利，迟迟不能出嫁。 病人梦见护士  痛苦很快要过去。'),('3','梦见老师',' 梦见受老师称赞  在学业方面乌云密布。由于连日的熬夜，在课堂上竟开始打陀结果受到老师批评。这就是所谓反梦。 梦见受老师责骂  家人关系极佳。对双亲能克尽孝道，你将令人刮目相看。也许每个月的零用钱会大幅度增加呢。 梦见到老师家里拜访  人际关系的运势衰退的预兆。头顶上有一颗争执之星，要注意你的言行举止，防止争执，尤其脾气不可暴躁。 梦见正在上课  读书运渐入佳境。将有指点迷津的同学出现，以此为契机，你的读书欲将大增。也就是说，你将会有很大的干劲。要努力喔! 梦见遇见校长  这是上学恐惧症。不要一天到晚抱着书本，过分拘泥于成绩，有时也要放松心情，尽量参加其他活动。否则你的神经将很快衰弱。 梦见异性老师向你亲密攀谈  爱情运将下降，情人之间的感情开始变得索然无味，最好改变一下约会的方式'),('4','梦见男孩','梦见男孩，是祥兆。女人梦见男孩，会生病。梦见漂亮的男孩，朋友会忘恩负义。梦见生男孩，生活会幸福、恬适。梦见男孩夭亡，大难将降临。女人梦见自己将要生一个男孩，意味着会过上幸福舒畅的生活'),('5','梦见皇后',' 男人梦见皇后  会经济受损。 女人梦见皇后  丈夫会心情愉快。 囚犯梦见皇后  很快能恢复自由。 叛国者梦见皇后  不久会成为国家领导人的宠儿。 商人梦见皇后  出国做生意能发大财。 已婚女人梦见自己成了皇后  很快会与丈夫分离，或孩子生病，或者由于丈夫失业，左支右绌。 未婚女子梦见自己当了皇后  嫁到一个有名望富有的家庭。 男人梦见同皇后握手  会受到国家尊重，官运亨通。 男人梦见同皇后握手  会娶富人家的小姐为妻。 已婚女人梦见和皇后握手  会身居高位。 未婚女子梦见和皇后握手  想与意中人结为伴侣，但却会遭到父母的阻拦。 梦见和皇后争吵  能发大财'),('6','梦见朋友',' 梦见大伙儿一起去旅行  最近，很可能发生快乐的事。如亲友参加电视猜谜得奖；老师临时有事，自习时间增加等等。 梦见与朋友一起挨老师责骂  考试运极度好转。考试前所看的复习题，全部出现在试卷中，必将获得近于满分的成绩。 梦见与朋友一起旷课到处游荡  在健康方面乌云密布。放学途中买东西吃，将食物中毒；咬紧牙关熬夜，眼睛将出毛病、只好去看医生，不料医生临时休业真是霉运当头! 梦见有朋友来玩  异性运上升。将有新的浪漫史产生。爱情战的武器，不外乎是电话。最幸运的黄金时段将是晚上八点到九点。 梦见与朋友一起又吃又喝  在金钱方面要多加小心。不要花无谓的钱，搞得囊空如洗。 梦见与朋友并排读书  进行新尝试的最好时机。参加社团、练技习艺，只要是日常想做的事，立刻开始行动吧。 梦见与朋友一起工作  人际关系好。没有钱的时候；有事情必须要别人帮忙时候，朋友一定会伸出援助的手。 梦见朋友与异性很要好  爱情运将停滞。与情人之间总是格格不入，波折迭起。选一处喝咖啡的地方，也争执不休。 梦见与朋友玩笑嬉戏  在异性方面将有问题出现。对性的诱惑千万要小心，要保持清醒与理智。 梦见一群好友反目而分裂  人际关系将不如意。无意间发觉最信赖的朋友在背后说你，因此受到莫大的打击。要治愈这心里的创伤，要一段很长的时间');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('7','梦见守财奴',' 梦见一毛不拔的人  朋友会吵嘴。 梦见与吝啬鬼交朋友  要遭不幸。 男人梦见自己成了铁公鸡  一毛不拔，生意能赚大钱。 已婚女人梦见自己小气  婆婆家的处境会很令人担忧。 梦见和吝啬人吵架  预示要交新朋友。 梦见偷拿守财奴的东西  会身居高位。 梦见铁公鸡送钱给自己家  会被偷窃');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('8','梦见尼姑',' 男人梦见女出家人  无数的灾难会临头。 女人梦见与出家人交谈  丈夫家的人能和睦相处，生活愉快。 少女梦见与女出家人发生争吵  是凶兆，亲人会受辱。'),('9','梦见小孩',' 梦见抱起婴儿  财运相当顺利的象征。你的存款将大幅度增加，但绝对不可借给别人，因为要不回来的可能性很大。 梦见欺负小孩儿  人际关系有阴影。你的隐私将被周围的人发觉。必须小心加以防范，别忘了隔墙有耳! 梦见与儿童一起游戏  学校里将发生快乐的事。新近成为会员的后生小辈，竟是英俊无比的异性每天都盼望快点下课'),('10','梦见人群',' 梦见许多穿着华贵服装的人聚集在一起  未婚者将会喜结良缘。 梦到穿脏衣服的人聚集在一起  做梦人的亲属会有人与世长辞'),('11','梦见疯子',' 少女梦见与女出家人发生争吵  是凶兆，亲人会受辱。 姑娘梦见疯子  会嫁给一个富有、如意的男子。 跛子梦见疯子  会找到忠诚的仆人。 梦见妻子疯了  她会把家安排得井井有条'),('12','梦见兄弟姊妹',' 梦见受兄或姊欺负而心有不甘  兄弟运蕴酿着波折。仅仅为了一支铅笔、或一块橡皮，就要起一场争执。这时，最好由父母出面做公道人。 梦见兄弟或妹妹将出去游玩  在人际关系中将有幸运来临。可能由于趣味相投，将结识新朋友。只要坦诚相待，必然可以成为心腹之交。 梦见兄弟吵架  比赛运衰减。围棋、象棋、电视娱乐比赛不管参加任何一种比赛，准输无疑。这种状态将持续半年，要有心理准备! 梦见与兄弟姊妹合力做些事情  学业方面将有进步。以往难缠的科目，也将全部都有好分数。有可能得到老师当众表扬，使你神采飞扬。 梦见与兄弟或姊妹远离  在异性方面将有幸运。可能接到某同学写的热情洋溢的情书。这时将如何应付?这是你个人的事。 梦见与兄弟姊妹同盖一床被子  雨过天睛，健康运上升。今后一个月，虽然有一点不如意，但过后将精力充沛，可以过一段无病无痛的日子'),('13','梦见军队','梦见军队开来或军队处于立定姿势，这是好的征兆。梦见军队离去，意味着将遭不幸。梦见军队打败了，倒霉的日子将要到来。梦见军队胜利了，寓意着要交好运。'),('14','梦见清道夫',' 梦见清洁夫  要遭厄运。 女人梦见清扫人  会忍受丈夫分离的痛苦。 梦见当了清道夫  前途无量。 商人梦见做清道夫的工作  生意会兴旺，能发大财。 梦见与清扫工吵架  会臭名远扬。 梦见和清洁工交朋友  会名声大噪，得财进宝'),('15','梦见祖父母',' 梦见祖父母给你零用钱  将有极佳的金钱运。但仍有浪费的倾向，所以出去逛街购物时要有节制。 梦见祖父母责骂母亲  健康方面亮起红灯。虽有强健的身体，也不可过信自己的体力。 必须保持良好的生活规律，要经常运动，加上充分的营养及休息才可保持健康。 梦见帮祖父母捶背  技能方面将进步。这将是练习弹奏吉他的良机，每天加紧练习吧! 梦见祖父母躺在病床上  家中可能发生纠葛。你与双亲及兄弟强能发生争执，注意不要任性。'),('16','梦见婴儿',' 孕妇梦见婴儿，则无象征意义。 梦见抱着婴儿，象征着梦者将会有所收获，不久之后将会得到对于来说很重要的东西。 梦见婴儿笑，象征着人际关系的良好，您能以诚待人，且身边会有很多。 梦见婴儿长牙，象征着计划的顺利实施，您能得到贵人的帮助，不久之后就会有好消息。 梦见婴儿说话，可能是在提醒您最近您会遇到麻烦，总有人从中做怪，也就是犯小人。 梦见婴儿死了，是不详之梦，表明自己计划和希望的破灭，您已经或者将要失去对自己来说很重要的东西。 梦见婴儿哭，表示当前有些压抑的情绪，使得自己心烦意乱，这时候最好静下心来，不要主动出击，做事低调些。'),('17','梦见野蛮人','男人梦见野蛮人，小心啊女人梦见野蛮人，丈夫家里会发生吵架。梦见与野蛮人打斗，是不祥之兆，灾祸会降临。商人梦见会见未开化的人，不久要出国，能发大财。受指控的人梦见未开化的人，会被驱逐出境。旅游者梦见野蛮人，旅行会愉快'),('18','梦见邻居','梦见邻人出现，有火难之灾。火灾固然要小心，也要注意开水、火柴等烫伤。但所梦见的如果是隔壁的邻居，就不会有危险，尽可放心。梦见与邻居同辈的异性，爱情将有新局面。表示你对爱情已有美好憧憬，一定会对某一个人产生爱情。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('19','梦见王子公主',' 一般梦中的王子、公主多代表着自己或兄弟姐妹。而梦中的国王、女王则代表你的双亲。 梦见王子或公主，则象征着这些日子，也许会与善意的人吵架');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('20','梦见小贩',' 男人梦见小贩  会与朋友分道扬镳。 已婚女人梦见小贩  会与丈夫家的人分开另过，独自操持家务。 梦见与小贩交谈  是好的征兆，丈夫会勤俭持家。 女人梦见与小贩交谈  会有一个装饰豪华具有现代化的住宅、华贵的服装、舒适的生活，一切应有尽有。 梦见与小贩吵架  陌生人会骗走自己的财产。 梦见和小贩交朋友  生意会好转，发大财。 官员梦见自己成了小贩  会被降职，最后只好提出辞职。 商人梦见自己成了小贩  生意会萧条，有可能倒闭。 病人梦见自己成了小贩  要遭厄运，尽管请西医大夫和中医治疗，但病情仍不见好转。 大学教师梦见自己成了小贩  是祥兆，会扬名天下，世界许多国家会邀请他发表演说。失业者梦见自己成了小贩  会接到好几个机构的聘书，但是都难以胜任'),('21','梦见医生',' 梦见医生  亲人会卧床不起。 病人和久病痊愈的人梦见医生  病情会加重，或突然病倒。 梦见与医生交谈，或向医生咨询  会身体健康，延年益寿。 病人梦见和医生谈话，或请教医生  不久病情会好转。 梦见与医生争吵，是不祥之兆  要遭受重大损失。 梦见自己当了医生或西医大夫  不久会被辞退，或生意受到冲击。 梦见去请医生  会与德高望众、受人民尊重的人建立友好关系。 梦见与医生交朋友  不用求人送礼，就能发财。 女人梦见丈夫当了医生  会患子宫病。 梦见侍候医护人员  生意会起伏不定，生活动荡不安'),('22','梦见丈夫',' 梦见担心丈夫头发掉落变成秃顶，这是用梦境体现了对 丈夫 的无能、软弱的嫌恶和怨恨。 梦见丈夫有第三者,可能是你对你们的婚姻在潜意识中总觉得有压力。也许是你的丈夫很优秀，也许是你总是看低自己的魅力，所以你在内心深处总会有危机感，总有着隐隐约约的压力和担心。梦仅仅是现实在我们脑海中扭曲的反映，梦反映什么不重要，重要的是通过梦境我们可以更多地了解自己的内心，更好的改进自己心理状况。 梦见丈夫睡在别人的床上,有这样的梦,实质上说明的情况是你爱老公的程度比不了他爱你的程度. 另外,你已经很习惯有他的日子了,但有的时候,他说不定比你还喜欢吃醋.......呵呵.既然这样,梦见这个,就不是坏事,好好珍惜这份感情吧.'),('23','梦见烈士',' 梦见烈士  会受到人们的尊重，不断进步。 梦见女烈士  要发大财。 梦见受到烈士的责骂  处境对自己极为不利。 梦见自己成了烈士  会灾难临头'),('24','梦见警察',' 梦见警察站着  是危险的兆头。 梦见自己被逮捕  会成为政府官员所喜欢的人物。 惯犯梦见警察抓人  多次作案，能发大财。梦见与警察交谈  会被提升。 女人梦见与警察交谈  丈夫的保镖会受伤。 囚犯梦见与警察谈话  很快会被释放。 商人梦见与警察交谈  要提防竞争对手。 领导人梦见与警察交谈  政府和警察会非常尊重自己。 梦见与警察吵架  是凶兆，预示仇人和强盗在威胁着自己。 未婚男子梦见与警察争吵  会带着自己的情侣逃跑。 男人梦见请求援助  作梦人会幸福安全。 女人梦见求助于警察帮助  很快要出狱。 梦见挨警察的打  会贪污公款，损失惨重。 梦见自己当了警察  会威信扫地。梦见自己穿着警服  会受到刑事案件的牵连'),('25','梦见导师',' 男人梦见自己的 导师 ，一切都会顺心如意。 女人梦见自己的 导师 ，要遭欺辱。梦见新的 导师 ，会遭受损失。 老叟梦见与自己的 导师 交谈，不久要与世长辞。 商人梦见和自己的 导师 谈话，吸收新的合股人，能发大财。 梦见与 导师 吵架，会家破人亡。 学生梦见和 导师 交谈，会因生活困难。中途辍学'),('26','梦见队伍',' 梦见迎亲的队伍  家里要死人。 女人梦见迎亲队伍  丈夫家里要吵架。 已婚男人梦见参加迎亲队伍  会身居高位，人们都有求于自己。 已婚女人梦见参加迎亲队伍  不久会怀孕，能生一个漂亮的男孩。 未婚男女梦见迎亲队伍  会喜结良缘。 未婚男子梦见参加迎亲队伍  会与恋人各奔东西。 囚犯梦见加入迎亲队伍  很快会获得自由。 病人梦见参加迎亲队伍  病情会恶化。 梦见参加政治或宗教游行  会有好消息。 政治领导人梦见加入政治或宗教游行  会受到国家领导人的尊重。旅游者梦见参加政治或宗教游行  会发生车祸');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('27','梦见名人',' 梦见接到喜欢的朋友来信  在异性关系上，将有快乐事发生。在朋友的生日派对中，被介绍认识几个异性，其中也许有上个喜欢你的人。爱情可能就此萌芽 梦见受著名的运动选手指导  健康方面将有不韦。尤其社团活动时，发生事故或受伤的可能性很大。 这个时候，最好避免练球练得太晚。 梦见与最喜欢的明星亲密相处  财运将大幅度好转。多余的支出减少，零用钱到了月底还有很多。借给朋友的钱，也一定可以收回。 梦见与外国电影明星交谈  朋友运上升的前兆。这时可以和几个要好的朋友计划郊游。这段快乐的时光将可以增强你与朋友之间的友谊。 梦见与首相握手  遭受意外事故的可能性极大。譬如向摇着尾巴走来的狗伸出友谊的手，却被咬等倒霉到了极点。 梦见与历史上的名人会见  亲友将遭受病难的预兆。要给予病痛缠身或体弱多病的朋友婉转的安慰');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('28','梦见同学',' 梦见同性的同学反映你现在人际关系上出现了问题。 梦见异性同学则表示你对朋友有不满的态度，反映了你现在被孤立而寂寞的心态。 梦见与同学打架，人际关系运上升。你跟任何人都可以大胆而积极地交往，周围的人对你也必然坦诚相待，绝不会发生冲突'),('29','梦见英雄',' 中年人梦见自己成了英雄  是身强力壮的兆头。 老年人梦见自己成英雄  会溘然而逝。应该尽早立遗嘱，分家产。 病人梦见自己成了英雄  病情会恶化，如果他的八字好，会得救'),('30','梦见上司','梦见上司，现实生活中想发挥自己的能力但受到阻挠。梦到这个梦时还要注意工作上可能将会惹麻烦梦见上司不批准自已请求，预示工作或工作环境有小麻烦。梦见与上司同行就是与麻烦同行。梦见上司生病，预示麻烦会没有。梦见上司来访，表示缺乏自信。'),('31','梦见同事','梦见和工作伙伴商讨事情，暗示计画可能受到阻挠或是会停滞不前。梦见商讨或交涉成功的话，表示你现实生活中将遭遇困难'),('32','梦见小偷','原版周公解梦内与小偷相关的内容：赶贼入市不出凶；强贼入宅主家破；与贼同行大吉利；赶贼行见者大吉。现代释梦：梦见小偷，暗示你最近情绪不太稳定，财运有起落。但是商人梦见小偷，预示生意兴隆。梦见自己成了小偷，预示有财运，钱财上会有意想不到的收获。梦见发现小偷偷东西，表示你可能正一步步制订某项计划，接近某个目标。另外，梦见小偷，还可能表示你有不正当的性行为。梦见自己和小偷同行，预示你将发财。梦见你是个小偷并被警察追赶，预示你进行的事业将陷入困境，你开展的社交关系也将陷入僵局。梦见你追赶或抓获一个小偷，意味着你将最终在较量中赢得对手的尊重。梦见自己认识的某个人成了小偷，则表示潜意识中你不信任那个人，虽然在生活中，你可能并未家常到这一点。梦见有小偷到自己家入室行窃，提醒你近期一定要小心谨慎，家里可能有会遇到祸事。梦见小偷入室窥视，环视屋内，表示你对性的好奇心很重，有偷窥欲。如果小偷没有发现你就离开了，表示你曾有一段不为人知的秘密性关系。如果小偷发现了你并攻击你，则暗示你最近有强烈的欲望。'),('33','梦见孤儿','梦中的孤儿，是人性脆弱一面的代表，往往表示你内心孤独渴望被关爱。如果梦见自己变成了孤儿，这是提醒做梦人必须要摆脱内心的依赖，独立自主，自力更生。如果梦见自己在照顾孤儿，预示你可能会得到他人的帮助。如果梦里看见街边的流浪儿，意味着工作上会有困难。梦见慰问孤儿，预示他人的忧虑将触动你的同情心，并将最终促使你牺牲掉个人的享乐。梦见与你有关的孤儿，预示你的生活将增添新的责任，并将由此导致你与某个朋友或某个爱好相同的人之间产生疏远。'),('34','梦见妻子','梦见拥抱妻子，是不祥之兆，会与妻子分居。梦见与妻子拌嘴，夫妻恩爱，生活幸福、美满。囚犯梦见与妻吵架，很快能见到妻子。梦见和妻分离，会更加宠爱妻子。梦见找了一位好吵闹的妻子，生活会幸福、舒适。梦见妻子疯了，寓意着妻子会把家安排得井井有条。梦见妻子十分疲乏，会与妻子分离。丈夫梦见妻子年青了许多，预示家庭幸福、美满。丈夫梦见援助妻子，夫妻生活会很幸福美满。梦见受到妻子的表扬，妻子会行为不轨，令人厌恶。'),('35','梦见和尚','梦见僧人或和尚，吉兆，会有好运。梦见僧侣念经，是死亡的暗示。梦到自己出家，表示重生或疾病可好转。遇高僧说教，表示将开拓人生大道。梦见出家人的画，生活富裕安逸。梦见听和尚或僧人说教，自己的事业会走上正轨。'),('36','梦见军人','梦见自己成为军人，预示梦者生活会出现新的转机。梦见别人成为军人，意味着梦者事业征途上一切井然有序。梦见军人在站岗，意味着梦者在未来的工作中需要提高警惕，防止小人捣乱。梦见军人休假，表示梦者已完全处于一种安全和谐的环境之中。女人梦见军人，表示潜意识内对婚姻的担忧。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('37','梦见病人','梦见自己成了病人，预示计划被延迟，或你会得到别人的帮助。梦见别人是病人，表示你会有机会寻求朋友、长辈的帮助。梦见家里有麻疯病病人来访，意味着将会有人登门拜访贵府，向你进行宣传。梦见自己成为精病患者，将跨过障碍和难关，生活越来越安定、富裕的征兆。梦见病人逐渐恢复健康，暗示梦者进行中的事情或计划的事情一帆顺的祥梦。梦见病人在歌唱，这是家里发生等不吉利的事情的征兆。梦见患上传染病的人吃后痊愈，这是将脱离某种组织或团体的意思。梦见精神病病人查看自己的，这是患病或诸事不顺的征兆。梦见病人出院回家，这是暗示梦者祈求一切安好，身体健康的梦。梦见去探望某个病人，梦中的病人即将获得痊愈的征兆。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('38','梦见婴儿','梦见婴儿，一方面，暗示了你自己内心中脆弱、渴望被爱的一面；另一方面，则预示你的自我发展，生活进展中将会转运，得到新机会和好运，之前的辛勤有回报等等。梦见漂亮可爱的婴儿，预示你会有好运气。梦见非常难看的婴儿，则象征可能会有信任的人捣鬼欺骗你。梦见自己抱起婴儿，象征着梦者将会有所收获，不久之后将会得到对于自己来说很重要的东西。梦见婴儿笑，象征着人际关系的良好，您能以诚待人，且身边会有很多。梦见婴儿长牙，象征着计划的顺利实施，您能得到贵人的帮助，不久之后就会有好消息。梦见婴儿说话，可能是在提醒您最近您会遇到麻烦，总有人从中做怪，也就是犯小人.梦见婴儿死亡的梦境，是不详之梦，表明自己计划和希望的破灭，您已经或者将要失去对自己来说很重要的东西。梦见婴儿哭，表示当前有些压抑的情绪，使得自己心烦意乱，这时候最好静下心来，不要主动出击，做事低调些。梦见婴儿哭，并且导致梦者心烦意乱，则预示将有不愉快的事情发生，也可能是你最近身体欠佳。梦见刚出生的孩子就开始直立行走，预示你的工作成绩，将得到赞誉好评。梦见生病的婴儿，表示你在工作或恋爱中可能会遭受挫折。如果梦见小孩丢失，则表示你现在遇到的麻烦或者担心，将会消除，心绪将重获安定，走上逐渐发展的正轨。梦见刚出生的婴儿在大小便，预示可能会遇到不吉利的事，也可能你信赖的人，会令你陷入尴尬境地。已婚但还没有孩子的人来说，梦见婴儿，有时则可能仅仅是表示心中想要孩子的愿望。初为父母的人，梦见婴儿要窒息或在危险中，通常表示了对孩子的强烈关心。孕妇梦见婴儿，则无象征意义。梦见自己的孩子出生，是将要怀孕，或发财、有丰厚进项的预兆。梦见有陌生人抚摩婴儿，暗示你近期运气不佳，会遇到倒霉事儿。'),('39','梦见姐姐','男人梦见自己的姐姐，是祥兆，能长寿；女人梦见未婚的姐姐，额外开销会突然增多。女人梦见已婚的姐姐，会与丈夫家的一个女性发生争吵。梦见给姐姐礼物，侵吞公款，会破财。梦见与姐姐吵嘴，会越来越富。梦见去姐姐的家，贵客会登门。梦见与姐姐交谈，会有好消息。梦见姐姐去世，她会长寿。梦见死去的姐姐，会有好运气，家庭和睦。'),('40','梦见陌生人','梦见陌生人或者不认识的人是一种好的暗示，如果你梦到从未见过的人的话，这暗示着在最近的将来，你将有恋爱的机会。(但是婴儿则例外)梦见陌生小男孩，或许会有一见钟情发生，但可惜的是和他似乎无法顺利发展。梦见陌生小女孩，会有你喜欢的人已有恋人的传言。但单纯为误传的可能性很高，所以请仔细去确认两者事实上的关系。梦见陌生年轻男孩，会有花花公子型的男孩接近你。但是如果你答应的话，将来可能会后悔。梦见陌生年轻女孩，似乎会在街上巧遇喜欢的男孩。外出时，请打扮得漂亮一点。梦见陌生男性中年，经由朋友的介绍，似乎会有发展成美丽恋情的机遇。梦见陌生女性中年，会得到自己所喜欢的男孩的消息。若能把握机会展开攻势的话，会有好结果产生。梦见陌生男性老年，似乎会得到从未和他讲过话、意想不到的男孩的青睐。梦见陌生女性老年，和不怎么样的男孩的关系，似乎会引起谣传。要控制自己容易招惹误会的言行举止。梦见请求陌生人原谅，预示将经历悲伤，遭受挫折。梦见陌生人表扬自己，寓意着会受到侮辱。梦见陌生人皱眉，预示你可能会结交新朋友。梦见躺在陌生人的床上，夫妻将要离婚。'),('41','梦见舅舅','梦见舅舅，会受到人们的尊重。女孩梦见舅舅，会陷入困境。犯人梦见舅舅，很快会获得自由'),('42','梦见裁缝','裁缝代表了辛勤劳作与生活转机，是好的预示，梦见裁缝通常表示前一段的时间的努力会有所回报或者有好事即将发生。男人梦见裁缝，预示将发财致富，事业会上个新台阶。女人梦见裁缝，预示娘家或夫家会有大办婚礼这样的喜事。梦见自己成了裁缝，预示收入增加，经济条件转好。梦见请裁缝做新衬衣，预示家里可能将举行婚礼。梦见把裁缝叫到家里，预示子女将独立，长大成人，成家立业。梦见和裁缝交朋友，预示家里会增加额外的开销。梦见自己和裁缝吵架，预示经济上可能会承担损失。梦见与裁缝发生误会，表示你将对某项计划的结果感到不满与失望。梦见裁缝给你量尺寸，预示你将会因某事与他人发生争吵，从而使你们的关系趋于紧张。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('43','梦见双胞胎','梦见双胞胎，反映做梦人互相对立的个性以及矛盾的心理；梦见双胞胎在打架，表示内心中有强烈对立的自我。梦见双胞胎一起快乐地玩耍，表示你的内心中，虽然有不同的自我，但不同自我之间相处都很和谐。梦中的双胞胎身体瘦弱，表示你将亲临失望和痛苦的第一线。梦见四胞胎，则预示你可能要承受困难，但依然充满希望。男子梦见双胞胎，通常表示经过思虑和内心的斗争后，成家立业，事业成功。商人梦见双胞胎，预示最终会财源广进。病人梦见双胞胎，预示身体正慢慢恢复健康。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('44','梦见寡妇','梦见寡妇，通常预示你会有损失或内心悲伤。女人梦见寡妇，暗示自己对目前的生活状况表示担忧，对未来没有信心。男人梦见寡妇，表现了对女性的渴望，同时又想得到一位独立自主的女性青睐，能在事业上帮助自己。男子梦见和寡妇交谈，要小心有人造谣中伤你。梦见自己给寡妇钱，或帮助寡妇，预示你会有好运气，或者得到意外的回报。梦见和寡妇吵架，预示你会倒霉。如果梦见了披麻戴孝的寡妇，则象征你自己心中对死亡的恐惧。'),('45','梦见警察','警察是具有典型男性特征的形象，有权威、规范、良心的含义。梦见警察站着，是危险的兆头。梦见与警察吵架，要多加小心，预示你会有强盗或仇人威胁你。梦见挨警察的打，预示你可能会发生营私舞弊，贪污渎职的事情，造成严重损失。梦见自己当了警察，或是当侦探去调查案件，预示你在人际关系方面将遇到挫折，可能会受到别人的造谣中伤，名誉受损，或威信扫地，陷入困境，要细致冷静地应付。梦见自己穿着警服，会受到刑事案件的牵连梦见被警察追捕，表示你可能有有些想法和冲动，在内心深处感觉是道德准则或社会规范所反对的，会受到责备，因此感到不安。这个时候的警察是超我的象征，也就是良心的化身。也有可能表示你内心中一直压抑的，少年时代对生活中那个独裁专制的父亲的怨恨。梦见和警察交谈，暗示你会得到重视和提升。梦见接受警察的盘问或问讯，提醒你要提防自己的身体健康，你可能会患病。尤其要注意饮食，对食物中毒、痢疾、便秘等消化疾病更要格外当心。梦见向警察求助，预示你会安全幸福，克服困难。梦见自己被警察抓住，预示工作将取得显着成绩，或是学习成绩进步惊人，会受到领导、上司、老师的重视，令人刮目相看。梦见犯了罪被警察抓住，读书运最好的时刻。在全班的成绩普遍低的情况下，只有你一个人是九十分以上的好成绩，必能一鸣惊人。未婚男子梦见与警察争吵，会带着自己的情侣逃跑。男人梦见请求援助，作梦人会幸福安全。女人梦见求助于警察帮助，很快要出狱。女人梦见与警察交谈，丈夫的保镖会受伤。妻子梦见和警察谈话，可能表示丈夫将遇到危险。商人梦见和警察谈话，警告你要小心提防竞争对手。官员梦见和警察谈话，预示会得到政府和警察的尊重。囚犯梦见与警察谈话，很快会被释放。惯犯梦见警察抓人，多次作案，能发大财。'),('46','梦见哑巴','梦见自己成为哑巴，不祥之兆，要提防小人。梦见自己突然变成哑巴，想要张嘴却说不出话来，暗示可能有小人中伤你，你却找不出原因，让你举步艰难。梦见自己成为哑巴然后又好了，预示着自己最终战胜小人。'),('47','梦见明星','名歌星出现于梦中时，则暗示与朋友有口角之争，人际关系亦将有麻烦产生。要多留意朋友嫉妒、吃醋的心理。'),('48','梦见弓箭手','梦中的弓箭手，是恋爱与婚姻的代表。梦见弓弩手/弓箭手，预示你即将找到生命中的另一半，要好好珍惜。已婚人士梦见弓箭手，预示你的婚姻幸福美满，将与你的伴侣携手走过人生。'),('49','梦见孕妇','预示着梦者所进行的事情非常顺利，而且金钱上会有好的运气。 　　未婚女人梦见孕妇，预示难以出嫁，或出嫁后婚姻会出现问题，如争吵分歧等，让家庭不和。'),('50','梦见妓女','梦见自己是妓女，预示你将因自己表现恶劣，让正直的朋友看不起你。年轻女子梦见妓女，预示她会欺骗她心爱的人，让其相信自己是纯洁的，坦诚的。对于已婚女人，此梦过后，她将开始怀疑丈夫，因此不断地争吵。梦见与妓女相殴，梦主得情助势，门户昌荣气象之兆。技艺之人争长夺色。老者梦此，主有虚症，不祥。梦见与妓女为夫妻，大吉，梦此者夫妻昌吉也。未配者主得技艺美色之妻。如素爱烟花之人，为与妓女合心。梦见与妓女相别，吉，此梦阴事之非远离之象。凡事得理，疾病脱身。其占为浪子回头，花凋蝶散。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('51','梦见已故的祖父母','梦见已故的祖父带着农具去种地，父亲或家里的其他人会调动工作岗位或搬家。梦见已故的祖父赶着一头母牛来到院里或将牛栓在牛棚，预示即将迎来儿媳、家庭主妇或妻子，或者得到意外的财物。梦见已故的祖父抚摸孙子，在现实中里的孙子会患病。梦见已故的祖父背着孙子或领到屋外，预示近期内孙子会死亡。梦见已故的祖父母欲向自己说什么话，这是预示有事情将发生，需要倍加小心。梦见已故的祖父母再世后准备带着自己外出，这是警告你有可能由于意外的事故或疾病而死亡，又或者面临严重的忧患。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('52','梦见坏人','梦见坏人预示者你似乎正向往着危险的恋爱。例如想抢夺朋友的男友，或是想和有妇之夫的他度过危险偷情的一夜等等。如果你已有男朋友的话，梦见坏人预示着是否浮动的心正开始萌芽。'),('53','梦见犯人','梦见与犯人交谈，要遭厄运，要提高警惕，避免灾祸。梦见与犯人交朋友，暗示你可能会结交品行不良的朋友，给你造成严重损失。梦见与囚犯吵架，烦恼和灾祸会过去，将要过上称心如意的日子。梦见犯人逃走，病患即将好转。犯人梦见获得大赦或无罪释放，家里的房屋有危险'),('54','梦见帅哥','梦见一帅哥被我打了，这是你潜意识的作用，在现实上，你可能比较在意这样的帅哥，有想接近的冲动，但你把这种冲动压抑在内心深处它，然后在梦中把压抑释放了出来，成一个暴力动作。梦中知道对方是个帅哥，但模模糊糊地看不清样子，好幸福的感觉。代表你的内心深处已经厌倦了你男朋友。哎！解铃还需系铃人，是因为你的男朋友和你太少的接触，所以你会在梦里面梦不到他。你们要是天天都在一起做一些有意义的事。做一些比较开心的事。我想你晚上也能梦到他的。女人若梦见帅哥的话一般意味着以后可能会被人追求或代表着你希望有人来爱你，真诚对待你。'),('55','梦见日本人','梦见日本人，预示能从敌人的魔爪中逃跑出来。未婚者梦见日本人，预兆您的爱情可成功。但双方不可我行我素。待业者梦见日本人，说明您的财运不顺，需待时机。'),('56','梦见处女','梦见处女，预示你的事业将会福星高照。已婚女人梦见自己仍是处女，表示她会对自己曾经做过的某些事情感到羞愧和后悔，但悔过的心并不会给她带来好运。一个年轻女子梦见自己已不再是处女，预示她与男性之间行为的不失检点会损及自己的名誉。男人梦见和一个处女保持不正当关系，预示他不管如何努力也将无法完成原定的某项计划，别人的行为倒会给他带来很多麻烦；或预示他的理想会由于合作缺乏保障而无法实现。'),('57','梦见老情人','很多人都会梦到老情人，(即过去的男女朋友或自己曾爱慕的对象)首先说明你在心中还留有他的位置，不管这个位置是好是坏，至少他还占着一点分量。梦见老情人，表示一些消极的态度，和令你困扰的人际关系。梦见和对方关系很差，代表你的人际关系会转好，还有你和对方的关系有可能以另一种形式出现。梦见和对方关系很好、相处得很开心，是反映了你现在寂寞的心态。梦见和对方结婚，则代表你和他的关系已经划清界线，你是完全决绝的了。已有伴侣的朋友梦见老情人，往往显示梦者对身边伴侣有所不满，而伴侣无意作出迁就。梦者在现实生活中已有异心，但未找到可以取代伴侣的人，因而令脑海产生找寻旧档案的讯息。一些从前曾经爱慕而无结果的爱情，成为暂代品。但这却不足以表示你们即将分手，相反等你醒来好好考虑一下现实情况，会更加珍惜身边人，为对方做出改变。经常梦见老情人，这时就该检讨与爱侣的感情，若让情况持续，梦者一旦在现实生活遇到投缘的异性，多会不能自制，出现三角关系。梦见老情人再次伤害或背叛你，让你在梦中痛不欲生，这或许则是在提醒你，不要在感情上再犯相同的愚蠢错误，小心审视身边伴侣。单身的朋友梦见老情人，或许你真的觉得寂寞了，很想渴望爱情的到来。而在没有具体想象对象的时候，老情人就出现在梦中。这时候你就该好好调整下自己的状态，时刻准备着，抓住任何爱情的机会哦。'),('58','梦见第三者','梦见第三者，吉兆，预示着爱情会很甜蜜。'),('59','梦见白马王子','女人梦见白马王子，说明心里极度缺乏爱的感觉，有滥交的可能性。少女梦见白马王子，即将要交到男朋友，但一定不是自己称心如意或者能过一辈子的那种。未婚女子梦见白马王子，将和男朋友分手，婚姻大事将会无限期拖延。已婚女子梦见白马王子，家庭关系要出现危机，和丈夫将有离婚的危险，有外遇的可能性大于百分之六十。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('60','梦见仇人','梦见仇人，预示你将取得成就，困难将过去，未来会有好运，值得期待。梦见和仇人交朋友，预示你将结交很多新朋友，帮助你获得成功。梦见和仇人争吵，则预示你会遭受损失。梦见仇人难过，预示你会打赢官司，或是战胜敌手。梦见收到了仇人死亡的消息，预示你会有宽容又忠实可靠的朋友。商人梦见仇人，预示你能打败对手占领市场。商人梦见仇人攻击自己，预示生意上会遇到挫折。女人梦见丈夫的情妇，预示你将赢得丈夫。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('61','梦见美女','梦见与美貌女子交往，为破财之兆。 　　梦见跟一个陌生的同龄美女长成了好朋友，说明交朋友，希望自己的快乐苦恼能和别人分享和承担，可能现实中缺少这种朋友关系，有什么事情都是自己扛。'),('62','梦见喜欢的人',''),('63','梦见外星人','梦见外星人就在自己眼前，预示你有好运气，可能会有意想不到的收获。梦见外星人可能暗示着生活被某种外来因素所左右，或者对自己所处的环境还不太了解。'),('64','梦见毛主席','梦见毛主席，朋友运好转。与朋友将有顺利的交往；与双亲和兄弟之间的关系也将非常好。梦见和毛主席握手，表示健康良好，将会变得越来越健康。二，三天连续熬夜看手也无所谓，加油吧!未婚的人梦见和毛主席握手，您的恋情性急则不成，耐心则成功。未婚男女梦见毛主席，您的恋情问题会有，但要主动去解决，好运才会来。老人梦见毛主席预示出远门，佳，可获得利益。生意人梦见毛主席，说明您的财运可聚财。求学者梦见和毛主席握手，说明考试成绩一般。病人梦见和毛主席握手，说明这段时间您的运气：外表美观而内在空虚，所以要先充实内在，才有好运气。要提防小人诽谤。'),('65','梦见陆军 ','梦见整齐的陆军队列向你开来或立在原地，暗示你有勇往直前积极进取的精神，并会踏踏实实，稳步追求事业的发展。梦见壮观的陆军队列，或挺拔的陆军战士，有时也可能仅仅表示你对军人阳刚气质的向往，或是过去有过参军的愿望。男人梦见陆军，预示事业要大步向前，稳定发展。女人梦见陆军，预示生活舒适，生活质量稳步提高。'),('66','梦见风骚女子','梦见看到一位风骚的女子，表示你有狡猾的敌人需要你去征服。梦见你杀死一名风骚女子，意味着你的欲望会实现。年轻女性梦见风骚女人，梦到她的行径快要比得上风骚女子的行径了，意味着她需要男人的保护。'),('67','梦见久未见面的人','梦见久未见面的人时，寓意着此人将会很快归来。'),('68','梦见工人','通常来说梦中的男建筑工人，有父亲的意味，充满力量，搭建遮风避雨的房屋。梦见有一个建筑工或维修工修理你的房子，预示你将反思出你生活中的问题，并对它加以解决。梦中的房子象征自我。梦见一个工人在疏通管道，预示你将解决情感积郁的问题。梦见技工，或技工面对着一堆拆零的零件，象征自己面对着生活中一摊乱麻般的局面，并为要理清头绪，解决现状，感到异常苦恼。梦见井下作业的矿工，则有可能暗示你正在探索自己幽暗的内心深处。梦见管道工，则表示你对内心精神或情感的摸索。孕妇梦见管道工，还有可能是妇道医生的形象在梦里的显现。'),('69','梦见厨师','梦见厨师在准备宴会，喜庆的日子将要到来。梦见厨师教你烹调，在金钱方面有暗影出现。 这时很容在路上或公共汽车上遗失钱财，小心不要带太多的钱出门。'),('70','梦见陆军','梦见整齐的陆军队列向你开来或立在原地，暗示你有勇往直前积极进取的精神，并会踏踏实实，稳步追求事业的发展。梦见壮观的陆军队列，或挺拔的陆军战士，有时也可能仅仅表示你对军人阳刚气质的向往，或是过去有过参军的愿望。男人梦见陆军，预示事业要大步向前，稳定发展。女人梦见陆军，预示生活舒适，生活质量稳步提高。'),('71','梦见烧香的人','梦见烧香的人，表示你的感情已经成熟，你的另一半也很优秀，很受到肯定，在不久之后你们就能共结礼堂。'),('72','梦见神秘人','梦中你看见很神秘的人，则意味着你的运气会变好或变坏，而这也要看个人的长相是不是好看或难看，有没有畸形而定。'),('73','梦见亿万富豪','梦见成为亿万富豪，则近日营业将会损失惨重。'),('74','梦见跛子','梦见跛子，在遇到困境时你不会知道求助于朋友。梦见自己成了跛子，会遇到难以克服的困难。梦见爬墙时摔跛了腿，会取得成功。'),('75','梦见虐待狂','虐待狂象征着被误导的生命力，也暗示着清醒状态下的受虐天性。梦见自己变成虐待狂，人际上将出现大的问题，须学会自我反顾。梦见自己被虐待狂虐待，暗示着梦者在现实心里上渴望被虐待。'),('76','梦见贵人','梦见贵人表示你能够出人头地的机会很大，未来有一番作为。梦见领袖，则表示心灵上得到安详;如果梦见领袖在行事，则会受到赏识。一般人梦见自己在贵人面前，表示将会出人头地;但若梦中与贵人为对等地位，则有忧事将至。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('77','梦见窃贼','梦见窃贼进入梦境，表示梦者经历着个人氛围的损害。产生的原因可能在外部，可是造成了愈来愈大的内心恐惧和困难的感觉。梦见窃贼在搜你的身，你将遇到强劲的竞争对手，在和陌生人交往时要万分小心，否则你将被对手打垮。梦见家或公司被人室盗窃。你在社会上和事业上的名望会受到质疑，但你直面困难的勇气将保护你。此梦后，可能由于疏忽而发生事故。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('78','梦见赤裸的男人','梦见赤裸的男人，将会感到忧愁和悲伤。梦到男人浑身赤裸，在清清的水中游泳，预示将有许多人爱(羡)慕她；如果水很浑浊，仰慕者将因为嫉妒而恶意造谣。'),('79','梦见两性人','梦见两性人或雌雄同体的生物表示梦者对自己的性别角色存在疑问或者不满意。此外，如果梦者希望对本身有进一步的了解，他就必须努力在自己理性的一面和感性的一面作出平衡，而这种心理状态会在他的梦中以两性人的形式表现出来。梦中出现的两性人表示一种完全的平衡。'),('80','梦见嫂子','经常梦见我嫂子，有可能和植物神经功能紊乱有关。建议注意休息，避免精神紧张，放松心情，药物方面可以给与谷维素片口服治疗。梦见跟嫂子发生关系，亲情缺乏和渴望的表现。梦见怀孕的嫂子遇难，梦中的死亡多与新生有关。你的嫂子和她的宝宝会平安无事，不久之后你将会听到宝宝出世的消息。'),('81','梦见工人','通常来说梦中的男建筑工人，有父亲的意味，充满力量，搭建遮风避雨的房屋。梦见有一个建筑工或维修工修理你的房子，预示你将反思出你生活中的问题，并对它加以解决。梦中的房子象征自我。梦见一个工作在疏通管道，预示你将解决情感积郁的问题。梦见技工，或技工面对着一堆拆零的零件，象征自己面对着生活中一摊乱麻般的局面，并为要理清头绪，解决现状，感到异常苦恼。梦见井下作业的矿工，则有可能暗示你正在探索自己幽暗的内心深处。梦见管道工，则表示你对内心精神或情感的摸索。孕妇梦见管道工，还有可能是妇道医生的形象在梦里的显现。'),('82','梦见国王','国王是统帅全国，为民排忧解难的形象。梦见国王，预示你将有机会面对富贵荣耀，但同时也意味着你会有忧愁烦闷，承担责任，为此你将奋力与现实博斗，并需要保持充足的自信心。梦见自己和国王对话，预示你勇于承担责任，不畏惧矛盾或痛苦，最终必将功成名就。'),('83','梦见老太太','梦见个老太太送小孩给我，非孕妇的梦中可能表过潜意识中想要个孩子的愿望。梦见和老太太结婚，会得到遗产。'),('84','梦见皇帝','梦中的皇帝，按照心理分析的观点，象征父权。通常来说，男人梦见如在电视剧中的情景一般，在金碧辉煌的房间里，看见黄袍加身的皇帝，预示会加官晋爵，发财兴旺。梦见被皇帝召见，对官场中人预示要升官。除此之外，做这个梦还可能预示工作中可能会遇到风波，尤其是要注意涉及大宗财务支出方面的事情。梦见被皇帝责罚，象征家业昌盛，人丁兴旺，子孙满堂。梦见与帝王对话、交谈，则暗示你凭借长辈指点或贵人相助，将走上荣身之路，梦想总有一天能够实现，将来定会功成名就。梦见自己是皇帝，则是在提醒你做事要多听各方面的意见，顾全大局，切忌独断专行。女人梦见皇帝，预示生活幸福，衣食无忧。商人梦见皇帝，预示财源广进，生意兴隆。'),('85','梦见陌生男人','梦见男性老年 似乎会得到从未和他讲过话、意想不到的男孩的青睐。'),('86','梦见老伯伯','梦见一位老伯伯变成小孩子，要小心提防，比你年长的人会陷害你。'),('87','梦见小人','梦见小人，预示身边有人正隐瞒一些对于你很重要的事情，可能会有不顺利的事情发生。也意味着要调动工作，财源会旺盛。女性梦见小人预兆有机会旅行，一路平安，有友相伴。待业者梦见小人主钱财方面：佳，但防投资错误。老人梦见小人则您的运气平平，安守本份，可保平安，否则会招致坏运。'),('88','梦见木匠','梦见木匠，寓意创造奇迹，生活美好。梦见和木匠吵架，是提示你某项预算花销太大，要开源节流。梦见木匠盖新房子，表示你会在政绩、学术或艺术领域等方面取得杰出成绩。梦见自己家请木匠做工，并且木匠手艺高超，做工精致，表示你是个很会享受生活的人，日子安逸舒适。梦见木匠在干木工活，预示你将从事正当诚实的劳动，排斥自私的消遣和娱乐活动，踏踏实实地改变自己的生活。男人梦见木匠，预示不久会收到好消息。女人梦见木匠，会成为精明能干的持家能手。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('89','梦见故人','梦见故人，孩子要成亲。梦见故人死亡，朋友运下降。因为你的竞争意识太强，所以让别人敬而远之。尤其在考试之后，这种现象特别明显，实在有反省的必要。梦见故人哭，服刑期间会做苦力待业者梦见故人哭主财运：初运佳，但防后运衰退。梦见故人借钱，人际关系方面运气上升的可能性很大。你与亲友的交情将更加深厚，有什么困难，都可以与之商量，对方一定可以替你分忧已婚者梦见故人借钱要出远门，最好不要立刻出发，等待好时机吧!梦见故人被追打，努力拼搏能赚钱。同时，在异性方面也会有所收获。将会有一次新的交际，相逢的地方是朋友家。但这次交往是否会发展成为恋爱，要看以后的进展情况。老人梦见故人则近期运势运程，运气不通，诸事不如意，避免与人发生纠纷，及家庭不和。老人梦见故人被追打则您的运势，万事如意。如果不谦虚，反而骄傲横行，则容易招到祸害。病人梦见故人死亡则近期运程，困难多，万事不如意。有小人加害，须小心谨慎。但不要悲观，要退一步想，以待好运来。未婚的人梦见故人哭预兆您的爱情：成功。待业者梦见故人被追打说明您的财运佳。未婚男女梦见故人被追打解释：最近爱情方面耐心则成功。');/* MySQLReback Separation */
 /* 插入数据 `vifnn_dream` */
 INSERT INTO `vifnn_dream` VALUES ('90','梦见小朋友','梦见一位很可爱、趣致的小朋友，你会收到年终的奖金、双薪。');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_dxsreply_info` */
 DROP TABLE IF EXISTS `vifnn_dxsreply_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_dxsreply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `picurl` varchar(120) NOT NULL DEFAULT '',
  `sharepicurl` varchar(120) NOT NULL DEFAULT '',
  `info` varchar(120) NOT NULL DEFAULT '',
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `copyright` text NOT NULL,
  `ad` int(11) NOT NULL,
  `wxname` varchar(255) NOT NULL,
  `tip` varchar(255) NOT NULL,
  `url` char(255) NOT NULL,
  `shareurl` char(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_eqx_info` */
 DROP TABLE IF EXISTS `vifnn_eqx_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_eqx_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pic` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `sceneid_bigint` varchar(200) NOT NULL DEFAULT '',
  `info` varchar(500) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `picurl` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_error_logs` */
 DROP TABLE IF EXISTS `vifnn_error_logs`;/* MySQLReback Separation */ CREATE TABLE `vifnn_error_logs` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `message` varchar(1000) NOT NULL,
  `file` varchar(1000) NOT NULL,
  `line` varchar(1000) NOT NULL,
  `time` varchar(1000) NOT NULL,
  `hostname` varchar(1000) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `now_url` varchar(1000) NOT NULL,
  `ip` varchar(1000) NOT NULL,
  `post` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_estate` */
 DROP TABLE IF EXISTS `vifnn_estate`;/* MySQLReback Separation */ CREATE TABLE `vifnn_estate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `matchtype` tinyint(11) NOT NULL COMMENT '关键词匹配模式：',
  `cover` varchar(200) NOT NULL COMMENT '图文消息封面',
  `panorama_id` int(11) NOT NULL,
  `classify_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `banner` varchar(200) NOT NULL,
  `video` varchar(200) DEFAULT NULL,
  `house_banner` varchar(200) NOT NULL,
  `place` varchar(80) NOT NULL DEFAULT '',
  `lng` varchar(15) NOT NULL,
  `lat` varchar(15) NOT NULL,
  `estate_desc` text NOT NULL,
  `project_brief` text NOT NULL,
  `traffic_desc` text NOT NULL,
  `path` varchar(3000) DEFAULT '0',
  `tpid` int(11) DEFAULT '36',
  `conttpid` int(11) DEFAULT NULL,
  `menu1` varchar(50) NOT NULL,
  `menu2` varchar(50) NOT NULL,
  `menu3` varchar(50) NOT NULL,
  `menu4` varchar(50) NOT NULL,
  `menu5` varchar(50) NOT NULL,
  `menu6` varchar(50) NOT NULL,
  `menu7` varchar(50) NOT NULL,
  `menu8` varchar(50) NOT NULL,
  `picurl1` varchar(500) NOT NULL,
  `picurl2` varchar(500) NOT NULL,
  `picurl3` varchar(500) NOT NULL,
  `picurl4` varchar(500) NOT NULL,
  `picurl5` varchar(500) NOT NULL,
  `picurl6` varchar(500) NOT NULL,
  `picurl7` varchar(500) NOT NULL,
  `picurl8` varchar(500) NOT NULL,
  `slide1` char(100) NOT NULL,
  `slide2` char(100) NOT NULL,
  `slide3` char(100) NOT NULL,
  `slide4` char(100) NOT NULL,
  `slide5` char(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `token_2` (`token`),
  FULLTEXT KEY `token` (`token`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_estate_album` */
 DROP TABLE IF EXISTS `vifnn_estate_album`;/* MySQLReback Separation */ CREATE TABLE `vifnn_estate_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `poid` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_estate_expert` */
 DROP TABLE IF EXISTS `vifnn_estate_expert`;/* MySQLReback Separation */ CREATE TABLE `vifnn_estate_expert` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(30) NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `face` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_estate_housetype` */
 DROP TABLE IF EXISTS `vifnn_estate_housetype`;/* MySQLReback Separation */ CREATE TABLE `vifnn_estate_housetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `panorama_id` int(10) NOT NULL DEFAULT '0',
  `son_id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `floor_num` varchar(20) NOT NULL,
  `area` varchar(50) NOT NULL,
  `fang` int(11) NOT NULL,
  `ting` int(11) NOT NULL,
  `wei` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `abid` varchar(200) NOT NULL,
  `type1` varchar(200) NOT NULL,
  `type2` varchar(200) NOT NULL,
  `type3` varchar(200) NOT NULL,
  `type4` varchar(200) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `panorama_id` (`panorama_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_estate_impress` */
 DROP TABLE IF EXISTS `vifnn_estate_impress`;/* MySQLReback Separation */ CREATE TABLE `vifnn_estate_impress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(30) NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `is_show` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_estate_impress_add` */
 DROP TABLE IF EXISTS `vifnn_estate_impress_add`;/* MySQLReback Separation */ CREATE TABLE `vifnn_estate_impress_add` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `imp_id` int(11) NOT NULL,
  `imp_user` char(20) NOT NULL,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_estate_nav` */
 DROP TABLE IF EXISTS `vifnn_estate_nav`;/* MySQLReback Separation */ CREATE TABLE `vifnn_estate_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(35) NOT NULL,
  `pic` char(100) NOT NULL,
  `url` varchar(120) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `is_system` enum('0','1') NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `pid` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_estate_son` */
 DROP TABLE IF EXISTS `vifnn_estate_son`;/* MySQLReback Separation */ CREATE TABLE `vifnn_estate_son` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_extendset` */
 DROP TABLE IF EXISTS `vifnn_extendset`;/* MySQLReback Separation */ CREATE TABLE `vifnn_extendset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cloose_display` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_extendset` */
 INSERT INTO `vifnn_extendset` VALUES ('1','2');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_extension_activity` */
 DROP TABLE IF EXISTS `vifnn_extension_activity`;/* MySQLReback Separation */ CREATE TABLE `vifnn_extension_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL COMMENT '活动标题',
  `company` varchar(90) NOT NULL DEFAULT '' COMMENT '公司名',
  `descr` varchar(300) NOT NULL DEFAULT '' COMMENT '活动描述',
  `keyword` varchar(90) NOT NULL DEFAULT '' COMMENT '触发关键词',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '活动状态',
  `reward_lv1_cash` int(11) NOT NULL DEFAULT '0' COMMENT '一级人脉现金红包（分）',
  `reward_lv1_score` int(11) NOT NULL DEFAULT '0' COMMENT '一级人脉积分奖励',
  `reward_lv2_cash` int(11) NOT NULL DEFAULT '0' COMMENT '二级人脉现金红包（分）',
  `reward_lv2_score` int(11) NOT NULL DEFAULT '0' COMMENT '二级人脉积分奖励',
  `start_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `banner_config` text NOT NULL COMMENT '海报设置',
  `token` varchar(60) NOT NULL COMMENT '商家token',
  `max_score` int(11) NOT NULL DEFAULT '0' COMMENT '最多消耗积分',
  `max_cash` int(11) NOT NULL DEFAULT '0' COMMENT '最多消耗现金（分）',
  `consume_score` int(11) NOT NULL DEFAULT '0' COMMENT '已经消耗的积分',
  `consume_cash` int(11) NOT NULL DEFAULT '0' COMMENT '已经消耗的红包',
  `url` varchar(150) NOT NULL COMMENT '推广内容',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `nickname_len` int(5) NOT NULL DEFAULT '9' COMMENT '用户昵称长度',
  `reward_type` enum('score','cash') NOT NULL DEFAULT 'cash' COMMENT '奖励类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_extension_relation` */
 DROP TABLE IF EXISTS `vifnn_extension_relation`;/* MySQLReback Separation */ CREATE TABLE `vifnn_extension_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promoter` int(11) NOT NULL COMMENT '推广人',
  `follower` int(11) NOT NULL COMMENT '关注人',
  `aid` int(11) NOT NULL COMMENT '活动id',
  `token` varchar(60) NOT NULL COMMENT '活动商家token',
  `follow_time` int(11) DEFAULT NULL COMMENT '关注时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_extension_user` */
 DROP TABLE IF EXISTS `vifnn_extension_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_extension_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `wecha_id` varchar(60) NOT NULL COMMENT '微信id',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '获得积分',
  `cash` int(11) NOT NULL DEFAULT '0' COMMENT '获得红包',
  `token` varchar(60) NOT NULL COMMENT '活动商家',
  `aid` int(11) NOT NULL COMMENT '活动',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_fangchan` */
 DROP TABLE IF EXISTS `vifnn_fangchan`;/* MySQLReback Separation */ CREATE TABLE `vifnn_fangchan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `type` varchar(200) DEFAULT NULL,
  `houseType` varchar(200) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` varchar(5000) NOT NULL,
  `contacter` varchar(255) NOT NULL,
  `phone` char(11) NOT NULL,
  `valid_date` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `click` int(11) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `wecha_id` varchar(200) NOT NULL,
  `area` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_fangchan_reply` */
 DROP TABLE IF EXISTS `vifnn_fangchan_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_fangchan_reply` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `info` varchar(500) DEFAULT NULL COMMENT '公司简介',
  `title` text NOT NULL,
  `tp` char(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_fanyan` */
 DROP TABLE IF EXISTS `vifnn_fanyan`;/* MySQLReback Separation */ CREATE TABLE `vifnn_fanyan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `timu` text NOT NULL,
  `answer1` text NOT NULL,
  `answer2` text NOT NULL,
  `answer3` text NOT NULL,
  `answer4` text NOT NULL,
  `score1` text NOT NULL,
  `score2` text NOT NULL,
  `score3` text NOT NULL,
  `score4` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_fanyan_reply` */
 DROP TABLE IF EXISTS `vifnn_fanyan_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_fanyan_reply` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `info` varchar(500) DEFAULT NULL COMMENT '????',
  `title` text NOT NULL,
  `tp` char(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `scorename` text NOT NULL,
  `tip1` text NOT NULL,
  `tip2` text NOT NULL,
  `tip3` text NOT NULL,
  `tip4` text NOT NULL,
  `tip5` text NOT NULL,
  `tip6` text NOT NULL,
  `tip7` text NOT NULL,
  `tip8` text NOT NULL,
  `gz` varchar(500) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_fanyan_setcin` */
 DROP TABLE IF EXISTS `vifnn_fanyan_setcin`;/* MySQLReback Separation */ CREATE TABLE `vifnn_fanyan_setcin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `info` text,
  `tel` varchar(11) DEFAULT NULL,
  `messages` text,
  `banner` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `lng` varchar(30) NOT NULL,
  `lat` varchar(30) NOT NULL,
  `picurl1` varchar(100) NOT NULL,
  `picurl2` varchar(100) NOT NULL,
  `picurl3` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_fenlei` */
 DROP TABLE IF EXISTS `vifnn_fenlei`;/* MySQLReback Separation */ CREATE TABLE `vifnn_fenlei` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `topic` varchar(200) DEFAULT NULL,
  `info` varchar(500) DEFAULT NULL,
  `sort` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_fenlei_flash` */
 DROP TABLE IF EXISTS `vifnn_fenlei_flash`;/* MySQLReback Separation */ CREATE TABLE `vifnn_fenlei_flash` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `picurl1` char(255) NOT NULL,
  `picurl2` char(255) NOT NULL,
  `picurl3` char(255) NOT NULL,
  `picurl4` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_fenlei_reply` */
 DROP TABLE IF EXISTS `vifnn_fenlei_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_fenlei_reply` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `info` varchar(500) DEFAULT NULL COMMENT '公司简介',
  `title` text NOT NULL,
  `tp` char(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_fenlei_setcin` */
 DROP TABLE IF EXISTS `vifnn_fenlei_setcin`;/* MySQLReback Separation */ CREATE TABLE `vifnn_fenlei_setcin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `info` text,
  `tel` varchar(11) DEFAULT NULL,
  `messages` text,
  `banner` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `lng` varchar(30) NOT NULL,
  `lat` varchar(30) NOT NULL,
  `picurl1` varchar(100) NOT NULL,
  `picurl2` varchar(100) NOT NULL,
  `picurl3` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_files` */
 DROP TABLE IF EXISTS `vifnn_files`;/* MySQLReback Separation */ CREATE TABLE `vifnn_files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(30) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `size` int(10) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `wecha_id` varchar(200) NOT NULL DEFAULT '0',
  `upload_ip` varchar(100) NOT NULL DEFAULT '0.0.0.0',
  `state` int(11) NOT NULL DEFAULT '0',
  `sync_url` varchar(200) NOT NULL,
  `media_id` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_files` */
 INSERT INTO `vifnn_files` VALUES ('1','admin','http://www.test.com/uploads/a/admin/9/e/4/6/thumb_56eff40e6f39f.jpg','39942','jpg','1458566158','0','0','121.58.37.18','0','',''),('2','admin','http://wx.sitore.com/uploads/a/admin/2/b/8/f/thumb_56f35c280667b.jpg','5659','jpg','1458789416','3','0','140.240.23.57','1','',''),('3','ezrehr1458789061','http://wx.sitore.com/uploads/e/ezrehr1458789061/0/8/5/4/thumb_56f370cbdc059.jpg','39942','jpg','1458794699','3','0','140.240.23.57','0','',''),('4','admin','http://wx.sitore.com/uploads/a/admin/3/c/5/9/thumb_5731f5bab6e56.jpg','39942','jpg','1462891962','3','0','112.67.2.177','0','',''),('5','zbemii1462891866','http://wx.sitore.com/uploads/z/zbemii1462891866/2/a/d/a/thumb_57558f6e04e3a.jpg','238875','jpg','1465225070','3','0','139.189.250.197','0','',''),('6','zbemii1462891866','http://wx.sitore.com/uploads/z/zbemii1462891866/9/2/c/7/thumb_575ac5127c482.jpg','1611203','jpg','1465566482','3','0','112.67.2.203','1','',''),('7','zbemii1462891866','http://wx.sitore.com/uploads/z/zbemii1462891866/b/9/5/5/thumb_575aceabb996c.jpg','9157','jpg','1465568939','3','0','139.189.147.215','1','',''),('8','admin','http://wx.sitore.com/uploads/a/admin/f/1/3/6/thumb_575ae5868f53f.jpg','39942','jpg','1465574790','3','0','139.189.147.215','0','',''),('9','ywxgfh1465574691','http://wx.sitore.com/uploads/y/ywxgfh1465574691/2/a/9/4/thumb_575ae5f70a16e.jpg','39942','jpg','1465574903','3','0','139.189.147.215','0','',''),('10','ywxgfh1465574691','http://wx.sitore.com/uploads/y/ywxgfh1465574691/0/8/6/f/thumb_575ae6790f8c3.jpg','39942','jpg','1465575033','3','0','139.189.147.215','0','',''),('11','ywxgfh1465574691','http://wx.sitore.com/uploads/y/ywxgfh1465574691/3/1/f/2/thumb_575b7c92ace20.jpg','3224','jpg','1465613458','3','0','139.189.147.215','0','','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_flash` */
 DROP TABLE IF EXISTS `vifnn_flash`;/* MySQLReback Separation */ CREATE TABLE `vifnn_flash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `img` char(255) NOT NULL,
  `url` char(255) NOT NULL,
  `info` varchar(90) NOT NULL,
  `tip` smallint(2) NOT NULL DEFAULT '1',
  `did` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '子分类ID',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `tip` (`tip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_forum_comment` */
 DROP TABLE IF EXISTS `vifnn_forum_comment`;/* MySQLReback Separation */ CREATE TABLE `vifnn_forum_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `uid` varchar(50) DEFAULT NULL,
  `uname` varchar(50) DEFAULT NULL,
  `content` varchar(3000) NOT NULL,
  `createtime` int(11) NOT NULL,
  `favourid` varchar(3000) DEFAULT NULL,
  `replyid` varchar(3000) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `token` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_forum_config` */
 DROP TABLE IF EXISTS `vifnn_forum_config`;/* MySQLReback Separation */ CREATE TABLE `vifnn_forum_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bgurl` varchar(200) NOT NULL DEFAULT '',
  `picurl` varchar(200) NOT NULL DEFAULT '',
  `comcheck` varchar(4) NOT NULL DEFAULT '',
  `intro` varchar(600) NOT NULL DEFAULT '',
  `ischeck` tinyint(4) NOT NULL DEFAULT '0',
  `pv` float NOT NULL DEFAULT '0',
  `forumname` char(60) DEFAULT NULL,
  `logo` varchar(200) DEFAULT NULL,
  `token` varchar(50) NOT NULL,
  `isopen` tinyint(4) DEFAULT '1',
  `notice_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_reg` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否需要粉丝信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_forum_message` */
 DROP TABLE IF EXISTS `vifnn_forum_message`;/* MySQLReback Separation */ CREATE TABLE `vifnn_forum_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(3000) NOT NULL,
  `createtime` int(11) NOT NULL,
  `fromuid` varchar(50) NOT NULL,
  `touid` varchar(40) NOT NULL,
  `fromuname` varchar(60) DEFAULT NULL,
  `touname` varchar(60) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `isread` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_forum_topics` */
 DROP TABLE IF EXISTS `vifnn_forum_topics`;/* MySQLReback Separation */ CREATE TABLE `vifnn_forum_topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(300) NOT NULL,
  `content` varchar(1500) NOT NULL,
  `likeid` varchar(3000) DEFAULT NULL,
  `commentid` varchar(3000) DEFAULT NULL,
  `favourid` varchar(3000) DEFAULT NULL,
  `createtime` int(11) NOT NULL,
  `updatetime` int(11) DEFAULT NULL,
  `uid` varchar(50) DEFAULT NULL,
  `uname` varchar(50) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `photos` varchar(10000) DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_friend` */
 DROP TABLE IF EXISTS `vifnn_friend`;/* MySQLReback Separation */ CREATE TABLE `vifnn_friend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL,
  `wecha_id` varchar(80) NOT NULL,
  `name` varchar(10) NOT NULL COMMENT '姓名',
  `sex` tinyint(1) unsigned NOT NULL COMMENT '性别(2:女，1：男)',
  `address` varchar(200) NOT NULL COMMENT '居住地',
  `school` varchar(100) NOT NULL COMMENT '学校',
  `into` smallint(2) unsigned NOT NULL COMMENT '入学年份',
  `profession` varchar(100) NOT NULL COMMENT '专业',
  `company` varchar(100) NOT NULL COMMENT '所在单位',
  `post` varchar(100) NOT NULL COMMENT '任职职务',
  `info` varchar(300) NOT NULL COMMENT '个人简介',
  `dateline` int(10) unsigned NOT NULL,
  `head` varchar(200) NOT NULL COMMENT '头像',
  `tel` varchar(11) NOT NULL COMMENT '电话',
  PRIMARY KEY (`id`),
  KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_frontpage_action` */
 DROP TABLE IF EXISTS `vifnn_frontpage_action`;/* MySQLReback Separation */ CREATE TABLE `vifnn_frontpage_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `action_name` varchar(100) NOT NULL DEFAULT '',
  `keyword` char(30) NOT NULL,
  `reply_pic` varchar(255) NOT NULL,
  `reply_title` varchar(100) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `remind_word` varchar(255) NOT NULL,
  `remind_img` varchar(255) NOT NULL,
  `remind_link` varchar(255) NOT NULL,
  `total_create` int(11) NOT NULL,
  `day_create` int(11) NOT NULL,
  `sponsors` varchar(50) NOT NULL DEFAULT '',
  `is_follow` tinyint(1) NOT NULL,
  `is_register` tinyint(1) NOT NULL,
  `custom_sharetitle` varchar(255) NOT NULL DEFAULT '',
  `custom_sharedsc` varchar(500) NOT NULL DEFAULT '',
  `sharecount` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `token` char(25) NOT NULL,
  `defaultnews_hide` varchar(255) NOT NULL DEFAULT '',
  `start_time` int(11) NOT NULL,
  `latest_count` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_frontpage_configure` */
 DROP TABLE IF EXISTS `vifnn_frontpage_configure`;/* MySQLReback Separation */ CREATE TABLE `vifnn_frontpage_configure` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `apikey` char(30) NOT NULL DEFAULT '',
  `secretkey` char(50) NOT NULL DEFAULT '',
  `access_token` char(100) NOT NULL DEFAULT '',
  `expires_in` int(11) NOT NULL,
  `token` char(50) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL,
  `isusing` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_frontpage_makenews` */
 DROP TABLE IF EXISTS `vifnn_frontpage_makenews`;/* MySQLReback Separation */ CREATE TABLE `vifnn_frontpage_makenews` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `userid` int(11) unsigned NOT NULL,
  `wecha_name` varchar(50) NOT NULL,
  `news_txt` text NOT NULL,
  `news_title` varchar(100) NOT NULL,
  `spd` tinyint(1) NOT NULL,
  `per` tinyint(1) NOT NULL,
  `frontpage_name` char(30) NOT NULL DEFAULT '',
  `news_type` tinyint(1) NOT NULL,
  `token` char(25) NOT NULL,
  `create_time` int(11) NOT NULL,
  `voicepath` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `frontpage_img` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_frontpage_newsimg` */
 DROP TABLE IF EXISTS `vifnn_frontpage_newsimg`;/* MySQLReback Separation */ CREATE TABLE `vifnn_frontpage_newsimg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` char(10) NOT NULL DEFAULT '',
  `default_img` varchar(255) NOT NULL,
  `cid` int(11) NOT NULL,
  `token` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_frontpage_users` */
 DROP TABLE IF EXISTS `vifnn_frontpage_users`;/* MySQLReback Separation */ CREATE TABLE `vifnn_frontpage_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `total_create` int(11) NOT NULL,
  `today_create` int(11) NOT NULL,
  `wecha_id` varchar(50) NOT NULL DEFAULT '',
  `wecha_name` varchar(50) NOT NULL DEFAULT '',
  `wecha_pic` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(15) NOT NULL DEFAULT '',
  `token` varchar(30) NOT NULL DEFAULT '',
  `share_key` varchar(100) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_funclass` */
 DROP TABLE IF EXISTS `vifnn_funclass`;/* MySQLReback Separation */ CREATE TABLE `vifnn_funclass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `counts` int(11) NOT NULL,
  `list` int(11) NOT NULL,
  `menu_icon` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_funclass_holi` */
 DROP TABLE IF EXISTS `vifnn_funclass_holi`;/* MySQLReback Separation */ CREATE TABLE `vifnn_funclass_holi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `class` varchar(200) NOT NULL,
  `classid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_function` */
 DROP TABLE IF EXISTS `vifnn_function`;/* MySQLReback Separation */ CREATE TABLE `vifnn_function` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` tinyint(3) NOT NULL,
  `usenum` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `funname` varchar(100) NOT NULL,
  `info` varchar(100) NOT NULL,
  `isserve` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=145 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_function` */
 INSERT INTO `vifnn_function` VALUES ('1','1','0','天气查询','tianqi','天气查询服务:例  城市名+天气','1','1'),('2','1','0','糗事','qiushi','糗事　直接发送糗事','1','1'),('3','1','0','计算器','jishuan','计算器使用方法　例：计算50-50　，计算100*100','1','1'),('4','1','0','朗读','langdu','朗读＋关键词　例：朗读vifnn多用户营销系统','1','1'),('5','3','0','健康指数查询','jiankang','健康指数查询　健康＋高，＋重　例：健康170,65','1','1'),('6','1','0','快递查询','kuaidi','快递＋快递名＋快递号　例：快递顺丰117215889174','1','1'),('7','1','0','笑话','xiaohua','笑话　直接发送笑话','1','1'),('8','2','0','藏头诗','changtoushi',' 藏头诗+关键词　例：藏头诗我爱你','1','1'),('9','1','0','陪聊','peiliao','聊天　直接输入聊天关键词即可','1','1'),('10','1','0','聊天','liaotian','聊天　直接输入聊天关键词即可','1','1'),('11','3','0','周公解梦','mengjian','周公解梦　梦见+关键词　例如:梦见父母','1','1'),('12','2','0','语音翻译','yuyinfanyi','翻译＋关键词 例：翻译你好','1','1'),('13','2','0','火车查询','huoche','火车查询　火车＋城市＋目的地　例火车上海南京','1','1'),('14','2','0','公交查询','gongjiao','公交＋城市＋公交编号　例：上海公交774','1','1'),('15','2','0','身份证查询','shenfenzheng','身份证＋号码　　例：身份证342423198803015568','1','1'),('16','1','0','手机归属地查询','shouji','手机归属地查询(吉凶 运势) 手机＋手机号码　例：手机13917778912','1','1'),('17','3','0','音乐查询','yinle','音乐＋音乐名  例：音乐爱你一万年','1','1'),('18','1','0','附近周边信息查询','fujin','附近周边信息查询(ＬＢＳ） 回复:附近+关键词  例:附近酒店','1','1'),('19','1','0','抽奖','lottery','抽奖,输入抽奖即可参加幸运大转盘','1','1'),('20','1','0','翻译','fanyi','翻译＋关键词 例：翻译你好','1','1'),('21','1','0','第三方接口','api','第三方接口整合模块，请在管理平台下载接口文件并配置接口信息，','1','1'),('22','1','0','姓名算命','suanming','姓名算命 算命＋关键词　例：算命李白','1','1'),('23','3','0','百度百科','baike','百度百科　使用方法：百科＋关键词　例：百科北京','2','1'),('24','2','0','彩票查询','caipiao','回复内容:彩票+彩种即可查询彩票中奖信息,例：彩票双色球','1','1'),('25','1','0','幸运大转盘','choujiang','输入抽奖　即可参加幸运大转盘抽奖活动','2','1'),('26','1','0','3G首页','shouye','输入首页,访问微3g 网站','1','1'),('27','1','0','DIY宣传页','adma','DIY宣传页,用于创建二维码宣传页权限开启','1','1'),('28','1','0','会员卡','huiyuanka','尊贵享受vip会员卡,回复会员卡即可领取会员卡','1','1'),('29','1','0','通用预订系统','host_kev','通用预订系统 可用于酒店预订，ktv订房，旅游预订等。','2','1'),('30','1','0','歌词查询','geci','歌词查询 回复歌词＋歌名即可查询一首歌曲的歌词,例：歌词醉清风','1','1'),('31','2','0','域名whois查询','whois','域名whois查询　回复域名＋域名 可查询网站备案信息,域名whois注册时间等等\\r\\n 例：域名vifnn.com','1','1'),('32','1','0','自定义表单','diyform','自定义表单(用于报名，预约,留言)等','1','1'),('33','2','0','无线网络订餐','dx','无线网络订餐','1','1'),('34','2','0','在线商城','shop','在线商城,购买系统','1','1'),('35','2','0','在线团购系统','etuan','在线团购系统','1','1'),('36','1','0','自定义菜单','diymen_set','自定义菜单,一键生成轻app','1','1'),('37','1','0','刮刮卡','gua2','刮刮卡抽奖活动','1','1'),('38','1','0','全景','panorama','','1','1'),('39','1','0','婚庆喜帖','wedding','','2','1'),('40','1','0','投票','vote','','2','1'),('41','1','0','房产','estate','','2','1'),('42','1','0','3G相册','album','','1','1'),('43','1','0','砸金蛋','GoldenEgg','','2','1'),('44','1','0','水果机','LuckyFruit','','2','1'),('45','1','0','留言板','messageboard','','2','1'),('46','1','0','微汽车','car','','2','1'),('47','1','0','微信墙','wall','','1','1'),('48','1','0','摇一摇','shake','','1','1'),('49','1','0','微论坛','forum','回复 讨论社区 即可使用','1','1'),('50','1','0','微医疗','medical','','1','1'),('51','1','0','群发消息','groupmessage','','1','1'),('52','1','0','分享统计','share','','1','1'),('53','1','0','酒店宾馆','hotel','','1','1'),('54','1','0','微教育','school','学校','1','1'),('55','1','0','中秋吃月饼','Autumn','','1','1'),('56','1','0','摁死小情侣游戏','Lovers','回复 “小情侣” 即可参加','1','1'),('57','1','0','七夕走鹊桥','AppleGame','回复 “走鹊桥” 即可参与','1','1'),('58','1','0','微场景','Live','','1','1'),('59','1','0','微调研','Research','','1','1'),('60','1','0','一战到底','Problem','','1','1'),('61','1','0','微信签到','Signin','','1','1'),('62','1','0','现场活动','Scene','','1','1');/* MySQLReback Separation */
 /* 插入数据 `vifnn_function` */
 INSERT INTO `vifnn_function` VALUES ('63','1','0','微商圈','Market','','1','1');/* MySQLReback Separation */
 /* 插入数据 `vifnn_function` */
 INSERT INTO `vifnn_function` VALUES ('64','1','0','微预约','Custom','','1','1'),('65','1','0','祝福贺卡','Greeting_card','','1','1'),('66','1','0','微美容','beauty','','1','1'),('67','1','0','微健身','fitness','','1','1'),('68','1','0','微政务','gover','','1','1'),('69','1','0','微食品','food','','1','1'),('70','1','0','微旅游','travel','','1','1'),('71','1','0','微花店','flower','','1','1'),('72','1','0','微物业','property','','1','1'),('73','1','0','微KTV','ktv','','1','1'),('74','1','0','微酒吧','bar','','1','1'),('75','1','0','微装修','fitment','','1','1'),('76','1','0','微婚庆','buswedd','','1','1'),('77','1','0','微宠物','affections','','1','1'),('78','1','0','微家政','housekeeper','','1','1'),('79','1','0','微租赁','lease','','1','1'),('80','1','0','微游戏','Gamecenter','','1','1'),('81','1','0','百度直达号','Zhida','','1','1'),('82','1','0','微信红包','Red_packet','','1','1'),('83','1','0','惩罚台','Punish','','1','1'),('84','1','0','邀请函','Invite','','1','1'),('85','1','0','拆礼盒','Autumns','','1','1'),('86','1','0','幸运九宫格','Jiugong','','1','1'),('87','1','0','全民经纪人','MicroBroker','','1','1'),('88','1','0','分享助力','Helping','','1','1'),('89','1','0','人气冲榜','Popularity','','1','1'),('90','1','0','一元购','Unitary','','1','1'),('91','1','0','手机站','Phone','','1','1'),('133','1','0','微租房','Fangchan','','1','1'),('93','1','0','微外卖','DishOut','','1','1'),('94','1','0','微信生活圈','LivingCircle','','1','1'),('95','1','0','趣味测试','Test','','1','1'),('96','1','0','微砍价','Bargain','','1','1'),('97','1','0','微众筹','Crowdfunding','','1','1'),('130','1','0','主题报名','Baoming','','1','1'),('99','1','0','秒杀','Seckill','','1','1'),('100','1','0','聊天交友','Service','','1','1'),('101','1','0','合体红包','Hongbao','','1','1'),('102','1','0','微信','Weixin','','1','1'),('103','1','0','微贺卡','Card','','1','1'),('104','1','0','支付宝服务窗','Fuwu','','1','1'),('105','1','0','图文投票','Voteimg','','1','1'),('106','1','0','微店系统','Micrstore','','1','1'),('107','1','0','摇一摇周边','Shakearound','','1','1'),('108','1','0','降价拍','Cutprice','','1','1'),('109','1','0','谁是情圣','Sentiment','','1','1'),('110','1','0','微名片','Person_card','','1','1'),('111','1','0','人工客服','ServiceUser','','1','1'),('112','1','0','微排号','Numqueue','','1','1'),('113','1','0','二维码扫描分析','RecognitionData','','1','1'),('114','1','0','H5动态自定义模板','CustomTmpls','','1','1'),('134','1','0','微招聘','Zhaopin','','1','1'),('116','1','0','账号审核','usernameCheck','正确的审核帐号方式是：审核+帐号','1','1'),('117','1','0','店员管理','Assistente','','1','1'),('118','1','0','摇钱树','CoinTree','','1','1'),('119','1','0','我要上头条','FrontPage','','1','1'),('120','1','0','集字游戏','Collectword','','1','1'),('121','1','0','微WIFI','RippleOS','','1','1'),('122','1','0','邮件通知','Vifnnemail','邮件通知','1','1'),('129','1','0','微街景','Jiejing','','1','1'),('131','1','0','极客答题','Jikedati','','1','1'),('125','1','0','微竞猜','Jingcai','','1','1'),('132','1','0','方言考试','Fangyan','','1','1'),('135','1','0','朋友圈AD','Pengyouquanad','','1','1'),('136','1','0','微拍卖','Auction','','1','1'),('137','1','0','AA报名活动','Aaactivity','','1','1'),('138','1','0','微召唤','Weizhaohuan','','1','1'),('139','1','0','分享大奖赛','Dajiangsai','','1','1'),('140','1','0','一元现金红包','Cashbonus','','1','1'),('141','1','0','收银台','Cashier','','1','1'),('142','1','0','微募捐','Donation','','1','1'),('143','1','0','拼团购','Pintuan','','1','1'),('144','1','0','二维码海报','Extension','','1','1');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_funintro` */
 DROP TABLE IF EXISTS `vifnn_funintro`;/* MySQLReback Separation */ CREATE TABLE `vifnn_funintro` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `isnew` tinyint(1) NOT NULL DEFAULT '0',
  `logo` varchar(200) NOT NULL DEFAULT '',
  `type` smallint(4) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `time` int(10) NOT NULL DEFAULT '0',
  `img1` char(200) NOT NULL,
  `img2` char(200) NOT NULL,
  `img3` char(200) NOT NULL,
  `img4` char(200) NOT NULL,
  `img5` char(200) NOT NULL,
  `img6` char(200) NOT NULL,
  `img7` char(200) NOT NULL,
  `img8` char(200) NOT NULL,
  `name1` varchar(200) NOT NULL,
  `name2` varchar(200) NOT NULL,
  `name3` varchar(200) NOT NULL,
  `name4` varchar(200) NOT NULL,
  `name5` varchar(200) NOT NULL,
  `name6` varchar(200) NOT NULL,
  `name7` varchar(200) NOT NULL,
  `name8` varchar(200) NOT NULL,
  `class` varchar(200) NOT NULL,
  `classid` int(11) NOT NULL DEFAULT '0',
  `nick_title` varchar(200) NOT NULL,
  `desc` text NOT NULL,
  `sce_content` varchar(200) NOT NULL,
  `holi_id` int(11) NOT NULL,
  `menu_link` varchar(200) NOT NULL,
  `public_id` int(11) NOT NULL,
  `timg` varchar(100) NOT NULL,
  `img_w` varchar(200) NOT NULL,
  `img_x` varchar(200) NOT NULL,
  `is_new` int(1) NOT NULL,
  `img_havecolor` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('2','天气查询','0','','0','<span class=\"marginb\">
<p>
输入城市+天气，可以马上得到近五天的气情况，出行无忧。
</p>
<p>
例如：上海天气
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn001.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('3','快递查询','0','','0','<span class=\"marginb\">
<p>
输入快递名称+运单号，可以快速查询快递的详细动态，收件从此不用愁。
</p>
<p>
例如：天天快递130004442691
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn002.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('4','手机归属地查询','0','','0','<span class=\"marginb\">
<p>
输入手机号码即可获得该手机相关信息，有手机归属地，手机类型，手机区号，邮编等。
</p>
<p>
例如：13625008699
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn003.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('5','身份证查询','0','','0','<span class=\"marginb\">
<p>
输入身份证号即可获得相关信息。
</p>
<p>
例如：342502198501250013
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn004.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('6','公交查询','0','','0','<span class=\"marginb\">
<p>
输入城市+公交+车次，有3.6万线路
</p>
<p>
例如线路查询：厦门公交92路
</p>
<p>
站点到站点查询：厦门公交厦大医院到软件园二期
</p>
<p>
站点查询：厦门公交厦大医院站
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn005.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('7','火车查询','0','','0','<span class=\"marginb\">
<p>
输入：火车 车次 或 火车 站点 站点，有4460列火车数据。
</p>
<p>
例如车次查询：火车k332
</p>
<p>
站点到站点查询：火车 厦门 东乡
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn006-2.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('8','健康指数查询','0','','0','<span class=\"marginb\">
<p>
身高单位为cm 体重单位为公斤
</p>
<p>
输入：身高173 体重56 或输入：高173 重56
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn007.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('9','实时翻译(语音)','0','','0','<span class=\"marginb\">
<p>
支持几十种语言的实时翻译，中、英、日、韩、法、俄、等等全文翻译。
</p>
<p>
输入翻译，可以查询支持的语种有哪些。
</p>
<p>
英文我爱你：表示把中文翻译成英文，日语早上好：表示把中文翻译成日文
</p>
<p>
法语我爱你：表示把中文翻译成法语，俄语我爱你：表示把中文翻译成俄语
</p>
<p>
备注：如果后台开启翻译朗读功能，可以将翻译结果直接朗读出来，
</p>
<p>
学习世界语言的必备武器！更有意思！
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn008.jpg\"/><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn008-2.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('10','百度百科','0','','0','<span class=\"marginb\">
<p>
输入百科（bk）+要查询的词，即可得到相关信息。
</p>
<p>
例如：百科刘德华 或 bk刘德华
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn009.jpg\"/><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn009-2.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('11','百度问答','0','','0','<span class=\"marginb\">
<p>
输入超过5个汉字自动回复相关问答内容。
</p>
<p>
例如：要怎么哄女人开心
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn010.jpg\"/><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn010-2.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('12','人品计算','0','','0','<span class=\"marginb\">
<p>
输入人品+姓名，计算当天人品。
</p>
<p>
例如：李白人品
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn011.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('13','笑话','0','','0','<span class=\"marginb\">
<p>
输入任何数字：0-9或者笑话或者笑脸的表情
</p>
<p>
可以随机查看笑话，有2.6万数据。
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn012.jpg\"/><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn012-2.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('14','糗事','0','','0','<span class=\"marginb\">
<p>
输入糗事或者任意字母，可以随机查看糗事，有51.5万数据。
</p>
<p>
例如：糗事 或者 q
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn013.jpg\"/><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn013-2.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('15','谜语','0','','0','<span class=\"marginb\">
<p>
输入谜语，即可玩猜谜语游戏，有5万数据。
</p>
<p>
查答案可输入答案谜语编号，例如：谜语 232
</p>
</span><img alt=\"\"src=\"/tpl/Home/pigcms/common/images/demo/gn014.jpg\"/>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('16','解梦','0','','0','<span class=\"marginb\">
<p>
	输入梦见发财或者梦到发财，立刻获得解梦信息。
</p>
<p>
	例如：梦见我发财了
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn015.jpg\" /><br />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('17','成语接龙','0','','0','<span class=\"marginb\">
<p>
	输入正确的成语即可
</p>
<p>
	例如：肝肠寸断
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn016.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('18','成语字典','0','','0','<span class=\"marginb\">
<p>
	输入例如：成语 半死不活，得到该成语的解释。
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn017.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('19','陪聊','0','','0','<span class=\"marginb\">
<p>
	开启陪聊功能可以自动跟用户智能聊天
</p>
<p>
	目前仅1000条陪聊库，在不断完善中...
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn018.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('20','机器人学习功能','0','','0','<span class=\"marginb\">
<p>
	用手机直接发送：问 关键词 答 内容，这样教机器人回答问题，机器人就学会了。
</p>
<p>
	例如：问 你是谁 答 我是欧阳啊！
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn019.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('21','自定义图文回复','0','','0','<span class=\"marginb\">
<p>
	在pigcms微信后台设置关键词，编辑对应的回复内容，以图文形式显示。
</p>
<p>
	还有关联的图文展示，推荐阅读。
</p>
<p>
	效果更好，用户体验更佳。
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn020.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn020-2.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('22','藏头藏尾诗','0','','0','<span class=\"marginb\">
<p>
	输入：藏头诗 我爱你香港 或者 cts 我爱你香港 或者
</p>
<p>
	藏尾诗 我爱你香港 或者 cws 我爱你香港
</p>
<p>
	会根据输入的内容自动生成藏头诗或者藏尾诗，非常有意思。
</p>
<p>
	例如：cts 我爱你香港 或者 cws 我爱你香港
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn021.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('23','诗歌接龙','0','','0','<span class=\"marginb\">
<p>
	输入诗词的任意一句，会自动回复下一句。
</p>
<p>
	例如：床前明月光
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn022.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('24','诗歌赏析','0','','0','<span class=\"marginb\">
<p>
	输入：古诗+诗名或者古诗+诗名+作者，可以欣赏这首完整的诗歌。
</p>
<p>
	例如：古诗 黄鹤楼
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn023.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('25','网络音乐搜索','0','','0','<span class=\"marginb\">
<p>
	输入：音乐+歌手+歌名 或者 音乐+歌名 可以欣赏这首歌曲。
</p>
<p>
	例如：音乐 谢霆锋谢谢你的爱
</p>
<p>
	还可以输入：播放音乐|来首歌|来首音乐|放歌|音乐|mp3|点首歌|点歌|我要听歌
</p>
<p>
	来随机播放音乐
</p>
<p>
	还可以输入：陈奕迅的歌或者播放浮夸
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn024.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn024-2.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('26','网络电影搜索','0','','0','<span class=\"marginb\">
<p>
	输入：电影+名称 可以欣赏这部电影了，当然有的电影是搜不到的，电影库会慢慢增加。
</p>
<p>
	例如：电影 功夫熊猫
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn025.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('27','文字转语音','0','','0','<span class=\"marginb\">
<p>
	输入：朗读+文字，就可以把文字转成语音播放。
</p>
<p>
	例如：朗读你好，pigcms欢迎你！
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn026.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('28','股票查询','0','','0','<span class=\"marginb\">
<p>
	输入：股票+股票代号或名称或拼音缩写，股票二字可以用gp缩写
</p>
<p>
	例如：股票601088 或者 股票西藏天路 或者 股票dqtl 或者 gp601088
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn027.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('29','彩票查询','0','','0','<span class=\"marginb\">
<p>
	输入彩票+名称
</p>
<p>
	可以查询彩票有：双色球、大乐透、七星彩、排列3、排列5、体彩22选5、胜负彩14场、任选9场、4场进球、6场半全场、老11选5、11选5、新11选5、双色球
</p>
<p>
	例如：彩票双色球
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn028.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn028-2.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('30','文字转语音','0','','0','<span class=\"marginb\">
<p>
	开启此功能，聊天内容出现敏感词时会自动转成语音，
</p>
<p>
	你也可以输入朗读+文字，例如：朗读你好呀
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn031.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('31','周边商铺','0','','0','<span class=\"marginb\">
<p>
	开启此功能，直接发送地理位置信息，然后输入“附近便利店”
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn033.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn033-2.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('32','.刮刮卡','0','','0','<span class=\"marginb\">
<p>
	开启此功能，直接发布刮刮卡刮奖活动，设置活动内容、奖项及中将比例，带给粉丝完全不同的感受。
</p>
<p>
	输入“刮刮卡”体验此互动活动
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn034.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn034-2.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('33','幸运大转盘活动','0','','0','<span class=\"marginb\">
<p>
	开启此功能，直接发布幸运大转盘活动，设置活动内容、奖项及中将比例，带给粉丝完全不同的感受。
</p>
<p>
	输入\"大转盘\"体验此互动活动
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn035.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn035-2.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('34','优惠券活动','0','','0','<span class=\"marginb\">
<p>
	开启此功能，直接发布优惠券活动，设置活动内容、奖项等，带给粉丝完全不同的感受。
</p>
<p>
	输入\"优惠券\"体验此互动活动
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn036.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn036-2.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('35','周边生活地图版','0','','0','<span class=\"marginb\">
<p>
	开启此功能，首先要发送地理位置信息，然后输入附近+关键词即可,
</p>
<p>
	例如 附近酒吧,附近学校,附近工商银行,附近医院,附近小吃,附近美食,附近酒吧,附近咖啡厅,附近公交站,附近加油站.......
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn037.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn037-2.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('36','步行导航','0','','0','<span class=\"marginb\">
<p>
	微信中输入“地图”会自动回复配置的商家地理信息，如果您选择步行，可以获取步行路书和地图导航。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/buxing.jpg\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('37','驾车导航','0','','0','<span class=\"marginb\">
<p>
	微信中输入“地图”会自动回复配置的商家地理信息，如果您选择开车，可以获取驾车路书和地图导航。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/kaiche.jpg\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('38','发位置显示周边','0','','0','<span class=\"marginb\"> 
<p>
	开启此开关,将不会提示地理位置已经记录，而是直接显示周边数据，不影响关键词。
</p>
<p>
	查询可以通过：附近+关键词，获取附近更精细的分类查询！
</p>
<p>
	例如：附近便利店 附近医院 附近美食 附近小吃 等等
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn041.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn041-2.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('39','公交地图版','0','','0','<span class=\"marginb\"> 
<p>
	开启此开关,可以查询公交某个站点到某个站点如何坐车，只能查你当前位置所在城市的公交等
</p>
<p>
	例如：公交龙山桥到SM 或者 厦大西村到中山路怎么坐车
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn042.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn042-2.jpg\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/gn042-3.jpg\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('40','自定义LBS数据','0','','0','<span class=\"marginb\">
<p>
	微信中输入“地图”会自动回复商家的地理信息，并且可以获取最近的分店，以及获取各种交通方式导航。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/lbs.jpg\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('41','DIY宣传页','0','','0','<span class=\"marginb\">
<p>
	只需要填写二维码地址，简单介绍你公众号的特点，就可以自动生成一个漂亮的PC推广也没，方便你分享给好友，分享到微博，论坛，博客等等，起到好的推广的作用。
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/diy.gif\" width=\"99%\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('42','相册功能','0','','0','<span class=\"marginb\">
<p>
	开启此功能,只需要设置相册名称，相册封面图及上传图片，就可以得到一个精美的相册网站。
									非常适合婚纱行业、汽车行业或者需要展示产品图片的商家。
									下面是效果图及简单的操作说明。
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/3gphoto.jpg\" width=\"99%\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('43','会员卡管理','0','','0','<span class=\"marginb\">
<p>
	强的会员卡功能，包含8大模块，diy设计会员卡，各版本设计，会员卡资料管理，在线自定义卡号，积分签到，消费积分，等强的大的功能。
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/member1.gif\" width=\"98%\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/cart1.gif\" width=\"98%\" /><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/cart2.gif\" width=\"98%\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('44','会员卡管理','0','','0','<span class=\"marginb\">
<p>
	开启此功能,可以设置酒店预订，KTV预订等，设置手机后，还可以收到订单提醒。
</p>
</span><img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/host_ktv.gif\" width=\"98%\" />','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('45','无线点餐订餐','0','','0','<span class=\"marginb\">
<p>
	微信中输入“订餐”会自动回复订餐功能，可以预定座位包厢、也可以在线点餐或叫外卖，<br />
网友提交订单后，商家无线打印机会自动打印出订单信息。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/dc.jpg\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('46','微团购','0','','0','<span class=\"marginb\">
<p>
	微信中输入“团购”会自动回复团购功能，可以设定团购截止日期，初始出售数量，并显示截止日期倒计时。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/tuangou.jpg\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('47','微商城','0','','0','<span class=\"marginb\">
<p>
	微信中输入“商城”会自动回复，可以设置商品分类，支持商品搜索，多种属性排序，多商品购物车结算，瀑布流展示。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/shangcheng.jpg\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('48','万能表单','0','','0','<span class=\"marginb\">
<p>
	微信中输入自定义的万能表单的关键词会自动回复相应表单，可以利用该功能自由定制各类表单信息，如下图所示。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/wnbd.jpg\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('49','微网站(3G网站)','0','','0','<span class=\"marginb\">
<p>
	开发团队10.1假期全体加班，加点所完成，
</p>
<p>
	<img src=\"/tpl/Home/pigcms/common/images/demo/tpl.gif\" width=\"930\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('50','crm客户关系管理','0','','0','<span class=\"marginb\">分组管理接口
									获取用户基本信息
									获取关注者列表
									获取用户地理位置
									网页授权获取用户基本信息
									网页获取用户网络状态</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('51','智能语音识别接口','0','','0','把用户输入的语音识别成文字并进行自动回复','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('52','主动信息推送','0','','0','认证服务号主动发送信息给粉丝','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('53','360°全景','0','','0','<span class=\"marginb\"> 
<p>
	通过该功能可以实现三维立体360°全景看车看房，也可以作为其他产品的三维立体展示。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/360.jpg\" /> 
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('54','支付宝在线支付','0','','0','<span class=\"marginb\">
<p>
	通过支付宝在线支付接口可以实现微商城的在线支付功能。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/alipay.jpg\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('55','婚庆喜帖','0','','0','<span class=\"marginb\">
<p>
	婚庆行业的微信营销解决方案，可以展现用户想要表达的话、结婚日期、地址、导航、接待电话，同时亲朋好友可以在微喜帖平台上提交赴宴通知、送上祝福，并且转发喜帖。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/wedding.jpg\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('56','留言板','0','','0','<span class=\"marginb\">
<p>
	微信留言板功能，能控制留言是否需要审核，留言间隔时间等。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/ly.png\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('57','房地产行业应用','0','','0','<span class=\"marginb\">
<p>
	针对房地产行业微信营销的应用，包括楼盘介绍，360°全景看房，户型介绍，专家点评，预约看房等功能。
</p>
<p>
	<img alt=\"\" src=\"/tpl/Home/pigcms/common/images/demo/fc.png\" />
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('58','财付通支付','0','','0','支持财付通即时到帐接口和wap接口，微信团购、商城、订餐、宾馆的支付都可以使用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('59','微信支付','0','','0','认证服务号可以使用微信支付，微信团购、商城、订餐、宾馆的支付都可以使用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('60','系统功能库','0','','0','<p>
	微网站的分类、幻灯片、文章等做外链的时候可以从系统功能库中选择任何功能作为链接，这个功能是融通整个系统的关键，也是VIFNNcms的原创功能。
</p>
<p>
	在设置自定义菜单关键词测时候也可以从功能库进行选择。
</p>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('61','生活服务外链','0','','0','系统提供外链库，涵盖常用生活服务、星座、小游戏、便民生活等，让商户作为网站的时候可以自由选择工具性链接','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('62','连锁商家管理','0','','0','会员卡功能、餐饮行业模块、酒店行业模块等都拥有连锁店功能，每个连锁店可以管理自己的信息订单，每个连锁店都有自己的独立后台','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('63','微网站无限级分类','0','','0','新版的系统支持无限级分类，这种结构特别有利于旅游、生活服务类微网站的制作','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('64','分类独立选择模板','0','','0','微网站的每个分类都可以独立选择列表页模板和文章页模板，这种功能让微网站的制作更灵活','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('65','底部导航菜单','0','','0','突破自定义菜单限制，微网站中可以设计底部导航菜单，共有15种导航菜单供选择','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('66','自定义菜单','0','','0','在系统后台便可以设定自定义菜单，在添加菜单的时候可以自由选择系统内设定的关键词，简单易用。','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('67','微论坛','0','','0','微信论坛，粉丝作为论坛的直接参与者进行话题讨论','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('68','微投票','0','','0','支持文字类和图片类投票，选项可以自定义，可以单选可以多选','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('69','微汽车','0','','0','汽车微信行业应用，包括品牌、车系车型管理，销售管理，保养预约，4s店业务等','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('70','微教育','0','','0','教育行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('71','微医疗','0','','0','医疗行业微信应用，包括医院各项业务介绍，挂号预约等功能','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('72','酒店宾馆','0','','0','酒店宾馆行业微信应用，包括在线订房，在线支付，连锁店管理，酒店全景等功能。','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('73','通用订单（KTV酒吧）','0','','0','比如ktv和酒吧类似行业的订单应用，比如ktv包厢的预定，订单管理等功能','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('74','微美容','0','','0','美容行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('75','微政务','0','','0','政府行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('76','微健身','0','','0','健身行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('77','微食品','0','','0','食品行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('78','微旅游','0','','0','旅游行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('79','微花店','0','','0','花店行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('80','微物业','0','','0','物业管理微信应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('81','微KTV','0','','0','ktv行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('82','微酒吧','0','','0','酒吧行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('83','微装修','0','','0','装修行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('84','微婚庆','0','','0','婚庆行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('85','微宠物','0','','0','宠物店、宠物医院微信应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('86','微家政','0','','0','家政行业微信应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('87','微租赁','0','','0','租赁行业应用','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('88','融合第三方','0','','0','融合第三方接口可以接入任何语言写的程序，接口类型分为文本接口和xml接口。在系统回答不上来的时候可以自动让第三方程序来回答粉丝的请求。','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('89','粉丝管理','0','','0','认证服务号可以在系统内管理自己的粉丝，包括对粉丝进行分组，粉丝的省份、城市、性别、头像等资料的管理','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('90','粉丝行为分析','0','','0','系统用图表方式对粉丝的喜好行为进行统计分析，为商家提供直观清晰的营销决策','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('91','渠道二维码','0','','0','<p>
	为了满足用户渠道推广分析的需要，系统提供了生成带参数二维码的接口。使用该功能可以获得多个带不同场景值的二维码，用户扫描后，公众号可以接收到事件推送。
</p>
<p>
	后台可以统计每个渠道的二维码扫描数量。
</p>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('92','人工客服','0','','0','<span>人工客服是指通过电脑端的真人客服工作人员直接与粉丝进行对话（类似于QQ聊天），人工客服在电脑端接收粉丝发送的信息并进行回答，粉丝在微信上进行对话并接收到客服发送的信息。<br />
人工客服支持多工号操作，您只需要在后台添加多个客服工号，每个客服便可以单独进行登录来接待不同的粉丝。</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('93','群发消息','0','','0','认证服务号可以通过系统群发图文消息，而不用再进入微信公众平台群发。','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('94','分享互动统计','0','','0','<p>
	　&nbsp;&nbsp; VIFNNcms推出了分享（分享到朋友圈、微博或者发送给朋友）赚取积分的功能，商家在后台可以设置分享一次赚取多少积分，并且可以限制每个粉丝每天最多赚取多少积分。
</p>
<p>
	　　分享功能与会员卡、商城系统形成一个良性互动的圈子，粉丝与商家可以在分享中实现互利互赢，是一种营销效果极佳的信息传播方式。
</p>
<p>
	　　粉丝通过分享获取积分，积分会累积到会员卡中，凭借积分可以兑换奖品，在商城内作为抵用券抵用现金，这种方式大大提高了粉丝分享传播信息的积极性。
</p>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('95','水果机','0','','0','幸运水果机功能，就是我们熟悉的老虎机功能','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('96','砸金蛋','0','','0','在微信上砸金蛋，是针对商家做活动推广而开发的游戏模块','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('97','祝福贺卡','0','','0','支持多种背景和效果，粉丝转发可以修改贺卡内容','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('98','摇一摇','0','','0','大型会场及活动现场应用，通过粉丝摇动手机，大屏幕展现摇晃次数结果并进行奖励，来提高现场活动气氛','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('99','微信墙','0','','0','大型活动现场功能，粉丝发送的文字和图片可以显示在大屏幕上，在大屏幕上可以对参与粉丝进行滚动抽奖','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('100','微调研','0','','0','<span>微调研功能支持自定义问卷，自定义题目，自定义选项数量，带有完善的统计功能，粉丝参加完调研后可以参加抽奖，以提高粉丝参与的积极性。 </span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('101','微信签到','0','','0','<span>粉丝签到功能可以在会议现场、活动现场使用，可以代替原有的线下签到方式。签到界面设计美观，banner可以自定义为广告图片。后台带有完善的签到数据统计功能。 </span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('102','数据魔方','0','','0','数据魔方就是系统内各类数据的统计分析，以直观的图标的形式提供给商家，包括粉丝地域性别分析、分析喜好分析、分析行为分析、新增粉丝及对话量对比粉丝、趋势对比分析等','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('103','无缝接入第三方应答','0','','0','<span>
<p>
	　　VIFNNcms新推出了无缝接入第三方程序应答功能，为了更好的支持第三方程序接入本系统，VIFNNcms新添加了在系统回答不上来的时候自动转接到第三方程序处理的功能。
</p>
<p>
	　　这个功能的工作原理是：当VIFNNcms中没有对应的自动回复的时候，就会把粉丝输入的信息连带粉丝的信息转交给用户设置的第三方程序处理，第三方程序可以进行自动回复，如果第三方程序也处理不了，则再转交给VIFNNcms处理。
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('104','短信提醒','0','','0','<span>
<p>
	　　各类订单信息及连锁店订单信息都会直接发送到相应的手机号上，商家不必盯着电脑屏幕查看订单信息，商家可以随时掌握订单情况。
</p>
<p>
	　　后台有完善的短信使用统计功能，您能清晰透明的了解您的短信用途和消费记录。
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('105','会员卡储值与支付','0','','0','<span>
<p align=\"left\">
	　微信粉丝领取微信会员卡后可以通过在线支付方式或者线下支付方式进行储值，储值的会员卡余额可用于微信商城、团购、酒店预订等线上支付，也可以用于线下支付。
</p>
<p align=\"left\">
	　无论是通过线上支付还是线下支付，在消费的同时都可以获取积分。获取的积分同样可以进行礼品兑换或者商城现金抵用。
</p>
<p align=\"left\">
	　通过这种消费互利机制，系统已在微信端建立了一套完善的商家与消费者生态循环体系；并且已完美的解决了微信会员卡对于线上消费与线下消费的积分问题。
</p>
</span>','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('106','微信wifi','0','','0','只有关注微信公众号的粉丝才可以使用wifi，通过这种方式在商家店铺里面安装微信wifi，可以大量吸引粉丝关注商家公众号。','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 插入数据 `vifnn_funintro` */
 INSERT INTO `vifnn_funintro` VALUES ('107','无线订单打印机','0','','0','该打印机设置简单，插入手机卡便可以自动打印订餐信息、酒店预定信息、预约信息等','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('108','微信照片打印机','0','','0','用于关注公众号以后，粉丝发送照片给公众号，便可以利用打印机打印照片，这是一个很好的增加粉丝的途径','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0',''),('113','关于我们','0','','1','后台关于我们手动添加','0','','','','','','','','','','','','','','','','','','0','','','','0','','0','','','','0','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_fuwuuser` */
 DROP TABLE IF EXISTS `vifnn_fuwuuser`;/* MySQLReback Separation */ CREATE TABLE `vifnn_fuwuuser` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `user_id` varchar(512) DEFAULT NULL COMMENT '用户的userId',
  `user_type_value` int(1) DEFAULT NULL COMMENT '用户类型（1/2） 1代表公司账户； 2代表个人账户',
  `user_status` varchar(1) DEFAULT NULL COMMENT '用户状态（Q/T/B/W）。 Q代表快速注册用户；T代表已认证用户；B代表被冻结账户；W代表已注册，未激活的账户',
  `firm_name` varchar(100) DEFAULT NULL COMMENT '公司名称（用户类型是公司类型时公司名称才有此字段）。',
  `real_name` varchar(100) DEFAULT NULL COMMENT '用户的真实姓名。',
  `avatar` varchar(200) DEFAULT NULL COMMENT '用户头像',
  `cert_no` varchar(100) DEFAULT NULL COMMENT '证件号码',
  `gender` varchar(1) DEFAULT NULL COMMENT '性别（F：女性；M：男性）',
  `phone` varchar(20) DEFAULT NULL COMMENT '电话号码。',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号码。',
  `is_certified` varchar(1) DEFAULT NULL COMMENT '是否通过实名认证。T是通过 F是没有实名认证',
  `is_student_certified` varchar(1) DEFAULT NULL COMMENT '是否是学生。T表示是学生，F表示不是学生',
  `is_bank_auth` varchar(1) DEFAULT NULL COMMENT 'T为是银行卡认证，F为非银行卡认证。',
  `is_id_auth` varchar(1) DEFAULT NULL COMMENT 'T为是身份证认证，F为非身份证认证。',
  `is_mobile_auth` varchar(1) DEFAULT NULL COMMENT 'T为是手机认证，F为非手机认证。',
  `is_licence_auth` varchar(1) DEFAULT NULL COMMENT 'T为通过营业执照认证，F为没有通过',
  `cert_type_value` int(3) DEFAULT NULL COMMENT '0:身份证；1:护照；2:军官证；3:士兵证；4:回乡证；5:临时身份证；6:户口簿；7:警官证；8:台胞证；9:营业执照；10其它证件',
  `province` varchar(20) DEFAULT NULL COMMENT '省份名称。',
  `city` varchar(20) DEFAULT NULL COMMENT '市名称。',
  `area` varchar(20) DEFAULT NULL COMMENT '区县名称。',
  `address` varchar(200) DEFAULT NULL COMMENT '详细地址。',
  `zip` int(20) DEFAULT NULL COMMENT '邮政编码。',
  `address_code` int(100) DEFAULT NULL COMMENT '区域编码，暂时不返回值',
  `type` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) DEFAULT NULL,
  `wecha_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`vifnn_id`),
  KEY `IDX_TOKEN` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_game_config` */
 DROP TABLE IF EXISTS `vifnn_game_config`;/* MySQLReback Separation */ CREATE TABLE `vifnn_game_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(30) NOT NULL DEFAULT '',
  `wxid` varchar(40) NOT NULL DEFAULT '',
  `wxname` varchar(50) NOT NULL DEFAULT '',
  `wxlogo` varchar(150) NOT NULL DEFAULT '',
  `link` varchar(150) NOT NULL DEFAULT '',
  `uid` int(11) NOT NULL DEFAULT '0',
  `key` varchar(40) NOT NULL DEFAULT '',
  `attentionText` char(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `TOKEN` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_game_convert` */
 DROP TABLE IF EXISTS `vifnn_game_convert`;/* MySQLReback Separation */ CREATE TABLE `vifnn_game_convert` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `u_game_id` int(11) NOT NULL,
  `fans_id` int(11) DEFAULT NULL,
  `convert_code` varchar(255) NOT NULL,
  `awards_level` tinyint(9) NOT NULL COMMENT '奖品级别',
  `is_use` tinyint(2) DEFAULT '0' COMMENT '0：未使用；1：已使用',
  `convert_time` timestamp NULL DEFAULT NULL COMMENT '兑换时间',
  `create_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `has_send` varchar(255) DEFAULT '0' COMMENT '微信红包已经发送的金额',
  PRIMARY KEY (`id`),
  KEY `UID` (`uid`) USING BTREE,
  KEY `GAME_ID` (`game_id`) USING BTREE,
  KEY `UGAME_ID` (`u_game_id`) USING BTREE,
  KEY `FANS_ID` (`fans_id`) USING BTREE,
  KEY `IDX_GAME_CONVERT` (`uid`,`game_id`,`u_game_id`,`fans_id`) USING BTREE,
  KEY `CONVERT_CODE` (`convert_code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_game_records` */
 DROP TABLE IF EXISTS `vifnn_game_records`;/* MySQLReback Separation */ CREATE TABLE `vifnn_game_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL DEFAULT '0',
  `token` varchar(30) NOT NULL DEFAULT '',
  `wecha_id` varchar(50) NOT NULL DEFAULT '',
  `score` float(11,2) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL,
  `u_game_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gameid` (`gameid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_gamereply_info` */
 DROP TABLE IF EXISTS `vifnn_gamereply_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_gamereply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `picurl` varchar(120) NOT NULL DEFAULT '',
  `picurls1` varchar(120) NOT NULL DEFAULT '',
  `info` varchar(120) NOT NULL DEFAULT '',
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `copyright` text NOT NULL,
  `ad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_games` */
 DROP TABLE IF EXISTS `vifnn_games`;/* MySQLReback Separation */ CREATE TABLE `vifnn_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(40) NOT NULL DEFAULT '',
  `gameid` int(11) NOT NULL DEFAULT '0',
  `picurl` varchar(160) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(60) NOT NULL DEFAULT '',
  `intro` varchar(500) NOT NULL DEFAULT '',
  `selfinfo` varchar(5000) NOT NULL DEFAULT '',
  `token` varchar(20) NOT NULL DEFAULT '',
  `playcount` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `limit_num` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `is_share` tinyint(1) NOT NULL,
  `share_value` varchar(255) NOT NULL COMMENT '序列化',
  `share_callback` tinyint(2) NOT NULL DEFAULT '0' COMMENT '分享回调类型0:再玩一次,1:红包,2:卡券,3:积分',
  `is_release` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0：未发布；1： 已发布；',
  PRIMARY KEY (`id`),
  KEY `TOKEN` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_gametreply_info` */
 DROP TABLE IF EXISTS `vifnn_gametreply_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_gametreply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `picurl` varchar(120) NOT NULL DEFAULT '',
  `picurls1` varchar(120) NOT NULL DEFAULT '',
  `info` varchar(120) NOT NULL DEFAULT '',
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `copyright` text NOT NULL,
  `ad` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_gamettreply_info` */
 DROP TABLE IF EXISTS `vifnn_gamettreply_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_gamettreply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `picurl` varchar(120) NOT NULL DEFAULT '',
  `picurls1` varchar(120) NOT NULL DEFAULT '',
  `info` varchar(120) NOT NULL DEFAULT '',
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `copyright` text NOT NULL,
  `ad` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_goldegg` */
 DROP TABLE IF EXISTS `vifnn_goldegg`;/* MySQLReback Separation */ CREATE TABLE `vifnn_goldegg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `joinnum` int(11) NOT NULL COMMENT '????',
  `click` int(11) NOT NULL COMMENT '????',
  `token` varchar(255) NOT NULL COMMENT '??TOKEN',
  `keyword` varchar(255) NOT NULL COMMENT '???',
  `startpicurl` varchar(255) NOT NULL COMMENT '??????????',
  `title` varchar(255) NOT NULL COMMENT '????',
  `txt` varchar(255) NOT NULL COMMENT '?????????????',
  `summary` varchar(255) NOT NULL COMMENT '??',
  `startdate` int(11) NOT NULL COMMENT '??????',
  `enddate` int(11) NOT NULL COMMENT '??????',
  `info` varchar(255) NOT NULL COMMENT '????',
  `aginfo` varchar(255) NOT NULL COMMENT '??????',
  `endtite` varchar(255) NOT NULL COMMENT '????????',
  `endpicurl` varchar(255) NOT NULL COMMENT '????????',
  `endinfo` varchar(255) NOT NULL COMMENT '??????',
  `allpeople` int(11) NOT NULL COMMENT '???????',
  `canrqnums` int(22) NOT NULL COMMENT '????????',
  `parssword` int(15) NOT NULL COMMENT '????',
  `snimport` tinyint(1) NOT NULL COMMENT 'SN?????',
  `renamesn` varchar(60) NOT NULL DEFAULT 'SN?' COMMENT 'SN?????',
  `renametel` varchar(60) NOT NULL DEFAULT '???' COMMENT '??????',
  `displayjpnums` int(1) NOT NULL COMMENT '????????????',
  `createtime` int(11) NOT NULL COMMENT '??????',
  `status` int(1) NOT NULL COMMENT '????,0???,1???,2???',
  `verify` int(1) NOT NULL COMMENT '???????',
  `verifynum` int(11) NOT NULL DEFAULT '0' COMMENT '?????',
  `verifycode` varchar(255) NOT NULL COMMENT '?????',
  `type` varchar(10) NOT NULL COMMENT '????',
  `first` varchar(50) NOT NULL COMMENT '???????',
  `firstnums` int(4) NOT NULL COMMENT '???????',
  `firstlucknums` int(11) NOT NULL COMMENT '???????',
  `second` varchar(50) NOT NULL COMMENT '???????',
  `secondnums` int(4) NOT NULL COMMENT '???????',
  `secondlucknums` int(11) NOT NULL COMMENT '???????',
  `third` varchar(50) NOT NULL COMMENT '???',
  `thirdnums` int(4) NOT NULL COMMENT '???',
  `thirdlucknums` int(11) NOT NULL COMMENT '???',
  `four` varchar(50) NOT NULL COMMENT '???????',
  `fournums` int(11) NOT NULL COMMENT '???????',
  `fourlucknums` int(11) NOT NULL COMMENT '???????',
  `five` varchar(50) NOT NULL COMMENT '???',
  `fivenums` int(11) NOT NULL COMMENT '???',
  `fivelucknums` int(11) NOT NULL COMMENT '???',
  `six` varchar(50) NOT NULL COMMENT '???????',
  `sixnums` int(11) NOT NULL COMMENT '???????',
  `sixlucknums` int(11) NOT NULL COMMENT '???????',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_goldegg_record` */
 DROP TABLE IF EXISTS `vifnn_goldegg_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_goldegg_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL COMMENT '??ID',
  `usenums` tinyint(1) NOT NULL DEFAULT '0' COMMENT '??????',
  `wecha_id` varchar(60) NOT NULL COMMENT '???????',
  `token` varchar(60) NOT NULL COMMENT '??TOKEN',
  `islucky` int(1) NOT NULL COMMENT '????',
  `wecha_name` varchar(60) NOT NULL COMMENT '???',
  `phone` varchar(20) NOT NULL COMMENT '???',
  `sn` varchar(60) NOT NULL COMMENT '??????',
  `time` int(11) NOT NULL COMMENT '??',
  `prize` varchar(60) NOT NULL DEFAULT '' COMMENT '????',
  `sendstutas` int(11) NOT NULL DEFAULT '0' COMMENT '????',
  `sendtime` int(11) NOT NULL COMMENT '????',
  PRIMARY KEY (`id`,`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_greeting_card` */
 DROP TABLE IF EXISTS `vifnn_greeting_card`;/* MySQLReback Separation */ CREATE TABLE `vifnn_greeting_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(10) NOT NULL DEFAULT '0',
  `token` varchar(60) NOT NULL,
  `num` int(11) NOT NULL,
  `click` int(11) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `bakcground_url` varchar(200) NOT NULL,
  `mp3` varchar(200) NOT NULL,
  `style` tinyint(2) NOT NULL,
  `name` varchar(60) NOT NULL,
  `zfname` varchar(60) NOT NULL,
  `copy` varchar(200) NOT NULL,
  `info` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hcar` */
 DROP TABLE IF EXISTS `vifnn_hcar`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hcar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hcarreplay` */
 DROP TABLE IF EXISTS `vifnn_hcarreplay`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hcarreplay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `jianjie` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `lj` varchar(255) DEFAULT NULL,
  `sm` mediumtext,
  `mypicurl1` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_heka` */
 DROP TABLE IF EXISTS `vifnn_heka`;/* MySQLReback Separation */ CREATE TABLE `vifnn_heka` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(200) NOT NULL,
  `info` varchar(600) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `background` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_heka_list` */
 DROP TABLE IF EXISTS `vifnn_heka_list`;/* MySQLReback Separation */ CREATE TABLE `vifnn_heka_list` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(12) unsigned NOT NULL,
  `token` varchar(200) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `backmusic` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_helping` */
 DROP TABLE IF EXISTS `vifnn_helping`;/* MySQLReback Separation */ CREATE TABLE `vifnn_helping` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(40) NOT NULL,
  `title` char(40) NOT NULL,
  `keyword` char(20) NOT NULL,
  `intro` varchar(250) NOT NULL,
  `info` text NOT NULL,
  `reply_pic` varchar(250) NOT NULL,
  `top_pic` varchar(250) NOT NULL,
  `start` char(15) NOT NULL,
  `end` char(15) NOT NULL,
  `add_time` char(15) NOT NULL,
  `is_open` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  `is_attention` tinyint(4) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `is_newtp` int(11) NOT NULL DEFAULT '0',
  `is_sms` int(11) NOT NULL DEFAULT '0',
  `fxtitle` varchar(200) DEFAULT NULL,
  `fxinfo` varchar(300) DEFAULT NULL,
  `rank_num` int(11) DEFAULT NULL,
  `pv` int(11) NOT NULL DEFAULT '0',
  `is_help` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `is_open` (`is_open`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_helping_news` */
 DROP TABLE IF EXISTS `vifnn_helping_news`;/* MySQLReback Separation */ CREATE TABLE `vifnn_helping_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `imgurl` varchar(200) NOT NULL,
  `url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_helping_prize` */
 DROP TABLE IF EXISTS `vifnn_helping_prize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_helping_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `imgurl` varchar(200) NOT NULL,
  `starttime` int(11) NOT NULL,
  `stoptime` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_helping_record` */
 DROP TABLE IF EXISTS `vifnn_helping_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_helping_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(35) NOT NULL,
  `pid` int(11) NOT NULL,
  `share_key` char(40) NOT NULL,
  `addtime` char(35) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `share_key` (`share_key`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_helping_user` */
 DROP TABLE IF EXISTS `vifnn_helping_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_helping_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(40) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `help_count` int(11) NOT NULL,
  `add_time` char(15) NOT NULL,
  `share_key` char(40) NOT NULL,
  `help_num` int(100) NOT NULL,
  `help_rank` int(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `share_num` int(11) NOT NULL DEFAULT '0',
  `is_join` int(11) NOT NULL DEFAULT '0',
  `is_join2` int(11) NOT NULL DEFAULT '1',
  `pv` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `share_key` (`share_key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hfor_item` */
 DROP TABLE IF EXISTS `vifnn_hfor_item`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hfor_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `wecha_id` varchar(255) NOT NULL,
  `createtime` int(11) NOT NULL,
  `tongji` int(11) DEFAULT '0',
  `ip` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hforward` */
 DROP TABLE IF EXISTS `vifnn_hforward`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hforward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `token` varchar(50) NOT NULL,
  `picurl` varchar(500) NOT NULL,
  `info` text NOT NULL,
  `statdate` int(11) NOT NULL,
  `lj` varchar(255) DEFAULT NULL,
  `gz` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_home` */
 DROP TABLE IF EXISTS `vifnn_home`;/* MySQLReback Separation */ CREATE TABLE `vifnn_home` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL,
  `picurl` varchar(120) NOT NULL,
  `apiurl` varchar(150) NOT NULL,
  `homeurl` varchar(120) NOT NULL,
  `info` varchar(120) NOT NULL,
  `musicurl` varchar(180) NOT NULL DEFAULT '',
  `plugmenucolor` varchar(10) NOT NULL DEFAULT '',
  `copyright` varchar(200) NOT NULL DEFAULT '',
  `animation` tinyint(2) NOT NULL,
  `logo` varchar(200) NOT NULL DEFAULT '',
  `radiogroup` mediumint(4) NOT NULL DEFAULT '0',
  `advancetpl` tinyint(1) NOT NULL DEFAULT '0',
  `dianhua` varchar(20) NOT NULL,
  `keyword` char(255) NOT NULL,
  `bjdh` int(10) NOT NULL DEFAULT '0',
  `head` varchar(255) NOT NULL,
  `zdh` tinyint(1) NOT NULL,
  `gzhurl` char(255) DEFAULT NULL COMMENT '公众号链接地址',
  `start` int(11) NOT NULL COMMENT '开场动画',
  `stpic` varchar(200) NOT NULL COMMENT '开场动画图片',
  `switch` int(11) NOT NULL DEFAULT '0',
  `pinglun` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启微站全站评论',
  `uyanid` varchar(50) NOT NULL DEFAULT '' COMMENT '友言官网注册的uid',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_home` */
 INSERT INTO `vifnn_home` VALUES ('5','zrtmca1421056092','1','http://800.vifnn.com/tpl/static/attachment/icon/canyin/canyin_red/1.png','','','1','','','','0','','0','0','','','0','','0','','0','/tpl/static/home/kcdhbj.jpg','0','1','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_home_background` */
 DROP TABLE IF EXISTS `vifnn_home_background`;/* MySQLReback Separation */ CREATE TABLE `vifnn_home_background` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL DEFAULT '',
  `picurl` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `taxis` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hongbao` */
 DROP TABLE IF EXISTS `vifnn_hongbao`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hongbao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `keyword` char(30) NOT NULL,
  `action_name` char(40) NOT NULL COMMENT '红包活动名称',
  `sharetimes` int(10) NOT NULL COMMENT '最小合体次数',
  `min_money` float(6,2) NOT NULL COMMENT '随机数最小值',
  `max_money` float(6,2) NOT NULL COMMENT '随机数最大值',
  `total_money` float(6,2) DEFAULT NULL,
  `start_time` char(11) NOT NULL COMMENT '活动开始时间',
  `end_time` char(11) NOT NULL COMMENT '活动结束时间',
  `need_phone` enum('1','2') NOT NULL COMMENT '是否需要注册手机号',
  `need_follow` enum('1','2') NOT NULL COMMENT '是否需要关注',
  `action_desc` varchar(256) NOT NULL COMMENT '活动介绍',
  `reply_pic` varchar(100) NOT NULL,
  `status` enum('1','2') NOT NULL COMMENT '是否开启',
  `reply_title` varchar(20) NOT NULL,
  `reply_content` varchar(200) NOT NULL,
  `remind_word` varchar(255) NOT NULL,
  `remind_link` varchar(255) NOT NULL,
  `getway` tinyint(1) NOT NULL DEFAULT '1',
  `timeline_hide` tinyint(1) NOT NULL,
  `display_nums` smallint(6) NOT NULL,
  `limit_join` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hongbao_index` (`id`,`token`,`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hongbao_grabber` */
 DROP TABLE IF EXISTS `vifnn_hongbao_grabber`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hongbao_grabber` (
  `grabber_id` int(11) NOT NULL AUTO_INCREMENT,
  `hongbao_id` int(11) NOT NULL COMMENT '抢到的红包id',
  `money` float(6,2) unsigned NOT NULL COMMENT '抢到的红包金额',
  `grabber_nickname` varchar(20) DEFAULT '' COMMENT '抢红包者昵称',
  `grabber_headimgurl` varchar(255) DEFAULT NULL COMMENT '抢红包者头像',
  `grabber_shareid` varchar(100) DEFAULT '' COMMENT '抢红包者分享的key',
  `grabber_wechaid` varchar(100) DEFAULT '' COMMENT '抢红包者wcha_id',
  `grabber_sex` enum('0','1') DEFAULT '0' COMMENT '抢红包者性别',
  `grabber_tel` varchar(20) DEFAULT '' COMMENT '抢红包者电话',
  `grabber_qq` varchar(20) DEFAULT '' COMMENT '抢红包者QQ',
  `grabber_address` varchar(50) DEFAULT '' COMMENT '抢红包者address',
  `grabber_province` varchar(50) DEFAULT '' COMMENT '抢红包者province',
  `grabber_city` varchar(50) DEFAULT '' COMMENT '抢红包者city',
  `share_money` int(11) DEFAULT '0' COMMENT '抢红包者合体奖励的金额',
  `share_content` int(11) DEFAULT '0' COMMENT '抢红包者分享语',
  `token` varchar(50) DEFAULT NULL COMMENT 'token',
  `grabber_time` int(11) NOT NULL,
  `isgrabbed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`grabber_id`),
  KEY `hongbao_id` (`hongbao_id`) USING BTREE,
  KEY `my_packets` (`hongbao_id`,`grabber_wechaid`,`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hongbao_share` */
 DROP TABLE IF EXISTS `vifnn_hongbao_share`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hongbao_share` (
  `share_id` int(11) NOT NULL AUTO_INCREMENT,
  `hongbao_id` int(11) NOT NULL COMMENT '红包id',
  `add_money` float(6,2) unsigned NOT NULL COMMENT '为合体者贡献的金额',
  `share_key` varchar(50) NOT NULL COMMENT '分享code',
  `share_nickname` varchar(50) DEFAULT '' COMMENT '分享者昵称',
  `share_pic` varchar(255) DEFAULT '' COMMENT '分享者头像',
  `is_opened` tinyint(4) DEFAULT '0' COMMENT '是否进入分享页',
  `share_time` int(11) DEFAULT '0' COMMENT '分享时间',
  `share_wechaid` varchar(50) DEFAULT '' COMMENT '分享者openid',
  PRIMARY KEY (`share_id`),
  KEY `hongbao_id` (`hongbao_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_host` */
 DROP TABLE IF EXISTS `vifnn_host`;/* MySQLReback Separation */ CREATE TABLE `vifnn_host` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL COMMENT '关键词',
  `title` varchar(50) NOT NULL COMMENT '商家名称',
  `address` varchar(50) NOT NULL COMMENT '商家地',
  `tel` varchar(13) NOT NULL COMMENT '商家电话',
  `tel2` varchar(13) NOT NULL COMMENT '手机号',
  `ppicurl` varchar(250) NOT NULL COMMENT '订房封面图片',
  `headpic` varchar(250) NOT NULL COMMENT '订单页头部图片',
  `name` varchar(50) NOT NULL COMMENT '文字描述',
  `sort` int(11) NOT NULL COMMENT '排序',
  `picurl` varchar(100) NOT NULL COMMENT '图片地址',
  `url` varchar(50) NOT NULL COMMENT '图片跳转地址以http',
  `info` text NOT NULL COMMENT '商家介绍：',
  `info2` text NOT NULL COMMENT '订房说明u',
  `creattime` int(11) NOT NULL COMMENT '创建日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_host_list_add` */
 DROP TABLE IF EXISTS `vifnn_host_list_add`;/* MySQLReback Separation */ CREATE TABLE `vifnn_host_list_add` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) NOT NULL COMMENT '商家id',
  `token` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT '房间类型',
  `typeinfo` varchar(100) NOT NULL COMMENT '简要说明',
  `price` decimal(10,2) NOT NULL COMMENT '原价：',
  `yhprice` decimal(10,2) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '文字描述',
  `picurl` varchar(110) NOT NULL COMMENT '图片地址',
  `url` varchar(500) NOT NULL COMMENT '图片跳转地址以http',
  `info` text NOT NULL COMMENT '配套设施',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_host_order` */
 DROP TABLE IF EXISTS `vifnn_host_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_host_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  `book_people` varchar(50) NOT NULL COMMENT '预订人',
  `tel` varchar(13) NOT NULL COMMENT '电话',
  `check_in` int(11) NOT NULL COMMENT '入住时间',
  `check_out` int(11) NOT NULL COMMENT '离开时间',
  `room_type` varchar(50) NOT NULL COMMENT '房间类型',
  `book_time` int(11) NOT NULL COMMENT '预订时间',
  `book_num` int(11) NOT NULL COMMENT '预订数量',
  `price` decimal(10,2) NOT NULL COMMENT ' 价格',
  `order_status` int(11) NOT NULL COMMENT '订单状态 1 成功,2 失败,3 未处理',
  `hid` int(11) NOT NULL COMMENT '订房商家id',
  `remarks` varchar(250) NOT NULL COMMENT '留言备注',
  `orderid` varchar(100) NOT NULL,
  `paytype` varchar(100) NOT NULL,
  `third_id` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hotels_house` */
 DROP TABLE IF EXISTS `vifnn_hotels_house`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hotels_house` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(80) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `sid` int(10) unsigned NOT NULL,
  `note` varchar(500) NOT NULL,
  `is_open` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `sid` (`sid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hotels_house_sort` */
 DROP TABLE IF EXISTS `vifnn_hotels_house_sort`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hotels_house_sort` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(200) NOT NULL,
  `price` float NOT NULL,
  `vprice` float NOT NULL,
  `note` varchar(500) NOT NULL,
  `num` tinyint(1) unsigned NOT NULL,
  `houses` smallint(3) unsigned NOT NULL,
  `area` float NOT NULL,
  `bed` varchar(100) NOT NULL,
  `floor` varchar(100) NOT NULL,
  `bedwidth` varchar(100) NOT NULL,
  `network` varchar(100) NOT NULL,
  `smoke` varchar(100) NOT NULL,
  `image_1` varchar(200) NOT NULL,
  `image_2` varchar(200) NOT NULL,
  `image_3` varchar(200) NOT NULL,
  `image_4` varchar(200) NOT NULL,
  `is_open` tinyint(1) NOT NULL,
  `keep_time` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hotels_image` */
 DROP TABLE IF EXISTS `vifnn_hotels_image`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hotels_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(80) NOT NULL,
  `image` varchar(200) NOT NULL,
  `info` varchar(80) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_hotels_order` */
 DROP TABLE IF EXISTS `vifnn_hotels_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_hotels_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `nums` smallint(3) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `tel` varchar(13) NOT NULL,
  `time` int(11) NOT NULL,
  `startdate` int(8) unsigned NOT NULL,
  `enddate` int(8) unsigned NOT NULL,
  `paid` tinyint(1) unsigned NOT NULL,
  `orderid` varchar(100) NOT NULL,
  `printed` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `paytype` varchar(100) NOT NULL,
  `third_id` varchar(100) NOT NULL,
  `remark` varchar(800) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`wecha_id`),
  KEY `token` (`token`),
  KEY `orderid` (`orderid`),
  KEY `enddate` (`enddate`),
  KEY `sid` (`sid`),
  KEY `stardate` (`startdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_iframe` */
 DROP TABLE IF EXISTS `vifnn_iframe`;/* MySQLReback Separation */ CREATE TABLE `vifnn_iframe` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `tp` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_images` */
 DROP TABLE IF EXISTS `vifnn_images`;/* MySQLReback Separation */ CREATE TABLE `vifnn_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fc` char(250) NOT NULL,
  `about` char(250) NOT NULL,
  `price` char(250) NOT NULL,
  `login` char(250) NOT NULL,
  `help` char(250) NOT NULL,
  `common` char(250) NOT NULL,
  `agentid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_AGENTID` (`agentid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_img` */
 DROP TABLE IF EXISTS `vifnn_img`;/* MySQLReback Separation */ CREATE TABLE `vifnn_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uname` varchar(90) NOT NULL,
  `keyword` char(255) NOT NULL,
  `precisions` tinyint(1) NOT NULL DEFAULT '0',
  `text` text NOT NULL COMMENT '简介',
  `classid` int(11) NOT NULL,
  `classname` varchar(60) NOT NULL,
  `pic` char(255) NOT NULL COMMENT '封面图片',
  `showpic` varchar(1) NOT NULL COMMENT '图片是否显示封面',
  `info` longtext NOT NULL,
  `url` char(255) NOT NULL COMMENT '图文外链地址',
  `createtime` varchar(13) NOT NULL,
  `uptatetime` varchar(13) NOT NULL,
  `click` int(11) NOT NULL,
  `token` char(30) NOT NULL,
  `title` varchar(60) NOT NULL,
  `usort` int(11) NOT NULL DEFAULT '1',
  `longitude` varchar(20) NOT NULL DEFAULT '0',
  `latitude` varchar(20) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `writer` varchar(200) DEFAULT NULL COMMENT '作者',
  `texttype` int(11) NOT NULL DEFAULT '1' COMMENT '文本类型',
  `usorts` int(11) NOT NULL DEFAULT '1' COMMENT '分类文章排列顺序',
  `is_focus` tinyint(4) NOT NULL,
  `wechat_group` varchar(500) NOT NULL,
  `pinglun` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启当前图文评论',
  `media_id` varchar(200) NOT NULL DEFAULT '',
  `dashang` int(11) NOT NULL DEFAULT '2',
  `dashang_type` int(11) NOT NULL DEFAULT '1',
  `dashang_price_type` int(11) NOT NULL DEFAULT '1',
  `dashang_price_min` int(20) unsigned NOT NULL DEFAULT '0',
  `dashang_price_max` int(20) unsigned NOT NULL DEFAULT '0',
  `dashang_price_1` int(20) unsigned NOT NULL DEFAULT '0',
  `dashang_price_2` int(20) unsigned NOT NULL DEFAULT '0',
  `dashang_price_3` int(20) unsigned NOT NULL DEFAULT '0',
  `dashang_price_4` int(20) unsigned NOT NULL DEFAULT '0',
  `dashang_price_5` int(20) unsigned NOT NULL DEFAULT '0',
  `dashang_price_6` int(20) unsigned NOT NULL DEFAULT '0',
  `dashang_rank_type` int(11) NOT NULL DEFAULT '1',
  `dashang_rank_num` int(11) NOT NULL DEFAULT '5',
  `iscomment` tinyint(1) NOT NULL DEFAULT '0',
  `autocomment` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `classid` (`classid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_img_comment` */
 DROP TABLE IF EXISTS `vifnn_img_comment`;/* MySQLReback Separation */ CREATE TABLE `vifnn_img_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `imgid` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `third_id` varchar(100) DEFAULT NULL,
  `ctime` int(11) NOT NULL,
  `content` text NOT NULL,
  `wname` varchar(200) NOT NULL,
  `wheaderimg` varchar(200) NOT NULL,
  `reply` text NOT NULL,
  `support` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_img_comment_record` */
 DROP TABLE IF EXISTS `vifnn_img_comment_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_img_comment_record` (
  `imgcmt_id` int(11) NOT NULL,
  `wecha_id` varchar(200) CHARACTER SET gbk NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_img_dashang_order` */
 DROP TABLE IF EXISTS `vifnn_img_dashang_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_img_dashang_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `imgid` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `orderid` varchar(100) DEFAULT NULL,
  `price` varchar(100) NOT NULL,
  `price_s` int(20) NOT NULL,
  `paid` int(11) NOT NULL DEFAULT '0',
  `paytype` varchar(100) DEFAULT NULL,
  `third_id` varchar(100) DEFAULT NULL,
  `transactionid` varchar(150) DEFAULT NULL,
  `addtime` int(11) NOT NULL,
  `sum` int(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_img_multi` */
 DROP TABLE IF EXISTS `vifnn_img_multi`;/* MySQLReback Separation */ CREATE TABLE `vifnn_img_multi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keywords` varchar(100) DEFAULT '',
  `imgs` varchar(100) DEFAULT '',
  `token` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_indent` */
 DROP TABLE IF EXISTS `vifnn_indent`;/* MySQLReback Separation */ CREATE TABLE `vifnn_indent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `gid` tinyint(2) NOT NULL,
  `month` mediumint(5) NOT NULL DEFAULT '0',
  `uname` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `info` int(11) NOT NULL,
  `indent_id` char(20) NOT NULL,
  `widtrade_no` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_indent` */
 INSERT INTO `vifnn_indent` VALUES ('6','2','0','0','vifnn','充值','0','2_1444760510','','200','1444760510','0'),('7','2','0','0','vifnn','充值','0','2_1444998688','','200','1444998688','0'),('8','2','0','0','vifnn','充值','0','2_1444998699','','200','1444998699','0'),('9','2','0','0','vifnn','充值','0','2_1444998754','','200','1444998754','0'),('10','3','0','0','ehaisong','充值','0','3_1459156176','','200','1459156176','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_invite` */
 DROP TABLE IF EXISTS `vifnn_invite`;/* MySQLReback Separation */ CREATE TABLE `vifnn_invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `keyword` varchar(10) NOT NULL,
  `title` varchar(30) NOT NULL,
  `content` varchar(360) NOT NULL,
  `replypic` varchar(200) NOT NULL,
  `cover` varchar(150) NOT NULL,
  `meetpic` varchar(150) NOT NULL,
  `photo` varchar(20) NOT NULL,
  `linkman` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `site` varchar(100) NOT NULL,
  `twopic` varchar(150) NOT NULL,
  `theme` varchar(50) NOT NULL,
  `themeurl` varchar(150) NOT NULL,
  `warn` varchar(100) NOT NULL,
  `inback` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_invite_enroll` */
 DROP TABLE IF EXISTS `vifnn_invite_enroll`;/* MySQLReback Separation */ CREATE TABLE `vifnn_invite_enroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yid` int(1) NOT NULL,
  `token` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `post` varchar(50) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `comp` varchar(100) NOT NULL,
  `wecha_id` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_invite_meeting` */
 DROP TABLE IF EXISTS `vifnn_invite_meeting`;/* MySQLReback Separation */ CREATE TABLE `vifnn_invite_meeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yid` int(1) NOT NULL,
  `token` varchar(60) NOT NULL,
  `time` int(11) NOT NULL,
  `ytime` int(11) NOT NULL,
  `xtime` int(11) NOT NULL,
  `content` text NOT NULL,
  `guest` varchar(200) NOT NULL,
  `call` varchar(20) NOT NULL,
  `site` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_invite_partner` */
 DROP TABLE IF EXISTS `vifnn_invite_partner`;/* MySQLReback Separation */ CREATE TABLE `vifnn_invite_partner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yid` int(1) NOT NULL,
  `token` varchar(30) NOT NULL,
  `partnertype` varchar(50) NOT NULL COMMENT '合作伙伴类型',
  `typepic` varchar(200) NOT NULL COMMENT '类型图片',
  `company` varchar(200) NOT NULL COMMENT '公司',
  `photo` varchar(100) NOT NULL COMMENT '服务热线',
  `scheme` text NOT NULL COMMENT '方案',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_invite_plan` */
 DROP TABLE IF EXISTS `vifnn_invite_plan`;/* MySQLReback Separation */ CREATE TABLE `vifnn_invite_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yid` int(1) NOT NULL,
  `token` varchar(50) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `call` varchar(20) NOT NULL,
  `site` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_invite_user` */
 DROP TABLE IF EXISTS `vifnn_invite_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_invite_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` char(60) NOT NULL,
  `yid` int(1) NOT NULL,
  `headpic` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `synopsis` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_invites` */
 DROP TABLE IF EXISTS `vifnn_invites`;/* MySQLReback Separation */ CREATE TABLE `vifnn_invites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `keyword` varchar(30) NOT NULL,
  `picurl` varchar(120) NOT NULL,
  `type` int(3) NOT NULL,
  `brief` varchar(200) NOT NULL,
  `content` varchar(300) NOT NULL,
  `statdate` varchar(30) NOT NULL,
  `address` varchar(120) NOT NULL,
  `lng` double NOT NULL,
  `lat` double NOT NULL,
  `qr_code` varchar(120) NOT NULL,
  `copyrite` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_invites_info` */
 DROP TABLE IF EXISTS `vifnn_invites_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_invites_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iid` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `username` varchar(20) NOT NULL,
  `telphone` varchar(15) NOT NULL,
  `rdo_go` int(2) NOT NULL,
  `content` varchar(200) NOT NULL,
  `type` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jiejing` */
 DROP TABLE IF EXISTS `vifnn_jiejing`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jiejing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `title` varchar(50) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `pano` varchar(200) NOT NULL,
  `text` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jikedati` */
 DROP TABLE IF EXISTS `vifnn_jikedati`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jikedati` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `timu` text NOT NULL,
  `answer1` text NOT NULL,
  `answer2` text NOT NULL,
  `answer3` text NOT NULL,
  `answer4` text NOT NULL,
  `score1` text NOT NULL,
  `score2` text NOT NULL,
  `score3` text NOT NULL,
  `score4` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jikedati_flash` */
 DROP TABLE IF EXISTS `vifnn_jikedati_flash`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jikedati_flash` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `picurl1` char(255) NOT NULL,
  `picurl2` char(255) NOT NULL,
  `picurl3` char(255) NOT NULL,
  `picurl4` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jikedati_reply` */
 DROP TABLE IF EXISTS `vifnn_jikedati_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jikedati_reply` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `info` varchar(500) DEFAULT NULL COMMENT '公司简介',
  `title` text NOT NULL,
  `tp` char(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `scorename` text NOT NULL,
  `tip1` text NOT NULL,
  `tip2` text NOT NULL,
  `tip3` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jikedati_setcin` */
 DROP TABLE IF EXISTS `vifnn_jikedati_setcin`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jikedati_setcin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `info` text,
  `tel` varchar(11) DEFAULT NULL,
  `messages` text,
  `banner` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `lng` varchar(30) NOT NULL,
  `lat` varchar(30) NOT NULL,
  `picurl1` varchar(100) NOT NULL,
  `picurl2` varchar(100) NOT NULL,
  `picurl3` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jikedati_user` */
 DROP TABLE IF EXISTS `vifnn_jikedati_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jikedati_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `diaoyan_id` int(11) DEFAULT NULL,
  `wecha_id` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jingcai_changci` */
 DROP TABLE IF EXISTS `vifnn_jingcai_changci`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jingcai_changci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `stime` int(11) NOT NULL COMMENT '赛事时间',
  `pid` int(11) NOT NULL COMMENT '赛事回复配置id',
  `type_id` int(11) NOT NULL COMMENT '赛事类型',
  `zhudui` int(11) NOT NULL COMMENT '主队',
  `kedui` int(11) NOT NULL COMMENT '客场',
  `speilv` int(11) NOT NULL COMMENT '主胜赔率',
  `ppeilv` int(11) NOT NULL COMMENT '平赔率',
  `fpeilv` int(11) NOT NULL COMMENT '主负赔率',
  `minlimit` int(11) NOT NULL DEFAULT '0' COMMENT '最小限额',
  `zhuduinum` int(11) NOT NULL DEFAULT '0' COMMENT '主队进球',
  `keduinum` int(11) NOT NULL DEFAULT '0' COMMENT '客队进球',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '比赛状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jingcai_changci_record` */
 DROP TABLE IF EXISTS `vifnn_jingcai_changci_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jingcai_changci_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(200) NOT NULL,
  `type_id` int(11) NOT NULL COMMENT '赛事类型',
  `changci_id` int(11) NOT NULL COMMENT '赛事id',
  `ycjg` int(1) NOT NULL DEFAULT '1' COMMENT '预测结果',
  `ctime` int(11) NOT NULL COMMENT '预测时间',
  `sjm` varchar(50) NOT NULL COMMENT '抽奖码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jingcai_set` */
 DROP TABLE IF EXISTS `vifnn_jingcai_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jingcai_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `cover` varchar(200) NOT NULL,
  `bannerpic` varchar(500) NOT NULL,
  `info` varchar(500) DEFAULT NULL,
  `choujiang` int(1) DEFAULT '0',
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `token_2` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jingcai_team` */
 DROP TABLE IF EXISTS `vifnn_jingcai_team`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jingcai_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '赛事回复配置id',
  `type_id` int(11) NOT NULL COMMENT '赛事类型',
  `token` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '团体名字',
  `desc` varchar(500) NOT NULL COMMENT '团体描述',
  `team_logo` varchar(500) NOT NULL COMMENT '团体logo',
  `sort` tinyint(4) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '团队状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_jingcai_type` */
 DROP TABLE IF EXISTS `vifnn_jingcai_type`;/* MySQLReback Separation */ CREATE TABLE `vifnn_jingcai_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_kefu` */
 DROP TABLE IF EXISTS `vifnn_kefu`;/* MySQLReback Separation */ CREATE TABLE `vifnn_kefu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `title` varchar(50) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `info2` varchar(200) NOT NULL,
  `text` varchar(50) NOT NULL,
  `status` text NOT NULL,
  `sc` text NOT NULL,
  `cy` text NOT NULL,
  `lt` text NOT NULL,
  `yy` text NOT NULL,
  `zp` text NOT NULL,
  `hyk` text NOT NULL,
  `car` text NOT NULL,
  `yiliao` text NOT NULL,
  `jiaoyu` text NOT NULL,
  `jdbg` text NOT NULL,
  `fc` text NOT NULL,
  `ktv` text NOT NULL,
  `jiuba` text NOT NULL,
  `huisuo` text NOT NULL,
  `zhuangxiu` text NOT NULL,
  `meirong` text NOT NULL,
  `beauty` tinyint(4) NOT NULL,
  `fitness` tinyint(4) NOT NULL,
  `gover` tinyint(4) NOT NULL,
  `food` tinyint(4) NOT NULL,
  `travel` tinyint(4) NOT NULL,
  `flower` tinyint(4) NOT NULL,
  `property` tinyint(4) NOT NULL,
  `bar` tinyint(4) NOT NULL,
  `fitment` tinyint(4) NOT NULL,
  `wedding` tinyint(4) NOT NULL,
  `affections` tinyint(4) NOT NULL,
  `housekeeper` tinyint(4) NOT NULL,
  `lease` tinyint(4) NOT NULL,
  `fxdr` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_keyword` */
 DROP TABLE IF EXISTS `vifnn_keyword`;/* MySQLReback Separation */ CREATE TABLE `vifnn_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` char(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `module` varchar(15) NOT NULL,
  `precision` tinyint(1) NOT NULL DEFAULT '0',
  `precisions` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=230 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_keyword` */
 INSERT INTO `vifnn_keyword` VALUES ('64','','21','zrtmca1421056092','Musiccar','0','0'),('183','waphelp','1','zrtmca1421056092','waphelp','0','1'),('76','生活圈','1','zrtmca1421056092','LivingCircle','0','0'),('79','微店','1','zrtmca1421056092','Micrstore','0','0'),('88','waphelp','1','qcjnep1444636005','waphelp','0','1'),('208','waphelp','1','ezrehr1458789061','waphelp','0','1'),('186','生活圈','2','ezrehr1458789061','LivingCircle','0','0'),('187','微店','2','ezrehr1458789061','Micrstore','0','0'),('228','生活圈','3','zbemii1462891866','LivingCircle','0','0'),('226','waphelp','1','zbemii1462891866','waphelp','0','1'),('227','微店','3','zbemii1462891866','Micrstore','0','0'),('229','生活圈','4','ywxgfh1465574691','LivingCircle','0','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_knwxmy` */
 DROP TABLE IF EXISTS `vifnn_knwxmy`;/* MySQLReback Separation */ CREATE TABLE `vifnn_knwxmy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wecha_id` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `content` varchar(5000) DEFAULT NULL,
  `pic` varchar(255) DEFAULT '',
  `style` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `knwxopen` int(1) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `catgroy` varchar(233) NOT NULL,
  `save` varchar(255) NOT NULL,
  `click` varchar(255) NOT NULL DEFAULT '0',
  `share` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_knwxreplay` */
 DROP TABLE IF EXISTS `vifnn_knwxreplay`;/* MySQLReback Separation */ CREATE TABLE `vifnn_knwxreplay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `jianjie` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `gzlj` varchar(255) DEFAULT NULL,
  `open` int(1) DEFAULT NULL,
  `banquan` varchar(255) NOT NULL,
  `mypicurl1` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_lapiao` */
 DROP TABLE IF EXISTS `vifnn_lapiao`;/* MySQLReback Separation */ CREATE TABLE `vifnn_lapiao` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '??',
  `ms` varchar(1023) DEFAULT NULL COMMENT '??',
  `kssj` datetime DEFAULT NULL,
  `jssj` datetime DEFAULT NULL,
  `sl` int(11) DEFAULT '0' COMMENT '??',
  `pic` varchar(255) DEFAULT NULL COMMENT '??????',
  `keyword` varchar(50) DEFAULT NULL COMMENT '???',
  `jg` decimal(20,1) DEFAULT NULL COMMENT '??',
  `scjg` decimal(20,1) DEFAULT NULL COMMENT '????',
  `tgsl` int(5) unsigned DEFAULT '0' COMMENT '??',
  `ctrs` int(11) unsigned DEFAULT '0' COMMENT '??',
  `tbtx` varchar(1023) DEFAULT '' COMMENT '????',
  `noticetelon` tinyint(1) NOT NULL DEFAULT '0',
  `moreurl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_lapiao_record` */
 DROP TABLE IF EXISTS `vifnn_lapiao_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_lapiao_record` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `wxid` varchar(128) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  `tid` bigint(20) DEFAULT NULL COMMENT '??ID',
  `ctime` varchar(128) DEFAULT NULL,
  `un` varchar(50) DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `isused` tinyint(1) DEFAULT '0' COMMENT '????',
  `sn` varchar(23) DEFAULT NULL,
  `sl` int(11) DEFAULT '0',
  `mdid` bigint(20) DEFAULT NULL,
  `bz` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_lapiao_toupiao` */
 DROP TABLE IF EXISTS `vifnn_lapiao_toupiao`;/* MySQLReback Separation */ CREATE TABLE `vifnn_lapiao_toupiao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wxid` varchar(128) NOT NULL COMMENT '???oponid',
  `sn` varchar(23) NOT NULL COMMENT '??',
  `tid` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_leave` */
 DROP TABLE IF EXISTS `vifnn_leave`;/* MySQLReback Separation */ CREATE TABLE `vifnn_leave` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wecha_id` varchar(60) NOT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `token` varchar(60) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_links` */
 DROP TABLE IF EXISTS `vifnn_links`;/* MySQLReback Separation */ CREATE TABLE `vifnn_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `url` char(255) NOT NULL,
  `status` varchar(1) NOT NULL,
  `agentid` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `agentid` (`agentid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_live` */
 DROP TABLE IF EXISTS `vifnn_live`;/* MySQLReback Separation */ CREATE TABLE `vifnn_live` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `show_company` enum('0','1') NOT NULL,
  `name` char(50) NOT NULL,
  `keyword` char(40) NOT NULL,
  `open_pic` char(120) NOT NULL,
  `is_masking` enum('0','1') NOT NULL,
  `masking_pic` char(120) NOT NULL,
  `intro` varchar(500) NOT NULL,
  `music` char(120) NOT NULL,
  `end_pic` char(120) NOT NULL,
  `share_bg` char(120) NOT NULL,
  `share_button` char(120) NOT NULL,
  `add_time` char(11) NOT NULL,
  `is_open` enum('0','1') NOT NULL,
  `token` char(25) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `warn` char(50) NOT NULL,
  `share_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_live_company` */
 DROP TABLE IF EXISTS `vifnn_live_company`;/* MySQLReback Separation */ CREATE TABLE `vifnn_live_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `name` char(50) NOT NULL,
  `bg_pic` char(120) NOT NULL,
  `top_pic` char(120) NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `live_id` int(10) unsigned NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `show_map` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_live_content` */
 DROP TABLE IF EXISTS `vifnn_live_content`;/* MySQLReback Separation */ CREATE TABLE `vifnn_live_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  `bg_pic` char(120) NOT NULL,
  `movie_pic` char(120) NOT NULL,
  `movie_link` char(200) NOT NULL,
  `type` enum('1','2') NOT NULL,
  `sort` tinyint(4) unsigned NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `token` char(25) NOT NULL,
  `add_time` char(11) NOT NULL,
  `live_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_livingcircle` */
 DROP TABLE IF EXISTS `vifnn_livingcircle`;/* MySQLReback Separation */ CREATE TABLE `vifnn_livingcircle` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `wxtitle` varchar(100) NOT NULL,
  `wxpic` varchar(100) NOT NULL,
  `wxinfo` text,
  `fistpic` varchar(100) NOT NULL,
  `secondpic` varchar(100) DEFAULT NULL,
  `thirdpic` varchar(100) DEFAULT NULL,
  `fourpic` varchar(100) DEFAULT NULL,
  `fivepic` varchar(100) DEFAULT NULL,
  `sixpic` varchar(100) DEFAULT NULL,
  `navpic` varchar(100) NOT NULL,
  `mysellerpic` varchar(100) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_livingcircle` */
 INSERT INTO `vifnn_livingcircle` VALUES ('1','zrtmca1421056092','生活圈','微信生活圈','http://s.404.cn/tpl/static/livingcircle/images/wxnewspic.jpg','','http://s.404.cn/tpl/static/livingcircle/images/wxnewspic.jpg','','','','','','http://s.404.cn/tpl/static/livingcircle/images/bendidaohang.gif','http://s.404.cn/tpl/static/livingcircle/images/wodeshangjia.gif'),('2','ezrehr1458789061','生活圈','微信生活圈','/tpl/static/livingcircle/images/wxnewspic.jpg','','/tpl/static/livingcircle/images/wxnewspic.jpg','','','','','','/tpl/static/livingcircle/images/bendidaohang.gif','/tpl/static/livingcircle/images/wodeshangjia.gif'),('3','zbemii1462891866','生活圈','微信生活圈','/tpl/static/livingcircle/images/wxnewspic.jpg','','/tpl/static/livingcircle/images/wxnewspic.jpg','','','','','','/tpl/static/livingcircle/images/bendidaohang.gif','/tpl/static/livingcircle/images/wodeshangjia.gif'),('4','ywxgfh1465574691','生活圈','微信生活圈','/tpl/static/livingcircle/images/wxnewspic.jpg','','/tpl/static/livingcircle/images/wxnewspic.jpg','','','','','','/tpl/static/livingcircle/images/bendidaohang.gif','/tpl/static/livingcircle/images/wodeshangjia.gif');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_livingcircle_comment` */
 DROP TABLE IF EXISTS `vifnn_livingcircle_comment`;/* MySQLReback Separation */ CREATE TABLE `vifnn_livingcircle_comment` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `star` int(11) NOT NULL,
  `info` text NOT NULL,
  `addtime` int(11) NOT NULL,
  `sellerid` int(11) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_livingcircle_favorite` */
 DROP TABLE IF EXISTS `vifnn_livingcircle_favorite`;/* MySQLReback Separation */ CREATE TABLE `vifnn_livingcircle_favorite` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `sellerid` int(11) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_livingcircle_mysellercart` */
 DROP TABLE IF EXISTS `vifnn_livingcircle_mysellercart`;/* MySQLReback Separation */ CREATE TABLE `vifnn_livingcircle_mysellercart` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `sellerid` int(11) NOT NULL,
  `cid` int(11) DEFAULT NULL,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `goodsid` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_livingcircle_mysellergoods` */
 DROP TABLE IF EXISTS `vifnn_livingcircle_mysellergoods`;/* MySQLReback Separation */ CREATE TABLE `vifnn_livingcircle_mysellergoods` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `sellerid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `display` int(11) NOT NULL DEFAULT '1',
  `num` int(11) NOT NULL DEFAULT '1',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_livingcircle_mysellerorder` */
 DROP TABLE IF EXISTS `vifnn_livingcircle_mysellerorder`;/* MySQLReback Separation */ CREATE TABLE `vifnn_livingcircle_mysellerorder` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `sellerid` int(11) NOT NULL,
  `cid` int(11) DEFAULT NULL,
  `price` varchar(100) NOT NULL,
  `addtime` int(11) NOT NULL,
  `paytype` varchar(50) DEFAULT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `third_id` varchar(100) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `orderid` varchar(255) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_livingcircle_sellcircle` */
 DROP TABLE IF EXISTS `vifnn_livingcircle_sellcircle`;/* MySQLReback Separation */ CREATE TABLE `vifnn_livingcircle_sellcircle` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '1',
  `display` int(11) NOT NULL DEFAULT '1',
  `sellcircleid` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_livingcircle_seller` */
 DROP TABLE IF EXISTS `vifnn_livingcircle_seller`;/* MySQLReback Separation */ CREATE TABLE `vifnn_livingcircle_seller` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `cid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '1',
  `typeid` int(11) NOT NULL,
  `zitypeid` int(11) NOT NULL DEFAULT '0',
  `sellcircleid` int(11) NOT NULL,
  `zisellcircleid` int(11) NOT NULL DEFAULT '0',
  `fistpic` varchar(100) NOT NULL,
  `secondpic` varchar(100) DEFAULT NULL,
  `thirdpic` varchar(100) DEFAULT NULL,
  `fourpic` varchar(100) DEFAULT NULL,
  `fivepic` varchar(100) DEFAULT NULL,
  `sixpic` varchar(100) DEFAULT NULL,
  `qrcode` varchar(100) DEFAULT NULL,
  `weurl` varchar(512) DEFAULT NULL,
  `recommend` int(11) NOT NULL DEFAULT '0',
  `pv` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_livingcircle_type` */
 DROP TABLE IF EXISTS `vifnn_livingcircle_type`;/* MySQLReback Separation */ CREATE TABLE `vifnn_livingcircle_type` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL COMMENT '分类名称',
  `pic` varchar(100) DEFAULT NULL,
  `num` int(11) NOT NULL DEFAULT '1',
  `typeid` int(11) NOT NULL DEFAULT '0' COMMENT '父类id',
  `display` int(11) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `fistpic` varchar(100) NOT NULL,
  `secondpic` varchar(100) DEFAULT NULL,
  `thirdpic` varchar(100) DEFAULT NULL,
  `fourpic` varchar(100) DEFAULT NULL,
  `fivepic` varchar(100) DEFAULT NULL,
  `sixpic` varchar(100) DEFAULT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_livingcircle_user` */
 DROP TABLE IF EXISTS `vifnn_livingcircle_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_livingcircle_user` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `token` varchar(100) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_lottery` */
 DROP TABLE IF EXISTS `vifnn_lottery`;/* MySQLReback Separation */ CREATE TABLE `vifnn_lottery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `infotxt` text NOT NULL,
  `joinnum` int(11) NOT NULL COMMENT '参与人数',
  `click` int(11) NOT NULL,
  `token` varchar(30) NOT NULL,
  `keyword` varchar(10) NOT NULL,
  `starpicurl` varchar(100) NOT NULL COMMENT '填写活动开始图片网址',
  `title` varchar(60) NOT NULL COMMENT '活动名称',
  `txt` varchar(60) NOT NULL COMMENT '用户输入兑奖时候的显示信息',
  `sttxt` varchar(200) NOT NULL COMMENT '简介',
  `statdate` int(11) NOT NULL COMMENT '活动开始时间',
  `enddate` int(11) NOT NULL COMMENT '活动结束时间',
  `info` varchar(200) NOT NULL COMMENT '活动说明',
  `aginfo` varchar(200) NOT NULL COMMENT '重复抽奖回复',
  `endtite` varchar(60) NOT NULL COMMENT '活动结束公告主题',
  `endpicurl` varchar(100) NOT NULL,
  `endinfo` varchar(60) NOT NULL,
  `fist` varchar(50) NOT NULL COMMENT '一等奖奖品设置',
  `fistnums` int(4) NOT NULL COMMENT '一等奖奖品数量',
  `fistlucknums` int(1) NOT NULL COMMENT '一等奖中奖号码',
  `second` varchar(50) NOT NULL COMMENT '二等奖奖品设置',
  `type` tinyint(1) NOT NULL,
  `secondnums` int(4) NOT NULL,
  `secondlucknums` int(1) NOT NULL,
  `third` varchar(50) NOT NULL,
  `thirdnums` int(4) NOT NULL,
  `thirdlucknums` int(1) NOT NULL,
  `allpeople` int(11) NOT NULL,
  `canrqnums` int(2) NOT NULL COMMENT '个人限制抽奖次数',
  `parssword` int(15) NOT NULL,
  `renamesn` varchar(50) NOT NULL DEFAULT '',
  `renametel` varchar(60) NOT NULL,
  `displayjpnums` int(1) NOT NULL,
  `createtime` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `four` varchar(50) NOT NULL,
  `fournums` int(11) NOT NULL,
  `fourlucknums` int(11) NOT NULL,
  `five` varchar(50) NOT NULL,
  `fivenums` int(11) NOT NULL,
  `fivelucknums` int(11) NOT NULL,
  `six` varchar(50) NOT NULL COMMENT '六等奖',
  `sixnums` int(11) NOT NULL,
  `sixlucknums` int(11) NOT NULL,
  `zjpic` varchar(150) NOT NULL DEFAULT '',
  `daynums` mediumint(4) NOT NULL DEFAULT '0',
  `maxgetprizenum` mediumint(4) NOT NULL DEFAULT '1',
  `needreg` tinyint(1) NOT NULL DEFAULT '0',
  `guanzhu` int(11) DEFAULT NULL COMMENT '是否关注',
  `fistpic` varchar(100) DEFAULT NULL,
  `secondpic` varchar(100) DEFAULT NULL,
  `thirdpic` varchar(100) DEFAULT NULL,
  `fourpic` varchar(100) DEFAULT NULL,
  `fivepic` varchar(100) DEFAULT NULL,
  `sixpic` varchar(100) DEFAULT NULL,
  `bg` varchar(100) DEFAULT NULL,
  `bgtype` int(11) NOT NULL DEFAULT '0',
  `timespan` int(11) NOT NULL DEFAULT '0',
  `isdaylottery` int(11) NOT NULL DEFAULT '0',
  `cardid` int(11) NOT NULL DEFAULT '0',
  `shareupnum` int(11) NOT NULL DEFAULT '0',
  `numtype` int(11) NOT NULL DEFAULT '0',
  `sharebeforenum` int(11) NOT NULL DEFAULT '1',
  `other_source` varchar(30) DEFAULT NULL COMMENT '其他活动对接来源',
  `scene_time` int(10) unsigned NOT NULL DEFAULT '0',
  `scene_state` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `zjpic` (`zjpic`) USING BTREE,
  KEY `zjpic_2` (`zjpic`) USING BTREE,
  KEY `other_source` (`other_source`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_lottery_cheat` */
 DROP TABLE IF EXISTS `vifnn_lottery_cheat`;/* MySQLReback Separation */ CREATE TABLE `vifnn_lottery_cheat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `lid` int(10) NOT NULL DEFAULT '0',
  `wecha_id` varchar(60) NOT NULL DEFAULT '',
  `mp` varchar(11) NOT NULL DEFAULT '',
  `prizetype` mediumint(4) NOT NULL DEFAULT '0',
  `intro` varchar(60) NOT NULL DEFAULT '',
  `code` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `lid` (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_lottery_record` */
 DROP TABLE IF EXISTS `vifnn_lottery_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_lottery_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL,
  `usenums` int(10) NOT NULL DEFAULT '0' COMMENT '用户使用次数',
  `wecha_id` varchar(60) NOT NULL COMMENT '微信唯一识别码',
  `token` varchar(30) NOT NULL,
  `islottery` int(1) NOT NULL COMMENT '是否中奖',
  `wecha_name` varchar(60) NOT NULL COMMENT '微信号',
  `phone` varchar(15) NOT NULL,
  `sn` varchar(13) NOT NULL COMMENT '中奖后序列号',
  `time` int(11) NOT NULL,
  `prize` varchar(50) NOT NULL DEFAULT '' COMMENT '已中奖项',
  `sendstutas` int(11) NOT NULL DEFAULT '0',
  `sendtime` int(11) NOT NULL,
  `type` varchar(60) NOT NULL,
  `sharecount` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_mail` */
 DROP TABLE IF EXISTS `vifnn_mail`;/* MySQLReback Separation */ CREATE TABLE `vifnn_mail` (
  `email` text NOT NULL,
  `token` varchar(60) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `smtpserver` varchar(40) NOT NULL DEFAULT '',
  `port` varchar(40) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `receive` varchar(60) NOT NULL DEFAULT '',
  `shangcheng` tinyint(1) NOT NULL DEFAULT '0',
  `yuyue` tinyint(1) NOT NULL DEFAULT '0',
  `dingdan` tinyint(1) NOT NULL DEFAULT '0',
  `biaodan` tinyint(1) NOT NULL DEFAULT '0',
  `toupiao` tinyint(1) NOT NULL DEFAULT '0',
  `emailuser` text NOT NULL,
  `emailpassword` text NOT NULL,
  `emailstatus` text NOT NULL,
  PRIMARY KEY (`token`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_market` */
 DROP TABLE IF EXISTS `vifnn_market`;/* MySQLReback Separation */ CREATE TABLE `vifnn_market` (
  `market_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL,
  `title` char(30) NOT NULL,
  `keyword` char(20) NOT NULL,
  `tel` char(25) NOT NULL,
  `address` varchar(100) NOT NULL,
  `longitude` char(20) NOT NULL,
  `latitude` char(20) NOT NULL,
  `line` varchar(100) NOT NULL,
  `intro` text NOT NULL,
  `logo_pic` char(100) NOT NULL,
  `token` char(20) NOT NULL,
  `market_index_tpl` mediumint(9) NOT NULL,
  `tenant_index_tpl` mediumint(9) NOT NULL,
  `tenant_list_tpl` mediumint(9) NOT NULL,
  PRIMARY KEY (`market_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_market_area` */
 DROP TABLE IF EXISTS `vifnn_market_area`;/* MySQLReback Separation */ CREATE TABLE `vifnn_market_area` (
  `area_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area_name` char(35) NOT NULL,
  `manage` char(50) NOT NULL,
  `area_pic` char(100) NOT NULL,
  `area_intro` text NOT NULL,
  `is_use` enum('0','1') NOT NULL,
  `add_time` char(10) NOT NULL,
  `sort` tinyint(2) NOT NULL,
  `market_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_market_cate` */
 DROP TABLE IF EXISTS `vifnn_market_cate`;/* MySQLReback Separation */ CREATE TABLE `vifnn_market_cate` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` char(35) NOT NULL,
  `cate_pic` char(100) NOT NULL,
  `cate_intro` varchar(200) NOT NULL,
  `cate_pid` int(11) NOT NULL,
  `path_info` varchar(255) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `market_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_market_nav` */
 DROP TABLE IF EXISTS `vifnn_market_nav`;/* MySQLReback Separation */ CREATE TABLE `vifnn_market_nav` (
  `nav_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nav_name` char(35) NOT NULL,
  `nav_pic` varchar(200) NOT NULL,
  `nav_link` varchar(200) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `market_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  `is_system` enum('0','1') NOT NULL,
  PRIMARY KEY (`nav_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_market_park` */
 DROP TABLE IF EXISTS `vifnn_market_park`;/* MySQLReback Separation */ CREATE TABLE `vifnn_market_park` (
  `park_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `park_name` char(35) NOT NULL,
  `park_num` int(11) NOT NULL,
  `park_intro` text NOT NULL,
  `is_use` enum('0','1') NOT NULL,
  `add_time` char(10) NOT NULL,
  `market_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY (`park_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_market_slide` */
 DROP TABLE IF EXISTS `vifnn_market_slide`;/* MySQLReback Separation */ CREATE TABLE `vifnn_market_slide` (
  `slide_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slide_title` char(35) NOT NULL DEFAULT '',
  `slide_url` char(100) NOT NULL,
  `slide_link` char(200) NOT NULL,
  `market_id` int(11) NOT NULL,
  PRIMARY KEY (`slide_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_market_slide` */
 INSERT INTO `vifnn_market_slide` VALUES ('1','','./tpl/static/attachment/focus/default/2.jpg','','1'),('2','','./tpl/static/attachment/focus/default/3.jpg','','1'),('3','','./tpl/static/attachment/focus/default/4.jpg','','1');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_market_slidebg` */
 DROP TABLE IF EXISTS `vifnn_market_slidebg`;/* MySQLReback Separation */ CREATE TABLE `vifnn_market_slidebg` (
  `slidebg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slidebg_title` char(35) NOT NULL DEFAULT '',
  `slidebg_url` char(100) NOT NULL,
  `slidebg_link` char(80) NOT NULL,
  `market_id` int(11) NOT NULL,
  PRIMARY KEY (`slidebg_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_market_slidebg` */
 INSERT INTO `vifnn_market_slidebg` VALUES ('1','','./tpl/static/attachment/background/view/1.jpg','','1'),('2','','./tpl/static/attachment/background/view/2.jpg','','1'),('3','','','','2'),('4','','','','2'),('5','','','','3'),('6','','','','3'),('7','','','','4'),('8','','','','4');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_medical` */
 DROP TABLE IF EXISTS `vifnn_medical`;/* MySQLReback Separation */ CREATE TABLE `vifnn_medical` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `piccover` varchar(500) NOT NULL,
  `registrationtoppic` varchar(500) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `lat` varchar(30) NOT NULL,
  `lng` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sms` varchar(100) NOT NULL,
  `open_email` varchar(50) NOT NULL,
  `open_sms` varchar(50) NOT NULL,
  `checked` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_medical_addtype` */
 DROP TABLE IF EXISTS `vifnn_medical_addtype`;/* MySQLReback Separation */ CREATE TABLE `vifnn_medical_addtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `typename` varchar(255) NOT NULL DEFAULT '',
  `onduty` tinyint(1) NOT NULL,
  `token` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_medical_departments` */
 DROP TABLE IF EXISTS `vifnn_medical_departments`;/* MySQLReback Separation */ CREATE TABLE `vifnn_medical_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_medical_duty` */
 DROP TABLE IF EXISTS `vifnn_medical_duty`;/* MySQLReback Separation */ CREATE TABLE `vifnn_medical_duty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `docter` varchar(100) NOT NULL,
  `did` int(11) NOT NULL,
  `week1` tinyint(1) NOT NULL,
  `week2` tinyint(1) NOT NULL,
  `week3` tinyint(1) NOT NULL,
  `week4` tinyint(1) NOT NULL,
  `week5` tinyint(1) NOT NULL,
  `week6` tinyint(1) NOT NULL,
  `week7` tinyint(1) NOT NULL,
  `resernumber1` int(11) NOT NULL,
  `resernumber2` int(11) NOT NULL,
  `intro` text NOT NULL,
  `addtime` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_medical_level` */
 DROP TABLE IF EXISTS `vifnn_medical_level`;/* MySQLReback Separation */ CREATE TABLE `vifnn_medical_level` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(100) NOT NULL,
  `lprice` float(6,2) NOT NULL,
  `token` varchar(50) NOT NULL,
  PRIMARY KEY (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_medical_set` */
 DROP TABLE IF EXISTS `vifnn_medical_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_medical_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `token` varchar(50) NOT NULL DEFAULT '',
  `head_url` varchar(200) NOT NULL DEFAULT '',
  `album_id` int(11) DEFAULT NULL,
  `menu1` varchar(20) NOT NULL DEFAULT '',
  `menu2` varchar(20) NOT NULL DEFAULT '',
  `menu3` varchar(20) NOT NULL DEFAULT '',
  `menu4` varchar(20) NOT NULL DEFAULT '',
  `menu5` varchar(20) NOT NULL DEFAULT '',
  `menu6` varchar(20) NOT NULL DEFAULT '',
  `menu7` varchar(20) NOT NULL DEFAULT '',
  `menu8` varchar(20) NOT NULL DEFAULT '',
  `menu9` varchar(50) DEFAULT '',
  `menu10` varchar(50) DEFAULT '',
  `picurl1` varchar(200) DEFAULT '',
  `picurl2` varchar(200) DEFAULT '',
  `picurl3` varchar(200) DEFAULT '',
  `picurl4` varchar(200) DEFAULT '',
  `picurl5` varchar(200) DEFAULT '',
  `picurl6` varchar(200) DEFAULT '',
  `picurl7` varchar(200) DEFAULT '',
  `picurl8` varchar(200) DEFAULT '',
  `picurl9` varchar(200) DEFAULT '',
  `picurl10` varchar(200) DEFAULT '',
  `hotfocus_id` int(11) NOT NULL,
  `experts_id` int(11) NOT NULL,
  `ceem_id` int(11) NOT NULL,
  `Rcase_id` int(11) NOT NULL,
  `technology_id` int(11) NOT NULL,
  `drug_id` int(11) NOT NULL,
  `evants_id` int(11) NOT NULL,
  `video` text NOT NULL,
  `symptoms_id` int(11) NOT NULL,
  `info` char(200) NOT NULL DEFAULT '',
  `path` varchar(3000) DEFAULT '0',
  `tpid` int(11) DEFAULT NULL,
  `conttpid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_medical_setup` */
 DROP TABLE IF EXISTS `vifnn_medical_setup`;/* MySQLReback Separation */ CREATE TABLE `vifnn_medical_setup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `age` varchar(20) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `scheduled_date` datetime NOT NULL,
  `address` varchar(500) NOT NULL,
  `departments` varchar(200) NOT NULL,
  `expert` varchar(200) NOT NULL,
  `disease` varchar(500) NOT NULL,
  `process` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_medical_setup_control` */
 DROP TABLE IF EXISTS `vifnn_medical_setup_control`;/* MySQLReback Separation */ CREATE TABLE `vifnn_medical_setup_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `age` varchar(20) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `scheduled_date` char(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `departments` varchar(200) NOT NULL,
  `expert` varchar(200) NOT NULL,
  `disease` varchar(500) NOT NULL,
  `Process` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_medical_user` */
 DROP TABLE IF EXISTS `vifnn_medical_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_medical_user` (
  `iid` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL DEFAULT '',
  `wecha_id` varchar(50) NOT NULL DEFAULT '',
  `rid` int(11) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `truename` varchar(50) NOT NULL DEFAULT '',
  `utel` char(13) NOT NULL,
  `dateline` varchar(50) NOT NULL,
  `sex` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `txt33` varchar(50) NOT NULL DEFAULT '',
  `txt44` varchar(50) NOT NULL DEFAULT '',
  `txt55` varchar(50) NOT NULL DEFAULT '',
  `yyks` varchar(50) NOT NULL DEFAULT '',
  `yyzj` varchar(50) NOT NULL DEFAULT '',
  `yybz` varchar(50) NOT NULL DEFAULT '',
  `yy4` varchar(50) NOT NULL DEFAULT '',
  `yy5` varchar(50) NOT NULL DEFAULT '',
  `uinfo` varchar(50) NOT NULL DEFAULT '',
  `kfinfo` varchar(100) NOT NULL DEFAULT '',
  `remate` int(10) NOT NULL DEFAULT '0',
  `booktime` int(11) DEFAULT NULL,
  `paid` tinyint(4) DEFAULT '0',
  `orderid` bigint(20) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `orderName` varchar(200) NOT NULL DEFAULT '',
  `txt3name` varchar(50) NOT NULL DEFAULT '',
  `txt4name` varchar(50) NOT NULL DEFAULT '',
  `txt5name` varchar(50) NOT NULL DEFAULT '',
  `select4name` varchar(50) NOT NULL DEFAULT '',
  `select5name` varchar(50) NOT NULL DEFAULT '',
  `reservation_time` int(11) NOT NULL,
  `yyks_name` varchar(100) NOT NULL,
  `yyzj_name` varchar(100) NOT NULL,
  PRIMARY KEY (`iid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member` */
 DROP TABLE IF EXISTS `vifnn_member`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `isopen` int(1) NOT NULL,
  `homepic` varchar(100) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_business` */
 DROP TABLE IF EXISTS `vifnn_member_business`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `crate_time` int(11) NOT NULL DEFAULT '0',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `catid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `font_img` varchar(100) NOT NULL DEFAULT '',
  `font_summary` varchar(300) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `displayorder` smallint(6) NOT NULL DEFAULT '0',
  `tel` varchar(20) NOT NULL DEFAULT '',
  `addr` varchar(300) NOT NULL DEFAULT '',
  `style` varchar(100) NOT NULL DEFAULT '',
  `flag_c` tinyint(1) NOT NULL DEFAULT '0',
  `flag_x` tinyint(2) NOT NULL DEFAULT '0',
  `flag_z` tinyint(2) NOT NULL DEFAULT '0',
  `flag_q` tinyint(2) NOT NULL DEFAULT '0',
  `map` varchar(200) NOT NULL DEFAULT '',
  `schedule` varchar(3000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_business_ad` */
 DROP TABLE IF EXISTS `vifnn_member_business_ad`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_business_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL DEFAULT '',
  `bid` int(60) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `img` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`bid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_business_case` */
 DROP TABLE IF EXISTS `vifnn_member_business_case`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_business_case` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL DEFAULT '',
  `bid` int(11) NOT NULL DEFAULT '0',
  `summary` varchar(3000) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `addr` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_business_fav` */
 DROP TABLE IF EXISTS `vifnn_member_business_fav`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_business_fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL DEFAULT '',
  `bid` int(11) NOT NULL DEFAULT '0',
  `summary` varchar(3000) NOT NULL DEFAULT '',
  `starttime` int(11) NOT NULL DEFAULT '0',
  `endtime` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_business_product` */
 DROP TABLE IF EXISTS `vifnn_member_business_product`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_business_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL DEFAULT '',
  `displayorder` smallint(6) NOT NULL DEFAULT '0',
  `price` varchar(12) NOT NULL DEFAULT '',
  `crate_time` int(11) NOT NULL DEFAULT '0',
  `bid` int(11) NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_contact` */
 DROP TABLE IF EXISTS `vifnn_member_card_contact`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `cname` varchar(30) NOT NULL,
  `tel` varchar(12) NOT NULL,
  `sort` tinyint(1) NOT NULL,
  `info` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_coupon` */
 DROP TABLE IF EXISTS `vifnn_member_card_coupon`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pic` char(150) NOT NULL,
  `cardid` int(10) NOT NULL DEFAULT '0',
  `token` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `group` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `attr` enum('0','1') NOT NULL,
  `price` int(11) NOT NULL,
  `people` int(3) NOT NULL,
  `statdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `info` text NOT NULL,
  `usetime` int(10) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `card_id` char(50) NOT NULL,
  `is_weixin` tinyint(4) NOT NULL,
  `color` char(10) NOT NULL,
  `is_check` tinyint(4) NOT NULL,
  `least_cost` decimal(10,2) NOT NULL,
  `reduce_cost` decimal(10,2) NOT NULL,
  `gift_name` char(30) NOT NULL,
  `integral` int(11) NOT NULL,
  `brand_name` char(20) NOT NULL,
  `logourl` char(150) NOT NULL,
  `is_delete` tinyint(4) NOT NULL,
  `is_huodong` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_coupon_record` */
 DROP TABLE IF EXISTS `vifnn_member_card_coupon_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_coupon_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `wecha_id` char(40) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `coupon_type` enum('1','2','3') NOT NULL,
  `is_use` enum('0','1') NOT NULL,
  `cardid` int(11) NOT NULL,
  `add_time` char(11) NOT NULL,
  `use_time` char(11) NOT NULL,
  `coupon_attr` text NOT NULL,
  `card_id` char(45) NOT NULL,
  `cancel_code` char(15) NOT NULL,
  `company_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `whereid` int(11) NOT NULL DEFAULT '0',
  `iswhere` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_create` */
 DROP TABLE IF EXISTS `vifnn_member_card_create`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_create` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cardid` int(10) NOT NULL DEFAULT '0',
  `token` varchar(60) NOT NULL,
  `number` varchar(20) NOT NULL,
  `wecha_id` varchar(60) NOT NULL,
  `is_bind` tinyint(4) NOT NULL,
  `old_number` char(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_custom` */
 DROP TABLE IF EXISTS `vifnn_member_card_custom`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_custom` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(40) NOT NULL,
  `wechaname` tinyint(4) NOT NULL DEFAULT '1',
  `tel` tinyint(4) NOT NULL DEFAULT '1',
  `truename` tinyint(4) NOT NULL DEFAULT '0',
  `qq` tinyint(4) NOT NULL DEFAULT '0',
  `paypass` tinyint(4) NOT NULL DEFAULT '1',
  `portrait` tinyint(4) NOT NULL DEFAULT '0',
  `sex` tinyint(4) NOT NULL DEFAULT '0',
  `bornyear` tinyint(4) NOT NULL DEFAULT '0',
  `bornmonth` tinyint(4) NOT NULL DEFAULT '0',
  `bornday` tinyint(4) NOT NULL DEFAULT '0',
  `is_wechaname` tinyint(1) NOT NULL DEFAULT '1',
  `is_tel` tinyint(1) NOT NULL DEFAULT '1',
  `is_truename` tinyint(1) NOT NULL DEFAULT '0',
  `is_qq` tinyint(1) NOT NULL DEFAULT '0',
  `is_paypass` tinyint(1) NOT NULL DEFAULT '1',
  `is_portrait` tinyint(1) NOT NULL DEFAULT '1',
  `is_sex` tinyint(1) NOT NULL DEFAULT '0',
  `is_bornyear` tinyint(1) NOT NULL DEFAULT '0',
  `is_bornmonth` tinyint(1) NOT NULL DEFAULT '0',
  `is_bornday` tinyint(1) NOT NULL DEFAULT '0',
  `carnumber` tinyint(4) NOT NULL,
  `is_carnumber` tinyint(1) NOT NULL,
  `cart` tinyint(4) NOT NULL DEFAULT '0',
  `is_cart` tinyint(1) NOT NULL DEFAULT '0',
  `address` tinyint(1) NOT NULL DEFAULT '0',
  `is_address` tinyint(1) NOT NULL DEFAULT '0',
  `origin` tinyint(1) NOT NULL DEFAULT '0',
  `is_origin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_custom_field` */
 DROP TABLE IF EXISTS `vifnn_member_card_custom_field`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_custom_field` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` char(15) NOT NULL,
  `field_option` varchar(500) NOT NULL,
  `field_type` char(10) NOT NULL,
  `item_name` char(15) NOT NULL,
  `field_match` char(80) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `is_empty` enum('0','1') NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `err_info` char(35) NOT NULL,
  `set_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY (`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_donate` */
 DROP TABLE IF EXISTS `vifnn_member_card_donate`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_donate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(45) NOT NULL,
  `cardid` int(11) NOT NULL,
  `min_price` decimal(10,2) NOT NULL,
  `max_price` decimal(10,2) NOT NULL,
  `donate_price` decimal(10,2) NOT NULL,
  `is_open` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_exchange` */
 DROP TABLE IF EXISTS `vifnn_member_card_exchange`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_exchange` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cardid` mediumint(8) NOT NULL DEFAULT '0',
  `token` varchar(60) NOT NULL,
  `everyday` int(11) NOT NULL,
  `continuation` tinyint(4) NOT NULL,
  `reward` int(11) NOT NULL,
  `cardinfo` text NOT NULL,
  `cardinfo2` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `discount` int(4) NOT NULL COMMENT '充值折扣',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_focus` */
 DROP TABLE IF EXISTS `vifnn_member_card_focus`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_focus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `info` varchar(300) DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `token` char(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_gifts` */
 DROP TABLE IF EXISTS `vifnn_member_card_gifts`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_gifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  `type` enum('1','2') NOT NULL,
  `item_value` int(11) NOT NULL,
  `item_attr` char(50) NOT NULL,
  `start` char(11) NOT NULL,
  `end` char(11) NOT NULL,
  `token` char(25) NOT NULL,
  `cardid` int(11) NOT NULL,
  `is_open` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_info` */
 DROP TABLE IF EXISTS `vifnn_member_card_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `info` varchar(200) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `description` varchar(12) NOT NULL,
  `class` tinyint(1) NOT NULL,
  `password` varchar(11) NOT NULL,
  `crate_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_integral` */
 DROP TABLE IF EXISTS `vifnn_member_card_integral`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_integral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cardid` int(10) NOT NULL DEFAULT '0',
  `token` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `integral` int(8) NOT NULL,
  `statdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `info` text NOT NULL,
  `usetime` int(10) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `pic` char(150) NOT NULL,
  `people` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_jfdhhb` */
 DROP TABLE IF EXISTS `vifnn_member_card_jfdhhb`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_jfdhhb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `cardid` int(11) NOT NULL DEFAULT '0',
  `integral` int(11) NOT NULL DEFAULT '0',
  `hongbao` int(11) NOT NULL DEFAULT '0',
  `display` int(2) unsigned NOT NULL DEFAULT '1',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `starttime` int(11) NOT NULL DEFAULT '0',
  `endtime` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `send_name` varchar(200) NOT NULL,
  `wishing` varchar(200) NOT NULL,
  `act_name` varchar(200) NOT NULL,
  `remark` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `hongbao` (`hongbao`) USING BTREE,
  KEY `integral` (`integral`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_jfdhhb_record` */
 DROP TABLE IF EXISTS `vifnn_member_card_jfdhhb_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_jfdhhb_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `cardid` int(11) NOT NULL DEFAULT '0',
  `jid` int(11) NOT NULL DEFAULT '0',
  `integral` int(11) NOT NULL DEFAULT '0',
  `hongbao` int(11) NOT NULL DEFAULT '0',
  `wechaname` varchar(100) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `wechaname` (`wechaname`) USING BTREE,
  KEY `integral` (`integral`) USING BTREE,
  KEY `hongbao` (`hongbao`) USING BTREE,
  KEY `tel` (`tel`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_myintegral` */
 DROP TABLE IF EXISTS `vifnn_member_card_myintegral`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_myintegral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cardid` int(10) NOT NULL DEFAULT '0',
  `token` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `integral` int(8) NOT NULL,
  `statdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `info` text NOT NULL,
  `usetime` int(10) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_notice` */
 DROP TABLE IF EXISTS `vifnn_member_card_notice`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_notice` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cardid` int(10) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `endtime` int(10) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_pay_record` */
 DROP TABLE IF EXISTS `vifnn_member_card_pay_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_pay_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` char(30) NOT NULL,
  `ordername` varchar(1000) NOT NULL,
  `transactionid` varchar(100) DEFAULT NULL,
  `paytype` char(30) DEFAULT NULL,
  `createtime` int(11) NOT NULL,
  `paytime` int(11) DEFAULT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT '0',
  `price` double(10,2) NOT NULL DEFAULT '0.00',
  `token` char(50) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `module` varchar(30) NOT NULL DEFAULT 'Card',
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `company_id` int(11) NOT NULL,
  `cardid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_rule` */
 DROP TABLE IF EXISTS `vifnn_member_card_rule`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_rule` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `percent_rule` varchar(100) NOT NULL COMMENT '百分比返还',
  `fixed_rule` text NOT NULL COMMENT '固定返还',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_set` */
 DROP TABLE IF EXISTS `vifnn_member_card_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `cardname` varchar(60) NOT NULL,
  `showname` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '显示卡名称',
  `miniscore` int(10) NOT NULL DEFAULT '0',
  `logo` varchar(200) NOT NULL,
  `showlogo` int(1) NOT NULL DEFAULT '1' COMMENT '显示logo',
  `bg` varchar(100) NOT NULL,
  `diybg` varchar(200) NOT NULL,
  `info` text NOT NULL,
  `msg` varchar(100) NOT NULL,
  `numbercolor` varchar(10) NOT NULL,
  `vipnamecolor` varchar(10) NOT NULL,
  `Lastmsg` varchar(100) NOT NULL,
  `vip` varchar(100) NOT NULL,
  `qiandao` varchar(100) NOT NULL,
  `shopping` varchar(100) NOT NULL,
  `memberinfo` varchar(100) NOT NULL,
  `membermsg` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL,
  `recharge` varchar(100) NOT NULL DEFAULT '/tpl/User/default/common/images/cart_info/recharge.jpg',
  `payrecord` varchar(100) NOT NULL DEFAULT '/tpl/User/default/common/images/cart_info/payrecord.jpg',
  `company_pwd` char(32) NOT NULL,
  `is_check` enum('0','1') NOT NULL,
  `donate_intro` text NOT NULL,
  `is_donate` tinyint(4) NOT NULL,
  `sub_give` tinyint(1) unsigned NOT NULL COMMENT '关注是赠送（0：不赠送，1：赠送）',
  `is_jfdhhb` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `miniscore` (`miniscore`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_sign` */
 DROP TABLE IF EXISTS `vifnn_member_card_sign`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_sign` (
  `id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  `sign_time` int(11) NOT NULL,
  `is_sign` int(11) NOT NULL,
  `score_type` int(11) NOT NULL,
  `expense` int(11) NOT NULL,
  `sell_expense` int(11) NOT NULL,
  `score_name` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_use_record` */
 DROP TABLE IF EXISTS `vifnn_member_card_use_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_use_record` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `itemid` int(10) NOT NULL DEFAULT '0',
  `token` varchar(30) NOT NULL DEFAULT '',
  `wecha_id` varchar(50) NOT NULL DEFAULT '',
  `staffid` int(10) NOT NULL DEFAULT '0',
  `cat` smallint(4) NOT NULL DEFAULT '0',
  `expense` varchar(50) NOT NULL DEFAULT '0',
  `score` mediumint(4) NOT NULL DEFAULT '0',
  `usecount` mediumint(4) NOT NULL DEFAULT '1',
  `time` int(10) NOT NULL DEFAULT '0',
  `notes` varchar(300) NOT NULL,
  `company_id` int(11) NOT NULL,
  `cardid` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `orderid` char(35) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `itemid` (`itemid`,`cat`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_card_vip` */
 DROP TABLE IF EXISTS `vifnn_member_card_vip`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_card_vip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cardid` int(10) NOT NULL DEFAULT '0',
  `token` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `group` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `statdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `info` text NOT NULL,
  `usetime` int(10) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_member_wei_category` */
 DROP TABLE IF EXISTS `vifnn_member_wei_category`;/* MySQLReback Separation */ CREATE TABLE `vifnn_member_wei_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL DEFAULT '',
  `title` varchar(60) NOT NULL DEFAULT '',
  `displayorder` smallint(10) NOT NULL DEFAULT '0',
  `summary` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_memberflash` */
 DROP TABLE IF EXISTS `vifnn_memberflash`;/* MySQLReback Separation */ CREATE TABLE `vifnn_memberflash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `img` char(255) NOT NULL,
  `url` char(255) NOT NULL,
  `info` varchar(90) NOT NULL,
  `tip` smallint(11) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tip` (`tip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_microsoft_withdraw` */
 DROP TABLE IF EXISTS `vifnn_microsoft_withdraw`;/* MySQLReback Separation */ CREATE TABLE `vifnn_microsoft_withdraw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vifnn_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `opening_bank` varchar(100) NOT NULL DEFAULT '' COMMENT '开户行',
  `bank_card` varchar(100) NOT NULL DEFAULT '' COMMENT '卡号',
  `bank_card_user` varchar(100) NOT NULL DEFAULT '' COMMENT '开户名',
  `withdrawal_type` tinyint(1) NOT NULL,
  `add_time` int(11) NOT NULL,
  `status` char(30) NOT NULL DEFAULT '',
  `amount` float(6,2) NOT NULL,
  `complate_time` int(11) NOT NULL,
  `bank` char(30) NOT NULL,
  `tel` char(30) NOT NULL,
  `nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '昵称',
  `store` varchar(100) NOT NULL DEFAULT '',
  `user` varchar(100) NOT NULL DEFAULT '',
  `token` char(30) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_micrstore` */
 DROP TABLE IF EXISTS `vifnn_micrstore`;/* MySQLReback Separation */ CREATE TABLE `vifnn_micrstore` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paid` tinyint(4) NOT NULL,
  `third_id` varchar(50) DEFAULT NULL,
  `orderid` varchar(50) NOT NULL,
  `price` float unsigned NOT NULL,
  `token` char(50) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `trade_no` char(50) DEFAULT NULL,
  `paytype` char(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_micrstore_reply` */
 DROP TABLE IF EXISTS `vifnn_micrstore_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_micrstore_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` char(20) NOT NULL,
  `description` varchar(300) NOT NULL,
  `title` varchar(300) NOT NULL,
  `img` varchar(300) NOT NULL,
  `token` varchar(100) NOT NULL,
  `sid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_micrstore_reply` */
 INSERT INTO `vifnn_micrstore_reply` VALUES ('1','微店','微店正式上线了','微店','http://www.test.com/tpl/static/attachment/icon/canyin/canyin_red/10.png','zrtmca1421056092',''),('2','微店','微店正式上线了','微店','','ezrehr1458789061','759'),('3','微店','微店正式上线了','微店','','zbemii1462891866','4');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_mobilesite` */
 DROP TABLE IF EXISTS `vifnn_mobilesite`;/* MySQLReback Separation */ CREATE TABLE `vifnn_mobilesite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `owndomain` varchar(150) NOT NULL COMMENT '自己站点域名',
  `admindomain` varchar(150) NOT NULL COMMENT '后台域名',
  `tjscript` text NOT NULL COMMENT '第三方js脚本字符创',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_OWNDOMAIN` (`owndomain`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_moopha_article` */
 DROP TABLE IF EXISTS `vifnn_moopha_article`;/* MySQLReback Separation */ CREATE TABLE `vifnn_moopha_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `channel_id` int(10) NOT NULL,
  `token` varchar(50) NOT NULL,
  `site` int(4) NOT NULL DEFAULT '1',
  `title` varchar(200) NOT NULL,
  `titles` varchar(400) NOT NULL DEFAULT '',
  `subtitle` varchar(200) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `externallink` tinyint(1) NOT NULL DEFAULT '0',
  `thumb` varchar(100) DEFAULT NULL,
  `content` longtext,
  `intro` varchar(2000) NOT NULL,
  `author` varchar(20) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `keywords` varchar(300) DEFAULT NULL,
  `uid` varchar(10) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL,
  `last_update` int(10) NOT NULL,
  `viewcount` int(10) NOT NULL DEFAULT '0',
  `template` varchar(50) DEFAULT NULL,
  `pagecount` tinyint(2) NOT NULL DEFAULT '1',
  `disagree` int(10) NOT NULL DEFAULT '0',
  `cancomment` tinyint(1) NOT NULL DEFAULT '1',
  `commentcount` int(10) NOT NULL DEFAULT '0',
  `agree` int(10) NOT NULL DEFAULT '0',
  `taxis` int(10) NOT NULL DEFAULT '0',
  `lastcreate` int(10) NOT NULL DEFAULT '1400000000',
  `sourcetype` smallint(2) NOT NULL DEFAULT '0',
  `ex` tinyint(1) DEFAULT '0',
  `pubed` tinyint(1) NOT NULL DEFAULT '1',
  `geoid` mediumint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `channel_id_2` (`channel_id`,`thumb`),
  KEY `time` (`time`),
  KEY `taxis` (`taxis`),
  KEY `ex` (`ex`),
  KEY `geoid` (`geoid`),
  KEY `uid` (`uid`),
  KEY `keywords` (`keywords`),
  KEY `sourcetype` (`sourcetype`),
  KEY `pubed` (`pubed`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_moopha_attachement` */
 DROP TABLE IF EXISTS `vifnn_moopha_attachement`;/* MySQLReback Separation */ CREATE TABLE `vifnn_moopha_attachement` (
  `url` varchar(150) NOT NULL DEFAULT '',
  `pubid` smallint(3) NOT NULL DEFAULT '1',
  `filetype` varchar(10) NOT NULL DEFAULT 'picture',
  `cat` varchar(20) NOT NULL DEFAULT '',
  `catid` int(10) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT '0',
  KEY `cat` (`cat`,`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_moopha_channel` */
 DROP TABLE IF EXISTS `vifnn_moopha_channel`;/* MySQLReback Separation */ CREATE TABLE `vifnn_moopha_channel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `shortname` varchar(50) NOT NULL DEFAULT '',
  `isnav` tinyint(1) NOT NULL DEFAULT '1',
  `channeltype` tinyint(1) NOT NULL DEFAULT '1',
  `cindex` varchar(50) NOT NULL,
  `token` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(200) NOT NULL,
  `externallink` tinyint(1) NOT NULL DEFAULT '0',
  `des` mediumtext NOT NULL,
  `thumb` varchar(100) DEFAULT NULL,
  `metatitle` varchar(100) DEFAULT NULL,
  `metakeyword` varchar(100) DEFAULT NULL,
  `metades` varchar(200) DEFAULT NULL,
  `thumbwidth` int(4) NOT NULL,
  `thumbheight` int(4) NOT NULL,
  `thumb2width` mediumint(4) NOT NULL DEFAULT '0',
  `thumb2height` mediumint(4) NOT NULL DEFAULT '0',
  `thumb3width` mediumint(4) NOT NULL DEFAULT '0',
  `thumb3height` mediumint(4) NOT NULL DEFAULT '0',
  `thumb4width` mediumint(4) NOT NULL DEFAULT '0',
  `thumb4height` mediumint(4) NOT NULL DEFAULT '0',
  `parentid` int(10) NOT NULL DEFAULT '0',
  `channeltemplate` int(10) DEFAULT '1',
  `contenttemplate` int(10) DEFAULT '1',
  `autotype` varchar(10) NOT NULL DEFAULT '',
  `ex` tinyint(1) NOT NULL DEFAULT '0',
  `iscity` tinyint(1) NOT NULL DEFAULT '0',
  `site` int(4) NOT NULL DEFAULT '0',
  `taxis` int(10) NOT NULL DEFAULT '0',
  `lastcreate` int(10) NOT NULL DEFAULT '1400000000',
  `pagesize` smallint(3) NOT NULL DEFAULT '30',
  `specialid` mediumint(4) NOT NULL DEFAULT '0',
  `homepicturechannel` tinyint(1) NOT NULL DEFAULT '0',
  `hometextchannel` tinyint(1) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`),
  KEY `site` (`site`),
  KEY `taxis` (`taxis`),
  KEY `time` (`time`),
  KEY `specialid` (`specialid`),
  KEY `token` (`token`),
  KEY `isnav` (`isnav`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_moopha_channel_contentattribute` */
 DROP TABLE IF EXISTS `vifnn_moopha_channel_contentattribute`;/* MySQLReback Separation */ CREATE TABLE `vifnn_moopha_channel_contentattribute` (
  `channelid` int(4) NOT NULL,
  `attributeid` int(4) NOT NULL,
  `taxis` int(4) NOT NULL DEFAULT '0',
  KEY `channelid` (`channelid`),
  KEY `taxis` (`taxis`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_moopha_keywords` */
 DROP TABLE IF EXISTS `vifnn_moopha_keywords`;/* MySQLReback Separation */ CREATE TABLE `vifnn_moopha_keywords` (
  `id` mediumint(4) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(60) NOT NULL DEFAULT '',
  `link` varchar(150) NOT NULL DEFAULT '',
  `title` varchar(150) NOT NULL DEFAULT '',
  `target` varchar(15) NOT NULL DEFAULT '_blank',
  `time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_moopha_picture` */
 DROP TABLE IF EXISTS `vifnn_moopha_picture`;/* MySQLReback Separation */ CREATE TABLE `vifnn_moopha_picture` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `contentid` int(10) NOT NULL,
  `url` varchar(100) NOT NULL,
  `intro` text NOT NULL,
  `taxis` mediumint(4) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contentid` (`contentid`),
  KEY `taxis` (`taxis`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_moopha_site` */
 DROP TABLE IF EXISTS `vifnn_moopha_site`;/* MySQLReback Separation */ CREATE TABLE `vifnn_moopha_site` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `intro` varchar(600) NOT NULL DEFAULT '',
  `picurl` varchar(120) NOT NULL DEFAULT '',
  `token` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(40) NOT NULL DEFAULT '',
  `logourl` varchar(120) NOT NULL DEFAULT '',
  `siteindex` varchar(50) NOT NULL,
  `taxis` int(4) NOT NULL,
  `main` int(1) NOT NULL,
  `abspath` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(100) DEFAULT NULL,
  `statisticcode` varchar(600) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `siteindex` (`siteindex`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_moopha_template` */
 DROP TABLE IF EXISTS `vifnn_moopha_template`;/* MySQLReback Separation */ CREATE TABLE `vifnn_moopha_template` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `path` varchar(200) NOT NULL,
  `generate_path` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1-index,2-channel,3-template,4-singlepage',
  `isdefault` tinyint(1) NOT NULL DEFAULT '0',
  `createhtml` tinyint(1) NOT NULL DEFAULT '1',
  `site` int(4) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `isdefault` (`isdefault`),
  KEY `site` (`site`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_moopha_user` */
 DROP TABLE IF EXISTS `vifnn_moopha_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_moopha_user` (
  `uid` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `realname` varchar(50) DEFAULT NULL,
  `email` varchar(60) NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(6) NOT NULL,
  `mp` char(11) DEFAULT NULL,
  `qq` varchar(15) DEFAULT '',
  `isadmin` tinyint(1) NOT NULL DEFAULT '0',
  `regip` varchar(30) DEFAULT NULL,
  `regtime` int(10) DEFAULT NULL,
  `lastloginip` varchar(30) DEFAULT NULL,
  `lastlogintime` int(10) DEFAULT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_msg` */
 DROP TABLE IF EXISTS `vifnn_msg`;/* MySQLReback Separation */ CREATE TABLE `vifnn_msg` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `tel` varchar(12) NOT NULL,
  `qq` int(11) NOT NULL,
  `domain` varchar(60) NOT NULL,
  `time` int(11) NOT NULL,
  `price` int(5) NOT NULL,
  `info` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_musiccar` */
 DROP TABLE IF EXISTS `vifnn_musiccar`;/* MySQLReback Separation */ CREATE TABLE `vifnn_musiccar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `jianjie` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `gzlj` varchar(255) DEFAULT NULL,
  `open` int(1) DEFAULT NULL,
  `banquan` varchar(255) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `lj` varchar(255) NOT NULL,
  `yd` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_musiccar` */
 INSERT INTO `vifnn_musiccar` VALUES ('21','zrtmca1421056092','','','','','','','','','','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_my_answer` */
 DROP TABLE IF EXISTS `vifnn_my_answer`;/* MySQLReback Separation */ CREATE TABLE `vifnn_my_answer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) DEFAULT NULL,
  `token` varchar(30) DEFAULT NULL,
  `question` varchar(255) DEFAULT NULL,
  `answer` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wid_index` (`token`),
  KEY `question_index` (`question`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_nearby_user` */
 DROP TABLE IF EXISTS `vifnn_nearby_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_nearby_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(30) NOT NULL,
  `uid` varchar(32) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_news` */
 DROP TABLE IF EXISTS `vifnn_news`;/* MySQLReback Separation */ CREATE TABLE `vifnn_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wxname` varchar(200) NOT NULL,
  `token` char(150) NOT NULL,
  `class1` int(11) NOT NULL,
  `class2` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `agentid` int(11) NOT NULL DEFAULT '0',
  `agentind` int(11) NOT NULL DEFAULT '0',
  `class3` int(11) NOT NULL,
  `name1` varchar(200) NOT NULL,
  `name2` varchar(200) NOT NULL,
  `name3` varchar(200) NOT NULL,
  `title` varchar(90) NOT NULL,
  `key` varchar(120) NOT NULL,
  `description` char(60) NOT NULL,
  `category` int(1) NOT NULL COMMENT '1:动态，2：公告，3：其他',
  `content` text NOT NULL,
  `imgs` char(120) NOT NULL,
  `showtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_news` */
 INSERT INTO `vifnn_news` VALUES ('1','vifnn','zrtmca1421056092','0','0','0','0','0','0','','','','','','','0','','','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_node` */
 DROP TABLE IF EXISTS `vifnn_node`;/* MySQLReback Separation */ CREATE TABLE `vifnn_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '节点名称',
  `title` varchar(50) NOT NULL COMMENT '菜单名称',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否激活 1：是 2：否',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `pid` smallint(6) unsigned NOT NULL COMMENT '父ID',
  `level` tinyint(1) unsigned NOT NULL COMMENT '节点等级',
  `data` varchar(255) DEFAULT NULL COMMENT '附加参数',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序权重',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '菜单显示类型 0:不显示 1:导航菜单 2:左侧菜单',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=688 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_node` */
 INSERT INTO `vifnn_node` VALUES ('1','cms','根节点','1','','0','1','','0','0'),('2','Site','站点管理','1','','1','0','','0','1'),('3','User','客户管理','1','','1','0','','0','1'),('4','extent','扩展管理','1','','1','0','','10','1'),('5','article','内容管理','1','','1','0','','0','1'),('6','Site','站点设置','1','','2','2','','0','2'),('7','index','基本信息设置','1','','6','3','','0','2'),('8','safe','安全设置','1','','6','3','','0','2'),('9','email','邮箱设置','1','','6','3','','0','2'),('10','upfile','附件设置','1','','6','3','','0','2'),('11','Node','节点管理','1','','2','2','','0','2'),('12','add','添加节点','1','','11','3','','0','2'),('13','index','节点列表','1','','11','3','','0','0'),('14','insert','写入','1','0','11','3','','0','0'),('15','edit','编辑节点','1','0','11','3','','0','0'),('16','update','更新节点','1','0','11','3','','0','0'),('17','del','删除节点','1','0','11','3','','0','0'),('18','User','站长管理','1','0','3','2','','0','2'),('19','add','添加站长','1','0','18','3','','0','2'),('20','index','站长列表','1','0','18','3','','0','0'),('21','edit','编辑站长','1','0','18','3','','0','0'),('22','insert','写入数据库','1','0','18','3','','0','0'),('23','update','更新用户','1','0','18','3','','0','0'),('24','del','删除用户','1','0','18','3','','0','0'),('25','Group','管理组','1','0','3','2','','0','2'),('26','add','创建用户组','1','0','25','3','','0','2'),('27','index','用户组列表','1','0','25','3','','0','0'),('28','edit','编辑用户组','1','0','25','3','','0','0'),('29','del','删除用户组','1','0','25','3','','0','0'),('30','insert','写入数据库','1','0','25','3','','0','0'),('153','role_sort','排序','1','','25','3','','0','0'),('32','insert','保存','1','0','6','3','','0','0'),('36','menu','左侧栏','1','0','35','3','','0','0'),('35','System','首页','1','0','2','2','','0','0'),('37','main','右侧栏目','1','0','35','3','','0','0'),('38','Article','微信图文','1','0','5','2','','0','2'),('39','index','图文列表','1','0','38','3','','0','0'),('40','add','图文添加','1','0','38','3','','0','2'),('41','edit','微信图文编辑','1','0','38','3','','0','0'),('42','del','微信图文删除','1','0','38','3','','0','0'),('80','token','公众号管理','1','0','1','0','','0','1'),('45','Function','功能模块','1','0','1','0','','0','1'),('46','Function','功能模块','1','0','45','2','','0','2'),('47','add','添加模块','1','0','46','3','','0','2'),('48','User_group','套餐管理','1','0','3','2','','0','2'),('49','add','添加套餐','1','0','48','3','','0','2'),('50','Users','客户管理','1','0','3','2','','0','2'),('51','index','客户列表','1','0','50','3','','0','0'),('52','add','添加客户','1','0','50','3','','0','2'),('53','edit','编辑客户','1','0','50','3','','0','0'),('54','del','删除客户','1','0','50','3','','0','0'),('55','insert','写入数据库','1','0','50','3','','0','0'),('56','upsave','更新用户','1','0','50','3','','0','0'),('57','Text','微信文本','1','0','5','2','','0','2'),('58','index','文本列表','1','0','57','3','','0','2'),('59','del','删除','1','0','57','3','','0','0'),('60','Custom','自定义页面','1','0','5','2','','0','2'),('61','index','列表','1','0','60','3','','0','2'),('62','edit','编辑','1','0','60','3','','0','0'),('63','del','删除','1','0','60','3','','0','0'),('64','Records','充值记录','1','0','4','2','','0','2'),('65','index','充值列表','1','0','64','3','','0','0'),('81','Token','公众号管理','1','0','80','2','','0','2'),('83','alipay','在线支付接口','1','0','6','3','','0','2'),('84','sms','短信接口','1','','6','3','','0','2'),('85','Funintro','功能介绍','1','0','45','2','','0','2'),('86','add','添加内容','1','0','85','3','','0','2'),('87','themes','模板设置','1','0','6','3','','0','2'),('88','Agent','代理商管理','1','0','1','2','','0','1'),('89','Agent','代理商管理','1','0','88','2','','0','2'),('90','add','添加代理商','1','0','89','3','','0','2'),('91','AgentPrice','优惠套餐包','1','0','88','2','','0','2'),('92','add','添加套餐包','1','0','91','3','','0','2'),('93','AgentBuyRecords','消费记录','1','0','88','2','','0','2'),('94','rippleos_key','微WIFI','1','0','6','3','','0','0'),('95','Aboutus','关于我们','1','0','45','2','','0','2'),('96','add','添加内容','1','0','95','3','','0','2'),('97','Database','数据库备份','1','0','2','2','','0','2'),('101','platform','平台支付配置','1','','6','3','','0','2'),('102','Platform','平台支付','1','','4','2','','0','2'),('103','index','平台对账','1','','102','3','','0','2'),('147','wechat_api','授权管理','1','0','6','3','','0','2'),('131','del','删除功能','1','0','85','3','','0','0'),('132','upsave','更新数据库','1','0','85','3','','0','0'),('133','insert','写入数据库','1','0','85','3','','0','0'),('134','index','功能列表','1','0','85','3','','1','2'),('135','edit','修改功能','1','0','85','3','','0','0'),('136','addclass','添加分类','1','0','85','3','','0','0'),('137','indexs','分类管理','1','0','85','3','','0','0');/* MySQLReback Separation */
 /* 插入数据 `vifnn_node` */
 INSERT INTO `vifnn_node` VALUES ('140','SystemIndex','后台首页','1','0','2','2','','0','2');/* MySQLReback Separation */
 /* 插入数据 `vifnn_node` */
 INSERT INTO `vifnn_node` VALUES ('145','Use','数据统计','1','','4','2','','0','2'),('146','index','统计图表','1','','145','3','','0','2'),('148','weixinPay','微信支付服务商','1','','6','3','','20','2'),('149','Cj','增值插件/系统','1','','2','2','','0','2'),('150','index','后台管理','1','','35','3','','0','0'),('151','index','后台管理','1','','140','3','','0','0'),('152','mysqlajax','优化修复数据库','1','','6','3','','0','0'),('154','access','权限浏览','1','','25','3','','0','0'),('155','access_edit','权限编辑','1','','25','3','','0','0'),('156','index','管理组列表','1','','48','3','','0','2'),('157','edit','修改套餐','1','','48','3','','0','0'),('158','del','删除套餐','1','','48','3','','0','0'),('159','search','客户搜索','1','','50','3','','0','0'),('160','syname','设为系统用户','1','','50','3','','0','0'),('161','sysname','取消系统用户','1','','50','3','','0','0'),('162','index','公众号列表','1','','81','3','','0','2'),('163','del','公众号删除','1','','81','3','','0','0'),('164','index','列表','1','','95','3','','0','2'),('165','edit','修改','1','','95','3','','0','0'),('166','del','删除','1','','95','3','','0','0'),('167','adds','执行添加分类','1','','85','3','','0','0'),('168','edits','分类修改','1','','85','3','','0','0'),('169','dels','分类删除','1','','85','3','','0','0'),('170','upsaves','执行分类修改','1','','85','3','','0','0'),('171','search','确认设置分类','1','','85','3','','0','0'),('172','add','添加','1','','85','3','','0','0'),('173','index','列表','1','','46','3','','0','2'),('174','edit','修改','1','','46','3','','0','0'),('175','del','删除','1','','46','3','','0','0'),('176','paid','处理账单','1','','102','3','','0','0'),('177','paid_all','一键处理','1','','102','3','','0','0'),('178','add','添加','1','','102','3','','0','0'),('179','del','删除','1','','102','3','','0','0'),('180','edit','修改','1','','102','3','','0','0'),('181','insert','插入数据库','1','','102','3','','0','0'),('182','upsave','更新数据库','1','','102','3','','0','0'),('189','index','图片列表','1','','188','3','','0','2'),('188','Examine_image','图片审核','1','','4','2','','0','2'),('187','send','确认充值','1','','64','3','','0','0'),('190','del','删除','1','','188','3','','0','0'),('191','set','审核','1','','188','3','','0','0'),('192','set_all','一键审核','1','','188','3','','0','0'),('193','info','查看详情','1','','188','3','','0','0'),('194','Susceptible','敏感词过滤','1','','4','2','','0','2'),('195','index','列表','1','','194','3','','0','2'),('196','add','添加','1','','194','3','','0','2'),('197','adds','批量添加','1','','194','3','','0','2'),('198','del','删除','1','','194','3','','0','0'),('199','set','开启关闭','1','','194','3','','0','0'),('200','set_all','一键开启关闭','1','','194','3','','0','0'),('201','edit','修改','1','','194','3','','0','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_norms` */
 DROP TABLE IF EXISTS `vifnn_norms`;/* MySQLReback Separation */ CREATE TABLE `vifnn_norms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL,
  `catid` int(10) unsigned NOT NULL,
  `value` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_notice_record` */
 DROP TABLE IF EXISTS `vifnn_notice_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_notice_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `n_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `n_id` (`n_id`) USING BTREE,
  KEY `customerid` (`userid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_numqueue_action` */
 DROP TABLE IF EXISTS `vifnn_numqueue_action`;/* MySQLReback Separation */ CREATE TABLE `vifnn_numqueue_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reply_keyword` char(30) NOT NULL,
  `reply_pic` varchar(100) NOT NULL,
  `reply_title` varchar(20) NOT NULL,
  `reply_content` varchar(200) NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '1',
  `token` char(25) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `is_hot` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_numqueue_admin` */
 DROP TABLE IF EXISTS `vifnn_numqueue_admin`;/* MySQLReback Separation */ CREATE TABLE `vifnn_numqueue_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(100) NOT NULL DEFAULT '' COMMENT '登陆密码',
  `wecha_id` char(50) NOT NULL DEFAULT '',
  `store_id` int(11) NOT NULL,
  `role` varchar(100) NOT NULL DEFAULT '' COMMENT '所属权限',
  `token` char(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_numqueue_receive` */
 DROP TABLE IF EXISTS `vifnn_numqueue_receive`;/* MySQLReback Separation */ CREATE TABLE `vifnn_numqueue_receive` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `queue_type` char(5) NOT NULL DEFAULT 'A' COMMENT '等待类型',
  `queue_number` char(30) NOT NULL DEFAULT '' COMMENT '排队号码',
  `numbers` int(11) NOT NULL DEFAULT '0',
  `phone` char(30) NOT NULL DEFAULT '0' COMMENT '手机号',
  `status` tinyint(1) NOT NULL COMMENT '号码状态',
  `wecha_id` char(50) NOT NULL,
  `token` char(25) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_numqueue_store` */
 DROP TABLE IF EXISTS `vifnn_numqueue_store`;/* MySQLReback Separation */ CREATE TABLE `vifnn_numqueue_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `store_type` tinyint(1) NOT NULL,
  `opentime` tinyint(4) NOT NULL DEFAULT '0',
  `closetime` tinyint(4) NOT NULL DEFAULT '0',
  `logo` varchar(255) NOT NULL COMMENT 'logo',
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `remark` char(50) NOT NULL,
  `price` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `type_value` varchar(255) NOT NULL,
  `address` varchar(100) NOT NULL,
  `tel` varchar(20) NOT NULL DEFAULT '',
  `privilege_link` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `action_id` int(11) NOT NULL,
  `token` char(50) NOT NULL,
  `jump_name` varchar(255) NOT NULL,
  `hankowthames` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL,
  `wait_time` int(11) NOT NULL DEFAULT '0',
  `allow_distance` float(6,2) NOT NULL,
  `need_numbers` tinyint(1) NOT NULL DEFAULT '0',
  `need_wait` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_order` */
 DROP TABLE IF EXISTS `vifnn_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `orderid` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `is_sub` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `refund_id` varchar(30) NOT NULL DEFAULT '0',
  `refund_no` varchar(30) NOT NULL,
  `refund_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `refund_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_ordering_class` */
 DROP TABLE IF EXISTS `vifnn_ordering_class`;/* MySQLReback Separation */ CREATE TABLE `vifnn_ordering_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `name` varchar(10) NOT NULL,
  `sort` tinyint(2) NOT NULL,
  `info` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_ordering_set` */
 DROP TABLE IF EXISTS `vifnn_ordering_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_ordering_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `keyword` varchar(10) NOT NULL,
  `title` varchar(60) NOT NULL,
  `info` varchar(120) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `flash` text NOT NULL,
  `create_time` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_orderprinter` */
 DROP TABLE IF EXISTS `vifnn_orderprinter`;/* MySQLReback Separation */ CREATE TABLE `vifnn_orderprinter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(20) NOT NULL DEFAULT '',
  `companyid` int(10) NOT NULL DEFAULT '0',
  `printtype` varchar(2) NOT NULL DEFAULT '',
  `apikey` varchar(100) NOT NULL DEFAULT '',
  `mc` varchar(50) NOT NULL DEFAULT '',
  `mcode` varchar(60) NOT NULL DEFAULT '',
  `mkey` varchar(60) NOT NULL DEFAULT '',
  `mp` varchar(11) NOT NULL DEFAULT '',
  `count` mediumint(5) NOT NULL DEFAULT '1',
  `modules` varchar(100) NOT NULL DEFAULT '',
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  `qr` varchar(200) DEFAULT NULL,
  `number` int(10) unsigned NOT NULL,
  `print_company` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_orderprinter` */
 INSERT INTO `vifnn_orderprinter` VALUES ('1','zrtmca1421056092','7','','','','','','13800138000','1','Store,Repast,Hotel,DishOut,Numqueue,Medical','0','','','0','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_other` */
 DROP TABLE IF EXISTS `vifnn_other`;/* MySQLReback Separation */ CREATE TABLE `vifnn_other` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `info` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_panorama` */
 DROP TABLE IF EXISTS `vifnn_panorama`;/* MySQLReback Separation */ CREATE TABLE `vifnn_panorama` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `picurl` varchar(300) NOT NULL,
  `intro` varchar(300) NOT NULL DEFAULT '',
  `music` varchar(100) NOT NULL DEFAULT '',
  `frontpic` varchar(100) NOT NULL DEFAULT '',
  `rightpic` varchar(100) NOT NULL DEFAULT '',
  `backpic` varchar(100) NOT NULL DEFAULT '',
  `leftpic` varchar(100) NOT NULL DEFAULT '',
  `toppic` varchar(100) NOT NULL DEFAULT '',
  `bottompic` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(60) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT '0',
  `taxis` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_paper` */
 DROP TABLE IF EXISTS `vifnn_paper`;/* MySQLReback Separation */ CREATE TABLE `vifnn_paper` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(40) NOT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `pic` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_payment` */
 DROP TABLE IF EXISTS `vifnn_payment`;/* MySQLReback Separation */ CREATE TABLE `vifnn_payment` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(200) DEFAULT NULL,
  `pay_code` varchar(20) NOT NULL DEFAULT '',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `pay_fee` varchar(10) NOT NULL DEFAULT '0',
  `pay_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_config` text NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_config` */
 DROP TABLE IF EXISTS `vifnn_pc_config`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_config` (
  `token` varchar(50) NOT NULL,
  `site_name` varchar(50) NOT NULL,
  `site_logo` varchar(150) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_address` varchar(200) NOT NULL,
  `site_qq` varchar(100) NOT NULL,
  `site_phone` varchar(100) NOT NULL,
  `site_email` varchar(100) NOT NULL,
  `site_count` text NOT NULL,
  `site_icp` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL,
  `seo_keywords` varchar(200) NOT NULL,
  `seo_description` text NOT NULL,
  `site_tpl` text NOT NULL,
  `other_info` text NOT NULL COMMENT '模板自定义配置项，序列化存储',
  PRIMARY KEY (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_down` */
 DROP TABLE IF EXISTS `vifnn_pc_down`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_down` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `key` varchar(50) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `hits` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`) USING BTREE,
  KEY `time` (`time`) USING BTREE,
  KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_down_category` */
 DROP TABLE IF EXISTS `vifnn_pc_down_category`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_down_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) NOT NULL,
  `cat_key` varchar(50) NOT NULL,
  `cat_sort` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `cat_key` (`cat_key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_flash` */
 DROP TABLE IF EXISTS `vifnn_pc_flash`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_flash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `key` varchar(50) NOT NULL,
  `pic` varchar(200) NOT NULL,
  `url` varchar(255) NOT NULL,
  `token` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_flash_cat` */
 DROP TABLE IF EXISTS `vifnn_pc_flash_cat`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_flash_cat` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) NOT NULL,
  `cat_key` varchar(50) NOT NULL COMMENT '分类Key,使用Key调用轮播图',
  `token` varchar(50) NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `cat_key` (`cat_key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_nav` */
 DROP TABLE IF EXISTS `vifnn_pc_nav`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `s_name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `key` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_news` */
 DROP TABLE IF EXISTS `vifnn_pc_news`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `key` varchar(50) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `hits` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`) USING BTREE,
  KEY `time` (`time`) USING BTREE,
  KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_news_category` */
 DROP TABLE IF EXISTS `vifnn_pc_news_category`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_news_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) NOT NULL,
  `cat_key` varchar(50) NOT NULL,
  `cat_sort` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `cat_key` (`cat_key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_page` */
 DROP TABLE IF EXISTS `vifnn_pc_page`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `s_title` varchar(100) NOT NULL,
  `key` varchar(50) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_product` */
 DROP TABLE IF EXISTS `vifnn_pc_product`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `key` varchar(50) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `hits` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`) USING BTREE,
  KEY `time` (`time`) USING BTREE,
  KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_product_category` */
 DROP TABLE IF EXISTS `vifnn_pc_product_category`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_product_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) NOT NULL,
  `cat_key` varchar(50) NOT NULL,
  `cat_sort` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `cat_key` (`cat_key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pc_site` */
 DROP TABLE IF EXISTS `vifnn_pc_site`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pc_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `site` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site` (`site`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pengyouquanad` */
 DROP TABLE IF EXISTS `vifnn_pengyouquanad`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pengyouquanad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(40) NOT NULL,
  `title` char(40) NOT NULL,
  `keyword` char(20) NOT NULL,
  `intro` varchar(250) NOT NULL,
  `reply_pic` varchar(250) NOT NULL,
  `adtitle` varchar(250) NOT NULL,
  `adurl` varchar(255) NOT NULL,
  `ad_pic` varchar(255) NOT NULL,
  `adinfo` varchar(255) NOT NULL,
  `adimg` varchar(255) NOT NULL,
  `gongzhonghao` char(100) NOT NULL,
  `gzhid` char(100) NOT NULL,
  `gzh_ewm` varchar(255) NOT NULL,
  `stats` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_photo` */
 DROP TABLE IF EXISTS `vifnn_photo`;/* MySQLReback Separation */ CREATE TABLE `vifnn_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(20) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `isshoinfo` tinyint(1) NOT NULL,
  `num` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `info` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_photo_list` */
 DROP TABLE IF EXISTS `vifnn_photo_list`;/* MySQLReback Separation */ CREATE TABLE `vifnn_photo_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `pid` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `sort` int(11) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `info` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_photo_log` */
 DROP TABLE IF EXISTS `vifnn_photo_log`;/* MySQLReback Separation */ CREATE TABLE `vifnn_photo_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL DEFAULT '',
  `picurl` varchar(200) NOT NULL DEFAULT '',
  `openid` varchar(100) NOT NULL DEFAULT '',
  `printed` tinyint(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_photography` */
 DROP TABLE IF EXISTS `vifnn_photography`;/* MySQLReback Separation */ CREATE TABLE `vifnn_photography` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '相册id',
  `token` varchar(60) NOT NULL COMMENT '所属公众号',
  `keyword` varchar(60) NOT NULL COMMENT '关键字',
  `tpl` varchar(30) NOT NULL DEFAULT 'index' COMMENT '模板名',
  `title` varchar(150) NOT NULL COMMENT '标题',
  `picurl` varchar(200) NOT NULL COMMENT '封面图',
  `openpic` varchar(200) NOT NULL COMMENT '开场图',
  `toppic` varchar(200) NOT NULL COMMENT '顶部图',
  `man` varchar(30) NOT NULL COMMENT '男方姓名',
  `woman` varchar(30) NOT NULL COMMENT '女方姓名',
  `who_first` tinyint(1) NOT NULL COMMENT '谁名字在先',
  `video` varchar(200) NOT NULL COMMENT '视频',
  `mp3url` varchar(200) NOT NULL COMMENT '背景音乐',
  `word` varchar(200) NOT NULL COMMENT '留言',
  `firstvote` varchar(20) NOT NULL COMMENT '第一个投票内容',
  `firstvotenum` int(1) NOT NULL COMMENT '第一个投票票数',
  `secondvote` varchar(20) NOT NULL COMMENT '第二个投票内容',
  `secondvotenum` int(1) NOT NULL COMMENT '第二个投票票数',
  `thirdvote` varchar(20) NOT NULL COMMENT '第三个投票内容',
  `thirdvotenum` int(1) NOT NULL COMMENT '第三个投票票数',
  `first` varchar(100) NOT NULL COMMENT '第一个奖品',
  `firstnum` int(1) NOT NULL COMMENT '第一个奖品需要的助力数量',
  `second` varchar(100) NOT NULL COMMENT '第二个奖品',
  `secondnum` int(1) NOT NULL COMMENT '第二个奖品数量',
  `nownum` int(1) NOT NULL COMMENT '当前助力数量',
  `success` varchar(100) NOT NULL COMMENT '报名成功提示',
  `bottompic` varchar(200) NOT NULL COMMENT '底部图片',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `toolbar` tinyint(1) NOT NULL DEFAULT '1' COMMENT '显示分类按钮',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_photography_info` */
 DROP TABLE IF EXISTS `vifnn_photography_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_photography_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL COMMENT '主表id',
  `phone` varchar(11) NOT NULL COMMENT '电话',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_photography_ip` */
 DROP TABLE IF EXISTS `vifnn_photography_ip`;/* MySQLReback Separation */ CREATE TABLE `vifnn_photography_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL COMMENT '主表id',
  `ip` varchar(32) NOT NULL COMMENT 'ip',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pic_wall` */
 DROP TABLE IF EXISTS `vifnn_pic_wall`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pic_wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `joinnum` int(11) NOT NULL COMMENT '参与人数',
  `click` int(11) NOT NULL COMMENT '点击次数',
  `token` varchar(30) NOT NULL,
  `keyword` varchar(10) NOT NULL,
  `starpicurl` varchar(100) NOT NULL COMMENT '填写活动开始图片网址',
  `title` varchar(60) NOT NULL COMMENT '活动名称',
  `sttxt` varchar(200) NOT NULL COMMENT '简介',
  `statdate` int(11) NOT NULL COMMENT '活动开始时间',
  `enddate` int(11) NOT NULL COMMENT '活动结束时间',
  `info` varchar(200) NOT NULL COMMENT '活动说明',
  `endtite` varchar(60) NOT NULL COMMENT '活动结束公告主题',
  `endpicurl` varchar(100) NOT NULL COMMENT '活动结束图片网址',
  `endinfo` varchar(60) NOT NULL COMMENT '活动结束说明',
  `status` int(1) NOT NULL,
  `createtime` int(11) NOT NULL,
  `ischeck` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pic_walllog` */
 DROP TABLE IF EXISTS `vifnn_pic_walllog`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pic_walllog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '活动id',
  `token` varchar(60) NOT NULL COMMENT '公众账号token',
  `picurl` varchar(100) NOT NULL COMMENT '照片链接',
  `wecha_id` varchar(30) NOT NULL COMMENT '微信用户id',
  `username` varchar(30) NOT NULL COMMENT '微信用户名字',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `state` int(1) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pintuan` */
 DROP TABLE IF EXISTS `vifnn_pintuan`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pintuan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `classify` int(11) NOT NULL DEFAULT '0',
  `price` int(20) NOT NULL DEFAULT '0',
  `facepic` varchar(200) NOT NULL,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `goods_info` text,
  `tuan_info` text,
  `display` int(11) NOT NULL DEFAULT '2',
  `addtime` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `tel` varchar(30) NOT NULL,
  `custom_sharetitle` varchar(255) NOT NULL,
  `custom_sharedsc` varchar(500) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `info` varchar(500) NOT NULL,
  `isopenlimit` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `title` (`title`),
  KEY `classify` (`classify`),
  KEY `display` (`display`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pintuan_changepic` */
 DROP TABLE IF EXISTS `vifnn_pintuan_changepic`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pintuan_changepic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `tid` int(11) NOT NULL DEFAULT '0',
  `pic` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pintuan_classify` */
 DROP TABLE IF EXISTS `vifnn_pintuan_classify`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pintuan_classify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pintuan_guize` */
 DROP TABLE IF EXISTS `vifnn_pintuan_guize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pintuan_guize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `tid` int(11) NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `discount` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pintuan_order` */
 DROP TABLE IF EXISTS `vifnn_pintuan_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pintuan_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `team_id` int(10) unsigned NOT NULL DEFAULT '0',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `ishead` int(11) NOT NULL DEFAULT '0',
  `price` float(9,2) NOT NULL,
  `paid` int(11) NOT NULL DEFAULT '0',
  `orderid` varchar(50) DEFAULT NULL,
  `transactionid` varchar(150) DEFAULT NULL,
  `paytype` varchar(30) DEFAULT NULL,
  `third_id` varchar(100) NOT NULL,
  `addtime` int(11) NOT NULL,
  `goods_name` varchar(100) DEFAULT NULL,
  `goods_pic` varchar(200) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_tel` varchar(50) DEFAULT NULL,
  `user_address` varchar(500) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `express_name` varchar(100) DEFAULT NULL,
  `express_num` varchar(200) DEFAULT NULL,
  `message` text,
  `isbucha` int(11) NOT NULL DEFAULT '0',
  `jilu_number` int(11) NOT NULL DEFAULT '0',
  `jilu_enddate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `TEAMID` (`team_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pintuan_refundlog` */
 DROP TABLE IF EXISTS `vifnn_pintuan_refundlog`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pintuan_refundlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) NOT NULL,
  `team_id` int(10) NOT NULL,
  `token` varchar(100) NOT NULL,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `wecha_id` varchar(50) NOT NULL,
  `money` float(6,2) NOT NULL DEFAULT '0.00',
  `refundtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pintuan_reply` */
 DROP TABLE IF EXISTS `vifnn_pintuan_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pintuan_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `pic` varchar(200) NOT NULL,
  `info` varchar(120) DEFAULT NULL,
  `tel` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_pintuan_team` */
 DROP TABLE IF EXISTS `vifnn_pintuan_team`;/* MySQLReback Separation */ CREATE TABLE `vifnn_pintuan_team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `head_id` int(10) unsigned NOT NULL DEFAULT '0',
  `count` int(10) unsigned NOT NULL DEFAULT '1',
  `type` int(10) unsigned NOT NULL DEFAULT '0',
  `guize_id` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `isrefund` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `tid` (`tid`),
  KEY `head_id` (`head_id`),
  KEY `type` (`type`),
  KEY `type_2` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_platform_pay` */
 DROP TABLE IF EXISTS `vifnn_platform_pay`;/* MySQLReback Separation */ CREATE TABLE `vifnn_platform_pay` (
  `platform_id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` varchar(60) NOT NULL,
  `price` float NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `from` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `thirdorderid` varchar(60) NOT NULL,
  PRIMARY KEY (`platform_id`),
  KEY `time` (`time`),
  KEY `orderid` (`orderid`,`from`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_popularity` */
 DROP TABLE IF EXISTS `vifnn_popularity`;/* MySQLReback Separation */ CREATE TABLE `vifnn_popularity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(30) NOT NULL,
  `keyword` char(35) NOT NULL,
  `title` char(45) NOT NULL,
  `pic` char(200) NOT NULL,
  `top_pic` varchar(250) NOT NULL,
  `start` char(15) NOT NULL,
  `end` char(15) NOT NULL,
  `addr` varchar(150) NOT NULL,
  `longitude` char(20) NOT NULL,
  `latitude` char(20) NOT NULL,
  `info` text NOT NULL,
  `is_open` tinyint(4) NOT NULL,
  `add_time` char(15) NOT NULL,
  `show_num` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  `is_attention` tinyint(4) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `id_2` (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_popularity_prize` */
 DROP TABLE IF EXISTS `vifnn_popularity_prize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_popularity_prize` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(30) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` char(45) NOT NULL,
  `img` char(200) NOT NULL,
  `num` int(11) NOT NULL,
  `use_num` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `gt` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_popularity_share` */
 DROP TABLE IF EXISTS `vifnn_popularity_share`;/* MySQLReback Separation */ CREATE TABLE `vifnn_popularity_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(30) NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `wecha_id` char(40) NOT NULL,
  `share_key` char(32) NOT NULL,
  `add_time` char(15) NOT NULL,
  `num` int(11) NOT NULL,
  `share_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_popularity_user` */
 DROP TABLE IF EXISTS `vifnn_popularity_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_popularity_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wecha_id` char(40) NOT NULL,
  `pid` int(11) NOT NULL,
  `token` char(35) NOT NULL,
  `add_time` char(15) NOT NULL,
  `share_count` int(11) NOT NULL,
  `share_key` char(40) NOT NULL,
  `is_real` tinyint(4) NOT NULL,
  `share_num` int(100) NOT NULL,
  `share_code` varchar(100) NOT NULL,
  `is_see` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_present` */
 DROP TABLE IF EXISTS `vifnn_present`;/* MySQLReback Separation */ CREATE TABLE `vifnn_present` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `class` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `info` varchar(300) NOT NULL,
  `img` char(250) NOT NULL,
  `classname` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_problem_game` */
 DROP TABLE IF EXISTS `vifnn_problem_game`;/* MySQLReback Separation */ CREATE TABLE `vifnn_problem_game` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(60) NOT NULL,
  `title` char(60) NOT NULL,
  `keyword` char(20) NOT NULL,
  `logo_pic` char(120) NOT NULL,
  `token` char(25) NOT NULL,
  `banner` char(120) NOT NULL,
  `explain` varchar(600) NOT NULL,
  `rule` text NOT NULL,
  `add_time` char(10) NOT NULL,
  `is_open` enum('0','1') NOT NULL,
  `answer_time` char(5) NOT NULL,
  `sub_limit` smallint(5) unsigned NOT NULL,
  `over_hint` varchar(500) NOT NULL,
  `question_num` mediumint(9) NOT NULL,
  `score` mediumint(9) NOT NULL,
  `end_day` smallint(6) NOT NULL,
  `start_time` char(10) NOT NULL,
  `is_follow` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_problem_option` */
 DROP TABLE IF EXISTS `vifnn_problem_option`;/* MySQLReback Separation */ CREATE TABLE `vifnn_problem_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `answer` varchar(500) NOT NULL,
  `is_true` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_problem_question` */
 DROP TABLE IF EXISTS `vifnn_problem_question`;/* MySQLReback Separation */ CREATE TABLE `vifnn_problem_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(20) NOT NULL,
  `title` varchar(500) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `problem_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_problem_question_log` */
 DROP TABLE IF EXISTS `vifnn_problem_question_log`;/* MySQLReback Separation */ CREATE TABLE `vifnn_problem_question_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(20) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `expend_time` char(5) NOT NULL,
  `add_time` char(10) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_problem_user` */
 DROP TABLE IF EXISTS `vifnn_problem_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_problem_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(20) NOT NULL,
  `wecha_id` char(100) NOT NULL,
  `user_name` char(50) NOT NULL,
  `phone` char(11) NOT NULL,
  `nickname` char(50) NOT NULL,
  `add_time` char(10) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `score_count` int(11) NOT NULL,
  `expend_count` mediumint(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product` */
 DROP TABLE IF EXISTS `vifnn_product`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `catid` mediumint(4) NOT NULL DEFAULT '0',
  `gid` int(10) unsigned NOT NULL,
  `storeid` mediumint(4) NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL DEFAULT '',
  `price` float NOT NULL DEFAULT '0',
  `vprice` float NOT NULL,
  `oprice` float NOT NULL DEFAULT '0',
  `mailprice` float NOT NULL,
  `discount` float NOT NULL DEFAULT '10',
  `intro` text NOT NULL,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(100) NOT NULL DEFAULT '',
  `salecount` mediumint(4) NOT NULL DEFAULT '0',
  `logourl` varchar(150) NOT NULL DEFAULT '',
  `dining` tinyint(1) NOT NULL DEFAULT '0',
  `groupon` tinyint(1) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0',
  `fakemembercount` mediumint(4) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `num` int(10) unsigned NOT NULL,
  `commission_type` varchar(10) NOT NULL DEFAULT '' COMMENT '佣金类型 固定金额fixed, 百分比 float',
  `commission` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '分销佣金',
  `allow_distribution` char(1) NOT NULL DEFAULT '0' COMMENT '允许分销 0, 1',
  `sn` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sn_name` varchar(200) DEFAULT NULL,
  `sn_pass` varchar(200) DEFAULT NULL,
  `groupon_num` int(10) unsigned NOT NULL DEFAULT '200',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`,`storeid`),
  KEY `catid_2` (`catid`),
  KEY `storeid` (`storeid`),
  KEY `token` (`token`),
  KEY `price` (`price`),
  KEY `oprice` (`oprice`),
  KEY `discount` (`discount`),
  KEY `dining` (`dining`),
  KEY `groupon` (`groupon`,`endtime`),
  KEY `cid` (`cid`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_attribute` */
 DROP TABLE IF EXISTS `vifnn_product_attribute`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_cart` */
 DROP TABLE IF EXISTS `vifnn_product_cart`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_cart` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `transactionid` varchar(100) NOT NULL DEFAULT '',
  `paytype` varchar(30) NOT NULL DEFAULT '',
  `productid` int(10) NOT NULL DEFAULT '0',
  `code` varchar(50) NOT NULL DEFAULT '',
  `token` varchar(50) NOT NULL DEFAULT '',
  `wecha_id` varchar(60) NOT NULL DEFAULT '',
  `diningtype` mediumint(2) NOT NULL DEFAULT '0',
  `info` text NOT NULL,
  `total` mediumint(4) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `truename` varchar(20) NOT NULL DEFAULT '',
  `tel` varchar(14) NOT NULL DEFAULT '',
  `address` varchar(100) NOT NULL DEFAULT '',
  `ordertype` mediumint(2) NOT NULL DEFAULT '0',
  `tableid` mediumint(4) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL DEFAULT '0',
  `buytime` varchar(100) NOT NULL DEFAULT '',
  `groupon` tinyint(1) NOT NULL DEFAULT '0',
  `dining` tinyint(1) NOT NULL DEFAULT '0',
  `year` mediumint(4) NOT NULL DEFAULT '0',
  `month` mediumint(4) NOT NULL DEFAULT '0',
  `day` mediumint(4) NOT NULL DEFAULT '0',
  `hour` smallint(4) NOT NULL DEFAULT '0',
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `orderid` varchar(40) NOT NULL DEFAULT '',
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `logistics` varchar(70) NOT NULL DEFAULT '',
  `logisticsid` varchar(50) NOT NULL DEFAULT '',
  `printed` tinyint(1) NOT NULL DEFAULT '0',
  `handled` tinyint(1) NOT NULL DEFAULT '0',
  `handledtime` int(10) NOT NULL DEFAULT '0',
  `handleduid` int(10) NOT NULL DEFAULT '0',
  `score` int(10) unsigned NOT NULL,
  `paymode` tinyint(1) unsigned NOT NULL,
  `comment` varchar(300) NOT NULL DEFAULT '' COMMENT '买家留言',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '买家id',
  `twid` varchar(20) NOT NULL COMMENT '来源推广人的推广ID',
  `totalprice` float NOT NULL COMMENT '购买商品的订单总价',
  `sn` tinyint(1) NOT NULL DEFAULT '0',
  `sn_content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`,`time`),
  KEY `groupon` (`groupon`),
  KEY `dining` (`dining`),
  KEY `printed` (`printed`),
  KEY `year` (`year`,`month`,`day`,`hour`),
  KEY `diningtype` (`diningtype`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_cart_list` */
 DROP TABLE IF EXISTS `vifnn_product_cart_list`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_cart_list` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `cartid` int(10) NOT NULL DEFAULT '0',
  `productid` int(10) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `total` mediumint(4) NOT NULL DEFAULT '0',
  `wecha_id` varchar(60) NOT NULL DEFAULT '',
  `token` varchar(50) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT '0',
  `shipping` float NOT NULL DEFAULT '0' COMMENT '运费',
  `sku_id` int(10) NOT NULL DEFAULT '0' COMMENT '库存id',
  `comment` varchar(300) NOT NULL DEFAULT '0' COMMENT '买家留言',
  PRIMARY KEY (`id`),
  KEY `cartid` (`cartid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_cat` */
 DROP TABLE IF EXISTS `vifnn_product_cat`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_cat` (
  `id` mediumint(4) NOT NULL AUTO_INCREMENT,
  `sort` int(10) NOT NULL DEFAULT '0',
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL,
  `des` varchar(500) NOT NULL DEFAULT '',
  `parentid` mediumint(4) NOT NULL,
  `logourl` varchar(100) NOT NULL,
  `dining` tinyint(1) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL,
  `norms` varchar(100) NOT NULL,
  `color` varchar(100) NOT NULL,
  `isfinal` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `pc_cat_id` int(11) NOT NULL,
  `pc_web_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`),
  KEY `token` (`token`),
  KEY `dining` (`dining`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_comment` */
 DROP TABLE IF EXISTS `vifnn_product_comment`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(50) NOT NULL,
  `cartid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `detailid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(60) NOT NULL,
  `truename` varchar(20) NOT NULL,
  `tel` varchar(14) NOT NULL,
  `content` varchar(500) NOT NULL,
  `productinfo` varchar(80) NOT NULL,
  `score` tinyint(1) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `isdelete` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `wecha_id` (`wecha_id`),
  KEY `token` (`token`),
  KEY `cartid` (`cartid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_detail` */
 DROP TABLE IF EXISTS `vifnn_product_detail`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `format` varchar(100) NOT NULL,
  `color` varchar(100) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `price` float NOT NULL,
  `vprice` float NOT NULL,
  `logo` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_diningtable` */
 DROP TABLE IF EXISTS `vifnn_product_diningtable`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_diningtable` (
  `id` mediumint(4) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `intro` varchar(500) NOT NULL DEFAULT '',
  `taxis` mediumint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_group` */
 DROP TABLE IF EXISTS `vifnn_product_group`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(32) NOT NULL,
  `name` varchar(50) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_image` */
 DROP TABLE IF EXISTS `vifnn_product_image`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `image` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_mail_price` */
 DROP TABLE IF EXISTS `vifnn_product_mail_price`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_mail_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_relation` */
 DROP TABLE IF EXISTS `vifnn_product_relation`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_setting` */
 DROP TABLE IF EXISTS `vifnn_product_setting`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(100) NOT NULL,
  `price` float NOT NULL DEFAULT '-1' COMMENT '满多少元免邮费',
  `score` float NOT NULL,
  `paymode` tinyint(1) unsigned NOT NULL,
  `tpid` int(10) unsigned NOT NULL,
  `footerid` int(10) unsigned NOT NULL,
  `headerbackgroud` text NOT NULL,
  `headerid` int(10) unsigned NOT NULL,
  `isgroup` tinyint(1) unsigned NOT NULL,
  `email` varchar(64) NOT NULL DEFAULT '',
  `email_status` char(1) NOT NULL DEFAULT '0',
  `shop_send_sms` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_product_setting` */
 INSERT INTO `vifnn_product_setting` VALUES ('1','22','ywxgfh1465574691','-1','0','0','165','9','','0','0','','0','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_product_sn` */
 DROP TABLE IF EXISTS `vifnn_product_sn`;/* MySQLReback Separation */ CREATE TABLE `vifnn_product_sn` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `wecha_id` varchar(60) NOT NULL COMMENT '微信唯一识别码',
  `token` varchar(30) NOT NULL,
  `sn` varchar(200) NOT NULL COMMENT '中奖后序列号',
  `pass` varchar(200) NOT NULL,
  `sendstutas` int(10) unsigned NOT NULL DEFAULT '0',
  `sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_punish` */
 DROP TABLE IF EXISTS `vifnn_punish`;/* MySQLReback Separation */ CREATE TABLE `vifnn_punish` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `title` char(40) NOT NULL,
  `keyword` char(20) NOT NULL,
  `pic` char(120) NOT NULL,
  `name` char(10) NOT NULL,
  `use_num` int(11) NOT NULL,
  `info` varchar(500) NOT NULL,
  `is_open` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_punish_item` */
 DROP TABLE IF EXISTS `vifnn_punish_item`;/* MySQLReback Separation */ CREATE TABLE `vifnn_punish_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_qcloud_sendout` */
 DROP TABLE IF EXISTS `vifnn_qcloud_sendout`;/* MySQLReback Separation */ CREATE TABLE `vifnn_qcloud_sendout` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `suborderid` varchar(1000) DEFAULT NULL,
  `orderid` varchar(1000) DEFAULT NULL,
  `packageid` varchar(1000) DEFAULT NULL,
  `payprice` varchar(100) DEFAULT NULL,
  `openid` varchar(1000) DEFAULT NULL,
  `paynum` varchar(100) DEFAULT NULL,
  `freedays` varchar(100) DEFAULT NULL,
  `servicedays` varchar(100) DEFAULT NULL,
  `payunit` char(100) DEFAULT NULL,
  `service` char(50) DEFAULT 'site',
  `serviceId` varchar(1000) DEFAULT NULL,
  `price` varchar(100) DEFAULT NULL,
  `providerId` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_qcloud_user` */
 DROP TABLE IF EXISTS `vifnn_qcloud_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_qcloud_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` char(100) NOT NULL,
  `token` char(255) NOT NULL,
  `mpname` char(60) NOT NULL,
  `mporiginalid` char(60) NOT NULL,
  `mpid` char(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_quanxian` */
 DROP TABLE IF EXISTS `vifnn_quanxian`;/* MySQLReback Separation */ CREATE TABLE `vifnn_quanxian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` char(32) NOT NULL,
  `admin` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `statu` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_question_bank` */
 DROP TABLE IF EXISTS `vifnn_question_bank`;/* MySQLReback Separation */ CREATE TABLE `vifnn_question_bank` (
  `id` int(11) NOT NULL,
  `figure` varchar(2) DEFAULT NULL,
  `question_types` varchar(2) DEFAULT NULL,
  `question` varchar(255) DEFAULT NULL,
  `option_num` int(11) DEFAULT NULL,
  `optionA` varchar(100) DEFAULT NULL,
  `optionB` varchar(100) DEFAULT NULL,
  `optionC` varchar(100) DEFAULT NULL,
  `optionD` varchar(100) DEFAULT NULL,
  `optionE` varchar(100) DEFAULT NULL,
  `optionF` varchar(100) DEFAULT NULL,
  `answer` varchar(6) DEFAULT NULL,
  `classify` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_reason` */
 DROP TABLE IF EXISTS `vifnn_reason`;/* MySQLReback Separation */ CREATE TABLE `vifnn_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `phos_one` varchar(250) NOT NULL,
  `create_time` varchar(100) NOT NULL,
  `classname` varchar(120) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_recipe` */
 DROP TABLE IF EXISTS `vifnn_recipe`;/* MySQLReback Separation */ CREATE TABLE `vifnn_recipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `begintime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `monday` text NOT NULL,
  `tuesday` text NOT NULL,
  `wednesday` text NOT NULL,
  `thursday` text NOT NULL,
  `friday` text NOT NULL,
  `saturday` text NOT NULL,
  `sunday` text NOT NULL,
  `ishow` int(1) NOT NULL DEFAULT '1' COMMENT '1:显示,2隐藏,默认1',
  `sort` int(11) NOT NULL DEFAULT '1',
  `type` char(15) NOT NULL DEFAULT '',
  `headpic` varchar(200) NOT NULL,
  `infos` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_recognition` */
 DROP TABLE IF EXISTS `vifnn_recognition`;/* MySQLReback Separation */ CREATE TABLE `vifnn_recognition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL DEFAULT '',
  `attention_num` int(5) NOT NULL DEFAULT '0',
  `keyword` varchar(60) NOT NULL DEFAULT '',
  `code_url` varchar(200) NOT NULL DEFAULT '',
  `scene_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `groupid` int(11) NOT NULL DEFAULT '0',
  `fuwu_code_url` varchar(200) DEFAULT NULL,
  `data` text NOT NULL COMMENT '二维码配置',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '处理类型',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '处理类型关联外键ID',
  `action_name` varchar(30) NOT NULL DEFAULT '' COMMENT '微信场景类型',
  `expire_seconds` int(11) NOT NULL DEFAULT '0' COMMENT '有效期',
  `scans` int(11) NOT NULL DEFAULT '0' COMMENT '扫描次数',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '关注次数',
  `ticket` varchar(300) NOT NULL DEFAULT '' COMMENT '微信返回的获取凭证',
  `url` varchar(300) NOT NULL DEFAULT '' COMMENT '微信返回的解析地址',
  `sha1` varchar(40) NOT NULL DEFAULT '' COMMENT '数据摘要',
  `expiration` int(11) DEFAULT NULL COMMENT '过期时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `config` text NOT NULL COMMENT '二维码配置',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_recognition_data` */
 DROP TABLE IF EXISTS `vifnn_recognition_data`;/* MySQLReback Separation */ CREATE TABLE `vifnn_recognition_data` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `rid` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `year` int(11) NOT NULL DEFAULT '0',
  `month` int(11) NOT NULL DEFAULT '0',
  `day` int(11) NOT NULL DEFAULT '0',
  `is_ali` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_red_packet` */
 DROP TABLE IF EXISTS `vifnn_red_packet`;/* MySQLReback Separation */ CREATE TABLE `vifnn_red_packet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `title` char(40) NOT NULL,
  `keyword` char(30) NOT NULL,
  `msg_pic` char(120) NOT NULL,
  `desc` varchar(200) NOT NULL,
  `info` text NOT NULL,
  `start_time` char(11) NOT NULL,
  `end_time` char(11) NOT NULL,
  `ext_total` mediumint(8) unsigned NOT NULL,
  `get_number` smallint(5) unsigned NOT NULL,
  `value_count` mediumint(8) unsigned NOT NULL,
  `is_open` enum('0','1') NOT NULL,
  `item_num` mediumint(9) NOT NULL,
  `item_sum` mediumint(9) NOT NULL,
  `item_max` mediumint(9) NOT NULL,
  `item_unit` varchar(30) NOT NULL,
  `packet_type` enum('1','2') NOT NULL,
  `deci` smallint(6) NOT NULL,
  `people` mediumint(9) NOT NULL,
  `password` char(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_red_packet_exchange` */
 DROP TABLE IF EXISTS `vifnn_red_packet_exchange`;/* MySQLReback Separation */ CREATE TABLE `vifnn_red_packet_exchange` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `packet_id` int(11) NOT NULL,
  `price` char(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `type_name` char(45) NOT NULL,
  `time` char(11) NOT NULL,
  `log_id` text NOT NULL,
  `mobile` char(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_red_packet_log` */
 DROP TABLE IF EXISTS `vifnn_red_packet_log`;/* MySQLReback Separation */ CREATE TABLE `vifnn_red_packet_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `add_time` char(11) NOT NULL,
  `token` char(25) NOT NULL,
  `packet_id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  `prize_name` char(40) NOT NULL,
  `worth` decimal(10,2) NOT NULL,
  `is_reward` enum('0','1','2') NOT NULL,
  `type` enum('1','2') NOT NULL,
  `code` char(40) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_red_packet_prize` */
 DROP TABLE IF EXISTS `vifnn_red_packet_prize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_red_packet_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `type` enum('1','2') NOT NULL,
  `name` char(40) NOT NULL,
  `worth` int(11) NOT NULL,
  `num` mediumint(9) NOT NULL,
  `odds` decimal(10,0) NOT NULL,
  `sue` mediumint(9) NOT NULL,
  `is_open` enum('0','1') NOT NULL,
  `packet_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_red_packet_reward` */
 DROP TABLE IF EXISTS `vifnn_red_packet_reward`;/* MySQLReback Separation */ CREATE TABLE `vifnn_red_packet_reward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `mobile` char(11) NOT NULL,
  `wxname` char(40) NOT NULL,
  `packet_id` int(11) NOT NULL,
  `log_id` int(11) NOT NULL,
  `add_time` char(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_renew` */
 DROP TABLE IF EXISTS `vifnn_renew`;/* MySQLReback Separation */ CREATE TABLE `vifnn_renew` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `url` char(255) NOT NULL,
  `img_x` char(200) NOT NULL,
  `img_w` char(200) NOT NULL,
  `time` varchar(13) NOT NULL,
  `status` int(1) NOT NULL,
  `agentid` int(10) NOT NULL DEFAULT '0',
  `imgs` char(250) NOT NULL,
  `color` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_reply` */
 DROP TABLE IF EXISTS `vifnn_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wecha_id` varchar(60) NOT NULL DEFAULT '',
  `differ` tinyint(1) NOT NULL DEFAULT '0',
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `message_id` int(11) NOT NULL,
  `reply` varchar(1000) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_reply_info` */
 DROP TABLE IF EXISTS `vifnn_reply_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_reply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `picurl` varchar(120) NOT NULL DEFAULT '',
  `info` varchar(120) NOT NULL DEFAULT '',
  `picurls1` varchar(120) NOT NULL,
  `picurls2` varchar(120) NOT NULL,
  `picurls3` varchar(120) NOT NULL,
  `infotype` varchar(20) NOT NULL DEFAULT '',
  `diningyuding` tinyint(1) NOT NULL DEFAULT '1',
  `diningwaimai` tinyint(1) NOT NULL DEFAULT '1',
  `config` text NOT NULL,
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `apiurl` varchar(50) NOT NULL DEFAULT '',
  `copyright` text NOT NULL,
  `readpass` char(40) DEFAULT NULL,
  `banner` varchar(500) NOT NULL DEFAULT '',
  `money_chg_send_sms` int(1) NOT NULL,
  `integral_chg_send_sms` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_requestdata` */
 DROP TABLE IF EXISTS `vifnn_requestdata`;/* MySQLReback Separation */ CREATE TABLE `vifnn_requestdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `day` int(2) NOT NULL,
  `time` int(11) NOT NULL,
  `textnum` int(5) NOT NULL,
  `imgnum` int(5) NOT NULL,
  `videonum` int(5) NOT NULL,
  `other` int(5) NOT NULL,
  `follownum` int(5) NOT NULL,
  `unfollownum` int(5) NOT NULL,
  `3g` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_requestdata` */
 INSERT INTO `vifnn_requestdata` VALUES ('1','ezrehr1458789061','2016','3','30','1459333462','6','0','0','0','3','2','0'),('2','ezrehr1458789061','2016','3','31','1459403724','3','0','0','0','1','1','0'),('3','zbemii1462891866','2016','5','11','1462912200','0','0','0','0','0','1','0'),('4','zbemii1462891866','2016','5','12','1463039652','0','0','0','0','1','0','0'),('5','zbemii1462891866','2016','5','13','1463076607','0','0','0','0','0','1','0'),('6','zbemii1462891866','2016','5','14','1463206124','0','0','0','0','2','2','0'),('7','zbemii1462891866','2016','5','16','1463376211','0','0','0','0','2','1','0'),('8','zbemii1462891866','2016','5','17','1463443353','0','0','0','0','1','4','0'),('9','zbemii1462891866','2016','5','18','1463550003','0','0','0','0','2','1','0'),('10','zbemii1462891866','2016','5','19','1463619536','0','0','0','0','4','4','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_research` */
 DROP TABLE IF EXISTS `vifnn_research`;/* MySQLReback Separation */ CREATE TABLE `vifnn_research` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lid` int(10) unsigned NOT NULL,
  `nums` int(10) unsigned NOT NULL,
  `token` varchar(80) NOT NULL,
  `title` varchar(50) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `logourl` varchar(300) NOT NULL,
  `description` varchar(300) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `lid` (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_research_answer` */
 DROP TABLE IF EXISTS `vifnn_research_answer`;/* MySQLReback Separation */ CREATE TABLE `vifnn_research_answer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(200) NOT NULL,
  `nums` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `qid` (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_research_question` */
 DROP TABLE IF EXISTS `vifnn_research_question`;/* MySQLReback Separation */ CREATE TABLE `vifnn_research_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_research_result` */
 DROP TABLE IF EXISTS `vifnn_research_result`;/* MySQLReback Separation */ CREATE TABLE `vifnn_research_result` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(80) NOT NULL,
  `qid` int(10) unsigned NOT NULL,
  `aids` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`),
  KEY `wecha_id` (`wecha_id`,`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_reservation` */
 DROP TABLE IF EXISTS `vifnn_reservation`;/* MySQLReback Separation */ CREATE TABLE `vifnn_reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `addtype` varchar(20) NOT NULL DEFAULT 'house_book',
  `address` varchar(50) NOT NULL,
  `place` varchar(50) NOT NULL,
  `lng` varchar(13) NOT NULL,
  `lat` varchar(13) NOT NULL,
  `tel` varchar(13) NOT NULL,
  `headpic` varchar(200) NOT NULL,
  `info` text,
  `typename` varchar(50) NOT NULL,
  `typename2` varchar(50) NOT NULL,
  `typename3` varchar(50) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `date` varchar(50) NOT NULL,
  `allnums` varchar(50) NOT NULL,
  `name_show` tinyint(4) NOT NULL DEFAULT '1',
  `time_show` tinyint(4) NOT NULL DEFAULT '1',
  `txt1` varchar(50) NOT NULL,
  `value1` varchar(50) NOT NULL,
  `txt2` varchar(50) NOT NULL,
  `value2` varchar(50) NOT NULL,
  `txt3` varchar(50) NOT NULL,
  `value3` varchar(50) NOT NULL,
  `txt4` varchar(50) NOT NULL,
  `value4` varchar(50) NOT NULL,
  `txt5` varchar(50) NOT NULL,
  `value5` varchar(50) NOT NULL,
  `select1` varchar(50) NOT NULL,
  `svalue1` varchar(100) NOT NULL,
  `select2` varchar(50) NOT NULL,
  `svalue2` varchar(100) NOT NULL,
  `select3` varchar(50) NOT NULL,
  `svalue3` varchar(100) NOT NULL,
  `select4` varchar(50) NOT NULL,
  `svalue4` varchar(100) NOT NULL,
  `select5` varchar(50) NOT NULL,
  `svalue5` varchar(100) NOT NULL,
  `datename` varchar(100) NOT NULL,
  `take` int(11) NOT NULL DEFAULT '1',
  `email` varchar(30) NOT NULL,
  `open_email` tinyint(4) NOT NULL DEFAULT '0',
  `sms` varchar(13) NOT NULL,
  `open_sms` tinyint(4) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_reservebook` */
 DROP TABLE IF EXISTS `vifnn_reservebook`;/* MySQLReback Separation */ CREATE TABLE `vifnn_reservebook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  `truename` varchar(50) NOT NULL,
  `tel` varchar(13) NOT NULL,
  `housetype` varchar(50) NOT NULL,
  `dateline` varchar(50) NOT NULL,
  `timepart` varchar(50) NOT NULL,
  `info` varchar(100) NOT NULL,
  `type` char(15) NOT NULL,
  `carnum` varchar(50) DEFAULT '',
  `orderName` varchar(50) DEFAULT '',
  `booktime` int(11) NOT NULL,
  `remate` tinyint(3) NOT NULL DEFAULT '0' COMMENT '客服标志',
  `kfinfo` varchar(100) NOT NULL,
  `sex` int(11) DEFAULT '0',
  `age` int(11) NOT NULL DEFAULT '0',
  `address` varchar(50) NOT NULL DEFAULT '',
  `choose` varchar(50) NOT NULL DEFAULT '',
  `number` int(11) NOT NULL DEFAULT '0',
  `paid` int(1) DEFAULT '0',
  `orderid` char(32) NOT NULL DEFAULT '',
  `payprice` decimal(10,2) DEFAULT NULL,
  `shiporder` char(32) NOT NULL DEFAULT '',
  `productName` varchar(50) NOT NULL DEFAULT '',
  `km` int(11) NOT NULL,
  `paytype` varchar(100) NOT NULL DEFAULT '',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `wecha_id` (`wecha_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_rippleos_node` */
 DROP TABLE IF EXISTS `vifnn_rippleos_node`;/* MySQLReback Separation */ CREATE TABLE `vifnn_rippleos_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) DEFAULT NULL,
  `node` int(20) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `code_keyword` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_role` */
 DROP TABLE IF EXISTS `vifnn_role`;/* MySQLReback Separation */ CREATE TABLE `vifnn_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '后台组名',
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '是否激活 1：是 0：否',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序权重',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_role_user` */
 DROP TABLE IF EXISTS `vifnn_role_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` smallint(6) unsigned NOT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_router` */
 DROP TABLE IF EXISTS `vifnn_router`;/* MySQLReback Separation */ CREATE TABLE `vifnn_router` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `bywechat` tinyint(1) NOT NULL DEFAULT '1',
  `wechat` varchar(50) NOT NULL DEFAULT '',
  `qrcode` varchar(200) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  `token` varchar(40) NOT NULL DEFAULT '',
  `gw_id` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_router_config` */
 DROP TABLE IF EXISTS `vifnn_router_config`;/* MySQLReback Separation */ CREATE TABLE `vifnn_router_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `picurl` varchar(200) NOT NULL DEFAULT '',
  `info` varchar(300) NOT NULL DEFAULT '',
  `contact_qq` varchar(12) NOT NULL DEFAULT '',
  `welcome_img` varchar(200) NOT NULL DEFAULT '',
  `other_img` varchar(200) NOT NULL DEFAULT '',
  `token` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scene` */
 DROP TABLE IF EXISTS `vifnn_scene`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scene` (
  `sceneid_bigint` bigint(20) NOT NULL,
  `scenecode_varchar` varchar(50) NOT NULL,
  `scenename_varchar` varchar(50) DEFAULT NULL,
  `scenetype_int` int(11) NOT NULL DEFAULT '0',
  `userid_int` int(50) NOT NULL,
  `hitcount_int` int(11) NOT NULL DEFAULT '0',
  `createtime_time` datetime DEFAULT NULL,
  `musicurl_varchar` varchar(500) DEFAULT NULL,
  `videocode_varchar` varchar(2000) DEFAULT NULL,
  `showstatus_int` int(11) NOT NULL DEFAULT '1' COMMENT '显示状态1显示,2关闭',
  `thumbnail_varchar` varchar(200) DEFAULT NULL COMMENT '缩略图',
  `movietype_int` int(11) DEFAULT '0' COMMENT '翻页方式',
  `desc_varchar` varchar(500) DEFAULT NULL COMMENT '场景描述',
  `ip_varchar` varchar(50) DEFAULT NULL,
  `delete_int` int(11) NOT NULL DEFAULT '0' COMMENT '0未删,1已经删除 ',
  `tagid_int` int(11) NOT NULL DEFAULT '0',
  `sourceId_int` int(11) NOT NULL DEFAULT '0',
  `biztype_int` int(11) NOT NULL DEFAULT '1',
  `eqid_int` int(11) DEFAULT NULL,
  `eqcode` varchar(50) DEFAULT NULL,
  `datacount_int` int(11) NOT NULL DEFAULT '0',
  `musictype_int` int(11) NOT NULL DEFAULT '3',
  `usecount_int` int(11) NOT NULL DEFAULT '0',
  `fromsceneid_bigint` bigint(20) NOT NULL DEFAULT '0',
  `publishTime` int(10) unsigned NOT NULL DEFAULT '0',
  `updateTime` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(200) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `picurl` varchar(5000) NOT NULL,
  `info` varchar(255) NOT NULL,
  `name` char(11) NOT NULL,
  `ca` varchar(255) NOT NULL,
  `catp` varchar(255) NOT NULL,
  `tph` varchar(255) NOT NULL,
  `musicurl` varchar(255) NOT NULL,
  `share` varchar(255) NOT NULL,
  `sharean` varchar(255) NOT NULL,
  `sharetp` varchar(255) NOT NULL,
  `sharetstp` varchar(255) NOT NULL,
  `sharetitle` varchar(255) NOT NULL,
  `miaosu` varchar(255) NOT NULL,
  `click` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scene_active` */
 DROP TABLE IF EXISTS `vifnn_scene_active`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scene_active` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scene_id` int(11) NOT NULL,
  `active_id` int(11) NOT NULL,
  `token` varchar(200) NOT NULL,
  `wecha_id` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scene_addtp` */
 DROP TABLE IF EXISTS `vifnn_scene_addtp`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scene_addtp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bd` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `pid` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `sort` tinyint(3) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `info` varchar(120) NOT NULL,
  `style1` tinyint(1) NOT NULL DEFAULT '0',
  `cover` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `lng` double NOT NULL,
  `lat` double NOT NULL,
  `address` varchar(255) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `wechat` varchar(255) NOT NULL,
  `bdname` varchar(255) NOT NULL,
  `bdtitle` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scene_reply` */
 DROP TABLE IF EXISTS `vifnn_scene_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scene_reply` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `info` varchar(500) DEFAULT NULL COMMENT '公司简介',
  `title` text NOT NULL,
  `tp` char(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scenedata` */
 DROP TABLE IF EXISTS `vifnn_scenedata`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scenedata` (
  `dataid_bigint` bigint(20) NOT NULL AUTO_INCREMENT,
  `sceneid_bigint` bigint(20) NOT NULL DEFAULT '0',
  `pageid_bigint` bigint(20) NOT NULL DEFAULT '0',
  `elementid_int` int(11) DEFAULT '0',
  `elementtitle_varchar` varchar(50) DEFAULT NULL,
  `elementtype_int` int(11) NOT NULL DEFAULT '5',
  `userid_int` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`dataid_bigint`),
  KEY `sceneid` (`sceneid_bigint`,`userid_int`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scenedatadetail` */
 DROP TABLE IF EXISTS `vifnn_scenedatadetail`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scenedatadetail` (
  `detailid_bigint` bigint(20) NOT NULL AUTO_INCREMENT,
  `sceneid_bigint` bigint(20) NOT NULL DEFAULT '0',
  `pageid_bigint` bigint(20) NOT NULL DEFAULT '0',
  `createtime_time` datetime DEFAULT NULL,
  `ip_varchar` varchar(50) DEFAULT NULL,
  `content_varchar` text,
  `is_import` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`detailid_bigint`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scenedatasys` */
 DROP TABLE IF EXISTS `vifnn_scenedatasys`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scenedatasys` (
  `dataid_bigint` bigint(20) NOT NULL AUTO_INCREMENT,
  `sceneid_bigint` bigint(20) NOT NULL DEFAULT '0',
  `pageid_bigint` bigint(20) NOT NULL DEFAULT '0',
  `elementid_int` int(11) DEFAULT '0',
  `elementtitle_varchar` varchar(50) DEFAULT NULL,
  `elementtype_int` int(11) NOT NULL DEFAULT '5',
  `userid_int` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`dataid_bigint`),
  KEY `sceneid` (`sceneid_bigint`,`userid_int`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scenepage` */
 DROP TABLE IF EXISTS `vifnn_scenepage`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scenepage` (
  `pageid_bigint` bigint(20) NOT NULL AUTO_INCREMENT,
  `sceneid_bigint` bigint(20) NOT NULL,
  `scenecode_varchar` varchar(50) NOT NULL,
  `pagecurrentnum_int` int(11) NOT NULL DEFAULT '1' COMMENT '当前页数',
  `createtime_time` datetime DEFAULT NULL,
  `content_text` longtext,
  `pagename_varchar` varchar(50) DEFAULT NULL,
  `userid_int` int(11) NOT NULL,
  `properties_text` text NOT NULL,
  `myTypl_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pageid_bigint`),
  KEY `sceneid` (`scenecode_varchar`) USING BTREE,
  KEY `scenid` (`sceneid_bigint`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scenepagesys` */
 DROP TABLE IF EXISTS `vifnn_scenepagesys`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scenepagesys` (
  `pageid_bigint` bigint(20) NOT NULL AUTO_INCREMENT,
  `sceneid_bigint` bigint(20) NOT NULL,
  `scenecode_varchar` varchar(50) NOT NULL,
  `pagecurrentnum_int` int(11) NOT NULL DEFAULT '1' COMMENT '当前页数',
  `createtime_time` datetime DEFAULT NULL,
  `content_text` longtext,
  `pagename_varchar` varchar(50) DEFAULT NULL,
  `userid_int` int(11) NOT NULL,
  `biztype_int` int(11) DEFAULT NULL,
  `tagid_int` int(11) DEFAULT NULL,
  `thumbsrc_varchar` varchar(200) DEFAULT NULL,
  `eqsrc_varchar` varchar(200) DEFAULT NULL,
  `eqid_int` int(11) DEFAULT NULL,
  `usecount_int` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pageid_bigint`),
  KEY `sceneid` (`scenecode_varchar`) USING BTREE,
  KEY `scenid` (`sceneid_bigint`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scenepin` */
 DROP TABLE IF EXISTS `vifnn_scenepin`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scenepin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(200) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `picurl` varchar(5000) NOT NULL,
  `info` varchar(255) NOT NULL,
  `name` char(11) NOT NULL,
  `ca` varchar(255) NOT NULL,
  `catp` varchar(255) NOT NULL,
  `tph` varchar(255) NOT NULL,
  `musicurl` varchar(255) NOT NULL,
  `share` varchar(255) NOT NULL,
  `sharean` varchar(255) NOT NULL,
  `sharetp` varchar(255) NOT NULL,
  `sharetstp` varchar(255) NOT NULL,
  `sharetitle` varchar(255) NOT NULL,
  `miaosu` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_scenepin_addtp` */
 DROP TABLE IF EXISTS `vifnn_scenepin_addtp`;/* MySQLReback Separation */ CREATE TABLE `vifnn_scenepin_addtp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `pid` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `sort` tinyint(3) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `info` varchar(120) NOT NULL,
  `style1` tinyint(1) NOT NULL DEFAULT '0',
  `cover` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `lng` double NOT NULL,
  `lat` double NOT NULL,
  `address` varchar(255) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `wechat` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_school_cat` */
 DROP TABLE IF EXISTS `vifnn_school_cat`;/* MySQLReback Separation */ CREATE TABLE `vifnn_school_cat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `name` char(50) NOT NULL,
  `icon` char(150) NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL,
  `is_show` enum('1','0') NOT NULL,
  `url` varchar(300) NOT NULL,
  `system` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_school_classify` */
 DROP TABLE IF EXISTS `vifnn_school_classify`;/* MySQLReback Separation */ CREATE TABLE `vifnn_school_classify` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `sname` varchar(50) NOT NULL,
  `ssort` int(5) NOT NULL,
  `token` varchar(50) NOT NULL,
  `type` char(20) NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `type` (`type`),
  FULLTEXT KEY `type_2` (`type`),
  FULLTEXT KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_school_score` */
 DROP TABLE IF EXISTS `vifnn_school_score`;/* MySQLReback Separation */ CREATE TABLE `vifnn_school_score` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `xq_id` int(11) NOT NULL,
  `qh_id` int(11) NOT NULL,
  `km_id` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `my_score` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_school_set_index` */
 DROP TABLE IF EXISTS `vifnn_school_set_index`;/* MySQLReback Separation */ CREATE TABLE `vifnn_school_set_index` (
  `setid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `head_url` varchar(200) NOT NULL,
  `info` varchar(100) NOT NULL,
  `album_id` int(11) NOT NULL,
  `musicurl` varchar(200) NOT NULL DEFAULT '',
  `menu1` varchar(50) NOT NULL,
  `menu1_id` int(11) NOT NULL,
  `menu2` char(15) NOT NULL,
  `menu3` char(15) NOT NULL,
  `menu4` char(15) NOT NULL,
  `menu5` char(15) NOT NULL,
  `menu6` char(15) NOT NULL,
  `menu7` char(15) NOT NULL,
  `menu8` char(15) NOT NULL,
  `menu9` varchar(50) NOT NULL DEFAULT '',
  `menu10` varchar(50) NOT NULL DEFAULT '',
  `menu2_id` int(11) NOT NULL,
  `menu3_id` int(11) NOT NULL,
  `menu4_id` int(11) NOT NULL,
  `menu5_id` int(11) NOT NULL,
  `menu6_id` int(11) NOT NULL,
  `path` varchar(3000) NOT NULL DEFAULT '0',
  `tpid` int(11) DEFAULT NULL,
  `conttpid` int(11) DEFAULT NULL,
  `picurl1` varchar(200) NOT NULL DEFAULT '',
  `picurl2` varchar(200) NOT NULL DEFAULT '',
  `picurl3` varchar(200) NOT NULL DEFAULT '',
  `picurl4` varchar(200) NOT NULL DEFAULT '',
  `picurl5` varchar(200) NOT NULL DEFAULT '',
  `picurl6` varchar(200) NOT NULL DEFAULT '',
  `picurl7` varchar(200) NOT NULL DEFAULT '',
  `picurl8` varchar(200) NOT NULL DEFAULT '',
  `picurl9` varchar(200) NOT NULL DEFAULT '',
  `picurl10` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`setid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_school_students` */
 DROP TABLE IF EXISTS `vifnn_school_students`;/* MySQLReback Separation */ CREATE TABLE `vifnn_school_students` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `xq_id` int(11) NOT NULL,
  `area_addr` varchar(60) NOT NULL DEFAULT '',
  `bj_id` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `sex` int(1) NOT NULL,
  `createdate` int(11) NOT NULL,
  `seffectivetime` date DEFAULT NULL,
  `stheendtime` date DEFAULT NULL,
  `jf_statu` int(11) DEFAULT NULL,
  `mobile` char(11) NOT NULL,
  `homephone` char(16) NOT NULL,
  `s_name` char(50) NOT NULL,
  `localdate_id` char(20) NOT NULL DEFAULT '',
  `note` varchar(50) NOT NULL DEFAULT '',
  `amount` int(11) NOT NULL,
  `area` varchar(50) NOT NULL,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_school_tcourse` */
 DROP TABLE IF EXISTS `vifnn_school_tcourse`;/* MySQLReback Separation */ CREATE TABLE `vifnn_school_tcourse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `tid` int(11) NOT NULL,
  `km_id` int(11) NOT NULL,
  `bj_id` int(11) NOT NULL,
  `xq_id` int(11) NOT NULL,
  `sd_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_school_teachers` */
 DROP TABLE IF EXISTS `vifnn_school_teachers`;/* MySQLReback Separation */ CREATE TABLE `vifnn_school_teachers` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `tname` char(50) NOT NULL,
  `birthdate` date NOT NULL,
  `createtime` int(11) NOT NULL,
  `homephone` char(12) NOT NULL,
  `mobile` char(11) NOT NULL,
  `email` char(50) NOT NULL,
  `sex` int(1) NOT NULL,
  `token` varchar(50) NOT NULL,
  `jiontime` date DEFAULT NULL,
  `info` text NOT NULL,
  `faceimg` varchar(200) NOT NULL DEFAULT '',
  `headinfo` varchar(600) NOT NULL DEFAULT '',
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_score_gift` */
 DROP TABLE IF EXISTS `vifnn_score_gift`;/* MySQLReback Separation */ CREATE TABLE `vifnn_score_gift` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL COMMENT '奖品名称',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '所需积分',
  `num` int(11) NOT NULL COMMENT '剩余数量',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_score_logs` */
 DROP TABLE IF EXISTS `vifnn_score_logs`;/* MySQLReback Separation */ CREATE TABLE `vifnn_score_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `scoretype` int(3) NOT NULL COMMENT '积分类型1.消耗2.增加',
  `typename` varchar(200) NOT NULL COMMENT '操作类型名称',
  `info` text NOT NULL COMMENT '详情记录',
  `time` varchar(200) NOT NULL COMMENT '记录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_secakeadminuser` */
 DROP TABLE IF EXISTS `vifnn_secakeadminuser`;/* MySQLReback Separation */ CREATE TABLE `vifnn_secakeadminuser` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_secakecouexp` */
 DROP TABLE IF EXISTS `vifnn_secakecouexp`;/* MySQLReback Separation */ CREATE TABLE `vifnn_secakecouexp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `descript` varchar(255) DEFAULT NULL,
  `zhan_gui` varchar(255) DEFAULT NULL,
  `zhan_gui_num` varchar(255) DEFAULT NULL,
  `img_1` varchar(255) DEFAULT NULL,
  `img_2` varchar(255) DEFAULT NULL,
  `img_3` varchar(255) DEFAULT NULL,
  `content` text,
  `add_time` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_secakecoupon` */
 DROP TABLE IF EXISTS `vifnn_secakecoupon`;/* MySQLReback Separation */ CREATE TABLE `vifnn_secakecoupon` (
  `coupon_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL DEFAULT '0',
  `coupon_name` varchar(100) NOT NULL DEFAULT '',
  `coupon_value` varchar(100) NOT NULL DEFAULT '0.00',
  `start_time` int(15) unsigned NOT NULL DEFAULT '0',
  `if_issue` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pro_id` int(10) DEFAULT NULL,
  `x_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`coupon_id`),
  KEY `store_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_secakehd` */
 DROP TABLE IF EXISTS `vifnn_secakehd`;/* MySQLReback Separation */ CREATE TABLE `vifnn_secakehd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL DEFAULT '0',
  `tel` varchar(50) NOT NULL,
  `jp_name` varchar(100) NOT NULL DEFAULT '',
  `start_time` int(15) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_secakepro` */
 DROP TABLE IF EXISTS `vifnn_secakepro`;/* MySQLReback Separation */ CREATE TABLE `vifnn_secakepro` (
  `p_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pro_name` varchar(100) NOT NULL DEFAULT '',
  `pro_num` varchar(100) NOT NULL DEFAULT '',
  `pro_zhanguan` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`p_id`),
  KEY `pro_name` (`pro_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_secakepur` */
 DROP TABLE IF EXISTS `vifnn_secakepur`;/* MySQLReback Separation */ CREATE TABLE `vifnn_secakepur` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `qq` varchar(255) DEFAULT NULL,
  `danwei` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `content` text,
  `add_time` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_secakexunbao` */
 DROP TABLE IF EXISTS `vifnn_secakexunbao`;/* MySQLReback Separation */ CREATE TABLE `vifnn_secakexunbao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL DEFAULT '0',
  `state` int(5) NOT NULL DEFAULT '0',
  `x_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_seckill_action` */
 DROP TABLE IF EXISTS `vifnn_seckill_action`;/* MySQLReback Separation */ CREATE TABLE `vifnn_seckill_action` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_name` varchar(20) NOT NULL COMMENT '活动名称',
  `action_header_img` text NOT NULL COMMENT '活动头部图片',
  `action_key` varchar(50) NOT NULL COMMENT '活动key',
  `action_sdate` int(11) NOT NULL COMMENT '活动开始时间',
  `action_edate` int(11) NOT NULL COMMENT '活动结束时间',
  `rand_min_time` int(11) NOT NULL COMMENT '最小分享时间',
  `rand_max_time` int(11) NOT NULL COMMENT '最大分享时间',
  `reply_pic` text COMMENT '活动图片',
  `action_token` varchar(50) DEFAULT '' COMMENT '活动发起人',
  `action_rule` text COMMENT '活动规则',
  `action_open` tinyint(4) NOT NULL DEFAULT '0',
  `reply_title` varchar(20) DEFAULT '' COMMENT '回复标题',
  `reply_content` varchar(200) DEFAULT '' COMMENT '回复简介',
  `reply_keyword` varchar(50) DEFAULT '' COMMENT '关键词',
  `action_is_reg` tinyint(4) NOT NULL DEFAULT '1',
  `action_is_attention` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`action_id`),
  KEY `action_name` (`action_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_seckill_base_shop` */
 DROP TABLE IF EXISTS `vifnn_seckill_base_shop`;/* MySQLReback Separation */ CREATE TABLE `vifnn_seckill_base_shop` (
  `shop_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) NOT NULL COMMENT '活动id',
  `shop_name` varchar(20) NOT NULL COMMENT '商品名称',
  `shop_num` int(11) unsigned NOT NULL,
  `shop_price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `shop_tran` decimal(10,2) NOT NULL COMMENT '运费',
  `shop_open` tinyint(4) DEFAULT '0' COMMENT '商品状态',
  `shop_detail` text COMMENT '商品描述文本',
  `shop_img` text NOT NULL,
  PRIMARY KEY (`shop_id`),
  KEY `shop_name` (`shop_name`) USING BTREE,
  KEY `action_id` (`action_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_seckill_book` */
 DROP TABLE IF EXISTS `vifnn_seckill_book`;/* MySQLReback Separation */ CREATE TABLE `vifnn_seckill_book` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` varchar(32) NOT NULL COMMENT '订单号',
  `price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `wecha_id` varchar(100) NOT NULL COMMENT '公众号的标识',
  `token` varchar(50) NOT NULL COMMENT '公众号的标识',
  `paytype` varchar(50) NOT NULL DEFAULT '' COMMENT '来自于何种支付(英文格式)',
  `paid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否支付，1代表已支付',
  `third_id` varchar(100) NOT NULL DEFAULT '' COMMENT '第三方支付平台的订单ID，用于对帐',
  `book_aid` int(11) NOT NULL COMMENT '活动id',
  `book_sid` int(11) NOT NULL COMMENT '商品id',
  `book_time` int(11) NOT NULL COMMENT '订单时间',
  `book_uid` int(11) NOT NULL COMMENT '订单用户',
  `deli_addr` varchar(100) DEFAULT '' COMMENT '收货地址',
  `true_name` varchar(30) DEFAULT '' COMMENT '收件人姓名',
  `tel` varchar(20) DEFAULT '' COMMENT '固话',
  `cel` varchar(20) DEFAULT '' COMMENT '手机',
  PRIMARY KEY (`book_id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `book_aid` (`book_aid`) USING BTREE,
  KEY `book_sid` (`book_sid`) USING BTREE,
  KEY `paid` (`paid`) USING BTREE,
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_seckill_share` */
 DROP TABLE IF EXISTS `vifnn_seckill_share`;/* MySQLReback Separation */ CREATE TABLE `vifnn_seckill_share` (
  `share_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_aid` int(11) NOT NULL COMMENT '活动id',
  `user_share` varchar(50) NOT NULL COMMENT '分享key',
  `share_nickname` varchar(50) DEFAULT '' COMMENT '昵称',
  `share_time` int(11) DEFAULT '0' COMMENT '减少时间',
  `share_pic` varchar(255) DEFAULT '' COMMENT '用户图像',
  `is_opened` tinyint(4) DEFAULT '0' COMMENT '0 表示用户未接受 1 表示已接受',
  `open_time` int(11) DEFAULT '0' COMMENT '分享时间',
  `share_wechaid` varchar(50) DEFAULT '' COMMENT '分享链接wecha_id',
  PRIMARY KEY (`share_id`),
  KEY `user_share` (`user_share`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_seckill_shop_thum` */
 DROP TABLE IF EXISTS `vifnn_seckill_shop_thum`;/* MySQLReback Separation */ CREATE TABLE `vifnn_seckill_shop_thum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` varchar(20) NOT NULL COMMENT '商品id',
  `shop_thum` varchar(500) NOT NULL COMMENT '缩略图',
  `shop_big` varchar(500) NOT NULL COMMENT '大图',
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_seckill_users` */
 DROP TABLE IF EXISTS `vifnn_seckill_users`;/* MySQLReback Separation */ CREATE TABLE `vifnn_seckill_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_aid` int(11) NOT NULL COMMENT '活动id',
  `user_nickname` varchar(20) DEFAULT '' COMMENT '普通用户昵称',
  `user_headimgurl` varchar(500) DEFAULT '' COMMENT '用户用户头像',
  `user_shareid` varchar(100) DEFAULT '' COMMENT '用户分享key',
  `user_wechaid` varchar(100) DEFAULT '' COMMENT '用户wcha_id',
  `user_sex` tinyint(4) DEFAULT '0' COMMENT '用户性别',
  `user_tel` varchar(20) DEFAULT '' COMMENT '用户电话',
  `user_qq` varchar(20) DEFAULT '' COMMENT '用户QQ',
  `user_address` varchar(50) DEFAULT '' COMMENT '用户address',
  `user_province` varchar(50) DEFAULT '' COMMENT '用户province',
  `user_city` varchar(50) DEFAULT '' COMMENT '用户city',
  `user_mintime` int(11) DEFAULT '0' COMMENT '用户分享奖励时间',
  `token` varchar(50) DEFAULT NULL COMMENT 'token',
  PRIMARY KEY (`user_id`),
  KEY `user_shareid` (`user_shareid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_selfform` */
 DROP TABLE IF EXISTS `vifnn_selfform`;/* MySQLReback Separation */ CREATE TABLE `vifnn_selfform` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(30) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(100) NOT NULL DEFAULT '',
  `intro` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `successtip` varchar(60) NOT NULL DEFAULT '',
  `failtip` varchar(60) NOT NULL DEFAULT '',
  `endtime` int(11) NOT NULL DEFAULT '0',
  `logourl` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `endtime` (`endtime`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_selfform` */
 INSERT INTO `vifnn_selfform` VALUES ('1','mbeboo1416823194','4534','353','534534','345345345','1417349913','','','1418227199','http://demo.weiqianlong.com/uploads/m/mbeboo1416823194/f/0/9/5/thumb_547b0608833d0.png');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_selfform_input` */
 DROP TABLE IF EXISTS `vifnn_selfform_input`;/* MySQLReback Separation */ CREATE TABLE `vifnn_selfform_input` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `formid` int(10) NOT NULL DEFAULT '0',
  `displayname` varchar(30) NOT NULL DEFAULT '',
  `fieldname` varchar(30) NOT NULL DEFAULT '',
  `inputtype` varchar(20) NOT NULL DEFAULT '',
  `options` varchar(200) NOT NULL DEFAULT '',
  `require` tinyint(1) NOT NULL DEFAULT '0',
  `regex` varchar(100) NOT NULL DEFAULT '',
  `taxis` mediumint(4) NOT NULL DEFAULT '0',
  `errortip` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `formid` (`formid`,`taxis`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_selfform_value` */
 DROP TABLE IF EXISTS `vifnn_selfform_value`;/* MySQLReback Separation */ CREATE TABLE `vifnn_selfform_value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `formid` int(10) NOT NULL DEFAULT '0',
  `wecha_id` varchar(50) NOT NULL DEFAULT '',
  `values` varchar(2000) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `formid` (`formid`,`wecha_id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_send_message` */
 DROP TABLE IF EXISTS `vifnn_send_message`;/* MySQLReback Separation */ CREATE TABLE `vifnn_send_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `msg_id` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `token` varchar(30) NOT NULL DEFAULT '',
  `msgtype` varchar(30) NOT NULL DEFAULT '',
  `text` varchar(800) NOT NULL DEFAULT '',
  `imgids` varchar(200) NOT NULL DEFAULT '',
  `mediasrc` varchar(200) NOT NULL DEFAULT '',
  `mediaid` varchar(100) NOT NULL DEFAULT '',
  `reachcount` int(10) NOT NULL DEFAULT '0',
  `groupid` int(10) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL DEFAULT '0',
  `openid` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `send_type` tinyint(4) NOT NULL,
  `schedule_time` int(11) NOT NULL,
  `schedule_type` tinyint(1) NOT NULL,
  `check_status` tinyint(1) NOT NULL,
  `errorcount` int(11) NOT NULL,
  `taskname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`,`time`),
  KEY `msg_id` (`msg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_senior_scene` */
 DROP TABLE IF EXISTS `vifnn_senior_scene`;/* MySQLReback Separation */ CREATE TABLE `vifnn_senior_scene` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  `keyword` char(40) NOT NULL,
  `intro` varchar(500) NOT NULL,
  `pic` varchar(120) NOT NULL,
  `url` varchar(200) NOT NULL,
  `token` char(25) NOT NULL,
  `add_time` char(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sentiment` */
 DROP TABLE IF EXISTS `vifnn_sentiment`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sentiment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `intro` text,
  `info` text,
  `reply_pic` varchar(200) NOT NULL,
  `start` int(11) NOT NULL DEFAULT '0',
  `end` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `is_open` int(11) NOT NULL DEFAULT '0',
  `is_reg` int(11) NOT NULL DEFAULT '0',
  `is_attention` int(11) NOT NULL DEFAULT '0',
  `is_sms` int(11) NOT NULL DEFAULT '0',
  `fxtitle` varchar(200) DEFAULT NULL,
  `is_nosex` int(11) NOT NULL DEFAULT '0',
  `opposite_sex` varchar(20) NOT NULL DEFAULT '0',
  `same_sex` varchar(20) NOT NULL DEFAULT '0',
  `no_sex` varchar(20) NOT NULL DEFAULT '0',
  `man_label` text NOT NULL,
  `woman_label` text NOT NULL,
  `name_zhi` varchar(50) NOT NULL DEFAULT '魅力值',
  `rank_num` int(11) NOT NULL DEFAULT '10',
  `fxinfo` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `title` (`title`) USING BTREE,
  KEY `is_open` (`is_open`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sentiment_label` */
 DROP TABLE IF EXISTS `vifnn_sentiment_label`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sentiment_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `label` varchar(50) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '1',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `state` (`state`) USING BTREE,
  KEY `label` (`label`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sentiment_label_helps` */
 DROP TABLE IF EXISTS `vifnn_sentiment_label_helps`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sentiment_label_helps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `labels` varchar(512) NOT NULL,
  `addtime` int(11) NOT NULL,
  `label_wecha_id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `label_wecha_id` (`label_wecha_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sentiment_news` */
 DROP TABLE IF EXISTS `vifnn_sentiment_news`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sentiment_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `imgurl` varchar(200) NOT NULL,
  `url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sentiment_prize` */
 DROP TABLE IF EXISTS `vifnn_sentiment_prize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sentiment_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `imgurl` varchar(200) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sentiment_record` */
 DROP TABLE IF EXISTS `vifnn_sentiment_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sentiment_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `share_key` varchar(100) NOT NULL,
  `addtime` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `share_key` (`share_key`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sentiment_user` */
 DROP TABLE IF EXISTS `vifnn_sentiment_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sentiment_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `help_count` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `share_key` varchar(100) NOT NULL DEFAULT '0',
  `share_num` int(11) NOT NULL DEFAULT '0',
  `tel` varchar(50) NOT NULL DEFAULT '0',
  `is_join` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `wecha_id` (`wecha_id`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `is_join` (`is_join`) USING BTREE,
  KEY `share_key` (`share_key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_service_logs` */
 DROP TABLE IF EXISTS `vifnn_service_logs`;/* MySQLReback Separation */ CREATE TABLE `vifnn_service_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `openid` varchar(60) NOT NULL,
  `enddate` int(11) NOT NULL,
  `keyword` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_service_my` */
 DROP TABLE IF EXISTS `vifnn_service_my`;/* MySQLReback Separation */ CREATE TABLE `vifnn_service_my` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `type` varchar(2) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `display` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_service_preferential` */
 DROP TABLE IF EXISTS `vifnn_service_preferential`;/* MySQLReback Separation */ CREATE TABLE `vifnn_service_preferential` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `info` text,
  `img` varchar(100) NOT NULL,
  `url` varchar(200) NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_service_setup` */
 DROP TABLE IF EXISTS `vifnn_service_setup`;/* MySQLReback Separation */ CREATE TABLE `vifnn_service_setup` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `headimgurl` varchar(200) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `desc` text,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_service_user` */
 DROP TABLE IF EXISTS `vifnn_service_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_service_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `token` varchar(60) NOT NULL,
  `userName` varchar(60) NOT NULL,
  `userPwd` varchar(32) NOT NULL,
  `endJoinDate` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_service_wxuser` */
 DROP TABLE IF EXISTS `vifnn_service_wxuser`;/* MySQLReback Separation */ CREATE TABLE `vifnn_service_wxuser` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `app_id` varchar(255) DEFAULT NULL,
  `app_key` varchar(255) DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `wxappid` varchar(200) DEFAULT NULL,
  `wxappsecret` varchar(500) DEFAULT NULL,
  `domain` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_service_wxuser` */
 INSERT INTO `vifnn_service_wxuser` VALUES ('1','ezrehr1458789061','25399','2b2ce2480878f97bc87f215063917929','0','wxfd7387d022ed6f94','11699dbda9e8bb7c1ed7997b2bff735c','http://wx.sitore.com');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_setinfo` */
 DROP TABLE IF EXISTS `vifnn_setinfo`;/* MySQLReback Separation */ CREATE TABLE `vifnn_setinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `kind` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `setname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_shake` */
 DROP TABLE IF EXISTS `vifnn_shake`;/* MySQLReback Separation */ CREATE TABLE `vifnn_shake` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `isact` int(1) NOT NULL DEFAULT '0',
  `title` varchar(40) NOT NULL,
  `keyword` varchar(100) NOT NULL DEFAULT '',
  `intro` varchar(400) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `logo` char(100) NOT NULL,
  `cheer` varchar(350) NOT NULL,
  `shownum` int(11) NOT NULL DEFAULT '10',
  `joinnum` int(11) DEFAULT NULL,
  `shaketype` int(11) NOT NULL DEFAULT '1',
  `token` varchar(40) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `isopen` int(1) NOT NULL DEFAULT '0',
  `clienttime` int(11) NOT NULL DEFAULT '3',
  `showtime` int(10) NOT NULL DEFAULT '3',
  `endtime` varchar(13) DEFAULT NULL,
  `background` varchar(150) DEFAULT NULL,
  `backgroundmusic` varchar(150) DEFAULT NULL,
  `music` varchar(150) DEFAULT NULL,
  `usetime` int(10) NOT NULL DEFAULT '0',
  `rule` varchar(600) NOT NULL DEFAULT '',
  `info` varchar(600) NOT NULL DEFAULT '',
  `starttime` int(11) NOT NULL,
  `endshake` int(11) NOT NULL,
  `qrcode` varchar(150) DEFAULT NULL,
  `time` int(11) NOT NULL,
  `round` int(11) NOT NULL DEFAULT '0' COMMENT '当前回合',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_shake_rt` */
 DROP TABLE IF EXISTS `vifnn_shake_rt`;/* MySQLReback Separation */ CREATE TABLE `vifnn_shake_rt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wecha_id` varchar(60) NOT NULL DEFAULT '',
  `token` varchar(30) NOT NULL DEFAULT '',
  `phone` varchar(12) NOT NULL DEFAULT '',
  `count` int(10) NOT NULL DEFAULT '0',
  `shakeid` int(10) NOT NULL DEFAULT '0',
  `round` mediumint(9) NOT NULL,
  `is_scene` enum('0','1') NOT NULL,
  `scene_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shakeid` (`shakeid`),
  KEY `wecha_id` (`wecha_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_shakearoung_device` */
 DROP TABLE IF EXISTS `vifnn_shakearoung_device`;/* MySQLReback Separation */ CREATE TABLE `vifnn_shakearoung_device` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL COMMENT '设备ID',
  `uuid` char(50) NOT NULL COMMENT '设备UUID',
  `major` int(11) NOT NULL COMMENT '主设备ID',
  `minor` int(11) NOT NULL COMMENT '次设备ID',
  `apply_id` int(11) NOT NULL DEFAULT '0' COMMENT '批次ID',
  `device_comment` char(30) NOT NULL DEFAULT '' COMMENT '设备名称',
  `page_num` int(11) NOT NULL COMMENT '关联的页面数',
  `page_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '关联的页面ID列表',
  `status` tinyint(1) NOT NULL COMMENT '设备状态',
  `token` char(50) NOT NULL,
  `add_reason` varchar(300) NOT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_shakearoung_page` */
 DROP TABLE IF EXISTS `vifnn_shakearoung_page`;/* MySQLReback Separation */ CREATE TABLE `vifnn_shakearoung_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL COMMENT '页面ID',
  `title` char(10) NOT NULL DEFAULT '' COMMENT '页面标题',
  `description` char(10) NOT NULL DEFAULT '' COMMENT '页面副标题',
  `icon_url` varchar(255) NOT NULL DEFAULT '' COMMENT '页面图标URL',
  `page_url` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转地址',
  `page_comment` char(15) NOT NULL DEFAULT '' COMMENT '页面的备注信息',
  `token` char(50) NOT NULL DEFAULT '',
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_shakelog` */
 DROP TABLE IF EXISTS `vifnn_shakelog`;/* MySQLReback Separation */ CREATE TABLE `vifnn_shakelog` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `mark` longtext,
  `endtime` int(15) NOT NULL,
  `joinnum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_shakelottery` */
 DROP TABLE IF EXISTS `vifnn_shakelottery`;/* MySQLReback Separation */ CREATE TABLE `vifnn_shakelottery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_name` varchar(50) NOT NULL DEFAULT '',
  `reply_title` varchar(50) NOT NULL DEFAULT '',
  `reply_content` varchar(200) NOT NULL DEFAULT '',
  `reply_pic` varchar(255) NOT NULL DEFAULT '',
  `action_desc` text NOT NULL,
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `remind_word` varchar(255) NOT NULL DEFAULT '',
  `remind_link` varchar(255) NOT NULL DEFAULT '',
  `totaltimes` int(11) NOT NULL DEFAULT '1',
  `everydaytimes` int(11) NOT NULL DEFAULT '0',
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `timespan` int(11) NOT NULL,
  `record_nums` int(11) NOT NULL,
  `is_limitwin` int(11) NOT NULL,
  `is_follow` tinyint(1) NOT NULL DEFAULT '1',
  `is_register` tinyint(1) NOT NULL DEFAULT '1',
  `share_count` int(11) NOT NULL,
  `custom_sharetitle` varchar(255) NOT NULL,
  `custom_sharedsc` varchar(500) NOT NULL,
  `follow_msg` varchar(255) NOT NULL,
  `follow_btn_msg` varchar(255) NOT NULL,
  `register_msg` varchar(255) NOT NULL,
  `custom_follow_url` varchar(255) NOT NULL,
  `token` varchar(30) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `join_number` int(11) NOT NULL,
  `actual_join_number` int(11) NOT NULL,
  `other_source` varchar(30) DEFAULT NULL COMMENT '其他活动对接来源',
  `scene_time` int(10) unsigned NOT NULL DEFAULT '0',
  `scene_state` int(10) unsigned NOT NULL DEFAULT '0',
  `is_amount` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `other_source` (`other_source`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_shakelottery_hbrecord` */
 DROP TABLE IF EXISTS `vifnn_shakelottery_hbrecord`;/* MySQLReback Separation */ CREATE TABLE `vifnn_shakelottery_hbrecord` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL,
  `mch_billno` char(50) NOT NULL DEFAULT '',
  `fans_id` varchar(60) NOT NULL DEFAULT '',
  `fans_nickname` varchar(60) NOT NULL DEFAULT '',
  `money` float(6,2) DEFAULT NULL,
  `status` char(20) NOT NULL DEFAULT '',
  `hb_type` tinyint(1) NOT NULL,
  `token` char(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hid` (`aid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_shakelottery_prize` */
 DROP TABLE IF EXISTS `vifnn_shakelottery_prize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_shakelottery_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `prizename` varchar(50) NOT NULL DEFAULT '',
  `prizeimg` varchar(255) NOT NULL DEFAULT '',
  `prizenum` int(11) NOT NULL,
  `expendnum` int(11) NOT NULL DEFAULT '0',
  `provider` varchar(100) NOT NULL DEFAULT '',
  `token` varchar(30) NOT NULL DEFAULT '',
  `prize_type` tinyint(1) NOT NULL,
  `hongbao_configure` varchar(800) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AID_TOKEN` (`aid`,`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_shakelottery_record` */
 DROP TABLE IF EXISTS `vifnn_shakelottery_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_shakelottery_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prizeid` int(11) NOT NULL,
  `prizename` varchar(50) NOT NULL,
  `iswin` tinyint(1) NOT NULL DEFAULT '0',
  `shaketime` int(11) NOT NULL,
  `isaccept` tinyint(1) NOT NULL DEFAULT '0',
  `accepttime` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `wecha_name` char(50) NOT NULL,
  `token` char(30) NOT NULL,
  `prize_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `AID` (`aid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_shakelottery_users` */
 DROP TABLE IF EXISTS `vifnn_shakelottery_users`;/* MySQLReback Separation */ CREATE TABLE `vifnn_shakelottery_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `total_shakes` int(11) NOT NULL,
  `today_shakes` int(11) NOT NULL,
  `wecha_id` varchar(50) NOT NULL DEFAULT '',
  `wecha_name` varchar(50) NOT NULL DEFAULT '',
  `wecha_pic` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(15) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL,
  `token` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_share` */
 DROP TABLE IF EXISTS `vifnn_share`;/* MySQLReback Separation */ CREATE TABLE `vifnn_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL DEFAULT '',
  `moduleid` int(10) NOT NULL DEFAULT '0',
  `token` varchar(30) NOT NULL DEFAULT '',
  `wecha_id` varchar(80) NOT NULL DEFAULT '',
  `to` varchar(30) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL,
  `url` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`moduleid`,`token`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_share_set` */
 DROP TABLE IF EXISTS `vifnn_share_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_share_set` (
  `token` varchar(40) NOT NULL DEFAULT '',
  `score` int(5) NOT NULL DEFAULT '0',
  `daylimit` int(5) NOT NULL DEFAULT '1',
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sharetalent` */
 DROP TABLE IF EXISTS `vifnn_sharetalent`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sharetalent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(255) NOT NULL,
  `picurl` varchar(255) NOT NULL,
  `prize` varchar(255) NOT NULL,
  `rule` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `info` mediumtext NOT NULL,
  `statdate` int(11) NOT NULL,
  `date` date NOT NULL,
  `hdrules` mediumtext NOT NULL,
  `picurl1` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `click` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sharetalent_record` */
 DROP TABLE IF EXISTS `vifnn_sharetalent_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sharetalent_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `statdate` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `click` int(11) NOT NULL,
  `rule` int(11) NOT NULL,
  `prize` varchar(255) NOT NULL,
  `end` int(11) NOT NULL,
  `picurl` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `number` int(11) NOT NULL,
  `statu` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sharetalent_reply` */
 DROP TABLE IF EXISTS `vifnn_sharetalent_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sharetalent_reply` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `info` varchar(500) DEFAULT NULL COMMENT '公司简介',
  `title` text NOT NULL,
  `tp` char(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sharetalent_sm` */
 DROP TABLE IF EXISTS `vifnn_sharetalent_sm`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sharetalent_sm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `info` mediumtext NOT NULL,
  `infos` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sharetalent_user` */
 DROP TABLE IF EXISTS `vifnn_sharetalent_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sharetalent_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sharetalent_userip` */
 DROP TABLE IF EXISTS `vifnn_sharetalent_userip`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sharetalent_userip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `enterdate` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sign_conf` */
 DROP TABLE IF EXISTS `vifnn_sign_conf`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sign_conf` (
  `conf_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `use` enum('0','1') NOT NULL,
  `integral` tinyint(4) NOT NULL,
  `stair` tinyint(4) NOT NULL,
  `token` char(25) NOT NULL,
  PRIMARY KEY (`conf_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sign_in` */
 DROP TABLE IF EXISTS `vifnn_sign_in`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sign_in` (
  `sign_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(40) NOT NULL,
  `wecha_id` char(60) NOT NULL,
  `user_name` char(50) NOT NULL,
  `integral` tinyint(4) NOT NULL,
  `time` char(11) NOT NULL,
  `continue` tinyint(2) NOT NULL,
  `phone` char(11) NOT NULL,
  `set_id` int(11) NOT NULL,
  `sid` int(10) NOT NULL,
  `del` int(1) NOT NULL,
  PRIMARY KEY (`sign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sign_set` */
 DROP TABLE IF EXISTS `vifnn_sign_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sign_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keywords` char(25) NOT NULL,
  `title` char(60) NOT NULL,
  `content` varchar(250) NOT NULL,
  `token` char(35) NOT NULL,
  `reply_img` char(150) NOT NULL,
  `top_pic` char(150) NOT NULL,
  `lng` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `range` varchar(20) NOT NULL,
  `signpass` char(16) NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL,
  `if` char(5) NOT NULL,
  `hour` char(5) NOT NULL,
  `minute` char(5) NOT NULL,
  `Cycle` char(5) NOT NULL,
  `day` varchar(10) NOT NULL,
  `site` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_site_message` */
 DROP TABLE IF EXISTS `vifnn_site_message`;/* MySQLReback Separation */ CREATE TABLE `vifnn_site_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `from` varchar(60) NOT NULL,
  `relation` tinyint(3) unsigned DEFAULT '0',
  `content` varchar(255) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `read_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_site_plugmenu` */
 DROP TABLE IF EXISTS `vifnn_site_plugmenu`;/* MySQLReback Separation */ CREATE TABLE `vifnn_site_plugmenu` (
  `token` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL DEFAULT '',
  `url` varchar(100) DEFAULT '',
  `taxis` mediumint(4) NOT NULL DEFAULT '0',
  `display` tinyint(1) NOT NULL DEFAULT '0',
  KEY `token` (`token`,`taxis`,`display`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sjmreply_info` */
 DROP TABLE IF EXISTS `vifnn_sjmreply_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sjmreply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `picurl` varchar(120) NOT NULL DEFAULT '',
  `sharepicurl` varchar(120) NOT NULL DEFAULT '',
  `info` varchar(120) NOT NULL DEFAULT '',
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `copyright` text NOT NULL,
  `ad` int(11) NOT NULL,
  `wxname` varchar(255) NOT NULL,
  `tip` varchar(255) NOT NULL,
  `url` char(255) NOT NULL,
  `shareurl` char(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sms_code` */
 DROP TABLE IF EXISTS `vifnn_sms_code`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sms_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(40) NOT NULL,
  `token` char(30) NOT NULL,
  `wecha_id` char(45) NOT NULL,
  `action` char(100) NOT NULL,
  `create_time` char(11) NOT NULL,
  `is_use` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sms_expendrecord` */
 DROP TABLE IF EXISTS `vifnn_sms_expendrecord`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sms_expendrecord` (
  `id` int(11) DEFAULT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `price` int(6) NOT NULL DEFAULT '0',
  `count` int(10) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  KEY `uid` (`uid`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sms_record` */
 DROP TABLE IF EXISTS `vifnn_sms_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sms_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `token` varchar(20) NOT NULL,
  `time` int(10) NOT NULL,
  `mp` varchar(11) NOT NULL DEFAULT '',
  `text` varchar(400) NOT NULL DEFAULT '',
  `status` mediumint(4) NOT NULL DEFAULT '0',
  `price` mediumint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`token`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_snccode` */
 DROP TABLE IF EXISTS `vifnn_snccode`;/* MySQLReback Separation */ CREATE TABLE `vifnn_snccode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `wechaname` varchar(60) NOT NULL,
  `caeatetime` int(11) NOT NULL,
  `phone` int(11) NOT NULL,
  `token` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sncode` */
 DROP TABLE IF EXISTS `vifnn_sncode`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sncode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `module` varchar(60) NOT NULL,
  `usenums` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户使用次数',
  `wecha_id` varchar(60) NOT NULL COMMENT '微信唯一识别码',
  `token` varchar(30) NOT NULL,
  `islucky` int(1) NOT NULL COMMENT '是否中奖',
  `wecha_name` varchar(60) NOT NULL COMMENT '微信号',
  `phone` varchar(15) NOT NULL,
  `sn` varchar(13) NOT NULL COMMENT '中奖后序列号',
  `prize` varchar(50) NOT NULL DEFAULT '' COMMENT '已中奖项',
  `sendstutas` int(11) NOT NULL DEFAULT '0',
  `sendtime` int(11) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`,`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_sql_log` */
 DROP TABLE IF EXISTS `vifnn_sql_log`;/* MySQLReback Separation */ CREATE TABLE `vifnn_sql_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL,
  `hash` char(40) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `code` smallint(6) NOT NULL DEFAULT '0',
  `exception` text NOT NULL,
  `update_time` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_store_flash` */
 DROP TABLE IF EXISTS `vifnn_store_flash`;/* MySQLReback Separation */ CREATE TABLE `vifnn_store_flash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  `img` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `info` varchar(90) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_storeflash` */
 DROP TABLE IF EXISTS `vifnn_storeflash`;/* MySQLReback Separation */ CREATE TABLE `vifnn_storeflash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `img` char(255) NOT NULL,
  `url` char(255) NOT NULL,
  `info` varchar(90) NOT NULL,
  `tip` smallint(11) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tip` (`tip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_styleset` */
 DROP TABLE IF EXISTS `vifnn_styleset`;/* MySQLReback Separation */ CREATE TABLE `vifnn_styleset` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `RadioGroup` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_subscribe_reply` */
 DROP TABLE IF EXISTS `vifnn_subscribe_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_subscribe_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_time` varchar(30) NOT NULL,
  `end_time` varchar(30) NOT NULL,
  `reply_type` tinyint(1) NOT NULL,
  `r_type` char(10) NOT NULL,
  `relevance_id` varchar(30) NOT NULL,
  `relevance_name` varchar(255) NOT NULL,
  `title` varchar(800) NOT NULL,
  `token` char(30) NOT NULL DEFAULT '',
  `add_time` int(11) NOT NULL,
  `update_time` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `isrepeat` tinyint(1) NOT NULL,
  `times` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `relevance_id` (`relevance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_subscribe_times` */
 DROP TABLE IF EXISTS `vifnn_subscribe_times`;/* MySQLReback Separation */ CREATE TABLE `vifnn_subscribe_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wecha_id` varchar(60) NOT NULL DEFAULT '',
  `times` int(11) NOT NULL,
  `token` varchar(60) NOT NULL DEFAULT '',
  `rid` int(10) NOT NULL,
  `last_rtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_susceptible` */
 DROP TABLE IF EXISTS `vifnn_susceptible`;/* MySQLReback Separation */ CREATE TABLE `vifnn_susceptible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(100) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `word` (`word`,`state`),
  KEY `IDX_STATE_WORD` (`state`,`word`)
) ENGINE=MyISAM AUTO_INCREMENT=1063 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('1','阿扁推翻','0','1447683995','1447683995'),('2','阿宾','0','1447683995','1447683995'),('3','阿賓','0','1447683995','1447683995'),('4','挨了一炮','0','1447683995','1447683995'),('5','爱液横流','0','1447683995','1447683995'),('6','安街逆','0','1447683995','1447683995'),('7','安局办公楼','0','1447683995','1447683995'),('8','安局豪华','0','1447683995','1447683995'),('9','安门事','0','1447683995','1447683995'),('10','安眠藥','0','1447683995','1447683995'),('11','案的准确','0','1447683995','1447683995'),('12','八九民','0','1447683995','1447683995'),('13','八九学','0','1447683995','1447683995'),('14','八九政治','0','1447683995','1447683995'),('15','把病人整','0','1447683995','1447683995'),('16','把邓小平','0','1447683995','1447683995'),('17','把学生整','0','1447683995','1447683995'),('18','罢工门','0','1447683995','1447683995'),('19','白黄牙签','0','1447683995','1447683995'),('20','败培训','0','1447683995','1447683995'),('21','办本科','0','1447683995','1447683995'),('22','办理本科','0','1447683995','1447683995'),('23','办理各种','0','1447683995','1447683995'),('24','办理票据','0','1447683995','1447683995'),('25','办理文凭','0','1447683995','1447683995'),('26','办理真实','0','1447683995','1447683995'),('27','办理证书','0','1447683995','1447683995'),('28','办理资格','0','1447683995','1447683995'),('29','办文凭','0','1447683995','1447683995'),('30','办怔','0','1447683995','1447683995'),('31','办证','0','1447683995','1447683995'),('32','半刺刀','0','1447683995','1447683995'),('33','辦毕业','0','1447683995','1447683995'),('34','辦證','0','1447683995','1447683995'),('35','谤罪获刑','0','1447683995','1447683995'),('36','磅解码器','0','1447683995','1447683995'),('37','磅遥控器','0','1447683995','1447683995'),('38','宝在甘肃修','0','1447683995','1447683995'),('39','保过答案','0','1447683995','1447683995'),('40','报复执法','0','1447683995','1447683995'),('41','爆发骚','0','1447683995','1447683995'),('42','北省委门','0','1447683995','1447683995'),('43','被打死','0','1447683995','1447683995'),('44','被指抄袭','0','1447683995','1447683995'),('45','被中共','0','1447683995','1447683995'),('46','本公司担','0','1447683995','1447683995'),('47','本无码','0','1447683995','1447683995'),('48','毕业證','0','1447683995','1447683995'),('49','变牌绝','0','1447683995','1447683995'),('50','辩词与梦','0','1447683995','1447683995'),('51','冰毒','0','1447683995','1447683995'),('52','冰火毒','0','1447683995','1447683995'),('53','冰火佳','0','1447683995','1447683995'),('54','冰火九重','0','1447683995','1447683995'),('55','冰火漫','0','1447683995','1447683995'),('56','冰淫传','0','1447683995','1447683995'),('57','冰在火上','0','1447683995','1447683995'),('58','波推龙','0','1447683995','1447683995'),('59','博彩娱','0','1447683995','1447683995'),('60','博会暂停','0','1447683995','1447683995'),('61','博园区伪','0','1447683995','1447683995'),('62','不查都','0','1447683995','1447683995'),('63','不查全','0','1447683995','1447683995'),('64','不思四化','0','1447683995','1447683995'),('65','布卖淫女','0','1447683995','1447683995'),('66','部忙组阁','0','1447683995','1447683995'),('67','部是这样','0','1447683995','1447683995'),('68','才知道只生','0','1447683995','1447683995'),('69','财众科技','0','1447683995','1447683995'),('70','采花堂','0','1447683995','1447683995'),('71','踩踏事','0','1447683995','1447683995'),('72','苍山兰','0','1447683995','1447683995'),('73','苍蝇水','0','1447683995','1447683995'),('74','藏春阁','0','1447683995','1447683995'),('75','藏獨','0','1447683995','1447683995'),('76','操了嫂','0','1447683995','1447683995'),('77','操嫂子','0','1447683995','1447683995'),('78','策没有不','0','1447683995','1447683995'),('79','插屁屁','0','1447683995','1447683995'),('80','察象蚂','0','1447683995','1447683995'),('81','拆迁灭','0','1447683995','1447683995'),('82','车牌隐','0','1447683995','1447683995'),('83','成人电','0','1447683995','1447683995'),('84','成人卡通','0','1447683995','1447683995'),('85','成人聊','0','1447683995','1447683995'),('86','成人片','0','1447683995','1447683995'),('87','成人视','0','1447683995','1447683995'),('88','成人图','0','1447683995','1447683995'),('89','成人文','0','1447683995','1447683995'),('90','成人小','0','1447683995','1447683995'),('91','城管灭','0','1447683995','1447683995'),('92','惩公安','0','1447683995','1447683995'),('93','惩贪难','0','1447683995','1447683995'),('94','充气娃','0','1447683995','1447683995'),('95','冲凉死','0','1447683995','1447683995'),('96','抽着大中','0','1447683995','1447683995'),('97','抽着芙蓉','0','1447683995','1447683995'),('98','出成绩付','0','1447683995','1447683995'),('99','出售发票','0','1447683995','1447683995'),('100','出售军','0','1447683995','1447683995'),('101','穿透仪器','0','1447683995','1447683995'),('102','春水横溢','0','1447683995','1447683995'),('103','纯度白','0','1447683995','1447683995'),('104','纯度黄','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('105','次通过考','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('106','催眠水','0','1447683995','1447683995'),('107','催情粉','0','1447683995','1447683995'),('108','催情药','0','1447683995','1447683995'),('109','催情藥','0','1447683995','1447683995'),('110','挫仑','0','1447683995','1447683995'),('111','达毕业证','0','1447683995','1447683995'),('112','答案包','0','1447683995','1447683995'),('113','答案提供','0','1447683995','1447683995'),('114','打标语','0','1447683995','1447683995'),('115','打错门','0','1447683995','1447683995'),('116','打飞机专','0','1447683995','1447683995'),('117','打死经过','0','1447683995','1447683995'),('118','打死人','0','1447683995','1447683995'),('119','打砸办公','0','1447683995','1447683995'),('120','大鸡巴','0','1447683995','1447683995'),('121','大雞巴','0','1447683995','1447683995'),('122','大纪元','0','1447683995','1447683995'),('123','大揭露','0','1447683995','1447683995'),('124','大奶子','0','1447683995','1447683995'),('125','大批贪官','0','1447683995','1447683995'),('126','大肉棒','0','1447683995','1447683995'),('127','大嘴歌','0','1447683995','1447683995'),('128','代办发票','0','1447683995','1447683995'),('129','代办各','0','1447683995','1447683995'),('130','代办文','0','1447683995','1447683995'),('131','代办学','0','1447683995','1447683995'),('132','代办制','0','1447683995','1447683995'),('133','代辦','0','1447683995','1447683995'),('134','代表烦','0','1447683995','1447683995'),('135','代開','0','1447683995','1447683995'),('136','代考','0','1447683995','1447683995'),('137','代理发票','0','1447683995','1447683995'),('138','代理票据','0','1447683995','1447683995'),('139','代您考','0','1447683995','1447683995'),('140','代您考','0','1447683995','1447683995'),('141','代写毕','0','1447683995','1447683995'),('142','代写论','0','1447683995','1447683995'),('143','代孕','0','1447683995','1447683995'),('144','贷办','0','1447683995','1447683995'),('145','贷借款','0','1447683995','1447683995'),('146','贷开','0','1447683995','1447683995'),('147','戴海静','0','1447683995','1447683995'),('148','当代七整','0','1447683995','1447683995'),('149','当官要精','0','1447683995','1447683995'),('150','当官在于','0','1447683995','1447683995'),('151','党的官','0','1447683995','1447683995'),('152','党后萎','0','1447683995','1447683995'),('153','党前干劲','0','1447683995','1447683995'),('154','刀架保安','0','1447683995','1447683995'),('155','导的情人','0','1447683995','1447683995'),('156','导叫失','0','1447683995','1447683995'),('157','导人的最','0','1447683995','1447683995'),('158','导人最','0','1447683995','1447683995'),('159','导小商','0','1447683995','1447683995'),('160','到花心','0','1447683995','1447683995'),('161','得财兼','0','1447683995','1447683995'),('162','的同修','0','1447683995','1447683995'),('163','灯草和','0','1447683995','1447683995'),('164','等级證','0','1447683995','1447683995'),('165','等屁民','0','1447683995','1447683995'),('166','等人老百','0','1447683995','1447683995'),('167','等人是老','0','1447683995','1447683995'),('168','等人手术','0','1447683995','1447683995'),('169','邓爷爷转','0','1447683995','1447683995'),('170','邓玉娇','0','1447683995','1447683995'),('171','地产之歌','0','1447683995','1447683995'),('172','地下先烈','0','1447683995','1447683995'),('173','地震哥','0','1447683995','1447683995'),('174','帝国之梦','0','1447683995','1447683995'),('175','递纸死','0','1447683995','1447683995'),('176','点数优惠','0','1447683995','1447683995'),('177','电狗','0','1447683995','1447683995'),('178','电话监','0','1447683995','1447683995'),('179','电鸡','0','1447683995','1447683995'),('180','甸果敢','0','1447683995','1447683995'),('181','蝶舞按','0','1447683995','1447683995'),('182','丁香社','0','1447683995','1447683995'),('183','丁子霖','0','1447683995','1447683995'),('184','顶花心','0','1447683995','1447683995'),('185','东北独立','0','1447683995','1447683995'),('186','东复活','0','1447683995','1447683995'),('187','东京热','0','1447683995','1447683995'),('188','東京熱','0','1447683995','1447683995'),('189','洞小口紧','0','1447683995','1447683995'),('190','都当警','0','1447683995','1447683995'),('191','都当小姐','0','1447683995','1447683995'),('192','都进中央','0','1447683995','1447683995'),('193','毒蛇钻','0','1447683995','1447683995'),('194','独立台湾','0','1447683995','1447683995'),('195','赌球网','0','1447683995','1447683995'),('196','短信截','0','1447683995','1447683995'),('197','对日强硬','0','1447683995','1447683995'),('198','多美康','0','1447683995','1447683995'),('199','躲猫猫','0','1447683995','1447683995'),('200','俄羅斯','0','1447683995','1447683995'),('201','恶势力操','0','1447683995','1447683995'),('202','恶势力插','0','1447683995','1447683995'),('203','恩氟烷','0','1447683995','1447683995'),('204','儿园惨','0','1447683995','1447683995'),('205','儿园砍','0','1447683995','1447683995'),('206','儿园杀','0','1447683995','1447683995'),('207','儿园凶','0','1447683995','1447683995'),('208','二奶大','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('209','发牌绝','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('210','发票出','0','1447683995','1447683995'),('211','发票代','0','1447683995','1447683995'),('212','发票销','0','1447683995','1447683995'),('213','發票','0','1447683995','1447683995'),('214','法车仑','0','1447683995','1447683995'),('215','法伦功','0','1447683995','1447683995'),('216','法轮','0','1447683995','1447683995'),('217','法轮佛','0','1447683995','1447683995'),('218','法维权','0','1447683995','1447683995'),('219','法一轮','0','1447683995','1447683995'),('220','法院给废','0','1447683995','1447683995'),('221','法正乾','0','1447683995','1447683995'),('222','反测速雷','0','1447683995','1447683995'),('223','反雷达测','0','1447683995','1447683995'),('224','反屏蔽','0','1447683995','1447683995'),('225','范燕琼','0','1447683995','1447683995'),('226','方迷香','0','1447683995','1447683995'),('227','防电子眼','0','1447683995','1447683995'),('228','防身药水','0','1447683995','1447683995'),('229','房贷给废','0','1447683995','1447683995'),('230','仿真枪','0','1447683995','1447683995'),('231','仿真证','0','1447683995','1447683995'),('232','诽谤罪','0','1447683995','1447683995'),('233','费私服','0','1447683995','1447683995'),('234','封锁消','0','1447683995','1447683995'),('235','佛同修','0','1447683995','1447683995'),('236','夫妻交换','0','1447683995','1447683995'),('237','福尔马林','0','1447683995','1447683995'),('238','福娃的預','0','1447683995','1447683995'),('239','福娃頭上','0','1447683995','1447683995'),('240','福香巴','0','1447683995','1447683995'),('241','府包庇','0','1447683995','1447683995'),('242','府集中领','0','1447683995','1447683995'),('243','妇销魂','0','1447683995','1447683995'),('244','附送枪','0','1447683995','1447683995'),('245','复印件生','0','1447683995','1447683995'),('246','复印件制','0','1447683995','1447683995'),('247','富民穷','0','1447683995','1447683995'),('248','富婆给废','0','1447683995','1447683995'),('249','改号软件','0','1447683995','1447683995'),('250','感扑克','0','1447683995','1447683995'),('251','冈本真','0','1447683995','1447683995'),('252','肛交','0','1447683995','1447683995'),('253','肛门是邻','0','1447683995','1447683995'),('254','岡本真','0','1447683995','1447683995'),('255','钢针狗','0','1447683995','1447683995'),('256','钢珠枪','0','1447683995','1447683995'),('257','港澳博球','0','1447683995','1447683995'),('258','港馬會','0','1447683995','1447683995'),('259','港鑫華','0','1447683995','1447683995'),('260','高就在政','0','1447683995','1447683995'),('261','高考黑','0','1447683995','1447683995'),('262','高莺莺','0','1447683995','1447683995'),('263','搞媛交','0','1447683995','1447683995'),('264','告长期','0','1447683995','1447683995'),('265','告洋状','0','1447683995','1447683995'),('266','格证考试','0','1447683995','1447683995'),('267','各类考试','0','1447683995','1447683995'),('268','各类文凭','0','1447683995','1447683995'),('269','跟踪器','0','1447683995','1447683995'),('270','工程吞得','0','1447683995','1447683995'),('271','工力人','0','1447683995','1447683995'),('272','公安错打','0','1447683995','1447683995'),('273','公安网监','0','1447683995','1447683995'),('274','公开小姐','0','1447683995','1447683995'),('275','攻官小姐','0','1447683995','1447683995'),('276','共狗','0','1447683995','1447683995'),('277','共王储','0','1447683995','1447683995'),('278','狗粮','0','1447683995','1447683995'),('279','狗屁专家','0','1447683995','1447683995'),('280','鼓动一些','0','1447683995','1447683995'),('281','乖乖粉','0','1447683995','1447683995'),('282','官商勾','0','1447683995','1447683995'),('283','官也不容','0','1447683995','1447683995'),('284','官因发帖','0','1447683995','1447683995'),('285','光学真题','0','1447683995','1447683995'),('286','跪真相','0','1447683995','1447683995'),('287','滚圆大乳','0','1447683995','1447683995'),('288','国际投注','0','1447683995','1447683995'),('289','国家妓','0','1447683995','1447683995'),('290','国家软弱','0','1447683995','1447683995'),('291','国家吞得','0','1447683995','1447683995'),('292','国库折','0','1447683995','1447683995'),('293','国一九五七','0','1447683995','1447683995'),('294','國內美','0','1447683995','1447683995'),('295','哈药直销','0','1447683995','1447683995'),('296','海访民','0','1447683995','1447683995'),('297','豪圈钱','0','1447683995','1447683995'),('298','号屏蔽器','0','1447683995','1447683995'),('299','和狗交','0','1447683995','1447683995'),('300','和狗性','0','1447683995','1447683995'),('301','和狗做','0','1447683995','1447683995'),('302','黑火药的','0','1447683995','1447683995'),('303','红色恐怖','0','1447683995','1447683995'),('304','红外透视','0','1447683995','1447683995'),('305','紅色恐','0','1447683995','1447683995'),('306','胡江内斗','0','1447683995','1447683995'),('307','胡紧套','0','1447683995','1447683995'),('308','胡錦濤','0','1447683995','1447683995'),('309','胡适眼','0','1447683995','1447683995'),('310','胡耀邦','0','1447683995','1447683995'),('311','湖淫娘','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('312','虎头猎','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('313','华国锋','0','1447683995','1447683995'),('314','华门开','0','1447683995','1447683995'),('315','化学扫盲','0','1447683995','1447683995'),('316','划老公','0','1447683995','1447683995'),('317','还会吹萧','0','1447683995','1447683995'),('318','还看锦涛','0','1447683995','1447683995'),('319','环球证件','0','1447683995','1447683995'),('320','换妻','0','1447683995','1447683995'),('321','皇冠投注','0','1447683995','1447683995'),('322','黄冰','0','1447683995','1447683995'),('323','浑圆豪乳','0','1447683995','1447683995'),('324','活不起','0','1447683995','1447683995'),('325','火车也疯','0','1447683995','1447683995'),('326','机定位器','0','1447683995','1447683995'),('327','机号定','0','1447683995','1447683995'),('328','机号卫','0','1447683995','1447683995'),('329','机卡密','0','1447683995','1447683995'),('330','机屏蔽器','0','1447683995','1447683995'),('331','基本靠吼','0','1447683995','1447683995'),('332','绩过后付','0','1447683995','1447683995'),('333','激情电','0','1447683995','1447683995'),('334','激情短','0','1447683995','1447683995'),('335','激情妹','0','1447683995','1447683995'),('336','激情炮','0','1447683995','1447683995'),('337','级办理','0','1447683995','1447683995'),('338','级答案','0','1447683995','1447683995'),('339','急需嫖','0','1447683995','1447683995'),('340','集体打砸','0','1447683995','1447683995'),('341','集体腐','0','1447683995','1447683995'),('342','挤乳汁','0','1447683995','1447683995'),('343','擠乳汁','0','1447683995','1447683995'),('344','佳静安定','0','1447683995','1447683995'),('345','家一样饱','0','1447683995','1447683995'),('346','家属被打','0','1447683995','1447683995'),('347','甲虫跳','0','1447683995','1447683995'),('348','甲流了','0','1447683995','1447683995'),('349','奸成瘾','0','1447683995','1447683995'),('350','兼职上门','0','1447683995','1447683995'),('351','监听器','0','1447683995','1447683995'),('352','监听王','0','1447683995','1447683995'),('353','简易炸','0','1447683995','1447683995'),('354','江胡内斗','0','1447683995','1447683995'),('355','江太上','0','1447683995','1447683995'),('356','江系人','0','1447683995','1447683995'),('357','江贼民','0','1447683995','1447683995'),('358','疆獨','0','1447683995','1447683995'),('359','蒋彦永','0','1447683995','1447683995'),('360','叫自慰','0','1447683995','1447683995'),('361','揭贪难','0','1447683995','1447683995'),('362','姐包夜','0','1447683995','1447683995'),('363','姐服务','0','1447683995','1447683995'),('364','姐兼职','0','1447683995','1447683995'),('365','姐上门','0','1447683995','1447683995'),('366','金扎金','0','1447683995','1447683995'),('367','金钟气','0','1447683995','1447683995'),('368','津大地震','0','1447683995','1447683995'),('369','津地震','0','1447683995','1447683995'),('370','进来的罪','0','1447683995','1447683995'),('371','京地震','0','1447683995','1447683995'),('372','京要地震','0','1447683995','1447683995'),('373','经典谎言','0','1447683995','1447683995'),('374','精子射在','0','1447683995','1447683995'),('375','警察被','0','1447683995','1447683995'),('376','警察的幌','0','1447683995','1447683995'),('377','警察殴打','0','1447683995','1447683995'),('378','警察说保','0','1447683995','1447683995'),('379','警车雷达','0','1447683995','1447683995'),('380','警方包庇','0','1447683995','1447683995'),('381','警用品','0','1447683995','1447683995'),('382','径步枪','0','1447683995','1447683995'),('383','敬请忍','0','1447683995','1447683995'),('384','究生答案','0','1447683995','1447683995'),('385','九龙论坛','0','1447683995','1447683995'),('386','九评共','0','1447683995','1447683995'),('387','酒象喝汤','0','1447683995','1447683995'),('388','酒像喝汤','0','1447683995','1447683995'),('389','就爱插','0','1447683995','1447683995'),('390','就要色','0','1447683995','1447683995'),('391','举国体','0','1447683995','1447683995'),('392','巨乳','0','1447683995','1447683995'),('393','据说全民','0','1447683995','1447683995'),('394','绝食声','0','1447683995','1447683995'),('395','军长发威','0','1447683995','1447683995'),('396','军刺','0','1447683995','1447683995'),('397','军品特','0','1447683995','1447683995'),('398','军用手','0','1447683995','1447683995'),('399','开邓选','0','1447683995','1447683995'),('400','开锁工具','0','1447683995','1447683995'),('401','開碼','0','1447683995','1447683995'),('402','開票','0','1447683995','1447683995'),('403','砍杀幼','0','1447683995','1447683995'),('404','砍伤儿','0','1447683995','1447683995'),('405','康没有不','0','1447683995','1447683995'),('406','康跳楼','0','1447683995','1447683995'),('407','考答案','0','1447683995','1447683995'),('408','考后付款','0','1447683995','1447683995'),('409','考机构','0','1447683995','1447683995'),('410','考考邓','0','1447683995','1447683995'),('411','考联盟','0','1447683995','1447683995'),('412','考前答','0','1447683995','1447683995'),('413','考前答案','0','1447683995','1447683995'),('414','考前付','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('415','考设备','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('416','考试包过','0','1447683995','1447683995'),('417','考试保','0','1447683995','1447683995'),('418','考试答案','0','1447683995','1447683995'),('419','考试机构','0','1447683995','1447683995'),('420','考试联盟','0','1447683995','1447683995'),('421','考试枪','0','1447683995','1447683995'),('422','考研考中','0','1447683995','1447683995'),('423','考中答案','0','1447683995','1447683995'),('424','磕彰','0','1447683995','1447683995'),('425','克分析','0','1447683995','1447683995'),('426','克千术','0','1447683995','1447683995'),('427','克透视','0','1447683995','1447683995'),('428','空和雅典','0','1447683995','1447683995'),('429','孔摄像','0','1447683995','1447683995'),('430','控诉世博','0','1447683995','1447683995'),('431','控制媒','0','1447683995','1447683995'),('432','口手枪','0','1447683995','1447683995'),('433','骷髅死','0','1447683995','1447683995'),('434','快速办','0','1447683995','1447683995'),('435','矿难不公','0','1447683995','1447683995'),('436','拉登说','0','1447683995','1447683995'),('437','拉开水晶','0','1447683995','1447683995'),('438','来福猎','0','1447683995','1447683995'),('439','拦截器','0','1447683995','1447683995'),('440','狼全部跪','0','1447683995','1447683995'),('441','浪穴','0','1447683995','1447683995'),('442','老虎机','0','1447683995','1447683995'),('443','雷人女官','0','1447683995','1447683995'),('444','类准确答','0','1447683995','1447683995'),('445','黎阳平','0','1447683995','1447683995'),('446','李洪志','0','1447683995','1447683995'),('447','李咏曰','0','1447683995','1447683995'),('448','理各种证','0','1447683995','1447683995'),('449','理是影帝','0','1447683995','1447683995'),('450','理证件','0','1447683995','1447683995'),('451','理做帐报','0','1447683995','1447683995'),('452','力骗中央','0','1447683995','1447683995'),('453','力月西','0','1447683995','1447683995'),('454','丽媛离','0','1447683995','1447683995'),('455','利他林','0','1447683995','1447683995'),('456','连发手','0','1447683995','1447683995'),('457','聯繫電','0','1447683995','1447683995'),('458','炼大法','0','1447683995','1447683995'),('459','两岸才子','0','1447683995','1447683995'),('460','两会代','0','1447683995','1447683995'),('461','两会又三','0','1447683995','1447683995'),('462','聊视频','0','1447683995','1447683995'),('463','聊斋艳','0','1447683995','1447683995'),('464','了件渔袍','0','1447683995','1447683995'),('465','猎好帮手','0','1447683995','1447683995'),('466','猎枪销','0','1447683995','1447683995'),('467','猎槍','0','1447683995','1447683995'),('468','獵槍','0','1447683995','1447683995'),('469','领土拿','0','1447683995','1447683995'),('470','流血事','0','1447683995','1447683995'),('471','六合彩','0','1447683995','1447683995'),('472','六死','0','1447683995','1447683995'),('473','六四事','0','1447683995','1447683995'),('474','六月联盟','0','1447683995','1447683995'),('475','龙湾事件','0','1447683995','1447683995'),('476','隆手指','0','1447683995','1447683995'),('477','陆封锁','0','1447683995','1447683995'),('478','陆同修','0','1447683995','1447683995'),('479','氯胺酮','0','1447683995','1447683995'),('480','乱奸','0','1447683995','1447683995'),('481','乱伦类','0','1447683995','1447683995'),('482','乱伦小','0','1447683995','1447683995'),('483','亂倫','0','1447683995','1447683995'),('484','伦理大','0','1447683995','1447683995'),('485','伦理电影','0','1447683995','1447683995'),('486','伦理毛','0','1447683995','1447683995'),('487','伦理片','0','1447683995','1447683995'),('488','轮功','0','1447683995','1447683995'),('489','轮手枪','0','1447683995','1447683995'),('490','论文代','0','1447683995','1447683995'),('491','罗斯小姐','0','1447683995','1447683995'),('492','裸聊网','0','1447683995','1447683995'),('493','裸舞视','0','1447683995','1447683995'),('494','落霞缀','0','1447683995','1447683995'),('495','麻古','0','1447683995','1447683995'),('496','麻果配','0','1447683995','1447683995'),('497','麻果丸','0','1447683995','1447683995'),('498','麻将透','0','1447683995','1447683995'),('499','麻醉狗','0','1447683995','1447683995'),('500','麻醉枪','0','1447683995','1447683995'),('501','麻醉槍','0','1447683995','1447683995'),('502','麻醉藥','0','1447683995','1447683995'),('503','蟆叫专家','0','1447683995','1447683995'),('504','卖地财政','0','1447683995','1447683995'),('505','卖发票','0','1447683995','1447683995'),('506','卖银行卡','0','1447683995','1447683995'),('507','卖自考','0','1447683995','1447683995'),('508','漫步丝','0','1447683995','1447683995'),('509','忙爱国','0','1447683995','1447683995'),('510','猫眼工具','0','1447683995','1447683995'),('511','毛一鲜','0','1447683995','1447683995'),('512','媒体封锁','0','1447683995','1447683995'),('513','每周一死','0','1447683995','1447683995'),('514','美艳少妇','0','1447683995','1447683995'),('515','妹按摩','0','1447683995','1447683995'),('516','妹上门','0','1447683995','1447683995'),('517','门按摩','0','1447683995','1447683995'),('518','门保健','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('519','門服務','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('520','氓培训','0','1447683995','1447683995'),('521','蒙汗药','0','1447683995','1447683995'),('522','迷幻型','0','1447683995','1447683995'),('523','迷幻药','0','1447683995','1447683995'),('524','迷幻藥','0','1447683995','1447683995'),('525','迷昏口','0','1447683995','1447683995'),('526','迷昏药','0','1447683995','1447683995'),('527','迷昏藥','0','1447683995','1447683995'),('528','迷魂香','0','1447683995','1447683995'),('529','迷魂药','0','1447683995','1447683995'),('530','迷魂藥','0','1447683995','1447683995'),('531','迷奸药','0','1447683995','1447683995'),('532','迷情水','0','1447683995','1447683995'),('533','迷情药','0','1447683995','1447683995'),('534','迷藥','0','1447683995','1447683995'),('535','谜奸药','0','1447683995','1447683995'),('536','蜜穴','0','1447683995','1447683995'),('537','灭绝罪','0','1447683995','1447683995'),('538','民储害','0','1447683995','1447683995'),('539','民九亿商','0','1447683995','1447683995'),('540','民抗议','0','1447683995','1447683995'),('541','明慧网','0','1447683995','1447683995'),('542','铭记印尼','0','1447683995','1447683995'),('543','摩小姐','0','1447683995','1447683995'),('544','母乳家','0','1447683995','1447683995'),('545','木齐针','0','1447683995','1447683995'),('546','幕没有不','0','1447683995','1447683995'),('547','幕前戲','0','1447683995','1447683995'),('548','内射','0','1447683995','1447683995'),('549','南充针','0','1447683995','1447683995'),('550','嫩穴','0','1447683995','1447683995'),('551','嫩阴','0','1447683995','1447683995'),('552','泥马之歌','0','1447683995','1447683995'),('553','你的西域','0','1447683995','1447683995'),('554','拟涛哥','0','1447683995','1447683995'),('555','娘两腿之间','0','1447683995','1447683995'),('556','妞上门','0','1447683995','1447683995'),('557','浓精','0','1447683995','1447683995'),('558','怒的志愿','0','1447683995','1447683995'),('559','女被人家搞','0','1447683995','1447683995'),('560','女激情','0','1447683995','1447683995'),('561','女技师','0','1447683995','1447683995'),('562','女人和狗','0','1447683995','1447683995'),('563','女任职名','0','1447683995','1447683995'),('564','女上门','0','1447683995','1447683995'),('565','女優','0','1447683995','1447683995'),('566','鸥之歌','0','1447683995','1447683995'),('567','拍肩神药','0','1447683995','1447683995'),('568','拍肩型','0','1447683995','1447683995'),('569','牌分析','0','1447683995','1447683995'),('570','牌技网','0','1447683995','1447683995'),('571','炮的小蜜','0','1447683995','1447683995'),('572','陪考枪','0','1447683995','1447683995'),('573','配有消','0','1447683995','1447683995'),('574','喷尿','0','1447683995','1447683995'),('575','嫖俄罗','0','1447683995','1447683995'),('576','嫖鸡','0','1447683995','1447683995'),('577','平惨案','0','1447683995','1447683995'),('578','平叫到床','0','1447683995','1447683995'),('579','仆不怕饮','0','1447683995','1447683995'),('580','普通嘌','0','1447683995','1447683995'),('581','期货配','0','1447683995','1447683995'),('582','奇迹的黄','0','1447683995','1447683995'),('583','奇淫散','0','1447683995','1447683995'),('584','骑单车出','0','1447683995','1447683995'),('585','气狗','0','1447683995','1447683995'),('586','气枪','0','1447683995','1447683995'),('587','汽狗','0','1447683995','1447683995'),('588','汽枪','0','1447683995','1447683995'),('589','氣槍','0','1447683995','1447683995'),('590','铅弹','0','1447683995','1447683995'),('591','钱三字经','0','1447683995','1447683995'),('592','枪出售','0','1447683995','1447683995'),('593','枪的参','0','1447683995','1447683995'),('594','枪的分','0','1447683995','1447683995'),('595','枪的结','0','1447683995','1447683995'),('596','枪的制','0','1447683995','1447683995'),('597','枪货到','0','1447683995','1447683995'),('598','枪决女犯','0','1447683995','1447683995'),('599','枪决现场','0','1447683995','1447683995'),('600','枪模','0','1447683995','1447683995'),('601','枪手队','0','1447683995','1447683995'),('602','枪手网','0','1447683995','1447683995'),('603','枪销售','0','1447683995','1447683995'),('604','枪械制','0','1447683995','1447683995'),('605','枪子弹','0','1447683995','1447683995'),('606','强权政府','0','1447683995','1447683995'),('607','强硬发言','0','1447683995','1447683995'),('608','抢其火炬','0','1447683995','1447683995'),('609','切听器','0','1447683995','1447683995'),('610','窃听器','0','1447683995','1447683995'),('611','禽流感了','0','1447683995','1447683995'),('612','勤捞致','0','1447683995','1447683995'),('613','氢弹手','0','1447683995','1447683995'),('614','清除负面','0','1447683995','1447683995'),('615','清純壆','0','1447683995','1447683995'),('616','情聊天室','0','1447683995','1447683995'),('617','情妹妹','0','1447683995','1447683995'),('618','情视频','0','1447683995','1447683995'),('619','情自拍','0','1447683995','1447683995'),('620','氰化钾','0','1447683995','1447683995'),('621','氰化钠','0','1447683995','1447683995'),('622','请集会','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('623','请示威','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('624','请愿','0','1447683995','1447683995'),('625','琼花问','0','1447683995','1447683995'),('626','区的雷人','0','1447683995','1447683995'),('627','娶韩国','0','1447683995','1447683995'),('628','全真证','0','1447683995','1447683995'),('629','群奸暴','0','1447683995','1447683995'),('630','群起抗暴','0','1447683995','1447683995'),('631','群体性事','0','1447683995','1447683995'),('632','绕过封锁','0','1447683995','1447683995'),('633','惹的国','0','1447683995','1447683995'),('634','人权律','0','1447683995','1447683995'),('635','人体艺','0','1447683995','1447683995'),('636','人游行','0','1447683995','1447683995'),('637','人在云上','0','1447683995','1447683995'),('638','人真钱','0','1447683995','1447683995'),('639','认牌绝','0','1447683995','1447683995'),('640','任于斯国','0','1447683995','1447683995'),('641','柔胸粉','0','1447683995','1447683995'),('642','肉洞','0','1447683995','1447683995'),('643','肉棍','0','1447683995','1447683995'),('644','如厕死','0','1447683995','1447683995'),('645','乳交','0','1447683995','1447683995'),('646','软弱的国','0','1447683995','1447683995'),('647','赛后骚','0','1447683995','1447683995'),('648','三挫','0','1447683995','1447683995'),('649','三级片','0','1447683995','1447683995'),('650','三秒倒','0','1447683995','1447683995'),('651','三网友','0','1447683995','1447683995'),('652','三唑','0','1447683995','1447683995'),('653','骚妇','0','1447683995','1447683995'),('654','骚浪','0','1447683995','1447683995'),('655','骚穴','0','1447683995','1447683995'),('656','骚嘴','0','1447683995','1447683995'),('657','扫了爷爷','0','1447683995','1447683995'),('658','色电影','0','1447683995','1447683995'),('659','色妹妹','0','1447683995','1447683995'),('660','色视频','0','1447683995','1447683995'),('661','色小说','0','1447683995','1447683995'),('662','杀指南','0','1447683995','1447683995'),('663','山涉黑','0','1447683995','1447683995'),('664','煽动不明','0','1447683995','1447683995'),('665','煽动群众','0','1447683995','1447683995'),('666','上门激','0','1447683995','1447683995'),('667','烧公安局','0','1447683995','1447683995'),('668','烧瓶的','0','1447683995','1447683995'),('669','韶关斗','0','1447683995','1447683995'),('670','韶关玩','0','1447683995','1447683995'),('671','韶关旭','0','1447683995','1447683995'),('672','射网枪','0','1447683995','1447683995'),('673','涉嫌抄袭','0','1447683995','1447683995'),('674','深喉冰','0','1447683995','1447683995'),('675','神七假','0','1447683995','1447683995'),('676','神韵艺术','0','1447683995','1447683995'),('677','生被砍','0','1447683995','1447683995'),('678','生踩踏','0','1447683995','1447683995'),('679','生肖中特','0','1447683995','1447683995'),('680','圣战不息','0','1447683995','1447683995'),('681','盛行在舞','0','1447683995','1447683995'),('682','尸博','0','1447683995','1447683995'),('683','失身水','0','1447683995','1447683995'),('684','失意药','0','1447683995','1447683995'),('685','狮子旗','0','1447683995','1447683995'),('686','十八等','0','1447683995','1447683995'),('687','十大谎','0','1447683995','1447683995'),('688','十大禁','0','1447683995','1447683995'),('689','十个预言','0','1447683995','1447683995'),('690','十类人不','0','1447683995','1447683995'),('691','十七大幕','0','1447683995','1447683995'),('692','实毕业证','0','1447683995','1447683995'),('693','实体娃','0','1447683995','1447683995'),('694','实学历文','0','1447683995','1447683995'),('695','士康事件','0','1447683995','1447683995'),('696','式粉推','0','1447683995','1447683995'),('697','视解密','0','1447683995','1447683995'),('698','是躲猫','0','1447683995','1447683995'),('699','手变牌','0','1447683995','1447683995'),('700','手答案','0','1447683995','1447683995'),('701','手狗','0','1447683995','1447683995'),('702','手机跟','0','1447683995','1447683995'),('703','手机监','0','1447683995','1447683995'),('704','手机窃','0','1447683995','1447683995'),('705','手机追','0','1447683995','1447683995'),('706','手拉鸡','0','1447683995','1447683995'),('707','手木仓','0','1447683995','1447683995'),('708','手槍','0','1447683995','1447683995'),('709','守所死法','0','1447683995','1447683995'),('710','兽交','0','1447683995','1447683995'),('711','售步枪','0','1447683995','1447683995'),('712','售纯度','0','1447683995','1447683995'),('713','售单管','0','1447683995','1447683995'),('714','售弹簧刀','0','1447683995','1447683995'),('715','售防身','0','1447683995','1447683995'),('716','售狗子','0','1447683995','1447683995'),('717','售虎头','0','1447683995','1447683995'),('718','售火药','0','1447683995','1447683995'),('719','售假币','0','1447683995','1447683995'),('720','售健卫','0','1447683995','1447683995'),('721','售军用','0','1447683995','1447683995'),('722','售猎枪','0','1447683995','1447683995'),('723','售氯胺','0','1447683995','1447683995'),('724','售麻醉','0','1447683995','1447683995'),('725','售冒名','0','1447683995','1447683995'),('726','售枪支','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('727','售热武','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('728','售三棱','0','1447683995','1447683995'),('729','售手枪','0','1447683995','1447683995'),('730','售五四','0','1447683995','1447683995'),('731','售信用','0','1447683995','1447683995'),('732','售一元硬','0','1447683995','1447683995'),('733','售子弹','0','1447683995','1447683995'),('734','售左轮','0','1447683995','1447683995'),('735','书办理','0','1447683995','1447683995'),('736','熟妇','0','1447683995','1447683995'),('737','术牌具','0','1447683995','1447683995'),('738','双管立','0','1447683995','1447683995'),('739','双管平','0','1447683995','1447683995'),('740','水阎王','0','1447683995','1447683995'),('741','丝护士','0','1447683995','1447683995'),('742','丝情侣','0','1447683995','1447683995'),('743','丝袜保','0','1447683995','1447683995'),('744','丝袜恋','0','1447683995','1447683995'),('745','丝袜美','0','1447683995','1447683995'),('746','丝袜妹','0','1447683995','1447683995'),('747','丝袜网','0','1447683995','1447683995'),('748','丝足按','0','1447683995','1447683995'),('749','司长期有','0','1447683995','1447683995'),('750','司法黑','0','1447683995','1447683995'),('751','私房写真','0','1447683995','1447683995'),('752','死法分布','0','1447683995','1447683995'),('753','死要见毛','0','1447683995','1447683995'),('754','四博会','0','1447683995','1447683995'),('755','四大扯','0','1447683995','1447683995'),('756','个四小码','0','1447683995','1447683995'),('757','苏家屯集','0','1447683995','1447683995'),('758','诉讼集团','0','1447683995','1447683995'),('759','素女心','0','1447683995','1447683995'),('760','速代办','0','1447683995','1447683995'),('761','速取证','0','1447683995','1447683995'),('762','酸羟亚胺','0','1447683995','1447683995'),('763','蹋纳税','0','1447683995','1447683995'),('764','太王四神','0','1447683995','1447683995'),('765','泰兴幼','0','1447683995','1447683995'),('766','泰兴镇中','0','1447683995','1447683995'),('767','泰州幼','0','1447683995','1447683995'),('768','贪官也辛','0','1447683995','1447683995'),('769','探测狗','0','1447683995','1447683995'),('770','涛共产','0','1447683995','1447683995'),('771','涛一样胡','0','1447683995','1447683995'),('772','特工资','0','1447683995','1447683995'),('773','特码','0','1447683995','1447683995'),('774','特上门','0','1447683995','1447683995'),('775','体透视镜','0','1447683995','1447683995'),('776','替考','0','1447683995','1447683995'),('777','替人体','0','1447683995','1447683995'),('778','天朝特','0','1447683995','1447683995'),('779','天鹅之旅','0','1447683995','1447683995'),('780','天推广歌','0','1447683995','1447683995'),('781','田罢工','0','1447683995','1447683995'),('782','田田桑','0','1447683995','1447683995'),('783','田停工','0','1447683995','1447683995'),('784','庭保养','0','1447683995','1447683995'),('785','庭审直播','0','1447683995','1447683995'),('786','通钢总经','0','1447683995','1447683995'),('787','偷電器','0','1447683995','1447683995'),('788','偷肃贪','0','1447683995','1447683995'),('789','偷听器','0','1447683995','1447683995'),('790','偷偷贪','0','1447683995','1447683995'),('791','头双管','0','1447683995','1447683995'),('792','透视功能','0','1447683995','1447683995'),('793','透视镜','0','1447683995','1447683995'),('794','透视扑','0','1447683995','1447683995'),('795','透视器','0','1447683995','1447683995'),('796','透视眼镜','0','1447683995','1447683995'),('797','透视药','0','1447683995','1447683995'),('798','透视仪','0','1447683995','1447683995'),('799','秃鹰汽','0','1447683995','1447683995'),('800','突破封锁','0','1447683995','1447683995'),('801','突破网路','0','1447683995','1447683995'),('802','推油按','0','1447683995','1447683995'),('803','脱衣艳','0','1447683995','1447683995'),('804','瓦斯手','0','1447683995','1447683995'),('805','袜按摩','0','1447683995','1447683995'),('806','外透视镜','0','1447683995','1447683995'),('807','外围赌球','0','1447683995','1447683995'),('808','湾版假','0','1447683995','1447683995'),('809','万能钥匙','0','1447683995','1447683995'),('810','万人骚动','0','1447683995','1447683995'),('811','王立军','0','1447683995','1447683995'),('812','王益案','0','1447683995','1447683995'),('813','网民案','0','1447683995','1447683995'),('814','网民获刑','0','1447683995','1447683995'),('815','网民诬','0','1447683995','1447683995'),('816','微型摄像','0','1447683995','1447683995'),('817','围攻警','0','1447683995','1447683995'),('818','围攻上海','0','1447683995','1447683995'),('819','维汉员','0','1447683995','1447683995'),('820','维权基','0','1447683995','1447683995'),('821','维权人','0','1447683995','1447683995'),('822','维权谈','0','1447683995','1447683995'),('823','委坐船','0','1447683995','1447683995'),('824','谓的和谐','0','1447683995','1447683995'),('825','温家堡','0','1447683995','1447683995'),('826','温切斯特','0','1447683995','1447683995'),('827','温影帝','0','1447683995','1447683995'),('828','溫家寶','0','1447683995','1447683995'),('829','瘟加饱','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('830','瘟假饱','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('831','文凭证','0','1447683995','1447683995'),('832','文强','0','1447683995','1447683995'),('833','纹了毛','0','1447683995','1447683995'),('834','闻被控制','0','1447683995','1447683995'),('835','闻封锁','0','1447683995','1447683995'),('836','瓮安','0','1447683995','1447683995'),('837','我的西域','0','1447683995','1447683995'),('838','我搞台独','0','1447683995','1447683995'),('839','乌蝇水','0','1447683995','1447683995'),('840','无耻语录','0','1447683995','1447683995'),('841','无码专','0','1447683995','1447683995'),('842','五套功','0','1447683995','1447683995'),('843','五月天','0','1447683995','1447683995'),('844','午夜电','0','1447683995','1447683995'),('845','午夜极','0','1447683995','1447683995'),('846','武警暴','0','1447683995','1447683995'),('847','武警殴','0','1447683995','1447683995'),('848','武警已增','0','1447683995','1447683995'),('849','务员答案','0','1447683995','1447683995'),('850','务员考试','0','1447683995','1447683995'),('851','雾型迷','0','1447683995','1447683995'),('852','西藏限','0','1447683995','1447683995'),('853','西服进去','0','1447683995','1447683995'),('854','希脏','0','1447683995','1447683995'),('855','习进平','0','1447683995','1447683995'),('856','习晋平','0','1447683995','1447683995'),('857','席复活','0','1447683995','1447683995'),('858','席临终前','0','1447683995','1447683995'),('859','席指着护','0','1447683995','1447683995'),('860','洗澡死','0','1447683995','1447683995'),('861','喜贪赃','0','1447683995','1447683995'),('862','先烈纷纷','0','1447683995','1447683995'),('863','现大地震','0','1447683995','1447683995'),('864','现金投注','0','1447683995','1447683995'),('865','线透视镜','0','1447683995','1447683995'),('866','限制言','0','1447683995','1447683995'),('867','陷害案','0','1447683995','1447683995'),('868','陷害罪','0','1447683995','1447683995'),('869','相自首','0','1447683995','1447683995'),('870','香港论坛','0','1447683995','1447683995'),('871','香港马会','0','1447683995','1447683995'),('872','香港一类','0','1447683995','1447683995'),('873','香港总彩','0','1447683995','1447683995'),('874','硝化甘','0','1447683995','1447683995'),('875','小穴','0','1447683995','1447683995'),('876','校骚乱','0','1447683995','1447683995'),('877','协晃悠','0','1447683995','1447683995'),('878','写两会','0','1447683995','1447683995'),('879','泄漏的内','0','1447683995','1447683995'),('880','新建户','0','1447683995','1447683995'),('881','新疆叛','0','1447683995','1447683995'),('882','新疆限','0','1447683995','1447683995'),('883','新金瓶','0','1447683995','1447683995'),('884','新唐人','0','1447683995','1447683995'),('885','信访专班','0','1447683995','1447683995'),('886','信接收器','0','1447683995','1447683995'),('887','兴中心幼','0','1447683995','1447683995'),('888','星上门','0','1447683995','1447683995'),('889','行长王益','0','1447683995','1447683995'),('890','形透视镜','0','1447683995','1447683995'),('891','型手枪','0','1447683995','1447683995'),('892','姓忽悠','0','1447683995','1447683995'),('893','幸运码','0','1447683995','1447683995'),('894','性爱日','0','1447683995','1447683995'),('895','性福情','0','1447683995','1447683995'),('896','性感少','0','1447683995','1447683995'),('897','性推广歌','0','1447683995','1447683995'),('898','胸主席','0','1447683995','1447683995'),('899','徐玉元','0','1447683995','1447683995'),('900','学骚乱','0','1447683995','1447683995'),('901','学位證','0','1447683995','1447683995'),('902','學生妹','0','1447683995','1447683995'),('903','丫与王益','0','1447683995','1447683995'),('904','烟感器','0','1447683995','1447683995'),('905','严晓玲','0','1447683995','1447683995'),('906','言被劳教','0','1447683995','1447683995'),('907','言论罪','0','1447683995','1447683995'),('908','盐酸曲','0','1447683995','1447683995'),('909','颜射','0','1447683995','1447683995'),('910','恙虫病','0','1447683995','1447683995'),('911','姚明进去','0','1447683995','1447683995'),('912','要人权','0','1447683995','1447683995'),('913','要射精了','0','1447683995','1447683995'),('914','要射了','0','1447683995','1447683995'),('915','要泄了','0','1447683995','1447683995'),('916','夜激情','0','1447683995','1447683995'),('917','液体炸','0','1447683995','1447683995'),('918','一小撮别','0','1447683995','1447683995'),('919','遗情书','0','1447683995','1447683995'),('920','蚁力神','0','1447683995','1447683995'),('921','益关注组','0','1447683995','1447683995'),('922','益受贿','0','1447683995','1447683995'),('923','阴间来电','0','1447683995','1447683995'),('924','陰唇','0','1447683995','1447683995'),('925','陰道','0','1447683995','1447683995'),('926','陰戶','0','1447683995','1447683995'),('927','淫魔舞','0','1447683995','1447683995'),('928','淫情女','0','1447683995','1447683995'),('929','淫肉','0','1447683995','1447683995'),('930','淫騷妹','0','1447683995','1447683995'),('931','淫兽','0','1447683995','1447683995'),('932','淫兽学','0','1447683995','1447683995'),('933','淫水','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('934','淫穴','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('935','隐形耳','0','1447683995','1447683995'),('936','隐形喷剂','0','1447683995','1447683995'),('937','应子弹','0','1447683995','1447683995'),('938','婴儿命','0','1447683995','1447683995'),('939','咏妓','0','1447683995','1447683995'),('940','用手枪','0','1447683995','1447683995'),('941','幽谷三','0','1447683995','1447683995'),('942','游精佑','0','1447683995','1447683995'),('943','有奶不一','0','1447683995','1447683995'),('944','右转是政','0','1447683995','1447683995'),('945','幼齿类','0','1447683995','1447683995'),('946','娱乐透视','0','1447683995','1447683995'),('947','愚民同','0','1447683995','1447683995'),('948','愚民政','0','1447683995','1447683995'),('949','与狗性','0','1447683995','1447683995'),('950','玉蒲团','0','1447683995','1447683995'),('951','育部女官','0','1447683995','1447683995'),('952','冤民大','0','1447683995','1447683995'),('953','鸳鸯洗','0','1447683995','1447683995'),('954','园惨案','0','1447683995','1447683995'),('955','园发生砍','0','1447683995','1447683995'),('956','园砍杀','0','1447683995','1447683995'),('957','园凶杀','0','1447683995','1447683995'),('958','园血案','0','1447683995','1447683995'),('959','原一九五七','0','1447683995','1447683995'),('960','原装弹','0','1447683995','1447683995'),('961','袁腾飞','0','1447683995','1447683995'),('962','晕倒型','0','1447683995','1447683995'),('963','韵徐娘','0','1447683995','1447683995'),('964','遭便衣','0','1447683995','1447683995'),('965','遭到警','0','1447683995','1447683995'),('966','遭警察','0','1447683995','1447683995'),('967','遭武警','0','1447683995','1447683995'),('968','择油录','0','1447683995','1447683995'),('969','曾道人','0','1447683995','1447683995'),('970','炸弹教','0','1447683995','1447683995'),('971','炸弹遥控','0','1447683995','1447683995'),('972','炸广州','0','1447683995','1447683995'),('973','炸立交','0','1447683995','1447683995'),('974','炸药的制','0','1447683995','1447683995'),('975','炸药配','0','1447683995','1447683995'),('976','炸药制','0','1447683995','1447683995'),('977','张春桥','0','1447683995','1447683995'),('978','找枪手','0','1447683995','1447683995'),('979','找援交','0','1447683995','1447683995'),('980','找政法委副','0','1447683995','1447683995'),('981','赵紫阳','0','1447683995','1447683995'),('982','针刺案','0','1447683995','1447683995'),('983','针刺伤','0','1447683995','1447683995'),('984','针刺事','0','1447683995','1447683995'),('985','针刺死','0','1447683995','1447683995'),('986','侦探设备','0','1447683995','1447683995'),('987','真钱斗地','0','1447683995','1447683995'),('988','真钱投注真善忍','0','1447683995','1447683995'),('989','真实文凭','0','1447683995','1447683995'),('990','真实资格','0','1447683995','1447683995'),('991','震惊一个民','0','1447683995','1447683995'),('992','震其国土','0','1447683995','1447683995'),('993','证到付款','0','1447683995','1447683995'),('994','证件办','0','1447683995','1447683995'),('995','证件集团','0','1447683995','1447683995'),('996','证生成器','0','1447683995','1447683995'),('997','证书办','0','1447683995','1447683995'),('998','证一次性','0','1447683995','1447683995'),('999','政府操','0','1447683995','1447683995'),('1000','政论区','0','1447683995','1447683995'),('1001','證件','0','1447683995','1447683995'),('1002','植物冰','0','1447683995','1447683995'),('1003','殖器护','0','1447683995','1447683995'),('1004','指纹考勤','0','1447683995','1447683995'),('1005','指纹膜','0','1447683995','1447683995'),('1006','指纹套','0','1447683995','1447683995'),('1007','至国家高','0','1447683995','1447683995'),('1008','志不愿跟','0','1447683995','1447683995'),('1009','制服诱','0','1447683995','1447683995'),('1010','制手枪','0','1447683995','1447683995'),('1011','制证定金','0','1447683995','1447683995'),('1012','制作证件','0','1447683995','1447683995'),('1013','中的班禅','0','1447683995','1447683995'),('1014','中共黑','0','1447683995','1447683995'),('1015','中国不强','0','1447683995','1447683995'),('1016','种公务员','0','1447683995','1447683995'),('1017','种学历证','0','1447683995','1447683995'),('1018','众像羔','0','1447683995','1447683995'),('1019','州惨案','0','1447683995','1447683995'),('1020','州大批贪','0','1447683995','1447683995'),('1021','州三箭','0','1447683995','1447683995'),('1022','宙最高法','0','1447683995','1447683995'),('1023','昼将近','0','1447683995','1447683995'),('1024','主席忏','0','1447683995','1447683995'),('1025','住英国房','0','1447683995','1447683995'),('1026','助考','0','1447683995','1447683995'),('1027','助考网','0','1447683995','1447683995'),('1028','专业办理','0','1447683995','1447683995'),('1029','专业代','0','1447683995','1447683995'),('1030','专业代写','0','1447683995','1447683995'),('1031','专业助','0','1447683995','1447683995'),('1032','转是政府','0','1447683995','1447683995'),('1033','赚钱资料','0','1447683995','1447683995'),('1034','装弹甲','0','1447683995','1447683995'),('1035','装枪套','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('1036','装消音','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 插入数据 `vifnn_susceptible` */
 INSERT INTO `vifnn_susceptible` VALUES ('1037','着护士的胸','0','1447683995','1447683995'),('1038','着涛哥','0','1447683995','1447683995'),('1039','姿不对死','0','1447683995','1447683995'),('1040','资格證','0','1447683995','1447683995'),('1041','资料泄','0','1447683995','1447683995'),('1042','梓健特药','0','1447683995','1447683995'),('1043','字牌汽','0','1447683995','1447683995'),('1044','自己找枪','0','1447683995','1447683995'),('1045','自慰用','0','1447683995','1447683995'),('1046','自由圣','0','1447683995','1447683995'),('1047','自由亚','0','1447683995','1447683995'),('1048','总会美女','0','1447683995','1447683995'),('1049','足球玩法','0','1447683995','1447683995'),('1050','最牛公安','0','1447683995','1447683995'),('1051','醉钢枪','0','1447683995','1447683995'),('1052','醉迷药','0','1447683995','1447683995'),('1053','醉乙醚','0','1447683995','1447683995'),('1054','尊爵粉','0','1447683995','1447683995'),('1055','左转是政','0','1447683995','1447683995'),('1056','作弊器','0','1447683995','1447683995'),('1057','作各种证','0','1447683995','1447683995'),('1058','作硝化甘','0','1447683995','1447683995'),('1059','唑仑','0','1447683995','1447683995'),('1060','做爱小','0','1447683995','1447683995'),('1061','做原子弹','0','1447683995','1447683995'),('1062','做证件','0','1447683995','1447683995');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_system_info` */
 DROP TABLE IF EXISTS `vifnn_system_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_system_info` (
  `lastsqlupdate` int(10) NOT NULL,
  `version` varchar(10) NOT NULL,
  `currentfileid` varchar(40) NOT NULL DEFAULT '',
  `currentsqlid` varchar(40) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_tag` */
 DROP TABLE IF EXISTS `vifnn_tag`;/* MySQLReback Separation */ CREATE TABLE `vifnn_tag` (
  `tagid_int` int(11) NOT NULL,
  `userid_int` int(11) NOT NULL DEFAULT '0',
  `name_varchar` varchar(50) DEFAULT NULL,
  `type_int` int(11) DEFAULT NULL COMMENT '背景还是图片0背景,1图片,2音乐,88样例,99用户',
  `biztype_int` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`tagid_int`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_taobao` */
 DROP TABLE IF EXISTS `vifnn_taobao`;/* MySQLReback Separation */ CREATE TABLE `vifnn_taobao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(64) NOT NULL,
  `keyword` varchar(200) NOT NULL,
  `title` varchar(100) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `homeurl` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_tempmsg` */
 DROP TABLE IF EXISTS `vifnn_tempmsg`;/* MySQLReback Separation */ CREATE TABLE `vifnn_tempmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tempkey` char(50) NOT NULL,
  `name` char(100) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `industry` char(50) NOT NULL,
  `topcolor` char(10) NOT NULL DEFAULT '#029700',
  `textcolor` char(10) NOT NULL DEFAULT '#000000',
  `token` char(40) NOT NULL,
  `tempid` char(100) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '模板类型（0：系统自带的，1：自己增加的）',
  PRIMARY KEY (`id`),
  KEY `tempkey` (`tempkey`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_tempmsg` */
 INSERT INTO `vifnn_tempmsg` VALUES ('1','TM00130','预约看房通知','{{first.DATA}}
预约楼盘：{{apartmentName.DATA}}
房号：{{roomNumber.DATA}}
楼盘地址：{{address.DATA}}
预约时间：{{time.DATA}}
{{remark.DATA}}','','#029700','#000000','kpktkq1416817563','','0','0'),('2','TM00785','开奖结果通知','
{{first.DATA}}
开奖项目：{{program.DATA}}
中奖详情：{{result.DATA}}
{{remark.DATA}}','','#029700','#000000','kpktkq1416817563','','0','0'),('3','TM00820','服务完成通知','
{{first.DATA}}
完成情况：{{keynote1.DATA}}
完成日期：{{keynote2.DATA}}
{{remark.DATA}}','','#029700','#000000','kpktkq1416817563','','0','0'),('4','OPENTM203605740','预约看房提醒','{{first.DATA}}
看房时间：{{keyword1.DATA}}
房屋地址：{{keyword2.DATA}}
房屋信息：{{keyword3.DATA}}
客服电话：{{keyword4.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('5','TM00695','中奖结果通知','
{{title.DATA}}	
{{headinfo.DATA}}
彩票名称：{{program.DATA}}
开奖结果：{{result.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('6','TM00499','服务完成通知','
{{first.DATA}}
{{Content1.DATA}}
商品名称：{{Good.DATA}}
服务措施：{{contentType.DATA}}
收费金额：{{price.DATA}}
收费标准：{{menu.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('7','TM00459','预订成功','
{{first.DATA}}
商户：{{keynote1.DATA}}
时间：{{keynote2.DATA}}
人数：{{keynote3.DATA}}
类型：{{keynote4.DATA}}
{{remark.DATA}}   ','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('8','OPENTM202183094','商品支付成功通知','
{{first.DATA}}
付款金额：{{keyword1.DATA}}
商品详情：{{keyword2.DATA}}
支付方式：{{keyword3.DATA}}
交易单号：{{keyword4.DATA}}
交易时间：{{keyword5.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('9','TM00009','会员充值通知','
{{first.DATA}}
{{accountType.DATA}}:{{account.DATA}}
充值金额：{{amount.DATA}}
充值状态：{{result.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('10','TM00017','订单状态更新','
{{first.DATA}}
订单编号: {{OrderSn.DATA}}
订单状态: {{OrderStatus.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('11','OPENTM202521011','订单完成通知','
{{first.DATA}}
订单号：{{keyword1.DATA}}
完成时间：{{keyword2.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('12','TM00184','订单未支付通知','
{{first.DATA}}
下单时间：{{ordertape.DATA}}
订单号：{{ordeID.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('13','OPENTM200681790','领取红包通知','
{{first.DATA}}
成功领取：{{keyword1.DATA}}
红包金额：{{keyword2.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('14','OPENTM200565259','订单发货提醒','
{{first.DATA}}
订单编号：{{keyword1.DATA}}
物流公司：{{keyword2.DATA}}
物流单号：{{keyword3.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('15','OPENTM200869995','排队提醒通知','
{{first.DATA}}
队列号：{{keyword1.DATA}}
取号时间：{{keyword2.DATA}}
排队时长：{{keyword3.DATA}}
等待人数：{{keyword4.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('16','OPENTM201812627','佣金提醒','
{{first.DATA}}
佣金金额：{{keyword1.DATA}}
时间：{{keyword2.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','zrtmca1421056092','','0','0'),('17','OPENTM203605740','预约看房提醒','{{first.DATA}}
看房时间：{{keyword1.DATA}}
房屋地址：{{keyword2.DATA}}
房屋信息：{{keyword3.DATA}}
客服电话：{{keyword4.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('18','TM00695','中奖结果通知','
{{title.DATA}}	
{{headinfo.DATA}}
彩票名称：{{program.DATA}}
开奖结果：{{result.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('19','TM00499','服务完成通知','
{{first.DATA}}
{{Content1.DATA}}
商品名称：{{Good.DATA}}
服务措施：{{contentType.DATA}}
收费金额：{{price.DATA}}
收费标准：{{menu.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0');/* MySQLReback Separation */
 /* 插入数据 `vifnn_tempmsg` */
 INSERT INTO `vifnn_tempmsg` VALUES ('20','TM00459','预订成功','
{{first.DATA}}
商户：{{keynote1.DATA}}
时间：{{keynote2.DATA}}
人数：{{keynote3.DATA}}
类型：{{keynote4.DATA}}
{{remark.DATA}}   ','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0');/* MySQLReback Separation */
 /* 插入数据 `vifnn_tempmsg` */
 INSERT INTO `vifnn_tempmsg` VALUES ('21','OPENTM202183094','商品支付成功通知','
{{first.DATA}}
付款金额：{{keyword1.DATA}}
商品详情：{{keyword2.DATA}}
支付方式：{{keyword3.DATA}}
交易单号：{{keyword4.DATA}}
交易时间：{{keyword5.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('22','TM00009','会员充值通知','
{{first.DATA}}
{{accountType.DATA}}:{{account.DATA}}
充值金额：{{amount.DATA}}
充值状态：{{result.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('23','TM00017','订单状态更新','
{{first.DATA}}
订单编号: {{OrderSn.DATA}}
订单状态: {{OrderStatus.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('24','OPENTM202521011','订单完成通知','
{{first.DATA}}
订单号：{{keyword1.DATA}}
完成时间：{{keyword2.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('25','TM00184','订单未支付通知','
{{first.DATA}}
下单时间：{{ordertape.DATA}}
订单号：{{ordeID.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('26','OPENTM200681790','领取红包通知','
{{first.DATA}}
成功领取：{{keyword1.DATA}}
红包金额：{{keyword2.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('27','OPENTM200565259','订单发货提醒','
{{first.DATA}}
订单编号：{{keyword1.DATA}}
物流公司：{{keyword2.DATA}}
物流单号：{{keyword3.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('28','OPENTM200869995','排队提醒通知','
{{first.DATA}}
队列号：{{keyword1.DATA}}
取号时间：{{keyword2.DATA}}
排队时长：{{keyword3.DATA}}
等待人数：{{keyword4.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('29','OPENTM201812627','佣金提醒','
{{first.DATA}}
佣金金额：{{keyword1.DATA}}
时间：{{keyword2.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','qcjnep1444636005','','0','0'),('30','OPENTM203605740','预约看房提醒','{{first.DATA}}
看房时间：{{keyword1.DATA}}
房屋地址：{{keyword2.DATA}}
房屋信息：{{keyword3.DATA}}
客服电话：{{keyword4.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0'),('31','TM00695','中奖结果通知','
{{title.DATA}}	
{{headinfo.DATA}}
彩票名称：{{program.DATA}}
开奖结果：{{result.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0'),('32','TM00499','服务完成通知','
{{first.DATA}}
{{Content1.DATA}}
商品名称：{{Good.DATA}}
服务措施：{{contentType.DATA}}
收费金额：{{price.DATA}}
收费标准：{{menu.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0'),('33','TM00459','预订成功','
{{first.DATA}}
商户：{{keynote1.DATA}}
时间：{{keynote2.DATA}}
人数：{{keynote3.DATA}}
类型：{{keynote4.DATA}}
{{remark.DATA}}   ','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0'),('34','OPENTM202183094','商品支付成功通知','
{{first.DATA}}
付款金额：{{keyword1.DATA}}
商品详情：{{keyword2.DATA}}
支付方式：{{keyword3.DATA}}
交易单号：{{keyword4.DATA}}
交易时间：{{keyword5.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0'),('35','TM00009','会员充值通知','
{{first.DATA}}
{{accountType.DATA}}:{{account.DATA}}
充值金额：{{amount.DATA}}
充值状态：{{result.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0'),('36','TM00017','订单状态更新','
{{first.DATA}}
订单编号: {{OrderSn.DATA}}
订单状态: {{OrderStatus.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0'),('37','OPENTM202521011','订单完成通知','
{{first.DATA}}
订单号：{{keyword1.DATA}}
完成时间：{{keyword2.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0'),('38','TM00184','订单未支付通知','
{{first.DATA}}
下单时间：{{ordertape.DATA}}
订单号：{{ordeID.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0'),('39','OPENTM200681790','领取红包通知','
{{first.DATA}}
成功领取：{{keyword1.DATA}}
红包金额：{{keyword2.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0');/* MySQLReback Separation */
 /* 插入数据 `vifnn_tempmsg` */
 INSERT INTO `vifnn_tempmsg` VALUES ('40','OPENTM200565259','订单发货提醒','
{{first.DATA}}
订单编号：{{keyword1.DATA}}
物流公司：{{keyword2.DATA}}
物流单号：{{keyword3.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0');/* MySQLReback Separation */
 /* 插入数据 `vifnn_tempmsg` */
 INSERT INTO `vifnn_tempmsg` VALUES ('41','OPENTM200869995','排队提醒通知','
{{first.DATA}}
队列号：{{keyword1.DATA}}
取号时间：{{keyword2.DATA}}
排队时长：{{keyword3.DATA}}
等待人数：{{keyword4.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0'),('42','OPENTM201812627','佣金提醒','
{{first.DATA}}
佣金金额：{{keyword1.DATA}}
时间：{{keyword2.DATA}}
{{remark.DATA}}','IT科技_互联网|电子商务','#029700','#000000','ezrehr1458789061','','0','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_test` */
 DROP TABLE IF EXISTS `vifnn_test`;/* MySQLReback Separation */ CREATE TABLE `vifnn_test` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `wxtitle` varchar(100) NOT NULL,
  `wxpic` varchar(100) NOT NULL,
  `wxinfo` varchar(100) DEFAULT NULL,
  `indexpic` varchar(100) DEFAULT NULL,
  `questionpic` varchar(100) DEFAULT NULL,
  `bgcolor` varchar(100) NOT NULL DEFAULT 'ffcb1d',
  `title` varchar(100) NOT NULL,
  `qtitle` varchar(100) NOT NULL,
  `fistq` varchar(100) NOT NULL,
  `fistapic` varchar(100) NOT NULL,
  `fistatitle` varchar(100) NOT NULL,
  `fistatitle2` varchar(100) NOT NULL,
  `fistainfo` varchar(200) DEFAULT NULL,
  `secondq` varchar(100) NOT NULL,
  `secondapic` varchar(100) NOT NULL,
  `secondatitle` varchar(100) NOT NULL,
  `secondatitle2` varchar(100) NOT NULL,
  `secondainfo` varchar(200) DEFAULT NULL,
  `thirdq` varchar(100) DEFAULT NULL,
  `thirdapic` varchar(100) DEFAULT NULL,
  `thirdatitle` varchar(100) DEFAULT NULL,
  `thirdatitle2` varchar(100) DEFAULT NULL,
  `thirdainfo` varchar(200) DEFAULT NULL,
  `fourq` varchar(100) DEFAULT NULL,
  `fourapic` varchar(100) DEFAULT NULL,
  `fouratitle` varchar(100) DEFAULT NULL,
  `fouratitle2` varchar(100) DEFAULT NULL,
  `fourainfo` varchar(200) DEFAULT NULL,
  `fiveq` varchar(100) DEFAULT NULL,
  `fiveapic` varchar(100) DEFAULT NULL,
  `fiveatitle` varchar(100) DEFAULT NULL,
  `fiveatitle2` varchar(100) DEFAULT NULL,
  `fiveainfo` varchar(200) DEFAULT NULL,
  `pv` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `fistfx` varchar(200) DEFAULT NULL,
  `secondfx` varchar(200) DEFAULT NULL,
  `thirdfx` varchar(200) DEFAULT NULL,
  `fourfx` varchar(200) DEFAULT NULL,
  `fivefx` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_test_user` */
 DROP TABLE IF EXISTS `vifnn_test_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_test_user` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `testid` int(11) NOT NULL,
  `testtype` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_text` */
 DROP TABLE IF EXISTS `vifnn_text`;/* MySQLReback Separation */ CREATE TABLE `vifnn_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uname` varchar(90) NOT NULL,
  `keyword` char(255) NOT NULL,
  `precisions` tinyint(1) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `updatetime` varchar(13) NOT NULL,
  `click` int(11) NOT NULL,
  `token` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_token_open` */
 DROP TABLE IF EXISTS `vifnn_token_open`;/* MySQLReback Separation */ CREATE TABLE `vifnn_token_open` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `queryname` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_token_open` */
 INSERT INTO `vifnn_token_open` VALUES ('26','2','zrtmca1421056092','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,Paper,taobao,userinfo,fanyi,api,suanming,baike,caipiao,Zhaopianwall,RippleOS,shouye,adma,huiyuanka,Vcard,usernameCheck,geci,whois,host_kev,diyform,dx,shop,etuan,diymen_set,choujiang,lottery,gua2,panorama,Wedding,Vote,estate,album,GoldenEgg,LuckyFruit,messageboard,car,wall,shake,forum,medical,groupmessage,share,hotel,school,Live,Jiejing,Autumn,Lovers,AppleGame,Research,Problem,Signin,Scene,Market,Custom,Greeting_card,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Gamecenter,Zhida,Red_packet,Punish,Invite,Autumns,Baoming,Scenes,Fenlei,Jikedati,Zhaopin,Fangchan,Shakeprize,Hforward,Sharetalent,Message,AdvanceTpl,Hcar,Knwx,Jingcai,Wzqemail,Kefu,Weizhuli,Lapiao,Wzz,Yingyong,Fangyan'),('27','2','qcjnep1444636005','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,lottery,fanyi,api,suanming,baike,caipiao,choujiang,shouye,adma,huiyuanka,host_kev,geci,whois,diyform,dx,shop,etuan,diymen_set,gua2,panorama,wedding,vote,estate,album,GoldenEgg,LuckyFruit,messageboard,car,wall,shake,forum,medical,groupmessage,share,hotel,school,Autumn,Lovers,AppleGame,Live,Research,Problem,Signin,Scene,Market,Custom,Greeting_card,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Gamecenter,Zhida,Red_packet,Punish,Invite,Autumns,Jiugong,MicroBroker,Helping,Popularity,Unitary,Phone,website,DishOut,LivingCircle,Test,Bargain,Crowdfunding,SeniorScene,Seckill,Service,Hongbao,Weixin,Card,Fuwu,Voteimg,Micrstore,Shakearound,Cutprice,Sentiment,Person_card,ServiceUser,Numqueue,RecognitionData,CustomTmpls,Yundabao,usernameCheck,Assistente,CoinTree,FrontPage,Collectword,RippleOS,Vifnnemail,Jiejing'),('28','2','zcbtlh1444656817','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,lottery,fanyi,api,suanming,baike,caipiao,choujiang,shouye,adma,huiyuanka,host_kev,geci,whois,diyform,dx,shop,etuan,diymen_set,gua2,panorama,wedding,vote,estate,album,GoldenEgg,LuckyFruit,messageboard,car,wall,shake,forum,medical,groupmessage,share,hotel,school,Autumn,Lovers,AppleGame,Live,Research,Problem,Signin,Scene,Market,Custom,Greeting_card,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Gamecenter,Zhida,Red_packet,Punish,Invite,Autumns,Jiugong,MicroBroker,Helping,Popularity,Unitary,Phone,website,DishOut,LivingCircle,Test,Bargain,Crowdfunding,SeniorScene,Seckill,Service,Hongbao,Weixin,Card,Fuwu,Voteimg,Micrstore,Shakearound,Cutprice,Sentiment,Person_card,ServiceUser,Numqueue,RecognitionData,CustomTmpls,Yundabao,usernameCheck,Assistente,CoinTree,FrontPage,Collectword,RippleOS,Vifnnemail,Jiejing,Baoming,Jingcai,Jikedati'),('29','3','ezrehr1458789061','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,lottery,fanyi,api,suanming,baike,caipiao,choujiang,shouye,adma,huiyuanka,host_kev,geci,whois,diyform,dx,shop,etuan,diymen_set,gua2,panorama,wedding,vote,estate,album,GoldenEgg,LuckyFruit,messageboard,car,wall,shake,forum,medical,groupmessage,share,hotel,school,Autumn,Lovers,AppleGame,Live,Research,Problem,Signin,Scene,Market,Custom,Greeting_card,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Gamecenter,Zhida,Red_packet,Punish,Invite,Autumns,Jiugong,MicroBroker,Helping,Popularity,Unitary,Phone,Fangchan,DishOut,LivingCircle,Test,Bargain,Crowdfunding,Baoming,Seckill,Service,Hongbao,Weixin,Card,Fuwu,Voteimg,Micrstore,Shakearound,Cutprice,Sentiment,Person_card,ServiceUser,Numqueue,RecognitionData,CustomTmpls,Zhaopin,usernameCheck,Assistente,CoinTree,FrontPage,Collectword,RippleOS,Vifnnemail,Jiejing,Jikedati,Jingcai,Fangyan,Pengyouquanad,Auction,Aaactivity,Weizhaohuan,Dajiangsai,Cashbonus,Cashier,Donation');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_toshake` */
 DROP TABLE IF EXISTS `vifnn_toshake`;/* MySQLReback Separation */ CREATE TABLE `vifnn_toshake` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `phone` varchar(15) NOT NULL,
  `token` varchar(20) NOT NULL,
  `wecha_id` varchar(30) DEFAULT NULL,
  `point` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_twitter_count` */
 DROP TABLE IF EXISTS `vifnn_twitter_count`;/* MySQLReback Separation */ CREATE TABLE `vifnn_twitter_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `twid` varchar(20) NOT NULL,
  `token` varchar(60) NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `total` float NOT NULL COMMENT '总金额',
  `remove` float NOT NULL COMMENT '提出的金额',
  PRIMARY KEY (`id`),
  KEY `twid` (`twid`),
  KEY `token` (`token`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_twitter_log` */
 DROP TABLE IF EXISTS `vifnn_twitter_log`;/* MySQLReback Separation */ CREATE TABLE `vifnn_twitter_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(60) NOT NULL,
  `twid` varchar(20) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `price` float NOT NULL,
  `fromsource` varchar(60) NOT NULL COMMENT '来自源',
  `param` float NOT NULL,
  `wecha_id` varchar(64) NOT NULL COMMENT '操作人，即操作了推广人推广的产品',
  `info` varchar(500) NOT NULL COMMENT '推广的详情',
  PRIMARY KEY (`id`),
  KEY `twid` (`twid`),
  KEY `token` (`token`),
  KEY `fromsource` (`fromsource`),
  KEY `cid` (`cid`),
  KEY `wecha_id` (`wecha_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_twitter_remove` */
 DROP TABLE IF EXISTS `vifnn_twitter_remove`;/* MySQLReback Separation */ CREATE TABLE `vifnn_twitter_remove` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(60) NOT NULL,
  `twid` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '提款人姓名',
  `tel` varchar(15) NOT NULL,
  `number` varchar(32) NOT NULL COMMENT '收款账号',
  `bank` varchar(30) NOT NULL COMMENT '银行名称',
  `address` varchar(60) NOT NULL COMMENT '开户行地址',
  `price` float NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_twitter_set` */
 DROP TABLE IF EXISTS `vifnn_twitter_set`;/* MySQLReback Separation */ CREATE TABLE `vifnn_twitter_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(60) NOT NULL,
  `clickprice` float NOT NULL COMMENT '点击的价格',
  `clickmax` int(10) unsigned NOT NULL COMMENT '每天点击的上限',
  `registerprice` float NOT NULL COMMENT '注册的价格',
  `registermax` int(10) unsigned NOT NULL COMMENT '每天注册的上限',
  `percent` float NOT NULL COMMENT '商品总价的百分比',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_twitter_user` */
 DROP TABLE IF EXISTS `vifnn_twitter_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_twitter_user` (
  `id` int(11) NOT NULL,
  `agentid` int(10) NOT NULL,
  `uid` int(11) NOT NULL,
  `wxname` varchar(60) NOT NULL,
  `token` varchar(255) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `updatetime` varchar(13) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_unitary` */
 DROP TABLE IF EXISTS `vifnn_unitary`;/* MySQLReback Separation */ CREATE TABLE `vifnn_unitary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) DEFAULT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL COMMENT '名称/微信中图文信息标题',
  `wxpic` varchar(100) DEFAULT NULL COMMENT '微信中图文信息图片',
  `wxinfo` varchar(100) DEFAULT NULL COMMENT '微信中图文信息说明',
  `wxregister` int(11) NOT NULL DEFAULT '1' COMMENT '关注/注册',
  `register` int(11) NOT NULL DEFAULT '0' COMMENT '注册/不注册',
  `price` int(11) DEFAULT NULL COMMENT '价格',
  `type` int(11) DEFAULT NULL COMMENT '分类',
  `logopic` varchar(100) DEFAULT NULL COMMENT 'logo图片',
  `fistpic` varchar(100) DEFAULT NULL COMMENT '展示图片1',
  `secondpic` varchar(100) DEFAULT NULL COMMENT '展示图片2',
  `thirdpic` varchar(100) DEFAULT NULL COMMENT '展示图片3',
  `fourpic` varchar(100) DEFAULT NULL COMMENT '展示图片4',
  `fivepic` varchar(100) DEFAULT NULL COMMENT '展示图片5',
  `sixpic` varchar(100) DEFAULT NULL COMMENT '展示图片6',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  `opentime` int(11) DEFAULT NULL COMMENT '结束后展示结果倒计时',
  `endtime` int(11) DEFAULT NULL COMMENT '结束时间',
  `state` int(11) DEFAULT NULL COMMENT '活动开关',
  `renqi` int(11) NOT NULL DEFAULT '0' COMMENT '人气',
  `lucknum` int(11) DEFAULT NULL COMMENT '幸运数字',
  `proportion` double NOT NULL DEFAULT '0',
  `lasttime` int(11) DEFAULT NULL,
  `lastnum` int(11) DEFAULT NULL,
  `introduction` varchar(255) NOT NULL,
  `shopdetails` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_unitary_cart` */
 DROP TABLE IF EXISTS `vifnn_unitary_cart`;/* MySQLReback Separation */ CREATE TABLE `vifnn_unitary_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) DEFAULT NULL,
  `wecha_id` varchar(100) DEFAULT NULL,
  `unitary_id` int(11) DEFAULT NULL COMMENT '商品id',
  `count` int(11) DEFAULT NULL COMMENT '数量',
  `state` int(11) NOT NULL DEFAULT '0' COMMENT '购买/购物车状态',
  `order_id` int(11) DEFAULT NULL COMMENT '订单id',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_unitary_lucknum` */
 DROP TABLE IF EXISTS `vifnn_unitary_lucknum`;/* MySQLReback Separation */ CREATE TABLE `vifnn_unitary_lucknum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL COMMENT '订单id',
  `token` varchar(100) DEFAULT NULL,
  `wecha_id` varchar(100) DEFAULT NULL,
  `lucknum` int(11) DEFAULT NULL,
  `addtime` double DEFAULT NULL,
  `unitary_id` int(11) DEFAULT NULL,
  `cart_id` int(11) DEFAULT NULL COMMENT '购物id',
  `state` int(11) NOT NULL DEFAULT '0',
  `paifa` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_unitary_order` */
 DROP TABLE IF EXISTS `vifnn_unitary_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_unitary_order` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) DEFAULT NULL,
  `wecha_id` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL COMMENT '总价',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  `paytype` varchar(50) DEFAULT NULL COMMENT '来自于何种支付(英文格式)',
  `paid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付，1代表已支付',
  `third_id` varchar(100) DEFAULT NULL COMMENT '第三方支付平台的订单ID，用于对帐。',
  `orderid` varchar(255) NOT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_unitary_user` */
 DROP TABLE IF EXISTS `vifnn_unitary_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_unitary_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '昵称',
  `phone` varchar(100) DEFAULT NULL COMMENT '手机号',
  `address` text COMMENT '地址',
  `token` varchar(100) DEFAULT NULL,
  `wecha_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_update_record` */
 DROP TABLE IF EXISTS `vifnn_update_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_update_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` varchar(600) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_upfile` */
 DROP TABLE IF EXISTS `vifnn_upfile`;/* MySQLReback Separation */ CREATE TABLE `vifnn_upfile` (
  `fileid_bigint` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid_int` int(11) NOT NULL,
  `filetype_int` int(11) NOT NULL DEFAULT '0' COMMENT '0背景,2音乐,1图片',
  `filesrc_varchar` varchar(200) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `sizekb_int` decimal(18,2) DEFAULT '0.00',
  `filethumbsrc_varchar` varchar(200) DEFAULT NULL,
  `biztype_int` int(11) DEFAULT '0',
  `ext_varchar` varchar(50) DEFAULT NULL,
  `filename_varchar` varchar(100) DEFAULT NULL,
  `eqsrc_varchar` varchar(200) DEFAULT NULL,
  `tagid_int` int(11) DEFAULT '0',
  `delete_int` int(11) NOT NULL DEFAULT '0' COMMENT '0正常,1删除',
  PRIMARY KEY (`fileid_bigint`),
  KEY `userid` (`userid_int`,`filetype_int`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_upfilesys` */
 DROP TABLE IF EXISTS `vifnn_upfilesys`;/* MySQLReback Separation */ CREATE TABLE `vifnn_upfilesys` (
  `fileid_bigint` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid_int` int(11) NOT NULL,
  `filetype_int` int(11) NOT NULL DEFAULT '0' COMMENT '0背景,2音乐,1图片',
  `filesrc_varchar` varchar(200) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `sizekb_int` decimal(18,2) DEFAULT '0.00',
  `filethumbsrc_varchar` varchar(200) DEFAULT NULL,
  `biztype_int` int(11) DEFAULT '0',
  `ext_varchar` varchar(50) DEFAULT NULL,
  `filename_varchar` varchar(100) DEFAULT NULL,
  `eqsrc_varchar` varchar(200) DEFAULT NULL,
  `tagid_int` int(11) DEFAULT '0',
  `eqsrcthumb_varchar` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`fileid_bigint`),
  KEY `userid` (`userid_int`,`filetype_int`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_upyun_attachement` */
 DROP TABLE IF EXISTS `vifnn_upyun_attachement`;/* MySQLReback Separation */ CREATE TABLE `vifnn_upyun_attachement` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `url` varchar(160) NOT NULL,
  `code` varchar(10) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_user` */
 DROP TABLE IF EXISTS `vifnn_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `role` smallint(6) unsigned NOT NULL COMMENT '组ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1:启用 0:禁止',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `last_login_time` int(11) unsigned NOT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(15) DEFAULT NULL COMMENT '最后登录IP',
  `last_location` varchar(100) DEFAULT NULL COMMENT '最后登录位置',
  `is_admin` int(11) NOT NULL DEFAULT '0',
  `free_time` int(11) NOT NULL DEFAULT '0',
  `scene_times` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_user` */
 INSERT INTO `vifnn_user` VALUES ('1','admin','09a46f982bde421200dadf0b98a44330','5','1','','1465797399','223.199.11.29','','1','0','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_user_address` */
 DROP TABLE IF EXISTS `vifnn_user_address`;/* MySQLReback Separation */ CREATE TABLE `vifnn_user_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '收货人',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` varchar(300) NOT NULL DEFAULT '' COMMENT '收货地址',
  `postcode` varchar(10) NOT NULL DEFAULT '' COMMENT '邮编',
  `default_address` char(1) NOT NULL DEFAULT '0' COMMENT '默认收货地址',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_user_group` */
 DROP TABLE IF EXISTS `vifnn_user_group`;/* MySQLReback Separation */ CREATE TABLE `vifnn_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taxisid` int(10) NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL,
  `diynum` int(11) NOT NULL,
  `connectnum` int(11) NOT NULL,
  `iscopyright` tinyint(1) NOT NULL,
  `activitynum` int(3) NOT NULL,
  `price` int(11) NOT NULL,
  `statistics_user` int(11) NOT NULL,
  `create_card_num` int(4) NOT NULL,
  `wechat_card_num` int(3) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `agentid` int(10) NOT NULL DEFAULT '0',
  `func` varchar(2000) DEFAULT NULL,
  `access_count` int(10) unsigned NOT NULL DEFAULT '0',
  `access_count_notice` varchar(200) NOT NULL DEFAULT '',
  `tmpls` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agentid` (`agentid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_user_group` */
 INSERT INTO `vifnn_user_group` VALUES ('1','1','试用版','2000','2000','0','0','0','0','10','1','1','0','','0','',''),('2','2','体验版','3000','3000','1','2','10','0','50','1','1','0','','0','',''),('3','3','增强版','40000','40000','1','4','150','0','80','2','1','0','','0','',''),('4','4','黄金版','50000','50000','1','10','200','0','10000','100','1','0','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,lottery,fanyi,api,suanming,baike,caipiao,choujiang,shouye,adma,huiyuanka,host_kev,geci,whois,diyform,dx,shop,etuan,diymen_set,gua2,panorama,wedding,vote,estate,album,GoldenEgg,LuckyFruit,messageboard,car,wall,shake,forum,medical,groupmessage,share,hotel,school,Autumn,Lovers,AppleGame,Live,Research,Problem,Signin,Scene,Market,Custom,Greeting_card,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Gamecenter,Zhida,Red_packet,Punish,Invite,Autumns,Jiugong,MicroBroker,Helping,Popularity,Unitary,Phone,Fangchan,DishOut,LivingCircle,Test,Bargain,Crowdfunding,Baoming,Seckill,Service,Hongbao,Weixin,Card,Fuwu,Voteimg,Micrstore,Shakearound,Cutprice,Sentiment,Person_card,ServiceUser,Numqueue,RecognitionData,CustomTmpls,Zhaopin,usernameCheck,Assistente,CoinTree,FrontPage,Collectword,RippleOS,Vifnnemail,Jiejing,Jikedati,Jingcai,Fangyan,Pengyouquanad,Auction,Aaactivity,Weizhaohuan,Dajiangsai,Cashbonus,Cashier,Donation','0','','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_user_request` */
 DROP TABLE IF EXISTS `vifnn_user_request`;/* MySQLReback Separation */ CREATE TABLE `vifnn_user_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(30) NOT NULL,
  `uid` varchar(32) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `msgtype` varchar(15) NOT NULL DEFAULT 'text',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `msgtype` (`msgtype`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_user_tmp_access` */
 DROP TABLE IF EXISTS `vifnn_user_tmp_access`;/* MySQLReback Separation */ CREATE TABLE `vifnn_user_tmp_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(200) DEFAULT NULL,
  `ctime` int(11) NOT NULL,
  `keyvalue` varchar(100) NOT NULL DEFAULT 'text',
  `type` tinyint(3) DEFAULT NULL,
  `utime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `msgtype` (`keyvalue`) USING BTREE,
  KEY `token` (`openid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_userfollow` */
 DROP TABLE IF EXISTS `vifnn_userfollow`;/* MySQLReback Separation */ CREATE TABLE `vifnn_userfollow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `followid` varchar(120) NOT NULL,
  `issub` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `followid` (`followid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_userinfo` */
 DROP TABLE IF EXISTS `vifnn_userinfo`;/* MySQLReback Separation */ CREATE TABLE `vifnn_userinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `portrait` varchar(200) NOT NULL DEFAULT '',
  `wallopen` tinyint(1) NOT NULL DEFAULT '0',
  `total_score` int(10) NOT NULL DEFAULT '0',
  `total_score_bf` int(10) NOT NULL,
  `expensetotal` int(10) NOT NULL DEFAULT '0',
  `token` varchar(60) NOT NULL,
  `wecha_id` varchar(60) NOT NULL,
  `wechaname` varchar(60) NOT NULL,
  `truename` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(11) NOT NULL,
  `bornyear` varchar(4) NOT NULL DEFAULT '',
  `bornmonth` varchar(4) NOT NULL DEFAULT '',
  `bornday` varchar(4) NOT NULL DEFAULT '',
  `qq` varchar(11) NOT NULL DEFAULT '',
  `sex` tinyint(1) NOT NULL,
  `age` varchar(3) NOT NULL DEFAULT '',
  `birthday` varchar(11) NOT NULL,
  `address` varchar(100) NOT NULL,
  `info` varchar(200) NOT NULL,
  `sign_score` int(11) NOT NULL,
  `expend_score` int(11) NOT NULL,
  `continuous` int(11) NOT NULL,
  `add_expend` int(11) NOT NULL,
  `add_expend_time` int(11) NOT NULL,
  `live_time` int(11) NOT NULL,
  `getcardtime` int(10) NOT NULL,
  `balance` double(10,2) unsigned NOT NULL DEFAULT '0.00',
  `balance_bf` decimal(10,2) NOT NULL,
  `paypass` varchar(32) DEFAULT NULL,
  `twid` varchar(20) NOT NULL COMMENT '推广号',
  `username` varchar(32) NOT NULL COMMENT '账号',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `city` varchar(40) DEFAULT NULL,
  `province` varchar(40) DEFAULT NULL,
  `store_id` int(10) DEFAULT '0',
  `drp_cart` text NOT NULL COMMENT '分销系统-用户购物车',
  `regtime` varchar(20) NOT NULL DEFAULT '' COMMENT '注册时间',
  `fakeopenid` varchar(100) NOT NULL DEFAULT '',
  `issub` tinyint(1) NOT NULL DEFAULT '0',
  `cart` varchar(20) DEFAULT '',
  `origin` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `twid` (`twid`),
  KEY `username` (`username`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `fakeopenid` (`fakeopenid`) USING BTREE,
  KEY `WECHA_NAME` (`wechaname`) USING BTREE,
  KEY `token_2` (`token`,`wecha_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_userinfo` */
 INSERT INTO `vifnn_userinfo` VALUES ('1','http://wx.qlogo.cn/mmopen/pfwWdc1JVJ1ExNESXnmh3iaR1icPdfVOWQKZaFHliacDUoOc94ZIj1yIfPFss8sThapOcVUF1PJ0r2U1ILbNicsNdIzVS237zPYN/0','0','0','0','0','ezrehr1458789061','opJiSw6wdBEB5QzJ-lgRixw50p5o','Super Junior','','','','','','','1','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','杭州','浙江','0','','','','1','',''),('2','http://wx.qlogo.cn/mmopen/USH8Nb3Hz5RhVFKHAW8ibB9Jgq2lEKzDTu8R9zOXTRyN1fTjWzMVnJjfaHpQcPc4u17aL0ricAyPKz4icVkKLmULA/0','0','0','0','0','zbemii1462891866','o4xIKt-RF3WlAO-Nu8UXeopnAlJ4','可可','','','','','','','1','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','包头','内蒙古','0','','','','1','',''),('3','http://wx.qlogo.cn/mmopen/k3aqqdcUMGZHxibpA1siaYBTzseUfBBo8gynJnFeAFaCPhfV2vAzNnyGRQ5RSyRqcz9jr7WSEXcMoOJMnliaOWRxAaiaiak31B5nV/0','0','0','0','0','zbemii1462891866','o4xIKt9E88SQR1uKlC_ZSrpyDP7c','小聪','','','','','','','1','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','鞍山','辽宁','0','','','','-1','',''),('4','http://wx.qlogo.cn/mmopen/EiczZ3VPhiafdsUp5275AX6z6sdoR0jEBicXnV6wvPFxJLosbkC9MaVpwbsbRuJZdRxSelBTs3Licv3G8jGXAqpeRel1rrkB4fms/0','0','0','0','0','zbemii1462891866','o4xIKt_u9waAoZNYlyLRRUBy42bI','勿忘心安','','','','','','','2','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','兰州市','甘肃','0','','','','1','',''),('5','http://wx.qlogo.cn/mmopen/ajNVdqHZLLAicDPa4pLo5Qys8rWz1oraMhYJDFPmTjQAPScbdOcrgQ6wLbuHqByZY1ARgVBfCjia1coAQ7ziaVMdPIbBsvOxYyibXACxpPdfrdU/0','0','0','0','0','zbemii1462891866','o4xIKt_Qiafl9-uAM_sLYtK6Edm8',' 褪色的诺言丶','','','','','','','2','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','合肥','安徽','0','','','','1','',''),('6','http://wx.qlogo.cn/mmopen/8fEasOlrlmAhbTficgzbdLwVsu8KbgrcYCoKEuAZaJic0LhTh9a9HkicnWIe7iayBIpGvWynxDlybGDd6O0Sz7FrNiceBLicEnDm79/0','0','0','0','0','zbemii1462891866','o4xIKt6j1jYYwE5X-kpX-wifmEPw','关关','','','','','','','2','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','石家庄','河北','0','','','','1','',''),('7','http://wx.qlogo.cn/mmopen/EiczZ3VPhiafdNS87RqGF84ibDibZWTxtu0nWSqCURvCatSMYtFN5HIibkMic3N7ktQElMTQkrOdia7JU67hCgAeLibRClicNV56r1tos/0','0','0','0','0','zbemii1462891866','o4xIKt_KSjRLRKosyZC81Ztpl6mY','晓晓','','','','','','','2','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','','','0','','','','-1','',''),('8','http://wx.qlogo.cn/mmopen/USH8Nb3Hz5SBEGWicIE2tiafPFGttYdItBERfiazkibiaAKZJ1Nib6O5XOxA43jAZUuia1If8o2UXJFRSuZJAM44vUO845ShmicwL34q/0','0','0','0','0','zbemii1462891866','o4xIKt4C755tq6SNkcJC3H3CkmeM','守护幸福','','','','','','','1','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','西安','陕西','0','','','','1','',''),('9','http://wx.qlogo.cn/mmopen/k3aqqdcUMGZHxibpA1siaYBUUPmj2fWDFohe1uRc9mgPxU1v3L6zVoQkviaicBOFwvZV2DHNh43ics08wicyUjWficjicWW6h0FFztxl/0','0','0','0','0','zbemii1462891866','o4xIKt4mJ76am75xIuAPEIN830Fs','火柴','','','','','','','1','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','宁波','浙江','0','','','','-1','',''),('10','http://wx.qlogo.cn/mmopen/k3aqqdcUMGaicfx8w2DaGNFhMMDRO949HfJWjqwJU2EEx2qfcibibqo9kHpwaASJjgtD9Q55jlaaGZZE1ydLTFibSwllXyozk7MQ/0','0','0','0','0','zbemii1462891866','o4xIKt-iRgPmZBGYzZzqnH-03_2I','诺诺','','','','','','','2','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','广州','广东','0','','','','-1','',''),('11','http://wx.qlogo.cn/mmopen/ockPdSNzhXQoFrFd8Ow0vlibCMLMLJnub665Xr9NtxrgNGJiaxCpBy4ib8Vkibicn7Xy1nmqY5TNZHs6d56iazWP0uwYWQ5A4lkzsG/0','0','0','0','0','zbemii1462891866','o4xIKt9NanyVhbE7f6ajIhRrgHRk','俞丹','','','','','','','2','','','','','0','0','0','0','0','0','0','0.00','0.00','','','','','佛山','广东','0','','','','1','','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_userinfo_attach` */
 DROP TABLE IF EXISTS `vifnn_userinfo_attach`;/* MySQLReback Separation */ CREATE TABLE `vifnn_userinfo_attach` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `field_id` int(10) unsigned NOT NULL,
  `field_value` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `field_id` (`field_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_users` */
 DROP TABLE IF EXISTS `vifnn_users`;/* MySQLReback Separation */ CREATE TABLE `vifnn_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(80) NOT NULL DEFAULT '',
  `agentid` int(10) NOT NULL DEFAULT '0',
  `inviter` int(10) NOT NULL DEFAULT '0',
  `gid` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `mp` varchar(11) NOT NULL DEFAULT '',
  `smscount` int(10) NOT NULL DEFAULT '0',
  `wifi` tinyint(2) NOT NULL DEFAULT '0',
  `gtpl` tinyint(2) NOT NULL DEFAULT '0',
  `ly` tinyint(2) NOT NULL DEFAULT '0',
  `lt` tinyint(2) NOT NULL DEFAULT '0',
  `sq` tinyint(2) NOT NULL DEFAULT '0',
  `dlsc` tinyint(2) NOT NULL DEFAULT '0',
  `lh` tinyint(2) NOT NULL DEFAULT '0',
  `tg` tinyint(2) NOT NULL DEFAULT '0',
  `dzp` tinyint(2) NOT NULL DEFAULT '0',
  `yhq` tinyint(2) NOT NULL DEFAULT '0',
  `ggk` tinyint(2) NOT NULL DEFAULT '0',
  `sgj` tinyint(2) NOT NULL DEFAULT '0',
  `zjd` tinyint(2) NOT NULL DEFAULT '0',
  `yzdd` tinyint(2) NOT NULL DEFAULT '0',
  `qd` tinyint(2) NOT NULL DEFAULT '0',
  `hk` tinyint(2) NOT NULL DEFAULT '0',
  `yyl` tinyint(2) NOT NULL DEFAULT '0',
  `zpq` tinyint(2) NOT NULL DEFAULT '0',
  `wxq` tinyint(2) NOT NULL DEFAULT '0',
  `wmp` tinyint(2) NOT NULL DEFAULT '0',
  `xwifi` tinyint(2) NOT NULL DEFAULT '0',
  `wdc` tinyint(2) NOT NULL DEFAULT '0',
  `wxt` tinyint(2) NOT NULL DEFAULT '0',
  `wqc` tinyint(2) NOT NULL DEFAULT '0',
  `wyl` tinyint(2) NOT NULL DEFAULT '0',
  `wjy` tinyint(2) NOT NULL DEFAULT '0',
  `wjd` tinyint(2) NOT NULL DEFAULT '0',
  `wfc` tinyint(2) NOT NULL DEFAULT '0',
  `wmr` tinyint(2) NOT NULL DEFAULT '0',
  `wjs` tinyint(2) NOT NULL DEFAULT '0',
  `wzw` tinyint(2) NOT NULL DEFAULT '0',
  `wsp` tinyint(2) NOT NULL DEFAULT '0',
  `wly` tinyint(2) NOT NULL DEFAULT '0',
  `whd` tinyint(2) NOT NULL DEFAULT '0',
  `wwy` tinyint(2) NOT NULL DEFAULT '0',
  `wtkv` tinyint(2) NOT NULL DEFAULT '0',
  `wjb` tinyint(2) NOT NULL DEFAULT '0',
  `wzx` tinyint(2) NOT NULL DEFAULT '0',
  `whq` tinyint(2) NOT NULL DEFAULT '0',
  `wcw` tinyint(2) NOT NULL DEFAULT '0',
  `wjz` tinyint(2) NOT NULL DEFAULT '0',
  `wzl` tinyint(2) NOT NULL DEFAULT '0',
  `wdy` tinyint(2) NOT NULL DEFAULT '0',
  `wxc` tinyint(2) NOT NULL DEFAULT '0',
  `wtp` tinyint(2) NOT NULL DEFAULT '0',
  `qj` tinyint(2) NOT NULL DEFAULT '0',
  `wnbd` tinyint(2) NOT NULL DEFAULT '0',
  `zxyd` tinyint(2) NOT NULL DEFAULT '0',
  `xwyy` tinyint(2) NOT NULL DEFAULT '0',
  `wyq` tinyint(2) NOT NULL DEFAULT '0',
  `zthd` tinyint(2) NOT NULL DEFAULT '0',
  `jjdh` tinyint(2) NOT NULL DEFAULT '0',
  `wsm` tinyint(2) NOT NULL DEFAULT '0',
  `jkdtw` tinyint(2) NOT NULL DEFAULT '0',
  `xwfc` tinyint(2) NOT NULL DEFAULT '0',
  `wzp` tinyint(2) NOT NULL DEFAULT '0',
  `cjyy` tinyint(2) NOT NULL DEFAULT '0',
  `uc_id` int(11) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(90) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `lasttime` varchar(13) NOT NULL,
  `status` varchar(1) NOT NULL,
  `apenable` tinyint(4) NOT NULL DEFAULT '0',
  `createip` varchar(30) NOT NULL,
  `mpenable` tinyint(4) NOT NULL DEFAULT '0',
  `lastip` varchar(30) NOT NULL,
  `diynum` int(11) NOT NULL,
  `activitynum` int(11) NOT NULL,
  `gongzhongnumgon` int(11) NOT NULL,
  `card_num` int(11) NOT NULL,
  `card_create_status` tinyint(1) NOT NULL,
  `money` int(11) NOT NULL,
  `moneybalance` int(10) NOT NULL DEFAULT '0',
  `spend` int(5) NOT NULL DEFAULT '0',
  `viptime` varchar(13) NOT NULL,
  `connectnum` int(11) NOT NULL DEFAULT '0',
  `lastloginmonth` smallint(2) NOT NULL DEFAULT '0',
  `attachmentsize` int(10) NOT NULL DEFAULT '0',
  `wechat_card_num` int(3) NOT NULL,
  `serviceUserNum` tinyint(3) NOT NULL,
  `invitecode` varchar(6) NOT NULL DEFAULT '',
  `remark` varchar(200) NOT NULL DEFAULT '',
  `business` char(20) NOT NULL DEFAULT 'other',
  `usertplid` tinyint(4) NOT NULL DEFAULT '0',
  `sysuser` int(11) NOT NULL,
  `is_syn` tinyint(4) NOT NULL DEFAULT '0',
  `source_domain` varchar(200) NOT NULL,
  `access_count` int(11) NOT NULL,
  `access_count_notice` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `agentid` (`agentid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_users` */
 INSERT INTO `vifnn_users` VALUES ('2','','0','0','4','vifnn','13388889999','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','c43ab39c87fa7fac50f04163f3d0eaa4','dsafsd111f@qq.com','1416820426','1460363188','1','0','218.23.209.76','0','114.218.73.211','0','0','0','0','0','0','0','0','2145801600','0','4','1661188','11','0','mgnamc','','other','1','0','0','','0',''),('3','','0','0','4','ehaisong','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','4172e03ac393a026b6d8f3e358f7fa5a','ee@22.net','1458653158','1465782458','1','0','112.66.125.151','0','223.199.11.29','0','0','0','0','0','0','0','0','1466524800','7','6','2067828','-2','0','cznpsj','','other','1','1','0','','0',''),('4','3','0','0','4','weidian_4b5ce2fe28_weidian','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0ca1bd6059cf904547be97a32a88c104','','1463637446','1463645004','1','0','121.40.34.179','0','121.40.34.179','0','0','0','0','0','0','0','0','2094789446','0','5','0','0','0','','','other','1','0','1','http://wd.sitore.com','0','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_vcard` */
 DROP TABLE IF EXISTS `vifnn_vcard`;/* MySQLReback Separation */ CREATE TABLE `vifnn_vcard` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `company_tel` varchar(100) DEFAULT NULL,
  `baiduapi` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `info` varchar(500) DEFAULT NULL COMMENT '公司简介',
  `fax` varchar(100) DEFAULT NULL,
  `title` text NOT NULL,
  `jianjie` text NOT NULL,
  `tp` char(255) NOT NULL,
  `logo` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_vcard_list` */
 DROP TABLE IF EXISTS `vifnn_vcard_list`;/* MySQLReback Separation */ CREATE TABLE `vifnn_vcard_list` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `tel` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `work` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `qq` varchar(100) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `banner` varchar(200) NOT NULL,
  `jianjie` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_verifier_message` */
 DROP TABLE IF EXISTS `vifnn_verifier_message`;/* MySQLReback Separation */ CREATE TABLE `vifnn_verifier_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL DEFAULT '',
  `nickname` varchar(100) NOT NULL DEFAULT '',
  `openid` varchar(100) NOT NULL DEFAULT '',
  `verifier_phone` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voiceresponse` */
 DROP TABLE IF EXISTS `vifnn_voiceresponse`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voiceresponse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uname` varchar(90) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `uptatetime` varchar(13) NOT NULL,
  `keyword` char(255) NOT NULL,
  `title` varchar(60) NOT NULL,
  `musicurl` char(255) NOT NULL,
  `hqmusicurl` char(255) NOT NULL,
  `description` char(255) NOT NULL,
  `token` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_vote` */
 DROP TABLE IF EXISTS `vifnn_vote`;/* MySQLReback Separation */ CREATE TABLE `vifnn_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `token` varchar(50) NOT NULL,
  `type` char(5) NOT NULL COMMENT 'text/img 文本/图片',
  `picurl` varchar(500) NOT NULL,
  `showpic` tinyint(4) NOT NULL COMMENT '是否显示图片',
  `info` varchar(5000) NOT NULL DEFAULT '',
  `statdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `display` tinyint(4) NOT NULL COMMENT '1投票前0投票后2投票结束',
  `cknums` tinyint(3) NOT NULL DEFAULT '1' COMMENT '最多可选择，默认1',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `sl` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `refresh` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  `scene_time` int(10) unsigned NOT NULL DEFAULT '0',
  `scene_state` int(10) unsigned NOT NULL DEFAULT '0',
  `ps` int(11) DEFAULT NULL,
  `spicurl` varchar(255) NOT NULL,
  `showhb` tinyint(4) NOT NULL,
  `tbpic` varchar(255) NOT NULL,
  `bgpic` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `keyword` (`keyword`),
  FULLTEXT KEY `token` (`token`),
  FULLTEXT KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_vote_item` */
 DROP TABLE IF EXISTS `vifnn_vote_item`;/* MySQLReback Separation */ CREATE TABLE `vifnn_vote_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vid` int(11) NOT NULL COMMENT 'vote_id',
  `item` varchar(50) NOT NULL,
  `vcount` int(11) NOT NULL,
  `startpicurl` varchar(200) NOT NULL DEFAULT '',
  `tourl` varchar(200) NOT NULL DEFAULT '',
  `rank` int(11) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_vote_record` */
 DROP TABLE IF EXISTS `vifnn_vote_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_vote_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` varchar(50) NOT NULL COMMENT '投票项 1,2,3,',
  `vid` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `touched` tinyint(4) NOT NULL,
  `touch_time` int(11) NOT NULL COMMENT '投票日期',
  `token` varchar(50) NOT NULL DEFAULT '',
  `trueip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vid` (`vid`) USING BTREE,
  KEY `vid_2` (`vid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg` */
 DROP TABLE IF EXISTS `vifnn_voteimg`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_name` varchar(50) NOT NULL,
  `action_desc` text NOT NULL,
  `award_desc` text NOT NULL,
  `flow_desc` text NOT NULL,
  `join_desc` text NOT NULL,
  `keyword` varchar(50) NOT NULL DEFAULT '' COMMENT '回复关键词',
  `reply_title` varchar(50) NOT NULL DEFAULT '' COMMENT '回复标题',
  `reply_content` varchar(200) NOT NULL DEFAULT '' COMMENT '回复内容',
  `reply_pic` varchar(255) NOT NULL COMMENT '回复图片',
  `start_time` int(11) NOT NULL COMMENT '开始时间',
  `apply_start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `apply_end_time` int(11) NOT NULL,
  `is_follow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否需要关注',
  `is_register` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否需要注册',
  `is_numtime` tinyint(1) NOT NULL DEFAULT '1',
  `limit_vote` int(11) NOT NULL COMMENT '限制投票数',
  `limit_vote_day` int(11) NOT NULL COMMENT '限制每天投票数',
  `limit_vote_item` int(11) NOT NULL,
  `phone` char(50) NOT NULL,
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'token',
  `page_type` enum('waterfall','page') NOT NULL DEFAULT 'waterfall',
  `display` tinyint(1) NOT NULL,
  `default_skin` tinyint(1) NOT NULL,
  `follow_msg` varchar(500) NOT NULL,
  `follow_url` varchar(255) NOT NULL,
  `self_status` tinyint(1) NOT NULL,
  `follow_btn_msg` varchar(255) NOT NULL,
  `register_msg` varchar(255) NOT NULL,
  `territory_limit` tinyint(1) NOT NULL,
  `pro_city` text NOT NULL,
  `ifsplit` tinyint(1) NOT NULL,
  `split_number` int(11) NOT NULL,
  `lottery_type` tinyint(1) NOT NULL,
  `lottery_id` int(11) NOT NULL,
  `lottery_name` varchar(100) NOT NULL,
  `custom_sharetitle` varchar(255) NOT NULL,
  `custom_sharedsc` varchar(500) NOT NULL,
  `custom_sharetitle_lp` varchar(255) NOT NULL,
  `custom_sharedsc_lp` varchar(500) NOT NULL,
  `img_count` tinyint(1) NOT NULL,
  `onoff_hongbao` tinyint(1) NOT NULL,
  `onoff_voice` tinyint(1) NOT NULL DEFAULT '1',
  `onoff_video` tinyint(1) NOT NULL,
  `apply_fields_name` varchar(500) NOT NULL,
  `need_check` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_banner` */
 DROP TABLE IF EXISTS `vifnn_voteimg_banner`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL COMMENT '活动id',
  `img_url` varchar(200) NOT NULL DEFAULT '',
  `external_links` varchar(200) NOT NULL DEFAULT '' COMMENT '外部链接',
  `banner_rank` int(11) NOT NULL DEFAULT '1' COMMENT '播放顺序',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'token',
  PRIMARY KEY (`id`),
  KEY `vote_id` (`vote_id`) USING BTREE,
  KEY `banner_index` (`vote_id`,`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_book` */
 DROP TABLE IF EXISTS `vifnn_voteimg_book`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_book` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` varchar(32) NOT NULL DEFAULT '',
  `price` float(9,2) NOT NULL,
  `wecha_id` varchar(100) NOT NULL DEFAULT '',
  `order_name` varchar(100) NOT NULL,
  `paytype` varchar(50) NOT NULL,
  `paid` tinyint(4) NOT NULL,
  `paystatus` tinyint(4) NOT NULL,
  `hb_id` int(11) NOT NULL,
  `vote_id` int(11) NOT NULL,
  `book_time` int(11) NOT NULL,
  `pay_time` int(11) NOT NULL,
  `platform` tinyint(1) NOT NULL,
  `token` varchar(50) NOT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_bottom` */
 DROP TABLE IF EXISTS `vifnn_voteimg_bottom`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_bottom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL COMMENT '活动id',
  `bottom_name` char(50) NOT NULL COMMENT '导航名称',
  `bottom_link` varchar(255) NOT NULL,
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'token',
  `bottom_icon` varchar(255) NOT NULL COMMENT '导航图标',
  `bottom_rank` int(11) NOT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否隐藏',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是内置导航',
  PRIMARY KEY (`id`),
  KEY `vote_id` (`vote_id`) USING BTREE,
  KEY `bottom_index` (`vote_id`,`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_confighb` */
 DROP TABLE IF EXISTS `vifnn_voteimg_confighb`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_confighb` (
  `hb_id` int(11) NOT NULL AUTO_INCREMENT,
  `wecha_id` varchar(100) NOT NULL,
  `item_id` int(11) NOT NULL,
  `shareer_scale` float(2,1) NOT NULL,
  `voter_scale` float(2,1) NOT NULL,
  `min_money` float(6,2) NOT NULL,
  `max_money` float(6,2) NOT NULL,
  `total_money` float(9,2) NOT NULL,
  `lost_money` float(9,2) NOT NULL,
  `token` varchar(50) NOT NULL DEFAULT '',
  `paystatus` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vote_id` int(11) NOT NULL,
  PRIMARY KEY (`hb_id`),
  KEY `itemID` (`item_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_custom_field` */
 DROP TABLE IF EXISTS `vifnn_voteimg_custom_field`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_custom_field` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` char(15) NOT NULL,
  `field_option` varchar(500) NOT NULL,
  `field_type` char(10) NOT NULL,
  `item_name` char(15) NOT NULL,
  `field_match` char(80) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `is_empty` enum('0','1') NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `err_info` char(35) NOT NULL,
  `set_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  `action_id` int(11) NOT NULL,
  `is_details` enum('0','1') NOT NULL,
  PRIMARY KEY (`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_hbinfo` */
 DROP TABLE IF EXISTS `vifnn_voteimg_hbinfo`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_hbinfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL,
  `mch_billno` char(50) NOT NULL DEFAULT '',
  `wecha_id` varchar(60) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL,
  `money` float(6,2) DEFAULT NULL,
  `open_status` tinyint(1) NOT NULL,
  `update_time` int(11) NOT NULL,
  `refund_time` int(11) NOT NULL,
  `token` char(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_hbrecord` */
 DROP TABLE IF EXISTS `vifnn_voteimg_hbrecord`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_hbrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `money` float(6,2) NOT NULL,
  `gettype` tinyint(1) NOT NULL,
  `item_id` int(11) NOT NULL,
  `gettime` int(11) NOT NULL,
  `token` varchar(50) NOT NULL DEFAULT '',
  `received` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_item` */
 DROP TABLE IF EXISTS `vifnn_voteimg_item`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL COMMENT '图片投票id',
  `baby_id` int(11) NOT NULL,
  `vote_title` varchar(100) NOT NULL DEFAULT '' COMMENT '图片描述',
  `introduction` text NOT NULL COMMENT '自我介绍',
  `manifesto` varchar(255) NOT NULL COMMENT '拉票宣言',
  `vote_img` varchar(500) NOT NULL DEFAULT '' COMMENT '图片地址',
  `jump_url` varchar(255) NOT NULL,
  `contact` varchar(11) NOT NULL COMMENT '手机号',
  `vote_count` int(11) NOT NULL COMMENT '获得票数',
  `upload_time` int(11) NOT NULL COMMENT '上传时间',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'token',
  `check_pass` tinyint(1) NOT NULL COMMENT '审核',
  `wecha_id` varchar(100) NOT NULL DEFAULT '',
  `upload_type` tinyint(1) NOT NULL COMMENT '区分管理上传还是报名',
  `video_path` varchar(500) NOT NULL,
  `ifhongbao` tinyint(1) NOT NULL,
  `record_time` int(11) NOT NULL,
  `upload_voice` varchar(800) NOT NULL,
  `alert_state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vote_id` (`vote_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_item_attach` */
 DROP TABLE IF EXISTS `vifnn_voteimg_item_attach`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_item_attach` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `field_id` int(10) unsigned NOT NULL,
  `field_value` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`) USING BTREE,
  KEY `field_id` (`field_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_menus` */
 DROP TABLE IF EXISTS `vifnn_voteimg_menus`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL COMMENT '活动id',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'token',
  `menu_name` varchar(50) NOT NULL DEFAULT '',
  `menu_icon` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `menu_link` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单链接',
  `hide` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否隐藏',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否是内置菜单',
  PRIMARY KEY (`id`),
  KEY `vote_id` (`vote_id`) USING BTREE,
  KEY `menus_index` (`vote_id`,`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_prize` */
 DROP TABLE IF EXISTS `vifnn_voteimg_prize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL,
  `prizegrade` varchar(100) NOT NULL DEFAULT '',
  `prizename` varchar(100) NOT NULL DEFAULT '',
  `prizesponsor` varchar(100) NOT NULL DEFAULT '',
  `sponsorurl` varchar(255) NOT NULL DEFAULT '',
  `prizepic` varchar(255) NOT NULL DEFAULT '',
  `token` varchar(50) NOT NULL DEFAULT '',
  `prizenum` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_receivelog` */
 DROP TABLE IF EXISTS `vifnn_voteimg_receivelog`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_receivelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mch_billno` char(50) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL,
  `money` float(6,2) DEFAULT NULL,
  `receive_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_record` */
 DROP TABLE IF EXISTS `vifnn_voteimg_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `vote_time` int(11) NOT NULL,
  `token` varchar(50) NOT NULL DEFAULT '',
  `vote_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`user_id`) USING BTREE,
  KEY `itemId` (`item_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_sponor` */
 DROP TABLE IF EXISTS `vifnn_voteimg_sponor`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_sponor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL,
  `sponsorname` varchar(100) NOT NULL DEFAULT '',
  `sponsorurl` varchar(255) NOT NULL DEFAULT '',
  `sponsorpic` varchar(255) NOT NULL DEFAULT '',
  `token` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_stat` */
 DROP TABLE IF EXISTS `vifnn_voteimg_stat`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL COMMENT '活动id',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'token',
  `stat_name` varchar(100) NOT NULL DEFAULT '',
  `count` int(11) NOT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否隐藏',
  PRIMARY KEY (`id`),
  KEY `vote_id` (`vote_id`) USING BTREE,
  KEY `stat_index` (`vote_id`,`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_voteimg_users` */
 DROP TABLE IF EXISTS `vifnn_voteimg_users`;/* MySQLReback Separation */ CREATE TABLE `vifnn_voteimg_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL COMMENT '活动id',
  `item_id` text NOT NULL COMMENT '投票选项',
  `wecha_id` varchar(100) NOT NULL,
  `nick_name` varchar(255) NOT NULL COMMENT '微信昵称',
  `votenum` int(11) NOT NULL COMMENT '已投票数',
  `votenum_day` int(11) NOT NULL COMMENT '今日已投票数',
  `vote_today` text NOT NULL,
  `vote_time` int(11) NOT NULL COMMENT '投票时间',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'token',
  `phone` varchar(11) NOT NULL,
  `share_code` varchar(50) NOT NULL DEFAULT '',
  `wecha_pic` varchar(255) NOT NULL DEFAULT '',
  `isrefund` tinyint(1) NOT NULL,
  `client_ip` varchar(30) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `vote_id` (`vote_id`) USING BTREE,
  KEY `IDX_WE_TO_VO` (`wecha_id`,`token`,`vote_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wall` */
 DROP TABLE IF EXISTS `vifnn_wall`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(20) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  `logo` varchar(100) DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `startbackground` varchar(100) DEFAULT '',
  `background` varchar(100) DEFAULT '',
  `endbackground` varchar(100) DEFAULT '',
  `isopen` tinyint(1) DEFAULT '1',
  `firstprizename` varchar(50) DEFAULT '',
  `firstprizepic` varchar(100) DEFAULT '',
  `firstprizecount` mediumint(5) DEFAULT '0',
  `secondprizename` varchar(50) DEFAULT '',
  `secondprizecount` mediumint(4) DEFAULT '0',
  `secondprizepic` varchar(150) DEFAULT '',
  `thirdprizename` varchar(50) DEFAULT '',
  `thirdprizepic` varchar(100) DEFAULT '',
  `thirdprizecount` mediumint(4) DEFAULT '0',
  `fourthprizename` varchar(50) DEFAULT '',
  `fourthprizecount` mediumint(4) DEFAULT '0',
  `fourthprizepic` varchar(100) DEFAULT '',
  `fifthprizename` varchar(50) DEFAULT '',
  `fifthprizecount` mediumint(5) DEFAULT '0',
  `fifthprizepic` varchar(100) DEFAULT '',
  `sixthprizename` varchar(50) DEFAULT '',
  `sixthprizecount` mediumint(4) DEFAULT '0',
  `sixthprizepic` varchar(100) DEFAULT '',
  `keyword` varchar(60) DEFAULT '',
  `qrcode` varchar(100) DEFAULT '',
  `ck_msg` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wall_member` */
 DROP TABLE IF EXISTS `vifnn_wall_member`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wall_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(20) NOT NULL DEFAULT '',
  `wecha_id` varchar(60) NOT NULL DEFAULT '',
  `portrait` varchar(150) NOT NULL DEFAULT '',
  `nickname` varchar(60) NOT NULL DEFAULT '',
  `truename` varchar(40) NOT NULL,
  `phone` char(11) NOT NULL,
  `mp` varchar(11) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT '0',
  `wallid` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `act_id` int(11) NOT NULL,
  `act_type` enum('1','2','3') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`,`wallid`),
  KEY `wecha_id` (`wecha_id`),
  KEY `wecha_id_2` (`wecha_id`,`act_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wall_message` */
 DROP TABLE IF EXISTS `vifnn_wall_message`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wall_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `wallid` int(10) NOT NULL DEFAULT '0',
  `token` varchar(20) NOT NULL DEFAULT '',
  `wecha_id` varchar(60) NOT NULL DEFAULT '',
  `content` varchar(500) NOT NULL DEFAULT '',
  `picture` varchar(150) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT '0',
  `is_scene` enum('0','1') NOT NULL,
  `is_check` tinyint(1) NOT NULL DEFAULT '1',
  `check_time` int(11) NOT NULL,
  `is_cheke` enum('0','1') NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'text',
  PRIMARY KEY (`id`),
  KEY `wallid` (`wallid`,`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wall_prize` */
 DROP TABLE IF EXISTS `vifnn_wall_prize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wall_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL,
  `pname` char(40) NOT NULL,
  `pic` char(100) NOT NULL,
  `num` mediumint(9) NOT NULL,
  `token` char(20) NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL,
  `sceneid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wall_prize_record` */
 DROP TABLE IF EXISTS `vifnn_wall_prize_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wall_prize_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `wallid` int(11) NOT NULL DEFAULT '0',
  `prize` mediumint(4) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `sceneid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wallid` (`wallid`,`prize`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wall_supperzzle` */
 DROP TABLE IF EXISTS `vifnn_wall_supperzzle`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wall_supperzzle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sceneid` int(10) unsigned NOT NULL,
  `nid` int(10) unsigned NOT NULL,
  `vid` int(10) unsigned NOT NULL,
  `addtime` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weather` */
 DROP TABLE IF EXISTS `vifnn_weather`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weather` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `code` char(9) NOT NULL,
  `name` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2502 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1','101010100','北京'),('2','101010200','海淀'),('3','101010400','顺义'),('4','101010500','怀柔'),('5','101010600','通州'),('6','101010700','昌平'),('7','101010800','延庆'),('8','101010900','丰台'),('9','101011000','石景山'),('10','101011100','大兴'),('11','101011200','房山'),('12','101011300','密云'),('13','101011400','门头沟'),('14','101011500','平谷'),('15','101020100','上海'),('16','101020200','闵行'),('17','101020300','宝山'),('18','101020500','嘉定'),('19','101020600','浦东南汇'),('20','101020700','金山'),('21','101020800','青浦'),('22','101020900','松江'),('23','101021000','奉贤'),('24','101021100','崇明'),('25','101021300','浦东'),('26','101030200','武清'),('27','101030300','宝坻'),('28','101030400','东丽'),('29','101030500','西青'),('30','101030600','北辰'),('31','101030700','宁河'),('32','101030800','汉沽'),('33','101030900','静海'),('34','101031000','津南'),('35','101031100','塘沽'),('36','101031200','大港'),('37','101031400','蓟县'),('38','101040100','重庆'),('39','101040200','永川'),('40','101040300','合川'),('41','101040400','南川'),('42','101040500','江津'),('43','101040600','万盛'),('44','101040700','渝北'),('45','101040800','北碚'),('46','101041000','长寿'),('47','101041100','黔江'),('48','101041300','万州'),('49','101041400','涪陵'),('50','101041500','开县'),('51','101041600','城口'),('52','101041700','云阳'),('53','101041800','巫溪'),('54','101041900','奉节'),('55','101042000','巫山'),('56','101042100','潼南'),('57','101042200','垫江'),('58','101042300','梁平'),('59','101042400','忠县'),('60','101042500','石柱'),('61','101042600','大足'),('62','101042700','荣昌'),('63','101042800','铜梁'),('64','101042900','璧山'),('65','101043000','丰都'),('66','101043100','武隆'),('67','101043200','彭水'),('68','101043300','綦江'),('69','101043400','酉阳'),('70','101043600','秀山'),('71','101050101','哈尔滨'),('72','101050102','双城'),('73','101050103','呼兰'),('74','101050104','阿城'),('75','101050105','宾县'),('76','101050106','依兰'),('77','101050107','巴彦'),('78','101050108','通河'),('79','101050109','方正'),('80','101050110','延寿'),('81','101050111','尚志'),('82','101050112','五常'),('83','101050113','木兰'),('84','101050201','齐齐哈尔'),('85','101050202','讷河'),('86','101050203','龙江'),('87','101050204','甘南'),('88','101050205','富裕'),('89','101050206','依安'),('90','101050207','拜泉'),('91','101050208','克山'),('92','101050209','克东'),('93','101050210','泰来'),('94','101050301','牡丹江'),('95','101050302','海林'),('96','101050303','穆棱'),('97','101050304','林口'),('98','101050305','绥芬河'),('99','101050306','宁安'),('100','101050307','东宁'),('101','101050401','佳木斯'),('102','101050402','汤原'),('103','101050403','抚远'),('104','101050404','桦川'),('105','101050405','桦南'),('106','101050406','同江'),('107','101050407','富锦'),('108','101050501','绥化'),('109','101050502','肇东'),('110','101050503','安达'),('111','101050504','海伦'),('112','101050505','明水'),('113','101050506','望奎'),('114','101050507','兰西'),('115','101050508','青冈'),('116','101050509','庆安'),('117','101050510','绥棱'),('118','101050601','黑河'),('119','101050602','嫩江'),('120','101050603','孙吴'),('121','101050604','逊克'),('122','101050605','五大连池'),('123','101050606','北安'),('124','101050701','大兴安岭'),('125','101050702','塔河'),('126','101050703','漠河'),('127','101050704','呼玛'),('128','101050801','伊春'),('129','101050804','铁力'),('130','101050805','嘉荫'),('131','101050901','大庆'),('132','101050902','林甸'),('133','101050903','肇州'),('134','101050904','肇源'),('135','101050905','杜蒙'),('136','101051002','七台河'),('137','101051003','勃利'),('138','101051101','鸡西'),('139','101051102','虎林'),('140','101051103','密山'),('141','101051104','鸡东'),('142','101051201','鹤岗'),('143','101051202','绥滨'),('144','101051203','萝北'),('145','101051301','双鸭山'),('146','101051302','集贤'),('147','101051303','宝清'),('148','101051304','饶河'),('149','101051305','友谊'),('150','101060101','长春'),('151','101060102','农安'),('152','101060103','德惠'),('153','101060104','九台'),('154','101060105','榆树'),('155','101060201','吉林'),('156','101060202','舒兰'),('157','101060203','永吉'),('158','101060204','蛟河'),('159','101060205','磐石'),('160','101060206','桦甸'),('161','101060302','敦化'),('162','101060303','安图'),('163','101060304','汪清'),('164','101060305','和龙'),('165','101060307','龙井'),('166','101060308','珲春'),('167','101060309','图们'),('168','101060312','延吉'),('169','101060401','四平'),('170','101060402','双辽'),('171','101060403','梨树'),('172','101060404','公主岭'),('173','101060405','伊通'),('174','101060501','通化'),('175','101060502','梅河口'),('176','101060503','柳河'),('177','101060504','辉南'),('178','101060505','集安'),('179','101060601','白城'),('180','101060602','洮南'),('181','101060603','大安'),('182','101060604','镇赉'),('183','101060605','通榆'),('184','101060701','辽源');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('185','101060702','东丰');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('186','101060703','东辽'),('187','101060801','松原'),('188','101060802','乾安'),('189','101060803','前郭'),('190','101060804','长岭'),('191','101060805','扶余'),('192','101060901','白山'),('193','101060902','靖宇'),('194','101060903','临江'),('195','101060905','长白'),('196','101060906','抚松'),('197','101060907','江源'),('198','101070101','沈阳'),('199','101070103','辽中'),('200','101070104','康平'),('201','101070105','法库'),('202','101070106','新民'),('203','101070201','大连'),('204','101070202','瓦房店'),('205','101070204','普兰店'),('206','101070206','长海'),('207','101070207','庄河'),('208','101070301','鞍山'),('209','101070302','台安'),('210','101070303','岫岩'),('211','101070304','海城'),('212','101070401','抚顺'),('213','101070402','新宾'),('214','101070403','清原'),('215','101070501','本溪'),('216','101070504','桓仁'),('217','101070601','丹东'),('218','101070602','凤城'),('219','101070603','宽甸'),('220','101070604','东港'),('221','101070701','锦州'),('222','101070702','凌海'),('223','101070704','义县'),('224','101070705','黑山'),('225','101070706','北镇'),('226','101070801','营口'),('227','101070802','大石桥'),('228','101070803','盖州'),('229','101070901','阜新'),('230','101070902','彰武'),('231','101071001','辽阳'),('232','101071003','灯塔'),('233','101071004','弓长岭'),('234','101071101','铁岭'),('235','101071102','开原'),('236','101071103','昌图'),('237','101071104','西丰'),('238','101071105','调兵山'),('239','101071201','朝阳'),('240','101071203','凌源'),('241','101071204','喀左'),('242','101071205','北票'),('243','101071207','建平'),('244','101071301','盘锦'),('245','101071302','大洼'),('246','101071303','盘山'),('247','101071401','葫芦岛'),('248','101071402','建昌'),('249','101071403','绥中'),('250','101071404','兴城'),('251','101080101','呼和浩特'),('252','101080102','土左'),('253','101080103','托克托'),('254','101080104','和林格尔'),('255','101080105','清水河'),('256','101080107','武川'),('257','101080201','包头'),('258','101080202','白云鄂博'),('259','101080204','土右'),('260','101080205','固阳'),('261','101080206','达茂'),('262','101080301','乌海'),('263','101080401','乌兰察布'),('264','101080402','卓资'),('265','101080403','化德'),('266','101080404','商都'),('267','101080406','兴和'),('268','101080407','凉城'),('269','101080408','察右前'),('270','101080409','察右中'),('271','101080410','察右后'),('272','101080411','四子王'),('273','101080412','丰镇'),('274','101080501','通辽'),('275','101080503','科左中'),('276','101080504','科左后'),('277','101080506','开鲁'),('278','101080507','库伦'),('279','101080508','奈曼'),('280','101080509','扎鲁特'),('281','101080601','赤峰'),('282','101080603','阿鲁'),('283','101080605','巴林左'),('284','101080606','巴林右'),('285','101080607','林西'),('286','101080608','克什'),('287','101080609','翁牛特'),('288','101080611','喀喇沁'),('289','101080613','宁城'),('290','101080614','敖汉'),('291','101080701','鄂尔多斯'),('292','101080703','达拉特'),('293','101080704','准格尔'),('294','101080706','河南'),('295','101080708','鄂托克'),('296','101080709','杭锦'),('297','101080710','乌审'),('298','101080711','伊金霍洛'),('299','101080801','巴彦淖尔'),('300','101080802','五原'),('301','101080803','磴口'),('302','101080804','乌前'),('303','101080806','乌中'),('304','101080807','乌后'),('305','101080810','杭锦后'),('306','101080901','锡林浩特'),('307','101080903','二连浩特'),('308','101080904','阿巴嘎'),('309','101080906','苏左'),('310','101080907','苏右'),('311','101080909','东乌'),('312','101080910','西乌'),('313','101080911','太仆寺'),('314','101080912','镶黄'),('315','101080913','正镶白'),('316','101080914','正蓝'),('317','101080915','多伦'),('318','101081000','呼伦贝尔'),('319','101081001','海拉尔'),('320','101081003','阿荣'),('321','101081004','莫力达瓦'),('322','101081005','鄂伦春'),('323','101081006','鄂温克'),('324','101081007','陈巴尔虎'),('325','101081008','新左'),('326','101081009','新右'),('327','101081010','满洲里'),('328','101081011','牙克石'),('329','101081012','扎兰屯'),('330','101081014','额尔古纳'),('331','101081015','根河'),('332','101081101','乌兰浩特'),('333','101081102','阿尔山'),('334','101081103','科右中'),('335','101081105','扎赉特'),('336','101081107','突泉'),('337','101081108','霍林郭勒'),('338','101081109','科右前'),('339','101081201','阿拉善'),('340','101081202','阿右'),('341','101081203','额济纳'),('342','101090101','石家庄'),('343','101090102','井陉'),('344','101090103','正定'),('345','101090104','栾城'),('346','101090105','行唐'),('347','101090106','灵寿'),('348','101090107','高邑'),('349','101090108','深泽'),('350','101090109','赞皇'),('351','101090110','无极'),('352','101090111','平山'),('353','101090112','元氏'),('354','101090113','赵县'),('355','101090114','辛集'),('356','101090115','藁城'),('357','101090116','晋州'),('358','101090117','新乐'),('359','101090118','鹿泉'),('360','101090201','保定');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('361','101090202','满城');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('362','101090203','阜平'),('363','101090204','徐水'),('364','101090205','唐县'),('365','101090206','高阳'),('366','101090207','容城'),('367','101090209','涞源'),('368','101090210','望都'),('369','101090211','安新'),('370','101090212','易县'),('371','101090214','曲阳'),('372','101090215','蠡县'),('373','101090216','顺平'),('374','101090217','雄县'),('375','101090218','涿州'),('376','101090219','定州'),('377','101090220','安国'),('378','101090221','高碑店'),('379','101090222','涞水'),('380','101090223','定兴'),('381','101090224','清苑'),('382','101090225','博野'),('383','101090301','张家口'),('384','101090302','宣化'),('385','101090303','张北'),('386','101090304','康保'),('387','101090305','沽源'),('388','101090306','尚义'),('389','101090307','蔚县'),('390','101090308','阳原'),('391','101090309','怀安'),('392','101090310','万全'),('393','101090311','怀来'),('394','101090312','涿鹿'),('395','101090313','赤城'),('396','101090314','崇礼'),('397','101090402','承德'),('398','101090404','兴隆'),('399','101090405','平泉'),('400','101090406','滦平'),('401','101090407','隆化'),('402','101090408','丰宁'),('403','101090409','宽城'),('404','101090410','围场'),('405','101090501','唐山'),('406','101090502','丰南'),('407','101090503','丰润'),('408','101090504','滦县'),('409','101090505','滦南'),('410','101090506','乐亭'),('411','101090507','迁西'),('412','101090508','玉田'),('413','101090509','唐海'),('414','101090510','遵化'),('415','101090511','迁安'),('416','101090512','曹妃甸'),('417','101090601','廊坊'),('418','101090602','固安'),('419','101090603','永清'),('420','101090604','香河'),('421','101090605','大城'),('422','101090606','文安'),('423','101090607','大厂'),('424','101090608','霸州'),('425','101090609','三河'),('426','101090701','沧州'),('427','101090702','青县'),('428','101090703','东光'),('429','101090704','海兴'),('430','101090705','盐山'),('431','101090706','肃宁'),('432','101090707','南皮'),('433','101090708','吴桥'),('434','101090709','献县'),('435','101090710','孟村'),('436','101090711','泊头'),('437','101090712','任丘'),('438','101090713','黄骅'),('439','101090714','河间'),('440','101090716','沧县'),('441','101090801','衡水'),('442','101090802','枣强'),('443','101090803','武邑'),('444','101090804','武强'),('445','101090805','饶阳'),('446','101090806','安平'),('447','101090807','故城'),('448','101090808','景县'),('449','101090809','阜城'),('450','101090810','冀州'),('451','101090811','深州'),('452','101090901','邢台'),('453','101090902','临城'),('454','101090905','柏乡'),('455','101090906','隆尧'),('456','101090907','南和'),('457','101090908','宁晋'),('458','101090909','巨鹿'),('459','101090910','新河'),('460','101090911','广宗'),('461','101090912','平乡'),('462','101090913','威县'),('463','101090914','清河'),('464','101090915','临西'),('465','101090916','南宫'),('466','101090917','沙河'),('467','101090918','任县'),('468','101090919','内丘'),('469','101091001','邯郸'),('470','101091002','峰峰矿'),('471','101091003','临漳'),('472','101091004','成安'),('473','101091005','大名'),('474','101091006','涉县'),('475','101091007','磁县'),('476','101091008','肥乡'),('477','101091009','永年'),('478','101091010','邱县'),('479','101091011','鸡泽'),('480','101091012','广平'),('481','101091013','馆陶'),('482','101091014','魏县'),('483','101091015','曲周'),('484','101091016','武安'),('485','101091101','秦皇岛'),('486','101091102','青龙'),('487','101091103','昌黎'),('488','101091104','抚宁'),('489','101091105','卢龙'),('490','101100101','太原'),('491','101100102','清徐'),('492','101100103','阳曲'),('493','101100104','娄烦'),('494','101100105','古交'),('495','101100201','大同'),('496','101100202','阳高'),('497','101100204','天镇'),('498','101100205','广灵'),('499','101100206','灵丘'),('500','101100207','浑源'),('501','101100208','左云'),('502','101100301','阳泉'),('503','101100302','盂县'),('504','101100303','平定'),('505','101100401','晋中'),('506','101100403','榆社'),('507','101100404','左权'),('508','101100405','和顺'),('509','101100406','昔阳'),('510','101100407','寿阳'),('511','101100408','太谷'),('512','101100409','祁县'),('513','101100410','平遥'),('514','101100411','灵石'),('515','101100412','介休'),('516','101100501','长治'),('517','101100502','黎城'),('518','101100503','屯留'),('519','101100504','潞城'),('520','101100505','襄垣'),('521','101100506','平顺'),('522','101100507','武乡'),('523','101100508','沁县'),('524','101100509','长子'),('525','101100510','沁源'),('526','101100511','壶关'),('527','101100601','晋城'),('528','101100602','沁水'),('529','101100603','阳城'),('530','101100604','陵川'),('531','101100605','高平'),('532','101100606','泽州'),('533','101100701','临汾'),('534','101100702','曲沃'),('535','101100703','永和'),('536','101100704','隰县'),('537','101100705','大宁'),('538','101100706','吉县'),('539','101100707','襄汾'),('540','101100708','蒲县'),('541','101100709','汾西'),('542','101100710','洪洞'),('543','101100711','霍州');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('544','101100712','乡宁');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('545','101100713','翼城'),('546','101100714','侯马'),('547','101100715','浮山'),('548','101100716','安泽'),('549','101100717','古县'),('550','101100801','运城'),('551','101100802','临猗'),('552','101100803','稷山'),('553','101100804','万荣'),('554','101100805','河津'),('555','101100806','新绛'),('556','101100807','绛县'),('557','101100808','闻喜'),('558','101100809','垣曲'),('559','101100810','永济'),('560','101100811','芮城'),('561','101100812','夏县'),('562','101100813','平陆'),('563','101100901','朔州'),('564','101100903','山阴'),('565','101100904','右玉'),('566','101100905','应县'),('567','101100906','怀仁'),('568','101101001','忻州'),('569','101101002','定襄'),('570','101101003','五台'),('571','101101004','河曲'),('572','101101005','偏关'),('573','101101006','神池'),('574','101101007','宁武'),('575','101101008','代县'),('576','101101009','繁峙'),('577','101101011','保德'),('578','101101012','静乐'),('579','101101013','岢岚'),('580','101101014','五寨'),('581','101101015','原平'),('582','101101100','吕梁'),('583','101101101','离石'),('584','101101102','临县'),('585','101101103','兴县'),('586','101101104','岚县'),('587','101101105','柳林'),('588','101101106','石楼'),('589','101101107','方山'),('590','101101108','交口'),('591','101101109','中阳'),('592','101101110','孝义'),('593','101101111','汾阳'),('594','101101112','文水'),('595','101101113','交城'),('596','101110101','西安'),('597','101110102','长安'),('598','101110104','蓝田'),('599','101110105','周至'),('600','101110106','户县'),('601','101110107','高陵'),('602','101110200','咸阳'),('603','101110201','三原'),('604','101110202','礼泉'),('605','101110203','永寿'),('606','101110204','淳化'),('607','101110205','泾阳'),('608','101110206','武功'),('609','101110207','乾县'),('610','101110208','彬县'),('611','101110209','长武'),('612','101110210','旬邑'),('613','101110211','兴平'),('614','101110300','延安'),('615','101110401','榆林'),('616','101110402','府谷'),('617','101110403','神木'),('618','101110404','佳县'),('619','101110405','定边'),('620','101110406','靖边'),('621','101110407','横山'),('622','101110408','米脂'),('623','101110409','子洲'),('624','101110410','绥德'),('625','101110411','吴堡'),('626','101110412','清涧'),('627','101110501','渭南'),('628','101110502','华县'),('629','101110503','潼关'),('630','101110504','大荔'),('631','101110505','白水'),('632','101110506','富平'),('633','101110507','蒲城'),('634','101110508','澄城'),('635','101110509','合阳'),('636','101110510','韩城'),('637','101110511','华阴'),('638','101110601','商洛'),('639','101110602','洛南'),('640','101110603','柞水'),('641','101110604','商州'),('642','101110605','镇安'),('643','101110606','丹凤'),('644','101110607','商南'),('645','101110608','山阳'),('646','101110701','安康'),('647','101110702','紫阳'),('648','101110703','石泉'),('649','101110704','汉阴'),('650','101110705','旬阳'),('651','101110706','岚皋'),('652','101110707','平利'),('653','101110708','白河'),('654','101110709','镇坪'),('655','101110710','宁陕'),('656','101110801','汉中'),('657','101110802','略阳'),('658','101110803','勉县'),('659','101110804','留坝'),('660','101110805','洋县'),('661','101110806','城固'),('662','101110807','西乡'),('663','101110808','佛坪'),('664','101110809','宁强'),('665','101110810','南郑'),('666','101110811','镇巴'),('667','101110901','宝鸡'),('668','101110903','千阳'),('669','101110904','麟游'),('670','101110905','岐山'),('671','101110906','凤翔'),('672','101110907','扶风'),('673','101110908','眉县'),('674','101110909','太白'),('675','101110910','凤县'),('676','101110911','陇县'),('677','101111001','铜川'),('678','101111003','宜君'),('679','101111101','杨凌'),('680','101120101','济南'),('681','101120103','商河'),('682','101120104','章丘'),('683','101120105','平阴'),('684','101120106','济阳'),('685','101120201','青岛'),('686','101120204','即墨'),('687','101120205','胶州'),('688','101120206','胶南'),('689','101120207','莱西'),('690','101120208','平度'),('691','101120301','淄博'),('692','101120304','高青'),('693','101120306','沂源'),('694','101120307','桓台'),('695','101120401','德州'),('696','101120402','武城'),('697','101120403','临邑'),('698','101120404','陵县'),('699','101120405','齐河'),('700','101120406','乐陵'),('701','101120407','庆云'),('702','101120408','平原'),('703','101120409','宁津'),('704','101120410','夏津'),('705','101120411','禹城'),('706','101120501','烟台'),('707','101120502','莱州'),('708','101120503','长岛'),('709','101120504','蓬莱'),('710','101120505','龙口'),('711','101120506','招远'),('712','101120507','栖霞'),('713','101120510','莱阳'),('714','101120511','海阳'),('715','101120601','潍坊'),('716','101120602','青州'),('717','101120603','寿光'),('718','101120604','临朐'),('719','101120605','昌乐'),('720','101120606','昌邑'),('721','101120607','安丘'),('722','101120608','高密'),('723','101120609','诸城'),('724','101120701','济宁'),('725','101120702','嘉祥'),('726','101120703','微山');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('727','101120704','鱼台');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('728','101120705','兖州'),('729','101120706','金乡'),('730','101120707','汶上'),('731','101120708','泗水'),('732','101120709','梁山'),('733','101120710','曲阜'),('734','101120711','邹城'),('735','101120801','泰安'),('736','101120802','新泰'),('737','101120804','肥城'),('738','101120805','东平'),('739','101120806','宁阳'),('740','101120901','临沂'),('741','101120902','莒南'),('742','101120903','沂南'),('743','101120904','苍山'),('744','101120905','临沭'),('745','101120906','郯城'),('746','101120907','蒙阴'),('747','101120908','平邑'),('748','101120909','费县'),('749','101120910','沂水'),('750','101121001','菏泽'),('751','101121002','鄄城'),('752','101121003','郓城'),('753','101121004','东明'),('754','101121005','定陶'),('755','101121006','巨野'),('756','101121007','曹县'),('757','101121008','成武'),('758','101121009','单县'),('759','101121101','滨州'),('760','101121102','博兴'),('761','101121103','无棣'),('762','101121104','阳信'),('763','101121105','惠民'),('764','101121106','沾化'),('765','101121107','邹平'),('766','101121201','东营'),('767','101121203','垦利'),('768','101121204','利津'),('769','101121205','广饶'),('770','101121301','威海'),('771','101121302','文登'),('772','101121303','荣成'),('773','101121304','乳山'),('774','101121401','枣庄'),('775','101121405','滕州'),('776','101121501','日照'),('777','101121502','五莲'),('778','101121503','莒县'),('779','101121601','莱芜'),('780','101121701','聊城'),('781','101121702','冠县'),('782','101121703','阳谷'),('783','101121704','高唐'),('784','101121705','茌平'),('785','101121706','东阿'),('786','101121707','临清'),('787','101121709','莘县'),('788','101130101','乌鲁木齐'),('789','101130105','达坂城'),('790','101130201','克拉玛依'),('791','101130202','乌尔禾'),('792','101130203','白碱滩'),('793','101130301','石河子'),('794','101130401','昌吉'),('795','101130402','呼图壁'),('796','101130403','米泉'),('797','101130404','阜康'),('798','101130405','吉木萨尔'),('799','101130406','奇台'),('800','101130407','玛纳斯'),('801','101130408','木垒'),('802','101130501','吐鲁番'),('803','101130503','克州'),('804','101130504','鄯善'),('805','101130601','库尔勒'),('806','101130602','轮台'),('807','101130603','尉犁'),('808','101130604','若羌'),('809','101130605','且末'),('810','101130606','和静'),('811','101130607','焉耆'),('812','101130608','和硕'),('813','101130612','博湖'),('814','101130701','阿拉尔'),('815','101130801','阿克苏'),('816','101130802','乌什'),('817','101130803','温宿'),('818','101130804','拜城'),('819','101130805','新和'),('820','101130806','沙雅'),('821','101130807','库车'),('822','101130808','柯坪'),('823','101130809','阿瓦提'),('824','101130901','喀什'),('825','101130902','英吉沙'),('826','101130903','塔什库尔干'),('827','101130904','麦盖提'),('828','101130905','莎车'),('829','101130906','叶城'),('830','101130907','泽普'),('831','101130908','巴楚'),('832','101130909','岳普湖'),('833','101130910','伽师'),('834','101130911','疏附'),('835','101130912','疏勒'),('836','101131001','伊宁'),('837','101131002','察布查尔'),('838','101131003','尼勒克'),('839','101131005','巩留'),('840','101131006','新源'),('841','101131007','昭苏'),('842','101131008','特克斯'),('843','101131009','霍城'),('844','101131011','奎屯'),('845','101131101','塔城'),('846','101131102','裕民'),('847','101131103','额敏'),('848','101131104','和布克赛尔'),('849','101131105','托里'),('850','101131106','乌苏'),('851','101131107','沙湾'),('852','101131201','哈密'),('853','101131203','巴里坤'),('854','101131204','伊吾'),('855','101131301','和田'),('856','101131302','皮山'),('857','101131303','策勒'),('858','101131304','墨玉'),('859','101131305','洛浦'),('860','101131306','民丰'),('861','101131307','于田'),('862','101131401','阿勒泰'),('863','101131402','哈巴河'),('864','101131405','吉木乃'),('865','101131406','布尔津'),('866','101131407','福海'),('867','101131408','富蕴'),('868','101131409','青河'),('869','101131501','阿图什'),('870','101131502','乌恰'),('871','101131503','阿克陶'),('872','101131504','阿合奇'),('873','101131601','博乐'),('874','101131602','温泉'),('875','101131603','精河'),('876','101140101','拉萨'),('877','101140102','当雄'),('878','101140103','尼木'),('879','101140104','林周'),('880','101140105','堆龙德庆'),('881','101140106','曲水'),('882','101140107','达孜'),('883','101140108','墨竹工卡'),('884','101140201','日喀则'),('885','101140202','拉孜'),('886','101140204','聂拉木'),('887','101140205','定日'),('888','101140206','江孜'),('889','101140208','仲巴'),('890','101140209','萨嘎'),('891','101140210','吉隆'),('892','101140211','昂仁'),('893','101140212','定结'),('894','101140213','萨迦'),('895','101140214','谢通门'),('896','101140215','楠木林'),('897','101140216','岗巴'),('898','101140217','白朗'),('899','101140218','亚东'),('900','101140219','康马'),('901','101140220','仁布'),('902','101140301','山南'),('903','101140302','贡嘎'),('904','101140303','札囊');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('905','101140304','加查');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('906','101140305','浪卡子'),('907','101140306','错那'),('908','101140307','隆子'),('909','101140309','乃东'),('910','101140310','桑日'),('911','101140311','洛扎'),('912','101140312','措美'),('913','101140313','琼结'),('914','101140314','曲松'),('915','101140401','林芝'),('916','101140402','波密'),('917','101140403','米林'),('918','101140404','察隅'),('919','101140405','工布江达'),('920','101140406','朗县'),('921','101140407','墨脱'),('922','101140501','昌都'),('923','101140502','丁青'),('924','101140503','边坝'),('925','101140504','洛隆'),('926','101140505','左贡'),('927','101140506','芒康'),('928','101140507','类乌齐'),('929','101140508','八宿'),('930','101140509','江达'),('931','101140510','察雅'),('932','101140511','贡觉'),('933','101140601','那曲'),('934','101140602','尼玛'),('935','101140603','嘉黎'),('936','101140604','班戈'),('937','101140605','安多'),('938','101140606','索县'),('939','101140607','聂荣'),('940','101140608','巴青'),('941','101140609','比如'),('942','101140610','双湖'),('943','101140701','阿里'),('944','101140702','改则'),('945','101140703','申扎'),('946','101140705','普兰'),('947','101140706','札达'),('948','101140707','噶尔'),('949','101140708','日土'),('950','101140709','革吉'),('951','101140710','措勤'),('952','101150101','西宁'),('953','101150102','大通'),('954','101150103','湟源'),('955','101150104','湟中'),('956','101150201','海东'),('957','101150202','乐都'),('958','101150203','民和'),('959','101150204','互助'),('960','101150205','化隆'),('961','101150206','循化'),('962','101150208','平安'),('963','101150301','黄南'),('964','101150302','尖扎'),('965','101150303','泽库'),('966','101150305','同仁'),('967','101150401','海南'),('968','101150404','贵德'),('969','101150406','兴海'),('970','101150407','贵南'),('971','101150408','同德'),('972','101150409','共和'),('973','101150501','果洛'),('974','101150502','班玛'),('975','101150503','甘德'),('976','101150504','达日'),('977','101150505','久治'),('978','101150506','玛多'),('979','101150508','玛沁'),('980','101150601','玉树'),('981','101150602','称多'),('982','101150603','治多'),('983','101150604','杂多'),('984','101150605','囊谦'),('985','101150606','曲麻莱'),('986','101150701','海西'),('987','101150708','天峻'),('988','101150709','乌兰'),('989','101150716','德令哈'),('990','101150801','海北'),('991','101150802','门源'),('992','101150803','祁连'),('993','101150804','海晏'),('994','101150806','刚察'),('995','101150901','格尔木'),('996','101150902','都兰'),('997','101160101','兰州'),('998','101160102','皋兰'),('999','101160103','永登'),('1000','101160104','榆中'),('1001','101160201','定西'),('1002','101160202','通渭'),('1003','101160203','陇西'),('1004','101160204','渭源'),('1005','101160205','临洮'),('1006','101160206','漳县'),('1007','101160207','岷县'),('1008','101160301','平凉'),('1009','101160302','泾川'),('1010','101160303','灵台'),('1011','101160304','崇信'),('1012','101160305','华亭'),('1013','101160306','庄浪'),('1014','101160307','静宁'),('1015','101160401','庆阳'),('1016','101160402','西峰'),('1017','101160403','环县'),('1018','101160404','华池'),('1019','101160405','合水'),('1020','101160406','正宁'),('1021','101160407','宁县'),('1022','101160408','镇原'),('1023','101160409','庆城'),('1024','101160501','武威'),('1025','101160502','民勤'),('1026','101160503','古浪'),('1027','101160505','天祝'),('1028','101160601','金昌'),('1029','101160602','永昌'),('1030','101160701','张掖'),('1031','101160702','肃南'),('1032','101160703','民乐'),('1033','101160704','临泽'),('1034','101160705','高台'),('1035','101160706','山丹'),('1036','101160801','酒泉'),('1037','101160803','金塔'),('1038','101160804','阿克塞'),('1039','101160805','瓜州'),('1040','101160806','肃北'),('1041','101160807','玉门'),('1042','101160808','敦煌'),('1043','101160901','天水'),('1044','101160903','清水'),('1045','101160904','秦安'),('1046','101160905','甘谷'),('1047','101160906','武山'),('1048','101160907','张家川'),('1049','101161001','陇南'),('1050','101161002','成县'),('1051','101161003','文县'),('1052','101161004','宕昌'),('1053','101161005','康县'),('1054','101161006','西和'),('1055','101161007','礼县'),('1056','101161008','徽县'),('1057','101161009','两当'),('1058','101161101','临夏'),('1059','101161102','康乐'),('1060','101161103','永靖'),('1061','101161104','广河'),('1062','101161105','和政'),('1063','101161107','积石山'),('1064','101161201','合作'),('1065','101161202','临潭'),('1066','101161203','卓尼'),('1067','101161204','舟曲'),('1068','101161205','迭部'),('1069','101161206','玛曲'),('1070','101161207','碌曲'),('1071','101161208','夏河'),('1072','101161301','白银'),('1073','101161302','靖远'),('1074','101161303','会宁'),('1075','101161304','平川'),('1076','101161305','景泰'),('1077','101161401','嘉峪关'),('1078','101170101','银川'),('1079','101170102','永宁'),('1080','101170103','灵武'),('1081','101170104','贺兰'),('1082','101170201','石嘴山'),('1083','101170203','平罗');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1084','101170301','吴忠');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1085','101170302','同心'),('1086','101170303','盐池'),('1087','101170306','青铜峡'),('1088','101170401','固原'),('1089','101170402','西吉'),('1090','101170403','隆德'),('1091','101170404','泾源'),('1092','101170406','彭阳'),('1093','101170501','中卫'),('1094','101170502','中宁'),('1095','101170504','海原'),('1096','101180101','郑州'),('1097','101180102','巩义'),('1098','101180103','荥阳'),('1099','101180104','登封'),('1100','101180105','新密'),('1101','101180106','新郑'),('1102','101180107','中牟'),('1103','101180108','上街'),('1104','101180201','安阳'),('1105','101180202','汤阴'),('1106','101180203','滑县'),('1107','101180204','内黄'),('1108','101180205','林州'),('1109','101180301','新乡'),('1110','101180302','获嘉'),('1111','101180303','原阳'),('1112','101180304','辉县'),('1113','101180305','卫辉'),('1114','101180306','延津'),('1115','101180307','封丘'),('1116','101180308','长垣'),('1117','101180401','许昌'),('1118','101180402','鄢陵'),('1119','101180403','襄城'),('1120','101180404','长葛'),('1121','101180405','禹州'),('1122','101180501','平顶山'),('1123','101180502','郏县'),('1124','101180503','宝丰'),('1125','101180504','汝州'),('1126','101180505','叶县'),('1127','101180506','舞钢'),('1128','101180507','鲁山'),('1129','101180508','石龙'),('1130','101180601','信阳'),('1131','101180602','息县'),('1132','101180603','罗山'),('1133','101180604','光山'),('1134','101180605','新县'),('1135','101180606','淮滨'),('1136','101180607','潢川'),('1137','101180608','固始'),('1138','101180609','商城'),('1139','101180701','南阳'),('1140','101180702','南召'),('1141','101180703','方城'),('1142','101180704','社旗'),('1143','101180705','西峡'),('1144','101180706','内乡'),('1145','101180707','镇平'),('1146','101180708','淅川'),('1147','101180709','新野'),('1148','101180710','唐河'),('1149','101180711','邓州'),('1150','101180712','桐柏'),('1151','101180801','开封'),('1152','101180802','杞县'),('1153','101180803','尉氏'),('1154','101180804','通许'),('1155','101180805','兰考'),('1156','101180901','洛阳'),('1157','101180902','新安'),('1158','101180903','孟津'),('1159','101180904','宜阳'),('1160','101180905','洛宁'),('1161','101180906','伊川'),('1162','101180907','嵩县'),('1163','101180908','偃师'),('1164','101180909','栾川'),('1165','101180910','汝阳'),('1166','101180911','吉利'),('1167','101181001','商丘'),('1168','101181003','睢县'),('1169','101181004','民权'),('1170','101181005','虞城'),('1171','101181006','柘城'),('1172','101181007','宁陵'),('1173','101181008','夏邑'),('1174','101181009','永城'),('1175','101181101','焦作'),('1176','101181102','修武'),('1177','101181103','武陟'),('1178','101181104','沁阳'),('1179','101181106','博爱'),('1180','101181107','温县'),('1181','101181108','孟州'),('1182','101181201','鹤壁'),('1183','101181202','浚县'),('1184','101181203','淇县'),('1185','101181301','濮阳'),('1186','101181302','台前'),('1187','101181303','南乐'),('1188','101181304','清丰'),('1189','101181305','范县'),('1190','101181401','周口'),('1191','101181402','扶沟'),('1192','101181403','太康'),('1193','101181404','淮阳'),('1194','101181405','西华'),('1195','101181406','商水'),('1196','101181407','项城'),('1197','101181408','郸城'),('1198','101181409','鹿邑'),('1199','101181410','沈丘'),('1200','101181501','漯河'),('1201','101181502','临颍'),('1202','101181503','舞阳'),('1203','101181504','临颖'),('1204','101181601','驻马店'),('1205','101181602','西平'),('1206','101181603','遂平'),('1207','101181604','上蔡'),('1208','101181605','汝南'),('1209','101181606','泌阳'),('1210','101181607','平舆'),('1211','101181608','新蔡'),('1212','101181609','确山'),('1213','101181610','正阳'),('1214','101181701','三门峡'),('1215','101181702','灵宝'),('1216','101181703','渑池'),('1217','101181704','卢氏'),('1218','101181705','义马'),('1219','101181706','陕县'),('1220','101181801','济源'),('1221','101190101','南京'),('1222','101190102','溧水'),('1223','101190103','高淳'),('1224','101190104','江宁'),('1225','101190105','六合'),('1226','101190107','浦口'),('1227','101190201','无锡'),('1228','101190202','江阴'),('1229','101190203','宜兴'),('1230','101190204','锡山'),('1231','101190301','镇江'),('1232','101190302','丹阳'),('1233','101190303','扬中'),('1234','101190304','句容'),('1235','101190305','丹徒'),('1236','101190401','苏州'),('1237','101190402','常熟'),('1238','101190403','张家港'),('1239','101190404','昆山'),('1240','101190405','吴中'),('1241','101190407','吴江'),('1242','101190408','太仓'),('1243','101190501','南通'),('1244','101190502','海安'),('1245','101190503','如皋'),('1246','101190504','如东'),('1247','101190507','启东'),('1248','101190508','海门'),('1249','101190601','扬州'),('1250','101190602','宝应'),('1251','101190603','仪征'),('1252','101190604','高邮'),('1253','101190605','江都'),('1254','101190606','邗江'),('1255','101190701','盐城'),('1256','101190702','响水'),('1257','101190703','滨海'),('1258','101190704','阜宁'),('1259','101190705','射阳'),('1260','101190706','建湖');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1261','101190707','东台');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1262','101190708','大丰'),('1263','101190709','盐都'),('1264','101190801','徐州'),('1265','101190802','铜山'),('1266','101190803','丰县'),('1267','101190804','沛县'),('1268','101190805','邳州'),('1269','101190806','睢宁'),('1270','101190807','新沂'),('1271','101190901','淮安'),('1272','101190902','金湖'),('1273','101190903','盱眙'),('1274','101190904','洪泽'),('1275','101190905','涟水'),('1276','101191001','连云港'),('1277','101191002','东海'),('1278','101191003','赣榆'),('1279','101191004','灌云'),('1280','101191005','灌南'),('1281','101191101','常州'),('1282','101191102','溧阳'),('1283','101191103','金坛'),('1284','101191104','武进'),('1285','101191201','泰州'),('1286','101191202','兴化'),('1287','101191203','泰兴'),('1288','101191204','姜堰'),('1289','101191205','靖江'),('1290','101191301','宿迁'),('1291','101191302','沭阳'),('1292','101191303','泗阳'),('1293','101191304','泗洪'),('1294','101191305','宿豫'),('1295','101200101','武汉'),('1296','101200102','蔡甸'),('1297','101200103','黄陂'),('1298','101200104','新洲'),('1299','101200105','江夏'),('1300','101200106','东西湖'),('1301','101200201','襄阳'),('1302','101200202','襄州'),('1303','101200203','保康'),('1304','101200204','南漳'),('1305','101200205','宜城'),('1306','101200206','老河口'),('1307','101200207','谷城'),('1308','101200208','枣阳'),('1309','101200301','鄂州'),('1310','101200302','梁子湖'),('1311','101200401','孝感'),('1312','101200402','安陆'),('1313','101200403','云梦'),('1314','101200404','大悟'),('1315','101200405','应城'),('1316','101200406','汉川'),('1317','101200407','孝昌'),('1318','101200501','黄冈'),('1319','101200502','红安'),('1320','101200503','麻城'),('1321','101200504','罗田'),('1322','101200505','英山'),('1323','101200506','浠水'),('1324','101200507','蕲春'),('1325','101200508','黄梅'),('1326','101200509','武穴'),('1327','101200510','团风'),('1328','101200601','黄石'),('1329','101200602','大冶'),('1330','101200603','阳新'),('1331','101200604','铁山'),('1332','101200605','下陆'),('1333','101200606','西塞山'),('1334','101200701','咸宁'),('1335','101200702','赤壁'),('1336','101200703','嘉鱼'),('1337','101200704','崇阳'),('1338','101200705','通城'),('1339','101200706','通山'),('1340','101200801','荆州'),('1341','101200802','江陵'),('1342','101200803','公安'),('1343','101200804','石首'),('1344','101200805','监利'),('1345','101200806','洪湖'),('1346','101200807','松滋'),('1347','101200901','宜昌'),('1348','101200902','远安'),('1349','101200903','秭归'),('1350','101200904','兴山'),('1351','101200906','五峰'),('1352','101200907','当阳'),('1353','101200908','长阳'),('1354','101200909','宜都'),('1355','101200910','枝江'),('1356','101201001','恩施'),('1357','101201002','利川'),('1358','101201003','建始'),('1359','101201004','咸丰'),('1360','101201005','宣恩'),('1361','101201006','鹤峰'),('1362','101201007','来凤'),('1363','101201008','巴东'),('1364','101201101','十堰'),('1365','101201102','竹溪'),('1366','101201103','郧西'),('1367','101201104','郧县'),('1368','101201105','竹山'),('1369','101201106','房县'),('1370','101201107','丹江口'),('1371','101201108','茅箭'),('1372','101201109','张湾'),('1373','101201201','神农架'),('1374','101201301','随州'),('1375','101201302','广水'),('1376','101201401','荆门'),('1377','101201402','钟祥'),('1378','101201403','京山'),('1379','101201404','掇刀'),('1380','101201405','沙洋'),('1381','101201406','沙市'),('1382','101201501','天门'),('1383','101201601','仙桃'),('1384','101201701','潜江'),('1385','101210101','杭州'),('1386','101210102','萧山'),('1387','101210103','桐庐'),('1388','101210104','淳安'),('1389','101210105','建德'),('1390','101210106','余杭'),('1391','101210107','临安'),('1392','101210108','富阳'),('1393','101210201','湖州'),('1394','101210202','长兴'),('1395','101210203','安吉'),('1396','101210204','德清'),('1397','101210301','嘉兴'),('1398','101210302','嘉善'),('1399','101210303','海宁'),('1400','101210304','桐乡'),('1401','101210305','平湖'),('1402','101210306','海盐'),('1403','101210401','宁波'),('1404','101210403','慈溪'),('1405','101210404','余姚'),('1406','101210405','奉化'),('1407','101210406','象山'),('1408','101210408','宁海'),('1409','101210410','北仑'),('1410','101210411','鄞州'),('1411','101210501','绍兴'),('1412','101210502','诸暨'),('1413','101210503','上虞'),('1414','101210504','新昌'),('1415','101210505','嵊州'),('1416','101210601','台州'),('1417','101210603','玉环'),('1418','101210604','三门'),('1419','101210605','天台'),('1420','101210606','仙居'),('1421','101210607','温岭'),('1422','101210610','临海'),('1423','101210701','温州'),('1424','101210702','泰顺'),('1425','101210703','文成'),('1426','101210704','平阳'),('1427','101210705','瑞安'),('1428','101210706','洞头'),('1429','101210707','乐清'),('1430','101210708','永嘉'),('1431','101210709','苍南'),('1432','101210801','丽水'),('1433','101210802','遂昌'),('1434','101210803','龙泉'),('1435','101210804','缙云'),('1436','101210805','青田');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1437','101210806','云和');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1438','101210807','庆元'),('1439','101210808','松阳'),('1440','101210809','景宁'),('1441','101210901','金华'),('1442','101210902','浦江'),('1443','101210903','兰溪'),('1444','101210904','义乌'),('1445','101210905','东阳'),('1446','101210906','武义'),('1447','101210907','永康'),('1448','101210908','磐安'),('1449','101211001','衢州'),('1450','101211002','常山'),('1451','101211003','开化'),('1452','101211004','龙游'),('1453','101211005','江山'),('1454','101211101','舟山'),('1455','101211102','嵊泗'),('1456','101211104','岱山'),('1457','101220101','合肥'),('1458','101220102','长丰'),('1459','101220103','肥东'),('1460','101220104','肥西'),('1461','101220201','蚌埠'),('1462','101220202','怀远'),('1463','101220203','固镇'),('1464','101220204','五河'),('1465','101220301','芜湖'),('1466','101220302','繁昌'),('1467','101220304','南陵'),('1468','101220401','淮南'),('1469','101220402','凤台'),('1470','101220403','潘集'),('1471','101220501','马鞍山'),('1472','101220502','当涂'),('1473','101220601','安庆'),('1474','101220602','枞阳'),('1475','101220603','太湖'),('1476','101220604','潜山'),('1477','101220605','怀宁'),('1478','101220606','宿松'),('1479','101220607','望江'),('1480','101220608','岳西'),('1481','101220609','桐城'),('1482','101220701','宿州'),('1483','101220702','砀山'),('1484','101220703','灵璧'),('1485','101220704','泗县'),('1486','101220705','萧县'),('1487','101220801','阜阳'),('1488','101220802','阜南'),('1489','101220803','颍上'),('1490','101220804','临泉'),('1491','101220805','界首'),('1492','101220806','太和'),('1493','101220901','亳州'),('1494','101220902','涡阳'),('1495','101220903','利辛'),('1496','101220904','蒙城'),('1497','101221001','黄山'),('1498','101221004','祁门'),('1499','101221005','黟县'),('1500','101221006','歙县'),('1501','101221007','休宁'),('1502','101221101','滁州'),('1503','101221102','凤阳'),('1504','101221103','明光'),('1505','101221104','定远'),('1506','101221105','全椒'),('1507','101221106','来安'),('1508','101221107','天长'),('1509','101221201','淮北'),('1510','101221202','濉溪'),('1511','101221301','铜陵'),('1512','101221401','宣城'),('1513','101221402','泾县'),('1514','101221403','旌德'),('1515','101221404','宁国'),('1516','101221405','绩溪'),('1517','101221406','广德'),('1518','101221407','郎溪'),('1519','101221501','六安'),('1520','101221502','霍邱'),('1521','101221503','寿县'),('1522','101221505','金寨'),('1523','101221506','霍山'),('1524','101221507','舒城'),('1525','101221601','巢湖'),('1526','101221602','庐江'),('1527','101221603','无为'),('1528','101221604','含山'),('1529','101221605','和县'),('1530','101221701','池州'),('1531','101221702','东至'),('1532','101221703','青阳'),('1533','101221705','石台'),('1534','101230101','福州'),('1535','101230102','闽清'),('1536','101230103','闽侯'),('1537','101230104','罗源'),('1538','101230105','连江'),('1539','101230107','永泰'),('1540','101230108','平潭'),('1541','101230110','长乐'),('1542','101230111','福清'),('1543','101230201','厦门'),('1544','101230301','宁德'),('1545','101230302','古田'),('1546','101230303','霞浦'),('1547','101230304','寿宁'),('1548','101230305','周宁'),('1549','101230306','福安'),('1550','101230307','柘荣'),('1551','101230308','福鼎'),('1552','101230309','屏南'),('1553','101230401','莆田'),('1554','101230402','仙游'),('1555','101230404','涵江'),('1556','101230405','秀屿'),('1557','101230406','荔城'),('1558','101230407','城厢'),('1559','101230501','泉州'),('1560','101230502','安溪'),('1561','101230504','永春'),('1562','101230505','德化'),('1563','101230506','南安'),('1564','101230508','惠安'),('1565','101230509','晋江'),('1566','101230510','石狮'),('1567','101230601','漳州'),('1568','101230602','长泰'),('1569','101230603','南靖'),('1570','101230604','平和'),('1571','101230605','龙海'),('1572','101230606','漳浦'),('1573','101230607','诏安'),('1574','101230609','云霄'),('1575','101230610','华安'),('1576','101230701','龙岩'),('1577','101230702','长汀'),('1578','101230703','连城'),('1579','101230704','武平'),('1580','101230705','上杭'),('1581','101230706','永定'),('1582','101230707','漳平'),('1583','101230801','三明'),('1584','101230802','宁化'),('1585','101230803','清流'),('1586','101230804','泰宁'),('1587','101230805','将乐'),('1588','101230806','建宁'),('1589','101230807','明溪'),('1590','101230808','沙县'),('1591','101230809','尤溪'),('1592','101230810','永安'),('1593','101230811','大田'),('1594','101230901','南平'),('1595','101230902','顺昌'),('1596','101230903','光泽'),('1597','101230904','邵武'),('1598','101230905','武夷山'),('1599','101230906','浦城'),('1600','101230907','建阳'),('1601','101230908','松溪'),('1602','101230909','政和'),('1603','101230910','建瓯'),('1604','101231001','钓鱼岛'),('1605','101240101','南昌'),('1606','101240102','新建'),('1607','101240104','安义'),('1608','101240105','进贤'),('1609','101240201','九江'),('1610','101240202','瑞昌'),('1611','101240204','武宁'),('1612','101240205','德安'),('1613','101240206','永修');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1614','101240207','湖口');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1615','101240208','彭泽'),('1616','101240209','星子'),('1617','101240210','都昌'),('1618','101240212','修水'),('1619','101240301','上饶'),('1620','101240302','鄱阳'),('1621','101240303','婺源'),('1622','101240305','余干'),('1623','101240306','万年'),('1624','101240307','德兴'),('1625','101240309','弋阳'),('1626','101240310','横峰'),('1627','101240311','铅山'),('1628','101240312','玉山'),('1629','101240313','广丰'),('1630','101240401','抚州'),('1631','101240402','广昌'),('1632','101240403','乐安'),('1633','101240404','崇仁'),('1634','101240405','金溪'),('1635','101240406','资溪'),('1636','101240407','宜黄'),('1637','101240408','南城'),('1638','101240409','南丰'),('1639','101240410','黎川'),('1640','101240411','东乡'),('1641','101240501','宜春'),('1642','101240502','铜鼓'),('1643','101240503','宜丰'),('1644','101240504','万载'),('1645','101240505','上高'),('1646','101240506','靖安'),('1647','101240507','奉新'),('1648','101240508','高安'),('1649','101240509','樟树'),('1650','101240510','丰城'),('1651','101240601','吉安'),('1652','101240603','吉水'),('1653','101240604','新干'),('1654','101240605','峡江'),('1655','101240606','永丰'),('1656','101240607','永新'),('1657','101240608','井冈山'),('1658','101240609','万安'),('1659','101240610','遂川'),('1660','101240611','泰和'),('1661','101240612','安福'),('1662','101240701','赣州'),('1663','101240702','崇义'),('1664','101240703','上犹'),('1665','101240704','南康'),('1666','101240705','大余'),('1667','101240706','信丰'),('1668','101240707','宁都'),('1669','101240708','石城'),('1670','101240709','瑞金'),('1671','101240710','于都'),('1672','101240711','会昌'),('1673','101240712','安远'),('1674','101240713','全南'),('1675','101240714','龙南'),('1676','101240715','定南'),('1677','101240716','寻乌'),('1678','101240717','兴国'),('1679','101240718','赣县'),('1680','101240801','景德镇'),('1681','101240802','乐平'),('1682','101240803','浮梁'),('1683','101240901','萍乡'),('1684','101240902','莲花'),('1685','101240903','上栗'),('1686','101240905','芦溪'),('1687','101240906','湘东'),('1688','101241001','新余'),('1689','101241002','分宜'),('1690','101241101','鹰潭'),('1691','101241102','余江'),('1692','101241103','贵溪'),('1693','101250101','长沙'),('1694','101250102','宁乡'),('1695','101250103','浏阳'),('1696','101250105','望城'),('1697','101250201','湘潭'),('1698','101250202','韶山'),('1699','101250203','湘乡'),('1700','101250301','株洲'),('1701','101250302','攸县'),('1702','101250303','醴陵'),('1703','101250305','茶陵'),('1704','101250306','炎陵'),('1705','101250401','衡阳'),('1706','101250402','衡山'),('1707','101250403','衡东'),('1708','101250404','祁东'),('1709','101250406','常宁'),('1710','101250407','衡南'),('1711','101250408','耒阳'),('1712','101250501','郴州'),('1713','101250502','桂阳'),('1714','101250503','嘉禾'),('1715','101250504','宜章'),('1716','101250505','临武'),('1717','101250507','资兴'),('1718','101250508','汝城'),('1719','101250509','安仁'),('1720','101250510','永兴'),('1721','101250511','桂东'),('1722','101250512','苏仙'),('1723','101250601','常德'),('1724','101250602','安乡'),('1725','101250603','桃源'),('1726','101250604','汉寿'),('1727','101250605','澧县'),('1728','101250606','临澧'),('1729','101250607','石门'),('1730','101250608','津市'),('1731','101250700','益阳'),('1732','101250702','南县'),('1733','101250703','桃江'),('1734','101250704','安化'),('1735','101250705','沅江'),('1736','101250801','娄底'),('1737','101250802','双峰'),('1738','101250803','冷水江'),('1739','101250805','新化'),('1740','101250806','涟源'),('1741','101250901','邵阳'),('1742','101250902','隆回'),('1743','101250903','洞口'),('1744','101250904','新邵'),('1745','101250905','邵东'),('1746','101250906','绥宁'),('1747','101250907','新宁'),('1748','101250908','武冈'),('1749','101250909','城步'),('1750','101251001','岳阳'),('1751','101251002','华容'),('1752','101251003','湘阴'),('1753','101251004','汨罗'),('1754','101251005','平江'),('1755','101251006','临湘'),('1756','101251101','张家界'),('1757','101251102','桑植'),('1758','101251103','慈利'),('1759','101251104','武陵源'),('1760','101251201','怀化'),('1761','101251203','沅陵'),('1762','101251204','辰溪'),('1763','101251205','靖州'),('1764','101251206','会同'),('1765','101251207','通道'),('1766','101251208','麻阳'),('1767','101251209','新晃'),('1768','101251210','芷江'),('1769','101251211','溆浦'),('1770','101251212','中方'),('1771','101251213','洪江'),('1772','101251401','永州'),('1773','101251402','祁阳'),('1774','101251403','东安'),('1775','101251404','双牌'),('1776','101251405','道县'),('1777','101251406','宁远'),('1778','101251407','江永'),('1779','101251408','蓝山'),('1780','101251409','新田'),('1781','101251410','江华'),('1782','101251501','吉首'),('1783','101251502','保靖'),('1784','101251503','永顺'),('1785','101251504','古丈'),('1786','101251505','凤凰'),('1787','101251506','泸溪'),('1788','101251507','龙山'),('1789','101251508','花垣'),('1790','101260101','贵阳');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1791','101260102','白云');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1792','101260103','花溪'),('1793','101260104','乌当'),('1794','101260105','息烽'),('1795','101260106','开阳'),('1796','101260107','修文'),('1797','101260108','清镇'),('1798','101260109','小河'),('1799','101260110','云岩'),('1800','101260111','南明'),('1801','101260201','遵义'),('1802','101260203','仁怀'),('1803','101260204','绥阳'),('1804','101260205','湄潭'),('1805','101260206','凤冈'),('1806','101260207','桐梓'),('1807','101260208','赤水'),('1808','101260209','习水'),('1809','101260210','道真'),('1810','101260211','正安'),('1811','101260212','务川'),('1812','101260213','余庆'),('1813','101260214','汇川'),('1814','101260215','红花岗'),('1815','101260301','安顺'),('1816','101260302','普定'),('1817','101260303','镇宁'),('1818','101260304','平坝'),('1819','101260305','紫云'),('1820','101260306','关岭'),('1821','101260401','都匀'),('1822','101260402','贵定'),('1823','101260403','瓮安'),('1824','101260404','长顺'),('1825','101260405','福泉'),('1826','101260406','惠水'),('1827','101260407','龙里'),('1828','101260408','罗甸'),('1829','101260409','平塘'),('1830','101260410','独山'),('1831','101260411','三都'),('1832','101260412','荔波'),('1833','101260501','凯里'),('1834','101260502','岑巩'),('1835','101260503','施秉'),('1836','101260504','镇远'),('1837','101260505','黄平'),('1838','101260507','麻江'),('1839','101260508','丹寨'),('1840','101260509','三穗'),('1841','101260510','台江'),('1842','101260511','剑河'),('1843','101260512','雷山'),('1844','101260513','黎平'),('1845','101260514','天柱'),('1846','101260515','锦屏'),('1847','101260516','榕江'),('1848','101260517','从江'),('1849','101260601','铜仁'),('1850','101260602','江口'),('1851','101260603','玉屏'),('1852','101260604','万山特'),('1853','101260605','思南'),('1854','101260607','印江'),('1855','101260608','石阡'),('1856','101260609','沿河'),('1857','101260610','德江'),('1858','101260611','松桃'),('1859','101260701','毕节'),('1860','101260702','赫章'),('1861','101260703','金沙'),('1862','101260704','威宁'),('1863','101260705','大方'),('1864','101260706','纳雍'),('1865','101260707','织金'),('1866','101260708','黔西'),('1867','101260801','水城'),('1868','101260802','六枝特'),('1869','101260804','盘县'),('1870','101260901','兴义'),('1871','101260902','晴隆'),('1872','101260903','兴仁'),('1873','101260904','贞丰'),('1874','101260905','望谟'),('1875','101260907','安龙'),('1876','101260908','册亨'),('1877','101260909','普安'),('1878','101270101','成都'),('1879','101270103','新都'),('1880','101270104','温江'),('1881','101270105','金堂'),('1882','101270106','双流'),('1883','101270107','郫县'),('1884','101270108','大邑'),('1885','101270109','蒲江'),('1886','101270110','新津'),('1887','101270111','都江堰'),('1888','101270112','彭州'),('1889','101270113','邛崃'),('1890','101270114','崇州'),('1891','101270201','攀枝花'),('1892','101270203','米易'),('1893','101270204','盐边'),('1894','101270301','自贡'),('1895','101270302','富顺'),('1896','101270303','荣县'),('1897','101270401','绵阳'),('1898','101270402','三台'),('1899','101270403','盐亭'),('1900','101270404','安县'),('1901','101270405','梓潼'),('1902','101270406','北川'),('1903','101270407','平武'),('1904','101270408','江油'),('1905','101270501','南充'),('1906','101270502','南部'),('1907','101270503','营山'),('1908','101270504','蓬安'),('1909','101270505','仪陇'),('1910','101270506','西充'),('1911','101270507','阆中'),('1912','101270601','达州'),('1913','101270602','宣汉'),('1914','101270603','开江'),('1915','101270604','大竹'),('1916','101270605','渠县'),('1917','101270606','万源'),('1918','101270608','达县'),('1919','101270701','遂宁'),('1920','101270702','蓬溪'),('1921','101270703','射洪'),('1922','101270801','广安'),('1923','101270802','岳池'),('1924','101270803','武胜'),('1925','101270804','邻水'),('1926','101270805','华蓥'),('1927','101270901','巴中'),('1928','101270902','通江'),('1929','101270903','南江'),('1930','101270904','平昌'),('1931','101271001','泸州'),('1932','101271003','泸县'),('1933','101271004','合江'),('1934','101271005','叙永'),('1935','101271006','古蔺'),('1936','101271101','宜宾'),('1937','101271104','南溪'),('1938','101271105','江安'),('1939','101271106','长宁'),('1940','101271107','高县'),('1941','101271108','珙县'),('1942','101271109','筠连'),('1943','101271110','兴文'),('1944','101271111','屏山'),('1945','101271201','内江'),('1946','101271203','威远'),('1947','101271204','资中'),('1948','101271205','隆昌'),('1949','101271301','资阳'),('1950','101271302','安岳'),('1951','101271303','乐至'),('1952','101271304','简阳'),('1953','101271401','乐山'),('1954','101271402','犍为'),('1955','101271403','井研'),('1956','101271404','夹江'),('1957','101271405','沐川'),('1958','101271406','峨边'),('1959','101271407','马边'),('1960','101271409','峨眉山'),('1961','101271501','眉山'),('1962','101271502','仁寿'),('1963','101271503','彭山'),('1964','101271504','洪雅'),('1965','101271505','丹棱'),('1966','101271506','青神');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1967','101271601','凉山');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('1968','101271603','木里'),('1969','101271604','盐源'),('1970','101271605','德昌'),('1971','101271606','会理'),('1972','101271607','会东'),('1973','101271608','宁南'),('1974','101271609','普格'),('1975','101271610','西昌'),('1976','101271611','金阳'),('1977','101271612','昭觉'),('1978','101271613','喜德'),('1979','101271614','冕宁'),('1980','101271615','越西'),('1981','101271616','甘洛'),('1982','101271617','雷波'),('1983','101271618','美姑'),('1984','101271619','布拖'),('1985','101271701','雅安'),('1986','101271702','名山'),('1987','101271703','荥经'),('1988','101271704','汉源'),('1989','101271705','石棉'),('1990','101271706','天全'),('1991','101271707','芦山'),('1992','101271708','宝兴'),('1993','101271801','甘孜'),('1994','101271802','康定'),('1995','101271803','泸定'),('1996','101271804','丹巴'),('1997','101271805','九龙'),('1998','101271806','雅江'),('1999','101271807','道孚'),('2000','101271808','炉霍'),('2001','101271809','新龙'),('2002','101271810','德格'),('2003','101271811','白玉'),('2004','101271812','石渠'),('2005','101271813','色达'),('2006','101271814','理塘'),('2007','101271815','巴塘'),('2008','101271816','乡城'),('2009','101271817','稻城'),('2010','101271818','得荣'),('2011','101271901','阿坝'),('2012','101271902','汶川'),('2013','101271903','理县'),('2014','101271904','茂县'),('2015','101271905','松潘'),('2016','101271906','九寨沟'),('2017','101271907','金川'),('2018','101271908','小金'),('2019','101271909','黑水'),('2020','101271910','马尔康'),('2021','101271911','壤塘'),('2022','101271912','若尔盖'),('2023','101271913','红原'),('2024','101272001','德阳'),('2025','101272002','中江'),('2026','101272003','广汉'),('2027','101272004','什邡'),('2028','101272005','绵竹'),('2029','101272006','罗江'),('2030','101272101','广元'),('2031','101272102','旺苍'),('2032','101272103','青川'),('2033','101272104','剑阁'),('2034','101272105','苍溪'),('2035','101280101','广州'),('2036','101280102','番禺'),('2037','101280103','从化'),('2038','101280104','增城'),('2039','101280105','花都'),('2040','101280201','韶关'),('2041','101280202','乳源'),('2042','101280203','始兴'),('2043','101280204','翁源'),('2044','101280205','乐昌'),('2045','101280206','仁化'),('2046','101280207','南雄'),('2047','101280208','新丰'),('2048','101280209','曲江'),('2049','101280210','浈江'),('2050','101280211','武江'),('2051','101280301','惠州'),('2052','101280302','博罗'),('2053','101280303','惠阳'),('2054','101280304','惠东'),('2055','101280305','龙门'),('2056','101280401','梅州'),('2057','101280402','兴宁'),('2058','101280403','蕉岭'),('2059','101280404','大埔'),('2060','101280406','丰顺'),('2061','101280407','平远'),('2062','101280408','五华'),('2063','101280409','梅县'),('2064','101280501','汕头'),('2065','101280502','潮阳'),('2066','101280503','澄海'),('2067','101280504','南澳'),('2068','101280601','深圳'),('2069','101280701','珠海'),('2070','101280702','斗门'),('2071','101280703','金湾'),('2072','101280800','佛山'),('2073','101280801','顺德'),('2074','101280802','三水'),('2075','101280803','南海'),('2076','101280804','高明'),('2077','101280901','肇庆'),('2078','101280902','广宁'),('2079','101280903','四会'),('2080','101280905','德庆'),('2081','101280906','怀集'),('2082','101280907','封开'),('2083','101280908','高要'),('2084','101281001','湛江'),('2085','101281002','吴川'),('2086','101281003','雷州'),('2087','101281004','徐闻'),('2088','101281005','廉江'),('2089','101281006','赤坎'),('2090','101281007','遂溪'),('2091','101281008','坡头'),('2092','101281009','霞山'),('2093','101281010','麻章'),('2094','101281101','江门'),('2095','101281103','开平'),('2096','101281104','新会'),('2097','101281105','恩平'),('2098','101281106','台山'),('2099','101281108','鹤山'),('2100','101281109','江海'),('2101','101281201','河源'),('2102','101281202','紫金'),('2103','101281203','连平'),('2104','101281204','和平'),('2105','101281205','龙川'),('2106','101281206','东源'),('2107','101281301','清远'),('2108','101281302','连南'),('2109','101281303','连州'),('2110','101281304','连山'),('2111','101281305','阳山'),('2112','101281306','佛冈'),('2113','101281307','英德'),('2114','101281308','清新'),('2115','101281401','云浮'),('2116','101281402','罗定'),('2117','101281403','新兴'),('2118','101281404','郁南'),('2119','101281406','云安'),('2120','101281501','潮州'),('2121','101281502','饶平'),('2122','101281503','潮安'),('2123','101281601','东莞'),('2124','101281701','中山'),('2125','101281801','阳江'),('2126','101281802','阳春'),('2127','101281803','阳东'),('2128','101281804','阳西'),('2129','101281901','揭阳'),('2130','101281902','揭西'),('2131','101281903','普宁'),('2132','101281904','惠来'),('2133','101281905','揭东'),('2134','101282001','茂名'),('2135','101282002','高州'),('2136','101282003','化州'),('2137','101282004','电白'),('2138','101282005','信宜'),('2139','101282006','茂港'),('2140','101282101','汕尾'),('2141','101282102','海丰'),('2142','101282103','陆丰'),('2143','101282104','陆河');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('2144','101290101','昆明');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('2145','101290103','东川'),('2146','101290104','寻甸'),('2147','101290105','晋宁'),('2148','101290106','宜良'),('2149','101290107','石林'),('2150','101290108','呈贡'),('2151','101290109','富民'),('2152','101290110','嵩明'),('2153','101290111','禄劝'),('2154','101290112','安宁'),('2155','101290201','大理'),('2156','101290202','云龙'),('2157','101290203','漾濞'),('2158','101290204','永平'),('2159','101290205','宾川'),('2160','101290206','弥渡'),('2161','101290207','祥云'),('2162','101290208','巍山'),('2163','101290209','剑川'),('2164','101290210','洱源'),('2165','101290211','鹤庆'),('2166','101290212','南涧'),('2167','101290301','红河'),('2168','101290302','石屏'),('2169','101290303','建水'),('2170','101290304','弥勒'),('2171','101290305','元阳'),('2172','101290306','绿春'),('2173','101290307','开远'),('2174','101290308','个旧'),('2175','101290309','蒙自'),('2176','101290310','屏边'),('2177','101290311','泸西'),('2178','101290312','金平'),('2179','101290313','河口'),('2180','101290401','曲靖'),('2181','101290402','沾益'),('2182','101290403','陆良'),('2183','101290404','富源'),('2184','101290405','马龙'),('2185','101290406','师宗'),('2186','101290407','罗平'),('2187','101290408','会泽'),('2188','101290409','宣威'),('2189','101290501','保山'),('2190','101290503','龙陵'),('2191','101290504','施甸'),('2192','101290505','昌宁'),('2193','101290506','腾冲'),('2194','101290601','文山'),('2195','101290602','西畴'),('2196','101290603','马关'),('2197','101290604','麻栗坡'),('2198','101290605','砚山'),('2199','101290606','丘北'),('2200','101290607','广南'),('2201','101290608','富宁'),('2202','101290701','玉溪'),('2203','101290702','澄江'),('2204','101290703','江川'),('2205','101290704','通海'),('2206','101290705','华宁'),('2207','101290706','新平'),('2208','101290707','易门'),('2209','101290708','峨山'),('2210','101290709','元江'),('2211','101290801','楚雄'),('2212','101290802','大姚'),('2213','101290803','元谋'),('2214','101290804','姚安'),('2215','101290805','牟定'),('2216','101290806','南华'),('2217','101290807','武定'),('2218','101290808','禄丰'),('2219','101290809','双柏'),('2220','101290810','永仁'),('2221','101290901','普洱'),('2222','101290902','景谷'),('2223','101290903','景东'),('2224','101290904','澜沧'),('2225','101290906','墨江'),('2226','101290907','江城'),('2227','101290908','孟连'),('2228','101290909','西盟'),('2229','101290911','镇沅'),('2230','101290912','宁洱'),('2231','101291001','昭通'),('2232','101291002','鲁甸'),('2233','101291003','彝良'),('2234','101291004','镇雄'),('2235','101291005','威信'),('2236','101291006','巧家'),('2237','101291007','绥江'),('2238','101291008','永善'),('2239','101291009','盐津'),('2240','101291010','大关'),('2241','101291011','水富'),('2242','101291101','临沧'),('2243','101291102','沧源'),('2244','101291103','耿马'),('2245','101291104','双江'),('2246','101291105','凤庆'),('2247','101291106','永德'),('2248','101291107','云县'),('2249','101291108','镇康'),('2250','101291201','怒江'),('2251','101291203','福贡'),('2252','101291204','兰坪'),('2253','101291205','泸水'),('2254','101291207','贡山'),('2255','101291301','迪庆'),('2256','101291302','德钦'),('2257','101291303','维西'),('2258','101291401','丽江'),('2259','101291402','永胜'),('2260','101291403','华坪'),('2261','101291404','宁蒗'),('2262','101291501','德宏'),('2263','101291503','陇川'),('2264','101291504','盈江'),('2265','101291506','瑞丽'),('2266','101291507','梁河'),('2267','101291508','潞西'),('2268','101291601','西双版纳'),('2269','101291603','勐海'),('2270','101291605','勐腊'),('2271','101300101','南宁'),('2272','101300103','邕宁'),('2273','101300104','横县'),('2274','101300105','隆安'),('2275','101300106','马山'),('2276','101300107','上林'),('2277','101300108','武鸣'),('2278','101300109','宾阳'),('2279','101300201','崇左'),('2280','101300202','天等'),('2281','101300203','龙州'),('2282','101300204','凭祥'),('2283','101300205','大新'),('2284','101300206','扶绥'),('2285','101300207','宁明'),('2286','101300301','柳州'),('2287','101300302','柳城'),('2288','101300304','鹿寨'),('2289','101300305','柳江'),('2290','101300306','融安'),('2291','101300307','融水'),('2292','101300308','三江'),('2293','101300401','来宾'),('2294','101300402','忻城'),('2295','101300403','金秀'),('2296','101300404','象州'),('2297','101300405','武宣'),('2298','101300406','合山'),('2299','101300501','桂林'),('2300','101300503','龙胜'),('2301','101300504','永福'),('2302','101300505','临桂'),('2303','101300506','兴安'),('2304','101300507','灵川'),('2305','101300508','全州'),('2306','101300509','灌阳'),('2307','101300510','阳朔'),('2308','101300511','恭城'),('2309','101300512','平乐'),('2310','101300513','荔浦'),('2311','101300514','资源'),('2312','101300601','梧州'),('2313','101300602','藤县'),('2314','101300604','苍梧'),('2315','101300605','蒙山'),('2316','101300606','岑溪'),('2317','101300701','贺州'),('2318','101300702','昭平'),('2319','101300703','富川'),('2320','101300704','钟山');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('2321','101300801','贵港');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('2322','101300802','桂平'),('2323','101300803','平南'),('2324','101300901','玉林'),('2325','101300902','博白'),('2326','101300903','北流'),('2327','101300904','容县'),('2328','101300905','陆川'),('2329','101300906','兴业'),('2330','101301001','百色'),('2331','101301002','那坡'),('2332','101301003','田阳'),('2333','101301004','德保'),('2334','101301005','靖西'),('2335','101301006','田东'),('2336','101301007','平果'),('2337','101301008','隆林'),('2338','101301009','西林'),('2339','101301010','乐业'),('2340','101301011','凌云'),('2341','101301012','田林'),('2342','101301101','钦州'),('2343','101301102','浦北'),('2344','101301103','灵山'),('2345','101301201','河池'),('2346','101301202','天峨'),('2347','101301203','东兰'),('2348','101301204','巴马'),('2349','101301205','环江'),('2350','101301206','罗城'),('2351','101301207','宜州'),('2352','101301208','凤山'),('2353','101301209','南丹'),('2354','101301210','都安'),('2355','101301211','大化'),('2356','101301301','北海'),('2357','101301302','合浦'),('2358','101301303','涠洲岛'),('2359','101301401','防城港'),('2360','101301402','上思'),('2361','101301403','东兴'),('2362','101301405','防城'),('2363','101310101','海口'),('2364','101310201','三亚'),('2365','101310202','东方'),('2366','101310203','临高'),('2367','101310204','澄迈'),('2368','101310205','儋州'),('2369','101310206','昌江'),('2370','101310207','白沙'),('2371','101310208','琼中'),('2372','101310209','定安'),('2373','101310210','屯昌'),('2374','101310211','琼海'),('2375','101310212','文昌'),('2376','101310214','保亭'),('2377','101310215','万宁'),('2378','101310216','陵水'),('2379','101310221','乐东'),('2380','101310222','五指山'),('2381','101320101','香港'),('2382','101330101','澳门'),('2383','101340101','台北'),('2384','101340102','桃园'),('2385','101340103','新竹'),('2386','101340104','宜兰'),('2387','101340201','高雄'),('2388','101340202','嘉义'),('2389','101340203','台南'),('2390','101340204','台东'),('2391','101340205','屏东'),('2392','101340401','台中'),('2393','101340402','苗栗'),('2394','101340403','彰化'),('2395','101340404','南投'),('2396','101340405','花莲'),('2397','101340406','云林'),('2398','102010100','首尔'),('2399','103010100','东京'),('2400','103020100','大阪'),('2401','103040100','札幌'),('2402','103050100','福冈'),('2403','103090100','京都'),('2404','104010100','新加坡'),('2405','105010100','吉隆坡'),('2406','106010100','曼谷'),('2407','107010100','河内'),('2408','107020100','胡志明市'),('2409','108010100','仰光'),('2410','109010100','万象'),('2411','111010100','雅加达'),('2412','111080100','登巴萨'),('2413','112010100','德黑兰'),('2414','113010100','新德里'),('2415','113030100','孟买'),('2416','113090100','斯利那加'),('2417','114010100','伊斯兰堡'),('2418','114030100','卡拉奇'),('2419','114040100','白沙瓦'),('2420','115010100','塔什干'),('2421','117010100','科伦坡'),('2422','118010100','喀布尔'),('2423','118030100','坎大哈'),('2424','120010100','斯里巴加湾'),('2425','121010100','巴林'),('2426','124010100','阿布扎比'),('2427','124020100','迪拜'),('2428','127010100','平壤'),('2429','130010100','阿斯塔纳'),('2430','132010100','乌兰巴托'),('2431','136010100','马尼拉'),('2432','138010100','利雅得'),('2433','139010100','大马士革'),('2434','201010100','伦敦'),('2435','201050100','曼彻斯特'),('2436','202010100','巴黎'),('2437','202100100','马赛'),('2438','203010100','柏林'),('2439','203020100','法兰克福'),('2440','203050100','汉堡'),('2441','204010100','罗马'),('2442','204040100','米兰'),('2443','205010100','阿姆斯特丹'),('2444','206010100','马德里'),('2445','206020100','巴塞罗那'),('2446','207010100','哥本哈根'),('2447','208010100','莫斯科'),('2448','210020100','日内瓦'),('2449','210030100','苏黎世'),('2450','211010100','斯德哥尔摩'),('2451','214010100','里斯本'),('2452','215020100','伊斯坦布尔'),('2453','216010100','布鲁塞尔'),('2454','217010100','维也纳'),('2455','218010100','雅典'),('2456','222010100','赫尔辛基'),('2457','301010100','开罗'),('2458','302010100','开普敦'),('2459','302020100','约翰内斯堡'),('2460','303010100','突尼斯'),('2461','304020100','拉各斯'),('2462','305010100','阿尔及尔'),('2463','311010100','亚的斯亚贝巴'),('2464','317010100','内罗毕'),('2465','321020100','卡萨布兰卡'),('2466','327010100','达喀尔'),('2467','332010100','达累斯萨拉姆'),('2468','334010100','卢萨卡'),('2469','401010100','华盛顿'),('2470','401020101','迈阿密'),('2471','401020104','奥兰多'),('2472','401030101','亚特兰大'),('2473','401040101','洛杉矶'),('2474','401040102','圣弗朗西斯科'),('2475','401060100','波士顿'),('2476','401070101','芝加哥'),('2477','401100101','西雅图'),('2478','401110101','纽约'),('2479','401120108','休斯敦'),('2480','401370100','拉斯维加斯'),('2481','401480100','火奴鲁鲁'),('2482','404010100','渥太华'),('2483','404030100','温哥华'),('2484','404040100','多伦多'),('2485','404130100','埃德蒙顿'),('2486','404140100','卡尔加里');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('2487','404220100','温尼伯');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weather` */
 INSERT INTO `vifnn_weather` VALUES ('2488','404230100','魁北克'),('2489','404240100','蒙特利尔'),('2490','406010100','哈瓦那'),('2491','411010100','墨西哥城'),('2492','416010100','加拉加斯'),('2493','601020101','悉尼'),('2494','601030101','布里斯班'),('2495','601040101','阿德来德'),('2496','601060101','墨尔本'),('2497','601070101','珀斯'),('2498','606010100','惠灵顿'),('2499','606020100','奥克兰'),('2500','606030100','克莱斯特彻奇'),('2501','101030100','天津');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wecha_user` */
 DROP TABLE IF EXISTS `vifnn_wecha_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wecha_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `wecha_id` varchar(60) NOT NULL,
  PRIMARY KEY (`token`,`wecha_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wechat_group` */
 DROP TABLE IF EXISTS `vifnn_wechat_group`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wechat_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wechatgroupid` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `intro` varchar(200) NOT NULL DEFAULT '',
  `token` varchar(30) NOT NULL DEFAULT '',
  `fanscount` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `wechatgroupid` (`wechatgroupid`,`token`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_wechat_group` */
 INSERT INTO `vifnn_wechat_group` VALUES ('1','0','未分组','','ezrehr1458789061','1'),('2','1','黑名单','','ezrehr1458789061','0'),('3','2','星标组','','ezrehr1458789061','0');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wechat_group_list` */
 DROP TABLE IF EXISTS `vifnn_wechat_group_list`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wechat_group_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `g_id` varchar(20) NOT NULL DEFAULT '',
  `nickname` varchar(60) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `province` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(30) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `subscribe_time` int(11) NOT NULL,
  `token` varchar(30) NOT NULL,
  `openid` varchar(60) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `g_id` (`g_id`) USING BTREE,
  KEY `token` (`token`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  FULLTEXT KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wechat_scene` */
 DROP TABLE IF EXISTS `vifnn_wechat_scene`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wechat_scene` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` char(30) NOT NULL,
  `title` char(50) NOT NULL,
  `pic` char(100) NOT NULL,
  `intro` varchar(250) NOT NULL,
  `shake_id` int(10) unsigned NOT NULL,
  `wall_id` int(10) unsigned NOT NULL,
  `vote_id` char(25) NOT NULL,
  `is_open` enum('0','1') NOT NULL,
  `open_vote` enum('0','1') NOT NULL,
  `open_zzle` enum('0','1') NOT NULL,
  `open_lottery` enum('0','1') NOT NULL,
  `lottery_type` tinyint(4) NOT NULL,
  `token` char(20) NOT NULL,
  `logo` char(100) NOT NULL,
  `background` char(100) NOT NULL,
  `qrcode` char(100) NOT NULL,
  `notice` text NOT NULL,
  `red_packet_id` int(10) unsigned NOT NULL,
  `jiugongge_id` int(10) unsigned NOT NULL,
  `zajindan_id` int(10) unsigned NOT NULL,
  `shuiguoji_id` int(10) unsigned NOT NULL,
  `guaguaka_id` int(10) unsigned NOT NULL,
  `dazhuanpan_id` int(10) unsigned NOT NULL,
  `shake_background` varchar(200) NOT NULL,
  `signin_background` varchar(200) NOT NULL,
  `lottery_background` varchar(200) NOT NULL,
  `guajiang_background` varchar(200) NOT NULL,
  `hongbao_background` varchar(200) NOT NULL,
  `luckyfruit_background` varchar(200) NOT NULL,
  `vote_background` varchar(200) NOT NULL,
  `goldenegg_background` varchar(200) NOT NULL,
  `wall_check` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `shake_keyword` varchar(200) NOT NULL,
  `lottery_keyword` varchar(200) NOT NULL,
  `guajiang_keyword` varchar(200) NOT NULL,
  `jiugongge_keyword` varchar(200) NOT NULL,
  `hongbao_keyword` varchar(200) NOT NULL,
  `luckyfruit_keyword` varchar(200) NOT NULL,
  `vote_keyword` varchar(200) NOT NULL,
  `goldenegg_keyword` varchar(200) NOT NULL,
  `ddp_is_sex` int(11) NOT NULL DEFAULT '1',
  `jiugongge_background` varchar(200) NOT NULL,
  `supperzzle_background` varchar(200) NOT NULL,
  `prize_background` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_wechat_scene` */
 INSERT INTO `vifnn_wechat_scene` VALUES ('1','22','12阿瓦斯短发散发的是','','','0','0','','1','1','1','1','0','kpktkq1416817563','./tpl/static/wall/images/default_logo.png','./tpl/static/wall/images/default_bg.jpg','','','0','0','0','0','0','0','','','','','','','','','0','','','','','','','','','1','','',''),('2','关于我们','投票开始了','','','0','0','','1','1','1','1','0','mbeboo1416823194','./tpl/static/wall/images/default_logo.png','./tpl/static/wall/images/default_bg.jpg','','','0','0','0','0','0','0','','','','','','','','','0','','','','','','','','','1','','','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wecht_grout` */
 DROP TABLE IF EXISTS `vifnn_wecht_grout`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wecht_grout` (
  `id` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `w_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `count` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wedding` */
 DROP TABLE IF EXISTS `vifnn_wedding`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wedding` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `title` varchar(150) NOT NULL,
  `picurl` varchar(150) NOT NULL,
  `openpic` varchar(200) NOT NULL,
  `coverurl` varchar(200) NOT NULL,
  `woman` varchar(30) NOT NULL,
  `man` varchar(30) NOT NULL,
  `who_first` tinyint(1) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `place` varchar(200) NOT NULL,
  `lng` varchar(16) NOT NULL,
  `lat` varchar(16) NOT NULL,
  `video` varchar(200) NOT NULL,
  `mp3url` varchar(200) NOT NULL,
  `passowrd` int(20) NOT NULL,
  `word` varchar(200) NOT NULL,
  `qr_code` varchar(200) NOT NULL,
  `copyrite` varchar(150) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wedding_info` */
 DROP TABLE IF EXISTS `vifnn_wedding_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wedding_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `num` tinyint(2) NOT NULL,
  `info` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wehcat_member_enddate` */
 DROP TABLE IF EXISTS `vifnn_wehcat_member_enddate`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wehcat_member_enddate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(60) NOT NULL,
  `enddate` int(11) NOT NULL,
  `joinUpDate` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `token` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`) USING BTREE,
  KEY `openid_2` (`openid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_wehcat_member_enddate` */
 INSERT INTO `vifnn_wehcat_member_enddate` VALUES ('1','opJiSw6wdBEB5QzJ-lgRixw50p5o','1459407629','0','0','ezrehr1458789061'),('2','opJiSw_cuETnHqum0vB0_1tg8F1Q','1459406003','0','0','ezrehr1458789061'),('3','o4xIKt6khyG50BfQy8QuZ3iAsRTY','1462895119','0','0','zbemii1462891866'),('4','o4xIKt1UdTsU6VAl_O12XjHcsIj0','1462912200','0','0','zbemii1462891866'),('5','o4xIKt_XtAoToHq1DOBj5hlC3MDM','1463076607','0','0','zbemii1462891866'),('6','o4xIKt9CxvF0GgDcC_dgLdULeWFQ','1463206124','0','0','zbemii1462891866'),('7','o4xIKt9E88SQR1uKlC_ZSrpyDP7c','1463224212','0','0','zbemii1462891866'),('8','o4xIKt5OA0yRNqATZHYFEBuFkcug','1463376212','0','0','zbemii1462891866'),('9','o4xIKt9EK8N26tW_is7jsb48AYPM','1463443353','0','0','zbemii1462891866'),('10','o4xIKt3HUWJ3N5r8rqkZY7yK1kyA','1463451789','0','0','zbemii1462891866'),('11','o4xIKt_KSjRLRKosyZC81Ztpl6mY','1463471930','0','0','zbemii1462891866'),('12','o4xIKt4SRUCVIKyw5wLRJXU-6RFc','1463498192','0','0','zbemii1462891866'),('13','o4xIKt2iLt9xORh7cocUSn3TeDzw','1463582239','0','0','zbemii1462891866'),('14','o4xIKt409CN7IYEdQfocohe5eYQQ','1463619536','0','0','zbemii1462891866'),('15','o4xIKt4mJ76am75xIuAPEIN830Fs','1463620021','0','0','zbemii1462891866'),('16','o4xIKt-iRgPmZBGYzZzqnH-03_2I','1463638019','0','0','zbemii1462891866'),('17','o4xIKt-yVbuLaH_wFNVZd7dJAf1U','1463664987','0','0','zbemii1462891866'),('18','','1465646163','0','0','ywxgfh1465574691'),('19','','1465712747','0','0','ywxgfh1465574691'),('20','','1465785502','0','0','ywxgfh1465574691'),('21','','1465788440','0','0','ywxgfh1465574691');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weilivereply_info` */
 DROP TABLE IF EXISTS `vifnn_weilivereply_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weilivereply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `picurl` varchar(120) NOT NULL DEFAULT '',
  `info` varchar(120) NOT NULL DEFAULT '',
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `copyright` text NOT NULL,
  `tel` varchar(11) NOT NULL,
  `biaoti` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weixin_account` */
 DROP TABLE IF EXISTS `vifnn_weixin_account`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weixin_account` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `appId` char(45) NOT NULL,
  `appSecret` char(45) NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `encodingAesKey` varchar(70) DEFAULT NULL,
  `type` tinyint(3) DEFAULT NULL COMMENT '1开放平台公众号服务，2其他',
  `date_time` char(15) DEFAULT NULL,
  `component_verify_ticket` varchar(100) DEFAULT NULL,
  `component_access_token` varchar(150) NOT NULL,
  `token_expires` char(15) NOT NULL,
  `agentid` int(10) unsigned NOT NULL,
  `pre_auth_code` varchar(200) NOT NULL,
  `pre_code_expires` char(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_weixin_account` */
 INSERT INTO `vifnn_weixin_account` VALUES ('1','wxce04760a2723d4ab','9a7d6ee461cbc20009eed99cd8ad59a4','sitore_com','3pWleVb5NR6uRsZ4kQzbSf6fybaMiycMke7AguabxHM','1','1465797118','ticket@@@YHXt-sJ8UuhH3llY3biFfNw0MagNmz60cPDQeZsNcL8lvWCJA9oudTM0NG40GqgO6jBfjwjSAzUAF5sjpouvmw','72GfKibFLeQFcWSLmBrkb1siCugVnboMlXn_8l3qFx17H8mItkDS0zRXibq7ORgNtLbdLJJIju5eiGiPpxxj-w7rsoBIEurdULBaZLxiUn-yXhtb0OZhSSubfr0UlfzFNRAfAJABFO','1465789661','0','','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weixin_bill` */
 DROP TABLE IF EXISTS `vifnn_weixin_bill`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weixin_bill` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` varchar(60) NOT NULL,
  `price` float NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `from` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `third_id` varchar(64) NOT NULL COMMENT '第三方支付id',
  `plat_type` tinyint(1) unsigned NOT NULL COMMENT '微信支付到账号来源（0：当前的微信号，1：系统平台的账号，2：自己公司的其他账号）',
  `appid` varchar(64) NOT NULL COMMENT '支付到账号的appid',
  `mchid` varchar(64) NOT NULL COMMENT '支付到账号的商户ID',
  PRIMARY KEY (`vifnn_id`),
  KEY `time` (`time`) USING BTREE,
  KEY `orderid` (`orderid`,`from`) USING BTREE,
  KEY `third_id` (`third_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weixin_pay` */
 DROP TABLE IF EXISTS `vifnn_weixin_pay`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weixin_pay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(40) NOT NULL,
  `appsecret` varchar(200) NOT NULL,
  `merchant_id` varchar(20) NOT NULL,
  `key` varchar(200) NOT NULL,
  `system` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  `apiclient_cert` varchar(255) NOT NULL,
  `apiclient_key` varchar(255) NOT NULL,
  `rootca` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weixin_region` */
 DROP TABLE IF EXISTS `vifnn_weixin_region`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weixin_region` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=482 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_weixin_region` */
 INSERT INTO `vifnn_weixin_region` VALUES ('1','四川','凉山'),('2','四川','资阳'),('3','四川','成都'),('4','四川','自贡'),('5','四川','泸州'),('6','四川','攀枝花'),('7','四川','绵阳'),('8','四川','德阳'),('9','四川','遂宁'),('10','四川','广元'),('11','四川','乐山'),('12','四川','内江'),('13','四川','南充'),('14','四川','宜宾'),('15','四川','眉山'),('16','四川','达州'),('17','四川','广安'),('18','四川','巴中'),('19','四川','雅安'),('20','四川','甘孜'),('21','四川','阿坝'),('22','重庆','酉阳'),('23','重庆','彭水'),('24','重庆','合川'),('25','重庆','永川'),('26','重庆','江津'),('27','重庆','南川'),('28','重庆','铜梁'),('29','重庆','大足'),('30','重庆','荣昌'),('31','重庆','璧山'),('32','重庆','长寿'),('33','重庆','綦江'),('34','重庆','潼南'),('35','重庆','梁平'),('36','重庆','城口'),('37','重庆','石柱'),('38','重庆','秀山'),('39','重庆','万州'),('40','重庆','渝中'),('41','重庆','涪陵'),('42','重庆','江北'),('43','重庆','大渡口'),('44','重庆','九龙坡'),('45','重庆','沙坪坝'),('46','重庆','北碚'),('47','重庆','南岸'),('48','重庆','黔江'),('49','重庆','巫溪'),('50','重庆','双桥'),('51','重庆','万盛'),('52','重庆','巴南'),('53','重庆','渝北'),('54','重庆','忠县'),('55','重庆','武隆'),('56','重庆','垫江'),('57','重庆','丰都'),('58','重庆','巫山'),('59','重庆','奉节'),('60','重庆','云阳'),('61','重庆','开县'),('62','陕西','商洛'),('63','陕西','西安'),('64','陕西','宝鸡'),('65','陕西','铜川'),('66','陕西','渭南'),('67','陕西','咸阳'),('68','陕西','汉中'),('69','陕西','延安'),('70','陕西','安康'),('71','陕西','榆林'),('72','甘肃','定西'),('73','甘肃','庆阳'),('74','甘肃','陇南'),('75','甘肃','甘南'),('76','甘肃','临夏'),('77','甘肃','兰州'),('78','甘肃','金昌'),('79','甘肃','嘉峪关'),('80','甘肃','天水'),('81','甘肃','白银'),('82','甘肃','张掖'),('83','甘肃','武威'),('84','甘肃','酒泉'),('85','甘肃','平凉'),('86','青海','海南'),('87','青海','果洛'),('88','青海','玉树'),('89','青海','海东'),('90','青海','海北'),('91','青海','黄南'),('92','青海','海西'),('93','青海','西宁'),('94','宁夏','银川'),('95','宁夏','吴忠'),('96','宁夏','石嘴山'),('97','宁夏','中卫'),('98','宁夏','固原'),('99','云南','红河'),('100','云南','文山'),('101','云南','楚雄'),('102','云南','怒江'),('103','云南','德宏'),('104','云南','西双版纳'),('105','云南','大理'),('106','云南','迪庆'),('107','云南','昆明'),('108','云南','曲靖'),('109','云南','保山'),('110','云南','玉溪'),('111','云南','丽江'),('112','云南','昭通'),('113','云南','临沧'),('114','云南','普洱'),('115','澳门','None'),('116','贵州','毕节'),('117','贵州','黔东南'),('118','贵州','黔南'),('119','贵州','铜仁'),('120','贵州','黔西南'),('121','贵州','贵阳'),('122','贵州','遵义'),('123','贵州','六盘水'),('124','贵州','安顺'),('125','香港','None'),('126','辽宁','盘锦'),('127','辽宁','辽阳'),('128','辽宁','朝阳'),('129','辽宁','铁岭'),('130','辽宁','葫芦岛'),('131','辽宁','沈阳'),('132','辽宁','鞍山'),('133','辽宁','大连'),('134','辽宁','本溪'),('135','辽宁','抚顺'),('136','辽宁','锦州'),('137','辽宁','丹东'),('138','辽宁','阜新'),('139','辽宁','营口'),('140','吉林','延边'),('141','吉林','长春'),('142','吉林','四平'),('143','吉林','吉林'),('144','吉林','通化'),('145','吉林','辽源'),('146','吉林','松原'),('147','吉林','白山'),('148','吉林','白城'),('149','黑龙江','黑河'),('150','黑龙江','牡丹江'),('151','黑龙江','绥化'),('152','黑龙江','哈尔滨'),('153','黑龙江','大兴安岭'),('154','黑龙江','鸡西'),('155','黑龙江','齐齐哈尔'),('156','黑龙江','双鸭山'),('157','黑龙江','鹤岗'),('158','黑龙江','伊春'),('159','黑龙江','大庆'),('160','黑龙江','七台河'),('161','黑龙江','佳木斯'),('162','海南','乐东'),('163','海南','昌江'),('164','海南','白沙'),('165','海南','西沙'),('166','海南','琼中'),('167','海南','保亭'),('168','海南','陵水'),('169','海南','中沙'),('170','海南','南沙'),('171','海南','海口'),('172','海南','三亚'),('173','海南','五指山'),('174','海南','儋州'),('175','海南','琼海'),('176','海南','文昌'),('177','海南','东方'),('178','海南','万宁'),('179','海南','定安'),('180','海南','屯昌'),('181','海南','澄迈'),('182','海南','临高'),('183','广东','揭阳'),('184','广东','中山'),('185','广东','广州'),('186','广东','深圳'),('187','广东','韶关'),('188','广东','汕头'),('189','广东','珠海'),('190','广东','江门'),('191','广东','佛山'),('192','广东','茂名'),('193','广东','湛江'),('194','广东','惠州'),('195','广东','肇庆'),('196','广东','汕尾'),('197','广东','梅州'),('198','广东','阳江'),('199','广东','河源'),('200','广东','东莞'),('201','广东','清远'),('202','广东','潮州'),('203','广东','云浮'),('204','广西','贺州'),('205','广西','百色');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weixin_region` */
 INSERT INTO `vifnn_weixin_region` VALUES ('206','广西','来宾');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weixin_region` */
 INSERT INTO `vifnn_weixin_region` VALUES ('207','广西','河池'),('208','广西','崇左'),('209','广西','南宁'),('210','广西','桂林'),('211','广西','柳州'),('212','广西','北海'),('213','广西','梧州'),('214','广西','钦州'),('215','广西','防城港'),('216','广西','玉林'),('217','广西','贵港'),('218','湖北','黄冈'),('219','湖北','荆州'),('220','湖北','随州'),('221','湖北','咸宁'),('222','湖北','神农架'),('223','湖北','恩施'),('224','湖北','武汉'),('225','湖北','十堰'),('226','湖北','黄石'),('227','湖北','宜昌'),('228','湖北','鄂州'),('229','湖北','襄樊'),('230','湖北','孝感'),('231','湖北','荆门'),('232','湖北','潜江'),('233','湖北','仙桃'),('234','湖北','天门'),('235','湖南','永州'),('236','湖南','郴州'),('237','湖南','娄底'),('238','湖南','怀化'),('239','湖南','湘西'),('240','湖南','长沙'),('241','湖南','湘潭'),('242','湖南','株洲'),('243','湖南','邵阳'),('244','湖南','衡阳'),('245','湖南','常德'),('246','湖南','岳阳'),('247','湖南','益阳'),('248','湖南','张家界'),('249','河南','漯河'),('250','河南','许昌'),('251','河南','南阳'),('252','河南','三门峡'),('253','河南','信阳'),('254','河南','商丘'),('255','河南','驻马店'),('256','河南','周口'),('257','河南','济源'),('258','河南','郑州'),('259','河南','洛阳'),('260','河南','开封'),('261','河南','安阳'),('262','河南','平顶山'),('263','河南','新乡'),('264','河南','鹤壁'),('265','河南','濮阳'),('266','河南','焦作'),('267','台湾','屏东县'),('268','台湾','澎湖县'),('269','台湾','台东县'),('270','台湾','花莲县'),('271','台湾','台北市'),('272','台湾','基隆市'),('273','台湾','高雄市'),('274','台湾','台南市'),('275','台湾','台中市'),('276','台湾','嘉义市'),('277','台湾','新竹市'),('278','台湾','宜兰县'),('279','台湾','台北县'),('280','台湾','新竹县'),('281','台湾','桃园县'),('282','台湾','台中县'),('283','台湾','苗栗县'),('284','台湾','南投县'),('285','台湾','彰化县'),('286','台湾','嘉义县'),('287','台湾','云林县'),('288','台湾','高雄县'),('289','台湾','台南县'),('290','北京','房山'),('291','北京','大兴'),('292','北京','顺义'),('293','北京','通州'),('294','北京','昌平'),('295','北京','密云'),('296','北京','平谷'),('297','北京','延庆'),('298','北京','东城'),('299','北京','怀柔'),('300','北京','崇文'),('301','北京','西城'),('302','北京','朝阳'),('303','北京','宣武'),('304','北京','石景山'),('305','北京','丰台'),('306','北京','门头沟'),('307','北京','海淀'),('308','河北','衡水'),('309','河北','廊坊'),('310','河北','石家庄'),('311','河北','秦皇岛'),('312','河北','唐山'),('313','河北','邢台'),('314','河北','邯郸'),('315','河北','张家口'),('316','河北','保定'),('317','河北','沧州'),('318','河北','承德'),('319','天津','西青'),('320','天津','东丽'),('321','天津','北辰'),('322','天津','津南'),('323','天津','宁河'),('324','天津','武清'),('325','天津','静海'),('326','天津','宝坻'),('327','天津','和平'),('328','天津','河西'),('329','天津','河东'),('330','天津','河北'),('331','天津','南开'),('332','天津','塘沽'),('333','天津','红桥'),('334','天津','大港'),('335','天津','汉沽'),('336','天津','蓟县'),('337','内蒙古','锡林郭勒'),('338','内蒙古','兴安'),('339','内蒙古','阿拉善'),('340','内蒙古','呼和浩特'),('341','内蒙古','乌海'),('342','内蒙古','包头'),('343','内蒙古','通辽'),('344','内蒙古','赤峰'),('345','内蒙古','呼伦贝尔'),('346','内蒙古','鄂尔多斯'),('347','内蒙古','乌兰察布'),('348','内蒙古','巴彦淖尔'),('349','山西','吕梁'),('350','山西','临汾'),('351','山西','太原'),('352','山西','阳泉'),('353','山西','大同'),('354','山西','晋城'),('355','山西','长治'),('356','山西','晋中'),('357','山西','朔州'),('358','山西','忻州'),('359','山西','运城'),('360','浙江','丽水'),('361','浙江','台州'),('362','浙江','杭州'),('363','浙江','温州'),('364','浙江','宁波'),('365','浙江','湖州'),('366','浙江','嘉兴'),('367','浙江','金华'),('368','浙江','绍兴'),('369','浙江','舟山'),('370','浙江','衢州'),('371','江苏','镇江'),('372','江苏','扬州'),('373','江苏','宿迁'),('374','江苏','泰州'),('375','江苏','南京'),('376','江苏','徐州'),('377','江苏','无锡'),('378','江苏','苏州'),('379','江苏','常州'),('380','江苏','连云港'),('381','江苏','南通'),('382','江苏','盐城'),('383','江苏','淮安'),('384','上海','杨浦'),('385','上海','南汇'),('386','上海','宝山'),('387','上海','闵行'),('388','上海','浦东新'),('389','上海','嘉定'),('390','上海','松江'),('391','上海','金山'),('392','上海','崇明'),('393','上海','奉贤'),('394','上海','青浦'),('395','上海','黄浦'),('396','上海','卢湾'),('397','上海','长宁'),('398','上海','徐汇'),('399','上海','普陀'),('400','上海','静安'),('401','上海','虹口'),('402','上海','闸北'),('403','山东','日照');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weixin_region` */
 INSERT INTO `vifnn_weixin_region` VALUES ('404','山东','威海');/* MySQLReback Separation */
 /* 插入数据 `vifnn_weixin_region` */
 INSERT INTO `vifnn_weixin_region` VALUES ('405','山东','临沂'),('406','山东','莱芜'),('407','山东','聊城'),('408','山东','德州'),('409','山东','菏泽'),('410','山东','滨州'),('411','山东','济南'),('412','山东','淄博'),('413','山东','青岛'),('414','山东','东营'),('415','山东','枣庄'),('416','山东','潍坊'),('417','山东','烟台'),('418','山东','泰安'),('419','山东','济宁'),('420','江西','上饶'),('421','江西','抚州'),('422','江西','南昌'),('423','江西','萍乡'),('424','江西','景德镇'),('425','江西','新余'),('426','江西','九江'),('427','江西','赣州'),('428','江西','鹰潭'),('429','江西','宜春'),('430','江西','吉安'),('431','福建','福州'),('432','福建','莆田'),('433','福建','厦门'),('434','福建','泉州'),('435','福建','三明'),('436','福建','南平'),('437','福建','漳州'),('438','福建','宁德'),('439','福建','龙岩'),('440','安徽','滁州'),('441','安徽','黄山'),('442','安徽','宿州'),('443','安徽','阜阳'),('444','安徽','六安'),('445','安徽','巢湖'),('446','安徽','池州'),('447','安徽','亳州'),('448','安徽','宣城'),('449','安徽','合肥'),('450','安徽','蚌埠'),('451','安徽','芜湖'),('452','安徽','马鞍山'),('453','安徽','淮南'),('454','安徽','铜陵'),('455','安徽','淮北'),('456','安徽','安庆'),('457','西藏','那曲'),('458','西藏','阿里'),('459','西藏','林芝'),('460','西藏','昌都'),('461','西藏','山南'),('462','西藏','日喀则'),('463','西藏','拉萨'),('464','新疆','博尔塔拉'),('465','新疆','吐鲁番'),('466','新疆','哈密'),('467','新疆','昌吉'),('468','新疆','和田'),('469','新疆','喀什'),('470','新疆','克孜勒苏'),('471','新疆','巴音郭楞'),('472','新疆','阿克苏'),('473','新疆','伊犁'),('474','新疆','塔城'),('475','新疆','乌鲁木齐'),('476','新疆','阿勒泰'),('477','新疆','克拉玛依'),('478','新疆','石河子'),('479','新疆','图木舒克'),('480','新疆','阿拉尔'),('481','新疆','五家渠');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weizhaohuan` */
 DROP TABLE IF EXISTS `vifnn_weizhaohuan`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weizhaohuan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(30) NOT NULL,
  `keyword` char(35) NOT NULL,
  `title` char(45) NOT NULL,
  `pic` char(200) NOT NULL,
  `top_pic` varchar(250) NOT NULL,
  `start` char(15) NOT NULL,
  `end` char(15) NOT NULL,
  `addr` varchar(150) NOT NULL,
  `longitude` char(20) NOT NULL,
  `latitude` char(20) NOT NULL,
  `info` text NOT NULL,
  `intro` text NOT NULL,
  `is_open` tinyint(4) NOT NULL,
  `add_time` char(15) NOT NULL,
  `show_num` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  `is_attention` tinyint(4) NOT NULL,
  `min` varchar(11) NOT NULL DEFAULT '',
  `max` varchar(11) NOT NULL DEFAULT '',
  `bgpic` varchar(200) NOT NULL DEFAULT '',
  `bgcolor` varchar(20) NOT NULL DEFAULT '#ca1a48',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weizhaohuan_prize` */
 DROP TABLE IF EXISTS `vifnn_weizhaohuan_prize`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weizhaohuan_prize` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(30) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` char(45) NOT NULL,
  `img` char(200) NOT NULL,
  `num` int(11) NOT NULL,
  `use_num` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `gt` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weizhaohuan_share` */
 DROP TABLE IF EXISTS `vifnn_weizhaohuan_share`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weizhaohuan_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(30) NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `wecha_id` char(40) NOT NULL,
  `share_key` char(32) NOT NULL,
  `add_time` char(15) NOT NULL,
  `num` int(11) NOT NULL,
  `share_count` int(11) NOT NULL,
  `share_score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weizhaohuan_user` */
 DROP TABLE IF EXISTS `vifnn_weizhaohuan_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weizhaohuan_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wecha_id` char(40) NOT NULL,
  `pid` int(11) NOT NULL,
  `token` char(35) NOT NULL,
  `add_time` char(15) NOT NULL,
  `share_count` int(11) NOT NULL,
  `share_key` char(40) NOT NULL,
  `is_real` tinyint(4) NOT NULL,
  `share_num` int(100) NOT NULL,
  `share_code` varchar(100) NOT NULL,
  `is_see` varchar(100) NOT NULL,
  `status` int(1) unsigned zerofill NOT NULL,
  `use_time` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weizhuli` */
 DROP TABLE IF EXISTS `vifnn_weizhuli`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weizhuli` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `token` varchar(50) NOT NULL,
  `picurl` varchar(500) NOT NULL,
  `nr` text NOT NULL,
  `statdate` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tishi` varchar(255) NOT NULL,
  `shuliang` text NOT NULL,
  `bgcolor` varchar(255) NOT NULL,
  `biaoti` varchar(255) NOT NULL,
  `hd` varchar(255) NOT NULL,
  `dfxz` varchar(255) NOT NULL,
  `csfz` varchar(255) NOT NULL,
  `jfdw` varchar(255) NOT NULL,
  `zjjf` varchar(255) NOT NULL,
  `hdbg` varchar(255) NOT NULL,
  `gz` varchar(255) NOT NULL,
  `banquan` varchar(255) NOT NULL,
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  `mode` tinyint(4) NOT NULL,
  `ch` varchar(255) NOT NULL,
  `lj` varchar(255) NOT NULL,
  `sharenr` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weizhuli_record` */
 DROP TABLE IF EXISTS `vifnn_weizhuli_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weizhuli_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` varchar(50) NOT NULL COMMENT '??? 1,2,3,',
  `wecha_id` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL DEFAULT '',
  `trueip` varchar(255) NOT NULL,
  `score` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_weizhuli_user` */
 DROP TABLE IF EXISTS `vifnn_weizhuli_user`;/* MySQLReback Separation */ CREATE TABLE `vifnn_weizhuli_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `score` varchar(255) NOT NULL,
  `num` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wifi` */
 DROP TABLE IF EXISTS `vifnn_wifi`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wifi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` char(255) NOT NULL,
  `appid` varchar(60) NOT NULL,
  `appkey` varchar(60) NOT NULL,
  `url` varchar(200) NOT NULL COMMENT '??url',
  `name` varchar(50) NOT NULL COMMENT 'wifi??',
  `token` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `intro` text NOT NULL,
  `picurl` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wqlcmswifi` */
 DROP TABLE IF EXISTS `vifnn_wqlcmswifi`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wqlcmswifi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` varchar(60) NOT NULL,
  `token` varchar(60) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `picurl` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wuye` */
 DROP TABLE IF EXISTS `vifnn_wuye`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wuye` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `title` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `toppicurl` varchar(200) NOT NULL,
  `roompicurl` varchar(200) NOT NULL,
  `address` varchar(500) NOT NULL,
  `lat` varchar(30) NOT NULL,
  `lng` varchar(30) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `xmcontent` varchar(2000) NOT NULL,
  `jtcontent` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wuyecom` */
 DROP TABLE IF EXISTS `vifnn_wuyecom`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wuyecom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `title` varchar(100) NOT NULL,
  `sort` varchar(3) NOT NULL,
  `name` varchar(100) NOT NULL,
  `zw` varchar(100) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `dpcontent` varchar(2000) NOT NULL,
  `subestatename` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wuyephoto` */
 DROP TABLE IF EXISTS `vifnn_wuyephoto`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wuyephoto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sort` varchar(3) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `namephoto` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wuyeposter` */
 DROP TABLE IF EXISTS `vifnn_wuyeposter`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wuyeposter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `status` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `picurl1` varchar(100) NOT NULL,
  `picurl2` varchar(200) NOT NULL,
  `picurl3` varchar(200) NOT NULL,
  `picurl4` varchar(200) NOT NULL,
  `subestatename` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wuyesub` */
 DROP TABLE IF EXISTS `vifnn_wuyesub`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wuyesub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `token` varchar(31) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sort` varchar(3) NOT NULL,
  `content` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wuyeunits` */
 DROP TABLE IF EXISTS `vifnn_wuyeunits`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wuyeunits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sub` varchar(30) NOT NULL,
  `lc` varchar(30) NOT NULL,
  `area` varchar(50) NOT NULL,
  `shi` varchar(30) NOT NULL,
  `ting` varchar(100) NOT NULL,
  `sort` varchar(3) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wxcert` */
 DROP TABLE IF EXISTS `vifnn_wxcert`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wxcert` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(25) NOT NULL,
  `apiclient_cert` varchar(255) NOT NULL COMMENT 'apiclient_cert私钥文件',
  `apiclient_key` varchar(255) NOT NULL COMMENT 'apiclient_key公钥文件',
  `rootca` varchar(255) NOT NULL COMMENT '根证书文件',
  `uploadtime` int(11) NOT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wxuser` */
 DROP TABLE IF EXISTS `vifnn_wxuser`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wxuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `routerid` varchar(50) NOT NULL DEFAULT '',
  `agentid` int(10) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL,
  `wxname` varchar(60) NOT NULL COMMENT '公众号名称',
  `winxintype` smallint(2) NOT NULL DEFAULT '1',
  `aeskey` varchar(45) NOT NULL DEFAULT '',
  `encode` tinyint(1) NOT NULL DEFAULT '0',
  `appid` varchar(50) NOT NULL DEFAULT '',
  `appsecret` varchar(50) NOT NULL DEFAULT '',
  `wxid` varchar(20) NOT NULL COMMENT '公众号原始ID',
  `weixin` char(20) NOT NULL COMMENT '微信号',
  `headerpic` char(255) NOT NULL COMMENT '头像地址',
  `token` char(255) NOT NULL,
  `pigsecret` varchar(150) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL COMMENT '省',
  `city` varchar(60) NOT NULL COMMENT '市',
  `qq` char(25) NOT NULL COMMENT '公众号邮箱',
  `wxfans` int(11) NOT NULL COMMENT '微信粉丝',
  `typeid` int(11) NOT NULL COMMENT '分类ID',
  `typename` varchar(90) DEFAULT '0' COMMENT '分类名',
  `tongji` text NOT NULL,
  `allcardnum` int(11) NOT NULL,
  `cardisok` int(11) NOT NULL,
  `yetcardnum` int(11) NOT NULL,
  `totalcardnum` int(11) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `tpltypeid` varchar(10) NOT NULL DEFAULT '1',
  `updatetime` varchar(13) NOT NULL,
  `tpltypename` varchar(20) NOT NULL COMMENT '首页模版名',
  `tpllistid` varchar(2) NOT NULL COMMENT '列表模版ID',
  `tpllistname` varchar(20) NOT NULL COMMENT '列表模版名',
  `tplcontentid` varchar(2) NOT NULL COMMENT '内容模版ID',
  `tplcontentname` varchar(20) NOT NULL COMMENT '内容模版名',
  `transfer_customer_service` tinyint(1) NOT NULL DEFAULT '0',
  `openphotoprint` tinyint(1) NOT NULL DEFAULT '0',
  `freephotocount` mediumint(4) NOT NULL DEFAULT '3',
  `oauth` tinyint(1) NOT NULL DEFAULT '0',
  `oauthinfo` tinyint(1) NOT NULL DEFAULT '1',
  `color_id` int(2) NOT NULL,
  `ifbiz` tinyint(1) DEFAULT '0',
  `fuwuappid` char(20) DEFAULT NULL,
  `share_ticket` varchar(150) NOT NULL,
  `share_dated` char(15) NOT NULL,
  `authorizer_access_token` varchar(200) NOT NULL,
  `authorizer_refresh_token` varchar(200) NOT NULL,
  `authorizer_expires` char(10) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `web_access_token` varchar(200) NOT NULL COMMENT ' 网页授权token',
  `web_refresh_token` varchar(200) NOT NULL,
  `web_expires` char(10) NOT NULL,
  `wx_coupons` tinyint(4) NOT NULL,
  `card_ticket` char(120) NOT NULL,
  `card_expires` char(15) NOT NULL,
  `wx_liaotian` tinyint(4) NOT NULL,
  `qr` varchar(200) NOT NULL,
  `dynamicTmpls` int(11) NOT NULL DEFAULT '0',
  `sub_notice` varchar(255) DEFAULT NULL,
  `need_phone_notice` varchar(255) DEFAULT NULL,
  `sub_notice_btn` varchar(60) DEFAULT NULL,
  `is_syn` tinyint(4) NOT NULL DEFAULT '0',
  `phone` text NOT NULL,
  `smsstatus` text NOT NULL,
  `smsuser` text NOT NULL,
  `smspassword` text NOT NULL,
  `email` text NOT NULL,
  `emailstatus` text NOT NULL,
  `emailuser` text NOT NULL,
  `emailpassword` text NOT NULL,
  `hurl` char(255) NOT NULL,
  `merchant_id` varchar(20) NOT NULL,
  `type_of_media_or_gov` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是认证的政府与媒体类订阅号；0否；1是',
  `blacklist_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否启用黑名单功能',
  `eqx` varchar(5) NOT NULL,
  `eqxpassword` varchar(32) NOT NULL,
  `kfaccount_nickname` varchar(45) NOT NULL DEFAULT '' COMMENT '微信客服昵称',
  `kfaccount_error` int(11) NOT NULL DEFAULT '0' COMMENT '微信客服昵称是否有效',
  `printstatus` int(1) DEFAULT '0',
  `member_code` varchar(50) DEFAULT NULL,
  `feiyin_key` varchar(50) DEFAULT NULL,
  `device_no` varchar(30) DEFAULT NULL,
  `wxun` varchar(64) NOT NULL COMMENT '公众账号用户名',
  `wxpwd` varchar(32) NOT NULL COMMENT '公众账号密码',
  `binok` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否绑定成功',
  `shoptpltypeid` int(11) NOT NULL DEFAULT '137' COMMENT '商城模板id',
  `shoptpltypename` varchar(20) NOT NULL DEFAULT '1137_index' COMMENT '商城模板模板名称',
  `wx_notify` int(1) NOT NULL DEFAULT '0',
  `ca_code` varchar(8) NOT NULL,
  `adm_openid` varchar(32) NOT NULL,
  `adm_fakeid` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` text NOT NULL,
  `dianhua` varchar(20) NOT NULL,
  `keyword` char(255) NOT NULL,
  `bjdh` int(10) NOT NULL,
  `zdh` tinyint(1) NOT NULL,
  `animation` tinyint(2) NOT NULL,
  `start` tinyint(1) NOT NULL DEFAULT '0',
  `stpic` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `uid_2` (`uid`),
  KEY `agentid` (`agentid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 插入数据 `vifnn_wxuser` */
 INSERT INTO `vifnn_wxuser` VALUES ('32','','0','3','思拓推广易软件','3','yQhIVUOUaNHEKHoqsufXLNiPUTOuCXZbOgJJbyDbllF','0','wx4658d70c5473c824','b7c9075990e6788bb0bc2ca891ba6ec6','gh_1405247b284b','nrnc_net','http://wx.qlogo.cn/mmopen/XzrZfMxJqh3qZTGAvyM0vjp2lmZcJRC4QxMS63OKvDiaI6icNE7H1CAjB4cicS5FI5gStUdVUJJA7KZcuTqXib5F5MKRgvtia7hWV/0','ywxgfh1465574691','sjafrSakEvQBuEpBy8C6','海南','海口市','ehaisong@126.com','0','4','','','0','0','0','0','','1','1465613593','','','','','','0','0','3','0','1','0','0','','','','Gx2KdJKb0ry5whk_gB7hCUVJttFDPYKA4rFjYCODF-fN1YgJb5-gyerEaDqbL9Kc8Y2ggpBouHAByaN8stvfbJnZnhaxsZNxmQpvZ_MOZ0614UABzcChGKExfRUr5FIWERJfAEDTGY','refreshtoken@@@AfNEvN1rI8suf009PxIIMKXW8HqY45XhRa6SMbtgWfI','1465581891','1','','','','0','','','0','http://wx.sitore.com/uploads/a/admin/f/1/3/6/thumb_575ae5868f53f.jpg','0','','','','0','','','','','','','','','','','0','0','','','','0','0','','','','','','0','137','1137_index','0','','','','','','','','0','0','0','0','');/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wxwall_award` */
 DROP TABLE IF EXISTS `vifnn_wxwall_award`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wxwall_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wxq_id` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wxwall_members` */
 DROP TABLE IF EXISTS `vifnn_wxwall_members`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wxwall_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user` varchar(50) NOT NULL COMMENT '???????ID',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '??',
  `avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '??',
  `wxq_id` int(10) unsigned NOT NULL COMMENT '????????????',
  `isjoin` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '????????',
  `isblacklist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '????????',
  `lastupdate` int(10) unsigned NOT NULL COMMENT '????????',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wxwall_message` */
 DROP TABLE IF EXISTS `vifnn_wxwall_message`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wxwall_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wxq_id` int(10) unsigned NOT NULL COMMENT '??ID',
  `from_user` varchar(50) NOT NULL COMMENT '?????ID',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '???????',
  `type` varchar(10) NOT NULL COMMENT '??????',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '????',
  `createtime` int(10) unsigned NOT NULL,
  `isshowed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '?????? 1 ? 0?',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wzzmy` */
 DROP TABLE IF EXISTS `vifnn_wzzmy`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wzzmy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wecha_id` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `content` varchar(5000) DEFAULT NULL,
  `pic` varchar(255) DEFAULT '',
  `style` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `knwxopen` int(1) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `catgroy` varchar(233) NOT NULL,
  `save` varchar(255) NOT NULL,
  `click` varchar(255) NOT NULL DEFAULT '0',
  `share` varchar(255) NOT NULL DEFAULT '0',
  `sharenr` varchar(255) NOT NULL,
  `sharepic` varchar(255) NOT NULL,
  `t` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_wzzreplay` */
 DROP TABLE IF EXISTS `vifnn_wzzreplay`;/* MySQLReback Separation */ CREATE TABLE `vifnn_wzzreplay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `jianjie` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `gzlj` varchar(255) DEFAULT NULL,
  `open` int(1) DEFAULT NULL,
  `banquan` varchar(255) NOT NULL,
  `mypicurl1` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_xinniannq` */
 DROP TABLE IF EXISTS `vifnn_xinniannq`;/* MySQLReback Separation */ CREATE TABLE `vifnn_xinniannq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nq` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_xinniannqreplay` */
 DROP TABLE IF EXISTS `vifnn_xinniannqreplay`;/* MySQLReback Separation */ CREATE TABLE `vifnn_xinniannqreplay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `jianjie` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `lj` varchar(255) DEFAULT NULL,
  `sm` mediumtext,
  `mypicurl1` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yeepay_tmp` */
 DROP TABLE IF EXISTS `vifnn_yeepay_tmp`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yeepay_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) NOT NULL,
  `token` varchar(60) NOT NULL,
  `wecha_id` varchar(60) NOT NULL,
  `from` varchar(30) NOT NULL,
  `time` int(11) NOT NULL,
  `platform` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yingyong` */
 DROP TABLE IF EXISTS `vifnn_yingyong`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yingyong` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `videourl` varchar(200) DEFAULT NULL,
  `p1` varchar(200) DEFAULT NULL,
  `p2` varchar(200) DEFAULT NULL,
  `p3` varchar(200) DEFAULT NULL,
  `p4` varchar(200) DEFAULT NULL,
  `p5` varchar(200) DEFAULT NULL,
  `p6` varchar(200) DEFAULT NULL,
  `p7` varchar(200) DEFAULT NULL,
  `p8` varchar(200) DEFAULT NULL,
  `p9` varchar(200) DEFAULT NULL,
  `p10` varchar(200) DEFAULT NULL,
  `p11` varchar(200) DEFAULT NULL,
  `p12` varchar(200) DEFAULT NULL,
  `p13` varchar(200) DEFAULT NULL,
  `p14` varchar(200) DEFAULT NULL,
  `p15` varchar(200) DEFAULT NULL,
  `p16` varchar(200) DEFAULT NULL,
  `p17` varchar(200) DEFAULT NULL,
  `p18` varchar(200) DEFAULT NULL,
  `p19` varchar(200) DEFAULT NULL,
  `p20` varchar(200) DEFAULT NULL,
  `p21` varchar(200) DEFAULT NULL,
  `p22` varchar(200) DEFAULT NULL,
  `p23` varchar(200) DEFAULT NULL,
  `p24` varchar(200) DEFAULT NULL,
  `p25` varchar(200) DEFAULT NULL,
  `p26` varchar(200) DEFAULT NULL,
  `p27` varchar(200) DEFAULT NULL,
  `p28` varchar(200) DEFAULT NULL,
  `p29` varchar(200) DEFAULT NULL,
  `musicurl` varchar(200) DEFAULT NULL,
  `type` tinyint(1) NOT NULL,
  `tip` text NOT NULL,
  `tip1` text NOT NULL,
  `tip1url` varchar(200) DEFAULT NULL,
  `tip2` text NOT NULL,
  `tipurl` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yingyong_reply` */
 DROP TABLE IF EXISTS `vifnn_yingyong_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yingyong_reply` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `info` varchar(500) DEFAULT NULL COMMENT '????',
  `title` text NOT NULL,
  `tp` char(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yml_config` */
 DROP TABLE IF EXISTS `vifnn_yml_config`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yml_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `secret` varchar(32) NOT NULL,
  `print_enable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `wx_appid` varchar(100) NOT NULL,
  `wx_appsecret` varchar(100) NOT NULL,
  `voice_enable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yml_record` */
 DROP TABLE IF EXISTS `vifnn_yml_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yml_record` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `wxid` varchar(100) NOT NULL,
  `step` text NOT NULL,
  `task_id` bigint(20) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yousetdiscount` */
 DROP TABLE IF EXISTS `vifnn_yousetdiscount`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yousetdiscount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `wxpic` varchar(200) NOT NULL,
  `wxtitle` varchar(100) NOT NULL,
  `wxinfo` text,
  `startdate` int(11) unsigned NOT NULL,
  `enddate` int(11) unsigned NOT NULL,
  `discount_endtime` int(10) unsigned NOT NULL,
  `playtime` int(11) NOT NULL DEFAULT '10',
  `info` text,
  `bg1` varchar(200) NOT NULL,
  `bg2` varchar(200) NOT NULL,
  `bg3` varchar(200) NOT NULL,
  `my_count` int(10) unsigned NOT NULL DEFAULT '1',
  `friends_count` int(10) unsigned NOT NULL DEFAULT '1',
  `discount_type` int(10) unsigned NOT NULL DEFAULT '1',
  `money_start` double NOT NULL,
  `money_end` double NOT NULL,
  `discount_start` double NOT NULL,
  `discount_end` double NOT NULL,
  `discount_min` double NOT NULL DEFAULT '0',
  `is_sms` int(10) unsigned NOT NULL DEFAULT '0',
  `is_attention` int(10) unsigned NOT NULL DEFAULT '0',
  `is_reg` int(10) unsigned NOT NULL DEFAULT '0',
  `is_open` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL,
  `fxtitle` varchar(500) DEFAULT NULL,
  `fxinfo` varchar(500) DEFAULT NULL,
  `fxtitle2` varchar(500) DEFAULT NULL,
  `fxinfo2` varchar(500) DEFAULT NULL,
  `gamepic1` varchar(200) NOT NULL,
  `gamepic2` varchar(200) NOT NULL,
  `fxpic` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `name` (`name`) USING BTREE,
  KEY `is_open` (`is_open`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yousetdiscount_direction` */
 DROP TABLE IF EXISTS `vifnn_yousetdiscount_direction`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yousetdiscount_direction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `yid` int(10) unsigned NOT NULL,
  `at_least` double NOT NULL,
  `discount` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yousetdiscount_helps` */
 DROP TABLE IF EXISTS `vifnn_yousetdiscount_helps`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yousetdiscount_helps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `yid` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `help` varchar(100) NOT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `playcount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yousetdiscount_helps_data` */
 DROP TABLE IF EXISTS `vifnn_yousetdiscount_helps_data`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yousetdiscount_helps_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `yid` int(11) NOT NULL,
  `hid` int(11) NOT NULL,
  `discount` double NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yousetdiscount_users` */
 DROP TABLE IF EXISTS `vifnn_yousetdiscount_users`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yousetdiscount_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `yid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `share_key` varchar(100) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `did` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yuezhanreply_info` */
 DROP TABLE IF EXISTS `vifnn_yuezhanreply_info`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yuezhanreply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `picurl` varchar(120) NOT NULL DEFAULT '',
  `picurls1` varchar(120) NOT NULL DEFAULT '',
  `info` varchar(120) NOT NULL DEFAULT '',
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `copyright` text NOT NULL,
  `ad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yundabao` */
 DROP TABLE IF EXISTS `vifnn_yundabao`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yundabao` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `AppId` int(11) DEFAULT NULL,
  `endtime` int(11) DEFAULT NULL,
  `AppName` varchar(100) DEFAULT NULL,
  `AppNote` text,
  `HideTop` int(11) DEFAULT NULL,
  `IconType` int(11) DEFAULT NULL,
  `IconName` varchar(200) DEFAULT NULL,
  `IconName_url` varchar(200) DEFAULT NULL,
  `LogoName` varchar(100) DEFAULT NULL,
  `LogoName_url` varchar(200) DEFAULT NULL,
  `BgColor` int(11) DEFAULT NULL,
  `SplashType` int(11) DEFAULT NULL,
  `SplashName` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yundabao_users` */
 DROP TABLE IF EXISTS `vifnn_yundabao_users`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yundabao_users` (
  `vifnn_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `endtime` int(11) NOT NULL,
  `AccessToken` varchar(200) DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  PRIMARY KEY (`vifnn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yuyue` */
 DROP TABLE IF EXISTS `vifnn_yuyue`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yuyue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `topic` varchar(200) DEFAULT NULL,
  `info` varchar(500) DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `statdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `copyright` varchar(30) DEFAULT NULL,
  `yuyue_type` tinyint(1) DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yuyue_order` */
 DROP TABLE IF EXISTS `vifnn_yuyue_order`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yuyue_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `memo` varchar(200) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `wecha_id` varchar(200) NOT NULL,
  `or_date` date DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `nums` varchar(20) DEFAULT NULL,
  `fieldsigle` varchar(100) DEFAULT NULL,
  `fielddownload` varchar(100) DEFAULT NULL,
  `price` varchar(10) DEFAULT NULL,
  `kind` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yuyue_setcin` */
 DROP TABLE IF EXISTS `vifnn_yuyue_setcin`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yuyue_setcin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `yuanjia` varchar(10) DEFAULT NULL,
  `youhui` varchar(10) DEFAULT NULL,
  `memo` varchar(100) DEFAULT NULL,
  `messages` text,
  `type` varchar(20) DEFAULT NULL,
  `pic1` varchar(100) DEFAULT NULL,
  `pic2` varchar(100) DEFAULT NULL,
  `pic3` varchar(100) DEFAULT NULL,
  `pic4` varchar(100) DEFAULT NULL,
  `pic5` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yzdd` */
 DROP TABLE IF EXISTS `vifnn_yzdd`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yzdd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `ms` varchar(1023) DEFAULT NULL,
  `kssj` int(11) DEFAULT NULL,
  `jssj` int(11) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `gjz` varchar(50) DEFAULT NULL,
  `limit` int(11) DEFAULT '20' COMMENT '???',
  `mrtm` int(11) DEFAULT NULL COMMENT 'ÿ',
  `tk` varchar(255) DEFAULT NULL,
  `dtts` int(11) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yzdd_record` */
 DROP TABLE IF EXISTS `vifnn_yzdd_record`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yzdd_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `yid` int(11) DEFAULT NULL,
  `tms` int(11) DEFAULT NULL,
  `zqs` int(11) DEFAULT '0',
  `ctime` int(11) DEFAULT NULL,
  `jrtms` int(11) DEFAULT '0',
  `jt` int(11) DEFAULT '0',
  `iscom` tinyint(1) DEFAULT '0',
  `hdrq` date DEFAULT NULL,
  `lasttmid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yzdd_record_data` */
 DROP TABLE IF EXISTS `vifnn_yzdd_record_data`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yzdd_record_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL COMMENT '?',
  `wecha_id` varchar(100) NOT NULL,
  `yid` int(11) NOT NULL COMMENT '??',
  `zd` varchar(2) NOT NULL,
  `rid` int(11) DEFAULT NULL,
  `tmid` int(11) NOT NULL,
  `uuid` varchar(60) NOT NULL,
  `ctime` int(11) NOT NULL,
  `htime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uuid` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_yzddtk` */
 DROP TABLE IF EXISTS `vifnn_yzddtk`;/* MySQLReback Separation */ CREATE TABLE `vifnn_yzddtk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tm` varchar(1023) DEFAULT NULL,
  `d1` varchar(255) DEFAULT NULL,
  `d2` varchar(255) DEFAULT NULL,
  `d3` varchar(255) DEFAULT NULL,
  `d4` varchar(255) DEFAULT NULL,
  `zd` varchar(2) DEFAULT NULL,
  `token` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_zhaopin` */
 DROP TABLE IF EXISTS `vifnn_zhaopin`;/* MySQLReback Separation */ CREATE TABLE `vifnn_zhaopin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `gangwei` varchar(200) DEFAULT NULL,
  `xinzi` varchar(200) DEFAULT NULL,
  `yaoqiu` varchar(5000) DEFAULT NULL,
  `ren` varchar(255) NOT NULL,
  `zhize` varchar(5000) NOT NULL,
  `jigou` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `people` varchar(255) NOT NULL,
  `tell` char(11) NOT NULL,
  `longitude` char(11) NOT NULL DEFAULT '',
  `latitude` char(11) NOT NULL DEFAULT '',
  `leibie` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `click` int(11) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `wecha_id` varchar(200) NOT NULL,
  `allow` tinyint(1) NOT NULL COMMENT '审核',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_zhaopin_jianli` */
 DROP TABLE IF EXISTS `vifnn_zhaopin_jianli`;/* MySQLReback Separation */ CREATE TABLE `vifnn_zhaopin_jianli` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `job` varchar(200) DEFAULT NULL,
  `salary` varchar(200) DEFAULT NULL,
  `introduce` varchar(5000) DEFAULT NULL,
  `education` varchar(255) NOT NULL,
  `workarea` varchar(255) NOT NULL,
  `phone` char(11) NOT NULL,
  `leibie` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `click` int(11) NOT NULL,
  `year` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `validTime` int(100) NOT NULL,
  `age` char(3) NOT NULL DEFAULT '',
  `wecha_id` varchar(200) NOT NULL,
  `allow` tinyint(1) NOT NULL COMMENT '审核',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_zhaopin_reply` */
 DROP TABLE IF EXISTS `vifnn_zhaopin_reply`;/* MySQLReback Separation */ CREATE TABLE `vifnn_zhaopin_reply` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `info` varchar(500) DEFAULT NULL COMMENT '????',
  `title` text NOT NULL,
  `tp` char(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `allowjl` tinyint(1) NOT NULL COMMENT '审核简历',
  `allowqy` tinyint(1) NOT NULL COMMENT '审核招聘',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_zhengwu` */
 DROP TABLE IF EXISTS `vifnn_zhengwu`;/* MySQLReback Separation */ CREATE TABLE `vifnn_zhengwu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(31) NOT NULL,
  `title` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `toppicurl` varchar(200) NOT NULL,
  `roompicurl` varchar(200) NOT NULL,
  `address` varchar(500) NOT NULL,
  `lat` varchar(30) NOT NULL,
  `lng` varchar(30) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `xmcontent` varchar(2000) NOT NULL,
  `jtcontent` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */
 /* 创建表结构 `vifnn_zhida` */
 DROP TABLE IF EXISTS `vifnn_zhida`;/* MySQLReback Separation */ CREATE TABLE `vifnn_zhida` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(1000) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `token` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;/* MySQLReback Separation */