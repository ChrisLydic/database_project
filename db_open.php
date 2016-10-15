<?php
$HOST = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$DBNAME = "rpg";
$con = mysqli_connect($HOST, $USERNAME, $PASSWORD, $DBNAME);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>