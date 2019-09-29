<?php
/**
 * Created by PhpStorm.
 * User: yolanda
 * Date: 24-ene-18
 * Time: 11:25 a.m.
 */

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class RNotifierForm{
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
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var object
     * @ORM\ManyToOne(targetEntity="Notifier")
     */
    private $notifier;

    /**
     * @var integer
     * @ORM\Column(name="form", type="integer")
     */
    private $form;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="sendto", type="string", length=255, nullable=true)
     */
    private $to;

    public function getId() {
        return $this->id;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function getNotifier()
    {
        return $this->notifier;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setForm($form)
    {
        $this->form = $form;
    }

    public function setNotifier($notifier)
    {
        $this->notifier = $notifier;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setTo($to)
    {
        $this->to = $to;
    }
}