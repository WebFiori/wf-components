<?php

namespace app\ini;

use webfiori\framework\Access;
/**
 * A class that has one method which is used to initialize privileges.
 * 
 * @author Ibrahim
 * @version 1.0
 */
class InitPrivileges {
    /**
     * Initialize user groups and privileges.
     * 
     * The developer can modify the body of this method to create user groups 
     * and assign privileges to each group. To create new group, use the 
     * method Access::newGroup(). To create a privilege in a group, use the 
     * method Access::newPrivilege().
     * 
     * @since 1.0
     */
    public static function init() {
        //Access::newGroup('MY_GROUP');
        //Access::newPrivilege('MY_GROUP', 'MY_PR');
    }
}
