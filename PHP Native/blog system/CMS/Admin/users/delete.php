<?php
require '../helper/includes.php';
require '../helper/checkAdmin.php';

    // fetch the id 
    $id = clean($_GET['id']);

    // valedate id 
    validate($id , ['required' , 'integar']);

    if(!isset($_SESSION['errorMassage'] )){

        $selectimg = "SELECT `image` FROM users WHERE id = $id";
        $op = doQuery($selectimg);
        $data = mysqli_fetch_assoc($op);

       

        $deleteQuery = "delete from users where id = $id" ;

        $deleteOpration = doQuery($deleteQuery);


    }else{
        unset($_SESSION['errorMassage']);

        $massage = ['id_Error' => 'the id of role is not correct'] ;
        
    }

    
    if($deleteOpration){
        removeFile($data['image']);
        $massage = ['success ' =>'the data deleted successfuly'] ;
    }else{
        $massage = ['field' =>'field in delete data try agian'];
    }

// set session for delete opration massage 
    $_SESSION['dbMassage'] = $massage ;
// to come back for data page 
    header('location: index.php');

?>