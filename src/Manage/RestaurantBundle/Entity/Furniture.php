<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Furniture
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Furniture {

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
     * @ORM\Column(name="details", type="text")
     */
    private $details;
    
    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    private $name;

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
     * @ORM\Column(name="serialnumber", type="string")
     */
    private $serialnumber;

    /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\AdminBundle\Entity\Status")
     */
    private $status;

    /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Listing")
     */
    private $location;


    public function getId() {
        return $this->id;
    }

    public function setDetails($details) {
        $this->details = $details;

        return $this;
    }

    public function getDetails() {
        return $this->details;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($value) {
        $this->name = $value;
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

    public function getSerialnumber(){
        return $this->serialnumber;
    }

    public function setSerialnumber($serialnumber){
        $this->serialnumber = $serialnumber;
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
        return $this;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setLocation($location) {
        $this->location = $location;
        return $this;
    }

    public function __toString(){
        return $this->name;
    }
}