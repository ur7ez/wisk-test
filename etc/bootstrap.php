<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

spl_autoload_register(function ($name) {
    $name = str_replace(
        '\\',
        DS,
        $name
    );
    $absPath = ROOT . DS . 'src' . DS . $name . '.php';
    if (file_exists($absPath)) include_once $absPath;
});

include_once ROOT . DS . 'etc' . DS . 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', App\Core\Config::get('debug') ? 1 : 0);
