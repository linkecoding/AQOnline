create database aqonline;

use aqonline;
-- 学生表
create table student(
	stu_id mediumint unsigned auto_increment primary key,
	stu_code varchar(20) not null,
	stu_password varchar(32) not null,
	stu_wechat_code varchar(32) not null,
	stu_nick_name nvarchar(20) not null,
	stu_avatar_url varchar(100) not null
);

-- 教师表
create table teacher(
	tea_id mediumint unsigned auto_increment primary key,
	tea_code varchar(20) not null,
	tea_password varchar(32) not null,
	tea_nick_name nvarchar(20) not null,
	tea_wechat_code varchar(32) not null,
	tea_avatar_url varchar(100) not null
);

-- 课程类别
create table course_category(
	cou_id mediumint unsigned auto_increment primary key,
	cou_name nvarchar(20) not null,
	cou_img_url varchar(100) not null
);

-- 问题表
create table question(
	que_id mediumint unsigned auto_increment primary key,
	que_title nvarchar(50) not null,
	que_content nvarchar(800) not null,
	que_reading_amount int
);

-- 提问表
create table ask_question(
	stu_id mediumint unsigned not null,
	que_id mediumint unsigned not null,
	cou_id mediumint unsigned not null,
	ask_time datetime not null,
	primary key(stu_id, que_id),
	foreign key(stu_id) references student(stu_id),
	foreign key(que_id) references question(que_id),
	foreign key(cou_id) references course_category(cou_id)
);

-- 解答表
create table answer_question(
	tea_id mediumint unsigned not null,
	que_id mediumint unsigned not null,
	ans_time datetime not null,
	ans_content nvarchar(800) not null,
	primary key(tea_id, que_id),
	foreign key(tea_id) references teacher(tea_id),
	foreign key(que_id) references question(que_id)
);

-- 评论表
create table comment(
	stu_id mediumint unsigned not null,
	que_id mediumint unsigned not null,
	com_time datetime not null,
	com_content nvarchar(200) not null,
	primary key(stu_id, que_id),
	foreign key(stu_id) references student(stu_id),
	foreign key(que_id) references question(que_id)
);

-- 经典题目表
create table good_topic(
	top_id mediumint unsigned auto_increment primary key,
	top_title nvarchar(50) not null,
	top_content nvarchar(800) not null,
	top_answer nvarchar(800) not null,
	top_time datetime not null,

	cou_id mediumint unsigned not null,
	tea_id mediumint unsigned not null,

	foreign key(cou_id) references course_category(cou_id),
	foreign key(tea_id) references teacher(tea_id)
);