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