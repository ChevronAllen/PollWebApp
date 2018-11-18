<?php
require("config.php");
//	connection using the sql credentials
$connection = new mysqli("107.180.25.129", "phpAPI", "Cop4331", "PollingZone", 3306)
or die('connection to server failed');
//	Get JSON input
$inData = json_decode(file_get_contents('php://input'), true);
if($connection->connect_error)
{
	returnWithError("Error Connecting to the Server");
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
				"'.$salt.'",
				@error
      );';
	//	Capture results
	$result = $connection->query($call);

	if ($result->num_rows == 0)
	{
    	   returnWithError('Ivalid query string');
	}
  else
	{
		$row = $result->fetch_assoc();
		$err = $row["error"];
		returnWithError($err);
	}
}
// Close the connection
$connection->close();

function createJSONString($error_)
{
  $ret = '
        {
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
  $retValue = createJSONString($err);
  sendResultInfoAsJson( $retValue );
}
?>
