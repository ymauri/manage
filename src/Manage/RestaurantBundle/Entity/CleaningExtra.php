<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * CleaningExtra
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Repository\CleaningExtraRepository")
 *
 */
class CleaningExtra {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var date
     * @ORM\Column(name="begin", type="date")
     */
    private $begin;

    /**
     * @var date
     * @ORM\Column(name="end", type="date")
     */
    private $end;

    /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Listing")
     */
    private $listing;


    /**
     * @var text
     * @ORM\Column(name="details", type="text")
     */
    private $details;

    /**
     * @var array
     * @ORM\Column(name="dayweek", type="array", nullable=true)
     */
    private $dayweek;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails($details)
    {
        $this->details = $details;
    }

    public function getListing()
    {
        return $this->listing;
    }

    public function setListing($listing)
    {
        $this->listing = $listing;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd($end)
    {
        $this->end = $end;
    }

    public function getBegin()
    {
        return $this->begin;
    }

    public function setBegin($begin)
    {
        $this->begin = $begin;
    }

    public function getDayweek()
    {
        return $this->dayweek;
    }

    public function setDayweek($dayweek)
    {
        $this->dayweek = $dayweek;
    }
    
}