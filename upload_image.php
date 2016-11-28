<?php
session_start();

if (!$_SESSION["auth"]) {
    $_SESSION["auth"] = false;
    header("Location: log_in.php");
} else {
    if (isset($_GET["char"])) {
        $char_id = $_GET["char"];
    } else if(isset($_POST["char"])) {
        $char_id = $_POST["char"];
    }else{
        header("Location: error.php");
    }

    $is_owner = false;

    if (isset($_SESSION["allowed"][$char_id])) {
        $is_owner = true;
    }

    require("db_open.php");
    require("character_utils.php");
    $result = mysqli_query($con, "SELECT * FROM characters WHERE character_id='$char_id'");
    $row = mysqli_fetch_array($result);
    $dir = "charImages/";

    if(isset($_FILES["image"]) and $char_id > 0) {
        if ($_FILES["image"]["error"] > 0) {
			if ($_FILES["image"]["error"] == UPLOAD_ERR_NO_FILE)
			{
				mysqli_query($con, "UPDATE  characters SET image_path = '' WHERE character_id = $char_id;");
				header("Location: character.php?char=$char_id");
			} else {
				echo "Return Code: " . $_FILES["image"]["error"] . "<br />";
			}
        } else {
            $file = $_FILES["image"];
            $file_name = $file['name'];
            $file_size = $file['size'];
            $file_error = $file['error'];

            $file_ext = explode('.', $file_name);
            $file_ext = strtolower(end($file_ext));

            $allowed = ['png', 'jpg', 'gif'];

            if (in_array($file_ext, $allowed)) {

                if (!file_exists($dir)) {
                    mkdir($dir);
                }
                $path = $char_id.".png";

                $fn = $_FILES['image']['tmp_name'];
                $size = getimagesize($fn);
                $ratio = $size[0]/$size[1]; // width/height
                if( $ratio > 1) {
                    $width = 500;
                    $height = 500/$ratio;
                }
                else {
                    $width = 500*$ratio;
                    $height = 500;
                }
                $src = imagecreatefromstring(file_get_contents($fn));
                $dst = imagecreatetruecolor($width,$height);
                imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
                imagedestroy($src);
                imagepng($dst,$dir . $path);
                imagedestroy($dst);

                mysqli_query($con, "UPDATE  characters SET image_path = '$path' WHERE character_id = $char_id;");
                $row["image_path"] = $path;
				header("Location: character.php?char=$char_id");
            } else {

            }
        }
	}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link href="screen.css" rel="stylesheet" type="text/css" media="screen"/>
		<title>Upload Image for <?= $row["character_name"] ?></title>
	</head>
	<body>
		<?php require("header.php"); ?>
		<?php
		if ($row["image_path"] != "") {
			$full_path = $dir . $row["image_path"];
			echo "<img src=$full_path alt='image currently not set'>";
		}
		?>
		<form action="upload_image.php" method="post" enctype="multipart/form-data">
			<label for="image">Image</label>
			<input type="file" class="form-control-file" id="image" name="image" aria-describedby="fileHelp2">
			<small id="fileHelp2" class="form-text text-muted">Select no file to clear stored image.</small>
			<?= "<input type='hidden' name='char' value=$char_id />" ?>
			<input type="submit" value="Upload Image" name="submit">
		</form>
		<p><a href="character.php?char=<?= $char_id ?>">Main Character Page</a></p>
	</body>
</html>
<?php
}
?>