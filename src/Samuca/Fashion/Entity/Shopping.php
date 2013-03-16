<?php

namespace Samuca\Fashion\Entity;

use Doctrine\Mapping as ORM;

/**
 * Shopping
 *
 * @Table(name="shopping")
 * @Entity(repositoryClass="ShoppingRepository")
 */ 
class Shopping
{
  const TYPE_WHOLESAIL = 'wholesail';
  
  const TYPE_RETAIL = 'retail';

  /**
   * @Id
   * @Column(name="id", type="integer")
   * @GeneratedValue(strategy="AUTO")
   */  
  private $id;
  
  /**
   * @Column(name="name")
   */
  private $name;
  
  /**
   * @Column(name="logo", type="string", length=255)
   */
  private $logo;
  
  /**
   * @Column(name="segment", type="string", length=255)
   */
  private $segment;
  
  /**
   * @Column(name="description", type="text")
   */
  private $description;
  
  /**
   * @Column(name="type", type="string", columnDefinition="ENUM('wholesail','retail')")
   */
  private $type;
  
  /**
   * @Column(name="keywords", type="string", length=255)
   */
  private $keywords;
  
  /**
   * @Column(name="region", type="string", length=255)
   */
  private $region;
  
  /**
   * @OneToMany(targetEntity="Address", mappedBy="shopping")
   */
  private $addresses;
  
  /**
   * @OneToMany(targetEntity="Network", mappedBy="shopping")
   */
  private $networks;
  
  /**
   * Constructor
   */
  public function __construct()
  {
      $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
      $this->networks = new \Doctrine\Common\Collections\ArrayCollection();
  }
  
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
   * @return Shopping
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
   * Set logo
   *
   * @param string $logo
   * @return Shopping
   */
  public function setLogo($logo)
  {
      $this->logo = $logo;
  
      return $this;
  }

  /**
   * Get logo
   *
   * @return string 
   */
  public function getLogo()
  {
      return $this->logo;
  }

  /**
   * Set segment
   *
   * @param string $segment
   * @return Shopping
   */
  public function setSegment($segment)
  {
      $this->segment = $segment;
  
      return $this;
  }

  /**
   * Get segment
   *
   * @return string 
   */
  public function getSegment()
  {
      return $this->segment;
  }

  /**
   * Set description
   *
   * @param string $description
   * @return Shopping
   */
  public function setDescription($description)
  {
      $this->description = $description;
  
      return $this;
  }

  /**
   * Get description
   *
   * @return string 
   */
  public function getDescription()
  {
      return $this->description;
  }

  /**
   * Set type
   *
   * @param string $type
   * @return Shopping
   */
  public function setType($type)
  {
      if ( ! in_array($status, array(self::TYPE_WHOLESAIL, self::TYPE_RETAIL))) {
          throw new \InvalidArgumentException("Invalid status");
      }
  
      $this->type = $type;
  
      return $this;
  }

  /**
   * Get type
   *
   * @return string 
   */
  public function getType()
  {
      return $this->type;
  }

  /**
   * Set keywords
   *
   * @param string $keywords
   * @return Shopping
   */
  public function setKeywords($keywords)
  {
      $this->keywords = $keywords;
  
      return $this;
  }

  /**
   * Get keywords
   *
   * @return string 
   */
  public function getKeywords()
  {
      return $this->keywords;
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
   * Add addresses
   *
   * @param \Samuca\Fashion\Entity\Address $addresses
   * @return Shopping
   */
  public function addAddresse(\Samuca\Fashion\Entity\Address $addresses)
  {
      $this->addresses[] = $addresses;
  
      return $this;
  }

  /**
   * Remove addresses
   *
   * @param \Samuca\Fashion\Entity\Address $addresses
   */
  public function removeAddresse(\Samuca\Fashion\Entity\Address $addresses)
  {
      $this->addresses->removeElement($addresses);
  }

  /**
   * Get addresses
   *
   * @return \Doctrine\Common\Collections\Collection 
   */
  public function getAddresses()
  {
      return $this->addresses;
  }

  /**
   * Add networks
   *
   * @param \Samuca\Fashion\Entity\Network $networks
   * @return Shopping
   */
  public function addNetwork(\Samuca\Fashion\Entity\Network $networks)
  {
      $this->networks[] = $networks;
  
      return $this;
  }

  /**
   * Remove networks
   *
   * @param \Samuca\Fashion\Entity\Network $networks
   */
  public function removeNetwork(\Samuca\Fashion\Entity\Network $networks)
  {
      $this->networks->removeElement($networks);
  }

  /**
   * Get networks
   *
   * @return \Doctrine\Common\Collections\Collection 
   */
  public function getNetworks()
  {
      return $this->networks;
  }
}