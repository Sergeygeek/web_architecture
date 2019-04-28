<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.04.2019
 * Time: 23:53
 */

namespace Composite;


class Menu extends MenuComposite
{
    public function render(): string
    {
        $output = parent::render();
        return "<ul class='{$this->getClass()}'>{$output}</ul>";
    }

}