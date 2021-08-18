<?php
//function to check for a file
function checkForFile($msg){
    $file = getMessageFileName($msg);
	if($file == "nothing" || $file == ""){
        $data = json_decode($msg, true);
        $id1 = $data["messages"][0]["user_from"]["id"];
        $id2 = $data["messages"][0]["user_to"]["id"];
        $file_name = "../messages/chat($id1,$id2).json";
        file_put_contents($file_name, $msg);
        insertFileIntoDataBase($id1, $id2, $file_name);
    }
    else{
        $data = json_decode($msg, true);
        $file_name = $file;
        $data_file = file_get_contents($file_name);
        $new_data = json_decode($data_file, true);
        array_push($new_data["messages"], $data["messages"][0]);
        $new_data = json_encode($new_data);
        file_put_contents($file_name, $new_data);
    }
}

function dataBaseConnection(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "joinus";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
// function to insert file into database
function insertFileIntoDataBase($id1, $id2, $file_name){
    $conn = dataBaseConnection();

    $sql = "UPDATE `open_chats` SET `FileName`=\"$file_name\" WHERE First_User_id=\"$id1\" AND Second_User_id=\"$id2\" OR First_User_id=\"$id2\" AND Second_User_id=\"$id1\"";
    $result = $conn->query($sql);
}

//function to get filename from database
function getMessageFileName($msg){
    $conn = dataBaseConnection();

    $data = json_decode($msg, true);
    $id1 = $data["messages"][0]["user_from"]["id"];
    $id2 = $data["messages"][0]["user_to"]["id"];

    $sql = "SELECT * FROM `open_chats` WHERE First_User_id=\"$id1\" AND Second_User_id=\"$id2\" AND FileName IS NOT NULL OR First_User_id=\"$id2\" AND Second_User_id=\"$id1\" AND FileName != \"\" AND FileName IS NOT NULL";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            return $row["FileName"];
        }
    }
    else{
        return "nothing";
    }
}