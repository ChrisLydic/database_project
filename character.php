<?php
session_start();

if (!$_SESSION["auth"]) {
	$_SESSION["auth"] = false;
	header("Location: log_in.php");
} else {
	if (isset($_GET["char"])) {
		$charId = $_GET["char"];
	} else {
		header("Location: error.php");
	}

	if (isset($_SESSION["allowed"][$charId])) {
		require("db_open.php");
		$result = mysqli_query($con, "SELECT * FROM characters WHERE character_id='$charId'");
		$row = mysqli_fetch_array($result);

		$result_class = mysqli_query($con, "SELECT class_name,base_attack,fort_save,ref_save,will_save FROM classes WHERE class_id='{$row["char_class"]}' ;");
		if ($result_class) {
			$class_row = mysqli_fetch_array($result_class);
			$_SESSION["class"] = $class_row["class_name"];
		}

		$result_race = mysqli_query($con, "SELECT race_name FROM races WHERE race_id='{$row["race"]}' ;");
		if ($result_race) {
			$race_row = mysqli_fetch_array($result_race);
			$_SESSION["race"] =  $race_row["race_name"];
		}

		//get skills in 2D array
		$result_skill = mysqli_query($con,"SELECT * FROM skills INNER JOIN characters_skills on characters_skills.skill_id=skills.skill_id WHERE characters_skills.character_id = '$charId'");
		$skills_table = mysqli_fetch_all($result_skill);

		//get equipped armor
		$result_armor_on = mysqli_query($con,"SELECT * FROM armors INNER JOIN characters_armors on characters_armors.armor_id=armors.armor_id WHERE characters_armors.character_id = '$charId' and characters_armors.location = 'EQUIPED'");
		if($result_armor_on) {
			$armor_on = mysqli_fetch_array($result_armor_on);
		}

		//get equipped weapon
		$result_weapon_on = mysqli_query($con,"SELECT * FROM weapons INNER JOIN characters_weapons on characters_weapons.weapon_id=weapons.weapon_id WHERE characters_weapons.character_id = '$charId' and characters_weapons.location = 'EQUIPED'");
		if($result_weapon_on) {
			$weapon_on = mysqli_fetch_array($result_weapon_on);
		}
	} else {
		header("Location: error.php");
	}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title><?php echo $row["character_name"]; ?></title>
		<meta name="author" content="Alex Hedges" />
	</head>
	<body onload="refresh(false);">
		<?php require("header.php"); ?>
		<h1><?php echo $row["character_name"]; ?></h1>

<!--		<a href="item.php?char=--><?php //echo $charId; ?><!--">Add Item</a> |-->
		<a href="character_form.php?mode=edit&char=<?php echo $charId; ?>">Edit Character</a> |
		<a href="delete_character.php?char=<?php echo $charId; ?>">Delete Character</a>
		<?php
		// basic modifier calculations
		$str_mod = (floor($row["str_attr"]/2)-5);
		$dex_mod = (floor($row["dex_attr"]/2)-5);
		$con_mod = (floor($row["con_attr"]/2)-5);
		$int_mod = (floor($row["int_attr"]/2)-5);
		$wis_mod = (floor($row["wis_attr"]/2)-5);
		$cha_mod = (floor($row["cha_attr"]/2)-5);

		?>


		<p>Level: <?php echo $row["character_level"]; ?></p>
		<p>Strength: <?php echo $row["str_attr"]; ?><br>
			Strength Modifier: <?= $str_mod ?></p>
		<p>Intelligence: <?php echo $row["int_attr"]; ?><br>
			Intelligence Modifier: <?= $int_mod ?></p>
		<p>Charisma: <?php echo $row["cha_attr"]; ?><br>
			Charisma Modifier: <?= $cha_mod ?></p>
		<p>Constitution: <?php echo $row["con_attr"]; ?><br>
			Constitution Modifier: <?= $con_mod ?></p>
		<p>Dexterity: <?php echo $row["dex_attr"]; ?><br>
			Dexterity Modifier: <?= $dex_mod ?></p>
		<p>Wisdom: <?php echo $row["wis_attr"]; ?><br>
			Wisdom Modifier: <?= $wis_mod ?></p>
		<p>Weight: <?php echo $row["weight"]; ?> pounds</p>
		<p>Height: <?php echo $row["height"]; ?> inches</p>
		<p>Age: <?php echo $row["age"]; ?></p>
		<p>Religion: <?php echo $row["religion"]; ?></p>
		<p>Gender: <?php echo $row["gender"]; ?></p>
		<p>Class: <?php echo $_SESSION["class"]; ?></p>
		<p>Race: <?php echo $_SESSION["race"]; ?></p>
		<p>Hit Points: <?php echo $row["hit_points"]; ?></p>
		<p>Alignment: <?php echo $row["alignment"]; ?></p>
		<p>Money: <?php echo $row["money"]; ?> gp</p>



		<?php
		//calculate mods from skills
		echo "<h3>Skills:</h3><br>";
		foreach ($skills_table as $value){
			echo "<b>";
			echo $value[1];
			echo "</b><br>";
			if($value[7] == 0 && $value[4] == 0){
				//skill has no effect
			}else
			{
				$result_skills_races = mysqli_query($con,"SELECT * FROM skills_races WHERE skill_id = '$value[0]' and race_id = '$row[15]'");
				$skills_races = mysqli_fetch_array($result_skills_races);
				print_r($skills_races);
				if($value[2] = "INT")
				{
					$mod = $int_mod;
				}elseif ($value[2] = "DEX"){
					$mod = $dex_mod;
				}elseif ($value[2] = "CON"){
					$mod = $con_mod;
				}elseif ($value[2] = "STR"){
					$mod = $str_mod;
				}elseif ($value[2] = "WIS"){
					$mod = $wis_mod;
				}elseif ($value[2] = "CHA"){
					$mod = $cha_mod;
				}
				$mod = $value[7]+$mod;
				if($result_armor_on)
				{
					$mod += $value[3] * $armor_on["armor_check_penalty"];
				}
				if($skills_races)
				{
					$mod += $skills_races["bonus"];
				}
				echo "Skill mod:";
				echo $mod;
				echo "<br><br>";
			}
		}

		//base attack bonus calculation
		$base_attack_bonus = 0;
		if($class_row["base_attack"] == "Good")
		{
			$base_attack_bonus = $row["character_level"];
		}else if($class_row["base_attack"] == "Average")
		{
			$base_attack_bonus = floor($row["character_level"]*(3.0/4.0));
		}else if($class_row["base_attack"] == "Poor")
		{
			$base_attack_bonus = floor($row["character_level"]*(1.0/2.0));
		}

		//size mod calculation TODO
		$size_mod = 0;

		//attack bonus calculation
		$attack_bonus = $base_attack_bonus + $size_mod;
		if($result_weapon_on)
		{
			if($weapon_on["type"] == "Melee")
			{
				$attack_bonus += $str_mod;
			}else if($weapon_on["type"] == "Ranged")
			{
				$attack_bonus += $dex_mod;
			}
		}

		//grapple_mod calc
		$grapple_mod = $base_attack_bonus + $str_mod + $size_mod;

		//Calculate armor stuff
		if($result_armor_on){
			$armor_class = 10 + $armor_on["armor_bonus"] + /*Shield_bonus +*/ min($dex_mod,$armor_on["max_dex"]) + $size_mod;
			$touch_armor_class = 10 + $dex_mod + $size_mod;
			$flat_footed_armor_class = 10 + $armor_on["armor_bonus"] + /*Shield bonus +*/ $size_mod;
		}else{
			$armor_class = 0;
			$touch_armor_class = 0;
			$flat_footed_armor_class = 0;
		}

		//calculate saves
		$base_save = 0;
		if($class_row["fort_save"] == "Good")
		{
			$base_save = floor(2 + $row["character_level"] *(1.0/2.0));
		}else if($class_row["fort_save"] == "Poor")
		{
			$base_save = floor($row["character_level"]*(1.0/3.0));
		}
		$fort_save_mod = $base_save+$con_mod;

		if($class_row["ref_save"] == "Good")
		{
			$base_save = floor(2 + $row["character_level"] *(1.0/2.0));
		}else if($class_row["ref_save"] == "Poor")
		{
			$base_save = floor($row["character_level"]*(1.0/3.0));
		}
		$ref_save_mod = $base_save+$dex_mod;

		if($class_row["will_save"] == "Good")
		{
			$base_save = floor(2 + $row["character_level"] *(1.0/2.0));
		}else if($class_row["will_save"] == "Poor")
		{
			$base_save = floor($row["character_level"]*(1.0/3.0));
		}
		$will_save_mod = $base_save+$wis_mod;
		?>

		<p>Base Attack Bonus: <?= $base_attack_bonus; ?></p>

		<p>Fort save modifier: <?= $fort_save_mod?><br>
			Ref save modifier: <?= $ref_save_mod?><br>
			Will save modifier: <?= $will_save_mod?><br>
		</p>
	</body>
</html>
<?php
}
?>