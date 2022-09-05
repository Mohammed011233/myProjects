<?php
if ($_SESSION['user']['role_title'] != 'admin' && $_SESSION['user']['role_title'] != 'writer') {
    header('location: ' . url(''));
}

?>