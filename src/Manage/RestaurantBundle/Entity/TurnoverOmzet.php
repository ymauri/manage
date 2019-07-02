<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * TurnoverOmzet
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class TurnoverOmzet {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="omzkitchen", type="float", nullable=true)
     */
    private $omzkitchen;
    
    /**
     * @ORM\Column(name="omzlaag", type="float", nullable=true)
     */
    private $omzlaag;
    
    /**
     * @ORM\Column(name="omzhoog", type="float", nullable=true)
     */
    private $omzhoog;
    
    /**
     * @ORM\Column(name="omzspacerent", type="float", nullable=true)
     */
    private $omzspacerent;
    
    /**
     * @ORM\Column(name="omzothers", type="float", nullable=true)
     */
    private $omzothers;
    
    /**
     * @ORM\Column(name="omzentry", type="float", nullable=true)
     */
    private $omzentry;
    
    /**
     * @ORM\Column(name="omzparking", type="float", nullable=true)
     */
    private $omzparking;
    /**
     * @ORM\Column(name="omzvouchersrek", type="float", nullable=true)
     */
    private $omzvouchersrek;
    
    /**
     * @ORM\Column(name="omztotal", type="float", nullable=true)
     */
    private $omztotal;
    
     /**
     * @ORM\Column(name="omzexkitchen", type="float", nullable=true)
     */
    private $omzexkitchen;
    
    /**
     * @ORM\Column(name="omzexlaag", type="float", nullable=true)
     */
    private $omzexlaag;
    
    /**
     * @ORM\Column(name="omzexhoog", type="float", nullable=true)
     */
    private $omzexhoog;
    
    /**
     * @ORM\Column(name="omzexspacerent", type="float", nullable=true)
     */
    private $omzexspacerent;
    
    /**
     * @ORM\Column(name="omzexothers", type="float", nullable=true)
     */
    private $omzexothers;
    
    /**
     * @ORM\Column(name="omzexentry", type="float", nullable=true)
     */
    private $omzexentry;
    
    /**
     * @ORM\Column(name="omzexparking", type="float", nullable=true)
     */
    private $omzexparking;
    /**
     * @ORM\Column(name="omzexvouchersrek", type="float", nullable=true)
     */
    private $omzexvouchersrek;
    
    /**
     * @ORM\Column(name="omzextotal", type="float", nullable=true)
     */
    private $omzextotal;
    
   
    
    public function getId() {
        return $this->id;
    }
 
    public function getOmzexkitchen () {
        return $this->omzexkitchen;
    }

    public function setOmzexkitchen ($v) {
        $this->omzexkitchen = $v;
        return $this;
    }
    
    public function getOmzexlaag () {
        return $this->omzexlaag;
    }

    public function setOmzexlaag ($v) {
        $this->omzexlaag = $v;
        return $this;
    }
    
    public function getOmzexhoog () {
        return $this->omzexhoog;
    }

    public function setOmzexhoog ($v) {
        $this->omzexhoog = $v;
        return $this;
    }
    
    public function getOmzexentry () {
        return $this->omzexentry;
    }

    public function setOmzexentry ($v) {
        $this->omzexentry = $v;
        return $this;
    }
    
    public function getOmzexparking () {
        return $this->omzexparking;
    }

    public function setOmzexparking ($v) {
        $this->omzexparking = $v;
        return $this;
    }
    
    public function getOmzexvouchersrek () {
        return $this->omzexvouchersrek;
    }

    public function setOmzexvouchersrek ($v) {
        $this->omzexvouchersrek = $v;
        return $this;
    }
    
    public function getOmzexspacerent () {
        return $this->omzexspacerent;
    }

    public function setOmzexspacerent ($v) {
        $this->omzexspacerent = $v;
        return $this;
    }
    
    public function getOmzexothers () {
        return $this->omzexothers;
    }

    public function setOmzexothers ($v) {
        $this->omzexothers = $v;
        return $this;
    }
    
    public function getOmzextotal () {
        return $this->omzextotal;
    }

    public function setOmzextotal ($v) {
        $this->omzextotal = $v;
        return $this;
    }
    
    public function getOmzkitchen () {
        return $this->omzkitchen;
    }

    public function setOmzkitchen ($v) {
        $this->omzkitchen = $v;
        return $this;
    }
    
    public function getOmzlaag () {
        return $this->omzlaag;
    }

    public function setOmzlaag ($v) {
        $this->omzlaag = $v;
        return $this;
    }
    
    public function getOmzhoog () {
        return $this->omzhoog;
    }

    public function setOmzhoog ($v) {
        $this->omzhoog = $v;
        return $this;
    }
    
    public function getOmzentry () {
        return $this->omzentry;
    }

    public function setOmzentry ($v) {
        $this->omzentry = $v;
        return $this;
    }
    
    public function getOmzparking () {
        return $this->omzparking;
    }

    public function setOmzparking ($v) {
        $this->omzparking = $v;
        return $this;
    }
    
    public function getOmzvouchersrek () {
        return $this->omzvouchersrek;
    }

    public function setOmzvouchersrek ($v) {
        $this->omzvouchersrek = $v;
        return $this;
    }
    
    public function getOmzspacerent () {
        return $this->omzspacerent;
    }

    public function setOmzspacerent ($v) {
        $this->omzspacerent = $v;
        return $this;
    }
    
    public function getOmzothers () {
        return $this->omzothers;
    }

    public function setOmzothers ($v) {
        $this->omzothers = $v;
        return $this;
    }
    
    public function getOmztotal () {
        return $this->omztotal;
    }

    public function setOmztotal ($v) {
        $this->omztotal = $v;
        return $this;
    }
    
}
