<?php
include "dbcon.php";
include "userData.php";
$user = new User();
if(!isset($_POST) || empty($_POST) && !isset($_SESSION["cart"]) || empty($_SESSION["cart"])){
	header("Location: Cart.php?error");
}
for ($i=0; $i < count($_SESSION["cart"]); $i++) { 
	if($i == 0){
		insertFoodOrder($_POST[$i], $i, 0);
	}
	else if($_SESSION["cart"][$i]["chef"] == $_SESSION["cart"][$i-1]["chef"]){
		insertFoodOrder($_POST[$i], $i, 1);
	}
	else{
		$msg = [];

		//get time, day, month and year
		$year =(int) date("Y");
		$month = (int) date("m");
		$day = (int) date("d");
		$time = date("H:i");

		//get data of user
		$user->getPageData($_SESSION["cart"][$i-1]["chef"]);

		//msg to show
		$msg += ["message" => $rate, "time" => $time, "day" => $day, "month" => $month, "year" => $year, "user_from" => ["id" => 0, "user_id" => $_SESSION["userInfo"]["data"]["name"], "name" => $_SESSION["userInfo"]["data"]["name"]], "user_to" => ["id" => $_SESSION["chef"]["id"] ,"name" => $_SESSION["chef"]["name"], "restname" => $_SESSION["chef"]["restname"]]];
		//get file name to send order to chat
		$file = getFileName($user, $chef);
		if($file == "error"){
			$fileName = "../messages/chat($_SESSION["userInfo"]["data"]["id"], $_SESSION["cart"][$i-1]["chef"])";
			$user->insertFile($_SESSION["userInfo"]["data"]["id"], $_SESSION["cart"][$i-1]["chef"], $fileName);
		}
		else{
			$fileData = file_get_contents($file);
			$fileData = json_decode($fileData, true);

			$fileData["messages"] += $msg[0];

			json_encode($fileData);
			file_put_contents($file, $data);
		}

		//insert all orders realted to a chef
		$isPlaced =  placeOrders($_SESSION["userInfo"]["data"]["id"], getFoodID($_SESSION["cart"][$i-1]["id"]), $_SESSION["cart"][$i-1]["chef"]);
		if(!$isPlaced){
			header("Location: Cart.php?error");
		}

		insertFoodOrder($_POST[$i], $i, 0);
	}
}






function insertFoodOrder($qauntity, $number, $anchor){
	$price = (int)$_SESSION["cart"][$number]["price"] - (int)$_SESSION["cart"][$number]["price"] * (int)$_SESSION["cart"][$number]["offer"];
	$prev_orderID;
	if($anchor == 0){
		$sql = "INSERT INTO `order`(`food_id`, `name`, `quantitiy`, `price`) VALUES ('$_SESSION["cart"][$number]["id"]','$_SESSION["cart"][$number]["name"]','$qauntity','$price')";
		$prev_orderID = $_SESSION["cart"][$number]["id"];
	}
	else{
		$id = getFoodID($id);
		$sql = "INSERT INTO `order`(`id`,`food_id`, `name`, `quantitiy`, `price`) VALUES ('$id','$_SESSION["cart"][$number]["id"]','$_SESSION["cart"][$number]["name"]','$qauntity','$price')";
	}
	$result = mysqli_query($con, $sql);
	if(!$result){
		header("Location: Cart.php?error");
	}
}

function getFoodID($id){
	$sql  = "SELECT * FROM `order` WHERE `food_id`='$id'";
	$result = mysqli_query($con, $sql);
	if ($result && mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			return $row["id"];
		}
	} 
}
