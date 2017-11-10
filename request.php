<?php
// Start the session
session_start();
?>

<html>
<head>
<?php include 'linkbar.php'; ?>
<br>
<h1>Search Results</h1>
<style>
<!-- This page requests the resoursource -->

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
</head>
<body>

<?php
	require 'session_check.php';
	include 'db_conn.php';
	echo "<br>";	
	//Get the user from the current session
	$Username = $_SESSION["user"];
	//$retDate = $_POST["returnDate"]; 
	//$_SESSION["retD"] = $retDate;

	//Get the POST Variable from the previous form
	$resourceID = $_POST["resourceID"];
	$_SESSION["rID"] = $resourceID;
	//$_SESSION["incidentID"] = $incidentID;
	$incidentID = $_SESSION["incidentID"];

	$stmt = $conn->prepare("INSERT INTO Requests (Username, IncidentID, ResourceID, ReturnDate)
							VALUES (?, ?, ?, DATE_ADD(CURDATE(),INTERVAL 30 DAY))");
		$stmt->bind_param("sii", $Username, $incidentID, $resourceID);
		$stmt->execute();
		$stmt->close();

	//Diplay the resource name and expected return date
	
	$reeID = $_SESSION["rID"];
	
	echo "<fieldset>";
	echo "<legend>You requested the following resource</legend>";
	$sql = "SELECT R.ResourceID, R.ResourceName, R.Model, R.ExpectedReturnDate
			FROM Resources R
			WHERE R.ResourceID = $reeID";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
        echo "<table><tr><th>resource ID</th><th>Name</th><th>Model</th></tr>";
        while($row = $result->fetch_assoc()) {
        echo "<tr>
			<td> " . $row["ResourceID"]."</td>
			<td> " . $row["ResourceName"]."</td>
			<td> " . $row["Model"]."</td>
			</tr>";
                }
        echo"</table>";
                } else {
        echo "No selected resource!<br><br>";
        echo $sql;
        }
		
	echo "</fieldset>";

?>

</body>
</html>
