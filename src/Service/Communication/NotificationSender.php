<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.04.2019
 * Time: 22:45
 */

namespace Service\Communication;


class NotificationSender
{
    private $notificationSender;

    public function __construct(ICommunication $notificationSender)
    {
        $this->notificationSender = $notificationSender;
    }

    public function send()
    {
        // TODO: Implement send() method.
    }
}