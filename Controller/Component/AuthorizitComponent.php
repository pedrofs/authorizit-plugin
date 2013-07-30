<?php
App::uses('CakeResourceFactory', 'AuthorizitPlugin.Lib');
App::uses('CakeModelAdapter', 'AuthorizitPlugin.Lib');

class AuthorizitComponent extends Component
{
    public function initialize(Controller $controller)
    {
        $this->controller = $controller;

        $dirAndClassName        = explode('.', $this->settings['class']);
        $dirToLoad              = $dirAndClassName[0];
        $this->authorizitClass  = $dirAndClassName[1];

        App::uses($this->authorizitClass, $dirToLoad);
    }

    public function startup(Controller $controller)
    {
        $user = $this->controller->Session->read('Auth.User');

        $this->authorizit = new $this->authorizitClass(
            $user,
            new CakeResourceFactory()
        );

        $this->authorizit->setModelAdapter();

        $this->authorizit->init();
    }

    public function load($resourceClass)
    {
        $this->authorizit->loadResources($resourceClass);
    }
}