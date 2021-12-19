<?php
namespace wfc\ui\vuetify;

use wfc\ui\vuetify\Dialog;
/**
 * A simple dialog for showing basic system messages with a close button.
 *
 * @author Ibrahim
 */
class SimpleMessageDialog extends Dialog {
    /**
     * Creates new instance of the class.
     * 
     * @param string $model The name of the object that will 
     * hold dialog properties. The object must have at least following
     * probs:
     * <ul>
     * <li>visible</li>
     * <li>title</li>
     * <li>message</li>
     * <li>icon</li>
     * <li>icon_color</li>
     * </ul>
     * 
     * @param string $closeText The text that will appear on the close button.
     * 
     * @param string $closeAction An optional function to call in case
     * close button is clicked.
     */
    public function __construct($model, $closeText = 'Close',  $closeAction = null) {
        parent::__construct($model, false);
        $this->setAttribute('width', '500');
        
        $dialogCard = $this->getVCard();
        
        $dialogCard->addChild('v-card-text', [
            'v-html' => $model.'.message'
        ]);
        $dialogCard->addChild('v-divider');
        $dialogActions = $dialogCard->addChild('v-card-actions');
        $dialogActions->addChild('v-spacer');
        $dialogActions->addChild('v-btn', [
            'color' => "primary",
            'text',
            '@click' => $closeAction === null ? $model.'.visible = false' : $closeAction
        ])->text($closeText);
    }
}
