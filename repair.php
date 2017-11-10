<?php
// Start the session
session_start();
?>

<html>
<head>
<?php include 'linkbar.php'; ?>
<br>
<h1>Set Repair</h1>
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
	echo "<br><br>";	
	//Get the user from the current session
	$Username = $_SESSION["user"];
    //Get the resource ID
    $resourceID = $_POST["resourceID"];
    //echo "The resource ID from POST : " . $resourceID."<br>";

    if($_SESSION["newForm"]!= "created"){
        //Save the session resourceID
        $_SESSION["resourceID"] = $resourceID;
        //echo "Session resourceID saved";
    }

    
    //$rID = $_SESSION["$resourceID"];

    //Update the resource and repair tables with repair status
    //Check that the user is connecting to the form for the first time or not
    if($_SESSION["newForm"] == "created"){
        //Update the request table and resource table
        //////////////////////////////////////////////
        //echo "in the created if <br><br>";
        //echo "Username: ".$Username."<br>";
        //echo "Resource ID: ".$resourceID."<br>";
        //echo "Session Resource ID: ".$_SESSION["resourceID"]."<br>";      
        //////////////////////////////////////////////


        $startD = $_POST["startD"];
        $endD = $_POST["endD"];
        //echo "Start Date: ".$startD."<br>";
        //echo "End date: ".$endD."<br>";

        $stmt = $conn->prepare("INSERT INTO Repairs (Username, ResourceID, StartDate, EndDate) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("siss",$_SESSION["user"], $_SESSION["resourceID"], $startD, $endD);
		$stmt->execute();
		$stmt->close();

        //Update the value of resources
        $rID = $_SESSION["resourceID"];

        $stmt = $conn->prepare("UPDATE Resources SET Status = 'in repair', ExpectedReturnDate = ? WHERE Resources.ResourceID = ? ");
		$stmt->bind_param("si", $endD, $_SESSION["resourceID"]);
		$stmt->execute();
		$stmt->close();

        //Display the resource being repaired as table 
        echo "<br><br>";
        echo "Resource in repair: <br>";
        $sql = "SELECT R.ResourceID, R.ResourceName, R.Model, R.ExpectedReturnDate
			FROM Resources R
			WHERE R.ResourceID = $rID";

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
        
    }

?>
<?php 
    //Set the session to being a new form
    $_SESSION["newForm"] = "created";
?>
<form action="repair.php" method="post">
<fieldset>
    <legend>Enter Repair Begin and End Date</legend>
    <table>
        <tr>
        <th>Start Date</th><th>End Date</th>
        </tr>
        <tr>
        <td><input type="date" name="startD" required /></td>
        <td><input type="date" name="endD" required /></td>
        </tr>
    </table>
    <input type="submit" value="Save">
</fieldset>

</<body>
</html>
