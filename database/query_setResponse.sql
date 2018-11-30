SET @uID = '1df66fbb427ff7e64ac46af29cc74b71';
SET @sessionKey = (SELECT session_key FROM Sessions WHERE userID = @uID);
SET @rID = '001e01e483e829340585fb2bff9fa651';

#SELECT fn_isValidSession(@uID,@sessionKey);

call PollingZone.room_setResponse(@uID, @sessionKey, @rID, '001e01e483e829340585fb2bff9fa6511', 3);
call PollingZone.room_setResponse(@uID, @sessionKey, @rID, '001e01e483e829340585fb2bff9fa6512', 2);
call PollingZone.room_setResponse(@uID, @sessionKey, @rID, '001e01e483e829340585fb2bff9fa6513', 1);
