<?php

require '../helpers/dbConnection.php';
require '../helpers/functions.php';


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $password = Clean($_POST['password']);
    $email    = Clean($_POST['email']);


    # Validate ...... 

    $errors = [];

    # validate email 
    if (empty($email)) {
        $errors['email'] = "Field Required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['Email'] = "Invalid Email";
    }


    # validate password 
    if (empty($password)) {
        $errors['password'] = "Field Required";
    } elseif (strlen($password) < 6) {
        $errors['Password'] = "Length Must be >= 6 chars";
    }


    # Check ...... 
    if (count($errors) > 0) {
        // print errors .... 

        foreach ($errors as $key => $value) {
            # code...

            echo '* ' . $key . ' : ' . $value . '<br>';
        }
    } else {

         # DB OP ......... 
      $password = md5($password);
      $sql = "select * from users where email = '$email'  and  password = '$password'";
      $op = mysqli_query($con,$sql); 

     if(mysqli_num_rows($op) == 1){
         // code .... 

         $userData = mysqli_fetch_assoc($op);

         $_SESSION['user'] = $userData;

         header("location: index.php");
         

     }else{
         echo 'Error in Your Cred Try Again';
     }


     # Close Connection .... 
      mysqli_close($con); 


    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

<div class="container">
    <h2>Login</h2>

    <form action="<?php echo   htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">


        <div class="form-group">
            <label for="exampleInputEmail">Email address</label>
            <input type="email" class="form-control" required id="exampleInputEmail1" aria-describedby="emailHelp"
                   name="email" placeholder="Enter email">
        </div>

        <div class="form-group">
            <label for="exampleInputPassword"> Password</label>
            <input type="password" class="form-control" required id="exampleInputPassword1" name="password"
                   placeholder="Password">
        </div>


        <button type="submit" class="btn btn-primary">GO!!!</button>
    </form>
</div>


</body>

</html>