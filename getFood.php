<?php
include "UserData.php";
$user = new User();

if(!isset($_POST["foodid"])){
	echo "error";
	exit();
}
$id = $_POST["foodid"];

$data = $user->getFoodDetails($id);
if($data == "error"){
	echo "error";
	exit();
}

if(isset($_POST["get"])){
	echo json_encode($data, JSON_FORCE_OBJECT);
}
else if(isset($_POST["add"]) && isset($_POST["chef"])){
	//echo json_encode($_SESSION["cart"], JSON_FORCE_OBJECT);
	if(!empty($_SESSION["cart"])){
		for ($i=0; $i < count($_SESSION["cart"]); $i++) {
			if(in_array($id, $_SESSION["cart"][$i])){
				exit();
			} 
		}
	}
	$chef = $_POST["chef"];
	$data += ["id" => $id];
	$data += ["chef" => $chef];
	$data += ["offer" => $_SESSION["chef"]["offer"]];
	$data += ["restname" => $_SESSION["chef"]["restname"]];
	if($chef == $_SESSION["userInfo"]["data"]["id"]){
		exit();
	}
	else if(!isset($_SESSION["cart"])){
		echo "not set\n";
		$_SESSION["cart"] = [];
		array_push($_SESSION["cart"], $data);
	}
	else{
		echo "set\n";
		array_push($_SESSION["cart"], $data);
	}
}
else{
	echo "error";
	exit();
}
