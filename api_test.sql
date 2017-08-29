##管理者登录##----ok
SELECT t.login_name, t.`pwd` FROM `user` as t WHERE t.`status` = 1;

##会员注册##---ok
#1.校验登录名是否被注册
SELECT COUNT(id) FROM member WHERE login_name = 'lyj';
#2.校验手机号码是否被注册
SELECT COUNT(id) FROM member WHERE mobile = '13895666698';

INSERT INTO member(login_name, pwd, real_name, mobile, gender) VALUES('lyj', '013895666698', '李玉娟', '13895666698', '0');

##管理者发布活动##
#1.校验同一时间是否发布过活动--ok
SELECT COUNT(id) FROM program WHERE start_time = '2017-08-19 10:00:00';
INSERT INTO program(name, description, imgs, start_time, end_time, address, fee_type, status, charge_user_id) 
	VALUES(
		'2017-08-19羽毛球活动',
		'2017-08-19羽毛球活动',
		'http://img1.imgtn.bdimg.com/it/u=997918794,3047337439&fm=26&gp=0.jpg',
		'2017-08-19 10:00:00',
		'2017-08-19 12:00:00',
		'深圳市龙华区民治民兴工业区民治羽毛球馆',
		'男-25元/人、女-20元/人',
		'fee_type_1',
		'program_status_1'
	);

##会员参加活动##--ok
#1.查询所有可以报名的活动
SELECT
	t.id,
	t.name,
	t.description,
	t.imgs,
	t.start_time,
	t.end_time,
	t.address,	
	t.participant_ids,
	t.field_num,
	f.name AS fee_type_name,
	f.min_num,
	f.max_num,
	f.remark AS fee_type_remark	
FROM 
	program AS t,
	user AS u,
	fee_type AS f
WHERE t.`status` = '1' AND t.charge_user_id = u.id AND t.fee_type_id = f.id;

#查询结果追加报名参加的人， IN后面的ids就是program 中的 participant_ids
SELECT t1.name, t1.mobile FROM participant t1 WHERE t1.sign_member_id IN('1','2','3') AND t1.program_id = '1';

#2.报名、报候补、放鸽子---ok
#2.1.查询客户是否已经报名
SELECT count(t1.id) FROM participant t1, program t2 WHERE t1.program_id = t2.id AND t1.sign_member_id = '1';

#2.2根据#1查询回来的数据进行判断客户能看到报名、报候补、放鸽子操作
#报名、报候补--participant_ids数量<field_num*max_num

INSERT INTO participant(name, mobile, program_id, member_id, gender, sign_member_id) VALUES(
	'惠玲',
	'13695448958',
	'1',
	'1',
	'0',
	'1'
);
INSERT INTO participant(name, mobile, program_id, member_id, gender, sign_member_id) VALUES(
	'惠玲姐姐',
	'13695448958',
	'1',
	'0',
	'0',
	'1'
);

UPDATE program AS t1 set t1.participant_ids = '1,2' WHERE t1.id = '1';

#2.3放鸽子
UPDATE program AS t1 set t1.participant_ids = '1' WHERE t1.id = '1';
DELETE FROM participant WHERE id = '2';

##管理员增加场地##--ok
#participant_ids数量-field_num*max_num>=4
UPDATE program AS t1 set t1.field_num = '2' WHERE t1.id = '1';

##截止报名##---ok
UPDATE program AS t1 set t1.`status` = '2' WHERE t1.id = '1';

##核对活动账务##
UPDATE program AS t1 set t1.`status` = '3' WHERE t1.id = '1';
UPDATE member AS t1 SET t1.balance = '75' WHERE t1.id = '1';
INSERT INTO fee_records(member_id, type, operator_id, operate_time, last_balance, after_balance) VALUES(
	'1',
	'1',
	'1',
	'2017-08-18 15:00:00',
	'0',
	'100'
);

##活动结束---ok
UPDATE program AS t1 set t1.`status` = '4' WHERE t1.id = '1';

##取消活动##---ok
UPDATE program AS t1 set t1.`status` = '0' WHERE t1.id = '1';

##会员充值##---ok
UPDATE member AS t1 SET t1.balance = '100' WHERE t1.id = '1';
INSERT INTO fee_records(member_id, type, operator_id, operate_time, last_balance, after_balance) VALUES(
	'1',
	'2',
	'1',
	'2017-08-18 15:00:00',
	'0',
	'100'
);









