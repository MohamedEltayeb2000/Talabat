<?php
include "../userData.php";
$user = new User();

if(!isset($_POST) || empty($_POST) && !isset($_SESSION["cart"]) || empty($_SESSION["cart"])){
	header("Location: ../Cart.php?error");
}
$order = [];
for ($i=0; $i < count($_SESSION["cart"]); $i++) { 
	if($i == 0 && $i != count($_SESSION["cart"])-1){ //First food in the cart
		insertFoodOrder($_POST[(string)$i], $i, 0);
		$_SESSION["cart"][$i] += ["quantity" => $_POST[(string)$i]];
		array_push($order, $_SESSION["cart"][$i]);
	}
	else if(count($_SESSION["cart"])-1 == 0){
		insertFoodOrder($_POST[(string)$i], $i, 0);
		$_SESSION["cart"][$i] += ["quantity" => $_POST[(string)$i]];
		array_push($order, $_SESSION["cart"][$i]);

		$isPlaced =  $user->placeOrders($_SESSION["userInfo"]["data"]["id"], getFoodID($_SESSION["cart"][$i]["id"]), $_SESSION["cart"][$i]["chef"]);
		if(!$isPlaced){
			header("Location: ../Cart.php?error=NotPlaced");
		}

		addMsgs($i+1, $order, $user);
	}
	else if($_SESSION["cart"][$i]["chef"] == $_SESSION["cart"][$i-1]["chef"] && $i != count($_SESSION["cart"])-1){//chef is different and not the end of the cart
		insertFoodOrder($_POST[(string)$i], $i, 1);
		$_SESSION["cart"][$i] += ["quantity" => $_POST[(string)$i]];
		array_push($order, $_SESSION["cart"][$i]);
	}
	else if($_SESSION["cart"][$i]["chef"] == $_SESSION["cart"][$i-1]["chef"] && $i == count($_SESSION["cart"])-1){//chef is different and end of the cart
		insertFoodOrder($_POST[(string)$i], $i, 1);
		$_SESSION["cart"][$i] += ["quantity" => $_POST[(string)$i]];
		array_push($order, $_SESSION["cart"][$i]);

		$isPlaced =  $user->placeOrders($_SESSION["userInfo"]["data"]["id"], getFoodID($_SESSION["cart"][$i]["id"]), $_SESSION["cart"][$i]["chef"]);
		if(!$isPlaced){
			header("Location: ../Cart.php?error=NotPlaced");
		}

		addMsgs($i+1, $order, $user);
	}
	else if($_SESSION["cart"][$i]["chef"] != $_SESSION["cart"][$i-1]["chef"] && $i == count($_SESSION["cart"])-1){ //chef is not different and end of the cart
		insertFoodOrder($_POST[(string)$i], $i, 0);
		$_SESSION["cart"][$i] += ["quantity" => $_POST[(string)$i]];
		$order = [];
		array_push($order, $_SESSION["cart"][$i]);

		$isPlaced =  $user->placeOrders($_SESSION["userInfo"]["data"]["id"], getFoodID($_SESSION["cart"][$i]["id"]), $_SESSION["cart"][$i]["chef"]);
		if(!$isPlaced){
			header("Location: ../Cart.php?error=NotPlaced");
		}

		addMsgs($i+1, $order, $user);
	}
	else{// chef is not different and not end of the cart
		//insert all orders realted to a chef
		$isPlaced =  $user->placeOrders($_SESSION["userInfo"]["data"]["id"], getFoodID($_SESSION["cart"][$i-1]["id"]), $_SESSION["cart"][$i-1]["chef"]);
		if(!$isPlaced){
			header("Location: ../Cart.php?error=NotPlaced");
		}

		addMsgs($i, $order, $user);

		insertFoodOrder($_POST[(string)$i], $i, 0);
		$_SESSION["cart"][$i] += ["quantity" => $_POST[(string)$i]];
		$order = [];
		array_push($order, $_SESSION["cart"][$i]);

	}
}
$_SESSION["cart"] = [];
header("Location: ../chat.php");



// add order and review to messages
function addMsgs($i, $orders, $user){
	$i = $i -1;

	//get time, day, month and year
	$year =(int) date("Y");
	$month = (int) date("m");
	$day = (int) date("d");
	$time = date("H:i");

	//get data of user
	$user->getPageData($_SESSION["cart"][$i]["chef"]);

	//msg to show
	$msg1 = ["message" => [$orders], "time" => $time, "day" => $day, "month" => $month, "year" => $year, "user_from" => ["id" => 0, "user_id" => $_SESSION["userInfo"]["data"]["name"], "name" => $_SESSION["userInfo"]["data"]["name"]], "user_to" => ["id" => $_SESSION["chef"]["id"] ,"name" => $_SESSION["chef"]["name"], "restname" => $_SESSION["chef"]["restname"]]];

	$msg2 = ["message" => $_SESSION["last_id"], "time" => $time, "day" => $day, "month" => $month, "year" => $year, "order"=> $_SESSION["last_id"],"user_from" => ["id" => -1, "user_id" => $_SESSION["userInfo"]["data"]["name"], "name" => $_SESSION["userInfo"]["data"]["name"]], "user_to" => ["id" => $_SESSION["chef"]["id"] ,"name" => $_SESSION["chef"]["name"], "restname" => $_SESSION["chef"]["restname"]]];
	//get file name to send order to chat
	$file = $user->getFileName($_SESSION["userInfo"]["data"]["id"], $_SESSION["cart"][$i]["chef"]);
	if($file == "error"){
		$file = "../messages/chat(".$_SESSION["userInfo"]["data"]["id"].",".$_SESSION["cart"][$i]["chef"].").json";
		$user->insertFile($_SESSION["userInfo"]["data"]["id"], $_SESSION["cart"][$i]["chef"], $file);

		$fileData = ["messages" => [$msg1, $msg2]];
		json_encode($fileData , JSON_FORCE_OBJECT);
		$data =  json_encode($fileData);
		file_put_contents($file, $data);
		}
	else{
		$fileData = file_get_contents($file);
		$fileData = json_decode($fileData, true);

		array_push($fileData["messages"], $msg1);
		array_push($fileData["messages"], $msg2);
		$data = json_encode($fileData);
		file_put_contents($file, $data);
	}
}




//insert food in order table
function insertFoodOrder($qauntity, $number, $anchor){
	$con = getConnection();
	$offer = (double)$_SESSION["cart"][$number]["offer"];
	$price = (double)$_SESSION["cart"][$number]["price"];
	$price = $price - $price * ($offer/100);
	$prev_orderID;
	$qauntity = (int)$qauntity;
	$foodid = (int)$_SESSION["cart"][$number]["id"];
	$name = $_SESSION["cart"][$number]["name"];
	if($anchor == 0){//food is first in the cart or food is first for a different chef
		$sql = "INSERT INTO `order`(`food_id`, `name`, `quantitiy`, `price`) VALUES ($foodid,'$name',$qauntity,$price)";
		$prev_orderID = $_SESSION["cart"][$number]["id"];
	}
	else{// food is not first in cart or for a different chef
		$id = (int)getFoodID($_SESSION["cart"][$number-1]["id"]);
		echo $id."\n";
		$sql = "INSERT INTO `order`(`id`,`food_id`, `name`, `quantitiy`, `price`) VALUES ($id,$foodid,'$name',$qauntity,$price)";
	}
	$result = mysqli_query($con, $sql);
	$_SESSION["last_id"] = mysqli_insert_id($con);
	if(!$result){
		header("Location: ../Cart.php?error=InsertOrder");
	}
}

//get id of last inserted order
function getFoodID($id){
	$con = getConnection();
	$sql  = "SELECT * FROM `order` WHERE `food_id`='$id' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($con, $sql);
	if ($result && mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			return $row["id"];
		}
	} 
}


//get connection
function getConnection(){
	$con = mysqli_connect("localhost", "root","","joinus");
	if($con->connect_error){
		die("Connection failed: " . $con->connect_error);
	}
	return $con;
}
