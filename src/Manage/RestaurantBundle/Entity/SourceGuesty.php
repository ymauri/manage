<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Source
 *
 * @ORM\Table()
 * @ORM\Entity()
 *  @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Entity\SourceGuestyRepository")
 */
class SourceGuesty {

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
     * @ORM\Column(name="name", type="string")
     */
    private $name;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set details
     *
     * @param string $details
     * @return CashClosure
     */
    public function setName($details) {
        $this->name = $details;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }


    public function __toString(){
        return $this->name;
    }
    

}