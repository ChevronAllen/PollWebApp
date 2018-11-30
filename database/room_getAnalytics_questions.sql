CREATE DEFINER=`ermine`@`%` PROCEDURE `room_getAnalytics_questions`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255)
)
BEGIN


    DROP TEMPORARY TABLE IF EXISTS QuestionGrades;
                                    
	CREATE TEMPORARY TABLE QuestionGrades (	
											questionID VARCHAR(255), 
											percentCorrect DOUBLE
									);
    
    INSERT INTO QuestionGrades (questionID ,percentCorrect)
    SELECT Q.questionID, (
		SELECT  SUM(R2.selection = Q2.correctResponse) / COUNT(Q2.correctResponse)
        FROM Questions AS Q2 LEFT OUTER JOIN Responses AS R2
			ON Q2.questionID = R2.questionID
		WHERE Q2.questionID = Q.questionID 
    ) FROM Questions AS Q
    WHERE Q.correctResponse !=0 AND Q.roomID = rID;
	
    SELECT * FROM QuestionGrades;
	DROP TEMPORARY TABLE QuestionGrades;
END