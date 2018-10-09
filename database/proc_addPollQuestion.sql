CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_addPollQuestion`(
	IN pID VARCHAR(6), 
    IN pQuestion TEXT, 
    IN pChoice1 TEXT,
    IN pChoice2 TEXT,
    IN pChoice3 TEXT,
    IN pChoice4 TEXT,
    IN pChoice5 TEXT,
    IN pChoice6 TEXT,
    IN pChoice7 TEXT,
    IN pChoice8 TEXT,
    IN pCorrect INT,
    IN uID VARCHAR(32), 
    IN uSession VARCHAR(32)
)
BEGIN
	
    
/* Test for valid user session, store the result */    
    DECLARE valid BOOLEAN DEFAULT FALSE;
    SELECT fn_validateUserSession(uID,uSession) INTO valid;
    
    SET @qNum = 0;
    SELECT countQuestions INTO @qNum
	FROM PollsActive
	WHERE pollID = pID
	LIMIT 1;
    
    
    IF EXISTS(
			SELECT COUNT(pollID) 
            FROM PollsActive 
            WHERE dateExpire < NOW
	)  THEN 		
    
		INSERT INTO PollQuestions (	pollID,
									questionNumber,
									questionText,
									responseText1,
									responseText2,
									responseText3,
									responseText4,
									responseText5,
									responseText6,
									responseText7,
									responseText8,
									correctResponse
		) VALUES (	pID,
					(@qNum + 1),
					pQuestion,
					pChoices1,
					pChoices2,
					pChoices3,
					pChoices4,
					pChoices5,
					pChoices6,
					pChoices7,
					pChoices8,
					pCorrect
		);
        
        UPDATE PollsActive
        SET countQuestions = (@qNum + 1)
        WHERE pollID = pID;
        
		CALL PollWebApp.proc_updateUserSession(uID,uSession);
    END IF;
    
	
    
    
END