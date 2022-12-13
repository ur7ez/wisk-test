<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

namespace App\Views;

use App\Core\App;

class Base
{
    /** @var string */
    protected $template;

    /** @var array */
    protected $data;

    /**
     * @param array $data
     * @param null $template
     * @throws \Exception
     */
    public function __construct($data = [], $template = null)
    {
        if (!$template) {
            $template = static::getDefaultTemplate();
        }

        if (!file_exists($template)) {
            throw new \Exception('Template file is not found: ' . $template);
        }

        $this->data = $data;
        $this->template = $template;
    }

    /**
     * @return string
     */
    protected static function getDefaultTemplate()
    {
        $router = App::getRouter();
        $route = $router->getRoute();
        $controller = $router->getController(true);
        $action = $router->getAction(true);

        return ROOT
            . DS . 'views'
            . DS . strtolower($route)
            . DS . strtolower($controller)
            . DS . strtolower($action) . '.php';
    }

    /**
     * @return string
     * does a view template rendering
     */
    public function render()
    {
        // $data и $router будут доступны и видны в шаблонах (связующее звено между контроллером и шаблоном)
        $data = $this->data;
        $router = App::getRouter();
        ob_start();
        include $this->template;
        return ob_get_clean();
    }
}