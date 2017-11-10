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
	
	//Get user ID from the session
	$Username = $_SESSION["user"];

	//Set the session  param for the subsequent forms
	$_SESSION["newForm"] = "new";	
	$keyword = $_POST["resourcename"];
    $ESF = $_POST["ESF"];
    $dist = $_POST["distance"];
    $incident = $_POST["incident"];
	$_SESSION["incidentID"] = $incident;
	
	if($incident !=""){
		$sql = "SELECT I.IncidentID , I.Description
				FROM Incidents I
				WHERE I.IncidentID = $incident";
	
		$result = $conn->query($sql);
	
		if ($result->num_rows > 0) {
		
		$row = $result->fetch_assoc();
		echo "<br>";
		echo "Search Result for Incident ";
		echo $row["IncidentID"] . "<br>";
		echo $row["Description"]."<br>";
		} else {
			echo "No incident selected <br>";
		}
		
	}
	
	//TODO Change this to a prepared query -- may be vulnerable as an entry point
	$sql = "SELECT
			DISTINCT(ae.ESF_ID),
			R.ResourceID,
			R.ResourceName, R.ResourceOwner,
			concat(C.Dollar,'/', C.Unit) as cost,
			R.Status,
			(CASE when R.Status = 'In repair' then rep.EndDate when R.Status = 'In use' then R.ExpectedReturnDate
			else 'NOW' end ) as NextAvailable,
			R.Latitude, R.Longitude,
			(TRUNCATE(111.045 * DEGREES(ACOS(COS(RADIANS(inc.Latitude))
			* COS(RADIANS(R.Latitude))
			* COS(RADIANS(R.Longitude) - RADIANS(inc.Longitude))
			+ SIN(RADIANS(inc.Latitude))
			* SIN(RADIANS(R.Latitude)))),4) ) as distance, 
			inc.IncidentID
			FROM Resources R
			left join Costs C on R.ResourceID = C.ResourceID
			left join Capabilities ca on ca.ResourceID = R.ResourceID
			left join Repairs rep on rep.ResourceID = R.ResourceID
			left join Additional_ESF ae on ae.ResourceID = R.ResourceID,
			Incidents inc
			WHERE
			(TRUNCATE(111.045 * DEGREES(ACOS(COS(RADIANS(inc.Latitude))
			* COS(RADIANS(R.Latitude))
			* COS(RADIANS(R.Longitude) - RADIANS(inc.Longitude))
			+ SIN(RADIANS(inc.Latitude))
			* SIN(RADIANS(R.Latitude)))),4)) < $dist AND
			 (R.ResourceName like '$keyword%'
			or R.Model like '%$keyword%'
			or ca.Capabilities like '$keyword%' )
			and (R.ESF_ID = $ESF
			or ae.Additional_ESF = $ESF)
			and inc.IncidentID = $incident";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
        echo "<table>
				<tr>
				<th>Resource ID</th>
				<th>ResourceName</th>
				<th>Owner</th>
				<th>Cost</th>
				<th>Status</th>
				<th>Next Available</th>
				<th>Distance in Km</th>
				<th>Action</th>
				</tr>";
        while($row = $result->fetch_assoc()) {
			echo "<tr><td>".$row["ResourceID"]."</td><td>". $row["ResourceName"]."</td><td>".$row["ResourceOwner"]."</td><td>".$row["cost"]."</td><td>".$row["Status"]."</td><td>".$row["NextAvailable"]."</td><td>".$row["distance"];
			echo "</td><td>";
			$rID = $row["ResourceID"];
			$currentStatus = $row["Status"];
			$rOwner =$row["ResourceOwner"];
			$incidentID = $row["IncidentID"];
			//actionButton($currentStatus, $rOwner); //function can be put here
			//if theuser owns the resource and is avail either deploy or repair
			if($Username == $rOwner && $currentStatus != "in repair"){
				//echo "Deploy or Repair";
				echo "<form action=\"deploy.php\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"resourceID\" value=\"$rID\">";
				echo "<input type=\"hidden\" name=\"incidentID\" value=\"$incidentID\">";
				echo "<input type=\"submit\" value=\"Deploy\">";
				echo "</form>";
				echo " ";
				echo "<form action=\"repair.php\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"resourceID\" value=\"$rID\">";
				echo "<input type=\"submit\" value=\"Repair\">";
				echo "</form>";
				
			}
			//if the resource is not owned allow the user to request
			if($Username != $rOwner && $currentStatus != "in repair"){
				//echo "Request";
				echo "<form action=\"request.php\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"resourceID\" value=\"$rID\">";
				echo "<input type=\"submit\" value=\"Request\">";
				echo "</form>";
			}
			//if the resource is in repair say that the resource is in repair
			if($currentStatus == "in repair"){
				echo "The resource is in repair!";
			}
		
			echo "</td></tr>";
					}
			echo"</table>";
                } else {
        echo "No matching resources found!<br><br>";
        echo $sql;
		
		//Added function for Table action
		//Check the status of a resource and display the appropriate action button
		
        }
	
?>
</body>
</html>

