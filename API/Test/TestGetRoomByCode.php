<?php

  class Question 
  {
      function __construct() 
	  {
  		$this->correctResponse = "2";
  		$this->questionText = "Hello World";
  		$this->choice1 = "H";
      $this->choice2 = "e";
      $this->choice3 = "l";
      $this->choice4 = "l";
      $this->choice5 = "o";
      $this->choice6 = "w";
      $this->choice7 = "o";
      $this->choice8 = "r";
      $this->choice9 = "l";
      $this->choice10 = "d";
      $this->choice11 = "1";
      $this->choice12 = "2";
      $this->choice13 = "3";
      $this->choice14 = "4";
      $this->choice15 = "5";
      $this->choice16 = "6";
      }
  }
  
  $question = array();
  
  for($i = 0; $i < 5; $i++)
  {
	$question[] = new Question();
  }
  
  returnWithInfo($question);

  function returnWithInfo($question_)
  {
	  $retValue = createJSONString($question_, "");
	  sendResultInfoAsJson( $retValue );
  }

  function createJSONString($question_, $error_)
  {
	$ret = '
        {
          "question" : '. $question_ .' ,
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
