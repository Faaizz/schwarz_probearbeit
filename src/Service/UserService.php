<?php
namespace App\Service;

use App\Business\UserLogic;
use App\Entity\User;
use App\Service\Interfaces\UserServiceInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserService implements UserServiceInterface
{   
    private $serializer;
    private $userLogic;
    private $apiEndpoint;

    function __construct(SerializerInterface $serializer, UserLogic $userLogic, string $apiEndpoint)
    {
        $this->serializer = $serializer;
        $this->userLogic = $userLogic;
        $this->apiEndpoint = $apiEndpoint;
    }
    
    public function getAll(): array
    {
        $usersJson = $this->userLogic->fetchUsers($this->apiEndpoint);
        return $this->serializer->deserialize($usersJson, User::class . '[]', 'json');
    }
}
