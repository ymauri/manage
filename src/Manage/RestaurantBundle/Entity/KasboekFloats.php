<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * KasboekFloats
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class KasboekFloats {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="wstandar", type="float", nullable=true)
     */
    private $wstandar;
    /**
     * @ORM\Column(name="astandar", type="float", nullable=true)
     */
    private $astandar;

    /**
     * @ORM\Column(name="bstandar", type="float", nullable=true)
     */
    private $bstandar;

    /**
     * @ORM\Column(name="wextra1", type="float", nullable=true)
     */
    private $wextra1;
    /**
     * @ORM\Column(name="aextra1", type="float", nullable=true)
     */
    private $aextra1;

    /**
     * @ORM\Column(name="bextra1", type="float", nullable=true)
     */
    private $bextra1;

    /**
     * @ORM\Column(name="wextra2", type="float", nullable=true)
     */
    private $wextra2;
    /**
     * @ORM\Column(name="aextra2", type="float", nullable=true)
     */
    private $aextra2;

    /**
     * @ORM\Column(name="bextra2", type="float", nullable=true)
     */
    private $bextra2;


    /**
     * @ORM\Column(name="wextra3", type="float", nullable=true)
     */
    private $wextra3;
    /**
     * @ORM\Column(name="aextra3", type="float", nullable=true)
     */
    private $aextra3;

    /**
     * @ORM\Column(name="bextra3", type="float", nullable=true)
     */
    private $bextra3;

    /**
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;



    public function getId() {
        return $this->id;
    }

    public function getWstandar(){
        return $this->wstandar;
    }

    public function setWstandar($wstandar){
        $this->wstandar = $wstandar;
        return $wstandar;
    }

    public function getAstandar(){
        return $this->astandar;
    }

    public function setAstandar($astandar){
        $this->astandar = $astandar;
        return $this;
    }

    public function getBstandar(){
        return $this->bstandar;
    }

    public function setBstandar($bstandar){
        $this->bstandar = $bstandar;
        return $this;
    }

    public function getWextra1(){
        return $this->wextra1;
    }

    public function setWextra1($wextra1){
        $this->wextra1 = $wextra1;
        return $this;
    }

    public function getAextra1(){
        return $this->aextra1;
    }

    public function setAextra1($aextra1){
        $this->aextra1 = $aextra1;
        return $this;
    }

    public function getBextra1(){
        return $this->bextra1;
    }

    public function setBextra1($bextra1){
        $this->bextra1 = $bextra1;
        return $this;
    }

    public function getWextra2(){
        return $this->wextra2;
    }

    public function setWextra2($wextra2){
        $this->wextra2 = $wextra2;
        return $this;
    }

    public function getAextra2(){
        return $this->aextra2;
    }

    public function setAextra2($aextra2){
        $this->aextra2 = $aextra2;
        return $this;
    }

    public function getBextra2(){
        return $this->bextra2;
    }

    public function setBextra2($bextra2){
        $this->bextra2 = $bextra2;
        return $this;
    }

    public function getWextra3(){
        return $this->wextra2;
    }

    public function setWextra3($wextra3){
        $this->wextra3 = $wextra3;
        return $this;
    }

    public function getAextra3(){
        return $this->aextra3;
    }

    public function setAextra3($aextra3){
        $this->aextra3 = $aextra3;
        return $this;
    }

    public function getBextra3(){
        return $this->bextra2;
    }

    public function setBextra3($bextra3){
        $this->bextra3 = $bextra3;
        return $this;
    }

    public function getTotal(){
        return $this->total;
    }

    public function setTotal($total){
        $this->total = $total;
        return $this;
    }
}
