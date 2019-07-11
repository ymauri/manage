<?php

namespace Manage\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Manage\AdminBundle\Entity\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 */
class User implements UserInterface{

    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    protected $id;

    /** @ORM\Column(type="string", length=255) */
    protected $email;

    /** @ORM\Column(type="string", length=255) */
    protected $password;

    /** @ORM\Column(type="string", length=255) */
    protected $name;

    /** @ORM\Column(type="string", length=255) */
    protected $lastname;

    /** @ORM\Column(type="boolean") */
    protected $enable;

    /** @ORM\Column(type="string", length=255) */
    protected $salt;

    /** @ORM\ManyToOne(targetEntity="Role", inversedBy="users")
     *      
     */
    protected $role;

    public function __construct() {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->enabled = true;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {

        if (false == empty($password))
            $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     * @return User
     */
    public function setEnable($enable) {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get salt
     *
     * @return boolean 
     */
    public function getSalt() {
        return $this->salt;
    }

    
        /**
     * Set enable
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean 
     */
    public function getEnable() {
        return $this->enable;
    }
    
    /**
     * Set user_role
     *
     * @param Role $userRole
     * @return User
     */
    public function setUserRole(Role $userRole = null) {
        $this->user_role = $userRole;

        return $this;
    }

    /**
     * Get user_role
     *
     * @return Role 
     */
    public function getUserRole() {
        return $this->user_role;
    }
    
    public function __toString(){
        return $this->getName();
    }
    
    public function getRoles() {
        return array('ROLE_SUPERADMIN', 'ROLE_MANAGER');
    }

    public function getRole() {
        return $this->role;
    }
    
    public function setRole($r){
        $this->role = $r;
        return $this;
    }

    public function getUsername() {
        return $this->email;
    }
    
    public function eraseCredentials() {
        
    }  

}
