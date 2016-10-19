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
		$result = mysqli_query($con, "SELECT * FROM characters WHERE character_id='$charId'");
		$row = mysqli_fetch_array($result);

		$result_class = mysqli_query($con, "SELECT class_name FROM classes WHERE class_id='{$row["char_class"]}' ;");
		if ($result_class) {
			$class_row = mysqli_fetch_array($result_class);
			$_SESSION["class"] = $class_row["class_name"];
		}

		$result_race = mysqli_query($con, "SELECT race_name FROM races WHERE race_id='{$row["race"]}' ;");
		if ($result_race) {
			$race_row = mysqli_fetch_array($result_race);
			$_SESSION["race"] =  $race_row["race_name"];
		}
	} else {
		header("Location: error.php");
	}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title><?php echo $row["character_name"]; ?></title>
		<meta name="author" content="Alex Hedges" />
	</head>
	<body onload="refresh(false);">
		<?php require("header.php"); ?>
		<h1><?php echo $row["character_name"]; ?></h1>
		<p>Level: <?php echo $row["character_level"]; ?></p>
		<p>Strength: <?php echo $row["str_attr"]; ?></p>
		<p>Intelligence: <?php echo $row["int_attr"]; ?></p>
		<p>Charisma: <?php echo $row["cha_attr"]; ?></p>
		<p>Constitution: <?php echo $row["con_attr"]; ?></p>
		<p>Dexterity: <?php echo $row["dex_attr"]; ?></p>
		<p>Wisdom: <?php echo $row["wis_attr"]; ?></p>
		<p>Weight: <?php echo $row["weight"]; ?> pounds</p>
		<p>Height: <?php echo $row["height"]; ?> inches</p>
		<p>Age: <?php echo $row["age"]; ?></p>
		<p>Religion: <?php echo $row["religion"]; ?></p>
		<p>Gender: <?php echo $row["gender"]; ?></p>
		<p>Class: <?php echo $_SESSION["class"]; ?></p>
		<p>Race: <?php echo $_SESSION["race"]; ?></p>
		<p>Hit Points: <?php echo $row["hit_points"]; ?></p>
		<p>Alignment: <?php echo $row["alignment"]; ?></p>
		<p>Money: $<?php echo $row["money"]; ?></p>

		<a href="edit_character.php?char=<?php echo $charId; ?>">Edit Character</a> |
		<a href="delete_character.php?char=<?php echo $charId; ?>">Delete Character</a>
	</body>
</html>
<?php
}
?>