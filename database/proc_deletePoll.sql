DELIMITER $$
CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_deletePoll`(IN pID VARCHAR(255) ,IN err VARCHAR(255))
BEGIN
	
    IF EXISTS(
		SELECT pollID FROM PollsActive
        WHERE pollID = pID
    ) THEN
		SET @formatTableLong 	= CONCAT( 'PollWebAppData.', pID, '_Format');
        SET @dataTableLong 		= CONCAT( 'PollWebAppData.', pID, '_Data'  );
        SET @formatTable 		= CONCAT( pID, '_Format');
        SET @dataTable 			= CONCAT( pID, '_Data'  );
		
        IF EXISTS(
			SELECT TABLE_NAME   
			FROM information_schema.tables 
			WHERE table_schema ='PollWebAppData' AND table_name = @formatTable
		) THEN
        
			/* Remove Generated tables */
			SET @qFormat = CONCAT(
									'DROP TABLE ',
                                    @formatTableLong, ';'
			);
            
            SET @qPoll   = CONCAT(
									'DROP TABLE ',
                                    @dataTableLong, ';'
			);
			
			PREPARE sFormat FROM @qFormat;
            PREPARE sPoll FROM @qPoll;
            
            EXECUTE sFormat;
            EXECUTE sPoll;
						
			/* Remove Active Table Entry */
            
            DELETE FROM PollWebApp.PollsActive
            WHERE pollID = pID;
		ELSE
			SELECT '404: Object not Found' INTO err;            
		END IF;
        
		SELECT '404: Object not Found' INTO err;
    END IF;
END$$
DELIMITER ;
