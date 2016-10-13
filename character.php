<?php
session_start();

if (!$_SESSION["auth"]) {
	$_SESSION["auth"] = false;
	header("Location: log_in.php");
} else {
	if (isset($_GET["char"])) {
		$charId = $_GET["char"];
	} else {
		header("Location: error.php");
	}

	if (isset($_SESSION["allowed"][$charId])) {
		require("db_open.php");
		$result = mysqli_query($con, "SELECT DisplayName, FileName FROM Characters WHERE CharacterId='$charId'");
		$row = mysqli_fetch_array($result);
		$display = $row["DisplayName"];
		//$file = $row["FileName"];
	} else {
		header("Location: error.php");
	}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title>Chat</title>
		<meta name="author" content="Alex Hedges" />
	</head>
	<body onload="refresh(false);">
		<?php require("header.php"); ?>
		<h1><?php echo $display; ?></h1>
	</body>
</html>
<?php
}
?>