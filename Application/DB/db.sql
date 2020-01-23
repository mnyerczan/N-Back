drop database if exists NBackDB;
create database NBackDB;
use NBackDB;

create table users (
    id int(8) zerofill unsigned auto_increment primary key, -- unsigned mezőben nem lehet nulla az érték
    name varchar(255) not null,
    email varchar(255) not null,
    login_datetime DATETIME default current_timestamp not null,
    u_name varchar(255) not null,
    `password` varchar(33), -- A default user miatt
    `privilege` int(2) default '1' not null,
    birth date default '1899-01-01',
    pw_length varchar(255) default 0 not null,
    file_name varchar(255) default 'none' not null,
    theme varchar(5) default 'white' not null,
    `online` int(1) default 0 not null,
    unique (u_name),
    index users_id_idx(id)
)default charset utf8;

-- Default user

insert into users (id, name, email, u_name, `privilege`) values
('1', 'default', 'd@d.hu','default', '0');


-- A userekhez tartozó játék beállítások


create table n_back_datas(	
	user_id int(8) zerofill unsigned not null primary key,
	manual varchar(3) default 'Off' not null,
	`level` int(2) default 1 not null,
	seconds float(3,2) default 3 not null,
	trials int(2) default 25 not null,
	event_length float(4,3) unsigned default 0.5 not null,
	`color` varchar(7) default 'blue' not null,
	foreign key(user_id) references users(id) on delete cascade,
	index n_back_datas_user_id_idx(user_id)
)default charset utf8 engine innoDB;

/*
 * Trigger az n_back_datas táblához
 */

CREATE TRIGGER default_n_back_data AFTER INSERT ON users FOR EACH ROW INSERT INTO n_back_datas(user_id) VALUES (NEW.`id`);


/*
 * Megjegyzés! Innentől lehet felhasználót készíteni, de vagy
 * az első az admin és csak aztán lehet feltölteni sql-ből,
 * vagy tetszőleges mikor, de admin felhasználónevet kell választani.
 */


-- insert into users (  `name`, `email`, `u_name`,`password`, `privilege`) values
-- ( 'Teszt Elek', 'te@mail.hu','tesztelek',md5(concat('salt',md5('tesztelek'))), '1'),
-- ( 'Kiss Ede ', 'ke@mail.hu','kissede',md5(concat('salt',md5('kissede'))), '1'),
-- ( 'Szigorú Elek', 'sze@mail.hu','szigoruelek',md5(concat('salt',md5('szigoruelek'))), '1'),
-- ( 'Kellemetlen Imre', 'ki@mail.hu','kellemetlenimre',md5(concat('salt',md5('kellemetlenimre'))), '1'),
-- ( 'Magyar Béla', 'mb@mail.hu','magyarbela',md5(concat('salt',md5('magyarbela'))), '1');

-- A navigációs sávban megjelenő menüpontok


create table `menus` (
	`id` int(8) zerofill unsigned auto_increment ,
	`name` varchar(255) not null,
	`parent_id` varchar(255) default 'none' not null,
	`path` varchar(255) default 'index.php' not null,
	`ikon` varchar(255) default 'none' not null,
	`privilege` int(2) default 1 not null,
	`child` int(1) unsigned default 0 not null,
	index menus_parent_id_idx(parent_id),
	primary key(`id`)
)default charset utf8;


insert into menus (`name`, `path`, `parent_id` , `ikon`, `privilege`, `child`) values ('Forum','?index=7','none','none','1','1');
insert into menus (`name`, `path`, `parent_id` , `ikon`, `privilege`, `child`) values ('Edit','?index=10','none','none','3','1');
insert into menus (`name`, `path`, `parent_id` , `ikon`, `privilege`, `child`) values ('Edit Start page','?index=11&choose=1','00000002','none','1','0');
insert into menus (`name`, `path`, `parent_id` , `ikon`, `privilege`, `child`) values ('Edit forum ', '?index=10&choose=0' , '00000002','img/edit_blue_16.png','3','0');
insert into menus (`name`, `path`, `parent_id` , `ikon`, `privilege`, `child`) values ('Profiles','?index=8&choose=0','none','img/users_colorful.png','1','0');
insert into menus (`name`, `path`, `parent_id` , `ikon`, `privilege`, `child`) values ('Communal room','?index=7&offset=0 ','00000001','none','1','0');


-- A játék meneteit rögzítő tábla.

CREATE TABLE `n_back_sessions` (
  `user_id` int(8) zerofill unsigned NOT NULL DEFAULT '1',
  `ip` varchar(40) NOT NULL,
  `level` int(2) default 1 NOT NULL,
  `correct_hit` int(3) NOT NULL DEFAULT '0.00',
  `wrong_hit` int(3) NOT NULL DEFAULT '0.00',
  `time_length` int(7) NOT NULL DEFAULT '0.00',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `manual` int(2) NOT NULL DEFAULT '0',
  `result` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`, `ip`, `timestamp`),
  CONSTRAINT `n_back_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  unique(`timestamp`, `ip`),
  index n_back_session_user_id_idx(`user_id`),
  index time_idx(`time_length`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;

-- Admin létrehozása nélkül hibát fog dobni a külső kulcs miatt.


-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES ("2","127.0.0.1","1","1","3","90000","2019-05-01 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","1","9","2","90000","2019-05-02 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","1","9","2","90000","2019-05-03 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","2","3","7","90000","2019-05-04 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","2","5","5","90000","2019-05-05 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","3","6","2","90000","2019-05-06 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","4","5","9","90000","2019-05-07 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","4","9","2","90000","2019-05-07 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","4","3","3","90000","2019-05-07 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","3","3","7","90000","2019-05-08 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","4","9","6","90000","2019-05-09 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","5","5","4","90000","2019-05-10 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","6","6","2","90000","2019-05-15 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","7","6","0","90000","2019-05-15 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","7","9","0","300000","2019-05-16 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","6","6","0","300000","2019-05-17 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","6","7","2","300000","2019-05-18 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","7","4","1","300000","2019-05-18 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","7","7","2","300000","2019-05-18 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","7","8","0","300000","2019-05-18 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","7","9","2","300000","2019-05-18 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","7","9","2","300000","2019-05-18 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","7","7","2","300000","2019-05-18 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","8","7","4","300000","2019-05-19 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","8","9","2","300000","2019-05-19 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","8","5","0","300000","2019-05-19 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","8","4","2","300000","2019-05-19 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","8","9","2","300000","2019-05-19 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","8","9","2","300000","2019-05-19 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","8","9","2","300000","2019-05-19 00:00:00");
-- insert into n_back_sessions(user_id, ip, level, correct_hit, wrong_hit, time_length, timestamp) VALUES("2","127.0.0.1","8","9","2","300000","2019-05-19 00:00:00");


-- Dokumentum tároló tábla


CREATE TABLE documents(
`id` int(8) zerofill unsigned auto_increment,
`user_id` int(8) zerofill unsigned not null,
`title` varchar(255) default 'No title' not null,
`content` text,
`timestamp` timestamp default current_timestamp,
`privilege` int(1) unsigned default 0 not null,
primary key(id),
foreign key(user_id) references users(id)
)default charset utf8;


-- Fórum bejegyzések

set @@foreign_key_checks=0;

create table logs(
`id` int(12) zerofill unsigned auto_increment primary key,
`user_id` int(8) zerofill unsigned not null,
`title` varchar(255) default 'none' not null,
`content` text not null,
`timestamp` timestamp default current_timestamp not null,
`menu_id` int(8) zerofill unsigned not null,
`privilege` int(2) default '1' not null, -- Szándékosan nem foreign key a user id
foreign key(menu_id) references menus(id) on delete cascade on update cascade,
foreign key(user_id) references users(id)
)CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- Foreign key nézőben


select
    concat(table_name, '.', column_name) as 'foreign key',
    concat(referenced_table_name, '.', referenced_column_name) as 'references'
from
    information_schema.key_column_usage
where
    referenced_table_name is not null;
/*

+-------------------------+--------------+
| foreign key             | references   |
|-------------------------+--------------|
| documents.user_id       | users.id     |
| logs.menu_id            | menus.id     |
| logs.user_id            | users.id     |
| n_back_datas.user_id    | users.id     |
| n_back_sessions.user_id | users.id     |
+-------------------------+--------------+

*/




-- Example page


INSERT INTO `documents`(user_id, title, content, privilege) VALUES ('00000002','tutorial of n-back trial','<br>
<h1>Position N_Back By Brain Workshop</H1>
<br>
<blockquote cite="https://www.iqmindware.com/iq-mindware/training-strategies/">
<div id="start_page_img"></div>
<p><h3>How To Optimize Your Dual N-back Training</h3>

The question ‘What are the best strategies for increasing my dual n-back level?’ should be reframed as ‘What are the best strategies for increasing my working memory capacity and for improving my overall cognitive functioning?’

Before we tackle this question directly let us review some of the science.</p>
<div class="clear"></div>
<h2>KEY BACKGROUND THEORY</h2>
<p>
Working memory definition: Our mental workspace.

The dual n-back trains train your brain’s working memory circuitry.

Working memory can be defined as a brain system that helps us keep information in mind while using that information to complete a task (e.g. planned or strategic action, comprehending,  problem solving, decision-making). This can involve actively inhibiting distracting information.

A useful metaphor for working memory is the ‘mental workspace’:</p>
<h3>Dual N-Back: Working memory training to increase IQ</h3>
<p>

How is working memory and IQ (general intelligence) related? How does working memory training transfer to gains in intelligence? The answer is interference control
– the ability to filter out distracting information while engaging in some cognitive task, using your attentional focus.

Studies by Burgess, Gray, and my grad-school colleague Tod Braver (article 1, article 2) provide brain imaging evidence of a large overlap of IQ and working memory
brain mechanisms when there is need for interference control on a task – but not otherwise. Brain regions common to fluid intelligence and working memory became more
active when there is a need to filter out distractions. Interference control is a so-called ‘executive function’ – the ability to use focused attention to filter out
distracting information or suppress irrelevant habits or responses, when faced with cognitive challenges.
</p>
<br>
<p>
IQ Mindware n-back training – unlike standard dual n-back training – has built-in interference requiring continual interference control to perform the task. You may
have experienced interference in the standard dual n-back when the sequence of stimuli repeats itself before the target is presented. This creates confusion where you
have to ‘repeat yourself’ to keep the series of items in memory. In standard dual n-back this happens randomly. I have built it in as a central feature for working
memory training. Our data suggests this results in significantly better IQ gains.
</p>
</blockquote>
</div>','3');



