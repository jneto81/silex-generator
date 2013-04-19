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
  private $name;
  
  /**
   * @Column(name="location", type="string", length=255)
   */
  private $location;
  
  /**
   * @ManyToOne(targetEntity="Region", cascade={"remove"})
   * @JoinColumn(name="region_id", referencedColumnName="id")
   */
  private $region;
  
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

    /**
     * Set name
     *
     * @param string $name
     * @return Address
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
  /**
   * Set region
   *
   * @param string $region
   * @return Shopping
   */
  public function setRegion($region)
  {
      $this->region = $region;
  
      return $this;
  }

  /**
   * Get region
   *
   * @return string 
   */
  public function getRegion()
  {
      return $this->region;
  }

    /**
     * Set location
     *
     * @param string $location
     * @return Address
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }
}