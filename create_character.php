<?php
session_start();

require("db_open.php");

$duplicate = false;

if (isset($_POST["user"]) && isset($_POST["pass"])) {
	$user = mysqli_real_escape_string($con, $_POST["user"]); //htmlentities
	$pass = mysqli_real_escape_string($con, sha1($_POST["pass"]));
	
	$result = mysqli_query($con, "SELECT username FROM users");
	if ($result) {
		while ($row = mysqli_fetch_array($result)) {
			if ($row["username"] === $user) {
				$duplicate = true;
			}
		}
	}

	if (!$duplicate) {
		mysqli_query($con, "INSERT INTO users (username, password_hash, auth_code) VALUES ('$user', '$pass', 'true')");
		header("Location: index.php");
	}
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
			function validateForm() {
				if (form.pass.value !== form.pass2.value) {
					alert("Your passwords do not match!");
					return false;
				}
			}
		//]]>
		</script>
	</head>
	<body>
		<?php require("header.php"); ?>
		<h1>Create User</h1>
<?php
if ($duplicate) {
?>
		<p class="error">Someone already took this username. Please choose another.</p>
<?php
}
?>
		<form name= "form" method="post" onsubmit="return validateForm();">
			<label for="name">Name:</label>
			<input type="text" name="name" placeholder="Name" maxlength="50" required="true">
			
			<label for="name">Level:</label>
			<input type="number" name="level" value="0" min="0" max="100">
			
			<label for="name">Strength:</label>
			<input type="number" name="str_attr" value="0" min="0" max="100">
			
			<label for="name">Intelligence:</label>
			<input type="number" name="int_attr" value="0" min="0" max="100">
			
			<label for="name">Charisma:</label>
			<input type="number" name="char_attr" value="0" min="0" max="100">
			
			<label for="name">Constitution:</label>
			<input type="number" name="con_attr" value="0" min="0" max="100">
			
			<label for="name">Dexterity:</label>
			<input type="number" name="dex_attr" value="0" min="0" max="100">
			
			<label for="name">Wisdom:</label>
			<input type="number" name="wis_attr" value="0" min="0" max="100">
			
			<label for="name">Weight:</label>
			<input type="number" name="weight" value="0" min="0" max="100">
			
			<label for="name">Height:</label>
			<input type="number" name="height" value="0" min="0" max="100">
			
			<label for="name">Age:</label>
			<input type="number" name="age" value="0" min="0" max="100">
			
			<label for="name">Religion:</label>
			<input type="text" name="religion" placeholder="Name" maxlength="20" required="true">
			
			<input type="submit" value="Submit" />
		</form>
	</body>
</html>
<?php
mysqli_close($con);
?>
