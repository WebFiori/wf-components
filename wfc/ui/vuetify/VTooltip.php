<?php

namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;

/**
 * A class which is used to represent a v-tooltip.
 *
 * @author Ibrahim
 */
class VTooltip extends HTMLNode {
    private $contentArea;
    /**
     * Creates new instance of the class.
     * 
     * @param HTMLNode $activator The element that will activate the tooltip
     * when the mouse is hover over. This usually is a button or an icon.
     * 
     * @param HTMLNode $tooltip The tooltip that will appear when the mouse pointer
     * is over the activator. Commonly, a span element with a text.
     */
    public function __construct(HTMLNode $activator = null, HTMLNode $tooltip = null) {
        parent::__construct('v-tooltip', [
            'bottom'
        ]);
        $this->contentArea = $this->addChild('template', [
            '#activator' => '{on, attrs}',
        ]);
        if ($activator !== null) {
            $this->setActivator($activator);
        }
        if ($tooltip !== null) {
            $this->setTooltip($tooltip);
        }
    }
    /**
     * Sets the element that will act as tooltip activator.
     * 
     * @param HTMLNode|string $el
     * 
     * @return HTMLNode The method will return the added element.
     */
    public function setActivator($el) : HTMLNode {
        $this->contentArea->removeAllChildNodes();
        $xEl = $this->contentArea->addChild($el);
        $xEl->setAttributes([
            'v-bind' => "attrs",
            'v-on' => "on",
        ]);
        
        return $xEl;
    }
    /**
     * Sets the element that will act as the tooltip.
     * 
     * @param HTMLNode|string $el
     * 
     * @return HTMLNode The method will return the added element.
     */
    public function setTooltip($el) : HTMLNode {
        if ($this->childrenCount() == 2) {
            $this->removeLastChild();
        }
        return parent::addChild($el);
    }
    /**
     * Sets the position of the tooltip.
     * 
     * The position will be relative to the activator.
     * 
     * @param string $position
     */
    public function setPosition(string $position) {
        $this->setAttribute($position);
    }
}
