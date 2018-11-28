CREATE DEFINER=`ermine`@`%` PROCEDURE `user_getPollsOwned`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255)
)
BEGIN

	SET @valid = fn_isValidSession(uID, uSession);
    IF @valid THEN
		SELECT * FROM Rooms
		WHERE ownerID = uID;
	ELSE 
		SELECT * FROM Rooms
        WHERE 1=0;
    END IF;
END