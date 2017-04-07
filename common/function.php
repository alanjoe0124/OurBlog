<?php
function charNum($str){
    return ( strlen($str)+ mb_strlen($str, 'utf-8') )/2;
}
