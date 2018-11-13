<?php
require("SQL_Credentials.php");

//	connection using the sql credentials
$$connection = new mysqli($serverURL, $serverLogin, $serverAuth, $serverDB);

//	Get JSON input
$inData = json_decode(file_get_contents('php://input'), true);;

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
	$call = $connection->prepare(
    	'PollingZone.user_login( :usrEmail, :usrPassword , @session, @error);'
    );
	$call->bindParam(':usrEmail', $userEmail);
	$call->bindParam(':usrPassword', $password);
  $call->execute();

	$result = $call->get_result();

	//	Capture error
	$err = $connection->query("SELECT @error");

	if ($result->num_rows == 0)
	{
		returnWithError("Unsuccessful Login");
	}else
	{
		$row = $result->fetch_assoc();

		$err = $row["error"];
		if($err == 1)
		{
			returnWithError("Unsuccessful Login");
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
  $retValue = createJSONString($id_, $firstName_, $lastName_, $optionalName_, $dateCreated_, $sessionID, "");
  sendResultInfoAsJson( $retValue );
}


?>
