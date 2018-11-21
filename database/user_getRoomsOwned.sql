CREATE DEFINER=`ermine`@`%` PROCEDURE `user_getRoomsOwned`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255)
)
BEGIN
	SET @valid = fn_isValidSession(uID,uSession);
    
    
    IF (@valid) THEN
		
        SELECT * 
        FROM Rooms
        WHERE ownerID = uID;
        
    END IF;
END