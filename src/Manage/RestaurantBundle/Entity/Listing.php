<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Listing
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Repository\ListingRepository")
 */
class Listing {

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
     * @ORM\Column(name="details", type="string")
     */
    private $details;
    
    /**
     * @var float
     * @ORM\Column(name="value", type="string")
     */
    private $number;

    /**
     * @Assert\Image()
     */
    private $image;

    /**
     * @ORM\Column(name="pathimage", type="string", nullable=true)
     */
    private $pathimage;

    /**
     *
     * @ORM\Column(name="activeforrent", type="boolean")
     */
    private $activeforrent;

    /**
     *
     * @ORM\Column(name="level", type="string")
     */
    private $level;

    /**
     *
     * @ORM\Column(name="idguesty", type="string", nullable=true)
     */
    private $idguesty;

    /**
     *
     * @ORM\Column(name="type", type="array", nullable=true)
     */
    private $type;

    /**
     *
     * @ORM\Column(name="maxprice", type="float", nullable=true)
     */
    private $maxprice;
    /**
     *
     * @ORM\Column(name="minprice", type="float", nullable=true)
     */
    private $minprice;

    /**
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;


    /**
     *
     * @ORM\Column(name="status", type="string")
     */
    private $status;


    /**
     *
     * @ORM\Column(name="updatedat", type="datetime")
     */
    private $updatedat;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set details
     *
     * @param string $details
     * @return CashClosure
     */
    public function setDetails($details) {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails() {
        return $this->details;
    }
       
    /**
     * Get value
     *
     * @return string 
     */
    public function getNumber() {
        return $this->number;
    }
    /**
     * Set value
     *
     * @param float $value
     * @return object
     */
    public function setNumber($value) {
        $this->number = $value;
        return $this;
    }

    public function getPathimage(){
        return $this->pathimage;
    }

    public function setPathimage($v){
        $this->pathimage = $v;
        return $this->pathimage;
    }

    public function setImage(UploadedFile $foto = null){
        $this->image = $foto;
    }

    public function getImage(){
        return $this->image;
    }

    public function uploadImage($destiny){
        if (null === $this->image) {
            return;
        }
        $nombreArchivoFoto = uniqid().'.jpg';
        $this->image->move($destiny, $nombreArchivoFoto);
        $this->setPathimage($nombreArchivoFoto);
    }

    public function getActiveforrent(){
        return $this->activeforrent;
    }

    public function setActiveforrent($activeforrent){
        $this->activeforrent = $activeforrent;
        return $this;
    }

    public function getLevel(){
        return $this->level;
    }

    public function setLevel($level){
        $this->level = $level;
        return $this;
    }

    public function getIdguesty()
    {
        return $this->idguesty;
    }

    public function setIdguesty($idguesty)
    {
        $this->idguesty = $idguesty;
        
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getMaxprice()
    {
        return $this->maxprice;
    }

    public function setMaxprice($maxprice)
    {
        $this->maxprice = $maxprice;
    }

    public function getMinprice()
    {
        return $this->minprice;
    }

    public function setMinprice($minprice)
    {
        $this->minprice = $minprice;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function __toString(){
        return (string)$this->details;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getUpdatedat()
    {
        return $this->updatedat;
    }

    public function setUpdatedat($updatedat)
    {
        $this->updatedat = $updatedat;
    }
}