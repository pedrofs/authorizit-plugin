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

App::uses('CakeFakeModelWrapper', 'AuthorizitPlugin.Lib');

/**
 * This class is used to wrap the Authorizit instance and works like a facade to
 * expose the Authorizit API to the Component and to the Helper.
 */
class AuthorizitWrapper
{
    /**
     * The authorizit base instance
     *
     * @var Base $authorizit
     */
    static private $authorizit;

    /**
     * @param Authorizit\Base $authorizit
     */
    static public function setAuthorizit($authorizit)
    {
        self::$authorizit = $authorizit;
    }

    /**
     * @param string $action
     * @param array $resourcesData
     * @param string $modelAlias
     * @return array Authorized resources
     * @throws BadMethodCallException
     */
    static public function authorizeResources($action, $resourcesData, $modelAlias = false)
    {
        self::checkAuthorizit();

        $authorizedResources = array();

        foreach ($resourcesData as $resourceData) {
            if (self::check($action, $resourceData, $modelAlias)) {
                $authorizedResources[] = $resourceData;
            }
        }

        return $authorizedResources;
    }

    /**
     * @param string $action
     * @param mixed $resource
     * @param string $modelAlias
     * @return boolean
     * @throws BadMethodCallException
     */
    static public function check($action, $resource, $modelAlias = false)
    {
        self::checkAuthorizit();

        if ($modelAlias) {
            $resource = new CakeFakeModelWrapper($modelAlias, $resource);
        }

        return self::$authorizit->check($action, $resource);
    }

    /**
     * @param string $resourceClass
     * @return array
     * @throws BadMethodCallException
     */
    static public function loadResources($resourceClass)
    {
        self::checkAuthorizit();

        return self::$authorizit->loadResources($resourceClass);
    }
    static private function checkAuthorizit()
    {
        if (!self::$authorizit) {
            throw new \BadMethodCallException("You must define the Authorizit instance before use it.");
        }
    }
}