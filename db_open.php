<?php
$HOST = "localhost";
$USERNAME = "rpg_user";
$PASSWORD = "This_is_a_passphrase!";
$DBNAME = "rpg";
$con = mysqli_connect($HOST, $USERNAME, $PASSWORD, $DBNAME);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>