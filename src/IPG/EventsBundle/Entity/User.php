<?php

namespace IPG\EventsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IPG\EventsBundle\Entity\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255)
     */
    private $avatar;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getRoles()
    {
        return array('ROLE_USER', 'ROLE_OAUTH_USER');
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return true;
    }

}
