<?php
App::uses('CakeFakeModelWrapper', 'AuthorizitPlugin.Lib');

class AuthorizitWrapper
{
    static private $authorizit;

    static public function setAuthorizit($authorizit)
    {
        self::$authorizit = $authorizit;
    }

    static public function check($action, $resource, $modelAlias = null)
    {
        self::checkAuthorizit();

        if ($modelAlias) {
            $resource = new CakeFakeModelWrapper($modelAlias, $resource);
        }

        return self::$authorizit->check($action, $resource);
    }

    static public function loadResources($resourceClass)
    {
        return self::$authorizit->loadResources($resourceClass);
    }

    static private function checkAuthorizit()
    {
        if (!self::$authorizit) {
            throw new \BadMethodCallException("You must define the Authorizit instance before use it.");
        }
    }
}