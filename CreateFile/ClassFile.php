<?php

class ClassFile implements FileModel
{
    private string $fileName;
    private string $classTemplate = "<?php\nclass %s\n{\n%s\n}";
    private string $propertyTemplate = "    public $%s;\n";
    private string $classCode = '';

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function addProperty(string $propertyName)
    {
        $propertyCode = sprintf($this->propertyTemplate, $propertyName);
        $this->classCode .= $propertyCode;
    }

    public function generate()
    {
        $classCode = sprintf($this->classTemplate, $this->fileName, $this->classCode);
        file_put_contents($this->fileName . '.php', $classCode);
        echo "Class " . $this->fileName . " generated successfully in " . $this->fileName ;
    }
}