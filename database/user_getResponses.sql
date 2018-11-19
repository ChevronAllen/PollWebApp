CREATE DEFINER=`ermine`@`%` PROCEDURE `user_getResponses`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255)
)
BEGIN
	
    SET @valid = fn_isValidSession(uID,uSession);
    
    
    IF (@valid) THEN
		
        SELECT * FROM Responses
        WHERE roomID = rID;
        
    END IF;
    
    
END