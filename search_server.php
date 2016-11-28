<?php
session_start();

if (!$_SESSION["auth"]) {
	$_SESSION["auth"] = false;
	header("Location: log_in.php");
} else {
	if (isset($_POST["query"])) {
		$query = $_POST["query"];
	} else {
		$query = "";
	}

	require("db_open.php");

	$query = mysqli_real_escape_string($con, $_GET["q"]);

	$result = mysqli_query($con, "SELECT character_name, character_id FROM characters WHERE character_name LIKE '%$query%' ORDER BY character_name");
	if ($result) {
		$results = mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	
	if (empty($results)) {
		$response = "<p>No results found.</p>";
	} else {
		$response = "<ul>";

		foreach ($results as $value) {
			$response .= "<li><a href=\"character.php?char={$value["character_id"]}\">{$value["character_name"]}</li>";
		}

		$response .= "</ul>";
	}
	
	echo $response;
}
?>