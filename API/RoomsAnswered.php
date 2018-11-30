<?php
  require("config.php");
  
    class Room 
	{
      function __construct() 
	  {
		$this->roomID = "";
		$this->roomCode = "";
      }
	}
	  
  //	connection using the sql credentials
  $connection = new mysqli($serverIP, $serverUSER, $serverPASS, $serverDB, $serverPORT)
  or die('connection to server failed');
  //	Get JSON input
  $inData = json_decode(file_get_contents('php://input'), true);
  $userID = "";
  $sessionID = "";
  if($connection->connect_error)
  {
  	returnWithError(1, "Error Connecting to the Server");
  }
  else
  {
    $userID             = mysqli_real_escape_string($connection, $inData["userID"]);
    $sessionID        = mysqli_real_escape_string($connection, $inData["sessionID"]);
    $call = 'CALL PollingZone.user_getSubmissions(
			   "' . $userID . '",
  			   "' . $sessionID . '"
                );';
    $result = $connection->query($call);
    if ($result == NULL || $result->num_rows == 0)
  	{
  		if($result == NULL)
  		{
  			returnWithError(2, "User authentication error. Please login");
  		}
  		returnWithError(3, "Parameter error/No results from query");
  	}
    else
    {
      $rooms = array();
      while($row = $result->fetch_assoc())
      {
		$room = new Room();
		$room->roomID = $row["roomID"];
		$room->roomCode = $row["roomCode"];
		
        $rooms[] = $room;
      }
      returnWithInfo(json_encode($rooms));
    }
  }
  $connection->close();

  function returnWithInfo($rooms_)
  {
	  $retValue = createJSONString($rooms_, "", 0);
	  sendResultInfoAsJson( $retValue );
  }

  function createJSONString($rooms_, $error_, $errCode_)
  {
	$ret = '
        {
          "rooms" : '. $rooms_ .' ,
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
  
  function returnWithError($errCode, $err)
  {
	$retValue = createJSONString("",$err,$errCode);
	sendResultInfoAsJson( $retValue );
  }
?>
