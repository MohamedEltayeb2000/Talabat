<?php
	require "dbcon.php";
	if(!isset($_POST["dishname"]) || !isset($_POST["dishprice"]) || !isset($_POST["dishdetails"]) || !isset($_POST["dishimage"])){
		echo '<script> alert("Please Enter all fields") </script>';
	    //require "chefs edit.html";
	    header("Location: chef.php?edit");
	}
	
	$dishname= $_POST["dishname"];
	$dishprice= $_POST["dishprice"];
	$dishdetails= $_POST["dishdetails"];

	session_start();
	$shefid=$_SESSION["userInfo"]["data"]["id"];

	if($dishname=="" ||$dishprice==""|| $dishdetails=="")
	{
		echo '<script> alert("Please full all fields") </script>';
	    //require "chefs edit.html";
	    header("Location: chef.php?edit");
	}
	else if(is_numeric($dishprice)==0)
	{
		echo '<script> alert("Please enter correct price") </script>';
	    //require "chefs edit.html";
	    header("Location: chef.php?edit");
	}
	else
	{
		/*$sql="select * from food where dishname= '$dishname'";
	    $res=mysqli_query($con,$sql) or die(mysqli_error($con));

		if(mysqli_num_rows($res)>=1){
		echo '<script> alert("Dish name already taken try another one!") </script>';
		//require "chefs edit.html";
		header("Location: chef.php?edit");*/
		
		/*}
		else{*/
		    $temp = session_id();
	        mysqli_query($con,"insert into food (dishname, image, price, description,chefid) values ('$dishname', '$temp', '$dishprice' , '$dishdetails', '$shefid')");

	        $result = mysqli_query($con, "SELECT * FROM `food` WHERE `image`='$temp'");
	        if (mysqli_num_rows($result) > 0){
	        	while($row = mysqli_fetch_assoc($result)){
	        		$id = $row["foodid"];
	        	}
	        	$imageExt = strtolower(end(explode(".", $_FILES["dishimage"]["name"])));
		   		$dishimage = "uploads/orders/".$id.".".$imageExt;
		    	move_uploaded_file($_FILES["dishimage"]["tmp_name"], $dishimage);

	        	mysqli_query($con, "UPDATE `food` SET `image`='$dishimage' WHERE `foodid`='$id'");
	        	echo '<script> alert("sucsses dish add!") </script>';
            	//require "chefs edit.html";
            	header("Location: chef.php?edit");	
	        }
	        else{
	        	mysqli_query($con, "UPDATE `food` SET `image`='' WHERE `foodid`='$id'");
	        	echo '<script> alert("failed dish image add!") </script>';
            	//require "chefs edit.html";
            	header("Location: chef.php?edit");	
	        }
		/*}*/
			
}
?>