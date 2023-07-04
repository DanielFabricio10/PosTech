<?php

define('ROOT_SISTEM',$_SERVER['DOCUMENT_ROOT'].'/app');

spl_autoload_register(function($class) {

    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $fileClass = ROOT_SISTEM.'/'.$class.'.php';

    if(file_exists($fileClass)) {
        require_once($fileClass);
    }
    
});