create table if not exists `PaymentAccount`
(
	`id` int(10) not null auto_increment,
	`user_id` int(10) not null,
	`amount` decimal(12,2) not null,
	primary key (`id`),
	key `user` (`user_id`)
) engine InnoDB;

create table if not exists `PaymentInvoice`
(
	`id` int(10) not null auto_increment,
	`provider` varchar(200) not null,
	`user_id` int(10) not null,
	`modelClass` varchar(200) not null,
	`modelId` varchar(200) not null,
	`amount` decimal(12,2) not null,
	`description` varchar(200) default null,
	`url` varchar(200) default null,
	`state` int(10) not null,
	`createDate` datetime not null,
	`payDate` datetime default null,
	`refundDate` datetime default null,
	primary key (`id`),
	key `user` (`user_id`),
	key `model` (`modelClass`, `modelId`, `state`)
) engine InnoDB;

create table if not exists `PaymentTransaction`
(
	`id` int(10) not null auto_increment,
	`user_id` int(10) not null,
	`date` datetime not null,
	`amount` decimal(12,2) not null,
	`description` varchar(200) default null,
	`url` varchar(200) default null,
	`balance` decimal(12,2) not null,
	primary key (`id`),
	key `user` (`user_id`, `date`)
) engine InnoDB;
