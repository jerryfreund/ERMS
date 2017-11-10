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
	
	$retDate = $_POST["returnDate"]; 
	//$_SESSION["retD"] = $retDate;
	
	//If this is a new form
	if($_SESSION["newForm"] == "new"){
		//Get the POST Variable from the previous form
		$resourceID = $_POST["resourceID"];
		$_SESSION["rID"] = $resourceID;
		$status = "in use";
		$incidentID = $_POST["incidentID"];
		
		
		$stmt = $conn->prepare("UPDATE Resources R
								SET status = ?, R.CurrentResourceUser = ?, DateOfRequest = CURDATE(),
								ExpectedReturnDate = DATE_ADD(CURDATE(),INTERVAL 30 DAY), IncidentID = ?
								WHERE Username = ? AND ResourceID = ?");
		$stmt->bind_param("ssisi", $status, $Username, $incidentID, $Username, $resourceID);
		$stmt->execute();
		$stmt->close();
		
	}
	//Diplay the resource name and expected return date
	echo "Resource are by default requested for 30 days, you can change the return date below! <br><br>";
	
	$reeID = $_SESSION["rID"];
	
	//if the user is adding a return date for the resource then 
		if($retDate != ""){
		$stmt = $conn->prepare("UPDATE  Resources SET ExpectedReturnDate = ? WHERE ResourceID = ?");
		$stmt->bind_param("si", $retDate, $reeID);
		$stmt->execute();
		$stmt->close();
			}
	
	$sql = "SELECT R.ResourceID, R.ResourceName, R.Model, R.ExpectedReturnDate
			FROM Resources R
			WHERE R.ResourceID = $reeID";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
        echo "<table><tr><th>resource ID</th><th>Name</th><th>Model</th><th>Expected Return Date</th></tr>";
        while($row = $result->fetch_assoc()) {
        echo "<tr>
			<td> " . $row["ResourceID"]."</td>
			<td> " . $row["ResourceName"]."</td>
			<td> " . $row["Model"]."</td>
			<td> " . $row["ExpectedReturnDate"]."</td>
			</tr>";
                }
        echo"</table>";
                } else {
        echo "No selected resource!<br><br>";
        echo $sql;
        }

	
	
	$_SESSION["newForm"] = "created";
	
	
?>

<br>
<br>
<form action="deploy.php" method="post">
<fieldset>
<legend>Select a return date</legend>
Date: <input type="date" name="returnDate"> <br><br>
<input type="submit" value="Save">
</fieldset>
</form>
</body>
</html>
