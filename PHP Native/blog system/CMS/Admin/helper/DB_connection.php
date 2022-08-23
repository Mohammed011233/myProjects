<?php
    
    $server = $_SERVER['SERVER_NAME'];
    $dbname = 'blog system';
    $dbuser = 'root';
    $dbpassword = '';

    $connect_db = mysqli_connect($server , $dbuser , $dbpassword , $dbname );

    // if ($connect_db){
    //     echo 'database connected';
    //     exit();
    // }


    if(!$connect_db){
       die( 'Connect Error \t' . mysqli_connect_error());
    }

    function doQuery($query){

        return mysqli_query($GLOBALS['connect_db'] , $query);
    }
?>