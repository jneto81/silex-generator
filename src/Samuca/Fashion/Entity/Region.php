<?php

namespace Samuca\Fashion\Entity;

use Doctrine\Mapping as ORM;

/**
 * Region
 *
 * @Table(name="region")
 * @Entity(repositoryClass="RegionRepository")
 */ 
class Region
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
   * @OneToOne(targetEntity="Brand", inversedBy="region", fetch="LAZY")    
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
   * Set name
   *
   * @param string $name
   * @return Media
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
     * Set brand
     *
     * @param \Samuca\Fashion\Entity\Brand $brand
     * @return Region
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