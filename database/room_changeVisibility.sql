CREATE DEFINER=`ermine`@`%` PROCEDURE `room_changeVisibility`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255),
    IN rVis VARCHAR(255)
)
BEGIN
	SET @valid = fn_isValidSession(uID,uSession);
    
    IF (@valid) THEN
    
		UPDATE Rooms 
        SET Rooms.roomPublic = rVis 
        WHERE roomID = rID AND ownerID = rID;
        
        SELECT *
        FROM Rooms
        WHERE roomID = rID;
        
    ELSEIF (uID = '') THEN
    
		SELECT *
        FROM Rooms
        WHERE 1=0;
        
    END IF;
END