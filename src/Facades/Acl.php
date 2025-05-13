<?php

namespace Tahmid\AclManager\Facades;

use Illuminate\Support\Facades\Facade;

class Acl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'acl';
    }
}
