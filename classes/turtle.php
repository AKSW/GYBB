<?php


/**
 * Description of turtle
 * 
 */
class Turtle {
  
    static function simpleLiteral($var) {
        $string = '"' . addcslashes((string)$var, "\t\n\r\"") . '"';
        return $string;
    }
    
    
}

?>
