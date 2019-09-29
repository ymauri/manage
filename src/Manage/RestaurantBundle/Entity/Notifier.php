<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * 
 * @ORM\Table()
 * @ORM\Entity()
 */
class Notifier  {

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
     * @ORM\Column(name="form", type="string", length=255, nullable=true)
     */
    private $form;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="mails", type="text", nullable=true)
     */
    private $mails;

    /**
     * @var string
     *
     * @ORM\Column(name="externals", type="text", nullable=true)
     */
    private $externals;


   public function __toString() {
        return $this->form;
    }

    public function getId() {
        return $this->id;
    }

    public function getForm() {
        return $this->form;
    }

    public function setForm($v) {
        $this->form = $v;

        return $this;
    }
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }
    public function getDescription() {
        return $this->description;
    }

    public function setMails($v) {
        $this->mails = $v;
        return $this;
    }
    public function getMails() {
        return $this->mails;
    }
    public function setExternals($v) {
        $this->externals = $v;
        return $this;
    }
    public function getExternals() {
        return $this->externals;
    }
}