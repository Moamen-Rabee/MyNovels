<?php
$dns = 'mysql:host=localhost;dbname=mynovels';
$username = 'root';
$password = '';
$option = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                );
try{
    $con = new PDO($dns,$username,$password,$option);
    $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
}
catch(Exception $e){
    echo 'ERROR : '.$e->getMassage();
}



?>