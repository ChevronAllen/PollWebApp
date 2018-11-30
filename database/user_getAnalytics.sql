CREATE DEFINER=`ermine`@`%` PROCEDURE `user_getAnalytics`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255)
)
BEGIN
	
    SELECT 	Q.questionID, 
			(R.selection = Q.correctResponse) AS `correct`, 
            R.selection AS `userResponse` , 
            Q.correctResponse AS `correctResponse`
    FROM Questions AS Q INNER JOIN Responses AS R
		ON Q.questionID = R.questionID
	WHERE R.userID = uID AND Q.roomID = rID
    ORDER BY Q.questionID ASC;
    
END