<?php
if (!isset($_SESSION['user'])) {

   header('location: ' . url('login.php'));
} 
