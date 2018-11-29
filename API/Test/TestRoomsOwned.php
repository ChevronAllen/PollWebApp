<?php

    class Room 
	{
      function __construct() 
	  {
		$this->roomID = "001e01e483e829340585fb2bff9fa651";
		$this->roomCode = "ABCDEF";
      }
	}
  
  $rooms = array();
  
  for($i = 0; $i < 5; $i++)
  {
	$rooms[] = new Room();
  }
  
  returnWithInfo($rooms);

  function returnWithInfo($question_)
  {
	  $retValue = createJSONString($question_, "");
	  sendResultInfoAsJson( $retValue );
  }

  function returnWithInfo($rooms_)
  {
	  $retValue = createJSONString($rooms_, "");
	  sendResultInfoAsJson( $retValue );
  }

  function createJSONString($rooms_, $error_)
  {
	$ret = '
        {
          "rooms" : '. $rooms_ .' ,
          "error" : "' . $error_ . '"
        }';
	return $ret;
  }
  
  function sendResultInfoAsJson( $obj )
  {  
	header('Content-type: application/json');
	echo $obj;
  }
  
  function returnWithError( $err , $sqlerr)
  {
	$retValue = createJSONString("",$err);
	sendResultInfoAsJson( $retValue );
  }
?>
