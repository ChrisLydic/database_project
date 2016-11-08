<?php

function is_valid($con, $form) {
	if ( strlen($form["character_name"]) > 50 ) {
		return false;
	}
	if ( $form["character_level"] <= 0 ) {
		return false;
	}
	if ( $form["str_attr"] < 0 ) {
		return false;
	}
	if ( $form["dex_attr"] < 0 ) {
		return false;
	}
	if ( $form["con_attr"] < 0 ) {
		return false;
	}
	if ( $form["int_attr"] < 0 ) {
		return false;
	}
	if ( $form["wis_attr"] <= 0 ) {
		return false;
	}
	if ( $form["cha_attr"] <= 0 ) {
		return false;
	}
	if ( $form["weight"] < 0 ) {
		return false;
	}
	if ( $form["height"] < 0 ) {
		return false;
	}
	if ( $form["age"] < 0 ) {
		return false;
	}
	if ( strlen($form["religion"]) > 20 ) {
		return false;
	}
	if ( strlen($form["gender"]) > 10 ) {
		return false;
	}
	$alignment_array = ['LG', 'NG', 'CG', 'LN', 'N', 'CN', 'LE', 'NE', 'CE'];
	if ( !in_array($form["alignment"], $alignment_array) ) {
		return false;
	}
	if ( $form["money"] < 0 ) {
		return false;
	}
	$result_class = mysqli_query($con, "SELECT class_id FROM classes WHERE class_id = '{$form["char_class"]}' ;");
	if ( !$result_class ) {
		return false;
	}
	$result_race = mysqli_query($con, "SELECT race_id FROM races WHERE race_id = '{$form["race"]}' ;");
	if ( !$result_race ) {
		return false;
	}

	// All constraints are satisfied
	return true;
}