<?php
namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;
/**
 * A class that can be used to add a top heading to the
 * page or inside a section.
 */
class Heading extends HTMLNode {
    private $headingNode;
    /**
     * Creates new instance of the class.
     * 
     * @param HTMLNode|string $title A string that represents page title.
     * This also can be an object of type 'HTMLNode'
     * 
     * @param int $level Heading level. A value between 1 and 6 inclusive.
     */
    public function __construct($title, $level = 1) {
        parent::__construct('v-row', [
            'class' => 'my-10'
        ]);
        $xlevel = $level >= 1 && $level <= 6 ? $level : 1;
        $this->headingNode = $this->addChild('v-col', [
            'cols' => 12
        ])->addChild('h'.$xlevel);
        $this->setText($title);
    }
    /**
     * Sets the text that will appear in the body of the heading.
     * 
     * @param HTMLNode|string $textOrNode This can be an object of type HTMLNode
     * or a simple string.
     */
    public function setText($textOrNode) {
        $this->headingNode->removeAllChildren();

        if ($textOrNode instanceof HTMLNode) {
            $this->headingNode->addChild($textOrNode);
        } else if (gettype($textOrNode) == 'string') {
            $this->headingNode->text($textOrNode);
        }
    }
    /**
     * Returns the note that contains heading text.
     * 
     * @return HTMLNode
     */
    public function getHedingNode() {
        return $this->headingNode;
    }
}
