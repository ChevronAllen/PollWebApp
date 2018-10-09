DELIMITER $$
CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_createUser`(
    IN uEmail varchar(255), 
    IN fName varchar(255),
    IN lName varchar(255),
    IN userAuth varchar(255) 
)
BEGIN
	DECLARE id INT default NULL;
      
	-- Inserts new user
	IF NOT EXISTS(
		SELECT userEmail 
        FROM PollWebApp.Users 
        WHERE Users.userEmail = uEmail
	) THEN 
		INSERT INTO PollWebApp.Users (userID, email, firstName, lastName, userAuth)
			SELECT MD5(uEmail), uEmail,fName, lName, userAuth;	
			
		-- Returns users polls
		SELECT p.* 
        FROM Users as u INNER JOIN PollsActive as p
			ON u.userID = p.ownerID  
		WHERE u.userEmail = uEmail;
    ELSE
		-- Returns empty table
		SELECT * FROM PollsActive WHERE 1=0;
    END IF;
    
END$$
DELIMITER ;
