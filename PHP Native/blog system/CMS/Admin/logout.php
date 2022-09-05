<?php
require './helper/functions.php';

session_destroy();

header('location: ' . url('login.php'));

?>