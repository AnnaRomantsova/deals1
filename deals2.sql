# Host: localhost  (Version 5.5.25)
# Date: 2016-07-19 13:48:02
# Generator: MySQL-Front 5.3  (Build 6.24)

/*!40101 SET NAMES cp1251 */;

#
# Structure for table "deals"
#

DROP TABLE IF EXISTS `deals`;
CREATE TABLE `deals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(11) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `sposob` varchar(255) DEFAULT NULL,
  `srok_end_podach` int(11) DEFAULT NULL,
  `customer` varchar(255) DEFAULT NULL,
  `inn_customer` varchar(255) DEFAULT NULL,
  `price_lot` varchar(255) DEFAULT NULL,
  `price_zaiav` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `responsible` varchar(255) DEFAULT NULL,
  `geographi` varchar(255) DEFAULT NULL,
  `status_prep` int(11) DEFAULT NULL,
  `itog_zaiav` varchar(255) DEFAULT NULL,
  `date_realiz` varchar(255) DEFAULT NULL,
  `date_contract` varchar(255) DEFAULT NULL,
  `number_contract` varchar(255) DEFAULT NULL,
  `date_income` varchar(255) DEFAULT NULL,
  `status_uch` varchar(255) DEFAULT NULL,
  `name_etp` varchar(255) DEFAULT NULL,
  `price_winner` varchar(255) DEFAULT NULL,
  `inn_winner` varchar(255) DEFAULT NULL,
  `name_winner` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `predmet` varchar(255) DEFAULT NULL,
  `price_contract` varchar(255) DEFAULT NULL,
  `status_contract` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=cp1251;

#
# Data for table "deals"
#

INSERT INTO `deals` VALUES (2,101,'5564545665','�������',248400,'��� ���� � ������','34566587865','2000','10','6','������ ��� 111','�����','��������',3,NULL,NULL,NULL,NULL,'15.07.2016',NULL,'���','80000','123456789','����������','�� �������','5555',NULL,NULL),(3,65454845,'654654','�������',1976400,'��� ��','654654','8000','1400','3','���������','�����','��������',1,NULL,NULL,NULL,NULL,'1468845241',NULL,'','100000','','','','����',NULL,NULL),(4,21549874,'767654873468634','��. �������',253054800,'����','123456749687','800000','400','4','���������','������','��',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','400000','23565778','�����','','',NULL,NULL),(5,54987987,'3434554','��. �������',635465446,'����-����','43534','70000','800','6','������������','�����','��������',3,NULL,NULL,NULL,NULL,'1468578914',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,23132111,'5564545665','�������',324234,'��� ���� � ������','34566587865','2000345455','70000','4','���������������������','������ �.�.','��������',0,'','','','','','','','','','',NULL,NULL,NULL,NULL),(15,65454845,'654654','�������',3254654,'��� ��','654654','8000','1600','1','���������!!!!!','�����','��������',1,'','','','','1468581292','','','','','',NULL,NULL,NULL,NULL),(16,21549874,'767654873468634','��. �������',32163465,'����','123456749687','800000','400','4','���������','������','��',0,'','','','','','','','400000','23565778','�����',NULL,NULL,NULL,NULL),(17,54987987,'3434554','��. �������',635465446,'����-����','43534','70000','800','3','������������','��������','��������',1,'','','','','','','','','','',NULL,NULL,NULL,NULL),(18,23132111,'5564545665','�������',324234,'��� ���� � ������','34566587865','2000','70000','6','������','�����','��������',3,'','','','','1468581372','2','','','','',NULL,NULL,NULL,NULL),(19,65454845,'654654','�������',3254654,'��� ��','654654','800000000','50','1','�����������!!!!!','�����','��������',1,'','','','','1468581579','2','','','','',NULL,NULL,NULL,NULL),(20,21549874,'767654873468634','��. �������',32163465,'����','123456749687','800000','400','3','���������',' ������������� ��������','��',1,'','','','','1468571581','3','','400000','23565778','�����',NULL,NULL,NULL,NULL),(21,54987987,'3434554','��. �������',635465446,'����-����','43534','70000','800','8','������� ������',' ������������� ��������','��������',1,'','','','','1468493848','2','','','','',NULL,NULL,NULL,1),(22,NULL,'7000','',0,'','','',NULL,'8','',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','',NULL,2),(23,NULL,'16565746546543','�������� �������',0,'�������� ������1','908723489237483927','40000',NULL,'8','',' ������������� ��������','���-�����',NULL,NULL,NULL,NULL,NULL,'1468493744',NULL,'�����-�� ���','70000','230949238746734','������ ���','��� ��� �� ���','������',NULL,3),(24,NULL,'11111111','111111111',0,'','','',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','',NULL,NULL);

#
# Structure for table "profile"
#

DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `guid` varchar(255) DEFAULT NULL,
  `type` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=cp1251 ROW_FORMAT=COMPACT;

#
# Data for table "profile"
#

INSERT INTO `profile` VALUES (7,'��������',NULL,NULL,'7c7084ce-c721-11e5-a847-d43d7eebfbd6',0),(8,'��������2',NULL,NULL,'14165d03-c722-11e5-a847-d43d7eebfbd6',1),(9,'234',NULL,NULL,'41a60c0f-1810-11e6-ae70-d43d7eebfbd6',0),(10,'����������',NULL,NULL,'76440f78-1810-11e6-ae70-d43d7eebfbd6',0);

#
# Structure for table "setup"
#

DROP TABLE IF EXISTS `setup`;
CREATE TABLE `setup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `var` varchar(40) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;

#
# Data for table "setup"
#

/*!40000 ALTER TABLE `setup` DISABLE KEYS */;
INSERT INTO `setup` VALUES (1,'SMTP_settings','mail=false;\r\nauth=false;\r\nHost=localhost;\r\nPort=25;\r\nUsername=;\r\nPassword='),(19,'FCKeditor','1'),(152,'news_pbcount','10'),(153,'news_pscount','5'),(154,'news_fcount','3'),(155,'news_acount','30'),(156,'catalog_fcount','30'),(157,'search_fcount','100'),(158,'search_num','100'),(159,'dollar_kurs','32.5072'),(160,'euro_kurs','45.4873'),(161,'auth_fcount','10'),(162,'auth_acount','50'),(163,'mail_manage','From=tvcd@yandex.ru;\r\nFromName=���� ;\r\nrecipient=tvcd@yandex.ru;\r\nSubject=����'),(164,'mail_consult','From=tvcd@yandex.ru;\r\nFromName=���� ;\r\nrecipient=;\r\nSubject=����'),(165,'city_acount','20'),(166,'street_acount','20'),(167,'house_acount','20'),(168,'company_acount','20'),(169,'law_fcount','2'),(170,'law_acount','10 '),(171,'tsjnews_fcount','2'),(172,'mail_order','From=a@m.ru;\r\nFromName=;\r\nrecipient=a@m.ru;\r\nSubject='),(173,'mail_questions','From=a@m.ru;\r\nFromName=;\r\nrecipient=a@m.ru;\r\nSubject='),(174,'mail_feed','From=a@m.ru;\r\nFromName=;\r\nrecipient=a@m.ru;\r\nSubject='),(175,'tsjnews_acount','10'),(176,'galery_acount','10'),(177,'mail_register','From=tvcd@yandex.ru;\r\nFromName=;\r\nrecipient=tvcd@yandex.ru;\r\nSubject=�����������'),(178,'zakupki_fcount','2'),(179,'act_category_acount','50'),(180,'reklama_fcount','10'),(181,'vendor_acount','10'),(182,'zakupki_acount','10'),(183,'offer_acount','10'),(184,'reklama_acount','10'),(185,'rek_acount','10'),(186,'deals_acount','50');
/*!40000 ALTER TABLE `setup` ENABLE KEYS */;

#
# Structure for table "site_moduls"
#

DROP TABLE IF EXISTS `site_moduls`;
CREATE TABLE `site_moduls` (
  `Id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `command` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;

#
# Data for table "site_moduls"
#

/*!40000 ALTER TABLE `site_moduls` DISABLE KEYS */;
INSERT INTO `site_moduls` VALUES (10,'�������','/catalog/front.php',NULL),(11,'�������-�������','/catalog/panel.php',NULL),(12,'�������-����','/catalog/menu.php',NULL),(14,'�����������-������','/auth/front.php',NULL),(15,'�����������-����','/auth/front.php',NULL),(16,'�����-������','/search/panel.php',NULL),(17,'������ �������','/cabinet/front.php',NULL),(18,'�������','/basket/front.php',NULL),(19,'�����','/search/front.php',NULL),(20,'����� �����','/catalog/kurs.php',NULL),(21,'���������� ������','/catalog/order.php',NULL),(22,'�����������-�������','/auth/messages.php',NULL),(23,'������ � ��������','/auth/pupup.php',NULL),(31,NULL,'/deals/front.php',NULL),(40,'�������','/activations/front.php',NULL),(41,'�������','/news/panel.php',NULL),(42,'��� ������','/house/panel.php',NULL),(43,'����������������','/law/front.php',NULL),(44,'����� ����','/menu/menu_sub.php',NULL),(45,'� ���','/about/front.php',NULL),(46,'���������','/manage/front.php',NULL),(47,'������ �������','/service/front.php',NULL),(48,'������','/reports/front.php',NULL),(49,'������� �� �������','/news/panel.php',NULL),(50,'�������������','/consult/front.php',NULL),(51,'�����������','/orgs/front.php',NULL),(52,'�������','/deals/s.php',NULL),(53,'�����','/deals/i.php',NULL),(54,'��� ����','/menu/menu_buttom.php',NULL),(55,'������ ����','/reklama/front.php',NULL),(56,'��� �����','/feedb/front.php',NULL),(57,'���. ��� �����������','/vendor/front.php',NULL),(58,'�����. ����������','/vendor/messages.php',NULL),(59,'�������','/zakupki/front.php',NULL),(60,'���� �������','/vendor/menu_vendor.php',NULL),(61,'������� ����������','/zakupki/vendor.php',NULL),(62,'������','/tarif/front.php',NULL);
/*!40000 ALTER TABLE `site_moduls` ENABLE KEYS */;

#
# Structure for table "site_pages"
#

DROP TABLE IF EXISTS `site_pages`;
CREATE TABLE `site_pages` (
  `id` tinyint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `content` longtext,
  `sort` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;

#
# Data for table "site_pages"
#

/*!40000 ALTER TABLE `site_pages` DISABLE KEYS */;
INSERT INTO `site_pages` VALUES (5,'license','<p style=\"text-align:justify\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut tincidunt justo sed tellus consequat feugiat. Quisque ut dapibus metus. Quisque augue ipsum, tincidunt vitae fringilla sit amet, tristique non odio. Duis egestas pretium molestie. Aenean elementum fringilla erat quis rutrum. Ut rutrum sodales diam, vel pretium nulla egestas et. In non velit quis urna elementum suscipit. Mauris neque arcu, lacinia sed molestie non, molestie ac purus. Suspendisse in nibh vitae enim adipiscing eleifend. Vivamus eleifend orci a turpis consectetur varius. Donec adipiscing, augue ut iaculis varius, orci est porttitor quam, eu feugiat tellus lorem non lacus. Aenean ante sem, placerat eu ornare vel, iaculis non elit. Nullam sagittis vestibulum pulvinar. In sagittis adipiscing sollicitudin. Phasellus et dui sit amet dolor sodales eleifend ut nec leo. Curabitur porta porttitor sollicitudin.</p>\r\n\r\n<p style=\"text-align:justify\">Vivamus lobortis, nisi sed auctor faucibus, turpis ante ornare metus, a egestas massa felis eu lacus. Sed tincidunt accumsan tortor, non fermentum metus eleifend vitae. Sed id massa a risus venenatis cursus eu nec est. Donec porttitor est in est hendrerit convallis. Praesent vitae quam nisl. Pellentesque bibendum varius justo sed posuere. Maecenas mi ante, vestibulum id lacinia auctor, iaculis a purus. Vivamus eget eros turpis.</p>\r\n\r\n<p style=\"text-align:justify\">Nam vehicula adipiscing massa ac scelerisque. Fusce blandit dui sit amet risus tincidunt sed condimentum metus lobortis. Aliquam erat volutpat. Vivamus diam eros, cursus nec aliquam accumsan, lacinia vitae urna. Duis vel arcu id nibh pharetra convallis. Nullam fermentum egestas eros quis suscipit. Nunc quis fringilla sem. Phasellus ultrices turpis a dui rutrum consectetur. In bibendum lectus at neque laoreet semper. Nullam hendrerit, libero non posuere egestas, urna lectus aliquam ante, placerat laoreet ligula enim eu mauris. Phasellus rutrum convallis tellus, eu faucibus erat commodo quis. Aliquam erat volutpat. Quisque sit amet tortor vitae odio gravida bibendum quis et enim.</p>\r\n\r\n<p style=\"text-align:justify\">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed magna odio, pretium et semper vel, semper in lorem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed ornare nulla augue. Cras leo nunc, sodales id luctus in, iaculis et purus. Donec sit amet sagittis quam. Donec quis massa et erat scelerisque molestie sed sit amet justo. Fusce in massa sed lectus egestas venenatis. Proin vitae sagittis dolor.</p>\r\n\r\n<p style=\"text-align:justify\">Nunc convallis tincidunt tellus non accumsan. In enim justo, mattis eget pellentesque nec, pellentesque sagittis arcu. Proin dictum tellus eget nisl fermentum eget faucibus sapien aliquet. Nunc cursus iaculis lacinia. Curabitur arcu orci, bibendum vel scelerisque vel, molestie id odio. Sed sagittis facilisis lacus, vitae fermentum justo dapibus sit amet. Praesent lacus purus, porta non tempor ut, suscipit ut nisi. Praesent fermentum risus ut erat commodo luctus sed ut enim.</p>\r\n',NULL),(7,'reklama','<p><img src=\"/i/reklama.png\" /></p>\r\n',NULL),(9,'consult','',NULL),(10,'index','<h2>�������� �������</h2>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>�������� �������. ������� �����</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p style=\"text-align:justify\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nec feugiat sem, quis elementum justo. Aenean consequat egestas enim, nec sagittis tellus porttitor posuere. In in interdum ex. Vestibulum ac urna a tellus bibendum vehicula. Vivamus non enim euismod, sagittis eros vel, vulputate odio. Aenean eros magna, dapibus maximus augue vel, aliquam consequat quam. Cras pretium eget metus at commodo. Aliquam auctor tellus et felis faucibus, non semper mauris dapibus. Quisque pulvinar lacus libero. Vivamus pulvinar vulputate pulvinar. Nullam viverra erat ac ex tempus mattis. Nulla facilisi. Maecenas nec pulvinar velit. Nulla eu leo sed elit lobortis egestas a eget augue. Maecenas sagittis est id viverra iaculis. Nullam ultricies convallis venenatis.</p>\r\n\r\n<p style=\"text-align:justify\">Donec sodales eu tortor in porta. Cras vestibulum molestie massa, in elementum felis maximus ut. Praesent in sagittis diam. In quam justo, luctus vitae nibh a, maximus facilisis ligula. Vestibulum scelerisque rutrum accumsan. Nam tellus felis, consectetur vitae ultrices et, auctor ac massa. Duis consectetur mattis maximus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse molestie a nunc ut cursus. Ut varius hendrerit diam varius imperdiet. Sed ornare elit sem, quis tincidunt orci gravida eu. Nullam accumsan ut neque at lacinia. Suspendisse ut interdum mi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>\r\n\r\n<p style=\"text-align:justify\">Curabitur quis dolor hendrerit dui porttitor rhoncus consequat eget mauris. Nulla mollis dui sed ante ultricies, in tempor velit rutrum. Vestibulum elementum sapien eu lectus commodo ultricies. Fusce dictum massa sed nulla ullamcorper ornare. Aenean a libero posuere quam commodo interdum. Sed lobortis sapien turpis, bibendum vehicula velit blandit a. Vestibulum vel quam rutrum, tincidunt urna a, venenatis urna.</p>\r\n\r\n<p style=\"text-align:justify\">Quisque posuere accumsan eleifend. Mauris vehicula, est non placerat efficitur, magna augue lobortis ipsum, quis vulputate ex felis nec justo. Morbi neque dolor, accumsan non purus fermentum, tincidunt malesuada elit. Sed ac aliquam lectus, a pharetra nibh. Mauris sagittis, odio quis sagittis maximus, felis leo tempor dui, eget convallis nisi diam ac sapien. Pellentesque cursus, ante ut blandit pulvinar, justo dolor tempor libero, vel pharetra nisl libero id urna. Phasellus pharetra massa ut mi rutrum gravida. Aenean venenatis dictum lorem, at egestas enim congue ut. Nam malesuada posuere interdum.</p>\r\n',1),(17,'error404','<div class=\\\"message\\\">�� ������� �� ���������� ��� �� ������������ ������</div>\r\n',1),(62,'gallery','',1),(63,'news','lkjlkjjk',1),(73,'company','<p><font size=\\\"4\\\"><u>������ � 21</u> </font></p>\r\n<p><font size=\\\"4\\\">A����: �. ������-��-����, ��.40��� ������, 308/3 </font></p>\r\n<p><font size=\\\"4\\\">����� ������: 9.00-19.00 ��������: ��.,��. ���.���� - �������. ������� ������ </font></p>\r\n<p><font size=\\\"4\\\">�������: 257-33-11,257-33-31. </font></p>\r\n<p><font size=\\\"4\\\">E-mail: </font><a href=\\\"mailto:f21@donlib.ru\\\"><font size=\\\"4\\\">f21@donlib.ru</font></a><font size=\\\"4\\\"> </font></p>\r\n<p><font size=\\\"4\\\">&nbsp;</font></p>\r\n<p><font size=\\\"4\\\">���.: &ldquo;40-����� ������&rdquo; (������ &ldquo;��������&rdquo;) ��� ��������� - 1969. </font></p>\r\n<p><font size=\\\"4\\\">������������ ���������� - ������������ �������� � �����. </font></p>\r\n<p><font size=\\\"4\\\">���������� ������������� - 6 900. </font></p>\r\n<p><font size=\\\"4\\\">������������ ������ ����� - �������������. </font></p>\r\n<p><font size=\\\"4\\\">����� � ������������ �����������: </font></p>\r\n<p><font size=\\\"4\\\">&bull;&ldquo;��������&rdquo; - ������� ����. </font></p>\r\n<p><font size=\\\"4\\\">&bull;&quot;��������&quot; - ������� ����.</font></p>',NULL),(90,'home_panel',NULL,NULL),(91,'p_1240463485','',NULL),(93,'search','',NULL),(94,'p_1339734922','<div><center><h2> <font color=\\\"008080\\\"> ������ � 21\r\n<div> <font color=\\\"000000\\\"> A����: �. ������-��-����, ��.40��� ������, 308/3 <br>\r\n����� ������: 9.00-19.00 <br> \r\n��������: ��.,��. <br>\r\n���.���� - �������. ������� ������ <br> \r\n�������: 257-33-11,257-33-31. <br>\r\nE-mail: f21@donlib.ru <br>\r\n���.: �40-����� ������� (������ ����������) <br>\r\n\r\n��� ��������� - 1969. <br>\r\n������������ ���������� - ������������ �������� � �����. <br> \r\n���������� ������������� - 6 900. <br>\r\n������������ ������ ����� - �������������. <br> \r\n\r\n����� � ������������ �����������: <br>\r\n����������� - ������� ����. <br>\r\n�\\\"��������\\\" - ������� ����. <div> </font> </h2></center></div><br>',NULL),(95,'p_1339754666','<table border=\\\"1\\\" cols=\\\"2\\\">\r\n    <tbody>\r\n        <tr>\r\n            <th>\r\n            <p align=\\\"center\\\">�.�.�.</p>\r\n            </th>\r\n            <div align=\\\"center\\\">\r\n            <th>���������</th>\r\n            </div>\r\n            <th>\r\n            <p align=\\\"center\\\">�����</p>\r\n            </th>\r\n        </tr>\r\n        <tr align=\\\"center\\\">\r\n            <td height=\\\"70\\\">\r\n            <p align=\\\"center\\\">�������� ��������� �����������</p>\r\n            </td>\r\n            <td>\r\n            <p align=\\\"center\\\">����������</p>\r\n            </td>\r\n            <td>\r\n            <p align=\\\"center\\\">���������</p>\r\n            </td>\r\n        </tr>\r\n        <tr align=\\\"center\\\">\r\n            <td>\r\n            <p align=\\\"center\\\">������ ������� ������, ��� 2</p>\r\n            </td>\r\n            <td>\r\n            <p align=\\\"center\\\">������ ������� 2, ��� 2</p>\r\n            </td>\r\n            <td>\r\n            <p align=\\\"center\\\">������ ������� 2, ��� 2</p>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>',NULL),(96,'p_1339754799','<a name=\\\"jjj\\\"></a><blockquote style=\\\"MARGIN-RIGHT: 0px\\\" dir=\\\"ltr\\\">\r\n<div align=\\\"left\\\"><font color=\\\"#008080\\\"><font size=\\\"4\\\">� ��� ��.��������� ������� � ������� ��������� ������������: <br />\r\n</font><font color=\\\"#000000\\\"><font size=\\\"4\\\">-��������� ����; <br />\r\n-7 ������������ �����������; <br />\r\n-��� Panasonic - 7 ��.; <br />\r\n-������� HP - 5 ��.</font> <br />\r\n</font></font></div>\r\n</blockquote>\r\n<h2 align=\\\"left\\\"><blockquote>\r\n<p><font color=\\\"#008080\\\"><font size=\\\"3\\\">������������ ��������� ��: <br />\r\n</font><font color=\\\"#000000\\\" size=\\\"3\\\">����� �������� ����������� ��������� �� ����������� �������� Opac Global, ������������� ������������ &quot;���-�&quot;. <br />\r\n� ��������� ����� ��������� &laquo;���-�&raquo; ����������� 3 ������ ����. OPAC-Global &ndash; ������������������� ������������������ �����������-�������������� �������, <br />\r\n������������� � ����������� ����������� web-�������� � web-��������. <br />\r\n������������� ��� ������������� ������������� ������ ���� ���������, <br />\r\n���������� ���������� ������ �������. ������������� ������� ����� ���� ����������� ��� �� ���� ����������������� �������,<br />\r\n��� � �� ���� �������������� �������� � ������ ���������� ������ ������� http. <br />\r\n� ����� �������, ��� ������� ������������ ��������� � ����� ������������ web-��������. ����� ��������� � ���� ����� ���� 100 � �����. &bull; OPAC-Global ����� � ������ ���������������� �������� �������� ��������� ������ ������ ������ (www.nilc.ru). &bull; OPAC-midi &ndash; ������������� ����������� ���� OPAC-Global, �� ����������� ��� ������������� ������� ��������� ��� ��������� ������������ �����. &bull; OPAC-mini &ndash; ����, ��������������� ��� ������������� ������� � ������ ��������� � ������ ����������� �������, <br />\r\n���������� ��������������� � ������ ������������� ����������������� � ������������ ������� �� ������� ��������� ���������� ������. </font></font></p>\r\n</blockquote></h2>\r\n<blockquote style=\\\"MARGIN-RIGHT: 0px\\\" dir=\\\"ltr\\\">\r\n<div align=\\\"left\\\"><font color=\\\"#008080\\\"><font size=\\\"4\\\">����������� ������������ ��������� � ��������� ������ ������� <br />\r\n</font><font color=\\\"#000000\\\"><font size=\\\"4\\\">����������� ����������� ��������� � ����������� ������-������. <br />\r\n������� ���������� ������ ������� ����� ��������� � ��������� �������� HTTP. <br />\r\n������� ���������� ������ ������ (����) - ADABAS. <br />\r\n���������� ����� �������� � ����������� web-��������� �� IBM PC ���������� �����������. <br />\r\n��������� ����� �������� �� ���������������� � ����������������� �����������, �������������� ����������� web-������� � ������� ��� ������. <br />\r\nIBM PC ����������� ����������, OS MS Windows NT/2000 <br />\r\nSUN, OS Solaris <br />\r\nIBM RS 6000, OS AIX <br />\r\nHP, OS HP-UX</font> <br />\r\nS 390, OS/390 <br />\r\n</font></font></div>\r\n</blockquote>',NULL),(97,'consult','',NULL),(98,'about','',NULL),(99,'manage','',NULL),(100,'service','',NULL),(101,'reports','',NULL),(103,'tsg_news','',NULL),(104,'opros','',NULL),(105,'photo','',NULL),(106,'zakupki','',NULL),(107,'auth',NULL,NULL),(108,'project','<p>I noticed that in order to deal with UTF-8 texts, without having to recompile php with the PCRE UTF-8 flag enabled, you can just add the following sequence at the start of your pattern: (*UTF8)</p>\r\n\r\n<p>for instance : &#39;#(*UTF8)[[:alnum:]]#&#39; will return TRUE for &#39;&eacute;&#39; where &#39;#[[:alnum:]]#&#39; will return FALSE</p>\r\n\r\n<p>found this very very useful tip after hours of research over the web directly in pcre website right here : http://www.pcre.org/pcre.txt<br />\r\nthere are many further informations about UTF-8 support in the lib</p>\r\n\r\n<p>hop that will help!</p>\r\n\r\n<p>--<br />\r\ncedric</p>\r\n',NULL),(109,'commerce','<p>preg_filter returns<br />\r\nArray<br />\r\n(<br />\r\n[0] =&gt; A:C:1<br />\r\n[1] =&gt; B:C:a<br />\r\n[2] =&gt; A:2<br />\r\n[3] =&gt; B:b<br />\r\n[4] =&gt; A:3<br />\r\n[7] =&gt; A:4<br />\r\n)<br />\r\npreg_replace returns<br />\r\nArray<br />\r\n(<br />\r\n[0] =&gt; A:C:1<br />\r\n[1] =&gt; B:C:a<br />\r\n[2] =&gt; A:2<br />\r\n[3] =&gt; B:b<br />\r\n[4] =&gt; A:3<br />\r\n[5] =&gt; A<br />\r\n[6] =&gt; B<br />\r\n[7] =&gt; A:4<br />\r\n)</p>\r\n',NULL),(110,'userguide','',NULL),(111,'feedb','<p>����� �� ������ ������ ������� ������������ �����</p>\r\n',NULL),(112,'howto','',NULL),(113,NULL,NULL,NULL),(114,'vendor_auth','',NULL),(115,'zakupki_1369735398','',NULL),(120,'tarif',NULL,NULL),(121,'podbor','',NULL),(122,'podgotov','',NULL),(123,'uchastie','',NULL),(124,'contract','',NULL);
/*!40000 ALTER TABLE `site_pages` ENABLE KEYS */;

#
# Structure for table "site_shablons"
#

DROP TABLE IF EXISTS `site_shablons`;
CREATE TABLE `site_shablons` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text,
  `preview_image` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;

#
# Data for table "site_shablons"
#

/*!40000 ALTER TABLE `site_shablons` DISABLE KEYS */;
INSERT INTO `site_shablons` VALUES (1,'index','index.html',NULL,NULL),(2,'inner','inner.html',NULL,NULL),(3,'auth','auth.html',NULL,NULL);
/*!40000 ALTER TABLE `site_shablons` ENABLE KEYS */;

#
# Structure for table "site_tree"
#

DROP TABLE IF EXISTS `site_tree`;
CREATE TABLE `site_tree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(10) unsigned DEFAULT NULL,
  `parent` int(10) unsigned DEFAULT NULL,
  `section` enum('0','1') DEFAULT '0',
  `fix` enum('0','1') DEFAULT '0',
  `pabl` enum('0','1') DEFAULT '1',
  `page` varchar(255) DEFAULT NULL,
  `menu` enum('0','1') DEFAULT '1',
  `name` varchar(255) DEFAULT NULL,
  `shablon` tinyint(6) DEFAULT NULL,
  `main_section` varchar(255) DEFAULT NULL,
  `section1` varchar(255) DEFAULT NULL,
  `section2` varchar(255) DEFAULT NULL,
  `section3` varchar(255) DEFAULT NULL,
  `section4` varchar(255) DEFAULT NULL,
  `section5` varchar(255) DEFAULT NULL,
  `section6` varchar(255) DEFAULT NULL,
  `FieldName` tinyint(3) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=168 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;

#
# Data for table "site_tree"
#

/*!40000 ALTER TABLE `site_tree` DISABLE KEYS */;
INSERT INTO `site_tree` VALUES (1,0,0,'1','1','1','���������','1','���������',1,NULL,'modul=42;','modul=44;','modul=54;','','','',NULL,'','',''),(2,0,1,'1','1','1','p_1208089819','1','�������',1,'page=10;','modul=42;','modul=44;','modul=54;',NULL,'','',NULL,NULL,'',''),(3,0,2,'0','0','1','index','1','�������',1,'page=10;','modul=42;','modul=44;','modul=54;',NULL,'','',NULL,'�������','',''),(16,59,1,'1','1','1','error404','0','Error404',1,NULL,'modul=42;','modul=44;','modul=54;',NULL,'','',NULL,'','',''),(17,1,16,'0','1','1','error404','1','Error404',1,NULL,'modul=42;','modul=44;','modul=54;',NULL,'','',NULL,'','',''),(130,65,1,'1','1','1','auth','0','�����������',1,'modul=22;','modul=42;','modul=44;','modul=54;',NULL,'','',NULL,'�����������','',''),(131,56,130,'0','1','1','auth','0','�����������',1,'modul=22;','modul=42;','modul=44;','modul=54;',NULL,'','',NULL,'�����������','',''),(135,68,1,'1','0','1',NULL,'0','������� ������������',2,NULL,'modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(148,81,145,'0','0','1','userguide','1','���������������� ����������',1,'page=110;','modul=42;',NULL,'modul=54;',NULL,NULL,NULL,NULL,'���������������� ����������','',''),(151,0,149,'0','0','1','feedb','1','�������� �����',1,'page=111;modul=56;','modul=42;',NULL,'modul=54;',NULL,NULL,NULL,NULL,'�������� �����','',''),(152,1,135,'0','0','1','lk','1','������ �������',1,'modul=57;',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(160,82,1,'1','0','1',NULL,'1','��������',1,NULL,'modul=42;',NULL,'page=3;',NULL,NULL,NULL,NULL,'��������','',''),(161,83,160,'0','0','1','podbor','1','��������',1,'page=121;modul=31;','modul=42;',NULL,'page=3;',NULL,NULL,NULL,NULL,'��������','',''),(162,84,1,'1','0','1',NULL,'1','���������� ������',1,NULL,'modul=42;',NULL,'page=3;',NULL,NULL,NULL,NULL,'���������� ������','',''),(163,85,162,'0','0','1','prep','1','���������� ������',1,'page=122;modul=31;','modul=42;',NULL,'page=3;',NULL,NULL,NULL,NULL,'���������� ������','',''),(164,86,1,'1','0','1',NULL,'1','�������',1,NULL,'modul=42;',NULL,'page=3;',NULL,NULL,NULL,NULL,'�������','',''),(165,87,164,'0','0','1','contrib','1','�������',1,'page=123;modul=31;','modul=42;',NULL,'page=3;',NULL,NULL,NULL,NULL,'�������','',''),(166,88,1,'1','0','1',NULL,'1','���������',1,NULL,'modul=42;',NULL,'page=3;',NULL,NULL,NULL,NULL,'���������','',''),(167,89,166,'0','0','1','contract','1','���������',1,'page=124;modul=31;','modul=42;',NULL,'page=3;',NULL,NULL,NULL,NULL,'���������','','');
/*!40000 ALTER TABLE `site_tree` ENABLE KEYS */;

#
# Structure for table "status"
#

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=cp1251 ROW_FORMAT=COMPACT;

#
# Data for table "status"
#

INSERT INTO `status` VALUES (1,'������',1),(2,'����� �� �������',1),(3,'���������� ������',1),(5,'����� �� �������',2),(6,'�������',NULL),(7,'����� �� �������',NULL),(8,'��������',NULL);

#
# Structure for table "status_contract"
#

DROP TABLE IF EXISTS `status_contract`;
CREATE TABLE `status_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251 ROW_FORMAT=COMPACT;

#
# Data for table "status_contract"
#

INSERT INTO `status_contract` VALUES (1,'��������',1),(2,'����������',1),(3,'��������',1);

#
# Structure for table "status_prep"
#

DROP TABLE IF EXISTS `status_prep`;
CREATE TABLE `status_prep` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=cp1251 ROW_FORMAT=COMPACT;

#
# Data for table "status_prep"
#

INSERT INTO `status_prep` VALUES (1,'�� ������� � ������',1),(2,'������� � ������',1),(3,'���������� ���������',1),(4,'�� ���������� ���������',2);

#
# Structure for table "status_uch"
#

DROP TABLE IF EXISTS `status_uch`;
CREATE TABLE `status_uch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251 ROW_FORMAT=COMPACT;

#
# Data for table "status_uch"
#

INSERT INTO `status_uch` VALUES (1,'������ ���������',1),(2,'��������',1),(3,'���������',1);

#
# Structure for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT '1',
  `tel` varchar(255) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `date` int(10) unsigned DEFAULT NULL,
  `pass_text` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;

#
# Data for table "users"
#

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (43,'a@m.ru','c4ca4238a0b923820dcc509a6f75849b','�����',5,'','',NULL,'1'),(44,'s@m.ru','c81e728d9d4c2f636f067f89cc14862c','����',0,NULL,NULL,NULL,'2');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
