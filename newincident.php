<?php
// Start the session
session_start();
?>

<html>
<head>
<?php include 'linkbar.php'; ?>
<br>
<h1>New Incident</h1>

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
<?php
	include 'db_conn.php';
?>
<br><br>
</head>
<body>
<form action="incidentinfo.php" method="post">
<fieldset>
<legend>New Incident Info</legend>
Incident ID (Assigned Automatically)<br><br>
Date <input type="date" name="incidentDate" required/><br><br>
Description <input type="text" name="incidentDesc" required/><br><br>
<br>
    <table border="0">
        <thead>
            <tr>
                <th>Incident Location</th>
                <th>Latitude</th>
                <th>Longitude</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td><input type="text" name="ilat" size="50" required /></td>
                <td><input type="text" name="ilon" size="50" required /></td>
            </tr>
        </tbody>
    </table>
	<br><br>
<input type="submit" value="Save">
</fieldset>
</body>
</html>
