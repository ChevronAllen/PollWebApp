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
				 "' . $question[1]["correctResponse"] . '",
				 "' . $question[1]["questionText"] . '",
  			     	 "' . $question[1]["choice1"] . '",
				 "' . $question[1]["choice2"] . '",
				 "' . $question[1]["choice3"] . '",
				 "' . $question[1]["choice4"] . '",
				 "' . $question[1]["choice5"] . '",
				 "' . $question[1]["choice6"] . '",
				 "' . $question[1]["choice7"] . '",
				 "' . $question[1]["choice8"] . '",
				 "' . $question[1]["choice9"] . '",
				 "' . $question[1]["choice10"] . '",
				 "' . $question[1]["choice11"] . '",
				 "' . $question[1]["choice12"] . '",
				 "' . $question[1]["choice13"] . '",
				 "' . $question[1]["choice14"] . '",
				 "' . $question[1]["choice15"] . '",
				 "' . $question[1]["choice16"] . '"
                 );';
      $result = $connection->query($call);
      if ($result->num_rows == 0)
    	{
    		returnWithError("Failed to add questions");
    	}
	  }
  	else // User Logged in
  	{
      for($i = 1; $i <= count($question); $i++)
      {
        $call = 'CALL PollingZone.room_addQuestion(
          "' . $userID . '",
          "' . $sessionKey . '",
          "' . $roomID . '",
          "' . $question[$i]["correctResponse"] . '",
          "' . $question[$i]["questionText"] . '",
          "' . $question[$i]["choice1"] . '",
          "' . $question[$i]["choice2"] . '",
          "' . $question[$i]["choice3"] . '",
          "' . $question[$i]["choice4"] . '",
          "' . $question[$i]["choice5"] . '",
          "' . $question[$i]["choice6"] . '",
          "' . $question[$i]["choice7"] . '",
          "' . $question[$i]["choice8"] . '",
          "' . $question[$i]["choice9"] . '",
          "' . $question[$i]["choice10"] . '",
          "' . $question[$i]["choice11"] . '",
          "' . $question[$i]["choice12"] . '",
          "' . $question[$i]["choice13"] . '",
          "' . $question[$i]["choice14"] . '",
          "' . $question[$i]["choice15"] . '",
          "' . $question[$i]["choice16"] . '"
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
