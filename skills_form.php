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
$char_result = mysqli_fetch_array(mysqli_query($con, "SELECT character_name, character_level, char_class, int_attr FROM characters WHERE character_id='$char_id'"));
$character_name = $char_result["character_name"];
$character_level = $char_result["character_level"];
// Extract class
$class_result = mysqli_query($con, "SELECT skill_points FROM classes WHERE class_id='{$char_result["char_class"]}'");
$class_skill_points = mysqli_fetch_array($class_result)["skill_points"];
$skill_points = max((4 + attr_modifier($char_result["int_attr"])) * (4 + $character_level - 1), 1 * (4 + $character_level - 1));
$max_ranks = $character_level + 3;

// Initializes skills to rank 0 if skill relationship does not already exist
$skills_array = mysqli_fetch_all(mysqli_query($con,"SELECT * FROM skills"), MYSQLI_ASSOC);
foreach ( $skills_array as $key => $value ) {
	$skill = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM characters_skills WHERE characters_skills.character_id = '$char_id' AND characters_skills.skill_id = '{$value["skill_id"]}'"), MYSQLI_ASSOC);
	if ($skill == NULL)
	{
	    $skill_id = $value["skill_id"];
		mysqli_query($con,"INSERT INTO characters_skills(skill_id, character_id, skill_rank) VALUES ($skill_id, $char_id, 0)");
	}
}

// Create list of skills with ranks for the character
$result_skills = mysqli_query($con,"SELECT skill_name, skill_rank FROM skills INNER JOIN characters_skills ON characters_skills.skill_id = skills.skill_id WHERE characters_skills.character_id = '$char_id' ORDER BY skill_name");

// Checks to ensure all input is exists
$is_form_full = true;
foreach ( $skills_array as $key => $value ) {
    if(!isset($_POST["skill_".str_replace(' ','',$value["skill_name"])]))
    {
        $is_form_full = false;
    }
}

// If input exists, then store in database and go back to main page for character
if ($is_form_full) {
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
        <title><?= ("Edit Skills of " . $character_name) ?></title>
        <script type="text/javascript">
            //<![CDATA[
				var skill_inputs = [<?php
				foreach ( $skills_array as $key => $value ) {
					echo "\"skill_".str_replace(' ','',$value["skill_name"])."\",\n";
				}
				?>];
				var allotted_skill_points = <?= $skill_points ?>;
			
				function total_skill_points() {
					var total = 0;
					for (let skill_name of skill_inputs) {
						var input = document.getElementById(skill_name);
						total += parseInt(input.value);
					}
					return total;
				}
				
				function check_total() {
					var total = total_skill_points();
					var total_display = document.getElementById("total_points");
					total_display.innerHTML = total;
					if (total > allotted_skill_points) {
						total_display.style.color = "red";
					} else {
						total_display.style.color = "";
					}
				}
				
				function check_form() {
					var total = total_skill_points();
					if (total > allotted_skill_points) {
						alert("You must enter fewer skill points.");
						return false;
					} else if (total < allotted_skill_points) {
						alert("You have skill points remaining.");
						return false;
					} else {
						return true;
					}
				}
            //]]>
        </script>
    </head>
    <body onload="check_total();">
    <?php require("header.php"); ?>
    <h1><?= ("Edit Skills of " . $character_name) ?></h1>
	<p>Skill Points Selected: <span id="total_points"></span></p>
	<p>Total Skill Points Allowed: <?= $skill_points ?></p>
    <form name="form" method="post" onsubmit="return check_form();">
		<ul>
        <?php
		while ($row = mysqli_fetch_array($result_skills, MYSQLI_ASSOC)) {
			$skill_name_parsed = "skill_".str_replace(' ','',$row["skill_name"]);
            echo "<li><label for='$skill_name_parsed'>{$row["skill_name"]}: </label>"?>
            <input type="number" id="<?= $skill_name_parsed ?>" name="<?= $skill_name_parsed ?>" required="required" value="<?= min($row["skill_rank"], $max_ranks) ?>" min="0" max="<?php echo $max_ranks ?>" onchange="check_total();"></li>
            <?php echo "";
        }
        ?>
		</ul>
        <input type="submit" value="Submit" />
    </form>
    </body>
    </html>
<?php
mysqli_close($con);
?>