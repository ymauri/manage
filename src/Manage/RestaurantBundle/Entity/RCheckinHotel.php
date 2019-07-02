<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Manage\RestaurantBundle\Entity\Checkin;
use Manage\RestaurantBundle\Entity\Hotel;
use Manage\RestaurantBundle\Entity\Source;

/**
 * RCheckoutHotel
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Entity\RCheckinHotelRepository")
 * 
 */
class RCheckinHotel {
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Checkin")
     */
    private $checkin;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Hotel")
     */
    private $hotel;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Source")
     */
    private $source;
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Listing")
     */
    private $listing;
    
    /**
     * @var boolean
     * @ORM\Column(name="checkindone", type="boolean", nullable=true)
     */
    private $checkindone;
    
    /**
     * @var integer
     * @ORM\Column(name="nights", type="integer", nullable=true)
     */
    private $nights;
    
    /**
     * @var integer
     * @ORM\Column(name="guests", type="integer", nullable=true)
     */
    private $guests;
    
        /**
     * @var float
     * @ORM\Column(name="parkingdag", type="float", nullable=true)
     */
    private $parkingdag;
        /**
     * @var float
     * @ORM\Column(name="parking", type="float", nullable=true)
     */
    private $parking;
    
            /**
     * @var float
     * @ORM\Column(name="latecheckin", type="float", nullable=true)
     */
    private $latecheckin;
            /**
     * @var float
     * @ORM\Column(name="betalen", type="float", nullable=true)
     */
    private $betalen;
            /**
     * @var float
     * @ORM\Column(name="totalbetalen", type="float", nullable=true)
     */
    private $totalbetalen;
            /**
     * @var float
     * @ORM\Column(name="voldan", type="float", nullable=true)
     */
    private $voldan;
            /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;
            /**
     * @var text
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;
            /**
     * @var text
     * @ORM\Column(name="toer", type="text", nullable=true)
     */
    private $toer;

    /**
     * @var text
     * @ORM\Column(name="fromguesty", type="boolean", nullable=true)
     */
    private $fromguesty;    

    
                /**
     * @var float
     * @ORM\Column(name="balance", type="float", nullable=true)
     */
    private $balance;
              /**
     * @var float
     * @ORM\Column(name="totalpaid", type="float", nullable=true)
     */
    private $totalpaid;

                /**
     * @var float
     * @ORM\Column(name="borg", type="float", nullable=true)
     */
    private $borg;


    /**
     * @var float
     * @ORM\Column(name="sourceguesty", type="string", nullable=true)
     */
    private $sourceguesty;

    /**
     * @var text
     * @ORM\Column(name="readytoclear", type="boolean", nullable=true)
     */
    private $readytoclear;

    /**
     * @var text
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var text
     * @ORM\Column(name="timeforcheck", type="datetime", nullable=true)
     */
    private $timeforcheck;


    /**
     * @var boolean
     * @ORM\Column(name="blacklist", type="boolean", nullable=true)
     */
    private $blacklist;
    
    public function getId() {
        return $this->id;
    }
    
    public function setCheckin($v) {
        $this->checkin = $v;
        return $this;
    }
    
    public function getCheckin() {
        return $this->checkin;
    }
    
    
    public function setHotel($v) {
        $this->hotel = $v;
        return $this;
    }
    
    public function getHotel() {
        return $this->hotel;
    }
    
    
    public function setCheckindone($v) {
        $this->checkindone = $v;
        return $this;
    }
    
    public function getCheckindone() {
        return $this->checkindone;
    }
        
    public function setSource($v) {
        $this->source = $v;
        return $this;
    }
    
    public function getSource() {
        return $this->source;
    }
    
    public function setListing($v) {
        $this->listing = $v;
        return $this;
    }
    
    public function getListing() {
        return $this->listing;
    }
    
    public function setNights($v) {
        $this->nights = $v;
        return $this;
    }
    
    public function getNights() {
        return $this->nights;
    }
    
    public function setGuests($v) {
        $this->guests = $v;
        return $this;
    }
    
    public function getGuests() {
        return $this->guests;
    }
    
    public function setParkingdag($v) {
        $this->parkingdag = $v;
        return $this;
    }
    
    public function getParkingdag() {
        return $this->parkingdag;
    }
    
    public function setParking($v) {
        $this->parking = $v;
        return $this;
    }
    
    public function getParking() {
        return $this->parking;
    }
    
    public function setLatecheckin($v) {
        $this->latecheckin = $v;
        return $this;
    }
    
    public function getLatecheckin() {
        return $this->latecheckin;
    }
    
    public function setBetalen($v) {
        $this->betalen = $v;
        return $this;
    }
    
    public function getBetalen() {
        return $this->betalen;
    }
    
    public function setTotalbetalen($v) {
        $this->totalbetalen = $v;
        return $this;
    }
    
    public function getTotalbetalen() {
        return $this->totalbetalen;
    }
    
    public function setVoldan($v) {
        $this->voldan = $v;
        return $this;
    }
    
    public function getVoldan() {
        return $this->voldan;
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
    public function setToer($v) {
        $this->toer = $v;
        return $this;
    }
    
    public function getToer() {
        return $this->toer;
    }

    public function getFromguesty(){
        return $this->fromguesty;
    }

    public function setFromguesty($fromguesty){
        $this->fromguesty = $fromguesty;
        return $this;
    }

    public function getBorg(){
        return $this->borg;
    }

    public function setBorg($borg){
        $this->borg = $borg;
        return $this;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getTotalpaid()
    {
        return $this->totalpaid;
    }

    public function setTotalpaid($totalpaid)
    {
        $this->totalpaid = $totalpaid;
    }

    public function getSourceguesty()
    {
        return $this->sourceguesty;
    }

    public function setSourceguesty($sourceguesty)
    {
        $this->sourceguesty = $sourceguesty;
    }

    public function getReadytoclear()
    {
        return $this->readytoclear;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getTimeforcheck()
    {
        return $this->timeforcheck;
    }

    public function setTimeforcheck($timeforcheck)
    {
        $this->timeforcheck = $timeforcheck;
    }

    public function setReadytoclear($readytoclear)
    {
        $this->readytoclear = $readytoclear;
    }

    public function isBlacklist()
    {
        return $this->blacklist;
    }

    public function setBlacklist($blacklist)
    {
        $this->blacklist = $blacklist;
    }
}