CREATE DEFINER=`ermine`@`%` PROCEDURE `room_setResponse`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255),
    IN qID VARCHAR(255),
    IN qChoice VARCHAR(255)
)
BEGIN
	
    
    SET @valid = fn_isValidSession(uID, uSession);
    SET @optName = '';
    SET @locked = TRUE;
    
    SELECT isLocked INTO @locked
    FROM Questions
    WHERE questionID = qID;
    
    
    IF (@valid) THEN
		
        SELECT user_optionalName INTO @optName
        FROM Users
        WHERE userID = uID;
        
        SELECT roomID INTO @valid
        FROM Questions
        WHERE questionID = qID AND roomID = rID;
        
        IF (@valid IS NOT NULL) AND NOT (@locked) THEN
        
			INSERT INTO Responses (questionID,roomID,userID,responder,selection)
			VALUES (qID, rID,uID,@optName, qchoice);
            
            SELECT responseID 
            FROM Responses 
            WHERE questionID = qID AND roomID = rID AND userID = uID;
            
		ELSE
			SELECT responseID 
            FROM Responses 
            WHERE 1=0;
        END IF;
        
    END IF;
    
    
END