<?php 
session_start();

if(!isset($_SESSION['loggedin'])|| $_SESSION['loggedin'] !== true){
    header("Locaion: ../login.php");
    exit;

}else{
    echo "Failed! ";
}

?>