<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bill
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Bill
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
     * @var integer
     *
     * @ORM\Column(name="e500", type="integer", nullable=true)
     */
    private $e500;

    /**
     * @var integer
     *
     * @ORM\Column(name="e200", type="integer", nullable=true)
     */
    private $e200;

    /**
     * @var integer
     *
     * @ORM\Column(name="e100", type="integer", nullable=true)
     */
    private $e100;

    /**
     * @var integer
     *
     * @ORM\Column(name="e50", type="integer", nullable=true)
     */
    private $e50;

    /**
     * @var integer
     *
     * @ORM\Column(name="e20", type="integer", nullable=true)
     */
    private $e20;

    /**
     * @var integer
     *
     * @ORM\Column(name="e10", type="integer", nullable=true)
     */
    private $e10;

    /**
     * @var integer
     *
     * @ORM\Column(name="e5", type="integer", nullable=true)
     */
    private $e5;

    /**
     * @var integer
     *
     * @ORM\Column(name="e2", type="integer", nullable=true)
     */
    private $e2;

    /**
     * @var integer
     *
     * @ORM\Column(name="e1", type="integer", nullable=true)
     */
    private $e1;

    /**
     * @var integer
     *
     * @ORM\Column(name="e050", type="integer", nullable=true)
     */
    private $e050;

    /**
     * @var integer
     *
     * @ORM\Column(name="e020", type="integer", nullable=true)
     */
    private $e020;

    /**
     * @var integer
     *
     * @ORM\Column(name="e010", type="integer", nullable=true)
     */
    private $e010;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="e005", type="integer", nullable=true)
     */
    private $e005;

    /**
     * @var float
     *
     * @ORM\Column(name="eind", type="float", nullable=true)
     */
    private $eind;

    /**
     * @var float
     *
     * @ORM\Column(name="waarvan", type="float", nullable=true)
     */
    private $waarvan;


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
     * Set e500
     *
     * @param integer $e500
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
     * @return integer 
     */
    public function getE500()
    {
        return $this->e500;
    }

    /**
     * Set e200
     *
     * @param integer $e200
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
     * @return integer 
     */
    public function getE200()
    {
        return $this->e200;
    }

    /**
     * Set e100
     *
     * @param integer $e100
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
     * @return integer 
     */
    public function getE100()
    {
        return $this->e100;
    }

    /**
     * Set e50
     *
     * @param integer $e50
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
     * @return integer 
     */
    public function getE50()
    {
        return $this->e50;
    }

    /**
     * Set e20
     *
     * @param integer $e20
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
     * @return integer 
     */
    public function getE20()
    {
        return $this->e20;
    }

    /**
     * Set e10
     *
     * @param integer $e10
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
     * @return integer 
     */
    public function getE10()
    {
        return $this->e10;
    }

    /**
     * Set e5
     *
     * @param integer $e5
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
     * @return integer 
     */
    public function getE5()
    {
        return $this->e5;
    }

    /**
     * Set e2
     *
     * @param integer $e2
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
     * @return integer 
     */
    public function getE2()
    {
        return $this->e2;
    }

    /**
     * Set e1
     *
     * @param integer $e1
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
     * @return integer 
     */
    public function getE1()
    {
        return $this->e1;
    }

    /**
     * Set e050
     *
     * @param integer $e050
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
     * @return integer 
     */
    public function getE050()
    {
        return $this->e050;
    }

    /**
     * Set e020
     *
     * @param integer $e020
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
     * @return integer 
     */
    public function getE020()
    {
        return $this->e020;
    }

    /**
     * Set e010
     *
     * @param integer $e010
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
     * @return integer 
     */
    public function getE010()
    {
        return $this->e010;
    }
    
    /**
     * Set e005
     *
     * @param integer $e005
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
     * @return integer 
     */
    public function getE005()
    {
        return $this->e005;
    }

    /**
     * Set eind
     *
     * @param float $eind
     * @return Bill
     */
    public function setEind($eind)
    {
        $this->eind = $eind;

        return $this;
    }
    

    /**
     * Get eind
     *
     * @return float 
     */
    public function getEind()
    {
        return $this->eind;
    }

    /**
     * Set waarvan
     *
     * @param float $waarvan
     * @return Bill
     */
    public function setWaarvan($waarvan)
    {
        $this->waarvan = $waarvan;

        return $this;
    }

    /**
     * Get waarvan
     *
     * @return float 
     */
    public function getWaarvan()
    {
        return $this->waarvan;
    }
}
