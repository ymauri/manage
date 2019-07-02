<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * HotelParking
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class HotelParking {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id      
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var object
     * @ORM\ManyToOne(targetEntity="Manage\RestaurantBundle\Entity\Hotel")
     */
    private $hotel;
    
    /**
     * @var boolean
     * @ORM\Column(name="p1", type="boolean", nullable=true)
     */
    private $p1;
    /**
     * @var boolean
     * @ORM\Column(name="p2", type="boolean", nullable=true)
     */
    private $p2;
     /**
     * @var boolean
     * @ORM\Column(name="p3", type="boolean", nullable=true)
     */
    private $p3;
    /**
     * @var boolean
     * @ORM\Column(name="p4", type="boolean", nullable=true)
     */
    private $p4;
    /**
     * @var boolean
     * @ORM\Column(name="p5", type="boolean", nullable=true)
     */
    private $p5;
    /**
     * @var boolean
     * @ORM\Column(name="p6", type="boolean", nullable=true)
     */
    private $p6;
    /**
     * @var boolean
     * @ORM\Column(name="p7", type="boolean", nullable=true)
     */
    private $p7;
 /**
     * @var boolean
     * @ORM\Column(name="p8", type="boolean", nullable=true)
     */
    private $p8;
 /**
     * @var boolean
     * @ORM\Column(name="p9", type="boolean", nullable=true)
     */
    private $p9;
    /**
     * @var boolean
     * @ORM\Column(name="p10", type="boolean", nullable=true)
     */
    private $p10;


    /**
     * @var boolean
     * @ORM\Column(name="p11", type="boolean", nullable=true)
     */
    private $p11;
    /**
     * @var boolean
     * @ORM\Column(name="p12", type="boolean", nullable=true)
     */
    private $p12;
    /**
     * @var boolean
     * @ORM\Column(name="p13", type="boolean", nullable=true)
     */
    private $p13;
    /**
     * @var boolean
     * @ORM\Column(name="p14", type="boolean", nullable=true)
     */
    private $p14;
    /**
     * @var boolean
     * @ORM\Column(name="p15", type="boolean", nullable=true)
     */
    private $p15;
    /**
     * @var boolean
     * @ORM\Column(name="p16", type="boolean", nullable=true)
     */
    private $p16;
    /**
     * @var boolean
     * @ORM\Column(name="p17", type="boolean", nullable=true)
     */
    private $p17;
    /**
     * @var boolean
     * @ORM\Column(name="p18", type="boolean", nullable=true)
     */
    private $p18;
    /**
     * @var boolean
     * @ORM\Column(name="p19", type="boolean", nullable=true)
     */
    private $p19;
    /**
     * @var boolean
     * @ORM\Column(name="p20", type="boolean", nullable=true)
     */
    private $p20;

    
    public function getId() {
        return $this->id;
    }

    public function getHotel()
    {
        return $this->hotel;
    }

    public function setHotel($hotel)
    {
        $this->hotel = $hotel;
    }

    public function isP1()
    {
        return $this->p1;
    }

    public function setP1($p1)
    {
        $this->p1 = $p1;
    }

    public function isP2()
    {
        return $this->p2;
    }

    public function setP2($p2)
    {
        $this->p2 = $p2;
    }

    public function isP3()
    {
        return $this->p3;
    }

    public function setP3($p3)
    {
        $this->p3 = $p3;
    }

    public function isP4()
    {
        return $this->p4;
    }

    public function setP4($p4)
    {
        $this->p4 = $p4;
    }

    public function isP5()
    {
        return $this->p5;
    }

    public function setP5($p5)
    {
        $this->p5 = $p5;
    }

    public function isP6()
    {
        return $this->p6;
    }

    public function setP6($p6)
    {
        $this->p6 = $p6;
    }

    public function isP7()
    {
        return $this->p7;
    }

    public function setP7($p7)
    {
        $this->p7 = $p7;
    }

    public function isP8()
    {
        return $this->p8;
    }

    public function setP8($p8)
    {
        $this->p8 = $p8;
    }

    public function isP9()
    {
        return $this->p9;
    }

    public function setP9($p9)
    {
        $this->p9 = $p9;
    }

    public function isP10()
    {
        return $this->p10;
    }

    public function setP10($p10)
    {
        $this->p10 = $p10;
    }

    public function isP11()
    {
        return $this->p11;
    }

    public function setP11($p11)
    {
        $this->p11 = $p11;
    }

    public function isP12()
    {
        return $this->p12;
    }

    public function setP12($p12)
    {
        $this->p12 = $p12;
    }

    public function isP13()
    {
        return $this->p13;
    }

    public function setP13($p13)
    {
        $this->p13 = $p13;
    }

    public function isP14()
    {
        return $this->p14;
    }

    public function setP14($p14)
    {
        $this->p14 = $p14;
    }

    public function isP15()
    {
        return $this->p15;
    }

    public function setP15($p15)
    {
        $this->p15 = $p15;
    }

    public function isP16()
    {
        return $this->p16;
    }

    public function setP16($p16)
    {
        $this->p16 = $p16;
    }

    public function isP17()
    {
        return $this->p17;
    }

    public function setP17($p17)
    {
        $this->p17 = $p17;
    }

    public function isP18()
    {
        return $this->p18;
    }

    public function setP18($p18)
    {
        $this->p18 = $p18;
    }

    public function isP19()
    {
        return $this->p19;
    }

    public function setP19($p19)
    {
        $this->p19 = $p19;
    }

    public function isP20()
    {
        return $this->p20;
    }

    public function setP20($p20)
    {
        $this->p20 = $p20;
    }

    public function setParkingTrue($number){
        switch ($number){
            case 1:
                $this->setP1(1);
                break;
            case 2:
                $this->setP2(1);
                break;
            case 2:
                $this->setP2(1);
                break;
            case 3:
                $this->setP3(1);
                break;
            case 4:
                $this->setP4(1);
                break;
            case 5:
                $this->setP5(1);
                break;
            case 6:
                $this->setP6(1);
                break;
            case 7:
                $this->setP7(1);
                break;
            case 8:
                $this->setP8(1);
                break;
            case 9:
                $this->setP9(1);
                break;
            case 10:
                $this->setP10(1);
                break;
            case 11:
                $this->setP11(1);
                break;
            case 12:
                $this->setP12(1);
                break;
            case 13:
                $this->setP13(1);
                break;
            case 14:
                $this->setP14(1);
                break;
            case 15:
                $this->setP15(1);
                break;
            case 16:
                $this->setP16(1);
                break;
            case 17:
                $this->setP17(1);
                break;
            case 18:
                $this->setP18(1);
                break;
            case 19:
                $this->setP19(1);
                break;
            case 20:
                $this->setP20(1);
                break;

        }
    }

    public function setParkingFalse($number){
        switch ($number){
            case 1:
                $this->setP1(0);
                break;
            case 2:
                $this->setP2(0);
                break;
            case 2:
                $this->setP2(0);
                break;
            case 3:
                $this->setP3(0);
                break;
            case 4:
                $this->setP4(0);
                break;
            case 5:
                $this->setP5(0);
                break;
            case 6:
                $this->setP6(0);
                break;
            case 7:
                $this->setP7(0);
                break;
            case 8:
                $this->setP8(0);
                break;
            case 9:
                $this->setP9(0);
                break;
            case 10:
                $this->setP10(0);
                break;
            case 11:
                $this->setP11(0);
                break;
            case 12:
                $this->setP12(0);
                break;
            case 13:
                $this->setP13(0);
                break;
            case 14:
                $this->setP14(0);
                break;
            case 15:
                $this->setP15(0);
                break;
            case 16:
                $this->setP16(0);
                break;
            case 17:
                $this->setP17(0);
                break;
            case 18:
                $this->setP18(0);
                break;
            case 19:
                $this->setP19(0);
                break;
            case 20:
                $this->setP20(0);
                break;

        }
    }
    
}
