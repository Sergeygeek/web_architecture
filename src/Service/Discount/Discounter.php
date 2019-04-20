<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.04.2019
 * Time: 23:07
 */

namespace Service\Discount;


class Discounter
{
    private $discounter;

    public function __construct(IDiscount $discounter)
    {
        $this->discounter = $discounter;
    }

    public function addDiscount()
    {
        return $this->discounter->getDiscount();
    }
}