<?php
namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;
/**
 * A class that represents a v-btn element.
 *
 * @author Ibrahim
 */
class VBtn extends HTMLNode {
    /**
     * 
     * @var HTMLNode|null
     */
    private $iconNode;
    /**
     * 
     * @var HTMLNode|null
     */
    private $textNode;
    /**
     * 
     * @var HTMLNode|null
     */
    private $loaderTemplate;
    /**
     * Creates a new instance of the class.
     * 
     * @param array $props An array that can hold proprties for the button.
     * The array can have the following indices to initialize special proprties:
     * <ul>
     * <li>label: A text to show on the button.</li>
     * <li>icon: The name of MDI icon to show on the button.</li>
     * <li>icon-props: An array that can have properties to set for the icon.</li>
     * </ul>
     */
    public function __construct($props = []) {
        parent::__construct('v-btn');
        
        if (isset($props['label'])) {
            $this->setLabel($props['label']);
            unset($props['label']);
        }
        if (isset($props['icon'])) {
            if (isset($props['icon-props'])) {
                $this->setIcon($props['icon'], $props['icon-props']);
            } else {
                $this->setIcon($props['icon']);
            }
            unset($props['icon']);
            unset($props['icon-props']);
        }
        $this->setAttributes($props);
    }
    /**
     * Sets the icon that will be shown on the button.
     * 
     * @param string $mdiIcon A string that represents MDI icon name such as 'mdi-information'.
     * 
     * @param array $iconProps An array that contains properties for the icon
     * such as its color.
     */
    public function setIcon($mdiIcon, array $iconProps = []) {
        if ($this->iconNode === null) {
            $this->iconNode = $this->addChild('v-icon');
        }
        if ($this->iconNode->childrenCount() != 1) {
            $this->iconNode->text('');
        }
        $this->iconNode->getChild(0)->setText($mdiIcon);
        $this->iconNode->setAttributes($iconProps);
    }
    /**
     * Returns the icon which was added to the v-btn component.
     * 
     * @return HTMLNode|null If an icon is added to the button, the method
     * will return it as an object of type HTMLNode. Other than that, null
     * is returned.
     */
    public function getVIcon() {
        return $this->iconNode;
    }
    /**
     * Sets a text to show on the button.
     * 
     * @param string|null $text A string that represents the text. If null is given,
     * the text will be removed from the button.
     */
    public function setLabel($text) {
        if ($text === null) {
            $this->removeChild($this->textNode);
            return;
        }
        
        if ($this->textNode !== null) {
            $this->textNode->setText($text);
        } else {
            $this->textNode = new HTMLNode(self::TEXT_NODE);
            $this->textNode->setText($text);
            $this->insert($this->textNode, 0);
        }
    }
    /**
     * Sets a custom loader element for the 'loader' slot.
     * 
     * @param HTMLNode $loader An HTML element that will appear if the button
     * is in loading state.
     */
    public function addCustomLoader(HTMLNode $loader) {
        if ($this->loaderTemplate === null) {
            $this->loaderTemplate = $this->addChild('template', [
                '#loader'
            ]);
        }
        $this->loaderTemplate->addChild($loader);
    }
}
