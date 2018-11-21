CREATE DEFINER=`ermine`@`%` PROCEDURE `room_create`(
	IN uID VARCHAR(255),
    IN uSession VARCHAR(255),
    IN rTitle TINYTEXT,
    IN rPublic BOOLEAN,
    IN rStart VARCHAR(255),
    IN rExpire VARCHAR(255),
    OUT err INT
)
BEGIN
	SET @rCode = fn_generateRoomCode();
    SET @today = NOW();
    #SET @rID = CONCAT(@rCode, DATE_FORMAT(@today,"%Y%m%d%H%i%s") );
    SET @rID = MD5(NOW());
    SET @startDate = NULL;
    SET @endDate = NULL;
    
    IF NOT(rStart = '') THEN
		SET @startDate = STR_TO_DATE(rStart,"%Y%m%d%H%i%s");
	ELSE
		SET @startDate = @today;
    END IF;
    
    IF NOT (rExpire = '') THEN
		SET @endDate = STR_TO_DATE(rExpire,"%Y%m%d%H%i%s");
	ELSE
		SET @endDate = DATE_ADD(@today, INTERVAL 1 DAY);
    END IF;
    
    
    # Anonymous User - Room Creation
    IF ((uID = '' || uID IS NULL ) OR (uSession = '' || uSession IS NULL )) THEN

    
        INSERT INTO PollingZone.Rooms (roomID, roomCode, ownerID, roomPublic, roomTitle, dateStart,dateExpire, dateLastUpdated)
        VALUES (
			@rID,
            @rCode,
            NULL,
            TRUE,
            rTitle,
            @today,
            DATE_ADD(@today,INTERVAL 1 DAY),
            @today
        );
        
        SELECT @rCode AS `RoomCode`, @rID AS `RoomID`;
        SET @err = 0;
        
	#	Registered User	- Room Creation
	ELSE
		

        SET @validUser = fn_isValidSession(uID,uSession);
        
        
        IF (@validUser) THEN
		# Valid user	
            INSERT INTO PollingZone.Rooms (roomID, roomCode, ownerID, roomPublic, roomTitle, dateStart, dateExpire, dateLastUpdated)
			VALUES (
				@rID,
				@rCode,
				uID,
				rPublic,
				rTitle,
                @startDate,
				@endDate,
				@today
			);
            
            SELECT @rCode AS `RoomCode`, @rID AS `RoomID`;
            SET @err = 0;
		ELSE
        # Invalid user / invalid session
			SET @err = 1;
        END IF;

    END IF;
    
    

    
END