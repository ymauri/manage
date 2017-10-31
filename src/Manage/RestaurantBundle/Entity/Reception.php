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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="details", type="text")
     */
    private $details;

    /**
     * @var \DateTime
     * @ORM\Column(name="dated", type="date") 
     */
    private $dated;

    /**
     * @var object
     * @ORM\ManyToOne(targetEntity="ReceptionVoucher")
     */
    private $voucher;

    /**
     * @var integer
     * @ORM\Column(name="vdstart", type="integer")
     */
    private $vdstart;
    
    /**
     * @var integer
     * @ORM\Column(name="vdend", type="integer")
     */
    private $vdend;
    
    /**
     * @var integer
     * @ORM\Column(name="vdamount", type="integer")
     */
    private $vdamount;
    
        /**
         * @var integer
     * @ORM\Column(name="vnstart", type="integer")
     */
    private $vnstart;
    
    /**
     * @var integer
     * @ORM\Column(name="vnend", type="integer")
     */
    private $vnend;
    
    /**
     * @var integer
     * @ORM\Column(name="vnamount", type="integer")
     */
    private $vnamount;
    
    /**
     * @var integer
     * @ORM\Column(name="freevoucher", type="integer")
     */
    private $freevoucher;
    
    /**
     * @var float
     * @ORM\Column(name="genralamount", type="float")
     */
    private $generalamount;
    
    /**
     * @var integer
     * @ORM\Column(name="halfprice", type="integer")
     */
    private $halfprice;
    
    /**
     * @var float
     * @ORM\Column(name="profit", type="float")
     */
    private $profit;
    
    /**
     * @var array
     * @ORM\Column(name="giftvouchers", type="array")
     */
    private $giftvouchers;
    
    /**
     * @var array
     * @ORM\Column(name="giftvouchersvalues", type="array")
     */
    private $giftvouchersvalues;
    
     /**
     * @var array
     * @ORM\Column(name="parcking", type="array")
     */
    private $parking;
    
         /**
     * @var array
     * @ORM\Column(name="parckingvalues", type="array")
     */
    private $parkingvalues;
    
     /**
     * @var float
     * @ORM\Column(name="giftvoucherstotal", type="float")
     */
    private $giftvoucherstotal;

    /**
     * @var float
     * @ORM\Column(name="parkingtotal", type="float")
     */
    private $parkingtotal;

    /**
     * @var float
     * @ORM\Column(name="othersales", type="float")
     */
    private $othersales;

        /**
     * @var float
     * @ORM\Column(name="completeprofit", type="float")
     */
    private $completeprofit;
    
    /* Get id
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /* Set details
     * @param string $name
     * @return Reception
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /* Get details
     * @return string 
     */
    public function getName() {
        return $this->name;
    }
    
    /* Set details
     * @param string $details
     * @return CashClosure
     */
    public function setDetails($details) {
        $this->details = $details;

        return $this;
    }

    /* Get details
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

    /* @return float 
     */
    public function getHalfprice() {
        return $this->halfprice;
    }

    /* @param float $genralamount
     * @return object
     */
    public function setHalfprice($halfprice) {
        $this->halfprice = $halfprice;
        return $this;
    }
    
     /* @return float 
     */
    public function getGeneralamount() {
        return $this->generalamount;
    }

    /* @param float $genralamount
     * @return object
     */
    public function setGeneralamount($genralamount) {
        $this->generalamount = $genralamount;
        return $this;
    }

     /* @return float 
     */
    public function getProfit() {
        return $this->profit;
    }

    /* @param float $profit
     * @return object
     */
    public function setProfit($profit) {
        $this->profit = $profit;
        return $this;
    }
    
    /* @return integer 
     */
    public function getFreevoucher() {
        return $this->freevoucher;
    }

    /* @param integer $freevoucher
     * @return object
     */
    public function setFreevoucher($freevoucher) {
        $this->freevoucher = $freevoucher;
        return $this;
    }
    
     /* @return object 
     */
    public function getVoucher() {
        return $this->voucher;
    }

    /* @param object $voucher
     * @return object
     */
    public function setVoucher($voucher) {
        $this->voucher = $voucher;
        return $this;
    }
    
      /* @return integer 
     */
    public function getVdstart() {
        return $this->vdstart;
    }

    /* @param integer $vdstart
     * @return object
     */
    public function setVdstart($vdstart) {
        $this->vdstart = $vdstart;
        return $this;
    }
    
          /* @return integer 
     */
    public function getVdend() {
        return $this->vdend;
    }

    /* @param integer $vdend
     * @return object
     */
    public function setVdend($vdend) {
        $this->vdend = $vdend;
        return $this;
    }
    
     /* @return integer 
     */
    public function getVdamount() {
        return $this->vdamount;
    }

    /* @param integer $vdamount
     * @return object
     */
    public function setVdamount($vdamount) {
        $this->vdamount = $vdamount;
        return $this;
    }
    
          /* @return integer 
     */
    public function getVnstart() {
        return $this->vnstart;
    }

    /* @param integer $vnstart
     * @return object
     */
    public function setVnstart($vnstart) {
        $this->vnstart = $vnstart;
        return $this;
    }
    
          /* @return integer 
     */
    public function getVnend() {
        return $this->vnend;
    }

    /* @param integer $vnend
     * @return object
     */
    public function setVnend($vnend) {
        $this->vnend = $vnend;
        return $this;
    }
    
     /* @return integer 
     */
    public function getVnamount() {
        return $this->vnamount;
    }

    /* @param integer $vnamount
     * @return object
     */
    public function setVnamount($vnamount) {
        $this->vnamount = $vnamount;
        return $this;
    }
    
         /* @return array 
     */
    public function getGiftvouchers() {
        return $this->giftvouchers;
    }

    /* @param array $giftvouchers
     * @return object
     */
    public function setGiftvouchers($giftvouchers) {
        $this->giftvouchers = $giftvouchers;
        return $this;
    }
    
             /* @return array 
     */
    public function getGiftvouchersvalues() {
        return $this->giftvouchersvalues;
    }

    /* @param array $giftvouchersvalues
     * @return object
     */
    public function setGiftvouchersvalues($giftvouchersvalues) {
        $this->giftvouchersvalues = $giftvouchersvalues;
        return $this;
    }
    
             /* @return array 
     */
    public function getParking() {
        return $this->parking;
    }

    /* @param array $parking
     * @return object
     */
    public function setParking($parking) {
        $this->parking = $parking;
        return $this;
    }
    
             /* @return array 
     */
    public function getParkingvalues() {
        return $this->parkingvalues;
    }

    /* @param array $parkingvalues
     * @return object
     */
    public function setParkingvalues($parkingvalues) {
        $this->parkingvalues = $parkingvalues;
        return $this;
    }
    
                 /* @return float 
     */
    public function getGiftvoucherstotal() {
        return $this->giftvoucherstotal;
    }

    /* @param float $giftvoucherstotal
     * @return object
     */
    public function setGiftvoucherstotal($giftvoucherstotal) {
        $this->giftvoucherstotal = $giftvoucherstotal;
        return $this;
    }
    
    /* @return float 
     */
    public function getParkingtotal() {
        return $this->parkingtotal;
    }

    /* @param float $parkingtotal
     * @return object
     */
    public function setParkingtotal($parkingtotal) {
        $this->parkingtotal = $parkingtotal;
        return $this;
    }
    
        /* @return float 
     */
    public function getCompleteprofit() {
        return $this->completeprofit;
    }

    /* @param float $completeprofit
     * @return object
     */
    public function setCompleteprofit($completeprofit) {
        $this->completeprofit = $completeprofit;
        return $this;
    }
    
            /* @return float 
     */
    public function getOthersales() {
        return $this->othersales;
    }

    /* @param float $othersales
     * @return object
     */
    public function setOthersales($othersales) {
        $this->othersales = $othersales;
        return $this;
    }
    
    public function __toString() {
        return $this->dated.' '.$this->name;
    }

}
