<?php

	/*
	 * file: vote.php
	 * Script that is intended to be called by ajax that takes an idea and adds one to it's vote count.
	 *
	 * Also returns vote count of given post id, whether or not you asked told it to vote
	 *
	 * Author: Mike Lyons <mdl0394@gmail.com>
	 */

	error_reporting(~E_NOTICE);
	include "connect.php"; // Connects to the database
	if( isset($_POST['id']) && isset($_POST['vote']) )
	{
		session_start();
		if( isset( $_SESSION['username'] ) )
		{
			$con = connect();
			selectDB( "test", $con );

			if( checkPassword( $_SESSION['username'], $_SESSION['password'] ) )
			{
				$up_query = "";
				if( $_POST['vote'] == "up" )
				{
					$up_query = "UPDATE ideas SET rating=rating+1 WHERE id=" . $_POST['id'];
				}
				elseif( $_POST['vote'] == "down" )
				{
					$up_query = "UPDATE ideas SET rating=rating-1 WHERE id=" . $_POST['id'];
				}
				mysql_query( $up_query );

				$up_usr_query = "UPDATE authors SET voted=CONCAT(voted, '," . $_POST['id'] . "') WHERE username='" . $_SESSION['username'] . "'";
				//echo $up_usr_query;
				mysql_query( $up_usr_query );

				array_push( $_SESSION['voted'], $_POST['id'] );
			}
		}
	}
	$con = connect();
	selectDB( "test", $con );
	$query = "SELECT * FROM ideas WHERE id=" . $_REQUEST['id'] . "";

	$results = mysql_query( $query );
	$row = mysql_fetch_array( $results );
	echo $row['rating'];
?>