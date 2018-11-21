CREATE DEFINER=`ermine`@`%` PROCEDURE `room_questionLock`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255),
    IN qID VARCHAR(255),
    IN qlocked VARCHAR(255)
)
BEGIN

	SET @valid = fn_isValidSession(uID,uSession);
	SET @locked = (qlocked + 0);
    
	IF @valid THEN
		
        
        UPDATE Questions
        SET isLocked = @locked
        WHERE questionID = qID AND roomID = rID AND userID = uID;
		
        SELECT 0 AS `error`;
        
    END IF;

END