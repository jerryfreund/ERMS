<?php
// Start the session
session_start();
?>

<html>
<?php include 'linkbar.php'; ?>
<br>
<h1>ERMS -- New Resource Form</h1>
	<?php
	require 'session_check.php';
	//Get user ID from the session
	
	
	
	include 'db_conn.php';
	//insert a new resource row
	echo "<br>";
	$sql = "SELECT MAX(ResourceID) AS MaxID FROM Resources";
	
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
   // echo "<table><tr><th>ID</th>";
    // output data of each row
    $row = $result->fetch_assoc();
       // echo "<tr><td>".$row["MaxID"]."</td></tr>";
		$currentID = $row["MaxID"];
   // echo "</table>";
	} else {
    	echo "0 results";
	}
	
	//echo "The current user is :".$_SESSION["user"]."<br>";
	//echo "user full name: ".$_SESSION["fullUserName"]."<br>";
	//Get user ID from the session
	$Username = $_SESSION["user"];
	$currentID = $currentID + 1;
	$_SESSION["resourceID"] = $currentID;
	echo "<br><br><h3>";
	echo "Resource ID :".$currentID."<br></h3>";
	
	
	$sql = "INSERT INTO Resources (Username, ResourceID, ResourceName, ESF_ID, ResourceOwner, CurrentResourceUser, Status, Latitude, Longitude, Model, DateOfRequest, Additional_ESF) VALUES ('$Username', $currentID, NULL, '15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL) ";
	if ($conn->query($sql) === TRUE) {
		//echo "New record created successfully";
		} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	//$conn->close(); //insert the user name...maybe set as an app var from Main menu //Insert form
 ?>
<!--what I added on 1122016 at night -->

<form action="resourceinfo.php" method="post">
<fieldset>
<legend>Resource Information</legend>
    Resource name <input type="text" name="resourcename" required / > <br><br>
    Primary ESF <select name="ESF" size="1" required >
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
	//$conn->close();
	?>
    </select>
    <br>
    <br>
    Additional ESFs <select name="Add_ESF[]" size="15" multiple="multiple">
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
        $conn->close();
        ?>

    </select>
    <br>
    <br>
    Model <input type="text" name="Model" value="" size="50" required /><br><br>
    Capabilities <input type="text" name="capability" value="" size="50" required /><br><br>
    Note: additioanal capabilities can be added after save <br><br>
    <br>
    <table border="0">
        <thead>
            <tr>
                <th>Home Location</th>
                <th>Latitude</th>
                <th>Longitute</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td><input type="text" name="lat" value="" size="50" required /></td>
                <td><input type="text" name="lon" value="" size="50" required /></td>
            </tr>
        </tbody>
    </table>
	<br><br>

    Cost $ <input type="text" name="amount" value="" size="10" required />
    &nbsp;&nbsp; per <select name="unit" required >
        <option>Hour </option>
        <option>Day </option>
        <option>Month </option>
    </select>
	<br>
	<br>

    <input type="submit" value="Save">
</fieldset>
</form>
<?php $_SESSION["newForm"] = "new"; ?>
<!-- end of what I added -->
</html>
