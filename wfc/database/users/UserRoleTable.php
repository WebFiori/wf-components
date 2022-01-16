<?php

namespace wfc\database\users;

use webfiori\database\mysql\MySQLTable;

/**
 * A class which represents the database table 'user_roles'.
 * The table which is associated with this class will have the following columns:
 * <ul>
 * <li><b>id</b>: Name in database: 'id'. Data type: 'int'.</li>
 * <li><b>name</b>: Name in database: 'name'. Data type: 'nvarchar'.</li>
 * <li><b>privileges</b>: Name in database: 'privileges'. Data type: 'nvarchar'.</li>
 * </ul>
 */
class PrivilegesGroupsTable extends MySQLTable {
    /**
     * Creates new instance of the class.
     */
    public function __construct(){
        parent::__construct('user_roles');
        $this->setComment('This table is used to hold privileges grouped in a role-based way');
        $this->addColumns([
            'id' => [
                'type' => 'int',
                'primary' => true,
                'comment' => 'The unique identifier of the role.',
            ],
            'name' => [
                'type' => 'varchar',
                'size' => '128',
                'is-unique' => true,
                'comment' => 'The name of user role such as "Admin".',
            ],
            'privileges' => [
                'type' => 'nvarchar',
                'size' => '1000',
                'comment' => 'The set of privileges that belongs to the role.',
            ],
        ]);
    }
}
