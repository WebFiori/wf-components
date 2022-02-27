<?php

namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;
use wfc\ui\vuetify\Dialog;
/**
 * A v-list elemrnt which supports performing CRUD operations.
 *
 * @author Ibrahim BinAlshikh
 */
class CRUDList extends HTMLNode {
    /**
     * 
     * @var Dialog
     */
    private $addEditDialog;
    /**
     * 
     * @var HTMLNode
     */
    private $inputsRow;
    /**
     * 
     * @var HTMLNode
     */
    private $addBtn;
    /**
     * 
     * @var HTMLNode
     */
    private $cancelBtn;
    /**
     * 
     * @var HTMLNode
     */
    private $editBtn;
    /**
     * 
     * @var HTMLNode
     */
    private $deleteBtn;
    /**
     * 
     * @var HTMLNode
     */
    private $saveBtn;

    /**
     * 
     * @param array $props An array that holds the properties of the list.
     * The array must have following indices:
     * <ul>
     * <li>title: The string that will appear at the header of the component. If not
     * provided, empty string is used.</li>
     * <li>items: The model that will hold list items.</li>
     * <li>dialog: The name of dialog model that will be used by the add/edit 
     * dialog. It follows same structure which is defined by the class 'Dialog'.</li>
     * <li>dialog-title: The title that will appear in the top of the add/edit dialog.
     * If not provided, the value 'New Item' is used.</li>
     * <li>add-action: The method that will be called when the action of 
     * add/edit dialog is 'add'</li>
     * <li>add-action: The method that will be called when the action of 
     * add/edit dialog is 'edit'. It accepts the item and its index in the list.</li>
     * <li>add-action: The method that will be called when the action delete is performed.</li>
     * </ul>
     */
    public function __construct($props) {
        parent::__construct('v-card', [
            'elevation' => 1
        ]);
        
        $top = $this->addChild('v-card-text', [
            'class' => 'py-0'
        ])->addChild('v-toolbar', [
            'dense', 'elevation',
            'flat'
        ]);
        $this->addChild('v-divider');
        $componentTitle = isset($props['title']) ? $props['title'] : '';
        $top->addChild('p', [
            'class' => 'text--secondary ma-0'
        ])->text($componentTitle);
        $top->addChild('v-spacer');
        $this->addEditDialog = new Dialog($props['dialog'], true);
        $this->getDialog()->setAttribute('max-width', 500);
        
        $top->addChild($this->getDialog());
        
        $this->addBtn = $this->getDialog()->addChild('template', [
            '#activator' => '{on, attrs}'
        ])->addChild('v-btn', [
            'color' => "primary",
            'text',
            'v-bind'=>"attrs",
            'v-on'=>"on"
        ]);
        $this->getAddBtn()->addChild('v-icon', [
            'color' => "primary",
        ])->text('mdi-plus-circle ');
        
        $itemsGroup = $this->addChild('v-card-text')->addChild('v-list', [
            'dense',
        ])->addChild('v-list-item-group',[
            
        ]);
        $itemsGroup->addChild('v-list-item', [
            'v-if' => $props['items'].'.length === 0',
            'disabled'
        ])->addChild('v-list-item-content', [
            'class' => 'text--disabled'
        ])->text('NO DATA');
        $listItem = $itemsGroup->addChild('v-list-item', [
            'v-else',
            'v-for' => '(item, i) in '.$props['items'],
            ':key' => 'i',
            'dense'
        ]);
        
        $listItem->addChild('v-list-item-content')->addChild('v-list-item-title')->text('{{ item.text }}');
        $editAction = isset($props['edit-action']) ? $props['edit-action'] : 'editItem';
        $this->editBtn = $listItem->addChild('v-list-item-action', [
            'class' => 'ma-0'
        ])->addChild('v-btn', [
            'icon', 'text',
            '@click' => $editAction.'(item, i)'
        ]);
        $this->getEditBtn()->addChild('v-icon', ['color' => 'primary', 'small'])->text('mdi-pencil');
        
        $deleteAction = isset($props['delete-action']) ? $props['delete-action'] : 'deleteItem';
        $listItem->addChild('v-list-item-action', [
            'class' => 'ma-0'
        ])->addChild('v-btn', [
            'icon', 'text',
            '@click' => $deleteAction.'(item, i)'
        ])->addChild('v-icon', ['color' => 'red lighten-2', 'small'])->text('mdi-delete');
        
        $dialogTitle = isset($props['dialog-title']) ? $props['dialog-title'] : 'New Item';

        $this->getDialog()->getToolbar()->addChild('v-toolbar-title')->text($dialogTitle);
        
        $this->inputsRow = $this->getDialog()->getVCard()->addChild('v-card-text')->addChild('v-row');
        $this->inputsRow->addChild('v-col', [
            'cols' => 12
        ]);
        
        $this->getDialog()->getVCard()->addChild('v-divider');
        $actionsContainer = $this->getDialog()->getVCard()->addChild('v-card-actions');
        $actionsContainer->addChild('v-spacer');
        
        $this->cancelBtn = $actionsContainer->addChild('v-btn', [
            'color' => "blue darken-1",
            'text',
            '@click' => $props['dialog'].".visible = false"
        ]);
        $this->getCancelBtn()->addChild('v-icon', [
            'color' => 'red lighten-1'
        ])->text('mdi-close-circle');
        
        $addAction = isset($props['add-action']) ? $props['add-action'] : 'addItem';
        $this->saveBtn = $actionsContainer->addChild('v-btn', [
            'color' => "blue darken-1",
            'text',
            '@click' => $addAction,
        ]);
        $this->getSaveBtn()->addChild('v-icon', [
            'color' => 'green'
        ])->text('mdi-check-circle');
    }
    /**
     * Returns the button which is used to call the 'delete' method.
     * 
     * @return HTMLNode an element with name 'v-btn'.
     */
    public function getDeleteBtn() {
        return $this->deleteBtn;
    }
    /**
     * Returns the button which is used to open add dialog.
     * 
     * @return HTMLNode an element with name 'v-btn'.
     */
    public function getAddBtn() {
        return $this->addBtn;
    }
    /**
     * Returns the button which is used to call the 'edit' method.
     * 
     * @return HTMLNode an element with name 'v-btn'.
     */
    public function getEditBtn() {
        return $this->editBtn;
    }
    /**
     * Returns the button which is used to call the 'cancel' method.
     * 
     * @return HTMLNode an element with name 'v-btn'.
     */
    public function getCancelBtn() {
        return $this->cancelBtn;
    }
    /**
     * Returns the button which is used to call the 'save' method.
     * 
     * @return HTMLNode an element with name 'v-btn'.
     */
    public function getSaveBtn() {
        return $this->saveBtn;
    }
    /**
     * Returns a node that represents a 'v-col' element added to the body of
     * the 'add/edit' dialog.
     * 
     * This method can be used to add input fields to the body of the add/edit
     * dialog.
     * 
     * @param array $colProps An optional array that can be used to set column
     * properties.
     * 
     * @return HTMLNode A node that represents a 'v-col' element added to the body of
     * the 'add/edit' dialog.
     */
    public function addCol($colProps = ['cols' => 12, 'sm' => 12]) {
        return $this->inputsRow->addChild('v-col', $colProps);
    }
    /**
     * Returns the dialog which is used as add/edit dialog.
     * 
     * @return Dialog
     */
    public function getDialog() {
        return $this->addEditDialog;
    }
}
