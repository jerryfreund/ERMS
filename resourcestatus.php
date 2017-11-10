<?php
// Start the session
session_start();
?>

<html>
<head>
<?php include 'linkbar.php'; ?>
<br>
<h1>Resource Status</h1>
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


    $currentUser = $_SESSION["user"];
    echo "The current user is :".$currentUser ."<br>";
    $condition = $_POST["condition"];
    $rStatus = "in use";
    $resourceID = $_POST["resourceID"]; //3
    $reqName = $_POST["requestor"];
    $incID = $_POST["incidentID"];


    echo "Debug Condition: ".$condition . "<br>";

    if($condition =="returnR"){
        //Update query here
        echo "returning a resource";
        $status = "Available";
        $curResUser = "";
        $stmt = $conn->prepare("UPDATE Resources 
                                SET Status = ?, CurrentResourceUser = ?, ExpectedReturnDate = CURRENT_DATE()
                                WHERE ResourceID = ?");                  
		$stmt->bind_param("ssi", $status, $curResUser, $resourceID);
		$stmt->execute();
		$stmt->close();

    }
    if($condition =="cancelReq"){
        //Cancel a request by the user (me) -2-
        echo "canceling a request I made <br>";
        echo "User: " . $currentUser . "<br>"; //debug
        echo "resourceID: " . $resourceID . "<br>"; //debug
        $stmt = $conn->prepare("DELETE FROM Requests WHERE Username = ? AND ResourceID = ?");                  
		$stmt->bind_param("si", $currentUser, $resourceID);
		$stmt->execute();
		$stmt->close();

    }
    if($condition =="deployR"){
        //Update query here 33333333333333333
        echo "Deploy the requested resource";
        $status = "in use";
        $stmt = $conn->prepare("UPDATE Resources 
                                SET Status = ?, CurrentResourceUser = ?, ExpectedReturnDate = DATE_ADD(CURRENT_DATE(),INTERVAL 30 DAY), IncidentID = ?
                                WHERE ResourceID = ?");                  
		$stmt->bind_param("ssii", $status, $reqName, $incID, $resourceID);
		$stmt->execute();
		$stmt->close();

        //Delete the request since it have been approved -3-
        $stmt = $conn->prepare("DELETE FROM Requests WHERE Username = ? AND ResourceID = ?");                  
		$stmt->bind_param("si", $reqName, $resourceID);
		$stmt->execute();
		$stmt->close();
        
    }
    if($condition =="rejectR"){
        echo "reject the requested resource";
        //Delete the request since it has been rejected -3-
        $stmt = $conn->prepare("DELETE FROM Requests WHERE Username = ? AND ResourceID = ?");                  
		$stmt->bind_param("si", $reqName, $resourceID);
		$stmt->execute();
		$stmt->close();
    }
    if($condition =="cancelR"){
        //Cancelling a repair by deleting it.
        echo "canceling the repair";
        $stmt = $conn->prepare("DELETE FROM Repairs WHERE Username = ? AND ResourceID = ?");                  
		$stmt->bind_param("si", $currentUser, $resourceID);
		$stmt->execute();
		$stmt->close();

        //Setting the resource as being available
        $status = "Available";
        $curResUser = "";
        $stmt = $conn->prepare("UPDATE Resources 
                                SET Status = ?, CurrentResourceUser = ?, ExpectedReturnDate = CURRENT_DATE()
                                WHERE ResourceID = ?");                  
		$stmt->bind_param("ssi", $status, $curResUser, $resourceID);
		$stmt->execute();
		$stmt->close();
        
    }

    //Select -- the resource in use by user ////////////////////////////////////////////////////////////////////////////////////// 111111111111111111111111111111 /////////
    echo "<fieldset>";
    echo "<legend>Resources in Use</legend>";

    $sql = "SELECT
            R.ResourceID, 
            R.ResourceName,
            I.Description,
            R.ResourceOwner,
            R.ExpectedReturnDate
            FROM Resources R
            INNER JOIN Incidents I ON 
            R.IncidentID = I.IncidentID
            WHERE R.Status  = '$rStatus'
            and R.CurrentResourceUser = '$currentUser'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    
    echo "<table>
				<tr>
				<th>ID</th>
				<th>Resource Name</th>
				<th>Incident</th>
				<th>Owner</th>
				<th>Return Date</th>
				<th>Action</th>
				</tr>";
     while($row = $result->fetch_assoc()) {
			echo "<tr>
                    <td>".$row["ResourceID"]."</td>
                    <td>".$row["ResourceName"]."</td>
                    <td>".$row["Description"]."</td>
                    <td>".$row["ResourceOwner"]."</td>
                    <td>".$row["ExpectedReturnDate"]."</td>";
            $resID = $row["ResourceID"];
			echo "<td>
            <form action=\"resourcestatus.php\" method=\"post\">
			<input type=\"hidden\" name=\"resourceID\" value=\"$resID\">
            <input type=\"hidden\" name=\"condition\" value=\"returnR\">
			<input type=\"submit\" value=\"Return\">
			</form>
            </td></tr>";
             }  
        echo "</table>";
         }else {
                echo "No matching resources found!<br><br>";
                //echo $sql;
         }
    
    echo "</fieldset>";
    echo "<br><br>";

    //Select -- show resources requested by the user /////////////////////////////////////////////////////////////////// 222222222222222222222222222 //////////////////

    echo "<fieldset>";
    echo "<legend>Resources Requested by me</legend>";

    $sql = "SELECT 
            R.ResourceID,
            R.ResourceName,
            Incidents.Description,
            R.ResourceOwner,
            R.ExpectedReturnDate
            FROM Resources R
            left join Requests on Requests.ResourceID = R.ResourceID
            left join Incidents on Incidents.IncidentID = Requests.IncidentID
            WHERE Requests.Username = '$currentUser' and Incidents.Username = '$currentUser' and 
            R.ResourceOwner != '$currentUser'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    
    echo "<table>
				<tr>
				<th>ID</th>
				<th>Resource Name</th>
				<th>Description</th>
				<th>Owner</th>
				<th>Return by</th>
				<th>Action</th>
				</tr>";
     while($row = $result->fetch_assoc()) {
			echo "<tr>
                    <td>".$row["ResourceID"]."</td>
                    <td>".$row["ResourceName"]."</td>
                    <td>".$row["Description"]."</td>
                    <td>".$row["ResourceOwner"]."</td>
                    <td>".$row["ExpectedReturnDate"]."</td>";
            $resID = $row["ResourceID"];
			echo "<td>
            <form action=\"resourcestatus.php\" method=\"post\">
			<input type=\"hidden\" name=\"resourceID\" value=\"$resID\">
            <input type=\"hidden\" name=\"condition\" value=\"cancelReq\">
			<input type=\"submit\" value=\"Cancel\">
			</form>
            </td></tr>";
             }  
        echo "</table>";
         }else {
                echo "No matching resources found!<br><br>";
                //echo $sql;
         }
    
    echo "</fieldset>";
    echo "<br><br>";

    //Select -- show resources requests received by the user ////////////////////////////////////////////////////////////////////// 33333333333333333333333333333333333333 ////
    echo "<fieldset>";
    echo "<legend>Resource Requests Received by me</legend>";

    $sql = "SELECT
            R.ResourceID,
            R.ResourceName,
            Incidents.IncidentID,
            Incidents.Description,
            Req.Username,
            R.ExpectedReturnDate, R.Status
            FROM Resources R 
            Left join Requests Req on Req.ResourceID = R.ResourceID
            left join Incidents on Incidents.IncidentID = Req.IncidentID
            WHERE R.ResourceOwner = '$currentUser' and Req.Username != '$currentUser'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    
    echo "<table>
				<tr>
				<th>ID</th>
				<th>Resource Name</th>
				<th>Incident</th>
				<th>Requested By</th>
				<th>Return by</th>
				<th>Action(s)</th>
				</tr>";
     while($row = $result->fetch_assoc()) {
			echo "<tr>
                    <td>".$row["ResourceID"]."</td>
                    <td>".$row["ResourceName"]."</td>
                    <td>".$row["Description"]."</td>
                    <td>".$row["Username"]."</td>
                    <td>".$row["ExpectedReturnDate"]."</td>";
                    //Add an if statement to check if the resource is in use...
                    //Get the resource's current status.
                    $resourceStatus = $row["Status"];
                    $resID = $row["ResourceID"];
                    $requestor = $row["Username"];
                    $incidentID = $row["IncidentID"];
			echo "<td>";

            //if the resource is not in use then display the deploy button
            if($resourceStatus != "in use"){
                echo "<form action=\"resourcestatus.php\" method=\"post\">
                <input type=\"hidden\" name=\"resourceID\" value=\"$resID\">
                <input type=\"hidden\" name=\"condition\" value=\"deployR\">
                <input type=\"hidden\" name=\"requestor\" value=\"$requestor\">
                <input type=\"hidden\" name=\"incidentID\" value=\"$incidentID\">
                <input type=\"submit\" value=\"Deploy\">
                </form>";
            }
            echo "<form action=\"resourcestatus.php\" method=\"post\">
			<input type=\"hidden\" name=\"resourceID\" value=\"$resID\">
            <input type=\"hidden\" name=\"condition\" value=\"rejectR\">
			<input type=\"submit\" value=\"Reject\">
			</form>";

            echo "</td></tr>";
             }  
        echo "</table>";
         }else {
                echo "No matching resources found!<br><br>";
                //echo $sql;
         }
    
    echo "</fieldset>";
    echo "<br><br>";
    //Select resources in repair schedule or in process /////////////////////////////////////////////////////////////////////// 44444444444444444444444444444444 //////////////

    echo "<fieldset>";
    echo "<legend>Repairs Scheduled - In Progress</legend>";

    $sql = "SELECT
            R.ResourceID,
            R.ResourceName,
            Repairs.StartDate,
            Repairs.EndDate,
            (CASE when Repairs.StartDate > CURRENT_DATE THEN 'Scheduled'
            when Repairs.StartDate = CURRENT_DATE and Repairs.EndDate > CURRENT_DATE then 'Now'
            when Repairs.StartDate < CURRENT_DATE and Repairs.EndDate<CURRENT_DATE then 'Repaired' END) as status
            FROM Resources R
            RIGHT join Repairs on Repairs.ResourceID = R.ResourceID
            WHERE R.Username = '$currentUser'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    
    echo "<table>
				<tr>
				<th>ID</th>
				<th>Resource Name</th>
				<th>Start On</th>
				<th>ready by</th>
				<th>Action</th>
				</tr>";
     while($row = $result->fetch_assoc()) {
			echo "<tr>
                    <td>".$row["ResourceID"]."</td>
                    <td>". $row["ResourceName"]."</td>
                    <td>".$row["StartDate"]."</td>
                    <td>".$row["EndDate"]."</td>";
            $resID = $row["ResourceID"];
            $repairStatus = $row["status"];
			echo "<td>";
            if($repairStatus == "Scheduled"){
                    echo "<form action=\"resourcestatus.php\" method=\"post\">
			        <input type=\"hidden\" name=\"resourceID\" value=\"$resID\">
                    <input type=\"hidden\" name=\"condition\" value=\"cancelR\">
			        <input type=\"submit\" value=\"Cancel\">
			        </form>";
            }
            if($repairStatus == "Repaired"){
                echo "Repair Completed!";
            }
            if($repairStatus == "Now"){
                echo "Repair In Progress!";
            }

            echo "</td></tr>";
             }  
        echo "</table>";
         }else {
                echo "No matching resources found!<br><br>";
                //echo $sql;
         }
    
    echo "</fieldset>";
    echo "<br><br>";


?>




