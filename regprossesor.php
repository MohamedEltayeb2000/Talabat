<?php
require "dbcon.php";
$fullname= $_POST["fullname"];
$email= $_POST["email"];
$address= $_POST["address"];
$password= $_POST["password"];
$compass= $_POST["compass"];
if($fullname=="" || $email=="" || $address==""|| $password==""|| $compass=="" )
{
	echo '<script> alert("Please full all fields") </script>';
    require "login.html";

}
else if($password!=$compass)
{
    echo '<script> alert("password doesnot match!") </script>';
    require "login.html";
}
else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    echo '<script> alert("Invaild email") </script>';
    require "login.html";
}
else if(!preg_match(" /^[A-Z][a-z]+ [A-Z][a-z]+$/", $fullname)){

    echo'<script> alert("Full name should be letters ONLY !!. Full name should be Firstname Lastname  first letter a capital letters like Ahmed Samir ") </script>';
    require "login.html";
}
else if(!preg_match(" /^[0-9]+,[A-Z][a-z]+,[A-Z][a-z]+,[A-Z][a-z]+$/", $address)){

    echo'<script> alert("Adress should be no,street,area,city  street should contain a number. first letter is capital   like 25,louxor,sporting,alexandria ") </script>';
    require "login.html";
}
else{
    
$sql="select * from register where name= '$fullname'";
$res=mysqli_query($con,$sql) or die(mysqli_error($con));
if(mysqli_num_rows($res)>=1)
{
    echo '<script> alert("username already taken try another username") </script>';
    require "login.html";
}
else
{
    mysqli_query($con,"insert into register (name, email, address, pass,compass) values ('$fullname', '$email', '$address' , '$password', '$compass')");
    echo '<script> alert("registration success, Please login!") </script>';
    require "login.html";

}
}
?>
