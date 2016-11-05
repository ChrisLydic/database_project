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
$result = mysqli_query($con, "SELECT * FROM characters WHERE character_id='$char_id'");
$character_name = mysqli_fetch_array($result)["character_name"];

// Initializes skills to rank 0 if skill relationship does not already exist
$skills = mysqli_query($con,"SELECT * FROM skills");
while ($skill_id = mysqli_fetch_array($skills, MYSQLI_ASSOC)["skill_id"]) {
	$skill = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM characters_skills WHERE characters_skills.character_id = '$char_id' AND characters_skills.skill_id = '$skill_id'"), MYSQLI_ASSOC);
	if ($skill == NULL)
	{
		mysqli_query($con,"INSERT INTO characters_skills(skill_id, character_id, skill_rank) VALUES ($skill_id, $char_id, 0)");
	}
}

// Create list of skills with ranks for the character
$result_skills = mysqli_query($con,"SELECT skill_name, skill_rank FROM skills INNER JOIN characters_skills ON characters_skills.skill_id = skills.skill_id WHERE characters_skills.character_id = '$char_id'");
//$skills_table = mysqli_fetch_all($result_skill);

// Checks to ensure all input is exists
$is_form_full = true;
$skills = mysqli_query($con,"SELECT * FROM skills");
while ($row = mysqli_fetch_array($skills, MYSQLI_ASSOC)) {
    if(!isset($_POST["skill_".str_replace(' ','',$row["skill_name"])]))
    {
        $is_form_full = false;
    }
}

// If input exists, then store in database and go back to main page for character
if ($is_form_full) {
	$skills = mysqli_query($con,"SELECT * FROM skills");
	while ($row = mysqli_fetch_array($skills, MYSQLI_ASSOC)) {
		$skill_id = $row["skill_id"];
		$rank = $_POST["skill_".str_replace(' ','',$row["skill_name"])];
		mysqli_query($con,"UPDATE  characters_skills SET skill_rank = $rank WHERE character_id = $char_id AND skill_id = $skill_id;");
	}
	header("Location: character.php?" . http_build_query($_GET)); # TODO Fix to not add edit to URL
}
?>
    <!DOCTYPE html>
    <html lang="en" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
        <title><?php echo ("Edit Skills of " . $character_name) ?></title>
        <script type="text/javascript">
            //<![CDATA[
            //			function validateForm() {
            //			}
            //]]>
        </script>
    </head>
    <body>
    <?php require("header.php"); ?>
    <h1><?php echo ("Edit Skills of " . $character_name) ?></h1>

    <form name="form" method="post">
        <?php
        echo "<ul>";
		while ($row = mysqli_fetch_array($result_skills, MYSQLI_ASSOC)) {
			$skill_name_parsed = "skill_".str_replace(' ','',$row["skill_name"]);
            echo "<label for='$skill_name_parsed'>";
            echo $row["skill_name"];
            echo ": </label>"?>
            <input type="number" name="<?= $skill_name_parsed ?>" required="required" value="<?php echo ($row["skill_rank"]) ?>" min="0" max="<?php echo PHP_INT_MAX ?>">
            <?php echo "";
        }
        echo "</ul>";
        ?>
        <input type="submit" value="Submit" />
    </form>
    </body>
    </html>
<?php
mysqli_close($con);
?>