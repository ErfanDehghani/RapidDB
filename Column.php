<?php 

class Column {
    private $name;
    private $type;
    private $length;
    private $default;
    private $nullable;
    private $primaryKey;
    
    public function __construct($name, $type, $length = null, $default = null, $nullable = false, $primaryKey = false) {
        $this->name = $name;
        $this->type = $type;
        $this->length = $length;
        $this->default = $default;
        $this->nullable = $nullable;
        $this->primaryKey = $primaryKey;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getLength() {
        return $this->length;
    }
    
    public function getDefault() {
        return $this->default;
    }
    
    public function isNullable() {
        return $this->nullable;
    }
    
    public function isPrimaryKey() {
        return $this->primaryKey;
    }
    
    public function __toString() 
    {
        $columnString = $this->name . ' ' . $this->type;
        
        if ($this->length)
            $columnString .= '(' . $this->length . ')';
        
        if($this->default == "AUTO_INCREMENT") 
            $columnString .= $this->default;

        elseif ($this->default !== null)
            $columnString .= ' DEFAULT ' . $this->default;
        
        if (!$this->nullable) 
            $columnString .= ' NOT NULL';
        
        if ($this->primaryKey) 
            $columnString .= ' PRIMARY KEY';
        
        return $columnString;
    }
}
