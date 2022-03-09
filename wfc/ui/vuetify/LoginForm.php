<?php
namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;
use wfc\ui\vuetify\VBtn;
/**
 * A class which builds a basic login form in top of 'v-card' element.
 *
 * @author Ibrahim
 */
class LoginForm extends HTMLNode {
    /**
     * Sets the value of the attribute 'v-model' of username input 
     * field.
     * 
     * @param string $model The name of the model as it appears in
     * javaScript.
     */
    public function setUsernameModel($model) {
        $this->getUsernameInput()->setAttribute('v-model', $model);
    }
    /**
     * Sets the label of the username input field.
     * 
     * @param string $lbl A string that represents the label.
     */
    public function setUsernameLabel($lbl) {
        $this->getUsernameInput()->setAttribute('label', $lbl);
    }
    /**
     * Returns the Vuetify component that represents usersname input field.
     * 
     * This method is useful if the developer would like to set extra attributes
     * for the input field.
     * 
     * @return HTMLNode
     */
    public function getUsernameInput() {
        return $this->getChildByID('username-input');
    }
    /**
     * Sets the value of the attribute 'v-model' of password input 
     * field.
     * 
     * @param string $model The name of the model as it appears in
     * javaScript.
     */
    public function setPasswordModel($model) {
        $this->getPasswordInput()->setAttribute('v-model', $model);
    }
    /**
     * Sets the label of the password input field.
     * 
     * @param string $lbl A string that represents the label.
     */
    public function setPasswordLabel($lbl) {
        $this->getPasswordInput()->setAttribute('label', $lbl);
    }
    /**
     * Returns the Vuetify component that represents password input field.
     * 
     * This method is useful if the developer would like to set extra attributes
     * for the input field.
     * 
     * @return HTMLNode
     */
    public function getPasswordInput() {
        return $this->getChildByID('password-input');
    }
    /**
     * Creates new instance of the class.
     * 
     * @param array $options An array that contains options which can be used
     * to initialize component settings. Supported options are:
     * <ul>
     * <li>username-label</li>
     * <li>password-label</li>
     * <li>login-label</li>
     * <li>username-model</li>
     * <li>password-model</li>
     * </ul>
     */
    public function __construct(array $options) {
        parent::__construct('v-card');
        $this->addChild('v-card-text')->addChild('v-text-field', [
            'id' => 'username-input',
            'outlined',
            'label' => 'Username'
        ]);
        $this->addChild('v-card-text')->addChild('v-text-field', [
            'label' => 'Password',
            'id' => 'password-input',
            'type' => 'password',
            'outlined'
        ]);
        $this->addChild('v-divider');

        $this->addChild('v-card-actions')->addChild(new VBtn([
            'id' => 'login-button',
            'color' => 'primary',
            'label' => 'Login'
        ]));
        
        if (isset($options['username-label'])) {
            $this->setUsernameLabel($options['username-label']);
        }
        if (isset($options['password-label'])) {
            $this->setPasswordLabel($options['password-label']);
        }
        if (isset($options['username-model'])) {
            $this->setUsernameModel($options['username-model']);
        }
        if (isset($options['password-model'])) {
            $this->setPasswordModel($options['password-model']);
        }
        if (isset($options['login-event'])) {
            $this->setLoginEvent($options['login-event']);
        }
        if (isset($options['login-label'])) {
            $this->setLoginBtnLabel($options['login-label']);
        }
    }
    /**
     * Returns the Vuetify component that represents login button
     * 
     * @return VBtn
     */
    public function getLoginButton() {
        return $this->getChildByID('login-button');
    }
    /**
     * Sets the function that will be get called when the login button is clicked.
     * 
     * @param string $jsFunc The name of JavaScript function on Vue instance.
     */
    public function setLoginEvent($jsFunc) {
        $this->getLoginButton()->setAttribute('@click', $jsFunc);
    }
    /**
     * Sets the text that will appear in the login button.
     * 
     * @param string $label
     */
    public function setLoginBtnLabel($label) {
        $this->getLoginButton()->removeAllChildNodes();
        $this->getLoginButton()->text($label);
    }
}
