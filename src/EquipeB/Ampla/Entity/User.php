<?php

namespace EquipeB\Ampla\Entity;

use Doctrine\Mapping as ORM;

/**
 * User
 *
 * @Table(name="user")
 * @Entity()
 */
class User
{
    /** 
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
		
		/** 
     * @Column(name="username", type="string", length=100, unique=TRUE) 
     */
    private $username;
		
		/** 
     * @Column(name="password", type="string", length=255) 
     */
    private $password;
		
		/** 
     * @Column(name="roles", type="string", length=255) 
     */
    private $roles;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roles
     *
     * @param string $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    
        return $this;
    }

    /**
     * Get roles
     *
     * @return string 
     */
    public function getRoles()
    {
        return $this->roles;
    }
}