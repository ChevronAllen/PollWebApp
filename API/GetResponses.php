<?php
require("config.php");

class Response {
    function __construct() {
		$this->responseID = "";
		$this->questionID = "";
		$this->choice = 0;
    $this->submitDate = "";
    }
}
class User {
	function __construct()
	{
		$this->id ='';
		$this->name = '';
		$this->responses = array();
	}
}

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

	$call =
    	'CALL PollingZone.room_getResponses( "'
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
		// Data recieved
    $responses = array();
		while($row = $result->fetch_assoc())
    {
      $userID = $row["userID"];
      $userFound = 0;
      $response = new Response();
      $response->responseID = $row["responseID"];
  		$response->questionID = $row["questionID"];
  		$response->choice = $row["selection"];
      $response->submitDate = $row["date_submitted"];
      foreach ($responses as $user) {
        if(userID == $user->id)
        {
          $user->responses[] = $response;
          $userFound = 1;
          break;
        }
      }
      if($userFound == 0)
      {
        $newUser = new User();
        $newUser->id = $userID;
        $newUser->optionalName = $row["optionalName"];
        $newUser->responses[] = $response;
      }

    }
	  $result->free();

	}

	// json_encode turns class into a json string
	returnWithInfo(json_encode($reponses));
}

// Close the connection
$connection->close();

function createJSONString($responses, $errorCode, $error_)
{
  $ret = '
      {
        "responses" : "'. $responses .'" ,
        "errorCode" : "' . $errorCode . '",
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
  $retValue = createJSONString([], $code, $err);
  sendResultInfoAsJson( $retValue );
  exit;
}

function returnWithInfo($responses)
{
  $retValue = createJSONString($responses, 0, "");
  sendResultInfoAsJson( $retValue );
  exit;
}

?>
