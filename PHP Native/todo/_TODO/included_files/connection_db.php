<?php
session_start();

$server = 'localhost';
$DB_name = 'todo';
$DB_user = 'root';
$password = '';

$connect = mysqli_connect($server  , $DB_user , $password, $DB_name);



if($connect == false){
    echo "thare are errors in connection database" . mysqli_connect_error() . mysqli_connect_errno();
}


?>