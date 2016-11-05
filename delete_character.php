<?php
session_start();

if (!isset($_SESSION["auth"])) {
	$_SESSION["auth"] = false;
}

if (!$_SESSION["auth"]) {
	header("Location: log_in.php");
}

require("db_open.php");
require("character_utils.php");

if (isset($_GET["char"])) {
	$charId = $_GET["char"];
} else {
	header("Location: error.php");
}

if (isset($_SESSION["allowed"][$charId])) {
	require("db_open.php");
	$result = mysqli_query($con, "DELETE FROM characters WHERE character_id='$charId'");
	$row = mysqli_fetch_array($result);
	header("Location: index.php");
} else {
	header("Location: error.php");
}

mysqli_close($con);
?>