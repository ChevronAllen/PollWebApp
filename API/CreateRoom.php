<?php
  require("config.php");
  //	connection using the sql credentials
  $connection = new mysqli($serverIP, $serverUSER, $serverPASS, $serverDB, $serverPORT)
  or die('connection to server failed');
  //	Get JSON input
  $inData = json_decode(file_get_contents('php://input'), true);
  $userID = "";
  $sessionKey = "";
  $roomTitle = "";
  $roomPublic = "";
  $startTime = "";
  $expirationTime = "";
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
    $roomPublic         = mysqli_real_escape_string($connection, $inData["roomPublic"]);
	  $startTime			    = mysqli_real_escape_string($connection, $inData["startTime"]);
    $expirationDateTime = mysqli_real_escape_string($connection, $inData["expirationTime"]);
	  $question 			    = mysqli_real_escape_string($connection, $inData["questions"]);

    $timeinSeconds = $startTime / 1000;
    $startTime = date("Ymdisv", $timeinSeconds);

    $timeinSeconds = $expirationTime / 1000;
    $expirationTime = date("Ymdisv", $timeinSeconds);

	  $call = 'CALL PollingZone.room_create(
			     "' . $userID . '",
  			   "' . $sessionKey . '",
  			   "' . $roomTitle . '",
  			   "' . $roomPublic . '",
  			   "' . $startTime . '",
  			   "' . $expirationTime . '"
                );';

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
    }

   mysqli_free_result($result);

   if ($userID == "") // Anon User
   {
      $call = 'CALL PollingZone.room_addQuestion(
    			 "' . $userID . '",
      		 "' . $sessionKey . '",
      		 "' . $roomID . '",
           "' . $question[0]["correctResponse"] . '",
      		 "' . $question[0]["questionText"] . '",
  			   "' . $question[0]["Choice_1"] . '",
           "' . $question[0]["Choice_2"] . '",
           "' . $question[0]["Choice_3"] . '",
           "' . $question[0]["Choice_4"] . '",
           "' . $question[0]["Choice_5"] . '",
           "' . $question[0]["Choice_6"] . '",
           "' . $question[0]["Choice_7"] . '",
           "' . $question[0]["Choice_8"] . '",
           "' . $question[0]["Choice_9"] . '",
           "' . $question[0]["Choice_10"] . '",
           "' . $question[0]["Choice_11"] . '",
           "' . $question[0]["Choice_12"] . '",
           "' . $question[0]["Choice_13"] . '",
           "' . $question[0]["Choice_14"] . '",
           "' . $question[0]["Choice_15"] . '",
           "' . $question[0]["Choice_16"] . '"
                );';
      $result = $connection->query($call);

      if ($result->num_rows == 0)
    	{
    		returnWithError("Failed to add questions");
    	}
	  }
  	else // User Logged in
  	{
      for($i = 0; $i < count($question); $i++)
      {
        $call = 'CALL PollingZone.room_addQuestion(
          "' . $userID . '",
          "' . $sessionKey . '",
          "' . $roomID . '",
          "' . $question[$i]["correctResponse"] . '",
          "' . $question[$i]["questionText"] . '",
          "' . $question[$i]["Choice_1"] . '",
          "' . $question[$i]["Choice_2"] . '",
          "' . $question[$i]["Choice_3"] . '",
          "' . $question[$i]["Choice_4"] . '",
          "' . $question[$i]["Choice_5"] . '",
          "' . $question[$i]["Choice_6"] . '",
          "' . $question[$i]["Choice_7"] . '",
          "' . $question[$i]["Choice_8"] . '",
          "' . $question[$i]["Choice_9"] . '",
          "' . $question[$i]["Choice_10"] . '",
          "' . $question[$i]["Choice_11"] . '",
          "' . $question[$i]["Choice_12"] . '",
          "' . $question[$i]["Choice_13"] . '",
          "' . $question[$i]["Choice_14"] . '",
          "' . $question[$i]["Choice_15"] . '",
          "' . $question[$i]["Choice_16"] . '"
               );';

         $result = $connection->query($call);

         if ($result->num_rows == 0)
       	 {
       		  returnWithError("Failed to add questions");
       	 }
      }
  	}

    returnWithInfo($roomID, $roomCode, "");
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
