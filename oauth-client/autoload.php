<?php

/**
 * @param $className
 * Fonction qui sert à charger les fichiers des classes grâce à leur nom
 */
function autoload($className)
{
    $class = __DIR__. "/".$className.".php";
    if(file_exists($class)){
        include $class;
    }
}

spl_autoload_register('autoload');