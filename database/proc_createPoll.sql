DELIMITER $$
CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_createPoll`(IN pTitle TEXT, IN pExpire VARCHAR(255), IN uID VARCHAR(32), IN uSession VARCHAR(32))
BEGIN
/*
//	Creates a poll entry in the PollsActive Table
//	Returns the created polls ID; If this fails the pollID will be an empty string
*/

	SET @pID = '';
    SET @pCode = fn_generatePollID();    
    SET @valid =FALSE;
    SELECT fn_validateUserSession(uID,uSession) INTO @valid;
	
    IF uID IS NULL THEN
		/*Anonymous Poll*/
        CALL PollWebApp.proc_createPollSubTables(@pCode, @pID);
        
		INSERT INTO PollsActive  (pollID, pollCode, ownerID, pollName, dateExpire)
			VALUES (@pID, @pCode, NULL, pTitle, DATE_ADD(NOW(), INTERVAL 1 DAY));
        SELECT @pCode AS 'pollID';
	ELSE 
		/*Registered User Poll*/
		IF ( @valid = TRUE ) THEN
			
            CALL PollWebApp.proc_createPollSubTables(@pCode, @pID);

            INSERT INTO PollsActive  (pollID, pollCode, ownerID, pollName,dateExpire)
			VALUES (@pID, @pCode, uID, pTitle, pExpire);
            
            CALL proc_updateUserSession(uID, uSession);
            SELECT @pCode AS 'pollID';
            
		else
			SELECT 'FAILED';
        END IF;
    END IF;


END$$
DELIMITER ;
