CREATE DEFINER=`ermine`@`%` PROCEDURE `room_getAnalytics_users`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255)
)
BEGIN
	DROP TEMPORARY TABLE IF EXISTS UserGrades;

	CREATE TEMPORARY TABLE UserGrades (	
										userID VARCHAR(255), 
										orgID VARCHAR(255), 
                                        numCorrect VARCHAR(255),
                                        numWrong VARCHAR(255)
									);
    
    INSERT INTO UserGrades (userID, orgID,numCorrect,numWrong)
    SELECT Distinct U.userID,U.user_optionalName,(
				SELECT SUM( R2.selection = Q2.correctResponse ) 
                FROM Responses AS R2 LEFT OUTER JOIN  Questions AS Q2
					ON R2.questionID = Q2.questionID
                WHERE R2.userID = U.userID AND Q2.correctResponse != 0
            ), (
				SELECT SUM(R2.selection != Q2.correctResponse) 
                FROM Responses AS R2 LEFT OUTER JOIN  Questions AS Q2
					ON R2.questionID = Q2.questionID
                WHERE R2.userID = U.userID AND Q2.correctResponse != 0
			)
		FROM Users AS U INNER JOIN Responses AS R
			ON U.userID = R.userID
		WHERE (U.userID IS NOT NULL) AND roomID = rID
        GROUP BY U.userID;
	
	
    SELECT * FROM UserGrades;

	DROP TEMPORARY TABLE UserGrades;
END