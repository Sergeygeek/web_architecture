<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.04.2019
 * Time: 23:44
 */

namespace Composite;


abstract class MenuComposite extends MenuElement
{
    /**
     * @var MenuElement[]
     */
    protected $items = [];

    /**
     * Методы добавления/удаления подобъектов.
     *
     * @param MenuElement $item
     */
    public function add(MenuElement $item): void
    {
        $title = $item->getTitle();
        $this->items[$title] = $item;
    }

    public function remove(MenuElement $item): void
    {
        $this->items = array_filter($this->items, function ($child) use ($item) {
            return $child != $item;
        });
    }

    public function render(): string
    {
        $output = "";

        foreach ($this->items as $title => $field) {
            $output .= $field->render();
        }

        return $output;
    }
}