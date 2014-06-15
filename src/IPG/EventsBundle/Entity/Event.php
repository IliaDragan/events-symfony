<?php

namespace IPG\EventsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IPG\EventsBundle\Entity\EventRepository")
 */
class Event
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
     * @var integer
     *
     * @ORM\Column(name="locationId", type="integer")
     */
    private $locationId;

    /**
     * @param int $locationId
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;
    }

    /**
     * @return int
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var class
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="events", cascade={"persist"})
     * @ORM\JoinTable(name="events_categories")
     */
    private $categories;

    /**
     * ORM\OneToMany(targetEntity="Picture", mappedBy="event")
     */
    private $pictures;

    public function __construct()
    {
        $this->pictures   = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Event
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Event
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add categories
     *
     * @param \IPG\EventsBundle\Entity\Category $categories
     * @return Event
     */
    public function addCategory(Category $category)
    {
        $this->categories->add($category);

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \IPG\EventsBundle\Entity\Category $categories
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    public function setPictures(Picture $picture)
    {
        $this->pictures->add($picture);
    }

    public function getPictures()
    {
        return $this->pictures;
    }
}
