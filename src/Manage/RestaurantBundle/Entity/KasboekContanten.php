<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KasboekContanten
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class KasboekContanten
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
     * @ORM\Column(name="e500", type="float", nullable=true)
     */
    private $e500;

    /**
     * @var float
     *
     * @ORM\Column(name="e200", type="float", nullable=true)
     */
    private $e200;

    /**
     * @var float
     *
     * @ORM\Column(name="e100", type="float", nullable=true)
     */
    private $e100;

    /**
     * @var float
     *
     * @ORM\Column(name="e50", type="float", nullable=true)
     */
    private $e50;

    /**
     * @var float
     *
     * @ORM\Column(name="e20", type="float", nullable=true)
     */
    private $e20;

    /**
     * @var float
     *
     * @ORM\Column(name="e10", type="float", nullable=true)
     */
    private $e10;

    /**
     * @var float
     *
     * @ORM\Column(name="e5", type="float", nullable=true)
     */
    private $e5;

    /**
     * @var float
     *
     * @ORM\Column(name="e2", type="float", nullable=true)
     */
    private $e2;

    /**
     * @var float
     *
     * @ORM\Column(name="e1", type="float", nullable=true)
     */
    private $e1;

    /**
     * @var float
     *
     * @ORM\Column(name="e050", type="float", nullable=true)
     */
    private $e050;

    /**
     * @var float
     *
     * @ORM\Column(name="e020", type="float", nullable=true)
     */
    private $e020;

    /**
     * @var float
     *
     * @ORM\Column(name="e010", type="float", nullable=true)
     */
    private $e010;
    
    /**
     * @var float
     *
     * @ORM\Column(name="e005", type="float", nullable=true)
     */
    private $e005;
    
 /**
     * @var float
     *
     * @ORM\Column(name="e002", type="float", nullable=true)
     */
    private $e002;
 /**
     * @var float
     *
     * @ORM\Column(name="e001", type="float", nullable=true)
     */
    private $e001;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;

    /**
     * @var float
     *
     * @ORM\Column(name="type", type="float", nullable=true)
     */
    private $type;


    /**
     * Get id
     *
     * @return float 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set e500
     *
     * @param float $e500
     * @return Bill
     */
    public function setE500($e500)
    {
        $this->e500 = $e500;

        return $this;
    }

    /**
     * Get e500
     *
     * @return float 
     */
    public function getE500()
    {
        return $this->e500;
    }

    /**
     * Set e200
     *
     * @param float $e200
     * @return Bill
     */
    public function setE200($e200)
    {
        $this->e200 = $e200;

        return $this;
    }

    /**
     * Get e200
     *
     * @return float 
     */
    public function getE200()
    {
        return $this->e200;
    }

    /**
     * Set e100
     *
     * @param float $e100
     * @return Bill
     */
    public function setE100($e100)
    {
        $this->e100 = $e100;

        return $this;
    }

    /**
     * Get e100
     *
     * @return float 
     */
    public function getE100()
    {
        return $this->e100;
    }

    /**
     * Set e50
     *
     * @param float $e50
     * @return Bill
     */
    public function setE50($e50)
    {
        $this->e50 = $e50;

        return $this;
    }

    /**
     * Get e50
     *
     * @return float 
     */
    public function getE50()
    {
        return $this->e50;
    }

    /**
     * Set e20
     *
     * @param float $e20
     * @return Bill
     */
    public function setE20($e20)
    {
        $this->e20 = $e20;

        return $this;
    }

    /**
     * Get e20
     *
     * @return float 
     */
    public function getE20()
    {
        return $this->e20;
    }

    /**
     * Set e10
     *
     * @param float $e10
     * @return Bill
     */
    public function setE10($e10)
    {
        $this->e10 = $e10;

        return $this;
    }

    /**
     * Get e10
     *
     * @return float 
     */
    public function getE10()
    {
        return $this->e10;
    }

    /**
     * Set e5
     *
     * @param float $e5
     * @return Bill
     */
    public function setE5($e5)
    {
        $this->e5 = $e5;

        return $this;
    }

    /**
     * Get e5
     *
     * @return float 
     */
    public function getE5()
    {
        return $this->e5;
    }

    /**
     * Set e2
     *
     * @param float $e2
     * @return Bill
     */
    public function setE2($e2)
    {
        $this->e2 = $e2;

        return $this;
    }

    /**
     * Get e2
     *
     * @return float 
     */
    public function getE2()
    {
        return $this->e2;
    }

    /**
     * Set e1
     *
     * @param float $e1
     * @return Bill
     */
    public function setE1($e1)
    {
        $this->e1 = $e1;

        return $this;
    }

    /**
     * Get e1
     *
     * @return float 
     */
    public function getE1()
    {
        return $this->e1;
    }

    /**
     * Set e050
     *
     * @param float $e050
     * @return Bill
     */
    public function setE050($e050)
    {
        $this->e050 = $e050;

        return $this;
    }

    /**
     * Get e050
     *
     * @return float 
     */
    public function getE050()
    {
        return $this->e050;
    }

    /**
     * Set e020
     *
     * @param float $e020
     * @return Bill
     */
    public function setE020($e020)
    {
        $this->e020 = $e020;

        return $this;
    }

    /**
     * Get e020
     *
     * @return float 
     */
    public function getE020()
    {
        return $this->e020;
    }

    /**
     * Set e010
     *
     * @param float $e010
     * @return Bill
     */
    public function setE010($e010)
    {
        $this->e010 = $e010;

        return $this;
    }

    /**
     * Get e010
     *
     * @return float 
     */
    public function getE010()
    {
        return $this->e010;
    }
    
    /**
     * Set e005
     *
     * @param float $e005
     * @return Bill
     */
    public function setE005($e005)
    {
        $this->e005 = $e005;

        return $this;
    }

    /**
     * Get e005
     *
     * @return float 
     */
    public function getE005()
    {
        return $this->e005;
    }
    
/**
     * Set e005
     *
     * @param float $e005
     * @return Bill
     */
    public function setE002($e002)
    {
        $this->e002 = $e002;

        return $this;
    }

    /**
     * Get e005
     *
     * @return float 
     */
    public function getE002()
    {
        return $this->e002;
    }
/**
     * Set e005
     *
     * @param float $e005
     * @return Bill
     */
    public function setE001($e001)
    {
        $this->e001 = $e001;

        return $this;
    }

    /**
     * Get e005
     *
     * @return float 
     */
    public function getE001()
    {
        return $this->e001;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return Bill
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }
    

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set type
     *
     * @param float $type
     * @return Bill
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return float 
     */
    public function getType()
    {
        return $this->type;
    }
}
