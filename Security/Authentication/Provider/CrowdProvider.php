<?php

namespace Seiffert\CrowdAuthBundle\Security\Authentication\Provider;

use Seiffert\CrowdAuthBundle\Security\Authentication\Token\CrowdToken;
use Seiffert\CrowdRestBundle\Crowd;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CrowdProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var Crowd
     */
    private $crowd;

    /**
     * @param UserProviderInterface $userProvider
     * @param Crowd $crowd
     */
    public function __construct(UserProviderInterface $userProvider, Crowd $crowd)
    {
        $this->userProvider = $userProvider;
        $this->crowd = $crowd;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if ($user
            && (
                $token->isAuthenticated()
                || $this->crowd->isauthenticationvalid($token->getUsername(), $token->getCredentials()))) {
            $authenticatedToken = new CrowdToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The Crowd authentication failed.');
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return Boolean true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof UsernamePasswordToken || $token instanceof CrowdToken;
    }
}
