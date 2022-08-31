<?php

namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;
use wfc\ui\vuetify\VDialog;
use webfiori\framework\exceptions\UIException;
use wfc\ui\vuetify\VBtn;
/**
 * A v-list element which supports performing CRUD operations.
 *
 * @author Ibrahim BinAlshikh
 */
class CRUDList extends HTMLNode {
    private $top;
    private $vlist;
    /**
     * 
     * @var VDialog
     */
    private $addEditDialog;
    /**
     * 
     * @var VDialog
     */
    private $confirmDeleteDialog;
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
    private $cancelConfirmBtn;
    /**
     * 
     * @var HTMLNode
     */
    private $confirmBtn;
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
     * If not provided, the value '{{dialog.title}}' is used where 'dialog' is the 
     * name of dialog model.</li>
     * <li>add-action: The method that will be called when the confirm button of the 
     * add/edit dialog is clicked.</li>
     * <li>edit-action: The method that will be called when the edit button is clicked.
     * It accepts the item and its index in the list (e.g. editItem(item, index).</li>
     * <li>delete-title: The title that will appear in the top of the delete dialog.
     * If not provided, the value '{{dialog.title}}' is used where 'dialog' is the 
     * name of delete dialog model.</li>
     * <li>confirm-delete-action: The method that will be called when the delete button is clicked.
     * It accepts the item and its index in the list (e.g. confirmDelete(item, index).</li>
     * <li>delete-action: The method that will be called when the action delete is performed.</li>
     * <li>delete-prompt: The text that will appear in the body of the delete dialog.</li>
     * </ul>
     */
    public function __construct($props) {
        parent::__construct('v-card', [
            'elevation' => 1
        ]);
        if (!isset($props['dialog'])) {
            throw new UIException('The add/edit dialog model is missing.');
        }
        $this->addEditDialog = new VDialog($props['dialog'], true);
        if (!isset($props['confirm-delete-dialog'])) {
            throw new UIException('The "confirm-delete-dialog" model is missing.');
        }
        $this->confirmDeleteDialog = new VDialog($props['confirm-delete-dialog'], true);
        $this->addChild($this->confirmDeleteDialog);
        $deleteText = isset($props['delete-prompt']) ? $props['delete-prompt'] : 'Are you sure that you would like to remove the item?';
        $this->getConfirmDeleteDialog()->addToBody('v-card-text', [
            'class' => 'pa-5'
        ])->text($deleteText);
        
        $this->createHeader($props);
        $this->createBody($props);
        
        $this->inputsRow = $this->getDialog()->addToBody('v-card-text')->addChild('v-row');
        $this->inputsRow->addChild('v-col', [
            'cols' => 12
        ]);
        
        $this->addDialogActions($props);
        $this->addConfirmDeleteActions($props);
        
        $dialogTitle = isset($props['dialog-title']) ? $props['dialog-title'] : '{{'.$props['dialog'].'.title}}';
        $this->getDialog()->getToolbar()->addChild('v-toolbar-title')->text($dialogTitle);
        
        $deleteDialogTitle = isset($props['delete-title']) ? $props['delete-title'] : '{{'.$props['confirm-delete-dialog'].'.title}}';
        $this->getConfirmDeleteDialog()->getToolbar()->addChild('v-toolbar-title')->text($deleteDialogTitle);
        
    }
    /**
     * Returns the v-list element that contains list items.
     * 
     * @return HTMLNode
     */
    public function getVList() {
        return $this->vlist;
    }
    private function createBody($props) {
        $this->vlist = $this->addChild('v-card-text')->addChild('v-list', [
            'dense',
        ]);
        $itemsGroup = $this->vlist->addChild('v-list-item-group',[
            
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
        ])->addChild(new VBtn([
            '@click' => $editAction.'(item, i)',
            'icon' => 'mdi-pencil',
            'icon-props' => [
                'color' => 'primary lighten-1', 
                'small'
            ]
        ]));
        
        $deleteConfirmAction = isset($props['confirm-delete-action']) ? $props['confirm-delete-action'] : 'confirmDeleteItem';
        
        $this->deleteBtn = new VBtn([
            '@click' => $deleteConfirmAction.'(item, i)',
            'icon' => 'mdi-delete',
            'icon-props' => [
                'color' => 'red lighten-2', 
                'small'
            ]
        ]);
        
        $listItem->addChild('v-list-item-action', [
            'class' => 'ma-0'
        ])->addChild($this->deleteBtn);
        
        
    }
    /**
     * Returns the element that represents the top section of the component.
     * 
     * The section simply contains the title of the component and the add button.
     * 
     * @return HTMLNode
     */
    public function getVToolbar() {
        return $this->top;
    }
    private function createHeader($props) {
        $this->top = $this->addChild('v-card-text', [
            'class' => 'py-0'
        ])->addChild('v-toolbar', [
            'dense', 'elevation',
            'flat',
        ]);
        $this->addChild('v-divider');
        $componentTitle = isset($props['title']) ? $props['title'] : '';
        $this->getVToolbar()->addChild('p', [
            'class' => 'text--secondary ma-0'
        ])->text($componentTitle);
        $this->getVToolbar()->addChild('v-spacer');
        $this->getVToolbar()->addChild($this->getDialog());
        
        $this->addBtn = $this->getDialog()->addChild('template', [
            '#activator' => '{on, attrs}'
        ])->addChild(new VBtn([
            'v-bind'=>"attrs",
            'v-on'=>"on",
            'icon' => 'mdi-plus-circle',
            'icon-props' => [
                'color' => "primary lighten-1"
            ]
        ]));
    }

    private function addConfirmDeleteActions($props) {
        $this->getConfirmDeleteDialog()->addToBody('v-divider');
        $confirmActions = $this->getConfirmDeleteDialog()->addToBody('v-card-actions');
        
        $confirmActions->addChild('v-spacer');
        $this->cancelConfirmBtn = $confirmActions->addChild(new VBtn([
            '@click' => $props['confirm-delete-dialog'].".visible = false",
            'icon' => 'mdi-close-circle',
            'icon-props' => [
                'color' => 'red lighten-2'
            ]
        ]));
        
        $deleteAction = isset($props['delete-action']) ? $props['delete-action'] : 'deleteItem';
        
        $this->confirmBtn = $confirmActions->addChild(new VBtn([
            '@click' => $deleteAction,
            'icon' => 'mdi-check-circle',
            'icon-props' => [
                'color' => 'green lighten-1'
            ]
        ]));
        $confirmActions->addChild('v-spacer');
        
    }

    private function addDialogActions($props) {
        $this->getDialog()->getVCard()->addChild('v-divider');
        $actionsContainer = $this->getDialog()->getVCard()->addChild('v-card-actions');
        $actionsContainer->addChild('v-spacer');
        
        $this->cancelBtn = $actionsContainer->addChild(new VBtn([
            '@click' => $props['dialog'].".visible = false",
            'icon' => 'mdi-close-circle',
            'icon-props' => [
                'color' => 'red lighten-2'
            ]
        ]));
        
        $addAction = isset($props['add-action']) ? $props['add-action'] : 'addItem';
        $this->saveBtn = $actionsContainer->addChild(new VBtn([
            '@click' => $addAction,
            'icon' => 'mdi-check-circle',
            'icon-props' => [
                'color' => 'green lighten-1'
            ]
        ]));
    }

    /**
     * Returns the button which is used to confirm delete operation.
     * 
     * @return VBtn an element with name 'v-btn'.
     */
    public function getConfirnBtn() {
        return $this->confirmBtn;
    }
    /**
     * Returns the button which is used to close the delete dialog.
     * 
     * @return VBtn an element with name 'v-btn'.
     */
    public function getConfirnCancelBtn() {
        return $this->cancelConfirmBtn;
    }
    /**
     * Returns the button which is used to call the 'delete' method.
     * 
     * @return VBtn an element with name 'v-btn'.
     */
    public function getDeleteBtn() {
        return $this->deleteBtn;
    }
    /**
     * Returns the button which is used to open add dialog.
     * 
     * @return VBtn an element with name 'v-btn'.
     */
    public function getAddBtn() {
        return $this->addBtn;
    }
    /**
     * Returns the button which is used to call the 'edit' method.
     * 
     * @return VBtn an element with name 'v-btn'.
     */
    public function getEditBtn() {
        return $this->editBtn;
    }
    /**
     * Returns the button which is used to call the 'cancel' method.
     * 
     * @return VBtn an element with name 'v-btn'.
     */
    public function getCancelBtn() {
        return $this->cancelBtn;
    }
    /**
     * Returns the button which is used to call the 'save' method.
     * 
     * @return VBtn an element with name 'v-btn'.
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
     * @return VDialog
     */
    public function getDialog() {
        return $this->addEditDialog;
    }
    /**
     * Returns the dialog which is used as confirm delete dialog.
     * 
     * @return VDialog
     */
    public function getConfirmDeleteDialog() {
        return $this->confirmDeleteDialog;
    }
}
