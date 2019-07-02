<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping\JoinColumn;


/**
 * Cleaning
 *
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class Cleaning {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\RCheckoutHotel" )
     * @JoinColumn(name="checkout", onDelete="SET NULL", nullable=true)
     */
    private $checkout;


    /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Listing")
     */
    private $listing;

    /**
     * @var string
     * @ORM\Column(name="status", type="string")
     */
    private $status;


    /**
     * @var string
     * @ORM\Column(name="dated", type="date")
     */
    private $dated;
    /**
     * @var string
     * @ORM\Column(name="isextra", type="boolean")
     */
    private $isextra;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCheckout()
    {
        return $this->checkout;
    }

    public function setCheckout($checkout)
    {
        $this->checkout = $checkout;
    }

    public function getListing()
    {
        return $this->listing;
    }

    public function setListing($listing)
    {
        $this->listing = $listing;
    }

    public function getDated()
    {
        return $this->dated;
    }

    public function setDated($dated)
    {
        $this->dated = $dated;
    }

    public function getIsextra()
    {
        return $this->isextra;
    }

    public function setIsextra($isextra)
    {
        $this->isextra = $isextra;
    }
}