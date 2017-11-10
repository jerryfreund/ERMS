<?php

$stmt = $conn->prepare("SELECT m.PopulationSize FROM Municipalities m JOIN Users u WHERE m.Username = u.Username AND u.Username = ? UNION ALL SELECT g.Jurisdiction FROM Governments g JOIN Users u WHERE g.Username = u.Username AND 	u.Username = ? UNION ALL SELECT c.Headquarter FROM Companies c JOIN Users u WHERE c.Username = u.Username AND u.Username = ? ");


        $stmt->bind_param("s", $Username);
        $stmt->execute();
        $stmt->bind_result($PopulationSize);
        $stmt->fetch();
        if($PopulationSize){
        echo "<br>";
        echo "$PopulationSize";
        //set session here
        //$_SESSION["user"] =$Username;
        }else{
        //header("location: index.php");
        //die();
		echo "Nothing";}
		
?>
