<?php

function clean ($input){
    return stripcslashes(strip_tags(trim($input)));
}

?>