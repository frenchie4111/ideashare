<?php
	include "connect.php";
	if( isset($_REQUEST['id']) )
	{
		$con = connect();
		selectDB( "test", $con);
		$ch_query = "SELECT ideas.id AS id, ideas.idea AS idea, ideas.rating AS rating, ideas.childrencount AS childrencount, authors.username AS username FROM ideas JOIN authors ON ideas.author=authors.id WHERE parent=" . $_REQUEST['id'];
		$results = mysql_query( $ch_query );
		echo "<content>\n";
		$row = "asdf";
		try
		{
			$row = mysql_fetch_array($results);
		}
		catch (Exception $e)
		{

		}
		if( !($row == "asdf") )
		{
			echo "<idea>\n";
			echo "<ideatext>\n";
			echo $row['idea'] . "\n";
			echo "</ideatext>\n";
			echo "<author>\n";
			echo $row['username'] . "\n";
			echo "</author>\n";
			echo "<id>\n";
			echo $row['id'] . "\n";
			echo "</id>\n";
			echo "<rating>\n";
			echo $row['rating'] . "\n";
			echo "</rating>\n";
			echo "<childrencount>\n";
			echo $row['childrencount'] . "\n";
			echo "</childrencount>\n";
			echo "</idea>";
		}
		while( $row = mysql_fetch_array($results) )
		{
			echo "\n";
			echo "<idea>\n";
			echo "<ideatext>\n";
			echo $row['idea'] . "\n";
			echo "</ideatext>\n";
			echo "<author>\n";
			echo $row['username'] . "\n";
			echo "</author>\n";
			echo "<id>\n";
			echo $row['id'] . "\n";
			echo "</id>\n";
			echo "<rating>\n";
			echo $row['rating'] . "\n";
			echo "</rating>\n";
			echo "<childrencount>\n";
			echo $row['childrencount'] . "\n";
			echo "</childrencount>\n";
			echo "</idea>";	
		}
		echo "\n";
		echo "</content>";
		header('Content-type: application/xml');
	}
?>