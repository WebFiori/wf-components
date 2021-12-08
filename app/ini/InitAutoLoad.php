<?php

namespace app\ini;

use webfiori\framework\AutoLoader;
/**
 * A class that has one method to initialize user-defined autoload directories.
 *
 * @author Ibrahim
 * @version 1.0
 */
class InitAutoLoad {
    /**
     * Add user-defined directories to the set of directories at which the framework 
     * will search for classes.
     * The developer can use the method AutoLoader::newSearchFolder() to add 
     * new search directory. Note that the developer does not have to 
     * register the folder 'vendor' as it will be auto-registered.
     */
    public static function init() {
        $AU = AutoLoader::get();

        //this is a sample code that shows how to add folders.
        $AU->newSearchFolder('my-system');
        $AU->newSearchFolder('my-entities', false);
    }
}
