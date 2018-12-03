<?php
require("config.php");
//	connection using the sql credentials
$connection = new mysqli("107.180.25.129", "phpAPI", "Cop4331", "PollingZone", 3306)
or die('connection to server failed');
//	Get JSON input
$inData = json_decode(file_get_contents('php://input'), true);
if($connection->connect_error)
{
	returnWithError(1, "Error Connecting to the Server");
}else
{
  $userEmail 	= "";
	$optionalName 	= "";
  $password 	= "";
	$firstName 	= "";
  $lastName 	= "";
	$salt = "";
	//	Sanitize JSON input
	$firstName 	= mysqli_real_escape_string($connection, $inData["firstName"]);
	$lastName 	= mysqli_real_escape_string($connection, $inData["lastName"]);
	$optionalName 	= mysqli_real_escape_string($connection, $inData["optionalName"]);
	$userEmail 	= mysqli_real_escape_string($connection, $inData["userEmail"]);
	$password 	= mysqli_real_escape_string($connection, $inData["password"]);
	$salt = bin2hex(openssl_random_pseudo_bytes(32));
	$hashedPass = hash("sha256",$password.$salt,FALSE);
	//	Call stored procedure that will insert a new user
	$call =
    'CALL PollingZone.user_create(
				"'.$firstName.'",
				"'.$lastName.'",
				"'.$optionalName.'",
				"'.$userEmail.'",
				"'.$hashedPass.'",
				"'.$salt.'"
					);';
	//	Capture results
	$result = $connection->query($call);
	if ($result == NULL)
	{
				 returnWithError(1, "Null results returned from stored procedure");
	}
	elseif ($result->num_rows == 0)
	{
    	   returnWithError(2, "Invalid query string");
	}
  else
	{
		returnWithError(0, "");
	}
}
// Close the connection
$connection->close();
function createJSONString($errCode_, $error_)
{
  $ret = '
        {
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
function returnWithError($errCode, $err )
{
  $retValue = createJSONString($errCode, $err);
  sendResultInfoAsJson( $retValue );
}
?>
