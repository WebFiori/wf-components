<?php

namespace wfc\database\users;

use webfiori\database\mysql\MySQLTable;

/**
 * A class which represents the database table 'users'.
 * The table which is associated with this class will have the following columns:
 * <ul>
 * <li><b>id</b>: Name in database: 'id'. Data type: 'int'.</li>
 * <li><b>full-name</b>: Name in database: 'full_name'. Data type: 'varchar'.</li>
 * <li><b>email</b>: Name in database: 'full_name'. Data type: 'varchar'.</li>
 * </ul>
 */
class UserInfoTable extends MySQLTable {
    /**
     * Creates new instance of the class.
     */
    public function __construct(){
        parent::__construct('users_info');
        $this->setComment('This table is used to hold basic information of application users.');
        $this->addColumns([
            'id' => [
                'type' => 'int',
                'size' => '11',
                'primary' => true,
                'comment' => 'The unique identifier of the user.',
                'auto-inc' => true
            ],
            'full-name' => [
                'type' => 'varchar',
                'size' => '256',
                'comment' => 'The full name of the user.',
            ],
            'email' => [
                'type' => 'varchar',
                'size' => '256',
                'is-unique' => true,
                'comment' => 'The email address of the user. Can be used as username in login table to login the user to the system.',
            ],
        ]);
    }
}
