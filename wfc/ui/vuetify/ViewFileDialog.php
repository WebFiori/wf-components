<?php

namespace wfc\ui\vuetify;

use wfc\ui\vuetify\Dialog;
use wfc\ui\vuetify\VBtn;
/**
 * A simple dialog which can be used to display a file in the same page as pop-up.
 *
 * @author Ibrahim
 */
class ViewFileDialog extends Dialog {
    /**
     * 
     * @param string $model The name of the object that will 
     * hold dialog properties. The object must have at least following
     * props:
     * <ul>
     * <li>visible: Show or hide the dialog.</li>
     * <li>file: The raw data of the file that will be displayed.</li>
     * <li>mime: MIME type of the file. Used to decide how the browser will render the file.</li>
     * </ul>
     * 
     * @param string $downloadAction The name of JavaScript function that will be
     * called in case download button is clicked. This is optional.
     */
    public function __construct(string $model, string $downloadAction = null) {
        parent::__construct($model, true);
        $this->setAttribute('width', '70%');
        $this->removeAttribute('max-width');
        $this->setAttribute(':fullscreen', $model.'.fullscreen');
        $this->getToolbar()->addChild(new VBtn([
            'icon',
            'dark',
            '@click' => $model.".visible = false",
            'icon' => 'mdi-close'
        ]));
        
        if ($downloadAction !== null) {
            $this->getToolbar()->addChild(new VBtn([
                'icon',
                'dark',
                '@click' => $downloadAction,
                'icon' => 'mdi-download'
            ]));
        }
        
        
        
        $this->addToBody('object', [
            'v-if' => '!'.$model.'.fullscreen',
            ':data' => $model.'.file',
            ':type' => $model.'.mime',
            'style' => [
                'height' => '500px',
                'width' => '100%'
            ]
        ]);
        $this->addToBody('object', [
            'v-else',
            ':data' => $model.'.file',
            ':type' => $model.'.mime',
            'class' => 'fill-height',
            'style' => [
                'width' => '100%'
            ]
        ]);
    }
    /**
     * Adds buttons to head section of the dialog to minimize and maximize its size.
     */
    public function includeFullScreenButton() {
        $this->getToolbar()->addChild(new VBtn([
            'icon',
            'dark',
            '@click' => $this->getModel().".fullscreen = true",
            'icon' => 'mdi-arrow-expand-all',
            'v-if' => '!'.$this->getModel().'.fullscreen'
        ]));
        
        $this->getToolbar()->addChild(new VBtn([
            'icon',
            'dark',
            '@click' => $this->getModel().".fullscreen = false",
            'icon' => 'mdi-arrow-collapse-all',
            'v-if' => $this->getModel().'.fullscreen'
        ]));
    }
}
