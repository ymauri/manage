<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * RuleLog
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="Manage\RestaurantBundle\Repository\RuleLogRepository")
 */
class RuleLog {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Rule")
     */
    private $rule;


    /**
     * @var \DateTime
     * @ORM\Column(name="checkin", type="date")
     */
    private $checkin;

    /**
     *
     * @ORM\Column(name="listing", type="string")
     */
    private $listing;

    /**
     * @var integer
     * @ORM\Column(name="oldprice", type="integer", nullable=true)
     */
    private $oldprice;


    public function getId() {
        return $this->id;
    }

    public function getRule()
    {
        return $this->rule;
    }

    public function setRule($rule)
    {
        $this->rule = $rule;
    }

    public function getCheckin()
    {
        return $this->checkin;
    }

    public function setCheckin($checkin)
    {
        $this->checkin = $checkin;
    }

    public function getListing()
    {
        return $this->listing;
    }

    public function setListing($listing)
    {
        $this->listing = $listing;
    }

    public function getOldprice()
    {
        return $this->oldprice;
    }

    public function setOldprice($oldprice)
    {
        $this->oldprice = $oldprice;
    }
}
