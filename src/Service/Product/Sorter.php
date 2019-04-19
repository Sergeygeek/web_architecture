<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.04.2019
 * Time: 22:10
 */

namespace Service\Product;


class Sorter
{
    /**
     * @var ISort
     */
    private $productSorter;

    public function __construct(ISort $productSorter)
    {
        $this->productSorter = $productSorter;
    }

    public function productSort(array $products):array
    {
        if(!count($products)){
            return $products;
        }

        return $this->productSorter->productSort($products);
    }
}