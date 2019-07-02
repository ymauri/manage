<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * KasboekHotelForms
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class KasboekHotelForms {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var object
     * @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\KasboekHotel")
     */
    private $kasboekhotel;

    /**
     * @var float
     * @ORM\Column(name="day", type="float", nullable=true)
     */
    private $day;

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

    public function getId() {
        return $this->id;
    }

    public function getKasboek(){
        return $this->kasboek;
    }

    public function setKasboek($v){
        $this->kasboek = $v;
        return $this;
    }

    public function getOvernachtingen(){
        return $this->overnachtingen;
    }

    public function setOvernachtingen($v){
        $this->overnachtingen = $v;
        return $this;
    }
    
    public function getDay(){
        return $this->day;
    }

    public function setDay($v){
        $this->day = $v;
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

    public function setKasboekhotel($kasboekhotel){
        $this->kasboekhotel = $kasboekhotel;
        return $this;
    }

    public function getKasboekhotel(){
        return $this->kasboekhotel;
    }
}
