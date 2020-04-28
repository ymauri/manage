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
class KasboekHotel {

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
     * @var float
     * @ORM\Column(name="overnachtingen", type="float", nullable=true)
     */
    private $overnachtingen;

    /**
     * @var float
     * @ORM\Column(name="parking", type="float", nullable=true)
     */
    private $parking;

    /**
     * @var float
     * @ORM\Column(name="toeristenbelasting", type="float", nullable=true)
     */
    private $toeristenbelasting;

    /**
     * @var float
     * @ORM\Column(name="totaalont", type="float", nullable=true)
     */
    private $totaalont;
    /**
     * @var float
     * @ORM\Column(name="totaalborg", type="float", nullable=true)
     */
    private $totaalborg;

    /**
     * @var float
     * @ORM\Column(name="contanten", type="float", nullable=true)
     */
    private $contanten;

    /**
     * @var float
     * @ORM\Column(name="debit", type="float", nullable=true)
     */
    private $debit;

    /**
     * @var float
     * @ORM\Column(name="credit", type="float", nullable=true)
     */
    private $credit;

    /**
     * @var float
     * @ORM\Column(name="totaalnaar", type="float", nullable=true)
     */
    private $totaalnaar;

    /**
     * @var float
     * @ORM\Column(name="kasverschil", type="float", nullable=true)
     */
    private $kasverschil ;

    /**
     * @var object
     * @ORM\OneToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekHotelFloat", cascade={"persist"})
     */
    private $float;

    /**
     * @var float
     * @ORM\Column(name="longstay", type="float", nullable=true)
     */
    private $longstay;
    
    /**
    * @var float
    * @ORM\Column(name="stripeguesty", type="float", nullable=true)
    */
   private $stripeguesty;

   /**
   * @var float
   * @ORM\Column(name="stripeinvoice", type="float", nullable=true)
   */
   private $stripeinvoice;

   /**
   * @var float
   * @ORM\Column(name="bank", type="float", nullable=true)
   */
   private $bank;

   /**
   * @var float
   * @ORM\Column(name="airbnb", type="float", nullable=true)
   */
   private $airbnb;   

   /**
   * @var float
   * @ORM\Column(name="omzet", type="float", nullable=true)
   */
  private $omzet;

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

    /*public function getIn () {
        return $this->in;
    }

    public function setIn($v) {
        $this->in = $v;
        return $this;
    }*/

    public function __toString() {
        return $this->dated.' '.$this->name;
    }

    public function getOvernachtingen(){
        return $this->overnachtingen;
    }

    public function setOvernachtingen($v){
        $this->overnachtingen = $v;
        return $this;
    }

    public function getParking(){
        return $this->parking;
    }

    public function setParking($v){
        $this->parking = $v;
        return $this;
    }

    public function getToeristenbelasting(){
        return $this->toeristenbelasting;
    }

    public function setToeristenbelasting($v){
        $this->toeristenbelasting = $v;
        return $this;
    }

    public function getTotaalont(){
        return $this->totaalont;
    }

    public function setTotaalont($v){
        $this->totaalont = $v;
        return $this;
    }

    public function getContanten(){
        return $this->contanten;
    }

    public function setContanten($v){
        $this->contanten = $v;
        return $this;
    }

    public function getCredit(){
        return $this->credit;
    }

    public function setCredit($v){
        $this->credit = $v;
        return $this;
    }

    public function getTotaalnaar(){
        return $this->totaalnaar;
    }

    public function setTotaalnaar($v){
        $this->totaalnaar = $v;
        return $this;
    }
    public function getDebit(){
        return $this->debit;
    }

    public function setDebit($v){
        $this->debit = $v;
        return $this;
    }

    public function getKasverschil(){
        return $this->kasverschil;
    }

    public function setKasverschil($kasverschil){
        $this->kasverschil = $kasverschil;
        return $this;
    }

    public function getFloat(){
        return $this->float;
    }

    public function setFloat($float){
        $this->float = $float;
        return $this;
    }

    public function getTotaalborg()
    {
        return $this->totaalborg;
    }

    public function setTotaalborg($totaalborg)
    {
        $this->totaalborg = $totaalborg;
    }

    public function getLongstay() {
        return $this->longstay;
    }

    public function setLongstay($longstay) {
        $this->longstay = $longstay;
    }  

    public function getStripeguesty() {
        return $this->stripeguesty;
    }

    public function setStripeguesty($stripeguesty) {
        $this->stripeguesty = $stripeguesty;
    }       

    public function getStripeinvoice() {
        return $this->stripeinvoice;
    }

    public function setStripeinvoice($stripeinvoice) {
        $this->stripeinvoice = $stripeinvoice;
    }     

    public function getBank() {
        return $this->bank;
    }

    public function setBank($bank) {
        $this->bank = $bank;
    }    

    public function getAirbnb() {
        return $this->airbnb;
    }

    public function setAirbnb($airbnb) {
        $this->airbnb = $airbnb;
    } 

    public function getOmzet() {
        return $this->omzet;
    }

    public function setOmzet($omzet) {
        $this->omzet = $omzet;
    }

}
