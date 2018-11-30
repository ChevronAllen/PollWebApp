SET @uID = 'e1bb0c6df90e764ce03931859bd6dadf';
SET @sessionKey = (SELECT session_key FROM Sessions WHERE userID = @uID);
SET @rID = '001e01e483e829340585fb2bff9fa651';

CALL `PollingZone`.`room_getAnalytics`(@uID, @sessionKey, @rID);
