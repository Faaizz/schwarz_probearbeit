<?php
namespace App\Service\Interfaces;

interface UserServiceInterface
{
    /**
     * @return array<int, App\Entity\User>
     */
    public function getAll(): array;
}
