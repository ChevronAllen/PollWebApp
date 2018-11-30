CREATE DEFINER=`ermine`@`%` PROCEDURE `room_getQuestion`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255),
    IN qID VARCHAR(255)
)
BEGIN
	
    SET @valid = fn_isValidSession(uID, uSession);
	
    SET @public    = FALSE;
    SET @locked	   = TRUE;
    SET @startDate = '';
    SET @endDate   = '';
    SET @today = CURRENT_TIMESTAMP;
    
    SELECT r.roomPublic, r.dateStart, r.dateExpire, Q.isLocked
	INTO @public, @startDate, @endDate, @locked
	FROM Questions Q INNER JOIN Rooms R
		ON Q.roomID = R.roomID
	WHERE Q.questionID = qID AND R.roomID = rID;
    
    IF NOT @locked THEN
		IF @valid THEN		        
			
			SELECT Q.* 
			FROM Questions Q INNER JOIN Rooms R
				ON Q.roomID = R.roomID
			WHERE questionID = qID AND
				(r.dateStart < @today AND r.dateExpire > @today );
					
		ELSEIF (@public = TRUE )  AND (uID = '') THEN
			
			SELECT Q.* 
			FROM Questions Q INNER JOIN Rooms R
				ON Q.roomID = R.roomID
			WHERE questionID = qID AND
				(r.dateStart < @today AND r.dateExpire > @today );				
		
		END IF;
    END IF;
END