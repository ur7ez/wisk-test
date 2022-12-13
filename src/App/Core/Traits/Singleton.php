<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

namespace App\Core\Traits;

trait Singleton
{
    protected static $instance = null;

    final public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    abstract protected function __construct();

    final private function __clone()
    {
    }

    final private function __sleep()
    {
    }

    final private function __wakeup()
    {
    }
}
