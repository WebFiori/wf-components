<?php

namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;

/**
 * A class that represents a 'v-dialog' element.
 *
 * @author Ibrahim
 */
class VDialog extends HTMLNode {
    private $vCard;
    private $vToolbar;
    private $model;
    /**
     * Creates new instance of the class.
     * 
     * @param string $model The name of the object that will 
     * hold dialog properties. The object must have at least following
     * probs:
     * <ul>
     * <li>visible</li>
     * <li>title (not required if toolbar is added)</li>
     * <li>icon( not required if toolbar is added)</li>
     * <li>icon_color (not required if toolbar is added)</li>
     * </ul>
     * 
     * @param boolean $toolbar If set to true, the dialog will only have a
     * 'v-toolbar' in the body.
     */
    public function __construct($model, $toolbar = false) {
        parent::__construct('v-dialog', [
            'v-model' => $model.'.visible',
            'scrollable',
            'max-width' => 500
        ]);
        $this->model = $model;
        $this->vCard = parent::addChild('v-card');
        
        if ($toolbar === true) {
            $this->vToolbar = $this->vCard->addChild('v-toolbar', [
                'color' => 'primary',
                'max-height' => 64
            ]);
            return;
        }
        
        $propsArr = [
            'style' => [
                'margin' => '10px'
            ],
            ':color' => $model.'.icon_color'
        ];
        
        $this->vCard->addChild('v-card-title', [
            'class' => 'text-h5 grey lighten-2'
        ])
            ->addChild('v-icon', $propsArr)
            ->text('{{ '.$model.'.icon }}')
            ->getParent()->addChild('div', [
                'style' => [
                    'display' => 'inline'
                ]
            ])->text("{{ ".$model.".title }}");
    }
    /**
     * Adds close button to the toolbar of the dialog.
     * 
     * This only applies of the dialog has a toolbar added to it.
     * 
     * @param string $closeAction An optional javaScript function to call
     * in case of button click.
     * 
     * @return VBtn|null If a button is added, the method will return it as
     * an object of type 'VBtn'. Other than that, null is returned.
     */
    public function addCloseBtn(string $closeAction = null) {
        $toolbar = $this->getToolbar();
        
        if ($toolbar !== null) {
            $clickEvnt = $closeAction !== null ? $closeAction : $this->getModel().".visible = false";
            
            return $toolbar->addChild(new VBtn([
                'icon',
                'dark',
                '@click' => $clickEvnt,
                'icon' => 'mdi-close'
            ]));
        }
    }
    /**
     * Returns the name of dialog model that will contain dialog properties.
     * 
     * @return string
     */
    public function getModel() : string {
        return $this->model;
    }
    /**
     * Returns the 'v-card-title' element that contains the title of the 
     * dialog.
     * 
     * @return HTMLNode|null If the dialog has a toolbar, the method will
     * return null. Other than that, it will return the node that represents
     * title node.
     */
    public function getTitleContainer() {
        if ($this->vToolbar !== null) {
            return null;
        }
        $this->vCard->getChild(0);
    }
    /**
     * Returns the element that represents dialog toolbar.
     * 
     * @return null|HTMLNode If the dialog is set to have a toolbar, the 
     * method will return an HTML node that represents it. Other than that,
     * the method will return null.
     */
    public function getToolbar() {
        return $this->vToolbar;
    }
    /**
     * Returns the HTML node which is inserted in the body of the dialog
     * as 'v-card' element.
     * 
     * @return HTMLNode
     */
    public function getVCard() {
        return $this->vCard;
    }
    /**
     * Add a child to the body of the dialog.
     * 
     * The element that represents the body of the dialog is the 'v-card' element.
     * 
     * @param HTMLNode|string $node
     * 
     * @param array|boolean $attrsOrChain
     * 
     * @param boolean $chainOnParent
     * 
     * @return HTMLNode The added element.
     */
    public function addToBody($node, $attrsOrChain = [], $chainOnParent = false) {
        return $this->getVCard()->addChild($node, $attrsOrChain, $chainOnParent);
    }
}
