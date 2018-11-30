CREATE DEFINER=`ermine`@`%` PROCEDURE `room_changeVisibility`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255),
    IN rVis VARCHAR(255)
)
BEGIN
	SET @valid = fn_isValidSession(uID,uSession);
    SET @visible = rVis +0;
    IF (@valid) THEN
    
		UPDATE Rooms 
        SET Rooms.roomPublic = @visible 
        WHERE roomID = rID AND ownerID = rID;
        
        IF @visible THEN
			UPDATE Rooms 
			SET Rooms.roomCode = fn_generateRoomCode() 
			WHERE roomID = rID AND ownerID = rID;
        ELSEIF NOT @visible THEN
			UPDATE Rooms 
			SET Rooms.roomCode = ''
			WHERE roomID = rID AND ownerID = rID;
        END IF;
        
        SELECT *
        FROM Rooms
        WHERE roomID = rID;
        
    ELSEIF (uID = '') THEN
    
		SELECT *
        FROM Rooms
        WHERE 1=0;
        
    END IF;
END