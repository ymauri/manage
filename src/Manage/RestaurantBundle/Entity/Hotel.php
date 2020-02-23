<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hotel
 * @ORM\Table()
 * @ORM\Entity()
 */
class Hotel {
 
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
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Worker")
     */
    private $userdoor;

                 /**
     * @var string
     * @ORM\Column(name="tax", type="float")
     */
    private $tax;
    
                     /**
     * @var string
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;
                     /**
     * @var string
     * @ORM\Column(name="finished", type="datetime", nullable=true)
     */
    private $finished;
    
    /**
     * @var float
     * @ORM\Column(name="totalont", type="float", nullable=true)
     */
    private $totalont;
    
    /**
     * @var float
     * @ORM\Column(name="totalover", type="float", nullable=true)
     */
    private $totalover;
    
    /**
     * @var float
     * @ORM\Column(name="totalvoldan", type="float", nullable=true)
     */
    private $totalvoldan;
    
    /**
     * @var float
     * @ORM\Column(name="totaltoer", type="float", nullable=true)
     */
    private $totaltoer;
    
    /**
     * @var float
     * @ORM\Column(name="totalparking", type="float", nullable=true)
     */
    private $totalparking;
    
    /**
     * @var float
     * @ORM\Column(name="totalextra", type="float", nullable=true)
     */
    private $totalextra;
    
    /**
     * @var float
     * @ORM\Column(name="totaldag", type="float", nullable=true)
     */
    private $totaldag;
    
               /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\Card", cascade={"persist"})
     */
    private $card;
    
        /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\Bill", cascade={"persist"})
     */
    private $bill;
    
   /**
     * @var float
     * @ORM\Column(name="totalparkingextra", type="float", nullable=true)
     */
    private $totalparkingextra;
    /**
     * @var float
     * @ORM\Column(name="totalcontanten", type="float", nullable=true)
     */
    private $totalcontanten;
    /**
     * @var float
     * @ORM\Column(name="totaldebit", type="float", nullable=true)
     */
    private $totaldebit;
    /**
     * @var float
     * @ORM\Column(name="totalcredit", type="float", nullable=true)
     */
    private $totalcredit;
    /**
     * @var float
     * @ORM\Column(name="kasver", type="float", nullable=true)
     */
    private $kasver;
    /**
     * @var float
     * @ORM\Column(name="totalborg", type="float", nullable=true)
     */
    private $totalborg;
    /**
     * @var float
     * @ORM\Column(name="totalretourborg", type="float", nullable=true)
     */
    private $totalretourborg;
    /**
     * @var float
     * @ORM\Column(name="saldoborg", type="float", nullable=true)
     */
    private $saldoborg;

    /**
     * @var float
     * @ORM\Column(name="longStay", type="float", nullable=true)
     */
    private $longStay;
    /**
     * @var float
     * @ORM\Column(name="totalccv", type="float", nullable=true)
     */
    private $totalccv;

    /**
     * @var float
     * @ORM\Column(name="parking", type="float", nullable=true)
     */
    private $parking;
    /**
     * @var float
     * @ORM\Column(name="nightslimit", type="float", nullable=true)
     */
    private $nightslimit;
    /**
     * @var float
     * @ORM\Column(name="ammountparking", type="float", nullable=true)
     */
    private $ammountparking;
    
    public function getId() {
        return $this->id;
    }
 
    public function setName($name) {
        $this->name = $name;

        return $this;
    } 
    
    public function getName() {
        return $this->name;
    }
     
    public function setDetails($details) {
        $this->details = $details;

        return $this;
    }

    public function getDetails() {
        return $this->details;
    }

    public function setDated($dated) {
        $this->dated = $dated;

        return $this;
    }

    public function getDated() {
        return $this->dated;
    }
    
    public function getUserdoor () {
        return $this->userdoor;
    }

    public function setUserdoor($userdoor) {
        $this->userdoor = $userdoor;
        return $this;
    }
    
    public function getTax () {
        return $this->tax;
    }

    public function setTax($notify) {
        $this->tax = $notify;
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
    public function getTotalont () {
        return $this->totalont;
    }

    public function setTotalont($v) {
        $this->totalont = $v;
        return $this;
    }
    public function getTotalover () {
        return $this->totalover;
    }

    public function setTotalover($v) {
        $this->totalover = $v;
        return $this;
    }
    public function getTotalvoldan () {
        return $this->totalvoldan;
    }

    public function setTotalvoldan($v) {
        $this->totalvoldan = $v;
        return $this;
    }
    public function getTotaltoer () {
        return $this->totaltoer;
    }

    public function setTotaltoer($v) {
        $this->totaltoer = $v;
        return $this;
    }
    public function getTotalparking () {
        return $this->totalparking;
    }

    public function setTotalparking($v) {
        $this->totalparking = $v;
        return $this;
    }
    public function getTotalextra () {
        return $this->totalextra;
    }

    public function setTotalextra($v) {
        $this->totalextra = $v;
        return $this;
    }
    public function getTotaldag () {
        return $this->totaldag;
    }

    public function setTotaldag($v) {
        $this->totaldag = $v;
        return $this;
    }
    
     public function getBill() {
        return $this->bill;
    }

    public function setBill($bill) {
        $this->bill = $bill;
        return $this;
    }
    
    public function getCard() {
        return $this->card;
    }

    public function setCard($card) {
        $this->card = $card;
        return $this;
    }
    
    public function getTotalparkingextra() {
        return $this->totalparkingextra;
    }

    public function setTotalparkingextra($v) {
        $this->totalparkingextra = $v;
        return $this;
    }
    public function getTotalcontanten() {
        return $this->totalcontanten;
    }

    public function setTotalcontanten($v) {
        $this->totalcontanten = $v;
        return $this;
    }
    public function getTotaldebit() {
        return $this->totaldebit;
    }

    public function setTotaldebit($v) {
        $this->totaldebit = $v;
        return $this;
    }
    public function getTotalcredit() {
        return $this->totalcredit;
    }

    public function setTotalcredit($v) {
        $this->totalcredit = $v;
        return $this;
    }
    public function getKasver() {
        return $this->kasver;
    }
    public function setKasver($v) {
        $this->kasver = $v;
        return $this;
    }
    public function getTotalccv() {
        return $this->totalccv;
    }
    public function setTotalccv($v) {
        $this->totalccv = $v;
        return $this;
    }
    public function getParking() {
        return $this->parking;
    }
    public function setParking($v) {
        $this->parking = $v;
        return $this;
    }   
    public function getNightslimit() {
        return $this->nightslimit;
    }
    public function setNightslimit($v) {
        $this->nightslimit = $v;
        return $this;
    }

    public function getTotalborg(){
        return $this->totalborg;
    }

    public function setTotalborg($totalborg){
        $this->totalborg = $totalborg;
        return $this;
    }

    public function getSaldoborg(){
        return $this->saldoborg;
    }

    public function setSaldoborg($saldoborg){
        $this->saldoborg = $saldoborg;
        return $this;
    }

    public function getTotalretourborg(){
        return $this->totalretourborg;
    }

    public function setTotalretourborg($totalretourborg){
        $this->totalretourborg = $totalretourborg;
        return $this;
    }

    public function getAmmountparking(){
        return $this->ammountparking;
    }

    public function setAmmountparking($ammountparking){
        $this->ammountparking = $ammountparking;
        return $this;
    }

    public function getLongstay(){
        return $this->longStay;
    }

    public function setLongstay($v){
        $this->longStay = $v;
        return $this;
    }




    public function __toString() {
        return $this->dated.' '.$this->name;
    }



    
}
