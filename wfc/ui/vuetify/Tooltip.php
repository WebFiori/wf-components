<?php

namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;

/**
 * A class which is used to represent a v-tooltip.
 *
 * @author Ibrahim
 */
class Tooltip extends HTMLNode {
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
    public function setActivator(HTMLNode $el) {
        $this->contentArea->removeAllChildNodes();
        $el->setAttributes([
            'v-bind' => "attrs",
            'v-on' => "on",
        ]);
        $this->contentArea->addChild($el);
    }
    /**
     * A method that does nothing.
     * 
     * @param type $node
     * @param type $attrsOrChain
     * @param type $chainOnParent
     */
    public function addChild($node, $attrsOrChain = [], $chainOnParent = false) {
        
    }
    public function setTooltip(HTMLNode $el) {
        if ($this->childrenCount() == 2) {
            $this->removeLastChild();
        }
        parent::addChild($el);
    }
    public function setPosition(string $position) {
        $this->setAttribute($position);
    }
}
