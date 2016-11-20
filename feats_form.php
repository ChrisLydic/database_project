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
$char_result = mysqli_fetch_array(mysqli_query($con, "SELECT character_name FROM characters WHERE character_id='$char_id'"));
$character_name = $char_result["character_name"];

$feat_array = mysqli_fetch_all(mysqli_query($con, "SELECT feat_id, feat_name, description, prerequisites FROM feats ORDER BY feat_name;"), MYSQLI_ASSOC);

// Create list of feats for the character
$result_feats = mysqli_query($con, "SELECT feats.feat_id FROM characters_feats INNER JOIN feats ON characters_feats.feat_id = feats.feat_id WHERE characters_feats.character_id = '$char_id';");
if ($result_feats) {
	$char_feats_array = mysqli_fetch_all($result_feats, MYSQLI_ASSOC);
	$char_feats = array();
	foreach ( $char_feats_array as $key => $value ) {
		array_push($char_feats, $value["feat_id"]);
	}
}

// If input exists, then store in database and go back to main page for character
if (isset($_POST["feats"])) {
	$feats = $_POST["feats"];
	mysqli_query($con,"DELETE FROM characters_feats WHERE character_id='$char_id';");
	foreach ( $feats as $key => $value ) {
		$feat_id = intval($value);
		mysqli_query($con,"INSERT INTO characters_feats(character_id, feat_id) VALUES ($char_id, $feat_id);");
	}
	header("Location: character.php?" . http_build_query($_GET));
}
?>
    <!DOCTYPE html>
    <html lang="en" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
        <title><?= ("Edit feats of " . $character_name) ?></title>
        <script type="text/javascript">
            //<![CDATA[
            //]]>
        </script>
    </head>
    <body>
    <?php require("header.php"); ?>
    <h1><?= ("Edit feats of " . $character_name) ?></h1>
    <form name="form" method="post">
		<?php
			foreach ($feat_array as $key => $value) {
		?>
			<label><input type="checkbox" name="feats[]" value="<?php echo $value["feat_id"]; ?>" <?= (isset($char_feats) && in_array($value["feat_id"], $char_feats)) ? "checked=\"checked\"" : "" ?> style="display:inline;"/> <?php echo $value["feat_name"]; ?></label>
		<?php
			}
		?>
        <input type="submit" value="Submit" />
    </form>
    </body>
    </html>
<?php
mysqli_close($con);
?>