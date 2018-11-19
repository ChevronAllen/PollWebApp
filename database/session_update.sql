CREATE DEFINER=`ermine`@`%` PROCEDURE `session_update`(
	IN uID VARCHAR(255), 
    IN uSession VARCHAR(255)
)
BEGIN
	
    SET @valid = fn_isValidSession(uID,uSession);
    SET @today = NOW();

	IF @valid THEN 
		INSERT INTO Sessions (session_key,date_lastActivity)
        VALUES ( fn_generateSessionKey(),@today);
        
        SELECT session_key
        FROM Sessions
        WHERE userID = uID;
    END IF;
END