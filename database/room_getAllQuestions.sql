CREATE DEFINER=`ermine`@`%` PROCEDURE `room_getAllQuestions`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255)	
)
BEGIN
	SET @valid = fn_isValidsession(uID,uSession);
    SET @public = TRUE;
    SET @today = CURRENT_TIMESTAMP;
    SET @startDate = '';
    SET @endDate = '';
   
    
    SELECT roomPublic, dateStart, dateExpire, roomTitle, ownerID
    INTO @public, @startDate, @endDate, @title, @ownedBy
    FROM Rooms
    WHERE roomID = rID
    LIMIT 1;
    
    IF (@today > @startDate AND @today < @endDate) THEN
		
        IF ((uID IS NULL OR uID = '') AND (@public = TRUE)) THEN
			
           
			SELECT * 
			FROM Questions 
			WHERE roomID = rID
            ORDER BY questionID ASC;
            
			#SET err = 0;
            
		ELSEIF @valid THEN
		
            
			SELECT * 
			FROM Questions 
			WHERE roomID = rID
            ORDER BY questionID ASC;
            
            #SET err = 0;
            
		END IF;
	ELSE
		       
        SELECT * 
		FROM Questions 
		WHERE 1 = 0;
        
		#SET err = 0;
        
    END IF;
END