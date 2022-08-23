<?php



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="style/login_style.css">
    <title>Login page</title>
</head>

<body>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

        <div class="container">
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="login_pass" required>

            <button type="submit" name="login">Login</button>
            <label>
                <input type="checkbox" checked="checked" name="remember"> Remember me
            </label>
        </div>

        <div class="container" style="background-color:#f1f1f1">
            <a href="registar.php" class="btn btn-success">Registration</a>
            <span class="psw">Forgot <a href="#">password?</a></span>
        </div>
    </form>

</body>

</html>

<?php

require("included_files/functions.php");
require('included_files/connection_db.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {

    $username = $_POST['username'];
    $login_pass = $_POST['login_pass'];

    $login_pass = md5($login_pass);
    if (isset($_POST['login'])) {

        $errors = [];
        #vaildtion of username & password
        if (empty($username)) {
            $errors['username'] =  'enter your username';
        }
        if (empty($login_pass)) {
            $errors['login_pass'] =  'enter your password';
        }
        if (count($errors) > 0) {
            foreach ($errors as $key => $message) {
                echo $message . '<br>';
            }
        } else {
            $query = 'SELECT * FROM users';
            $data = mysqli_query($connect, $query);


            $flage = true;
            while ($fetch_raw = mysqli_fetch_assoc($data)) {
                if ($fetch_raw['email'] == $username && $fetch_raw['password'] == $login_pass) {
                    $flage = false;
                    $_SESSION['user_id'] = $fetch_raw['users_id'];
                    $_SESSION['name'] = $fetch_raw['name'];
                    $_SESSION['image_path'] = $fetch_raw['image'];
                    header('location: ToDo_page.php');
                }
            }
            
            if($flage){
                echo "<h4>username and password are not correct</h4>";
            }
        }
    }
}



?>