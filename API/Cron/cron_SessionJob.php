<?php

require("config.php");

//	connection using the sql credentials

$connection = new mysqli("107.180.25.129", "phpAPI", "Cop4331", "PollingZone", 3306)

or die('connection to server failed');



if($connection->connect_error)

{

	returnWithError("Error Connecting to the Server");

}else

{

	$call =

    'CALL PollingZone.session_removeOld();';

	

	$connection->query($call);





}
// Close the connection
$connection->close();

?>