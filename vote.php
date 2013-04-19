<?php
	error_reporting(~E_NOTICE);
	include "connect.php";
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


		//echo $up_query;
	}
	$con = connect();
	selectDB( "test", $con );
	$query = "SELECT * FROM ideas WHERE id=" . $_REQUEST['id'] . "";
	//echo $query;
	$results = mysql_query( $query );
	$row = mysql_fetch_array( $results );
	echo $row['rating'];
?>