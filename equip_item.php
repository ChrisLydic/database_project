<?php
session_start();

if (!$_SESSION["auth"]) {
	$_SESSION["auth"] = false;
	header("Location: log_in.php");
} else {
	if (isset($_GET["char"]) && isset($_GET["equip"])) {
		$char_id = intval($_GET["char"]);
		$equip = $_GET["equip"];
	} else {
		header("Location: error.php");
	}

	require("db_open.php");

	if (isset($_GET["weapon"])) {
		$weapon = intval($_GET["weapon"]);

		if ($equip === 'true') {
			mysqli_query($con, "UPDATE characters_weapons SET location='EQUIPPED' WHERE character_id=$char_id AND weapon_id=$weapon");
		} else {
			mysqli_query($con, "UPDATE characters_weapons SET location='UNEQUIPPED' WHERE character_id=$char_id AND weapon_id=$weapon");
		}

	} elseif (isset($_GET["armor"])) {
		$armor = $_GET["armor"];

		if ($equip === 'true') {
			mysqli_query($con, "UPDATE characters_armor SET location='EQUIPPED' WHERE character_id=$char_id AND armor_id=$armor");
		} else {
			mysqli_query($con, "UPDATE characters_armor SET location='UNEQUIPPED' WHERE character_id=$char_id AND armor_id=$armor");
		}
	}

	header("Location: character.php?char=$char_id");
}
?>