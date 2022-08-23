
        <?php
        require '../included_files/connection_db.php';
        require '../included_files/functions.php';

        $todo_id = $_SESSION['todo_id'];

        $select_quary = "SELECT * FROM todo WHERE todo_id = $todo_id ";

        $run_select = mysqli_query($connect , $select_quary);

        #fetch data

        $fetch_data = mysqli_fetch_assoc($run_select);


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            $title = clean($_POST['editTitle']);
            $content_task = clean($_POST['editContent']);
            $start_date = clean($_POST['editStrTm']);
            $end_date = clean($_POST['editEndTm']);
            $theUserID = $_SESSION['user_id'];

            // echo date_default_timezone_get() ;
            // exit();


            $errors = [];
            if (empty($title)) {
                $errors['title'] = "Enter the title of task";
            }
            if (empty($content_task)) {
                $errors['content'] = "Enter the content of task";
            }

            if (empty($start_date)) {
                $errors['str_dt'] = "Enter the start date of task";
            }

            if (empty($end_date)) {
                $errors['end_dt'] = "Enter the end of task";
            }

            if (count($errors) > 0) {
                foreach ($errors as $index => $message) {
                    echo $message . '<br>';
                }

                // $_SESSION['edit_errors'] = $errors;

            } else {

                // $insert_query = "INSERT INTO todo (`title` , `content` , `start_date` , `end_date` , `theUser_id`	) VALUES ( '$title' , '$content_task' , '$start_date' , '$end_date' , $theUserID)";

                // $insert = mysqli_query($connect, $insert_query);



                $update_query = "UPDATE todo SET title='$title' , content='$content_task' , `start_date`='$start_date' , `end_date`='$end_date' WHERE todo_id=$todo_id";

                $run_update = mysqli_query($connect, $update_query);
                if (!$run_update) {
                    echo "error update";
                } else {
                    unset($_SESSION['todo_id']);
                    header('location: http://localhost/NTI_course/todo/_TODO/ToDo_page.php');
                    exit();
                }
            }
        }



        ?>