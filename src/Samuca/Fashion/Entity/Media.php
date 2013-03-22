<?php

namespace Samuca\Fashion\Entity;

use Doctrine\Mapping as ORM;

/**
 * Media
 *
 * @Table(name="media")
 * @Entity(repositoryClass="MediaRepository")
 */ 
class Media
{
  /**
   * @Id
   * @Column(name="id", type="integer")
   * @GeneratedValue(strategy="AUTO")
   */  
  private $id;
  
  /**
   * @Column(name="title", type="string", length=255)
   */
  private $title;
  
  /**
   * @Column(name="src", type="string", length=255)
   */
  private $src;
  
  /**
   * @Column(name="caption", type="string", length=255)
   */
  private $caption;
  
  /**
   * @ManyToOne(targetEntity="Brand", inversedBy="medias", fetch="LAZY")    
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
     * Set title
     *
     * @param string $title
     * @return Media
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set src
     *
     * @param string $src
     * @return Media
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
     * Set caption
     *
     * @param string $caption
     * @return Media
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    
        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set brand
     *
     * @param \Samuca\Fashion\Entity\Brand $brand
     * @return Media
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