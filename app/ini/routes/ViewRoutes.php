<?php

namespace app\ini\routes;

use webfiori\framework\router\Router;
/**
 * A class that only has one method to initiate some of system routes.
 * The class is meant to only initiate the routes which uses the method 
 * Router::view().
 * @author Ibrahim
 * @version 1.0
 */
class ViewRoutes {
    /**
     * Create all views routes. Include your own here.
     * @since 1.0
     */
    public static function create() {
        Router::page([
            'path' => '/', 
            'route-to' => '/default.html'
        ]);
        Router::page([
            'path' => '/example', 
            'route-to' => \app\pages\ExamplePage::class,
            'case-sensitive' => false,
            'middleware' => [
                'sample-middleware','sample-middleware-2'
            ],
            'methods' => 'get'
        ]);
        Router::incSiteMapRoute();
    }
}
