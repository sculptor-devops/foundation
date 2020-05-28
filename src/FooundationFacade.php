<?php

namespace Sculptor\Fooundation;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sculptor\Fooundation\Skeleton\SkeletonClass
 */
class FooundationFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fooundation';
    }
}
