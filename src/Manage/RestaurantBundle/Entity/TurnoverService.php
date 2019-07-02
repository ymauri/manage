<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * TurnoverService
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class TurnoverService {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="omzkitchen", type="float", nullable=true)
     */
    private $omzkitchen;
    
    /**
     * @ORM\Column(name="omzlaag", type="float", nullable=true)
     */
    private $omzlaag;
    
    /**
     * @ORM\Column(name="omzhoog", type="float", nullable=true)
     */
    private $omzhoog;
    
    /**
     * @ORM\Column(name="omzspacerent", type="float", nullable=true)
     */
    private $omzspacerent;
    
    /**
     * @ORM\Column(name="omzothers", type="float", nullable=true)
     */
    private $omzothers;
    
    /**
     * @ORM\Column(name="omzentry", type="float", nullable=true)
     */
    private $omzentry;
    
    /**
     * @ORM\Column(name="omzparking", type="float", nullable=true)
     */
    private $omzparking;
    /**
     * @ORM\Column(name="omzvouchersrek", type="float", nullable=true)
     */
    private $omzvouchersrek;
    
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
     * @ORM\Column(name="ontbelevoucher", type="float", nullable=true)
     */
    private $ontbelevoucher;
    
    /**
     * @ORM\Column(name="ontvooverkoop", type="float", nullable=true)
     */
    private $ontvooverkoop;
    
    /**
     * @ORM\Column(name="ontkadopagina", type="float", nullable=true)
     */
    private $ontkadopagina;
    
    /**
     * @ORM\Column(name="ontrekening", type="float", nullable=true)
     */
    private $ontrekening;
    
    /**
     * @ORM\Column(name="onttickets", type="float", nullable=true)
     */
    private $onttickets;
    
    /**
     * @ORM\Column(name="onttotal", type="float", nullable=true)
     */
    private $onttotal;
    
    public function getId() {
        return $this->id;
    }
 
    public function getOmzkitchen () {
        return $this->omzkitchen;
    }

    public function setOmzkitchen ($v) {
        $this->omzkitchen = $v;
        return $this;
    }
    
    public function getOmzlaag () {
        return $this->omzlaag;
    }

    public function setOmzlaag ($v) {
        $this->omzlaag = $v;
        return $this;
    }
    
    public function getOmzhoog () {
        return $this->omzhoog;
    }

    public function setOmzhoog ($v) {
        $this->omzhoog = $v;
        return $this;
    }
    
    public function getOmzentry () {
        return $this->omzentry;
    }

    public function setOmzentry ($v) {
        $this->omzentry = $v;
        return $this;
    }
    
    public function getOmzparking () {
        return $this->omzparking;
    }

    public function setOmzparking ($v) {
        $this->omzparking = $v;
        return $this;
    }
    
    public function getOmzvouchersrek () {
        return $this->omzvouchersrek;
    }

    public function setOmzvouchersrek ($v) {
        $this->omzvouchersrek = $v;
        return $this;
    }
    
    public function getOmzspacerent () {
        return $this->omzspacerent;
    }

    public function setOmzspacerent ($v) {
        $this->omzspacerent = $v;
        return $this;
    }
    
    public function getOmzothers () {
        return $this->omzothers;
    }

    public function setOmzothers ($v) {
        $this->omzothers = $v;
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
    
    public function getOntbelevoucher () {
        return $this->ontbelevoucher;
    }

    public function setOntbelevoucher ($v) {
        $this->ontbelevoucher = $v;
        return $this;
    }
    
    public function getOntvooverkoop () {
        return $this->ontvooverkoop;
    }

    public function setOntvooverkoop ($v) {
        $this->ontvooverkoop = $v;
        return $this;
    }
    
    public function getOntkadopagina () {
        return $this->ontkadopagina;
    }

    public function setOntkadopagina ($v) {
        $this->ontkadopagina = $v;
        return $this;
    }
    
    public function getOntrekening () {
        return $this->ontrekening;
    }

    public function setOntrekening ($v) {
        $this->ontrekening = $v;
        return $this;
    }
    
    public function getOnttickets () {
        return $this->onttickets;
    }

    public function setOnttickets ($v) {
        $this->onttickets = $v;
        return $this;
    }
    
}
