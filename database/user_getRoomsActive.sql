CREATE DEFINER=`ermine`@`%` PROCEDURE `user_getRoomsActive`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255)
)
BEGIN
	SET @valid = fn_isValidSession(uID,uSession);
    CALL session_update(uID, uSession);
    
    IF (@valid) THEN
		
        SELECT * 
        FROM Rooms
        WHERE ownerID = uID AND dateExpire < NOW()
        ORDER BY dateExpire DESC;
        
    END IF;
END