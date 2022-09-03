<?php
session_start();

function clean($input)
{

    return stripslashes(strip_tags(trim($input)));
}

// to validation eny data

function validate($input, $flag , $minlenth = 6 , $maxlenth = 50   , $fileSize = 5*1024*1024 , $pass = '12' , $realExtention = [' jj'])
{
    
    $errors = [];
foreach ($flag as $index => $valid){

    switch ($valid) {
        case 'required':
            
            if (empty($input)) {
               
                $errors['required'] = 'this input require';
            }
            break;
        case 'string':
            $pattern = "/^[a-zA-Z-' ]*$/";
            if(!preg_match($pattern , $input)){
                $errors['string'] = 'the input shoud conten oly litters ';
            }
            break;
        case 'integar':
            if(!filter_var($input , FILTER_VALIDATE_INT)){
                $errors['integar'] = 'the input shoud be integar';
            }
            break;
        case 'email':
            if(!filter_var($input , FILTER_VALIDATE_EMAIL)){
                $errors['email'] = 'This Email is not valid';
            }
            break;
        case 'conf_pass':
            if($input != $pass){
                $errors = ['field_confirm' => 'the password is not comfirm'];
            }
            break;
        case 'phone':
            $pattern = '/^01[0-2,5][0-9]{8}$/';
            if(!preg_match($pattern , $input)){
                $errors = ['invaild_phone' => 'this phone is not valid'];
            }
            break;
        
        case 'min':
            if(strlen($input) < $minlenth && strlen($input) != 0 ){
                $errors['minmam'] = 'the input is very short ';
            }
            break;

        case 'max':
            if(strlen($input) > $maxlenth){
                $errors['maxmam'] = 'the input is very long ';
            }
            break;

        case 'fileExtention':
            
            if(!in_array($input , $realExtention)){
                $errors = ['invaild_Extention' => 'This extention of image is not valid '];
            }
            break;

        case 'fileSize':
        
            if($input > $fileSize){
                $sizeM = ($fileSize / 1024) / 1024 ;
                $errors = ['size_Error' => 'Very larg . must to be less than  ' . $sizeM .'MB' ];
            }
            break;

        case 'id':
            if(!filter_var($input , FILTER_VALIDATE_INT)){
                $errors = ['ID_Error' => 'The ID id not valid'];
            }
            break;

        case 'date':
            $date = explode('-', $input);
            if(!checkdate($date[1] , $date[2] , $date[0])){
                $errors = ['publish date' => 'the date is not valid'];
            }
            break;

        case 'next_date':

            if(time() > strtotime($input)){
                $errors = ['publish date' => 'This time has passed '];
            }
            break;
    }

     // make array of errors in session
    if (count($errors) > 0) {
        $_SESSION['errorMassage'] = $errors;
        
    }

}

   
}


// display enymassage such as error massage  
function displayMassage($sessionName , $title)
{
   
        if (isset($_SESSION[$sessionName])) {
            $inSession = $_SESSION[$sessionName];

            foreach ($inSession as $index => $massage) {
                echo $index . '  ** ' . $massage . '<br>';
            }
        } else {
            echo  $title ;
        }

        unset($_SESSION[$sessionName]);
    
}


//remove files from uploads folder 
function removeFile($file){
    unlink('uploads/'.$file);
}

function url($page){
    // http://localhost/my%20projects/PHP%20Native/blog%20system/CMS/Admin/index.php
    
    return 'http://'. $_SERVER['HTTP_HOST'] .'/my%20projects/PHP%20Native/blog%20system/CMS/Admin/'. $page ; 
}
