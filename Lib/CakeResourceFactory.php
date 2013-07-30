<?php
App::uses('CakeResource', 'AuthorizitPlugin.Lib');

class CakeResourceFactory
{
    public function get($resource)
    {
        return new CakeResource($resource);
    }
}
