<?php

$arr = ['hh','mm'=>['yy' , 'xx']];

$check = 'xx';

if(in_array($check , $arr['mm'])){
    echo 'checked' ;
}

?>