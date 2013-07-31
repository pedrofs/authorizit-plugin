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
        if (is_string($this->resource)) {
            return $this->resource;
        }

        return $this->resource->alias;
    }

    public function checkProperties($conditions)
    {
        $data = $this->resource->read();

        foreach ($conditions as $attr => $value) {
            if ($data[$this->resource->alias][$attr] != $value) {
                return false;
            }
        }

        return true;
    }
}
