-- ##########################################################
-- USER SECTION
-- ##########################################################

-------------------------------------------------------------
-- Procedure for create user
-------------------------------------------------------------
CREATE DEFINER=`ms`@`%` PROCEDURE `CreateNewUserprocedure`(
    IN userName varchar(255),  
    IN email varchar(255),     
    IN password varchar(33),   
    IN birth date,             
    IN sex varchar(6),         
    IN privilege INT,          
    IN passwordLength tinyint, 
    -- image                   
    IN imgBin blob             
)
BEGIN

    DECLARE errno INT; 
    DECLARE userid INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            GET CURRENT DIAGNOSTICS CONDITION 1 errno = MYSQL_ERRNO;
            SELECT errno AS MYSQL_ERROR;
            ROLLBACK;
        END;

    DECLARE EXIT HANDLER FOR 1062
        BEGIN
            -- duplicate error handling
            SELECT CONCAT('Duplicate key (',email,') occurred') as message, '1062' as errno; 
            ROLLBACK;
        END;

    START TRANSACTION;

        INSERT INTO `users` ( `userName`, `email`, `password`, `birth`,`sex` , `privilege`, `passwordLength` ) VALUES
            ( userName, email, password, birth, sex, privilege, passwordLength);

        SELECT MAX(`id`) INTO userid FROM `users`;

        INSERT INTO `images` (`userID`,`imgBin`) VALUES (userid, imgBin);

    COMMIT; 
END;

-------------------------------------------------------------
-- Procedure for get user
-------------------------------------------------------------
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
            `users` AS `u` JOIN `images` AS `i` JOiN `nbackDatas` AS n
        WHERE 
            `u`.`id` = `i`.`userID`         AND
            `u`.`id` = `n`.`userID`         AND
            `u`.`id` = `inUId`;
    ELSE
        SELECT 
            `u`.*, `i`.*, `n`.*, CURRENT_TIMESTAMP 
        FROM 
            `users` AS `u` JOIN `images` AS `i` JOiN `nbackDatas` AS n
        WHERE 
            `u`.`id` = `i`.`userID`         AND
            `u`.`id` = `n`.`userID`         AND
            `u`.`email` LiKE `inEmail`      AND
            `u`.`password` LIKE `inPass`;
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
        `parentID` = "none" AND 
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
-- sERIA SECTION
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
