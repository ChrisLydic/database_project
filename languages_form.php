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

if (isset($_GET["char"])) {
	$char_id = $_GET["char"];
} else {
	header("Location: error.php");
}

if (!isset($_SESSION["allowed"][$char_id])) {
	header("Location: error.php");
}

// Extract character name
$char_result = mysqli_fetch_array(mysqli_query($con, "SELECT character_name, int_attr FROM characters WHERE character_id='$char_id'"));
$character_name = $char_result["character_name"];
$character_int = attr_modifier($char_result["int_attr"]);

$language_array = mysqli_fetch_all(mysqli_query($con, "SELECT language_id, language_name, alphabet FROM languages;"), MYSQLI_ASSOC);

// Create list of languages for the character
$result_languages = mysqli_query($con, "SELECT languages.language_id FROM characters_languages INNER JOIN languages ON characters_languages.language_id = languages.language_id WHERE characters_languages.character_id = '$char_id';");
if ($result_languages) {
	$char_languages_array = mysqli_fetch_all($result_languages, MYSQLI_ASSOC);
	$char_languages = array();
	foreach ( $char_languages_array as $key => $value ) {
		array_push($char_languages, $value["language_id"]);
	}
}

// If input exists, then store in database and go back to main page for character
if (isset($_POST["languages"])) {
	$languages = $_POST["languages"];
	mysqli_query($con,"DELETE FROM characters_languages WHERE character_id='$char_id';");
	foreach ( $languages as $key => $value ) {
		$language_id = intval($value);
		mysqli_query($con,"INSERT INTO characters_languages(character_id, language_id) VALUES ($char_id, $language_id);");
	}
	header("Location: character.php?" . http_build_query($_GET));
}
?>
    <!DOCTYPE html>
    <html lang="en" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
        <title><?= ("Edit Languages of " . $character_name) ?></title>
        <script type="text/javascript">
            //<![CDATA[
            //]]>
        </script>
    </head>
    <body>
    <?php require("header.php"); ?>
    <h1><?= ("Edit Languages of " . $character_name) ?></h1>
    <form name="form" method="post">
		<label for="languages[]">Languages:</label>
			<select name="languages[]" multiple="multiple">
				<?php
					foreach ($language_array as $key => $value) {
				?>
					<option value="<?php echo $value["language_id"]; ?>" <?= (isset($char_languages) && in_array($value["language_id"], $char_languages)) ? "selected=\"selected\"" : "" ?>><?php echo $value["language_name"]; ?></option>
				<?php
					}
				?>
			</select>
        <input type="submit" value="Submit" />
    </form>
    </body>
    </html>
<?php
mysqli_close($con);
?>