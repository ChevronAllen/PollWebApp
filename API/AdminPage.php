<?php
    class Room
	{
      function __construct()
	  {
		$this->roomID = "";
		$this->roomCode = "";
      }
	}
	require("config.php");

	//	connection using the sql credentials
	$connection = new mysqli($serverIP, $serverUSER, $serverPASS, $serverDB, $serverPORT)
	or die('connection to server failed');
	//	Get JSON input
	$inData = json_decode(file_get_contents('php://input'), true);

	$userID = "";
	$sessionID = "";
	$today = getDate();

	if($connection->connect_error)
	{
		returnWithError(1, "Error Connecting to the Server");
	}
	else
	{
		$userID = mysqli_real_escape_string($connection, $inData["userID"]);
		$sessionID = mysqli_real_escape_string($connection, $inData["sessionID"]);

		$call = 'CALL PollingZone.user_getRoomsOwned(
			   "' . $userID . '",
  			   "' . $sessionID . '"
                );';

		$result = $connection->query($call);

		if ($result == NULL)
		{
				returnWithError(2, "User authentication error.");
        exit();
		}
    else if($result->num_rows == 0)
    {
      $CreatedRooms = "No rooms owned by this user";
    }
		else
		{
		  $CreatedRooms = array();
		  $num_rows = mysql_num_rows($result);

		  while($row = $result->fetch_assoc())
		  {
  			if($num_rows < 4)
  			{
  				for($i = 0; $i < 4; $i++)
  				{
  					$roomC = new Room();
  					$roomC->roomID = $row["roomID"];
  					$roomC->roomCode = $row["roomCode"];

  					$CreatedRooms[] = $roomC;
  				}
  			}
  			else
  			{
  				for($i = 0; $i < 4; $i++)
  				{
  					$roomC = new Room();
  					$roomC->roomID = $row["roomID"];
  					$roomC->roomCode = $row["roomCode"];

  					$CreatedRooms[] = $roomC;
  				}
  			}
  			$result->free();
		  }
		}
		$connection->next_result();

		$call = 'CALL PollingZone.user_getSubmissions(
			   "' . $userID . '",
  			 "' . $sessionID . '"
                );';

		$result = $connection->query($call);
		if ($result == NULL)
		{
				returnWithError(2, "User authentication error.");
			  exit();
		}
    else if($result->num_rows == 0)
    {
      $AnsweredRooms = "No rooms answered by this user";

    }
		else
		{
		  $AnsweredRooms = array();
		  $num_rows = mysql_num_rows($result);
		  while($row = $result->fetch_assoc())
		  {
			if($num_rows < 4)
			{
				for($i = 0; $i < 4; $i++)
				{
					$roomA = new Room();
					$roomA->roomID = $row["roomID"];
					$roomA->roomCode = $row["roomCode"];

					$AnsweredRooms[] = $roomA;
				}
			}
			else
			{
				for($i = 0; $i < $num_rows; $i++)
				{
					$roomA = new Room();
					$roomA->roomID = $row["roomID"];
					$roomA->roomCode = $row["roomCode"];

					$AnsweredRooms[] = $roomA;
				}
			}
			$result->free();
		  }
		}
		$connection->next_result();

		$call = 'CALL PollingZone.user_getRoomsOwned(
			   "' . $userID . '",
  			 "' . $sessionID . '"
                );';

		$result = $connection->query($call);

		if ($result == NULL)
		{
				returnWithError(2, "User authentication error. Please login");
        exit();
		}
    else if($result->num_rows == 0)
    {
      returnWithError(3, "Parameter error/No rooms owned by this user");
    }
		else
		{
		  while($row = $result->fetch_assoc())
		  {
			$roomExpirationDate = date_parse($row["dateExpire"]);
			$RemainingRooms = array();
			if($roomExpirationDate["year"] >= $today["year"] &&
			   $roomExpirationDate["month"] >= $today["mon"] &&
			   $roomExpirationDate["day"] >= $today["mday"] &&
			   $roomExpirationDate["hour"] >= $today["hours"] &&
			   $roomExpirationDate["minute"] >= $today["minutes"] &&
			   $roomExpirationDate["second"] > $today["seconds"])
			   {
				$roomO = new Room();
				$roomO->roomID = $row["roomID"];
				$roomO->roomCode = $row["roomCode"];

				$RemainingRooms[] = $roomO;
			   }
		  }
		}

		returnWithInfo(json_encode($CreatedRooms), json_encode($AnsweredRooms), json_encode($RemainingRooms));
	}

  function returnWithError($errCode, $err )
  {
    $retValue = createJSONString("","","",$err, $errCode);
    sendResultInfoAsJson( $retValue );
  }

  function returnWithInfo($CreatedRooms, $AnsweredRooms, $RemainingRooms)
  {
	  $retValue = createJSONString($CreatedRooms, $AnsweredRooms, $RemainingRooms, "", 0);
	  sendResultInfoAsJson( $retValue );
  }

  function createJSONString($CreatedRooms_, $AnsweredRooms_, $RemainingRooms_, $error_, $errCode_)
  {
		$ret = '
        {
    		  "createdRooms" : "' . $CreatedRooms_ . '",
    		  "answeredRooms" : "' . $AnsweredRooms_ . '",
    		  "remainingRooms" : "' . $RemainingRooms_ . '",
          "error" : "' . $error_ . '",
    		  "errorCode" : "' . $errCode_ . '"
        }';
    return $ret;
  }

  function sendResultInfoAsJson( $obj )
  {
    header('Content-type: application/json');
    echo $obj;
  }
?>
