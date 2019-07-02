<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Cleaning
 *
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class CleaningLog {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var object
     *  @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Cleaning",  inversedBy="")
     * @JoinColumn(name="cleaning", referencedColumnName="id", onDelete="CASCADE")
     */
    private $cleaning;


    /**
     * @var string
     * @ORM\Column(name="updatedat", type="datetime")
     */
    private $updatedat;

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

    public function getCleaning()
    {
        return $this->cleaning;
    }

    public function setCleaning($cleaning)
    {
        $this->cleaning = $cleaning;
    }
}