<?php

namespace wfc\database\users;

use webfiori\database\mssql\MSSQLTable;
use wfc\database\users\UserInfoTable;

/**
 * A class which represents the database table 'user_settings'.
 * The table which is associated with this class will have the following columns:
 * <ul>
 * <li><b>id</b>: Name in database: 'id'. Data type: 'int'.</li>
 * <li><b>prefered-lang</b>: Name in database: 'prefered_lang'. Data type: 'varchar'.</li>
 * <li><b>privileges</b>: Name in database: 'privileges'. Data type: 'varchar'.</li>
 * </ul>
 */
class UserSettingsTable extends MSSQLTable {
    /**
     * Creates new instance of the class.
     */
    public function __construct(){
        parent::__construct('users_settings');
        $this->setComment('This table should hold any settings which is related to the user.');
        $this->addColumns([
            'id' => [
                'type' => 'int',
                'size' => '11',
                'primary' => true,
                'comment' => 'The ID of the user taken from general users information table.',
            ],
            'prefered-lang' => [
                'type' => 'varchar',
                'size' => '2',
                'default' => 'EN',
                'comment' => 'Prefered display language of the user.',
            ],
            'privileges' => [
                'type' => 'varchar',
                'size' => '5000',
                'comment' => 'This column will hold a string which contain user privileges.',
            ],
        ]);
        $this->addReference(new UserInfoTable(), [
            'id' => 'id',
        ], 'user_settings_fk', 'cascade', 'cascade');
    }
}
