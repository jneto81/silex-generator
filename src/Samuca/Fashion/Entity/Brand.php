<?php

namespace Samuca\Fashion\Entity;

use Doctrine\Mapping as ORM;

/**
 * Shopping
 *
 * @Table(name="brand")
 * @Entity(repositoryClass="BrandRepository")
 */ 
class Brand
{
  const TYPE_WHOLESALE = 'wholesale';
  
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
   * @OneToOne(targetEntity="Segment")
   * @JoinColumn(name="segment_id", referencedColumnName="id")
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
   * @Column(name="keyword", type="string", length=255)
   */
  private $keyword;
   
  /**
   * @OneToOne(targetEntity="Region")
   * @JoinColumn(name="region_id", referencedColumnName="id")
   */
  private $region;
  
  /**
   * @OneToMany(targetEntity="Address", mappedBy="brand")
   */
  private $addresses;
  
  /**
   * @OneToMany(targetEntity="Network", mappedBy="brand", cascade={"persist"})
   */
  private $networks;
  
  /**
   * @OneToMany(targetEntity="Media", mappedBy="brand", cascade={"persist"})
   */
  private $medias;
  
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
      if ( ! in_array($type, array(self::TYPE_WHOLESALE, self::TYPE_RETAIL))) {
          throw new \InvalidArgumentException("Invalid type");
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
   * Set keyword
   *
   * @param string $keyword
   * @return Shopping
   */
  public function setKeyword($keyword)
  {
      $this->keyword = $keyword;
  
      return $this;
  }

  /**
   * Get keyword
   *
   * @return string 
   */
  public function getKeyword()
  {
      return $this->keyword;
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

  public function setAddresses(\Doctrine\Common\Collections\ArrayCollection $addresses)
  {
    /*
    foreach ($addresses as $address) {
      $address->setBrand($this);
    }
    */

    $this->addresses = $addresses;
    
    return $this;
  }
  
  /**
   * Add address
   *
   * @param \Samuca\Fashion\Entity\Address $address
   * @return Shopping
   */
  public function addAddress(\Samuca\Fashion\Entity\Address $address)
  {
      $this->address[] = $address;
  
      return $this;
  }

  /**
   * Remove address
   *
   * @param \Samuca\Fashion\Entity\Address $address
   */
  public function removeAddress(\Samuca\Fashion\Entity\Address $address)
  {
      $this->addresses->removeElement($address);
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

  public function setNetworks(\Doctrine\Common\Collections\ArrayCollection $networks)
  {
    /*
    foreach ($networks as $network) {
      $network->setBrand($this);
    }
    */

    $this->networks = $networks;
    
    return $this;
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

    /**
     * Add medias
     *
     * @param \Samuca\Fashion\Entity\Media $medias
     * @return Brand
     */
    public function addMedia(\Samuca\Fashion\Entity\Media $medias)
    {
        $this->medias[] = $medias;
    
        return $this;
    }

    /**
     * Remove medias
     *
     * @param \Samuca\Fashion\Entity\Media $medias
     */
    public function removeMedia(\Samuca\Fashion\Entity\Media $medias)
    {
        $this->medias->removeElement($medias);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Add addresses
     *
     * @param \Samuca\Fashion\Entity\Address $addresses
     * @return Brand
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
}