<?php
// Start the session
session_start();
?>


<html>
<?php include 'linkbar.php'; ?>
<br>
<h1>Search Resources</h1>
<?php
	require 'session_check.php';
	include 'db_conn.php';
?>
<br><br>
<form action="searchresults.php" method="post">
<fieldset>
<legend>Search for resources</legend>
Keyword <input type="text" name="resourcename" ><br><br>
ESF <select name="ESF" size="1" required / >
        <!-- some PHP here -->
        <?php
		
		$sql = "SELECT ESF_ID, ESF_Description FROM ESF";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {		   
				// output data of each seletion
				//$row = $result->fetch_assoc();
				   
				$ESF_ID = $row["ESF_ID"];
				$ESF_Description = $row["ESF_Description"];
					
				echo "<option value ='$ESF_ID'>";
				echo $ESF_ID . " ". $ESF_Description;
				echo "</option>";
				} 
		}
	?>
    </select>
<br><br>
Location within <input type="text" name="distance" required / > Km of incident <br><br>
Incident <select name="incident" size="1" required >
        <!-- some PHP here -->
        <?php
		$currentUser = $_SESSION["user"];
		$sql = "SELECT I.IncidentID, I.Description FROM Incidents I WHERE Username = '$currentUser'";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {		   
				// output data of each seletion
				//$row = $result->fetch_assoc();
				   
				$IncidentID = $row["IncidentID"];
				$Description = $row["Description"];
					
				echo "<option value ='$IncidentID'>";
				echo $IncidentID . " ". $Description;
				echo "</option>";
				} 
		}
		//Close the db connection.
		$conn->close();
	?>
    </select>
	<br><br>
<input type="submit" value="Search">
</fieldset>
</html>

