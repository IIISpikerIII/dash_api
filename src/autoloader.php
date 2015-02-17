<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 17.02.15
 * Time: 22:09
 */

function autoload_boards($class_name)
{
    $file = __DIR__.'/'.$class_name.'.php';

    if (file_exists($file))
    {
        require_once($file);
    } else
        print_r($file);
}

spl_autoload_register('autoload_boards');