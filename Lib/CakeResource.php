<?php
use Authorizit\Resource\ResourceInterface;

class CakeResource implements ResourceInterface
{
    private $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function getClass()
    {
        return $this->resource->alias;
    }

    public function checkProperties($conditions)
    {
        $data = $this->resource->find('first', array(
            'conditions' => array(
                "{$this->resource->alias}.id" => $this->resource->id
            ))
        );

        if ($data) {
            foreach ($conditions as $attr => $value) {
                if ($data[$this->resource->alias][$attr] != $value) {
                    return false;
                }
            }
        }

        return true;
    }

    public function getResource()
    {
        return $this->resource;
    }
}
