<?php
/**
 * Created by PhpStorm.
 * User: Mike Nykytenko
 */

namespace App\Core;
class Button
{
    public $page;
    public $isActive;
    public $isCurrent;
    public $text;

    public function __construct($page, $isActive = true, $isCurrent = false, $text = null)
    {
        $this->page = $page;
        $this->isActive = $isActive;
        $this->isCurrent = $isCurrent;
        $this->text = is_null($text) ? $page : $text;
    }

    public function activate()
    {
        $this->isActive = true;
    }

    public function deactivate()
    {
        $this->isActive = false;
    }
}