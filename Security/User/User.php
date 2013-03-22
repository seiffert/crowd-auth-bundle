<?php

namespace Seiffert\CrowdAuthBundle\Security\User;

use Seiffert\CrowdRestBundle\Crowd\User as BaseUser;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends BaseUser implements UserInterface
{
    /**
     * @param BaseUser $user
     * @return User
     */
    public static function fromUser(BaseUser $user)
    {
        $newUser = new User();
        $newUser->setUsername($user->getUsername());
        $newUser->setEmail($user->getEmail());
        $newUser->setActive($user->isActive());
        $newUser->setDisplayName($user->getDisplayName());
        $newUser->setFirstName($user->getFirstName());
        $newUser->setLastName($user->getLastName());

        return $newUser;
    }

    /**
     * @return array|string[]|Role[]
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
    }
}
