<?php
/**
 * Authorizit Cake Plugin - Cake plugin for using Authorizit. See: https://github.com/pedrofs/authorizit
 * https://github.com/pedrofs/authorizit-plugin
 *
 * Licensed under UNLICENSE
 * For full copyright and license information, please see the UNLICENSE.txt
 * Check http://unlicense.org/
 *
 * @link          https://github.com/pedrofs/authorizit-plugin
 * @license       http://unlicense.org/ Unlicense Yourself: Set Your Code Free
 */

use Authorizit\Resource\ResourceInterface;

/**
 * This class implements the ResourceInterface and basically knows how to check conditions against $resource
 */
class CakeResource implements ResourceInterface
{
    /**
     * @var mixed $resource It can be a CakePHP Model or a CakeFakeModelWrapper
     */
    private $resource;

    /**
     * Constructor
     *
     * @param mixed $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Return the class for the given $resource
     *
     * @reteurn string
     */
    public function getClass()
    {
        return $this->resource->alias;
    }

    /**
     * Check the conditions against the $resource
     *
     * @param array $conditions
     * @return boolean
     */
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

    /**
     * @return mixed $resource
     */
    public function getResource()
    {
        return $this->resource;
    }
}
