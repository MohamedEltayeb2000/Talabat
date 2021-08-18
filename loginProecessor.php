<?php
require "UserData.php";
//require "dbcon.php";

$user = new User();



if(isset($_POST["email"]) && $_POST["email"] != ""){
	if(isset($_POST["password"]) && $_POST["password"] != ""){
		$user->login($_POST["email"], $_POST["password"]);
	}
	else{
		header("Location: login.html?Error=PasswordNotFound");
	}
}
else{
	header("Location: login.html?Error=EmailNotFound");
}
