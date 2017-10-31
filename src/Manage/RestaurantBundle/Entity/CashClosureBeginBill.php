<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bill
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CashClosureBeginBill {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="e20", type="integer")
     */
    private $e20;

    /**
     * @var integer
     *
     * @ORM\Column(name="e10", type="integer")
     */
    private $e10;

    /**
     * @var integer
     *
     * @ORM\Column(name="e5", type="integer")
     */
    private $e5;

    /**
     * @var integer
     *
     * @ORM\Column(name="e2", type="integer")
     */
    private $e2;

    /**
     * @var integer
     *
     * @ORM\Column(name="e1", type="integer")
     */
    private $e1;

    /**
     * @var integer
     *
     * @ORM\Column(name="e050", type="integer")
     */
    private $e050;

    /**
     * @var integer
     *
     * @ORM\Column(name="e020", type="integer")
     */
    private $e020;

    /**
     * @var integer
     *
     * @ORM\Column(name="e010", type="integer")
     */
    private $e010;

        /**
     * @var integer
     *
     * @ORM\Column(name="s20", type="integer")
     */
    
    private $s20;

    /**
     * @var integer
     *
     * @ORM\Column(name="s10", type="integer")
     */
    private $s10;

    /**
     * @var integer
     *
     * @ORM\Column(name="s5", type="integer")
     */
    private $s5;

    /**
     * @var integer
     *
     * @ORM\Column(name="s2", type="integer")
     */
    private $s2;

    /**
     * @var integer
     *
     * @ORM\Column(name="s1", type="integer")
     */
    private $s1;

    /**
     * @var integer
     *
     * @ORM\Column(name="s050", type="integer")
     */
    private $s050;

    /**
     * @var integer
     *
     * @ORM\Column(name="s020", type="integer")
     */
    private $s020;

    /**
     * @var integer
     *
     * @ORM\Column(name="s010", type="integer")
     */
    private $s010;

    
    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;

     /**
     * @var boolean
     *
     * @ORM\Column(name="extra", type="boolean")
     */
    private $extra;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="standard", type="boolean")
     */
    private $standard;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set e20
     *
     * @param integer $e20
     * @return CashClosureBeginBill
     */
    public function setE20($e20) {
        $this->e20 = $e20;

        return $this;
    }

    /**
     * Get e20
     *
     * @return integer 
     */
    public function getE20() {
        return $this->e20;
    }

    /**
     * Set e10
     *
     * @param integer $e10
     * @return CashClosureBeginBill
     */
    public function setE10($e10) {
        $this->e10 = $e10;

        return $this;
    }

    /**
     * Get e10
     *
     * @return integer 
     */
    public function getE10() {
        return $this->e10;
    }

    /**
     * Set e5
     *
     * @param integer $e5
     * @return CashClosureBeginBill
     */
    public function setE5($e5) {
        $this->e5 = $e5;

        return $this;
    }

    /**
     * Get e5
     *
     * @return integer 
     */
    public function getE5() {
        return $this->e5;
    }

    /**
     * Set e2
     *
     * @param integer $e2
     * @return CashClosureBeginBill
     */
    public function setE2($e2) {
        $this->e2 = $e2;

        return $this;
    }

    /**
     * Get e2
     *
     * @return integer 
     */
    public function getE2() {
        return $this->e2;
    }

    /**
     * Set e1
     *
     * @param integer $e1
     * @return CashClosureBeginBill
     */
    public function setE1($e1) {
        $this->e1 = $e1;

        return $this;
    }

    /**
     * Get e1
     *
     * @return integer 
     */
    public function getE1() {
        return $this->e1;
    }

    /**
     * Set e050
     *
     * @param integer $e050
     * @return CashClosureBeginBill
     */
    public function setE050($e050) {
        $this->e050 = $e050;

        return $this;
    }

    /**
     * Get e050
     *
     * @return integer 
     */
    public function getE050() {
        return $this->e050;
    }

    /**
     * Set e020
     *
     * @param integer $e020
     * @return CashClosureBeginBill
     */
    public function setE020($e020) {
        $this->e020 = $e020;
        return $this;
    }

    /**
     * Get e020
     *
     * @return integer 
     */
    public function getE020() {
        return $this->e020;
    }

    /**
     * Set e010
     *
     * @param integer $e010
     * @return CashClosureBeginBill
     */
    public function setE010($e010) {
        $this->e010 = $e010;

        return $this;
    }

    /**
     * Get e010
     *
     * @return integer 
     */
    public function getE010() {
        return $this->e010;
    }

       /**
     * Set s20
     *
     * @param integer $s20
     * @return CashClosureBeginBill
     */
    public function setS20($s20) {
        $this->s20 = $s20;

        return $this;
    }

    /**
     * Get s20
     *
     * @return integer 
     */
    public function getS20() {
        return $this->s20;
    }

    /**
     * Set s10
     *
     * @param integer $s10
     * @return CashClosureBeginBill
     */
    public function setS10($s10) {
        $this->s10 = $s10;

        return $this;
    }

    /**
     * Get s10
     *
     * @return integer 
     */
    public function getS10() {
        return $this->s10;
    }

    /**
     * Set s5
     *
     * @param integer $s5
     * @return CashClosureBeginBill
     */
    public function setS5($s5) {
        $this->s5 = $s5;

        return $this;
    }

    /**
     * Get s5
     *
     * @return integer 
     */
    public function getS5() {
        return $this->s5;
    }

    /**
     * Set s2
     *
     * @param integer $s2
     * @return CashClosureBeginBill
     */
    public function setS2($s2) {
        $this->s2 = $s2;

        return $this;
    }

    /**
     * Get s2
     *
     * @return integer 
     */
    public function getS2() {
        return $this->s2;
    }

    /**
     * Set s1
     *
     * @param integer $s1
     * @return CashClosureBeginBill
     */
    public function setS1($s1) {
        $this->s1 = $s1;

        return $this;
    }

    /**
     * Get s1
     *
     * @return integer 
     */
    public function getS1() {
        return $this->s1;
    }

    /**
     * Set s050
     *
     * @param integer $s050
     * @return CashClosureBeginBill
     */
    public function setS050($s050) {
        $this->s050 = $s050;

        return $this;
    }

    /**
     * Get s050
     *
     * @return integer 
     */
    public function getS050() {
        return $this->s050;
    }

    /**
     * Set s020
     *
     * @param integer $s020
     * @return CashClosureBeginBill
     */
    public function setS020($s020) {
        $this->s020 = $s020;
        return $this;
    }

    /**
     * Get s020
     *
     * @return integer 
     */
    public function getS020() {
        return $this->s020;
    }

    /**
     * Set s010
     *
     * @param integer $s010
     * @return CashClosureBeginBill
     */
    public function setS010($s010) {
        $this->s010 = $s010;

        return $this;
    }

    /**
     * Get s010
     *
     * @return integer 
     */
    public function getS010() {
        return $this->s010;
    }

    
    /**
     * Set total
     *
     * @param float $total
     * @return CashClosureBeginBill
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal() {
        return $this->total;
    }
    
        /**
     * Set extra
     *
     * @param boolean $extra
     * @return CashClosureBeginBill
     */
    public function setExtra($extra) {
        $this->extra = $extra;
        return $this;
    }

    /**
     * Get extra
     *
     * @return boolean 
     */
    public function getExtra() {
        return $this->extra;
    }
    
            /**
     * Set standard
     *
     * @param boolean $standard
     * @return CashClosureBinginBill
     */
    public function setStandard($standard) {
        $this->standard = $standard;
        return $this;
    }

    /**
     * Get standard
     *
     * @return boolean 
     */
    public function getStandard() {
        return $this->standard;
    }

}
