<?php
    // Very simple logout script, just destroys the session and redirects back to the homepage
	session_start();
	session_destroy();
	header("Location: index.php")
?>