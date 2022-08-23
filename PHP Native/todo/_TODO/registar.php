<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="style/stylesheet.css">

</head>

<body>
    <div class="signup-form">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
            <h2>Register</h2>
            <p class="hint-text">Create your account. It's free and only takes a minute.</p>
            <div class="form-group">
                <div class="row">
                    <div class="col"><input type="text" class="form-control" name="first_name" placeholder="First Name"  ></div>
                    <div class="col"><input type="text" class="form-control" name="last_name" placeholder="Last Name"  ></div>
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="email" placeholder="Email"  >
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password"  >
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password"  >
            </div>

            <div class="form-group">
                <label class="form-label" for="imageupload">Enter your image , it must to be [jpg , jpeh or png] </label>
                <input type="file" class="form-control" name="userimg" id="imageupload">
            </div>

            <div class="form-group">
                <label class="form-check-label"><input type="checkbox"  > I accept the <a href="#">Terms of Use</a> &amp; <a href="#">Privacy Policy</a></label>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success btn-lg btn-block">Register Now</button>
            </div>
        </form>
        <div class="text-center">Already have an account? <a href="index.php">Sign in</a></div>
    </div>
</body>

</html>



<?php
require("included_files/functions.php");
require('included_files/connection_db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    #create varibles for input registred value
    $firstname = clean($_POST['first_name']);  //first_name  last_name email  password  confirm_password userimg
    $lastname = clean($_POST['last_name']);
    $fullname = $firstname .' '. $lastname;
    $email = $_POST['email'];
    $password = clean($_POST['password']);
    $confPass =  clean($_POST['confirm_password']);
    #varibles for user image 
    $img_name = $_FILES['userimg']['name'];
    $img_tmppath = $_FILES['userimg']['tmp_name'];
    $img_type = $_FILES['userimg']['type'];
    $img_size = $_FILES['userimg']['size'];
     
    #get extention of image
    $div_type = explode('/' , $img_type);
    $exten_imag = strtolower(end($div_type));

    $final_name=uniqid().'.'.$exten_imag;


    $vaild_extentions = ['jpg' , 'jpeg' , 'png'];
    $errors = [];
    #validation of name 
    if (empty($firstname) || empty($lastname)) {
        $errors['name'] = 'Enter your name please';
    } elseif (is_numeric($firstname) || is_numeric($lastname)) {
        $errors['name'] = 'your name must to be string ';
    } elseif (strlen($fullname) >= 25) {
        $errors['name'] = 'your name is very long ';
    }

    #validation of email
    if (empty($email)) {
        $errors['email'] = 'Enter your email please';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'your email is not vaild';
    }

    #validtion of password
    if (empty($password)) {
        $errors['password'] = 'Enter your password please';
    } elseif (strlen($password) <= 6) {
        $errors['password'] = 'your password is very short';
    } elseif ($confPass !== $password) {
        $errors['password'] = 'your password is not conferm';
    }

    #validtion of image

    if (empty($img_name)) {
        $errors['image'] = 'Enter your image please';
    } elseif (!in_array($exten_imag , $vaild_extentions)) {
        $errors['image'] = 'your image  is not vaild , it maust to be  [ jpg , jpeg or png ]';
    } 
    elseif ($img_size >= (5*8*1024*1024) ) {
        $errors['image'] = 'your image is very larg it must to be less than 5Mb';
    }


    #print errors
    if (count($errors) > 0) {
        foreach ($errors as $index => $message) {

            echo $index . ' error :=>' . $message . '<br>';
        }
    }else{
        #upload image in a local folder
        $uploaded_path = 'uploaded/'.$final_name;

        move_uploaded_file($img_tmppath , $uploaded_path);

        #insert data to the database
        $password = md5($password);
        $quary = "INSERT INTO users (`name` , `email` , `password` , `image` ) VALUES ('$fullname' , '$email' , '$password' , '$uploaded_path')";

        $run_quary = mysqli_query($connect , $quary);

        if($run_quary){
            echo '<h3>your registration done</h3>';
        }else{
            echo 'thare are errors';
        }
    }
}



?>