<?php
session_start();

$boardNum = $_POST["board"];

require("db_open.php");
$result = mysqli_query($con, "SELECT FileName FROM Chats WHERE ChatID='$boardNum'");
$row = mysqli_fetch_array($result);
$fileName = "chats/" . $row["FileName"] . ".txt";

$clearWord = "clear";
$admin = "alexwho314";

if (!file_exists($fileName)) {
	$file = fopen($fileName, "w");
	$message = "<p>Your conversation:</p>";
	$results = fwrite($file, $message);
	fclose($file);
}

if (isset($_POST["message"]) and $_POST["message"] !== "") {
	$message = htmlspecialchars($_REQUEST["message"]);
	$message = str_replace("[b]", "<b>", $message);
	$message = str_replace("[/b]", "</b>", $message);
	$message = str_replace("[i]", "<i>", $message);
	$message = str_replace("[/i]", "</i>", $message);
	$message = str_replace(":D", "<img src=\"emoticons\\grin.svg\" alt=\":D\" class=\"emoticon\"/>", $message);
	$message = str_replace(":-(", "<img src=\"emoticons\\sad.svg\" alt=\":-(\" class=\"emoticon\"/>", $message);
	$message = str_replace(":-)", "<img src=\"emoticons\\smile.svg\" alt=\":-)\" class=\"emoticon\"/>", $message);
	$message = str_replace(":-o", "<img src=\"emoticons\\surprise.svg\" alt=\":-o\" class=\"emoticon\"/>", $message);
	$message = str_replace(";-)", "<img src=\"emoticons\\wink.svg\" alt=\";-)\" class=\"emoticon\"/>", $message);
	$user = $_SESSION["user"];

	if ($message === $clearWord && $user === $admin) {
		$file = fopen($fileName, "w");
		$message = "<p>Your conversation:</p>";
	} else {
		$file=fopen($fileName, "a");
		$message="\n<p>$user: $message</p>";
	} 

	$results = fwrite($file, $message);
	fclose($file);
}

$file = fopen($fileName, "r");
$response = "";
while (!feof($file)) {
	$response = $response . fgets($file);
}
fclose($file);

echo $response;
?>