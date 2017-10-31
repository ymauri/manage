<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * CashClosure
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class CashClosure {

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text")
     */
    private $details;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dated", type="date")
     * 
     */
    private $dated;

    /**
     * @var object
     * @ORM\OneToOne(targetEntity="CashClosureTotal")
     */
    private $total;

    /**
     * @var object
     * @ORM\OneToOne(targetEntity="CashClosureBill")
     */
    private $bill;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CashClosure
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set details
     *
     * @param string $details
     * @return CashClosure
     */
    public function setDetails($details) {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * Set dated
     *
     * @param \DateTime $dated
     * @return \DateTime
     */
    public function setDated($dated) {
        $this->dated = $dated;

        return $this;
    }

    /**
     * Get dated
     *
     * @return \DateTime 
     */
    public function getDated() {
        return $this->dated;
    }

    /**
     * Get total
     *
     * @return object 
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Set total
     *
     * @param object $total
     * @return ClashClosureTotal
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get bill
     *
     * @return object 
     */
    public function getBill() {
        return $this->bill;
    }

    /**
     * Set bill
     *
     * @param object $bill
     * @return CashClosureBill
     */
    public function setBill($bill) {
        $this->bill = $bill;

        return $this;
    }

}
