<?php

function autoload($className)
{
    $class = __DIR__. "/".$className.".php";
    if(file_exists($class)){
        include $class;
    }
}

spl_autoload_register('autoload');