<?php 
	if(!$_SESSION["user"]){
		//Execute if there is no current session ongoing ie; no user logged in
		// remove all session variables
		session_unset(); 
		// destroy the session 
		session_destroy();
		//Take the user back to the main login 
		header("location: index.php");
		die();
	}else{
		echo "You are logged in as: " . $_SESSION["user"] . ".<br>";
	}
	
?>
