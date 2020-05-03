<?php

namespace fabienChn\SmoothVoterBundle\Component;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class AbstractSmoothVoter
 * @package fabienChn\SmoothVoterBundle\Component
 */
class AbstractSmoothVoter extends Voter
{
    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $userEntityName;

    /**
     * @var array
     */
    protected $actions;

    /**
     * @param string $userEntityName
     *
     * @return self
     */
    public function setUserEntityName(string $userEntityName): self
    {
        $this->userEntityName = $userEntityName;

        return $this;
    }

    /**
     * @param string $attribute
     * @param mixed $subject: entity to work with
     * @return bool
     * @throws \Exception
     */
    protected function supports($attribute, $subject): bool
    {
        if (empty($this->entityName)) {
            throw new \Exception(
                'Entity Name has to be set in any Voter extending AbstractSmoothVoter'
            );
        }

        // if the subject is null then
        if (!$subject instanceof $this->entityName) {
            throw new NotFoundHttpException();
        }

        if (!in_array($attribute, $this->actions)) {
            throw new \Exception('Attribute/Action given isn\'t supported by this Voter');
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject: entity to work with
     * @param TokenInterface $token
     * @return bool
     * @throws \LogicException
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // the user must be logged in; if not, deny access
        if (!$user instanceof $this->userEntityName) {
            return false;
        }

        foreach ($this->actions as $action) {
            if ($attribute == $action) {
                $method = 'can'.ucfirst($action);
                return $this->$method($subject, $user);
            }
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param TokenInterface $token
     * @param $entity
     * @param string $action
     */
    public function processVote(TokenInterface $token, $entity, string $action)
    {
        $result = $this->vote($token, $entity, [$action]);

        if ($result != Voter::ACCESS_GRANTED) {
            throw new AccessDeniedException();
        }
    }
}
