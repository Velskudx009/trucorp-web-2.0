<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAS</title>
</head>
<body>
<?php

function restoreDatabaseTables($connect, $filePath){
  $templine = '';
  $lines = file($filePath);
  $error = '';
  foreach ($lines as $line){
      if(substr($line, 0, 2) == '--' || $line == ''){
          continue;
      }     
      $templine .= $line;    
      if (substr(trim($line), -1, 1) == ';'){
          if(!$connect->query($templine)){
              $error .= 'Error performing query "<b>' . $templine . '</b>": ' . $connect->error . '<br /><br />';
          }
          $templine = '';
      }
  }
  return !empty($error)?$error:true;
}

$host = "172.19.0.2";
$username = "root";
$db = "users";
$password = "sql";
$filePath="users.sql";
try{
  //jika ada database maka lanjutkan
  $conn = new mysqli($host, $username, $password, $db);
  $sql = "SELECT id, nama, kantor FROM users";
  $result = $conn->query($sql);
  

  if ($result->num_rows > 0) {
    //jika sudah ada data maka tidak usah restore
    while($row = $result->fetch_assoc()) {
      echo "id: " . $row["id"]. " - Name: " . $row["nama"]. " " . $row["kantor"]. "<br>";
    }
  } else {
    //jika tidak ada data restore dlu baru tampilkan
    restoreDatabaseTables($conn, $filePath);
    $sql = "SELECT id, nama, kantor FROM users";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
      echo "id: " . $row["id"]. " - Name: " . $row["nama"]. " " . $row["kantor"]. "<br>";
    }
  }
  $conn->close();
}
catch(Exception $z){
  //jika tidak ada database buat dlu
  $conn = new mysqli($host, $username, $password);
  $sql="CREATE DATABASE users";
  if ($conn->query($sql) === TRUE) {
    $conn->close();
    //close dlu connectionya habis itu establish pake connection yang ada db
    $conn = new mysqli($host, $username, $password, $db);
    //restore database habis tu tampilin data ke user
    restoreDatabaseTables($conn, $filePath);
    $sql = "SELECT id, nama, kantor FROM users";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
      echo "id: " . $row["id"]. " - Name: " . $row["nama"]. " " . $row["kantor"]. "<br>";
    }
  } else {
    echo "Error restoring database" . $conn->error;
  }
$conn->close();
  }

?>
</body>
</html>
