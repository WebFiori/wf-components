<?php

namespace app\ini;

use webfiori\framework\cli\CLI;
/**
 * A class that contains one method for registering custom CLI 
 * commands.
 *
 * @author Ibrahim
 */
class InitCliCommands {
    /**
     * Register user defined CLI commands.
     * 
     * This method can be used by the developers to add any custom 
     * CLI command that they have created that does not exist in the folder 
     * 'app/commands'. Assuming that we have a 
     * custom command with the name 'ProcessEmailCommand', then it 
     * can be registered as follows:<br/>
     * <code>
     * CLI::register(new ProcessEmailCommand());
     * </code>
     */
    public static function init() {
    }
}
