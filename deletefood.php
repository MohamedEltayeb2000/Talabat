<?php
require "dbcon.php";

if(!isset($_GET["foodid"])){
	echo "error";
	exit();
}
$id = $_GET["foodid"];

$sql="DELETE FROM `food` WHERE foodid='$id'";
$result=mysqli_query($con,$sql);
if($result){
    header("Location: chef.php?edit");
}
else{
    header("Location: chef.php?edit&error");
}
