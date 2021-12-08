<?php

namespace wfc\database\users;

use webfiori\database\mysql\MySQLTable;
use wfc\database\users\UserInfoTable;

/**
 * A class which represents the database table 'user_login'.
 * The table which is associated with this class will have the following columns:
 * <ul>
 * <li><b>id</b>: Name in database: 'id'. Data type: 'varchar'.</li>
 * <li><b>username</b>: Name in database: 'username'. Data type: 'varchar'.</li>
 * <li><b>salt</b>: Name in database: 'salt'. Data type: 'varchar'.</li>
 * <li><b>password</b>: Name in database: 'password'. Data type: 'varchar'.</li>
 * <li><b>last-login</b>: Name in database: 'last_login'. Data type: 'datetime2'.</li>
 * </ul>
 */
class UserLoginTable extends MySQLTable {
    /**
     * Creates new instance of the class.
     */
    public function __construct(){
        parent::__construct('users_login');
        $this->setComment('This table is used to hold authentication information of the user.');
        $this->addColumns([
            'id' => [
                'type' => 'int',
                'size' => '11',
                'primary' => true,
                'comment' => 'The unique identifier of the user.',
            ],
            'username' => [
                'type' => 'varchar',
                'size' => '256',
                'is-unique' => true,
                'comment' => 'Username of the user. Each user must have a unique username.',
            ],
            'salt' => [
                'type' => 'varchar',
                'size' => '10',
                'comment' => 'A secure generated salt which is used in top of the password to secure it more.',
            ],
            'password' => [
                'type' => 'varchar',
                'size' => '128',
                'comment' => 'The hashed salted password.',
            ],
            'last-login' => [
                'type' => 'datetime',
                'comment' => 'Last date and time at which the user was successfully logged-in to the system.',
            ],
        ]);
        $this->addReference(new UserInfoTable(), [
            'id' => 'id',
        ], 'login_id_fk', 'cascade', 'cascade');
    }
}
