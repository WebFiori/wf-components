<?php
namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;

class Heading extends HTMLNode {
    private $headingNode;
    public function __construct($title, $level = 1) {
        parent::__construct('v-row', [
            'class' => 'my-10'
        ]);
        $level = $level >= 1 && $level <= 6 ? $level : 1;
        $this->headingNode = $this->addChild('v-col', [
            'cols' => 12
        ])->addChild('h'.$level);
        $this->setText($title);
    }
    public function setText($textOrNode) {
        $this->headingNode->removeAllChildren();

        if ($textOrNode instanceof HTMLNode) {
            $this->headingNode->addChild($textOrNode);
        } else if (gettype($textOrNode) == 'string') {
            $this->headingNode->text($textOrNode);
        }
    }
    public function getHedingNode() {
        return $this->headingNode;
    }
}
