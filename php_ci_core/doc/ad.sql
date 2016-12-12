--格式说明

--@author 添加人姓名
--@date 日期
--@desc 描述
--@todo dev
--@todo test
--@todo product  每个环境部署完代码后，删除相应的todo行
------------------------------------------------------

--@author wanggang
--@date 2014-03-05
--@desc 描述
------------------------------------------------------
use ad_core;

create table order_bid_log (
    id int not null auto_increment primary key,
    order_id int not null,
    date date not null,
    price decimal(10,2) not null,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key(order_id, date)
) engine=innodb default charset utf8;

--@author wanggang
--@date 2014-03-10
--@desc 联盟收入采集
------------------------------------------------------
use ad_report;

create table union_baidu_data (
    id int not null auto_increment primary key,
    date date not null,
    name varchar(50) not null,
    ad_id int not null default 0,
    impression int not null default 0,
    click int not null default 0,
    income decimal(10,2) not null default 0,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (date, name)
) engine=innodb default charset utf8;

--@author zhaolei
--@date 2014-03-13
--@desc 淘宝收入采集
------------------------------------------------------
use ad_report;

create table union_taobao_data (
    id int not null auto_increment primary key,
    date date not null,
    name varchar(50) not null,
    ad_id int not null default 0,
    impression int not null default 0,
    click int not null default 0,
    income decimal(10,2) not null default 0,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (date, name)
) engine=innodb default charset utf8;

--@author zhaolei
--@date 2014-03-13
--@desc Google收入采集
------------------------------------------------------
use ad_report;

create table union_google_data (
    id int not null auto_increment primary key,
    date date not null,
    name varchar(50) not null,
    ad_id int not null default 0,
    impression int not null default 0,
    click int not null default 0,
    income decimal(10,2) not null default 0,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (date, name)
) engine=innodb default charset utf8;

--@author zhaolei
--@date 2014-03-13
--@desc 腾果收入采集
------------------------------------------------------
use ad_report;

create table union_tengguo_data (
    id int not null auto_increment primary key,
    date date not null,
    name varchar(50) not null,
    ad_id int not null default 0,
    impression int not null default 0,
    click int not null default 0,
    income decimal(10,2) not null default 0,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (date, name)
) engine=innodb default charset utf8;

--@author zhaolei
--@date 2014-03-18
--@desc cnzz采集
------------------------------------------------------
use ad_report;

create table cnzz_page_data (
    id int not null auto_increment primary key,
    date date not null,
    site varchar(50) not null,
    name varchar(50) not null,
    uv int not null default 0,
    pv int not null default 0,
    ip int not null default 0,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (date, name)
) engine=innodb default charset utf8;

create table cnzz_page_total_data (
    id int not null auto_increment primary key,
    date date not null,
    site varchar(50) not null,
    uv int not null default 0,
    pv int not null default 0,
    ip int not null default 0,
    averageupv decimal(10,2) not null default 0,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (date, site)
) engine=innodb default charset utf8;

--@author wanggang
--@date 日期
--@desc 收入数据添加版本
------------------------------------------------------
use ad_report;
alter table income_report add data_version int not null default 1;

--@author wanggang
--@date 日期
--@desc BOX二期
------------------------------------------------------
use ad_core;
CREATE TABLE advertiser_journal (
    id int not null auto_increment primary key,
    advertiser_id int not null,
    trans_time timestamp not null default '0000-00-00' comment '交易时间',
    trans_type varchar(50) not null comment '交易类型，如充值，扣费，应用自定义',
    amount decimal(10,2) not null comment '交易金额，包含负数',
    balance decimal(10,2) not null comment '交易前余额数',
    rel_id int not null default 0 comment '关联的对象id，如扣费关联order_bid.id',
    ext_order_no varchar(50) not null default '' comment '外部订单/流水号',
    attachment varchar(200) not null default '' comment '附件',
    remark varchar(200) not null default '' comment '备注',
    status tinyint not null default 1 comment '交易状态，0:未处理 1:进行中 2:交易确认 3:交易完成 -1:交易取消 -3:交易失败',
    op_username varchar(50) not null default '' comment '操作人员',
    create_time timestamp not null default current_timestamp
) ENGINE=INNODB DEFAULT CHARSET UTF8 comment '广告主账户流水';

CREATE TABLE tag (
    id int not null auto_increment primary key,
    name varchar(50) not null comment '程序内部使用的标志，公包含字母下划线',
    title varchar(50) not null comment '可视化的名称，一般是中文',
    parent_id int not null default 0 comment '父级节点',
    type enum('industry') not null comment '标签类别',
    unique key(type, name)
) ENGINE=INNODB DEFAULT CHARSET UTF8 comment '标签';


ALTER TABLE advertiser ADD COLUMN balance decimal(10,2) not null comment '账户余额';

drop table box_slot;

ALTER TABLE orders ADD COLUMN source ENUM('bid', 'default') NOT NULL DEFAULT 'default' COMMENT '订单来源';


-- 订单号产生过程
create table order_sequence (
    order_date date not null,
    order_sequence int not null default 0,
    key(order_date)
) engine=innodb;

delimiter //
create procedure get_order_seq ( in a date )
begin
    declare b int;

    select order_sequence into b from order_sequence where order_date = a for update;

    if b is null then
        set b = 0;
        insert order_sequence (order_date,order_sequence) values (a, 0);
    end if;

    set b = b + 1;

    update order_sequence set order_sequence = b where order_date = a;

    select b as sequence;

    commit;
end
//
delimiter ;

-- 初始化
insert into order_sequence (order_date, order_sequence)
select
date_format(current_date, '%Y-%m-01') as order_date,
count(*) + 10 as order_sequence from orders
where date_format(create_time, '%Y-%m') = date_format(current_date, '%Y-%m');


USE ad_box;
CREATE TABLE bid_record (
    id int not null auto_increment primary key,
    order_id int not null,
    advertiser_id int not null,
    date date not null,
    price int not null comment '出价',
    round int not null comment '第几轮',
    status tinyint not null default 1 comment '状态 1:竞价中 10:竞价成功 -10:竞价失败 100:已扣费',
    data_version int not null default 1 comment '数据版本',
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key(order_id,date,round),
    key(advertiser_id),
    key(order_id)
) ENGINE=INNODB DEFAULT CHARSET UTF8 comment '竞价记录';

CREATE TABLE industry_round (
    id int not null auto_increment primary key,
    date date not null,
    industry_id int not null comment 'tags.id',
    max_round int not null comment '最大轮数',
    unique key(date, industry_id),
    FOREIGN KEY (industry_id) REFERENCES ad_core.tag (id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET UTF8 comment '行业box轮数设置';

CREATE TABLE box (
    id int not null primary key comment 'ad_core.orders.id',
    industry_id int not null comment '行业ID',
    campaign_id int not null comment '推广计划ID',
    advertiser_id int not null,
    key (campaign_id),
    FOREIGN KEY (id) REFERENCES ad_core.orders (id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET UTF8 comment 'box表，主数据保存在ad_core.orders表中';

--@author zhaolei
--@date 2014-03-24
--@desc CNZZ统计字段增加
------------------------------------------------------
use ad_report;
ALTER TABLE cnzz_page_total_data ADD COLUMN `extra` TEXT NULL;

--@author zhaolei
--@date 2014-03-24
--@desc 互众BOX
------------------------------------------------------
use ad_core;
ALTER TABLE advertiser CHANGE `bank_name` `bank_name` VARCHAR(100) DEFAULT ''  NOT NULL  COMMENT '收款开户银行';
INSERT INTO agent (`id`, `name`, `login_name`, `password_hash`) VALUES ('1000', 'BOX代理', 'adeaz_box', '11111111111111111111');

--@author wanggang
--@date 2014-03-24
--@desc 互众BOX
------------------------------------------------------
use ad_box;
ALTER TABLE box ADD COLUMN status tinyint NOT NULL DEFAULT 1 COMMENT 'box状态';
ALTER TABLE box ADD COLUMN start_date date NULL COMMENT '开始日期',
ADD COLUMN end_date date NULL COMMENT '结束日期';

UPDATE box LEFT JOIN
(
select order_id, min(date) as start_date, max(date) as end_date,
sum(case status when 1 then 1 when 10 then 1 else 0 end) as active_count
from bid_record group by order_id
)
as t ON box.id=t.order_id
SET box.start_date=t.start_date,box.end_date=t.end_date,box.status=if(t.active_count > 0, 1, -1);

--@author 王刚
--@date 2014-04-04
--@desc box
------------------------------------------------------
use ad_core;
create table message_queue (
    id int not null auto_increment primary key,
    create_by varchar(20) not null default '' comment '创建人',
    advertiser_id int not null default 0,
    message_id varchar(50) not null,
    subject varchar(250) not null default '',
    message text not null,
    type enum('mail', 'sms'),
    address varchar(50) not null comment '收件地址',
    status tinyint not null default 0 comment '0:未处理 1:已发送 2:发送失败',
    process_result varchar(50) not null default '',
    process_time timestamp null,
    create_time timestamp not null default current_timestamp,
    key(status),
    unique key (message_id)
) engine=innodb default charset utf8;



--@author wanggang
--@date 2014-04-10
--@desc 媒介数据权限
------------------------------------------------------
USE ad_core;
CREATE TABLE role_resource (
    id int not null auto_increment primary key,
    rolename varchar(50) not null comment '角色名称',
    username varchar(50) not null comment '角色账号',
    type enum('media', 'slot') not null comment '资源类型',
    enable tinyint not null default 1 comment '是否有效',
    object_id int not null comment '资源ID'
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT '角色资源映射表';



--@author wanggang
--@date 2014-04-10
--@desc adx
------------------------------------------------------
USE ad_core;

ALTER TABLE tag AUTO_INCREMENT=1000000;
ALTER TABLE tag DROP INDEX type;
ALTER TABLE tag ADD UNIQUE KEY(type, parent_id, title);
ALTER TABLE tag DROP COLUMN name;

ALTER TABLE ad
MODIFY COLUMN type enum('union','brand','pop', 'adx') NOT NULL DEFAULT 'union' COMMENT '广告类型',
MODIFY COLUMN business_type enum('union','u-baidu','u-google','cki','u-tango','u-adx','agent','e-commerce','cpa','media','52game','game','none') NOT NULL DEFAULT 'none' COMMENT '业务类型';

CREATE TABLE tag_media (
    id varchar(10) not null primary key,
    title varchar(50) not null,
    parent_id varchar(10) not null default ''
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT '媒体分类';

INSERT tag_media (id, title, parent_id) VALUES ('01', '音乐影视', ''),('0101', '娱乐综合', '01'),('0102', '在线音乐', '01'),('0103', '宽带电影', '01'),('0104', '视频短片', '01'),('0105', '网络电视', '01'),('0106', '其他', '01'),('02', '休闲娱乐', ''),('0201', '笑话', '02'),('0202', '图片', '02'),('0203', '动漫', '02'),('0204', 'flash', '02'),('0205', '星座/算命与占卜', '02'),('0206', '非主流', '02'),('0207', '其他', '02'),('03', '游戏', ''),('0301', '综合游戏', '03'),('0302', '网络游戏', '03'),('0303', '单机游戏', '03'),('0304', '掌机/手机游戏', '03'),('0305', '休闲小游戏', '03'),('0306', '其他', '03'),('04', '网络服务应用', ''),('0401', '源码下载', '04'),('0402', '技术论坛', '04'),('0403', '电子邮箱', '04'),('0404', '网盘', '04'),('0405', '网络相册', '04'),('0406', '在线翻译', '04'),('0407', '域名注册', '04'),('0408', '搜索查询', '04'),('0409', '其他', '04'),('05', '博客及空间', ''),('0501', '财经', '05'),('0502', '娱乐', '05'),('0503', '体育', '05'),('0504', '社会', '05'),('0505', '教育', '05'),('0506', '女性', '05'),('0507', '空间周边', '05'),('07', '计算机软件硬件', ''),('0701', '电脑产品', '07'),('0702', '硬件资讯', '07'),('0703', '软件下载', '07'),('0704', '软件交流', '07'),('0705', '其他', '07'),('08', '数码', ''),('0801', '数码及手机资讯', '08'),('0802', 'MP3及MP4', '08'),('0803', '相机/摄像机', '08'),('0804', '数字家电', '08'),('0805', '游戏机', '08'),('0806', '手机资源及下载', '08'),('0807', '3G服务', '08'),('0808', '其他', '08'),('09', '教育', ''),('0901', '幼儿教育', '09'),('0902', '小学/初中教育', '09'),('0903', '高中教育/高考', '09'),('0904', '考研', '09'),('0905', '资格考试', '09'),('0906', '英语及其他培训', '09'),('0907', '课件下载', '09'),('0908', '其他', '09'),('10', '医疗保健', ''),('1001', '医学行业', '10'),('1002', '医疗健康', '10'),('1003', '母婴育儿', '10'),('1004', '两性健康', '10'),('1005', '儿童健康', '10'),('1006', '其他', '10'),('11', '女性时尚', ''),('1101', '美容减肥', '11'),('1102', '时装时尚', '11'),('1103', '奢侈品', '11'),('1104', '时尚论坛', '11'),('1105', '女性综合', '11'),('1106', '其他', '11'),('12', '社交服务', ''),('1201', '综合论坛', '12'),('1202', '交友和婚恋', '12'),('1203', '同学录', '12'),('1204', 'SNS', '12'),('1205', '垂直论坛', '12'),('1206', '其他', '12'),('13', '生活服务', ''),('1301', '宠物综合', '13'),('1302', '菜谱/饮食网址', '13'),('1303', '本地服务', '13'),('1304', '团购服务', '13'),('1305', '其他', '13'),('14', '房产家居', ''),('1401', '买卖租赁', '14'),('1402', '家居装饰', '14'),('1403', '其他', '14'),('15', '汽车', ''),('1501', '汽车资讯', '15'),('1502', '汽车周边服务', '15'),('1503', '车友会', '15'),('1504', '地方车网', '15'),('1505', '其他', '15'),('16', '交通旅游', ''),('1601', '旅游资讯', '16'),('1602', '票务服务（机票、火车票）', '16'),('1603', '旅行社/酒店', '16'),('1604', '出行查询（地图、天气）', '16'),('1605', '其他', '16'),('17', '体育', ''),('1701', '足球', '17'),('1702', '篮球NBA', '17'),('1703', '体育综合', '17'),('1704', '其他', '17'),('18', '金融', ''),('1801', '财经资讯', '18'),('1802', '股票基金', '18'),('1803', '博彩/彩票', '18'),('1804', '其他', '18'),('19', '垂直行业', ''),('1901', '电子五金', '19'),('1902', '农林牧渔', '19'),('1903', '轻工/纺织', '19'),('1904', '重工/机械', '19'),('1905', '化工/能源', '19'),('1906', '仪表仪器', '19'),('1907', '纸业印刷', '19'),('1908', '交通物流', '19'),('1909', '法律法规', '19'),('1910', '商务贸易', '19'),('1911', '其他', '19'),('20', '新闻', ''),('2001', '综合门户', '20'),('2002', '地方媒体', '20'),('2003', '媒体报刊', '20'),('2004', '广播/电视', '20'),('2005', '政史军事', '20'),('2006', '其他', '20'),('21', '人文艺术', ''),('2101', '摄影', '21'),('2102', '琴棋书画', '21'),('2103', '设计', '21'),('2104', '曲艺', '21'),('2105', '鉴赏收藏', '21'),('2106', '其他', '21'),('22', '小说', ''),('2201', '小说阅读', '22'),('2202', '文化文学', '22'),('2203', '电子书', '22'),('2204', '其他', '22'),('23', '人才招聘', ''),('2301', '综合人才网站', '23'),('2302', '地方人才网站精选', '23'),('2303', '行业人才网站', '23'),('2304', '其他', '23'),('24', '网络购物', ''),('2401', '购物综合', '24'),('2402', '家居用品', '24'),('2403', '数码家电', '24'),('2404', '导购比价', '24'),('2405', '电子支付', '24'),('2406', '其他', '24'),('25', '其他', '');

CREATE TABLE tag_ad (
    id varchar(10) not null primary key,
    title varchar(50) not null,
    parent_id varchar(10) not null default ''
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT '分类';

INSERT tag_ad (id, title, parent_id) VALUES ('10', 'IT产品类', ''),('1010', 'IT产品类企业形象', '10'),('101010', 'IT企业', '1010'),('1011', '办公及耗材', '10'),('101110', '办公设备', '1011'),('101111', '耗材', '1011'),('1012', '电脑', '10'),('101210', '笔记本', '1012'),('101211', '服务器', '1012'),('101212', '台式机', '1012'),('101213', '掌上电脑', '1012'),('1013', '技术服务', '10'),('101310', '技术服务', '1013'),('1014', '配件及外设', '10'),('101410', '核心配件', '1014'),('101411', '网络设备', '1014'),('101412', '周边外设', '1014'),('1015', '其他IT产品', '10'),('101510', '其他IT产品', '1015'),('1016', '软件产品', '10'),('101610', '办公软件', '1016'),('101611', '管理软件', '1016'),('101612', '金融软件', '1016'),('101613', '其他软件产品', '1016'),('101614', '手机操作系统', '1016'),('101615', '网络安全软件', '1016'),('101616', '游戏软件', '1016'),('1017', '网络通信', '10'),('101710', '电视', '1017'),('101711', '广播', '1017'),('101712', '通信服务', '1017'),('101713', '通信设备', '1017'),('101714', '通信终端', '1017'),('101715', '网站建设', '1017'),('101716', '网站社区', '1017'),('101717', '域名空间', '1017'),('11', '房地产类', ''),('1110', '房产', '11'),('111010', '房产中介', '1110'),('111011', '房屋出租', '1110'),('111012', '房屋买卖', '1110'),('111013', '楼盘宣传', '1110'),('111014', '其他房产', '1110'),('111015', '物业', '1110'),('1111', '房地产类企业形象', '11'),('111110', '房地产企业', '1111'),('12', '服饰类', ''),('1210', '服饰类企业形象', '12'),('121010', '服饰企业', '1210'),('1211', '内衣', '12'),('121110', '保暖内衣', '1211'),('121111', '塑身内衣', '1211'),('121112', '内衣', '1211'),('121113', '男袜', '1211'),('121114', '女袜', '1211'),('121115', '睡衣', '1211'),('121116', '家居服', '1211'),('1212', '其他服饰', '12'),('121210', '冬装', '1212'),('121211', '纺织辅料', '1212'),('121212', '婚纱', '1212'),('121213', '礼服', '1212'),('121214', '家用纺织品', '1212'),('121215', '其他服饰', '1212'),('121216', '童装', '1212'),('121217', '孕妇装', '1212'),('1213', '商务与休闲装', '12'),('121310', '衬衫', '1213'),('121311', '男装', '1213'),('121312', '女装', '1213'),('121313', '西服', '1213'),('121314', '外套', '1213'),('121315', '休闲装', '1213'),('1214', '饰品与配饰', '12'),('121410', '领带', '1214'),('121411', '腰带', '1214'),('121412', '帽子', '1214'),('121413', '丝巾', '1214'),('121414', '围巾', '1214'),('1215', '鞋类', '12'),('121510', '男鞋', '1215'),('121511', '女鞋', '1215'),('121512', '休闲鞋', '1215'),('121513', '运动鞋', '1215'),('121514', '正装鞋', '1215'),('1216', '运动服饰', '12'),('121610', '其他运动服饰', '1216'),('121611', '泳装', '1216'),('121612', '运动服', '1216'),('13', '个人用品类', ''),('1310', '个人用品类企业形象', '13'),('131010', '个人用品类企业', '1310'),('1311', '工艺品', '13'),('131110', '工艺品', '1311'),('1312', '礼品', '13'),('131210', '礼品', '1312'),('1313', '其他个人用品', '13'),('131310', '其他个人用品', '1313'),('1314', '饰品', '13'),('131410', '男饰品', '1314'),('131411', '女饰品', '1314'),('1315', '收藏品', '13'),('131510', '收藏品', '1315'),('1316', '手表', '13'),('131610', '手表', '1316'),('1317', '剃须产品', '13'),('131710', '剃须刀', '1317'),('131711', '剃须护理产品', '1317'),('1318', '箱包', '13'),('131810', '功能包', '1318'),('131811', '旅行箱包', '1318'),('131812', '男包', '1318'),('131813', '女包', '1318'),('131814', '钱包', '1318'),('131815', '挎包', '1318'),('1319', '眼镜', '13'),('131910', '功能镜', '1319'),('131911', '太阳镜', '1319'),('131912', '隐形眼镜', '1319'),('131913', '眼镜护理产品', '1319'),('1320', '珠宝首饰', '13'),('132010', '珠宝', '1320'),('132011', '首饰', '1320'),('14', '家居装饰类', ''),('1410', '床上用品', '14'),('141010', '床上用品', '1410'),('1411', '家居卖场', '14'),('141110', '家居卖场', '1411'),('1412', '家居装饰', '14'),('141210', '家具', '1412'),('141211', '建材', '1412'),('141212', '建筑工程', '1412'),('141213', '木材加工', '1412'),('141214', '装饰', '1412'),('1413', '家居装饰类企业形象', '14'),('141310', '家居企业', '1413'),('141311', '装饰企业', '1413'),('1414', '其他家居装饰', '14'),('141410', '其他家居装饰', '1414'),('1415', '装饰服务', '14'),('141510', '装饰服务', '1415'),('15', '交通类', ''),('1510', '乘用车', '15'),('151010', 'A级轿车', '1510'),('151011', 'B级轿车', '1510'),('151012', 'C级轿车', '1510'),('151013', 'MPV', '1510'),('151014', 'SUV', '1510'),('151015', '豪华车', '1510'),('151016', '交叉车型', '1510'),('151017', '新能源车', '1510'),('1511', '交通类企业形象', '15'),('151110', '交通企业', '1511'),('1512', '其他交通运输工具', '15'),('151210', '公路运输', '1512'),('151211', '航空服务', '1512'),('151212', '摩托车', '1512'),('151213', '其他交通运输工具', '1512'),('151214', '铁路服务', '1512'),('151215', '自行车', '1512'),('1513', '汽车服务', '15'),('151310', '其他汽车服务', '1513'),('151311', '汽车保养', '1513'),('151312', '汽车维修', '1513'),('151313', '汽车美容', '1513'),('151314', '汽车销售', '1513'),('151315', '汽车租赁', '1513'),('1514', '汽车零配件及周边', '15'),('151410', '轮胎', '1514'),('151411', '其他汽车零配件', '1514'),('151412', '汽油', '1514'),('151413', '燃油', '1514'),('151414', '润滑油', '1514'),('1515', '商用车', '15'),('151510', '货车', '1515'),('151511', '客车', '1515'),('151512', '商用车', '1515'),('1516', '物流', '15'),('151610', '物流', '1516'),('16', '教育出国类', ''),('1610', 'IT培训', '16'),('161010', 'IT培训', '1610'),('1611', '国内院校', '16'),('161110', '高等教育院校', '1611'),('161111', '高教自考', '1611'),('161112', '其他院校', '1611'),('161113', '职业技能学校', '1611'),('1612', '家教和拓展', '16'),('161210', '家教', '1612'),('161211', '拓展', '1612'),('1613', '教育出国类企业形象', '16'),('161310', '教育出国企业', '1613'),('1614', '留学出国', '16'),('161410', '国外院校', '1614'),('161411', '留学出国', '1614'),('1615', '其他教育出国', '16'),('161510', '其他教育', '1615'),('161511', '其他教育出国', '1615'),('1616', '休闲培训', '16'),('161610', '休闲培训', '1616'),('1617', '语言培训', '16'),('161710', '韩语培训', '1617'),('161711', '其它语言培训', '1617'),('161712', '日语培训', '1617'),('161713', '英语培训', '1617'),('1618', '在线培训', '16'),('161810', '在线培训', '1618'),('17', '金融服务类', ''),('1710', '金融服务类企业形象', '17'),('171010', '金融企业', '1710'),('1711', '投资理财产品及服务', '17'),('171110', '保险', '1711'),('171111', '发票', '1711'),('171112', '股票', '1711'),('171113', '基金', '1711'),('171114', '债券', '1711'),('171115', '期货', '1711'),('171116', '外汇', '1711'),('171117', '其他投资产品', '1711'),('1712', '银行产品及服务', '17'),('171210', '银行服务', '1712'),('171211', '银行卡', '1712'),('18', '零售及服务类', ''),('1810', '安防产品', '18'),('181010', '安保', '1810'),('181011', '保安', '1810'),('181012', '防盗产品', '1810'),('181013', '消防产品', '1810'),('181014', '警用装备产品', '1810'),('181015', '门禁产品', '1810'),('181016', '考勤产品', '1810'),('181017', '其他安防产品', '1810'),('1811', '技术商务服务', '18'),('181110', '包装', '1811'),('181111', '殡葬', '1811'),('181112', '出国', '1811'),('181113', '代理', '1811'),('181114', '调查', '1811'),('181115', '法律咨询', '1811'),('181116', '翻译', '1811'),('181117', '公关', '1811'),('181118', '广告', '1811'),('181119', '会计', '1811'),('181120', '审计', '1811'),('181121', '拍卖', '1811'),('181122', '配音', '1811'),('181123', '其他技术商务服务', '1811'),('181124', '设计', '1811'),('181125', '信息服务', '1811'),('181126', '印刷', '1811'),('181127', '招聘', '1811'),('181128', '专业服务', '1811'),('181129', '展会服务', '1811'),('181130', '咨询策划', '1811'),('1812', '居民生活服务', '18'),('181210', '搬家', '1812'),('181211', '办证', '1812'),('181212', '刻章', '1812'),('181213', '保洁', '1812'),('181214', '保姆', '1812'),('181215', '家政', '1812'),('181216', '虫害控制', '1812'),('181217', '婚庆', '1812'),('181218', '美容', '1812'),('181219', '美发', '1812'),('181220', '其他服务业', '1812'),('181221', '摄影', '1812'),('181222', '邮政', '1812'),('181223', '速递', '1812'),('181224', '征婚交友', '1812'),('1813', '零售及服务类企业形象', '18'),('181310', '零售服务', '1813'),('1814', '零售业', '18'),('181410', '其他零售业', '1814'),('181411', '商业零售服务', '1814'),('19', '其他类', ''),('1910', '包版', '19'),('191010', '包版', '1910'),('1911', '其他', '19'),('191110', '其他', '1911'),('1912', '社会福利', '19'),('191210', '社会福利', '1912'),('1913', '文字链', '19'),('191310', '文字链', '1913'),('1914', '政府机关', '19'),('191410', '政府机关', '1914'),('20', '日化类', ''),('2010', '护肤品', '20'),('201010', '面部护理', '2010'),('201011', '其他护肤用品', '2010'),('201012', '身体护理用品', '2010'),('201013', '手部护理用品', '2010'),('201014', '药妆用品', '2010'),('201015', '婴儿护肤用品', '2010'),('2011', '化妆品', '20'),('201110', '彩妆用品', '2011'),('201111', '化妆工具', '2011'),('201112', '精油香薰', '2011'),('201113', '其他化妆用品', '2011'),('201114', '香水', '2011'),('2012', '口腔护理', '20'),('201210', '其他口腔护理用品', '2012'),('201211', '牙膏', '2012'),('201212', '牙刷', '2012'),('2013', '其他日化用品', '20'),('201310', '其他日化用品', '2013'),('2014', '日化类企业形象', '20'),('201410', '日化企业', '2014'),('2015', '卫生用品', '20'),('201510', '计生用品', '2015'),('201511', '其他卫生用品', '2015'),('201512', '卫生巾', '2015'),('201513', '孕婴用品', '2015'),('201514', '纸巾', '2015'),('201515', '卫生纸', '2015'),('201516', '纸尿裤', '2015'),('2016', '卫生用品', '20'),('201617', '毛巾', '2016'),('201618', '浴巾', '2016'),('201619', '沐浴用品', '2016'),('201620', '其他卫浴用品', '2016'),('201621', '头发护理', '2016'),('2017', '洗涤用品', '20'),('201710', '餐具', '2017'),('201711', '厨具', '2017'),('201712', '其他洗涤用品', '2017'),('201713', '清洁剂', '2017'),('201714', '洗洁精', '2017'),('201715', '洗衣用品', '2017'),('201716', '消毒卫生用品', '2017'),('21', '食品饮料类', ''),('2110', '餐饮服务', '21'),('211010', '其他餐饮服务', '2110'),('211011', '西式连锁餐饮', '2110'),('211012', '饮料', '2110'),('211013', '小吃连锁', '2110'),('211014', '中式连锁', '2110'),('2111', '酒精饮料', '21'),('211110', '白酒', '2111'),('211111', '啤酒', '2111'),('211112', '其他酒精饮料', '2111'),('211113', '洋酒', '2111'),('2112', '软饮料', '21'),('211210', '茶饮料', '2112'),('211211', '果蔬汁饮料', '2112'),('211212', '机能饮料', '2112'),('211213', '咖啡饮料', '2112'),('211214', '奶粉', '2112'),('211215', '瓶装水', '2112'),('211216', '其他软饮料', '2112'),('211217', '乳饮料', '2112'),('211218', '碳酸饮料', '2112'),('211219', '植物饮料', '2112'),('2113', '食品', '21'),('211310', '冰雪食品', '2113'),('211311', '饼干', '2113'),('211312', '糕点', '2113'),('211313', '调味品', '2113'),('211314', '食用油', '2113'),('211315', '方便食品', '2113'),('211316', '糖果', '2113'),('211317', '其他食品', '2113'),('2114', '食品饮料类企业形象', '21'),('211410', '食品饮料企业', '2114'),('2115', '烟草与茶叶', '21'),('211510', '茶叶', '2115'),('211511', '烟草', '2115'),('2116', '饮食原料', '21'),('211610', '饮食原料', '2116'),('22', '网络服务类', ''),('2210', '购物', '22'),('221010', '电子产品', '2210'),('221011', '服饰', '2210'),('221012', '其他购物', '2210'),('221013', '日化产品', '2210'),('221014', '网上预订', '2210'),('221015', '综合购物', '2210'),('2211', '其他网络服务', '22'),('221110', '其他网络服务', '2211'),('2212', '企业网络服务', '22'),('221210', '企业网络服务', '2212'),('2213', '生活', '22'),('221310', '婚介', '2213'),('221311', '交友', '2213'),('221312', '教育培训', '2213'),('221313', '求职', '2213'),('2214', '虚拟', '22'),('221410', 'QQ专区', '2214'),('221411', '手机充值', '2214'),('221412', '网游点卡', '2214'),('2215', '娱乐', '22'),('221510', '彩票', '2215'),('221511', '下载', '2215'),('221512', '音乐', '2215'),('221513', '影视', '2215'),('2216', '资讯', '22'),('221610', '垂直网站', '2216'),('221611', '门户网站', '2216'),('23', '网络游戏类', ''),('2310', '大型多人在线游戏', '23'),('231010', '大型多人在线第一人称射击游戏', '2310'),('231011', '大型多人在线即时战略游戏', '2310'),('231012', '大型多人在线角色扮演游戏', '2310'),('231013', '大型多人在线竞速游戏', '2310'),('231014', '大型多人在线模拟经营游戏', '2310'),('231015', '大型多人在线体育游戏', '2310'),('231016', '大型多人在线音乐游戏', '2310'),('231017', '其他大型多人在线游戏', '2310'),('2311', '单机游戏', '23'),('231110', '单机第一人称射击游戏', '2311'),('231111', '单机即时战略游戏', '2311'),('231112', '单机角色扮演游戏', '2311'),('231113', '单机竞速游戏', '2311'),('231114', '单机模拟经营游戏', '2311'),('231115', '单机体育游戏', '2311'),('231116', '单机音乐游戏', '2311'),('231117', '其他单机游戏', '2311'),('2312', '网络游戏类企业形象', '23'),('231210', '网络游戏企业', '2312'),('2313', '网页游戏', '23'),('231310', '其他网页游戏', '2313'),('231311', '网页第一人称射击游戏', '2313'),('231312', '网页即时战略游戏', '2313'),('231313', '网页角色扮演游戏', '2313'),('231314', '网页竞速游戏', '2313'),('231315', '网页模拟经营游戏', '2313'),('231316', '网页体育游戏', '2313'),('231317', '网页音乐游戏', '2313'),('2314', '休闲游戏', '23'),('231410', '迷你休闲游戏', '2314'),('231411', '其他休闲游戏', '2314'),('231412', '棋牌休闲游戏', '2314'),('2315', '游戏平台', '23'),('231510', '电子竞技', '2315'),('231511', '电子竞技游戏平台', '2315'),('231512', '网页游戏平台', '2315'),('231513', '综合性游戏门户网站', '2315'),('24', '消费类电子类', ''),('2410', '家用电器', '24'),('241010', '厨卫电器', '2410'),('241011', '大电器', '2410'),('241012', '个人护理', '2410'),('241013', '小家电', '2410'),('2411', '其他消费类电子', '24'),('241110', '其他消费电子', '2411'),('2412', '数码影像', '24'),('241210', '摄像机', '2412'),('241211', '摄影配件', '2412'),('241212', '娱乐影音', '2412'),('241213', '照相机', '2412'),('2413', '通信产品', '24'),('241310', '3G手机', '2413'),('241311', 'GSM手机', '2413'),('241312', '电话机', '2413'),('241313', '手机配件', '2413'),('2414', '维修', '24'),('241410', '维修', '2414'),('2415', '消费类电子类企业形象', '24'),('241510', '消费类电子类企业形象', '2415'),('25', '行业用品类', ''),('2510', '采矿', '25'),('251010', '采矿', '2510'),('2511', '地质、水利、环境与公共设施管理', '25'),('251110', '地质', '2511'),('251111', '公共设施管理', '2511'),('251112', '水利管理', '2511'),('251113', '环境管理', '2511'),('2512', '电力燃气及水', '25'),('251210', '供电', '2512'),('251211', '供热', '2512'),('251212', '供水', '2512'),('2513', '电子电气', '25'),('251310', '电工器材', '2513'),('251311', '电机设备', '2513'),('251312', '电缆', '2513'),('251313', '电线', '2513'),('251314', '电源设备', '2513'),('251315', '电子元件', '2513'),('251316', '其他电子电气', '2513'),('251317', '仪器仪表', '2513'),('251318', '照明设备', '2513'),('2514', '环保回收', '25'),('251410', '废旧回收', '2514'),('251411', '节能', '2514'),('251412', '其他环保', '2514'),('251413', '污染处理', '2514'),('2515', '金属设备', '25'),('251510', '包装机械', '2515'),('251511', '纺织机械', '2515'),('251512', '工程机械', '2515'),('251513', '化工机械', '2515'),('251514', '机床设备', '2515'),('251515', '金属材料', '2515'),('251516', '勘探机械', '2515'),('251517', '模具', '2515'),('251518', '磨具', '2515'),('251519', '木工机械', '2515'),('251520', '农林机械', '2515'),('251521', '其他金属设备', '2515'),('251522', '清洁设备', '2515'),('251523', '商业设备', '2515'),('251524', '食品机械', '2515'),('251525', '通风设备', '2515'),('251526', '通用机械设备', '2515'),('251527', '通用零配件', '2515'),('251528', '五金机械', '2515'),('251529', '物流设备', '2515'),('251530', '橡塑设备', '2515'),('251531', '冶金', '2515'),('251532', '冶金机械', '2515'),('251533', '娱乐设备', '2515'),('251534', '造纸设备', '2515'),('2516', '农林牧渔', '25'),('251610', '化肥', '2516'),('251611', '农药', '2516'),('251612', '其他农林牧渔', '2516'),('251613', '兽药', '2516'),('251614', '兽医', '2516'),('251615', '养殖', '2516'),('251616', '园林景观', '2516'),('251617', '种植', '2516'),('2517', '石油化工', '25'),('251710', '化工材料', '2517'),('251711', '能源', '2517'),('251712', '其他石油化工', '2517'),('251713', '塑料', '2517'),('251714', '涂料', '2517'),('251715', '橡胶', '2517'),('2518', '文体用品', '25'),('251810', '办公设备', '2518'),('251811', '教学设备', '2518'),('251812', '乐器', '2518'),('251813', '其他文体用品', '2518'),('251814', '体育器械', '2518'),('251815', '玩具', '2518'),('251816', '文具', '2518'),('26', '医疗服务类', ''),('2610', '科室', '26'),('261010', '保健心理', '2610'),('261011', '传染科', '2610'),('261012', '儿科', '2610'),('261013', '妇科', '2610'),('261014', '内科', '2610'),('261015', '男科', '2610'),('261016', '皮肤科', '2610'),('261017', '其他科室', '2610'),('261018', '外科', '2610'),('261019', '五官科', '2610'),('261020', '中医', '2610'),('2611', '美容', '26'),('261110', '美容产品', '2611'),('261111', '整形产品', '2611'),('261112', '美容机构', '2611'),('261113', '整形机构', '2611'),('2612', '药品及保健品', '26'),('261210', '保健品', '2612'),('261211', '药品', '2612'),('261212', '医疗器械', '2612'),('2613', '医疗服务类企业形象', '26'),('261310', '医疗企业', '2613'),('2614', '医疗机构', '26'),('261410', '口腔诊所', '2614'),('261411', '其他医疗机构', '2614'),('261412', '体检机构', '2614'),('261413', '专科医院', '2614'),('261414', '综合医院', '2614'),('27', '娱乐及消闲类', ''),('2710', '彩票', '27'),('271010', '彩票', '2710'),('2711', '宠物', '27'),('271110', '宠物', '2711'),('2712', '出版品', '27'),('271210', '报纸', '2712'),('271211', '杂志', '2712'),('271212', '图书', '2712'),('271213', '音像', '2712'),('2713', '传媒与文化', '27'),('271310', '电视台', '2713'),('271311', '电台', '2713'),('271312', '电影', '2713'),('271313', '演出', '2713'),('2714', '公园与游乐园', '27'),('271410', '公园', '2714'),('271411', '游乐园', '2714'),('2715', '旅游餐饮住宿业', '27'),('271510', '其他住宿', '2715'),('2716', '旅游酒店', '27'),('271610', '宾馆酒店', '2716'),('271611', '餐饮业', '2716'),('271612', '交通票务', '2716'),('271613', '旅行社', '2716'),('271614', '旅游局', '2716'),('271615', '住宿业', '2716'),('2717', '其他娱乐及消闲', '27'),('271710', '其他娱乐', '2717'),('2718', '体育运动', '27'),('271810', '大学体育', '2718'),('271811', '单项体育', '2718'),('271812', '冬季运动', '2718'),('271813', '格斗运动', '2718'),('271814', '极限运动', '2718'),('271815', '汽车运动', '2718'),('271816', '世界体育竞赛', '2718'),('271817', '水上运动', '2718'),('271818', '体育用品', '2718'),('271819', '体育指导与训练', '2718'),('271820', '团队运动', '2718'),('271821', '虚拟体育', '2718'),('2719', '文体票务', '27'),('271910', '文体票务', '2719'),('2720', '消闲用品', '27'),('272010', '健身', '2720'),('272011', '玩具', '2720'),('2721', '星座占卜', '27'),('272110', '星座占卜', '2721'),('2722', '娱乐及消闲类企业形象', '27'),('272210', '娱乐企业', '2722'),('28', '运营商类', ''),('2810', '电信', '28'),('281010', '电信地市公司', '2810'),('281011', '电信集团', '2810'),('281012', '电信省公司', '2810'),('2811', '联通', '28'),('281110', '联通地市公司', '2811'),('281111', '联通集团', '2811'),('281112', '联通省公司', '2811'),('2812', '其他运营商', '28'),('281210', '其他运营商', '2812'),('2813', '移动', '28'),('281310', '移动地市公司', '2813'),('281311', '移动集团', '2813'),('281312', '移动省公司', '2813'),('29', '招商加盟类', ''),('2910', '保健品', '29'),('291010', '保健品', '2910'),('2911', '餐饮服务', '29'),('291110', '餐饮招商', '2911'),('2912', '服装鞋帽', '29'),('291210', '服装招商', '2912'),('291211', '鞋帽招商', '2912'),('2913', '干洗加盟', '29'),('291310', '干洗加盟', '2913'),('2914', '机械电子', '29'),('291410', '机械电子招商', '2914'),('2915', '家居建材', '29'),('291510', '家居建材招商', '2915'),('2916', '教育培训', '29'),('291610', '教育培训', '2916'),('2917', '美容化妆', '29'),('291710', '美容化妆招商', '2917'),('2918', '其他招商', '29'),('291810', '其他招商', '2918'),('2919', '生活用品', '29'),('291910', '生活用品招商', '2919'),('2920', '饰品礼品', '29'),('292010', '饰品礼品招商', '2920'),('2921', '娱乐休闲', '29'),('292110', '娱乐休闲招商', '2921');

CREATE DATABASE adx DEFAULT CHARSET UTF8;
USE adx;

CREATE TABLE dsp (
    id int not null auto_increment primary key,
    login_name varchar(50) not null comment '账户名',
    password char(32) not null comment 'password hash',
    name varchar(50) not null comment '名称',
    contact varchar(50) not null default '' comment '联系人',
    tel varchar(50) not null default '' comment '固定电话',
    mobile varchar(50) not null default '' comment '手机',
    email varchar(100) not null default '' comment '邮箱',
    company varchar(50) not null default '' comment '公司名称',
    address varchar(100) not null default '' comment '公司地址',
    zip_code varchar(10) not null default '' comment '公司邮编',
    fax varchar(20) not null default '' comment '传真',
    industry varchar(10) not null default '' comment '所属行业',
    api_token char(32) not null default '' comment 'api token',
    api_ip_list varchar(250) not null default '' comment 'api ip 白名单',
    adx_secret varchar(100) not null default '' comment 'adx密钥',
    dsp_qps_limit int not null default 0 comment 'DSQ QPS限制',
    sys_qps_limit int not null default 0 comment '系统QPS限制',
    cm_url varchar(250) not null default '' comment 'cookie mapping url',
    rtb_url varchar(250) not null default '' comment 'RTB url',
    click_monitor_domain varchar(250) not null default '' comment '点击监控域',
    pv_monitor_domain varchar(250) not null default '' comment '展现监控域',
    filter_position varchar(50) not null default '' comment '广告位位置过滤',
    filter_size varchar(250) not null default '' comment '广告位尺寸过滤',
    filter_industry varchar(1000) not null default '' comment '行业过滤',
    filter_area varchar(1000) not null default '' comment '地区过滤',
    deposit decimal(10,2) not null default 0 comment '保证金',
    factor decimal(10,1) not null default 1 comment '授信额度杠杆率',
    balance decimal(10,2) not null default 0 comment '账户余额',
    status tinyint not null default 1 comment '账户状态',
    create_time timestamp not null default current_timestamp comment '注册时间',
    unique key(login_name),
    unique key(name)
) ENGINE=INNODB DEFAULT CHARSET UTF8 AUTO_INCREMENT=100000 COMMENT 'DSP';

CREATE TABLE customer (
    id int not null auto_increment primary key,
    dsp_id int not null comment 'DSP ID',
    name varchar(50) not null comment '客户名称',
    legal_name varchar(100) not null default '' comment '客户主体资质名称',
    site_name varchar(50) not null default '' comment '网站名',
    site_url varchar(100) not null default '' comment '网站URL',
    tel varchar(50) not null default '' comment '联系电话',
    address varchar(100) not null default '' comment '通讯地址',
    attach_license varchar(100) not null default '' comment '营业执照附件',
    attach_legal_id varchar(100) not null default '' comment '法人身份证附件',
    attach_icp varchar(100) not null default '' comment 'ICP证附件',
    audit_result varchar(250) not null default '' comment '审核结果信息',
    status tinyint not null default 1 comment '审核状态',
    create_time timestamp not null default current_timestamp comment '创建时间',
    unique (dsp_id, name)
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT 'DSP客户';

CREATE TABLE ad (
    id int not null primary key comment 'ad id',
    dsp_id int not null comment 'DSP ID',
    fn_id varchar(50) null comment 'DSP系统内部ID',
    name varchar(100) not null comment '创意名称',
    type enum('image', 'flash') not null default 'image',
    target_url varchar(1000) not null comment '到达页面',
    click_url varchar(2000) not null comment '点击链接',
    pv_monitor_url varchar(1000) not null default '' comment '展现监控链接',
    industry varchar(50) not null default '' comment '行业',
    customer_id int not null comment '客户ID',
    width int not null default 0 comment '创意宽度',
    height int not null default 0 comment '创意高度',
    audit_result varchar(250) not null default '' comment '审核结果信息',
    status tinyint not null default 1 comment '状态',
    create_time timestamp not null default current_timestamp comment '创建时间',
    unique key (dsp_id,fn_id)
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT '广告创意';

CREATE TABLE journal (
    id int not null auto_increment primary key,
    dsp_id int not null comment 'DSP ID',
    amount decimal(10,2) not null comment '金额',
    type enum('add', 'inc') not null comment '类型',
    business varchar(20) not null comment '业务类型',
    invoice_status tinyint not null default 0 comment '发票状态',
    invoice_time timestamp null comment '开票时间',
    order_time date null comment '账单时间',
    expire_time date null comment '账单到期时间',
    deal_time timestamp null comment '完成时间',
    actual_amount decimal(10,2) not null default 0 comment '实际支付',
    remark varchar(250) not null default '' comment '备注',
    status tinyint not null default 1 comment '订单状态',
    last_udpate_time timestamp not null default current_timestamp on update current_timestamp,
    create_time timestamp null,
    key (dsp_id)
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT '账户流水';

CREATE TABLE summary_report (
    id int not null auto_increment primary key,
    date date not null comment '日期',
    dsp_id int not null comment 'DSP ID',
    bid_valid_request int not null default 0 comment '可用流量',
    bid_request int not null default 0 comment '实际发送流量',
    bid_response int not null default 0 comment '响应量',
    bid_part int not null default 0 comment '参与竞价量',
    bid_succ int not null default 0 comment '竞价成功数',
    bid_fail int not null default 0 comment '竞价失败数',
    bid_invalid int not null default 0 comment '无效竞价数',
    bid_timeout int not null default 0 comment '响应超时数',
    bid_error int not null default 0 comment '链接错误数',
    bid_q50 int not null default 0 comment '有效响应时间50分位',
    bid_q85 int not null default 0 comment '有效响应时间85分位',
    bid_q99 int not null default 0 comment '有效响应时间99分位',
    request int not null default 0 comment '广告请求',
    impression int not null default 0 comment '广告展现',
    click int not null default 0 comment '广告点击',
    consume decimal(10, 2) not null default 0 comment '消耗',
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key(dsp_id, date)
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT '总体竞价报表';

CREATE TABLE ad_report (
    id int not null auto_increment primary key,
    date date not null comment '日期',
    dsp_id int not null comment 'DSP ID',
    ad_id int not null comment 'AD ID',
    bid_part int not null default 0 comment '竞价数',
    bid_succ int not null default 0 comment '竞价成功数',
    bid_fail int not null default 0 comment '竞价失败数',
    request int not null default 0 comment '广告请求',
    impression int not null default 0 comment '广告展现',
    click int not null default 0 comment '点击',
    consume decimal(10,2) not null default 0 comment '消耗',
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key(ad_id,date),
    key (dsp_id)
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT '创意报告';

CREATE TABLE industry_report (
    id int not null auto_increment primary key,
    date date not null comment '日期',
    dsp_id int not null comment 'DSP ID',
    industry varchar(10) not null comment '行业',
    bid_part int not null default 0 comment '竞价数',
    bid_succ int not null default 0 comment '竞价成功数',
    bid_fail int not null default 0 comment '竞价失败数',
    request int not null default 0 comment '广告请求',
    impression int not null default 0 comment '广告展现',
    click int not null default 0 comment '点击',
    consume decimal(10,2) not null default 0 comment '消耗',
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key(dsp_id,date,industry)
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT '行业效果报告';

ALTER TABLE customer
ADD COLUMN is_white tinyint NOT NULL DEFAULT 0 COMMENT '是否在白名单中',
ADD COLUMN attach_license_time timestamp NULL DEFAULT NULL COMMENT '营业执照上传时间',
ADD COLUMN attach_legal_id_time timestamp NULL DEFAULT NULL COMMENT '法人身份证上传时间',
ADD COLUMN attach_icp_time timestamp NULL DEFAULT NULL COMMENT 'icp证上传时间';

ALTER TABLE customer ADD COLUMN attach_status tinyint NOT NULL DEFAULT 1 COMMENT '资质附件状态';
ALTER TABLE journal MODIFY COLUMN type enum('recharge', 'consume') NOT NULL COMMENT '类型';
ALTER TABLE journal ADD COLUMN sys_remark varchar(1000) NOT NULL DEFAULT '' COMMENT '系统备注';
ALTER TABLE journal ADD COLUMN op_username varchar(50) NOT NULL DEFAULT '' COMMENT '操作人用户名';
ALTER TABLE journal ADD COLUMN attachment varchar(100) NOT NULL DEFAULT '' COMMENT '附件';

ALTER TABLE ad ADD COLUMN upload varchar(100) NOT NULL DEFAULT '' COMMENT '物料地址';


-- 2014-04-21
CREATE TABLE size_report (
    id int not null auto_increment primary key,
    date date not null comment '日期',
    dsp_id int not null comment 'DSP ID',
    size varchar(20) not null comment '尺寸',
    bid_part int not null default 0 comment '竞价数',
    bid_succ int not null default 0 comment '竞价成功数',
    bid_fail int not null default 0 comment '竞价失败数',
    request int not null default 0 comment '广告请求',
    impression int not null default 0 comment '广告展现',
    click int not null default 0 comment '点击',
    consume decimal(10,2) not null default 0 comment '消耗',
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key(dsp_id,date,size)
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT '尺寸效果报告';

--@author 王玮
--@date 2014-04-28
--@desc 添加域名过滤字段
USE adx;
ALTER TABLE `adx`.`dsp` ADD COLUMN `filter_domain` TEXT NULL COMMENT '域名过滤' AFTER filter_industry;
ALTER TABLE `adx`.`dsp` CHANGE `filter_position` `filter_position` VARCHAR(50) DEFAULT 'unlimited' NOT NULL COMMENT '广告位位置过滤';

--@author 王玮
--@date 2014-05-13
--@desc 添加余额字段
ALTER TABLE `ad_core`.`agent` ADD COLUMN `balance` DECIMAL(10,2) DEFAULT '0.00' NOT NULL COMMENT '余额';
--@desc 建cpc的库
CREATE DATABASE `ad_cpc`
--@desc 创建ad_cpc客户表
use ad_cpc;
CREATE TABLE `advertiser` (
   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
   `name` varchar(50) NOT NULL COMMENT '客户名称',
   `agent_id` int(11) DEFAULT NULL COMMENT '广告主id',
   `allocation_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分配金额',
   `current_bid` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '当前出价',
   `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--@author 王玮
--@date 2014-05-14
--@desc 代理商流水表
CREATE TABLE `agent_journal` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `agent_id` int(11) DEFAULT NULL COMMENT '广告主id',
   `trans_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '交易时间',
   `trans_type` enum('充值','分配') DEFAULT '充值' COMMENT '交易状态 充值/分配',
   `amount` decimal(10,2) DEFAULT '0.00' COMMENT '交易花费',
   `balance` decimal(10,2) DEFAULT '0.00' COMMENT '交易前余额',
   `rel_id` int(11) DEFAULT '0' COMMENT '被分配客户流水id',
   `status` int(3) DEFAULT '0' COMMENT '交易状态，0:未处理 1:进行中 2:交易确认 3:交易完成 -1:交易取消 -3:交易失败',
   `op_username` varchar(30) DEFAULT '' COMMENT '操作人员名',
   `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
--@desc 客户流水表
CREATE TABLE `advertiser_journal` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `agent_id` int(11) DEFAULT NULL,
   `advertiser_id` int(11) NOT NULL,
   `trans_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '交易时间',
   `trans_type` enum('花费','分配') NOT NULL DEFAULT '花费' COMMENT '交易类型 花费/分配',
   `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '交易花费',
   `balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '交易前余额',
   `status` int(3) NOT NULL DEFAULT '0' COMMENT '交易状态，0:未处理 1:进行中 2:交易确认 3:交易完成 -1:交易取消 -3:交易失败',
   `op_username` varchar(30) NOT NULL DEFAULT '' COMMENT '操作人员名字',
   `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8

--@author 王刚
--@date 2014-05-05
--@desc 添加新的业务类型
USE ad_core;
ALTER TABLE ad MODIFY business_type enum('union','u-baidu','u-google','cki','u-tango','u-adx','agent', 'a-baidu', 'a-google', 'a-alimama', 'a-union', 'e-commerce','cpa','media','52game','game','none') NOT NULL DEFAULT 'none';


--@author 王刚
--@date 2014-05-09
--@desc 广告位扩展属性
------------------------------------------------------
use ad_core;
ALTER TABLE slot ADD COLUMN tag VARCHAR(50) NOT NULL DEFAULT '' COMMENT '媒体分类，数据表在tag_media，多个用逗号分隔',
ADD COLUMN page_position INT NOT NULL DEFAULT 1 COMMENT '1:首屏 100:非首屏',
ADD COLUMN page_domain VARCHAR(50) NOT NULL DEFAULT '' COMMENT '广告位所有页面的域名';

--@author 王刚
--@date 2014-05-15
--@desc 业务类型添加
------------------------------------------------------
use ad_core;
ALTER TABLE ad MODIFY COLUMN business_type VARCHAR(20) NOT NULL DEFAULT 'none' COMMENT '业务类型';

--@author 王刚
--@date 2014-05-20
--@desc cpc实时消耗表
------------------------------------------------------
use ad_cpc;
create table realtime_consume (
    id int not null auto_increment primary key,
    advertiser_id int not null,
    date date not null,
    consume decimal(10,2) not null default 0,
    status tinyint not null default 0 comment '0:未处理 1:已处理',
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key(advertiser_id, date),
    index(advertiser_id,status)
) engine=innodb default charset utf8;

alter table realtime_consume add column real_consume decimal(10,2) not null default 0 comment '结算真实消耗' after consume ;

rename table realtime_consume to advertiser_consume;

create table ad_consume(
    id int not null auto_increment primary key,
    ad_id int not null,
    date date not null,
    consume decimal(10,2) not null default 0,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key(ad_id, date)
) engine=innodb default charset utf8;

--@author 王刚
--@date 2014-05-22
--@desc CPC
------------------------------------------------------
use ad_cpc;
ALTER TABLE advertiser ADD COLUMN last_bid decimal(10,2) NOT NULL DEFAULT 0 COMMENT '上一次竞价价格' AFTER current_bid;

--@author 王刚
--@date 2014-05-29
--@desc ADX
------------------------------------------------------
use adx;
ALTER TABLE customer ADD COLUMN fn_id VARCHAR(50) NULL;
UPDATE customer SET fn_id=NULL where fn_id='';
ALTER TABLE customer ADD unique key (dsp_id, fn_id);

--@author 赵磊
--@date 2014-05-20
--@desc dsp cookie mapping统计
------------------------------------------------------
use adx;
CREATE TABLE statistic(
  id INT NOT NULL AUTO_INCREMENT,
  report_date DATE NOT NULL COMMENT '统计日期',
  dsp_id INT NOT NULL COMMENT 'dsp id',
  request INT NOT NULL COMMENT '请求数',
  `match` INT NOT NULL COMMENT '匹配成功数',
  fresh INT NOT NULL COMMENT '新用户数',
  PRIMARY KEY (id),
  UNIQUE INDEX (report_date, dsp_id)
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT 'adx dsp统计';

ALTER TABLE dsp ADD COLUMN enable_win_notify BOOLEAN DEFAULT FALSE  NOT NULL  COMMENT '发送win notify';
ALTER TABLE dsp ADD COLUMN win_notify_url VARCHAR(250) DEFAULT ''  NOT NULL  COMMENT '默认win_notify_url';

--@author 王刚
--@date 2014-06-19
--@desc 物料添加标签
------------------------------------------------------
use ad_core;

ALTER TABLE material add column tag varchar(20) not null default '' comment '行业标签' after name;

--@author 王刚
--@date 2014-06-19
--@desc cm表改名
------------------------------------------------------
use adx;
alter table statistic rename cm_statistic;

--@author 王刚
--@date 2014-06-23 21:42:48
--@desc 统计
------------------------------------------------------
use ad_core;
alter table analysis add column config text null default null comment '配置' after page_url;

--@author 王玮
--@date 2014-06-25
--@desc 增加ad_cpc.advertiser 唯一name
------------------------------------------------------
use ad_cpc;
ALTER TABLE `ad_cpc`.`advertiser` ADD UNIQUE `name` (`name`);

--@author 赵磊
--@date 2014-07-01
--@desc 更改字段注释
------------------------------------------------------
use adx;
ALTER TABLE summary_report CHANGE `bid_response` `bid_response` INT(11) DEFAULT 0  NOT NULL  COMMENT '参与竞价量';
ALTER TABLE summary_report CHANGE `bid_part` `bid_part` INT(11) DEFAULT 0  NOT NULL  COMMENT '有效竞价量';
ALTER TABLE cm_statistic ADD COLUMN `real_mached` INT(11) NOT NULL  COMMENT '实际匹配' AFTER `fresh`;

--@author 赵磊
--@date 2014-07-02
--@desc 增加更新时间和spam比例值
------------------------------------------------------
use adx;
ALTER TABLE cm_statistic ADD COLUMN `last_update_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE dsp ADD COLUMN `spam` INT DEFAULT 0  NOT NULL  COMMENT 'spam';

--@author 赵磊
--@date 2014-07-07
--@desc 错误报告表for wukong
------------------------------------------------------
use adx;
CREATE TABLE error_report(
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL DEFAULT '0000-00-00',
  `dsp_id` INT NOT NULL DEFAULT 0,
  `detail` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX (`date`, `dsp_id`)
) ENGINE=INNODB DEFAULT CHARSET UTF8 COMMENT 'adx 错误报告';

--@author 杨帆元
--@date 2014-07-08
--@desc dsp操作日志
------------------------------------------------------
use adx;
CREATE TABLE `data_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_name` varchar(30) NOT NULL,
  `table_name` varchar(30) NOT NULL,
  `object_id` varchar(20) NOT NULL,
  `op_username` varchar(20) NOT NULL DEFAULT '',
  `op_type` enum('insert','update','delete') NOT NULL,
  `change_fields` varchar(100) NOT NULL DEFAULT '',
  `change_data` text,
  `origin_data` text,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `db_name` (`db_name`,`table_name`,`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET UTF8;

--@author 赵磊
--@date 2014-07-09
--@desc 收录录入公共表
------------------------------------------------------
use ad_report;
CREATE TABLE union_data(
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `union_name` VARCHAR(50) NOT NULL,
  `union_passport` VARCHAR(50) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `request` INT NOT NULL DEFAULT 0,
  `impression` INT NOT NULL DEFAULT 0,
  `click` INT NOT NULL DEFAULT 0,
  `income` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `last_update_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`,`name`, `union_name`)
) ENGINE=InnoDB DEFAULT CHARSET UTF8;

--@author 王刚
--@date 2014-07-10 10:35:03
--@desc 添加impression字段
------------------------------------------------------
use ad_rt;
alter table data_5min add column impression int not null default 0 comment '曝光数，后面逐步替换play字段' after request;

use ad_report;
alter table ad_slot_hour_report add column impression int not null default 0 comment '曝光数，后面逐步替换play字段' after request;
alter table ad_slot_daily_report add column impression int not null default 0 comment '曝光数，后面逐步替换play字段' after request;
alter table slot_daily_report add column impression int not null default 0 comment '曝光数，后面逐步替换play字段' after request;
alter table ad_slot_daily_report add column consume bigint not null default 0 comment '消耗金额，单位 元 * 10e6' after valid_click;

--@author 王刚
--@date 2014-07-13 22:22:41
--@desc 媒体添加标签，广告位添加自定义标签
------------------------------------------------------
use ad_core;
alter table slot add column custom_tags varchar(100) not null default '' comment '自定义标签';

--@author 赵磊
--@date 2014-07-14
--@desc adx表增加放弃竞价字段
------------------------------------------------------
use adx;
ALTER TABLE summary_report ADD COLUMN `bid_giveup` INT(11) NOT NULL default 0 COMMENT '放弃竞价数';
ALTER TABLE ad_report ADD COLUMN `bid_giveup` INT(11) NOT NULL default 0 COMMENT '放弃竞价数';
ALTER TABLE size_report ADD COLUMN `bid_giveup` INT(11) NOT NULL default 0 COMMENT '放弃竞价数';
ALTER TABLE industry_report ADD COLUMN `bid_giveup` INT(11) NOT NULL default 0 COMMENT '放弃竞价数';

--@author 王刚
--@date 2014-07-18 23:12:31
--@desc 流量预警
------------------------------------------------------
use ad_core;
-- 删除废弃字段
alter table slot
drop column monitor_data,
drop column monitor_rule,
drop column min_cpm,
drop column max_cpm,
drop column send_mail,
drop column impression_rate;

alter table slot
add column estimated_type enum('none', 'estimate', 'agreement') not null default 'none' comment '流量预估类型',
add column estimated_request int not null default 0 comment '预估请求量',
add column ae varchar(50) not null default '' comment '运营人员';

--@author 王刚
--@date 2014-07-23 13:48:23
--@desc 投放添加联盟名称绑定
------------------------------------------------------
use ad_core;
alter table ad_brand add column union_slot_name varchar(100) not null default '' comment '联盟素材对应联盟后台的广告位名称，做收入映射';
alter table ad_brand add index (union_slot_name);

--@author 赵磊
--@date 2014-07-24 15:19:15
--@desc union_data表增加快速查询字段和索引
------------------------------------------------------
use ad_report;
ALTER TABLE union_data ADD COLUMN `ad_id` INT(11) NOT NULL DEFAULT 0;
ALTER TABLE union_data ADD INDEX (`name`);

--@author 王刚
--@date 2014-07-27 23:08:52
--@desc PBB-3 收入预估以及财务收入详情报告
--@todo dev
--@todo test
--@todo product
------------------------------------------------------
use hzeng_backend;
INSERT INTO `config` VALUES (NULL,'business_type','订单业务分类','[\n[ \n\"平台联盟\", \n[ \n\"百度-互众\", \"百度-奥丁\                               ", \"淘宝-互众\", \"淘宝-CNTV\", \n\"AFC-奥丁\", \"AFC-CNTV\            ", \"搜狗-互众\", \"搜狗-奥丁\",\n\"腾果\", \"好耶\", \"            其他联盟\"\n], \n[ \"平台联盟\" ]\n],\n\n[  \n\"平台客户\", \n  [ \"CPC\", \"BOX\", \"CPA\" ], \n   [\"电商\", \"医疗\", \"游戏\", \"金融\", \"其它\" ]\n],\n[ \n\"其它\", \n                       [ \"CKI\", \"LL\", \"52game\", \"自有电商\", \"自媒体\" ],\n[       \"-\" ]\n]\n]');

CREATE table box_sale (
    id int not null auto_increment primary key,
    advertiser_id int not null,
    date date not null,
    round int not null default 0,
    last_update_time timestamp not null default current_timestamp
) engine=innodb default charset utf8 comment 'box售卖情况';


--@author 王刚
--@date 2014-07-28 23:39:12
--@desc CIP-3 广告位,投放,物料. 权限系统
------------------------------------------------------
use ad_core;
create table object_lock (
    id int not null auto_increment primary key,
    type enum('slot', 'ad', 'material'),
    object_id int not null,
    locked tinyint not null default 0,
    username varchar(50) not null,
    lock_time timestamp not null default current_timestamp,
    unique key (type, object_id)
) engine=innodb default charset utf8;
--@author lihongyu
--@date 2014-04-30 13:32:13
--@desc PBB-3 收入预估以及财务收入详情报
------------------------------------------------------
ALTER TABLE `ad_core`.`orders` ADD COLUMN `order_category` VARCHAR(50) NULL AFTER `source`, ADD COLUMN `order_type` VARCHAR(50)     NULL AFTER `order_category`, ADD COLUMN `order_line` VARCHAR(50) NULL AFTER `order_type`;

--@author 王刚
--@date 2014-08-21 00:53:57
--@desc ADX-37 STORM统计代码开发
------------------------------------------------------
use adx;
create table status_report (
    id int not null auto_increment primary key,
    dsp_id int not null,
    date date not null,
    status int not null,
    cnt int unsigned  not null default 0,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key(dsp_id,date,status)
) engine=innodb default charset utf8;

--@author 杨帆元
--@date 2014-08-26
--@desc adx.dsp表增加日志输出级别字段|ADX-42 DSP添加日志输出级别属性
------------------------------------------------------
use adx;
ALTER TABLE dsp ADD COLUMN log_level tinyint(4) NOT NULL DEFAULT 0 COMMENT '日志输出级别，0所有状态的DSP LOG都输出，1未发起请求状态日志不记录';

--@author 杨帆元
--@date 2014-08-26
--@desc adx.dsp表增加日志输出级别字段|ADX-42 DSP添加日志输出级别属性
------------------------------------------------------
use adx;
ALTER TABLE `ad` MODIFY COLUMN `type` enum('image','flash','video') NOT NULL DEFAULT 'image';


--@author 王刚
--@date 2014-10-06 00:27:14
--@desc 后台收入
--@todo dev
------------------------------------------------------
use ad_report;
create table adv_month_income (
    id int not null auto_increment primary key,
    month char(7) not null,
    adv_id int not null,
    request bigint not null default 0,
    click bigint not null default 0,
    income decimal(10,2) not null default 0,
    data_version int not null default 0,
    manual tinyint not null default 0 comment '是否是手动编辑',
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (month, adv_id)
) engine=innodb default charset utf8;

use ad_core;
alter table advertiser add column union_name varchar(100) null comment '联盟唯一标志名称' after name,
add unique key(union_name);

CREATE TABLE `adv_daily_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `adv_id` int(11) NOT NULL,
  `request` bigint(20) NOT NULL DEFAULT '0',
  `click` bigint(20) NOT NULL DEFAULT '0',
  `income` decimal(10,2) NOT NULL DEFAULT '0.00',
  `data_version` int(11) NOT NULL DEFAULT '0',
  `manual` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是手动编辑',
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`date`,`adv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

use adx;
ALTER TABLE dsp add column advertiser_id int not null default 0 comment 'dsp对应的系统客户ID';

--@author 王刚
--@date 2014-10-19 21:02:05
--@desc 媒体成本
--@todo dev
--@todo test
------------------------------------------------------
use ad_report;
CREATE TABLE `media_month_cost` (
    id int not null auto_increment primary key,
    month char(7) not null,
    media_id int not null comment '媒体id，ad_core.slot.group_id',
    request bigint not null default 0,
    click int not null default 0,
    cost decimal(10,2) not null default 0,
    data_version int not null default 0,
    manual tinyint not null default 0 comment '是否手动编辑',
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (month, media_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--@author 万小阔
--@date 2014-10-20
--@desc adx.ad表增加flv格式文件秒针时间字段
------------------------------------------------------
use adx;
ALTER TABLE `ad` ADD COLUMN `flv_time`  int(11) NOT NULL DEFAULT 0 COMMENT 'flv文件秒针时间' AFTER `upload`;

--@author 王刚
--@date 2014-10-21 17:14:31
--@desc 小时，分钟数据添加消耗字段
------------------------------------------------------
use ad_report;
alter table ad_slot_hour_report add column consume int not null default 0 comment '消耗';

use ad_rt;
alter table data_5min add column consume int not null default 0 comment '消耗';

--@author 王刚
--@date 2014-10-30 20:31:29
--@desc DATA-2 大数据应用-文字链链接生成和投放
------------------------------------------------------
use ad_core;
create table system_config (
    id varchar(50) not null primary key,
    config text not null,
    last_update_time timestamp not null default current_timestamp on update current_timestampt
) engine=innodb default charset utf8;

--@author 赵磊
--@date 2014-10-31 17:37:14
--@desc ADM 客户、物料
------------------------------------------------------
USE ad_core;
ALTER TABLE advertiser ADD COLUMN `account_type` ENUM('common','dsp') DEFAULT 'common'   NOT NULL  COMMENT '账户类型';
ALTER TABLE advertiser ADD COLUMN `modified` TINYINT(4) DEFAULT 0  NOT NULL  COMMENT '数据更改(for DSP)';
ALTER TABLE advertiser ADD COLUMN `dealed` TINYINT(4) DEFAULT 0  NULL  COMMENT '是否脚本处理过';
ALTER TABLE material ADD COLUMN `account_type` ENUM('common','dsp') DEFAULT 'common'   NOT NULL  COMMENT '账户类型';
ALTER TABLE material ADD COLUMN `modified` TINYINT(4) DEFAULT 0  NOT NULL  COMMENT '数据更改(for DSP)';
ALTER TABLE material ADD COLUMN `dealed` TINYINT(4) DEFAULT 0  NULL  COMMENT '是否脚本处理过';

--@author 万小阔
--@date 2014-11-03 16:12
--@desc DSP 客户、物料 DATA-4后台支持外采ADX流量需求
------------------------------------------------------
DROP DATABASE IF EXISTS ad_dsp
CREATE DATABASE ad_dsp;
use ad_dsp;

SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `advertiser_adx` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '关系表id',
  `advertiser_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户id',
  `publisher_id` int(11) NOT NULL DEFAULT '0' COMMENT 'adx的id',
  `advertiser_adx_id` varchar(50) NOT NULL DEFAULT '0' COMMENT ' 该客户在adx那边id或编号',
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '该客户在adx那边审核状态 1审核通过 2待审核 3审核不通过 其他数值审核异常',
  `status_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '状态更改时间',
  `last_normal_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上一次审核通过时间',
  PRIMARY KEY (`id`),
  KEY `advertiser_id` (`advertiser_id`),
  KEY `publisher_id` (`publisher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE `material_adx` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '关系表id',
  `material_id` int(11) NOT NULL DEFAULT '0' COMMENT '物料id',
  `advertiser_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户id',
  `publisher_id` int(11) NOT NULL DEFAULT '0' COMMENT 'adx的id',
  `material_adx_id` varchar(50) NOT NULL DEFAULT '0' COMMENT '该物料在adx那边id或编号',
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '该物料在adx那边审核状态 1审核通过 2待审核 3审核不通过 其他为对应错误标示',
  `status_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '状态更改时间',
  `material_upload_path` varchar(250) NOT NULL,
  `last_normal_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上一次审核通过时间',
  PRIMARY KEY (`id`),
  KEY `material_id` (`material_id`),
  KEY `advertiser_id` (`advertiser_id`),
  KEY `material_upload_path` (`material_upload_path`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

--@author 王刚
--@date 2014-11-11 15:04:18
--@desc ADX外采
--@todo product
------------------------------------------------------
use ad_core;

ALTER TABLE publisher ADD COLUMN `platform` ENUM('default', 'dsp') DEFAULT 'default'   NOT NULL  COMMENT '绑定平台';
ALTER TABLE publisher ADD COLUMN `config` TEXT NULL  COMMENT '自定义配置';
ALTER TABLE material ADD COLUMN `mime` varchar(20) NOT NULL DEFAULT '' COMMENT '素材类型';
ALTER TABLE material ADD COLUMN duration int not null default 0 comment '播放时长';
ALTER TABLE upload_resource ADD COLUMN duration int not null default 0 comment '播放时长';
ALTER TABLE object_lock MODIFY COLUMN `type` enum('slot','ad','material', 'publisher', 'advertiser') not null;

--@author 万小阔
--@date 2014-11-12 11:11
--@desc DSP 客户、物料 DATA-4后台支持外采ADX流量需求
--@todo product
------------------------------------------------------
USE ad_dsp;

ALTER TABLE `material_adx`
ADD COLUMN `publisher_status`  tinyint(4) NOT NULL DEFAULT 0 COMMENT '在adx那边状态' AFTER `last_normal_time`;

ALTER TABLE `material_adx`
ADD COLUMN `publisher_status_explain`  varchar(500) NOT NULL COMMENT '第三方异常说明' AFTER `publisher_status`;

ALTER TABLE `material_adx`
MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 2 COMMENT '该物料在adx那边审核状态 1审核通过 2待审核 3审核不通过 4异常 其他为对应错误标示' AFTER `material_adx_id`;

--@author 王刚
--@date 2014-11-13 12:01:37
--@desc DESC
--@todo product
------------------------------------------------------
USE ad_dsp;
drop table advertiser_adx;
CREATE TABLE `advertiser` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '关系表id',
  `publisher_id` int(11) NOT NULL DEFAULT '0' COMMENT 'adx的id',
  `advertiser_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户id',
  `oid` varchar(50) NOT NULL DEFAULT '' COMMENT ' 该客户在adx那边id或编号',
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '该客户在adx那边审核状态 1审核通过 2待审核 3审核不通过 其他数值审核异常',
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '上次更改时间',
  `last_ok_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上一次审核通过时间',
  PRIMARY KEY (`id`),
  unique key(publisher_id, advertiser_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table material_adx;
CREATE TABLE `material` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '关系表id',
  `publisher_id` int(11) NOT NULL DEFAULT '0' COMMENT 'adx的id',
  `material_id` int(11) NOT NULL DEFAULT '0' COMMENT '物料id',
  `advertiser_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户id',
  `oid` varchar(50) NOT NULL DEFAULT '' COMMENT '该物料在adx那边id或编号',
  `url` varchar(250) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '该物料在adx那边审核状态 1审核通过 2待审核 3审核不通过 4异常 其他为对应错误标示',
  `ostatus` tinyint(4) NOT NULL DEFAULT '0' COMMENT '在adx那边状态',
  `ostatus_explain` varchar(500) NOT NULL COMMENT '第三方异常说明',
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '状态更改时间',
  `last_ok_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上一次审核通过时间',
  PRIMARY KEY (`id`),
  unique key(publisher_id,material_id),
  KEY (`advertiser_id`),
  KEY (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

use ad_core;
ALTER TABLE advertiser DROP COLUMN `modified`;
ALTER TABLE advertiser DROP COLUMN `dealed`;
ALTER TABLE advertiser DROP COLUMN `account_type`;
ALTER TABLE advertiser DROP COLUMN `platform`;
ALTER TABLE advertiser ADD COLUMN platform enum('default', 'dsp') NOT NULL DEFAULT 'default'  COMMENT '绑定的平台';
ALTER TABLE advertiser ADD COLUMN adx_status TINYINT NOT NULL DEFAULT 0  COMMENT '外接ADX平台的状态';

ALTER TABLE material DROP COLUMN `modified`;
ALTER TABLE material DROP COLUMN `dealed`;
ALTER TABLE material DROP COLUMN `account_type`;
ALTER TABLE material ADD COLUMN platform enum ('default', 'dsp') NOT NULL DEFAULT 'default' COMMENT '绑定的平台';
ALTER TABLE material ADD COLUMN adx_status TINYINT NOT NULL DEFAULT 0 COMMENT '外接ADX平台的状态';

ALTER TABLE ad ADD COLUMN publisher_id int NOT NULL DEFAULT 0 AFTER advertiser_id;



--@author 万小阔
--@date 2014-11-12 11:11
--@desc DSP 客户、物料 DATA-4后台支持外采ADX流量需求
--@todo test
--@todo product
------------------------------------------------------
use ad_core;
DROP TABLE IF EXISTS `cost_estimation`;
CREATE TABLE `cost_estimation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '预估单id',
  `name` varchar(100) NOT NULL COMMENT '预估单名称',
  `start_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '生效时间',
  `end_time` date NOT NULL COMMENT '结束时间',
  `media_id` int(11) NOT NULL DEFAULT '0' COMMENT '媒体id',
  `company_name` varchar(50) NOT NULL COMMENT '收款公司',
  `payer` tinyint(4) NOT NULL COMMENT '付款公司',
  `whole_site` int(4) NOT NULL DEFAULT '1' COMMENT '是否包站 1否 其他值为包月金额',
  `remark` text NOT NULL COMMENT '备注',
  `file_path` varchar(200) NOT NULL COMMENT '描述文件',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`,`company_name`),
  KEY `media_id` (`media_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='预估单表';

DROP TABLE IF EXISTS `cost_origin_slot`;
CREATE TABLE `cost_origin_slot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '广告位名称',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `media_id` int(11) NOT NULL COMMENT '媒体id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='原始广告位表';

DROP TABLE IF EXISTS `cost_origin_slot_log`;
CREATE TABLE `cost_origin_slot_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '原始广告位logid',
  `start_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '生效时间',
  `end_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '结束时间',
  `is_test` tinyint(4) NOT NULL DEFAULT '2' COMMENT '是否测试媒体 1是 2否',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类型 1包段 2包站 3固定单价',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格数字',
  `unit` tinyint(4) NOT NULL COMMENT '单位 1元 2天 3月 4年 5pv 6click 7adshow',
  `assurance` int(11) NOT NULL DEFAULT '0' COMMENT '是否保量 0不保量 其他值为保量数字',
  `assurance_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '保量类型 1pv 2click 3adshow',
  `remark` text NOT NULL COMMENT '备注',
  `cost_estimation_id` int(11) NOT NULL DEFAULT '0' COMMENT '预估单id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '产生时间',
  `basic_slot_id` int(11) NOT NULL DEFAULT '0' COMMENT '原始广告位id',
  PRIMARY KEY (`id`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='原始广告位日志表';


------------------------------------------------------
--@author 万小阔
--@date 2014-11-12 11:11
--@desc DSP 客户、物料 DATA-4后台支持外采ADX流量需求
--@todo test
--@todo product
------------------------------------------------------
use ad_core;
ALTER TABLE `cost_origin_slot`
ADD COLUMN `data_log`  text NOT NULL COMMENT '存储logs' AFTER `media_id`;

--@author 万小阔
--@date 2014-11-28 14:45
--@desc DSP 客户、物料 DATA-4后台支持外采ADX流量需求
------------------------------------------------------
use ad_dsp;

CREATE TABLE `slot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adx_slot_id` int(11) NOT NULL COMMENT 'adx那边广告位id',
  `publisher_id` int(11) NOT NULL COMMENT 'adx的id',
  `name` varchar(200) NOT NULL COMMENT '广告位名称',
  `width` int(11) NOT NULL COMMENT '宽度',
  `height` int(11) NOT NULL COMMENT '高度',
  `price` int(11) NOT NULL COMMENT '价格单位：分/千次曝光',
  `mime` varchar(255) NOT NULL COMMENT '类型',
  PRIMARY KEY (`id`),
  UNIQUE KEY `adx_slot_id` (`adx_slot_id`,`publisher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--@author 赵磊
--@date 2014-11-28 14:33
--@desc DSP增加报表
------------------------------------------------------
use ad_dsp;

CREATE TABLE report(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `publisher_id` INT(11) NOT NULL,
  `adx_slot_id` INT(11) NOT NULL COMMENT 'aid',
  `bid` INT(11) NOT NULL,
  `winbid` INT(11) NOT NULL,
  `pv` INT(11) NOT NULL,
  `click` INT(11) NOT NULL,
  `consume` BIGINT(20) NOT NULL COMMENT '消耗金额，单位 元 * 10e6',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`,`publisher_id`,`adx_slot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--@author 万小阔
--@date 2014-12-4 18:20
--@desc DSP 客户、物料 DATA-4后台支持外采ADX流量需求
------------------------------------------------------
use ad_dsp;

ALTER TABLE `material`
MODIFY COLUMN `ostatus`  smallint(4) NOT NULL DEFAULT 0 COMMENT '在adx那边状态' AFTER `status`;
ALTER TABLE `advertiser`
ADD COLUMN `ostatus`  smallint NULL AFTER `last_ok_time`,

ADD COLUMN `ostatus_explain`  varchar(500) NULL AFTER `ostatus`;



--@author 王刚
--@date 2014-12-09 12:03:37
--@desc 流量来源
------------------------------------------------------
use ad_report;

CREATE TABLE source_data (
    id int not null auto_increment primary key,
    `date` date not null,
    slot_id int not null,
    se_slot_id int not null,
    se_ad_id int not null,
    request int not null,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (date,slot_id,se_slot_id,se_ad_id)
) engine=Innodb default charset=utf8;


--@author 王刚
--@date 2014-12-17 13:49:28
--@desc PBB-24 成本录入分配
------------------------------------------------------
use ad_report;

CREATE TABLE cost_metadata (
    id int not null auto_increment primary key,
    date date not null,
    root_id int not null default 0 comment '根节点ID',
    parent_id int not null default 0 comment '父节点ID',
    object_id int not null comment '成本对象ID',
    object_type enum('slot', 'ad') not null comment '成本对象类型',
    parent_object_id int not null default 0 comment '父节点对象ID，冗余字段，便于检索',
    node_cost decimal(10,2) not null default 0 comment '节点总成本',
    cost decimal(10,2) not null default 0 comment '节点实际分摊的成本',
    request int not null default 0 comment '请求量',
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    data_version int not null default 0 comment '数据版本，用于脚本多次运行的数据控制',
    unique key (date,parent_id,object_type,object_id)
) ENGINE=Innodb DEFAULT CHARSET=UTF8;

ALTER TABLE income_report
ADD COLUMN union_request int not null default 0 comment '联盟请求' AFTER cost2,
ADD COLUMN union_click int not null default 0 comment '联盟点击' AFTER union_request;

--@author 王刚
--@date 2014-12-24 00:41:07
--@desc 收入
------------------------------------------------------
use ad_report;
create table union_ad_data (
    id int not null auto_increment primary key,
    date date not null,
    ad_id int not null,
    impression int not null default 0,
    click int not null default 0,
    income decimal(10,2) not null default 0,
    remark varchar(250) not null default '',
    data_version int not null default 1,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (date, ad_id)
) engine=innodb default charset utf8;

--@author 杨帆元
--@date 2014-12-31
--@desc adx增加动态创意支持
------------------------------------------------------
use adx;
ALTER TABLE `ad` MODIFY COLUMN `type`  enum('image','flash','rich','video') NOT NULL DEFAULT 'image',
ADD COLUMN `ssi`  text NOT NULL COMMENT '创意代码';

ALTER TABLE `dsp` ADD COLUMN `allow_rich`  tinyint NOT NULL DEFAULT 0 COMMENT '是否支持动态创意';

--@author 王刚
--@date 2015-01-04 15:12:17
--@desc 广告筛选器流量统计
------------------------------------------------------
use ad_report;
CREATE TABLE ad_selector_report (
    id int not null auto_increment primary key,
    date date not null,
    slot_id int not null,
    selector varchar(20) not null default '',
    response int not null default 0,
    last_update_time timestamp not null default current_timestamp on update current_timestamp,
    unique key (date, slot_id, selector)
) engine=innodb default charset utf8;

--@author 王刚
--@date 2015-01-06 16:43:35
--@desc 配置管理权限控制
------------------------------------------------------
use hzeng_backend;
alter table config add column admin_uids varchar(200) not null default '' comment '配置管理人员uid，逗号分隔';

--@author 万小阔
--@date 2015-01-06 16:10
--@desc 任务工具
------------------------------------------------------
use hzeng_backend;

CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `type` varchar(40) NOT NULL COMMENT '类型 手动配置',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=>新建，2=>运行中，3=>运行成功，4=>运行失败',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `argument` varchar(200) NOT NULL COMMENT '参数',
  `start_time` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `end_time` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `remark` varchar(500) DEFAULT '' COMMENT '描述',
  `uid` int(11) DEFAULT NULL COMMENT '操作人',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


--@author 万小阔
--@date 2015-01-06 16:10
--@desc 任务工具
------------------------------------------------------
use hzeng_backend;

ALTER TABLE `task`
DROP COLUMN `name`,
DROP COLUMN `remark`;

--@author 王刚
--@date 2015-01-07 11:20:06
--@desc PBB-33 利润→联盟报表，新增mediav业务类型
------------------------------------------------------
use ad_core;
alter table ad_brand add column ecpm decimal(10,2) not null default 0 comment '投放的ecpm';

--@author 王刚
--@date 2015-01-09 15:45:36
--@desc PBB-30 新增主广告位报表
------------------------------------------------------
use ad_report;
CREATE TABLE main_slot_report (
    id int not null auto_increment primary key,
    date date not null,
    slot_id int not null,
    request int not null default 0,
    click int not null default 0,
    auto_click int not null default 0,
    income decimal(10, 2) not null default 0,
    cost decimal(10,2 ) not null default 0,
    data_version int not null default 0,
    last_update_time timestamp not null default current_timestamp on update current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--@author 王刚
--@date 2015-01-12 10:18:21
--@desc PBB-46 adm，广告主管理，新增所属公司字段
------------------------------------------------------
use ad_core;
alter table advertiser add column owned_company varchar(50) not null default '互众广告（上海）有限公司' comment '业务所属公司';

use hzeng_backend;
insert config (name, remark, content) values (
    'advertiser_owned_companies',
    '客户所属公司列表',
    '[\n"互众广告（上海）有限公司",\n"安徽奥丁信息技术有限公司",\n"北京都锦网络科技有限公司",\n"游炫信息科技（上海）有限公司"\n]'
);

--@author 赵磊
--@date 2015-01-12 10:17:53
--@desc PBB-27 abtest-V1.0
------------------------------------------------------
use ad_core;
ALTER TABLE object_group CHANGE `type` `type` ENUM('slot','ad','material','pv','abtest') NOT NULL;
ALTER TABLE ad CHANGE `type` `type` ENUM('union','brand','pop','adx','abtest') DEFAULT 'union'   NOT NULL  COMMENT '广告类型';
ALTER TABLE ad_brand ADD COLUMN `inhert_abtest` TINYINT(4) DEFAULT 0  NOT NULL  COMMENT '继承abtest';
ALTER TABLE ad_brand ADD COLUMN `abtest_group_id` INT NULL  COMMENT 'object_group id';
ALTER TABLE ad_brand ADD COLUMN `abtest_bid_rule` VARCHAR(500) NULL  COMMENT 'abtest胜出规则';
CREATE TABLE ad_abtest (
  `id` INT(11) NOT NULL,
  `slot_id` VARCHAR(250) NOT NULL DEFAULT '',
  `start_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '投放开始时间',
  `end_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '投放结束时间，0000-00-00为不限',
  `weight` INT(11) NOT NULL DEFAULT '50',
  `last_update_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `union_slot_name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '联盟素材对应联盟后台的广告位名称，做收入映射',
  `abtest_group_id` INT NULL  COMMENT 'object_group id',
  PRIMARY KEY (`id`),
  KEY `union_slot_name` (`union_slot_name`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE ad_abtest_bid_log (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ad_id` INT NOT NULL COMMENT '继承abtest投放ID',
  `rule` VARCHAR(500) NOT NULL COMMENT '胜出规则',
  `winner_id` INT NOT NULL COMMENT '胜出投放ID',
  `abtest_group_id` INT NOT NULL COMMENT '绑定abtest分组',
  `bid_ids` VARCHAR(200) NOT NULL COMMENT '参与计算投放ID',
  `rule_result` VARCHAR(500) NOT NULL COMMENT '胜出结果',
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`ad_id`, `create_time`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

use ad_report;
CREATE TABLE ad_abtest_daily_report(
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `ad_id` INT NOT NULL,
  `winner_id` INT NOT NULL COMMENT '胜出广告位',
  `request` INT NOT NULL,
  `click` INT NOT NULL,
  `income` decimal(10,2) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX (`date`, `ad_id`, `winner_id`)
);

--@author 王刚
--@date 2015-01-12 11:12:12
--@desc 添加主广告位标记
------------------------------------------------------
use ad_core;
alter table slot add column fin_type tinyint not null default 2 comment '账务类型, 1:主广告位 2:非主广告位 3:测试广告位';

--@author 王刚
--@date 2015-01-12 14:10:14
--@desc 清理表结构
------------------------------------------------------
use ad_core;
alter table slot drop column cost_value, drop column cost_unit,drop column cost_ctr;
alter table slot add column monitor_rule varchar(500) not null default '' comment '监控配置';

--@author 王刚
--@date 2015-01-12 19:49:49
--@desc 收入详情报表添加备注字段，填写一些校验信息
------------------------------------------------------
use ad_report;
alter table income_report add column remark varchar(255) not null default '';

alter table union_ad_data modify column remark text null;

--@author 王刚
--@date 2015-01-14 15:02:08
--@desc 添加移动广告位
------------------------------------------------------
use ad_core;
alter table slot modify column type enum('page', 'video', 'wap', 'app') not null default 'page' comment '广告位类型';

--@author 王刚
--@date 2015-02-02 15:42:36
--@desc 主广告位成本表优化
------------------------------------------------------
use ad_report;
alter table main_slot_report add column cost_rule varchar(250) not null default '' after cost,
add column cost_rule_parse varchar(250) not null default '' after cost_rule;

alter table cost_metadata add column cost_rule varchar(250) not null default '' after cost,
add column cost_rule_parse varchar(250) not null default '' after cost_rule;

--@author 杨帆元
--@date 2015-03-05 14:28:08
--@desc 添加adx广告位清单用于上传与下载
------------------------------------------------------
use adx;
DROP TABLE IF EXISTS `slot`;
CREATE TABLE `slot` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `slot_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `media` varchar(200) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `page_position` int(11) NOT NULL DEFAULT '1' COMMENT '1:首屏 100:非首屏',
  `estimated_request` int(11) NOT NULL DEFAULT '0' COMMENT '预估流量CPM',
  `bid_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'cpm底价',
  `size` varchar(100) NOT NULL DEFAULT '0*0' COMMENT '尺寸',
  `version` varchar(100) NOT NULL DEFAULT 'default' COMMENT '版本',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--@author 王刚
--@date 2015-03-26 11:07:12
--@desc 外采ADX
------------------------------------------------------
use ad_core;
alter table publisher add column dsp_sync_material tinyint not null default 1 comment 'DSP是否需要同步物料';

--@author 杨帆元
--@date 2015-03-26 17:58:08
--@desc 添加adx DSP系统内的广告主
------------------------------------------------------
use adx;
ALTER TABLE `ad`
ADD COLUMN `fn_customer_id`  int NOT NULL COMMENT 'DSP内部的广告主ID' AFTER `industry`;

--@author 王刚
--@date 2015-04-22 14:51:53
--@desc
------------------------------------------------------
use ad_dsp;
create table advertiser_publisher (
    advertiser_id int not null,
    publisher_id int not null,
    is_white tinyint not null comment '是否是白名单'
) engine=innodb default charset utf8;

--@author 王刚
--@date 2015-04-23 18:00:17
--@desc 订单预算控制
------------------------------------------------------
use ad_core;

alter table orders add column budget int not null default 0 comment '每日预算控制';

--@author 杨帆元
--@date 2015-04-15 10:18:08
--@desc 修改adx 尺寸过滤的字段长度
------------------------------------------------------
ALTER TABLE `dsp`
MODIFY COLUMN `filter_size`  varchar(1000) NOT NULL DEFAULT '' COMMENT '广告位尺寸过滤';

--@author 王刚
--@date 2015-05-08 14:14:19
--@desc ADM用户组功能
------------------------------------------------------
use ad_core;
alter table object_group modify type enum('slot','ad','material','pv','abtest', 'user') not null;
alter table user add column group_id int not null default 0 comment '用户组ID' after role;

create table user_object (
    _id int not null auto_increment primary key,
    _object_table varchar(50) not null,
    _object_id int not null,
    _user_id int not null,
    _user_group_id int not null,
    _create_time timestamp not null default current_timestamp,
    unique key (_object_table, _object_id),
    key (_user_group_id)
) engine=innodb default charset utf8;

insert object_group (name, type) values ('互众', 'user');
insert object_group (name, type) values ('晋拓', 'user');

--@author 王刚
--@date 2015-05-29 12:54:53
--@desc DESC
--@todo dev
------------------------------------------------------
ALTER TABLE ad_rt.data_5min ADD COLUMN
cost int unsigned  not null default 0 comment '成本，单位1/1000000元';

ALTER TABLE ad_report.ad_slot_hour_report ADD COLUMN
cost int unsigned not null default 0 comment '成本，单位1/1000000元';

ALTER TABLE ad_report.ad_slot_daily_report ADD COLUMN
cost int unsigned  not null default 0 comment '成本，单位1/1000000元';


--@author yangfanyuan
--@date 2015-07-03
--@desc DESC
------------------------------------------------------
use adx;
ALTER TABLE `dsp` ADD COLUMN `release_first` TINYINT(4) NOT NULL DEFAULT 0;

ALTER TABLE `ad` ADD COLUMN `release_status` TINYINT(4) NOT NULL DEFAULT 0;

ALTER TABLE `ad` ADD COLUMN `last_update_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';

--@author yangfanyuan
--@date 2015-07-06
--@desc DESC
------------------------------------------------------
use adx;
ALTER TABLE `ad` ADD INDEX  `create_time` (`create_time`);

--@author yangfanyuan
--@date 2015-08-06
--@desc DESC
------------------------------------------------------
use adx;
ALTER TABLE `customer` ADD COLUMN `industry` varchar(50) NOT NULL DEFAULT '' COMMENT '行业';


--@author yangfanyuan
--@date 2015-09-07
--@desc DESC
------------------------------------------------------
use adx;
ALTER TABLE `ad` CHANGE COLUMN `pv_monitor_url` `pv_monitor_url` TEXT;
ALTER TABLE `ad` ADD COLUMN `click_monitor_url` text  COMMENT '点击监控链接' AFTER  `pv_monitor_url`;

--@author yangfanyuan
--@date 2015-11-10
--@desc DESC
------------------------------------------------------
use adx;
ALTER TABLE `ad` ADD COLUMN `title` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `ad` CHANGE COLUMN `type` `type` ENUM('image','flash','video','rich','text-icon') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'image'  COMMENT '';


--@author wanggang
--@date 2016-01-28 11:17
--@desc
use ad_report;
ALTER TABLE income_report
DROP COLUMN cpa_register,
DROP COLUMN order_total,
DROP COLUMN order_deal,
DROP COLUMN order_money,
DROP COLUMN order_wj,
DROP COLUMN order_wj_deal,
DROP COLUMN order_wj_deal_money,
DROP COLUMN order_platform,
DROP COLUMN order_platform_deal,
DROP COLUMN order_platform_deal_money,
ADD COLUMN impression int not null default 0,
ADD COLUMN play int not null default 0,
ADD COLUMN act_click int not null default 0;

ALTER TABLE union_ad_data ADD COLUMN data_ids varchar(50) NOT NULL DEFAULT '';
