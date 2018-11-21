CREATE DEFINER=`ermine`@`%` PROCEDURE `room_getQuestion`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255),
    IN qID VARCHAR(255),
    OUT err INT
)
BEGIN
	SET err = 1;
    SET @valid = fn_isValidSession(uID, uSession);
	
    SET @public    = FALSE;
    SET @startDate = '';
    SET @endDate   = '';
    SET @today = CURRENT_TIMESTAMP;
    
    SELECT r.roomPublic, r.dateStart, r.dateExpire
	INTO @public, @startDate, @endDate
	FROM Questions Q INNER JOIN Rooms R
		ON Q.roomID = R.roomID
	WHERE Q.questionID = qID AND R.roomID = rID;
    
    IF @valid THEN
		        
        
		SELECT Q.* 
		FROM Questions Q INNER JOIN Rooms R
			ON Q.roomID = R.roomID
		WHERE questionID = qID AND
			(r.dateStart < @today AND r.dateExpire > @today );
		

		SET err = 0;
        
    ELSEIF (@public = FALSE ) AND (uID = '') THEN
		
        SELECT Q.* 
		FROM Questions Q INNER JOIN Rooms R
			ON Q.roomID = R.roomID
		WHERE questionID = qID AND
			(r.dateStart < @today AND r.dateExpire > @today );
            
        SET err = 0;
	ELSE 
		
        SELECT *
		FROM Questions 
		WHERE 1=0;
        
		SET err = 2;
    END IF;
    
END