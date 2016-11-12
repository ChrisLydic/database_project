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

// Create list of skills with ranks for the character
$result_skills = mysqli_query($con,"SELECT skill_name, skill_rank FROM skills INNER JOIN characters_skills ON characters_skills.skill_id = skills.skill_id WHERE characters_skills.character_id = '$char_id'");



// If input exists, then store in database and go back to main page for character
if (isset($_POST["languages[]"])) {
	$languages = $_POST["languages[]"];
	foreach ( $skills_array as $key => $value ) {
		$skill_id = $value["skill_id"];
		$rank = intval($_POST["skill_".str_replace(' ','',$value["skill_name"])]);
		mysqli_query($con,"UPDATE  characters_skills SET skill_rank = $rank WHERE character_id = $char_id AND skill_id = $skill_id;");
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
					<option value="<?php echo $value["language_id"]; ?>" <?= (isset($row["char_class"]) && $value["language_id"] == $row["race"]) ? "selected=\"selected\"" : "" ?>><?php echo $value["language_name"]; ?></option>
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