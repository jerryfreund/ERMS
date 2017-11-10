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
</head>
<body>
<?php include 'linkbar.php'; ?>
<br>
<h1>Resource Report</h1>
<br>
<?php require 'session_check.php' ?>
<br>
<?php include 'db_conn.php';
	echo "<br>";
	$sql = "SELECT ESF.ESF_ID,ESF.ESF_DESCRIPTION,count(Resources.ResourceName) AS 'Total Resources',SUM(CASE when STATUS like '%in%use' THEN 1 else 0 END) as inUse FROM ESF left join Resources on ESF.ESF_ID = Resources.ESF_ID GROUP BY ESF.ESF_ID ORDER BY ESF.ESF_ID ";

	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
    	// output data of each row
	echo "<table><tr><th>ESF ID</th><th>ESF Description</th><th>Total Resource</th><th>In Use</th></tr>";
   	 while($row = $result->fetch_assoc()) {
        echo "<tr><td> " . $row["ESF_ID"]. "</td><td> " . $row["ESF_DESCRIPTION"]."</td><td> ".$row["Total Resources"]."</td><td> ".$row["inUse"]. "</td></tr>";
	//echo"</table>";
    		}
	echo"</table>";
		} else {
    	echo "0 results <br><br>";
	echo $sql;
	}    

	$conn->close();

 ?>
</body>
</html>

