<?php

require '../helpers/dbConnection.php';
require '../helpers/functions.php';
require 'checklogin.php';
# Fetch Id Data .... 

$id = $_GET['id'];

$sql = "select * from users where id = $id";
$op  = mysqli_query($con, $sql);
$data = mysqli_fetch_assoc($op);


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $name     = Clean($_POST['name']);
    $email    = Clean($_POST['email']);


    # Validate ...... 

    $errors = [];

    # validate name .... 
    if (empty($name)) {
        $errors['name'] = "Field Required";
    }


    # validate email 
    if (empty($email)) {
        $errors['email'] = "Field Required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['Email'] = "Invalid Email";
    }


      # Validate Image 
      if (!empty($_FILES['image']['name'])) {
      
        $typesInfo  =  explode('/', $_FILES['image']['type']);   // convert string to array ... 
        $extension  =  strtolower(end($typesInfo));      // get last element in array .... 

        $allowedExtension = ['png', 'jpeg', 'jpg'];   // allowed Extension    // PNG JPG 

        if (!in_array($extension, $allowedExtension)) {

            $errors['Image'] = "Invalid Extension";
        }
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


        if (!empty($_FILES['image']['name'])) {
        # Create Final Name ... 
        $FinalName = uniqid() . '.' . $extension;

        $disPath = 'uploads/' . $FinalName;

        $temPath = $_FILES['image']['tmp_name'];

        if (move_uploaded_file($temPath, $disPath)) {
           
            unlink('uploads/'.$data['image']);

        }

    }else{
        $FinalName = $data['image'];
    }


        $sql = "update users set name='$name' , email = '$email' , image = '$FinalName' where  id = $id";

        $op =  mysqli_query($con, $sql);

        if ($op) {
            $message =  'Raw updated';
            # Set Message to Session

            $_SESSION['Message'] = $message;

            header("location: index.php");
        } else {
            echo 'Error Try Again ' . mysqli_error($con);
        }


        # Close Connection .... 
        mysqli_close($con);
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Update Account</h2>

        <form action="<?php echo   htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $data['id']; ?>" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="exampleInputName">Name</label>
                <input type="text" class="form-control" required id="exampleInputName" aria-describedby="" name="name" placeholder="Enter Name" value="<?php echo $data['name'] ?>">
            </div>


            <div class="form-group">
                <label for="exampleInputEmail">Email address</label>
                <input type="email" class="form-control" required id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="Enter email" value="<?php echo $data['email'] ?>">
            </div>

            <!-- <div class="form-group">
            <label for="exampleInputPassword">New Password</label>
            <input type="password" class="form-control" required id="exampleInputPassword1" name="password"
                   placeholder="Password">
        </div> -->


           <div class="form-group">
                <label for="exampleInputName">Image</label>
                <input type="file" name="image">
            </div>

            <br>
            <img src="uploads/<?php echo $data['image'];?>" alt="userImage"  height="50px" width="50px" >

            <br>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


</body>

</html>