<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Card
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Card {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="maestro", type="float", nullable = true)
     */
    private $maestro;

    /**
     * @var float
     *
     * @ORM\Column(name="alipay", type="float", nullable=true)
     */
    private $alipay;

    /**
     * @var float
     *
     * @ORM\Column(name="visa", type="float", nullable=true)
     */
    private $visa;

    /**
     * @var float
     *
     * @ORM\Column(name="visaelec", type="float", nullable=true)
     */
    private $visaelec;

    /**
     * @var float
     *
     * @ORM\Column(name="mastercard", type="float", nullable=true)
     */
    private $mastercard;

    /**
     * @var float
     *
     * @ORM\Column(name="american", type="float", nullable=true)
     */
    private $american;

    /**
     * @var float
     *
     * @ORM\Column(name="unionpay", type="float", nullable=true)
     */
    private $union;

    /**
     * @var float
     *
     * @ORM\Column(name="diners", type="float", nullable=true)
     */
    private $diners;

    /**
     * @var float
     *
     * @ORM\Column(name="vpay", type="float", nullable=true)
     */
    private $vpay;

    /**
     * @var float
     *
     * @ORM\Column(name="ccmaestro", type="float", nullable=true)
     */
    private $ccmaestro;

    /**
     * @var float
     *
     * @ORM\Column(name="ccalipay", type="float", nullable=true)
     */
    private $ccalipay;

    /**
     * @var float
     *
     * @ORM\Column(name="ccvisa", type="float", nullable=true)
     */
    private $ccvisa;

    /**
     * @var float
     *
     * @ORM\Column(name="ccvisaelec", type="float", nullable=true)
     */
    private $ccvisaelec;

    /**
     * @var float
     *
     * @ORM\Column(name="ccmastercard", type="float", nullable=true)
     */
    private $ccmastercard;

    /**
     * @var float
     *
     * @ORM\Column(name="ccamerican", type="float", nullable=true)
     */
    private $ccamerican;

    /**
     * @var float
     *
     * @ORM\Column(name="ccunion", type="float", nullable=true)
     */
    private $ccunion;

    /**
     * @var float
     *
     * @ORM\Column(name="ccdiners", type="float", nullable=true)
     */
    private $ccdiners;

    /**
     * @var float
     *
     * @ORM\Column(name="ccvpay", type="float", nullable=true)
     */
    private $ccvpay;
    
        /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;
        /**
     * @var float
     *
     * @ORM\Column(name="totalcc", type="float", nullable=true)
     */
    private $totalcc;
        /**
     * @var float
     *
     * @ORM\Column(name="profit", type="float", nullable=true)
     */
    private $profit;
        /**
     * @var float
     *
     * @ORM\Column(name="tcredit", type="float", nullable=true)
     */
    private $tcredit;
        /**
     * @var float
     *
     * @ORM\Column(name="tdebit", type="float", nullable=true)
     */
    private $tdebit;
        /**
     * @var float
     *
     * @ORM\Column(name="cctcredit", type="float", nullable=true)
     */
    private $cctcredit;
        /**
     * @var float
     *
     * @ORM\Column(name="cctdebit", type="float", nullable=true)
     */
    private $cctdebit;
        /**
     * @var boolean
     *
     * @ORM\Column(name="iscc", type="boolean", nullable=true)
     */
    private $iscc;

    //------------------------------------------------------------------
    /**
     * @var float
     *
     * @ORM\Column(name="exmaestro", type="float", nullable = true)
     */
    private $exmaestro;

   /**
     * @var float
     *
     * @ORM\Column(name="exvisa", type="float", nullable=true)
     */
    private $exvisa;

    /**
     * @var float
     *
     * @ORM\Column(name="exvisaelec", type="float", nullable=true)
     */
    private $exvisaelec;

    /**
     * @var float
     *
     * @ORM\Column(name="exmastercard", type="float", nullable=true)
     */
    private $exmastercard;

    /**
     * @var float
     *
     * @ORM\Column(name="examerican", type="float", nullable=true)
     */
    private $examerican;

    /**
     * @var float
     *
     * @ORM\Column(name="exunionpay", type="float", nullable=true)
     */
    private $exunion;

    /**
     * @var float
     *
     * @ORM\Column(name="exdiners", type="float", nullable=true)
     */
    private $exdiners;

    /**
     * @var float
     *
     * @ORM\Column(name="exvpay", type="float", nullable=true)
     */
    private $exvpay;

    /**
     * @var float
     *
     * @ORM\Column(name="extotal", type="float", nullable=true)
     */
    private $extotal;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function setMaestro($maestro) {
        $this->maestro = $maestro;
        return $this;
    }

    public function getMaestro() {
        return $this->maestro;
    }
    
    public function setCcmaestro($ccmaestro) {
        $this->ccmaestro = $ccmaestro;
        return $this;
    }

    public function getCcmaestro() {
        return $this->ccmaestro;
    }
    
    public function setAlipay($alipay) {
        $this->alipay = $alipay;
        return $this;
    }

    public function getAlipay() {
        return $this->alipay;
    }
    
    public function setCcalipay($ccalipay) {
        $this->ccalipay = $ccalipay;
        return $this;
    }

    public function getCcalipay() {
        return $this->ccalipay;
    }
    
    public function setVisa($visa) {
        $this->visa = $visa;
        return $this;
    }

    public function getVisa() {
        return $this->visa;
    }
    
    public function setCcvisa($ccvisa) {
        $this->ccvisa = $ccvisa;
        return $this;
    }

    public function getCcvisa() {
        return $this->ccvisa;
    }
    
    public function setVisaelec($visaelec) {
        $this->visaelec = $visaelec;
        return $this;
    }

    public function getVisaelec() {
        return $this->visaelec;
    }
    
    public function setCcvisaelec($ccvisaelec) {
        $this->ccvisaelec = $ccvisaelec;
        return $this;
    }

    public function getCcvisaelec() {
        return $this->ccvisaelec;
    }

    public function setMastercard($mastercard) {
        $this->mastercard = $mastercard;
        return $this;
    }

    public function getMastercard() {
        return $this->mastercard;
    }
    
    public function setCcmastercard($ccmastercard) {
        $this->ccmastercard = $ccmastercard;
        return $this;
    }

    public function getCcmastercard() {
        return $this->ccmastercard;
    }
    
    public function setAmerican($american) {
        $this->american = $american;
        return $this;
    }

    public function getAmerican() {
        return $this->american;
    }
    
    public function setCcamerican($ccamerican) {
        $this->ccamerican = $ccamerican;
        return $this;
    }

    public function getCcamerican() {
        return $this->ccamerican;
    }
    
    public function setUnion($union) {
        $this->union = $union;
        return $this;
    }

    public function getUnion() {
        return $this->union;
    }
    
    public function setCcunion($ccunion) {
        $this->ccunion = $ccunion;
        return $this;
    }

    public function getCcunion() {
        return $this->ccunion;
    }
    
    public function setDiners($diners) {
        $this->diners = $diners;
        return $this;
    }

    public function getDiners() {
        return $this->diners;
    }
    
    public function setCcdiners($ccdiners) {
        $this->ccdiners = $ccdiners;
        return $this;
    }

    public function getCcdiners() {
        return $this->ccdiners;
    }
    
    public function setVpay($vpay) {
        $this->vpay = $vpay;
        return $this;
    }

    public function getVpay() {
        return $this->vpay;
    }
    
    public function setCcvpay($ccvpay) {
        $this->ccvpay = $ccvpay;
        return $this;
    }

    public function getCcvpay() {
        return $this->ccvpay;
    }
    
    public function setTotal($total) {
        $this->total = $total;
        return $this;
    }

    public function getTotal() {
        return $this->total;
    }
    
    public function setTotalcc($totalcc) {
        $this->totalcc = $totalcc;
        return $this;
    }

    public function getTotalcc() {
        return $this->totalcc;
    }
    
    public function setTdebit($tdebit) {
        $this->tdebit = $tdebit;
        return $this;
    }

    public function getTdebit() {
        return $this->tdebit;
    }
    
    public function setTcredit($tcredit) {
        $this->tcredit = $tcredit;
        return $this;
    }

    public function getTcredit() {
        return $this->tcredit;
    }
    
    public function setCctdebit($cctdebit) {
        $this->cctdebit = $cctdebit;
        return $this;
    }

    public function getCctdebit() {
        return $this->cctdebit;
    }
    
    public function setCctcredit($cctcredit) {
        $this->cctcredit = $cctcredit;
        return $this;
    }

    public function getCctcredit() {
        return $this->cctcredit;
    }
    
    public function setProfit($profit) {
        $this->profit = $profit;
        return $this;
    }

    public function getProfit() {
        return $this->profit;
    }
    
    public function setIscc($iscc) {
        $this->iscc = $iscc;
        return $this;
    }

    public function getIscc() {
        return $this->iscc;
    }

    public function setExmaestro($maestro) {
        $this->exmaestro = $maestro;
        return $this;
    }

    public function getExmaestro() {
        return $this->exmaestro;
    }

    public function setExvisa($visa) {
        $this->exvisa = $visa;
        return $this;
    }

    public function getExvisa() {
        return $this->exvisa;
    }

    public function getExamerican()
    {
        return $this->examerican;
    }

    public function setExamerican($examerican)
    {
        $this->examerican = $examerican;
        return $this;
    }

    public function getExdiners()
    {
        return $this->exdiners;
    }

    public function setExdiners($exdiners)
    {
        $this->exdiners = $exdiners;
        return $this;
    }

    public function getExmastercard()
    {
        return $this->exmastercard;
    }

    public function setExmastercard($exmastercard)
    {
        $this->exmastercard = $exmastercard;
        return $this;
    }

    public function getExunion()
    {
        return $this->exunion;
    }

    public function setExunion($exunion)
    {
        $this->exunion = $exunion;
        return $this;
    }

    public function getExvisaelec()
    {
        return $this->exvisaelec;
    }

    public function setExvisaelec($exvisaelec)
    {
        $this->exvisaelec = $exvisaelec;
        return $this;
    }

    public function getExvpay()
    {
        return $this->exvpay;
    }

    public function setExvpay($exvpay)
    {
        $this->exvpay = $exvpay;
        return $this;
    }

    public function getExtotal()
    {
        return $this->extotal;
    }

    public function setExtotal($extotal)
    {
        $this->extotal = $extotal;
        return $this;
    }
}
