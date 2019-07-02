<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * KasboekSalarissen
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class KasboekSalarissen {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name1", type="string", length=255, nullable=true)
     */
    private $name1;

    /**
     * @var string
     * @ORM\Column(name="name2", type="string", length=255, nullable=true)
     */
    private $name2;

    /**
     * @var string
     * @ORM\Column(name="name3", type="string", length=255, nullable=true)
     */
    private $name3;

    /**
     * @ORM\Column(name="bedrag1", type="float", nullable=true)
     */
    private $bedrag1;

    /**
     * @ORM\Column(name="bedrag2", type="float", nullable=true)
     */
    private $bedrag2;

    /**
     * @ORM\Column(name="bedrag3", type="float", nullable=true)
     */
    private $bedrag3;

    /**
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;



    public function getId() {
        return $this->id;
    }

    public function getName1(){
        return $this->name1;
    }

    public function setName1($name1){
        $this->name1 = $name1;
        return $this;
    }

    public function getName2(){
        return $this->name2;
    }

    public function setName2($name2){
        $this->name2 = $name2;
        return $this;
    }

    public function getName3(){
        return $this->name3;
    }

    public function setName3($name3){
        $this->name3 = $name3;
        return $this;
    }

    public function getBedrag1(){
        return $this->bedrag1;
    }

    public function setBedrag1($bedrag1){
        $this->bedrag1 = $bedrag1;
        return $this;
    }

    public function getBedrag2(){
        return $this->bedrag2;
    }

    public function setBedrag2($bedrag2){
        $this->bedrag2 = $bedrag2;
        return $this;
    }

    public function getBedrag3(){
        return $this->bedrag3;
    }

    public function setBedrag3($bedrag3){
        $this->bedrag3 = $bedrag3;
        return $this;
    }

    public function getTotal(){
        return $this->total;
    }

    public function setTotal($total){
        $this->total = $total;
        return $this;
    }
}
