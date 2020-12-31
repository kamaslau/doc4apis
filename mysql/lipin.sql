DROP TABLE IF EXISTS `address` ;

CREATE TABLE `address` (
  `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '地址ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '所属用户ID',
  `brief` varchar(10) DEFAULT NULL COMMENT '简称；例如“母亲家”，最多10个字符',
  `is_default` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为默认项；0否1是',
  `fullname` varchar(10) NOT NULL COMMENT '姓名；最多10个字符',
  `mobile` varchar(11) NOT NULL COMMENT '手机号；不支持固定电话号码',
  `nation` varchar(10) DEFAULT '中国' COMMENT '国别；默认“中国”，最多10个字符',
  `province` varchar(10) NOT NULL COMMENT '省；省级行政区/直辖市，最多10个字符',
  `city` varchar(10) NOT NULL COMMENT '市；地级行政区，最多10个字符',
  `county` varchar(10) DEFAULT NULL COMMENT '区/县；县级行政区，最多10个字符',
  `street` varchar(10) NOT NULL COMMENT '街道；街道级行政区，最多10个字符',
  `detail` varchar(50) DEFAULT NULL COMMENT '具体地址；5-50个字符',
  `longitude` varchar(10) DEFAULT NULL COMMENT '经度；高德坐标系，最多10个字符，小数点后保留5位数字，下同',
  `latitude` varchar(10) DEFAULT NULL COMMENT '纬度；高德坐标系，最多10个字符，小数点后保留5位数字，下同',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者ID',
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='地址信息表';

DROP TABLE IF EXISTS `apply` ;

CREATE TABLE `apply` (
  `apply_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '申请ID',
  `uuid` varchar(36) NOT NULL COMMENT 'UUID',
  `biz_id` bigint(20) unsigned NOT NULL COMMENT '所属企业ID',
  `biz_name` varchar(15) NOT NULL COMMENT '所属企业名称；简称',
  `branch_id` int(11) unsigned DEFAULT NULL COMMENT '所属办公地点ID',
  `branch_name` varchar(20) DEFAULT NULL COMMENT '所属办公地点名称',
  `section_id` int(11) unsigned DEFAULT NULL COMMENT '所属部门ID',
  `section_name` varchar(20) DEFAULT NULL COMMENT '所属部门名称',
  `post_id` bigint(20) unsigned NOT NULL COMMENT '职位ID',
  `post_name` varchar(40) DEFAULT NULL COMMENT '职位名称；2-40个字符',
  `salary_min` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '月薪最低值；100的倍数，1000-1千万',
  `salary_max` int(8) unsigned DEFAULT '0' COMMENT '月薪最高值；100的倍数，1100-1千万',
  `user_id` int(11) unsigned NOT NULL COMMENT '候选人用户ID',
  `user_name` varchar(21) DEFAULT NULL COMMENT '候选人姓名；2-21个字符',
  `agent_id` int(11) unsigned DEFAULT NULL COMMENT '经纪人用户ID',
  `agent_name` varchar(21) DEFAULT NULL COMMENT '经纪人姓名；2-21个字符',
  `promoter_id` int(11) unsigned DEFAULT NULL COMMENT '推广者用户ID',
  `promoter_name` varchar(21) DEFAULT NULL COMMENT '推广者姓名；2-21个字符',
  `repricer_id` int(11) unsigned DEFAULT NULL COMMENT '改价者用户ID',
  `stage` enum('0','10','20','30','40','50','60','70') NOT NULL DEFAULT '0' COMMENT '阶段；0待查看、5已查看、10HR初审通过、20部门初审通过、30待面试、40协商待遇、50待入职、60待转正、70已转正',
  `status` enum('active','close','halt','done') NOT NULL DEFAULT 'active' COMMENT '状态；active进行中、close企业中止、halt人才中止、done已完成',
  `time_create` int(10) unsigned NOT NULL COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间',
  `time_reprice` int(10) unsigned DEFAULT NULL COMMENT '改价时间；UNIX时间戳',
  `time_accept` int(10) unsigned DEFAULT NULL COMMENT 'HR初审时间；UNIX时间戳',
  `time_approve` int(10) unsigned DEFAULT NULL COMMENT '部门初审时间；UNIX时间戳',
  `time_admit` int(10) unsigned DEFAULT NULL COMMENT '面试通过时间；UNIX时间戳',
  `time_offer` int(10) unsigned DEFAULT NULL COMMENT '邀请入职时间；UNIX时间戳',
  `time_agree` int(10) unsigned DEFAULT NULL COMMENT '同意入职时间；UNIX时间戳',
  `time_enroll` int(10) unsigned DEFAULT NULL COMMENT '入职时间；UNIX时间戳',
  `time_close` int(10) unsigned DEFAULT NULL COMMENT '企业中止时间；UNIX时间戳',
  `time_halt` int(10) unsigned DEFAULT NULL COMMENT '用户中止时间；UNIX时间戳',
  `time_done` int(10) unsigned DEFAULT NULL COMMENT '留用时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`apply_id`),
  KEY `post_id_idx` (`post_id`),
  KEY `user_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='职位申请信息表';

DROP TABLE IF EXISTS `article` ;

CREATE TABLE `article` (
  `article_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `category_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '所属分类ID',
  `title` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '标题；3-30个字符',
  `excerpt` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '摘要；10-100个字符',
  `content` varchar(20000) CHARACTER SET utf8 NOT NULL COMMENT '正文；10-20000个字符',
  `url_name` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '自定义域名；3-30个字符',
  `url_figure` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '形象图/视频；URL',
  `url_figure_thumb` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '形象视频缩略图；仅url_figure为视频时适用',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='平台文章信息表';

DROP TABLE IF EXISTS `article_category` ;

CREATE TABLE `article_category` (
  `category_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章分类ID',
  `parent_id` tinyint(3) unsigned DEFAULT '0' COMMENT '所属分类ID',
  `name` varchar(30) NOT NULL COMMENT '名称；3-30个字符',
  `url_name` varchar(30) DEFAULT NULL COMMENT '自定义域名；3-30个字符',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类信息表';

DROP TABLE IF EXISTS `bank` ;

CREATE TABLE `bank` (
  `bank_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '银行ID',
  `name` varchar(20) DEFAULT NULL COMMENT '名称；4-20个字符',
  `brief_name` varchar(30) NOT NULL COMMENT '通称；4-30个字符',
  `first_letter` varchar(1) NOT NULL COMMENT '发音首字母',
  `url_logo` varchar(255) DEFAULT NULL COMMENT 'LOGO；URL',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='银行信息表';

DROP TABLE IF EXISTS `bank_card` ;

CREATE TABLE `bank_card` (
  `card_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '银行卡ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '所属用户ID',
  `number` varchar(19) NOT NULL COMMENT '卡号；中国境内借记卡，最长19位',
  `bank_name` varchar(30) DEFAULT NULL COMMENT '开户行名称；通称',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='银行卡信息表';

DROP TABLE IF EXISTS `biz` ;

CREATE TABLE `biz` (
  `biz_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '企业ID',
  `uuid` varchar(36) CHARACTER SET utf8 NOT NULL COMMENT 'UUID',
  `owner_id` int(11) unsigned NOT NULL COMMENT '所有者用户ID',
  `industry_id` tinyint(3) unsigned DEFAULT '0' COMMENT '所属行业ID',
  `identity_id` int(11) unsigned DEFAULT '0' COMMENT '企业认证ID',
  `index_id` int(10) unsigned DEFAULT '0' COMMENT '排序序号',
  `name` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '企业全称；5-40个字符',
  `brief_name` varchar(15) CHARACTER SET utf8 DEFAULT NULL COMMENT '企业简称；2-15个字符',
  `ticker_base` varchar(10) DEFAULT NULL COMMENT '股票交易所；最长10个字符',
  `ticker` varchar(8) CHARACTER SET utf8 DEFAULT NULL COMMENT '股票代码；最长8位',
  `url_name` varchar(15) CHARACTER SET utf8 DEFAULT NULL COMMENT '自定义域名；2-15个字符，仅允许英文小写及下划线，未设置此项者只能通过传入biz_id参数访问',
  `url_logo` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'LOGO；不含根域名的图片文件相对路径',
  `slogan` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '宣传语；3-30个字符',
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '简介；最多255个字符',
  `stuff_count` int(8) unsigned DEFAULT '1' COMMENT '员工人数',
  `branch_id` int(11) unsigned DEFAULT NULL COMMENT '默认办公地点ID',
  `id_status` enum('NONE','HOLD','GOOD') CHARACTER SET utf8 NOT NULL DEFAULT 'NONE' COMMENT '认证状态；NONE未认证HOLD冻结GOOD已认证',
  `status` enum('END','HOLD','NORMAL') CHARACTER SET utf8 NOT NULL DEFAULT 'HOLD' COMMENT '状态；END已注销HOLD冻结NORMAL正常',
  `max_bonus` int(8) unsigned DEFAULT '0' COMMENT '最高奖金；当地货币，最低1000，需为100的倍数',
  `max_probation` tinyint(3) unsigned DEFAULT '90' COMMENT '最长试用期；自然日，最高255',
  `reputation_mark` tinyint(3) unsigned DEFAULT '60' COMMENT '信用分；0-100',
  `violation_count` tinyint(3) unsigned DEFAULT '0' COMMENT '违约数；1800天（约5个自然年）内',
  `time_create` int(10) unsigned NOT NULL COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`biz_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='商家信息表';

DROP TABLE IF EXISTS `branch` ;

CREATE TABLE `branch` (
  `branch_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '办公地点ID',
  `biz_id` bigint(20) unsigned NOT NULL COMMENT '所属企业ID',
  `name` varchar(20) DEFAULT NULL COMMENT '名称；3-20个字符',
  `nation` varchar(10) NOT NULL DEFAULT '中国' COMMENT '国别；默认“中国”，最多10个字符',
  `province` varchar(10) NOT NULL COMMENT '省；省级行政区/直辖市，最多10个字符',
  `city` varchar(10) NOT NULL COMMENT '市；地级行政区，最多10个字符',
  `county` varchar(10) NOT NULL COMMENT '区/县；区县级行政区，最多10个字符',
  `street` varchar(50) NOT NULL COMMENT '具体地址；5-50个字符',
  `longitude` varchar(10) DEFAULT NULL COMMENT '经度；高德坐标系，最多10个字符，小数点后保留5位数字',
  `latitude` varchar(10) DEFAULT NULL COMMENT '纬度；高德坐标系，最多10个字符，小数点后保留5位数字',
  `longlat` varchar(21) DEFAULT NULL COMMENT '经纬度；高德坐标系，以半角逗号分隔的经纬度信息，精度为小数点后第5位，例如120.42285,36.07191',
  `status` enum('正常','冻结') NOT NULL DEFAULT '正常' COMMENT '状态；正常、冻结',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='办公地点信息表';

DROP TABLE IF EXISTS `captcha` ;

CREATE TABLE `captcha` (
  `captcha_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片验证码ID',
  `user_ip` varchar(39) DEFAULT NULL COMMENT '用户IP地址；支持IPv6',
  `text` varchar(6) NOT NULL COMMENT '验证码内容；最多6个字符',
  `time_expire` int(10) unsigned NOT NULL COMMENT '失效时间；UNIX时间戳',
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片验证码信息表';

DROP TABLE IF EXISTS `career` ;

CREATE TABLE `career` (
  `career_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '履历ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '所属用户ID',
  `org_name` varchar(30) NOT NULL COMMENT '机构名称；4-30个字符',
  `time_start` mediumint(6) unsigned NOT NULL COMMENT '起始时间；YYYYMM',
  `time_end` mediumint(6) unsigned DEFAULT NULL COMMENT '结束时间；YYYYMM',
  `job_category_id` tinyint(3) unsigned DEFAULT NULL COMMENT '职位类别ID',
  `section` varchar(20) DEFAULT NULL COMMENT '部门；3-20个字符',
  `title` varchar(20) NOT NULL DEFAULT '''员工''' COMMENT '职位；2-20个字符',
  `level` enum('专员/职员','组长','主管/主任','经理','总监','总经理/C级','总裁/CEO','董事长/主席') DEFAULT '专员/职员' COMMENT '级别',
  `salary` int(8) unsigned DEFAULT '0' COMMENT '月薪；100的倍数，1000-1千万',
  `salary_count` tinyint(12) unsigned NOT NULL DEFAULT '12' COMMENT '年度总月薪数；最低12',
  `privacy` enum('公开','半公开','不公开') NOT NULL DEFAULT '公开' COMMENT '公开程度',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`career_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='工作/实习信息表（resume_career）';

DROP TABLE IF EXISTS `comment_biz` ;

CREATE TABLE `comment_biz` (
  `comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `biz_id` bigint(20) unsigned NOT NULL COMMENT '相关商家ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '相关用户ID；面试者',
  `interview_id` bigint(20) unsigned NOT NULL COMMENT '所属面试ID；仅面试过才可评价',
  `score_true` tinyint(1) unsigned DEFAULT '4' COMMENT '描述相符；5分制整数，信息真实可信',
  `score_com` tinyint(1) unsigned DEFAULT '4' COMMENT '交流气氛；5分制整数，态度平等尊重，内容专业有价值',
  `score_env` tinyint(1) unsigned DEFAULT '4' COMMENT '办公环境；5分制整数，交通便利度、空间舒适度',
  `content` varchar(255) DEFAULT NULL COMMENT '评价内容；最多255个字',
  `url_images` varchar(255) DEFAULT NULL COMMENT '评价图片；不含根域名的图片相对路径URL，CSV格式，最多4项',
  `status` enum('正常','冻结') DEFAULT '正常' COMMENT '状态；正常、冻结',
  `time_create` int(10) unsigned NOT NULL COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`comment_id`),
  KEY `_biz_id` (`biz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商家评论信息表';

DROP TABLE IF EXISTS `comment_user` ;

CREATE TABLE `comment_user` (
  `comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `biz_id` bigint(20) unsigned NOT NULL COMMENT '相关商家ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '相关用户ID；面试者',
  `interview_id` bigint(20) unsigned NOT NULL COMMENT '所属面试ID；仅面试过者可写评价',
  `score_true` tinyint(1) unsigned DEFAULT '4' COMMENT '描述相符；5分制整数，信息真实可信',
  `score_com` tinyint(1) unsigned DEFAULT '4' COMMENT '交流气氛；5分制整数，态度平等尊重，内容专业有价值',
  `score_pro` tinyint(1) unsigned DEFAULT '4' COMMENT '专业能力；5分制整数，交流中体现的履职能力',
  `content` varchar(255) DEFAULT '好评不解释！' COMMENT '评价内容；最多255个字',
  `url_images` varchar(255) DEFAULT NULL COMMENT '评价图片；不含根域名的图片相对路径URL，CSV格式，最多4项',
  `status` enum('正常','冻结') DEFAULT '正常' COMMENT '状态；正常、冻结',
  `time_create` int(10) unsigned NOT NULL COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户评论信息表；商家对用户的评论';

DROP TABLE IF EXISTS `edu` ;

CREATE TABLE `edu` (
  `edu_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '学历ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '所属用户ID',
  `org_name` varchar(30) NOT NULL COMMENT '机构名称；4-30个字符',
  `year_start` smallint(4) unsigned NOT NULL COMMENT '起始年份；YYYY',
  `year_end` smallint(4) unsigned DEFAULT NULL COMMENT '结束年份；YYYY',
  `major` varchar(30) DEFAULT NULL COMMENT '专业；2-30个字符',
  `degree` enum('5','10','20','30','40','50','60','70','80') NOT NULL COMMENT '学位；5小学、10初中、20职高/中专/技校、30高中、40专科/副学士、50本科/学士、60硕士/双学士、70副博士、80博士',
  `status` enum('10','5','0') DEFAULT '10' COMMENT '状态；0就读、5肄业、10毕业',
  `url_images` varchar(255) DEFAULT NULL COMMENT '图片；URL，CSV格式',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`edu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='教育/培训信息表（resume_edu）';

DROP TABLE IF EXISTS `fav_biz` ;

CREATE TABLE `fav_biz` (
  `record_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏记录ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '所属用户ID',
  `biz_id` bigint(20) unsigned NOT NULL COMMENT '商家ID',
  `biz_name` varchar(15) NOT NULL COMMENT '商家名称；简称',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商家收藏信息表';

DROP TABLE IF EXISTS `fav_post` ;

CREATE TABLE `fav_post` (
  `record_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏记录ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '所属用户ID',
  `post_id` bigint(20) unsigned NOT NULL COMMENT '职位ID',
  `post_name` varchar(40) NOT NULL COMMENT '职位名称',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='职位收藏信息表';

DROP TABLE IF EXISTS `identity_biz` ;

CREATE TABLE `identity_biz` (
  `identity_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '身份ID',
  `biz_id` bigint(20) unsigned NOT NULL COMMENT '所属商家ID',
  `name` varchar(35) NOT NULL COMMENT '主体名称；按照营业执照填写，5-35个字符',
  `code_license` varchar(18) NOT NULL COMMENT '统一社会信用代码；15位营业执照注册号或18位统一社会信用代码',
  `url_image_license` varchar(255) NOT NULL COMMENT '营业执照；不含根域名的图片文件相对路径',
  `status` enum('10','20','30','40') NOT NULL DEFAULT '10' COMMENT '状态；10待受理20已通过30已驳回40已失效',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`identity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='企业认证信息表';

DROP TABLE IF EXISTS `identity_user` ;

CREATE TABLE `identity_user` (
  `identity_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '认证ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '所属用户ID',
  `fullname` varchar(15) NOT NULL COMMENT '姓名；须与身份证一致，2-15个字符',
  `code_ssn` varchar(18) NOT NULL COMMENT '身份证号；最长18位',
  `url_image_ssn` varchar(255) NOT NULL COMMENT '身份证照片；不含根域名的图片文件相对路径',
  `url_verify_photo` varchar(255) DEFAULT NULL COMMENT '用户持身份证照片；不含根域名的图片文件相对路径',
  `status` enum('10','20','30','40') NOT NULL DEFAULT '10' COMMENT '状态；10待受理20已通过30已驳回40已失效',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`identity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='个人认证表';

DROP TABLE IF EXISTS `interview` ;

CREATE TABLE `interview` (
  `int_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '面试ID',
  `uuid` varchar(36) NOT NULL COMMENT 'UUID',
  `biz_id` int(11) unsigned NOT NULL COMMENT '所属企业ID',
  `biz_name` varchar(20) NOT NULL COMMENT '企业名称',
  `post_id` bigint(20) unsigned NOT NULL COMMENT '职位ID',
  `post_name` varchar(30) NOT NULL COMMENT '职位名称',
  `user_id` int(11) unsigned NOT NULL COMMENT '面试者用户ID',
  `user_name` varchar(16) NOT NULL COMMENT '面试者姓名',
  `officer_id` int(11) unsigned DEFAULT NULL COMMENT '面试官用户ID',
  `officer_name` varchar(15) DEFAULT NULL COMMENT '面试官姓名',
  `officer_title` varchar(32) DEFAULT NULL COMMENT '面试官职位',
  `branch_id` int(10) unsigned DEFAULT NULL COMMENT '面试地点ID；现场类型面试必填',
  `branch_address` varchar(100) DEFAULT NULL COMMENT '面试地点地址',
  `branch_location` varchar(21) DEFAULT NULL COMMENT '面试地点位置；以半角逗号分隔的经纬度信息，精度为小数点后第5位，例如120.42285,36.07191',
  `time_start` int(10) unsigned NOT NULL COMMENT '面试开始时间；UNIX时间戳',
  `time_end` int(10) unsigned DEFAULT NULL COMMENT '面试结束时间；UNIX时间戳',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '面试类型；0现场10视频20音频/电话',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态；0待面试10未到场20已到场30已结束10已评价',
  `comment_biz_id` bigint(20) unsigned DEFAULT NULL COMMENT '企业评价ID',
  `comment_user_id` bigint(20) unsigned DEFAULT NULL COMMENT '用户评价ID',
  `time_create` int(10) unsigned NOT NULL COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间',
  `time_checkin` int(10) unsigned DEFAULT NULL COMMENT '到场时间；UNIX时间戳',
  `time_finish` int(10) unsigned DEFAULT NULL COMMENT '完成时间；UNIX时间戳',
  `time_comment_biz` int(10) unsigned DEFAULT NULL COMMENT '企业评价时间；UNIX时间戳，面试官对面试进行评价',
  `time_comment_user` int(10) unsigned DEFAULT NULL COMMENT '用户评价时间；UNIX时间戳，候选人对面试进行评价',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned DEFAULT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`int_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='面试信息表';

DROP TABLE IF EXISTS `notice` ;

CREATE TABLE `notice` (
  `notice_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '通知ID',
  `type` enum('SYSTEM') NOT NULL COMMENT '类型',
  `sender` varchar(255) DEFAULT NULL COMMENT '发送者；留空则为系统发信',
  `receiver` varchar(255) DEFAULT NULL COMMENT '接收者；CLIENT所有用户，BIZ所有企业用户，ADMIN所有系统用户，或JSON条件参数',
  `title` varchar(15) NOT NULL COMMENT '标题',
  `text` varchar(100) NOT NULL COMMENT '文字内容',
  `figure_url` varchar(255) DEFAULT NULL COMMENT '形象图；URL',
  `target_url` varchar(255) DEFAULT NULL COMMENT '跳转目标；路由路径，或网址，及参数',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='通知信息表';

DROP TABLE IF EXISTS `post` ;

CREATE TABLE `post` (
  `post_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '职位ID',
  `uuid` varchar(36) CHARACTER SET utf8 NOT NULL COMMENT 'UUID',
  `biz_id` int(11) unsigned NOT NULL COMMENT '所属企业ID',
  `biz_name` varchar(15) CHARACTER SET utf8 NOT NULL COMMENT '所属企业名称；简称',
  `branch_id` int(11) unsigned DEFAULT NULL COMMENT '所属办公地点ID',
  `branch_name` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '所属办公地点名称',
  `section_id` int(11) unsigned DEFAULT NULL COMMENT '所属部门ID',
  `section_name` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '所属部门名称',
  `code_biz` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '企业内部编码；最多30个字符',
  `name` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '名称；2-40个字符',
  `salary_min` int(8) unsigned NOT NULL DEFAULT '1000' COMMENT '月薪最低值；100的倍数，1000-99999999（即不限）',
  `salary_max` int(8) unsigned DEFAULT '99999999' COMMENT '月薪最高值；100的倍数，1100-99999999（即不限）',
  `salary_count` tinyint(2) unsigned NOT NULL DEFAULT '12' COMMENT '年度总月薪数；最低12',
  `bonus_final` int(8) unsigned NOT NULL DEFAULT '1000' COMMENT '奖金；100的倍数，最低100',
  `probation` tinyint(3) unsigned NOT NULL DEFAULT '90' COMMENT '试用期；自然日，最高255，默认90天',
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '简介',
  `duty` varchar(5000) CHARACTER SET utf8 DEFAULT NULL COMMENT '工作职责',
  `competency` varchar(5000) CHARACTER SET utf8 DEFAULT NULL COMMENT '任职要求',
  `edu_min` tinyint(2) unsigned NOT NULL DEFAULT '50' COMMENT '学历最低要求；详见edu表degree字段，0为不限，默认本科',
  `edu_max` tinyint(2) unsigned NOT NULL DEFAULT '99' COMMENT '学历最高要求；99为不限，最高80',
  `exp_min` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '工作年限最低要求；年，0为不限',
  `exp_max` tinyint(2) unsigned NOT NULL DEFAULT '99' COMMENT '工作年限最高要求；年，99为不限，0-44',
  `age_min` tinyint(2) unsigned NOT NULL DEFAULT '16' COMMENT '年龄最低要求；公历，16-99',
  `age_max` tinyint(2) unsigned NOT NULL DEFAULT '99' COMMENT '年龄最高要求；公历，16-99',
  `type` enum('FL','PT','IT','FT') CHARACTER SET utf8 NOT NULL DEFAULT 'FT' COMMENT '类型；FL远程/自由职业、PT兼职、IT实习、FT全职',
  `status` enum('PAUSE','HOLD','BAN','ACTIVE') CHARACTER SET utf8 NOT NULL DEFAULT 'ACTIVE' COMMENT '状态；有效ACTIVE,企业暂停PAUSE,系统冻结HOLD,系统禁止BAN',
  `time_create` int(10) unsigned NOT NULL COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COMMENT='职位信息表';

DROP TABLE IF EXISTS `refer` ;

CREATE TABLE `refer` (
  `refer_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '推荐记录ID',
  `uuid` varchar(36) NOT NULL COMMENT 'UUID',
  `apply_id` bigint(20) unsigned NOT NULL COMMENT '申请ID',
  `biz_id` int(11) unsigned NOT NULL COMMENT '所属企业ID',
  `biz_name` varchar(15) NOT NULL COMMENT '所属企业名称；简称',
  `post_id` bigint(20) unsigned NOT NULL COMMENT '职位ID',
  `post_name` varchar(40) NOT NULL COMMENT '职位名称；2-40个字符',
  `user_id` int(11) unsigned NOT NULL COMMENT '候选人用户ID',
  `user_name` varchar(21) NOT NULL COMMENT '候选人姓名；2-21个字符',
  `bonus_final` int(8) unsigned NOT NULL COMMENT '奖金',
  `probation` tinyint(3) unsigned NOT NULL COMMENT '试用期',
  `agent_id` int(11) unsigned DEFAULT NULL COMMENT '经纪人用户ID',
  `agent_name` varchar(21) DEFAULT NULL COMMENT '经纪人姓名；2-21个字符',
  `promoter_id` int(11) unsigned DEFAULT NULL COMMENT '推广者用户ID',
  `promoter_name` varchar(21) DEFAULT NULL COMMENT '推广者姓名；2-21个字符',
  `status` enum('active','close','halt','done','payed') NOT NULL DEFAULT 'active' COMMENT '状态；active进行中、close企业中止、halt人才中止、done已完成、payed已结算',
  `time_create` int(10) unsigned NOT NULL COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间',
  `time_apply` int(10) unsigned NOT NULL COMMENT '申请时间；UNIX时间戳',
  `time_offer` int(10) unsigned DEFAULT NULL COMMENT '邀请入职时间；UNIX时间戳',
  `time_enroll` int(10) unsigned DEFAULT NULL COMMENT '入职时间；UNIX时间戳',
  `time_done` int(10) unsigned DEFAULT NULL COMMENT '完成时间；UNIX时间戳',
  `time_pay` int(10) unsigned DEFAULT NULL COMMENT '结算时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`refer_id`),
  KEY `apply_id` (`apply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐记录信息表';

DROP TABLE IF EXISTS `region` ;

CREATE TABLE `region` (
  `region_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区ID',
  `index_code` mediumint(6) unsigned DEFAULT NULL COMMENT '行政区划代码',
  `zip_code` varchar(6) DEFAULT NULL COMMENT '邮政编码',
  `nation` varchar(10) DEFAULT '中国' COMMENT '国别；最多10个字符，默认“中国”',
  `province` varchar(10) NOT NULL COMMENT '省；省级行政区，最多10个字符',
  `province_index` varchar(3) DEFAULT NULL COMMENT '省级索引；前二/三个词/字的首字母',
  `province_abbr` varchar(1) DEFAULT NULL COMMENT '省级简称；例如“鲁”',
  `province_brief` varchar(3) DEFAULT NULL COMMENT '省级通称；例如“山东”',
  `city` varchar(11) NOT NULL COMMENT '市；市级行政区，最多11个字符',
  `city_index` varchar(1) DEFAULT NULL COMMENT '市级索引；首字母',
  `county` varchar(15) NOT NULL COMMENT '区；区县级行政区，最多15个字符（“积石山保安族东乡族撒拉族自治县”等）',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='地区信息表；主要用于省市区选择器等，数据来源 http://www.mca.gov.cn/article/sj/xzqh';

DROP TABLE IF EXISTS `resume` ;

CREATE TABLE `resume` (
  `resume_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '简历ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '所属用户ID',
  `name` varchar(30) DEFAULT NULL COMMENT '名称；2-30个字符',
  `description` varchar(255) DEFAULT NULL COMMENT '自我描述；30-255个字符',
  `year_start` smallint(4) unsigned DEFAULT NULL COMMENT '开始工作年份',
  `wishes` mediumtext COMMENT '求职意向',
  `edus` mediumtext COMMENT '教育/培训经历',
  `careers` mediumtext COMMENT '工作/实习经历',
  `projects` mediumtext COMMENT '项目经历',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`resume_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='简历信息表';

DROP TABLE IF EXISTS `section` ;

CREATE TABLE `section` (
  `section_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '部门ID',
  `biz_id` bigint(20) unsigned NOT NULL COMMENT '所属企业ID',
  `manager_id` int(11) unsigned DEFAULT NULL COMMENT '部门负责人用户ID',
  `name` varchar(20) NOT NULL COMMENT '名称；3-20个字符',
  `name_public` varchar(20) DEFAULT NULL COMMENT '对外名称；3-20个字符',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='部门信息表';

DROP TABLE IF EXISTS `sms` ;

CREATE TABLE `sms` (
  `sms_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '短信ID',
  `mobile` varchar(11) DEFAULT NULL COMMENT '收信手机号；不可与mobile_list同时为空',
  `mobile_list` mediumtext COMMENT '批量发信手机号清单；CSV，不可与mobile同时为空',
  `type` enum('1','2','9') NOT NULL DEFAULT '1' COMMENT '短信类型；1验证码2非验证码9通知类群发短信',
  `captcha` varchar(6) DEFAULT NULL COMMENT '验证码；仅验证码类型有此项',
  `content` varchar(67) DEFAULT NULL COMMENT '短信内容；最多67个字符，非验证码类型有此项',
  `time_to_send` varchar(19) DEFAULT NULL COMMENT '批量发送定时；yyyy-mm-dd hh:ii:ss格式',
  `user_ip` varchar(39) DEFAULT NULL COMMENT '用户IP地址；支持IPv6',
  `time_expire` int(10) unsigned NOT NULL COMMENT '失效时间；UNIX时间戳',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  PRIMARY KEY (`sms_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='短信发送记录资料';

DROP TABLE IF EXISTS `stuff_admin` ;

CREATE TABLE `stuff_admin` (
  `stuff_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '员工ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '对应用户ID',
  `code_biz` varchar(30) DEFAULT NULL COMMENT '企业内部编码；最多30个字符',
  `fullname` varchar(12) NOT NULL COMMENT '姓名',
  `mobile` varchar(11) NOT NULL COMMENT '手机号',
  `title` varchar(30) DEFAULT NULL COMMENT '职位；最多30个字符',
  `role` enum('管理员','经理','成员') DEFAULT '成员' COMMENT '角色',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '级别；0暂不授权，1普通员工，10门店级，20品牌级，30企业级',
  `status` enum('0','20','30','50','100') NOT NULL DEFAULT '100' COMMENT '状态；100在职、50待入职、30待审核、20已冻结、0已离职',
  `time_join` int(10) unsigned DEFAULT NULL COMMENT '入职时间；UNIX时间戳',
  `time_left` int(10) unsigned DEFAULT NULL COMMENT '离职时间；UNIX时间戳',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`stuff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商家员工信息表';

DROP TABLE IF EXISTS `stuff_biz` ;

CREATE TABLE `stuff_biz` (
  `stuff_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '员工ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '对应用户ID',
  `biz_id` bigint(20) unsigned NOT NULL COMMENT '所属企业ID',
  `section_id` bigint(20) unsigned DEFAULT NULL COMMENT '所属部门ID',
  `branch_id` bigint(20) unsigned DEFAULT NULL COMMENT '所属办公地点ID',
  `code_biz` varchar(30) DEFAULT NULL COMMENT '企业内部编码；最多30个字符',
  `fullname` varchar(12) NOT NULL COMMENT '姓名',
  `mobile` varchar(11) NOT NULL COMMENT '手机号',
  `title` varchar(30) DEFAULT NULL COMMENT '职位；最多30个字符',
  `role` enum('管理员','经理','成员') DEFAULT '成员' COMMENT '角色',
  `level` tinyint(3) unsigned DEFAULT '1' COMMENT '级别；0暂不授权，1普通员工，10门店级，20品牌级，30企业级',
  `status` tinyint(3) unsigned DEFAULT '100' COMMENT '状态；100在职、50待入职、30待审核、20已冻结、0已离职默认100',
  `time_join` int(10) unsigned DEFAULT NULL COMMENT '入职时间；UNIX时间戳',
  `time_left` int(10) unsigned DEFAULT NULL COMMENT '离职时间；UNIX时间戳',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者用户ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者用户ID',
  PRIMARY KEY (`stuff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商家员工信息表';

DROP TABLE IF EXISTS `user` ;

CREATE TABLE `user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `uuid` varchar(36) DEFAULT NULL COMMENT 'UUID',
  `identity_id` int(11) unsigned DEFAULT NULL COMMENT '个人认证ID',
  `password` varchar(40) DEFAULT NULL COMMENT '登录密码',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(40) DEFAULT NULL COMMENT '电子邮件地址；最长40位',
  `wechat_id` varchar(20) DEFAULT NULL COMMENT '微信号；6-20个字符',
  `wechat_openid` varchar(255) DEFAULT NULL COMMENT '微信openID',
  `wechat_unionid` varchar(29) DEFAULT NULL COMMENT '微信开放平台unionID',
  `getui_id` varchar(100) DEFAULT NULL COMMENT '个推ID；即个推的CID',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像；图片URL',
  `nickname` varchar(12) DEFAULT NULL COMMENT '昵称；最多12个字符',
  `lastname` varchar(9) DEFAULT NULL COMMENT '姓氏；最多9个汉字“爨邯汕寺武穆云籍鞲”（这不是乱码，真的有这个姓氏啊我去……）',
  `firstname` varchar(6) DEFAULT NULL COMMENT '名；最多6个汉字中文最长名字是“欧阳成功奋发图强”，唉……',
  `gender` enum('M','F','M2F','F2M') DEFAULT NULL COMMENT '性别；M男,F女,M2F男变女,F2M女变男',
  `dob` date DEFAULT NULL COMMENT '生日；公历，YYYY-MM-DD',
  `lob_province` varchar(10) DEFAULT NULL COMMENT '出生省份；即location_of_birth',
  `lob_city` varchar(10) DEFAULT NULL COMMENT '出生城市',
  `lop_province` varchar(10) DEFAULT NULL COMMENT '现居省份；即location_of_presence',
  `lop_city` varchar(10) DEFAULT NULL COMMENT '现居城市',
  `year_start` smallint(4) unsigned DEFAULT NULL COMMENT '开始工作年份',
  `address_id` int(11) unsigned DEFAULT NULL COMMENT '默认收货地址ID',
  `card_id` bigint(20) DEFAULT NULL COMMENT '默认银行卡ID',
  `last_login_timestamp` varchar(10) DEFAULT NULL COMMENT '最后登录时间；UNIX时间戳',
  `last_login_ip` varchar(39) DEFAULT NULL COMMENT '最后登录IP地址；兼容IPv6',
  `role` enum('admin','biz','client') DEFAULT 'client' COMMENT '身份角色；客户端',
  `status` enum('正常','已冻结') NOT NULL DEFAULT '正常' COMMENT '状态',
  `time_create` varchar(10) NOT NULL COMMENT '创建时间；UNIX时间戳',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间；UNIX时间戳',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间；UNIX时间戳',
  `operator_id` int(11) unsigned DEFAULT NULL COMMENT '最后操作者用户ID',
  `promoter_id` int(11) unsigned DEFAULT NULL COMMENT '推广者ID',
  `agent_id` int(11) unsigned DEFAULT NULL COMMENT '经纪人用户ID',
  PRIMARY KEY (`user_id`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192 ROW_FORMAT=DYNAMIC COMMENT='用户信息表';

DROP TABLE IF EXISTS `wish` ;

CREATE TABLE `wish` (
  `wish_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '意向ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '所属用户ID',
  `type` enum('10','20','30') NOT NULL DEFAULT '30' COMMENT '工作性质；30全职、20兼职、10实习',
  `province` varchar(10) NOT NULL COMMENT '期望省份',
  `city` varchar(10) NOT NULL COMMENT '期望城市',
  `salary_min` int(8) unsigned NOT NULL COMMENT '期望最低月薪（元）',
  `industry_ids` tinyint(1) unsigned DEFAULT NULL COMMENT '期望行业；行业ID',
  `post_cat_id` tinyint(1) unsigned DEFAULT NULL COMMENT '期望职位类别；职位类别ID',
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间',
  `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间',
  `creator_id` int(11) unsigned NOT NULL COMMENT '创建者ID',
  `operator_id` int(11) unsigned NOT NULL COMMENT '最后操作者ID',
  PRIMARY KEY (`wish_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='简历-求职意向信息表（resume_wish）';

DROP PROCEDURE IF EXISTS `delete_user_by_id` ;

DELIMITER $$
CREATE DEFINER=`liuyajie728`@`%` PROCEDURE `delete_user_by_id`(
		IN user_id INT UNSIGNED 
)
    COMMENT '删除特定用户所有相关资料（address、comment_biz、comment_item、credit、fav_biz、fav'
BEGIN
DELETE FROM `address` WHERE `user_id` = user_id;
DELETE FROM `comment_biz` WHERE `user_id` = user_id;
DELETE FROM `comment_item` WHERE `user_id` = user_id;
DELETE FROM `credit` WHERE `user_id` = user_id;
DELETE FROM `fav_biz` WHERE `user_id` = user_id;
DELETE FROM `fav_item` WHERE `user_id` = user_id;
DELETE FROM `history_item_view` WHERE `user_id` = user_id;
DELETE FROM `identity_user` WHERE `user_id` = user_id;
DELETE FROM `member_biz` WHERE `user_id` = user_id;
DELETE FROM `message` WHERE `user_id` = user_id;
DELETE FROM `notice` WHERE `user_id` = user_id;
DELETE FROM `order` WHERE `user_id` = user_id;
DELETE FROM `order_items` WHERE `user_id` = user_id;
DELETE FROM `refund` WHERE `user_id` = user_id;
DELETE FROM `refund_record` WHERE `user_id` = user_id;
DELETE FROM `stuff` WHERE `user_id` = user_id;
DELETE FROM `user` WHERE `user_id` = user_id;
END
$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `get_order_items` ;

DELIMITER $$
CREATE DEFINER=`liuyajie728`@`%` PROCEDURE `get_order_items`(
		IN p_order_id INT UNSIGNED 
)
    COMMENT 'return order items'
BEGIN
	/* 获取订单相关商品信息 */
	SELECT `order_id`, `item_id`,  `sku_id`, `count` FROM `order_items` WHERE `order_id` = p_order_id;
END
$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `reset_database` ;

DELIMITER $$
CREATE DEFINER=`liuyajie728`@`%` PROCEDURE `reset_database`()
    DETERMINISTIC
    COMMENT '重置数据库'
BEGIN

/* 优化部分表 */
TRUNCATE TABLE `address`;
TRUNCATE TABLE `apply`;
TRUNCATE TABLE `bank_card`;
TRUNCATE TABLE `biz`;
TRUNCATE TABLE `branch`;
TRUNCATE TABLE `captcha`;
TRUNCATE TABLE `career`;
TRUNCATE TABLE `comment_biz`;
TRUNCATE TABLE `comment_user`;
TRUNCATE TABLE `edu`;
TRUNCATE TABLE `fav_biz`;
TRUNCATE TABLE `fav_post`;
TRUNCATE TABLE `identity_biz`;
TRUNCATE TABLE `identity_user`;
TRUNCATE TABLE `interview`;
TRUNCATE TABLE `notice`;
TRUNCATE TABLE `post`;
TRUNCATE TABLE `refer`;
TRUNCATE TABLE `resume`;
TRUNCATE TABLE `section`;
TRUNCATE TABLE `sms`;
TRUNCATE TABLE `stuff_biz`;
TRUNCATE TABLE `user`;
TRUNCATE TABLE `wish`;

/* 优化部分表 */
OPTIMIZE TABLE `article`, `region`, `stuff_admin`;

END
$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `truncate_user_by_id` ;

DELIMITER $$
CREATE DEFINER=`liuyajie728`@`%` PROCEDURE `truncate_user_by_id`(
		IN user_id INT UNSIGNED 
)
    COMMENT '清空特定用户所有相关资料，但保留账户'
BEGIN

DELETE FROM `address` WHERE `user_id` = user_id;
DELETE FROM `comment_biz` WHERE `user_id` = user_id;
DELETE FROM `comment_item` WHERE `user_id` = user_id;
DELETE FROM `credit` WHERE `user_id` = user_id;
DELETE FROM `fav_biz` WHERE `user_id` = user_id;
DELETE FROM `fav_item` WHERE `user_id` = user_id;
DELETE FROM `history_item_view` WHERE `user_id` = user_id;
DELETE FROM `identity_user` WHERE `user_id` = user_id;
DELETE FROM `member_biz` WHERE `user_id` = user_id;
DELETE FROM `message` WHERE `user_id` = user_id;
DELETE FROM `notice` WHERE `user_id` = user_id;
DELETE FROM `order` WHERE `user_id` = user_id;
DELETE FROM `order_items` WHERE `user_id` = user_id;
DELETE FROM `refund` WHERE `user_id` = user_id;
DELETE FROM `refund_record` WHERE `user_id` = user_id;
DELETE FROM `stuff` WHERE `user_id` = user_id;

UPDATE `user` SET `address_id` = NULL, `wechat_union_id` = NULL, `getui_id` = NULL, `cart_string` = NULL WHERE `user_id` = user_id;
END
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `apply_set_uuid_before_insert` ;

DELIMITER $$
CREATE DEFINER=`liuyajie728`@`%` TRIGGER `lipin`.`apply_set_uuid_before_insert`
BEFORE INSERT ON `lipin`.`apply`
FOR EACH ROW
BEGIN

/**trigger body**/
/* 需要在插入数据之前确定待操作字段值 */
SET NEW.`uuid` = UUID();

END
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `biz_set_uuid_before_insert` ;

DELIMITER $$
CREATE DEFINER=`liuyajie728`@`%` TRIGGER `lipin`.`biz_set_uuid_before_insert`
BEFORE INSERT ON `lipin`.`biz`
FOR EACH ROW
BEGIN

/**trigger body**/
/* 需要在插入数据之前确定待操作字段值 */
SET NEW.`uuid` = UUID();

END
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `interview_set_uuid_before_insert` ;

DELIMITER $$
CREATE DEFINER=`liuyajie728`@`%` TRIGGER `lipin`.`interview_set_uuid_before_insert`
BEFORE INSERT ON `lipin`.`interview`
FOR EACH ROW
BEGIN

/**trigger body**/
/* 需要在插入数据之前确定待操作字段值 */
SET NEW.`uuid` = UUID();

END
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `user_set_uuid_before_insert` ;

DELIMITER $$
CREATE DEFINER=`liuyajie728`@`%` TRIGGER `lipin`.`user_set_uuid_before_insert`
BEFORE INSERT ON `lipin`.`user`
FOR EACH ROW

BEGIN

/**trigger body**/
/* 需要在插入数据之前确定待操作字段值 */
SET NEW.`uuid` = UUID();

END
$$
DELIMITER ;

DROP EVENT IF EXISTS `minute_cleaning` ;

DELIMITER $$
CREATE DEFINER=`liuyajie728`@`%` EVENT `minute_cleaning` ON SCHEDULE EVERY 1 MINUTE STARTS '2021-01-01 00:00:01' ON COMPLETION PRESERVE ENABLE COMMENT '每分钟清理\n优化常用表' DO begin

/* 常用时间间隔 */
set @period_3_days_ago = unix_timestamp() - 60*60*24*3;
set @period_7_days_ago = unix_timestamp() - 60*60*24*7;
set @period_30_days_ago = unix_timestamp() - 60*60*24*30;
set @period_90_days_ago = unix_timestamp() - 60*60*24*90;
set @period_180_days_ago = unix_timestamp() - 60*60*24*180;

/* 清理过期的图形/短信验证码 */
delete from `captcha` where `time_expire` < unix_timestamp();
delete from `sms` where `time_expire` < unix_timestamp();

/* 清理已删除3天的非核心数据 */
delete from `address` where `time_delete` < @period_3_days_ago;
delete from `article` where `time_delete` < @period_3_days_ago;
delete from `article_category` where `time_delete` < @period_3_days_ago;
delete from `bank_card` where `time_delete` < @period_3_days_ago;
delete from `branch` where `time_delete` < @period_3_days_ago;
delete from `career` where `time_delete` < @period_3_days_ago;
delete from `comment_biz` where `time_delete` < @period_3_days_ago;
delete from `comment_user` where `time_delete` < @period_3_days_ago;
delete from `edu` where `time_delete` < @period_3_days_ago;
delete from `fav_biz` where `time_delete` < @period_3_days_ago;
delete from `fav_post` where `time_delete` < @period_3_days_ago;
delete from `region` where `time_delete` < @period_3_days_ago;
delete from `section` where `time_delete` < @period_3_days_ago;
delete from `wish` where `time_delete` < @period_3_days_ago;

/* 优化部分表 */
OPTIMIZE TABLE `apply`, `biz`, `career`, `edu`, `interview`, `post`, `refer`, `stuff_admin`, `stuff_biz`, `user`, `wish`;

end
$$
DELIMITER ;



INSERT INTO `article` (`article_id`, `category_id`, `title`, `excerpt`, `content`, `url_name`, `url_figure`, `url_figure_thumb`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('2','0','用户协议及隐私政策','使用本平台即视为了解并同意此协议。','<p>在您成为我们的用户、使用我们提供的服务之前，请您认真阅读本《用户协议及隐私政策》，更好的了解我们所提供的服务以及您享有的权利和义务。您开始使用<span style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;">我们</span><span style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;">提供的服务，即表示您已经确认并接受了本文件中的全部条款。</span></p>','user-and-privacy-agreements',null,null,'2019-05-17 00:40:08',null,'2020-12-29 20:27:43','1','1');
INSERT INTO `article` (`article_id`, `category_id`, `title`, `excerpt`, `content`, `url_name`, `url_figure`, `url_figure_thumb`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('3','0','关于礼聘','礼聘是一个悬赏招聘平台，为企业更快、更准地招聘称职、稳定的人才。','<p>礼聘是一个奖金制众包招聘平台，为企业更快、更准地招聘称职、可靠、干得住的人才。</p>
<p>如果你有自己的户外展板、电梯屏幕、商超看板、公众号、网站、朋友圈，即可投放招聘广告赚取奖金；如果你有招聘需求，通过礼聘可以让目标人才被直接接触的人脉和信息流多方位覆盖，且大幅提高内推积极性。</p>','about-us',null,null,'2019-06-25 05:18:46',null,'2020-12-29 20:27:41','1','2');
INSERT INTO `article` (`article_id`, `category_id`, `title`, `excerpt`, `content`, `url_name`, `url_figure`, `url_figure_thumb`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('4','0','职位发布规则','您在注册、使用礼聘软件发布职位信息之前，请务必审慎阅读、充分理解本规则。您使用礼聘软件职位信息服务的行为，即表示您已阅读并同意本规则所涉全部内容。','<p>感谢您使用礼聘!本《礼聘职位信息发布规则》(以下简称&ldquo;本规则&rdquo;。)系由您与本产品(以下简称"礼聘")所有者订立的《礼聘用户和隐私协议》的有效组成部分，与《礼聘用户和隐私协议》共同构成您与礼聘合作的法律文件。您在注册、使用礼聘软件发布职位信息之前，请务必审慎阅读、充分理解本规则。您使用礼聘软件职位信息服务的行为，即表示您已阅读并同意本规则所涉全部内容。</p>
<p>礼聘有权根据法律法规、政策及产品需求更新本规则，并通过软件发布更新版本的方式修改本规则，修改后的规则于发布时生效。请您定期访问并查看最新规则。如您继续使用礼聘服务，即表示您接受经修订后的规则。</p>
<p>1.礼聘为用户提供发布职位招聘信息的服务。用户使用礼聘产品应当遵守本规则以及其他与礼聘签署的协议。</p>
<p>2.礼聘有权对不同的职位类别下职位信息设置不同的有效期，有效期届满时该职位信息可能被下线。</p>
<p>3.礼聘有权对用户发布的职位信息进行审核，对于不符合本规则的内容，有权拒绝发布或者删除职位，和/或撤销职位相关的其它功能数据。</p>
<p>4.礼聘不为下述公司提供职位发布服务，因此如使用礼聘服务的用户服务于下述公司的，礼聘有权删除其发布的职位信息</p>
<p>(1)存在猎头、招聘流程外包（即RPO）等代招聘业务的公司</p>
<p>(2)涉及以招聘名义招人培训等情况的公司</p>
<p>(3)所招聘岗位无薪资、工资低于当地最低工资标准、或涉及拒发或恶意拖欠工资的公司</p>
<p>(4)存在淘宝刷单、微商代理、手机兼职、手工兼职业务的公司</p>
<p>(5)存在跟剧组行为的公司</p>
<p>(6)存在兼职模特、试衣模特业务的公司</p>
<p>(7)存在培养演员、练习生、歌手业务的公司</p>
<p>(8)需要求职者去境外工作的公司</p>
<p>(9)夜场等娱乐场所的公司</p>
<p>(10)涉及收取求职者费用的公司</p>
<p>(11)存在直销或传销模式业务的公司</p>
<p>(12)存在信用卡套现的公司</p>
<p>(13)存在挂靠、外派业务的公司</p>
<p>(14)存在招聘网约车司机业务公司</p>
<p>(15)在国家企业信用信息公示系统中被列入经营异常名录，或登记状态为注销/吊销状态的公司</p>
<p>(16)已被法院判定为破产或正在进行破产申请流程的公司</p>
<p>(17)在第三方网站中查询到存在严重失信行为的公司</p>
<p>(18)在国家企业信用信息公示系统中查询不到注册信息的公司</p>
<p>(19)被多位用户举报的公司</p>
<p>(20)涉及发布代孕、捐精、捐卵的业务</p>
<p>(21)存在兼职主播业务的公司</p>
<p>5.用户不得发布包含下列内容的职位信息或其他信息：</p>
<p>(1)包含广告(寻求合作)、传销、直销等内容</p>
<p>(2)包含色情、淫秽内容</p>
<p>(3)包含违法、政治敏感内容</p>
<p>(4)包含不真实的公司信息</p>
<p>(5)包含虚假的职业身份信息</p>
<p>(6)包含不真实的薪资、工作地点，或者工作地点不在中国境内的</p>
<p>(7)包含不真实的职位描述、工作内容</p>
<p>(8)职位类型与职位描述不符的</p>
<p>(9)一个岗位发布为多个职位</p>
<p>(10)职位名称与职位描述不明确的</p>
<p>(11)职位名称包含职位名称以外信息的</p>
<p>(12)涉及违反《劳动合同法》</p>
<p>(13)包含性别歧视或指向性歧视，如地域歧视、婚姻状态歧视等内容</p>
<p>(14)包含人身攻击或其他侵害他人权益的内容</p>
<p>(15)兼职模特、试衣模特类职位</p>
<p>(16)无经验交易员、分析师类职位</p>
<p>(17)网约车司机类职位</p>
<p>(18)非正常招聘助理类职位</p>
<p>(19)跟剧组类职位</p>
<p>(20)演员、练习生、歌手类职位</p>
<p>(21)夜场等娱乐会所类职位</p>
<p>(22)船员、普工类职位且身份存疑</p>
<p>(23)信用卡套现类职位</p>
<p>(24)淘宝刷单、微商代理、手机兼职、手工兼职类职位</p>
<p>(25)游戏代练类职位</p>
<p>(26)被多位用户举报</p>
<p>(27)涉及发布代孕、捐精、捐卵业务</p>
<p>(28)兼职主播类职位</p>
<p>(29)挂靠类职位</p>
<p>(30)发布的职位信息与实际所招聘职位不符</p>
<p>(31)其他(鸡汤，段子，水贴等)</p>
<p>用户发布的职位信息或其他信息包含但不限于上述内容的，经礼聘发现或用户投诉后核实的，礼聘有权驳回其职位发布申请、删除职位信息。</p>
<p>6、礼聘严禁用户利用礼聘服务，实施下述行为：</p>
<p>(1)以培训费、服装费、工作需要等任何名义骗取求职者财物或涉及收取求职者费用、要求求职者贷款等</p>
<p>(2)实施传销活动或诱骗他人加入传销组织</p>
<p>(3)招聘他人从事违法活动</p>
<p>(4)向求职者或其他用户索取或使用与工作内容或相关合法用途无关的个人信息、隐私内容</p>
<p>(5)骚扰其他用户</p>
<p>(6)利用平台可能存在的漏洞获取或修改任何形式的额度、积分、道具、数据等礼聘资产</p>
<p>(7)在第三方售卖礼聘资产</p>
<p>(8)存在辱党、辱国、传播邪教思想的行为</p>
<p>(9)存在使用涉及各国政治人物的图片</p>
<p>(10)存在使用含色情、淫秽内容的图片</p>
<p>(11)存在使用人身攻击或其他侵害他人权益的图片</p>
<p>(12)存在使用广告(寻求合作)的头像</p>
<p>(13)存在帮其他公司招聘或猎头、RPO的行为</p>
<p>(14)以招聘名义招人培训</p>
<p>(15)所招聘岗位无薪资或存在涉及拒发或恶意拖欠工资的行为</p>
<p>(16)招聘入职当天未满16周岁人员</p>
<p>(17)其他关联账号存在违规行为</p>
<p>(18)其他违反法律规定的行为</p>
<p>用户实施上述行为，经礼聘发现或用户投诉后核实的，礼聘有权立即停止对该用户提供服务、删除用户发布的职位及相关功能数据。</p>
<p>7.若用户违反前述第4条、第5条、第6条规定的内容，或者有其他明显损害礼聘利益或违法行为的，礼聘有权视情节采取冻结用户账户的方式暂时乃至永久停止其使用礼聘服务且不做解封与退款处理。如用户行为涉嫌违法犯罪的，礼聘有权向有关行政机关及司法部门举报、披露相关信息。</p>
<p>8.用户违反本规则的，礼聘可以依据本规则直接采取相关措施，并视情节严重程度自主选择是否通知用户。接到通知的用户可以通过客户服务渠道向礼聘提交申诉。</p>','post-publishing-rules',null,null,'2019-07-01 03:50:00',null,'2019-07-01 03:50:00','2','2');
INSERT INTO `article` (`article_id`, `category_id`, `title`, `excerpt`, `content`, `url_name`, `url_figure`, `url_figure_thumb`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('5','0','企业入驻标准及材料需求','符合标准的企业即可申请入驻平台或续约。','<div>
<p>5年内未出现以下记录：</p>
<ul>
<li>违反《安全生产法》等法律法规，并造成相关人员死亡、残疾、或丧失劳动能力。</li>
<li>在劳动或劳务关系相关的仲裁、被诉讼过程中缺席、提出管辖地异议，或在对方无专业代理人的情况下使用专业代理人。</li>
</ul>
<p>3年内未出现以下记录：</p>
<ul>
<li>拖欠任一雇员薪资超过1个自然月。</li>
<li>被劳动监察部门处罚。</li>
<li>任一股份占比超过5%的现任股东被列入失信人。</li>
</ul>
<p>1年内未出现以下记录：</p>
<ul>
<li>对任一劳动仲裁结果提起上诉。</li>
<li>违反《劳动法》、《劳动合同法》、《住房公积金管理条例》等法律法规。</li>
</ul>
</div>
<div>
<p>请准备好这些材料。</p>
<p>对于证件或文书，请确保处于有效期内，并提供原件的清晰照片。</p>
<ul>
<li>营业执照</li>
<li>法人身份证</li>
<li>代办人身份证（若为代办）</li>
<li>代办授权书（若为代办）</li>
</ul>
<div class="bg bg-light">
<h2 class="text-center">代办授权书</h2>
<p>兹授权 ________ （身份证号码 __________________ ）全权处理本司对接「礼聘」平台事宜。</p>
<p>此授权至另行通知撤销之前长期有效。</p>
<br>
<p>企业名称 __________________</p>
<p>统一社会信用代码 __________________</p>
<p>签发日期 ________年____月____日</p>
<p>（请在此处盖企业公章；合同专用章、人事专用章等不适用。）</p>
</div>
</div>','admission-requirements',null,null,'2020-12-22 23:35:03',null,'2020-12-29 20:27:39','9','9');
INSERT INTO `article` (`article_id`, `category_id`, `title`, `excerpt`, `content`, `url_name`, `url_figure`, `url_figure_thumb`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('6','0','经纪人奖金结算流程及收款账户标准','推荐候选人给企业正在招聘的职位，双方互相完成考察并转正后进入奖金结算流程。','<h2>第一章 概述</h2>
<p>第一条【目的和依据】为明确经纪人使用本平台提供的产品服务（以下简称为平台）为委托人（以下又称为招聘方）提供推广服务可获得的佣金计算方式，保障经纪人、招聘方的合法权益，根据平台其它协议规定，特制定本规范。</p>
<p>第二条【规范修订】平台随时可能根据业务的发展等变更本规范并在平台网站（www.urakp.com，下同）上予以公告或以其他形式通知经纪人、招聘方，经纪人、招聘方应及时查看。如经纪人、招聘方不同意相关变更，应立即停止使用平台；如经纪人或招聘方继续使用平台的，视为其同意变更后的规范。</p>
<p>第三条 【生效时间】本规范于2020年12月29日发布，自发布之日起生效。</p>
<h2>第二章 定义</h2>
<p>第四条 站内：指平台（网站、桌面客户端及移动客户端应用或页面，下同），及其他后续平台定位为站内资源的网站或产品（含软件和移动客户端）。应用指在移动端设备安装、使用的软件系统等，如APP等，下同。</p>
<p>第五条 站内经纪人：指在平台事先允许的平台特定区域或产品范围内使用平台为招聘方提供推广服务的经纪人。</p>
<p>第六条 站外经纪人：指在平台以外推广渠道上使用平台为招聘方提供推广服务的经纪人。前述情形下，如经纪人使用站内经纪人的经纪人推广内容至平台以外推广渠道推广的，其仍为站内经纪人。</p>
<p>第七条 职位佣金比率：指招聘方针对职位单独设置的经纪人推广佣金比率。</p>
<p>第八条 部门佣金比率：指招聘方为每个职位所属部门（部门分类以部门信息所在平台后台类目为准）所设置的经纪人推广佣金比率（包括招聘方默认的系统设定比率）。</p>
<p>第九条 招聘方平均佣金比率：该佣金比率只是按招聘方职位及佣金比率估算的一个参考比率，实际的佣金仍按具体职位适用的佣金比率结算。</p>
<p>第十条 招聘方最高佣金比率：该佣金比率只是按招聘方当前开放的职位中佣金比例最高值展示的一个参考比率，实际的佣金仍按具体职位适用的佣金比率结算。</p>
<p>第十一条 特殊推广计划：指招聘方针对职位、部门，或经纪人等特别设置的推广方案，招聘方会针对此类推广计划设置特别的佣金比率，同时此类推广计划会有一定有效时限，并可能设置推广计划申请条件。</p>
<p>第十二条 成交：即成功完成职位推荐，指招聘方通过经纪人的推广服务于限制时间内与应聘者达成签订劳动合同，且确认结束试用期。</p>
<p>第十三条【未定义词语】本规范未作定义的词语或术语，如在平台其它协议中已有定义的，适用其定义；如在平台其它协议中未进行定义的，按照上下文意思及该词语或术语的通常含义理解。</p>
<h2>第三章 【结算流程】</h2>
<p>第十四条 成交的职位申请将进入结算流程。</p>
<p>第十五条 每自然月的第3个工作日前（含），平台将统计各招聘方上一个自然月完成的职位申请，并核定招聘方需支付的奖金账单；</p>
<p>第十六条 招聘方当月15日前（不含）与平台完成账单核对，将总应付金额在当月15日前（含）支付到平台指定账户；</p>
<p>第十七条 平台完成金额核对后，自当月20日起将各经纪人应收的金额支付到该经纪人提供的银行账户；</p>
<p>第十八条 国家税务部门将在支付过程中征缴个人所得税，具体扣缴流水详情可在国务院《个人所得税》APP中查阅。</p>
<h2>第四章 【收款账户要求】</h2>
<p>第十九条 根据《中华人民共和国反洗钱法》、《中华人民共和国个人账户存款实名制规定》，及《中华人民共和国刑法》、《中华人民共和国刑事诉讼法》、《中华人民共和国中国人民银行法》、《中华人民共和国反恐怖主义法》等法律法规及平台要求，经纪人的收款账户必须满足以下要求：</p>
<p>第二十条 经纪人必须为中华人民共和国公民，且已成功在平台进行个人身份认证。</p>
<p>第二十一条 经纪人必须提供于本人名下开户，且预留手机号与其待结算业务所属的平台账户注册手机号相符的银行账户。</p>
<p>第二十二条 经纪人必须提供可正常接受人民币汇款的中国大陆境内银行借记卡账户。</p>
<p>第二十三条 经纪人可提供多个银行账户，以便招聘方和平台优先选择其汇付速度最快、费率最低的银行进行支付。</p>','commisson-pay-process',null,null,'2020-12-29 18:28:10',null,'2020-12-29 21:18:49','1','1');
INSERT INTO `article` (`article_id`, `category_id`, `title`, `excerpt`, `content`, `url_name`, `url_figure`, `url_figure_thumb`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('7','0','经纪人等级体系','平台根据经纪人成交推广的职位申请数量、成功率，评定经纪人等级及相应奖金结算比例和技术服务费率。','<p>根据经纪人在12个月内成交的职位推广数量，及推广成功率（即成交的职位申请数量与招聘方受理的职位申请总数量之比率）同时满足的最高等级标准核定该<span style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;">经纪人等级</span><span style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;">，具体评定标准及平台技术服务费率标准如下。</span><span style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;"></span></p>
<table summary="经纪人等级体系" class="table table-hover">
<thead>
<tr>
<th>等级</th>
<th>头衔</th>
<th>推广成交数</th>
<th>推广成功率</th>
<th>技术服务费率</th>
</tr>
</thead>
<tbody>
<tr>
<td>0</td>
<td>初级寻访员</td>
<td>0</td>
<td>0</td>
<td>0</td>
</tr>
<tr>
<td>1</td>
<td>初级寻访员</td>
<td>1</td>
<td>&gt; 1%</td>
<td>1%</td>
</tr>
<tr>
<td>2</td>
<td>初级寻访员</td>
<td>2</td>
<td>&gt; 2%</td>
<td>2%</td>
</tr>
<tr>
<td>3</td>
<td>初级寻访员</td>
<td>3</td>
<td>&gt; 3%</td>
<td>3%</td>
</tr>
<tr>
<td>4</td>
<td>初级寻访员</td>
<td>4</td>
<td>&gt; 4%</td>
<td>4%</td>
</tr>
<tr>
<td>5</td>
<td>初级寻访员</td>
<td>5</td>
<td>&gt; 5%</td>
<td>5%</td>
</tr>
<tr>
<td>6</td>
<td>中级寻访员</td>
<td>6</td>
<td>&gt; 6%</td>
<td>6%</td>
</tr>
<tr>
<td>7</td>
<td>中级寻访员</td>
<td>7</td>
<td>&gt; 7%</td>
<td>7%</td>
</tr>
<tr>
<td>8</td>
<td>中级寻访员</td>
<td>8</td>
<td>&gt; 8%</td>
<td>8%</td>
</tr>
<tr>
<td>9</td>
<td>中级寻访员</td>
<td>9</td>
<td>&gt; 9%</td>
<td>9%</td>
</tr>
<tr>
<td>10</td>
<td>中级寻访员</td>
<td>10</td>
<td>&gt; 10%</td>
<td>10%</td>
</tr>
<tr>
<td>11</td>
<td>高级寻访员</td>
<td>11</td>
<td>&gt; 11%</td>
<td>11%</td>
</tr>
<tr>
<td>12</td>
<td>高级寻访员</td>
<td>12</td>
<td>&gt; 12%</td>
<td>12%</td>
</tr>
<tr>
<td>13</td>
<td>高级寻访员</td>
<td>13</td>
<td>&gt; 13%</td>
<td>13%</td>
</tr>
<tr>
<td>14</td>
<td>高级寻访员</td>
<td>14</td>
<td>&gt; 14%</td>
<td>14%</td>
</tr>
<tr>
<td>15</td>
<td>高级寻访员</td>
<td>15</td>
<td>&gt; 15%</td>
<td>15%</td>
</tr>
<tr>
<td>16</td>
<td>初级助理顾问</td>
<td>16</td>
<td>&gt; 16%</td>
<td>16%</td>
</tr>
<tr>
<td>21</td>
<td>中级助理顾问</td>
<td>21</td>
<td>&gt; 21%</td>
<td>21%</td>
</tr>
<tr>
<td>26</td>
<td>高级助理顾问</td>
<td>26</td>
<td>&gt; 26%</td>
<td>26%</td>
</tr>
<tr>
<td>31</td>
<td>初级顾问</td>
<td>31</td>
<td>&gt; 31%</td>
<td>31%</td>
</tr>
<tr>
<td>36</td>
<td>中级顾问</td>
<td>36</td>
<td>&gt; 36%</td>
<td>36%</td>
</tr>
<tr>
<td>41</td>
<td>高级顾问</td>
<td>41</td>
<td>&gt; 41%</td>
<td>41%</td>
</tr>
<tr>
<td>46</td>
<td>资深顾问</td>
<td>46</td>
<td>&gt; 46%</td>
<td>46%</td>
</tr>
<tr>
<td>51</td>
<td>合伙人</td>
<td>51</td>
<td>&gt; 51%</td>
<td>51%</td>
</tr>
</tbody>
<tfoot>
<tr>
<td colspan="5" class="text-end">51级封顶</td>
</tr>
</tfoot>
</table>','agent-level',null,null,'2020-12-29 21:34:55',null,'2020-12-29 21:48:16','1','1');




INSERT INTO `biz` (`biz_id`, `uuid`, `owner_id`, `industry_id`, `identity_id`, `index_id`, `name`, `brief_name`, `ticker_base`, `ticker`, `url_name`, `url_logo`, `slogan`, `description`, `stuff_count`, `branch_id`, `id_status`, `status`, `max_bonus`, `max_probation`, `reputation_mark`, `violation_count`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','69b2d605-4835-11eb-be99-7cd30ab8a96e','1','0','0','0','青岛森思壮电子商务有限公司','森思壮',null,null,null,null,null,null,'1',null,'NONE','HOLD','12000','90','60','0','1609082732',null,'2020-12-29 06:14:04','1','1');
INSERT INTO `biz` (`biz_id`, `uuid`, `owner_id`, `industry_id`, `identity_id`, `index_id`, `name`, `brief_name`, `ticker_base`, `ticker`, `url_name`, `url_logo`, `slogan`, `description`, `stuff_count`, `branch_id`, `id_status`, `status`, `max_bonus`, `max_probation`, `reputation_mark`, `violation_count`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('2','164d78d9-4961-11eb-be99-7cd30ab8a96e','3','0','0','0','山东海博科技信息系统股份有限公司','海博科技',null,'872905',null,'biz/url_logo/202012/1230/044811.jpg',null,null,'1',null,'NONE','NORMAL','65000','90','60','0','1609196689',null,'2020-12-30 04:49:33','3','3');
INSERT INTO `biz` (`biz_id`, `uuid`, `owner_id`, `industry_id`, `identity_id`, `index_id`, `name`, `brief_name`, `ticker_base`, `ticker`, `url_name`, `url_logo`, `slogan`, `description`, `stuff_count`, `branch_id`, `id_status`, `status`, `max_bonus`, `max_probation`, `reputation_mark`, `violation_count`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('3','ed499bbb-4a17-11eb-be99-7cd30ab8a96e','4','0','0','0','青岛熙正数字科技有限公司','熙正科技',null,null,null,'biz/url_logo/202012/1230/045456.jpg',null,null,'1',null,'NONE','NORMAL','52000','92','60','0','1609275219',null,'2020-12-30 05:24:19','4','4');
INSERT INTO `biz` (`biz_id`, `uuid`, `owner_id`, `industry_id`, `identity_id`, `index_id`, `name`, `brief_name`, `ticker_base`, `ticker`, `url_name`, `url_logo`, `slogan`, `description`, `stuff_count`, `branch_id`, `id_status`, `status`, `max_bonus`, `max_probation`, `reputation_mark`, `violation_count`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('4','06c63381-4a19-11eb-be99-7cd30ab8a96e','5','0','0','0','青岛易迈进信息技术有限公司','Imaginato',null,null,null,'biz/url_logo/202012/1230/050144.jpg',null,null,'1',null,'NONE','NORMAL','50000','92','60','0','1609275691',null,'2020-12-30 05:20:56','5','5');

INSERT INTO `branch` (`branch_id`, `biz_id`, `name`, `nation`, `province`, `city`, `county`, `street`, `longitude`, `latitude`, `longlat`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','1','总部','中国','山东省','青岛市','市北区','镇江路26号',null,null,null,'正常','2020-12-27 23:31:25',null,'2020-12-27 23:31:25','1','1');


INSERT INTO `career` (`career_id`, `user_id`, `org_name`, `time_start`, `time_end`, `job_category_id`, `section`, `title`, `level`, `salary`, `salary_count`, `privacy`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','7','青岛礼贤信息技术有限公司','201808',null,null,null,'创始人','专员/职员','0','12','公开','2020-12-30 00:46:32',null,'2020-12-30 00:46:32','7','7');



INSERT INTO `edu` (`edu_id`, `user_id`, `org_name`, `year_start`, `year_end`, `major`, `degree`, `status`, `url_images`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','7','Murray State University, KY','2009','2010','经济学','50','5',null,'2020-12-30 00:43:47',null,'2020-12-30 00:43:47','7','7');

INSERT INTO `fav_biz` (`record_id`, `user_id`, `biz_id`, `biz_name`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','2','1','森思壮','2020-12-28 06:32:36',null,'2020-12-28 06:32:36','2','2');


INSERT INTO `identity_biz` (`identity_id`, `biz_id`, `name`, `code_license`, `url_image_license`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','0','青岛森思壮电子商务有限公司','1234567890','identity_biz/url_image_license/202012/1227/192510.jpg','10','2020-12-27 19:25:16',null,'2020-12-27 19:25:16','1','1');

INSERT INTO `identity_user` (`identity_id`, `user_id`, `fullname`, `code_ssn`, `url_image_ssn`, `url_verify_photo`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','1','刘亚杰','370203198907286319','identity_user/url_image_ssn/202012/1227/192014.jpg',null,'10','2020-12-27 19:21:44',null,'2020-12-27 19:21:44','1','1');



INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','','1','森思壮',null,null,null,null,null,'PHP工程师','8000','15000','12','12000','30',null,null,'熟练使用PHP5面向对象方式实现业务需求、缓存机制、单元测试、异常处理等一般性代码
熟练使用MySQL5管理数据库结构及通过PHP5书写数据管理代码
熟练使用文本编辑器书写规范、注释清晰，且冗余度低的代码
熟悉Laravel、CI3、ZF3等至少一种主流开发框架
熟悉基于微信、新浪、淘宝、大众点评等主流开放平台API的应用程序开发
熟悉面向过程、面向对象、面向方面等编程方式的特点及差异
了解PHP5、PHP7之间的主要区别
了解WordPress、Drupal、Magento、ECShop、ECMall等主流开源内容管理系统或电商系统各至少一种','40','99','3','99','16','99','IT','BAN','1609192305',null,'2020-12-30 01:19:14','1','1');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('2','','1','森思壮',null,null,null,null,null,'WEB前端开发工程师','4000','15000','12','8000','60',null,null,'熟练使用div+css技术书写符合搜索引擎优化要求的页面代码
熟练使用jQuery及其衍生插件
熟练使用任何文本编辑器书写规范、注释清晰、且冗余度低的代码
熟悉借助jQuery实现AJAX技术的原理
熟悉HTML5及CSS3技术标准及应用
了解Laravel、CI3、ZF3等至少一种主流开发框架
了解表格切图前端制作技术的原理','40','99','1','99','16','99','FT','PAUSE','1609192550',null,'2020-12-30 01:15:13','1','1');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('3','','2','海博科技',null,null,null,null,null,'招聘主管','7000','10000','12','18200','90',null,'①深入了解业务规划和岗位需求，对标外部人才地图和薪资水平，做好人才的外部储备和跟进以及内部薪酬设置的信息反馈和建议。
②开拓招聘渠道，在熟悉各类渠道优劣势的基础上，快速找到公司需要的人才
③进行人才的前期搜索和简历的快速筛选，为业务精准提供候选人
④协调内部HRG，做好后续人员的面试和结果的跟进反馈，快速在招聘上拿结果
⑤规划化公司人才招聘流程和制度','①全日制统招本科以上学历
②5年以上招聘相关经验，有猎头经验，有市场，销售，Java等技术开发等人才人脉资源者优先；善于挖掘优秀人才资源者优先；
③有要性，带小伙伴拿结果，强烈的目标导向
④抗压性好，皮实，有耐性','50','99','5','99','16','99','FT','ACTIVE','1609273193',null,'2020-12-30 04:19:53','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('4','','2','海博科技',null,null,null,null,null,'技术总监/架构师','20000','40000','12','52000','92',null,'1. 参与制定公司发展战略，负责公司技术发展路线规划和架构规划，组织制定和实施重大技术决策和技术方案;
2．熟悉系统架构整体设计，技术架构选型，功能模块设计、数据结构设计、对外接口设计，解决项目开发过程中的技术难题，包括技术方案、技术路线和核心算法等；
3. 从市场和客户需求出发，不断优化和升级公司系统和架构体系，规划公司未来技术架构方向；
4. 负责制定公司技术规范和执行，负责团队开发人员的技术指导；
5. 负责公司技术部门的技术管理工作。','1. 计算机专业或信息技术专业，本科及以上学历;
2.10年以上大型平台系统的软件开发经验，5年以上架构设计经验，3年以上技术总监或技术经理管理经验，具备二次开发平台构建经验优先;
3. 精通JAVA体系的主流开发技术和框架，熟练运用各类中间件和数据库（Oracle、SqlServer和Mysql），熟悉C/C++和视频技术；
4. 具备丰富的平台架构设计经验，精通常用设计模式和主流设计工具，具备大型系统的总体设计能力；
5. 熟练掌握hadoop大数据平台体系的相关技术，具备大数据分析处理（Hadoop/HDFS/Spark/HBase/Storm）等技术内部机制的开发经验了解ElasticSearch技术优先；
6.对软件技术的发展具备很好的敏锐度，能够不断地自我学习保证技术的领先性；
7.能够带领团队的技术水平不断提升发展。
8. 熟悉实时数据处理，in-app实时用户行为数据采集，清洗，存储(ETL)过程','50','99','5','99','16','99','FT','ACTIVE','1609273458',null,'2020-12-30 04:24:18','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('5','','2','海博科技',null,null,null,null,null,'软件项目经理（安防）','15000','16000','12','39000','90',null,'1. 负责项目需求制定、投标、代码开发、实施过程控制、交付管理等工作；
2. 负责监管、跟踪项目进度情况等，确保项目按时完成，识别项目风险，监控项目各个阶段等日常项目管理工作；
3. 协调公司售前、销售部门参与项目前期工作，配合售前人员编写可研报告、初设文档、技术方案等；
4. 负责软件项目代码质量审核及管理、版本持续集成和持续交付，并支持投产部署及运维，完成交付系统质量评估方案和组织实施评估；
5. 组织、负责实施项目的需求调研、需求文档编写及审阅、实施项目开发进度制定及监控、测试团队讲解业务知识及系统设计工作；
6. 配合架构师，负责项目的整体设计和技术实现的的方案制定、实现方式评审；
7. 牵头组织并参与项目验收工作。','1. 计算机、软件工程等相关本科以上学历，3年以上软件项目管理经验；，
2. 拥有丰富的文档撰写能力，熟练编写解决方案、PPT汇报材料等相关文档 ；
3. 精通java，熟悉web开发，对分布式开发、移动端设计、微服务技术有项目开发经验；
4. 精通 Oracle、Sqlserver、Mysql 中的一种数据库，拥有较好的数据库设计能力，有较强的SQL编程能力；
5. 精通 Tomcat、Nginx 至少一种应用服务器的应用部署和配置；
6. 熟悉大数据开发、熟悉容器化技术docker、k8s等技术优先；
8. 善于沟通，具有良好的团队合作精神和协作能力，有团队管理能力和项目质量及进度的把控能力；
9. 具有规范编程的习惯和文档编写能力，积极配合公司各项规范化建设工作；
5. 具备高度的责任心、执行力、创造力和团队合作精神。','50','99','5','99','16','99','FT','ACTIVE','1609273800',null,'2020-12-30 04:30:00','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('6','','2','海博科技',null,null,null,null,null,'高级产品经理','18000','30000','12','50000','90',null,'1、负责公安类产品的规划、需求整理、原型设计、竞品分析以及产品迭代，包括数据分析、数据挖掘、数据治理等方向的产品；
2、洞悉客户需求、梳理业务流程、深度挖掘公安行业通用需求，分析问题并提出创新解决方案，包括需求引导分析、系统架构设计，参与技术交流，并对项目方案的实施质量进行管控；
3、基于海量数据进行内容挖掘，根据业务特征，对挖掘获取的内容进行质量评估和应用场景的设计，探索领域数据模型，进行产品设计和策略制定；
4、负责在项目推进过程中的协调沟通工作，与团队配合，协调各方资源进行产品全生命周期管理，解决项目执行过程中的各类问题，不断改进和提升产品的质量；
5、负责产品市场的推广，完成部门各项产品类的经营指标。','1、本科以上学历，至少五年以上的智能安防行业售前/产品经理/解决方案工作经验，对行业技术现状、发展趋势、痛点和解决方案有深入了解，有同行业背景为佳；
2、主导或参与过相关产品的设计和规划，具备数据分析、数据挖掘、数据可视化等相关产品经验优先；
3、对数据和业务敏感，善于把握用户及数据特征，具有良好的逻辑思维、数据洞察与信息挖掘能力；
4、对新技术如大数据、云计算、分布式计算、人工智能等感兴趣，具备独立的信息化整体解决方案设计能力；
5、善于沟通，有过硬的演讲能力和思维能力，能胜任行业用户引导、汇报，达到预期成果；
6、具备较强的统筹能力、商务谈判能力以及较强的跨界学习能力。','50','99','3','99','16','99','FT','ACTIVE','1609273996',null,'2020-12-30 04:34:51','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('7','','2','海博科技',null,null,null,null,null,'中高级产品经理','15000','25000','12','40000','90',null,'1、参与制定公司产品规划、负责产品设计和项目执行及产品生命周期管理；
2、负责公共安全视频智能应用软硬件产品的规划、需求整理、原型设计、竞品分析以及产品迭代；
3、基于海量数据进行内容挖掘，根据业务特征，对挖掘获取的内容进行质量评估和应用场景的设计，探索各类数据模型，进行产品设计和策略制定；
4、负责在项目推进过程中的协调沟通工作，与团队配合，协调各方资源进行产品全生命周期管理，解决项目执行过程中的各类问题，不断改进和提升产品的质量；
5、指导销售完成产品市场的推广，包含产品培训、售前支持、售后服务。','1、本科以上学历，至少五年以上的相关领域产品经理工作经验，对行业技术现状、发展趋势、痛点和解决方案有深入了解，有同行业背景为佳；
2、对数据和业务敏感，善于把握用户及数据特征，具有良好的逻辑思维、数据洞察与信息挖掘能力；
3、计算机视觉、人脸、行为分析相关技术背景、AI相关从业经验优先；
4、善于沟通，有良好的演讲能力，能胜任行业用户引导、汇报，达到预期成果；
5、思维活跃，逻辑清晰，结果导向。','50','99','3','99','16','99','FT','ACTIVE','1609274145',null,'2020-12-30 04:35:45','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('8','','2','海博科技',null,null,null,null,null,'企业文化主管','7000','10000','12','20000','92',null,'1，根据公司业务发展阶段，制定企业文化建设方案，包括文化的传播和落地
2，负责组织文化在公司范围内的宣导，推广和落地执行，提升员工对文化的认知认同，并在日常的行为中践行
3，负责主导策划公司年会，周年庆，节假日等文化活动
4，完善公司内部沟通渠道，保障组织信息和员工心声的及时，畅通的传达
5，年度组织温度，新人温度等的测温，并配合HRBP做好员工的后续管理和部门活动的开展。','1，全日制统招本科及以上学历，人力资源，心理学专业优先
2，五年以上互联网公司企业文化运营经验
3，对组织文化建设有热情，认可公司的价值观和文化
4，文化功底扎实，具备运营大型项目的经验
5，性格开朗，责任心强，沟通表达能力强，具有很好的团队协作能力。','50','99','5','99','16','99','FT','ACTIVE','1609274197',null,'2020-12-30 04:36:37','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('9','','2','海博科技',null,null,null,null,null,'网络工程师','8000','13000','12','20000','90',null,'1、 负责信息系统网络、服务器配置等基础架构的设计与评估工作
2、 系统渗透测试、漏洞扫描、安全加固、安全整改实施
3、 网络、操作系统层级的安全的评估与整改
4、 日常系统及数据库运维
5、 响应故障报警，及时处理运维事故，分析处理过程并及时上报
6、 推动提升系统服务的可靠性、可扩展性以及性能优化，保障系统SLA','1、专科，计算机或IT相关专业，3年以上工作经验
2、有扎实的网络基础知识，熟悉TCP/IP、各种路由协议等技术
3、熟悉DevOps流程，熟悉ELK，熟练掌握docker swarm, kubernetes等;熟练使用脚本语言python，能快速编写服务器维护脚本;
4、熟悉Jenkins、ansible等CI/CDI具中的一种或多种
5、熟悉prometheus, grafana， zabbix等监控工具，有二次开发经验优先
6、熟练掌握日常服务器的数据备份、迁移、扩容等技术工作
7、有华为云，阿里云 使用经验
8、熟悉nginx /tomcat /keepalived/Kibana/ jenkins/git/lvs/es/redis/kafka， mysql，oracle配置和性能优化','50','99','0','99','16','99','FT','ACTIVE','1609274339',null,'2020-12-30 04:38:59','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('10','','2','海博科技',null,null,null,null,null,'高级算法专家','25000','30000','12','65000','90',null,'1. 从事计算机视觉/图形图像等适应公司业务需求的算法模型研发，并将成果应用于各产品线；
2. 负责深度学习领域的技术研发工作，结合实际应用场景，提供全面的技术解决方案；
3.负责公司产品相关的体验策略设计与优化，进行算法改进、架构优化和策略研发；
4. 参与产品设计，并提供算法角度的专业意见，协助拓展业务边界；
5. 追踪前沿技术和论文，指导如何在现有工作中使用最新算法研究成果；
6.带领小而精的算法团队，独立负责至少一个算法方向的落地；
7.指导算法工程师落地。','1、 5年以上相关经历，拥有计算机、数学或相关学科硕士或博士学位；
2.具备较强的策略设计和数据分析能力，能够针对业务提炼指标评估策略价值及发现关键问题；
3.产品问题驱动导向，能够跟进领域内最新技术研究成果，并结合应用场景快速实验和调优；
4.良好的业务敏感性和技术前瞻性、规划、执行力，优秀的分析解决问题能力；
5.在深度学习领域有较深的工业界经验或学术积累，有相关领域业界知名成功项目经验优先，在国际顶级会议和期刊以第一作者发表过高水平论文优先；
6.具备安防领域模型算法工作经验，对安防业务有深入了解优先。','50','99','5','99','16','99','FT','ACTIVE','1609274398',null,'2020-12-30 04:39:58','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('11','','2','海博科技',null,null,null,null,null,'市场营销总监','12000','20000','12','30000','92',null,'1. 制定和实施年度市场推广计划，确定产品的目标市场，产品的市场定位、产品概念，制定具体的的产品推广方案、产品策略等；；
2. 组织市场策划和新闻传播活动，提升产品知名度和公司品牌形象；做好网站、微信、微博、客户端等新媒体宣传规划、管理运营；
3. 组织公司产品和竞争对手产品在市场上销售情况的调查，把握市场趋势，综合客户的反馈意见，撰写市场调查报告、竞品分析、行业分析报告，为决策提供准确的相关信息，开拓和发展销售市场；
4. 负责对外宣传、产品介绍、汇报材料、演讲稿等题材的撰写，有自媒体运营经验优先考虑；
5. 统一规划组织企业内部公关活动，拓展媒体合作关系，通过公关活动和形象传播提高企业产品的市场占有率;
6. 制定公司整体公关策略及危机公关的应对处理；
7. 负责市场团队的组建、管理、考核，做好人才梯队建设等。','1．统招本科以上、公关、新闻、广告、等专业，硕士学历、公关专业优先；
2．大数据、人工智能行业从业经验优先；
3．熟练应用办公软件，较强的PPT展现能力，擅长文字撰写、市场调研、组织能力、资源整合；
4．有市场调研、媒体资源等公关行业相关资质者优先；
5. 良好的沟通表达能力及演讲能力。','50','99','5','99','16','99','FT','ACTIVE','1609274482',null,'2020-12-30 04:41:22','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('12','','2','海博科技',null,null,null,null,null,'UXC总监','18000','28000','12','46800','90',null,'1.负责深入了解产品业务需求与市场趋势，根据产品需求和体验要素完成流程和界面的交互设计，编写交互设计说明书，主导项目落地，保证产品的可用和易用性，同时保证良好的产品体验；
2.负责公司核心产品的交互和视觉设计工作，确定公司视觉发展方向，领导和管理设计团队，提供良好的用户体验；
3.带领设计团队根据对业务的思考、用户体验和心理的理解分析,对现有产品体验进行测评和评估,提出专业意见,不断对产品结构、流程、功能、界面用户体验、交互功能等进行研究并优化;
4.制定设计标准，对交互与视觉定义出设计规范，提炼控件，制定色彩与图标规范；
5.负责与产品、研发团队全过程配合进行产品研发；
6.负责对现有产品进行持续优化，提升产品的品质和用户体验；
7.跟进UI设计的整个流程，对每个流程进行可用性设计和测试，参与产品的规划，提供专业意见，与产品经理共同改进产品服务；
8.负责对市场需求和业务情况进行调研分析，不断更新产品，提升产品与市场的匹配度；
9.负责UED团队人才梯队建设，提高团队整体设计水平，并给与成长性学习环境制造条件；
10.完成领导交办的其他工作。','学历要求：全日制统招本科以上学历，设计类、视觉传达、心理学专业优先考虑；
经验要求：5年以上UI/UX设计经验,2年以上团队管理经理；
专业技能要求：
1.具备5年及以上软件行业交互设计工作经验，熟练掌握交互设计的相关工具，比如axure、ps、Sketch等；具备较强视频制作能力与手绘基础者可优先考虑；
2.深入了解交互设计、界面设计、前端等发展趋势,有自己的见解与实践。对产品流程、场景搭建、用户体验及用户行为习惯有深入的理解和实践;
3.熟悉多平台设计流程及规范，具有独立主导项目的交互设计工作经验，对交互设计和用户体验有深刻理解：包括功能分析、用户角色分析、原型设计、界面开发、易用性测试等；
4.对视觉设计、色彩搭配有敏锐的观察及分析能力,优秀的审美及设计创意能力，对国内外互联网产品有强烈好奇心和研究心;
5.出色的沟通能力与组织能力,责任心强,有创业心态,具有良好的团队合作精神，
6.有团队领导能力以及强烈的自驱力与抗压能力。','50','99','5','99','16','99','FT','ACTIVE','1609274554',null,'2020-12-30 04:42:34','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('13','','2','海博科技',null,null,null,null,null,'前端开发工程师','10000','15000','12','26000','90',null,'1. 能够与产品、项目经理、运营沟通，快速正确处理需求，并针对需求合理设计完整功能模块的数据结构、代码实现方案；
2. 负责公司业务支撑平台的概要设计，主导详细设计和编码的任务，负责内部工具、组件、基础服务的开发维护；
3. 组织codereview、单元测试等并能够发现存在的问题针对性提出合理建议；
4. 重点应用项目的预研和方案实施，复杂问题解决以及技术难点攻关；
5. 负责有关技术方案、文档的编写；
完成领导交办的其他工作。','1、本科及以上学历，具有两年以上开发经验；
2、Java SE基础扎实；
3、熟练使用SVN、GIT等版本管理工具，熟练使用MAVEN包管理工具；
4、深入理解SpringMVC、Spring、Mybatis等主流框架，了解SpringBoot、SpringCloud等微服务开发架构；
5、熟练使用SQL，熟悉mysql、oracle等主流数据库的使用；
6、熟练使用linux命令；
7、熟悉redis、mq、zookeeper、elasticsearch等各种中间件的原理以及使用场景。','50','99','3','99','16','99','FT','ACTIVE','1609274616',null,'2020-12-30 04:43:36','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('14','','2','海博科技',null,null,null,null,null,'高级Java开发工程师','12000','18000','12','31200','90',null,'1. 深入理解产品（项目）需求，与产品经理、开发经理共同参与需求的沟通、分析、讨论，保证需求与功能设计、开发的一致性；
2. 参与系统架构设计和技术选型，根据业务发展需要设计合理的架构方案，完成相关功能的设计；
3. 遵守公司开发规范，完成相关功能的开发，保证质量、性能和安全，维护相关技术文档；
4. 协助测试、运维同事完成软件的部署及调试工作；
5. 线上系统问题排查及监控，及时解决客户反馈的问题；
6. 参与代码评审及代码重构工作；
7. 解决项目或产品中遇到的技术难题，指导初中级开发人员；
8. 主动学习新技术，积极分享；
9. 完成领导交办的其他工作。','1. 本科及以上，4年以上开发经验；
2. 熟练掌握多线程高并发、事务等Java高级技术。熟悉JVM，包括内存模型、类加载机制以及性能优化；
3. 熟练使用设计模式、面向切面编程、反射机制；
4. 熟悉常用的数据结构及算法；
5. 掌握微服务思想，熟练使用springboot、springCloud等微服务技术；
6. 了解分布式、缓存、消息队列等机制，熟悉相关技术（Zookeeper、Ehcache、Redis、kafka）；
7. 了解大数据存储与分析技术，如ElasticSearch、Hbase、Solr、Hadoop、Spark、Flink、Storm
8. 具有分析和解决问题的能力，思路清晰，善于思考
9. 具有良好的沟通和表达能力，良好的团队协作能力
10. 保持技术敏感性','50','99','3','99','16','99','FT','ACTIVE','1609274676',null,'2020-12-30 04:44:36','3','3');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('15','','3','熙正科技',null,null,null,null,null,'人力总监','8000','13000','12','20000','92',null,'负责招聘大数据、互联网中高级人才，负责薪酬管理，团队建设。','具有良好的猎头和人脉渠道','50','99','3','99','16','99','FT','ACTIVE','1609275463',null,'2020-12-30 05:24:19','4','4');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('16','','3','熙正科技',null,null,null,null,null,'大数据售前咨询顾问','20000','30000','12','52000','92',null,'负责智慧城市顶层设计，负责具体方案系统编制，负责支撑销售对接政府部门，负责项目招投标。','有成熟落地的城市大脑、城市运营管理中心落地案例。
有多个政府项目落地案例。','60','99','5','99','16','99','FT','ACTIVE','1609275521',null,'2020-12-30 04:58:41','4','4');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('17','','3','熙正科技',null,null,null,null,null,'数据中台研发工程师','11000','20000','12','30000','90',null,'负责公司数据中台的相关架构与开发工作，指标设计，数据建模，数据架构, 架构优化，数据的准确性， API接口设计和架构，性能优化， 大数据处理，数据深度分析和深度学习 。','1. 精通C#/java/python任一门，并不排斥学习其它的语言；
2.精通mysql, oracle等关系数据库任一门 
3.精通nosql 的数据库；
4.熟练掌握Hadoop，Spark，Kafka等大数据组；
5.至少熟悉一种常见的分布式任务调度框架，例如cronsun、Elastic-job、saturn、lts、TBSchedule、xxl-job等；
6.有数据建模经验；
7.有大数据ETL开发经验者优先；
8.有数据挖掘工作经验者优先；
9.有技术博客等写作经验者优先；
10.有数据治理相关工作经验者优先。','40','99','3','99','16','99','FT','ACTIVE','1609275587',null,'2020-12-30 04:59:47','4','4');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('18','','4','Imaginato',null,null,null,null,null,'外企CTO','18000','22000','12','50000','92',null,'1、负责组织、制定和实施重大技术决策和技术方案， 制定有关技术的愿景和战略，把握总体技术方向，监督技术研究与发展（R&D）的活动； 
2、全面管理公司研发工作，负责技术团队的组织规划、日常运营和管理，全面负责技术层面的整体运营包括软件研发、项目实施及技术管理等；
 3、深入理解电商及社交类产品业务和相关国际前沿技术研究课题，参与需求分析并提供领先的技术架构设计方案；
4、推动并实施技术管理、技术开发等相关制度体系的建设，制订符合业务发展需要的规范化制度、流程并监督落地实施；
5、培养公司技术团队，监督及指导技术部门的工作，打造一支高绩效的国际化技术团队。','1、优秀的英语能力，具备与国外客户无障碍沟通交流和宣讲的能力，有海外工作经验；
2、8年以上互联网IT领域经验，至少5年以上管理经验，有50+人员以上管理经验；
3、熟悉电商或社交类领域业务，深刻理解行业发展方向，具备敏锐准确的洞察力和缜密的逻辑思维，能够把握行业业务发展动向和关键技术发展趋势，并有独立见解
4、具有良好的沟通能力和领导能力，能统筹全局，合理调配团队资源；
5、在IT技术队伍的建设和管理，人员配置与协调，项目进展的监控等方面有丰富的团队管理经验； 
 6、具有战略思维和良好的团队激励的能力，有较强的执行力、良好的商业谈判能力、预算管理技能、领导力和学习能力； 
7、以目标为导向，能够主动的推进工作进展，有极强的工作责任心及目标感。','50','99','5','99','16','99','FT','ACTIVE','1609275883',null,'2020-12-30 05:04:43','5','5');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('19','','4','Imaginato',null,null,null,null,null,'QA测试工程师','5000','6000','12','13000','90',null,'设计开发测试框架或测试管理方法，帮助测试团队利用技术手段把产品测试做得更快更好，利用测试技术工具提高测试的效率和覆盖率；
搜集挖掘测试团队的需求，提供技术解决方案；
在测试团队中善于创新与分享，为测试团队提供技术支持。','有过大型系统，分布式系统测试经验者优先
有自动化，性能经验者优先
有过长期国外（英语环境）项目经验者优先
你的个人资料：
热爱计算机事业，掌握1～2门开发语言，具备良好的web应用或App应用测试技巧；
至少2年软件测试岗位工作经验，；
工作中善于创新，喜欢分享，能够有效解决遇到的问题；
具备python，Js或其他语言开发经验者
熟悉selenium，appium 自动化框架','40','99','1','99','16','99','FT','ACTIVE','1609275953',null,'2020-12-30 05:05:53','5','5');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('20','','4','Imaginato',null,null,null,null,null,'Graphic designer/网页设计制作','5000','8000','12','13000','92',null,'1、负责互联网相关的Web产品的视觉设计工作；
2、为网站产品和服务推广设计视觉交互界面设计，跟踪产品效果，提出设计改善方案；
3、负责公司整体形象包装及VI设计；
4、对现有产品的视觉设计提出改进方案，持续优化产品的视觉呈现方式；
5、建立、维护和更新视觉设计标准和规范，并执行实施。','1. 从事设计行业工作3年以上，具有互联网行业视觉设计工作经验，并有成功案例；
2. 熟悉多类基于互联网的产品，并能有所见解；
3.熟练掌视觉设计的相应设计软件，精通Photoshop并熟练使用Flash、Illustrator等软件，掌握Dreamweaver等制作工具；
4. 了解 Web 前端技术基本应用，熟悉css和html；
5. 有丰富的视觉设计理论知识基础和成熟高效的实现应用能力、经验、技法和图形原创能力；
6. 良好的跨团队协作能力；具有较强的学习能力和洞察力；
7. 擅长摄影、插画、3D等优先考虑。','40','99','1','99','16','99','FT','ACTIVE','1609276020',null,'2020-12-30 05:20:56','5','5');
INSERT INTO `post` (`post_id`, `uuid`, `biz_id`, `biz_name`, `branch_id`, `branch_name`, `section_id`, `section_name`, `code_biz`, `name`, `salary_min`, `salary_max`, `salary_count`, `bonus_final`, `probation`, `description`, `duty`, `competency`, `edu_min`, `edu_max`, `exp_min`, `exp_max`, `age_min`, `age_max`, `type`, `status`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('21','','4','Imaginato',null,null,null,null,null,'SYS运维工程师','7000','10000','12','18200','92',null,'1、 负责信息系统网络、服务器配置等基础架构的设计与评估工作
2、 系统渗透测试、漏洞扫描、安全加固、安全整改实施
3、 网络、操作系统层级的安全的评估与整改
4、 日常系统及数据库运维
5、 响应故障报警，及时处理运维事故，分析处理过程并及时上报
6、 推动提升系统服务的可靠性、可扩展性以及性能优化，保障系统SLA','1、本科，计算机或IT相关专业，3年以上工作经验
2、有扎实的网络基础知识，熟悉TCP/IP、各种路由协议等技术
3、熟悉DevOps流程，熟悉ELK，熟练掌握docker swarm, kubernetes等;熟练使用脚本语言python，能快速编写服务器维护脚本;
4、熟悉Jenkins、ansible等CI/CDI具中的一种或多种
5、熟悉prometheus, grafana， zabbix等监控工具， 有二次开发经验者优先
6、熟练掌握日常服务器的数据备份、迁移、扩容等技术工作
7、aws、Google Cloud，CloudFlare云功能j经验
8、熟悉nginx /tomcat /keepalived/Kibana/ jenkins/git/haproxy/lvs/es/redis/kafka， mysql配置和性能优化','40','99','1','99','16','99','FT','ACTIVE','1609276664',null,'2020-12-30 05:17:44','5','5');




INSERT INTO `section` (`section_id`, `biz_id`, `manager_id`, `name`, `name_public`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','1',null,'产品部',null,'2020-12-27 23:29:55',null,'2020-12-27 23:29:55','1','1');

INSERT INTO `sms` (`sms_id`, `mobile`, `mobile_list`, `type`, `captcha`, `content`, `time_to_send`, `user_ip`, `time_expire`, `time_create`, `time_delete`) VALUES ('1','13698673572',null,'1','360987','360987是您的验证码，3分钟内有效；如非本人操作，请忽略本信息且勿将此码转告他人。',null,'112.9.109.36','1609012973','2020-12-27 03:59:53',null);
INSERT INTO `sms` (`sms_id`, `mobile`, `mobile_list`, `type`, `captcha`, `content`, `time_to_send`, `user_ip`, `time_expire`, `time_create`, `time_delete`) VALUES ('2','13698673572',null,'1','148205','148205是您的验证码，3分钟内有效；如非本人操作，请忽略本信息且勿将此码转告他人。',null,'112.9.109.36','1609013295','2020-12-27 04:05:15',null);
INSERT INTO `sms` (`sms_id`, `mobile`, `mobile_list`, `type`, `captcha`, `content`, `time_to_send`, `user_ip`, `time_expire`, `time_create`, `time_delete`) VALUES ('3','13698673572',null,'1','123476','123476是您的验证码，3分钟内有效；如非本人操作，请忽略本信息且勿将此码转告他人。',null,'112.9.109.36','1609013516','2020-12-27 04:08:57',null);
INSERT INTO `sms` (`sms_id`, `mobile`, `mobile_list`, `type`, `captcha`, `content`, `time_to_send`, `user_ip`, `time_expire`, `time_create`, `time_delete`) VALUES ('4','13698673572',null,'1','927843','927843是您的验证码，3分钟内有效；如非本人操作，请忽略本信息且勿将此码转告他人。',null,'112.9.109.36','1609013824','2020-12-27 04:14:04',null);
INSERT INTO `sms` (`sms_id`, `mobile`, `mobile_list`, `type`, `captcha`, `content`, `time_to_send`, `user_ip`, `time_expire`, `time_create`, `time_delete`) VALUES ('5','17664073966',null,'1','027439','027439是您的验证码，3分钟内有效；如非本人操作，请忽略本信息且勿将此码转告他人。',null,'223.104.189.13','1609108501','2020-12-28 06:32:01',null);
INSERT INTO `sms` (`sms_id`, `mobile`, `mobile_list`, `type`, `captcha`, `content`, `time_to_send`, `user_ip`, `time_expire`, `time_create`, `time_delete`) VALUES ('6','13668865673',null,'1','032675','032675是您的验证码，3分钟内有效；如非本人操作，请忽略本信息且勿将此码转告他人。',null,'223.80.70.88','1609259658','2020-12-30 00:31:19',null);


INSERT INTO `stuff_biz` (`stuff_id`, `user_id`, `biz_id`, `section_id`, `branch_id`, `code_biz`, `fullname`, `mobile`, `title`, `role`, `level`, `status`, `time_join`, `time_left`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','1','1',null,null,null,'刘亚杰','13698673572','董事长','管理员','30','100',null,null,'2020-12-27 19:19:40',null,'2020-12-27 23:28:16','1','1');
INSERT INTO `stuff_biz` (`stuff_id`, `user_id`, `biz_id`, `section_id`, `branch_id`, `code_biz`, `fullname`, `mobile`, `title`, `role`, `level`, `status`, `time_join`, `time_left`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('2','3','2',null,null,null,'万力','19900000001','董事长','管理员','30','100',null,null,'2020-12-29 07:04:50',null,'2020-12-29 07:04:50','3','3');
INSERT INTO `stuff_biz` (`stuff_id`, `user_id`, `biz_id`, `section_id`, `branch_id`, `code_biz`, `fullname`, `mobile`, `title`, `role`, `level`, `status`, `time_join`, `time_left`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('3','4','3',null,null,null,'史女士','19900000002','人力资源总监','管理员','30','100',null,null,'2020-12-30 04:53:39',null,'2020-12-30 04:53:39','4','4');
INSERT INTO `stuff_biz` (`stuff_id`, `user_id`, `biz_id`, `section_id`, `branch_id`, `code_biz`, `fullname`, `mobile`, `title`, `role`, `level`, `status`, `time_join`, `time_left`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('4','5','4',null,null,null,'王女士','19900000003','人力资源经理','管理员','30','100',null,null,'2020-12-30 05:01:31',null,'2020-12-30 05:01:31','5','5');

INSERT INTO `user` (`user_id`, `uuid`, `identity_id`, `password`, `mobile`, `email`, `wechat_id`, `wechat_openid`, `wechat_unionid`, `getui_id`, `avatar`, `nickname`, `lastname`, `firstname`, `gender`, `dob`, `lob_province`, `lob_city`, `lop_province`, `lop_city`, `year_start`, `address_id`, `card_id`, `last_login_timestamp`, `last_login_ip`, `role`, `status`, `time_create`, `time_delete`, `time_edit`, `operator_id`, `promoter_id`, `agent_id`) VALUES ('1','36180b9f-47b6-11eb-be99-7cd30ab8a96e',null,'7c4a8d09ca3762af61e59520943dc26494f8941b','13698673572',null,null,null,null,null,'user/avatar/202012/1228/234005.jpg','Kamas','Lau','Kamas','M','1989-07-28','山东省','青岛市','山东省','青岛市','2009',null,null,'1609261340','223.80.70.88','client','正常','1609013348',null,'2020-12-30 01:02:20','1',null,null);
INSERT INTO `user` (`user_id`, `uuid`, `identity_id`, `password`, `mobile`, `email`, `wechat_id`, `wechat_openid`, `wechat_unionid`, `getui_id`, `avatar`, `nickname`, `lastname`, `firstname`, `gender`, `dob`, `lob_province`, `lob_city`, `lop_province`, `lop_city`, `year_start`, `address_id`, `card_id`, `last_login_timestamp`, `last_login_ip`, `role`, `status`, `time_create`, `time_delete`, `time_edit`, `operator_id`, `promoter_id`, `agent_id`) VALUES ('2','5e6471f6-4893-11eb-be99-7cd30ab8a96e',null,'7c4a8d09ca3762af61e59520943dc26494f8941b','17664073966',null,null,null,null,null,'user/avatar/202012/1228/063333.jpg','睿孝','刘','睿孝',null,null,'山东省','青岛市','山东省','青岛市','2009',null,null,'1609108340','223.104.189.13','client','正常','1609108334',null,'2020-12-28 23:21:50','2',null,null);
INSERT INTO `user` (`user_id`, `uuid`, `identity_id`, `password`, `mobile`, `email`, `wechat_id`, `wechat_openid`, `wechat_unionid`, `getui_id`, `avatar`, `nickname`, `lastname`, `firstname`, `gender`, `dob`, `lob_province`, `lob_city`, `lop_province`, `lop_city`, `year_start`, `address_id`, `card_id`, `last_login_timestamp`, `last_login_ip`, `role`, `status`, `time_create`, `time_delete`, `time_edit`, `operator_id`, `promoter_id`, `agent_id`) VALUES ('3','e5778025-495f-11eb-be99-7cd30ab8a96e',null,'7c4a8d09ca3762af61e59520943dc26494f8941b','19900000001',null,null,null,null,null,null,'user275638','万力',null,null,null,null,null,null,null,null,null,null,'1609269565','223.80.70.88','client','正常','1609018348',null,'2020-12-30 05:23:36','3',null,null);
INSERT INTO `user` (`user_id`, `uuid`, `identity_id`, `password`, `mobile`, `email`, `wechat_id`, `wechat_openid`, `wechat_unionid`, `getui_id`, `avatar`, `nickname`, `lastname`, `firstname`, `gender`, `dob`, `lob_province`, `lob_city`, `lop_province`, `lop_city`, `year_start`, `address_id`, `card_id`, `last_login_timestamp`, `last_login_ip`, `role`, `status`, `time_create`, `time_delete`, `time_edit`, `operator_id`, `promoter_id`, `agent_id`) VALUES ('4','41c5219c-4960-11eb-be99-7cd30ab8a96e',null,'7c4a8d09ca3762af61e59520943dc26494f8941b','19900000002',null,null,null,null,null,null,'user275639','史女士',null,null,null,null,null,null,null,null,null,null,'1609277029','223.80.70.88','client','正常','1609018349',null,'2020-12-30 05:23:49','4',null,null);
INSERT INTO `user` (`user_id`, `uuid`, `identity_id`, `password`, `mobile`, `email`, `wechat_id`, `wechat_openid`, `wechat_unionid`, `getui_id`, `avatar`, `nickname`, `lastname`, `firstname`, `gender`, `dob`, `lob_province`, `lob_city`, `lop_province`, `lop_city`, `year_start`, `address_id`, `card_id`, `last_login_timestamp`, `last_login_ip`, `role`, `status`, `time_create`, `time_delete`, `time_edit`, `operator_id`, `promoter_id`, `agent_id`) VALUES ('5','6883da6f-4960-11eb-be99-7cd30ab8a96e',null,'7c4a8d09ca3762af61e59520943dc26494f8941b','19900000003',null,null,null,null,null,null,'user275640','王女士',null,null,null,null,null,null,null,null,null,null,'1609275638','223.80.70.88','client','正常','1609018350',null,'2020-12-30 05:23:34','5',null,null);
INSERT INTO `user` (`user_id`, `uuid`, `identity_id`, `password`, `mobile`, `email`, `wechat_id`, `wechat_openid`, `wechat_unionid`, `getui_id`, `avatar`, `nickname`, `lastname`, `firstname`, `gender`, `dob`, `lob_province`, `lob_city`, `lop_province`, `lop_city`, `year_start`, `address_id`, `card_id`, `last_login_timestamp`, `last_login_ip`, `role`, `status`, `time_create`, `time_delete`, `time_edit`, `operator_id`, `promoter_id`, `agent_id`) VALUES ('6','694c1e10-4960-11eb-be99-7cd30ab8a96e',null,'7c4a8d09ca3762af61e59520943dc26494f8941b','19900000004',null,null,null,null,null,null,'user275641',null,null,null,null,null,null,null,null,null,null,null,null,null,'client','正常','1609018351',null,'2020-12-30 05:23:33',null,null,null);
INSERT INTO `user` (`user_id`, `uuid`, `identity_id`, `password`, `mobile`, `email`, `wechat_id`, `wechat_openid`, `wechat_unionid`, `getui_id`, `avatar`, `nickname`, `lastname`, `firstname`, `gender`, `dob`, `lob_province`, `lob_city`, `lop_province`, `lop_city`, `year_start`, `address_id`, `card_id`, `last_login_timestamp`, `last_login_ip`, `role`, `status`, `time_create`, `time_delete`, `time_edit`, `operator_id`, `promoter_id`, `agent_id`) VALUES ('7','51d9c085-49f3-11eb-be99-7cd30ab8a96e',null,'7c4a8d09ca3762af61e59520943dc26494f8941b','13668865673',null,null,null,null,null,'user/avatar/202012/1230/003846.jpg','亚杰5673','刘','亚杰','M','1989-07-28','山东省','青岛市','山东省','青岛市','2009',null,null,'1609259539','223.80.70.88','client','正常','1609259496',null,'2020-12-30 00:39:15','7',null,null);

INSERT INTO `wish` (`wish_id`, `user_id`, `type`, `province`, `city`, `salary_min`, `industry_ids`, `post_cat_id`, `time_create`, `time_delete`, `time_edit`, `creator_id`, `operator_id`) VALUES ('1','7','30','山东省','青岛市','50000',null,null,'2020-12-30 00:39:38',null,'2020-12-30 00:39:38','7','7');

