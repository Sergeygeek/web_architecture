<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.04.2019
 * Time: 23:24
 */

namespace Composite;


abstract class MenuElement
{
    protected $title;
    protected $class;

    public function __construct(string $class, string $title)
    {
        $this->class = $class;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    abstract public function render(): string;
}