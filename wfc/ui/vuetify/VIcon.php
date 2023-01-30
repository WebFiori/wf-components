<?php

namespace wfc\ui\vuetify;

/**
 * A class that represents a v-icon vuetify element.
 *
 * @author i.binalshikh
 */
class VIcon extends \webfiori\ui\HTMLNode {
    /**
     * Creates new instance of the class.
     * 
     * @param string $icon The name of material design icon.
     * 
     * @param array $iconProps An optional array that holds properties of
     * the icon.
     */
    public function __construct(string $icon = 'mdi-information', array $iconProps = []) {
        parent::__construct('v-icon', $iconProps);
        $this->text('m');
        $this->setIcon($icon);
    }
    /**
     * Sets the icon that will be shown by the element.
     * 
     * @param string $icon The name of a material design icon such as 'mdi-eye'.
     * The 'mdi' part can be omitted of the name as it is optional. 
     */
    public function setIcon(string $icon) {
        $isSlot = $icon[0] == '{' && $icon[1] == '{';
        
        if (!$isSlot) {
            $sub = substr($icon, 0, 3);

            if ($sub !== 'mdi') {
                $icon = 'mdi-'.$icon;
            }
        }
        $this->getChild(0)->setText($icon);
    }
}
