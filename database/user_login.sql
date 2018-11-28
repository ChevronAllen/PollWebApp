CREATE DEFINER=`ermine`@`%` PROCEDURE `user_login`(
    IN uEmail VARCHAR(255), 
    IN uPassword VARCHAR(255)
)
BEGIN

	#	Check for populated fields
    IF  NOT (
		(uEmail = '' ) OR
        (uPassword = '' )    ) THEN
		
        #	Find user with matching credentials
		SET @id = (
					SELECT userID 
                    FROM PollingZone.Users
                    WHERE user_password = SHA2(CONCAT(uPassword ,user_salt), 0) AND user_email = uEmail
                    LIMIT 1
        );
        
		IF NOT (@id IS NULL) THEN
        
			#	Set Error and generate session key
			
			SET @uSession = (SELECT fn_generateSessionKey());
			
			#	If user already has a session reset it and assign new key
			IF EXISTS(	SELECT userID FROM Sessions S WHERE userID = @id ) THEN
				UPDATE Sessions
					SET session_key = @uSession, date_lastActivity = NOW()
					WHERE userID = @id;
			ELSE
				INSERT INTO PollingZone.Sessions (userID, session_key) 
				VALUES (@id, @uSession);
			END IF;
            
            SELECT userID, user_firstName, user_lastName, user_optionalName, date_created , @uSession AS `session`
            FROM PollingZone.Users 
            WHERE Users.userID = @id
            LIMIT 1;

        END IF;
    
    END IF;
    
END