DELIMITER $$
CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_addPollQuestion`(
	IN pCode VARCHAR(6), 
    IN pQuestion TEXT, 
    IN pChoice1 TEXT,
    IN pChoice2 TEXT,
    IN pChoice3 TEXT,
    IN pChoice4 TEXT,
    IN pChoice5 TEXT,
    IN pChoice6 TEXT,
    IN pChoice7 TEXT,
    IN pChoice8 TEXT,
    IN pChoice9 TEXT,
    IN pChoice10 TEXT,
    IN pChoice11 TEXT,
    IN pChoice12 TEXT,
    IN pChoice13 TEXT,
    IN pChoice14 TEXT,
    IN pChoice15 TEXT,
    IN pChoice16 TEXT,
    IN pCorrect INT,
    IN uID VARCHAR(32), 
    IN uSession VARCHAR(32),
    INOUT err VARCHAR(255)
)
BEGIN
	    
	/* Test for valid user session, store the result */    
    SET @valid = fn_validateUserSession(uID,uSession);    	
    
    /* Get pollID*/
    SET @pID = '';
    SELECT pollID into @pID
    FROM PollsActive
    WHERE pollCode = pCode
    LIMIT 1;
    
    /* Get number of questions*/
    SET @num = '';
    SELECT countQuestions into @num
    FROM PollsActive
    WHERE pollID = @pID
    LIMIT 1;
    
    
    /* If the poll exists and is visible */
    IF EXISTS(
			SELECT pollID 
            FROM PollsActive 
            WHERE (dateExpire > NOW()) AND (publicVisible = TRUE)
	) THEN 		
		
		/* Set format Table name Variable*/
        SET @formatTable 	= CONCAT('PollWebAppData.'	, @pID 	, '_Format');
		SET @formatTableShort = CONCAT( @pID 	, '_Format');
        /* If the format table exists */
		IF (
			SELECT count(*)   
			FROM information_schema.tables 
			WHERE table_schema ='PollWebAppData' AND table_name = @formatTableShort
		) > 0 THEN        
			
            /* Anonymous Check */
            IF uID IS NULL THEN
				
                /* If Anonymous, check if reached max question limit for anonymous users*/
                IF @Num < 1 THEN
					SET @qFormat 	= 
						CONCAT( 
							'INSERT INTO '	, 
							@formatTable	, 
							' ( questionText,
								responseText1 ,
								responseText2 ,
								responseText3 ,
								responseText4 ,
								responseText5 ,
								responseText6 ,
								responseText7 ,
								responseText8 ,
								correctResponse
							) VALUES (', 	pQuestion, 	'\',\'', 
											pChoice1,	'\',\'',
											pChoice2,	'\',\'',
											pChoice3,	'\',\'',
											pChoice4,	'\',\'',
											pChoice5,	'\',\'',
											pChoice6,	'\',\'',
											pChoice7,	'\',\'',
											pChoice8,	'\',\'',
											pCorrect,	'\');'
						);
                    
                    PREPARE stmtf FROM @qFormat;    
					EXECUTE stmtf;					
					
                    
                    UPDATE PollWebApp.PollsActive
                    SET countQuestions = countQuestions + 1
                    WHERE pollID = @pID;
                    
					DEALLOCATE PREPARE stmtf;
                    
                   
				ELSE
					SELECT '430: Cannot Add Questions. Maximum number of anon questions reached.' into err;
                END IF;
			ELSE

				/* Test user Session */
				IF @valid THEN
					
					SET @qFormat 	= 
						CONCAT( 
							'INSERT INTO '	, @formatTable, 
								' ( questionText,
									responseText1 ,
									responseText2 ,
									responseText3 ,
									responseText4 ,
									responseText5 ,
									responseText6 ,
									responseText7 ,
									responseText8 ,
                                    responseText9 ,
                                    responseText10 ,
                                    responseText11 ,
                                    responseText12,
                                    responseText13 ,
                                    responseText14 ,
                                    responseText15 ,
                                    responseText16 ,
									correctResponse
								) VALUES ( \'',	pQuestion, 	'\',\'', 
												pChoice1,	'\',\'',
												pChoice2,	'\',\'',
												pChoice3,	'\',\'',
												pChoice4,	'\',\'',
												pChoice5,	'\',\'',
												pChoice6,	'\',\'',
												pChoice7,	'\',\'',
												pChoice8,	'\',\'',
												pChoice9,	'\',\'',
												pChoice10,	'\',\'',
												pChoice11,	'\',\'',
												pChoice12,	'\',\'',
												pChoice13,	'\',\'',
												pChoice14,	'\',\'',
												pChoice15,	'\',\'',
												pChoice16,	'\',\'',
												pCorrect,	'\');' 
						);
					

                    PREPARE stmtf FROM @qFormat;
                    EXECUTE stmtf;
                    
                    UPDATE PollWebApp.PollsActive
                    SET countQuestions = countQuestions + 1
                    WHERE pollID = @pID;
                    
                    DEALLOCATE PREPARE stmtf;
                    
                    /* TODO: Max questions implementation*/
                    
				ELSE
					
                    SELECT 'error 410: Invalid User session' into err;
					
                END IF;
            END IF;
            
            
           
            
        END IF;
    END IF;
END$$
DELIMITER ;
