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
     * 
     * @param string $textFieldProps An optional array that holds properties of the
     * text field that will hold date value.
     * 
     */
    public function __construct(string $model = null, string $menuModel = null, array $textFieldProps = []) {
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
        
        $this->getTextField()->setAttributes($textFieldProps);
        
        if (!isset($textFieldAttrs['placeholder'])) {
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
     * 
     * @return DateInput
     */
    public function setOnInput(string $method) : DateInput {
        $this->getTextField()->setAttribute('@input', $method);
        $this->getDatePicker()->setAttribute('@change', $method);
        
        return $this;
    }
    /**
     * Sets the v-model of the menu component.
     * 
     * @param string $model The name of the model.
     * 
     * @return DateInput
     */
    public function setMenuVModel(string $model) : DateInput {
        $this->setAttribute('v-model', $model);
        $this->getDatePicker()->setAttribute('@input', $model.' = false');
        
        return $this;
    }
    /**
     * Sets v-model of the text field and the date picker.
     * 
     * @param string $model The name of the model.
     * 
     * @return DateInput
     */
    public function setVModel(string $model) : DateInput {
        $this->getTextField()->setAttribute('v-model', $model);
        $this->getDatePicker()->setAttribute('v-model', $model);
        
        return $this;
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
