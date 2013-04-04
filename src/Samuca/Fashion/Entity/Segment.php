<?php

namespace Samuca\Fashion\Entity;

use Doctrine\Mapping as ORM;

/**
 * Segment
 *
 * @Table(name="segment")
 * @Entity(repositoryClass="SegmentRepository")
 */ 
class Segment
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
}