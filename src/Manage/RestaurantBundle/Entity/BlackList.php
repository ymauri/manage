<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * BlackList
 * @ORM\Table()
 * @ORM\Entity()
 * @UniqueEntity("name")
 * @UniqueEntity("email")
 */
class BlackList {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;


    /**
     * @var string
     * @ORM\Column(name="checkin", type="date", nullable=true)
     */
    private $checkin;

    /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Listing")
     */
    private $listing;


    public function getId() {
        return $this->id;
    }
 
    public function setName($v) {
        $this->name = $v;

        return $this;
    } 
    
    public function getName() {
        return $this->name;
    }
     
    public function setDetails($v) {
        $this->details = $v;

        return $this;
    }

    public function getDetails() {
        return $this->details;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getCheckin()
    {
        return $this->checkin;
    }

    public function setCheckin($checkin)
    {
        $this->checkin = $checkin;
    }

    public function getListing()
    {
        return $this->listing;
    }

    public function setListing($listing)
    {
        $this->listing = $listing;
    }
}
