<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

namespace App\Core\DB;

interface IConnection
{
    public function __construct($host, $user, $pass, $dbName);

    public function query($sql, $data = []);

    public function escape($data);
}