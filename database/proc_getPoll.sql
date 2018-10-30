DELIMITER $$
CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_getPoll`(IN pCode VARCHAR(6), IN uID VARCHAR(32), IN uSession VARCHAR(32), INOUT err VARCHAR(255))
BEGIN

	IF EXISTS (
		SELECT public FROM PollsActive
        WHERE  pollCode = pCode
	) AND  NOT (pCode = '' OR pCode IS NULL) THEN
		
        SET @visible = (SELECT public FROM PollsActive WHERE  pollCode = pCode );
        SET @validUser =  fn_validateUserSession(uID,uSession);
        SET @pID = (SELECT pollID FROM PollsActive WHERE  pollCode = pCode );
        
        SET @qPoll = CONCAT('SELECT * FROM PollWebAppData.',@pID,'_Format ');
		PREPARE stmt FROM @qPoll;
        
        IF @visible THEN
				
               EXECUTE stmt;
        ELSE
		
			IF @valid THEN
				EXECUTE stmt;
            END IF;
        END IF;
        
		DEALLOCATE PREPARE stmt;
		-- CALL PollWebApp.proc_updateUserSession(uID,uSession);
    END IF;
        
    
    
END$$
DELIMITER ;
