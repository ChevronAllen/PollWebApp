<?php
require("SQL_Credentials.php");

class Question {
    function __construct() {
		$this->questionID = "";
		$this->roomID = "";
		$this->questionText = "";
		$this->choice1 = "";
    $this->choice2 = "";
    $this->choice3 = "";
    $this->choice4 = "";
    $this->choice5 = "";
    $this->choice6 = "";
    $this->choice7 = "";
    $this->choice8 = "";
    $this->choice9 = "";
    $this->choice10 = "";
    $this->choice11 = "";
    $this->choice12 = "";
    $this->choice13 = "";
    $this->choice14 = "";
    $this->choice15 = "";
    $this->choice16 = "";
    }
}

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
  $roomCode 	= "";
  $sessionID  = "";

	//	Sanitize JSON input
	$id 	= mysqli_real_escape_string($connection, $inData["userID"]);
	$sessionID 	= mysqli_real_escape_string($connection, $inData["sessionID"]);
  $roomCode 	= mysqli_real_escape_string($connection, $inData["roomCode"]);

	//	Call stored procedure that will insert a new user
	$call = $connection->prepare(
    	'PollingZone.room_getByCode( :usrID, :usrSession , :usrRoom, @error);'
    );
	$call->bindParam(':usrID', $id);
	$call->bindParam(':usrSession', $sessionID);
  $call->bindParam(':usrRoom', $roomCode);
  $call->execute();

	$result = $call->get_result();

	if ($result->num_rows == 0)
	{
		returnWithError("No results from stored procedure");
	}else
	{
		$questionArray = array();

    while($row = $result->fetch_assoc())
    {
      $jsonObject = new Contact();
      $jsonObject->questionID = $row["questionID"];
      $jsonObject->roomID = $row["roomID"];
      $jsonObject->questionText = $row["questionText"];
      $jsonObject->choice1 = $row["choice1"];
      $jsonObject->choice2 = $row["choice2"];
      $jsonObject->choice3 = $row["choice3"];
      $jsonObject->choice4 = $row["choice4"];
      $jsonObject->choice5 = $row["choice5"];
      $jsonObject->choice6 = $row["choice6"];
      $jsonObject->choice7 = $row["choice7"];
      $jsonObject->choice8 = $row["choice8"];
      $jsonObject->choice9 = $row["choice9"];
      $jsonObject->choice10 = $row["choice10"];
      $jsonObject->choice11 = $row["choice11"];
      $jsonObject->choice12 = $row["choice12"];
      $jsonObject->choice13 = $row["choice13"];
      $jsonObject->choice14 = $row["choice14"];
      $jsonObject->choice15 = $row["choice15"];
      $jsonObject->choice16 = $row["choice16"];
      $questionArray[] = $jsonObject;
    }

    returnWithInfo(json_encode($questionArray));
	}
}

// Close the connection
$connection->close();

function createJSONString($questions_, $error_)
{
  $ret = '
        {
          "questions" : '. $questions_ .' ,
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
  $retValue = createJSONString("[]",$err);
  sendResultInfoAsJson( $retValue );
}

function returnWithInfo($questions_)
{
  $retValue = createJSONString($questions_, "");
  sendResultInfoAsJson( $retValue );
}


?>
