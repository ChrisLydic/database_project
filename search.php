<?php
session_start();

if (!$_SESSION["auth"]) {
	$_SESSION["auth"] = false;
	header("Location: log_in.php");
} else {
	if (isset($_POST["query"]) && isset($_POST["search_type"])) {
		$query = $_POST["query"];
		$search_type = $_POST["search_type"];

		require("db_open.php");

		$result = mysqli_query($con, "SELECT character_name, character_id FROM characters WHERE character_name like '%$query%'");
		if ($result) {
			$empty_result = false;
			$results = array();
			while($row = mysqli_fetch_array($result)) {
				$results[$row["character_id"]] = $row["character_name"];
			}

			if (sizeof($results) === 0) {
				$empty_result = true;
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
		<title>Search</title>
		<meta name="author" content="Alex Hedges" />
	</head>
	<body onload="refresh(false);">
		<?php require("header.php"); ?>

		<h1>Search</h1>

		<form name="form" method="post">
			<input name="query" type="search" required="required">

			<select name="search_type">
				<option value="character">Characters</option>
				<option value="item">Items</option>
			</select>

			<input type="submit" value="Search">
		</form>

		<?php
		if(isset($results)) {
		?>
		<h2>Results:</h2>

		<?php
		if($empty_result) {
		?>
		<p>No results found.</p>
		<?php
		} else {
		?>
		<ul>
			<?php
			foreach ($results as $key => $value) {
				?>
				<li><a href="character.php?char=<?php echo $key; ?>"><?php echo $value; ?></a></li>
				<?php
			}
			?>
		</ul>
		<?php
		}}
		?>

	</body>
</html>
<?php
}
?>