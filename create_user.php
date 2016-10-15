<?php
session_start();

require("db_open.php");

$duplicate = false;

if (isset($_POST["user"]) && isset($_POST["pass"])) {
	$user = mysqli_real_escape_string($con, $_POST["user"]); //htmlentities
	$pass = mysqli_real_escape_string($con, sha1($_POST["pass"]));
	
	$result = mysqli_query($con, "SELECT Username FROM Users");
	if ($result) {
		while ($row = mysqli_fetch_array($result)) {
			if ($row["Username"] === $user) {
				$duplicate = true;
			}
		}
	}

	if (!$duplicate) {
		mysqli_query($con, "INSERT INTO Users (Username, PasswordHash, AuthCode) VALUES ('$user', '$pass', 'true')");
	}
}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title>Create User</title>
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
			<input type="text" name="user" placeholder="Username" required="required"/>
			<input type="password" name="pass" placeholder="Password" required="required"/>
			<input type="password" name="pass2" placeholder="Confirm Password" required="required"/>
			<input type="submit" value="Submit" />
		</form>
	</body>
</html>
<?php
mysqli_close($con);
?>
