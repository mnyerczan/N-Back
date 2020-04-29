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
