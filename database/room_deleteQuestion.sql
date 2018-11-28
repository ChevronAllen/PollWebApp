CREATE DEFINER=`ermine`@`%` PROCEDURE `room_deleteQuestion`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN qID VARCHAR(255)
)
BEGIN
	SET @valid = fn_isValidSession(uID,uSession);
    
    IF @valid THEN 
		
        DELETE FROM Questions
        WHERE questionID = qID AND userID = uID;
        
        SELECT qID AS `questionID`;

    END IF;
END