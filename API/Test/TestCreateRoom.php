<?php

  returnWithInfo("001e01e483e829340585fb2bff9fa651", "ABCDEF", "");

  function createJSONString($roomID_, $roomCode_, $error_)
	{
		$ret = '
        {
          "roomID" : "'. $roomID_ .'" ,
          "roomCode" : "' . $roomCode_ . '",
          "error" : "' . $error_ . '"
        }';
    return $ret;
	}
  function returnWithError( $err )
  {
    $retValue = createJSONString("","",$err);
    sendResultInfoAsJson( $retValue );
  }
  function returnWithInfo($roomID, $roomCode, $err)
  {
    $retValue = createJSONString($roomID, $roomCode, $err);
    sendResultInfoAsJson( $retValue );
  }
  function sendResultInfoAsJson( $obj )
  {
    header('Content-type: application/json');
    echo $obj;
  }
?>
