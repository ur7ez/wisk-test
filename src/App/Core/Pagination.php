<?php
/**
 * Created by PhpStorm.
 * User: Mike Nykytenko
 */

namespace App\Core;
class Pagination
{
    public $buttons = [];

    /**
     * Pagination constructor
     * @param array $options
     */
    public function __construct(Array $options = ['itemsCount' => 200, 'itemsPerPage' => 5, 'currentPage' => 1])
    {
        /**
         * @var $itemsCount
         * @var $itemsPerPage
         * @var $currentPage
         */
        extract($options);
        // in PHP 7.1 we can use this is new syntax:
        /*
        list(
            'itemsCount' => $itemsCount,
            'itemsPerPage' => $itemsPerPage,
            'currentPage' => $currentPage
            ) = $options;
        */

        // нулевая текущая страница
        if (!$currentPage) return;
        $pagesCount = ceil($itemsCount / $itemsPerPage);
        // количество страниц равное 1
        if ($pagesCount == 1) return;

        // номер текущей страницы превышает номер последней
        if ($currentPage > $pagesCount) $currentPage = $pagesCount;

        $this->buttons[] = new Button($currentPage - 1, $currentPage > 1, false, 'Previous');

        for ($i = 1; $i <= $pagesCount; $i++) {
            $active = $currentPage != $i;
            $this->buttons[] = new Button($i, $active, $currentPage == $i);
        }

        $this->buttons[] = new Button($currentPage + 1, $currentPage < $pagesCount, false, 'Next');
    }
}