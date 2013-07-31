<?php 
App::uses('AppHelper', 'View/Helper');
App::uses('AuthorizitWrapper', 'AuthorizitPlugin.Lib');

class AuthorizitHelper extends AppHelper
{
    public function check($action, $resource, $modelAlias)
    {
        return AuthorizitWrapper::check($action, $resource, $modelAlias);
    }
}