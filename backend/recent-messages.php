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

if($_SESSION["userInfo"]["data"]["id"] == null || $_SESSION["userInfo"]["data"]["id"] == ""){
	exit();
}


$id = $_SESSION["userInfo"]["data"]["id"];
$user_name = $_SESSION["userInfo"]["data"]["name"];
$sql = "SELECT * FROM `open_chats` WHERE First_User_id=\"$id\" OR Second_User_id=\"$id\"";

$result = $conn->query($sql);

$chats = [];
$users = [];
$type = [];
$photo = [];
if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		if($id == $row["First_User_id"]){
			array_push($chats, $row["Second_User_id"]);
		}
		else if($id != $row["First_User_id"]){
			array_push($chats, $row["First_User_id"]);
		}
	}
}

for ($i=0; $i <sizeof($chats); $i++) {
	$sql1 = "SELECT * FROM `joinu` WHERE ID=\"$chats[$i]\"";
	$result = $conn->query($sql1);

	if(!empty($result) && $result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			array_push($users, $row["Name"]);
			array_push($photo, $row["Photo"]);
			array_push($type, "chef");
		}
	}
	else{
		$sql = "SELECT * FROM `register` where id=\"$chats[$i]\"";
		$result = $conn->query($sql);

		if(!empty($result) && $result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				array_push($users, $row["name"]);
				array_push($photo, "nothing");
				array_push($type, "user");
			}
		}
	}
}

$data = [
'id' => $id,
'username' => $user_name,
'chats' => $chats,
'users' => $users,
'photo' => $photo,
'type' => $type
];
echo json_encode($data, JSON_FORCE_OBJECT);

$conn->close();
?>