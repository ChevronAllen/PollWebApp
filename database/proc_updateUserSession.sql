DELIMITER $$
CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_updateUserSession`(IN uID VARCHAR(32),IN uSession VARCHAR(32))
BEGIN
	

	IF NOT ( (uID IS NULL OR uID = "") OR (uSession IS NULL OR uSession = "") ) THEN
		IF (fn_validateUserSession(uID,uSession)) = TRUE THEN
			UPDATE Users 
			SET dateLastActive = NOW
			WHERE userID = uID;
        END IF;
    END IF;
END$$
DELIMITER ;
