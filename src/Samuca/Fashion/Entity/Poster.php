<?php

namespace Samuca\Fashion\Entity;

use Doctrine\Mapping as ORM;

/**
 * Poster
 *
 * @Table(name="Poster")
 * @Entity(repositoryClass="PosterRepository")
 */ 
class Poster
{
  // 958x210
  const SIZE_LARGE = 'Large';
  
  // 240x540
  const SIZE_MEDIUM = 'Medium';

  /**
   * @Id
   * @Column(name="id", type="integer")
   * @GeneratedValue(strategy="AUTO")
   */  
  private $id;
  
  /**
   * @Column(name="size", type="string", columnDefinition="ENUM('Large','Medium')")
   */
  private $size;
  
  /**
   * @Column(name="link", type="string", length=255)
   */
  private $link;
  
  /**
   * @Column(name="src", type="string", length=255)
   */
  private $src;
  
  /**
   * @ManyToOne(targetEntity="Brand", inversedBy="posters", fetch="LAZY")
   * @JoinColumn(name="brand_id", referencedColumnName="id", nullable=true)
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
   * Set size
   *
   * @param string $size
   * @return Poster
   */
  public function setSize($size)
  {
    if ( ! in_array($size, array(self::SIZE_LARGE, self::SIZE_MEDIUM))) {
      throw new \InvalidArgumentException("Invalid size");
    }

    $this->size = $size;

    return $this;
  }

  /**
   * Get size
   *
   * @return string 
   */
  public function getSize()
  {
      return $this->size;
  }
  
  /**
   * Set link
   *
   * @param string $link
   * @return Poster
   */
  public function setLink($link)
  {
      $this->link = $link;
  
      return $this;
  }

  /**
   * Get link
   *
   * @return string 
   */
  public function getLink()
  {
      return $this->link;
  }
  
  /**
   * Set src
   *
   * @param string $src
   * @return Poster
   */
  public function setSrc($src)
  {
      $this->src = $src;
  
      return $this;
  }

  /**
   * Get src
   *
   * @return string 
   */
  public function getSrc()
  {
      return $this->src;
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
   * Set brand
   *
   * @param \Samuca\Fashion\Entity\Brand $brand
   * @return Poster
   */
  public function setBrand(\Samuca\Fashion\Entity\Brand $brand = null)
  {
    $this->brand = $brand;

    return $this;
  }
}