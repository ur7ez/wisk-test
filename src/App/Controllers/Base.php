<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

namespace App\Controllers;
use App\Core\Config;

class Base
{
    /** @var array */
    protected $params;

    /** @var array */
    protected $data;

    /** @var string */
    protected $template = null;

    /** @var int */
    protected $itemsPerPage;

    /**
     * Base constructor.
     * @param array $params
     * @param int $itemsPerPage
     */
    public function __construct($params = [], $itemsPerPage = null)
    {
        $this->params = $params;
        $this->itemsPerPage = (!isset($itemsPerPage)) ? Config::get('itemsPerPage') : $itemsPerPage;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Throw 404 status & page
     */
    public function page404()
    {
        header('HTTP/1.1 404 Not Found');
        $this->template = ROOT . DS . 'views' . DS . '404.php';
    }
}