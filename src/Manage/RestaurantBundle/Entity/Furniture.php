<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Manage\RestaurantBundle\Entity\Tag;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
/**
 * Furniture
 *
 * @ORM\Table()
 * @ORM\Entity() 
 * @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Repository\FurnitureRepository")
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
     * @ORM\Column(name="details", type="text", nullable=true)
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
     * @ORM\Column(name="serialnumber", type="string", nullable=true)
     */
    private $serialnumber;

    /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Status")
     */
    private $status;

    /**
     * @var object
     * @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Folder")
     * @ORM\JoinColumn(name="folder_id", onDelete="CASCADE")
     */
    private $folder;

    /**
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     *
     * @ORM\Column(name="totalvalue", type="float")
     */
    private $totalvalue;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="furnitures")
     * @ORM\JoinTable(name="RFurnitureTags")
     */
    private $tags;

    /**
     * @ORM\Column(name="pathfolder", type="string", nullable=true)
     */
    private $pathfolder;


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

    public function getFolder() {
        return $this->folder;
    }

    public function setFolder($folder) {
        $this->folder = $folder;
        return $this;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setTotalvalue($totalvalue)
    {
        $this->totalvalue = $totalvalue;
    }

    public function getTotalvalue()
    {
        return $this->totalvalue;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getTagsArray()
    {
        $result = array();
        foreach ($this->tags as $tag) {
            $result[] = $tag->getName();
        }
        return $result;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getPathfolder()
    {
        return $this->pathfolder;
    }

    public function setPathfolder($pathfolder)
    {
        if ($pathfolder === "" || is_null($pathfolder))
            $this->pathfolder = '/'.$this->folder->getId().'/';
        else $this->pathfolder = $pathfolder;
    }

    public function __toString(){
        return $this->name;
    }
}