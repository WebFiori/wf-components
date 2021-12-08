<?php

namespace app\ini\routes;

use webfiori\framework\Util;
use webfiori\http\Response;
use webfiori\framework\router\Router;
/**
 * A class that only has one method to initiate some of system routes.
 * The class is meant to only initiate the routes which uses the method 
 * Router::closure().
 * @author Ibrahim
 * @version 1.0
 */
class ClosureRoutes {
    /**
     * Create all closure routes. Include your own here.
     * @since 1.0
     */
    public static function create() {
        $arrayOfParams = ['WebFiori Framework'];
        Router::closure([
            'path' => '/closure',
            'closure-params' => $arrayOfParams,
            'route-to' => function($params)
            {
                Response::write('This is a closure route.');
                Util::print_r($params, false);
            }
        ]);
    }
}
