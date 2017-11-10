<?php
	$servername = "localhost";
	//Enter the credentials here
	$usernameDB = "";
	$passwordDB = "";
        $dbname = "";
	// Create connection
	$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);
	
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	} 
	echo "Connected successfully";	
	
?>

