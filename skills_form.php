<?php
session_start();

if (!isset($_SESSION["auth"])) {
    $_SESSION["auth"] = false;
}

if (!$_SESSION["auth"]) {
    header("Location: log_in.php");
}

$edit = true;

require("db_open.php");
require("character_utils.php");

$result_skills = mysqli_query($con, "SELECT skill_id, skill_name FROM skills ;");
if ($result_skills) {
    while ($row = mysqli_fetch_array($result_skills)) {
        $skill_array[$row["skill_id"]] = $row["skill_name"];
    }
    $_SESSION["skills"] = $skill_array;
}
// check if all form data exists
// TODO Either allow nullable fields to be unset or change nullable fields to nonullable fields
$is_form_full = true;

foreach ($skill_array as $key => $value)
{
    if(!isset($_POST["skill_".str_replace(' ','',$value)]))
    {
        echo "skill not set: ";
        echo $value;
        $is_form_full = false;
    }
}

if ($edit)
{
    if (isset($_GET["char"])) {
        $charId = $_GET["char"];
    } else {
        header("Location: error.php");
    }

    if (isset($_SESSION["allowed"][$charId])) {
        require("db_open.php");
        $result = mysqli_query($con, "SELECT * FROM characters WHERE character_id='$charId'");
        $row = mysqli_fetch_array($result);

        $result_skill = mysqli_query($con,"SELECT * FROM skills INNER JOIN characters_skills ON characters_skills.skill_id = skills.skill_id WHERE characters_skills.character_id = '$charId'");
        $skills_table = mysqli_fetch_all($result_skill);
    } else {
        header("Location: error.php");
    }
}

if ($is_form_full) {
    $form_array = array();

    $res = mysqli_query($con, "SELECT user_id FROM users WHERE username = '{$_SESSION["user"]}' ;");
    if ($res) {
        $row = mysqli_fetch_array($res);
        $form_array["user_id"] = $row["user_id"];
    } else {
        header("Location: index.php");
    }

    if (true) {
        if ($edit)
        {
            //update skills
            foreach($skill_array as $key=>$value)
            {
                $rank = $_POST["skill_".str_replace(' ','',$value)];
                mysqli_query($con,"DELETE FROM `characters_skills` WHERE character_id=$charId AND skill_id=$key");
                mysqli_query($con,"INSERT INTO characters_skills(character_id, skill_id, skill_rank) VALUES ($charId,$key,$rank);");
            }
            header("Location: character.php?" . http_build_query($_GET)); # TODO Fix to not add edit to URL
        }
    } # doesn't do anything if invalid because invalid form data would require user to subvert html form
}
?>
    <!DOCTYPE html>
    <html lang="en" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
        <title><?php echo ($edit ? "Edit " . $row["character_name"] : "Create Character") ?></title>
        <script type="text/javascript">
            //<![CDATA[
            //			function validateForm() {
            //			}
            //]]>
        </script>
    </head>
    <body>
    <?php require("header.php"); ?>
    <h1><?php echo ("Edit Skills of " . $row["character_name"]) ?></h1>

    <form name="form" method="post">
        <?php
        $array = $_SESSION["skills"];
        echo "<h3>Skills:</h3>";
        echo "<ul>";
        foreach($array as $key => $value){
            echo "<label for='skill_";
            echo $value;
            echo "'>";
            echo $value;
            echo ": </label>"?>
            <input type="number" name="<?= "skill_".str_replace(' ','',$value) ?>" required="required" value="<?php echo (array_key_exists($key,$skills_table) ? $skills_table[$key] : 0) ?>" min="<?php echo PHP_INT_MIN ?>" max="<?php echo PHP_INT_MAX ?>">
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