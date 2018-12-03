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
class Room {
	function __construct()
	{
		$this->id ='';
		$this->title = '';
		$this->start = '';
		$this->expire = '';
		$this->owner = '';
		$this->questions = array();
		$this->errorCode = 0;
		$this->error = '';
	}
	// For Debugging
	public function __toString()
	{
		return 	"roomID: " . $this->id .
				" title: " . $this->title;
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
	$id = "";			// incoming userID
	$roomCode 	= "";	// incoming room code
	$sessionID  = "";	// incoming sessionID
	$roomObject = new Room();	// data structure to organise Room
	//	Sanitize JSON input
	$id 		= mysqli_real_escape_string($connection, $inData["userID"]);
	$sessionID 	= mysqli_real_escape_string($connection, $inData["sessionKey"]);
	$roomCode 	= mysqli_real_escape_string($connection, $inData["roomCode"]);
	/*
	///////////////////////////////
	//	First Query
	///////////////////////////////
	*/
	//	Build stored procedure, find a room with this roomCode
	$call =
    	'CALL PollingZone.room_getByCode( "'
      . $id . '","'
      . $sessionID . '","'
      . $roomCode . '");';
	//	Call stored procedure
	$result = $connection->query($call);
	// Error catching
	if($result == null || $result == false){
		// Query didnt complete or User Credentials Incorrect
		returnWithError(2,"Invalid User Authentication");
	}else if ($result->num_rows == 0)
	{
		// Table structure recieved but no rows populated
		// Still successful
		returnWithError(0,"No Room Found");
	}else
	{
		// Data recieved
		$row = $result->fetch_assoc();				// Get First Row
		$roomObject->id 	= $row["roomID"];		// Read roomId
		$roomObject->title 	= $row["roomTitle"];	// Read roomTitle
		$roomObject->start 	= $row["dateStart"];	// Read start date of room
		$roomObject->expire = $row["dateExpire"];	// Read expiration date of
		$roomObject->owner 	= $row["ownerName"];	// Read room owners first and last name
		$result->free();
	}
	$connection->next_result();	// clear results
	//	Build stored procedure, find questions for a given roomID
	$call =
    	'CALL PollingZone.room_getAllQuestions( "'
      . $id . '","'
      . $sessionID . '","'
      . $roomObject->id . '"
    );';
	//	Call stored procedure
	$result = $connection->query($call);
	//	Error catching
  if($result == null || $result == false){
	//	Only caught here if problems earlier
    returnWithError(2, "Invalid User Authentication");
  }else if ($result->num_rows == 0)
	{
		// Empty room means someting went wrong during room initialisation
		returnWithError(0, "Corrupted Room");
	}else
	{
		$questionArray = array();	// Initialise array to hold questions
		while($row = $result->fetch_assoc())		// While there are rows to read
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
		  // add populated question class to the Room objects array of questions
		  $roomObject->questions[] = $question;
		}
		// json_encode turns class into a json string
		returnWithInfo(json_encode($roomObject));
	}
}
// Close the connection
$connection->close();
function createJSONString($roomData, $error_)
{
  // No need to edit or concatenate json just pass it through
  return $roomData;
}
function sendResultInfoAsJson( $obj )
{
  header('Content-type: application/json');
  echo $obj;
}
function returnWithError( $code,$err )
{
	// If there is an error, Create a new room objects
	// only populate error fields. then passit through as json string
  $temp = new Room();
  $temp->error = $err;
  $retValue = json_encode($temp);
  sendResultInfoAsJson( $retValue );
  exit;
}
function returnWithInfo($roomData)
{
  $retValue = createJSONString($roomData, "");
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
