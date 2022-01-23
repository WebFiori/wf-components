<?php
namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;
/**
 * A v-card element that can be used to display privileges tree.
 *
 * @author Ibrahim
 */
class PrivilegesTree extends HTMLNode {
    private $treeview;
    private $title;
    private $search;
    /**
     * Creates new instance of the class.
     * 
     * @param string $model The name of the object that will hold tree attributes.
     * The object must have the following attributes:
     * <ul>
     * <li>privileges: An array that holds a tree of privileges as an object.
     * Each object can have 3 attributes: 'id', 'name' and 'children'.</li>
     * <li>selected_privileges: An array that holds selected privileges (Ids)</li>
     * <li>search_model: If search is enabled, this must be provided.</li>
     * </ul>
     * @param string|null $title An optional title to show at the top of the component.
     * 
     * @param boolean $withSearch If set to true, a search field will be 
     * included in the component.
     */
    public function __construct($model = 'privileges_tree', $title = null, $withSearch = false, ) {
        parent::__construct('v-card');
        if ($title !== null) {
            $this->title = $this->addChild('v-card-title')->text($title);
        }
        if ($withSearch === true) {
            $this->search = $this->addChild('v-card-text')->addChild('v-text-field', [
                'v-model' => $model.'.search_model'
            ]);
        }
        $this->treeview = $this->addChild('v-card-text')->addChild('v-treeview', [
            ':items' => $model.'.privileges',
            ':search' => $model.'.search_model',
            'v-model' => $model.'.selected_privileges',
            'hide-details','dense',
            'clear-icon' => "mdi-close-circle-outline",
            'selectable',
            'rounded',
            'hoverable',
            'activatable',
        ]);
    }
    /**
     * Returns the 'v-text-field' that represents search field.
     * 
     * @return HTMLNode|null If search is enabled, the method will return an
     * object of type HTMLNode. Other than that, null is returned.
     */
    public function getSearchField() {
        return $this->search;
    }
    /**
     * Returns the 'v-treeview' that represents privileges tree.
     * 
     * @return HTMLNode The method will return an
     * object of type HTMLNode.
     */
    public function getTreeview() {
        return $this->treeview;
    }
    /**
     * Sets the JS function which will be get executed if a checkbox in the
     * tree changed value.
     * 
     * This will set the value of the attribute '@input' of the 'v-treeview'.
     * 
     * @param string $jsFunctionName
     */
    public function setOnPrivilegeChange($jsFunctionName) {
        $this->treeview->setAttribute('@input', $jsFunctionName);
    }
}
