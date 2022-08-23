<?php
require('../included_files/connection_db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete_quary = "DELETE FROM todo WHERE todo_id = $id ";

    $run_quary = mysqli_query($connect, $delete_quary);
    $message;

    if($run_quary){
        $message = "deleted task";
    }else{
        $message = "the task not delete";
    }

    $_SESSION['message'] = $message;

    header('location: ../ToDo_page.php');
}
