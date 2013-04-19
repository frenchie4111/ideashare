<html>
<head>
<?php
	include "connect.php";
	$con = connect();
	selectDB("test", $con);

	$logged = false;
?>
<link rel="stylesheet" type="text/css" href="indexstyle.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="script.js"></script>
<script type="text/javascript" src="http://api.recaptcha.net/js/recaptcha_ajax.js"></script> 
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
			<?php
				if( !isset( $_GET['order'] ) )
				{
			?>
					<a class="menuitem" href="?order=top">Top</a>
					<a class="menuitem" href="?order=new">New</a>
			<?php
				}
				elseif( $_GET['order'] == 'top' )
				{
					?>
						<a id="menuitemselected">Top</a>
						<a class="menuitem" href="?order=new">New</a>
					<?php
				}
				else
				{
					?>
						<a class="menuitem" href="?order=top">Top</a>
						<a id="menuitemselected">New</a>
					<?php
				}
			?>
			<?php
				if( $logged )
				{
					?>
						<a class="menuitem" href="submit.php">Submit</a>
						<a class="menuitem" href="logout.php">Logout</a>
					<?php
				}
				else
				{
					?>
						<a class="menuitem" href="login.php">Login</a>
						<a class="menuitem" href="signup.php">Sign Up</a>
					<?php
				}
			?>
		</div>
	</div>

	<?php
		$query = "";
		if( ( isset(  $_GET['order'] ) && $_GET['order'] == "top" ) || !isset( $_GET['order'] ) )
		{
			$query = "SELECT ideas.id AS id, ideas.idea AS idea, ideas.rating AS rating, ideas.childrencount AS childrencount, authors.username AS username, authors.voted AS voted FROM ideas JOIN authors ON ideas.author=authors.id WHERE parent=0 ORDER BY rating DESC";
		}
		else
		{
			$query = "SELECT ideas.id AS id, ideas.idea AS idea, ideas.rating AS rating, ideas.childrencount AS childrencount, authors.username AS username, authors.voted AS voted FROM ideas JOIN authors ON ideas.author=authors.id WHERE parent=0 ORDER BY id DESC";
		}
		
		$result = mysql_query($query);

		while($row = mysql_fetch_array($result))
		{
			echo "
				<div class='idea' id='div" . $row['id'] . "'>
					<table>
						<tr><td>
							<div>
								<table>
									<tr><td class='vote'>";
									echo "<div id='u" . $row['id'] . "'>";
										if( isset( $_SESSION['voted'] ) )
										{
											if( in_array( $row['id'], $_SESSION['voted'] ) )
											{
												echo "<a>Up</a>";
											}
											else
											{
												echo "<a href=\"#1\" class=\"up\" id='". $row['id'] ."'>Up</a>";
											}
										}
										else
										{
											echo "<a>Up</a>";
										}
									echo "</div>";
								echo"</td></tr>
									<tr><td class='vote'>
										<a id='r". $row['id'] . "'>" . $row['rating'] . " </a>
									</td></tr>
									<tr><td class='vote'>";
									echo "<div id='d" . $row['id'] . "'>";
										if( isset( $_SESSION['voted'] ) )
										{
											if(  in_array( $row['id'], $_SESSION['voted'] ) )
												{
													echo "<a>Down</a>";
												}
												else
												{
													echo	"<a href=\"#1\" class=\"down\" id='". $row['id'] ."'>Down</a>";
												}
										}
										else
										{
											echo "<a>Down</a>";
										}
									echo "</div>";
								echo "</td></tr>
								</table>
							</div>
						</td> 
						<td style='vertical-align:text-top;' width=100% height=100%>
							<div class='ideatext'>
								<a>" . $row['idea'] . "</a>
							</div>
							<div class='ideabottom'>";
							if( $row['childrencount'] != 0 )
							{
								echo "<span class='ideabottomchildren'><a class='showchildren' id='". $row['id'] ."' onclick=\"expandChildren(". $row['id'] .", 'true')\">Show Inklings</a></span>";
							}
							echo "<span class='ideaaddon' id='ideaaddon" . $row['id'] . "'><a class='ideaaddontext' id='ideaaddontext" . $row['id'] . "' onclick='showAddonBox(" . $row['id'] . ")'>Add An Inkling</a></span>";
							echo "<div class='boxes'> <div id='ideaaddonbox" . $row['id'] . "'></div> <div class='children'> </div> </div>";
							echo"<div class='ideabottomauthor'>
									<a>" . $row['username'] . "</a>
								</div>";
					echo	"</div>
						</td></tr>
					</table>
				</div>\n";
		}
	?>
</body>
</html>