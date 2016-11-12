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

	if (isset($_POST["amount"])) {
		$amount = $_POST["amount"];

		if ($item_type === "weapon") {
			mysqli_query($con, "UPDATE characters_weapons SET quantity=$amount WHERE character_id=$char_id AND weapon_id=$item_id");
		} elseif ($item_type === "armor") {
			mysqli_query($con, "UPDATE characters_armor SET quantity=$amount WHERE character_id=$char_id AND armor_id=$item_id");
		} else {
			mysqli_query($con, "UPDATE characters_generic_items SET quantity=$amount WHERE character_id=$char_id AND generic_item_id=$item_id");
		}

		header("Location: character.php?char=$char_id");

	} else {
		if ($item_type === "weapon") {
			$item_result = mysqli_query($con, "SELECT weapon_name as name FROM weapons WHERE weapon_id='$item_id'");
			$item_amt = mysqli_query($con, "SELECT quantity FROM characters_weapons WHERE weapon_id='$item_id'");
		} elseif ($item_type === "armor") {
			$item_result = mysqli_query($con, "SELECT armor_name as name FROM armor WHERE armor_id='$item_id'");
			$item_amt = mysqli_query($con, "SELECT quantity FROM characters_armor WHERE armor_id='$item_id'");
		} else {
			$item_result = mysqli_query($con, "SELECT generic_item_name as name FROM generic_items WHERE generic_item_id='$item_id'");
			$item_amt = mysqli_query($con, "SELECT quantity FROM characters_generic_items WHERE generic_item_id='$item_id'");
		}
		$item_row = mysqli_fetch_array($item_result);
		$item_amt = mysqli_fetch_array($item_amt);
	}
	?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title><?= $item_row["name"]; ?></title>
	</head>
	<body onload="refresh(false);">
	<?php require("header.php"); ?>

	<h1>Add <?= $item_row["name"]; ?> to <?= $char_row["character_name"]; ?></h1>

	<form name="form" method="post">
		<label for="amount">Amount:</label>
		<input name="amount" type="number" value="<?php echo $item_amt['quantity'] ?>" min="0" max="<?php echo PHP_INT_MAX ?>" >

		<input type="submit" value="Update Item">
	</form>

	</body>
</html>
<?php
}
?>