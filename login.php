<?php
	error_reporting(E_ALL);

	include "connect.php";
	function displayForm()
	{
		?>
			<form method="post" action="login.php">
				Username: <input type="text" name="username" /> <br/>
				Password: <input type="password" name="password" /> <br/>
				<input type="submit" value="Login" />
			</form>
		<?php
	}
	if( !isset( $_POST['username'] ) )
	{
		displayForm();
	}
	else
	{
		$con = connect();
		selectDB( "test", $con);

		$query = "SELECT * FROM authors WHERE username='" . $_POST['username'] . "'";
		
		$result = mysql_query( $query );

		$row = mysql_fetch_array($result);

		if( $row['password'] == md5( $_POST['password'] ) )
		{
			session_start();
			$_SESSION['username'] = $row['username'];
			$_SESSION['password'] = $row['password'];
			$_SESSION['voted'] = explode(",", $row['voted']);

			echo("Logged in as " . $_POST['username']);
			echo("<a href=\"index.php\"> Click here to go back </a>");
			header( 'Location: index.php' );
		}
		else
		{
			echo "Invalid Login information <br/>";
			displayForm();
		}
	}
?>