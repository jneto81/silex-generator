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
   * @ManyToOne(targetEntity="Segment")
   * @JoinColumn(name="segment_id", referencedColumnName="id")
   */
  private $segment;
  
  /**
   * @Column(name="description", type="text", nullable=true)
   */
  private $description;
  
  /**
   * @Column(name="type", type="string", columnDefinition="ENUM('wholesale','retail')")
   */
  private $type;
  
  /**
   * @Column(name="keyword", type="string", length=255)
   */
  private $keyword;
   
  /**
   * @OneToMany(targetEntity="Address", mappedBy="brand", cascade={"persist","remove"})
   */
  private $addresses;
  
  /**
   * @OneToMany(targetEntity="Network", mappedBy="brand", cascade={"persist","remove"})
   */
  private $networks;
  
  /**
   * @OneToMany(targetEntity="Media", mappedBy="brand", cascade={"remove"})
   */
  private $medias;
  
  /**
   * @OneToMany(targetEntity="Poster", mappedBy="brand", cascade={"persist","remove"})
   */
  private $posters;
  
  /**
   * Constructor
   */
  public function __construct()
  {
      $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
      $this->networks = new \Doctrine\Common\Collections\ArrayCollection();
      $this->posters = new \Doctrine\Common\Collections\ArrayCollection();
      $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
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

  public function setAddresses(\Doctrine\Common\Collections\ArrayCollection $addresses)
  {
    foreach ($addresses as $address) {
      $address->setBrand($this);
    }

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
    $address->setBrand($this);
    $this->addresses[] = $address;
  
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
    foreach ($networks as $network) {
      $network->setBrand($this);
    }

    $this->networks = $networks;
    
    return $this;
  }
  
  /**
   * Add network
   *
   * @param \Samuca\Fashion\Entity\Network $network
   * @return Shopping
   */
  public function addNetwork(\Samuca\Fashion\Entity\Network $network)
  {
    $network->setBrand($this);
    $this->networks[] = $network;
  
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
   * Add media
   *
   * @param \Samuca\Fashion\Entity\Media $media
   * @return Brand
   */
  public function addMedia(\Samuca\Fashion\Entity\Media $media)
  {
    $this->medias[] = $media;

    return $this;
  }

  /**
   * Remove media
   *
   * @param \Samuca\Fashion\Entity\Media $media
   */
  public function removeMedia(\Samuca\Fashion\Entity\Media $media)
  {
    $this->medias->removeElement($media);
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
   * Add poster
   *
   * @param \Samuca\Fashion\Entity\Poster $poster
   * @return Brand
   */
  public function addPoster(\Samuca\Fashion\Entity\Poster $poster)
  {
    $poster->setBrand($this);
    $this->posters[] = $poster;

    return $this;
  }

  /**
   * Remove poster
   *
   * @param \Samuca\Fashion\Entity\Poster $poster
   */
  public function removePoster(\Samuca\Fashion\Entity\Poster $poster)
  {
    $this->posters->removeElement($poster);
  }

  /**
   * Get posters
   *
   * @return \Doctrine\Common\Collections\Collection 
   */
  public function getPosters()
  {
    return $this->posters;
  }
  
  public function setPosters(\Doctrine\Common\Collections\ArrayCollection $posters)
  {
    foreach ($posters as $poster) {
      $poster->setBrand($this);
    }

    $this->posters = $posters;
    
    return $this;
  }
}
