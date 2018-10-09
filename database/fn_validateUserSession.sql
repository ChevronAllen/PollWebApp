CREATE DEFINER=`ermine`@`%` FUNCTION `fn_validateUserSession`(uID VARCHAR(32), uSession VARCHAR(32)) RETURNS tinyint(1)
BEGIN
/*
// 	Tests if the user's current session is still active
//	Returns true if the session is still open
//	Returns fasle if the session ID doesnt match, user id doesnt exist, or session is too old
*/	

	IF 	(
		SELECT COUNT(userID) 
        FROM Users 
        WHERE (userID = uID AND sessionID = uSession) AND (DATE_ADD(dateLastActive, INTERVAL 1 DAY) < NOW )
        ) > 0 
	THEN
		RETURN TRUE;	
    END IF;

RETURN FALSE;
END