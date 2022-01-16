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
     * @param string $model The name of the object that will hold component
     * properties. The object must have the following properties:
     * <ul>
     * <li>'menu' The model of the 'v-menu'. It must be a boolean.</li>
     * <li>'date' The model of the 'v-date-picker'. It must be a string.</li>
     * </ul>
     */
    public function __construct($model, $label = null, $placeholder = null) {
        parent::__construct('v-menu', [
            'ref' => $model,
            'v-model' => $model.'.menu',
            ':close-on-content-click' => "false",
            'transition' => "scale-transition",
            'offset-y',
            'min-width' => "290px",
        ]);
        $pickerAttrs = [];
        $textFieldAttrs = [];
        
        $pickerAttrs['v-model'] = $model.'.date';
        $textFieldAttrs['v-model'] = $model.'.date';
        $textFieldAttrs['v-bind'] = 'attrs';
        $textFieldAttrs['v-on'] = 'on';
        
        $this->textField = $this->addChild('template ', [
            'v-slot:activator' => "{ on, attrs }"
        ], false)->addChild('v-text-field', $textFieldAttrs);

        $pickerAttrs['@input'] = "$model.menu = false";
        $this->datePicker = $this->addChild('v-date-picker', $pickerAttrs);
        
        if ($label !== null) {
            $this->getTextField()->setAttribute('label', $label);
        }
        if ($placeholder !== null) {
            $this->getTextField()->setAttribute('placeholder', $placeholder);
        } else {
            $this->getTextField()->setAttribute('placeholder', 'YYYY-MM-DD');
        }
    }
    /**
     * Returns the 'v-text-field' component of the picker.
     * 
     * @return HTMLNode
     */
    public function getTextField() {
        return $this->textField;
    }
    /**
     * Returns the 'v-date-picker' component of the picker.
     * 
     * @return HTMLNode
     */
    public function getDatePicker() {
        return $this->datePicker;
    }
}
