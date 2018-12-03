CREATE DEFINER=`ermine`@`%` PROCEDURE `room_setResponse`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255),
    IN qID VARCHAR(255),
    IN qChoice VARCHAR(255)
)
BEGIN
	CALL session_update(uID, uSession);
    
    SET @valid = fn_isValidSession(uID, uSession);
    SET @optName = '';
    SET @locked = TRUE;
    
    SELECT isLocked INTO @locked
    FROM Questions
    WHERE questionID = qID;
    
    SELECT roomPublic INTO @public
	FROM Rooms
	WHERE roomID = rID;
    
    IF (@valid) THEN
		
        SELECT user_optionalName INTO @optName
        FROM Users
        WHERE userID = uID;        
        
        
        IF (NOT @locked)  THEN
        
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
        
	ELSEIF (uID = '') AND @public THEN
    
		IF (NOT @locked) THEN
			INSERT INTO Responses (questionID,roomID,userID,selection)
			VALUES (qID, rID,uID,qchoice);
				
			SELECT responseID 
			FROM Responses 
			WHERE questionID = qID AND roomID = rID AND userID = uID;
		ELSEIF @locked THEN
			SELECT responseID 
			FROM Responses 
			WHERE 1=0;
		END IF;
        
    END IF;
    
    
END