<?php
App::uses('CakeResourceFactory', 'AuthorizitPlugin.Lib');
App::uses('CakeModelAdapter', 'AuthorizitPlugin.Lib');
App::uses('AuthorizitWrapper', 'AuthorizitPlugin.Lib');

class AuthorizitComponent extends Component
{
    public function initialize(Controller $controller)
    {
        $this->controller = $controller;
        $dirAndClassName = $this->settings['class'];

        if (is_string($this->settings['class'])) {
            $dirAndClassName = explode('.', $this->settings['class']);
        }
        
        $dirToLoad = $dirAndClassName[0];
        $this->authorizitClass = $dirAndClassName[1];        
        
        App::uses($this->authorizitClass, $dirToLoad);
    }

    public function startup(Controller $controller)
    {
        $user = $this->controller->Session->read('Auth.User');

        $authorizit = new $this->authorizitClass(
            $user,
            new CakeResourceFactory()
        );

        $authorizit->setModelAdapter(new CakeModelAdapter());

        $authorizit->init();

        AuthorizitWrapper::setAuthorizit($authorizit);
    }

    public function load($resourceClass)
    {
        return AuthorizitWrapper::loadResources($resourceClass);
    }

    public function authorize($action, $resource)
    {
        if (! AuthorizitWrapper::check($action, $resource)) {
            throw new ForbiddenException(__('You don\'t have permission to access this resource.'));
        }

        return true;
    }

    public function check($action, $resource)
    {
        return AuthorizitWrapper::check($action, $resource);
    }
}