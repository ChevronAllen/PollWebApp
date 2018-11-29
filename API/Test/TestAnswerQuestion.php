<?php

  returnWithInfo("8");

  function returnWithInfo($correctChoice_)
  {
	  $retValue = createJSONString($correctChoice_, "");
	  sendResultInfoAsJson( $retValue );
  }

  function createJSONString($correctChoice_, $error_)
  {
	$ret = '
        {
          "correctChoice" : '. $correctChoice_ .' ,
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
	$retValue = createJSONString("",$err,$sqlerr);
	sendResultInfoAsJson( $retValue );
  }
?>
