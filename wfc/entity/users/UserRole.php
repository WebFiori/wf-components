<?php
namespace wfc\entity\users;

use webfiori\framework\PrivilegesGroup;

/**
 * An auto-generated entity class which maps to a record in the
 * table 'user_roles'
 **/
class UserRole extends PrivilegesGroup {
    /**
     * Returns an object of type 'Json' that contains object information.
     * 
     * The returned object will have the following attributes:
     * <ul>
     * <li>homePage</li>
     * <li>id</li>
     * <li>name</li>
     * <li>privileges</li>
     * </ul>
     * 
     * @return Json An object of type 'Json'.
     */
    public function toJSON() {
        $json = parent::toJSON();
        $json->addMultiple([
            'value' => $this->getID(),
            'text' => $this->getName()
        ]);
        return $json;
    }
}
