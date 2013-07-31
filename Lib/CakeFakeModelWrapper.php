<?php
class CakeFakeModelWrapper
{
    public $alias;
    private $data;

    public function __construct($modelAlias, $resource)
    {
        $this->alias = $modelAlias;
        $this->data  = $resource;
    }

    public function read()
    {
        return $this->data;
    }
}