CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_getPoll`(IN pID VARCHAR(6))
BEGIN

	SELECT ownerID, pollName, dateExpire, countQuestions 
    FROM PollsActive
    WHERE pollID = pID;
    
    SELECT questionNumber, questionText, 	responseText1,
											responseText2,
                                            responseText3,
                                            responseText4,
                                            responseText5,
                                            responseText6,
                                            responseText7,
                                            responseText8
    FROM PollQuestions
    WHERE pollID = pID
    ORDER BY questionNumber ASC;
    
    CALL PollWebApp.proc_updateUserSession(uID,uSession);
END