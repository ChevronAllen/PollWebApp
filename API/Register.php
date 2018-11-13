<?php
require("SQL_Credentials.php");

//	connection using the sql credentials
$connection = new mysqli($serverURL, $serverLogin, $serverAuth, $serverDB);

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
	$call = $connection->prepare(
    'CALL PollingZone.user_create(
				:usrFstNm, :usrLstNm, :usrOpNm, :usrEmail, :usrPass, :usrSalt, @error
      );'
	);

	$call->bindParam(':usrFstNm', $firstName);
	$call->bindParam(':usrLstNm', $lastName);
	$call->bindParam(':usrOpNm', $optionalName);
	$call->bindParam(':usrEmail', $userEmail);
	$call->bindParam(':usrPass', $password);
	$call->bindParam(':usrSalt', $salt);
	$call->execute();


	//	Capture results
	$result = $call->get_result();;

	if ($result->num_rows == 0)
	{
		returnWithError("Invalid query");
	}else
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
