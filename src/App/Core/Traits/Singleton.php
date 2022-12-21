<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

namespace App\Core\Traits;

trait Singleton
{
    protected static $instance;

    final public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    abstract protected function __construct();

    private function __clone()
    {
    }

    final public function __sleep(): array
    {
        return  [];
    }

    final public function __wakeup()
    {
    }
}