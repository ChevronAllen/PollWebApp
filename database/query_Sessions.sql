SELECT S.session_key, U.*
FROM PollingZone.Sessions AS S 
	INNER JOIN Users AS U ON U.userID = S.userID;