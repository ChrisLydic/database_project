<?php

function is_valid($con, $form) {
    $character_name_valid = true;
    $character_level_valid = true;
    $str_attr_valid = true;
    $int_attr_valid = true;
    $cha_attr_valid = true;
    $con_attr_valid = true;
    $dex_attr_valid = true;
    $wis_attr_valid = true;
    $weight_valid = true;
    $height_valid = true;
    $age_valid = true;
    $religion_valid = true;
    $gender_valid = true;
    $char_class_valid = true;
    $race_valid = true;
    $hit_points_valid = true;
    $alignment_valid = true;
    $money_valid = true;

    if ( strlen($form["character_name"]) > 50 ) {
        $character_name_valid = false;
    }
    if ( $form["character_level"] <= 0 ) {
        $character_level_valid = false;
    }
    if ( $form["str_attr"] < 0 ) {
        $str_attr_valid = false;
    }
    if ( $form["int_attr"] < 0 ) {
        $int_attr_valid = false;
    }
    if ( $form["cha_attr"] < 0 ) {
        $cha_attr_valid = false;
    }
    if ( $form["con_attr"] < 0 ) {
        $con_attr_valid = false;
    }
    if ( $form["dex_attr"] < 0 ) {
        $dex_attr_valid = false;
    }
    if ( $form["wis_attr"] < 0 ) {
        $wis_attr_valid = false;
    }
    if ( $form["weight"] <= 0 ) {
        $weight_valid = false;
    }
    if ( $form["height"] <= 0 ) {
        $height_valid = false;
    }
    if ( $form["age"] <= 0 ) {
        $age_valid = false;
    }
    if ( strlen($form["religion"]) > 20 ) {
        $religion_valid = false;
    }
    if ( strlen($form["gender"]) > 10 ) {
        $gender_valid = false;
    }
    if ( $form["hit_points"] < 0 ) {
        $hit_points_valid = false;
    }
    $alignment_array = ['LG', 'NG', 'CG', 'LN', 'N', 'CN', 'LE', 'NE', 'CE'];
    if ( !in_array($form["alignment"], $alignment_array) ) {
        $alignment_valid = false;
    }
    if ( $form["money"] < 0 ) {
        $money_valid = false;
    }

    $result_class = mysqli_query($con, "SELECT class_id FROM classes WHERE class_id = '{$form["char_class"]}' ;");
    $result_race = mysqli_query($con, "SELECT race_id FROM races WHERE race_id = '{$form["race"]}' ;");
    if ( !$result_class ) {
        $char_class_valid = false;
    }
    if ( !$result_race ) {
        $race_valid = false;
    }

    // check if all constraints are valid
    return $character_name_valid
        && $character_level_valid
        && $str_attr_valid
        && $int_attr_valid
        && $cha_attr_valid
        && $con_attr_valid
        && $dex_attr_valid
        && $wis_attr_valid
        && $weight_valid
        && $height_valid
        && $age_valid
        && $religion_valid
        && $gender_valid
        && $char_class_valid
        && $race_valid
        && $hit_points_valid
        && $alignment_valid
        && $money_valid;
}