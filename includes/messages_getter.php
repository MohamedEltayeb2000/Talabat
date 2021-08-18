<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "joinus";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  	die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["second_user"])) {
	$id1 = $_SESSION["userInfo"]["data"]["id"];
	$id2 = $_POST["second_user"];
}
else if(!isset($id1) && isset($_POST["first_user"])){
	$id1 = $_POST["first_user"];
}

$sql = "SELECT * FROM `open_chats` WHERE `First_User_id`=\"$id1\" AND `Second_User_id`=\"$id2\" AND `FileName` != \"\" AND `FileName` IS NOT NULL OR `First_User_id`=\"$id2\" AND `Second_User_id`=\"$id1\" AND `FileName` != \"\" AND `FileName` IS NOT NULL";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)){
		if(isset($row["FileName"]) || !empty($row["FileName"]) || $row["FileName"] != ""){
			$msg = file_get_contents($row["FileName"]);
			echo $msg;
		}
		else{
			echo "nothing";
		}
	}
}
else{
	echo "nothing";
}
