<?php
require "../UserData.php";
$userData = new User();

if(isset($_POST["rate"]) & isset($_GET["to"]) && isset($_GET["order"])){
	if(isset($_GET["from"])){
		$user = $_GET["from"];
	}
	else{
		$user = $_SESSION["userInfo"]["data"]["id"];
	}
	$chef = $_GET["to"];
	$rate = $_POST["rate"];
	$order = $_GET["order"];
}
else{
	header("Location: chat.php?error=RateError");
}

$isRated = $userData->setRate($chef, $rate);
if(!$isRated){
	header("Location: ../chat.php?error=RateError");
}

$year =(int) date("Y");
$month = (int) date("m");
$day = (int) date("d");
$time = date("H:i");

$userData->getPageData($chef);

$msg = ["message" => $rate, "time" => $time, "day" => $day, "month" => $month, "year" => $year, "user_from" => ["id" => -2, "user_id" =>$_SESSION["userInfo"]["data"]["id"],"name" => $_SESSION["userInfo"]["data"]["name"]], "user_to" => ["id" => $chef,"name" => $_SESSION["chef"]["name"], "restname" => $_SESSION["chef"]["restname"]]];

$file = $userData->getFileName($user, $chef);
if($file == "error"){
	header("Location: ../chat.php?error=RateError");
}



$fileData = file_get_contents($file);
$fileData = json_decode($fileData, true);


for ($i=0; $i < count($fileData["messages"]); $i++) { 
	if($fileData["messages"][$i]["user_from"]["id"] == -1 && $fileData["messages"][$i]["message"] == $order){
		$fileData["messages"][$i] = $msg;
	}
}

$data = json_encode($fileData);
file_put_contents($file, $data);
header("Location: ../chat.php");




