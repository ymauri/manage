<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping\JoinColumn;
use Manage\RestaurantBundle\Controller\Nomenclator;

/**
 * ReportPlanning
 *
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class ReportPlanning
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
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Furniture")
     * @ORM\JoinColumn(name="furniture_id", referencedColumnName="id", nullable=true)
     */
    private $furniture;


    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Folder")
     * @ORM\JoinColumn(name="folder_id", referencedColumnName="id", nullable=true)
     */
    private $folder;


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
     * @ORM\Column(name="pathimage", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status = "Open";


    /**
     * @var string
     *
     * @ORM\Column(name="frequency", type="string", length=255)
     */
    private $frequency;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="date", nullable=true)
     */
    private $updated;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begins", type="date", nullable=true)
     */
    private $begins;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ends", type="date", nullable=true)
     */
    private $ends;


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

    public function uploadImage($destiny)
    {
        if (null === $this->image) {
            return;
        }
        $nombreArchivoFoto = uniqid() . '.jpg';
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

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    public function getFolder()
    {
        return $this->folder;
    }

    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    public function canApplyToday()
    {
        $today = new \DateTime();
        if ((empty($this->getBegins() && empty($this->getEnds()))) || (!empty($this->getBegins()) && $today >= $this->getBegins()) || (!empty($this->getEnds()) && $today <= $this->getEnds())) {
            switch ($this->frequency) {
                case Nomenclator::PLANNING_WEEKLY:
                    //Si hoy es lunes entonces crear
                    if (/*$today->format('w') == 1 &&*/
                    (empty($this->updated) || $this->getUpdated()->diff($today)->d >= 7)
                    )
                        return true;
                    break;
                case Nomenclator::PLANNING_MONTHLY:
                    //Si hoy es 1 de cualquier mes entonces cerar
                    if (/*$today->format('d') == 1 &&*/
                    (empty($this->updated) || $this->getUpdated()->diff($today)->m >= 1)
                    )
                        return true;
                    break;
                case Nomenclator::PLANNING_QUATERLY:
                    //Si hoy es 1 de enero, abril, julio u octubre, entonces crear
                    if (/*$today->format('d') == 1 && ($today->format('n') == 1 || $today->format('n') == 4 || $today->format('n') == 7 || $today->format('n') == 10) &&*/
                    (empty($this->updated) || $this->getUpdated()->diff($today)->m >= 3)
                    )
                        return true;
                    break;
                case Nomenclator::PLANNING_BIANNUAL:
                    //Si hoy es 1 de enero o julio, entonces crear
                    if (/*$today->format('d') == 1 && ($today->format('n') == 1 || $today->format('n') == 7) &&*/
                    (empty($this->updated) || $this->getUpdated()->diff($today)->m >= 6)
                    )
                        return true;
                    break;
                case Nomenclator::PLANNING_YEARLY:
                    //Si ho yes 1 de enero entonces crear
                    if (/*$today->format('d') == 1 && $today->format('n') == 1 && */
                    (empty($this->updated) || $this->getUpdated()->diff($today)->y >= 1)
                    )
                        return true;
                    break;
            }
        }
        return false;
    }

    public function getBegins()
    {
        return $this->begins;
    }

    public function setBegins($begins)
    {
        $this->begins = $begins;
    }

    public function getEnds()
    {
        return $this->ends;
    }

    public function setEnds($ends)
    {
        $this->ends = $ends;
    }
}
