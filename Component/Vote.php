<?php

namespace fabienChn\SmoothVoterBundle\Component;

use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Vote
 * @package fabienChn\SmoothVoterBundle\Component
 */
class Vote
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var array
     */
    private $userEntity;

    /**
     * Vote constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param array $config
     */
    public function __construct(TokenStorageInterface $tokenStorage, array $config)
    {
        $this->token = $tokenStorage->getToken();

        $this->userEntity = $config['user_entity'];
    }

    /**
     * This is a security checker
     *
     * Executes the given voter with the given params for the authenticated user.
     * Throws an Exception If the voter doesn't grant the access.
     *
     * @param string $voterName
     * @param string $action
     * @param $entity
     * @return void
     * @throws AccessDeniedException
     */
    public function process(string $voterName, string $action, $entity): void
    {
        $voter = new $voterName();

        $voter->setUserEntityName($this->userEntity)->processVote(
            $this->token,
            $entity,
            $action
        );
    }
}
