<?php

	/*
	 * file: submit.php
	 * This file contains both the form, and the submission for submitting an idea
	 *
	 * TODO: Make this into an ajax request so the user does not ever have to leave the homepage to submit a story
	 *
	 * Author: Mike Lyons <mdl0394@gmail.com>
	 */

	error_reporting(~E_NOTICE); // Don't show me notices when error reporting
	ini_set('display_errors', 'On'); // Do show me error messages

	include "connect.php"; // Scripts for connecting to database

	/*
	 * function displayForm()
	 * function that prints out the html for the submission form when called.
	 * to be used to not print the form when it is not desired (After the story is submitted)
	 */
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

	/*
	 * Function checkCAPTCH()
	 *
	 * PHP Fucntion that checks if captcha is correct or not
	 *
	 * @return - bool - whether or not the captach was correct
	 */
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
			die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
			     "(reCAPTCHA said: " . $resp->error . ")");
		} 
		else 
		{
			return TRUE;
		}
	}
	session_start();
	if( isset( $_SESSION['username'] ) ) // Checks if user is logged in
	{
		$con = connect(); // Connect to database
		selectDB("test", $con);
		$query = "SELECT * FROM authors WHERE username='" . mysql_real_escape_string($_SESSION['username'], $con) . "'";
		$results = mysql_query( $query );
		$row = mysql_fetch_array( $results );

		if($row['password'] == $_SESSION['password'])
		{
			if( !isset( $_POST["hidden"] ) && $_POST["hidden"] != "1")
			{
				// If the user has not submitted anything, BUT IS LOGGED ON, then show them the form
				displayForm();
			}
			else
			{
				if( checkCAPTCHA() == TRUE ) // If the captcha clears
				{
					if( isset($_GET["parent"]) ) // If a parent is given then add idea as child to parent
					{
						if( !($_GET["parent"] == "") )
						{
							// TODO: Makes sure the idea is also here before submitting through mysql
							$in_query = "INSERT INTO ideas(rating, idea, author, parent) VALUES(0, '". mysql_real_escape_string($_POST['idea'], $con) . "'," . mysql_real_escape_string($row['id'], $con) . "," . mysql_real_escape_string($_GET["parent"],$con) . ")";
							mysql_query( $in_query );
							header("Location: index.php");
						}
					}
					else
					{
						// Otherwise don't give the idea a parent, and threrefore it will show up on the front page
						$in_query = "INSERT INTO ideas(rating, idea, author) VALUES(0, '". mysql_real_escape_string($_POST['idea'], $con) . "'," . mysql_real_escape_string($row['id'], $con) . ")";
						mysql_query( $in_query );
						header("Location: index.php");
					}
				}
				else
				{
					displayForm();
					echo "Invalid Captcha";
				}
			}
		}
		else // If the password is wrong send the user back to the index
		{
			header("Location: index.php");
		}
	}
	else // If the user is not logged in they can not be on this page, so send them back to index
	{
		// TODO: add message that tells them to login before accessing this page
		header("Location: index.php");
	}
?>