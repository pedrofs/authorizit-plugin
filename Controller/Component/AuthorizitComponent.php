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

App::uses('CakeResourceFactory', 'AuthorizitPlugin.Lib');
App::uses('CakeModelAdapter', 'AuthorizitPlugin.Lib');
App::uses('AuthorizitWrapper', 'AuthorizitPlugin.Lib');

/**
 * This component is in charge to enable Controller to interact with Authorizit.
 *
 * Features:
 * . $this->load('Example') will load all Example resources based on rules defined in the Authorizit class.
 * . $this->authorizeResources($action, $resourcesArray, $modelAlias) will authorize each resource in the $resourcesArray
 * . $this->authorize($action, $resource) will check if the $resource Model (with id set) is authorized for the given $action
 *   and throws exception if not authorized
 * . $this->check($action, $resource, $modelAlias) Pretty similar to the above one but can accept an array as $resource and doesn't
 *   throws exception
 */
class AuthorizitComponent extends Component
{

    /**
     * Default settings for the component
     *
     * @var array $defaultSettings
     */
    public $defaultSettings = array(
        'autoCheck' => true,
    );

    /**
     * autoCheckMap is used for the auto checking feature when a user tries to run a controller action
     *
     * @var array $autoCheckMap
     */
    protected $autoCheckMap = array(
        'admin_index' => 'read',
        'admin_add' => 'create',
        'admin_view' => 'read',
        'admin_edit' => 'update',
        'admin_delete' => 'delete'
    );

    /**
     * Constructor
     *
     * @param ComponentCollection $collection
     * @param array $settions
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
        $this->settings = array_merge(
            $this->defaultSettings,
            $settings
        );
    }

    /**
     * Initialize method called by cake to init the component
     *
     * It expects the $settings to have a class index indicating where is located the Authorizit concrete class.
     * For instance: 'Lib.Authorizit' will locate the Authorizit class inside the lib/ directory.
     *
     * @param Controller $controller
     */
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


    /**
     * The startup method will create an instance of the Authorizit object, set its modelAdapter, init it and
     * if the autoCheck function is enabled it will authorize the user in advance.
     *
     * @param Controller $controller
     * @throws UnauthorizedException
     */
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

    /**
     * This method implements the autoCheck feature
     *
     * @param Controller $controller
     * @throws UnauthorizedException
     */
    public function mayUserAccess(Controller $controller)
    {
        $action = $controller->params['action'];

        if (!isset($this->autoCheckMap[$action])) {
            return;
        }

        if ($this->isActionAllowed($controller, $action) || !$this->isUserLogged($controller)) {
            return;
        }

        $action = $this->autoCheckMap[$action];

        if (!$this->check($action, $controller->{$controller->modelClass}->alias)) {
            throw new UnauthorizedException('Access denied.');
        }
    }

    /**
     * The method load will load the authorized resources for the given class 
     *
     * @param string $resourceClass
     * @return array Authorized resources
     * @throws BadMethodCallException When the Authorizit class was not set in the AuthorizitWrapper
     */
    public function load($resourceClass)
    {
        return AuthorizitWrapper::loadResources($resourceClass);
    }

    /**
     * For a given $resourcesData this method will authorize them using $modelAlias agaisnt an $action
     *
     * @param string $action
     * @param array $resourcesData An array of cake object arrays
     * @param string $modelAlias Once cake treats its resources as array we have to know what resources we are authorizing
     * @throws BadMethodCallException When the Authorizit class was not set in the AuthorizitWrapper
     */
    public function authorizeResources($action, $resourcesData, $modelAlias = false)
    {
        return AuthorizitWrapper::authorizeResources($action, $resourcesData, $modelAlias);
    }

    /**
     * Authorize an action over a $resource CakePHP Model
     *
     * @param string $action
     * @param Model $resource
     * @return true
     * @throws ForbiddenException
     */
    public function authorize($action, $resource)
    {
        if (! AuthorizitWrapper::check($action, $resource)) {
            throw new ForbiddenException(__('You don\'t have permission to access this resource.'));
        }

        return true;
    }

    /**
     * Checks the $action for a $resource
     *
     * @param string $action
     * @param mixed $resource It can be a Cake Model object or a resulting array
     * @param string $modelAlias It is used when the $resource is an array
     * @return boolean
     * @throws BadMethodCallException When the Authorizit class was not set in the AuthorizitWrapper
     */
    public function check($action, $resource, $modelAlias = false)
    {
        return AuthorizitWrapper::check($action, $resource, $modelAlias);
    }

    private function isActionAllowed(Controller $controller, $action)
    {
        return isset($controller->Auth) && in_array($action, $controller->Auth->allowedActions);
    }

    private function isUserLogged(Controller $controller)
    {
        return isset($controller->Auth) && $controller->Auth->user('id') !== null;
    }
}
