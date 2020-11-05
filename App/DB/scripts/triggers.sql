/*
    ID generator trigger for users
*/

CREATE  TRIGGER `userIdGen`
BEFORE INSERT ON `users`
FOR EACH ROW
BEGIN 
    DECLARE maxId int;
    SELECT MAX(`id`) + 1 INTO maxId  FROM `users`;
    IF maxId >= 1 THEN
        SET NEW.id = maxId;
    ELSE
        SET NEW.id = 1;    
    END IF;
END;


-- -----------------------------
-- Menu id generator
-- -----------------------------
CREATE  TRIGGER `menuIdGen`
BEFORE INSERT ON `menus`
FOR EACH ROW
BEGIN 
    DECLARE maxId int;
    SELECT MAX(`id`) + 1 INTO maxId  FROM `menus`;
    IF maxId >= 1 THEN
        SET NEW.id = maxId;
    ELSE
        SET NEW.id = 1;    
    END IF;
END;
