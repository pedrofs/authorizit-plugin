<?php
class CakeFakeModelWrapper
{
    public $alias;
    public $id;
    private $data;

    public function __construct($modelAlias, $resource)
    {
        $this->alias = $modelAlias;
        $this->data  = $resource;
    }

    public function find($type, $options)
    {
        return $this->data;
    }
}