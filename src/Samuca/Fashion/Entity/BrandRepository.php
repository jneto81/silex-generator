<?php

namespace Samuca\Fashion\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ShoppingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BrandRepository extends EntityRepository
{
  public function findByWildcard($wildcard) 
  {

    if (empty($wildcard) || !is_array($wildcard))
        $wildcard = array();
      
    $params = array();
  
    foreach ($wildcard as $col => $val) {
      $opr = '=';
      
      if (strpos($val, '%') !== false && preg_match('/%?(.*)%?$/', $val))
        $opr = 'LIKE';
      
      if (!is_numeric($val))
        $val = "'$val'";
      
      $params[] = sprintf('b.%s %s %s', $col, $opr, $val);
    }
  
    return $this->getEntityManager()
      ->createQuery('SELECT b FROM Samuca\Fashion\Entity\Brand b' . (count($params) ? ' WHERE ' . implode(' AND ', $params) : ''))
      ->getResult();
  }
  
  public function findByParams(array $args) 
  {
    $param = array();
    $query = array();
    $query = array();
  
    if (isset($args['region'])) {
      $param['R.id'] = 'R.id = ' . $args['region'];
    }
    
    if (isset($args['segment'])) {
      $param['B.segment_id'] = 'B.segment_id = ' . $args['segment'];
    }
    
    if (isset($args['type'])) {
      $param['B.type'] = 'B.type = \'' . $args['type'] . '\'';
    }
    
    $query[] = implode(' AND ', $param);
    
    $param = array();
    
    if (isset($args['keyword'])) {
      $param['B.keyword'] = 'B.keyword = \'' . $args['keyword'] . '\'';
    }
    
    if (isset($args['name'])) {
      $param['B.name'] = 'B.name = \'' . $args['name'] . '\'';
    }
    
    $query[] = implode(' OR ', $param);

    $param = array_filter($query, function ($value) {
      return ! empty($value);
    });
    
    $param = implode(' AND ', $param);
    
    $sql = "SELECT B.* FROM brand B 
      LEFT JOIN address A ON B.id = A.brand_id 
      LEFT JOIN region R ON R.id = A.region_id" . 
      (empty($param) ? "" : " WHERE $param");
  
    $stmt = $this->getEntityManager()
      ->getConnection()
      ->prepare($sql);
      
    if ($stmt->execute()) {
      return $stmt->fetchAll();
    } else {
      return false;
    }
  }
}
