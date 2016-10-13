<?php
session_start();

if (!isset($_SESSION["auth"])) {
	$_SESSION["auth"] = false;
}

if (!$_SESSION["auth"]) {
	header("Location: log_in.php");
} else {
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title>Welcome!</title>
		<meta name="author" content="Alex Hedges" />
	</head>
	<body>
		<?php require("header.php"); ?>
		<h1>Welcome, <?php echo $_SESSION["user"]; ?>!</h1>
		<h2>Available Chats</h2>
		<ul>
<?php
			$array = $_SESSION["allowed"];
			foreach ($array as $key => $value) {
?>
			<li><a href="chat.php?b=<?php echo $key; ?>"><?php echo $value; ?></a></li>
<?php
			}
?>
		</ul>
	</body>
</html>
<?php
}
?>