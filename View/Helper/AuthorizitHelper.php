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

App::uses('AppHelper', 'View/Helper');
App::uses('AuthorizitWrapper', 'AuthorizitPlugin.Lib');

/**
 * This helper is used to enable checks from View.
 */
class AuthorizitHelper extends AppHelper
{
    /**
     * Once you never have toe Model object in the view layer
     * you will always need to provide the $modelAlias for right checking the $resource
     *
     * @param string $action
     * @param array $resource
     * @param string $modelAlias
     * @return boolean
     */
    public function check($action, $resource, $modelAlias = false)
    {
        return AuthorizitWrapper::check($action, $resource, $modelAlias);
    }
}