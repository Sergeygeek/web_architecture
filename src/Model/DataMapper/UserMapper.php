<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.04.2019
 * Time: 22:30
 */

namespace Model\DataMapper;

use Model\Entity\Role;
use Model\Entity\User;

class UserMapper
{
    private $dataSource;

    public function __construct($dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * Получаем пользователей из источника данных
     *
     * @param array $search
     *
     * @return array
     */
    private function getDataFromSource(array $search = [])
    {

        if (!count($search)) {
            return $this->dataSource;
        }

        $productFilter = function (array $dataSource) use ($search): bool {
            return (bool) array_intersect($dataSource, $search);
        };

        return array_filter($this->dataSource, $productFilter);
    }

    /**
     * Получаем пользователя по идентификатору
     *
     * @param int $id
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        foreach ($this->getDataFromSource(['id' => $id]) as $user) {
            return $this->createUser($user);
        }

        return null;
    }

    /**
     * Получаем пользователя по логину
     *
     * @param string $login
     * @return User
     */
    public function getByLogin(string $login): ?User
    {
        foreach ($this->getDataFromSource(['login' => $login]) as $user) {
            if ($user['login'] === $login) {
                return $this->createUser($user);
            }
        }

        return null;
    }

    public function getUsers(array $ids){

    }

    /**
     * Фабрика по созданию сущности пользователя
     *
     * @param array $user
     * @return User
     */
    private function createUser(array $user): User
    {
        $role = $user['role'];

        return new User(
            $user['id'],
            $user['name'],
            $user['login'],
            $user['password'],
            new Role($role['id'], $role['title'], $role['role'])
        );
    }
}