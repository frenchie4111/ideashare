<html>
<head>
<?php
	
	/*
	 * 
	 */

	include "connect.php";
	$con = connect();
	selectDB("test", $con);
?>
<link rel="stylesheet" type="text/css" href="profilestyle.css">
</head>
<body>
	<div id="top">
		<span id="title">
			<a id="titletext" href="index.php">ideashare</a>
			<?php  
				session_start();
				if( isset( $_SESSION['username'] ) )
				{
					echo "- " . $_SESSION['username'];
					$logged = true;
				}
			?>
		</span>

		<div id="menubar">
			<a class="menuitem"></a>
			<a class="menuitem" href="index.php">Home</a>
		</div>
	</div>
	<div id="userbar">
		<a>Viewing User: </a>
		<a id="username">Frenchie</a>

		<div id="usermenubar">
			<a class="usermenuitem">Posts</a>
			<a class="usermenuitem">Inklings</a>
			<a class="usermenuitem">Message</a>
		</div>
	</div>
	<div id="content">
		<?php
			if( isset($_GET['show']) )
			{
				if( $_GET['show'] == 'posts' )
				{
					$query = "SELECT * FROM ideas WHERE author=" . $id;
					$results = mysql_query( $query );
					while( $row = mysql_fetch_array( $results ) )
					{
						<div class="idea">
							
						</div>
					}
				}
			}
			else
			{

			}
		?>
	</div>
</body>
</html>
