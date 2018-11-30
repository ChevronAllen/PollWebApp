<?php
require("config.php");

class Question {
    function __construct() {
		$this->questionID = "";
		$this->questionText = "";
		$this->choiceCount = 0;
		$this->choices = array();
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

	$id         = "";			// incoming userID
  $sessionID  = "";	    // incoming sessionID
	$roomID 	  = "";	    // incoming room ID
  $questionID = "";			// incoming question ID

	//	Sanitize JSON input
	$id 		    = mysqli_real_escape_string($connection, $inData["userID"]);
	$sessionID 	= mysqli_real_escape_string($connection, $inData["sessionKey"]);
	$roomID 	 	= mysqli_real_escape_string($connection, $inData["roomID"]);
  $questionID = mysqli_real_escape_string($connection, $inData["questionID"]);


	//	Build stored procedure, find questions for a given roomID
	$call =
    	'CALL PollingZone.room_getQuestion( "'
      . $id . '","'
      . $sessionID . '","'
      . $roomID . '","'
      . $questionID '"
    );';
	//	Call stored procedure
	$result = $connection->query($call);

	//	Error catching
  if($result == null || $result == false){
	//	Only caught here if problems earlier
    returnWithError(1, "Invalid User Authentication");
  }else if ($result->num_rows == 0)
	{
		// Empty room means someting went wrong during room initialisation
		returnWithError(2,"Corrupted Room");
	}else
	{
		$question = new Question();	//	initialise a question class
		// Store the following into the question class
		$question->questionID = $row["questionID"];
		$question->questionText = $row["questionText"];
		$question->choices[] = $row["choice1"];
		$question->choices[] = $row["choice2"];
		$question->choices[] = $row["choice3"];
		$question->choices[] = $row["choice4"];
		$question->choices[] = $row["choice5"];
		$question->choices[] = $row["choice6"];
		$question->choices[] = $row["choice7"];
		$question->choices[] = $row["choice8"];
		$question->choices[] = $row["choice9"];
		$question->choices[] = $row["choice10"];
		$question->choices[] = $row["choice11"];
		$question->choices[] = $row["choice12"];
		$question->choices[] = $row["choice13"];
		$question->choices[] = $row["choice14"];
		$question->choices[] = $row["choice15"];
		$question->choices[] = $row["choice16"];
		// Custom count function, only counts sequential non empty strings in an array
		$question->choiceCount = countChoices($question->choices);
		// json_encode turns class into a json string
		returnWithInfo(json_encode($question));
	}
}

// Close the connection
$connection->close();

function createJSONString($question, $errorCode, $error_)
{
  // No need to edit or concatenate json just pass it through
  $ret = '
      {
        "question" : "'. $question .'" ,
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

function returnWithInfo($question)
{
  $retValue = createJSONString($question, 0 , "");
  sendResultInfoAsJson( $retValue );
  exit;
}

function countChoices($list)
{
  $counter = 0;
  foreach ($list as $x) {
    if($x == NULL || $x == "" )
    {
      break;
    }
    $counter++;
  }
  return $counter;
}


?>
