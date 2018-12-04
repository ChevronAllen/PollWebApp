<?php
	require("config.php");

	//	connection using the sql credentials
	$connection = new mysqli($serverIP, $serverUSER, $serverPASS, $serverDB, $serverPORT)
	or die('connection to server failed');
	//	Get JSON input
	$inData = json_decode(file_get_contents('php://input'), true);

	$editOption = -1;
	$userID = "";
	$sessionID = "";
	$roomID = "";
	$questionID = "";
	$isLocked = -1;
	$correctResponse = -1;
	$questionText = "";
	$choice1 = "";
	$choice2 = "";
	$choice3 = "";
	$choice4 = "";
	$choice5 = "";
	$choice6 = "";
	$choice7 = "";
	$choice8 = "";
	$choice9 = "";
	$choice10 = "";
	$choice11 = "";
	$choice12 = "";
	$choice13 = "";
	$choice14 = "";
	$choice15 = "";
	$choice16 = "";

	if($connection->connect_error)
	{
		returnWithError(1, "Error Connecting to the Server");
	}
	else
	{
		$editOption = mysqli_real_escape_string($connection, $inData["editOption"]);
		$userID = mysqli_real_escape_string($connection, $inData["userID"]);
		$sessionID = mysqli_real_escape_string($connection, $inData["sessionID"]);
		$roomID = mysqli_real_escape_string($connection, $inData["roomID"]);
		$questionID = mysqli_real_escape_string($connection, $inData["questionID"]);
		$isLocked = mysqli_real_escape_string($connection, $inData["isLocked"]);
		$correctResponse = mysqli_real_escape_string($connection, $inData["correctResponse"]);
		$questionText = mysqli_real_escape_string($connection, $inData["questionText"]);
		$choice1 = mysqli_real_escape_string($connection, $inData["choice1"]);
		$choice2 = mysqli_real_escape_string($connection, $inData["choice2"]);
		$choice3 = mysqli_real_escape_string($connection, $inData["choice3"]);
		$choice4 = mysqli_real_escape_string($connection, $inData["choice4"]);
		$choice5 = mysqli_real_escape_string($connection, $inData["choice5"]);
		$choice6 = mysqli_real_escape_string($connection, $inData["choice6"]);
		$choice7 = mysqli_real_escape_string($connection, $inData["choice7"]);
		$choice8 = mysqli_real_escape_string($connection, $inData["choice8"]);
		$choice9 = mysqli_real_escape_string($connection, $inData["choice9"]);
		$choice10 = mysqli_real_escape_string($connection, $inData["choice10"]);
		$choice11 = mysqli_real_escape_string($connection, $inData["choice11"]);
		$choice12 = mysqli_real_escape_string($connection, $inData["choice12"]);
		$choice13 = mysqli_real_escape_string($connection, $inData["choice13"]);
		$choice14 = mysqli_real_escape_string($connection, $inData["choice14"]);
		$choice15 = mysqli_real_escape_string($connection, $inData["choice15"]);
		$choice16 = mysqli_real_escape_string($connection, $inData["choice16"]);

		if($editOption == 0)
		{
			$call = 'CALL PollingZone.room_deleteQuestion(
					"' . $userID . '",
					"' . $sessionID . '",
					"' . $questionID. '"
					);';

			$result = $connection->query($call);

			if($result->num_rows == NULL)
			{
					returnWithError(2, "Invalid User Credentials.");
          exit();
			}
      else if
      {
        returnWithError(3, "Question doesn't exist.");
        exit();
      }
			else
				returnWithError(0, "");
		}
		else if($editOption == 1)
		{
			$call = 'CALL PollingZone.room_addQuestion(
					"' . $userID . '",
					"' . $sessionID . '",
					"' . $roomID . '",
					"' . $questionText . '",
					"' . $correctResponse . '",
					"' . $isLocked . '",
					"' . $choice1 . '",
				    "' . $choice2 . '",
				    "' . $choice3 . '",
				    "' . $choice4 . '",
				    "' . $choice5 . '",
				    "' . $choice6 . '",
				    "' . $choice7 . '",
				    "' . $choice8 . '",
				    "' . $choice9 . '",
				    "' . $choice10 . '",
				    "' . $choice11 . '",
				    "' . $choice12 . '",
				    "' . $choice13 . '",
				    "' . $choice14 . '",
				    "' . $choice15 . '",
				    "' . $choice16 . '"
					);';

			$result = $connection->query($call);

			 if ($result == NULL)
			{
				returnWithError(4, "Failed to add question result was null");
				exit();
			}
			elseif($result->num_rows == 0)
			{
				returnWithError(5, "Failed to add question result had no rows");
				exit();
			}
			else
				returnWithError(0, "");
		}
		else if($editOption == 2)
		{
			$call = 'CALL PollingZone.room_deleteQuestion(
					"' . $userID . '",
					"' . $sessionID . '",
					"' . $questionID. '"
					);';

			$result = $connection->query($call);

			if($result == NULL)
			{
					returnWithError(2, "Invalid User Credentials.");
          exit();
			}
      else if($result->num_rows == 0)
      {
        returnWithError(3, "Question doesn't exist.");
        exit();
      }
			else
				returnWithError(0, "");

			$result->free();

			$connection->next_result();

			$call = 'CALL PollingZone.room_addQuestion(
					"' . $userID . '",
					"' . $sessionID . '",
					"' . $roomID . '",
					"' . $questionText . '",
					"' . $correctResponse . '",
					"' . $isLocked . '",
					"' . $choice1 . '",
				    "' . $choice2 . '",
				    "' . $choice3 . '",
				    "' . $choice4 . '",
				    "' . $choice5 . '",
				    "' . $choice6 . '",
				    "' . $choice7 . '",
				    "' . $choice8 . '",
				    "' . $choice9 . '",
				    "' . $choice10 . '",
				    "' . $choice11 . '",
				    "' . $choice12 . '",
				    "' . $choice13 . '",
				    "' . $choice14 . '",
				    "' . $choice15 . '",
				    "' . $choice16 . '"
					);';

			$result = $connection->query($call);

			 if ($result == NULL)
			{
				returnWithError(6, "Failed to edit result was null");
				exit();
			}
			elseif($result->num_rows == 0)
			{
				returnWithError(7, "Failed to edit result had no rows");
				exit();
			}
			else
				returnWithError(0, "");

		}
		else if($editOption == 3)
		{
			$call = 'CALL PollingZone.room_QuestionLock(
			   "' . $userID . '",
  			   "' . $sessionID . '",
  			   "' . $roomID . '",
			   "' . $questionID . '",
			   "' . $isLocked . '"
                );';

			$result = $connection->query($call);

			if($result == NULL)
			{
					returnWithError(2, "Invalid User Credentials.");
			    exit();
			}
      else if($result->num_rows == 0)
      {
        returnWithError(8, "Failed to lock question.");
        exit();
      }
      else
      {
	      returnWithError(0, "");
      }
     }
     else
     {
	     returnWithError(9, "Option not supported");
     }
	
}
$connection->close();

  function returnWithError($errCode, $err )
  {
    $retValue = createJSONString($err, $errCode);
    sendResultInfoAsJson( $retValue );
  }

  function createJSONString($error_, $errCode_)
  {
		$ret = '
        {
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
