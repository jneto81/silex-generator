<?php

namespace Samuca\Fashion\Entity;

use Doctrine\Mapping as ORM;

/**
 * Address
 *
 * @Table(name="address")
 * @Entity(repositoryClass="AddressRepository")
 */ 
class Address
{
  /**
   * @Id
   * @Column(name="id", type="integer")
   * @GeneratedValue(strategy="AUTO")
   */  
  private $id;
  
  /**
   * @Column(name="name", type="string", length=255)
   */
  private $address;
  
  /**
   * @ManyToOne(targetEntity="Shopping", inversedBy="addresses", fetch="LAZY")    
   */
  private $shopping;

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
     * Set address
     *
     * @param string $address
     * @return Address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set shopping
     *
     * @param \Samuca\Fashion\Entity\Shopping $shopping
     * @return Address
     */
    public function setShopping(\Samuca\Fashion\Entity\Shopping $shopping = null)
    {
        $this->shopping = $shopping;
    
        return $this;
    }

    /**
     * Get shopping
     *
     * @return \Samuca\Fashion\Entity\Shopping 
     */
    public function getShopping()
    {
        return $this->shopping;
    }
}