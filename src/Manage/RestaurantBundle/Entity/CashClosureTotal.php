<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CashClosureTotal
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CashClosureTotal
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="xlaag", type="float", nullable=true)
     */
    private $xlaag;

    /**
     * @var float
     *
     * @ORM\Column(name="xkitchen", type="float",  nullable=true)
     */
    private $xkitchen;

    /**
     * @var float
     *
     * @ORM\Column(name="xhoog", type="float", nullable=true)
     */
    private $xhoog;

    /**
     * @var float
     *
     * @ORM\Column(name="xparking", type="float", nullable=true)
     */
    private $xparking;

    /**
     * @var float
     *
     * @ORM\Column(name="xentry", type="float",  nullable=true)
     */
    private $xentry;

    /**
     * @var float
     *
     * @ORM\Column(name="xspacesrent", type="float", nullable=true)
     */
    private $xspacesrent;

    /**
     * @var float
     *
     * @ORM\Column(name="xothers", type="float",  nullable=true)
     */
    private $xothers;

    /**
     * @var float
     *
     * @ORM\Column(name="xtotal", type="float", nullable=true)
     */
    private $xtotal;

    /**
     * @var float
     *
     * @ORM\Column(name="zlaag", type="float", nullable=true)
     */
    private $zlaag;

    /**
     * @var float
     *
     * @ORM\Column(name="zkitchen", type="float", nullable=true)
     */
    private $zkitchen;

    /**
     * @var float
     *
     * @ORM\Column(name="zhoog", type="float", nullable=true)
     */
    private $zhoog;

    /**
     * @var float
     *
     * @ORM\Column(name="zparking", type="float", nullable=true)
     */
    private $zparking;

    /**
     * @var float
     *
     * @ORM\Column(name="zentry", type="float", nullable=true)
     */
    private $zentry;

    /**
     * @var float
     *
     * @ORM\Column(name="zspacesrent", type="float", nullable=true)
     */
    private $zspacesrent;

    /**
     * @var float
     *
     * @ORM\Column(name="zothers", type="float", nullable=true)
     */
    private $zothers;

    /**
     * @var float
     *
     * @ORM\Column(name="ztotal", type="float", nullable=true)
     */
    private $ztotal;

   
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
     * Set xlaag
     *
     * @param float $xlaag
     * @return Total
     */
    public function setXlaag($xlaag)
    {
        $this->xlaag = $xlaag;

        return $this;
    }

    /**
     * Get xlaag
     *
     * @return float 
     */
    public function getXlaag()
    {
        return $this->xlaag;
    }

    /**
     * Set xkitchen
     *
     * @param float $xkitchen
     * @return Total
     */
    public function setXkitchen($xkitchen)
    {
        $this->xkitchen = $xkitchen;

        return $this;
    }

    /**
     * Get xkitchen
     *
     * @return float 
     */
    public function getXkitchen()
    {
        return $this->xkitchen;
    }

    /**
     * Set xhoog
     *
     * @param float $xhoog
     * @return Total
     */
    public function setXhoog($xhoog)
    {
        $this->xhoog = $xhoog;

        return $this;
    }

    /**
     * Get xhoog
     *
     * @return float 
     */
    public function getXhoog()
    {
        return $this->xhoog;
    }

    /**
     * Set xparking
     *
     * @param float $xparking
     * @return Total
     */
    public function setXparking($xparking)
    {
        $this->xparking = $xparking;

        return $this;
    }

    /**
     * Get xparking
     *
     * @return float 
     */
    public function getXparking()
    {
        return $this->xparking;
    }

    /**
     * Set xentry
     *
     * @param float $xentry
     * @return Total
     */
    public function setXentry($xentry)
    {
        $this->xentry = $xentry;

        return $this;
    }

    /**
     * Get xentry
     *
     * @return float 
     */
    public function getXentry()
    {
        return $this->xentry;
    }

    /**
     * Set xspacesrent
     *
     * @param float $xspacesrent
     * @return Total
     */
    public function setXspacesrent($xspacesrent)
    {
        $this->xspacesrent = $xspacesrent;

        return $this;
    }

    /**
     * Get xspacesrent
     *
     * @return float 
     */
    public function getXspacesrent()
    {
        return $this->xspacesrent;
    }

    /**
     * Set xothers
     *
     * @param float $xothers
     * @return Total
     */
    public function setXothers($xothers)
    {
        $this->xothers = $xothers;

        return $this;
    }

    /**
     * Get xothers
     *
     * @return float 
     */
    public function getXothers()
    {
        return $this->xothers;
    }

    /**
     * Set xtotal
     *
     * @param float $xtotal
     * @return Total
     */
    public function setXtotal($xtotal)
    {
        $this->xtotal = $xtotal;

        return $this;
    }

    /**
     * Get xtotal
     *
     * @return float 
     */
    public function getXtotal()
    {
        return $this->xtotal;
    }

    /**
     * Set zlaag
     *
     * @param float $zlaag
     * @return Total
     */
    public function setZlaag($zlaag)
    {
        $this->zlaag = $zlaag;

        return $this;
    }

    /**
     * Get zlaag
     *
     * @return float 
     */
    public function getZlaag()
    {
        return $this->zlaag;
    }

    /**
     * Set zkitchen
     *
     * @param float $zkitchen
     * @return Total
     */
    public function setZkitchen($zkitchen)
    {
        $this->zkitchen = $zkitchen;

        return $this;
    }

    /**
     * Get zkitchen
     *
     * @return float 
     */
    public function getZkitchen()
    {
        return $this->zkitchen;
    }

    /**
     * Set zhoog
     *
     * @param float $zhoog
     * @return Total
     */
    public function setZhoog($zhoog)
    {
        $this->zhoog = $zhoog;

        return $this;
    }

    /**
     * Get zhoog
     *
     * @return float 
     */
    public function getZhoog()
    {
        return $this->zhoog;
    }

    /**
     * Set zparking
     *
     * @param float $zparking
     * @return Total
     */
    public function setZparking($zparking)
    {
        $this->zparking = $zparking;

        return $this;
    }

    /**
     * Get zparking
     *
     * @return float 
     */
    public function getZparking()
    {
        return $this->zparking;
    }

    /**
     * Set zentry
     *
     * @param float $zentry
     * @return Total
     */
    public function setZentry($zentry)
    {
        $this->zentry = $zentry;

        return $this;
    }

    /**
     * Get zentry
     *
     * @return float 
     */
    public function getZentry()
    {
        return $this->zentry;
    }

    /**
     * Set zspacesrent
     *
     * @param float $zspacesrent
     * @return Total
     */
    public function setZspacesrent($zspacesrent)
    {
        $this->zspacesrent = $zspacesrent;

        return $this;
    }

    /**
     * Get zspacesrent
     *
     * @return float 
     */
    public function getZspacesrent()
    {
        return $this->zspacesrent;
    }

    /**
     * Set zothers
     *
     * @param float $zothers
     * @return Total
     */
    public function setZothers($zothers)
    {
        $this->zothers = $zothers;

        return $this;
    }

    /**
     * Get zothers
     *
     * @return float 
     */
    public function getZothers()
    {
        return $this->zothers;
    }

    /**
     * Set ztotal
     *
     * @param float $ztotal
     * @return Total
     */
    public function setZtotal($ztotal)
    {
        $this->ztotal = $ztotal;

        return $this;
    }

    /**
     * Get ztotal
     *
     * @return float 
     */
    public function getZtotal()
    {
        return $this->ztotal;
    }

}
