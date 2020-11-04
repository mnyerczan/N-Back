-- ##########################################################
-- USER SECTION
-- ##########################################################

-- -----------------------------------------------------------
-- Procedure for create user
-- -----------------------------------------------------------
CREATE PROCEDURE `CreateNewUserprocedure`(
    IN `name` varchar(255),  
    IN `email` varchar(255),     
    IN `inNewPassword` varchar(33),   
    IN `birth` date,             
    IN `sex` varchar(6),         
    IN `privilege` INT,          
    IN `passwordLength` tinyint, 
    -- image                   
    IN `imgBin` blob             
)
BEGIN

    DECLARE `errno` INT; 
    DECLARE `userid` INT;    

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
                @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text); 
            SELECT @full_error;    
            ROLLBACK;
        END;

    DECLARE EXIT HANDLER FOR 1062
        BEGIN
            -- duplicate error handling
            SELECT CONCAT('Duplicate key (',email,') occurred') as message, '1062' as errno; 
            ROLLBACK;
        END;

    START TRANSACTION;

        INSERT INTO `users` ( `name`, `email`, `password`, `birth`,`sex` , `privilege`, `passwordLength` ) VALUES
            ( `name`, `email`, md5(CONCAT("salt",md5(`inNewPassword`))), `birth`, `sex`, `privilege`, `passwordLength`);

        SELECT MAX(`id`) INTO `userid` FROM `users`;

        INSERT INTO `images` (`userID`,`imgBin`) VALUES (`userid`, `imgBin`);

    COMMIT; 
END;

-- -----------------------------------------------------------
-- Procedure for get user
-- -----------------------------------------------------------
CREATE PROCEDURE `GetUser`(
    IN `inUId` INT,
    IN `inEmail` VARCHAR(255),
    IN `inPass` VARCHAR(33)
)
BEGIN
    IF `inUId` IS NOT NULL THEN
        SELECT 
            `u`.*, `i`.*,`n`.*, CURRENT_TIMESTAMP 
        FROM 
            `users` AS `u` JOIN `images` AS `i` JOiN `nbackDatas` AS `n`
        WHERE 
            `u`.`id` = `i`.`userID`         AND
            `u`.`id` = `n`.`userID`         AND
            `u`.`id` = `inUId`;
    ELSE
        SELECT 
            `u`.*, `i`.*, `n`.*, CURRENT_TIMESTAMP 
        FROM 
            `users` AS `u` JOIN `images` AS `i` JOiN `nbackDatas` AS `n`
        WHERE 
            `u`.`id` = `i`.`userID`         AND
            `u`.`id` = `n`.`userID`         AND
            `u`.`email` LiKE `inEmail`      AND
            `u`.`password` LIKE `inPass`;
    END IF;
END;

-------------------------------------------------------------
-- Procedure for upgrade user's personal data
-------------------------------------------------------------

CREATE PROCEDURE `UpdateUserPersonalDatas`(
    IN `userId`     int UNSIGNED,
    IN `name`       varchar(255),
    IN `email`      varchar(255),
    IN `about`      varchar(255), 
    IN `sex`        enum('male','female'),
    IN `privilege`  int UNSIGNED
)
BEGIN
    DECLARE errno INT;  

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
                @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text); 
            SELECT @full_error;    
            ROLLBACK;
        END;

    IF `privilege` LIKE '' THEN             
        UPDATE `users` AS `u` SET 
            `u`.`name`      = `name`,
            `u`.`email`     = `email`,
            `u`.`about`     = `about`,            
            `u`.`sex`       = `sex`
        WHERE `u`.`id` LIKE `userId`;
    ELSE
        UPDATE `users` AS `u` SET 
            `u`.`name`      = `name`,
            `u`.`email`     = `email`,
            `u`.`about`     = `about`,
            `u`.`privilege` = `privilege`,
            `u`.`sex`       = `sex`
        WHERE `u`.`id` LIKE `userId`;
    END IF;    
END;

-------------------------------------------------------------
-- Procedure for upgrade user's password 
-------------------------------------------------------------
CREATE PROCEDURE `upgradePassword`(
    IN `userId`   int,
    IN `inNewPassword` varchar(32),
    IN `inOldPassword` varchar(32)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
                @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text); 
            SELECT @full_error;    
            ROLLBACK;
        END;
        
    IF md5(CONCAT("salt", md5(`inOldPassword`))) = (
        SELECT `password` FROM `users` WHERE `id` LIKE `userId` ) THEN

        UPDATE `users` SET 
            `password` = md5(CONCAT("salt",md5(`inNewPassword`))),
            `passwordLength` = LENGTH(`inNewPassword`)
        WHERE `users`.`id` LIKE `userId` ;
        SELECT 'true' AS `result`;

    ELSE
        SELECT 'false' AS `result`;
    END IF; 
    
END;

-------------------------------------------------------------
-- Procedure for update game options
-------------------------------------------------------------
CREATE PROCEDURE `updateNBackOptions` (
    IN `inUserId`       INT,
    IN `newGameMode`    VARCHAR(8),
    IN `newLevel`       INT,
    IN `newSeconds`     FLOAT(3, 2),
    IN `newTrials`      INT,
    IN `newEventLength` FLOAT(4,3),
    IN `newColor`       VARCHAR(7)
)
BEGIN    
    -- A játék szintje(level) * 5 + 20 értéknél nem lehet kisebb 
    -- az összes esemény száma (trials)!
    -- Egy esemény hossza (eventhLength) nem lehet nagyobb két
    -- esemény közt eltelt időnél (seconds) - átlapolás
    IF  (20 + `newLevel` * 5) <= `newTrials` AND
        `newSeconds` >= `newEventLength` AND
        `newColor` IN ('blue','cyan','green','grey','magenta','red','yellow') THEN
        UPDATE `nbackDatas` SET 
            `gameMode`      = `newGameMode`,
            `level`         = `newLevel`,
            `seconds`       = `newSeconds`,
            `trials`        = `newTrials`,
            `eventLength`   = `newEventLength`,
            `color`         = `newColor`
        WHERE `userID` LIKE `inUserId`;
        SELECT true AS `result`;
    ELSE
        SELECT false AS `result`;
    END IF;
END;


-------------------------------------------------------------
-- Procedure for get users count
-------------------------------------------------------------
CREATE PROCEDURE `GetUserCount`()
BEGIN
    SELECT COUNT(*) AS num FROM `users`;
END;

-- ##########################################################
-- HOME PAGE SECTION
-- ##########################################################

-------------------------------------------------------------
-- Procedure for get home contents
-------------------------------------------------------------
CREATE PROCEDURE `GetHomeContent`(
    IN `inPrivilege` INT
)
BEGIN
    SELECT 
        `content` 
    FROM 
        `documents` 
    WHERE 
        `title` = "start_page" AND 
        `privilege` = `inPrivilege`;
END;

-- ##########################################################
-- NAVBAR SECTION
-- ##########################################################

-------------------------------------------------------------
-- Procedure for get parent menu items
-------------------------------------------------------------
CREATE PROCEDURE `GetMenus`(

    IN `inPrivilege` INT
)
BEGIN
    DECLARE errno INT; 

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            GET CURRENT DIAGNOSTICS CONDITION 1 errno = MYSQL_ERRNO;
            SELECT errno AS MYSQL_ERROR;
            ROLLBACK;
        END;
    SELECT 
        * 
    FROM 
        `menus`        
    WHERE 
        `parentID` is null AND 
        `privilege` <= `inPrivilege`
    ORDER BY 
        `child` ASC, `name` ASC;
END;


-------------------------------------------------------------
-- Procedure for get child menu items
-------------------------------------------------------------

CREATE PROCEDURE `GetChildMenus`(
    IN `inMenuId` INT,
    IN `inPrivilege` INT
)
BEGIN
    SELECT 
        * 
    FROM 
        `menus`
    where 
        `parentID` = `inMenuId` AND 
        `privilege` <= `inPrivilege`;
END;

-- ##########################################################
-- SERIA SECTION
-- ##########################################################

-------------------------------------------------------------
-- Procedure for get user's seria
-------------------------------------------------------------
CREATE PROCEDURE `GetSeria`(
    IN `inRemoteAddress` varchar(30),
    IN `inUserId` INT
)
BEGIN
    -- Ez a script lekéri a timestamp mezők date tagjának inté castolt értékét GROUP BY-olva, hozzá az összevonás számát
    -- pusztán szemléltetésnek, és az aznapi összes időt.

    select
        cast(current_date as unsigned) as `intDate`,
        -1 as `session`,
        -1 as `minutes`
        union all (
            select
                substr(cast(str_to_date(substr(`timestamp`, 1 ,10), '%Y-%m-%d %h:%i%p' ) as unsigned), 1, 8) as `intDate`,
                count(*) as `session`,
                round(sum(sessionLength) / 1000 /60, 1) as `minutes`
            from nbackSessions
            where 
                userID = `inUserId`  and 
                ip = `inRemoteAddress` and 
                gameMode = 0
            group by intDate
            having `minutes` >= 20
            order by `intDate` DESC, `session` ASC
            LIMIT 30
        );
END;


-- ##########################################################
-- SESSIONS SECTION
-- ##########################################################

-------------------------------------------------------------
-- Procedure for get sessions
-------------------------------------------------------------

-- Ha null értéket kapunk az inTimestamp-re, akkor 
-- beállítjuk egy nappal a mai előtti napfordulóra.

CREATE PROCEDURE `GetSessions`(
    IN `inUserId` INT,
    IN `inTimestamp` DATETIME
)
BEGIN  
    IF `inTimestamp` IS NULL THEN 
        SET `inTimestamp` = DATE_SUB(CONCAT(CURDATE(), ' 00:00:00'), INTERVAL 1 DAY); 
    END IF; 
    
    SELECT        
        `n`.*,
        -- CASE 
        --     WHEN `n`.`timestamp` < current_date THEN concat('Yesterday ', substr(`n`.`timestamp`,12, 5)) 
        --     ELSE substr(`timestamp`, 12, 5) 
        -- END `time`
        substr(`timestamp`, 12, 5) `time`
    FROM `nbackSessions` AS `n`
    WHERE 
        `n`.`userID` = `inUserId` AND 
        `n`.`timestamp` > `inTimestamp` 
    ORDER BY 
        `n`.`timestamp` DESC
    LIMIT 10;
END;


-------------------------------------------------------------
-- Procedure for get times of sessions of today 
-- and last 24 hours
-------------------------------------------------------------

CREATE PROCEDURE `GetTimes`(
    IN `inUserId` INT
)
BEGIN
    DECLARE `lastDate` datetime;
    SET `lastDate` = DATE_SUB(CONCAT(CURDATE(), ' 00:00:00'), INTERVAL 1 DAY);

    SELECT
        SEC_TO_TIME(CEIL(SUM(`sessionLength`) / 1000)) AS "last_day", (
            SELECT
                SEC_TO_TIME(CEIL(SUM(`sessionLength`) / 1000))
            FROM 
                `nbackSessions`
            WHERE 
                `userID` = `inUserId` AND 
                `timestamp` > current_date
        ) AS "today", (
            SELECT
                SEC_TO_TIME(CEIL(SUM(`sessionLength`) / 1000))
            FROM 
                `nbackSessions`
            WHERE 
                `userID` = `inUserId` AND 
                `timestamp` > CURRENT_DATE AND 
                `gameMode` = 0
            ) AS "today_position"
    FROM `nbackSessions`
    WHERE `userID` = `inUserId`
    AND `timestamp` > `lastDate`;
END;