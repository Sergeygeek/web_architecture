<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.04.2019
 * Time: 22:06
 */

namespace Service\Order;


use Service\Billing\IBilling;
use Service\Communication\ICommunication;
use Service\Discount\Discounter;
use Service\Discount\IDiscount;
use Service\User\ISecurity;

class BasketBuilder
{
    private $billing;
    private $discount;
    private $communication;
    private $security;

    public function setBilling(IBilling $billing)
    {
        $this->billing = $billing;
    }

    public function getBilling(): IBilling
    {
        return $this->billing;
    }

    public function setDiscount(Discounter $discount)
    {
        $this->discount = $discount;
    }

    public function getDiscount(): IDiscount
    {
        return $this->discount;
    }

    public function setCommunication(ICommunication $communication)
    {
        $this->communication = $communication;
    }

    public function getCommunication(): ICommunication
    {
        return $this->communication;
    }

    public function setSecurity(ISecurity $security)
    {
        $this->security = $security;
    }

    public function getSecurity(): ISecurity
    {
        return $this->security;
    }

    public function build()
    {
        return $this;
    }
}