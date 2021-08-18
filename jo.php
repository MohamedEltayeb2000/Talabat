<?php 
        require "dbcon.php";
        $name=$con->real_escape_string(trim($_POST["name"]));
        $RestName=$con->real_escape_string(trim($_POST["resturantname"]));
        $email=$con->real_escape_string(trim($_POST["email"]));
        $uname=$con->real_escape_string(trim($_POST["username"]));
        $pass=$con->real_escape_string(trim($_POST["password"]));
        $conpass=$con->real_escape_string(trim($_POST["conpass"]));
        $idnational=$con->real_escape_string(trim($_POST["id-national"]));
        $idimage=$con->real_escape_string(trim($_POST["id-image"]));
        $address=$con->real_escape_string(trim($_POST["address"]));
        $phone=$con->real_escape_string(trim($_POST["Phone"]));
        $Category=$con->real_escape_string(trim($_POST["category"]));
        
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
             echo '<script type="text/javascript"> alert("Invalide Email") </script>';
            require "join-us.html"; 
        }
        
          elseif($pass!=$conpass){
          require "join-us.html";
           echo '<script type="text/javascript"> alert("Password and Confirm Password are not identical ") </script>';
           
        }
        else{

            $sql="select * from joinu where UserName='$uname'";
            $res=mysqli_query($con,$sql) or die(mysqli_error($con));
            if(mysqli_num_rows($res)>=1){
              echo '<script type="text/javascript"> alert("Username already taken") </script>';
              require "join-us.html";                          
            }
            $sql2="select * from joinu where RestName='$RestName'";
            $res2=mysqli_query($con,$sql2) or die(mysqli_error($con));
            if(mysqli_num_rows($res2)>=1){
                 echo '<script type="text/javascript"> alert("Resturant name already taken") </script>';
              require "join-us.html";                          
            }
            else
            {
                $sqlUser = "INSERT INTO `register` (name, email, address, pass, compass) VALUES('$name', '$email', '$address', '$pass', '$conpass')";

                 if(mysqli_query($con,$sqlUser)){
                    echo "done";
                    $id = getUserId($con, $email);

                    $imageExt = strtolower(end(explode(".", $_FILES["id-image"]["name"])));
                    $image = "uploads/profiles/".$id.".".$imageExt;
                    move_uploaded_file($_FILES["id-image"]["tmp_name"], $image);

                    $sqlChef = "INSERT INTO joinu (ID ,Name, RestName, Email, UserName, Pass, Conpass, NatId, Photo, Address, PhoneNumber, Category, About, Facebook, Twitter, Instagram) VALUES('$id' ,'$name', '$RestName', '$email', '$uname','$pass','$conpass', '$idnational', '$image', '$address', '$phone', '$Category', '', '', '', '')";

                      if(mysqli_query($con,$sqlChef)){
                        echo '<script type="text/javascript"> alert("successfull") </script>';
                        header('Location: login.html');
                        exit;
                      } else{
                        echo '<script type="text/javascript"> alert("Unsuccessfull") </script>';
                        header('Location: join-us.html');
                        exit;
                      }
                 } 
                 else{
                    echo '<script type="text/javascript"> alert("Unsuccessfull") </script>';
                     header('Location: join-us.html');
                      exit;
                 }

            }

        }
 function getUserId($con, $email){
  $sql = "SELECT `id` FROM `register` WHERE `email` = \"". $email ."\"";

  $result = mysqli_query($con,$sql) or die(mysqli_error($con));
  if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    return $row["id"];
  }  
 }
 mysqli_close($con);
 ?>