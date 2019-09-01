<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * ReportIssue
 *
 * @ORM\Table(name="ReportIssue")
 * @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Repository\ReportIssueRepository")
 */
class ReportIssue
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dated", type="date")
     */
    private $dated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reportedat", type="time")
     */
    private $reportedat;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Folder")
     */
    private $location;

   /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Furniture")
    *  @ORM\JoinColumn(name="furniture_id", referencedColumnName="id", nullable=true)
     */
    private $furniture;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**
     * @Assert\Image()
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="pathimage", type="string", length=255)
     */
    private $pathimage;

    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="string", length=255)
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Manage\AdminBundle\Entity\Worker")
     */
    private $reporter;


    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status = "Open";

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dated
     *
     * @param \DateTime $dated
     * @return ResportIssue
     */
    public function setDated($dated)
    {
        $this->dated = $dated;

        return $this;
    }

    /**
     * Get dated
     *
     * @return \DateTime 
     */
    public function getDated()
    {
        return $this->dated;
    }

    /**
     * Set reportedat
     *
     * @param \DateTime $reportedat
     * @return ResportIssue
     */
    public function setReportedat($reportedat)
    {
        $this->reportedat = $reportedat;

        return $this;
    }

    /**
     * Get reportedat
     *
     * @return \DateTime 
     */
    public function getReportedat()
    {
        return $this->reportedat;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return ResportIssue
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set details
     *
     * @param string $details
     * @return ResportIssue
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set image
     *
     * @param UploadedFile $image
     * @return ResportIssue
     */
    public function setImage(UploadedFile $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set priority
     *
     * @param string $priority
     * @return ResportIssue
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set reporter
     *
     * @param string $reporter
     * @return ResportIssue
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return string 
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    public function getPathimage()
    {
        return $this->pathimage;
    }

    public function setPathimage($pathimage)
    {
        $this->pathimage = $pathimage;
    }

    public function uploadImage($destiny){
        if (null === $this->image) {
            return;
        }
        $nombreArchivoFoto = uniqid().'.jpg';
        $this->image->move($destiny, $nombreArchivoFoto);
        $this->setPathimage($nombreArchivoFoto);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getFurniture()
    {
        return $this->furniture;
    }

    public function setFurniture($furniture)
    {
        $this->furniture = $furniture;
    }
}
