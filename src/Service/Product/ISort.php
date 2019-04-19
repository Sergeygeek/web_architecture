<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.04.2019
 * Time: 21:52
 */

namespace Service\Product;


interface ISort
{
    public function productSort(array $products): array;
}