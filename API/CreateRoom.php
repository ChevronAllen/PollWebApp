<?php
  require("SQL_Credentials.php");
  //	connection using the sql credentials
  $connection = new mysqli($serverURL, $serverLogin, $serverAuth, $serverDB);
  //	Get JSON input
  $inData = json_decode(file_get_contents('php://input'), true);
  $userID = "";
  $sessionKey = "";
  $roomTitle = "";
  $roomPublic = "";
  $startTime = "";
  $expirationTime = "";
  $correctResponse = -1;
  $questions = array();

  if($connection->connect_error)
  {
  	returnWithError("Error Connecting to the Server");
  }
  else
  {
    $userID             = mysqli_real_escape_string($connection, $inData["userID"]);
    $sessionKey         = mysqli_real_escape_string($connection, $inData["sessionKey"]);
    $roomTitle          = mysqli_real_escape_string($connection, $inData["roomTitle"]);
    $roomPublic         = $inData["roomPublic"];
    $startTime		= mysqli_real_escape_string($connection, $inData["startTime"]);
    $expirationDateTime = mysqli_real_escape_string($connection, $inData["userID"]);
    $correctResponse    = $inData["correctResponse"];
    $question 		= $inData["questions"];

	  $call = 'CALL PollingZone.room_create(
			   "' . $userID . '",
  			   "' . $sessionKey . '",
  			   "' . $roomTitle . '",
  			   "' . $roomPublic . '",
  			   "' . $startTime . '",
  			   "' . $expirationTime . '
				@err)';

   $result = $connection->query($call);

   if ($result->num_rows == 0)
    {
      returnWithError("Room creation failed");
    }
   else
    {
      $row = $result->fetch_assoc();

      $roomID = $row["roomID"];
      $roomCode = $row["roomCode"];

      returnWithInfo($roomID, $roomCode, "");
   }

   mysqli_free_result($result);

   if ($userID == "") // Anon User
   {
      $call = 'CALL PollingZone.room_addQuestion(
    	   "' . $userID . '",
      	   "' . $sessionKey . '",
      	   "' . $roomID . '",
           "' . $correctResponse . '",
      	   "' . $question[0][0] . '",
  	   "' . $question[0][1] . '",
           "' . $question[0][2] . '",
           "' . $question[0][3] . '",
           "' . $question[0][4] . '",
           "' . $question[0][5] . '",
           "' . $question[0][6] . '",
           "' . $question[0][7] . '",
           "' . $question[0][8] . '",
           "' . $question[0][9] . '",
           "' . $question[0][10] . '",
           "' . $question[0][11] . '",
           "' . $question[0][12] . '",
           "' . $question[0][13] . '",
           "' . $question[0][14] . '",
           "' . $question[0][15] . '",
           "' . $question[0][16] . '",
	        @err)';
  }
  else // User Logged in
  {
      for($i = 0; $i < count($question); $i++)
      {
        $call = 'CALL PollingZone.room_addQuestion(
          "' . $userID . '",
          "' . $sessionKey . '",
          "' . $roomID . '",
          "' . $correctResponse . '",
          "' . $question[$i][0] . '",
          "' . $question[$i][1] . '",
          "' . $question[$i][2] . '",
          "' . $question[$i][3] . '",
          "' . $question[$i][4] . '",
          "' . $question[$i][5] . '",
          "' . $question[$i][6] . '",
          "' . $question[$i][7] . '",
          "' . $question[$i][8] . '",
          "' . $question[$i][9] . '",
          "' . $question[$i][10] . '",
          "' . $question[$i][11] . '",
          "' . $question[$i][12] . '",
          "' . $question[$i][13] . '",
          "' . $question[$i][14] . '",
          "' . $question[$i][15] . '",
          "' . $question[$i][16] . '",
          @err)';
      }
  }
    $result = $connection->query($call);

    if ($result->num_rows == 0)
  	{
  		returnWithError("Failed to add questions");
  	}
    else
  	{
  		$err = $row["error"];
  		returnWithError($err);
  	}
  }

  $connection->close();

  function returnWithError( $err )
  {
    $retValue = createJSONString(0,"","",$err);
    sendResultInfoAsJson( $retValue );
  }
  function returnWithInfo($roomID, $roomCode, $err)
  {
    $retValue = createJSONString($roomID, $roomCode, $err);
    sendResultInfoAsJson( $retValue );
  }
  function sendResultInfoAsJson( $obj )
  {
    header('Content-type: application/json');
    echo $obj;
  }
?>

