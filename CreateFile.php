<?php

require_once 'CreateFile/FILETYPES.php';
require_once 'CreateFile/ClassFile.php';

use CreateFile\FILETYPES;

class CreateFile
{
    private string $name;
    private string $type;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;

        if ($this->type == FILETYPES::DATABASE_CLASS) {
            $classFile = new ClassFile('MyClass');
            $classFile->addProperty('property1');
            $classFile->addProperty('property2');
            $classFile->generate();
        }
        
    }

}