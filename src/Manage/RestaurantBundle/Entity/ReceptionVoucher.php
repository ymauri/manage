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
 * @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Repository\ReceptionVoucherRepository")
 */
class ReceptionVoucher {

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
     * @ORM\Column(name="isactive", type="boolean")
     */
    private $isactive;
    
    /**
     * @var boolean
     * @ORM\Column(name="forreception", type="boolean")
     */
    private $forreception;
    
    /**
     * @var boolean
     * @ORM\Column(name="isext", type="boolean")
     */
    private $isext;

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

    /**
     * Get forreception
     *
     * @return object 
     */
    public function getForreception() {
        return $this->forreception;
    }

    /**
     * Set forreception
     *
     * @param object $forreception
     * @return boolean
     */
    public function setForreception($forreception) {
        $this->forreception = $forreception;
        return $this;
    }
    
     /**
     * Get forreception
     *
     * @return object 
     */
    public function getIsext() {
        return $this->isext;
    }

    /**
     * Set forreception
     *
     * @param object $isext
     * @return boolean
     */
    public function setIsext($is) {
        $this->isext = $is;
        return $this;
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
        return $this->details .' '.$this->value .'€';
    }
    

}