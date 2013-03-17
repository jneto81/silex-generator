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
   * @ManyToOne(targetEntity="Brand", inversedBy="addresses", fetch="LAZY")    
   */
  private $brand;

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
     * Set brand
     *
     * @param \Samuca\Fashion\Entity\Brand $brand
     * @return Address
     */
    public function setBrand(\Samuca\Fashion\Entity\Brand $brand = null)
    {
        $this->brand = $brand;
    
        return $this;
    }

    /**
     * Get brand
     *
     * @return \Samuca\Fashion\Entity\Brand 
     */
    public function getBrand()
    {
        return $this->brand;
    }
}