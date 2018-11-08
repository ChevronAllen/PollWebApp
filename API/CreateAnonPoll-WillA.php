<?php
  require("SQL_Credentials.php");

  //	connection using the sql credentials
  $$connection = new mysqli($serverURL, $serverLogin, $serverAuth, $serverDB);

  //	Get JSON input
  $inData = json_decode(file_get_contents('php://input'), true);

  // Initialize variable
  $pollName = "";
  $questionText = "";
  $responseCount = 0;
  $correctResponse = 0; //index of answer array
  $responses = array();

  if($inData == NULL)
  {
    returnWithError("Communications Error, NULL input");
  }
  else if($connection->connect_error)
  {
  	returnWithError("Error Connecting to the Server");
  }
  else
  {
    // Declare variable
    $pollName =         mysqli_real_escape_string($connection, $inData["pollName"]);
    $questionText =     mysqli_real_escape_string($connection, $inData["questionText"]);
    $responseCount =    mysqli_real_escape_string($connection, $inData["responseCount"]);
    $correctResponse =  mysqli_real_escape_string($connection, $inData["correctResponse"]);

    for($i = 0; $i <= $responseCount; $i++)
    {
      $responses[] = mysqli_real_escape_string($connection, $inData["responseText"]);
    }
  }

  $connection->close();

  function returnWithError( $err )
  {
    $retValue = createJSONString(0,"","",$err);
    sendResultInfoAsJson( $retValue );
  }
?>
