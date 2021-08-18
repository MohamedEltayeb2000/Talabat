<?php

class User{
	private $userInfo;
	private $userSql;
	private $chefSql;
	private $conn;

	//constructor
	public function __construct(){
		session_start();
		$userData = [];
		$this->conn = $this->getConnection();
	}

	//function to establish the connection
	private function getConnection(){
		$con = mysqli_connect("localhost", "root","","joinus");
		if($con->connect_error){
			die("Connection failed: " . $con->connect_error);
		}
		return $con;
	}

	//chef data
	private function chefData($email, $password){
		$data = [];
		$result = mysqli_query($this->conn, $this->chefSql);
		if ($result && mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)) {
				$data += ["id" => $row["ID"]];
				$data += ["name" => $row["Name"]];
				$data += ["restname" => $row["RestName"]];
				$data += ["email" => $row["Email"]];
				$data += ["NatID" => $row["NatId"]];
				$data += ["address" => $row["Address"]];
				$data += ["phone" => $row["PhoneNumber"]];
				$data += ["categ" => $row["Category"]];
				$data += ["photo" => $row["Photo"]];
				$data += ["about" => $row["About"]];
				$data += ["rating" => $row["Rating"]];
				$data += ["reviews" => $row["Reviews"]];
				$data += ["facebook" => $row["Facebook"]];
				$data += ["instagram" => $row["Instagram"]];
				$data += ["twitter" => $row["Twitter"]];
				$data += ["offer" => $row["Offer"]];
  			}
  			$this->userInfo = ["data" => $data];
  			$_SESSION["userInfo"] = $this->userInfo;
  			$_SESSION["type"] = "chef";
  			return 1;
		}
		else{
			return 0;
		}
	}



	//user data
	private function userData($email, $password){
		$data = [];
		$result = mysqli_query($this->conn, $this->userSql);
		if ($result && mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)) {
				$data += ["id" => $row["id"]];
				$data += ["name" => $row["name"]];
				$data += ["email" => $row["email"]];
				$data += ["address" => $row["address"]];
  			}
  			$this->userInfo = ["data" => $data];
  			$_SESSION["userInfo"] = $this->userInfo;
  			$_SESSION["type"] = "user";
  			return 1;
		}
		else{
			return 0;
		}
	}

	//get chef data from id
	private function getIdData($id){
		$sql = "SELECT * FROM `joinu` WHERE `ID`=\"$id\"";
		$data = [];
		$result = mysqli_query($this->conn, $sql);
		if ($result && mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)) {
				$data += ["id" => $row["ID"]];
				$data += ["name" => $row["Name"]];
				$data += ["restname" => $row["RestName"]];
				$data += ["username" => $row["UserName"]];
				$data += ["email" => $row["Email"]];
				$data += ["NatID" => $row["NatId"]];
				$data += ["address" => $row["Address"]];
				$data += ["phone" => $row["PhoneNumber"]];
				$data += ["categ" => $row["Category"]];
				$data += ["photo" => $row["Photo"]];
				$data += ["about" => $row["About"]];
				$data += ["rating" => $row["Rating"]];
				$data += ["reviews" => $row["Reviews"]];
				$data += ["facebook" => $row["Facebook"]];
				$data += ["instagram" => $row["Instagram"]];
				$data += ["twitter" => $row["Twitter"]];
				$data += ["offer" => $row["Offer"]];
  			}
  			$_SESSION["chef"] = $data;
  			return 1;
		}
		else{
			return 0;
		}
	}

	private function chatRegister($id1, $id2){
		$sql = "INSERT INTO `open_chats` (`First_User_id`, `Second_User_id`, `FileName`) VALUES ('$id1', '$id2', NULL)";
		$result = mysqli_query($this->conn, $sql);
		if($result){
			return 1;
		}
		else{
			return 0;
		}
	}


	private function FileNameGetter($user, $chef){
		$sql = "SELECT * FROM `open_chats` WHERE `First_User_id`=\"$user\" AND `Second_User_id`=\"$chef\" AND `FileName` != \"\" AND `FileName` IS NOT NULL OR `First_User_id`=\"$chef\" AND `Second_User_id`=\"$user\" AND `FileName` != \"\" AND `FileName` IS NOT NULL";
		$result = mysqli_query($this->conn, $sql);
		if ($result && mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				return $row["FileName"];
			}
		}
		else{
			return "error";
		}
	}


	private function updateChefRate($chef, $rate){
		$sql = "UPDATE `joinu` SET Rating = IF(Reviews=0, $rate, (Rating + $rate)/2) ,Reviews=Reviews + 1 WHERE `ID`='$chef'";
		$result = mysqli_query($this->conn, $sql);
		if($result){
			return 1;
		}
		else{
			return 0;
		}
	}

	private function getFoodById($id){
		$data = [];
		$sql = "SELECT * FROM `food` WHERE `foodid`=\"$id\"";
		$result = mysqli_query($this->conn, $sql);
		if ($result && mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				$data +=["name" => $row["dishname"]];
				$data +=["image" => $row["image"]];
				$data +=["price" => $row["price"]];
				$data +=["details" => $row["description"]];
			}
			return $data;
		}
		else{
			return "error";
		}
	}

	private function saveOrders($user, $order, $chef){
		$sql = "INSERT INTO `orders` (`user_id`, `order_id`, `chef_id`) VALUES ('$user', '$order', '$chef')";
		$result = mysqli_query($this->conn, $sql);
		if($result){
			return 1;
		}
		else{
			return 0;
		}
	}


	private function insertFileIntoDataBase($id1, $id2, $file_name){
		$sql = "DELETE FROM `open_chats` WHERE `First_User_id`='$id1' AND `Second_User_id`='$id2' OR `First_User_id`='$id2' AND `Second_User_id`='$id1'";
		mysqli_query($this->conn, $sql);
    	$sql = "INSERT INTO `open_chats` (`First_User_id`, `Second_User_id`, `FileName`) VALUES('$id1', '$id2', '$file_name')";
    	$result = mysqli_query($this->conn, $sql);
    	if($result){
    		return 1;
    	}
    	else{
    		return 0;
    	}
	}

	//################################################################## End of private functions
	public function insertFile($user, $chef, $file_name){
		return $this->insertFileIntoDataBase($user, $chef, $file_name);
	}

	public function placeOrders($user, $order, $chef){
		return $this->saveOrders($user, $order, $chef);
	}

	public function getFoodDetails($id){
		return $this->getFoodById($id);
	}

	public function setRate($chef, $rate){
		return $this->updateChefRate($chef, $rate);
	}

	public function getFileName($user, $chef){
		return $this->FileNameGetter($user, $chef);
	}

	public function registerChatUsers($id1, $id2){
		return $this->chatRegister($id1, $id2);
	}

	//public function to user getIdData($id) function
	public function getPageData($id){
		$isFound = $this->getIdData($id);
		if(!$isFound){
			header("Location: index.php");
		}
	}

	public function updateData($id){
		$isFound = $this->getIdData($id);
		if(!$isFound){
			header("Location: chef.php?edit&error");
		}
		$_SESSION["userInfo"]["data"] = $_SESSION["chef"];
	}

	//public function to log in the user (user or chef).
	public function login($email, $password){
		$this->chefSql = "SELECT * FROM `joinu` WHERE `Email`=\"$email\" AND `Pass`=\"$password\"";
		$this->userSql = "SELECT * FROM `register` WHERE `email`=\"$email\" AND `pass`=\"$password\"";

		$isChef = $this->chefData($email, $password);

		if($isChef){
			header("Location: chef.php?id=".$this->userInfo["data"]["id"]);
			return;
		}

		$isUser = $this->userData($email, $password);

		if(!$isChef && $isUser){
			header("Location: index.php");
		}
		else{
			header("Location: login.html?error=UserNotFound");
		}
	}

}