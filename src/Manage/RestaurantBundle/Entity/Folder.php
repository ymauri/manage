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
 * @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Repository\FolderRepository")
 */
class Folder {

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
     * @var string
     *
     * @ORM\Column(name="isroot", type="boolean")
     */
    private $isroot;

    /**
     * @var string
     *
     * @ORM\Column(name="issheet", type="boolean")
     */
    private $issheet;

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

    public function getIsroot()
    {
        return $this->isroot;
    }

    public function setIsroot($isroot)
    {
        $this->isroot = $isroot;
    }

    public function getIssheet()
    {
        return $this->issheet;
    }

    public function setIssheet($issheet)
    {
        $this->issheet = $issheet;
    }

    public function __toString(){
        return $this->details;
    }
}