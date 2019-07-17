<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;


/**
 * ListingCalendar
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Entity\ListingCalendarRepository")
 *
 */
class ListingCalendar {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\Column(name="idcalendar", type="string")
     */
    private $idcalendar;
    /**
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var \DateTime
     * @ORM\Column(name="checkin", type="date")
     */
    private $checkin;

    /**
     *
     * @ORM\Column(name="listing", type="string")
     */
    private $listing;

    /**
     *
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Rule")
     */
    private $rule;


    /**
     *
     * @ORM\Column(name="applied", type="boolean")
     */
    private $applied = false;

    public function getId() {
        return $this->id;
    }

    public function getIdcalendar()
    {
        return $this->idcalendar;
    }

    public function setIdcalendar($idcalendar)
    {
        $this->idcalendar = $idcalendar;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
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

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getRule()
    {
        return $this->rule;
    }

    public function setRule($rule)
    {
        $this->rule = $rule;
    }

    public function getApplied()
    {
        return $this->applied;
    }

    public function setApplied($applied)
    {
        $this->applied = $applied;
    }
}
