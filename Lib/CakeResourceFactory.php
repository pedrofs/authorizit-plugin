<?php
App::uses('CakeResource', 'AuthorizitPlugin.Lib');

use Authorizit\Resource\Factory\AbstractResourceFactory;

class CakeResourceFactory extends AbstractResourceFactory
{
    public function getInstance($resource)
    {
        return new CakeResource($resource);
    }
}
