<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KasboekHotelFloat
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class KasboekHotelFloat
{
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
     * @ORM\Column(name="e500", type="float", nullable=true)
     */
    private $e500;

    /**
     * @var float
     *
     * @ORM\Column(name="e200", type="float", nullable=true)
     */
    private $e200;

    /**
     * @var float
     *
     * @ORM\Column(name="e100", type="float", nullable=true)
     */
    private $e100;

    /**
     * @var float
     *
     * @ORM\Column(name="e50", type="float", nullable=true)
     */
    private $e50;

    /**
     * @var float
     *
     * @ORM\Column(name="e20", type="float", nullable=true)
     */
    private $e20;

    /**
     * @var float
     *
     * @ORM\Column(name="e10", type="float", nullable=true)
     */
    private $e10;

    /**
     * @var float
     *
     * @ORM\Column(name="e5", type="float", nullable=true)
     */
    private $e5;


    /**
     * @var float
     *
     * @ORM\Column(name="waarde", type="float", nullable=true)
     */
    private $waarde;

    /**
     * @var float
     *
     * @ORM\Column(name="bank1", type="float", nullable=true)
     */
    private $bank1;
    /**
     * @var float
     *
     * @ORM\Column(name="bank2", type="float", nullable=true)
     */
    private $bank2;
    /**
     * @var float
     *
     * @ORM\Column(name="bank3", type="float", nullable=true)
     */
    private $bank3;
    /**
     * @var float
     *
     * @ORM\Column(name="bank4", type="float", nullable=true)
     */
    private $bank4;

    /**
     * @var float
     *
     * @ORM\Column(name="bank", type="array", nullable=true)
     */
    private $bank;

    /**
     * @var float
     *
     * @ORM\Column(name="contant", type="float", nullable=true)
     */
    private $contant;

    /**
     * @var float
     * @ORM\Column(name="kasverschil", type="float", nullable=true)
     */
    private $kasverschil ;


    /**
     * @var float
     *
     * @ORM\Column(name="type", type="float", nullable=true)
     */
    private $type;
    
    
/**
     * @var float
     *
     * @ORM\Column(name="totalmoney", type="float", nullable=true)
     */
    private $totalmoney;


    /**
     * Get id
     *
     * @return float 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set e500
     *
     * @param float $e500
     * @return Bill
     */
    public function setE500($e500)
    {
        $this->e500 = $e500;

        return $this;
    }

    /**
     * Get e500
     *
     * @return float 
     */
    public function getE500()
    {
        return $this->e500;
    }

    /**
     * Set e200
     *
     * @param float $e200
     * @return Bill
     */
    public function setE200($e200)
    {
        $this->e200 = $e200;

        return $this;
    }

    /**
     * Get e200
     *
     * @return float 
     */
    public function getE200()
    {
        return $this->e200;
    }

    /**
     * Set e100
     *
     * @param float $e100
     * @return Bill
     */
    public function setE100($e100)
    {
        $this->e100 = $e100;

        return $this;
    }

    /**
     * Get e100
     *
     * @return float 
     */
    public function getE100()
    {
        return $this->e100;
    }

    /**
     * Set e50
     *
     * @param float $e50
     * @return Bill
     */
    public function setE50($e50)
    {
        $this->e50 = $e50;

        return $this;
    }

    /**
     * Get e50
     *
     * @return float 
     */
    public function getE50()
    {
        return $this->e50;
    }

    /**
     * Set e20
     *
     * @param float $e20
     * @return Bill
     */
    public function setE20($e20)
    {
        $this->e20 = $e20;

        return $this;
    }

    /**
     * Get e20
     *
     * @return float 
     */
    public function getE20()
    {
        return $this->e20;
    }

    /**
     * Set e10
     *
     * @param float $e10
     * @return Bill
     */
    public function setE10($e10)
    {
        $this->e10 = $e10;

        return $this;
    }

    /**
     * Get e10
     *
     * @return float 
     */
    public function getE10()
    {
        return $this->e10;
    }

    /**
     * Set e5
     *
     * @param float $e5
     * @return Bill
     */
    public function setE5($e5)
    {
        $this->e5 = $e5;

        return $this;
    }

    /**
     * Get e5
     *
     * @return float 
     */
    public function getE5()
    {
        return $this->e5;
    }

    /**
     * Set e2
     *
     * @param float $e2
     * @return Bill
     */
    public function setE2($e2)
    {
        $this->e2 = $e2;

        return $this;
    }

    /**
     * Get e2
     *
     * @return float 
     */
    public function getE2()
    {
        return $this->e2;
    }

    public function getKasverschil(){
        return $this->kasverschil;
    }

    public function setKasverschil($kasverschil){
        $this->kasverschil = $kasverschil;
        return $this;

    }

    public function getContant(){
        return $this->contant;
    }

    public function setContant($contant){
        $this->contant = $contant;
        return $this;
    }

    public function getType(){
        return $this->type;
    }

    public function setType($type){
        $this->type = $type;
        return $this;
    }

    public function getWaarde(){
        return $this->waarde;
    }

    public function setWaarde($waarde){
        $this->waarde = $waarde;
        return $this;
    }

    public function getBank1()
    {
        return $this->bank1;
    }

    public function setBank1($bank)
    {
        $this->bank1 = $bank;
    }

    public function getBank2()
    {
        return $this->bank2;
    }

    public function setBank2($bank2)
    {
        $this->bank2 = $bank2;
    }

    public function getBank3()
    {
        return $this->bank3;
    }

    public function setBank3($bank3)
    {
        $this->bank3 = $bank3;
    }

    public function getBank4()
    {
        return $this->bank4;
    }

    public function setBank4($bank4)
    {
        $this->bank4 = $bank4;
    }

    public function getBank()
    {
        return $this->bank;
    }

    public function setBank($bank)
    {
        $this->bank = $bank;
    }

    public function getTotalmoney()
    {
        return $this->totalmoney;
    }

    public function setTotalmoney($totalmoney)
    {
        $this->totalmoney = $totalmoney;
    }
}
