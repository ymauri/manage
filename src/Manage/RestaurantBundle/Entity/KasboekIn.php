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
class KasboekIn {

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
     * @ORM\Column(name="omzvoucher", type="float", nullable=true)
     */
    private $omzvoucher;
    
    /**
     * @ORM\Column(name="omzentry", type="float", nullable=true)
     */
    private $omzentry;
    
    /**
     * @ORM\Column(name="omzparking", type="float", nullable=true)
     */
    private $omzparking;
    
    /**
     * @ORM\Column(name="omzothers", type="float", nullable=true)
     */
    private $omzothers;
    
    /**
     * @ORM\Column(name="omztotalin", type="float", nullable=true)
     */
    private $omztotalin;


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
     * @ORM\Column(name="omzexvoucher", type="float", nullable=true)
     */
    private $omzexvoucher;

    /**
     * @ORM\Column(name="omzexentry", type="float", nullable=true)
     */
    private $omzexentry;

    /**
     * @ORM\Column(name="omzexparking", type="float", nullable=true)
     */
    private $omzexparking;

    /**
     * @ORM\Column(name="omzexothers", type="float", nullable=true)
     */
    private $omzexothers;

    /**
     * @ORM\Column(name="omzextotalin", type="float", nullable=true)
     */
    private $omzextotalin;

    /**
     * @ORM\Column(name="omzbtwkitchen", type="float", nullable=true)
     */
    private $omzbtwkitchen;


    /**
     * @ORM\Column(name="omzbtwlaag", type="float", nullable=true)
     */
    private $omzbtwlaag;

    /**
     * @ORM\Column(name="omzbtwhoog", type="float", nullable=true)
     */
    private $omzbtwhoog;

    /**
     * @ORM\Column(name="omzbtwspacerent", type="float", nullable=true)
     */
    private $omzbtwspacerent;

    /**
     * @ORM\Column(name="omzbtwvoucher", type="float", nullable=true)
     */
    private $omzbtwvoucher;

    /**
     * @ORM\Column(name="omzbtwentry", type="float", nullable=true)
     */
    private $omzbtwentry;

    /**
     * @ORM\Column(name="omzbtwparking", type="float", nullable=true)
     */
    private $omzbtwparking;

    /**
     * @ORM\Column(name="omzbtwothers", type="float", nullable=true)
     */
    private $omzbtwothers;

    /**
     * @ORM\Column(name="omzbtwtotalin", type="float", nullable=true)
     */
    private $omzbtwtotalin;


    public function getId() {
        return $this->id;
    }

    public function getOmzkitchen() {
        return $this->omzkitchen;
    }

    public function setOmzkitchen ($omzkitchen) {
        $this->omzkitchen = $omzkitchen;
        return $this;
    }
    
    public function getOmzlaag() {
        return $this->omzlaag;
    }

    public function setOmzlaag ($omzlaag) {
        $this->omzlaag = $omzlaag;
        return $this;
    }
    public function getOmzhoog() {
        return $this->omzhoog;
    }

    public function setOmzhoog ($omzhoog) {
        $this->omzhoog = $omzhoog;
        return $this;
    }
    public function getOmzspacerent() {
        return $this->omzspacerent;
    }

    public function setOmzspacerent ($omzspacerent) {
        $this->omzspacerent = $omzspacerent;
        return $this;
    }
    
     public function getOmzvoucher () {
        return $this->omzvoucher;
    }

    public function setOmzvoucher ($v) {
        $this->omzvoucher = $v;
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
    
    public function getOmzothers () {
        return $this->omzothers;
    }

    public function setOmzothers ($v) {
        $this->omzothers = $v;
        return $this;
    }
    
    public function getOmztotalin () {
        return $this->omztotalin;
    }

    public function setOmztotalin ($v) {
        $this->omztotalin = $v;
        return $this;
    }

    public function getOmzexkitchen() {
        return $this->omzexkitchen;
    }

    public function setOmzexkitchen ($omzexkitchen) {
        $this->omzexkitchen = $omzexkitchen;
        return $this;
    }

    public function getOmzexlaag() {
        return $this->omzexlaag;
    }

    public function setOmzexlaag ($omzexlaag) {
        $this->omzexlaag = $omzexlaag;
        return $this;
    }
    public function getOmzexhoog() {
        return $this->omzexhoog;
    }

    public function setOmzexhoog ($omzexhoog) {
        $this->omzexhoog = $omzexhoog;
        return $this;
    }
    public function getOmzexspacerent() {
        return $this->omzexspacerent;
    }

    public function setOmzexspacerent ($omzexspacerent) {
        $this->omzexspacerent = $omzexspacerent;
        return $this;
    }

    public function getOmzexvoucher () {
        return $this->omzexvoucher;
    }

    public function setOmzexvoucher ($v) {
        $this->omzexvoucher = $v;
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

    public function getOmzexothers () {
        return $this->omzexothers;
    }

    public function setOmzexothers ($v) {
        $this->omzexothers = $v;
        return $this;
    }

    public function getOmzextotalin () {
        return $this->omzextotalin;
    }

    public function setOmzextotalin ($v) {
        $this->omzextotalin = $v;
        return $this;
    }

    public function getOmzbtwkitchen() {
        return $this->omzbtwkitchen;
    }

    public function setOmzbtwkitchen ($omzbtwkitchen) {
        $this->omzbtwkitchen = $omzbtwkitchen;
        return $this;
    }

    public function getOmzbtwlaag() {
        return $this->omzbtwlaag;
    }

    public function setOmzbtwlaag ($omzbtwlaag) {
        $this->omzbtwlaag = $omzbtwlaag;
        return $this;
    }
    public function getOmzbtwhoog() {
        return $this->omzbtwhoog;
    }

    public function setOmzbtwhoog ($omzbtwhoog) {
        $this->omzbtwhoog = $omzbtwhoog;
        return $this;
    }
    public function getOmzbtwspacerent() {
        return $this->omzbtwspacerent;
    }

    public function setOmzbtwspacerent ($omzbtwspacerent) {
        $this->omzbtwspacerent = $omzbtwspacerent;
        return $this;
    }

    public function getOmzbtwvoucher () {
        return $this->omzbtwvoucher;
    }

    public function setOmzbtwvoucher ($v) {
        $this->omzbtwvoucher = $v;
        return $this;
    }

    public function getOmzbtwentry () {
        return $this->omzbtwentry;
    }

    public function setOmzbtwentry ($v) {
        $this->omzbtwentry = $v;
        return $this;
    }

    public function getOmzbtwparking () {
        return $this->omzbtwparking;
    }

    public function setOmzbtwparking ($v) {
        $this->omzbtwparking = $v;
        return $this;
    }

    public function getOmzbtwothers () {
        return $this->omzbtwothers;
    }

    public function setOmzbtwothers ($v) {
        $this->omzbtwothers = $v;
        return $this;
    }

    public function getOmzbtwtotalin () {
        return $this->omzbtwtotalin;
    }

    public function setOmzbtwtotalin ($v) {
        $this->omzbtwtotalin = $v;
        return $this;
    }
}
