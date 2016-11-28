<?php
session_start();

if (!$_SESSION["auth"]) {
	$_SESSION["auth"] = false;
	header("Location: log_in.php");
} else {
	if (isset($_GET["char"])) {
		$char_id = $_GET["char"];
	} else {
		header("Location: error.php");
	}
	
	require("db_open.php");
	$char_result = mysqli_query($con, "SELECT * FROM characters WHERE character_id='$char_id'");
	$char_row = mysqli_fetch_array($char_result);
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title>Add Items for <?= $char_row["character_name"] ?></title>
		<script type="text/javascript">
            //<![CDATA[
			function showResult() {
				str = document.getElementById("query").value;
				type = document.getElementById("search_type").value;
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				} else {  // code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function() {
					if (this.readyState==4 && this.status==200) {
						document.getElementById("results").innerHTML=this.responseText;
					}
				}
				xmlhttp.open("GET","add_item_server.php?q="+str+"&type="+type+"&char=<?= $char_id ?>",true);
				xmlhttp.send();
			}
            //]]>
        </script>
	</head>
	<body onload="showResult();">
		<?php require("header.php"); ?>

		<h1>Add Items for <?= $char_row["character_name"] ?></h1>

		<form name="form">
			<input id="query" name="query" type="search" onkeyup="showResult();"/>
			<select id="search_type" name="search_type" onchange="showResult();">
				<option value="item">Generic Items</option>
				<option value="weapon">Weapons</option>
				<option value="armor">Armor</option>
			</select>
		</form>

		<h2>Results:</h2>

		<div id="results"></div>

	</body>
</html>
<?php
}
?>