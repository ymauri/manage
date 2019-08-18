<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Manage\RestaurantBundle\Entity\Checkout;
use Manage\RestaurantBundle\Entity\Hotel;

/**
 * RCheckoutHotel
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Repository\RCheckoutHotelRepository")
 */
class RCheckoutHotel {
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Listing")
     */
    private $listing;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Hotel")
     */
    private $hotel;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Checkout")
     */
    private $checkout;

    /**
     * @var boolean
     * @ORM\Column(name="checkoutdone", type="boolean", nullable=true)
     */
    private $checkoutdone;

    /**
     * @var string
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**
     * @var text
     * @ORM\Column(name="fromguesty", type="boolean", nullable=true)
     */
    private $fromguesty;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;
    
        /**
     * @var string
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status;
    
            /**
     * @var string
     * @ORM\Column(name="changestatusat", type="datetime", nullable=true)
     */
    private $changestatusat;
    
                    /**
     * @var float
     * @ORM\Column(name="borg", type="float", nullable=true)
     */
    private $borg;
    
    public function getId() {
        return $this->id;
    }
    
    public function setListing($v) {
        $this->listing = $v;
        return $this;
    }
    
    public function getListing() {
        return $this->listing;
    }
    
    
    public function setHotel($v) {
        $this->hotel = $v;
        return $this;
    }
    
    public function getHotel() {
        return $this->hotel;
    }
    
    public function setCheckoutdone($v) {
        $this->checkoutdone = $v;
        return $this;
    }
    
    public function getCheckoutdone() {
        return $this->checkoutdone;
    }

    public function setDetails($v) {
        $this->details = $v;
        return $this;
    }

    public function getDetails() {
        return $this->details;
    }

    public function getFromguesty(){
        return $this->fromguesty;
    }

    public function setFromguesty($fromguesty){
        $this->fromguesty = $fromguesty;
        return $this;
    }

    public function setCheckout($v) {
        $this->checkout = $v;
        return $this;
    }

    public function getCheckout() {
        return $this->checkout;
    }

    public function setName($v) {
        $this->name = $v;
        return $this;
    }

    public function getName() {
        return $this->name;
    }
    
    
    public function getStatus(){
        return $this->status;
    }
    
    public function setStatus($v){
        $this->status = $v;
        return $this;
    }
    
    public function getChangestatusat(){
        return $this->changestatusat;
    }
    
    public function setChangestatusat($v){
        $this->changestatusat = $v;
        return $this;
    }
    
    public function getDate(){
        return $this->hotel->getDated();
    }
    
    public function getBorg(){
        return $this->borg;
    }

    public function setBorg($borg){
        $this->borg = $borg;
        return $this;
    }
}