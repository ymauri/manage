<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Source
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Source {

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
     * @var boolean
     * @ORM\Column(name="isactive", type="boolean", nullable=true)
     */
    private $isactive;

    /**
     * @var string
     * @ORM\Column(name="guesty", type="string", nullable=true)
     */
    private $guesty;
    
    /**
     * @var float
     * @ORM\Column(name="extrafield", type="boolean", nullable=true)
     */
    private $extrafield;
    
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
     * Set dated
     *
     * @param \DateTime $dated
     * @return \DateTime
     */
    public function setDated($dated) {
        $this->dated = $dated;

        return $this;
    }

    /**
     * Get dated
     *
     * @return \DateTime 
     */
    public function getDated() {
        return $this->dated;
    }

    /**
     * Get isactive
     *
     * @return boolean 
     */
    public function getIsactive() {
        return $this->isactive;
    }

    /**
     * Set isactive
     *
     * @param boolean $isactive
     * @return object
     */
    public function setIsactive($isactive) {
        $this->isactive = $isactive;

        return $this;
    }

    public function getExtrafield() {
        return $this->extrafield;
    }
    public function setExtrafield($value) {
        $this->extrafield = $value;
        return $this;
    }

    public function getGuesty()
    {
        return $this->guesty;
    }

    public function setGuesty($guesty)
    {
        $this->guesty = $guesty;
        return $this;
    }

    public function __toString(){
        return $this->details;
    }
    

}