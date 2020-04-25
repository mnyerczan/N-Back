
-------------------------------------------------------------
-- Procedure for create user
-------------------------------------------------------------
CREATE PROCEDURE gnb(
    IN userName varchar(255),
    IN email varchar(255),
    IN password varchar(33),
    IN birth date,
    IN privilege int,
    IN passwordLength tinyint,
    -- image
    IN imgBin blob
)
BEGIN

    DECLARE errno INT;
    declare userid int;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            GET CURRENT DIAGNOSTICS CONDITION 1 errno = MYSQL_ERRNO;
            SELECT errno AS MYSQL_ERROR;
            ROLLBACK;
        END;

    START TRANSACTION; 

        INSERT INTO `users` ( `userName`, `email`, `password`, `birth`, `privilege`, `passwordLength` ) VALUES 
            ( userName, email, password, birth, privilege, passwordLength);

	    SELECT MAX(`id`) INTO userid FROM `users`;	

        INSERT INTO `images` (`userID`,`imgBin`) VALUES (userid, imgBin);
    
    COMMIT;
END;

