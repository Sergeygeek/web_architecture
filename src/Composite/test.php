<?php

    $menu = new \Composite\Menu('menu', 'menu');
    $menu->add(new \Composite\MenuItem('menu_item', 'Главная', 'index.html'));
    $menu->add(new \Composite\MenuItem('menu_item', 'Товары', 'products.html'));

    $subMenu = new \Composite\Menu('sub-menu', 'sub-menu');
    $subMenu->add(new \Composite\MenuItem('sub-menu_item', 'Книги', 'books.html'));
    $subMenu->add(new \Composite\MenuItem('sub-menu_item', 'Журналы', 'magazines.html'));

    $menu->add($subMenu);

    echo $menu->render();