<?php

namespace Seiffert\CrowdAuthBundle\Security\User;

use Seiffert\CrowdRestBundle\Crowd;
use Seiffert\CrowdRestBundle\Exception\UserNotFoundException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CrowdUserProvider implements UserProviderInterface
{
    /**
     * @var Crowd
     */
    private $crowd;

    /**
     * @var Session
     */
    private $session;

    public function __construct(Crowd $crowd, Session $session)
    {
        $this->crowd = $crowd;
        $this->session = $session;
    }

    /**
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
        $sessionKey = 'seiffert_crowd_auth.user';

        if ($this->session->has($sessionKey)) {
            return $this->session->get($sessionKey);
        }

        try {
            $user = User::fromUser($this->crowd->getUser($username));

            $this->session->set($sessionKey, $user);

            return $user;
        } catch (UserNotFoundException $e) {
            throw new UsernameNotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException();
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return $class == 'Seiffert\CrowdAuthBundle\Security\User\User';
    }
}
