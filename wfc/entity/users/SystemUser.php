<?php
namespace wfc\entity\users;

use webfiori\framework\User;
use webfiori\framework\Access;
/**
 * Description of SystemUser
 *
 * @author Ibrahim
 */
class SystemUser extends User {
    /**
     * 
     * @var UserSettings
     **/
    private $settings;

    /**
     * The attribute which is mapped to the column '[salt]'.
     * 
     * @var string
     **/
    private $salt;
    
    /**
     * Returns the value of the attribute 'salt'.
     * 
     * The value of the attribute is mapped to the column which has
     * the name '[salt]'.
     * 
     * @return string The value of the attribute.
     **/
    public function getSalt() {
        return $this->salt;
    }
    
    /**
     * Sets the value of the attribute 'salt'.
     * 
     * The value of the attribute is mapped to the column which has
     * the name '[salt]'.
     * 
     * @param $salt string The new value of the attribute.
     **/
    public function setSalt($salt) {
        $this->salt = $salt;
    }
    
    /**
     * Maps a record which is taken from the table [user_login] to an instance of the class.
     * 
     * @param array $record An associative array that represents the
     * record. The array should have the following indices:
     * <ul>
     * <li>[id]</li>
     * <li>[username]</li>
     * <li>[salt]</li>
     * <li>[password]</li>
     * <li>[last_login]</li>
     * </ul>
     * 
     * @return OshcoUser An instance of the class.
     */
    public static function map(array $record) {
        $instance = new SystemUser();
        
        $instance->setId($record['id']);
        $instance->setUsername($record['username']);
        $instance->setSalt($record['salt']);
        $instance->setPassword($record['password']);
        $instance->setLastLogin($record['last_login']);
        $instance->setDisplayName($record['full_name']);
        if (isset($record['privileges'])) {
            $instance->setSettings(UserSettings::map($record));
            Access::resolvePriviliges($instance->getSettings()->getPrivileges(), $instance);
        }
        
        if (isset($record['password'])) {
            $instance->setUserName($record['username']);
            $instance->setEmail($record['username']);
            $instance->setSalt($record['salt']);
            $instance->setLastLogin($record['last_login']);
            $instance->setPassword($record['password']);
        }
        
        return $instance;
    }
    /**
     * Returns a string that represents the hash of user password.
     * 
     * @return string The returned value can be used in comparison with 
     * the hash of the password at which the user provides.
     */
    public function getPassHash() {
        return hash('sha256', $this->getSalt().$this->getPassword());
    }
    /**
     * Returns an object that holds user settings.
     * 
     * @return UserSettings|null
     */
    public function getSettings() {
        return $this->settings;
    }
    /**
     * Sets the object which is used to hold user settings.
     * 
     * @param UserSettings $settings
     */
    public function setSettings(UserSettings $settings) {
        $this->settings = $settings;
    }
    /**
     * Returns an object of type 'Json' that contains object information.
     * 
     * @return Json An object of type 'Json'.
     */
    public function toJSON() {
        $json = parent::toJSON();
        $privileges = [];
        
        foreach ($this->privileges() as $pr) {
            $privileges[] = $pr->getID();
        }
        
        $json->addMultiple([
            'settings' => $this->getSettings(),
            'last-login' => $this->getLastLogin(),
            'privileges' => $privileges
        ]);
        return $json;
    }
}
