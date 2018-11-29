<?php
require("config.php");

class Question {
    function __construct() {
		$this->questionID = "";
		$this->roomID = "";
		$this->questionText = "";
    $this->choiceCount = 0;
		$this->choices;
    }
}

//	connection using the sql credentials
$connection = new mysqli($serverIP, $serverUSER, $serverPASS, $serverDB, $serverPORT)
or die('connection to server failed');
//	Get JSON input
$inData = json_decode(file_get_contents('php://input'), true);;

if($connection->connect_error)
{
	returnWithError("Error Connecting to the Server");
}
else
{
  $id = "";
  $roomCode 	= "";
  $sessionID  = "";

	//	Sanitize JSON input
	$id 	= mysqli_real_escape_string($connection, $inData["userID"]);
	$sessionID 	= mysqli_real_escape_string($connection, $inData["sessionID"]);
  $roomCode 	= mysqli_real_escape_string($connection, $inData["roomCode"]);

	//	Call stored procedure that will insert a new user
	$call =
    	'CALL PollingZone.room_getByCode( "'
      . $id . '","'
      . $sessionID . '","'
      . $roomCode . '"
    );';
	$result = $connection->query($call);

  if($result == null){
    returnWithError("Invalid User Authentication");
  }else if ($result->num_rows == 0)
	{
		returnWithError("No results from stored procedure");
	}else
	{
		$questionArray = array();

    while($row = $result->fetch_assoc())
    {
      $jsonObject = new Question();
      $jsonObject->questionID = $row["questionID"];
      $jsonObject->roomID = $row["roomID"];
      $jsonObject->questionText = $row["questionText"];

      $jsonObject->choices = array();
      $jsonObject->choices[] = $row["choice1"];
      $jsonObject->choices[] = $row["choice2"];
      $jsonObject->choices[] = $row["choice3"];
      $jsonObject->choices[] = $row["choice4"];
      $jsonObject->choices[] = $row["choice5"];
      $jsonObject->choices[] = $row["choice6"];
      $jsonObject->choices[] = $row["choice7"];
      $jsonObject->choices[] = $row["choice8"];
      $jsonObject->choices[] = $row["choice9"];
      $jsonObject->choices[] = $row["choice10"];
      $jsonObject->choices[] = $row["choice11"];
      $jsonObject->choices[] = $row["choice12"];
      $jsonObject->choices[] = $row["choice13"];
      $jsonObject->choices[] = $row["choice14"];
      $jsonObject->choices[] = $row["choice15"];
      $jsonObject->choices[] = $row["choice16"];
      $jsonObject->choiceCount = count($jsonObject->choices);
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
