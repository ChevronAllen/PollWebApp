CREATE DEFINER=`ermine`@`%` FUNCTION `fn_generatePollID`() RETURNS varchar(6) CHARSET latin1
BEGIN
DECLARE id VARCHAR(6);
    
    SET id = CONVERT( CONCAT(
		CHAR(65 + 25*rand()), 
        CHAR(65 + 25*rand()), 
        CHAR(65 + 25*rand()), 
        CHAR(65 + 25*rand()), 
        CHAR(65 + 25*rand()), 
        CHAR(65 + 25*rand())  
	) USING utf8);
    
    
    WHILE (SELECT COUNT(pollID) FROM PollsActive WHERE pollID = id) > 0 DO
		SET id = CONVERT( CONCAT(
			CHAR(65 + 25*rand()), 
			CHAR(65 + 25*rand()), 
			CHAR(65 + 25*rand()), 
			CHAR(65 + 25*rand()), 
			CHAR(65 + 25*rand()), 
			CHAR(65 + 25*rand())  
		) USING utf8);
    END WHILE;
    
    
RETURN id;
END