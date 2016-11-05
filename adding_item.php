<?php
session_start();

if (!$_SESSION["auth"]) {
	$_SESSION["auth"] = false;
	header("Location: log_in.php");
} else {
	if (isset($_GET["char"]) && isset($_GET["item"]) && isset($_GET["type"])) {
		$char_id = $_GET["char"];
		$item_id = $_GET["item"];
		$item_type = $_GET["type"];
	} else {
		header("Location: error.php");
	}

	require("db_open.php");

	$char_result = mysqli_query($con, "SELECT * FROM characters WHERE character_id='$char_id'");
	$char_row = mysqli_fetch_array($char_result);

	if (isset($_POST["search_type"])) {
		$search_type = $_POST["search_type"];

		if ($search_type === "weapon") {
			$result = mysqli_query($con, "SELECT weapon_id as id, weapon_name as name FROM weapons");
		} elseif ($search_type === "armor") {
			$result = mysqli_query($con, "SELECT armor_id as id, armor_name as name FROM armor");
		} else {
			$result = mysqli_query($con, "SELECT generic_item_id as id, generic_item_name as name FROM generic_items");
		}

	} else {
		$search_type = "item";
		$result = mysqli_query($con, "SELECT generic_item_id as id, generic_item_name as name FROM generic_items");
	}
	?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title><?= $row["character_name"]; ?></title>
		<meta name="author" content="Alex Hedges" />
	</head>
	<body onload="refresh(false);">
	<?php require("header.php"); ?>

	<h1>Add Items | <?= $char_row["character_name"]; ?></h1>

	<form name="form" method="post">
s		<select name="search_type">
			<option value="item">Generic Items</option>
			<option value="weapon">Weapons</option>
			<option value="armor">Armor</option>
		</select>

		<input type="submit" value="Search">
	</form>

	</body>
</html>
<?php
}
?>