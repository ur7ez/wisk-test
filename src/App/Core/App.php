<?php
/**
 * Created by PhpStorm.
 * User: Mike Nykytenko
 */

namespace App\Core;

use App\Controllers\Base;
use App\Core\DB\Connection;

class App
{
    /** @var DB\IConnection */
    private static $conn;

    /** @var Router */
    private static $router;

    /** @var Session */
    private static $session;

    /**
     * @return Session
     */
    public static function getSession(): Session {
        return self::$session;
    }

    /**
     * @return Router
     */
    public static function getRouter(): Router {
        return self::$router;
    }

    /**
     * @return DB\IConnection
     */
    public static function getConnection(): DB\IConnection {
        return self::$conn;
    }

    /**
     * @param $uri
     * @throws \Exception
     */
    public static function run($uri) {
        static::$router = new Router($uri);
        static::$session = Session::getInstance();

        static::$conn = new Connection(
            Config::get('db.host'),
            Config::get('db.user'),
            Config::get('db.password'),
            Config::get('db.name')
        );

        $route = static::$router->getRoute();
        $className = static::$router->getController();  // get Controller's class name
        $action = static::$router->getAction();  // defining Controller method
        $params = static::$router->getParams();

        if ($className) {
            $controllerName = '\App\Controllers\\' . $className;

            // @todo Show 404 page
            if (!class_exists($controllerName)) {
                throw new \Exception('Controller ' . $controllerName . ' not found');
            }

            /** @var \App\Controllers\Base $controller */
            $controller = new $controllerName($params);

            if (!method_exists($controller, $action)) {
                throw new \Exception('Action ' . $action . ' not found in ' . $controllerName);
            }

            if (!$controller instanceof Base) {
                throw new \Exception('Controller must extend Base class');
            }

            ob_start();

            $controller->$action();

            $view = new \App\Views\Base(
                $controller->getData(),
                $controller->getTemplate()
            );

            $content = $view->render();
        } else {
            ob_start();
            $content = '';
        }

        // if request was type of XHR - render only data template requested:
        if ($_GET && isset($_GET['transport']) && $_GET['transport'] === 'xhr') {
            header("Access-Control-Allow-Origin: *");
            header('Content-type: application/json; charset=utf-8');
            echo(json_encode($content));
        } else {
            $layout = new \App\Views\Base(
                ['content' => $content],
                ROOT . DS . 'views' . DS . $route . '.php'
            );

            echo $layout->render();
        }

        ob_end_flush();
    }
}