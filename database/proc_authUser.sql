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
		WHERE (userEmail = uName OR username = uName) && (userAuth = uAuth);

		SELECT pollID, pollName, dateCreated, dateExpire, countQuestions 
		FROM PollsActive
		WHERE PollsActive.ownerID = @id;
        
        SELECT md5(NOW) into uSessionID;
		CALL proc_updateUserSession(@id, uSessionID);
	END IF;
END$$
DELIMITER ;
