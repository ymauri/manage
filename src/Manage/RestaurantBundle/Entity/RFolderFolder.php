<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Manage\RestaurantBundle\Entity\Checkout;
use Manage\RestaurantBundle\Entity\Hotel;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * RFolderFolder
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class RFolderFolder {
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
     * @JoinColumn(name="father_id", onDelete="CASCADE")

     */
    private $father;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Folder")
     * @JoinColumn(name="child_id", onDelete="CASCADE")

     */
    private $child;
    
    public function getId() {
        return $this->id;
    }

    public function getChild()
    {
        return $this->child;
    }

    public function setChild($child)
    {
        $this->child = $child;
    }

    public function getFather()
    {
        return $this->father;
    }

    public function setFather($father)
    {
        $this->father = $father;
    }
}