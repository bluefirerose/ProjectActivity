<!-- store your database credentials and establish a database connection. -->

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kodego_db"

$conn = new mysqli($servername, $username, $password);

if($conn->connect_error){
    die("Connection error:" .$conn -> connect_error);

}
echo "Connected successfully";

?>

