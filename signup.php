<?php
	error_reporting(~E_NOTICE);
	ini_set('display_errors', 'On');

	include "connect.php";
	function displayForm()
	{
		?>
			<form method="post" action="signup.php">
				Username: <input type="text" name="username" /> <br/>
				Password: <input type="password" name="password" /> <br/>
				Retype Password: <input type="password" name="password2" /> <br/>
				Email: <input type="text" name="email" /> <br/>
				<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6Ld5TtoSAAAAABwreXK5D-N-Bithq46CwCwyvWlu"></script>
				<noscript>
					<iframe src="http://www.google.com/recaptcha/api/noscript?k=6Ld5TtoSAAAAABwreXK5D-N-Bithq46CwCwyvWlu"
					height="300" width="500" frameborder="0"></iframe><br>
					<textarea name="recaptcha_challenge_field" rows="3" cols="40">
					</textarea>
					<input type="hidden" name="recaptcha_response_field"
					value="manual_challenge">
				</noscript>
				<input type="submit" value="Login" />
			</form>
		<?php
	}
	if( !isset( $_POST['username'] ) && $_POST['username'] != "")
	{
		displayForm();
	}
	else
	{
		require_once('recaptchalib.php');
		$privatekey = "6Ld5TtoSAAAAAH7ukKKeljVCCfBTyg63tJspsCgH";
		$resp = recaptcha_check_answer ($privatekey,
		                            $_SERVER["REMOTE_ADDR"],
		                            $_POST["recaptcha_challenge_field"],
		                            $_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
			displayForm();
			die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
			     "(reCAPTCHA said: " . $resp->error . ")");
		} 
		else 
		{
		// Your code here to handle a successful verification
			$con = connect();
			selectDB( "test", $con);

			$query = "SELECT * FROM authors WHERE username='" . $_POST['username'] . "'";
			
			$result = mysql_query( $query );
			

			$row_count = 0;
			while( $row = mysql_fetch_array($result) )
			{
				$row_count++;
			}
			if( $row_count == 0 )
			{
				if( isset( $_POST['username'] ) && $_POST['username'] != "" && isset( $_POST['password'] ) && $_POST['password'] != "" && isset( $_POST['password2'] ) && $_POST['password2'] != "" && isset( $_POST['email'] ) && $_POST['email'] != "" )
				{
					if( strpos( $_POST['email'], "@" ) !== false )
					{
						if( $_POST['password'] == $_POST['password2'] )
						{
							$in_query = "INSERT INTO authors(username, password, email) VALUES('". $_POST['username'] ."','". md5( $_POST['password'] ) . "','" . $_POST['email'] . "')";
							echo $in_query;
							mysql_query( $in_query );
							echo("Created user: " . $_POST['username']);
							echo("<br/><a href=\"index.php\"> Click here to go back </a>");
							session_start();
							$_SESSION['username'] = $_POST['username'];
							$_SESSION['password'] = md5( $_POST['password'] );
							header( 'Location: index.php' );
						}
						else
						{
							echo "Passwords do not match <br/>";
							displayForm();
						
						}
					}
					else
					{
						echo "Invalid Email";
						displayForm();
					}
				}
				else
				{
					echo "Missing information <br/>";
					displayForm();
				}
			}
			else
			{
				echo "Username already exists <br/>";
				displayForm();
			}
		}
	}
?>