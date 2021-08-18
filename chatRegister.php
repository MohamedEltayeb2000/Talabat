<?php
include "userData.php";
session_start();
$user = new User();

if(isset($_GET["id"])){
	if($_GET["id"] == $_SESSION["userInfo"]["data"]["id"]){
		header("Location: chef.php?id=".$_SESSION["userInfo"]["data"]["id"]);
	}
	else{
		$id1 = $_SESSION["userInfo"]["data"]["id"];
		$id2 = $_GET["id"];
		$isInserted = $user->registerChatUsers($id1, $id2);
		if($isInserted){
			header("Location: chat.php");
		}
		else{
			header("Location: chef.php?id=".$id2);
		}
	}
}