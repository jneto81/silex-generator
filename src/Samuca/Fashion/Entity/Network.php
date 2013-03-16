<?php

namespace Samuca\Fashion\Entity;

use Doctrine\Mapping as ORM;

/**
 * Network
 *
 * @Table(name="network")
 * @Entity(repositoryClass="NetworkRepository")
 */ 
class Network
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
   * @Column(name="link", type="string", length=255)
   */
  private $link;
  
  /**
   * @ManyToOne(targetEntity="Shopping", inversedBy="networks", fetch="LAZY")    
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
     * Set name
     *
     * @param string $name
     * @return Network
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
     * Set link
     *
     * @param string $link
     * @return Network
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
     * Set shopping
     *
     * @param \Samuca\Fashion\Entity\Shopping $shopping
     * @return Network
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