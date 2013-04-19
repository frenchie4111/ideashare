<?php

	/*
	 * file: children.php
	 * Get's information about an ideas children and prints (echo) the information as an xml document
	 *
	 * Author: Mike Lyons <mdl0394@gmail.com>
	 */

	include "connect.php";
	if( isset($_REQUEST['id']) )
	{
		$con = connect();
		selectDB( "test", $con);
		$ch_query = "SELECT ideas.id AS id, ideas.idea AS idea, ideas.rating AS rating, ideas.childrencount AS childrencount, authors.username AS username FROM ideas JOIN authors ON ideas.author=authors.id WHERE parent=" . $_REQUEST['id'];
		$results = mysql_query( $ch_query );
		echo "<content>\n";
		$row = "asdf"; // Start the row as asdf so it can be checked later on if the row retrieval was succesful
		try
		{
			$row = mysql_fetch_array($results);
		}
		catch (Exception $e)
		{

		}
		// TODO: Combine this into one loop and elimate code copying between if statement and while loop
		if( !($row == "asdf") ) // If row retrieval successful
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
		while( $row = mysql_fetch_array($results) ) // Output results
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