<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Checkout
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Checkout {

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
     * @ORM\Column(name="listing", type="integer")
     */
    private $listing;
    
    /**
     * @var datetime
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var date
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var datetime
     * @ORM\Column(name="updatedat", type="datetime", nullable=true)
     */
    private $updatedat;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    private $name;
    
    /**
     * @var string
     * @ORM\Column(name="email", type="string", nullable=true)
     */
    private $email;
    
    /**
     * @var string
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    private $phone;
    
    /**
     * @var string
     * @ORM\Column(name="source", type="string")
     */
    private $source;
    
    /**
     * @var integer
     * @ORM\Column(name="nights", type="integer")
     */
    private $nights;

    /**
     * @var string
     * @ORM\Column(name="confcode", type="string")
     */
    private $confcode;

    /**
     * @var integer
     * @ORM\Column(name="guests", type="integer")
     */
    private $guests;

    /**
     * @var string
     * @ORM\Column(name="idguesty", type="string")
     */
    private $idguesty;

    /**
     * @var string
     * @ORM\Column(name="status", type="string")
     */
    private $status;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function setEmail($v) {
        $this->email = $v;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }
    
    public function setListing($v) {
        $this->listing = $v;
        return $this;
    }

    public function getListing() {
        return $this->listing;
    }
    
    public function setName($v) {
        $this->name = $v;
        return $this;
    }

    public function getName() {
        return $this->name;
    }
    
    public function setPhone($v) {
        $this->phone = $v;
        return $this;
    }

    public function getPhone() {
        return $this->phone;
    }
    
    public function setSource($v) {
        $this->source = $v;
        return $this;
    }

    public function getSource() {
        return $this->source;
    }
    
    public function setTime($v) {
        $this->time = $v;
        return $this;
    }

    public function getTime() {
        return $this->time;
    }
    
    public function setNights($v) {
        $this->nights = $v;
        return $this;
    }

    public function getNights() {
        return $this->nights;
    }

    public function setUpdatedat($v) {
        $this->updatedat = $v;
        return $this;
    }

    public function getUpdatedat() {
        return $this->updatedat;
    }

    public function setConfcode($confcode){
        $this->confcode = $confcode;
        return $this;
    }

    public function getConfcode() {
        return $this->confcode;
    }

    public function setDate($date){
        $this->date = $date;
        return $this;
    }

    public function getDate(){
        return $this->date;
    }

    public function setGuests($v) {
        $this->guests = $v;
        return $this;
    }

    public function getGuests() {
        return $this->guests;
    }

    public function getIdguesty(){
        return $this->idguesty;
    }

    public function setIdguesty($idguesty){
        $this->idguesty = $idguesty;
        return $this;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
        return $this;
    }

    public function __toString(){
        return $this->listing . ' ' . $this->arrival ;
    }
    

}