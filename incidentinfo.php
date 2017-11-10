<?php 
	// Start the session 
	session_start();
 ?>


 <html>
 <head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
<?php include 'linkbar.php'; ?>
<br>
 <h1>Incident Info</h1>
 </head>
 <body>
 <br><br>

 <?php
	require 'session_check.php';
	include 'db_conn.php';
	echo "<br><br>";
	echo "<fieldset>";
	echo "<legend> Incident </legend>";	
	
	$Username = $_SESSION["user"];
	//Get the POST value from the newincident.php page
	$incidentDate = $_POST["incidentDate"];
	$incidentDesc = $_POST["incidentDesc"];
	$ilat = $_POST["ilat"];
	$ilon = $_POST["ilon"];
	
	//Get the Max incident ID from the incident table
	
	$sql = "SELECT MAX(I.IncidentID) AS MaxID FROM Incidents I";
	
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
		$currentID = $row["MaxID"] + 1;
	} else {
    	echo "0 results";
	}
	
	//Insert the data using the incident ID
	//Note that this is not an elegant solution, the dev is aware!
	
	$stmt = $conn->prepare("INSERT INTO Incidents (Username, IncidentID, IncidentDate, Description, Longitude, Latitude) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sissss", $Username, $currentID, $incidentDate, $incidentDesc, $ilon, $ilat);
		$stmt->execute();
		$stmt->close();
		
	//Display the data back to the user
	$sql = "SELECT Username, IncidentID, IncidentDate, Description, Longitude, Latitude FROM Incidents I WHERE I.IncidentID = $currentID";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
        echo "<table><tr><th>User Name</th><th>Incident ID</th><th>Date</th><th>Description</th><th>Longitude</th><th>Latitude</th></tr>";
        while($row = $result->fetch_assoc()) {
        echo "<tr><td> " . $row["Username"]."</td><td>". $row["IncidentID"]."</td><td>". $row["IncidentDate"]."</td><td>"
				. $row["Description"]."</td><td>". $row["Longitude"]. "</td><td>"
				. $row["Latitude"] ."</td></tr>";
                }
        echo"</table>";
                } else {
        echo "No Incident Avail<br><br>";
        echo $sql;
        }
		echo "</fieldset>";
		echo "<br><br>";
		echo "<fieldset>";
		echo "<legend>The following incidents may impact this incident</legend>";
		$sql ="SELECT inc.IncidentID
				,inc.Description
					,inc.Username
					,COUNT(r.IncidentID) as resourcesInUse
				,(TRUNCATE(111.045 * DEGREES(ACOS(COS(RADIANS(inc.Latitude))
				* COS(RADIANS(i.Latitude))
				* COS(RADIANS(i.Longitude) - RADIANS(inc.Longitude))
				+ SIN(RADIANS(inc.Latitude))
				* SIN(RADIANS(i.Latitude)))),4) ) as distance
				FROM Incidents i,
				Incidents inc left JOIN Requests r on inc.IncidentID = r.IncidentID
				WHERE
				(TRUNCATE(111.045 * DEGREES(ACOS(COS(RADIANS(inc.Latitude))
				* COS(RADIANS(i.Latitude))
				* COS(RADIANS(i.Longitude) - RADIANS(inc.Longitude))
				+ SIN(RADIANS(inc.Latitude))
				* SIN(RADIANS(i.Latitude)))),4)) < 200
				AND i.IncidentID = $currentID and i.IncidentID != inc.IncidentID
				GROUP by r.IncidentID, inc.IncidentID, inc.Description, inc.Username, inc.Longitude, inc.Longitude, i.Longitude, i.Latitude";
		
		$result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
        echo "<table>
				<tr><th>Incident ID</th>
				<th>Description</th>
				<th>Username</th>
				<th>Distance</th>
				</tr>";
        while($row = $result->fetch_assoc()) {
        echo "<tr>
		<td>" .$row["IncidentID"]."</td>
		<td>". $row["Description"]."</td>
		<td>". $row["Username"]."</td>
		<td>". $row["distance"]. "</td>
		</tr>";
                }
        echo"</table>";
                } else {
        echo "No Incident listed within 200 Km<br><br>";
        echo $sql;
        }
		echo "</fieldset>";

		?>
		
		</body>
		</html>
	
	
	
