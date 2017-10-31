<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * ReceptionVoucher
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class ReceptionParking {

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
     * @ORM\Column(name="details", type="text")
     */
    private $details;
    
    /**
     * @var float
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set details
     *
     * @param string $details
     * @return CashClosure
     */
    public function setDetails($details) {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails() {
        return $this->details;
    }
       
    /**
     * Get value
     *
     * @return float 
     */
    public function getValue() {
        return $this->value;
    }
    /**
     * Set value
     *
     * @param float $value
     * @return object
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }
    
    public function __toString(){
        return $this->details;
    }
    

}