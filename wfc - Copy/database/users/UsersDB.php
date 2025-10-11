<?php
namespace wfc\database\users;

use webfiori\framework\DB;
use webfiori\framework\User;
use webfiori\framework\Access;
use webfiori\framework\session\SessionsManager;
use webfiori\framework\File;
use webfiori\json\Json;
use wfc\entity\users\SystemUser;
use wfc\entity\users\UserRole;
/**
 * A basic class which holds basic operations on users tables.
 *
 * @author Ibrahim
 */
class UsersDB extends DB {
    /**
     * Adds a user to the list of users who needs to refresh their privileges.
     * 
     * This method is used after updating the privileges of a user.
     * 
     * @param int $userId The ID of the user.
     */
    public function addToRefresh($userId) {
        $file = new File(ROOT_DIR.DS.APP_DIR_NAME.DS.'sto'.DS.'ref.txt');
        $file->setRawData($userId."\n");
        $file->write(true, true);
    }
    /**
     * Returns an array that holds objects that represents all system users.
     * 
     * @return array An array that holds objects of type 'Systemuser'.
     */
    public function getAllUsers() {
        $resultSet = $this->table('users_info')->join(
                $this->table('users_settings')->select([
                    'preferred-lang',
                    'privileges'
                ])
        )->on('id', 'id')->join(
                $this->table('users_login')->select([
                    'username',
                    'salt',
                    'password',
                    'last-login'
                ])
        )->on('id', 'id')->select()->execute();
        
        $resultSet->setMappingFunction(function ($data) {
            $retVal = [];
            foreach ($data as $rcord) {
                $retVal[] = SystemUser::map($rcord);
            }
            return $retVal;
        });
        return $resultSet->getMappedRows();
    }
    /**
     * Returns an array that contains users who have specific privilege.
     * 
     * @param string $prId The ID Of the privilege that will be checked.
     * 
     * @return array An array that holds objects of type 'SystemUser'.
     */
    public function getUsersByPrivilege($prId) {
        $users = $this->getAllUsers();
        $retVal = [];
        
        foreach ($users as $user) {
            if ($user->hasPrivilege($prId)) {
                $retVal[] = $user;
            }
        }
        return $retVal;
    }
    /**
     * Refresh session user given his ID.
     *   
     * @param int $userId The ID of session user.
     */
    public function refreshUser($userId) {
        $file = new File(ROOT_DIR.DS.APP_DIR_NAME.DS.'sto'.DS.'ref.txt');
        if ($file->isExist()) {
            $file->read();
            $ids = explode("\n", $file->getRawData());

            if (in_array($userId.'', $ids)) {
                SessionsManager::getActiveSession()->setUser($this->getUserByID($userId));

                $idsStr = '';
                foreach($ids as $id) {
                    if ($id != $userId) {
                        $idsStr .= trim($id)."\n";
                    }
                }
                $file->setRawData($idsStr);
                $file->write(false, true);
            }
        }
    }
    /**
     * Updates the display name of a specific user given his ID.
     * 
     * @param int $id The ID of the user.
     * 
     * @param string $newName The new display name of the user.
     */
    public function updateName($id, $newName) {
        $this->table('users_info')->update([
            'full-name' => $newName
        ])->where('id', '=', $id)->execute();
        $this->addToRefresh($id);
    }
    /**
     * Updates email address of a specific user given his ID.
     * 
     * @param int $id The ID of the user.
     * 
     * @param string $newEmail The new email of the user.
     */
    public function updateEmail($id, $newEmail) {
        $this->table('users_info')->update([
            'email' => $newEmail
        ])->where('id', '=', $id)->execute();
        $this->addToRefresh($id);
    }
    /**
     * Updates the privileges of a user.
     * 
     * @param int $id The ID of the user.
     * 
     * @param array $prsArr An array that holds the IDs of the privileges that 
     * will be applied.
     * 
     */
    public function updatePrivileges($id, $prsArr) {
        $user = new User();
        
        foreach ($prsArr as $pr) {
            $user->addPrivilege($pr);
        }
        $this->table('users_settings')->update([
            'privileges' => Access::createPermissionsStr($user)
        ])->where('id', '=', $id)->execute();
        $this->addToRefresh($id);
    }

    public function __construct($connName) {
        parent::__construct($connName);
        $this->addTable(new UserInfoTable());
        $this->addTable(new UserSettingsTable());
        $this->addTable(new UserLoginTable());
    }
    /**
     * Returns an array which holds objects of type 'Json' that can 
     * be used with Vuetify components.
     * 
     * @return array
     */
    public function getVUsers() {
        $resultSet = $this->table('users_info')->select()->execute();
        $resultSet->setMappingFunction(function ($data) {
            $retVal = [];
            foreach ($data as $rcord) {
                $retVal[] = new Json([
                    'text' => $rcord['full_name'],
                    'value' => $rcord['id']
                ]);
            }
            return $retVal;
        });
        return $resultSet->getMappedRows();
    }
    /**
     * Returns a single user given his ID.
     * 
     * @param int $userId The ID of the user.
     * 
     * @return SystemUser|null
     */
    public function getUserByID($userId) {
        $this->table('users_info')->join(
                $this->table('users_settings')->select([
                    'preferred-lang',
                    'privileges'
                ])
        )->on('id', 'id')->join(
                $this->table('users_login')->select([
                    'username',
                    'salt',
                    'password',
                    'last-login'
                ])
        )->on('id', 'id')->select()->where('id', '=', $userId);
        
        $resultSet = $this->execute();
        
        if ($resultSet->getRowsCount() == 1) {
            return SystemUser::map($resultSet->getRows()[0]);
        }
    }
    /**
     * Returns a user given his username.
     * 
     * @param string $username The username of the user.
     * 
     * @return SystemUser|null If a user with the given name was found, the method 
     * will return its info as object. Other than that, null is returned.
     */
    public function getUserByUsername($username) {
        $resultSet = $this->table('users_info')->join(
                $this->table('users_settings')->select([
                    'preferred-lang',
                    'privileges'
                ])
        )->on('id', 'id')->join(
                $this->table('users_login')->select([
                    'username',
                    'salt',
                    'password',
                    'last-login'
                ])
        )->on('id', 'id')->select()->where('username', '=', $username)->execute();
        
        if ($resultSet->getRowsCount() == 1) {
            return SystemUser::map($resultSet->getRows()[0]);
        }
    }
    /**
     * Perform basic user authentication.
     * 
     * @param string $username The username of the user which is stored in the 
     * table 'users_login'.
     * 
     * @param string $password The password of the user.
     * 
     * @return boolean|SystemUser If the user is authenticated, the method will return an 
     * object that holds user information. False otherwise.
     */
    public function login($username, $password) {
        $user = $this->getUserByUsername($username);
        if ($user === null) {
            return false;
        }
        $hashed = hash('sha256', $user->getSalt().$password);
        if ($hashed == $user->getPassword()) {
            $this->table('users_login')->update([
                'last-login' => date('Y-m-d H:i:s')
            ])->where('username', '=', $username)->execute();
            SessionsManager::getActiveSession()->setUser($user);
            return $user;
        }
    }
    /**
     * Sign the user into the system.
     * 
     * @param string $username The username of the user.
     * 
     * @param string $password The password of the user.
     * 
     * @return boolean|User If the user is logged in, the method will return true. 
     */
    public function signIn($username, $password) {
        if ($this->_normalLogin($username, $password)) {
            $user = $this->getUserByUsername($username);
            SessionsManager::getActiveSession()->setUser($user);
            return $user;
        } else {
            return false;
        }
    }
    
    private function _normalLogin($username, $password) {
        if ($this->login($username, $password)) {
            $user = $this->getUserByUsername($username);
            $activeSession = SessionsManager::getActiveSession();
            $activeSession->setUser($user);
            $activeSession->setDuration(30);
            $activeSession->setIsRefresh(true);
            return true;
        }
        return false;
    }
    /**
     * Update the login password of a user given his ID.
     * 
     * @param $id The ID of the user.
     * 
     * @param string $newPass The new password of the user.
     */
    public function updatePassword($id, $newPass) {
        $salt = substr(hash('sha256', date('Y-m-d H:i:s'). random_bytes(10)), 0, 10);
        $user = new SystemUser();
        $user->setSalt($salt);
        $user->setPassword($newPass);
        
        $this->table('users_login')->update([
            'id' => $id,
            'salt' => $user->getSalt(),
            'password' => $user->getPassHash(),
        ])->where('id', '=', $id)->execute();
    }
    /**
     * Adds new user to the table of users.
     * 
     * @param SystemUser $sysUser
     */
    public function addUser(SystemUser $sysUser) {
        $this->table('users_info')->insert([
            'id' => $sysUser->getID(),
            'full-name' => $sysUser->getDisplayName(),
            'email' => $sysUser->getEmail()
        ])->execute();
        $sysUser->setSalt(substr(hash('sha256', date('Y-m-d H:i:s'). random_bytes(10)), 0, 10));
        $this->table('users_login')->insert([
            'id' => $sysUser->getID(),
            'salt' => $sysUser->getSalt(),
            'password' => $sysUser->getPassHash(),
            'username' => $sysUser->getUserName(),
            'last-login' => date('Y-m-d H:i:s')
        ])->execute();
        
        $this->table('users_settings')->insert([
            'id' => $sysUser->getID(),
            'prefered-lang' => 'EN',
            'privileges' => Access::createPermissionsStr($sysUser)
        ])->execute();
    }
    /**
     * Returns the maximum value of the column ID in users table.
     * 
     * This is used to generate the ID of the user.
     * 
     * @return int
     */
    public function getMaxUserID() {
        $result = $this->table('users_info')->selectMax('id')->execute();
        $id = 0;
        if ($result->getRows()[0]['max'] !== null) {
            $id = $result->getRows()[0]['max'];
        }
        return $id;
    }
    
    /**
     * Update user role given its information as an object.
     * 
     * @param UserRole $role An object that holds role information.
     */
    public function updateRole(UserRole $role) {
        $this->table('user_roles')->update([
            'name' => $role->getName(),
            'privileges' => $this->getPrStr($role),
        ])->where('id', '=', $role->getID())->execute();
    }
    /**
     * Returns an array that holds all users which belongs to specific role.
     * 
     * @param array $roleId The Id of the role taken from
     * users roles table.
     */
    public function getUsersByRole($roleId) {
        return $this->getUsersByRoles([$roleId]);
    }
    /**
     * Returns an array that holds all users which belongs to one or more
     * roles.
     * 
     * @param array $rolesIdsArr An array that holds the IDs of roles taken from
     * users roles table.
     */
    public function getUsersByRoles($rolesIdsArr) {
        $users = $this->getAllUsers();
        $usersToReturn = [];
        $addedUsersIds = [];
        
        foreach ($rolesIdsArr as $groupId) {
            $roleObj = $this->getUserRole($groupId);
            if ($roleObj !== null) {
                
                foreach ($users as $userObj) {
                    $userObj instanceof SystemUser;
                    
                    if (!in_array($userObj->getID(), $addedUsersIds) && $userObj->hasRole($roleObj)) {
                        $usersToReturn[] = $userObj;
                        $addedUsersIds[] = $userObj->getID();
                    }
                }
            }
        }
    }
    /**
     * Returns user role given its ID.
     * 
     * @param int $roleId The ID of user role.
     * 
     * @return UserRole|null
     */
    public function getUserRole($roleId) {
        $resultSet = $this->table('user_roles')->select()->where('id', '=', $roleId)->execute();
        
        if ($resultSet->getRowsCount() == 1) {
            $record = $resultSet->getRows()[0];
            
            $group = new UserRole();
            $group->setID($record['id']);
            $group->setName($record['name']);
            
            $user = new User();
            Access::resolvePriviliges($record['privileges'], $user);

            foreach ($user->privileges() as $pr) {
                $group->addPrivilage($pr);
            }
            return $group;
        }
    }
    /**
     * 
     * @param UserRole $role
     * @return string
     */
    private function getPrStr($role) {
        $user = new User();
        
        foreach ($role->privileges() as $pr) {
            $user->addPrivilege($pr->getID());
        }
        
        return Access::createPermissionsStr($user);
    }
    /**
     * Adds new user role to the database.
     * 
     * @param UserRole $userRole An object that holds user role information.
     */
    public function addUserRole(UserRole $userRole) {
        $prevId = $this->getMaxRoleID();
        $this->table('user_roles')->insert([
            'id' => $prevId + 1,
            'name' => $userRole->getName(),
            'privileges' => $this->getPrStr($userRole),
        ])->execute();
    }
    /**
     * Returns an array that holds all added user roles.
     * 
     * @return array An array that holds objects of type 'UserRole'.
     */
    public function getRoles() {
        $resultSet = $this->table('user_roles')->select()->execute();
        
        $resultSet->setMappingFunction(function ($data) {
            
            $retVal = [];
            foreach ($data as $record) {
                $group = new UserRole();
                $group->setID($record['id']);
                $group->setName($record['name']);

                $user = new User();
                Access::resolvePriviliges($record['privileges'], $user);
                
                foreach ($user->privileges() as $pr) {
                    $group->addPrivilage($pr);
                }
                $retVal[] = $group;
            }
            return $retVal;
        });
        
        return $resultSet->getMappedRows();
    }
    /**
     * Returns the maximum value of the column ID in 'user_roles'.
     * 
     * This is used to generate the ID of the sector.
     * 
     * @return int
     */
    public function getMaxRoleID() {
        $result = $this->table('user_roles')->selectMax('id')->execute();
        $id = 0;
        if ($result->getRows()[0]['max'] !== null) {
            $id = $result->getRows()[0]['max'];
        }
        return $id;
    }
}
