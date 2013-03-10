create table if not exists `devices` (
`id` int(11) not null auto_increment,
`name` varchar(255),
primary key(`id`)
);

insert into devices (`id`, `name`) values(1, 'Center bulb');
insert into devices (`id`, `name`) values(2, 'Side wall bulb');
insert into devices (`id`, `name`) values(2, 'Ceiling Fan');