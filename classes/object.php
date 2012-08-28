<?php


class Object {
    
    const URI = 0;
    
    const LITERAL = 1;
    
    const POINT = 2;
    
    private $value;
    
    
    private $type;
    
    
    private function __construct($value, $type) {
        $this->value = $value;
        $this->type = $type;
        
    }
    
    public function toStatement() {
        switch ($this->type) {
            case Object::URI: return '<' . (string)$this->value . '>';
            case Object::LITERAL: return '"' . (string)$this->value . '"';
            case Object::POINT: return 'TODO';//TODO
        }
        
    }
    
    public static function uri($value) {
        return new Object($value, Object::URI);
    }
    
    public static function literal($value) {
        return new Object($value, Object::LITERAL);
    }
    
    public static function point($point) {
        return new Object(array($point->lon, $point->lat), $Object::POINT);
    }
}

?>
