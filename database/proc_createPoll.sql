DELIMITER $$
CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_createPoll`(IN pTitle TEXT, IN uID VARCHAR(32), IN uSession VARCHAR(32))
BEGIN
/*
//	Creates a poll entry in the PollsActive Table
//	Returns the created polls ID; If this fails the pollID will be an empty string
*/


    SET @pID = fn_generatePollID();    
    SET @valid = fn_validateUserSession(uID,uSession);
    
	
    IF ((@uID = "") OR (@uID IS NULL)) THEN
		/*Anonymous Poll*/
		INSERT INTO PollsActive  (pollID, ownerID, pollName)
		VALUES (pID, NULL, pTitle);
        SELECT pID AS 'pollID';
	ELSE 
		/*Registered User Poll*/
		IF ( @valid = TRUE ) THEN
			INSERT INTO PollsActive  (pollID, ownerID, pollName)
			VALUES (pID, uID, pTitle);
            
            SET @valid = fn_updateUserSession();
            SELECT pID AS 'pollID';
        END IF;
    END IF;


END$$
DELIMITER ;
