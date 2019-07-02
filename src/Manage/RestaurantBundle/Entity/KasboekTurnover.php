<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * KasboekTurnover
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class KasboekTurnover {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var object
     * @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Kasboek")
     */
    private $kasboek;

    /**
     * @var float
     * @ORM\Column(name="day", type="float", nullable=true)
     */
    private $day;

    /**
     * @var float
     * @ORM\Column(name="cash", type="float", nullable=true)
     */
    private $cash;
    
    /**
     * @var float
     * @ORM\Column(name="inkoopfood", type="float", nullable=true)
     */
    private $inkoopfood;
    
    /**
     * @var float
     * @ORM\Column(name="bedrijfskleding", type="float", nullable=true)
     */
    private $bedrijfskleding;
    
    /**
     * @var float
     * @ORM\Column(name="kleineinv", type="float", nullable=true)
     */
    private $kleineinv;
    
    /**
     * @var float
     * @ORM\Column(name="bankkosten", type="float", nullable=true)
     */
    private $bankkosten;
    
    /**
     * @var float
     * @ORM\Column(name="was", type="float", nullable=true)
     */
    private $was;
    
    /**
     * @var float
     * @ORM\Column(name="entertainment", type="float", nullable=true)
     */
    private $entertainment;
    
    /**
     * @var float
     * @ORM\Column(name="diversekosten", type="float", nullable=true)
     */
    private $diversekosten;

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

    public function getCash(){
        return $this->cash;
    }

    public function setCash($v){
        $this->cash = $v;
        return $this;
    }
    
    public function getDay(){
        return $this->day;
    }

    public function setDay($v){
        $this->day = $v;
        return $this;
    }

    public function getInkoopfood(){
        return $this->inkoopfood;
    }

    public function setInkoopfood($v){
        $this->inkoopfood = $v;
        return $this;
    }

    public function getBedrijfskleding(){
        return $this->bedrijfskleding;
    }

    public function setBedrijfskleding($v){
        $this->bedrijfskleding = $v;
        return $this;
    }

    public function getKleineinv(){
        return $this->kleineinv;
    }

    public function setKleineinv($v){
        $this->kleineinv = $v;
        return $this;
    }

    public function getBankkosten(){
        return $this->bankkosten;
    }

    public function setBankkosten($v){
        $this->bankkosten = $v;
        return $this;
    }

    public function getEntertainment(){
        return $this->entertainment;
    }

    public function setEntertainment($v){
        $this->entertainment = $v;
        return $this;
    }
    
    public function getDiversekosten(){
        return $this->diversekosten;
    }

    public function setDiversekosten($v){
        $this->diversekosten = $v;
        return $this;
    }
    public function getWas(){
        return $this->was;
    }

    public function setWas($v){
        $this->was = $v;
        return $this;
    }
}
