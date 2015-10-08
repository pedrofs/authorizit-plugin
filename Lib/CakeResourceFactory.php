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

App::uses('CakeResource', 'AuthorizitPlugin.Lib');

use Authorizit\Resource\Factory\AbstractResourceFactory;

/**
 * The abstract factory that creates the Resource
 */
class CakeResourceFactory extends AbstractResourceFactory
{
    /**
     * @param mixed $resource
     * @return CakeResource
     */
    public function getInstance($resource)
    {
        return new CakeResource($resource);
    }
}
