<?php

namespace Manage\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Worker
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="Manage\AdminBundle\Entity\ParametersRepository")
 */
class Parameters
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
     * @var string
     *
     * @ORM\Column(name="variable", type="string", length=255)
     */
    private $variable;

    /**
     * @var float
     *
     * @ORM\Column(name="valnumber", type="float",nullable=true)
     */
    private $valnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="valstring", type="string", length=255, nullable=true)
     */
    private $valstring;

    /**
     * @var booblean
     *
     * @ORM\Column(name="isactive", type="boolean",nullable=true)
     */
    private $isactive;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    public function setVariable($name)
    {
        $this->variable = $name;

        return $this;
    }


    public function getVariable()
    {
        return $this->variable;
    }

    public function setValnumber($v)
    {
        $this->valnumber = $v;

        return $this;
    }

    public function getValnumber()
    {
        return $this->valnumber;
    }


    public function setValstring($v)
    {
        $this->valstring = $v;

        return $this;
    }

 
    public function getValstring()
    {
        return $this->valstring;
    }

    public function setIsactive($v)
    {
        $this->isactive = $v;

        return $this;
    }


    public function getIsactive()
    {
        return $this->isactive;
    }

    public function getValue(){
        if (is_null($this->getValnumber())){
            return $this->getValstring();
        }
        return $this->getValnumber();
    }

    public function setValue($val){
        if (is_null($this->getValnumber())){
             $this->setValstring($val);
        }
        else $this->setValnumber($val);
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function __toString() {
        return $this->variable;
    }

}
