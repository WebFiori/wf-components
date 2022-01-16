<?php
namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;
/**
 * A 'v-row' component with 3 'v-autocomplete' inputs which can be used to
 * create a hijri date input.
 *
 * @author Ibrahim
 */
class HijriDateInput extends HTMLNode {
    private $yearInput;
    private $dayInput;
    private $monthInput;
    /**
     * Creates new instance of the class.
     * 
     * @param string $model The name of the object that represents the
     * model of the input field. The object must have the following properties:
     * <ul>
     * <li>'year': The place where the year value is stored.</li>
     * <li>'month': The place where the month value is stored.</li>
     * <li>'day': The place where the day value is stored.</li>
     * <li>'hijri_years': TAn array that holds possible values for hejri year 
     * input field.</li>
     * </ul>
     * 
     * @param string $labelTxt The label that will be shown at the top
     * of the field.
     */
    public function __construct($model, $labelTxt) {
        parent::__construct('v-row', [
            'no-gutters'
        ]);

        $label = new HTMLNode('v-label', [
            ':dark' => '$vuetify.theme.dark'
        ]);
        $label->text($labelTxt);
        $this->addChild('v-col', ['cols' => 12])->addChild($label);
        $this->yearInput = $this->addChild('v-col', [
            'cols' => 12, 'md' => 6
        ])->addChild('v-autocomplete', [
            ':items' => $model.'.hijri_years',
            'v-model' => $model.'.year',
            'dense', 'height' => 20
        ]);
        
        
        $this->monthInput = $this->addChild('v-col', [
            'cols' => 12, 'md' => 3
        ])->addChild('v-autocomplete', [
            ':items' => '[1,2,3,4,5,6,7,8,9,10,11,12]',
            'v-model' => $model.'.month',
            'dense', 'height' => 20
        ]);
        
        $this->dayInput = $this->addChild('v-col', [
            'cols' => 12, 'md' => 3
        ])->addChild('v-autocomplete', [
            ':items' => '[1,2,3,4,5,6,7,8,9,10,'
            . '11,12,13,14,15,16,17,18,19,20,'
            . '21,22,23,24,25,26,27,28,29,30]',
            'v-model' => $model.'.day',
            'dense', 'height' => 20
        ]);
    }
    /**
     * Returns the 'v-autocomplete' that represents year input field.
     * 
     * @return HTMLNode
     */
    public function getYearInput() {
        return $this->yearInput;
    }
    /**
     * Returns the 'v-autocomplete' that represents month input field.
     * 
     * @return HTMLNode
     */
    public function getMonthInput() {
        return $this->monthInput;
    }
    /**
     * Returns the 'v-autocomplete' that represents day input field.
     * 
     * @return HTMLNode
     */
    public function getDayInput() {
        return $this->dayInput;
    }
}
