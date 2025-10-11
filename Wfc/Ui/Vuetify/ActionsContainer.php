<?php

namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;
use wfc\ui\vuetify\VBtn;
use wfc\ui\vuetify\VTooltip;
/**
 * A div element that can be used to contain a set of action buttons.
 *
 * @author Ibrahim
 */
class ActionsContainer extends HTMLNode {
    /**
     * Create new instance of the class.
     */
    public function __construct() {
        parent::__construct('div', [
            //'class' => 'text-center'
        ]);
        
    }
    /**
     * Adds action button with an eye as its icon with blue color.
     * 
     * @param string $jsMeth The name of JavaScript method that represents
     * view action.
     * 
     * @param string|HTMLNode|null $tooltip An optional tooltip to show when the user
     * hover over the button. This can also be an object of type HTMLNode.
     * Default is 'View'.
     * 
     * @return VBtn The method will return a VBtn instance that represents the
     * added button. Developer can use the instance to further customize the
     * button.
     */
    public function addViewAction(string $jsMeth, $tooltip = 'View') : VBtn {
        $vbtn = $this->addAction($jsMeth, 'mdi-eye', $tooltip);
        $vbtn->getVIcon()->setAttribute('color', 'blue lighten-1');
        return $vbtn;
    }
    /**
     * Adds action button with cloud as its icon with gray color.
     * 
     * @param string $jsMeth The name of JavaScript method that represents
     * upload action.
     * 
     * @param string|HTMLNode|null $tooltip An optional tooltip to show when the user
     * hover over the button. This can also be an object of type HTMLNode.
     * Default is 'Upload'.
     * 
     * @return VBtn The method will return a VBtn instance that represents the
     * added button. Developer can use the instance to further customize the
     * button.
     */
    public function addUploadAction(string $jsMeth, $tooltip = 'Upload') : VBtn {
        $vbtn = $this->addAction($jsMeth, 'mdi-cloud-upload', $tooltip);
        $vbtn->getVIcon()->setAttribute('color', 'blue-grey');
        return $vbtn;
    }
    /**
     * Adds action button with plus as its icon with green color.
     * 
     * @param string $jsMeth The name of JavaScript method that represents
     * add action.
     * 
     * @param string|HTMLNode|null $tooltip An optional tooltip to show when the user
     * hover over the button. This can also be an object of type HTMLNode.
     * Default is 'Add'.
     * 
     * @return VBtn The method will return a VBtn instance that represents the
     * added button. Developer can use the instance to further customize the
     * button.
     */
    public function addAddAction(string $jsMeth, $tooltip = 'Add') : VBtn {
        $vbtn = $this->addAction($jsMeth, 'mdi-plus-circle', $tooltip);
        $vbtn->getVIcon()->setAttribute('color', 'green lighten-1');
        return $vbtn;
    }
    /**
     * Adds action button with trashcan as its icon with red color.
     * 
     * @param string $jsMeth The name of JavaScript method that represents
     * delete action.
     * 
     * @param string|HTMLNode|null $tooltip An optional tooltip to show when the user
     * hover over the button. This can also be an object of type HTMLNode.
     * Default is 'Delete'.
     * 
     * @return VBtn The method will return a VBtn instance that represents the
     * added button. Developer can use the instance to further customize the
     * button.
     */
    public function addDeleteAction(string $jsMeth, $tooltip = 'Delete') : VBtn {
        $vbtn = $this->addAction($jsMeth, 'mdi-trash-can', $tooltip);
        $vbtn->getVIcon()->setAttribute('color', 'red lighten-1');
        return $vbtn;
    }
    /**
     * Adds action button with pencil as its icon with blue color.
     * 
     * @param string $jsMeth The name of JavaScript method that represents
     * edit action.
     * 
     * @param string|HTMLNode|null $tooltip An optional tooltip to show when the user
     * hover over the button. This can also be an object of type HTMLNode.
     * Default is 'Edit'.
     * 
     * @return VBtn The method will return a VBtn instance that represents the
     * added button. Developer can use the instance to further customize the
     * button.
     */
    public function addEditAction(string $jsMeth, $tooltip = 'Edit') : VBtn {
        $vbtn = $this->addAction($jsMeth, 'mdi-pencil', $tooltip);
        $vbtn->getVIcon('color', 'blue lighten-1');
        return $vbtn;
    }
    /**
     * Adds action button to the body of the component.
     * 
     * @param string $jsMeth The name of JavaScript method that will be called
     * when the action button is clicked. 
     * 
     * @param string $mdiIcon The material design icon that will be shown
     * on the button.
     * 
     * @param string|HTMLNode|null $tooltip An optional tooltip to show when the user
     * hover over the button. This can also be an object of type HTMLNode.
     * Default is null.
     * 
     * @return VBtn The method will return a VBtn instance that represents the
     * added button. Developer can use the instance to further customize the
     * button.
     */
    public function addAction(string $jsMeth, string $mdiIcon = 'mdi-pencil', $tooltip = null) : VBtn {
        $actionBtn = new VBtn([
            '@click' => $jsMeth,
            'icon' => $mdiIcon,
            'icon',
            'icon-props' => [
                'small'
            ]
        ]);
        if ($tooltip !== null) {
            $approveFile = $this->addChild(new VTooltip($actionBtn));
            if ($tooltip instanceof HTMLNode) {
                $approveFile->setTooltip($tooltip);
            } else {
                $approveFile->setTooltip('span')->text($tooltip);
            }
        } else {
            $this->addChild($actionBtn);
        }
        return $actionBtn;
    }
}
