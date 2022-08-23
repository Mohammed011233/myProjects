<?php 


// delete raw from db  ..... 

require '../helpers/dbConnection.php';
require 'checklogin.php';

$id = $_GET['id'];

if(filter_var($id,FILTER_VALIDATE_INT)){
// code .... 
# Select Image .... 
$sql = "select image from users where id = $id"; 
$op = mysqli_query($con,$sql); 
$data = mysqli_fetch_assoc($op); 



$sql = "delete from users where id = $id"; 

$op = mysqli_query($con,$sql); 

if($op){
    $message = 'Raw Removed';

    unlink('uploads/'.$data['image']);

}else{
    $message = 'Error Try Again';
}


}else{
    $message = 'invalid ID';
}



 # Set Message to Session
 
 $_SESSION['Message'] = $message; 

header("location: index.php"); 



?>