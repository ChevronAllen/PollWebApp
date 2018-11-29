<?php
  require("config.php");
  //	connection using the sql credentials
  $connection = new mysqli($serverIP, $serverUSER, $serverPASS, $serverDB, $serverPORT)
  or die('connection to server failed');
  //	Get JSON input
  $inData = json_decode(file_get_contents('php://input'), true);
  $userID = "";
  $sessionKey = "";

  if($connection->connect_error)
  {
  	returnWithError("Error Connecting to the Server");
  }
  else
  {
    $userID             = mysqli_real_escape_string($connection, $inData["userID"]);
    $sessionKey         = mysqli_real_escape_string($connection, $inData["sessionKey"]);

    $call = 'CALL PollingZone.user_getRoomsOwned(
			     "' . $userID . '",
  			   "' . $sessionKey . '"
                );';

    $result = $connection->query($call);

    if ($result == NULL || $result->num_rows == 0)
  	{
  		if($result == NULL)
  		{
  			returnWithError("User authentication error. Please login");
  		}
  		returnWithError("Parameter error/No results from query");
  	}
    else
    {
      $rooms = array();
      while($row = $result->fetch_assoc())
      {
        $rooms[] = $row["room"];
      }
      returnWithInfo($rooms, "");
    }
  }

  $connection->close();

  function createJSONString( $rooms_, $error_)
  {
    $ret = '
          {
            "rooms" : '. $rooms_ .' ,
            "error" : "' . $error_ . '"
          }';
    return $ret;
  }
  function returnWithError( $err )
  {
    $retValue = createJSONString("",$err);
    sendResultInfoAsJson( $retValue );
  }
  function returnWithInfo($rooms_, $err_)
  {
    $retValue = createJSONString($rooms_, $err_);
    sendResultInfoAsJson( $retValue );
  }
  function sendResultInfoAsJson( $obj )
  {
    header('Content-type: application/json');
    echo $obj;
  }
?>
