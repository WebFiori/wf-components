<?php
namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;
/**
 * A single component which is used to construct gregorian date 
 * input field.
 * 
 * The component is simply a 'v-ment' that has a 'v-text-field' component
 * and a 'v-date-picker' component.
 *
 * @author Ibrahim
 */
class DateInput extends HTMLNode {
    /**
     * 
     * @var HTMLNode
     */
    private $textField;
    /**
     * 
     * @var HTMLNode
     */
    private $datePicker;
    /**
     * Creates new instance of the class.
     * 
     * @param string $model The name of the model that will be associated with
     * the text field and date picker.
     * 
     * @param string $menuModel The name of the model that will be associated with
     * date picker's menu component.
     * 
     * @param string $label An optional label to show on the component.
     * 
     * @param string $placeholder An optional placeholder to show on the
     * component.
     * 
     */
    public function __construct(string $model = null, string $menuModel = null, string $label = null, string $placeholder = null) {
        parent::__construct('v-menu', [
            ':close-on-content-click' => "false",
            'transition' => "scale-transition",
            'offset-y',
            'min-width' => "290px",
        ]);
        $pickerAttrs = [];
        $textFieldAttrs = [];
        
        $textFieldAttrs['v-bind'] = 'attrs';
        $textFieldAttrs['v-on'] = 'on';
        $textFieldAttrs['prepend-inner-icon'] = 'mdi-calendar';
        
        $this->textField = $this->addChild('template ', [
            'v-slot:activator' => "{ on, attrs }"
        ], false)->addChild('v-text-field', $textFieldAttrs);

        $this->datePicker = $this->addChild('v-date-picker', $pickerAttrs);
        
        if ($label !== null) {
            $this->getTextField()->setAttribute('label', $label);
        }
        if ($placeholder !== null) {
            $this->getTextField()->setAttribute('placeholder', $placeholder);
        } else {
            $this->getTextField()->setAttribute('placeholder', 'YYYY-MM-DD');
        }
        if ($menuModel !== null) {
            $this->setMenuVModel($menuModel);
        }
        if ($model !== null) {
            $this->setVModel($model);
        }
    }
    
    /**
     * Sets the value of the attribute input of the text field and change of the
     * date select.
     *  
     * @param string $method The name of JavaScript method.
     */
    public function setOnInput(string $method) {
        $this->getTextField()->setAttribute('@input', $method);
        $this->getDatePicker()->setAttribute('@change', $method);
    }
    /**
     * Sets the v-model of the menu component.
     * 
     * @param string $model The name of the model.
     */
    public function setMenuVModel(string $model) {
        $this->setAttribute('v-model', $model);
        $this->getDatePicker()->setAttribute('@input', $model.' = false');
    }
    /**
     * Sets v-model of the text field and the date picker.
     * 
     * @param string $model The name of the model.
     */
    public function setVModel(string $model) {
        $this->getTextField()->setAttribute('v-model', $model);
        $this->getDatePicker()->setAttribute('v-model', $model);
    }
    /**
     * Returns the 'v-text-field' component of the picker.
     * 
     * @return HTMLNode
     */
    public function getTextField() : HTMLNode {
        return $this->textField;
    }
    /**
     * Returns the 'v-date-picker' component of the picker.
     * 
     * @return HTMLNode
     */
    public function getDatePicker() : HTMLNode {
        return $this->datePicker;
    }
}
