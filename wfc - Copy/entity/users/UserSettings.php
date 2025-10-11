<?php

namespace wfc\entity\users;

/**
 * A class that holds user settings.
 *
 * @author Ibrahim
 */
class UserSettings {
    /**
     * The attribute which is mapped to the column '[id]'.
     * 
     * @var int
     **/
    private $id;
    /**
     * The attribute which is mapped to the column '[prefered_lang]'.
     * 
     * @var string
     **/
    private $preferedLang;
    /**
     * The attribute which is mapped to the column '[privileges]'.
     * 
     * @var string
     **/
    private $privileges;
    /**
     * Returns the value of the attribute 'id'.
     * 
     * The value of the attribute is mapped to the column which has
     * the name '[id]'.
     * 
     * @return int The value of the attribute.
     **/
    public function getId() {
        return $this->id;
    }
    /**
     * Returns the value of the attribute 'preferedLang'.
     * 
     * The value of the attribute is mapped to the column which has
     * the name '[prefered_lang]'.
     * 
     * @return string The value of the attribute.
     **/
    public function getPreferedLang() {
        return $this->preferedLang;
    }
    /**
     * Returns the value of the attribute 'privileges'.
     * 
     * The value of the attribute is mapped to the column which has
     * the name '[privileges]'.
     * 
     * @return string The value of the attribute.
     **/
    public function getPrivileges() {
        return $this->privileges;
    }
    /**
     * Sets the value of the attribute 'id'.
     * 
     * The value of the attribute is mapped to the column which has
     * the name '[id]'.
     * 
     * @param $id int The new value of the attribute.
     **/
    public function setId($id) {
        $this->id = $id;
    }
    /**
     * Sets the value of the attribute 'preferedLang'.
     * 
     * The value of the attribute is mapped to the column which has
     * the name '[prefered_lang]'.
     * 
     * @param $preferedLang string The new value of the attribute.
     **/
    public function setPreferedLang($preferedLang) {
        $this->preferedLang = $preferedLang;
    }
    /**
     * Sets the value of the attribute 'privileges'.
     * 
     * The value of the attribute is mapped to the column which has
     * the name '[privileges]'.
     * 
     * @param $privileges string The new value of the attribute.
     **/
    public function setPrivileges($privileges) {
        $this->privileges = $privileges;
    }
    /**
     * Maps a record which is taken from the table [user_settings] to an instance of the class.
     * 
     * @param array $record An associative array that represents the
     * record. The array should have the following indices:
     * <ul>
     * <li>[id]</li>
     * <li>[prefered_lang]</li>
     * <li>[privileges]</li>
     * </ul>
     * 
     * @return UserSettings An instance of the class.
     */
    public static function map(array $record) {
        $instance = new UserSettings();
        
        $instance->setId($record['id']);
        $instance->setPreferedLang($record['prefered_lang']);
        $instance->setPrivileges($record['privileges']);
        
        return $instance;
    }
    /**
     * Returns an object of type 'Json' that contains object information.
     * 
     * @return Json An object of type 'Json'.
     */
    public function toJSON() {
        $json = new Json([
            'preferedLang' => $this->getPreferedLang(),
        ]);
        return $json;
    }
}
