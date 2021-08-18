<?php
require "dbcon.php";
if(!isset($_POST["dishname"]) || !isset($_POST["dishprice"]) || !isset($_POST["dishdetails"]) || !isset($_POST["dishimage"]) || !isset($_GET["foodid"])){
    echo '<script> alert("Please Enter all fields") </script>';
    //require "chefs edit.html";
    header("Location: chef.php?edit");
}
$dishname= $_POST["dishname"];
$dishprice= (int)$_POST["dishprice"];
$dishdetails= $_POST["dishdetails"];
$foodid=$_GET["foodid"];


if(empty($_FILES["dishimage"]["name"])){
	$sql="UPDATE `food` SET `dishname`='$dishname', `price`=$dishprice, `description`='$dishdetails' WHERE `foodid`='$foodid'";
}
else{
	$imageExt = strtolower(end(explode(".", $_FILES["dishimage"]["name"])));
	$dishimage = "uploads/orders/".$foodid.".".$imageExt;
	move_uploaded_file($_FILES["dishimage"]["tmp_name"], $dishimage);
	$sql="UPDATE `food` SET `dishname`='$dishname', `price`=$dishprice, `description`='$dishdetails',`image`='$dishimage' WHERE `foodid`='$foodid'";
}

$result=mysqli_query($con,$sql);
if($result){
    header("Location: chef.php?edit");
}
else{
    header("Location: chef.php?edit&error");
}
