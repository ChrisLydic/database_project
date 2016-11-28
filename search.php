<?php
session_start();

if (!$_SESSION["auth"]) {
	$_SESSION["auth"] = false;
	header("Location: log_in.php");
} else {
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title>Search</title>
		<script type="text/javascript">
            //<![CDATA[
			function showResult() {
				str = document.getElementById("query").value;
				if (str.length==0) {
					document.getElementById("results").innerHTML="";
					return;
				}
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
				xmlhttp.open("GET","search_server.php?q="+str,true);
				xmlhttp.send();
			}
            //]]>
        </script>
	</head>
	<body>
		<?php require("header.php"); ?>

		<h1>Search</h1>

		<form name="form">
			<input id="query" name="query" type="search" onkeyup="showResult();"/>
		</form>
		
		<h2>Results:</h2>

		<div id="results"></div>

	</body>
</html>
<?php
}
?>