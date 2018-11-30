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
    
    INSERT INTO UserGrades (userID, orgID,numCorrect,numWrong)
    SELECT U.userID,U.user_optionalName,(
				SELECT SUM(R2.selection = Q2.correctResponse) 
                FROM Responses AS R2 INNER JOIN  Questions AS Q2
                WHERE userID = U.userID AND Q2.correctResponse != 0
            ), (
				SELECT SUM(R2.selection != Q2.correctResponse) 
                FROM Responses AS R2 INNER JOIN  Questions AS Q2
                WHERE userID = U.userID AND Q2.correctResponse != 0
			)
            FROM Users AS U RIGHT JOIN Responses AS R
				ON U.userID = R.userID
			WHERE (userID IS NOT NULL) AND roomID = rID;
	
    INSERT INTO UserGrades (questionID ,percentCorrect)
    SELECT questionID, (
		SELECT SUM(R2.selection = Q2.correctResponse)/COUNT(Q2.questionID)
        FROM Questions AS Q2 RIGHT JOIN Responses AS R2
			ON Q2.questionID = R2.questionID
		WHERE Q2.questionID = Q.questionID
    ) FROM Questions AS Q
    WHERE correctResponse !=0;
	
    SELECT * FROM UserGrades;
    SELECT * FROM QuestionGrades;
	DROP TEMPORARY TABLE UserGrades, QuestionGrades;
END