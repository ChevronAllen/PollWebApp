CREATE DEFINER=`ermine`@`%` PROCEDURE `user_getSubmissions`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255)
)
BEGIN
	CALL session_update(uID, uSession);
    SET @valid = fn_isValidSession(uID,uSession);
    
    IF (@valid) THEN
		
        SELECT DISTINCT R2.roomCode, R.roomID, date_submitted
        FROM Responses AS R LEFT OUTER JOIN Rooms AS R2
			ON R.roomID = R2.roomID
        WHERE R.userID = R.uID 
        GROUP BY R.roomID;
        
    END IF;
    
END