<?php
// Start the session
session_start();
?>


<html>
<body>

<br><br>
<?php
	$Username = $_POST["Username"];
	$Password = $_POST["Password"];
	if($Username == "" OR $Password == "")
	{
	 //echo("Please log in");
	header("Location: index.php");
	die();
	}
	//Testing the db connection

	$servername = "localhost";
	$usernameDB = "webuser";
	$passwordDB = "wi26wppaEEPN01wz";
        $dbname = "EMSDB";

	// Create connection
	$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);
	
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	} 
	echo "Connected successfully";	
        
        //select the user's password and check if it is equal to the one from the db
        //optimally the passwords are hashed
        //$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$mypassword'";
        //$result=mysql_query($sql);
        
        //$sql = "SELECT Username, Password FROM Users WHERE ($Username == Username) AND ($Password == Password) ";
        //$result = $conn->query($sql);
        
        // prepare and bind
        $stmt = $conn->prepare("SELECT Name FROM Users WHERE ? = Username AND ? = Password");
        $stmt->bind_param("ss", $Username, $Password);
        $stmt->execute();
	$stmt->bind_result($Name);
	$stmt->fetch();
	if($Name){
	echo "<br>";
	echo "$Name";
	//set session here
	$_SESSION["user"] =$Username;
	$_SESSION["fullUserName"] =$Name;
	}else{
	header("location: index.php");
	die();
	}
	/*        
        if ($stmt->rowCount()) {
        // output data of each row
        //while($row = $result->fetch_assoc()) {
        //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
        echo"2016";
        } else {
            echo "0 results";
        }
       */
        $stmt->close();

	//Edited on 11012016

	$stmt = $conn->prepare("SELECT m.PopulationSize FROM Municipalities m JOIN Users u WHERE m.Username = u.Username AND u.Username = ?");


        $stmt->bind_param("s", $Username);
        $stmt->execute();
        $stmt->bind_result($PopulationSize);
        $stmt->fetch();
        if($PopulationSize){
        echo "<br>";
        echo  "Population size: "."$PopulationSize";
        //set session here
        //$_SESSION["user"] =$Username;
        }else{
        //header("location: index.php");
        //die();
		//echo "Nothing";
	}
		$stmt->close();
	//End of 11012016 edition
	//Jurisdiction
	$stmt = $conn->prepare("SELECT g.Jurisdiction FROM Governments g JOIN Users u WHERE g.Username = u.Username AND u.Username = ?");


        $stmt->bind_param("s", $Username);
        $stmt->execute();
        $stmt->bind_result($Jurisdiction);
        $stmt->fetch();
        if($Jurisdiction){
        echo "<br>";
        echo "Jurisdiction: "."$Jurisdiction";
        //set session here
        //$_SESSION["user"] =$Username;
        }else{
        //header("location: index.php");
        //die();
		echo "<br>";}
		$stmt->close();
	//Company
	$stmt = $conn->prepare("SELECT c.Headquarter FROM Companies c JOIN Users u WHERE c.Username = u.Username AND u.Username = ?");


        $stmt->bind_param("s", $Username);
        $stmt->execute();
        $stmt->bind_result($Headquarter);
        $stmt->fetch();
        if($Headquarter){
        echo "<br>";
        echo "Headquarter: "."$Headquarter";
        //set session here
        //$_SESSION["user"] =$Username;
        }else{
        //header("location: index.php");
        //die();
		echo "<br>";}
		$stmt->close();	
        $conn->close();
        
        
?>
<br>
<br>
<fieldset>
<a href="newresource.php">Add Resource</a><br>
<a href="newincident.php">Add Emergency Incident</a><br>
<a href="searchresources.php">Search Resources</a><br>
<a href="resourcestatus.php">Resource Status</a><br>
<a href="resourcereport.php">Resource Report</a><br><br>
<a href="index.php">Exit</a><br>
</fieldset>
</body>
</html>
