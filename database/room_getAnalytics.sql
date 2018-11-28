CREATE DEFINER=`ermine`@`%` PROCEDURE `room_getAnalytics`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255)
)
BEGIN

	CREATE TEMPORARY TABLE UserGrades (	
										userID VARCHAR(255), 
										orgID VARCHAR(255), 
                                        numCorrect VARCHAR(255),
                                        numWrong VARCHAR(255)
									);
                                    
	CREATE TEMPORARY TABLE QuestionGrades (	
											questionID VARCHAR(255), 
											percentCorrect DOUBLE
									);
    

	DROP TEMPORARY TABLE UserGrades, QuestionGrades;
END