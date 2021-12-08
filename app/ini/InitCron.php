<?php

namespace app\ini;

use webfiori\framework\cron\Cron;

/**
 * A class that has one method to initialize cron jobs.
 *
 * @author Ibrahim
 * @version 1.0
 */
class InitCron {
    /**
     * A method that can be used to initialize cron jobs.
     * 
     * The main aim of this method is to give the developer a way to register 
     * the jobs which are created outside the folder 'app/jobs'. To register 
     * any job, use the method Cron::scheduleJob().
     * 
     * @since 1.0
     */
    public static function init() {
        //enable job execution log
        Cron::execLog(true);

        //add jobs
        Cron::dailyJob("13:00", "Test Job", function ()
        {
            echo "I'm Running in Background.\n";
        });
    }
}
