CREATE DEFINER=`ermine`@`%` PROCEDURE `user_create`(
	IN firstName VARCHAR(255),
    IN lastName VARCHAR(255),
    IN uOptional VARCHAR(255),
    IN uEmail VARCHAR(255), 
    IN uPassword VARCHAR(255), 
    IN uSalt VARCHAR(255), 
    OUT err INT)
BEGIN
    IF NOT (
		(firstName = '' OR firstName IS NULL) OR
        (lastName = '' OR lastName IS NULL) OR
        (uEmail = '' OR uEmail IS NULL) OR
        (uPassword = '' OR uPassword IS NULL) OR
        (uSalt = '' OR uSalt IS NULL)
    ) THEN
		INSERT INTO PollingZone.Users (userID, user_email, user_firstName, user_lastName, user_optionalName, user_password, user_salt)
		VALUES (
			MD5(uEmail),
			uEmail,
			firstName,
			lastName,
            uOptional,
			uPassword,
			uSalt
		);
        
        SELECT userID FROM Users WHERE userID = MD5(uEmail);
        SET err = 0;
	ELSE
		SELECT userID FROM Users WHERE 1=0;
        SET err = 1;
        
    END IF;
    
    
END