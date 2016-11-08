<?php
session_start();

if (!$_SESSION["auth"]) {
	$_SESSION["auth"] = false;
	header("Location: log_in.php");
} else {
	if (isset($_GET["char"])) {
		$char_id = $_GET["char"];
	} else {
		header("Location: error.php");
	}

	require("db_open.php");

	if (isset($_GET["item"])) {
		$item_id = $_GET["item"];

		mysqli_query($con, "DELETE FROM characters_generic_items WHERE character_id=$char_id AND generic_item_id=$item_id");

	} elseif (isset($_GET["weapon"])) {
		$item_id = $_GET["weapon"];

		mysqli_query($con, "DELETE FROM characters_weapons WHERE character_id=$char_id AND weapon_id=$item_id");

	} elseif (isset($_GET["armor"])) {
		$item_id = $_GET["armor"];

		mysqli_query($con, "DELETE FROM characters_armor WHERE character_id=$char_id AND armor_id=$item_id");

	}

	header("Location: character.php?char=$char_id");
}
?>