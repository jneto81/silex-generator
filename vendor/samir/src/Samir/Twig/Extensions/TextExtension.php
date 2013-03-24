<?php

namespace Samir\Twig\Extensions;

/**
 * TextExtension class
 * 
 */
class TextExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'wordwrap' => new \Twig_Function_Method($this, 'wordwrap'),
        );
    }
    
    public function wordwrap($str, $length, $append) 
    {    
      if (strlen($str) > $length) {
        $array = explode(' ', strip_tags($str));
        $output = array();
        $count = 0;
      
        foreach ($array as $word) {
          $count += strlen($word) + 1;
          
          if ($count > $length) {
            break;
          }
          
          $output[] = $word;
        }
        
        $str = implode(' ', $output) . '...';
      }
      
      return $str;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'samir_text';
    }
}