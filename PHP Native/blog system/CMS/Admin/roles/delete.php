<?php
require '../helper/DB_connection.php';
require '../helper/functions.php';


    // fetch the id 
    $id = clean($_GET['id']);

    // valedate id 
    validate($id , ['required' , 'integar']);

    if(!isset($_SESSION['errorMassage'] )){

        $deleteQuery = "delete from user_roles where id = $id" ;

        $deleteOpration = doQuery($deleteQuery);


    }else{
        unset($_SESSION['errorMassage']);

        $massage = ['id_Error' => 'the id of role is not correct'] ;
        
    }

    
    if($deleteOpration){
        $massage = ['success ' =>'the data deleted successfuly'] ;
    }else{
        $massage = ['field' =>'field in delete data try agian'];
    }

// set session for delete opration massage 
    $_SESSION['dbMassage'] = $massage ;
// to come back for data page 
    header('location: index.php');

?>