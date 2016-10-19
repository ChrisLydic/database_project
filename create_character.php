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

$class_array = array();
$race_array = array();

$result_classes = mysqli_query($con, "SELECT class_id, class_name FROM classes ;");
if ($result_classes) {
	while ($row = mysqli_fetch_array($result_classes)) {
		$class_array[$row["class_id"]] = $row["class_name"];
	}
	$_SESSION["classes"] = $class_array;
}

$result_races = mysqli_query($con, "SELECT race_id, race_name FROM races ;");
if ($result_races) {
	while ($row = mysqli_fetch_array($result_races)) {
		$race_array[$row["race_id"]] = $row["race_name"];
	}
	$_SESSION["races"] = $race_array;
}

// check if all form data exists
$is_form_full = !empty($_POST["character_name"])
	&& isset($_POST["character_level"])
	&& isset($_POST["str_attr"])
	&& isset($_POST["int_attr"])
	&& isset($_POST["cha_attr"])
	&& isset($_POST["con_attr"])
	&& isset($_POST["dex_attr"])
	&& isset($_POST["wis_attr"])
	&& isset($_POST["weight"])
	&& isset($_POST["height"])
	&& isset($_POST["age"])
	&& !empty($_POST["religion"])
	&& !empty($_POST["gender"])
	&& isset($_POST["char_class"])
	&& isset($_POST["race"])
	&& isset($_POST["hit_points"])
	&& !empty($_POST["alignment"])
	&& isset($_POST["money"]);

if ($is_form_full) {
	$form_array = array();
	$form_array["character_name"] = mysqli_real_escape_string($con, $_POST["character_name"]);
	$form_array["character_level"] = $_POST["character_level"];
	$form_array["str_attr"] = $_POST["str_attr"];
	$form_array["int_attr"] = $_POST["int_attr"];
	$form_array["cha_attr"] = $_POST["cha_attr"];
	$form_array["con_attr"] = $_POST["con_attr"];
	$form_array["dex_attr"] = $_POST["dex_attr"];
	$form_array["wis_attr"] = $_POST["wis_attr"];
	$form_array["weight"] = $_POST["weight"];
	$form_array["height"] = $_POST["height"];
	$form_array["age"] = $_POST["age"];
	$form_array["religion"] = mysqli_real_escape_string($con, $_POST["religion"]);
	$form_array["gender"] = mysqli_real_escape_string($con, $_POST["gender"]);
	$form_array["char_class"] = $_POST["char_class"];
	$form_array["race"] = $_POST["race"];
	$form_array["hit_points"] = $_POST["hit_points"];
	$form_array["alignment"] = mysqli_real_escape_string($con, $_POST["alignment"]);
	$form_array["money"] = $_POST["money"];

	$res = mysqli_query($con, "SELECT user_id FROM users WHERE username = '{$_SESSION["user"]}' ;");
	if ($res) {
		while ($row = mysqli_fetch_array($res)) {
			$form_array["user_id"] = $row["user_id"];
		}
	} else {
		header("Location: index.php");
	}

	if ( is_valid($con, $form_array) ) {
		$insert_str = "INSERT INTO characters (";
		$values_str = "VALUES (";

		foreach ( $form_array as $key => $value ) {
			if ( $key === "user_id" ) {
				$insert_str = $insert_str . $key . ")";
				$values_str = $values_str . "'" . $value . "')";
			} else {
				$insert_str = $insert_str . $key . ",";
				$values_str = $values_str . "'" . $value . "',";
			}
		}

		mysqli_query($con, "$insert_str $values_str;");

		header("Location: index.php");
	} # doesn't do anything if invalid because invalid form data would require user to subvert html form
}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title>Create Character</title>
		<script type="text/javascript">
		//<![CDATA[
//			function validateForm() {
//			}
		//]]>
		</script>

		<style>
			label, input, select { display: block; font-size: 1rem; }
			input, select { margin: 0 0 20px 0; padding: 5px; }
			label { margin: 0 0 5px 0; }
		</style>
	</head>
	<body>
		<?php require("header.php"); ?>
		<h1>Create New Character</h1>

		<form name="form" method="post">

			<label for="character_name">Name:</label>
			<input type="text" name="character_name" placeholder="Name" maxlength="50" required="true">
			
			<label for="character_level">Level:</label>
			<input type="number" name="character_level" value="1" min="1" max="100">
			
			<label for="str_attr">Strength:</label>
			<input type="number" name="str_attr" value="0" min="0" max="100">
			
			<label for="int_attr">Intelligence:</label>
			<input type="number" name="int_attr" value="0" min="0" max="100">
			
			<label for="cha_attr">Charisma:</label>
			<input type="number" name="cha_attr" value="0" min="0" max="100">
			
			<label for="con_attr">Constitution:</label>
			<input type="number" name="con_attr" value="0" min="0" max="100">
			
			<label for="dex_attr">Dexterity:</label>
			<input type="number" name="dex_attr" value="0" min="0" max="100">
			
			<label for="wis_attr">Wisdom:</label>
			<input type="number" name="wis_attr" value="0" min="0" max="100">
			
			<label for="weight">Weight:</label>
			<input type="number" name="weight" value="1" min="1" max="100">
			
			<label for="height">Height:</label>
			<input type="number" name="height" value="1" min="1" max="100">
			
			<label for="age">Age:</label>
			<input type="number" name="age" value="1" min="1" max="100">
			
			<label for="religion">Religion:</label>
			<input type="text" name="religion" placeholder="Religion" maxlength="20" required="true">

			<label for="gender">Gender:</label>
			<input type="text" name="gender" placeholder="Gender" maxlength="10" required="true">

			<label for="char_class">Class:</label>
			<select name="char_class">
				<?php
					$array = $_SESSION["classes"];
					foreach ($array as $key => $value) {
				?>
					<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php
					}
				?>
			</select>

			<label for="race">Race:</label>
			<select name="race">
				<?php
					$array = $_SESSION["races"];
					foreach ($array as $key => $value) {
				?>
					<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php
					}
				?>
			</select>

			<label for="hit_points">Hit Points:</label>
			<input type="number" name="hit_points" value="0" min="0" max="100">

			<label for="alignment">Alignment:</label>
			<select name="alignment">
				<option value="LG">Lawful Good</option>
				<option value="NG">Neutral Good</option>
				<option value="CG">Chaotic Good</option>
				<option value="LN">Lawful</option>
				<option value="N">Neutral</option>
				<option value="CN">Chaotic Neutral</option>
				<option value="LE">Lawful Evil</option>
				<option value="NE">Neutral Evil</option>
				<option value="CE">Chaotic Evil</option>
			</select>

			<label for="money">Money:</label>
			<input type="number" name="money" value="0" min="0" max="10000000">
			
			<input type="submit" value="Submit" />

		</form>
	</body>
</html>
<?php
mysqli_close($con);
?>