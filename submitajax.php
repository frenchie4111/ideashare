<?php
	error_reporting(~E_NOTICE);
	ini_set('display_errors', 'On');

	include "connect.php";
	function displayForm()
	{
		?>
			<form method="post" action="submit.php">
				Idea: <br/>
				<textarea name="idea" rows="5" cols="40"></textarea>
				
				<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6Ld5TtoSAAAAABwreXK5D-N-Bithq46CwCwyvWlu"></script>
				<noscript>
					<iframe src="http://www.google.com/recaptcha/api/noscript?k=6Ld5TtoSAAAAABwreXK5D-N-Bithq46CwCwyvWlu"
					height="300" width="500" frameborder="0"></iframe><br>
					<textarea name="recaptcha_challenge_field" rows="3" cols="40">
					</textarea>
					<input type="hidden" name="recaptcha_response_field"
					value="manual_challenge">
				</noscript>

				<input type="hidden" name="hidden" value="1" />
				<input type="submit" value="Login" />
			</form>
		<?php
	}
	function checkCAPTCHA()
	{
		require_once('recaptchalib.php');
		$privatekey = "6Ld5TtoSAAAAAH7ukKKeljVCCfBTyg63tJspsCgH";
		$resp = recaptcha_check_answer ($privatekey,
		                            $_SERVER["REMOTE_ADDR"],
		                            $_POST["recaptcha_challenge_field"],
		                            $_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
			return FALSE;
			echo ("The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp->error . ")");
		} 
		else 
		{
			return TRUE;
		}
	}
	session_start();
	if( isset($_SESSION['username']) )
	{
		$con = connect();
		selectDB("test", $con);
		$query = "SELECT * FROM authors WHERE username='" . $_SESSION['username'] . "'";
		$results = mysql_query( $query );
		$row = mysql_fetch_array( $results );

		if($row['password'] == $_SESSION['password'])
		{
			if( !isset( $_POST["hidden"] ) && $_POST["hidden"] != "1")
			{
				echo "Nothing";
			}
			else
			{
				if( checkCAPTCHA() ==TRUE )
				{
					if( isset($_POST["parent"]) )
					{
						if( !($_POST["parent"] == "") )
						{
							$in_query = "INSERT INTO ideas(rating, idea, author, parent) VALUES(0, '". mysql_real_escape_string($_POST['idea'],$con) . "'," . $row['id'] . "," . mysql_real_escape_string( $_POST["parent"], $con ) . ")";
							mysql_query( $in_query );
							$up_query = "UPDATE ideas SET childrencount=childrencount+1 WHERE id=" . $_POST["parent"];
							//echo $up_query;
							mysql_query( $up_query );
							echo "done";
						}
					}
					else
					{
						$in_query = "INSERT INTO ideas(rating, idea, author) VALUES(0, '". mysql_real_escape_string($_POST['idea'], $con) . "'," . $row['id'] . ")";
						//mysql_query( $in_query );
						echo "Derp";
					}
				}
				else
				{
					echo "Captcha Incorrect";
				}
			}
		}
		else
		{
		}
	}
	else
	{
	}
?>