<div id="header">
<?php

if (!isset($_SESSION["auth"])) {
	$_SESSION["auth"] = false;
}

if (!$_SESSION["auth"]) {
?>
		<a href="log_in.php">Log In<a>
<?php
} else {
?>
			<p><a href="index.php">Home</a> | 
			User: <?php echo $_SESSION["user"]; ?> | 
			<a href="log_out.php">Log Out</a></p>
<?php
}
?>
		</div>
