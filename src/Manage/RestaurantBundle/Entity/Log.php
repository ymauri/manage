<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Log
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class Log {

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
     * @var string
     * @ORM\Column(name="bedlunch", type="text", nullable=true)
     */
    private $bedlunch;
    
            /**
     * @var string
     * @ORM\Column(name="beddinner", type="text", nullable=true)
     */
    private $beddinner;
    
            /**
     * @var string
     * @ORM\Column(name="bedsuites", type="text", nullable=true)
     */
    private $bedsuites;
    
            /**
     * @var string
     * @ORM\Column(name="bedacties", type="text", nullable=true)
     */
    private $bedacties;
    
            /**
     * @var string
     * @ORM\Column(name="bedlost", type="text", nullable=true)
     */
    private $bedlost;
    
            /**
     * @var string
     * @ORM\Column(name="keulunch", type="text", nullable=true)
     */
    private $keulunch;
    
            /**
     * @var string
     * @ORM\Column(name="keudinner", type="text", nullable=true)
     */
    private $keudinner;
    
            /**
     * @var string
     * @ORM\Column(name="keusuites", type="text", nullable=true)
     */
    private $keusuites;
    
            /**
     * @var string
     * @ORM\Column(name="keuacties", type="text", nullable=true)
     */
    private $keuacties;
    
    /**
     * @var string
     * @ORM\Column(name="updated", type="date", nullable=true)
     */
    private $updated;
                     /**
     * @var string
     * @ORM\Column(name="finished", type="date", nullable=true)
     */
    private $finished;
    
    /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Worker")
     */
    private $managerdag;
    
    /**
    * @var object
    *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Worker")
    */
    private $manageravond;
    
    /**
    * @var float
    * @ORM\Column(name="lastguesttime", type="time", nullable=true)
    */
    private $lastguesttime;
    
    /**
    * @var float
    * @ORM\Column(name="closetime", type="time", nullable=true)
    */
    private $closetime;
    
                /**
     * @var string
     * @ORM\Column(name="weather", type="string", nullable=true)
     */
    private $weather;
    
        /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Worker")
     */
    private $receptiedag;
    
    /**
    * @var object
    *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Worker")
    */
    private $receptieavond;
    
                /**
     * @var string
     * @ORM\Column(name="recapp", type="text", nullable=true)
     */
    private $recapp ;
    
            /**
     * @var string
     * @ORM\Column(name="recrest", type="text", nullable=true)
     */
    private $recrest;
    
            /**
     * @var string
     * @ORM\Column(name="recsales", type="text", nullable=true)
     */
    private $recsales;
    
            /**
     * @var string
     * @ORM\Column(name="recmenu", type="text", nullable=true)
     */
    private $recmenu;
    
            /**
     * @var string
     * @ORM\Column(name="recacties", type="text", nullable=true)
     */
    private $recacties;
            /**
     * @var string
     * @ORM\Column(name="reclost", type="text", nullable=true)
     */
    private $reclost;
    
    
    /**
    * @var object
    *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Worker")
    */
    private $chef;
    
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
    
    public function getBedacties() {
        return $this->bedacties;
    }

    public function setBedacties($v) {
        $this->bedacties = $v;
        return $this;
    }
    public function getBeddinner() {
        return $this->beddinner;
    }

    public function setBeddinner($v) {
        $this->beddinner = $v;
        return $this;
    }
    public function getBedlost() {
        return $this->bedlost;
    }

    public function setBedlost($v) {
        $this->bedlost = $v;
        return $this;
    }
    public function getBedlunch() {
        return $this->bedlunch;
    }

    public function setBedlunch($v) {
        $this->bedlunch = $v;
        return $this;
    }
    public function getBedsuites() {
        return $this->bedsuites;
    }

    public function setBedsuites($v) {
        $this->bedsuites = $v;
        return $this;
    }
    public function getChef() {
        return $this->chef;
    }

    public function setChef($v) {
        $this->chef = $v;
        return $this;
    }
    public function getClosetime() {
        return $this->closetime;
    }
    public function setClosetime($v) {
        $this->closetime = $v;
        return $this;
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
    
    public function getKeuacties() {
        return $this->keuacties;
    }
    public function setKeuacties($v) {
        $this->keuacties = $v;
        return $this;
    }
    public function getKeudinner() {
        return $this->keudinner;
    }
    public function setKeudinner($v) {
        $this->keudinner = $v;
        return $this;
    }
    public function getKeulunch() {
        return $this->keulunch;
    }
    public function setKeulunch($v) {
        $this->keulunch = $v;
        return $this;
    }
    public function getKeusuites() {
        return $this->keusuites;
    }
    public function setKeusuites($v) {
        $this->keusuites = $v;
        return $this;
    }
    public function getLastguesttime() {
        return $this->lastguesttime;
    }
    public function setLastguesttime($v) {
        $this->lastguesttime = $v;
        return $this;
    }
    public function getManageravond() {
        return $this->manageravond;
    }
    public function setManageravond($v) {
        $this->manageravond = $v;
        return $this;
    }
    public function getManagerdag() {
        return $this->managerdag;
    }
    public function setManagerdag($v) {
        $this->managerdag = $v;
        return $this;
    }
    public function getReceptieavond() {
        return $this->receptieavond;
    }
    public function setReceptieavond($v) {
        $this->receptieavond = $v;
        return $this;
    }
    public function getReceptiedag() {
        return $this->receptiedag;
    }
    public function setReceptiedag($v) {
        $this->receptiedag = $v;
        return $this;
    }
    public function getWeather() {
        return $this->weather;
    }
    public function setWeather($v) {
        $this->weather = $v;
        return $this;
    }
    public function getRecacties() {
        return $this->recacties;
    }
    public function setRecacties($v) {
        $this->recacties = $v;
        return $this;
    }
    public function getRecapp() {
        return $this->recapp;
    }
    public function setRecapp($v) {
        $this->recapp = $v;
        return $this;
    }
    public function getReclost() {
        return $this->reclost;
    }
    public function setReclost($v) {
        $this->reclost = $v;
        return $this;
    }
    public function getRecmenu() {
        return $this->recmenu;
    }
    public function setRecmenu($v) {
        $this->recmenu = $v;
        return $this;
    }
    public function getRecrest() {
        return $this->recrest;
    }
    public function setRecrest($v) {
        $this->recrest = $v;
        return $this;
    }
    public function getRecsales() {
        return $this->recsales;
    }
    public function setRecsales($v) {
        $this->recsales = $v;
        return $this;
    }
    public function __toString() {
        return $this->dated.' '.$this->name;
    }

    
}
