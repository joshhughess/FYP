<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
$mysqli = new Mysqli($host,$userName,$password, $db);
if(isset($_SESSION['username'])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

    $imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION));

    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        //Get the content of the image and then add slashes to it
        $imagetmp = addslashes(file_get_contents($_FILES['fileToUpload']['tmp_name']));
        $userID = mysqli_real_escape_string($connect,$_SESSION['userID']);
        $insertImage = "UPDATE users SET picture='" . $imagetmp . "' WHERE userID='" . $userID . "'";
        $res = mysqli_query($connect, $insertImage);
        if (!$res) {
            echo mysqli_error($connect);
        } else {
            header("Location:profile.php");
        }
    }
}else{
    header("Location:index.php?notLoggedin");
}
?>
