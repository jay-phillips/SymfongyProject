<?php
namespace App\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\InvalidConfirmationTokenException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserConfirmationService
{
    /**
     
     * @var UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger )
    {
        $this->userRespository = $userRepository;
        $this->entityManager = $entityManager;
    }
    public function confirmUser(string $confirmationToken)
    {
        $this->logger->debug('Fetching user by confirmationtoken');

        $user = $this->userRepository->findOneBy(
            ['confirmationToken' => $confirmationToken] 
        );

        if (!$user){
            $this->logger->debug('User by confirmation token not found ');
            throw new InvalidConfirmationTokenException();
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $this->entityManager->flush();

        $this->logger->debug('Confirmed user by  confirmation token');

    }

}