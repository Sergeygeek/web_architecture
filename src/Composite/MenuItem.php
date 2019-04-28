<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.04.2019
 * Time: 23:30
 */

namespace Composite;


class MenuItem extends MenuElement
{
    private $link;

    public function __construct(string $class, string $title, string $link)
    {
        parent::__construct($class, $title);
        $this->link = $link;
    }

    public function render(): string
    {
        return "<li class=\"{$this->getClass()}\"><a href='{$this->link}'>{$this->getTitle()}</a></li>";
    }
}