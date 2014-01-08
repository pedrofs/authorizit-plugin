<?php
App::uses('CakeResourceFactory', 'AuthorizitPlugin.Lib');
App::uses('CakeModelAdapter', 'AuthorizitPlugin.Lib');
App::uses('AuthorizitWrapper', 'AuthorizitPlugin.Lib');

class AuthorizitComponent extends Component
{

    public $defaultSettings = array(
        'autoCheck' => true,
    );

    protected $autoCheckMap = array(
        'admin_index' => 'read',
        'admin_add' => 'add',
        'admin_view' => 'read',
        'admin_edit' => 'update',
        'admin_delete' => 'delete'
    );

    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
        $this->settings = array_merge(
            $this->defaultSettings,
            $settings
        );
    }

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

        if ($this->settings['autoCheck']) {
            $this->mayUserAccess($controller);
        }
    }

    public function mayUserAccess(Controller $controller)
    {
        $action = $controller->params['action'];

        if (!isset($this->autoCheckMap[$action])) {
            return;
        }

        if (isset($controller->Auth) && in_array($action, $controller->Auth->allowedActions)) {
            return;
        }
        
        if (isset($controller->Auth) && $controller->Auth->user('id') === null) {
            return;
        }        

        $action = $this->autoCheckMap[$action];

        if (!$this->check($action, $controller->{$controller->modelClass}->alias)) {
            throw new UnauthorizedException('Access denied.');
        }        
    }

    public function load($resourceClass)
    {
        return AuthorizitWrapper::loadResources($resourceClass);
    }

    public function authorizeResources($action, $resourcesData, $modelAlias = false)
    {
        return AuthorizitWrapper::authorizeResources($action, $resourcesData, $modelAlias);
    }

    public function authorize($action, $resource)
    {
        if (! AuthorizitWrapper::check($action, $resource)) {
            throw new ForbiddenException(__('You don\'t have permission to access this resource.'));
        }

        return true;
    }

    public function check($action, $resource, $modelAlias = false)
    {
        return AuthorizitWrapper::check($action, $resource, $modelAlias);
    }
}
