DELIMITER $$
CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_authUser`(IN uName VARCHAR(255), IN uAuth VARCHAR(255), OUT uSessionID VARCHAR(32))
BEGIN
/*
//	Authenticates a user. 
//	Allows either email or username to be paired to the password
//	Returns session ID via output parameter as an MD5 hash of the server clock
//	Returns users created polls 
*/	

    SET @id = -1; 
       
    IF NOT ( 
		(uName IS NULL OR uName = "") 
        OR (uAuth IS NULL OR uAuth = "") 
        -- OR (uSessionID IS NULL OR uSessionID = "")
    ) THEN
    	
        SELECT userID into @id 
        FROM PollWebApp.Users 
		WHERE (userEmail = uName) AND (userAuth = uAuth)
        LIMIT 1;
	
		SELECT pollID, pollName, dateCreated, dateExpire, countQuestions 
		FROM PollWebApp.PollsActive
		WHERE PollsActive.ownerID = @id;
        
        SELECT md5(CURRENT_TIMESTAMP()) into uSessionID;
        
		UPDATE Users 
			SET dateLastActive = CURRENT_TIMESTAMP, sessionID = uSessionID
			WHERE (userID = @id);
	END IF;
END$$
DELIMITER ;
