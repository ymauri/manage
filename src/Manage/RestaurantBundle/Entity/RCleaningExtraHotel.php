<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Manage\RestaurantBundle\Entity\Checkin;
use Manage\RestaurantBundle\Entity\Hotel;
use Manage\RestaurantBundle\Entity\Source;

/**
 * RCheckoutHotel
 *
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class RCleaningExtraHotel {
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="CleaningExtra")
     */
    private $cleaningextra;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Hotel")
     */
    private $hotel;

    /**
     * @var boolean
     * @ORM\Column(name="paymentAmount", type="float", nullable=true)
     */
    private $paymentAmount;
    
    /**
     * @var integer
     * @ORM\Column(name="paymentDay", type="integer", nullable=true)
     */
    private $paymentDay;


    /**
     * @var integer
     * @ORM\Column(name="payed", type="boolean", nullable=true)
     */
    private $payed;


    /**
     * @var integer
     * @ORM\Column(name="payedAt", type="datetime", nullable=true)
     */
    private $payedAt;

    public function getId() {
        return $this->id;
    }
    
    public function setCleaningextra($v) {
        $this->cleaningextra = $v;
        return $this;
    }
    
    public function getCleaningextra() {
        return $this->cleaningextra;
    }
    
    
    public function setHotel($v) {
        $this->hotel = $v;
        return $this;
    }
    
    public function getHotel() {
        return $this->hotel;
    }


    public function getPaymentday () {
        return $this->paymentDay;
    }

    public function setPaymentday ($paymentDay) {
        $this->paymentDay = $paymentDay;
    }

    public function getPaymentamount () {
        return $this->paymentAmount;
    }


    public function setPaymentamount ($paymentAmount) {
        $this->paymentAmount = $paymentAmount;
    }

    public function setPayed ($v) {
        $this->payed = $v;
    }

    public function getPayed () {
        return $this->payed;
    }

    public function setPayedat ($v) {
        $this->payedAt = $v;
    }

    public function getPayedat () {
        return $this->payedAt;
    }

    public function __toString()
    {
        return (string) $this->paymentAmount;
    }
}