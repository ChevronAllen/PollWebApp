<?php
require("config.php");
//	connection using the sql credentials
$connection = new mysqli($serverIP, $serverUSER, $serverPASS, $serverDB, $serverPORT)
or die('connection to server failed');
//	Get JSON input
$inData = json_decode(file_get_contents('php://input'), true);
if($connection->connect_error)
{
	returnWithError("Error Connecting to the Server");
}
else
{
  $id = 0;
  $userEmail 	= "";
  $password 	= "";
  $sessionID  = "";
	//	Sanitize JSON input
	$userEmail 	= mysqli_real_escape_string($connection, $inData["userEmail"]);
	$password 	= mysqli_real_escape_string($connection, $inData["password"]);
	//	Call stored procedure that will insert a new user
	$call =
    	'CALL PollingZone.user_login( "'
				. $userEmail . '","'
				. $password . '"
				);';
	$result = $connection->query($call);
	if ($result == NULL || $result->num_rows == 0)
	{
		if($result == NULL)
		{
			returnWithError("User authentication error. Please login");
		}
		returnWithError("Parameter error/No results from query");
	}
	else
	{
		$row = $result->fetch_assoc();
		$err = $row["error"];
		if($err == 1)
		{
			returnWithError("Unsuccessful Login error returned 1");
		}
		$sessionID = $row["session"];
		$id = $row["userID"];
		$firstName = $row["user_firstName"];
		$lastName = $row["user_lastName"];
		$optionalName = $row["user_optionalName"];
		$dateCreated = $row["date_created"];
    returnWithInfo($id, $firstName, $lastName, $optionalName, $dateCreated, $sessionID);
	}
}
// Close the connection
$connection->close();
function createJSONString($id_, $firstName_, $lastName_, $optionalName_, $dateCreated_, $sessionID_, $error_)
{
  $ret = '
        {
          "id" : '. $id_ .' ,
          "firstName" : "' . $firstName_ . '",
          "lastName" : "' . $lastName_ . '",
	  "optionalName" : "' . $optionalName_ . '",
          "dateCreated" : "' . $dateCreated_ . '",
          "sessionID" : "' . $sessionID_ . '",
          "error" : "' . $error_ . '"
        }';
  return $ret;
}
function sendResultInfoAsJson( $obj )
{
  header('Content-type: application/json');
  echo $obj;
}
function returnWithError( $err )
{
  $retValue = createJSONString(0,"","","","","",$err);
  sendResultInfoAsJson( $retValue );
}
function returnWithInfo($id_, $firstName_, $lastName_, $optionalName_, $dateCreated_, $sessionID_)
{
  $retValue = createJSONString($id_, $firstName_, $lastName_, $optionalName_, $dateCreated_, $sessionID_, "");
  sendResultInfoAsJson( $retValue );
}
?>
