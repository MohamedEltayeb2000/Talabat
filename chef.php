<?php
require "UserData.php";
$user = new User();

if(isset($_GET["edit"]) && $_SESSION["type"] == "chef"){
	include "chefs edit.html";
}
else if(isset($_GET["id"])){
	$user->getPageData($_GET["id"]);
	if(isset($_SESSION["userInfo"])){
		if($_GET["id"] == $_SESSION["userInfo"]["data"]["id"]){
			$_SESSION["isOwner"] = 1;
		}
		else{
			$_SESSION["isOwner"] = 0;
		}
	}
	else{
		$_SESSION["isOwner"] = -1;
	}
	include "chefs page.html";
}
else{
	header("Location: index.php");
}

