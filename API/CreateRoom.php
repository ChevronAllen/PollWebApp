<?php
  require("config.php");

  class Question {
      function __construct() {
  		$this->correctResponse = "";
  		$this->questionText = "";
  		$this->choice1 = "";
      $this->choice2 = "";
      $this->choice3 = "";
      $this->choice4 = "";
      $this->choice5 = "";
      $this->choice6 = "";
      $this->choice7 = "";
      $this->choice8 = "";
      $this->choice9 = "";
      $this->choice10 = "";
      $this->choice11 = "";
      $this->choice12 = "";
      $this->choice13 = "";
      $this->choice14 = "";
      $this->choice15 = "";
      $this->choice16 = "";
      }
  }

  //	connection using the sql credentials
  $connection = new mysqli($serverIP, $serverUSER, $serverPASS, $serverDB, $serverPORT)
  or die('connection to server failed');
  //	Get JSON input
  $inData = json_decode(file_get_contents('php://input'), true);
  $userID = "";
  $sessionKey = "";
  $roomTitle = "";
  $roomPublic = "";
  $roomID = "";
  $roomCode = "";
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
    $expirationTime = mysqli_real_escape_string($connection, $inData["expirationTime"]);
    $questions = $inData["questions"];
    $roomQuestions = array();
    foreach($questions as $question)
    {
      $roomQuestion = new Question();

      $roomQuestion->correctResponse = mysqli_real_escape_string($connection, $question["correctResponse"]);
      $roomQuestion->questionText = mysqli_real_escape_string($connection, $question["questionText"]);
  		$roomQuestion->choice1 = mysqli_real_escape_string($connection, $question["choice1"]);
      $roomQuestion->choice2 = mysqli_real_escape_string($connection, $question["choice2"]);
      $roomQuestion->choice3 = mysqli_real_escape_string($connection, $question["choice3"]);
      $roomQuestion->choice4 = mysqli_real_escape_string($connection, $question["choice4"]);
      $roomQuestion->choice5 = mysqli_real_escape_string($connection, $question["choice5"]);
      $roomQuestion->choice6 = mysqli_real_escape_string($connection, $question["choice6"]);
      $roomQuestion->choice7 = mysqli_real_escape_string($connection, $question["choice7"]);
      $roomQuestion->choice8 = mysqli_real_escape_string($connection, $question["choice8"]);
      $roomQuestion->choice9 = mysqli_real_escape_string($connection, $question["choice9"]);
      $roomQuestion->choice10 = mysqli_real_escape_string($connection, $question["choice10"]);
      $roomQuestion->choice11 = mysqli_real_escape_string($connection, $question["choice11"]);
      $roomQuestion->choice12 = mysqli_real_escape_string($connection, $question["choice12"]);
      $roomQuestion->choice13 = mysqli_real_escape_string($connection, $question["choice13"]);
      $roomQuestion->choice14 = mysqli_real_escape_string($connection, $question["choice14"]);
      $roomQuestion->choice15 = mysqli_real_escape_string($connection, $question["choice15"]);
      $roomQuestion->choice16 = mysqli_real_escape_string($connection, $question["choice16"]);

      $roomQuestions[] = $roomQuestion;
    }
    //$question 			    = mysqli_real_escape_string($connection, $inData["questions"]);
    $timeinSeconds = $startTime / 1000;
    $startTime = date("Ymdhis", $timeinSeconds);
    $timeinSeconds = $expirationTime / 1000;
    $expirationTime = date("Ymdhis", $timeinSeconds);

    // TESTING DONT LEVE HERE
    echo $startTime;
    // TESTING DONT LEVE HERE

	  $call = 'CALL PollingZone.room_create(
			     "' . $userID . '",
  			   "' . $sessionKey . '",
  			   "' . $roomTitle . '",
  			   "' . $roomPublic . '",
  			   "' . $startTime . '",
  			   "' . $expirationTime . '"
                );';
   $result = $connection->query($call);
   if ($result == NULL)
    {
      returnWithError("Invalid User Credentials");
    }
    else if( $result->num_rows == 0)
    {
      returnWithError("Room creation failed");
    }
    else
    {
      $row = $result->fetch_assoc();
      $roomID = $row["roomID"];
      $roomCode = $row["roomCode"];
      mysqli_free_result($result);
    }

   if ($userID == "") // Anon User
   {
      $call = 'CALL PollingZone.room_addQuestion(
    			 "' . $userID . '",
				 "' . $sessionKey . '",
				 "' . $roomID . '",
				 "' . $roomQuestions[0]->correctResponse . '",
				 "' . $roomQuestions[0]->questionText . '",
         "' . $roomQuestions[0]->choice1 . '",
				 "' . $roomQuestions[0]->choice2 . '",
				 "' . $roomQuestions[0]->choice3 . '",
				 "' . $roomQuestions[0]->choice4 . '",
				 "' . $roomQuestions[0]->choice5 . '",
				 "' . $roomQuestions[0]->choice6 . '",
				 "' . $roomQuestions[0]->choice7 . '",
				 "' . $roomQuestions[0]->choice8 . '",
				 "' . $roomQuestions[0]->choice9 . '",
				 "' . $roomQuestions[0]->choice10 . '",
				 "' . $roomQuestions[0]->choice11 . '",
				 "' . $roomQuestions[0]->choice12 . '",
				 "' . $roomQuestions[0]->choice13 . '",
				 "' . $roomQuestions[0]->choice14 . '",
				 "' . $roomQuestions[0]->choice15 . '",
				 "' . $roomQuestions[0]->choice16 . '"
                 );';
      $result = $connection->query($call);
      if ($result == NULL || $result->num_rows == 0)
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
          "' . $roomQuestions[$i]->correctResponse . '",
          "' . $roomQuestions[$i]->questionText . '",
          "' . $roomQuestions[$i]->choice1 . '",
 				 "' . $roomQuestions[$i]->choice2 . '",
 				 "' . $roomQuestions[$i]->choice3 . '",
 				 "' . $roomQuestions[$i]->choice4 . '",
 				 "' . $roomQuestions[$i]->choice5 . '",
 				 "' . $roomQuestions[$i]->choice6 . '",
 				 "' . $roomQuestions[$i]->choice7 . '",
 				 "' . $roomQuestions[$i]->choice8 . '",
 				 "' . $roomQuestions[$i]->choice9 . '",
 				 "' . $roomQuestions[$i]->choice10 . '",
 				 "' . $roomQuestions[$i]->choice11 . '",
 				 "' . $roomQuestions[$i]->choice12 . '",
 				 "' . $roomQuestions[$i]->choice13 . '",
 				 "' . $roomQuestions[$i]->choice14 . '",
 				 "' . $roomQuestions[$i]->choice15 . '",
 				 "' . $roomQuestions[$i]->choice16 . '"
               );';
         $result = $connection->query($call);
         if ($result == NULL || $result->num_rows == 0)
       	 {
       		  returnWithError("Failed to add questions");
       	 }
      }
  }
    returnWithInfo($roomID, $roomCode, "");
 }
  $connection->close();
  function createJSONString($roomID_, $roomCode_, $error_)
	{
		$ret = '
        {
          "roomID" : "'. $roomID_ .'" ,
          "roomCode" : "' . $roomCode_ . '",
          "error" : "' . $error_ . '"
        }';
    return $ret;
	}
  function returnWithError( $err )
  {
    $retValue = createJSONString("","",$err);
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
