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
	$startTime			= mysqli_real_escape_string($connection, $inData["startTime"]);
    $expirationDateTime = mysqli_real_escape_string($connection, $inData["userID"]);
	$questions			= $inData["questions"];
    
	$call = 'CALL PollingZone.proc_room_create.sql(
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
	
	if ($userID == "") // Anon User
	{
		
	}
	else // User Logged in
	{
	}
	
  }
  
  $connection->close();
  
  function returnWithError( $err )
  {
    $retValue = createJSONString(0,"","",$err);
    sendResultInfoAsJson( $retValue );
  }
  function returnWithInfo($userID, $contactID, $err)
  {
    $retValue = createJSONString($userID, $contactID, $err);
    sendResultInfoAsJson( $retValue );
  }
  function sendResultInfoAsJson( $obj )
  {
    header('Content-type: application/json');
    echo $obj;
  }
?>
