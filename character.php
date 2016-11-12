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
		require("character_utils.php");
		$result = mysqli_query($con, "SELECT * FROM characters WHERE character_id='$charId'");
		$row = mysqli_fetch_array($result);

		$result_class = mysqli_query($con, "SELECT class_name, base_attack, fort_save, ref_save, will_save FROM classes WHERE class_id='{$row["char_class"]}' ;");
		if ($result_class) {
			$class_row = mysqli_fetch_array($result_class);
			$_SESSION["class"] = $class_row["class_name"];
		}

		$result_race = mysqli_query($con, "SELECT race_name FROM races WHERE race_id='{$row["race"]}' ;");
		if ($result_race) {
			$race_row = mysqli_fetch_array($result_race);
			$_SESSION["race"] = $race_row["race_name"];
		}

		//get skills in 2D array
		$result_skill = mysqli_query($con,"SELECT * FROM skills INNER JOIN characters_skills ON characters_skills.skill_id = skills.skill_id WHERE characters_skills.character_id = '$charId'");
		$skills_table = mysqli_fetch_all($result_skill, MYSQLI_ASSOC);

		//get equipped armor
		$result_armor_on = mysqli_query($con,"SELECT * FROM armor INNER JOIN characters_armor ON characters_armor.armor_id = armor.armor_id WHERE characters_armor.character_id = '$charId' AND characters_armor.location = 'EQUIPPED'");
		if(mysqli_num_rows($result_armor_on)) {
			$armor_res = mysqli_fetch_all($result_armor_on);
			$armor_on = $armor_res[0];
		}

		//get equipped weapon
		$result_weapon_on = mysqli_query($con,"SELECT * FROM weapons INNER JOIN characters_weapons ON characters_weapons.weapon_id = weapons.weapon_id WHERE characters_weapons.character_id = '$charId' AND characters_weapons.location = 'EQUIPPED'");
		/*if(mysqli_num_rows($result_weapon_on)) {
			$weapon_res = mysqli_fetch_all($result_weapon_on);
			$weapon_on = $weapon_res[0];
		}*/

		//get equipped armor
		$armor_off = mysqli_query($con,"SELECT * FROM armor INNER JOIN characters_armor ON characters_armor.armor_id = armor.armor_id WHERE characters_armor.character_id = '$charId' AND characters_armor.location <> 'EQUIPPED'");

		//get equipped weapon
		$weapon_off = mysqli_query($con,"SELECT * FROM weapons INNER JOIN characters_weapons ON characters_weapons.weapon_id = weapons.weapon_id WHERE characters_weapons.character_id = '$charId' AND characters_weapons.location <> 'EQUIPPED'");

		//get equipped weapon
		$generic_items = mysqli_query($con,"SELECT * FROM generic_items INNER JOIN characters_generic_items ON characters_generic_items.generic_item_id = generic_items.generic_item_id WHERE characters_generic_items.character_id = '$charId'");

	} else {
		header("Location: error.php");
	}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title><?= $row["character_name"]; ?></title>
	</head>
	<body onload="refresh(false);">
		<?php require("header.php"); ?>
		<h1><?= $row["character_name"]; ?></h1>

		<!--<a href="item.php?char=--><?php //echo $charId; ?><!--">Add Item</a> |-->
		<a href="character_form.php?mode=edit&char=<?= $charId; ?>">Edit Character</a> |
		<a href="delete_character.php?char=<?= $charId; ?>" onclick="return confirm('Are you sure you want to delete this character?')">Delete Character</a> |
		<a href="skills_form.php?char=<?= $charId; ?>">Edit Skills</a> |
		<a href="add_item.php?char=<?= $charId; ?>">Add Item</a>
		<?php
		// basic modifier calculations
		$str_mod = attr_modifier($row["str_attr"]);
		$dex_mod = attr_modifier($row["dex_attr"]);
		$con_mod = attr_modifier($row["con_attr"]);
		$int_mod = attr_modifier($row["int_attr"]);
		$wis_mod = attr_modifier($row["wis_attr"]);
		$cha_mod = attr_modifier($row["cha_attr"]);

		?>


		<p>Level: <?= $row["character_level"]; ?></p>
		<p>Strength: <?= $row["str_attr"]; ?></p>
		<p>Strength Modifier: <?= $str_mod ?></p>
		<p>Intelligence: <?= $row["int_attr"]; ?></p>
		<p>Intelligence Modifier: <?= $int_mod ?></p>
		<p>Charisma: <?= $row["cha_attr"]; ?></p>
		<p>Charisma Modifier: <?= $cha_mod ?></p>
		<p>Constitution: <?= $row["con_attr"]; ?></p>
		<p>Constitution Modifier: <?= $con_mod ?></p>
		<p>Dexterity: <?= $row["dex_attr"]; ?></p>
		<p>Dexterity Modifier: <?= $dex_mod ?></p>
		<p>Wisdom: <?= $row["wis_attr"]; ?></p>
		<p>Wisdom Modifier: <?= $wis_mod ?></p>
		<p>Weight: <?= $row["weight"]; ?> pounds</p>
		<p>Height: <?= $row["height"]; ?> inches</p>
		<p>Age: <?= $row["age"]; ?></p>
		<p>Religion: <?= $row["religion"]; ?></p>
		<p>Gender: <?= $row["gender"]; ?></p>
		<p>Class: <?= $_SESSION["class"]; ?></p>
		<p>Race: <?= $_SESSION["race"]; ?></p>
		<p>Hit Points: <?= $row["hit_points"]; ?></p>
		<p>Alignment: <?= $row["alignment"]; ?></p>
		<p>Money: <?= $row["money"]; ?> gp</p>

		<?php
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
		/*$attack_bonus = $base_attack_bonus + $size_mod;
		if(mysqli_num_rows($result_weapon_on))
		{
			if($weapon_on[7] == "Melee") //////////TODO something needs fixed here, changed from type -> damage_type -> the index of damage_type
			{
				$attack_bonus += $str_mod;
			}else if($weapon_on[7] == "Ranged")
			{
				$attack_bonus += $dex_mod;
			}
		}*/

		//grapple_mod calc
		$grapple_mod = $base_attack_bonus + $str_mod + $size_mod;

		//Calculate armor stuff
		if(mysqli_num_rows($result_armor_on)) {
			$armor_class = 10 + $armor_on[3] + /*Shield_bonus +*/ min($dex_mod,$armor_on[4]) + $size_mod;
			$touch_armor_class = 10 + $dex_mod + $size_mod;
			$flat_footed_armor_class = 10 + $armor_on[3] + /*Shield bonus +*/ $size_mod;
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

		<p>Base attack bonus: <?= $base_attack_bonus; ?></p>

		<p>Fortitude save modifier: <?= $fort_save_mod?></p>
		<p>Reflex save modifier: <?= $ref_save_mod?></p>
		<p>Will save modifier: <?= $will_save_mod?></p>

		<h3>Skills:</h3><ul>
		<?php
		//calculate mods from skills
		foreach ($skills_table as $value){
			echo "<li><b>{$value["skill_name"]}</b>: ";
			if($value["skill_rank"] == 0 && $value["untrained"] == 0) {
				//skill has no effect
				unset($mod);
			}else {
				$result_skills_races = mysqli_query($con,"SELECT * FROM skills_races WHERE skill_id = '{$value["skill_id"]}' and race_id = '{$row["race"]}'");
				$skills_races = mysqli_fetch_array($result_skills_races);
				print_r($skills_races);
				if ($value["attribute"] == "INT") {
					$mod = $int_mod;
				} elseif ($value["attribute"] == "DEX") {
					$mod = $dex_mod;
				} elseif ($value["attribute"] == "CON") {
					$mod = $con_mod;
				} elseif ($value["attribute"] == "STR") {
					$mod = $str_mod;
				} elseif ($value["attribute"] == "WIS") {
					$mod = $wis_mod;
				} elseif ($value["attribute"] == "CHA") {
					$mod = $cha_mod;
				} elseif ($value["attribute"] == "NON") {
					$mod = 0;
				}
				$mod = $value["skill_rank"]+$mod;
				if (mysqli_num_rows($result_armor_on)) {
					$mod += $value["armor_penalty"] * $armor_on[5];
				}
				if ($skills_races) {
					$mod += $skills_races["bonus"];
				}
				echo $mod > 0 ? "+" : "";
				
			}
			echo isset($mod) ? $mod : "n/a";
			echo "</li>";
		}
		?>
		</ul>
		<h3>Weapons:</h3>
		<ul>
			<?php
				if (!mysqli_num_rows($result_weapon_on)) {
					echo '<li>No Equipped Weapons</li>';
				} else {
					echo '<li>Equipped Weapons</li><ul>';
					foreach ($result_weapon_on as $weapon_row) {
						echo "<li>{$weapon_row["weapon_name"]} ({$weapon_row["quantity"]}) | <a href='equip_item.php?char=$charId&weapon={$weapon_row["weapon_id"]}&equip=false'>Unequip</a> | <a href='drop_item.php?char=$charId&weapon={$weapon_row["weapon_id"]}'>Remove</a></li>";
					}
					echo '</ul>';
				}

				if (!mysqli_num_rows($weapon_off)) {
					echo '<li>No Unequipped Weapons</li>';
				} else {
					echo '<li>Unequipped Weapons</li><ul>';
					while ($weapon_row = mysqli_fetch_array($weapon_off)) {
						echo "<li>{$weapon_row["weapon_name"]} ({$weapon_row["quantity"]}) | <a href='equip_item.php?char=$charId&weapon={$weapon_row["weapon_id"]}&equip=true'>Equip</a> | <a href='drop_item.php?char=$charId&weapon={$weapon_row["weapon_id"]}'>Remove</a></li>";
					}
					echo '</ul>';
				}
			?>
		</ul>

		<h3>Armor:</h3>
		<ul>
			<?php
                if (!mysqli_num_rows($result_armor_on)) {
                    echo '<li>No Equipped Armor</li>';
                } else {
                    echo '<li>Equipped Armor</li><ul>';
					foreach ($armor_res as $row) {
						//amount is coerced to float when sql->array conversion happens, cast it to int
						$amount = (int)$row[2];
                        echo "<li>$row[1] ($amount) | <a href='equip_item.php?char=$charId&armor=$row[0]&equip=false'>Unequip</a> | <a href='drop_item.php?char=$charId&armor={$row[0]}'>Remove</a></li>";
                    }
                    echo '</ul>';
                }

                if (!mysqli_num_rows($armor_off)) {
                    echo '<li>No Unequipped Armor</li>';
                } else {
                    echo '<li>Unequipped Armor</li><ul>';
                    while ($row = mysqli_fetch_array($armor_off)) {
                        echo "<li>{$row["armor_name"]} ({$row["quantity"]}) | <a href='equip_item.php?char=$charId&armor={$row["armor_id"]}&equip=true'>Equip</a> | <a href='drop_item.php?char=$charId&armor={$row["armor_id"]}'>Remove</a></li>";
                    }
                    echo '</ul>';
                }
			?>
		</ul>

		<h3>Items:</h3>
		<ul>
			<?php
				if (!mysqli_num_rows($generic_items)) {
					echo '<li>No Items</li>';
				} else {
					while ($row = mysqli_fetch_array($generic_items)) {
						echo "<li>{$row["generic_item_name"]} ({$row["quantity"]}) | <a href='drop_item.php?char=$charId&item={$row["generic_item_id"]}'>Remove</a></li>";
					}
				}
			?>
		</ul>

	</body>
</html>
<?php
}
?>