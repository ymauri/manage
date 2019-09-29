<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Turnover
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class Turnover {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**
     * @var \DateTime
     * @ORM\Column(name="dated", type="date", nullable=true) 
     */
    private $dated;
   
    /**
     * @var datetime
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;
                     /**
     * @var datetime
     * @ORM\Column(name="finished", type="datetime", nullable=true)
     */
    private $finished;
    
    /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Worker")
     */
    private $chief;
    
        /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\TurnoverReception", cascade={"persist"})
     */
    private $reception;
    
        /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\TurnoverService", cascade={"persist"})
     */
    private $service;
    
        /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\TurnoverSkybar", cascade={"persist"})
     */
    private $skybar;
        /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\TurnoverOmzet", cascade={"persist"})
     */
    private $omzet;
    
    /**
     * @ORM\Column(name="omzkitchendag", type="float", nullable=true)
     */
    private $omzkitchendag;
    
    
    /**
     * @ORM\Column(name="omzkitchenavond", type="float", nullable=true)
     */
    private $omzkitchenavond;
    
    /**
     * @ORM\Column(name="omzbeverageps", type="float", nullable=true)
     */
    private $omzbeverageps;
    
    /**
     * @ORM\Column(name="omzbeveragedag", type="float", nullable=true)
     */
    private $omzbeveragedag;
    
    /**
     * @ORM\Column(name="omzbeverageavond", type="float", nullable=true)
     */
    private $omzbeverageavond;
    
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
    
    
    
    public function getId() {
        return $this->id;
    }
 
    public function setName($v) {
        $this->name = $v;

        return $this;
    } 
    
    public function getName() {
        return $this->name;
    }
     
    public function setDetails($v) {
        $this->details = $v;

        return $this;
    }

    public function getDetails() {
        return $this->details;
    }

    public function setDated($v) {
        $this->dated = $v;

        return $this;
    }
    public function getDated() {
        return $this->dated;
    }
    
   
    public function getUpdated () {
        return $this->updated;
    }

    public function setUpdated($updated) {
        $this->updated = $updated;
        return $this;
    }
    public function getFinished () {
        return $this->finished;
    }

    public function setFinished($finished) {
        $this->finished = $finished;
        return $this;
    }
    public function getChief () {
        return $this->chief;
    }

    public function setChief ($chief) {
        $this->chief = $chief;
        return $this;
    }
    public function getReception() {
        return $this->reception;
    }

    public function setReception ($reception) {
        $this->reception = $reception;
        return $this;
    }
    public function getService() {
        return $this->service;
    }

    public function setService ($service) {
        $this->service = $service;
        return $this;
    }
    
    public function getSkybar() {
        return $this->skybar;
    }

    public function setSkybar ($skybar) {
        $this->skybar = $skybar;
        return $this;
    }
    
    public function getOmzet() {
        return $this->omzet;
    }

    public function setOmzet ($omzet) {
        $this->omzet = $omzet;
        return $this;
    }
    
    public function getOmzkitchendag() {
        return $this->omzkitchendag;
    }

    public function setOmzkitchendag ($omzkitchendag) {
        $this->omzkitchendag = $omzkitchendag;
        return $this;
    }
    
    public function getOmzkitchenavond() {
        return $this->omzkitchenavond;
    }

    public function setOmzkitchenavond ($omzkitchenavond) {
        $this->omzkitchenavond = $omzkitchenavond;
        return $this;
    }
    
    public function getOmzbeverageps() {
        return $this->omzbeverageps;
    }

    public function setOmzbeverageps ($omzbeverageps) {
        $this->omzbeverageps = $omzbeverageps;
        return $this;
    }
    public function getOmzbeveragedag() {
        return $this->omzbeveragedag;
    }

    public function setOmzbeveragedag ($omzbeveragedag) {
        $this->omzbeveragedag = $omzbeveragedag;
        return $this;
    }
    public function getOmzbeverageavond() {
        return $this->omzbeverageavond;
    }

    public function setOmzbeverageavond ($omzbeverageavond) {
        $this->omzbeverageavond = $omzbeverageavond;
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
    
    public function __toString() {
        return $this->dated.' '.$this->name;
    }

    
}
