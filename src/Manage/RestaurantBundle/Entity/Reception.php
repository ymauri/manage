<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Reception
 * @ORM\Table()
 * @ORM\Entity()
 */
class Reception {

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
     * @ORM\Column(name="voucher", type="integer", nullable=true)
     */
    private $voucher;

    /**
     * @var integer
     * @ORM\Column(name="vdstart", type="integer", nullable=true)
     */
    private $vdstart;
    
    /**
     * @var integer
     * @ORM\Column(name="vdend", type="integer", nullable=true)
     */
    private $vdend;
    
    /**
     * @var integer
     * @ORM\Column(name="vdamount", type="integer", nullable=true)
     */
    private $vdamount;
    
        /**
         * @var integer
     * @ORM\Column(name="vnstart", type="integer",nullable=true)
     */
    private $vnstart;
    
    /**
     * @var integer
     * @ORM\Column(name="vnend", type="integer", nullable=true)
     */
    private $vnend;
    
    /**
     * @var integer
     * @ORM\Column(name="vnamount", type="integer", nullable=true)
     */
    private $vnamount;
    
    /**
     * @var integer
     * @ORM\Column(name="freevoucher", type="integer", nullable=true)
     */
    private $freevoucher;
    
    /**
     * @var float
     * @ORM\Column(name="genralamount", type="float", nullable=true)
     */
    private $generalamount;
    
    /**
     * @var integer
     * @ORM\Column(name="halfprice", type="integer", nullable=true)
     */
    private $halfprice;
    
    /**
     * @var float
     * @ORM\Column(name="profit", type="float", nullable=true)
     */
    private $profit;
    
    /**
     * @var array
     * @ORM\Column(name="giftvouchers", type="array", nullable=true)
     */
    private $giftvouchers;
    
    /**
     * @var array
     * @ORM\Column(name="giftvouchersvalues", type="array", nullable=true)
     */
    private $giftvouchersvalues;
    
     /**
     * @var array
     * @ORM\Column(name="parcking", type="array", nullable=true)
     */
    private $parking;
    
         /**
     * @var array
     * @ORM\Column(name="parckingvalues", type="array", nullable=true)
     */
    private $parkingvalues;
    
     /**
     * @var float
     * @ORM\Column(name="giftvoucherstotal", type="float", nullable=true)
     */
    private $giftvoucherstotal;

    /**
     * @var float
     * @ORM\Column(name="parkingtotal", type="float", nullable=true)
     */
    private $parkingtotal;

    /**
     * @var float
     * @ORM\Column(name="othersales", type="float", nullable=true)
     */
    private $othersales;

        /**
     * @var float
     * @ORM\Column(name="completeprofit", type="float", nullable=true)
     */
    private $completeprofit;
    
        /**
     * @var object
     * @ORM\OneToOne(targetEntity="Bill", cascade={"persist"})
     */
    private $bill;
    
            /**
     * @var object
     * @ORM\OneToOne(targetEntity="BeginBill", cascade={"persist"})
     */
    private $beginbill;
    
                /**
     * @var object
     * @ORM\OneToOne(targetEntity="Card", cascade={"persist"})
     */
    private $card;
    
    /**
     * @var array
     * @ORM\Column(name="voorgeschoten", type="array", nullable=true)
     */
    private $voorgeschoten;
    
     /**
     * @var float
     * @ORM\Column(name="voorgeschotentotal", type="float", nullable=true)
     */
    private $voorgeschotentotal;
    
         /**
     * @var float
     * @ORM\Column(name="ontvangen", type="float", nullable=true)
     */
    private $ontvangen ;
   
             /**
     * @var float
     * @ORM\Column(name="kasverschil", type="float", nullable=true)
     */
    private $kasverschil;
    
                 /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\AdminBundle\Entity\Worker")
     */
    private $userdag;
    
                 /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\AdminBundle\Entity\Worker")
     */
    private $useravond;

                 /**
     * @var string
     * @ORM\Column(name="notify", type="string", nullable=true)
     */
    private $notify;
    
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
     * @var float
     * @ORM\Column(name="time", type="time", nullable=true)
     */
    private $time;
    
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

    public function getHalfprice() {
        return $this->halfprice;
    }

    public function setHalfprice($halfprice) {
        $this->halfprice = $halfprice;
        return $this;
    }
 
    public function getGeneralamount() {
        return $this->generalamount;
    }

    public function setGeneralamount($genralamount) {
        $this->generalamount = $genralamount;
        return $this;
    }

    public function getProfit() {
        return $this->profit;
    }

    public function setProfit($profit) {
        $this->profit = $profit;
        return $this;
    }
 
    public function getFreevoucher() {
        return $this->freevoucher;
    }

    public function setFreevoucher($freevoucher) {
        $this->freevoucher = $freevoucher;
        return $this;
    }
    
    public function getVoucher() {
        return $this->voucher;
    }

    public function setVoucher($voucher) {
        $this->voucher = $voucher;
        return $this;
    }
    
    public function getVdstart() {
        return $this->vdstart;
    }

    public function setVdstart($vdstart) {
        $this->vdstart = $vdstart;
        return $this;
    }
    
    public function getVdend() {
        return $this->vdend;
    } 
    
    public function setVdend($vdend) {
        $this->vdend = $vdend;
        return $this;
    } 
    
    public function getVdamount() {
        return $this->vdamount;
    }
 
    public function setVdamount($vdamount) {
        $this->vdamount = $vdamount;
        return $this;
    }
     
    public function getVnstart() {
        return $this->vnstart;
    } 
    
    public function setVnstart($vnstart) {
        $this->vnstart = $vnstart;
        return $this;
    }
     
    public function getVnend() {
        return $this->vnend;
    }
 
    public function setVnend($vnend) {
        $this->vnend = $vnend;
        return $this;
    }
     
    public function getVnamount() {
        return $this->vnamount;
    }
 
    public function setVnamount($vnamount) {
        $this->vnamount = $vnamount;
        return $this;
    }
     
    public function getGiftvouchers() {
        return $this->giftvouchers;
    }
 
    public function setGiftvouchers($giftvouchers) {
        $this->giftvouchers = $giftvouchers;
        return $this;
    }
     
    public function getGiftvouchersvalues() {
        return $this->giftvouchersvalues;
    }
 
    public function setGiftvouchersvalues($giftvouchersvalues) {
        $this->giftvouchersvalues = $giftvouchersvalues;
        return $this;
    }
     
    public function getParking() {
        return $this->parking;
    }
 
    public function setParking($parking) {
        $this->parking = $parking;
        return $this;
    }
     
    public function getParkingvalues() {
        return $this->parkingvalues;
    }
 
    public function setParkingvalues($parkingvalues) {
        $this->parkingvalues = $parkingvalues;
        return $this;
    }
     
    public function getGiftvoucherstotal() {
        return $this->giftvoucherstotal;
    }
 
    public function setGiftvoucherstotal($giftvoucherstotal) {
        $this->giftvoucherstotal = $giftvoucherstotal;
        return $this;
    }
     
    public function getParkingtotal() {
        return $this->parkingtotal;
    }
 
    public function setParkingtotal($parkingtotal) {
        $this->parkingtotal = $parkingtotal;
        return $this;
    }
     
    public function getCompleteprofit() {
        return $this->completeprofit;
    }
 
    public function setCompleteprofit($completeprofit) {
        $this->completeprofit = $completeprofit;
        return $this;
    }
     
    public function getOthersales() {
        return $this->othersales;
    }
 
    public function setOthersales($othersales) {
        $this->othersales = $othersales;
        return $this;
    }
    
    public function getBill() {
        return $this->bill;
    }

    public function setBill($bill) {
        $this->bill = $bill;

        return $this;
    }
    
    public function getBeginbill() {
        return $this->beginbill;
    }

    public function setBeginbill($beginbill) {
        $this->beginbill = $beginbill;
        return $this;
    }
    
    public function getCard() {
        return $this->card;
    }

    public function setCard($card) {
        $this->card = $card;
        return $this;
    }
    
    public function getVoorgeschoten () {
        return $this->voorgeschoten ;
    }

    public function setVoorgeschoten ($voorgeschoten ) {
        $this->voorgeschoten  = $voorgeschoten ;
        return $this;
    }
    
    public function getVoorgeschotentotal () {
        return $this->voorgeschotentotal;
    }

    public function setVoorgeschotentotal($voorgeschotentotal) {
        $this->voorgeschotentotal = $voorgeschotentotal;
        return $this;
    }
    
    public function getOntvangen () {
        return $this->ontvangen;
    }

    public function setOntvangen($ontvangen) {
        $this->ontvangen = $ontvangen;
        return $this;
    }
    
    public function getKasverschil () {
        return $this->kasverschil;
    }

    public function setKasverschil($kasverschil) {
        $this->kasverschil = $kasverschil;
        return $this;
    }
    
    public function getUserdag () {
        return $this->userdag;
    }

    public function setUserdag($userdag) {
        $this->userdag = $userdag;
        return $this;
    }
    
    public function getUseravond () {
        return $this->useravond;
    }

    public function setUseravond($useravond) {
        $this->useravond = $useravond;
        return $this;
    }
    
    public function getNotify () {
        return $this->notify;
    }

    public function setNotify($notify) {
        $this->notify = $notify;
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

    public function getTime(){
        return $this->time;
    }

    public function setTime($time){
        $this->time = $time;
        return $this;
    }
    
    public function __toString() {
        return $this->dated.' '.$this->name;
    }

    
}
