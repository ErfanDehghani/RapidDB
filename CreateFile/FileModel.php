<?php
interface FileModel
{
    public function __construct(string $fileName);

    public function addProperty(string $propertyName);
    public function generate();
}