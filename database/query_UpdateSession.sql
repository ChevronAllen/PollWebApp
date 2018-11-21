SELECT U.userID, S.session_key INTO @uID, @uSession
FROM Users AS U JOIN Sessions AS S 
	ON U.userID = S.userID
WHERE user_email = 'Larry@Larry.com';


CALL `PollingZone`.`session_update`(@uID, @uSession);