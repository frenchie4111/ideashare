<?php

include "connect.php";

$con = connect();
selectDB( "test", $con );


$result = mysql_query("SELECT * FROM ideas");

while($row = mysql_fetch_array($result))
{
	echo $row['id'] . " " . $row['idea'];
	echo "<br />";
}

mysql_close($con);
?>