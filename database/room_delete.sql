CREATE DEFINER=`ermine`@`%` PROCEDURE `room_delete`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255),
    OUT err INT
)
BEGIN
	SET err = 1;
	SET @valid = fn_isValidSession(uID,uSession);
    
    IF @valid THEN
		DELETE FROM Rooms
        WHERE ownerID = uID AND roomID = rID;
        SET err = 0;
	ELSE
		SET err = 2;
    END IF;
    
    
END