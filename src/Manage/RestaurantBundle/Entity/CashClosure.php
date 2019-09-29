<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * CashClosure
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class CashClosure {

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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dated", type="date")
     * 
     */
    private $dated;

    /**
     * @var object
     * @ORM\OneToOne(targetEntity="CashClosureTotal", cascade={"persist"})
     */
    private $total;

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
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Worker")
     */
    private $userdag;
    
                 /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Worker")
     */
    private $useravond;


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
     * @var float
     * @ORM\Column(name="giftvoucherstotal", type="float", nullable=true)
     */
    private $giftvoucherstotal;
    
        /**
     * @var array
     * @ORM\Column(name="extvouchers", type="array", nullable=true)
     */
    private $extvouchers;
    
    /**
     * @var array
     * @ORM\Column(name="extvouchersvalues", type="array", nullable=true)
     */
    private $extvouchersvalues;
    
         /**
     * @var float
     * @ORM\Column(name="voucherstotal", type="float", nullable=true)
     */
    private $voucherstotal;
    
          /**
     * @var float
     * @ORM\Column(name="belevoucherstotal", type="float", nullable=true)
     */
    private $belevoucherstotal;
    
             /**
     * @var float
     * @ORM\Column(name="kadovoucherstotal", type="float", nullable=true)
     */
    private $kadovoucherstotal;
    
                 /**
     * @var float
     * @ORM\Column(name="ticketvoucherstotal", type="float", nullable=true)
     */
    private $ticketvoucherstotal;
    
             /**
     * @var integer
     * @ORM\Column(name="skydag", type="integer", nullable=true)
     */
    private $skydag;
             /**
     * @var integer
     * @ORM\Column(name="skyavond", type="integer", nullable=true)
     */
    private $skyavond;
    
             /**
     * @var float
     * @ORM\Column(name="skymoneydag", type="float", nullable=true)
     */
    private $skymoneydag;
             /**
     * @var float
     * @ORM\Column(name="skymoneyavond", type="float", nullable=true)
     */
    private $skymoneyavond;
             /**
     * @var float
     * @ORM\Column(name="skymeandag", type="float", nullable=true)
     */
    private $skymeandag;
             /**
     * @var float
     * @ORM\Column(name="skymeanavond", type="float", nullable=true)
     */
    private $skymeanavond;
    
                 /**
     * @var integer
     * @ORM\Column(name="skytotal", type="integer", nullable=true)
     */
    private $skytotal;
    
                     /**
     * @var float
     * @ORM\Column(name="skymoneytotal", type="float", nullable=true)
     */
    private $skymoneytotal;
    
        
                     /**
     * @var float
     * @ORM\Column(name="skymeantotal", type="float", nullable=true)
     */
    private $skymeantotal;
    
                         /**
     * @var float
     * @ORM\Column(name="voorverkoop", type="array", nullable=true)
     */
    private $voorverkoop;
    
                         /**
     * @var float
     * @ORM\Column(name="rekening", type="array", nullable=true)
     */
    private $rekening;
    
                             /**
     * @var float
     * @ORM\Column(name="voorverkooptotal", type="float", nullable=true)
     */
    private $voorverkooptotal;
    
                         /**
     * @var float
     * @ORM\Column(name="rekeningtotal", type="float", nullable=true)
     */
    private $rekeningtotal;
    
                             /**
     * @var float
     * @ORM\Column(name="time", type="time", nullable=true)
     */
    private $time;
    
    /**
     * @var float
     * @ORM\Column(name="completeprofit", type="float", nullable=true)
     */
    private $completeprofit;

    /**
     * @var float
     * @ORM\Column(name="voucherday", type="integer", nullable=true)
     */
    private $voucherday;
    
    /**
     * @var float
     * @ORM\Column(name="vouchernight", type="integer", nullable=true)
     */
    private $vouchernight;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CashClosure
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
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
     * Get total
     *
     * @return object 
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Set total
     *
     * @param object $total
     * @return ClashClosureTotal
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get bill
     *
     * @return object 
     */
    public function getBill() {
        return $this->bill;
    }

    /**
     * Set bill
     *
     * @param object $bill
     * @return Bill
     */
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
    
    public function getCard() {
        return $this->card;
    }

    public function setCard($card) {
        $this->card = $card;
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
    public function getGiftvoucherstotal() {
        return $this->giftvoucherstotal;
    }
 
    public function setGiftvoucherstotal($giftvoucherstotal) {
        $this->giftvoucherstotal = $giftvoucherstotal;
        return $this;
    } 
    
    public function getExtvouchers() {
        return $this->extvouchers;
    }
 
    public function setExtvouchers($extvouchers) {
        $this->extvouchers = $extvouchers;
        return $this;
    }
     
    public function getExtvouchersvalues() {
        return $this->extvouchersvalues;
    }
 
    public function setExtvouchersvalues($extvouchersvalues) {
        $this->extvouchersvalues = $extvouchersvalues;
        return $this;
    }
    public function getVoucherstotal() {
        return $this->voucherstotal;
    }
 
    public function setVoucherstotal($voucherstotal) {
        $this->voucherstotal = $voucherstotal;
        return $this;
    } 
    
    public function getBelevoucherstotal() {
        return $this->belevoucherstotal;
    }
 
    public function setBelevoucherstotal($voucherstotal) {
        $this->belevoucherstotal = $voucherstotal;
        return $this;
    }
    
    public function getKadovoucherstotal() {
        return $this->kadovoucherstotal;
    }
 
    public function setKadovoucherstotal($voucherstotal) {
        $this->kadovoucherstotal = $voucherstotal;
        return $this;
    } 
    
    public function getTicketvoucherstotal() {
        return $this->ticketvoucherstotal;
    }
 
    public function setTicketvoucherstotal($voucherstotal) {
        $this->ticketvoucherstotal= $voucherstotal;
        return $this;
    } 
    
    public function getSkydag() {
        return $this->skydag;
    }
    public function setSkydag($sky) {
        $this->skydag = $sky;
        return $this;
    }
    
    public function getSkyavond() {
        return $this->skyavond;
    }
    public function setSkyavond($sky) {
        $this->skyavond = $sky;
        return $this;
    } 
    
    public function getSkymoneydag() {
        return $this->skymoneydag;
    }
    public function setSkymoneydag($sky) {
        $this->skymoneydag = $sky;
        return $this;
    }
    
    
    public function getSkymoneyavond() {
        return $this->skymoneyavond;
    }
    public function setSkymoneyavond($sky) {
        $this->skymoneyavond = $sky;
        return $this;
    } 
    
    public function getSkymeandag() {
        return $this->skymeandag;
    }
    public function setSkymeandag($sky) {
        $this->skymeandag = $sky;
        return $this;
    }
    
    public function getSkymeanavond() {
        return $this->skymeanavond;
    }
    public function setSkymeanavond($sky) {
        $this->skymeanavond = $sky;
        return $this;
    } 
    
    public function getSkytotal() {
        return $this->skytotal;
    }
    public function setSkytotal($sky) {
        $this->skytotal = $sky;
        return $this;
    }
    
    public function getSkymeantotal() {
        return $this->skymeantotal;
    }
    public function setSkymeantotal($sky) {
        $this->skymeantotal = $sky;
        return $this;
    } 
    public function getSkymoneytotal() {
        return $this->skymoneytotal;
    }
    public function setSkymoneytotal($sky) {
        $this->skymoneytotal = $sky;
        return $this;
    } 
   
    public function getVoorgeschoten () {
        return $this->voorgeschoten ;
    }

    public function setVoorgeschoten ($voorgeschoten ) {
        $this->voorgeschoten  = $voorgeschoten ;
        return $this;
    }
    
    public function getRekening () {
        return $this->rekening ;
    }

    public function setRekening  ($r ) {
        $this->rekening  = $r ;
        return $this;
    }
    
    public function getVoorverkoop () {
        return $this->voorverkoop ;
    }

    public function setVoorverkoop ($r ) {
        $this->voorverkoop  = $r ;
        return $this;
    }
    
    public function getRekeningtotal () {
        return $this->rekeningtotal ;
    }

    public function setRekeningtotal  ($r ) {
        $this->rekeningtotal  = $r ;
        return $this;
    }
    
    public function getVoorverkooptotal () {
        return $this->voorverkooptotal ;
    }

    public function setVoorverkooptotal ($r ) {
        $this->voorverkooptotal  = $r ;
        return $this;
    }
    
        
    public function getCompleteprofit () {
        return $this->completeprofit ;
    }

    public function setCompleteprofit ($r ) {
        $this->completeprofit  = $r ;
        return $this;
    }
    
    public function getTime () {
        return $this->time ;
    }

    public function setTime ($r ) {
        $this->time  = $r ;
        return $this;
    }   
    
    public function getVoucherday () {
        return $this->voucherday ;
    }

    public function setVoucherday ($r ) {
        $this->voucherday  = $r ;
        return $this;
    }    
    
    public function getVouchernight () {
        return $this->vouchernight ;
    }

    public function setVouchernight ($r ) {
        $this->vouchernight  = $r ;
        return $this;
    }
    
    //Total credit card
    public function TotalCreditCard(){
        return  $this->card->getVisa()+$this->card->getCcvisa()+$this->card->getExvisa()
                +$this->card->getVisaelec()+$this->card->getCcvisaelec()+$this->card->getExvisaelec()
                +$this->card->getMastercard()+$this->card->getCcmastercard()+$this->card->getExmastercard()
                +$this->card->getAmerican()+$this->card->getCcamerican()+$this->card->getExamerican()
                +$this->card->getUnion()+$this->card->getCcunion()+$this->card->getExunion()
                +$this->card->getDiners()+$this->card->getCcdiners()+$this->card->getExdiners();
    }
    
    //Total debit card
    public function TotalDebitCard(){
        return  $this->card->getMaestro()+$this->card->getCcmaestro()+$this->card->getExmaestro()
                +$this->card->getVpay()+$this->card->getCcvpay()+$this->card->getExvpay();
    }
    
    
    public function __toString() {
        return $this->dated.' '.$this->name;
    }
}
