<?php
	include "UserData.php";
	$user = new User();

	$connection = mysqli_connect("localhost", "root", "", "joinus");

	$proimage= $_POST["proimage"];
	$facebook= $_POST["facebook"];
	$instagram= $_POST["instagram"];
	$twitter= $_POST["twitter"];
	$name= $_POST["name"];
	$email= $_POST["email"];
	$phonenumber= $_POST["phonenumber"];
	$address= $_POST["address"];
	$offer= $_POST["offer"];
	$about= $_POST["about"];

	session_start();
	$shefid=$_SESSION["userInfo"]["data"]["id"]; 

	$imageExt = strtolower(end(explode(".", $_FILES["proimage"]["name"])));
    $image = "uploads/profiles/".$shefid.".".$imageExt;
    move_uploaded_file($_FILES["proimage"]["tmp_name"], $image);

	$offer = (int) $offer;

	$query="UPDATE `joinu` SET `Name`='$name',`Email`='$email',`Address`='$address',`PhoneNumber`='$phonenumber',`Photo`='$image',`About`='$about',`Facebook`='$facebook',`Instagram`='$instagram',`Twitter`='$twitter',`Offer`=$offer WHERE ID='$shefid'";
$res=mysqli_query($connection,$query);
if($res)
{
	$user->updateData($shefid);
	echo '<script type="text/javascript"> alert("changes are sccessfuly saved") </script>';
	header("Location: chef.php?edit");

}else{
	echo '<script type="text/javascript"> alert("changes not saved") </script>';
	header("Location: chef.php?edit&error");
}
 ?>