<?php
session_start();

function clean($input)
{

    return stripslashes(strip_tags(trim($input)));
}

// to validation eny data

function validate($input, $flag , $minlenth = 6 , $maxlenth = 50)
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
            if(filter_var($input , FILTER_VALIDATE_INT)){
                $errors['string'] = 'the input shoud be string not integar';
            }
            break;
        
        case 'min':
            if(strlen($input) < $minlenth && strlen($input) != 0 ){
                $errors['minmam'] = 'the input is very short';
            }
            break;

        case 'max':
            if(strlen($input) > $maxlenth){
                $errors['maxmam'] = 'the input is very long';
            }
            break;
    }

}

    // make array of errors in session
    if (count($errors) > 0) {
        $_SESSION['errorMassage'] = $errors;
        
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

?>