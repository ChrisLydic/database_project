<?php
session_start();

if (!isset($_SESSION["auth"])) {
	$_SESSION["auth"] = false;
}

if (!$_SESSION["auth"]) {
	header("Location: log_in.php");
}

$edit = false;
if (isset($_GET["mode"]) && $_GET["mode"] == "edit")
{
	$edit = true;
}	

require("db_open.php");
require("character_utils.php");

$class_big_array = array();
$class_array = array();
$race_big_array = array();
$race_array = array();

// Get the array of classes
$class_big_array = mysqli_fetch_all(mysqli_query($con, "SELECT class_id, class_name FROM classes;"), MYSQLI_ASSOC);
foreach($class_big_array as $key=>$value){
	array_push($class_array, $value["class_name"]);
}
// Get the length of the class array
$class_length = count($class_array);

// Get the array of races
$race_big_array = mysqli_fetch_all(mysqli_query($con, "SELECT race_id, race_name FROM races;"), MYSQLI_ASSOC);
foreach($race_big_array as $key=>$value){
	array_push($race_array, $value["race_name"]);
}
// Get the length of the race array
$race_length = count($race_array);



// Level
$level = 1;

// Roll the given number of dice with a given dice type. Return the total.
function roll_dice($numDice, $diceType){
	$total = 0;
	for($i = 0; $i < $numDice; $i++){
		$total += rand(1, $diceType);
	}
	return $total;
}

// Stats
// Roll the stats by rolling 4d6 and dropping the lowest.
function roll_stats(){
	$array = [0, 0, 0, 0];
	$sum = 0;
	// Roll 4d6
	for($i = 0; $i < 4; $i++)
	{
		$array[$i] = rand(1, 6);
		// Add to the sum
		$sum += $array[$i];
	}
	// Drop the lowest
	$sum -= min($array);
	return $sum;
}

// Strength
$str = roll_stats();
// Dexterity
$dex = roll_stats();
// Constitution
$const = roll_stats();
// Intelligence
$int = roll_stats();
// Wisdom
$wis = roll_stats();
// Charisma
$cha = roll_stats();

// Religion
// Read the religions into an array
$religion_array = file("random\\deities.txt", FILE_IGNORE_NEW_LINES);
// Grab a random index in the array
$rand_index = rand(1, count($religion_array));
$religion = $religion_array[$rand_index - 1];


// Gender
$rand_index = rand(0, 1);
$gender = "";
if($rand_index == 0){
	// Male
	$gender = "Male";
}else{
	// Female
	$gender = "Female";
}

// Class
// Grab a random class from the array
$class_id = rand(1, $class_length);
$class = $class_array[$class_id - 1];

// Race
// Grab a random class from the array
$race_id = rand(1, $race_length);
$race = $race_array[$race_id - 1];

// Name - may be a bit difficult
$name = "Noname";
$name_file = "";
$last_name_file = "";
// For half breeds
$choice = 0;
switch ($race){
	case "Human":
		$name_file = "human_names.txt";
		break;
	case "Dwarf":
		$name_file = "dwarven_names.txt";
		$last_name_file = "dwarven_clan_names.txt";
		break;
	case "Elf":
		$name_file = "elven_names.txt";
		$last_name_file = "elven_family_names.txt";
		break;
	case "Gnome":
		$name_file = "gnome_names.txt";
		$last_name_file = "gnome_clan_names.txt";
		break;
	case "Half-elf":
		// Half elf go with either human or elf
		$choice = rand(1,2);
		if($choice == 1){
			//Human
			$name_file = "human_names.txt";
		} else {
			// Elf
			$name_file = "elven_names.txt";
			$last_name_file = "elven_family_names.txt";
		}
		break;
	case "Half-orc":
		// Half orc go with either human or orc
		$choice = rand(1, 2);
		if($choice == 1){
			// Human
			$name_file = "human_names.txt";
		} else {
			// Orc
			$name_file = "orc_names.txt";
		}
		break;
	case "Halfling":
		$name_file = "halfling_names.txt";
		break;
}
// Read the file into an array
$first_name_array = file("random\\".$name_file, FILE_IGNORE_NEW_LINES);
// Get a random index from the array
$rand_index = rand(0, count($first_name_array));
$name = $first_name_array[$rand_index];

if($last_name_file != ""){
	// Read into array
	$last_name_array = file("random\\".$last_name_file, FILE_IGNORE_NEW_LINES);
	// Get random index out of the array
	$rand_index = rand(0, count($last_name_array));
	$name = $name . " " . $last_name_array[$rand_index];
}

// Weight - based on race and gender
$w_h_result = mysqli_query($con, "SELECT * FROM r_heights_weights WHERE race_id='$race_id' AND gender='$gender'");
$w_h_info = mysqli_fetch_array($w_h_result);
// 0		1		2		3		4			5		6		7
// race_id	gender	base_h	#dice_h	d_type_h	base_w	#dice_w	d_type_w

$weight = $w_h_info["base_weight"] + roll_dice($w_h_info["num_dice_weight"], $w_h_info["sides_dice_weight"]);

// Height - based on race and gender
$height = $w_h_info["base_height"] + roll_dice($w_h_info["num_dice_height"], $w_h_info["sides_dice_height"]);

// Age - based on race and class
$base_age_result = mysqli_fetch_array(mysqli_query($con, "SELECT base_age FROM r_base_ages WHERE race_id='$race_id'"));
$base_age = $base_age_result["base_age"];

$class_type_result = mysqli_fetch_array(mysqli_query($con, "SELECT class_type_id FROM r_class_types WHERE class_id='$class_id'"));
$class_type = $class_type_result["class_type_id"];

$age_result = mysqli_query($con, "SELECT * from r_additional_ages WHERE race_id='$race_id' AND class_type_id='$class_type'");
$age_info = mysqli_fetch_array($age_result);
$age = $base_age + roll_dice($age_info["num_dice"], $age_info["sides_dice"]);

// Hit Points - based on class, add const modifier
$hit_dice_result = mysqli_fetch_array(mysqli_query($con, "SELECT hd FROM classes WHERE class_id='class_id'"));
$hit_dice = $hit_dice_result["hd"];

$const_mod = $const - 10;
if($const_mod < 0) {
	$const_mod == 0;
} else
$hit_points = roll_dice(1, $hit_dice) + $const;

// Alignment - random.
// Function to take care of setting the alignment
function set_alignment(){
	$align_num = rand(0, 8);
	// set the alignment based on the number rolled
	switch($align_num){
		case 0:
			$alignment = "LG";
		break;
		case 1:
			$alignment = "NG";
		break;
		case 2:
			$alignment = "CG";
		break;
		case 3:
			$alignment = "LN";
		break;
		case 4:
			$alignment = "N";
		break;
		case 5:
			$alignment = "CN";
		break;
		case 6:
			$alignment = "LE";
		break;
		case 7:
			$alignment = "NE";
		break;
		case 8:
			$alignment = "CE";
		break;
	}
	return $alignment;
}
$alignment = "";
$alignment = set_alignment();
// Later, if we add restrictions to the alignment based on class/race, it can
// be reset until it has the correct alignment.


// Money - Based on class.
$money = 0;
switch ($class){
	// Barbarian 	4d4 x 10
	// Bard			4d4 x 10
	case "Barbarian": case "Bard":
		$money = roll_dice(4, 4);
	break;
	// Cleric		5d4 x 10
	// Monk			5d4	
	// Rogue		5d4 x 10
	case "Cleric": case "Monk": case "Rogue":
		$money = roll_dice(5, 4);
	break;
	// Druid		2d4 x 10
	case "Druid":
		$money = roll_dice(2, 4);
	break;
	// Fighter		6d4 x 10
	// Paladin		6d4 x 10
	// Ranger		6d4 x 10
	case "Fighter": case "Paladin": case "Ranger":
		$money = roll_dice(6, 4);
	break;
	// Sorcerer		3d4 x 10
	// Wizard		3d4 x 10
	case "Sorcerer": case "Wizard":
		$money = roll_dice(3, 4);
	break;
}
// All but monk are multiplied by 10
if($class != "Monk"){
	$money *= 10;
}

$form_array = array();
$form_array["character_name"] = mysqli_real_escape_string($con, $name);
$form_array["character_level"] = intval($level);
$form_array["str_attr"] = intval($str);
$form_array["int_attr"] = intval($int);
$form_array["cha_attr"] = intval($cha);
$form_array["con_attr"] = intval($const);
$form_array["dex_attr"] = intval($dex);
$form_array["wis_attr"] = intval($wis);
$form_array["weight"] = intval($weight);
$form_array["height"] = intval($height);
$form_array["age"] = intval($age);
$form_array["religion"] = mysqli_real_escape_string($con, $religion);
$form_array["gender"] = mysqli_real_escape_string($con, $gender);
$form_array["char_class"] = intval($class_id);
$form_array["race"] = intval($race_id);
$form_array["hit_points"] = intval($hit_points);
$form_array["alignment"] = mysqli_real_escape_string($con, $alignment);
$form_array["money"] = floatval($money);

$res = mysqli_query($con, "SELECT user_id FROM users WHERE username = '{$_SESSION["user"]}' ;");
if ($res) {
	$row = mysqli_fetch_array($res);
	$form_array["user_id"] = $row["user_id"];
} else {
	header("Location: index.php");
}

if (is_valid($con, $form_array)) {
	$insert_str = "INSERT INTO characters (";
	$values_str = "VALUES (";

	foreach ( $form_array as $key => $value ) {
		if ( $key === "user_id" ) {
			$insert_str = $insert_str . $key . ")";
			$values_str = $values_str . "'" . $value . "')";
		} else {
			$insert_str = $insert_str . $key . ",";
			$values_str = $values_str . "'" . $value . "',";
		}
	}
	$newChar = mysqli_multi_query($con, "$insert_str $values_str");
	header("Location: index.php");
} # doesn't do anything if invalid because invalid form data would require user to subvert html form
else{
	echo "User: " . $form_array["user_id"]  . "\n";
	echo "Name: " . $form_array["character_name"] . "\n";
	echo "Level: " . $form_array["character_level"] . "\n";
	echo "Strength: " . $form_array["str_attr"] . "\n";
	echo "Intelligence: " . $form_array["int_attr"] . "\n";
	echo "Charisma: " . $form_array["cha_attr"] . "\n";
	echo "Constitution: " . $form_array["con_attr"] . "\n";
	echo "Dexterity: " . $form_array["dex_attr"] . "\n";
	echo "Wisdom: " . $form_array["wis_attr"] . "\n";
	echo "Weight: " . $form_array["weight"] . "\n";
	echo "Height: " . $form_array["height"] . "\n";
	echo "Age: " . $form_array["age"] . "\n";
	echo "Religion: " . $form_array["religion"] . "\n";
	echo "Gender: " . $form_array["gender"] . "\n";
	echo "Class: " . $form_array["char_class"] . "\n";
	echo "Race: " . $form_array["race"] . "\n";
	echo "HP: " . $form_array["hit_points"] . "\n";
	echo "Alignment: " . $form_array["alignment"] . "\n";
	echo "Money: " . $form_array["money"] . "\n";
}

mysqli_close($con);
?>