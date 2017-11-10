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
 <h1>Edit Resource</h1>
 </head>
 <body>
 <br><br>

 <?php
	require 'session_check.php';
	include 'db_conn.php';
	echo "<br><br>";	
	
	$Username = $_SESSION["user"];
	$resourceID = $_SESSION["resourceID"];
	$Status = "Available";
	
	//echo "the username is: " .$Username ."<br>";
	echo "The resource ID is : " .$resourceID."<br>";
	
	//get the value from the session
	//check if the form is refreshed from within using a session var (maybe not needed)
	//if it is a new form update the info
	//this can be done by setting a Bool or flag... if the flag is true then
	if($_SESSION["newForm"] == "new"){
		//Get the values from the form
		echo "This is a new resource <br><br>";
	
	    //Upload to the db the Value
		//Get the value from the form
		
		//$Username = $_POST["Username"];
		$resourcename = $_POST["resourcename"];
		//echo $resourcename ."<br>";
		$ESF = $_POST["ESF"];
		//echo $ESF . "<br>";
		$Model = $_POST["Model"];
		//echo $Model . "<br>";
		$capability = $_POST["capability"];
		//echo $capability . "<br>";
		$lat = $_POST["lat"];
		//echo $lat . "<br>";
		$lon = $_POST["lon"];
		//echo $lon . "<br>";
		$amount = $_POST["amount"];
		//echo $amount . "<br>";
		$unit = $_POST["unit"];
		//echo $unit . "<br>";
		
		
		
		//$stmt = $conn->prepare("UPDATE Resources SET ResourceName = ? WHERE ResourceID = ?");
		//$stmt->bind_param("si", $resourcename, $resourceID);
		$stmt = $conn->prepare("UPDATE Resources SET ResourceName = ?, ESF_ID = ?, ResourceOwner = ?, Status = ?, Latitude = ?, Longitude = ?, Model = ? WHERE ResourceID = ?");
		$stmt->bind_param("sisssssi", $resourcename, $ESF, $Username, $Status, $lat, $lon, $Model, $resourceID);
		$stmt->execute();
		$stmt->close();

		//INSERT Costs 
		$stmt = $conn->prepare("INSERT INTO Costs (Username, ResourceID, Dollar, Unit) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("siss", $Username, $resourceID, $amount, $unit);
		$stmt->execute();
		$stmt->close();
		
		//INSERT Capability if any
		if($capability != ""){
		$stmt = $conn->prepare("INSERT INTO Capabilities (Username, ResourceID, Capabilities) VALUES (?, ?, ?)");
		$stmt->bind_param("sis", $Username, $resourceID, $capability);
		$stmt->execute();
		$stmt->close();
			}
		
		
		
		//INSERTS the additional ESFs
		//The prepared statement
		foreach($_POST['Add_ESF'] as $Add_ESF){
		if($ESF != $Add_ESF){
		$stmt = $conn->prepare("INSERT INTO Additional_ESF (Username, ResourceID, ESF_ID, Additional_ESF) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("siii", $Username, $resourceID, $ESF, $Add_ESF);
		$stmt->execute();
		$stmt->close();
			}
		}
	}
	
	
	
	//Display the resourcer info in a table format
	//If the resource had addional capabilities display them
	$sql = "SELECT ResourceID, ResourceName, ESF_ID, Latitude, Longitude, Model FROM Resources WHERE ResourceID = $resourceID";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		echo "<table><tr><th>Resource ID</th><th>Name</th><th>Primary ESF</th><th>Latitude</th><th>Longitude</th><th>Model</th></tr>";
		while($row = $result->fetch_assoc()) {
		echo "<tr><td> " . $row["ResourceID"]. "</td><td> " . $row["ResourceName"]."</td><td> ".$row["ESF_ID"]."</td><td> ".$row["Latitude"]. "</td><td>" .$row["Longitude"]."</td><td>".$row["Model"]."</td></tr>";
	}
	echo"</table>";
		} else {
		echo "No resource yet, please create one <br><br>";
		echo $sql;
	}	
	
	//Get the additional ESF
	echo "<br><br><br>";

	$sql = "SELECT E.ESF_ID, E.ESF_Description
		FROM Additional_ESF A INNER JOIN ESF E ON E.ESF_ID = A.Additional_ESF
		WHERE A.ResourceID = $resourceID
		ORDER BY E.ESF_ID";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
        echo "<table><tr><th>Additional ESF(s)</th></tr>";
        while($row = $result->fetch_assoc()) {
        echo "<tr><td> " . $row["ESF_ID"]. " " . $row["ESF_Description"]."</td></tr>";
                }
        echo"</table>";
                } else {
        echo "No Addional ESF for this resource<br><br>";
        echo $sql;
        }


	//Update the resource with addional capabilies
	
	//INSERT Additional Capability if any
		$add_cap = $_POST["add_cap"];
	
		if($add_cap != ""){
		$stmt = $conn->prepare("INSERT INTO Capabilities (Username, ResourceID, Capabilities) VALUES (?, ?, ?)");
		$stmt->bind_param("sis", $Username, $resourceID, $add_cap);
		$stmt->execute();
		$stmt->close();
			}
			
	//Display the additional Capabilities
	$sql = "SELECT C.Capabilities
			FROM Capabilities C 
			WHERE C.ResourceID = $resourceID";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
        echo "<table><tr><th>Current Capabilities</th></tr>";
        while($row = $result->fetch_assoc()) {
        echo "<tr><td> " . $row["Capabilities"]."</td></tr>";
                }
        echo"</table>";
                } else {
        echo "No Addional Capabilities for this resource, you can add one below!<br><br>";
        echo $sql;
        }
	
	
	
	
	
	
	//Set the session var for form as not being new
	//don't forget to set a new form session var in the newresource.php page
	
	//Refreh the view on save.
	
	//Close the Sql connection
	
	//set the resource as already existing
	$_SESSION["newForm"] = "created";
	
	$conn->close();
	
	?>

<br><br>
<form action="resourceinfo.php" method="post">
<fieldset>
<legend>Add Capability</legend>
Capability: <input type="text" name="add_cap" required / > <br><br>
<input type="submit" value="Add this capability">
</fieldset>
</form>
</body>
</html>
