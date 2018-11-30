CREATE DEFINER=`ermine`@`%` PROCEDURE `room_addQuestion`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rID VARCHAR(255),
    IN qText TEXT,
    IN qCorrect INT,
    IN qLocked VARCHAR(255),
    IN qChoice1 TINYTEXT,
    IN qChoice2 TINYTEXT,
    IN qChoice3 TINYTEXT,
    IN qChoice4 TINYTEXT,
    IN qChoice5 TINYTEXT,
    IN qChoice6 TINYTEXT,
    IN qChoice7 TINYTEXT,
    IN qChoice8 TINYTEXT,
    IN qChoice9 TINYTEXT,
    IN qChoice10 TINYTEXT,
    IN qChoice11 TINYTEXT,
    IN qChoice12 TINYTEXT,
    IN qChoice13 TINYTEXT,
    IN qChoice14 TINYTEXT,
    IN qChoice15 TINYTEXT,
    IN qChoice16 TINYTEXT
)
BEGIN
	SET @anonMax = 1;
    SET @roomOwner = '';
    SET @questionsCount = 0;
    SET @valid = fn_isValidSession(uID,uSession);
    SET @locked = (qLocked + 0);
    
    SELECT ownerID, roomSize
    INTO @roomOwner, @questionCount
    FROM Rooms
    WHERE roomID = rID;
    
    #	Test if  Room can be Edited
    IF (@roomOwner IS NULL) AND (@questionsCount < @anonMax) THEN

        INSERT INTO `PollingZone`.`Questions`(	`questionID`,
												`roomID`,
                                                `correctResponse`,                                                
												`questionText`,
												`choice1`,
												`choice2`,
												`choice3`,
												`choice4`,
												`choice5`,
												`choice6`,
												`choice7`,
												`choice8`)
										VALUES(	CONCAT(@rID, (@questionCount+1)),
												@rID,
                                                qCorrect,
                                                @locked,
												qText,
												qChoice1,
												qChoice2,
												qChoice3,
												qChoice4,
												qChoice5,
												qChoice6,
												qChoice7,
												qChoice8);
		
    UPDATE Rooms
    SET roomSize = (@questionsCount+1) 
    WHERE roomID = rID;
    
    SELECT *
    FROM Questions
    WHERE roomID = rID;
    
        
	ELSEIF (@roomOwner = uID) AND @valid THEN
		
        
        
        INSERT INTO `PollingZone`.`Questions`(	`questionID`,
												`roomID`,
												`userID`,
                                                `correctResponse`,
                                                `isLocked`,
												`questionText`,
												`choice1`,
												`choice2`,
												`choice3`,
												`choice4`,
												`choice5`,
												`choice6`,
												`choice7`,
												`choice8`,
												`choice9`,
												`choice10`,
												`choice11`,
												`choice12`,
												`choice13`,
												`choice14`,
												`choice15`,
												`choice16`)
										VALUES(	CONCAT(@rID, @questionCount+1),
												@rID,
												uID,
                                                qCorrect,
                                                @locked,
												qText,
												qChoice1,
												qChoice2,
												qChoice3,
												qChoice4,
												qChoice5,
												qChoice6,
												qChoice7,
												qChoice8,
												qChoice9,
												qChoice10,
												qChoice11,
												qChoice12,
												qChoice13,
												qChoice14,
												qChoice15,
												qChoice16);
        UPDATE Rooms
		SET roomSize = (@questionsCount+1) 
		WHERE roomID = rID;
        
        SELECT *
		FROM Questions
		WHERE roomID = rID;
        
    END IF;
    
    
END