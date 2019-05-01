<?php

declare(strict_types = 1);

namespace Service\User;

use Model;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Security implements ISecurity
{
    private const SESSION_USER_IDENTITY = 'userId';

    /**
     * @var SessionInterface
     */
    private $session;

    private $dataSource;

    public function __construct(SessionInterface $session, $dataSource)
    {
        $this->session = $session;
        $this->dataSource = $dataSource;
    }

    /**
     * @inheritdoc
     */
    public function getUser(): ?Model\Entity\User
    {
        $userId = $this->session->get(self::SESSION_USER_IDENTITY);

        return $userId ? (new Model\DataMapper\UserMapper($this->dataSource))->getById($userId) : null;
    }

    /**
     * @inheritdoc
     */
    public function isLogged(): bool
    {
        return $this->getUser() instanceof Model\Entity\User;
    }

    /**
     * @inheritdoc
     */
    public function authentication(string $login, string $password): bool
    {
        $user = $this->getUserRepository()->getByLogin($login);

        if ($user === null) {
            return false;
        }

        if (!password_verify($password, $user->getPasswordHash())) {
            return false;
        }

        $this->session->set(self::SESSION_USER_IDENTITY, $user->getId());

        // Здесь могут выполняться другие действия связанные с аутентификацией пользователя

        return true;
    }

    /**
     * @inheritdoc
     */
    public function logout(): void
    {
        $this->session->set(self::SESSION_USER_IDENTITY, null);

        // Здесь могут выполняться другие действия связанные с разлогиниванием пользователя
    }

    /**
     * Фабричный метод для репозитория User
     *
     * @return Model\DataMapper\UserMapper
     */
    protected function getUserRepository(): Model\DataMapper\UserMapper
    {
        return new Model\DataMapper\UserMapper($this->dataSource);
    }
}
