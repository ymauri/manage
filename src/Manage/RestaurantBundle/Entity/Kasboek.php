<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Kasboek
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class Kasboek {

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
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekIn", cascade={"persist"})
     */
    private $in;

    /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekOut", cascade={"persist"})
     */
    private $out;

    /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekContanten", cascade={"persist"})
     */
    private $los;

    /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekContanten", cascade={"persist"})
     */
    private $rol;

    /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekContanten", cascade={"persist"})
     */
    private $waarde;

   /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekContanten", cascade={"persist"})
     */
    private $bedrag;
   /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekFloats", cascade={"persist"})
     */
    private $floats;

    /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekSalarissen", cascade={"persist"})
     */
    private $salarissen;

   /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekKas", cascade={"persist"})
     */
    private $kas;

    /**
     * @ORM\Column(name="totalsalarissenkas", type="float", nullable=true)
     */
    private $totalsalarissenkas;
    /**
     * @ORM\Column(name="totalinkas", type="float", nullable=true)
     */
    private $totalinkas;

    /**
     * @ORM\Column(name="kasverschil", type="float", nullable=true)
     */
    private $kasverschil;
    /**
     * @ORM\Column(name="eindsaldo", type="float", nullable=true)
     */
    private $eindsaldo;
    /**
     * @ORM\Column(name="beginsaldo ", type="float", nullable=true)
     */
    private $beginsaldo;


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

    public function getFinished(){
        return $this->finished;
    }

    public function setFinished($finished){
        $this->finished = $finished;
        return $this;
    }

    public function getIn () {
        return $this->in;
    }

    public function setIn($v) {
        $this->in = $v;
        return $this;
    }

    public function getOut () {
        return $this->out;
    }

    public function setOut($v) {
        $this->out = $v;
        return $this;
    }

    public function getLos(){
        return $this->los;
    }

    public function setLos($los){
        $this->los = $los;
        return $this;
    }
    public function getRol(){
        return $this->rol;
    }

    public function setRol($rol){
        $this->rol = $rol;
        return $this;
    }

    public function getWaarde(){
        return $this->waarde;
    }

    public function setWaarde($waarde){
        $this->waarde = $waarde;
        return $this;

    }

    public function getBedrag(){
        return $this->bedrag;
    }

    public function setBedrag($bedrag){
        $this->bedrag = $bedrag;
        return $this;
    }

    public function getFloats(){
        return $this->floats;
    }

    public function setFloats($floats){
        $this->floats = $floats;
        return $this;
    }

    public function getKas()
    {
        return $this->kas;
    }

    public function setKas($kas)
    {
        $this->kas = $kas;
    }

    public function getSalarissen(){
        return $this->salarissen;
    }

    public function setSalarissen($salarissen){
        $this->salarissen = $salarissen;
        return $this;
    }

    public function getTotalsalarissenkas(){
        return $this->totalsalarissenkas;

    }

    public function setTotalsalarissenkas($totalsalarissenkas){
        $this->totalsalarissenkas = $totalsalarissenkas;
        return $this;
    }

    public function setTotalinkas($totalinkas){
        $this->totalinkas = $totalinkas;
        return $this;
    }

    public function getTotalinkas(){
        return $this->totalinkas;
    }

    public function getBeginsaldo(){
        return $this->beginsaldo;
    }

    public function setBeginsaldo($beginsaldo){
        $this->beginsaldo = $beginsaldo;
        return $this;
    }

    public function getEindsaldo(){
        return $this->eindsaldo;
    }

    public function setEindsaldo($eindsaldo){
        $this->eindsaldo = $eindsaldo;
        return $this;
    }

    public function getKasverschil(){
        return $this->kasverschil;
    }

    public function setKasverschil($kasverschil){
        $this->kasverschil = $kasverschil;
        return $this;
    }

    public function __toString() {
        return $this->dated.' '.$this->name;
    }




}
