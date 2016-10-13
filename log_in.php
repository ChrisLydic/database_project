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
		
		$result = mysqli_query($con, "SELECT PasswordHash, AuthCode FROM Users WHERE Username='$user'");
		if (!$result) {
			$invalid = true;
		} else {
			$row = mysqli_fetch_array($result);
			if ($row["PasswordHash"] !== $pass) {
				$invalid = true;
			} else if ($row["AuthCode"] !== "true") {
				$disabled = true;
			} else {
				$_SESSION["auth"] = true;
				$_SESSION["user"] = $user;
				$array = array();
				mysqli_query($con, "CREATE OR REPLACE VIEW AllowedChars AS
				SELECT Characters.CharacterId, DisplayName, Username FROM Characters
				INNER JOIN Permissions
				ON Characters.CharacterId = Permissions.CharacterId
				INNER JOIN Users
				ON Permissions.UserId = Users.UserId;");
				$result = mysqli_query($con, "SELECT CharacterId, DisplayName FROM AllowedChars 
				WHERE Username = '{$_SESSION["user"]}';");
				if ($result) {
					while($row = mysqli_fetch_array($result)) {
						$array[$row["CharacterId"]] = $row["DisplayName"];
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