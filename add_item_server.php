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
	
	$query = mysqli_real_escape_string($con, $_GET["q"]);
	$search_type = mysqli_real_escape_string($con, $_GET["type"]);

	if ($search_type === "weapon") {
		$result = mysqli_query($con, "SELECT weapon_id AS id, weapon_name AS name FROM weapons WHERE weapon_name LIKE '%$query%' ORDER BY weapon_name");
	} elseif ($search_type === "armor") {
		$result = mysqli_query($con, "SELECT armor_id AS id, armor_name AS name FROM armor WHERE armor_name LIKE '%$query%' ORDER BY armor_name");
	} else {
		$result = mysqli_query($con, "SELECT generic_item_id AS id, generic_item_name AS name FROM generic_items WHERE generic_item_name LIKE '%$query%' ORDER BY generic_item_name");
	}

	if ($result) {
		$results = mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	
	if (empty($results)) {
		$response = "<p>No results found.</p>";
	} else {
		$response = "<ul>";

		foreach ($results as $value) {
			$response .= "<li><a href=\"adding_item.php?char=$char_id&item={$value["id"]}&type=$search_type\">{$value["name"]}</a></li>";
		}

		$response .= "</ul>";
	}
	
	echo $response;
}
?>