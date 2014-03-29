<?php

namespace IPG\EventsBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use IPG\EventsBundle\IPGEventsBundle;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserProvider extends \HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        $username = $resourceOwnerName . ':' . $response->getUsername();
        $user = $this->repository->findOneBy(array('username' => $username));

        if (null === $user) {
            $user = new \IPG\EventsBundle\Entity\User();
            $user->setUsername($username);
            $user->setName($response->getNickname());
            $user->setAvatar($response->getProfilePicture());
            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }
}
