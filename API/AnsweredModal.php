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
	
    class QuestionResult 
	{
      function __construct() 
	  {
		$this->questionID = "";
		$this->userResponse = "";
		$this->correctResponse = "";
		$this->correct = "";
      }
	}

	if($connection->connect_error)
	{
		returnWithError(1, "Error Connecting to the Server");
	}
	else
	{
		$userID = mysqli_real_escape_string($connection, $inData["userID"]);
		$sessionKey = mysqli_real_escape_string($connection, $inData["sessionID"]);
		$roomID = mysqli_real_escape_string($connection, $inData["roomID"]);

		$call = 'CALL PollingZone.user_getAnalytics(
			   "' . $userID . '",
  			   "' . $sessionID . '",
  			   "' . $roomID . '"
                );';

		$result = $connection->query($call);

		if($result->num_rows == NULL || $result->num_rows == 0)
		{
			if($result->num_rows == NULL)
				returnWithError(2, "Invalid User Credentials.");

			returnWithError(3, "Failed to access stats or no stats for this poll.");
		}
		else
		{
			$roomResult = array();
			while($row = $result->fetch_assoc();)
			{
				$question = new QuestionResult();
				$question->questionID = $row["questionID"];
				$question->userResponse = $row["userResponse"];
				$question->correctResponse = $row["correctResponse"];
				$question->correct = $row["correct"];
				
				$roomResult[] = $question;
			}
			
			sendWithInfo(json_encode($roomResult));
		}
	}
	// Close the connection
	$connection->close();
	
  function returnWithError($errCode, $err )
  {
    $retValue = createJSONString("",$err, $errCode);
    sendResultInfoAsJson( $retValue );
  }
  
  function returnWithInfo($roomResult_)
  {
	  $retValue = createJSONString($roomResult_, "", 0);
	  sendResultInfoAsJson( $retValue );
  }
  
  function createJSONString($roomResult_, $error_, $errCode_)
  {
		$ret = '
        {
		  "roomResult" : "' . $roomResult_ . '",
          "error" : "' . $error_ . '",
		  "errorCode" : "' . $errCode_ . '"
        }';
    return $ret;
  }
  
  function sendResultInfoAsJson( $obj )
  {
    header('Content-type: application/json');
    echo $obj;
  }
?>
