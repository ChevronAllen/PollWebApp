<?php
	require("config.php");

    class RoomQuestionAnal
	{
      function __construct()
	  {
		$this->questionID = "";
		$this->percentCorrect = "";
      }
	}

	class userAnal
	{
	  function __construct()
	  {
		$this->userID = "";
		$this->orgID = "";
		$this->numCorrect = "";
		$this->numWrong = "";
	  }
	}

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
		returnWithError(1, "Error Connecting to the Server");
	}
	else
	{
		$userID = mysqli_real_escape_string($connection, $inData["userID"]);
		$sessionID = mysqli_real_escape_string($connection, $inData["sessionID"]);
		$roomID = mysqli_real_escape_string($connection, $inData["roomID"]);

		$call = 'CALL PollingZone.room_getAnalytics_questions(
			   "' . $userID . '",
  			   "' . $sessionID . '",
  			   "' . $roomID . '"
                );';

		$result = $connection->query($call);

		if($result == NULL)
		{
				returnWithError(2, "Invalid User Credentials.");
				exit();
		}
		else if($result->num_rows == 0)
		{
			returnWithError(3, "Failed to access question grades for this room.");
			exit();
		}
		else
		{
			$roomQuestions = array();
			while($row = $result->fetch_assoc())
			{
				$question = new RoomQuestionAnal();
				$question->questionID = $row["questionID"];
				$question->percentCorrect = $row["percentCorrect"];

				$roomQuestions[] = $question;
			}
			$result->free();
		}

		$connection->next_result();

		$call = 'CALL PollingZone.room_getAnalytics_users(
			   "' . $userID . '",
  			   "' . $sessionID . '",
  			   "' . $roomID . '"
                );';

		$result = $connection->query($call);

		if($result->num_rows == NULL)
		{
				returnWithError(2, "Invalid User Credentials.");
				exit();
		}
		else if($result->num_rows == 0)
		{
			returnWithError(4, "Failed to access user grades for this room.");
			exit();
		}
		else
		{
			$roomUsers = array();
			while($row = $result->fetch_assoc())
			{
				$user = new userAnal();
				$user->userID = $row["userID"];
				$user->orgID = $row["orgID"];
				$user->numCorrect = $row["numCorrect"];
				$user->numWrong = $row["numWrong"];

				$roomUsers[] = $user;
			}
		}

		sendWithInfo(json_encode($roomQuestions), json_encode($roomUsers));
	}
	// Close the connection
	$connection->close();

  function returnWithError($errCode, $err )
  {
    $retValue = createJSONString('[]','[]',$err, $errCode);
    sendResultInfoAsJson( $retValue );
  }

  function returnWithInfo($roomQuestions_, $roomUsers_)
  {
	  $retValue = createJSONString($roomQuestions_, $roomUsers_, "", 0);
	  sendResultInfoAsJson( $retValue );
  }

  function createJSONString($roomQuestions_, $roomUsers_, $error_, $errCode_)
  {
		$ret = '
        {
		  "roomQuestions" : ' . $roomQuestions_ . ',
		  "roomUsers" : ' . $roomUsers_ . ',
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
