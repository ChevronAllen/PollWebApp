<?php
require("config.php");

//	connection using the sql credentials
$connection = new mysqli($serverIP, $serverUSER, $serverPASS, $serverDB, $serverPORT)
or die('connection to server failed');
//	Get JSON input
$inData = json_decode(file_get_contents('php://input'), true);;

if($connection->connect_error)
{
	// Connection Error
	returnWithError(1,"Error Connecting to the Server");
}
else
{
	// Successful Connection

	$id = "";			// incoming userID
	$roomID 	= "";	// incoming room code
	$sessionID  = "";	// incoming sessionID

	//	Sanitize JSON input
	$id 		= mysqli_real_escape_string($connection, $inData["userID"]);
	$sessionID 	= mysqli_real_escape_string($connection, $inData["sessionID"]);
	$roomID 	= mysqli_real_escape_string($connection, $inData["roomID"]);

	/*
	///////////////////////////////
	//	First Query
	///////////////////////////////
	*/

	//	Build stored procedure, find a room with this roomCode
	$call =
    	'CALL PollingZone.room_getByCode( "'
      . $id . '","'
      . $sessionID . '","'
      . $roomID . '");';

	//	Call stored procedure
	$result = $connection->query($call);

	// Error catching
	if($result == null || $result == false){
		// Query didnt complete or User Credentials Incorrect
		returnWithError(2,"Invalid User Authentication");
	}else if ($result->num_rows == 0)
	{
		// Table structure recieved but no rows populated
		// Still successful
		returnWithError(0,"No Room Found");

	}else
	{
		$result->free();
    // json_encode turns class into a json string
		returnWithError(0,"");
	}
}

// Close the connection
$connection->close();

function createJSONString($errorCode_, $error_)
{

  $ret = '
      {
        "errorCode" : "' . $errorCode_ . '",
        "error" : "' . $error_ . '"
      }';
  return $ret;
}

function sendResultInfoAsJson( $obj )
{
  header('Content-type: application/json');
  echo $obj;
}

function returnWithError( $code,$err )
{
  $retValue = createJSONString($code, $err);

  sendResultInfoAsJson( $retValue );
  exit;
}

?>
