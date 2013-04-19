<?php
function connect()
{
	$con = mysql_connect("localhost","root","mike9");
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	return $con;
}
function selectDB( $db, $con )
{
	mysql_select_db($db, $con);
}
function checkPassword( $username, $password )
{
	$query = "SELECT * FROM authors WHERE username='" . $username . "'";
	$results = mysql_query( $query );
	$row = mysql_fetch_array( $results );

	if($row['password'] == $password)
	{
		return TRUE;
	}
	echo $row['password'] . " didn't equal " . $password;
	return FALSE;
}
?>