CREATE DEFINER=`ermine`@`%` PROCEDURE `room_getResponses`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255)
)
BEGIN
	
    SET @valid = fn_isValidSession(uID,uSession);
    
    
    IF (@valid) THEN
		
        SELECT DISTINCT R.responseID, R.questionID, R.roomID, R.responder AS `optionalName`, R.selection, R.date_submitted
        FROM Responses AS R LEFT JOIN Users AS U
			ON R.userID = U.userID        
        WHERE roomID = rID   
        ORDER BY U.userID, U.user_optionalName, questionID DESC;
        
    END IF;
    
    
END