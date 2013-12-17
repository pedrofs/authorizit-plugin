<?php
class CakeFakeModelWrapper
{
    public $alias;
    public $id;
    public $data;

    public function __construct($modelAlias, $resource)
    {
        $this->alias = $modelAlias;
        $this->data  = $resource;
        $this->id = $resource[$modelAlias]['id'];
    }

    public function find($type = 'first', $options = array())
    {
        return $this->data;
    }
}