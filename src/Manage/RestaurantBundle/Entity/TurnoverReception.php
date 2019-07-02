<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * TurnoverReception
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class TurnoverReception {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="omzvoucher", type="float", nullable=true)
     */
    private $omzvoucher;
    
    /**
     * @ORM\Column(name="omzparking", type="float", nullable=true)
     */
    private $omzparking;
    
    /**
     * @ORM\Column(name="omzoverig", type="float", nullable=true)
     */
    private $omzoverig;
    
    /**
     * @ORM\Column(name="omztotal", type="float", nullable=true)
     */
    private $omztotal;
    
    /**
     * @ORM\Column(name="ontdebitcard", type="float", nullable=true)
     */
    private $ontdebitcard;
    
    /**
     * @ORM\Column(name="ontcreditcard", type="float", nullable=true)
     */
    private $ontcreditcard;
    
    /**
     * @ORM\Column(name="onttotal", type="float", nullable=true)
     */
    private $onttotal;
    
    public function getId() {
        return $this->id;
    }
 
    public function getOmzvoucher () {
        return $this->omzvoucher;
    }

    public function setOmzvoucher ($v) {
        $this->omzvoucher = $v;
        return $this;
    }
    
    public function getOmzparking () {
        return $this->omzparking;
    }

    public function setOmzparking ($v) {
        $this->omzparking = $v;
        return $this;
    }
    
    public function getOmzoverig () {
        return $this->omzoverig;
    }

    public function setOmzoverig ($v) {
        $this->omzoverig = $v;
        return $this;
    }
    
    public function getOmztotal () {
        return $this->omztotal;
    }

    public function setOmztotal ($v) {
        $this->omztotal = $v;
        return $this;
    }
    
    public function getOntdebitcard () {
        return $this->ontdebitcard;
    }

    public function setOntdebitcard ($v) {
        $this->ontdebitcard = $v;
        return $this;
    }
    
    public function getOntcreditcard () {
        return $this->ontcreditcard;
    }

    public function setOntcreditcard ($v) {
        $this->ontcreditcard = $v;
        return $this;
    }
    
    public function getOnttotal () {
        return $this->onttotal;
    }

    public function setOnttotal ($v) {
        $this->onttotal = $v;
        return $this;
    }
}
