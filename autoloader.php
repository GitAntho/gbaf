<?php

function autoload($classname)
{
    if (file_exists($file = __DIR__ . '/Classes/' . $classname . '.php'))
    {
        require $file;
    }
}

spl_autoload_register('autoload');


