drop database if exists `NBackDB`;
create database `NBackDB`;
use `NBackDB`;

-- web felhasználó létrehozása
create user if not exists `www-data`@`localhost` identified by '0000';
-- Összes jogosultság adása az adatbázis minden eleméhez
grant all privileges on NBackDB.* to 'www-data'@'localhost';

create table IF NOT EXISTS `users` (
    `id` int(8) zerofill unsigned auto_increment primary key, -- unsigned mezőben nem lehet nulla az érték
    `userName` varchar(255) not null,
    `email` varchar(255) not null,
    `loginDatetime` DATETIME default current_timestamp not null,
    `password` varchar(33), -- A default user miatt
    `privilege` int(2) default '1' not null,
    `birth` date default '1899-01-01',
    `passwordLength` varchar(255) default 0 not null,
    -- `fileName` varchar(255) default 'none' not null,
    `theme` varchar(5) default 'white' not null, 
    `online` int(1) default 0 not null,
    unique ( `email` ),
    index users_id_idx(id)
)default charset utf8;


-- Images
CREATE TABLE `images`(
    `userID` INT(8) zerofill unsigned primary key,
    `imgBin` blob,
    `update` DATETIME,
    `create` TIMESTAMP NOT NULL DEFAULT current_timestamp,
    CONSTRAINT FOREIGN KEY(`userID`) REFERENCES `users`(`id`)
) ROW_FORMAT=DYNAMIC;

-- Default user

insert into users ( `id`, `userName`, `email`, `privilege`) values
('1', 'default', 'd@d.hu', '0');

-- A userekhez tartozó játék beállítások


create table nbackDatas(	
	userID int(8) zerofill unsigned not null primary key,
	gameMode varchar(8) default 'Position' not null,
	`level` int(2) default 1 not null,
	seconds float(3,2) default 3 not null,
	trials int(2) default 25 not null,
	eventLength float(4,3) unsigned default 0.5 not null,
	`color` varchar(7) default 'blue' not null,
	foreign key(userID) references users(id) on delete cascade,
	index nbackDatas_userID_idx(userID)
)default charset utf8 engine innoDB;

/*
 * Trigger az nbackDatas táblához
 */

CREATE TRIGGER defaultNbackData AFTER INSERT ON users FOR EACH ROW INSERT INTO nbackDatas(userID) VALUES (NEW.`id`);


/*
 * Megjegyzés! Innentől lehet felhasználót készíteni, de vagy
 * az első az admin és csak aztán lehet feltölteni sql-ből,
 * vagy tetszőleges mikor, de admin felhasználónevet kell választani.
 */


-- insert into users (  `name`, `email`, `userName`,`password`, `privilege`) values
-- ( 'Teszt Elek', 'te@mail.hu','tesztelek',md5(concat('salt',md5('tesztelek'))), '1'),
-- ( 'Kiss Ede ', 'ke@mail.hu','kissede',md5(concat('salt',md5('kissede'))), '1'),
-- ( 'Szigorú Elek', 'sze@mail.hu','szigoruelek',md5(concat('salt',md5('szigoruelek'))), '1'),
-- ( 'Kellemetlen Imre', 'ki@mail.hu','kellemetlenimre',md5(concat('salt',md5('kellemetlenimre'))), '1'),
-- ( 'Magyar Béla', 'mb@mail.hu','magyarbela',md5(concat('salt',md5('magyarbela'))), '1');

-- A navigációs sávban megjelenő menüpontok


create table `menus` (
	`id` int(8) zerofill unsigned auto_increment ,
	`name` varchar(255) not null,
	`parentID` varchar(255) default 'none' not null,
	`path` varchar(255) default 'index.php' not null,
	`ikon` varchar(255) default 'none' not null,
	`privilege` int(2) default 1 not null,
	`child` int(1) unsigned default 0 not null,
	index menus_parentID_idx(parentID),
	primary key(`id`)
)default charset utf8;


insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Forum','?#','none','none','0','1');
insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Edit','/forum/edit','none','none','3','1');
insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Edit Start page','/main/eidt','00000002','none','1','0');
insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Edit forum ', '/forum/edit' , '00000002','img/edit_blue_16.png','3','0');
insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Profiles','/profiles','none','img/users_colorful.png','1','0');
insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Communal room','/forum/common','00000001','none','1','0');


-- A játék meneteit rögzítő tábla.

CREATE TABLE `nbackSessions` (
  `userID` int(8) zerofill unsigned NOT NULL DEFAULT '1',
  `ip` varchar(40) NOT NULL,
  `level` int(2) default 1 NOT NULL,
  `correctHit` int(3) NOT NULL DEFAULT '0.00',
  `wrongHit` int(3) NOT NULL DEFAULT '0.00',
  `sessionLength` int(7) NOT NULL DEFAULT '0.00',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gameMode` varchar(9) NOT NULL DEFAULT 'Position',
  `result` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`, `ip`, `timestamp`),
  CONSTRAINT `nbackSessions_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  unique(`timestamp`, `ip`),
  index nbackSession_userID_idx(`userID`),
  index time_idx(`sessionLength`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;

-- Admin létrehozása nélkül hibát fog dobni a külső kulcs miatt.


-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES ("2","127.0.0.1","1","1","3","90000","2019-05-01 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","1","9","2","90000","2019-05-02 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","1","9","2","90000","2019-05-03 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","2","3","7","90000","2019-05-04 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","2","5","5","90000","2019-05-05 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","3","6","2","90000","2019-05-06 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","4","5","9","90000","2019-05-07 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","4","9","2","90000","2019-05-07 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","4","3","3","90000","2019-05-07 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","3","3","7","90000","2019-05-08 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","4","9","6","90000","2019-05-09 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","5","5","4","90000","2019-05-10 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","6","6","2","90000","2019-05-15 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","7","6","0","90000","2019-05-15 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","7","9","0","300000","2019-05-16 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","6","6","0","300000","2019-05-17 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","6","7","2","300000","2019-05-18 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","7","4","1","300000","2019-05-18 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","7","7","2","300000","2019-05-18 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","7","8","0","300000","2019-05-18 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","7","9","2","300000","2019-05-18 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","7","9","2","300000","2019-05-18 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","7","7","2","300000","2019-05-18 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","8","7","4","300000","2019-05-19 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","8","9","2","300000","2019-05-19 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","8","5","0","300000","2019-05-19 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","8","4","2","300000","2019-05-19 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","8","9","2","300000","2019-05-19 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","8","9","2","300000","2019-05-19 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","8","9","2","300000","2019-05-19 00:00:00");
-- insert into nbackSessions(userID, ip, level, correctHit, wrongHit, sessionLength, timestamp) VALUES("2","127.0.0.1","8","9","2","300000","2019-05-19 00:00:00");


-- Dokumentum tároló tábla


CREATE TABLE documents(
`id` int(8) zerofill unsigned auto_increment,
`userID` int(8) zerofill unsigned not null,
`title` varchar(255) default 'No title' not null,
`content` text,
`timestamp` timestamp default current_timestamp,
`privilege` int(1) unsigned default 0 not null,
primary key(id),
foreign key(userID) references users(id)
)default charset utf8;


-- Fórum bejegyzések

set @@foreign_key_checks=0;

create table logs(
`id` int(12) zerofill unsigned auto_increment primary key,
`userID` int(8) zerofill unsigned not null,
`title` varchar(255) default 'none' not null,
`content` text not null,
`timestamp` timestamp default current_timestamp not null,
`menuID` int(8) zerofill unsigned not null,
`privilege` int(2) default '1' not null, -- Szándékosan nem foreign key a user id
foreign key(menuID) references menus(id) on delete cascade on update cascade,
foreign key(userID) references users(id)
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
| documents.userID       | users.id     |
| logs.menuID            | menus.id     |
| logs.userID            | users.id     |
| nbackDatas.userID    | users.id     |
| nbackSessions.userID | users.id     |
+-------------------------+--------------+

*/




-- Example page

/* 
INSERT INTO `documents`(userID, title, content, privilege) VALUES ('00000002','tutorial of n-back trial','<br>
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
 */


