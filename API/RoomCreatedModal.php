<?php
	require("config.php");
	
	//	connection using the sql credentials
	$connection = new mysqli($serverIP, $serverUSER, $serverPASS, $serverDB, $serverPORT)
	or die('connection to server failed');
	//	Get JSON input
	$inData = json_decode(file_get_contents('php://input'), true);
	
	$userID = "";
	$sessionID = "";
	$roomID = "";

	if($connection->connect_error)
	{
		returnWithError(5, "Error Connecting to the Server");
	}
	else
	{
		$userID = mysqli_real_escape_string($connection, $inData["userID"]);
		$sessionKey = mysqli_real_escape_string($connection, $inData["sessionID"]);
		$roomID = mysqli_real_escape_string($connection, $inData["roomID"]);
		
		$call = 'CALL PollingZone.room_getAnalytics(
			   "' . $userID . '",
  			   "' . $sessionID . '",
  			   "' . $roomID . '"
                );';
				
		$result = $connection->query($call);
		
		if($result->num_rows == NULL || $result->num_rows == 0)
		{
			$sqlReport = $mysqli_error;
			if($result->num_rows == NULL)
				returnWithError(2, "Invalid User Credentials.", $sqlReport);
			
			returnWithError(1, "Failed to access stats or no stats for this poll.", $sqlReport);
		}
		else
		{
			//What am I getting?
		}
	}
	// Close the connection
	$connection->close();
?>
