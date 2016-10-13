<?php
$HOST = "localhost";
$USERNAME = "catnob_chatlogin";
$PASSWORD = "durian";
$DBNAME = "catnob_chatlogin";
$con = mysqli_connect($HOST, $USERNAME, $PASSWORD, $DBNAME);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>