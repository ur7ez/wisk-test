<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

use App\Core\Config;

/**
 * Routing
 */
Config::set('routes', ['default']);
Config::set('defaultRoute', 'default');
Config::set('defaultController', '');
Config::set('defaultAction', 'index');

/**
 * Pagination
 */
Config::set('itemsPerPage', 15);

/**
 * Debug
 */
Config::set('debug', true);

/**
 * Meta
 */
Config::set('siteName', 'Phonebook');

/**
 * Database
 */
Config::set('db.host', 'mariadb');  // for non-docker use 'localhost:3306'
Config::set('db.user', 'root');
Config::set('db.password', 'root');
Config::set('db.name', 'phonebook');

/**
 * User
 */
Config::set('salt', 'sdf703dfg884$hsd7dfdf4');