<?php
require("SQL_Credentials.php");
/*
class Poll {
    function __construct() {
		$this->pollID = "";
		$this->pollName = "";
		$this->dateCreated = "";
		$this->dateExpire = "";
    $this->countQuestions = "";
    }
}
*/

//	connection using the sql credentials
$$connection = new mysqli($serverURL, $serverLogin, $serverAuth, $serverDB);

//	Get JSON input
$inData = json_decode(file_get_contents('php://input'), true);;

if($connection->connect_error)
{
	returnWithError("Error Connecting to the Server");
}else
{
  $id = 0;
  $username 	= "";
  $password 	= "";
  $sessionID  = "";

	//	Sanitize JSON input
	$username 	= mysqli_real_escape_string($connection, $inData["userEmail"]);
	$password 	= mysqli_real_escape_string($connection, $inData["password"]);

	//	Call stored procedure that will insert a new user
	$call = $connection->prepare(
    'CALL PollingZone.proc_authUser(
      ?,
      ?,
      @sessionID )'
    );
  $call->bind_param('ss', $username, $password);
  $call->execute();

	//	Capture results
	$result = $connection->query($call);

	if ($result->num_rows == 0)
	{
		returnWithError("Invalid Username/Password.");
	}else
	{
		$row = $result->fetch_assoc();
    $select = $connection->query('SELECT @sessionID');
    $out = $select->fetch_assoc();

		$id = $row["id"];
		$firstName = $row["firstname"];
		$lastName = $row["lastname"];
    $sessionID = $out['@sessionID'];
    $jsonArray = array();
    /*
    if($result->num_rows != 0)
    {
      while($row = $result->fetch_assoc())
      {
        $jsonObject = new Poll();
        $jsonObject->pollID = $row["pollID"];
        $jsonObject->pollName = $row["pollName"];
        $jsonObject->dateCreated = $row["dateCreated"];
        $jsonObject->dateExpire = $row["dateExpire"];
        $jsonObject->countQuestions = $row["countQuestions"];
        $jsonArray[] = $jsonObject;
      }
    }
    */
		$result->close();

    returnWithInfo($id, $firstName, $lastName, $sessionID/*,json_encode($jsonArray)*/ );
	}
}

// Close the connection
$connection->close();

function createJSONString($id_, $firstName_, $lastName_, $sessionID_,/*$polls_,*/ $error_)
{
  $ret = '
        {
          "id" : '. $id_ .' ,
          "firstName" : "' . $firstName_ . '",
          "lastName" : "' . $lastName_ . '",
          "sessionID" : "' . $sessionID_ . /*'",
          "contacts" : '. $contacts_ . ' ,*/'",
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
  $retValue = createJSONString(0,"","","","[]",$err);
  sendResultInfoAsJson( $retValue );
}

function returnWithInfo($id_, $firstName_, $lastName_, $sessionID_/*, $polls_*/ )
{
  $retValue = createJSONString($id_, $firstName_, $lastName_, $sessionID, /* $contacts_,*/"");
  sendResultInfoAsJson( $retValue );
}


?>
