<?php 
if($_SESSION['user']['role_title'] != 'admin'){
    header('location: ' .url(''));
}

?>