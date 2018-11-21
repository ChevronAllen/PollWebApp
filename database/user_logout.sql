CREATE DEFINER=`ermine`@`%` PROCEDURE `user_logout`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    OUT err INT
)
BEGIN
	SET err = 1;
    SET @valid = fn_isValidSession(uID, uSession);
    
    IF @valid THEN
		DELETE FROM Sessions
        WHERE userID = uID ;
        SET err = 0;
    ELSE
		SET err = 2;
    END IF;
    
END