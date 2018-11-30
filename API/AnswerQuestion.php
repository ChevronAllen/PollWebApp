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
	$questionID = "";
	$choice = -1;

	if($connection->connect_error)
	{
		returnWithError(5, "Error Connecting to the Server");
	}
	else
	{
		$userID = mysqli_real_escape_string($connection, $inData["userID"]);
		$sessionID = mysqli_real_escape_string($connection, $inData["sessionID"]);
		$roomID = mysqli_real_escape_string($connection, $inData["roomID"]);
		$questionID = mysqli_real_escape_string($connection, $inData["questionID"]);
		$choice = mysqli_real_escape_string($connection, $inData["choice"]);

		$call = 'CALL PollingZone.????(
			   "' . $userID . '",
  			   "' . $sessionID . '",
  			   "' . $roomID . '",
  			   "' . $questionID . '",
  			   "' . $choice . '"
                );';

		$result = $connection->query($call);
		if($result->num_rows == NULL || $result->num_rows == 0)
		{
			$sqlReport = $mysqli_error;
			if($result->num_rows == NULL)
				returnWithError(1, "Invalid User Credentials.";

			returnWithError(2, "Failed to answer question.");
		}
		else
		{
			$row = $result->fetch_assoc();
			$correctChoice = $row["correctChoice"];

			returnWithInfo($correctChoice);
		}

	}
	// Close the connection
	$connection->close();

  function returnWithInfo($correctChoice_)
  {
	  $retValue = createJSONString($correctChoice_, "", 0);
	  sendResultInfoAsJson( $retValue );
  }

  function createJSONString($correctChoice_, $error_, $errCode)
  {
	$ret = '
        {
          "correctChoice" : '. $correctChoice_ .' ,
          "error" : "' . $error_ . '",
	  "errorCode" : "' . $errCode . '"
        }';
	return $ret;
  }

  function sendResultInfoAsJson( $obj )
  {
	header('Content-type: application/json');
	echo $obj;
  }

  function returnWithError($errCode, $err)
  {
	$retValue = createJSONString("",$err, $errCode);
	sendResultInfoAsJson( $retValue );
  }
?>
