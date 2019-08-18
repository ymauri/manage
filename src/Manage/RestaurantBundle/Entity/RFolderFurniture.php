<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Manage\RestaurantBundle\Entity\Checkout;
use Manage\RestaurantBundle\Entity\Hotel;

/**
 * RFolderFolder
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class RFolderFurniture {
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Folder")
     */
    private $father;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Furniture")
     */
    private $furniture;
    
    public function getId() {
        return $this->id;
    }

    public function getFather()
    {
        return $this->father;
    }

    public function setFather($father)
    {
        $this->father = $father;
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