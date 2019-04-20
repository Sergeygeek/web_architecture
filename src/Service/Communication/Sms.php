<?php

declare(strict_types = 1);

namespace Service\Communication;

use Model;

class Sms implements ICommunication, \SplObserver
{
    /**
     * @inheritdoc
     */
    public function process(Model\Entity\User $user, string $templateName, array $params = []): void
    {
        // Вызываем метод по формированию смс текста и последующего его отправления
    }

    public function update(\SplSubject $subject)
    {
        // TODO: Implement update() method.
    }
}
