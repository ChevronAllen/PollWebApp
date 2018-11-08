<?php
require("SQL_Credentials.php");

//	connection using the sql credentials
$$connection = new mysqli($serverURL, $serverLogin, $serverAuth, $serverDB);

//	Get JSON input
$inData = json_decode(file_get_contents('php://input'), true);;

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

	//	Call stored procedure that will insert a new user
	$call =
    'CALL PollingZone.proc_authUser(
      "' . $firstName . '",
			"' . $lastName . '",
			"' . $optionalName . '",
			"' . $userEmail . '",
			"' . $password . '",
			"' . $salt . '")';

	//	Capture results
	$result = $connection->query($call);

	if ($result->num_rows == 0)
	{
		returnWithError("Invalid Username/Password.");
	}else
	{
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

function returnWithInfo()
{
  $retValue = createJSONString("");
  sendResultInfoAsJson( $retValue );
}


?>