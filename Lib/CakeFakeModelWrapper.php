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

/**
 * This class is used when there is the need to use cake array as resource then it just wraps the array and 'mock' the find method.
 */
class CakeFakeModelWrapper
{
    /**
     * @var string alias
     */
    public $alias;

    /**
     * @var integer $id
     */
    public $id;

    /**
     * @var array $data
     */
    public $data;

    /**
     * Constructor
     *
     * @params string $modelAlias
     * @params array $resource
     */
    public function __construct($modelAlias, $resource)
    {
        $this->alias = $modelAlias;
        $this->data  = $resource;
        $this->id = $resource[$modelAlias]['id'];
    }

    /**
     * @return array
     */
    public function find($type = 'first', $options = array())
    {
        return $this->data;
    }
}