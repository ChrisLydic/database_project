<?php
session_start();

if (!isset($_SESSION["auth"])) {
	$_SESSION["auth"] = false;
}

if ($_SESSION["auth"]) {
	header("Location: index.php");
} else {
	require("db_open.php");

	$invalid = false;
	$disabled = false;

	if (isset($_POST["user"]) && isset($_POST["pass"])) {
		$user = mysqli_real_escape_string($con, $_POST["user"]); //htmlentities
		$pass = mysqli_real_escape_string($con, sha1($_POST["pass"]));
		
		$result = mysqli_query($con, "SELECT password_hash, auth_code FROM users WHERE username='$user'");
		if (!$result) {
			$invalid = true;
		} else {
			$row = mysqli_fetch_array($result);
			if ($row["password_hash"] !== $pass) {
				$invalid = true;
			} else if ($row["auth_code"] !== "true") {
				$disabled = true;
			} else {
				$_SESSION["auth"] = true;
				$_SESSION["user"] = $user;
				$array = array();
				mysqli_query($con, "CREATE OR REPLACE VIEW allowed_chars AS
				SELECT characters.character_id, character_name, username FROM characters
				INNER JOIN permissions
				ON characters.character_id = permissions.character_id
				INNER JOIN users
				ON permissions.user_id = users.user_id;");
				$result = mysqli_query($con, "SELECT character_id, character_name FROM allowed_chars 
				WHERE username = '{$_SESSION["user"]}';");
				if ($result) {
					while($row = mysqli_fetch_array($result)) {
						$array[$row["character_id"]] = $row["character_name"];
					}
					$_SESSION["allowed"] = $array;
				}
				header("Location: index.php");
			}
		}	
	}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title>Log In</title>
	</head>
	<body>
		<h1>Log In</h1>
<?php
	if ($invalid) {
?>
		<p class="error">This is an invalid username-password combination.</p>
<?php
	}
	if ($disabled) {
?>
		<p class="error">Your account is disabled.</p>
<?php
	}
}
?>
		<form method="post">
			<input type="text" name="user" maxlength="50" placeholder="Username" required="required"/>
			<input type="password" name="pass" placeholder="Password" required="required"/>
			<input type="submit" value="Submit" />
		</form>
		<a href="create_user.php">Create an Account</a>
	</body>
</html>
<?php
mysqli_close($con);
?>