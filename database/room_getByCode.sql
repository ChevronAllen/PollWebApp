CREATE DEFINER=`ermine`@`%` PROCEDURE `room_getByCode`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rCode VARCHAR(255),
    OUT err INT
)
BEGIN
	
    SET @valid = fn_isValidsession(uID,uSession);
    SET @rID = '';
    SET @public = TRUE;
    SET @today = CURRENT_TIMESTAMP;
    SET @startDate = '';
    SET @endDate = '';
   
    
    SELECT roomID, roomPublic, dateStart, dateExpire
    INTO @rID, @public, @startDate, @endDate
    FROM Rooms
    WHERE roomCode = rCode;
    
    IF (@today > @startDate AND @today < @endDate) THEN
		
        IF ((uID IS NULL OR uID = '') AND (@public = TRUE)) THEN
			
			SELECT * 
			FROM Questions 
			WHERE roomID = @rID;
            
			SET err = 0;
            
		ELSEIF @valid THEN
		
			SELECT * 
			FROM Questions 
			WHERE roomID = @rID;
            
            SET err = 0;
            
		ELSE
        
			SET err = 1;
            
		END IF;
	ELSE
		
        SELECT * 
		FROM Questions 
		WHERE 1 = 0;
        
		SET err = 0;
        
    END IF;
    
    
END