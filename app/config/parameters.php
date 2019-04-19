<?php

$container->setParameter('environment', 'dev');

$container->setParameter('view.directory', __DIR__ . '/../../src/View/');

$container->setParameter('order.listeners',
    [
        'Service\Communication\Email',
        'Service\Communication\Sms'
    ]);
