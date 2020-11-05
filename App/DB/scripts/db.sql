drop database if exists `NBackDB`;
create database `NBackDB`;
use `NBackDB`;

-- web felhasználó létrehozása
create user if not exists `www-data`@`localhost` identified by '0000';
-- Összes jogosultság adása az adatbázis minden eleméhez
grant all privileges on NBackDB.* to 'www-data'@'localhost';

-------------------------------------------------------------------------
-- users definition
-- Hivatkoznak rá:
-- images
-- nbackDatas
-- nbackSessions
-- userWrongSessions
-- Documents
-- images
-------------------------------------------------------------------------

create table IF NOT EXISTS `users` (
    `id` int(8) unsigned primary key, -- unsigned mezőben nem lehet nulla az érték
    `name` varchar(255) DEFAULT "Anonim" not null,
    `email` varchar(255) not null,
    `loginDatetime` DATETIME DEFAULT current_timestamp not null,
    `password` varchar(33), -- A default user miatt
    `privilege` int(2) DEFAULT '1' not null,
    `birth` date DEFAULT '1899-01-01',
    `passwordLength` tinyint DEFAULT 0 not null,
    `about` varchar(255),
    `sex` enum('male', 'female') DEFAULT 'male' not null,
    `theme` varchar(5) DEFAULT 'white' not null, 
    `online` int(1) DEFAULT 0 not null,
    unique ( `email` ),
    index users_id_idx(id)
)DEFAULT charset utf8;

-- Add trigger idGen from triggers.sql!!!

-------------------------------------------------------------------------
-- images definition
-------------------------------------------------------------------------


-- Images
CREATE TABLE IF NOT EXISTS `images`(
    `userID` INT(8) zerofill unsigned primary key,
    `imgBin` blob,
    `update` DATETIME,
    `create` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FOREIGN KEY(`userID`) REFERENCES `users`(`id`) on delete cascade
) ROW_FORMAT=DYNAMIC;



-- A userekhez tartozó játék beállítások
-- Azért nem elsődleges kulcs a userID, mert akkor nem
-- hajtódik végre a delete cascade módosító.

create table IF NOT EXISTS `nbackDatas`(	
	`userID` int(8) unsigned not null,
	`gameMode` varchar(8) default 'Position' not null,
	`level` int(2) default 1 not null,
	`seconds` float(3,2) default 3 not null,
	`trials` int(2) default 25 not null,
	`eventLength` float(4,3) unsigned default 0.5 not null,
	`color` varchar(7) default 'blue' not null,
	foreign key(`userID`) references `users`(`id`) on delete cascade,
	index nbackDatas_userID_idx(userID)
)default charset utf8 engine innoDB;




-- insert into users (  `name`, `email`, `userName`,`password`, `privilege`) values
-- ( 'Teszt Elek', 'te@mail.hu','tesztelek',md5(concat('salt',md5('tesztelek'))), '1'),
-- ( 'Kiss Ede ', 'ke@mail.hu','kissede',md5(concat('salt',md5('kissede'))), '1'),
-- ( 'Szigorú Elek', 'sze@mail.hu','szigoruelek',md5(concat('salt',md5('szigoruelek'))), '1'),
-- ( 'Kellemetlen Imre', 'ki@mail.hu','kellemetlenimre',md5(concat('salt',md5('kellemetlenimre'))), '1'),
-- ( 'Magyar Béla', 'mb@mail.hu','magyarbela',md5(concat('salt',md5('magyarbela'))), '1');

-- A navigációs sávban megjelenő menüpontok


create table IF NOT EXISTS `menus` (
	`id` int(8) zerofill unsigned auto_increment ,
	`name` varchar(255) not null,
	`parentID` varchar(255),
	`path` varchar(255),
	`ikon` varchar(255),
	`privilege` int(2) default 0 not null,
	`child` int(1) unsigned default 0 not null,
	index menus_parentID_idx(parentID),
	primary key(`id`)
)default charset utf8;


insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Forum','?#',null,null,'0','1');
insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Edit','/forum/edit',null,null,'3','1');
insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Edit Start page','/main/eidt','00000002',null,'1','0');
insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Edit forum ', '/forum/edit' , '00000002','edit_blue_16.png','3','0');
insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Profiles','/profiles',null,'users_colorful.png','1','0');
insert into menus (`name`, `path`, `parentID` , `ikon`, `privilege`, `child`) values ('Communal room','/forum/common','00000001',null,'1','0');


-- A játék meneteit rögzítő tábla.

CREATE TABLE IF NOT EXISTS`nbackSessions` (
  `userID` int(8) zerofill unsigned NOT NULL DEFAULT '1',
  `ip` varchar(40) NOT NULL,
  `level` int(2) default 1 NOT NULL,
  `correctHit` int(3) NOT NULL DEFAULT '0.00',
  `wrongHit` int(3) NOT NULL DEFAULT '0.00',
  `sessionLength` int(7) NOT NULL DEFAULT '0.00' COMMENT "In miliseconds",
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gameMode` varchar(9) NOT NULL DEFAULT 'Position', 
  PRIMARY KEY (`userID`, `ip`, `timestamp`),
  CONSTRAINT `nbackSessions_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  unique(`timestamp`, `ip`),
  index nbackSession_userID_idx(`userID`)
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

-- User session eredmény tároló tábla

CREATE TABLE `sessionWrongResult`(
    `userId` int unsigned primary key,
    `result` tinyint unsigned DEFAULT 0 CHECK(`result` <= 2),
    CONSTRAINT `users_fk` FOREIGN KEY(`userId`) REFERENCES `users`(`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
)COLLATE "utf8_hungarian_ci" COMMENT "Users game sessions wrong result. If result == 2, user's game level decrease.";

-- Dokumentum tároló tábla


CREATE TABLE IF NOT EXISTS `documents`(
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

create table IF NOT EXISTS `logs`(
`id` int(12) zerofill unsigned auto_increment primary key,
`userID` int(8) zerofill unsigned not null,
`title` varchar(255) default 'none' not null,
`content` text not null,
`timestamp` timestamp default current_timestamp not null,
`menuID` int(8) zerofill unsigned not null,
`privilege` int(2) default '1' not null, -- Szándékosan nem foreign key a user id
foreign key(menuID) references menus(id) on delete cascade on update cascade,
foreign key(userID) references users(id)
)CHARACTER SET utf8 COLLATE utf8_hungarian_ci;


-- Foreign key nézőben


select
    concat(table_name, '.', column_name) as 'foreign key',
    concat(referenced_table_name, '.', referenced_column_name) as 'references'
from
    information_schema.key_column_usage
where
    referenced_table_name is not null;



drop table images;
drop table nbackDatas;
drop table nbackSessions;
drop table sessionWrongResult;
drop table documents;
drop table logs;
drop table users;