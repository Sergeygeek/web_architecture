<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.04.2019
 * Time: 10:54
 */

namespace Service\Order;


class OrderTransaction
{
    private $orderBuilder;
    private $totalPrice;

    public function __construct(OrderBuilder $orderBuilder)
    {
        $this->orderBuilder = $orderBuilder;
    }

    public function order(array $productsInfo)
    {
        $totalPrice = $this->getTotalPrice($productsInfo);

        try{
            $discount = $this->orderBuilder->getDiscount();
            $this->totalPrice = $totalPrice - $totalPrice / 100 * $discount;
            $billing = $this->orderBuilder->getBilling();

            $billing->pay($totalPrice);

            $security = $this->orderBuilder->getSecurity();

            $user = $security->getUser();

            $communication = $this->orderBuilder->getCommunication();
            $communication->process($user, 'checkout_template');
        }catch (\Exception $e){
            $e->getMessage();
        }
    }

    public function getTotalPrice(array $productsInfo)
    {
        $totalPrice = 0;
        foreach ($productsInfo as $product) {
            $totalPrice += $product->getPrice();
        }

        return $totalPrice;
    }
}
