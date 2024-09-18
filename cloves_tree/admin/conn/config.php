<?php 

$conn = mysqli_connect("localhost", "root", "", "cloves");

if(!$conn){
    echo "Connection failed".mysqli_connect_erroor();
}

?>