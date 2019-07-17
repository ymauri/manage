<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Rule
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class Rule {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var text
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**
     * @var \DateTime
     * @ORM\Column(name="time", type="time", nullable=true)
     */
    private $time;
    
            /**
     * @var string
     * @ORM\Column(name="method", type="text", nullable=true)
     */
    private $method;
    
            /**
     * @var boolean
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    
            /**
     * @var text
     * @ORM\Column(name="action", type="text", nullable=true)
     */
    private $action;
    
            /**
     * @var text
     * @ORM\Column(name="cond", type="text", nullable=true)
     */
    private $cond;


    /**
     * @var string
     * @ORM\Column(name="conditionvalue", type="string", nullable=true)
     */
    private $conditionvalue;


    /**
     * @var string
     * @ORM\Column(name="actionvalue", type="string", nullable=true)
     */
    private $actionvalue;

    /**
     * @var date
     * @ORM\Column(name="begin", type="date", nullable=true)
     */
    private $begin;


    /**
     * @var date
     * @ORM\Column(name="ends", type="date", nullable=true)
     */
    private $ends;

      /**
     * @var string
     * @ORM\Column(name="unit", type="string", nullable=true)
     */
    private $unit;

    /**
     * @var integer
     * @ORM\Column(name="daysahead", type="integer", nullable=true)
     */
    private $daysahead;

    /**
     * @var string
     * @ORM\Column(name="apartments", type="array", nullable=true)
     */
    private $apartments;

    /**
     * @var string
     * @ORM\Column(name="typeofapartment", type="array", nullable=true)
     */
    private $typeofapartment;


    /**
     * @var string
     * @ORM\Column(name="pricesbylisting", type="array", nullable=true)
     */
    private $pricesbylisting;

    /**
     * @var string
     * @ORM\Column(name="pricesbytype", type="array", nullable=true)
     */
    private $pricesbytype;

    /**
     * @var boolean
     * @ORM\Column(name="bytype", type="boolean")
     */
    private $bytype;

    /**
     * @var array
     * @ORM\Column(name="dayweek", type="array", nullable=true)
     */
    private $dayweek;

    /**
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;

    /**
     * @var boolean
     * @ORM\Column(name="ishook", type="boolean")
     */
    private $ishook;

    /**
     * @var integer
     * @ORM\Column(name="startingfrom", type="integer", nullable=true)
     */
    private $startingfrom;

    public function getId() {
        return $this->id;
    }
 
    public function setName($v) {
        $this->name = $v;

        return $this;
    } 
    
    public function getName() {
        return $this->name;
    }
     
    public function setDetails($v) {
        $this->details = $v;

        return $this;
    }

    public function getDetails() {
        return $this->details;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getCond()
    {
        return $this->cond;
    }

    public function setCond($condition)
    {
        $this->cond = $condition;
    }

    public function getConditionvalue()
    {
        return $this->conditionvalue;
    }

    public function setConditionvalue($conditionvalue)
    {
        $this->conditionvalue = $conditionvalue;
    }

    public function getActionvalue()
    {
        return $this->actionvalue;
    }

    public function setActionvalue($actionvalue)
    {
        $this->actionvalue = $actionvalue;
    }
    
    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getBegin()
    {
        return $this->begin;
    }

    public function setBegin($begin)
    {
        $this->begin = $begin;
    }

    public function getEnds()
    {
        return $this->ends;
    }

    public function setEnds($ends)
    {
        $this->ends = $ends;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    public function getDaysahead()
    {
        return $this->daysahead;
    }

    public function setDaysahead($daysahead)
    {
        $this->daysahead = $daysahead;
    }

    public function getApartments()
    {
        return $this->apartments;
    }

    public function setApartments($apartments)
    {
        $this->apartments = $apartments;
    }

    public function getTypeofapartment()
    {
        return $this->typeofapartment;
    }

    public function setTypeofapartment($typeofapartment)
    {
        $this->typeofapartment = $typeofapartment;
    }

    public function getPricesbylisting()
    {
        return $this->pricesbylisting;
    }

    public function setPricesbylisting($pricesbylisting)
    {
        $this->pricesbylisting = $pricesbylisting;
    }

    public function setPricesbytype($pricesbytype)
    {
        $this->pricesbytype = $pricesbytype;
    }

    public function getPricesbytype()
    {
        return $this->pricesbytype;
    }

    public function getBytype()
    {
        return $this->bytype;
    }

    public function setBytype($bytype)
    {
        $this->bytype = $bytype;
    }


    public function __toString() {
        return $this->dated.' '.$this->name;
    }

    public function setDayweek($dayweek)
    {
        $this->dayweek = $dayweek;
    }

    public function getDayweek()
    {
        return $this->dayweek;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function getIshook()
    {
        return $this->ishook;
    }

    public function setIshook($ishook)
    {
        $this->ishook = $ishook;
    }

    public function getStartingfrom()
    {
        return $this->startingfrom;
    }

    public function setStartingfrom($startingfrom)
    {
        $this->startingfrom = $startingfrom;
    }

    /**
     * Verificar si tiene solo una fecha de inicio
     *
     * */
    public function hasOnlyBeginingDate(){
        $now = new \DateTime('now');
        if (((!is_null($this->getBegin()) && is_null($this->getEnds())) && ($now >= $this->getBegin())) || is_null($this->getBegin())) {
            return true;
        }
        return false;
    }

    /**
     *
     * Verificar si tiene solo fecha de fin
    */
    public function hasOnlyEndingDate(){
        $now = new \DateTime('now');
        if (((!is_null($this->getEnds()) && is_null($this->getBegin()) && ($now <= $this->getEnds()))) || is_null($this->getEnds()) ) {
            return true;
        }
        return false;
    }

    /**
     *
     * Verificar si hay rango de fechas
     */
    public function hasRangeDate(){
        $now = new \DateTime('now');
        if (((!is_null($this->getEnds()) && !is_null($this->getBegin())) && ($now >= $this->getBegin() && $now <= $this->getEnds())) || (is_null($this->getEnds()) && is_null($this->getBegin()))) {
            return true;
        }
        return false;
    }

    /**
     *
     * Verificar días de la semana
     * @param \DateTime $date
     * @return bool
     */
    public function hasWeekDates($date = null){
        $now = is_null($date) ? new \DateTime('now') : new \DateTime($date);
        if (!is_null($this->getDayweek()) && count((array)$this->getDayweek()) > 0){
            $days = (array)$this->getDayweek();
            foreach ($days as $day){
                if ($now->format('w') == $day){
                    return true;
                }
            }
            return false;
        }
        if (is_null($this->getDayweek()) || count((array)$this->getDayweek()) == 0)
            return true;
        return false;

    }

    /**
     * Saber si la regla está en tiempo para ser ejecutada
     * 3 minutos
     *
     */
    public function canExecuteNow()
    {
        $now = new \DateTime('now');
        $time = is_null($this->getTime()) ? new \DateTime($now->format('d-m-Y') . ' 00:02') : $this->getTime();
        if (strtotime($now->format('H:i:s')) - strtotime($time->format('H:i:s')) > 0 && strtotime($now->format('H:i:s')) - strtotime($time->format('H:i:s')) < 180) {
            return true;
        }
        return false;
    }

}
