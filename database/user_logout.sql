CREATE DEFINER=`ermine`@`%` PROCEDURE `user_logout`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255)
)
BEGIN
    SET @valid = fn_isValidSession(uID, uSession);
    
    IF @valid THEN
    
		DELETE FROM Sessions
        WHERE userID = uID ;
        
        SELECT '' AS `session`;
        
    END IF;
    
END